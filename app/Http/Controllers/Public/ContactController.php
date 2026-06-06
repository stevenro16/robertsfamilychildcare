<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\NewInquiryMail;
use App\Models\Inquiry;
use App\Models\InquiryStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('public.contact');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'parentName'      => 'required|string|max:255',
            'parentPhone'     => 'required|string|max:50',
            'parentEmail'     => 'required|email|max:255',
            'childName'       => 'required|string|max:255',
            'childDob'        => 'nullable|date',
            'desiredStart'    => 'nullable|date',
            'hearAbout'       => 'nullable|string|max:255',
            'programInterest' => 'nullable|string|max:255',
            'message'         => 'nullable|string|max:2000',
        ]);

        $inquiry = Inquiry::create([
            'parentName'      => $data['parentName'],
            'parentEmail'     => $data['parentEmail'],
            'parentPhone'     => $data['parentPhone'],
            'childName'       => $data['childName'],
            'childDob'        => $data['childDob'] ?? null,
            'desiredStart'    => $data['desiredStart'] ?? null,
            'hearAbout'       => $data['hearAbout'] ?? null,
            'programInterest' => $data['programInterest'] ?? null,
            'message'         => $data['message'] ?? null,
            'status'          => 'NEW',
            'isSnoozed'       => false,
            'createdAt'       => now(),
            'updatedAt'       => now(),
        ]);

        $notificationEmail = config('mail.notification_email');
        if ($notificationEmail) {
            try {
                Mail::to($notificationEmail)->send(new NewInquiryMail($inquiry));
                InquiryStatusHistory::create([
                    'inquiryId'  => $inquiry->id,
                    'oldStatus'  => '',
                    'newStatus'  => 'NOTIFICATION_SENT',
                    'employeeId' => '',
                ]);
            } catch (\Exception) {
                // silently skip if mail not configured
            }
        }

        return redirect()->route('contact')->with('inquiry_success', true);
    }
}