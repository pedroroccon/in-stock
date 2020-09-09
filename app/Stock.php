<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Clients\BestBuy;
use App\Clients\Target;
use App\Events\NowInStock;
use App\UseCases\TrackStock;

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

    public function track()
    {
        // We are using UseCases here, but we 
        // can handle that like Laravel's jobs.
        TrackStock::dispatch($this);
    }


}
