<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'old_price',
        'price',
        'on_sale',
        'images',
        'item_state',
        'category_id',
        'stock',
        'product_avg',
        'product_count',
        'sell_count',
    ];

    // Esto convierte los datos automáticamente al formato correcto
    protected $casts = [
        'on_sale' => 'boolean',
        'images' => 'array', // Transforma el JSON de la BD a un array en PHP/Vue
        'old_price' => 'float',
        'price' => 'float',
        'product_avg' => 'float',
    ];

    // Relación: Un artículo pertenece a una categoría
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}