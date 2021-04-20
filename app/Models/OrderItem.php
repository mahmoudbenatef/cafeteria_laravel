<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\Product;

class OrderItem extends Model
{
    use HasFactory;
    protected $table = 'order_items';

//    public function products()
//    {
//        return $this->hasMany(Product::class);
//    }
}
