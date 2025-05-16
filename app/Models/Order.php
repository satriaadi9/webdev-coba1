<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'invoice_number',
        'user_id',
        'customer_name',
        'total_price',
        'status',
        'payment_url',
    ];
    //
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order_detail():HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}
