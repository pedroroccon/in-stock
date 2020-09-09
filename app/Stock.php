<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Clients\BestBuy;
use App\Clients\Target;

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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function track($callback = null)
    {
       $status = $this->retailer
            ->client()
            ->checkAvailability($this);
        
        $this->update([
            'in_stock' => $status->available, 
            'price' => $status->price
        ]);

        // If there is a callback, then we should
        // pass it and do whatever we want.
        $callback && $callback($this);

        // Instead of using the callback, we 
        // could use events/listeners instead. But for now 
        // let's keep it simple.
    }


}
