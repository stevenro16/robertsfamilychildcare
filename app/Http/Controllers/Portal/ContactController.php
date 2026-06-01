<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactNote;
use App\Models\ParentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::orderBy('name')->get();
        return view('portal.contacts.index', compact('contacts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:200',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
        ]);

        $contact = Contact::create($data);
        return redirect()->route('portal.contacts.show', $contact->id);
    }

    public function show(string $id)
    {
        $contact = Contact::with(['notes.employee', 'childContacts.child', 'parentUser'])->findOrFail($id);
        return view('portal.contacts.show', compact('contact'));
    }

    public function update(Request $request, string $id)
    {
        $contact = Contact::findOrFail($id);
        $data = $request->validate([
            'name'  => 'sometimes|string|max:200',
            'email' => 'sometimes|nullable|email|max:255',
            'phone' => 'sometimes|nullable|string|max:50',
        ]);
        $contact->update($data);
        return back()->with('success', 'Contact updated.');
    }

    public function notes(string $id)
    {
        $contact = Contact::with('notes.employee')->findOrFail($id);
        return response()->json($contact->notes);
    }

    public function addNote(Request $request, string $id)
    {
        $contact = Contact::findOrFail($id);
        $data = $request->validate(['content' => 'required|string']);
        ContactNote::create([
            'contactId'  => $contact->id,
            'content'    => $data['content'],
            'employeeId' => Auth::id(),
        ]);
        return back()->with('success', 'Note added.');
    }

    public function updateNote(Request $request, string $id, string $noteId)
    {
        $note = ContactNote::where('contactId', $id)->findOrFail($noteId);
        $data = $request->validate(['content' => 'required|string']);
        $note->update($data);
        return back()->with('success', 'Note updated.');
    }

    public function createParentUser(Request $request, string $id)
    {
        $contact = Contact::findOrFail($id);

        if ($contact->parentUser) {
            return back()->with('error', 'Parent account already exists.');
        }

        $data = $request->validate([
            'username' => 'required|string|max:100|unique:ParentUser,username',
            'password' => 'required|string|min:8',
        ]);

        ParentUser::create([
            'contactId'          => $contact->id,
            'username'           => $data['username'],
            'password'           => Hash::make($data['password']),
            'mustChangePassword' => true,
        ]);

        return back()->with('success', 'Parent account created.');
    }
}