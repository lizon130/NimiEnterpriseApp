<?php
// app/Models/ProductStock.php

namespace App\Models;

use App\Models\Product;
use App\Models\StockHistory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    protected $table = 'product_stocks';

    protected $fillable = ['product_id', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function history()
    {
        return $this->hasMany(StockHistory::class, 'product_id', 'product_id');
    }

    // Add stock (in)
    public function addStock($quantity, $reason = 'purchase', $referenceId = null, $notes = null)
    {
        $this->quantity += $quantity;
        $this->save();

        // Log history
        StockHistory::create([
            'product_id' => $this->product_id,
            'type' => 'in',
            'quantity' => $quantity,
            'reason' => $reason,
            'reference_id' => $referenceId,
            'notes' => $notes,
            'created_by' => auth()->id()
        ]);

        return true;
    }

    // Remove stock (out)
    public function removeStock($quantity, $reason = 'sale', $referenceId = null, $notes = null)
    {
        if ($this->quantity < $quantity) {
            throw new \Exception("Insufficient stock! Available: {$this->quantity}, Requested: {$quantity}");
        }

        $this->quantity -= $quantity;
        $this->save();

        // Log history
        StockHistory::create([
            'product_id' => $this->product_id,
            'type' => 'out',
            'quantity' => $quantity,
            'reason' => $reason,
            'reference_id' => $referenceId,
            'notes' => $notes,
            'created_by' => auth()->id()
        ]);

        return true;
    }

    // Check if in stock
    public function hasStock($quantity = 1)
    {
        return $this->quantity >= $quantity;
    }
}
