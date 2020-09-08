<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Stock extends Model
{

	protected $table = 'stock';

    protected $fillable = [
    	'price', 
		'url', 
		'sku', 
		'in_stock', 
    ];

    protected $casts = [
    	'in_stock' => 'boolean'
    ];

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }

    public function track()
    {
        // We need different strategies to get the products 
        // price and availability from each retailers. Later 
        // we need to extract this to a Strategy Pattern, 
        // avoiding the use of "ifs" in this class.
        if ($this->retailer->name === 'Best Buy') {

            // Hit an API endpoint for the associated retailer
            // Fetch the up-to-date details for the item.
            $results = Http::get('http://foo.test')->json();

            // And then, refresh the current stock recrod.
            
            // Given we are could be working with multiple APIs, 
            // we should normalize the returns later to avoid 
            // inconsistencies in database.
            $this->update([
                'in_stock' => $results['available'], 
                'price' => $results['price']
            ]);
        }

    }
}
