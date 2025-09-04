<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    use HasFactory;

    const TYPE_IN = 'in';
    const TYPE_OUT = 'out';

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'unit_cost',
        'unit_price',
        'notes',
        'discount',
        'is_closed',
        'closed_at',
    ];

    protected $casts = [
        'unit_cost'   => 'decimal:2',
        'unit_price'  => 'decimal:2',
        'quantity'    => 'integer',
        'is_closed'   => 'boolean',
        'closed_at'   => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
