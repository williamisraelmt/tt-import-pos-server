<?php

namespace App\Http\Controllers;

use App\Customer;
use App\DeliveryLead;
use App\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.invoice');
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return JsonResponse
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

        $invoices = DB::table('invoices as i')
            ->join('customers as c', 'c.id', '=', 'i.customer_id')
            ->selectRaw("
                i.id as id,
                i.display_name,
                i.amount_total,
                i.residual,
                i.create_date,
                i.date_due,
                i.customer_id,
                c.name as customer_name
                ");

        if ($grid->getSearch() !== null) {
            $invoices = $invoices->whereRaw("lower(concat(
            CONVERT(i.id, char),
            i.display_name,
            CONVERT(i.amount_total, char),
            CONVERT(i.create_date, char),
            CONVERT(i.date_due, char),
            CONVERT(i.customer_id, char),
            c.name
            )) like lower('%{$grid->getSearch()}%')");

        }

        if (!empty($grid->getSortBy())) {
            $invoices->orderByRaw(collect($grid->getSortBy())->map(function ($sortBy) {
                return "{$sortBy[0]} {$sortBy[1]}";
            })->implode(","));
        } else {
            $invoices->orderByRaw('i.id DESC, c.name, i.name');
        }
        return response()->json([
            "total" => $invoices->count(),
            "data" => $invoices->limit($grid->getLimit())->offset($grid->getOffset())->get()
        ]);
    }
}
