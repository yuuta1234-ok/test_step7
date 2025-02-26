<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Product;

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

        //　リクエストデータが正しいかをチェックするためのルールを定義している
        $request->validate([
            'product_name' => 'required',
            'company_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable|string',
            'img_path' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $product->product_name = $request->input('product_name');
        $product->company_id = $request->input('company_id');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->comment = $request->input('comment');

        // 画像アップロード処理
        $imagePath = null;
        if ($request->hasFile('img_path') && $request->file('img_path')->isValid()) {
            $imagePath = $request->file('img_path')->store('images', 'public');
            $product->img_path = $imagePath;
        }

        $product->save();

        return redirect()->route('index')->with('success', '商品が登録されました！');
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
