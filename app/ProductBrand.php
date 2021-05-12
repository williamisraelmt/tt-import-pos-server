<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

class ProductBrand extends Model
{
    use InsertOnDuplicateKey;

    protected $table = "product_brands";

    protected $primaryKey = 'id';

    protected $casts = [
        'status' => 'boolean'
    ];

    public function products(){
        return $this->hasMany(Product::class);
    }
}
