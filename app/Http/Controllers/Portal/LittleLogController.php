<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\ChildCheckinLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LittleLogController extends Controller
{
    public function index()
    {
        $today = strtolower(now()->format('l'));

        $all = Child::where('status', 'ACTIVE')
            ->orderBy('lastName')
            ->orderBy('firstName')
            ->get();

        $checkedIn    = $all->filter(fn($c) => $c->checkedInAt !== null)->values();
        $checkedInIds = $checkedIn->pluck('id')->all();

        $expected = $all->filter(function ($c) use ($today, $checkedInIds) {
            if (in_array($c->id, $checkedInIds)) return false;
            $schedule = is_array($c->schedule) ? $c->schedule : [];
            $entry = $schedule[$today] ?? null;
            if (!$entry) return false;
            if (is_array($entry)) return !empty($entry['dropoff']) || !empty($entry['pickup']);
            return trim((string) $entry) !== '';
        })->values();

        $unscheduled = $all->filter(function ($c) use ($today, $checkedInIds) {
            if (in_array($c->id, $checkedInIds)) return false;
            $schedule = is_array($c->schedule) ? $c->schedule : [];
            $entry = $schedule[$today] ?? null;
            if (!$entry) return true;
            if (is_array($entry)) return empty($entry['dropoff']) && empty($entry['pickup']);
            return trim((string) $entry) === '';
        })->values();

        return view('portal.littlelog.index', compact('expected', 'checkedIn', 'unscheduled', 'today'));
    }

    public function checkin(Request $request, string $id)
    {
        $child = Child::where('status', 'ACTIVE')->findOrFail($id);
        $now   = now();

        $child->update(['checkedInAt' => $now]);

        ChildCheckinLog::create([
            'childId'    => $child->id,
            'action'     => 'CHECKIN',
            'employeeId' => Auth::id(),
            'occurredAt' => $now,
        ]);

        return response()->json([
            'ok'          => true,
            'checkedInAt' => $now->format('g:i A'),
        ]);
    }

    public function checkout(Request $request, string $id)
    {
        $child = Child::where('status', 'ACTIVE')->findOrFail($id);

        $customTime = $request->input('customTime'); // "HH:MM" from the browser time input
        $occurredAt = $customTime
            ? Carbon::today()->setTimeFromTimeString($customTime)
            : now();

        ChildCheckinLog::create([
            'childId'    => $child->id,
            'action'     => 'CHECKOUT',
            'employeeId' => Auth::id(),
            'occurredAt' => $occurredAt,
            'note'       => $customTime ? "Time manually set to {$occurredAt->format('g:i A')}" : null,
        ]);

        $child->update(['checkedInAt' => null]);

        return response()->json(['ok' => true]);
    }
}
