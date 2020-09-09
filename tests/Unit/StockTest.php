<?php

namespace Tests\Unit;

use Mockery;
use App\Retailer;
use App\Stock;
use Facades\App\Clients\ClientFactory;
use App\Clients\Client;
use App\Clients\ClientException;
use App\Clients\StockStatus;
use Tests\TestCase;
use RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StockTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    function it_throws_an_exception_if_a_client_is_not_found_when_tracking()
    {
        // Given i have a retailer with stock
        $this->seed(RetailerWithProductSeeder::class);
        Retailer::first()->update(['name' => 'Foo Retailer']);

        $this->expectException(ClientException::class);

        // If I track that stock, and if the retailer 
        // doesn't have a client class 
        // Then an exception should be thrown.
        Stock::first()->track();
    }

    /** @test */
    function it_updates_local_stock_status_after_being_tracked()
    {
        $this->seed(RetailerWithProductSeeder::class);

        // At this point we have 
        // the ClientFactory to determine the appropriate Client.
        // to use the checkAvailability() method.
        
        // We are using Mockery to mock the ClientFactory and expect a call 
        // to make() method. Then, we get the result of the call, and 
        // mock the return again to call the checkAvailability() method.

        // You can add variables to the arguments, so after 6 months 
        // you'll remember what each argument is :)
        $this->mockClientRequest($available = true, $price = 9900);

        $stock = tap(Stock::first())->track();

        $this->assertTrue($stock->refresh()->in_stock);
        $this->assertEquals(9900, $stock->price);
    }
}
