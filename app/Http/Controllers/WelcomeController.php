<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deal;
use App\Models\Task;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;



class WelcomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $actualDeals = Deal::where('phase_id', "<", 4)
            ->join("phases", "deals.phase_id", "=", "phases.id")
            ->join('contacts', 'deals.contact_id', '=', 'contacts.id')
            ->orderBy("end_date", "asc")->get();

        $nextWeek = now()->addDays(7); // Через 7 дней

        $actualTasks = Task::orderBy("date", "asc")->get();
        try {
            $soonTasks = Task::whereNotNull('date')
                ->where("priority_id", "!=", "3")
                ->whereDate('date', '<=', $nextWeek)
                ->leftJoin('contacts', 'tasks.contact_id', '=', 'contacts.id')
                ->leftJoin('priorities', 'tasks.priority_id', '=', 'priorities.id')
                ->orderBy("tasks.date", "asc")
                ->get([
                    'tasks.*'
                ]);
        } catch (Exception $e) {
            dd($e->getMessage());
        }


        return view("welcome", [
            'user' => $user,
            'actualDeals' => $actualDeals,
            'soonTasks' => $soonTasks,
            'actualTasks' => $actualTasks
        ]);
    }

    public function getData(Request $request)
    {
        $month = $request->query('month', now()->month);

        $completed = 0;
        $inProgress = 0;
        $canceled = 0;

        $deals = Deal::withTrashed()
            ->whereHas('phase', function ($query) {
                $query->whereIn('id', [1, 2, 3, 4, 5]);
            })
            ->when($month, function ($query, $month) {
                return $query
                    ->where(function ($q) use ($month) {
                        $q->whereNotNull('deleted_at')->whereMonth('deleted_at', $month);
                    })
                    ->orWhere(function ($q) use ($month) {
                        $q->whereNull('deleted_at')->whereMonth('updated_at', $month);
                    });
            })
            ->get();

        foreach ($deals as $deal) {
            $date = $deal->deleted_at ?? $deal->updated_at;

            if (!$date) continue;

            $status = $deal->phase->id;

            if ($status == 4) {
                $completed += 1;
            } elseif ($status == 5) {
                $canceled += 1;
            } elseif (in_array($status, [1, 2, 3])) {
                $inProgress += 1;
            }
        }

        $totalAll = $completed + $canceled + $inProgress;

        $percentCompleted = $totalAll > 0 ? round(($completed / $totalAll) * 100, 1) : 0;

        return response()->json([
            'chart' => [
                'labels' => ['Завершено', 'Отменено', 'В процессе'],
                'data' => [$completed, $canceled, $inProgress],
            ],
            'summary' => [
                'total' => $totalAll,
                'completed' => $completed,
                'canceled' => $canceled,
                'in_progress' => $inProgress,
                'percent_completed' => $percentCompleted . '%',
                'average_per_month' => round($totalAll / 12, 2),
            ]
        ]);
    }

    public function getTasksData(Request $request)
    {
        try {
            $month = $request->query('month', now()->month);

            // Считаем количество задач по статусам
            $inProgress = Task::where('status_id', 1)->whereMonth('created_at', $month)->count();

            $completed = Task::where('status_id', 2)
                ->whereNotNull('deleted_at')
                ->whereMonth('deleted_at', $month)
                ->withTrashed()
                ->count();

            $overdue = Task::where('status_id', 3)
                ->whereNotNull('deleted_at')
                ->whereMonth('deleted_at', $month)
                ->withTrashed()
                ->count();

            $canceled = Task::where('status_id', 4)
                ->whereNotNull('deleted_at')
                ->whereMonth('deleted_at', $month)
                ->withTrashed()
                ->count();

            $total = $inProgress + $completed + $overdue + $canceled;

            return response()->json([
                'chart' => [
                    'labels' => ['В процессе', 'Завершённые', 'Просроченные', 'Отменённые'],
                    'data' => [$inProgress, $completed, $overdue, $canceled],
                ],
                'summary' => [
                    'total' => $total,
                    'in_progress' => $inProgress,
                    'completed' => $completed,
                    'overdue' => $overdue,
                    'canceled' => $canceled,
                    'percent_completed' => $total > 0 ? round(($completed / $total) * 100, 1) : 0,
                ]
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ошибка при получении данных: ' . $e->getMessage()], 500);
        }
    }
}
