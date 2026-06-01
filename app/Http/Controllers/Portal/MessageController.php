<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\ParentUser;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $parentIds = Message::select('parentUserId')->distinct()->pluck('parentUserId');

        $conversations = ParentUser::whereIn('id', $parentIds)->get()->map(function ($parent) {
            $latest = Message::where('parentUserId', $parent->id)
                ->orderBy('createdAt', 'desc')->first();
            $unread = Message::where('parentUserId', $parent->id)
                ->where('senderRole', 'PARENT')->whereNull('readAt')->count();
            return (object) ['parent' => $parent, 'latest' => $latest, 'unread' => $unread];
        })->sortByDesc(fn ($c) => $c->latest?->createdAt)->values();

        return view('portal.messages.index', compact('conversations'));
    }

    public function unreadCount()
    {
        $count = Message::where('senderRole', 'PARENT')->whereNull('readAt')->count();
        return response()->json(['unread' => $count]);
    }

    public function conversation(string $parentId)
    {
        $parent = ParentUser::findOrFail($parentId);
        $messages = Message::where('parentUserId', $parentId)->orderBy('createdAt')->get();

        Message::where('parentUserId', $parentId)
            ->where('senderRole', 'PARENT')->whereNull('readAt')
            ->update(['readAt' => now()]);

        return view('portal.messages.conversation', compact('parent', 'messages'));
    }

    public function send(Request $request, string $parentId)
    {
        $parent = ParentUser::findOrFail($parentId);
        $data = $request->validate(['content' => 'required|string|max:5000']);

        Message::create([
            'parentUserId' => $parent->id,
            'senderRole'   => 'STAFF',
            'content'      => $data['content'],
        ]);

        return redirect()->route('portal.messages.conversation', $parentId);
    }
}