<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\DeliveryLeadDetail
 *
 * @property int $id
 * @property int $delivery_lead_id
 * @property int $invoice_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryLeadDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryLeadDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryLeadDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryLeadDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryLeadDetail whereDeliveryLeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryLeadDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryLeadDetail whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliveryLeadDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DeliveryLeadDetail extends Model
{
    public function deliveryLead(){
        return $this->belongsTo(DeliveryLead::class);
    }

    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }
}
