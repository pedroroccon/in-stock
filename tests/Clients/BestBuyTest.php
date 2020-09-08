<?php

namespace Tests;

use App\Stock;
use App\Clients\BestBuy;
use Tests\TestCase;
use RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Exception;

/**
 * @group api
 */
class BestBuyTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function it_tracks_a_product()
	{
		// Given I have a product
		$this->seed(RetailerWithProductSeeder::class);

		// with a stock at BestBuy
		$stock = tap(Stock::first())->update([
			'sku' => '6364253', 
			'url' => 'https://www.bestbuy.com/site/nintendo-switch-32gb-console-neon-red-neon-blue-joy-con/6364255.p?skuId=6364255'
		]); // Nintendo Switch SKU

		// If i use the BestBuy client to track that stock/sku
		// it should return the appropriate StockStatus.
		try {
			(new BestBuy())->checkAvailability($stock);
		} catch (Exception $e) {
			$this->fail('Failed to track the BestBuy API properly. ' . $e->getMessage());
		}

		// Using a try/catch loop we can track if 
		// something went wrong. If we reach at this 
		// point, it means that the test passed successfully! 
		$this->assertTrue(true);
	}

	/** @test */
	function it_creates_the_proper_stock_status_response()
	{
		Http::fake(function() {
			return [
				'salePrice' => 299.99, 
				'onlineAvailability' => true, 
			];
		});

		$stockStatus = (new BestBuy())->checkAvailability(new Stock());

		$this->assertEquals(29999, $stockStatus->price);
		$this->assertTrue($stockStatus->available);
	}
}