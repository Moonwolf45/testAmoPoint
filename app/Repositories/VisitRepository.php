<?php

namespace App\Repositories;

use App\Models\Visit;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class VisitRepository
{
    public function create(array $data): Visit
    {
        return Visit::create($data);
    }

    public function countSince(CarbonInterface $date): int
    {
        return Visit::where('visited_at', '>=', $date)->count();
    }

    public function countDistinctIpsSince(CarbonInterface $date): int
    {
        return Visit::where('visited_at', '>=', $date)
            ->distinct('ip')
            ->count('ip');
    }

    public function groupByHourSince(CarbonInterface $date): Collection
    {
        return Visit::where('visited_at', '>=', $date)
            ->selectRaw('HOUR(visited_at) as hour, COUNT(DISTINCT ip) as unique_visits')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
    }

    public function topCitiesSince(CarbonInterface $date, int $limit = 10): Collection
    {
        return Visit::where('visited_at', '>=', $date)
            ->whereNotNull('city')
            ->selectRaw('city, COUNT(DISTINCT ip) as unique_visits')
            ->groupBy('city')
            ->orderByDesc('unique_visits')
            ->limit($limit)
            ->get();
    }

    public function groupByDeviceSince(CarbonInterface $date): Collection
    {
        return Visit::where('visited_at', '>=', $date)
            ->whereNotNull('device')
            ->selectRaw('device, COUNT(*) as count')
            ->groupBy('device')
            ->orderByDesc('count')
            ->get();
    }

    public function groupByBrowserSince(CarbonInterface $date): Collection
    {
        return Visit::where('visited_at', '>=', $date)
            ->whereNotNull('browser')
            ->selectRaw('browser, COUNT(*) as count')
            ->groupBy('browser')
            ->orderByDesc('count')
            ->get();
    }

    public function groupByOsSince(CarbonInterface $date): Collection
    {
        return Visit::where('visited_at', '>=', $date)
            ->whereNotNull('os')
            ->selectRaw('os, COUNT(*) as count')
            ->groupBy('os')
            ->orderByDesc('count')
            ->get();
    }

    public function groupByDaySince(CarbonInterface $date): Collection
    {
        return Visit::where('visited_at', '>=', $date)
            ->selectRaw('DATE(visited_at) as date, COUNT(DISTINCT ip) as unique_visits')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}
