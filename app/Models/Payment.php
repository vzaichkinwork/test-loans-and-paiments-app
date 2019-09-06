<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'loan_number',
        'payment_number',
        'amount',
        'orig_amount',
        'orig_currency',
        'status',
        'imported',
    ];

    const STATUS_NOT_ASSIGNED = 0;
    const STATUS_PARTIALLY_ASSIGNED = 1;
    const STATUS_ASSIGNED = 2;

    const TEXT_STATUSES = [
        self::STATUS_NOT_ASSIGNED => 'not assigned',
        self::STATUS_PARTIALLY_ASSIGNED => 'partially assigned',
        self::STATUS_ASSIGNED => 'assigned',
    ];

    public function isNotAssigned() : bool
    {
        return $this->status === self::STATUS_NOT_ASSIGNED;
    }

    public function isPartiallyAssigned() : bool
    {
        return $this->status === self::STATUS_PARTIALLY_ASSIGNED;
    }

    public function isAssigned() : bool
    {
        return $this->status === self::STATUS_ASSIGNED;
    }

    /*
     * Better to make repository layer to work with entities & collections
     */
    public function updateStatusPartiallyAssigned() : bool
    {
        $this->status = self::STATUS_PARTIALLY_ASSIGNED;
        return $this->save();
    }

    public function updateStatusAssigned() : bool
    {
        $this->status = self::STATUS_ASSIGNED;
        return $this->save();
    }

    public function loan() : BelongsTo
    {
        return $this->belongsTo(Loan::class, 'loan_number', 'loan_number');
    }

    public function getStatusHumanAttribute() : string
    {
        return self::TEXT_STATUSES[$this->status];
    }
}
