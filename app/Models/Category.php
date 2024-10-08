<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    // public $timestamps = false;
    protected $fillable = ['name', 'slug', 'image', 'status'];

    public function sub_Category(){
        return $this->hasMany(SubCategory::class);
    }
}
