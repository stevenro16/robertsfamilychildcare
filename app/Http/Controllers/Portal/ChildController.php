<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\ChildNote;
use App\Models\ChildDocument;
use App\Models\ChildContact;
use App\Models\Contact;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChildController extends Controller
{
    public function index()
    {
        $children = Child::with(['contacts', 'documents'])->orderBy('lastName')->orderBy('firstName')->get();
        return view('portal.children.index', compact('children'));
    }

    public function create()
    {
        $inquiries = Inquiry::whereNull('id')
            ->orWhereDoesntHave('child')
            ->whereNotIn('status', ['COMPLETE'])
            ->orderBy('createdAt', 'desc')
            ->get();
        return view('portal.children.create', compact('inquiries'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'firstName'  => 'required|string|max:100',
            'lastName'   => 'required|string|max:100',
            'dob'        => 'required|date',
            'inquiryId'  => 'nullable|string',
        ]);

        $child = Child::create([
            'firstName'   => $data['firstName'],
            'lastName'    => $data['lastName'],
            'dateOfBirth' => $data['dob'],
            'inquiryId'   => $data['inquiryId'] ?? null,
        ]);
        return redirect()->route('portal.children.show', $child->id);
    }

    public function show(string $id)
    {
        $child = Child::with(['contacts', 'documents'])->findOrFail($id);
        $notes = ChildNote::with('employee')->where('childId', $id)->orderByDesc('createdAt')->get();
        $tab = request('tab', 'stats');
        return view('portal.children.show', compact('child', 'tab', 'notes'));
    }

    public function update(Request $request, string $id)
    {
        $child = Child::findOrFail($id);

        $data = $request->validate([
            'firstName'     => 'sometimes|string|max:100',
            'lastName'      => 'sometimes|string|max:100',
            'dob'           => 'sometimes|nullable|date',
            'expectedStart' => 'sometimes|nullable|date',
            'status'        => 'sometimes|string|max:50',
            'notes'         => 'sometimes|nullable|string',
        ]);

        if (array_key_exists('dob', $data)) {
            $data['dateOfBirth'] = $data['dob'];
            unset($data['dob']);
        }

        // Build schedule from per-day active flags + time inputs
        if ($request->has('schedule_active') || $request->has('schedule')) {
            $activeFlags  = $request->input('schedule_active', []);
            $timeInputs   = $request->input('schedule', []);
            $schedule     = [];

            foreach (['monday','tuesday','wednesday','thursday','friday'] as $day) {
                $isActive = !empty($activeFlags[$day]);
                $dropoff  = trim($timeInputs[$day]['dropoff'] ?? '');
                $pickup   = trim($timeInputs[$day]['pickup']  ?? '');

                if ($isActive || $dropoff || $pickup) {
                    $entry = array_filter(['dropoff' => $dropoff ?: null, 'pickup' => $pickup ?: null]);
                    $schedule[$day] = empty($entry) ? 'scheduled' : $entry;
                } else {
                    $schedule[$day] = null;
                }
            }

            $data['schedule'] = $schedule;
        }

        $child->update($data);
        return redirect()->route('portal.children.show', $id)->with('success', 'Child updated.');
    }

    public function notes(string $id)
    {
        Child::findOrFail($id);
        return response()->json(ChildNote::with('employee')->where('childId', $id)->get());
    }

    public function addNote(Request $request, string $id)
    {
        $child = Child::findOrFail($id);
        $data = $request->validate(['content' => 'required|string']);

        ChildNote::create([
            'childId'    => $child->id,
            'content'    => $data['content'],
            'employeeId' => Auth::id(),
        ]);

        return redirect(route('portal.children.show', $id) . '?tab=notes')->with('success', 'Note added.');
    }

    public function updateNote(Request $request, string $id, string $noteId)
    {
        $note = ChildNote::where('childId', $id)->findOrFail($noteId);
        $data = $request->validate(['content' => 'required|string']);
        $note->update($data);
        return back()->with('success', 'Note updated.');
    }

    public function contacts(string $id)
    {
        $child = Child::with('contacts')->findOrFail($id);
        return response()->json($child->contacts);
    }

    public function addContact(Request $request, string $id)
    {
        $child = Child::findOrFail($id);
        $data = $request->validate([
            'contactId'    => 'required|string',
            'relationship' => 'required|string|max:100',
            'isPrimary'    => 'boolean',
        ]);

        ChildContact::create([
            'childId'      => $child->id,
            'contactId'    => $data['contactId'],
            'relationship' => $data['relationship'],
            'isPrimary'    => $data['isPrimary'] ?? false,
            'addedByParent'=> false,
        ]);

        return redirect(route('portal.children.show', $id) . '?tab=contacts')->with('success', 'Contact added.');
    }

    public function updateContact(Request $request, string $id, string $ccId)
    {
        $cc = ChildContact::where('childId', $id)->findOrFail($ccId);
        $data = $request->validate([
            'relationship' => 'sometimes|string|max:100',
            'isPrimary'    => 'sometimes|boolean',
            'name'         => 'sometimes|string|max:200',
            'phone'        => 'sometimes|nullable|string|max:30',
            'email'        => 'sometimes|nullable|email|max:200',
        ]);

        $pivotData   = array_intersect_key($data, array_flip(['relationship', 'isPrimary']));
        $contactData = array_intersect_key($data, array_flip(['name', 'phone', 'email']));

        if (!empty($pivotData))   $cc->update($pivotData);
        if (!empty($contactData)) $cc->contact->update($contactData);

        return redirect()->route('portal.children.show', $id)->with('success', 'Contact updated.');
    }

    public function documents(string $id)
    {
        $child = Child::with('documents')->findOrFail($id);
        return response()->json($child->documents);
    }

    public function uploadDocument(Request $request, string $id)
    {
        $child = Child::findOrFail($id);
        $request->validate([
            'file' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs("uploads/children/docs/{$child->id}", $filename, 'public');

        ChildDocument::create([
            'childId'        => $child->id,
            'name'           => $file->getClientOriginalName(),
            'fileUrl'        => parse_url(Storage::disk('public')->url($path), PHP_URL_PATH),
            'uploadedByParent' => false,
        ]);

        return redirect(route('portal.children.show', $id) . '?tab=documents')->with('success', 'Document uploaded.');
    }

    public function deleteDocument(string $id, string $docId)
    {
        $doc = ChildDocument::where('childId', $id)->findOrFail($docId);
        $relativePath = ltrim(preg_replace('#^/[^/]+/#', '', $doc->fileUrl), '/');
        Storage::disk('public')->delete($relativePath);
        $doc->delete();

        return back()->with('success', 'Document deleted.');
    }

    public function uploadPhoto(Request $request, string $id)
    {
        $child = Child::findOrFail($id);
        $request->validate(['photo' => 'required|image|max:5120']);

        $file = $request->file('photo');
        $path = $file->storeAs('uploads/children', $child->id . '.jpg', 'public');
        $url  = parse_url(Storage::disk('public')->url($path), PHP_URL_PATH);

        $child->update(['photoUrl' => $url]);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'photoUrl' => $url]);
        }
        return back()->with('success', 'Photo updated.');
    }

    public function clearPhoto(string $id)
    {
        $child = Child::findOrFail($id);

        if ($child->photoUrl) {
            $relativePath = ltrim(preg_replace('#^/[^/]+/#', '', $child->photoUrl), '/');
            Storage::disk('public')->delete($relativePath);
            $child->update(['photoUrl' => null]);
        }

        return back()->with('success', 'Photo removed.');
    }
}