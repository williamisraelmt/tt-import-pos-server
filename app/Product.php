<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

class Product extends Model
{
    use InsertOnDuplicateKey;

    protected $table = "products";

    protected $primaryKey = 'id';

    protected $casts = [
        'status' => 'boolean'
    ];

    public function productPhotos(){
        return $this->hasMany(ProductPhoto::class);
    }

    public function productCategory(){
        return $this->belongsTo(ProductCategory::class);
    }

    public function productBrand(){
        return $this->belongsTo(ProductBrand::class);
    }

    public function productType(){
        return $this->belongsTo(ProductType::class);
    }
}
