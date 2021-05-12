<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

class DebtCollector extends Model
{
    use InsertOnDuplicateKey;

    protected $table = "debt_collectors";

    protected $primaryKey = 'id';

    protected $casts = [
        'status' => 'boolean'
    ];

    public function customers(){
        return $this->hasMany(Customer::class);
    }
}
