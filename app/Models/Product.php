<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public static function getProduct()
    {
        // クエリビルダを使用して products テーブルと companies テーブルを結合
        $query = DB::table('products')
            ->join('companies', 'products.company_id', '=', 'companies.id')
            ->select('products.*', 'companies.company_name');

        return $query->get();
    }
}
