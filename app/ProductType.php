<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

class ProductType extends Model
{
    use InsertOnDuplicateKey;

    protected $table = "product_types";

    protected $primaryKey = 'id';

    protected $casts = [
        'status' => 'boolean'
    ];

    public function products(){
        return $this->hasMany(Product::class);
    }
}
