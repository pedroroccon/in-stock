<?php

namespace App\Listeners;

use App\User;
use App\Events\NowInStock;
use App\Notifications\ImportantStockUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStockUpdateNotification
{

    /**
     * Handle the event.
     *
     * @param  NowInStock  $event
     * @return void
     */
    public function handle(NowInStock $event)
    {
        // We are using the first user just 
        // for test, but in a real application 
        // we should get the users.
        User::first()->notify(new ImportantStockUpdate($event->stock));
    }
}
