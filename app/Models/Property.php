<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = ['property_id','property_seller_id', 'seller_id', 'title','image', 'propertytype', 'actiontype', 'price', 'city', 'area', 'bedrooms', 'address', 'oneStar', 'twoStars', 'threeStars', 'fourStars', 'fiveStars', 'rating'];

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'property_id');
    }
}
