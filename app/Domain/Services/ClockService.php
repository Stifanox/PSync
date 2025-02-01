<?php

namespace App\Domain\Services;

use App\Domain\ModelManager\TeamClockCycleManager;
use App\Domain\Repositories\TeamClockModelQuery;
use App\Domain\Repositories\TeamConfigurationModelQuery;
use App\Enums\ClockState;
use App\Models\TeamClockCycle;
use App\Models\TeamConfiguration;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class ClockService {


    public function __construct(
        protected TeamClockCycleManager $teamClockCycleManager,
    ) {
    }

    public function getCurrentClock(User $currentUser, int $teamId): TeamClockCycle {
        $clock = TeamClockModelQuery::getQuery()
            ->whereTeam($teamId)
            ->orderBy(TeamClockCycle::ATTRIBUTE_CREATED_AT, desc: true)
            ->first();
        if(is_null($clock)) {
            return $this->teamClockCycleManager->createTeamClockCycle($teamId);
        }
        return $clock;
    }

    public function getClock(User $currentUser, int $clockId): TeamClockCycle {
        return TeamClockModelQuery::getQuery()
            ->getModelById($clockId)
            ->first();
    }

    public function changeState(User $currentUser, int $clockId): TeamClockCycle {
        /** @var TeamClockCycle $clock */

        $clock = TeamClockModelQuery::getQuery()
            ->getModelById($clockId)
            ->first();

        $key = 'changeState-' . $clockId;
        $clockFromLock = Cache::lock($key, 5)->get(
            function () use ($currentUser, $clock) {
                /** @var TeamClockCycle $newestClock */
                $newestClock = TeamClockModelQuery::getQuery()
                    ->orderByDesc('created_at')
                    ->first();

                if ($clock->shouldMoveToNextState()) {
                    $clock->clock_state = $clock->clock_state->getNextState();
                    $clock->save();
                }

                if ($clock->clock_state === ClockState::ENDED && $clock->id === $newestClock->id) {
                    $this->teamClockCycleManager->createTeamClockCycleFromRef($clock);
                }

                return $clock;
            }
        );

        return $clockFromLock === false ? $clock : $clockFromLock;
    }

    public function resetClock(User $currentUser, int $clockId): TeamClockCycle {
        /** @var TeamClockCycle $clock */
        $clock = TeamClockModelQuery::getQuery()
            ->getModelById($clockId)
            ->first();

        $clock->clock_state = ClockState::ENDED;
        $clock->save();
        return $this->teamClockCycleManager->createTeamClockCycleFromRef($clock);
    }
}
