<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'description', 'images', 'is_active', 'is_featured', 'in_stock', 'on_sale', 'price', 'brand_id', 'category_id'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    protected $casts=[
        'images'=>'array',
    ];

    public function category(){
        return $this->belongsTo(Category::class);        
    }
     public function brand(){
        return $this->belongsTo(Brand::class);
        
    }
     public function orderItems(){
        return $this->belongsTo(OrderItem::class);
        
    }
}
