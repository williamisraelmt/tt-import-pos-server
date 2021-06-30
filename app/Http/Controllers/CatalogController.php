<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('catalog.catalog');
    }

    public function productExists(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'numeric|required|exists:products,id'
        ]);
        return response()->json([
            "message" => "ok"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showCatalog(Request $request)
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

        $products = DB::table(Product::getTableName() . " as p")
            ->leftJoin(ProductBrand::getTableName() . " as pb", "pb.id", "=", "p.product_brand_id")
            ->orderBy('p.name')
            ->whereRaw("p.status = '1'");

        if ($grid->getSearch() !== null) {
            $search = addslashes($grid->getSearch());
            $products = $products->whereRaw("concat(CONVERT(p.id,char), p.name, p.default_code, pb.name) like '%{$search}%'");
        }

        return response()->json([
            "total" => $products->count(),
            "data" => $products
                ->limit($grid->getLimit())
                ->offset($grid->getOffset())
                ->selectRaw("p.id, p.name, p.status, p.default_code, p.available_quantity > 0 as available, p.default_photo_url, pb.name as brand")
                ->get()
                ->toArray()
        ]);
    }


    public function show(Request $request, int $productId)
    {
        $product = Product::with(
            'productCategory',
            'productBrand',
            'productType',
            'productDepartment',
            'productPhotos')->findOrFail($productId);
        return response()->json([
            "total" => 1,
            "data" => $product
        ]);
    }
}
