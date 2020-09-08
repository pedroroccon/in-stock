<?php

namespace Tests\Unit;

use App\Retailer;
use App\Stock;
use App\Clients\ClientException;
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
}
