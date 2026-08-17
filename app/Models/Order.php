<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'invoice_no',
        'date',
        'note',
        'total_price',
        'billing_information',
        'status',
        'payment_status',
        'payment_method',
        'transaction_id',
        'ancestor_id'
    ];

    protected $casts = [
        'date' => 'datetime',
        'total_price' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = substr(uniqid(), 0, 13).'-ordr-'.random_int(10000000000000000, 99999999999999999);

            // Generate Invoice Number if not set
            if (!$model->invoice_no) {
                $model->invoice_no = self::generateInvoiceNumber();
            }
        });
    }

    public static function generateInvoiceNumber()
    {
        $year = date('Y');
        $lastOrder = self::whereYear('created_at', $year)
                        ->orderBy('created_at', 'desc')
                        ->first();

        if ($lastOrder && $lastOrder->invoice_no) {
            // Extract the number from invoice_no
            preg_match('/\/(\d{3})$/', $lastOrder->invoice_no, $matches);
            $lastNumber = isset($matches[1]) ? intval($matches[1]) : 0;
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return 'Nimi-' . $year . '/' . $newNumber;
    }

    public function company(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details(){
        return $this->hasMany(OrderDetail::class);
    }
}
