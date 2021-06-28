<?php


namespace App\Http\Traits;


use App\Http\Controllers\Grid;
use Illuminate\Database\Eloquent\Model;

trait MultiSelectDropdownTrait
{

    public function getDropdown(Model $model, ?string $search = null)
    {
        $grid = new Grid(
            null,
            null,
            $search,
            null
        );
        $data = $model::query()->select('id', 'name')->whereStatus(true);
        if ($grid->getSearch() !== null) {
            $data = $data->whereRaw("lower(concat(CONVERT(id, char), name, CONVERT(commission, char))) like lower('%{$grid->getSearch()}%')");
        }
        if (!empty($grid->getSortBy())) {
            $data->orderBy('name');
        }
        return [
            "total" => $data->count(),
            "data" => $data->get()
        ];
    }

}
