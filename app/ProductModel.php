<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

class ProductModel extends Model
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
