<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

class ProductPhoto extends Model
{
    use InsertOnDuplicateKey;

    protected $table = "product_photos";

    protected $primaryKey = 'id';

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
