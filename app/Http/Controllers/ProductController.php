<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductBrand;
use App\ProductCategory;
use App\ProductPhoto;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
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
        return view('admin.product');
    }


    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showCatalog(Request $request) {

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
                ->selectRaw("p.id, p.name, p.default_code, p.available_quantity, p.default_photo_url, pb.name as brand")
                ->get()
                ->toArray()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAll(Request $request): \Illuminate\Http\JsonResponse
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
            ->orderBy('p.name');
        if ($grid->getSearch() !== null) {
            $search = addslashes($grid->getSearch());
            $products = $products->whereRaw("concat(
                CONVERT(p.id,char),
                p.name,
                p.default_code,
                pb.name,
                CONVERT(p.standard_price,char),
                CONVERT(p.list_price,char)
                ) like '%{$search}%'");
        }
        return response()->json([
            "total" => $products->count(),
            "data" => $products
                ->limit($grid->getLimit())
                ->offset($grid->getOffset())
                ->selectRaw("p.id, p.name, p.default_code, p.status, p.available_quantity, p.default_photo_url, pb.name as brand, p.standard_price, p.list_price")
                ->get()
                ->toArray()
        ]);
    }

    public function updateStatus(Request $request, int $productId)
    {
        $status = $request->all()['status'] ?? true;
        try {
            $product = Product::findOrFail($productId);
            $product->status = $status;
            $product->save();
            return response()->json([
                "message" => "ok"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function showPhotos(Request $request, int $productId)
    {
        $product = Product::findOrFail($productId);
        return response()->json([
            "data" => [
                "default" => $product->default_photo_url,
                "list" => ProductPhoto::whereProductId($productId)->get()
            ]
        ]);
    }

    public function uploadPhoto(Request $request, int $productId)
    {
        try {
            $product = Product::findOrFail($productId);

            $file = $request->file('product_photo');

            $file_name = md5(Crypt::encryptString(Carbon::now()->format('YMdHms') . $product->name)) . "." . $file->extension();

            Storage::putFileAs("public/product-photos/{$product->id}", $file, $file_name, "public");

            $product_photo = new ProductPhoto();

            $product_photo->url = $file_name;

            $product_photo->product_id = $product->id;

            $product_photo->save();

            if ($product->default_photo_url === null) {

                $product->default_photo_url = $file_name;

                $product->save();

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

    public function showPhoto(Request $request, string $productId, string $url)
    {
        $path = "storage/product-photos/{$productId}/{$url}";

        if (!File::exists($path)) {
            abort(404);
        }
        $file = File::get($path);
        $type = File::mimeType($path);
        $response = response()->make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }

    public function deletePhoto(Request $request, $productId, $productPhotoId)
    {
        $product = Product::findOrFail($productId);
        $product_photo = ProductPhoto::whereProductId($productId)->whereId($productPhotoId)->firstOrFail();
        try {
            DB::beginTransaction();
            File::delete("storage/product-photos/{$product->id}/{$product_photo->url}");
            if ($product_photo->url === $product->default_photo_url) {
                $product->default_photo_url = null;
                $product->save();
            }
            $product_photo->delete();
            DB::commit();
            return response()->json([
                "message" => "ok"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function markAsFavoritePhoto(Request $request, $productId, $productPhotoId)
    {
        $product = Product::findOrFail($productId);
        $product_photo = ProductPhoto::whereProductId($productId)->whereId($productPhotoId)->firstOrFail();
        try {
            $product->default_photo_url = $product_photo->url;
            $product->save();
            DB::commit();
            return response()->json([
                "message" => "ok"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
    }
}
