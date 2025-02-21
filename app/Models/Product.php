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

    public static function getProduct($search = null, $company_id = null)
    {
        // クエリビルダを使用して products テーブルと companies テーブルを結合
        $query = DB::table('products')
            ->join('companies', 'products.company_id', '=', 'companies.id')
            ->select(
                'products.id',
                'products.product_name',
                'products.price',
                'products.stock',
                'products.comment',
                'products.img_path',
                'companies.company_name'
            );

        // 検索条件を適用
        if (!empty($search)) {
            $query->where('products.product_name', 'LIKE', "%{$search}%");
        }

        if (!empty($company_id)) {
            $query->where('products.company_id', $company_id);
        }

        return $query->paginate(10); // 10件ずつページネーション
    }
}
