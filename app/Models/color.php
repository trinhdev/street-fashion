<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class color extends Model
{
    protected $table = 'color';
    use HasFactory;
    public function product()
    {
        return $this->belongsTo(product::class, 'id_product');
    }
}