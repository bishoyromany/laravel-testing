<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\CurrencyService;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'price'
    ];

    public function getPriceEurAttribute(){
        return CurrencyService::convert($this->price, 'usd', 'eur');
    }
}
