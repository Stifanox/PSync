<?php

namespace App\Domain\Repositories;

use App\Models\TeamClockCycle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TeamClockModelQuery extends BaseModelQuery {
    protected function provideQueryBuilder(): Builder {
        return TeamClockCycle::query();
    }

    public function whereTeam(int $teamId): static {
        $this->query->where(TeamClockCycle::ATTRIBUTE_TEAM_ID, $teamId);
        return $this;
    }

    public function withTeam():static {
        $this->query->with(TeamClockCycle::RELATION_PARENT_TEAM);
        return $this;
    }

}
