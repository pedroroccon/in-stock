<?php

namespace Tests\Feature;

use RetailerWithProductSeeder;
use App\Product;
use App\Retailer;
use App\Stock;
use App\User;
use App\Clients\StockStatus;
use App\Notifications\ImportantStockUpdate;
use Facades\App\Clients\ClientFactory;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TrackCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     * 
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();

        Notification::fake();

        $this->seed(RetailerWithProductSeeder::class);
    }

    /** @test */
    function it_tracks_product_stock()
    {
        // Given
        // I have a product with stock.
        $this->assertFalse(Product::first()->inStock());

        // When
        // I trigger the php artisan track command.
        // And assuming the stock is available.
        $this->mockClientRequest();

        $this->artisan('instock:track');

        // Then
        // The stock details should be refreshed.
        $this->assertTrue(Product::first()->inStock());

    }
}
