<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\Contact;
use App\Models\ParentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ParentPortalController extends Controller
{
    public function index()
    {
        $parents = ParentUser::with('contact')->orderBy('createdAt', 'desc')->get();
        return view('portal.parent-portals.index', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'contactId' => 'required|string|exists:Contact,id',
            'username'  => 'required|string|max:100|unique:ParentUser,username',
            'password'  => 'required|string|min:8',
        ]);

        $contact = Contact::findOrFail($data['contactId']);

        if ($contact->parentUser) {
            return back()->with('error', 'This contact already has a parent account.');
        }

        ParentUser::create([
            'contactId'          => $data['contactId'],
            'username'           => $data['username'],
            'password'           => Hash::make($data['password']),
            'mustChangePassword' => true,
        ]);

        return back()->with('success', 'Parent portal account created.');
    }

    public function update(Request $request, string $id)
    {
        $parent = ParentUser::findOrFail($id);
        $data = $request->validate([
            'password'           => 'sometimes|string|min:8',
            'mustChangePassword' => 'sometimes|boolean',
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
            $data['mustChangePassword'] = true;
        }

        $parent->update($data);
        return back()->with('success', 'Account updated.');
    }
}