<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\DeliveryLead
 *
 * @property int $id
 * @property float $package_quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryLead newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryLead newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryLead query()
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryLead whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryLead whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryLead wherePackageQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryLead whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DeliveryLead extends Model
{
    public function deliveryLeadDetails(){
        return $this->hasMany(DeliveryLeadDetail::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
