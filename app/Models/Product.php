<?php

namespace App\Models;

use App\Traits\HandleImageTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, HandleImageTrait;
    protected $fillable = [
        'name',
        'description',
        'sale',
        'price'
    ];

    public function details()
    {
        return $this->hasMany(ProductDetail::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function getBy($categoryId)
    {
        return $this->whereHas('categories', fn ($q) => $q->where('category_id', $categoryId))->paginate(8);
    }

    public function salePrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['sale']
                ? $this->attributes['price'] - ($this->attributes['sale'] * 0.01  * $this->attributes['price'])
                : 0
        );
    }
}
