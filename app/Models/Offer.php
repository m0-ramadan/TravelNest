<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = ['name', 'image'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}