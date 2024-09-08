<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;
    protected $table = 'customer_addresses';
    protected $fillable = ['user_id', 'first_name', 'last_name', 'email', 'country_id', 'state_id','lga_id', 'city', 'zip', 'address','mobile','notes','appartment'];
}
