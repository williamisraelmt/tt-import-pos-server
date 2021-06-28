<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Invoice;
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
        return view('admin.customer');
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
        $customers = DB::table( Customer::getTableName() . ' as c')
            ->leftJoin('debt_collectors as dc', 'dc.id', '=', 'c.debt_collector_id')
            ->selectRaw('c.id, c.name, c.address, c.phone, c.debt_collector_id, dc.name as debt_collector_name');
        if ($grid->getSearch() !== null) {
            $customers = $customers->whereRaw("lower(concat(CONVERT(c.id, char), COALESCE(c.name, ''), coalesce(c.address, ''), COALESCE(c.phone, ''), COALESCE(dc.name, ''))) like lower('%{$grid->getSearch()}%')");
        }
        if (!empty($grid->getSortBy())) {
            $customers->orderByRaw(collect($grid->getSortBy())->map(function ($sortBy) {
                return "{$sortBy[0]} {$sortBy[1]}";
            })->implode(","));
        } else {
            $customers->orderByRaw('c.id, c.name');
        }
        return response()->json([
            "total" => $customers->count(),
            "data" => $customers->limit($grid->getLimit())->offset($grid->getOffset())->get()
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


    public function showInvoicesWithCustomer(Request $request, int $customerId)
    {
        $valid = $this->validate($request, [
            "search" => "string|nullable"
        ]);
        $search = $valid['search'] ?? null;
        $customer = Customer::findOrFail($customerId);
        $invoices = Invoice
            ::whereCustomerId($customer->id)
            ->whereRaw("id not in (
            SELECT invoice_id as id
              FROM delivery_lead_details dld
        INNER JOIN invoices i ON (i.id = dld.invoice_id)
             WHERE i.customer_id  = ?
        ) ", [$customer->id]);
        if (isset($search)) {
            $invoices = $invoices->where([
                [
                    'display_name',
                    'like',
                    '%' . $search . '%'
                ]
            ]);
        }
        $invoices->orderByRaw('id DESC');
        return response()->json([
            "total" => $invoices->count(),
            "data" => $invoices->get(['id', 'display_name'])->toArray()
        ]);

    }

    public function updateDebtCollector(Request $request, int $customerId)
    {
        $valid = $this->validate($request, [
            "debt_collector_id" => "nullable|numeric|exists:debt_collectors,id",
            "update_previous_payments" => "boolean|numeric|in:1,0",
        ]);

        try {

            $customer = Customer::findOrFail($customerId);

            $customer->debt_collector_id = $valid['debt_collector_id'];

            $customer->save();

            $update_previous_payments = ($valid['update_previous_payments'] ?? false) === "1";

            if ($update_previous_payments) {

                DB::update("UPDATE payments SET debt_collector_id = {$customer->debt_collector_id} WHERE customer_id = {$customer->id}");

            }

            return response()->json([
                "message" => "ok"
            ]);

        } catch (\Exception $e) {

            return response()->json([
                "message" => $e->getMessage()
            ], 500);

        }
    }
}
