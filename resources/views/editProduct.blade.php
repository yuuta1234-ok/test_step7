<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品情報編集画面</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>

@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="form-title">商品情報編集画面</h1>
    <div class="form-container border border-dark">
        <form action="{{ route('update', ['id' => $product->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label fst-italic">ID.</label>
                <label class="control fw-bolder fs-1">{{$product->id}}<span>.</span></label>
            </div>
            <div class="form-group">
                <label class="form-label">商品名<span class="required">*</span></label>
                <input type="text" name="product_name" class="control">
            </div>
            <div class="form-group">
                <label class="form-label">メーカー名<span class="required">*</span></label>
                <select type="text" name="company_id" class="control">
                    <option value=""></option>
                    @foreach($companies as $company)
                    <option value="{{$company->id}}">{{$company->company_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">価格<span class="required">*</span></label>
                <input type="text" name="price" class="control">
            </div>
            <div class="form-group">
                <label class="form-label">在庫数<span class="required">*</span></label>
                <input type="text" name="stock" class="control">
            </div>
            <div class="form-group">
                <label class="form-label">コメント</label>
                <textarea id="text" name="comment" class="control"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">商品画像</label>
                <input type="file" name="img_path" class="image">
            </div>


            <button type="submit" class="button btn btn-warning py-3 fs-5">更新</button>
            <a class="button btn btn-info py-3 fs-5" href="{{ route('detail',['id'=>$product->id])}}">戻る</a>
        </form>
    </div>
</div>
@endsection