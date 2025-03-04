<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'product_name' => 'required|string|max:255',
            'company_id'   => 'required|exists:companies,id',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'comment'      => 'nullable|string|max:500',
            'img_path'     => 'nullable|file|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'product_name.required' => '商品名は必須です。',
            'company_id.required'   => 'メーカーを選択してください。',
            'company_id.exists'     => '選択されたメーカーは存在しません。',
            'price.required'        => '価格は必須です。',
            'price.numeric'         => '価格は数値で入力してください。',
            'stock.required'        => '在庫数は必須です。',
            'stock.integer'         => '在庫数は整数で入力してください。',
            'comment.max'           => 'コメントは500文字以内で入力してください。',
            'img_path.image'        => 'アップロードできるのは画像ファイルのみです。',
            'img_path.max'          => '画像のサイズは2MB以下にしてください。',
        ];
    }
}
