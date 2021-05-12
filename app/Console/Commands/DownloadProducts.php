<?php

namespace App\Console\Commands;

use App\Console\Commands\Models\OdooProductBrand;
use App\Console\Commands\Models\OdooProduct;
use App\Console\Commands\Traits\OdooDownload;
use App\Product;
use App\ProductBrand;
use App\ProductCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DownloadProducts extends Command
{
    use OdooDownload;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will download products';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->table = "product.template";
        $this->fields = [
            'id',
            'name',
            'categ_id',
            'default_code',
            'qty_available',
            'lst_price',
            'standard_price',
            'list_price'
        ];
        $this->where = [];
        $this->connection = new OdooConnectionModel(
            config("odoo.user"),
            config("odoo.password"),
            config("odoo.database"),
            config("odoo.host")
        );
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Starting to download products");
        try {
            DB::beginTransaction();
            $this->download()
                ->map(function ($record) {
                    if ($record['categ_id'] !== null && !empty($record['categ_id'])){
                        $brand = new OdooProductBrand($record['categ_id'][0], $record['categ_id'][1]);
                    }
                    return new OdooProduct(
                        $record["id"],
                        $record["name"],
                        $brand ?? null,
                        $record['default_code'],
                        $record['qty_available'],
                        $record['lst_price'],
                        $record['list_price'],
                        $record['standard_price']
                    );
                })
                ->chunk(1000)
                ->each(function(Collection $records){
                    /**
                     * Adding categories
                     */
                    ProductBrand::insertOnDuplicateKey(
                        ($records
                            ->filter(function(OdooProduct $product){
                                return $product->getBrand() !== null;
                            })
                            ->map(function(OdooProduct $product){
                                return $product->getBrand();
                            })
                        )->unique(function(OdooProductBrand $brand){
                            return $brand->getId();
                        })->map(function (OdooProductBrand $brand) {
                          return $brand->toStoreAsArray();
                        })->values()->toArray()
                    );
                })
                ->each(function (Collection $records) {
                    $this->info("Inserting/updating list {$records->count()} records to the database...");
                    Product::insertOnDuplicateKey(
                        $records->map(function (OdooProduct $product) {
                            return $product->toStoreAsArray();
                        })->values()->toArray()
                    );
                });
            DB::commit();
            Log::info('Downloaded products');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
            $this->error($e->getTraceAsString());
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }
}
