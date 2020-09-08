<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{

    protected $table = 'product_history';

    protected $fillable = [
        'price', 
        'in_stock', 
        'product_id', 
        'stock_id', 
    ];

    protected $casts = [
    	'in_stock' => 'boolean'
    ];
}
