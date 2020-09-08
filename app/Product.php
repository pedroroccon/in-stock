<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
    	'name', 
    ];

    public function stock()
    {
    	return $this->hasMany(Stock::class);
    }

    public function track()
    {
        $this->stock->each->track();
    }

    public function inStock()
    {
        // You can use magic methods too: return $this->stock()->whereInStock(true)->exists();
    	return $this->stock()->where('in_stock', true)->exists();
    }


}
