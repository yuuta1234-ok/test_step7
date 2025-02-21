<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧画面</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

</head>

<body>

    @extends('layouts.app')

    @section('content')
    <div class="container">

        <h1 class="form-title">商品一覧画面</h1>

        <div class="search-container">
            <form action="{{route('index')}}" method="get" class="search-form">
                @csrf
                <input type="text" name="product_name" placeholder="検索キーワード" id="sarch_text" class="search-input">

                <select name="company_id" class="search-select">
                    <option value="">メーカー名</option>
                    @foreach($companies as $company)
                    <option value="{{$company->id}}">{{$company->company_name}}</option>
                    @endforeach
                </select>
                <button type="submit" class="search-button">検索</button>
            </form>
        </div>

        <div class="form-container border border-dark">
            <table class="table table-striped">
                <tr>
                    <th class="title align-middle fst-italic" style="width: 10%;">ID</th>
                    <th class="title align-middle" style="width: 10%;">商品画像</th>
                    <th class="title align-middle" style="width: 10%;">商品名</th>
                    <th class="title align-middle" style="width: 10%;">価格</th>
                    <th class="title align-middle" style="width: 10%;">在庫数</th>
                    <th class="title align-middle" style="width: 10%;">メーカー名</th>
                    <th colspan="2" class="title align-middle col-9" style="width: 10%;">
                        <a href="{{route('store')}}" class="create-button btn btn-warning align-middle">新規登録</a>
                    </th>
                </tr>
                @foreach ($products as $product)
                <tr>
                    <td>{{$product->id}}.</td>
                    <td><img src="{{asset($product->img_path)}}" alt="商品画像" style="width: 100px; height: auto;"></td>
                    <td>{{$product->product_name}}</td>
                    <td><span>¥</span>{{$product->price}}</td>
                    <td>{{$product->stock}}</td>
                    <td>{{$product->company_name}}</td>
                    <td><a href="{{route('detail',['id'=>$product->id])}}" class="detail-button btn btn-info fs-5 ms-5">詳細</a></td>
                    <td>
                        <form action="{{route('delete',['id'=>$product->id])}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="delete-button btn btn-danger fs-5" onclick="return confirm('本当に削除しますか？');">削除</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                </td>
                </tr>
            </table>
        </div>
        <ul class="pagination justify-content-center mt-5">
            {!! $products->links('pagination::bootstrap-4') !!}
        </ul>
    </div>
</body>
@endsection