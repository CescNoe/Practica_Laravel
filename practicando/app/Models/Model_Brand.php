<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Model_Brand extends Model
{
    use HasFactory;
    protected $table = 'model_brands';
    protected $fillable = ['name', 'brand_id', 'description'];
}
