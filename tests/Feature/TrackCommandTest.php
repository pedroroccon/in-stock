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

        $this->artisan('instock:track')
            ->expectsOutput('Completed!');

        // Then
        // The stock details should be refreshed.
        $this->assertTrue(Product::first()->inStock());

    }

    /** @test */
    function it_notifies_the_user_when_the_stock_is_now_available()
    {
        
        // Given I have a user
        // And also a product is out of stock
        $this->mockClientRequest();

        // If I update the stock and now it's available
        $this->artisan('instock:track');

        // then the user should be notified.
        Notification::assertSentTo(User::first(), ImportantStockUpdate::class);
    }

    /** @test */
    function it_does_not_notifies_the_user_when_the_stock_remains_unavailable()
    {
        // Given I have a user
        // And also a product is out of stock
        $this->mockClientRequest(false);

        // If I update the stock and now it still unavailable
        $this->artisan('instock:track');

        // then the user should not be notified
        Notification::assertNothingSent();
    }
}
