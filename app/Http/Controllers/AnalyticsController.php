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

        $monthNames = [
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

        $deals = Deal::withTrashed()
            ->whereHas('phase', function ($query) {
                $query->whereIn('id', ['4', '5']);
            })
            ->get();

        $stats = [];

        foreach ($deals as $deal) {
            $status = $deal->phase->id;
            $date = $deal->deleted_at ?: now();
            $shortMonth = $date->format('M');

            $ruMonth = $monthNames[$shortMonth] ?? $shortMonth;

            if (!isset($stats[$ruMonth])) {
                $stats[$ruMonth] = [4 => 0, 5 => 0];
            }

            $stats[$ruMonth][$status]++;
        }
        ksort($stats);


        // dd($deals);
        return view('analytics', [
            'stats' => $stats,
            'user' => $user,
            'months' => $monthNames,
        ]);
    }

    public function getData(Request $request)
    {
        $selectedMonth = $request->query('month');

        $monthNames = [
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


        $dateStart = \Carbon\Carbon::createFromFormat('M', $selectedMonth)->startOfMonth();
        $dateEnd = $dateStart->copy()->endOfMonth();

        $deals = Deal::withTrashed()
            ->whereHas('phase', function ($query) {
                $query->whereIn('id', [4, 5]);
            })
            ->whereBetween('deleted_at', [$dateStart, $dateEnd])
            ->get();

        $completed = $deals->where('phase.id', 4)->count();
        $ruMonth = $monthNames[$selectedMonth] ?? $selectedMonth;

        return response()->json([
            'month' => $ruMonth,
            'completed' => $completed,
        ]);
    }
}
