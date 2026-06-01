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
            'name'      => 'sometimes|string|max:100',
            'title'     => 'sometimes|string|max:100',
            'bio'       => 'sometimes|nullable|string',
            'sortOrder' => 'sometimes|integer',
        ]);
        $member->update($data);
        return back()->with('success', 'Staff member updated.');
    }

    public function uploadPhoto(Request $request, string $id)
    {
        $member = StaffMember::findOrFail($id);
        $request->validate(['photo' => 'required|image|max:5120']);

        $file = $request->file('photo');
        $filename = $member->id . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('uploads/staff', $filename, 'public');

        $member->update(['photoUrl' => '/storage/' . $path]);
        return back()->with('success', 'Photo updated.');
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