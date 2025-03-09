<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_name' => 'required|string|max:255',
            'company_id'   => 'required|exists:companies,id',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'comment'      => 'nullable|string|max:500',
            'img_path'     => 'required|file|image|max:2048', // 新規登録は画像必須
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => '商品名を入力してください。',
            'company_id.required'   => 'メーカーを選択してください。',
            'company_id.exists'     => '選択されたメーカーは存在しません。',
            'price.required'        => '価格を入力してください。',
            'price.numeric'         => '価格は数値で入力してください。',
            'stock.required'        => '在庫数を入力してください。',
            'stock.integer'         => '在庫数は整数で入力してください。',
            'comment.max'           => 'コメントは500文字以内で入力してください。',
            'img_path.required'     => '画像をアップロードしてください。',
            'img_path.image'        => 'アップロードできるのは画像ファイルのみです。',
            'img_path.max'          => '画像のサイズは2MB以下にしてください。',
        ];
    }
}
