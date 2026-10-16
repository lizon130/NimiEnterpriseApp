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
            $model->id = substr(uniqid(), 0, 13) . '-ordr-' . random_int(10000000000000000, 99999999999999999);

            // Generate Invoice Number if not set
            if (!$model->invoice_no) {
                $model->invoice_no = self::generateInvoiceNumber($model->user_id);
            }
        });
    }

    /**
     * Business day windows (Bangladesh time, UTC+6):
     *   Window 1: 00:00 (midnight) to 13:59  -> all orders share one invoice
     *   Window 2: 14:00 (2 PM) to 23:59      -> separate invoice
     * Orders of the same user inside the same window reuse one invoice number.
     */
    public static function generateInvoiceNumber($userId = null)
    {
        $nowBd = now()->timezone('Asia/Dhaka');

        // Window start in BD time: 00:00 or 14:00
        $windowStartBd = ($nowBd->format('H') < 14)
            ? $nowBd->copy()->startOfDay()
            : $nowBd->copy()->setTime(14, 0, 0);

        // Convert back to app timezone for comparing with created_at
        $windowStart = $windowStartBd->timezone(config('app.timezone'));

        // If this user already ordered in the current window, reuse that invoice
        if ($userId) {
            $existing = self::where('user_id', $userId)
                ->where('created_at', '>=', $windowStart)
                ->orderByDesc('created_at')
                ->first();

            if ($existing && $existing->invoice_no) {
                return $existing->invoice_no;
            }
        }

        // Otherwise generate the next sequential invoice number of the year
        $year = date('Y');
        $lastOrder = self::whereYear('created_at', $year)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastOrder && $lastOrder->invoice_no) {
            // Extract the number from invoice_no
            preg_match('/\/(\d+)$/', $lastOrder->invoice_no, $matches);
            $lastNumber = isset($matches[1]) ? intval($matches[1]) : 0;
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return 'Nimi-' . $year . '/' . $newNumber;
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The partner's company record (company name, phone etc.).
     */
    public function partnerCompany()
    {
        return $this->hasOne(Company::class, 'user_id', 'user_id');
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * All orders sharing the same invoice number (same user + same business window).
     */
    public function sameInvoice()
    {
        return $this->hasMany(Order::class, 'invoice_no', 'invoice_no');
    }
}