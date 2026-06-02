<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\StaffMember;
use App\Models\StaffNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index()
    {
        $members = StaffMember::orderBy('sortOrder')->get();
        return view('portal.staff.index', compact('members'));
    }

    public function reorder(Request $request)
    {
        $ids = $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'string',
        ])['ids'];

        foreach ($ids as $index => $id) {
            StaffMember::where('id', $id)->update(['sortOrder' => $index + 1]);
        }

        return response()->json(['ok' => true]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:100',
            'title'     => 'required|string|max:100',
            'bio'       => 'nullable|string',
            'sortOrder' => 'nullable|integer',
        ]);

        $maxSort = StaffMember::max('sortOrder') ?? 0;
        $data['sortOrder'] = $data['sortOrder'] ?? $maxSort + 1;

        $member = StaffMember::create($data);
        return redirect()->route('portal.staff.show', $member->id);
    }

    public function show(string $id)
    {
        $member = StaffMember::with('notes.employee')->findOrFail($id);
        return view('portal.staff.show', compact('member'));
    }

    public function update(Request $request, string $id)
    {
        $member = StaffMember::findOrFail($id);
        $data = $request->validate([
            'name'            => 'sometimes|string|max:100',
            'title'           => 'sometimes|string|max:100',
            'bio'             => 'sometimes|nullable|string',
            'sortOrder'       => 'sometimes|integer',
            'startDate'       => 'sometimes|nullable|date',
            'yearsExperience' => 'sometimes|nullable|integer|min:0|max:99',
            'isActive'        => 'sometimes|boolean',
            'inactiveNote'    => 'nullable|string|max:2000',
        ]);

        $inactiveNote = $data['inactiveNote'] ?? null;
        unset($data['inactiveNote']);

        // Checkbox sends nothing when unchecked — treat absence as false
        if ($request->has('isActive') || $request->exists('isActive')) {
            $data['isActive'] = $request->boolean('isActive');
        }

        $wasActive = $member->isActive;
        $member->update($data);

        // If being marked inactive, auto-save a note (with or without a custom reason)
        if ($wasActive && !$member->fresh()->isActive) {
            $content = $inactiveNote
                ? $inactiveNote
                : 'Staff member marked inactive.';

            StaffNote::create([
                'staffMemberId' => $member->id,
                'content'       => $content,
                'sentiment'     => 'NEGATIVE',
                'employeeId'    => Auth::id(),
            ]);
        }

        return back()->with('success', 'Staff member updated.');
    }

    public function uploadPhoto(Request $request, string $id)
    {
        $member = StaffMember::findOrFail($id);
        $request->validate(['photo' => 'required|image|max:5120']);

        $file     = $request->file('photo');
        $filename = $member->id . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs('uploads/staff', $filename, 'public');

        $member->update(['photoUrl' => '/storage/' . $path]);
        return back()->with('success', 'Photo updated.');
    }

    public function clearPhoto(string $id)
    {
        $member = StaffMember::findOrFail($id);

        if ($member->photoUrl && str_starts_with($member->photoUrl, '/storage/')) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete(
                str_replace('/storage/', '', $member->photoUrl)
            );
        }

        $member->update(['photoUrl' => null]);
        return back()->with('success', 'Photo removed.');
    }

    public function notes(string $id)
    {
        $member = StaffMember::with('notes.employee')->findOrFail($id);
        return response()->json($member->notes);
    }

    public function addNote(Request $request, string $id)
    {
        $member = StaffMember::findOrFail($id);
        $data = $request->validate([
            'content'   => 'required|string',
            'sentiment' => 'nullable|in:POSITIVE,NEUTRAL,NEGATIVE',
        ]);

        StaffNote::create([
            'staffMemberId' => $member->id,
            'content'       => $data['content'],
            'sentiment'     => $data['sentiment'] ?? 'NEUTRAL',
            'employeeId'    => Auth::id(),
        ]);

        return back()->with('success', 'Note added.');
    }

    public function updateNote(Request $request, string $id, string $noteId)
    {
        $note = StaffNote::where('staffMemberId', $id)->findOrFail($noteId);
        $data = $request->validate([
            'content'   => 'required|string',
            'sentiment' => 'nullable|in:POSITIVE,NEUTRAL,NEGATIVE',
        ]);
        $note->update($data);
        return back()->with('success', 'Note updated.');
    }
}