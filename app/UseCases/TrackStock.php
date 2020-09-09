<?php

namespace App\UseCases;

use App\User;
use App\Stock;
use App\History;
use App\Clients\StockStatus;
use App\Notifications\ImportantStockUpdate;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;

class TrackStock implements ShouldQueue
{

    use Dispatchable, SerializesModels;

    /**
     * Receives the Stock 
     * instance.
     * 
     * @var \App\Stock
     */
    protected $stock;
    
    /**
     * Receives the StockStatus
     * instance.
     * 
     * @var \App\Clients\StockStatus
     */
    protected $status;

    public function __construct(Stock $stock)
    {
        $this->stock = $stock;
    }

    public function handle()
    {
        $this->checkAvailability();
        $this->notifyUser();
        $this->refreshStock();
        $this->recordToHistory();
    }

    protected function checkAvailability()
    {
       $this->status = $this->stock->retailer
            ->client()
            ->checkAvailability($this->stock);
    }

    protected function notifyUser()
    {
        if ($this->isNowInStock()) {
            // To avoid complexibility, we will not fire an event here.
            User::first()->notify(new ImportantStockUpdate($this->stock));
        }
    }

    protected function refreshStock()
    {
        $this->stock->update([
            'in_stock' => $this->status->available, 
            'price' => $this->status->price
        ]);
    }

    protected function recordToHistory()
    {
        History::create([
            'price' => $this->stock->price, 
            'in_stock' => $this->stock->in_stock, 
            'product_id' => $this->stock->product_id, 
            'stock_id' => $this->stock->id, 
        ]);
    }

    protected function isNowInStock()
    {
        return ! $this->stock->in_stock && $this->status->available;
    }

}