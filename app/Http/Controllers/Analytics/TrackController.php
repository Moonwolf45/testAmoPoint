<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrackVisitRequest;
use App\Services\VisitTrackerService;

class TrackController extends Controller
{
    public function __construct(
        protected VisitTrackerService $trackerService,
    ) {}

    public function track(TrackVisitRequest $request)
    {
        $this->trackerService->track($request);

        return response()->json(['status' => 'ok']);
    }
}
