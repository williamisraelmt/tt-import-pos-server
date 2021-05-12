<?php

namespace App\Http\Controllers;

use App\Jobs\OdooSync;
use App\Utils\StateTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SyncController extends Controller
{

//    public function pending()
//    {
//        $unfinished_jobs = DB::table('odoo_syncs')
//            ->where('displayed', '=', false)->count();
//
//        if ($unfinished_jobs > 0) {
//
//            return response()->json([
//                "data" => true,
//                "message" => "ok"
//            ]);
//
//        }
//
//        return response()->json([
//            "data" => false,
//            "message" => "ok"
//        ]);
//    }

    public function sync()
    {
        try {

            $unfinished_jobs = DB::table('odoo_syncs')
                ->where('displayed', '=', false)->count();

            if ($unfinished_jobs > 0) {

                return response()->json([
                    "data" => null,
                    "message" => "ok"
                ]);

            }

            $odoo_sync = new \App\OdooSync();

            $odoo_sync->state = StateTypes::IN_PROGRESS;

            $odoo_sync->save();

            OdooSync::dispatchAfterResponse($odoo_sync);

            return response()->json([
                "data" => null,
                "message" => "ok"
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], 500);

        }

    }

    public function latest()
    {
        try {

            $odoo_sync = \App\OdooSync
                ::where('displayed', '=', false)
                ->orderByDesc('created_at')
                ->first();

            if (!isset($odoo_sync)) {

                return response()->json([
                    "data" => null,
                    "message" => "ok"
                ]);

            }

            if ($odoo_sync->state !== StateTypes::IN_PROGRESS) {

                $odoo_sync->displayed = true;

                $odoo_sync->save();

            }

            return response()->json([
                "data" => $odoo_sync,
                "message" => "ok"
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], 500);

        }
    }
}
