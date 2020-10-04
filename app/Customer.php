<?php

namespace App;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

/**
 * App\Customer
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string $phone
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePhone($value)
 */
class Customer extends Model
{
    use InsertOnDuplicateKey;

    protected $table = "customers";

    protected $primaryKey = 'id';

    protected $appends = ['parsed_address'];

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }

    public function deliveryLeads(){
        return $this->hasMany(DeliveryLead::class);
    }

    public function getParsedAddressAttribute($value)
    {
        return explode("\n", $this->address) ;
    }
}
