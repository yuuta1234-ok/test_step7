<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreProductRequest;

class TestController extends Controller
{

    public function index(Request $request)
    {

        $search = $request->input('product_name');
        $company_id = $request->input('company_id');

        $priceMin = $request->input('price_min');
        $priceMax = $request->input('price_max');
        $stockMin = $request->input('stock_min');
        $stockMax = $request->input('stock_max');

        $sortColumn = $request->input('sort_column', 'id');
        $sortOrder = $request->input('sort_order', 'desc');


        $products = Product::with('company')
            ->when($search, function ($query) use ($search) {
                return $query->where('product_name', 'like', "%{$search}%");
            })
            ->when($company_id, function ($query) use ($company_id) {
                return $query->where('company_id', $company_id);
            })
            ->when($priceMin, function ($query) use ($priceMin) {
                return $query->where('price', '>=', $priceMin);
            })
            ->when($priceMax, function ($query) use ($priceMax) {
                return $query->where('price', '<=', $priceMax);
            })
            ->when($stockMin, function ($query) use ($stockMin) {
                return $query->where('stock', '>=', $stockMin);
            })
            ->when($stockMax, function ($query) use ($stockMax) {
                return $query->where('stock', '<=', $stockMax);
            })
            ->orderBy($sortColumn, $sortOrder)
            ->paginate(10);


        $companies = Company::all(['id', 'company_name']);

        // Ajaxリクエストならview部分だけ返す
        if ($request->ajax()) {
            return view('homeProduct', compact('products', 'companies'))->render();
        }

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
    public function store(StoreProductRequest $request, Product $product)
    {
        DB::beginTransaction(); // トランザクション開始

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
            DB::commit();

            return redirect()->route('index')->with('success', '商品が登録されました！');
        } catch (\Exception $e) {
            DB::rollBack();
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
    public function update(StoreProductRequest $request, $id)
    {
        DB::beginTransaction(); // トランザクション開始

        try {
            $product = Product::find($id);
            if (!$product) {
                abort(404);
            }

            // 画像の処理
            if ($request->hasFile('img_path')) {
                if ($product->img_path && file_exists(storage_path('app/public/' . $product->img_path))) {
                    unlink(storage_path('app/public/' . $product->img_path));
                }
                $file = $request->file('img_path');
                if ($file->isValid()) {
                    $product->img_path = $file->store('images', 'public');
                } else {
                    throw new \Exception('アップロードされた画像が無効です。');
                }
            }

            // 商品情報を更新
            $product->update($request->only(['product_name', 'company_id', 'price', 'stock', 'comment']));

            DB::commit();
            return redirect()->route('index')->with('success', '商品情報が更新されました！');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '商品情報の更新に失敗しました: ' . $e->getMessage());
        }
    }


    public function delete($id)
    {
        Log::info("削除リクエスト受信 ID: " . $id); // ログを出力して確認

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => '商品が見つかりません。'], 404);
        }

        $product->delete();

        return response()->json(['success' => true, 'message' => '商品を削除しました。']);
    }
}
