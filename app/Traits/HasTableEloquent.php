<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasTableEloquent
{
    public array $columns;
    public array $select;
    public array $relations;
    public array $columnMap;
    public string $sortColumn;
    public string $sortDirection;
    public string $search = '';

    public function sortBy(string $column): void
    {
        if (!$column) return;

        if ($this->sortColumn == $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function applySearchFilter(Builder $query): void
    {
        if (!$this->search) return;

        $query->where(function ($q) {
            foreach ($this->columnMap as $column) {
                if ($column) {
                    $q->orWhere($column, 'LIKE', "%{$this->search}%");
                }
            }
        });
    }

    public function resetExceptConfig(array $excepts = []): void
    {
        $resets = array_merge(['columns', 'select', 'relations', 'columnMap', 'sortColumn', 'sortDirection', 'search'], $excepts);

        $this->resetExcept($resets);
    }
}
