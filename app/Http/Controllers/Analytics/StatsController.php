<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetStatsRequest;
use App\Services\StatsService;

class StatsController extends Controller
{
    public function __construct(
        protected StatsService $statsService,
    ) {}

    public function index()
    {
        return view('analytics.index');
    }

    public function getData(GetStatsRequest $request)
    {
        $startDate = now()->subDays($request->getDays());

        return response()->json($this->statsService->getFullStats($startDate));
    }
}
