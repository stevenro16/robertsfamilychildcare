<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\ChildContact;
use App\Models\ChildDocument;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentChildController extends Controller
{
    private function assertAccess(string $childId): Child
    {
        $parent = Auth::guard('parent')->user();
        $cc = ChildContact::where('childId', $childId)
            ->where('contactId', $parent->contactId)
            ->firstOrFail();

        return Child::findOrFail($childId);
    }

    public function show(string $id)
    {
        $child = $this->assertAccess($id);
        $child->load('contacts', 'documents', 'notes');
        return view('parent.children.show', compact('child'));
    }

    public function contacts(string $id)
    {
        $child = $this->assertAccess($id);
        $child->load('contacts');
        return view('parent.children.contacts', compact('child'));
    }

    public function addContact(Request $request, string $id)
    {
        $child = $this->assertAccess($id);

        $data = $request->validate([
            'firstName'    => 'required|string|max:100',
            'lastName'     => 'required|string|max:100',
            'phone'        => 'nullable|string|max:50',
            'email'        => 'nullable|email|max:255',
            'relationship' => 'required|string|max:100',
        ]);

        $contact = Contact::create([
            'firstName' => $data['firstName'],
            'lastName'  => $data['lastName'],
            'phone'     => $data['phone'] ?? null,
            'email'     => $data['email'] ?? null,
        ]);

        ChildContact::create([
            'childId'       => $child->id,
            'contactId'     => $contact->id,
            'relationship'  => $data['relationship'],
            'isPrimary'     => false,
            'addedByParent' => true,
        ]);

        return back()->with('success', 'Contact added.');
    }

    public function documents(string $id)
    {
        $child = $this->assertAccess($id);
        $child->load('documents');
        return view('parent.children.documents', compact('child'));
    }

    public function uploadDocument(Request $request, string $id)
    {
        $child = $this->assertAccess($id);

        $request->validate([
            'file' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs("uploads/children/docs/{$child->id}", $filename, 'public');

        ChildDocument::create([
            'childId'         => $child->id,
            'name'            => $file->getClientOriginalName(),
            'fileUrl'         => '/storage/' . $path,
            'uploadedByParent'=> true,
        ]);

        return back()->with('success', 'Document uploaded.');
    }
}