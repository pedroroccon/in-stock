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

    public function history()
    {
        return $this->hasMany(History::class);
    }

    public function track()
    {
        $this->stock->each->track(function($stock) {
            return $this->recordHistory($stock);
        });
    }

    public function inStock()
    {
        // You can use magic methods too: return $this->stock()->whereInStock(true)->exists();
    	return $this->stock()->where('in_stock', true)->exists();
    }

    public function recordHistory(Stock $stock)
    {
        // We can use model observers instead.
        $this->history()->create([
            'price' => $stock->price, 
            'in_stock' => $stock->in_stock, 
            'stock_id' => $stock->id, 
        ]);
    }

}
