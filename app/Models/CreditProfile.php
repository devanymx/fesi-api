<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'limit',
        'balance',
        'cutoff_date',
        'due_date',
        'status',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function payments()
    {
        return $this->hasMany(CreditPayment::class);
    }

}
