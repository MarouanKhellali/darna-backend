<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;
    protected $fillable = ['type', 'accepted', 'seller_id', 'bio', 'phone', 'city', 'address','rating'];

    public function user()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'seller_id');
    }
}
