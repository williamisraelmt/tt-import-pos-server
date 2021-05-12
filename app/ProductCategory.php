<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

class ProductCategory extends Model
{
    use InsertOnDuplicateKey;

    protected $table = "product_categories";

    protected $primaryKey = 'id';

    protected $casts = [
        'status' => 'boolean'
    ];

    public function products(){
        return $this->hasMany(Product::class);
    }
}
