<?php

namespace App\Domain\Repositories;

use App\Models\TeamConfiguration;
use Illuminate\Database\Eloquent\Builder;

class TeamConfigurationModelQuery extends BaseModelQuery {
    protected function provideQueryBuilder(): Builder {
        return TeamConfiguration::query();
    }

    public function whereTeam(int $teamId): static {
        $this->query->where(TeamConfiguration::ATTRIBUTE_TEAM_ID, $teamId);
        return $this;
    }

    public function withTeam(): static {
        $this->query->with(TeamConfiguration::RELATION_PARENT_TEAM);
        return $this;
    }

    public function withClocks(): static {
        $this->query->with(TeamConfiguration::RELATION_CHILD_TEAM_CLOCK_CYCLES);
        return $this;
    }

    public function withAll(): static {
        $this->withClocks();
        $this->withTeam();
        return $this;
    }
}
