<?php

namespace App\Http\Controllers;

use App\DebtCollector;
use App\User;
use App\Utils\CamelCaseUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.user');
    }


    public function loggedUser(){

        return response()->json([
            "data" => auth()->user() ?? null
        ]);

    }

    public function store(Request $request)
    {

        $id = $request->all()['id'] ?? null;

        $unique_validation = isset($id) ? ",{$id}" : "";

        $password_validation = isset($id) ? "nullable|" : "required|min:1|";

        $valid = $this->validate($request, [
            "id" => "nullable|exists:users,id",
            "full_name" => "required|string",
            "name" => "nullable|string|unique:users,name{$unique_validation}",
            "identification" => [
                "required",
                "string",
                "unique:users,identification{$unique_validation}"
//                "regex:/[0-9]/"
            ],
            "email" => "nullable|email|unique:users,email{$unique_validation}",
            "phone" => "nullable|string",
            "status" => "required|numeric|in:1,0",
            "password" => "{$password_validation}string"
        ]);

        try {

            if (isset($id)) {

                $user = User::findOrFail($valid['id']);

                $user->full_name = $valid['full_name'];

                $user->identification = $valid['identification'];

                $user->name = $valid['identification'];

                $user->email = $valid['email'] ?? null;

                $user->phone = $valid['phone'] ?? null;

                $user->status = $valid['status'] === "1";

                $user->save();

            } else {

                $user = new User();

                $user->full_name = $valid['full_name'];

                $user->identification = $valid['identification'];

                $user->name = $valid['identification'];

                $user->email = $valid['email'] ?? null;

                $user->phone = $valid['phone'] ?? null;

                $user->status = $valid['status'] === "1";

                $user->password = bcrypt($valid['password']);

                $user->save();

            }

            return response()->json([
                "data" => $user,
                "error" => null,
                "message" => "ok"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "data" => null,
                "error" => $e->getMessage()
            ], 500);
        }

    }

    public function show(Request $request, $userId)
    {
        $user = User::with('roles', 'customers')->findOrFail($userId);

        return response()->json([
            "data" => $user
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function showAll(Request $request)
    {
        $valid = $this->validate($request, [
            "limit" => "numeric|nullable",
            "offset" => "numeric|nullable",
            "search" => "string|nullable",
            "sort_by" => "array|nullable"
        ]);
        $grid = new Grid(
            $valid['limit'] ?? null,
            $valid['offset'] ?? null,
            $valid['search'] ?? null,
            $valid['sort_by'] ?? null
        );
        $users = User::query();
        if ($grid->getSearch() !== null) {
            $users = $users->whereRaw("lower(concat(CONVERT(id, char), name, email, full_name)) like lower('%{$grid->getSearch()}%')");
        }
        if (!empty($grid->getSortBy())) {
            $users->orderByRaw(collect($grid->getSortBy())->map(function ($sortBy) {
                return "{$sortBy[0]} {$sortBy[1]}";
            })->implode(","));
        } else {
            $users->orderByRaw('id, full_name, name, email');
        }
        return response()->json([
            "total" => $users->limit($grid->getLimit())->offset($grid->getOffset())->count(),
            "data" => $users->get()
        ]);
    }
}
