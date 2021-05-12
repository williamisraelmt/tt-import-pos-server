<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

class Payment extends Model
{
    use InsertOnDuplicateKey;

    protected $table = "payments";

    protected $primaryKey = 'id';

    public function invoices(){
        return $this->belongsToMany(Invoice::class);
    }
}
