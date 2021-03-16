<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('customer');
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
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
        $customers = Customer::query()->limit($grid->getLimit())->offset($grid->getOffset());
        if ($grid->getSearch() !== null) {
            $customers = $customers->whereRaw("lower(concat(CONVERT(id, char), name, address, phone)) like lower('%{$grid->getSearch()}%')");
        }
        if (!empty($grid->getSortBy())) {
            $customers->orderByRaw(collect($grid->getSortBy())->map(function ($sortBy) {
                return "{$sortBy[0]} {$sortBy[1]}";
            })->implode(","));
        } else {
            $customers->orderBy('name');
        }
        return response()->json([
            "total" => DB::table('customers')->select('id')->count(),
            "data" => $customers->get()
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
        $customers = Customer::query()->select('id', 'name');
        if ($grid->getSearch() !== null) {
            $customers = $customers->whereRaw("lower(concat(CONVERT(id, char), name, address, phone)) like lower('%{$grid->getSearch()}%')");
        }
        if (!empty($grid->getSortBy())) {
            $customers->orderBy('name');
        }
        return response()->json([
            "total" => DB::table('customers')->select('id')->count(),
            "data" => $customers->get()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Customer $customer
     * @return Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Customer $customer
     * @return Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Customer $customer
     * @return Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
