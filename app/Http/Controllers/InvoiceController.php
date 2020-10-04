<?php

namespace App\Http\Controllers;

use App\Customer;
use App\DeliveryLead;
use App\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('invoice');
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return Response
     */
    public function showAll(Request $request)
    {
        return response()->json(Invoice::all());
    }

    public function showByCustomer(Request $request, int $customerId)
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
        return response()->json([
            "total" => $invoices->count(),
            "data" => $invoices->get(['id', 'display_name'])->toArray()
        ]);

    }
}
