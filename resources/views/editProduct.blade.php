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
                <label class="control fw-bolder fs-1">{{ $product->id }}<span>.</span></label>
            </div>
            <div class="form-group">
                <label class="form-label">商品名<span class="required">*</span></label>
                <input type="text" class="control @error('product_name') @enderror" id="product_name" name="product_name" value="{{ old('product_name', $product->product_name) }}">
                @error('product_name')
                <div class="text-danger" style="font-size: 20px; margin-left: 40%;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">メーカー名<span class="required">*</span></label>
                <select name="company_id" class="control">
                    <option value="">選択してください</option>
                    @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ $product->company_id == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                    @endforeach
                </select>
                @error('company_id')
                <div class="text-danger" style="font-size: 20px; margin-left: 40%;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">価格<span class="required">*</span></label>
                <input type="text" name="price" class="control" value="{{ old('price', $product->price) }}">
                @error('price')
                <div class="text-danger" style="font-size: 20px; margin-left: 40%;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">在庫数<span class="required">*</span></label>
                <input type="text" name="stock" class="control" value="{{ old('stock', $product->stock) }}">
                @error('stock')
                <div class="text-danger" style="font-size: 20px; margin-left: 40%;">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">コメント</label>
                <textarea id="text" name="comment" class="control">{{ old('comment', $product->comment) }}</textarea>
            </div>
            <div class="form-group d-flex align-items-center" style="padding: 20px 0;">
                <label class="form-label col-sm-4 text-start fw-bold">商品画像</label>
                <input type="file" class="control @error('img_path') is-invalid @enderror" id="img_path" name="img_path">
            </div>

            <button type="submit" class="button btn btn-warning py-3 fs-5">更新</button>
            <a class="button btn btn-info py-3 fs-5" href="{{ route('detail', ['id' => $product->id]) }}">戻る</a>
        </form>
    </div>
</div>
@endsection