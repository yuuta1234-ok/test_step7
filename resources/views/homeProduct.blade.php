<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>商品一覧画面</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

    @extends('layouts.app')

    @section('content')
    <div class="container">

        <h1 class="form-title">商品一覧画面</h1>

        <div class="search-container">
            <form id="searchForm" method="get" class="search-form">
                <input type="text" name="product_name" placeholder="検索キーワード" id="sarch_text" class="search-input">

                <!-- 価格範囲 -->
                <input type="number" name="price_min" placeholder="価格（下限）" class="search-input">
                <input type="number" name="price_max" placeholder="価格（上限）" class="search-input">

                <!-- 在庫範囲 -->
                <input type="number" name="stock_min" placeholder="在庫（下限）" class="search-input">
                <input type="number" name="stock_max" placeholder="在庫（上限）" class="search-input">

                <select name="company_id" class="search-select">
                    <option value="">メーカー名</option>
                    @foreach($companies as $company)
                    <option value="{{$company->id}}">{{$company->company_name}}</option>
                    @endforeach
                </select>
                <button type="submit" class="search-button">検索</button>
            </form>
        </div>


        <div id="searchResult">
            <div class="form-container border border-dark">
                <table class="table table-striped">
                    <tr>
                        <th style="font-style: italic; font-size: 1.2rem;">
                            <a href="#" class="sort-link" data-column="id" data-order="desc">ID</a>
                        </th>
                        <th style="font-size: 1.2rem;">商品画像</th>
                        <th style="font-size: 1.2rem;">
                            <a href="#" class="sort-link" data-column="product_name" data-order="desc">商品名</a>
                        </th>
                        <th style="font-size: 1.2rem;">
                            <a href="#" class="sort-link" data-column="price" data-order="asc">価格</a>
                        </th>
                        <th style="font-size: 1.2rem;">
                            <a href="#" class="sort-link" data-column="stock" data-order="asc">在庫数</a>
                        </th>
                        <th style="font-size: 1.2rem;">
                            <a href="#" class="sort-link" data-column="company_id" data-order="asc">メーカー名</a>
                        </th>
                        <th colspan="2" class="title align-middle col-9" style="width: 10%;">
                            <a href="{{route('store')}}" class="create-button btn btn-warning align-middle">新規登録</a>
                        </th>
                    </tr>
                    @foreach ($products as $product)
                    <tr id="product-{{ $product->id }}">
                        <td>{{ $product->id }}</td>
                        <td><img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像" style="width: 100px; height: auto;"></td>
                        <td>{{ $product->product_name }}</td>
                        <td>¥{{ $product->price }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->company->company_name }}</td>
                        <td><a href="{{ route('detail', ['id' => $product->id]) }}" class="btn btn-info">詳細</a></td>
                        <td>
                            <button class="delete-button btn btn-danger" data-id="{{ $product->id }}">削除</button>
                        </td>
                    </tr>
                    @endforeach
                </table>
                </td>
                </tr>
                </table>
            </div>
            <ul class="pagination justify-content-center mt-5">
                {!! $products->links('pagination::bootstrap-4') !!}
            </ul>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {

                // ソート状態の記録用オブジェクト（カラムごとに昇順・降順を記録）
                let sortStates = {};

                // 検索フォーム送信（非同期）
                $('#searchForm').on('submit', function(e) {
                    e.preventDefault();

                    const formData = $(this).serialize(); // 検索条件まとめて取得

                    $.ajax({
                        url: '{{ route("index") }}',
                        method: 'GET',
                        data: formData,
                        success: function(response) {
                            $('#searchResult').html($(response).find('#searchResult').html());
                        },
                        error: function() {
                            alert('検索に失敗しました。');
                        }
                    });
                });

                // ソートリンククリック時の処理
                $(document).on('click', '.sort-link', function(e) {
                    e.preventDefault();

                    const column = $(this).data('column');
                    const currentOrder = sortStates[column] || 'asc';
                    const newOrder = (currentOrder === 'asc') ? 'desc' : 'asc';

                    sortStates[column] = newOrder;

                    // フォームの値も取得して一緒に送信
                    const formData = $('#searchForm').serializeArray();

                    formData.push({
                        name: 'sort_column',
                        value: column
                    });
                    formData.push({
                        name: 'sort_order',
                        value: newOrder
                    });

                    $.ajax({
                        url: '{{ route("index") }}',
                        method: 'GET',
                        data: formData,
                        success: function(response) {
                            $('#searchResult').html($(response).find('#searchResult').html());
                        },
                        error: function() {
                            alert('ソートに失敗しました。');
                        }
                    });
                });

                // 商品削除（非同期）
                $(document).on('click', '.delete-button', function(e) {
                    e.preventDefault();

                    const id = $(this).data('id');
                    if (!confirm('本当に削除しますか？')) return;

                    $.ajax({
                        url: '{{ url("deleteProduct") }}/' + id,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#product-' + id).remove();
                            }
                        },
                        error: function() {
                            alert('削除に失敗しました。');
                        }
                    });
                });

            });
        </script>
</body>
@endsection