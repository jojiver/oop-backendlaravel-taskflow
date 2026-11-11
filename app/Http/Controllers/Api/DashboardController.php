<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserResource;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboard,
    ) {}

    public function show(Request $request): JsonResponse
    {
        $data = $this->dashboard->summary($request->user()->id);

        return response()->json([
            'data' => [
                'user' => new UserResource($request->user()),
                'summary' => $data['summary'],
                'priorityMix' => $data['priorityMix'],
                'nextUp' => TaskResource::collection($data['nextUp'])->resolve(),
            ],
        ]);
    }
}
