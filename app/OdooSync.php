<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OdooSync extends Model
{

    protected $casts = [
        'displayed' => 'boolean'
    ];
}
