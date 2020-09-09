<?php

namespace App\Console\Commands;

use App\Product;
use Illuminate\Console\Command;

class TrackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instock:track';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Track all product stock';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get all products
        $products = Product::all();

       // Start a progress bar
       $this->output->progressStart($products->count());

        $products->each(function ($product) {
            // Loop through the products and increment progress bar
            $product->track();
            $this->output->progressAdvance();
        });

        // Finish the progress bar and show the results
        $this->showResults($products);
    }

    protected function showResults($products) : void
    {
        $this->output->progressFinish();

        $data = Product::leftJoin('stock', 'stock.product_id', '=', 'products.id')
            ->get($this->columns());

        $this->table(
            array_map('ucwords', $this->columns()), 
            $data
        );
    }

    protected function columns() : array
    {
        return ['name', 'price', 'url', 'in_stock'];
    }
}
