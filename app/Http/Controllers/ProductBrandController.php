<?php

namespace App\Http\Controllers;

use App\Http\Traits\MultiSelectDropdownTrait;
use App\ProductBrand;
use Illuminate\Http\Request;

class ProductBrandController extends Controller
{
    use MultiSelectDropdownTrait;

    public function showList(Request $request){
        $valid = $this->validate($request, [
           'search' => 'string|nullable'
        ]);
        return response()->json($this->getDropdown(new ProductBrand(), $valid['search'] ?? null));
    }
}
