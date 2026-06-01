<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentMessageController extends Controller
{
    public function index()
    {
        $parent = Auth::guard('parent')->user();

        $messages = Message::where('parentUserId', $parent->id)
            ->orderBy('createdAt')
            ->get();

        // Mark staff messages as read
        Message::where('parentUserId', $parent->id)
            ->where('senderRole', 'STAFF')
            ->whereNull('readAt')
            ->update(['readAt' => now()]);

        return view('parent.messages', compact('messages', 'parent'));
    }

    public function send(Request $request)
    {
        $parent = Auth::guard('parent')->user();
        $data = $request->validate(['content' => 'required|string|max:5000']);

        Message::create([
            'parentUserId' => $parent->id,
            'senderRole'   => 'PARENT',
            'content'      => $data['content'],
        ]);

        return redirect()->route('parent.messages.index');
    }
}