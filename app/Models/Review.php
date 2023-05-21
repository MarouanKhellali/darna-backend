<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = ['seller_id','client_id', 'review', 'rating'];

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
