<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'due_date',
        'user_id',
        'amount',
        'vat',
        'is_vat_inclusive'
    ];

    protected $casts = [
        'status' => TransactionStatus::class,
    ];

    static function createTransaction($request)
    {
        $transaction = Transaction::create($request);

        $transaction->updateStatus();

        return $transaction;
    }

    public function updateStatus()
    {
        $status = ($this->payments()->sum('amount') >= $this->amount)
            ? TransactionStatus::Paid
            : (now()->gt($this->due_date) ? TransactionStatus::Overdue : TransactionStatus::Outstanding);

        $this->update([ 'status' => $status ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
