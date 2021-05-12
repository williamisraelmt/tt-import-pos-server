<?php

namespace App;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

/**
 * App\Invoice
 *
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string $number
 * @property string $reference
 * @property float $amount_total
 * @property string $create_date
 * @property string $date_invoice
 * @property string $date_due
 * @property string $date
 * @property int $customer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereAmountTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereCreateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereDateDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereDateInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Invoice extends Model
{
    use InsertOnDuplicateKey;

    protected $table = "invoices";

    protected $primaryKey = 'id';

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function payments(){
        return $this->belongsToMany(Payment::class);
    }
}
