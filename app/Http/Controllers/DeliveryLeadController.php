<?php

namespace App\Http\Controllers;

use App\DeliveryLead;
use App\DeliveryLeadDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class DeliveryLeadController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.delivery-lead');
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
        $delivery_leads = DB::table('delivery_leads as dl')
            ->join('delivery_lead_details as dld', 'dld.delivery_lead_id', '=', 'dl.id')
            ->join('invoices as i', 'i.id', '=', 'dld.invoice_id')
            ->join('customers as c', 'c.id', '=', 'i.customer_id')
            ->selectRaw("dl.id, c.name as customer_name, GROUP_CONCAT(i.display_name SEPARATOR ',') as invoices, dl.package_quantity, dl.created_at")
            ->groupByRaw("dl.id, c.name, dl.package_quantity, dl.created_at");
        if ($grid->getSearch() !== null) {
            $delivery_leads = $delivery_leads->whereRaw("lower(concat(CONVERT(dl.id, char), c.name, i.display_name, concat(CONVERT(dl.package_quantity, char)))) like lower('%{$grid->getSearch()}%')");
        }
        if (!empty($grid->getSortBy())) {
            $delivery_leads->orderByRaw(collect($grid->getSortBy())->map(function ($sortBy) {
                return "{$sortBy[0]} {$sortBy[1]}";
            })->implode(","));
        } else {
            $delivery_leads->orderByRaw('dl.id DESC');
        }
        return response()->json([
            "total" => $delivery_leads->count(),
            "data" => $delivery_leads->get()->map(function($record){
                $invoices = $record->invoices;
                $record->invoices = explode(",", $invoices);
                return $record;
            })
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function print(Request $request)
    {
        $lead_ids = $request->all();
        $leads = collect($lead_ids)
            ->filter(function ($leadId) {
                return is_numeric((int)$leadId);
            })
            ->flatMap(function ($leadId) {
                return DeliveryLead::with('deliveryLeadDetails.invoice', 'customer')->find($leadId);
            })->toArray();

        if (empty($leads)){
            return abort(404);
        }

        return view('admin.delivery-lead-print', [
            'leads' => $leads
        ]);
    }


    public function create(Request $request)
    {
        $valid = $this->validate($request, [
            'leads' => 'array|required',
            'leads.*.id' => 'numeric|required|exists:customers,id',
            'leads.*.invoices.*.id' => 'numeric|required|exists:invoices,id',
            'leads.*.package_quantity' => 'numeric|required|min:1'
        ]);
        try {
            $delivery_lead_ids = [];
            DB::beginTransaction();
            foreach ($valid['leads'] as $lead) {
                $delivery_lead = new DeliveryLead();
                $delivery_lead->package_quantity = $lead['package_quantity'];
                $delivery_lead->customer_id = $lead['id'];
                $delivery_lead->save();
                foreach ($lead['invoices'] as $invoice) {
                    $lead_detail = new DeliveryLeadDetail();
                    $lead_detail->delivery_lead_id = $delivery_lead->id;
                    $lead_detail->invoice_id = $invoice['id'];
                    $lead_detail->save();
                }
                $delivery_lead_ids[] = $delivery_lead->id;
            }
            DB::commit();
            return response()->json([
                'data' => $delivery_lead_ids,
                'count' => sizeof($delivery_lead_ids),
                'message' => ""
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'data' => [],
                'count' => 0,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete(Request $request, int $id)
    {
        try {
            DeliveryLeadDetail::whereDeliveryLeadId($id)->delete();
            DeliveryLead::findOrFail($id)->delete();
            return response()->json([
                'data' => [],
                'count' => 1,
                'message' => "ok"
            ]);
        } catch (\Exception $e){
            return response()->json([
                'data' => [],
                'count' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
