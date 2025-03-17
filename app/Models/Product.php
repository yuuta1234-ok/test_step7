<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'company_id',
        'product_name',
        'price',
        'stock',
        'comment',
        'img_path',
    ];

    // 🔹 会社とのリレーションを定義
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
