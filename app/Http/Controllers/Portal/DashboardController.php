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

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $children = Child::where('status', 'ACTIVE')->orderBy('lastName')->orderBy('firstName')->get();

        $totals   = array_fill_keys($days, 0);   // headcount per day
        $dailyMins = array_fill_keys($days, 0);  // total care-minutes per day (all kids)

        $toMins = fn($t) => intval(explode(':', $t)[0]) * 60 + intval(explode(':', $t)[1] ?? 0);

        $fmtTime = function (string $t) use ($toMins): string {
            $total = $toMins($t);
            $h = intdiv($total, 60);
            $m = $total % 60;
            $ampm = $h >= 12 ? 'pm' : 'am';
            $h12  = $h === 0 ? 12 : ($h > 12 ? $h - 12 : $h);
            return $m > 0 ? "{$h12}:{$m}{$ampm}" : "{$h12}{$ampm}";
        };

        $fmtMins = function (int $mins): string {
            $h = intdiv($mins, 60);
            $m = $mins % 60;
            return $m > 0 ? "{$h}h {$m}m" : "{$h}h";
        };

        foreach ($children as $child) {
            $schedule     = is_array($child->schedule) ? $child->schedule : [];
            $scheduleDays = [];
            $weeklyMins   = 0;

            foreach ($days as $day) {
                $entry = $schedule[$day] ?? null;

                // Display string
                if (is_array($entry)) {
                    $drop = isset($entry['dropoff']) && trim($entry['dropoff']) !== '' ? $fmtTime($entry['dropoff']) : null;
                    $pick = isset($entry['pickup'])  && trim($entry['pickup'])  !== '' ? $fmtTime($entry['pickup'])  : null;
                    $disp = ($drop && $pick) ? "{$drop}–{$pick}" : ($drop ?? $pick ?? '');
                } elseif ($entry !== null) {
                    $disp = trim((string) $entry);
                } else {
                    $disp = '';
                }

                $scheduleDays[$day] = $disp;
                if ($disp !== '') $totals[$day]++;

                // Minute accumulation
                if (is_array($entry) && !empty($entry['dropoff']) && !empty($entry['pickup'])) {
                    $mins = max(0, $toMins($entry['pickup']) - $toMins($entry['dropoff']));
                    $weeklyMins       += $mins;
                    $dailyMins[$day]  += $mins;
                }
            }

            $child->weeklyHours = $weeklyMins > 0 ? $fmtMins($weeklyMins) : null;
            $child->scheduleDays = $scheduleDays;
        }

        // ── Total weekly hours across all kids ──────────────────────────
        $totalWeeklyMins = array_sum($dailyMins);
        $totalWeeklyHours = $totalWeeklyMins > 0 ? $fmtMins($totalWeeklyMins) : null;

        // ── Trend: delivered vs linear pace through today ────────────────
        // ISO day-of-week: 1=Mon … 5=Fri, 6=Sat, 7=Sun
        $todayIso  = (int) now()->format('N');
        $dayNums   = ['monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5];

        // Days fully elapsed before today (strictly less than today's ISO number, capped at weekdays)
        $elapsedDays     = 0;
        $deliveredMins   = 0;
        foreach ($dayNums as $day => $iso) {
            if ($iso < min($todayIso, 6)) {   // only weekdays before today
                $deliveredMins += $dailyMins[$day];
                $elapsedDays++;
            }
        }

        $trend = null;
        if ($totalWeeklyMins > 0) {
            if ($todayIso >= 6) {
                // Weekend — full week done
                $trend = ['label' => 'Week complete', 'diff' => 0, 'status' => 'neutral'];
            } elseif ($todayIso === 1 || $elapsedDays === 0) {
                // Monday or no days elapsed yet
                $trend = ['label' => 'Week just started', 'diff' => 0, 'status' => 'neutral'];
            } else {
                // Linear expectation based on elapsed weekdays
                $linearMins = (int) round(($elapsedDays / 5) * $totalWeeklyMins);
                $diffMins   = $deliveredMins - $linearMins;
                $absDiff    = abs($diffMins);
                $diffStr    = $fmtMins($absDiff);
                $trend = [
                    'delivered'  => $fmtMins($deliveredMins),
                    'expected'   => $fmtMins($linearMins),
                    'diff'       => $diffMins,
                    'diffStr'    => $diffStr,
                    'elapsedDays'=> $elapsedDays,
                    'status'     => $diffMins > 0 ? 'ahead' : ($diffMins < 0 ? 'behind' : 'on-pace'),
                    'label'      => $diffMins > 0
                        ? "↑ {$diffStr} ahead of pace"
                        : ($diffMins < 0 ? "↓ {$diffStr} behind pace" : "→ Exactly on pace"),
                ];
            }
        }

        return view('portal.dashboard', compact(
            'stats', 'children', 'days', 'totals',
            'totalWeeklyHours', 'dailyMins', 'trend', 'fmtMins'
        ));
    }
}
