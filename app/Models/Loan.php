<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    protected $fillable = [
        'loan_number',
        'amount',
        'status',
        'imported',
    ];

    const STATUS_ACTIVE = 0;
    const STATUS_PAID = 1;

    const TEXT_STATUSES = [
        self::STATUS_ACTIVE => 'active',
        self::STATUS_PAID => 'paid',
    ];

    public function isActive() : bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isPaid() : bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function payments() : HasMany
    {
        return $this->hasMany(Payment::class, 'loan_number', 'loan_number');
    }

    /*
     * Better to make repository layer to work with entities & collections
     */
    public function updateStatusActive(bool $resetImport = true) : bool
    {
        $this->status = self::STATUS_ACTIVE;
        if ($resetImport) {
            $this->imported = false;
        }
        return $this->save();
    }

    public function updateStatusPaid(bool $resetImport = true) : bool
    {
        $this->status = self::STATUS_PAID;
        if ($resetImport) {
            $this->imported = false;
        }
        return $this->save();
    }

    public function resetImported() : bool
    {
        $this->imported = false;
        return $this->save();
    }

    public function calcStatus() : void
    {
        $total = $this->payments()->sum('amount');

        // Have to compare this way because of the float
        if (abs($this->amount - $total) < 0.001) {
            $this->updateStatusPaid();
        }
        elseif ($this->amount < $total) {
            // Case of overpayments.
            // Need some new status
            // & admin warning
            // or something like that.
        }
        else {
            $this->resetImported();
        }
    }

    public function getStatusHumanAttribute() : string
    {
        return self::TEXT_STATUSES[$this->status];
    }
}
