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
     * Business day window (Bangladesh time, UTC+6):
     *   Window: 14:00 (2 PM) to next day 13:59 (2 PM to 2 PM rolling window)
     * All orders of the same user inside the same 2PM-to-2PM window share one invoice.
     *
     * A DB-level lock (lockForUpdate) is used when generating a new sequential number
     * so that two users placing their very first order at the same instant cannot
     * receive the same invoice number.
     */
    public static function generateInvoiceNumber($userId = null)
    {
        $nowBd = now()->timezone('Asia/Dhaka');

        // Window start in BD time: today 14:00 if at/after 2 PM, else yesterday 14:00
        $windowStartBd = ($nowBd->format('H') < 14)
            ? $nowBd->copy()->subDay()->setTime(14, 0, 0)
            : $nowBd->copy()->setTime(14, 0, 0);

        // Convert back to app timezone for comparing with created_at
        $windowStart = $windowStartBd->timezone(config('app.timezone'));

        // If this user already ordered in the current window, reuse that invoice.
        // Scope strictly to this user so two different users never share an invoice.
        if ($userId) {
            $existing = self::where('user_id', $userId)
                ->where('created_at', '>=', $windowStart)
                ->orderByDesc('created_at')
                ->first();

            if ($existing && $existing->invoice_no) {
                return $existing->invoice_no;
            }
        }

        // Otherwise generate the next sequential invoice number of the year.
        // Lock the row so concurrent inserts cannot read the same "last" number and
        // produce a duplicate invoice number for two different users.
        $year = date('Y');
        $lastOrder = self::whereYear('created_at', $year)
            ->orderByDesc('created_at')
            ->lockForUpdate()
            ->first();

        if ($lastOrder && $lastOrder->invoice_no) {
            // Extract the trailing number from e.g. "Nimi-2026/013"
            preg_match('/\/(\d+)$/', $lastOrder->invoice_no, $matches);
            $lastNumber = isset($matches[1]) ? intval($matches[1]) : 0;
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
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
     * All orders sharing the same invoice number AND same user (same business window).
     * Scoping by user_id prevents a collision from leaking another user's orders.
     */
    public function sameInvoice()
    {
        return $this->hasMany(Order::class, 'invoice_no', 'invoice_no')
            ->where('user_id', $this->user_id);
    }
}