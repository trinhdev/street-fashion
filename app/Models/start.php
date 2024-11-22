<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class start extends Model
{
    protected $table = 'start';
    use HasFactory;
    protected $fillable = ['id_product', 'id_user', 'comment'];
    public function product()
    {
        return $this->belongsTo(Product::class,'id_product');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'id_user');
    }
}
