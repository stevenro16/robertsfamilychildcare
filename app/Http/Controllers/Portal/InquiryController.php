<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\InquiryNote;
use App\Models\InquiryStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    private const STATUS_ORDER = [
        'NEW', 'LEFT_VOICEMAIL', 'LEFT_VOICEMAIL_2', 'LEFT_VOICEMAIL_FINAL',
        'PROVIDED_PRICING_WAITING', 'FOLLOW_UP_WHEN_ROOM',
    ];

    public function index()
    {
        $inquiries = Inquiry::whereNotIn('status', ['COMPLETE', 'FOLLOW_UP_WHEN_ROOM'])
            ->where(function ($q) {
                $q->where('isSnoozed', false)
                  ->orWhereNull('snoozeUntil')
                  ->orWhere('snoozeUntil', '<=', now());
            })
            ->orderBy('createdAt', 'desc')
            ->get();

        return view('portal.inquiries.index', [
            'inquiries' => $inquiries,
            'queue'     => 'active',
        ]);
    }

    public function snoozed()
    {
        $inquiries = Inquiry::where('isSnoozed', true)
            ->where('snoozeUntil', '>', now())
            ->orderBy('snoozeUntil')
            ->get();

        return view('portal.inquiries.index', [
            'inquiries' => $inquiries,
            'queue'     => 'snoozed',
        ]);
    }

    public function room()
    {
        $inquiries = Inquiry::where('status', 'FOLLOW_UP_WHEN_ROOM')
            ->orderBy('createdAt', 'desc')
            ->get();

        return view('portal.inquiries.index', [
            'inquiries' => $inquiries,
            'queue'     => 'room',
        ]);
    }

    public function completed()
    {
        $inquiries = Inquiry::where('status', 'COMPLETE')
            ->orderBy('updatedAt', 'desc')
            ->limit(100)
            ->get();

        return view('portal.inquiries.index', [
            'inquiries' => $inquiries,
            'queue'     => 'completed',
        ]);
    }

    public function show(string $id)
    {
        $inquiry = Inquiry::with(['notes.employee', 'statusHistory.employee', 'child'])->findOrFail($id);

        return view('portal.inquiries.show', [
            'inquiry'     => $inquiry,
            'statuses'    => self::STATUS_ORDER,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $inquiry = Inquiry::findOrFail($id);

        $data = $request->validate([
            'status'   => 'sometimes|string',
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|max:255',
            'phone'    => 'sometimes|string|max:50',
            'message'  => 'sometimes|string',
            'childDob' => 'sometimes|nullable|date',
        ]);

        $oldStatus = $inquiry->status;

        $inquiry->update($data);

        if (isset($data['status']) && $data['status'] !== $oldStatus) {
            InquiryStatusHistory::create([
                'inquiryId'  => $inquiry->id,
                'status'     => $data['status'],
                'employeeId' => Auth::id(),
            ]);
        }

        return redirect()->route('portal.inquiries.show', $id)
            ->with('success', 'Inquiry updated.');
    }

    public function snooze(Request $request, string $id)
    {
        $inquiry = Inquiry::findOrFail($id);

        $data = $request->validate([
            'snoozeUntil' => 'required|date|after:now',
        ]);

        $inquiry->update([
            'isSnoozed'   => true,
            'snoozeUntil' => $data['snoozeUntil'],
        ]);

        return redirect()->route('portal.inquiries.show', $id)
            ->with('success', 'Inquiry snoozed until ' . $data['snoozeUntil'] . '.');
    }

    public function notes(string $id)
    {
        $inquiry = Inquiry::with('notes.employee')->findOrFail($id);
        return response()->json($inquiry->notes);
    }

    public function addNote(Request $request, string $id)
    {
        $inquiry = Inquiry::findOrFail($id);

        $data = $request->validate(['content' => 'required|string']);

        InquiryNote::create([
            'inquiryId'  => $inquiry->id,
            'content'    => $data['content'],
            'employeeId' => Auth::id(),
        ]);

        return redirect()->route('portal.inquiries.show', $id)
            ->with('success', 'Note added.');
    }
}
