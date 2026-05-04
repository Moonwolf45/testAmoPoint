<?php

namespace App\Services;

use App\Repositories\VisitRepository;
use Carbon\CarbonInterface;

class StatsService
{
    public function __construct(
        protected VisitRepository $visitRepository,
    ) {}

    /**
     * Получить полную статистику за период.
     */
    public function getFullStats(CarbonInterface $startDate): array
    {
        return [
            'total_visits' => $this->visitRepository->countSince($startDate),
            'unique_visitors' => $this->visitRepository->countDistinctIpsSince($startDate),
            'hourly_data' => $this->visitRepository->groupByHourSince($startDate),
            'city_data' => $this->visitRepository->topCitiesSince($startDate),
            'device_data' => $this->visitRepository->groupByDeviceSince($startDate),
            'browser_data' => $this->visitRepository->groupByBrowserSince($startDate),
            'os_data' => $this->visitRepository->groupByOsSince($startDate),
            'daily_data' => $this->visitRepository->groupByDaySince($startDate)
        ];
    }

    /**
     * Получить только основные метрики (для дашборда).
     */
    public function getSummary(CarbonInterface $startDate): array
    {
        return [
            'total_visits' => $this->visitRepository->countSince($startDate),
            'unique_visitors' => $this->visitRepository->countDistinctIpsSince($startDate)
        ];
    }
}
