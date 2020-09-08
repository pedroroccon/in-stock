<?php

namespace Tests\Feature;

use App\Product;
use App\Retailer;
use App\Stock;
use App\Clients\StockStatus;
use Facades\App\Clients\ClientFactory;
use RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TrackCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_tracks_product_stock()
    {
        // Given
        // I have a product with stock.
        $this->seed(RetailerWithProductSeeder::class);
        $this->assertFalse(Product::first()->inStock());

        // When
        // I trigger the php artisan track command.
        // And assuming the stock is available.

        ClientFactory::shouldReceive('make->checkAvailability')->andReturn(new StockStatus($available = true, $price = 29900));

        $this->artisan('instock:track')
            ->expectsOutput('Completed!');

        // Then
        // The stock details should be refreshed.
        $this->assertTrue(Product::first()->inStock());

    }
}
