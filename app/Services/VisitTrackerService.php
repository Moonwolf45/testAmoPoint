<?php

namespace App\Services;

use App\Http\Requests\TrackVisitRequest;
use App\Repositories\VisitRepository;

class VisitTrackerService
{
    public function __construct(
        protected VisitRepository $visitRepository,
        protected IpLocationService $ipLocationService,
        protected UserAgentParserService $userAgentParserService,
    ) {}

    public function track(TrackVisitRequest $request): void
    {
        $ip = $request->ip();
        $userAgent = $request->header('User-Agent') ?? $request->input('user_agent');
        $pageUrl = $request->header('Referer') ?? $request->input('page_url');

        $location = $this->ipLocationService->resolve($ip);
        $parsed = $this->userAgentParserService->parse($userAgent);

        $this->visitRepository->create([
            'ip' => $ip,
            'city' => $location['city'],
            'country' => $location['country'],
            'device' => $parsed['device'],
            'browser' => $parsed['browser'],
            'os' => $parsed['os'],
            'screen_resolution' => $request->input('screen_resolution'),
            'user_agent' => $userAgent,
            'page_url' => $pageUrl,
            'visited_at' => now()
        ]);
    }
}
