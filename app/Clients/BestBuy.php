<?php

namespace App\Clients;

use App\Stock;
use Illuminate\Support\Facades\Http;

class BestBuy implements Client {

	public function checkAvailability(Stock $stock) : StockStatus
	{
		$results =  Http::get($this->endpoint($stock->sku))->json();				
		return new StockStatus(
			$results['onlineAvailability'], 
			(int) $results['salePrice'] * 100 // BestBuy displays in dollars, but we store in cents.
		);
	}

	public function endpoint($sku) : string
	{
		$apiKey = config('services.clients.bestBuy.key');
		return "https://api.bestbuy.com/v1/products/{$sku}.json?apiKey={$apiKey}";
	}

}