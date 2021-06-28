<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.payment');
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

        $payments = DB::table('payments as p')
            ->join('customers as c', 'c.id', '=', 'p.customer_id')
            ->leftJoin('debt_collectors as dc', 'dc.id', '=', 'p.debt_collector_id')
            ->join('payment_invoices as pi', 'pi.payment_id', '=', 'p.id')
            ->join('invoices as i', 'i.id', '=', 'pi.invoice_id')
            ->selectRaw("
                p.id as payment_id,
                p.name as payment_name,
                p.amount as payment_amount,
                p.customer_id as customer_id,
                c.name as customer_name,
                p.debt_collector_id as debt_collector_id,
                dc.name as debt_collector_name,
                GROUP_CONCAT(i.display_name ORDER BY i.id  SEPARATOR ',') as invoices
                ");
        if ($grid->getSearch() !== null) {
            $payments = $payments->whereRaw("lower(concat(
            CONVERT(p.id, char),
            COALESCE(p.name, ''),
            COALESCE(CONVERT(p.amount, char), ''),
            COALESCE(CONVERT(p.customer_id, char), ''),
            COALESCE(c.name, ''),
            COALESCE(CONVERT(p.debt_collector_id, char), ''),
            COALESCE(dc.name, ''),
            COALESCE(i.display_name, '')
            )) like lower('%{$grid->getSearch()}%')");
        }
        if (!empty($grid->getSortBy())) {
            $payments->orderByRaw(collect($grid->getSortBy())->map(function ($sortBy) {
                return "{$sortBy[0]} {$sortBy[1]}";
            })->implode(","));
        } else {
            $payments->orderByRaw('payment_id DESC, payment_name, payment_amount, customer_id, customer_name, debt_collector_id, debt_collector_name, invoices');
        }
        return response()->json([
            "total" => $payments->count(),
            "data" => $payments
                ->limit($grid->getLimit())
                ->offset($grid->getOffset())
                ->groupByRaw("
                payment_id,
                payment_name,
                payment_amount,
                customer_id,
                customer_name,
                debt_collector_id,
                debt_collector_name")
                ->get()
                ->map(function ($record) {
                    $invoices = $record->invoices;
                    $record->invoices = explode(",", $invoices);
                    return $record;
                })
        ]);
    }

    public function updateDebtCollector(Request $request, int $paymentId)
    {

        $valid = $this->validate($request, [
            "debt_collector_id" => "nullable|numeric|exists:debt_collectors,id"
        ]);

        try {
            $payment = Payment::findOrFail($paymentId);
            $payment->debt_collector_id = $valid['debt_collector_id'];
            $payment->save();
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
