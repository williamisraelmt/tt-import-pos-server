<?php

namespace App\Http\Controllers;

use App\Http\Traits\MultiSelectDropdownTrait;
use App\ProductType;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    use MultiSelectDropdownTrait;

    public function showList(Request $request){
        $valid = $this->validate($request, [
            'search' => 'string|nullable'
        ]);
        return response()->json($this->getDropdown(new ProductType(), $valid['search'] ?? null));
    }
}
