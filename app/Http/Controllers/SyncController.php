<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SyncController extends Controller
{

    public function sync()
    {
        try {
            Artisan::call("download:ci");
            return response()->json([
                'message' => "ok"
            ]);
        } catch (\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
