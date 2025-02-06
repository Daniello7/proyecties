<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class EntryTable extends Component
{
    public $rows;
    public $columns;
    public $alias;

    public $relations;

    public function __construct($model, $alias = [], $columns = [], $relations = [])
    {
        $this->columns = $columns;

        $this->alias = $alias;

        $this->relations = $relations;

        $query = $model::query();

        // Cargar relaciones si es necesario
        foreach ($this->relations as $relation => $col) {
            $query->with([$relation => function ($query) use ($col) {
                // Seleccionamos solo los campos necesarios de la relación
                $query->select($col);
            }]);
        }

        $this->rows = $model::select($this->columns)->get();

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.entry-table');
    }
}
