<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'image',
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? url('storage/' . $value) : null,
        );
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}