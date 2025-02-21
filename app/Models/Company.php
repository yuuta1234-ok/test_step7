<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Company extends Model
{

    protected $fillable = [
        'company_name',
    ];

    public function getCompany()
    {
        return DB::table('companies')
            ->select('id', 'company_name')
            ->orderBy("id", "ASC")  //並び順の指定(クエリの準備)
            ->get();                 //データを取得する
    }
}
