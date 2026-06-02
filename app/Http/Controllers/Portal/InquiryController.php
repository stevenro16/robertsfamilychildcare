<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Child;
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
        $inquiries = Inquiry::where('status', 'NEW')
            ->where(function ($q) {
                $q->where('isSnoozed', false)
                  ->orWhereNull('snoozeUntil')
                  ->orWhere('snoozeUntil', '<=', now());
            })
            ->orderBy('createdAt', 'desc')
            ->get();

        return view('portal.inquiries.index', [
            'inquiries' => $inquiries,
            'queue'     => 'needs_attention',
            'counts'    => $this->bucketCounts(),
        ]);
    }

    private const AWAITING_STATUSES = [
        'LEFT_VOICEMAIL', 'LEFT_VOICEMAIL_2', 'LEFT_VOICEMAIL_FINAL', 'PROVIDED_PRICING_WAITING',
    ];

    public function awaiting()
    {
        $inquiries = Inquiry::whereIn('status', self::AWAITING_STATUSES)
            ->where(function ($q) {
                $q->where('isSnoozed', false)
                  ->orWhereNull('snoozeUntil')
                  ->orWhere('snoozeUntil', '<=', now());
            })
            ->orderBy('updatedAt', 'asc')
            ->get();

        return view('portal.inquiries.index', [
            'inquiries' => $inquiries,
            'queue'     => 'awaiting',
            'counts'    => $this->bucketCounts(),
        ]);
    }

    public function all()
    {
        $inquiries = Inquiry::orderBy('createdAt', 'desc')->get();

        return view('portal.inquiries.index', [
            'inquiries' => $inquiries,
            'queue'     => 'all',
            'counts'    => $this->bucketCounts(),
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
            'counts'    => $this->bucketCounts(),
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
            'counts'    => $this->bucketCounts(),
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
            'counts'    => $this->bucketCounts(),
        ]);
    }

    private function bucketCounts(): array
    {
        $notSnoozed = function ($q) {
            $q->where('isSnoozed', false)
              ->orWhereNull('snoozeUntil')
              ->orWhere('snoozeUntil', '<=', now());
        };

        return [
            'needs_attention' => Inquiry::where('status', 'NEW')->where($notSnoozed)->count(),
            'awaiting'        => Inquiry::whereIn('status', self::AWAITING_STATUSES)->where($notSnoozed)->count(),
            'room'            => Inquiry::where('status', 'FOLLOW_UP_WHEN_ROOM')->count(),
            'snoozed'         => Inquiry::where('isSnoozed', true)->where('snoozeUntil', '>', now())->count(),
            'all'             => Inquiry::count(),
        ];
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
            'status'          => 'sometimes|string',
            'parentName'      => 'sometimes|string|max:255',
            'parentEmail'     => 'sometimes|nullable|email|max:255',
            'parentPhone'     => 'sometimes|nullable|string|max:50',
            'childName'       => 'sometimes|nullable|string|max:255',
            'childDob'        => 'sometimes|nullable|date',
            'desiredStart'    => 'sometimes|nullable|date',
            'hearAbout'       => 'sometimes|nullable|string|max:255',
            'programInterest' => 'sometimes|nullable|string|max:255',
            'message'         => 'sometimes|nullable|string',
        ]);

        $oldStatus = $inquiry->status;

        $inquiry->update($data);

        if (isset($data['status']) && $data['status'] !== $oldStatus) {
            InquiryStatusHistory::create([
                'inquiryId'  => $inquiry->id,
                'oldStatus'  => $oldStatus,
                'newStatus'  => $data['status'],
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

    public function convert(Request $request, string $id)
    {
        $inquiry = Inquiry::findOrFail($id);

        $autoComplete = $request->boolean('autoComplete', true);

        $nameParts = explode(' ', trim($inquiry->childName ?? ''), 2);
        $firstName = $nameParts[0] ?: 'Unknown';
        $lastName  = $nameParts[1] ?? '';

        $child = Child::create([
            'firstName'   => $firstName,
            'lastName'    => $lastName,
            'dateOfBirth' => $inquiry->childDob,
            'inquiryId'   => $inquiry->id,
            'status'      => 'ACTIVE',
        ]);

        $noteLines = ["Converted from inquiry — child record created (#{$child->id})."];

        if ($autoComplete) {
            $oldStatus = $inquiry->status;
            $inquiry->update(['status' => 'COMPLETE']);
            InquiryStatusHistory::create([
                'inquiryId'  => $inquiry->id,
                'oldStatus'  => $oldStatus,
                'newStatus'  => 'COMPLETE',
                'employeeId' => Auth::id(),
            ]);
            $noteLines[] = 'Inquiry automatically marked Complete upon conversion.';
        }

        InquiryNote::create([
            'inquiryId'  => $inquiry->id,
            'content'    => implode(' ', $noteLines),
            'employeeId' => Auth::id(),
        ]);

        return redirect()->route('portal.children.show', $child->id)
            ->with('success', 'Child record created from inquiry.');
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
