<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\TestRequest;

class TestController extends Controller
{

    // 商品一覧画面の表示と検索処理
    public function index(Request $request)
    {
        $search = $request->input('product_name');
        $company_id = $request->input('company_id');

        // Product モデルのEloquentを使ってデータ取得
        $products = Product::with('company') // companyリレーションを使って関連データも取得
            ->when($search, function ($query) use ($search) {
                return $query->where('product_name', 'like', "%{$search}%");
            })
            ->when($company_id, function ($query) use ($company_id) {
                return $query->where('company_id', $company_id);
            })
            ->paginate(10); // ページネーションを利用

        $companies = Company::all(['id', 'company_name']); // 会社情報の取得

        return view('homeProduct', compact('products', 'companies'));
    }


    // 新規登録画面の表示
    public function create()
    {
        $company = new Company();
        $companies = $company->getCompany();

        return view('createProduct', compact('companies'));
    }


    // 商品新規登録処理
    public function store(TestRequest $request, Product $product)
    {
        try {
            // 基本情報の保存
            $product->product_name = $request->input('product_name');
            $product->company_id   = $request->input('company_id');
            $product->price        = $request->input('price');
            $product->stock        = $request->input('stock');
            $product->comment      = $request->input('comment');

            // 画像アップロード処理
            if ($request->hasFile('img_path')) {
                $file = $request->file('img_path');

                // ファイルが有効か確認
                if (!$file->isValid()) {
                    throw new \Exception('アップロードされたファイルが無効です。');
                }

                // ファイルを保存
                $imagePath = $file->store('images', 'public');

                if (!$imagePath) {
                    throw new \Exception('ファイルの保存に失敗しました。');
                }

                $product->img_path = $imagePath;
            }

            $product->save();

            return redirect()->route('index')->with('success', '商品が登録されました！');
        } catch (\Exception $e) {
            // エラーをログに記録
            Log::error('Product creation error: ' . $e->getMessage());

            // エラーメッセージとともに入力データを保持して前のページに戻る
            return back()
                ->withInput()
                ->with('error', '商品の登録に失敗しました。: ' . $e->getMessage());
        }
    }


    // IDを取得して詳細画面を表示 
    public function detail($id)
    {
        $product = Product::with('company')->find($id);

        // 商品が見つからなかった場合は404エラー
        if (!$product) {
            abort(404);
        }

        $companies = Company::all(['id', 'company_name']);

        return view('detailProduct', compact('product', 'companies'));
    }


    // IDを取得して編集画面を表示
    public function edit($id)
    {
        $product = Product::find($id);

        if (!$product) {
            abort(404);
        }

        $companies = Company::all(['id', 'company_name']);

        return view('editProduct', compact('product', 'companies'));
    }

    // 更新処理
    public function updateProduct(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            abort(404);
        }

        // 画像がアップロードされていた場合、画像を処理
        if ($request->hasFile('img_path')) {
            // 既存の画像ファイルがあれば削除
            if ($product->img_path && file_exists(storage_path('app/public/' . $product->img_path))) {
                unlink(storage_path('app/public/' . $product->img_path));
            }

            // 新しい画像を保存
            $file = $request->file('img_path');
            if ($file->isValid()) {
                $imagePath = $file->store('images', 'public');
                $product->img_path = $imagePath; // 新しい画像のパスを保存
            } else {
                throw new \Exception('アップロードされた画像が無効です。');
            }
        }

        $product->update($request->only([
            'product_name',
            'company_id',
            'price',
            'stock',
            'comment'
        ]));
    }

    // 更新処理
    public function update(TestRequest $request, $id)
    {
        try {
            $this->updateProduct($request, $id);
            return redirect()->route('index')->with('success', '商品情報が更新されました！');
        } catch (\Exception $e) {

            return back()->with('error', '商品情報の更新に失敗しました: ' . $e->getMessage());
        }
    }


    // 削除処理
    public function delete($id)
    {
        $product = Product::find($id);

        if (!$product) {
            abort(404);
        }

        $product->delete();

        // 削除したら一覧画面にリダイレクト
        return redirect()->route('index');
    }
}
