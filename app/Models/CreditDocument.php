<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditDocument extends Model
{
    use HasFactory;

    protected $fillable = [
      'credit_profile_id',
      'name',
      'type'
    ];

    public function creditProfile()
    {
      return $this->belongsTo(CreditProfile::class);
    }
}
