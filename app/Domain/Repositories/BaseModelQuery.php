<?php

namespace App\Domain\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


/**
 * @phpstan-template T of Model
 */
abstract class BaseModelQuery {

    protected Builder $query;

    /**
     */
    public function __construct() {
        $this->query = $this->provideQueryBuilder();
    }


    abstract protected function provideQueryBuilder(): Builder;

    public function getModelById(int $id): static {
        $this->query->where('id', $id);
        return $this;
    }

    /**
     * @return T|Model
     */
    public function first(): ?Model {
        return $this->query->first();
    }

    /**
     * @return Collection<int,Model|T>
     */
    public function get(): Collection {
        return $this->query->get();
    }

    public function orderBy(string $column, bool $desc = false): static {
        return $desc ? $this->orderByDesc($column) : $this->orderByAsc($column);
    }

    public function orderByDesc(string $column): static {
        $this->query->orderByDesc($column);
        return $this;
    }

    public function orderByAsc(string $column): static {
        $this->query->orderByDesc($column);
        return $this;
    }

    public static function getQuery(): static {
        return new static();
    }
}
