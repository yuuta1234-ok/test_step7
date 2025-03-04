<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品情報詳細画面</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>

@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="form-title">商品情報詳細画面</h1>
    <div class="form-container border border-dark">


        <div class="form-group">
            <label class="form-label fst-italic">ID</label>
            <p class="text">{{$product->id}}<span>.</span></p>
        </div>
        <div class="form-group">
            <label class="form-label">商品画像</label>
            <p class="text"><img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像" style="width: 100px; height: auto;"></p>
        </div>
        <div class="form-group">
            <label class="form-label">商品名</label>
            <p class="text">{{$product->product_name}}</p>
        </div>
        <div class="form-group">
            <label class="form-label">メーカー名</label>
            <p class="text">{{$product->company->company_name}}</p>
        </div>
        <div class="form-group">
            <label class="form-label">価格</label>
            <p class="text"><span>¥</span>{{$product->price}}</p>
        </div>
        <div class="form-group">
            <label class="form-label">在庫数</label>
            <p class="text">{{$product->stock}}</p>
        </div>
        <div class="form-group">
            <label class="form-label">コメント</label>
            <p class="text">{{$product->comment}}</p>
        </div>

        <a href="{{route('edit',['id'=>$product->id])}}" class="button btn btn-warning py-3 fs-5">編集</a>
        <a class="button btn btn-info py-3 fs-5" href="{{ route('index') }}">戻る</a>
    </div>
    @endsection