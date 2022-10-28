<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'credit_profile_id',
        'amount',
        'date',
        'status',
    ];

    public function creditProfile()
    {
        return $this->belongsTo(CreditProfile::class);
    }
}
