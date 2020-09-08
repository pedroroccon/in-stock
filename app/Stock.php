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

    public function history()
    {
        return $this->hasMany(History::class);
    }

    public function track()
    {
       $status = $this->retailer
            ->client()
            ->checkAvailability($this);
        
        $this->update([
            'in_stock' => $status->available, 
            'price' => $status->price
        ]);

        $this->recordHistory();
    }

    private function recordHistory()
    {
        // We can use model observers instead.
        $this->history()->create([
            'price' => $this->price, 
            'in_stock' => $this->in_stock, 
            'product_id' => $this->product_id, 
        ]);
    }
}
