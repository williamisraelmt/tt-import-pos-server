<?php

namespace App\Http\Controllers;

use App\Http\Traits\MultiSelectDropdownTrait;
use App\ProductDepartment;
use Illuminate\Http\Request;

class ProductDepartmentController extends Controller
{
    use MultiSelectDropdownTrait;

    public function showList(Request $request){
        $valid = $this->validate($request, [
            'search' => 'string|nullable'
        ]);
        return response()->json($this->getDropdown(new ProductDepartment(), $valid['search'] ?? null));
    }
}
