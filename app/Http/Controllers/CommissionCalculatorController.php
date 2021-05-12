<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CommissionCalculatorController extends Controller
{
    public function __constructor()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        return view('admin.commission-calculator');
    }

    public function getPaymentsByDebtCollectors(Request $request)
    {
        $dates = $this->validate($request, [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            "search" => "string|nullable",
        ]);


        return response()->json(
            DB::table('debt_collectors as dc')
                ->leftJoin('payments as p', 'p.debt_collector_id', '=', 'dc.id')
                ->leftJoin('payment_invoices as pi', 'pi.payment_id', '=', 'p.id')
                ->whereRaw("p.payment_date >= '{$dates['start_date']}' AND p.payment_date <= '{$dates['end_date']}'")
                ->selectRaw('
                        dc.id as debt_collector_id,
                        dc.name as debt_collector_name,
                        SUM(COALESCE(p.amount, 0)) as amount,
                        (MAX(COALESCE(dc.commission, 0)) * 100) as commission,
                        COUNT(DISTINCT p.id) as payments_count,
                        COUNT(DISTINCT pi.invoice_id) as invoices_count')
                ->groupByRaw('debt_collector_id, debt_collector_name')
                ->get()
        );
    }
}
