<?php

namespace App\Http\Controllers;

use App\Http\Traits\MultiSelectDropdownTrait;
use App\ProductModel;
use Illuminate\Http\Request;

class ProductModelController extends Controller
{
    use MultiSelectDropdownTrait;

    public function showList(Request $request){
        $valid = $this->validate($request, [
            'search' => 'string|nullable'
        ]);
        return response()->json($this->getDropdown(new ProductModel(), $valid['search'] ?? null));
    }
}
