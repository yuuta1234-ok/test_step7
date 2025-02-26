<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{

    // 商品一覧画面の表示と検索処理
    public function index(Request $request)
    {
        $search = $request->input('product_name');
        $company_id = $request->input('company_id');

        // Product モデルのクエリビルダを使ってデータ取得
        $products = Product::getProduct($search, $company_id);

        $companies = DB::table('companies')->select('id', 'company_name')->get();

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
    public function store(Request $request, Product $product)
    {
        try {
            // バリデーション
            $request->validate([
                'product_name' => 'required',
                'company_id' => 'required',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'comment' => 'nullable|string',
                'img_path' => 'nullable|file|max:2048',
            ]);

            // 基本情報の保存
            $product->product_name = $request->input('product_name');
            $product->company_id = $request->input('company_id');
            $product->price = $request->input('price');
            $product->stock = $request->input('stock');
            $product->comment = $request->input('comment');

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
        $product = DB::table('products')
            ->join('companies', 'products.company_id', '=', 'companies.id')
            ->select(
                'products.id',
                'products.product_name',
                'products.price',
                'products.stock',
                'products.comment',
                'products.img_path',
                'companies.company_name'
            )
            ->where('products.id', $id)
            ->first();

        // 商品が見つからなかった場合は404エラー
        if (!$product) {
            abort(404);
        }

        // メーカー一覧を取得
        $companies = DB::table('companies')->select('id', 'company_name')->get();

        return view('detailProduct', compact('product', 'companies'));
    }


    // IDを取得して編集画面を表示
    public function edit($id)
    {
        $product = Product::find($id);

        $company = new Company();
        $companies = $company->getCompany();

        return view('editProduct', compact('product', 'companies'));
    }


    // 更新処理
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required',
            'company_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable|string',
            'img_path' => 'nullable|image',
        ]);

        $this->updateProduct($request, $id);

        $product = Product::find($id);

        return redirect()->route('index')->with('success', '商品情報が更新されました！');
    }

    // 更新処理
    public function updateProduct(Request $request, $id)
    {
        DB::table('products')
            ->where('id', $id)
            ->update([
                'product_name' => $request->input('product_name'),
                'company_id' => $request->input('company_id'),
                'price' => $request->input('price'),
                'stock' => $request->input('stock'),
                'comment' => $request->input('comment'),
            ]);
    }


    // 削除処理
    public function delete($id)
    {
        DB::table('products')
            ->where('id', $id)
            ->delete();
        // 削除したら一覧画面にリダイレクト
        return redirect()->route('index');
    }
}
