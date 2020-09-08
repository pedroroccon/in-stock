<?php

namespace Tests\Unit;

use App\History;
use App\Stock;
use Tests\TestCase;
use RetailerWithProductSeeder;
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

        $this->assertEquals(0, History::count());

        Http::fake(function() {
            return [
                'salePrice' => 99, 
                'onlineAvailability' => true
            ];
        });

        // If I track that stock
        $stock = tap(Stock::first())->track();

        // a new history entry should be created
        $this->assertEquals(1, History::count());

        $history = History::first();

        $this->assertEquals($stock->price, $history->price);
        $this->assertEquals($stock->in_stock, $history->in_stock);
        $this->assertEquals($stock->product_id, $history->product_id);
        $this->assertEquals($stock->id, $history->stock_id);

    }
}
