<?php

namespace App\Http\Controllers;

use App\Customer;
use App\DebtCollector;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DebtCollectorController extends Controller
{
    /**
     * @return View
     */
    public function index()
    {
        return view('admin.debt-collector');
    }

    public function store(Request $request)
    {
        $id = $request->all()['id'] ?? null;

        $name_validation = isset($id) ? ",{$id}" : "";

        $valid = $this->validate($request, [
            "id" => "nullable|exists:debt_collectors,id",
            "name" => "required|string|unique:debt_collectors,name{$name_validation}",
            "status" => "required|numeric|in:1,0",
            "commission" => "required|numeric|min:0|max:100"
        ]);

        try {

            if (isset($id)) {

                $debt_collector = DebtCollector::findOrFail($valid['id']);

                $debt_collector->name = $valid['name'];

                $debt_collector->commission = $valid['commission'] / 100;

                $debt_collector->status = $valid['status'] === "1";

                $debt_collector->save();

            } else {

                $debt_collector = new DebtCollector();

                $debt_collector->name = $valid['name'];

                $debt_collector->commission = $valid['commission'] / 100;

                $debt_collector->status = $valid['status'] === "1";

                $debt_collector->save();

            }

            return response()->json([
                "data" => $debt_collector,
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

    public function show(Request $request, $debtCollectorId)
    {
        $debt_collector = DebtCollector::findOrFail($debtCollectorId);

        return response()->json([
            "data" => $debt_collector
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
        $debt_collectors = DebtCollector::query()->limit($grid->getLimit())->offset($grid->getOffset());
        if ($grid->getSearch() !== null) {
            $debt_collectors = $debt_collectors->whereRaw("lower(concat(CONVERT(id, char), name, CONVERT(commission, char))) like lower('%{$grid->getSearch()}%')");
        }
        if (!empty($grid->getSortBy())) {
            $debt_collectors->orderByRaw(collect($grid->getSortBy())->map(function ($sortBy) {
                return "{$sortBy[0]} {$sortBy[1]}";
            })->implode(","));
        } else {
            $debt_collectors->orderByRaw('id, name');
        }
        return response()->json([
            "total" => DB::table('debt_collectors')->select('id')->count(),
            "data" => $debt_collectors->get()
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return Response
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
        $debt_collectors = DebtCollector::query()->select('id', 'name')->whereStatus(true);
        if ($grid->getSearch() !== null) {
            $debt_collectors = $debt_collectors->whereRaw("lower(concat(CONVERT(id, char), name, CONVERT(commission, char))) like lower('%{$grid->getSearch()}%')");
        }
        if (!empty($grid->getSortBy())) {
            $debt_collectors->orderBy('name');
        }
        return response()->json([
            "total" => DB::table('debt_collectors')->select('id')->count(),
            "data" => $debt_collectors->get()
        ]);
    }
}
