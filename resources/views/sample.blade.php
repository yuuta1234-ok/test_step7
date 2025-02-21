<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品リスト</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>

    <div class="container mt-5">

        <h1 id="title">商品一覧画面</h1>

        <div id="search_contents" class="mt-5">
            <!-- 商品名部分検索 -->
            <input type="text" name="product_name" id="search_text">

            <select name="company_id" id="search_select">
                <option>選択してください</option>
                @foreach($companys as $company)
                <option value="{{$company->id}}">{{$company->name}}</option>
                @endforeach
            </select>

            <button class="search_button">検索</button>
        </div>


        <table class="table table-striped mt-5">
            <tr class="bg-danger">
                <th>商品ID</th>
                <th>商品名</th>
                <th>説明</th>
                <th>価格</th>
                <th>在庫</th>
                <th>会社名</th>
                <th>
                    <form action="{{route('product.form')}}" method="get">
                        <button type="submit" class="product_register">新規登録</button>
                    </form>

                </th>

                <th></th>

            </tr>
            @foreach ($products as $p)
            <tr>
                <td>{{$p->id}}</td>
                <td>{{$p->name}}</td>
                <td>{{$p->description}}</td>
                <td>{{$p->price}}</td>
                <td>{{$p->stock}}</td>
                <td>{{$p->company_name}}</td>
                <td>
                    <!-- 詳細画面 -->
                    <form action="{{route('product.detail' ,['id' => $p->id])}}" method="get">
                        <button type="submit" class="btn btn-success">詳細</button>
                    </form>
                </td>
                <!-- 削除処理 -->
                <td>
                    <form id="deleteForm" action="{{route('product.delete' ,['id' => $p->id])}}" method="post">
                        @method('DELETE')
                        @csrf
                        <button type="button" class="btn btn-danger" onclick="deleteClick('{{ $p->id }}')">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>

        <div class="paginate">
            {!! $products->links('pagination::bootstrap-4') !!}
        </div>
    </div>

    <script>
        function deleteClick(event) {
            if (confirm("削除しますか？")) {
                document.getElementById("deleteForm").submit();
            } else {
                event.preventDefault();
            }
        }
    </script>
</body>

</html>