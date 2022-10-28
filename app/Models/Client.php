<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lastname',
        'email',
        'phone',
        'details',
    ];

    public function addresses(){
        return $this->hasMany(Address::class);
    }

    public function creditProfile(){
        return $this->hasOne(CreditProfile::class);
    }

    public function creditPayments(){
        return $this->hasManyThrough(CreditPayment::class, CreditProfile::class);
    }
}
