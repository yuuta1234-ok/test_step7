<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sale extends Model
{
    protected $fillable = ['product_id'];

    public function getSale()
    {
        return DB::table('sales')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->select('product_id')
            ->get();
    }
}
