<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WholesaleCalculation extends Model
{
    use HasFactory;

    protected $table = 'WholesaleCalculation';

    protected $fillable = [
        'date',
        'purchase_amount',
        'sale_amount',
    ];

    protected $casts = [
        'date' => 'date',
        'purchase_amount' => 'decimal:2',
        'sale_amount' => 'decimal:2',
    ];
}
