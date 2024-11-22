<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_meta extends Model
{
    protected $table = 'product_meta';
    protected $fillable = [
        'id_product',
        'quantity',
        'sold',
        'price',
        'price_sale',
        
    ]; 
    use HasFactory;
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product'); // Liên kết đến bảng product
    }
    public function Color()
    {
        return $this->belongsTo(Color::class, 'id_product_meta ');
    }

    public function Size()
    {
        return $this->belongsTo(Size::class, 'id_product_meta ');
    }
}