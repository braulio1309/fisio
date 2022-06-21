<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPaymentWebpay extends Model
{
    protected $table = "detail_payment_webpays";
    protected $fillable = [
        'payment_webpay_id',
        'product_id',
        'quantity',
        'observations'
    ];

    public function paymentWebpay()
    {
        return $this->belongsTo(PaymentWebpay::class, 'payment_webpay_id');
    }
}
