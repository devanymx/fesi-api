<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'street',
        'number',
        'city',
        'state',
        'country',
        'zip_code',
        'phone',
        'client_id',
    ];

    public function client(){
        return $this->belongsTo(Client::class);
    }
}
