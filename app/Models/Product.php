<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = ['title', 'slug', 'description', 'price', 'is_featured', 'qty', 'compare_price', 'brand_category_id', 'category_id', 'sub_category_id', 'sku', 'barcode', 'qty_track', 'status'];

    public function product_images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
}
