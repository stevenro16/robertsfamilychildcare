<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\Inquiry;
use App\Models\Message;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'inquiries'      => Inquiry::where('status', '!=', 'COMPLETE')->count(),
            'children'       => Child::where('status', 'ACTIVE')->count(),
            'unreadMessages' => Message::whereNull('readAt')->where('senderRole', 'PARENT')->count(),
        ];

        return view('portal.dashboard', compact('stats'));
    }
}
