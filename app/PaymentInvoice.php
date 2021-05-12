<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

class PaymentInvoice extends Model
{
    use InsertOnDuplicateKey;
}
