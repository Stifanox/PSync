<?php

namespace App\Domain\Repositories;

use App\Models\Organization;
use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;

class TeamModelQuery extends BaseModelQuery {
    protected function provideQueryBuilder(): Builder {
        return Team::query();
    }

    public function whereOrganization(Organization $organization): static {
        $this->query->where(Team::ATTRIBUTE_ORGANIZATION_ID, $organization->id);
        return $this;
    }

    public function withUsers(): static {
        $this->query->with(Team::RELATION_ATTACH_USERS);
        return $this;
    }

    public function withConfiguration(): static {
        $this->query->with(Team::RELATION_CHILD_CONFIGURATION);
        return $this;
    }

    public function withClocks(): static {
        $this->query->with(Team::RELATION_CHILD_TEAM_CLOCKS);
        return $this;
    }

    public function withAllRelations(): static {
        $this->withUsers();
        $this->withClocks();
        $this->withConfiguration();
        return $this;
    }
}
