<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Deal;
use Illuminate\Support\Facades\Auth;


class AnalyticsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $months = [
            'Jan' => 'Январь',
            'Feb' => 'Февраль',
            'Mar' => 'Март',
            'Apr' => 'Апрель',
            'May' => 'Май',
            'Jun' => 'Июнь',
            'Jul' => 'Июль',
            'Aug' => 'Август',
            'Sep' => 'Сентябрь',
            'Oct' => 'Октябрь',
            'Nov' => 'Ноябрь',
            'Dec' => 'Декабрь'
        ];

        return view('analytics', compact('months', 'user'));
    }

    public function getData(Request $request)
    {
        $userId = Auth::id();
        $year = $request->query('year', now()->year);

        $monthNames = [
            'Январь',
            'Февраль',
            'Март',
            'Апрель',
            'Май',
            'Июнь',
            'Июль',
            'Август',
            'Сентябрь',
            'Октябрь',
            'Ноябрь',
            'Декабрь'
        ];

        $labels = $monthNames;

        $completed = array_fill(0, 12, 0);
        $inProgress = array_fill(0, 12, 0);
        $canceled = array_fill(0, 12, 0);

        $deals = Deal::where('user_id', $userId)
            ->whereHas('phase', function ($query) {
                $query->whereIn('id', [1, 2, 3, 4, 5]);
            })
            ->when($year, function ($query) use ($year, $userId) {
                return $query->where(function ($q) use ($year, $userId) {
                    $q->where('user_id', $userId)
                        ->where(function ($subQ) use ($year) {
                            $subQ->whereNotNull('deleted_at')
                                ->whereYear('deleted_at', $year);
                        })
                        ->orWhere(function ($subQ) use ($year) {
                            $subQ->whereNull('deleted_at')
                                ->whereYear('updated_at', $year);
                        });
                });
            })
            ->withTrashed()
            ->get();

        foreach ($deals as $deal) {
            $date = $deal->deleted_at ?? $deal->updated_at;

            if (!$date) {
                continue; // пропускаем сделки без дат
            }

            $monthNum = (int) \Carbon\Carbon::parse($date)->format('n');
            $status = $deal->phase->id;

            if ($status == 4) {
                $completed[$monthNum - 1] += 1;
            } elseif ($status == 5) {
                $canceled[$monthNum - 1] += 1;
            } elseif (in_array($status, [1, 2, 3])) {
                $inProgress[$monthNum - 1] += 1;
            }
        }

        $totalCompleted = array_sum($completed);
        $totalCanceled = array_sum($canceled);
        $totalInProgress = array_sum($inProgress);
        $totalAll = $totalCompleted + $totalCanceled + $totalInProgress;

        $percentCompleted = $totalAll > 0 ? round(($totalCompleted / $totalAll) * 100, 1) : 0;

        return response()->json([
            'labels' => $labels,
            'completed' => $completed,
            'canceled' => $canceled,
            'inProgress' => $inProgress,
            'summary' => [
                'total' => $totalAll,
                'completed' => $totalCompleted,
                'canceled' => $totalCanceled,
                'percentCompleted' => $percentCompleted . '%',
                'averagePerMonth' => round($totalAll / 12, 2)
            ]
        ]);
    }

    // public function getAllTimeData()
    // {
    //     $userId = Auth::id();

    //     $deals = Deal::where('user_id', $userId)
    //         ->whereHas('phase', function ($query) {
    //             $query->whereIn('id', [1, 2, 3, 4, 5]);
    //         })
    //         ->withTrashed()
    //         ->get();

    //     $totalCompleted = $deals->where('phase_id', 4)->count();
    //     $totalCanceled = $deals->where('phase_id', 5)->count();
    //     $totalInProgress = $deals->whereIn('phase_id', [1, 2, 3])->count();

    //     return response()->json([
    //         'summary' => [
    //             'completed' => $totalCompleted,
    //             'canceled' => $totalCanceled,
    //             'inProgress' => $totalInProgress,
    //             'total' => $totalCompleted + $totalCanceled + $totalInProgress,
    //             'percentCompleted' => $totalCompleted > 0 ? round(($totalCompleted / ($totalCompleted + $totalCanceled + $totalInProgress)) * 100, 1) . '%' : '0%',
    //             'percentInProgress' => $totalInProgress > 0 ? round(($totalInProgress / ($totalCompleted + $totalCanceled + $totalInProgress)) * 100, 1) . '%' : '0%'
    //         ]
    //     ]);
    // }
    public function getAllTimeData()
    {
        $userId = Auth::id();

        // Получаем количество завершенных сделок
        $totalCompleted = Deal::where('user_id', $userId)
            ->whereHas('phase', function ($query) {
                $query->where('id', 4); // Только завершенные
            })
            ->withTrashed()
            ->count();

        // Получаем количество отмененных сделок
        $totalCanceled = Deal::where('user_id', $userId)
            ->whereHas('phase', function ($query) {
                $query->where('id', 5); // Только отмененные
            })
            ->withTrashed()
            ->count();

        // Получаем количество сделок в процессе
        $totalInProgress = Deal::where('user_id', $userId)
            ->whereHas('phase', function ($query) {
                $query->whereIn('id', [1, 2, 3]); // Все статусы "в процессе"
            })
            ->withTrashed()
            ->count();

        $total = $totalCompleted + $totalCanceled + $totalInProgress;

        return response()->json([
            'summary' => [
                'completed' => $totalCompleted,
                'canceled' => $totalCanceled,
                'inProgress' => $totalInProgress,
                'total' => $total,
                'percentCompleted' => $total > 0 ? round(($totalCompleted / $total) * 100, 1) . '%' : '0%',
                'percentInProgress' => $total > 0 ? round(($totalInProgress / $total) * 100, 1) . '%' : '0%'
            ]
        ]);
    }
}
