<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{


    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function showList(Request $request)
    {
        $valid = $this->validate($request, [
            "search" => "string|nullable",
        ]);
        $grid = new Grid(
            null,
            null,
            $valid['search'] ?? null,
            null
        );
        $roles = Role::query()->select('id', 'name');
        if ($grid->getSearch() !== null) {
            $roles = $roles->whereRaw("lower(concat(CONVERT(id, char), name)) like lower('%{$grid->getSearch()}%')");
        }
        if (!empty($grid->getSortBy())) {
            $roles->orderBy('name');
        }
        return response()->json([
            "total" => DB::table('roles')->select('id')->count(),
            "data" => $roles->get()
        ]);
    }
}
