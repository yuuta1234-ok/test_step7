<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品新規登録画面</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>

@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="form-title">商品新規登録画面</h1>
    <!-- エラーメッセージ表示部分を追加 -->
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <!-- 成功メッセージ表示も追加しておくと良いでしょう -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="form-container border border-dark">
        <form action="{{route('store')}}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">商品名<span class="required">*</span></label>
                <input type="text" class="control" name="product_name" required>
            </div>
            <div class="form-group">
                <label class="form-label">メーカー名<span class="required">*</span></label>
                <select class="control" name="company_id" id="company_id" required>
                    <option value=""></option>
                    @foreach($companies as $company)
                    <option value="{{$company->id}}">{{$company->company_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">価格<span class="required">*</span></label>
                <input type="text" class="control" name="price" required>
            </div>

            <div class="form-group">
                <label class="form-label">在庫数<span class="required">*</span></label>
                <input type="text" class="control" name="stock" required>
            </div>
            <div class="form-group">
                <label class="form-label">コメント</label>
                <textarea id="number" class="control" name="comment"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">商品画像</label>
                <input type="file" class="image" name="img_path">
            </div>

            <button type="submit" class="button btn btn-warning py-3 fs-5">新規登録</button>
            <a class="button btn btn-info py-3 fs-5" href="{{ route('index') }}">戻る</a>
        </form>
    </div>
</div>
@endsection