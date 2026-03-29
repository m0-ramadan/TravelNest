<?php

namespace App\Http\Controllers\Admin;

use App\Models\Visitor;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class VisitorController extends Controller
{
    public function chartData(): JsonResponse
    {
        $labels = [];
        $values = [];

        foreach (range(6, 0) as $daysAgo) {
            $date = Carbon::now()->subDays($daysAgo)->toDateString();
            $labels[] = $date;
            $values[] = Visitor::whereDate('created_at', $date)->count();
        }

        return response()->json(compact('labels', 'values'));
    }

    public function quickStats(): JsonResponse
    {
        return response()->json([
            'visitors_today' => Visitor::whereDate('created_at', today())->count(),
            'visitors_total' => Visitor::count(),
        ]);
    }

    public function ordersStats(int $year): JsonResponse
    {
        return response()->json(['year' => $year, 'months' => []]);
    }
}
