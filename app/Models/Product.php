<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'detail',
        'department_id',
        'category_id',
        'code',
        'description',
        'measurement',
        'price',
        'sale_price',
        'profit',
        'unit',
        'minimum',
        'maximum',
        'taxes',
        'image',
        'status',
        'dealer_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class);
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }
}
