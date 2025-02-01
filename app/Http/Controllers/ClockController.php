<?php

namespace App\Http\Controllers;

use App\Domain\Services\ClockService;
use App\Http\Resources\TeamClockResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ClockController extends Controller
{

    public function __construct(
        protected ClockService $clockService
    ) {
    }

    public function getCurrentClock(Request $request, int $teamId): JsonResource{
        $user = $request->user();
        $teamClock = $this->clockService->getCurrentClock($user, $teamId);

        return new TeamClockResource($teamClock);
    }

    public function show(Request $request, int $teamId, int $clockId): JsonResource{
        $user = request()->user();
        $teamClock = $this->clockService->getClock($user, $clockId);

        return new TeamClockResource($teamClock);
    }

    public function changeState(Request $request, int $teamId, int $clockId): JsonResource {
        $user = request()->user();
        $teamClock = $this->clockService->changeState($user, $clockId);

        return new TeamClockResource($teamClock);
    }

    public function resetClock(Request $request, int $teamId, int $clockId): JsonResource{
        $user = request()->user();
        $teamClock = $this->clockService->resetClock($user, $clockId);

        return new TeamClockResource($teamClock);
    }
}
