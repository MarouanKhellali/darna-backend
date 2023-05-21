<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyReview extends Model
{
    use HasFactory;
    protected $fillable = ['property_id','client_id', 'review', 'rating'];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
