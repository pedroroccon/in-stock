<?php

namespace Tests\Unit;

use App\Product;
use App\Stock;
use Tests\TestCase;
use RetailerWithProductSeeder;
use Facades\App\Clients\ClientFactory;
use App\Clients\StockStatus;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductHistoryTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    function it_records_history_each_time_stock_is_tracked()
    {
        // Given I have stock at a retailer
        $this->seed(RetailerWithProductSeeder::class);

        ClientFactory::shouldReceive('make->checkAvailability')
            ->andReturn(new StockStatus($available = true, $price = 9990));

        $product = tap(Product::first(), function ($product) {
            $this->assertCount(0, $product->history);

            // If I track that stock
            $product->track();

            // a new history entry should be created
            $this->assertCount(1, $product->refresh()->history);
        });

        $history = $product->history->first();

        $this->assertEquals($price, $history->price);
        $this->assertEquals($available, $history->in_stock);
        $this->assertEquals($product->id, $history->product_id);
        $this->assertEquals($product->stock[0]->id, $history->stock_id);

    }
}
