<?php

namespace App\Domain\ModelManager;

use App\Domain\Repositories\TeamConfigurationModelQuery;
use App\Enums\ClockState;
use App\Models\TeamClockCycle;
use App\Models\TeamConfiguration;
use Carbon\Carbon;

class TeamClockCycleManager {

    public function createTeamClockCycleFromRef(TeamClockCycle $ref): TeamClockCycle {
        $model = new TeamClockCycle();
        $model->clock_state = ClockState::WORK;
        $model->team_id = $ref->team_id;
        /** @var TeamConfiguration $conf */
        $conf = TeamConfigurationModelQuery::getQuery()->whereTeam($ref->team_id)->first();
        $model->team_configuration_id = $conf->id;
        $model->start_time = Carbon::now();
        $model->end_work_time = $model->start_time->addMinutes($conf->work_time);
        $model->end_free_time = $model->end_work_time->addMinutes($conf->free_time);
        $model->save();
        return $model;
    }

    public function createTeamClockCycle(int $teamId): TeamClockCycle {
        $model = new TeamClockCycle();
        $model->clock_state = ClockState::WORK;
        $model->team_id = $teamId;
        /** @var TeamConfiguration $conf */
        $conf = TeamConfigurationModelQuery::getQuery()->whereTeam($teamId)->first();
        $model->team_configuration_id = $conf->id;
        $model->start_time = Carbon::now();
        $model->end_work_time = $model->start_time->addMinutes($conf->work_time);
        $model->end_free_time = $model->end_work_time->addMinutes($conf->free_time);
        $model->save();
        return $model;
    }
}
