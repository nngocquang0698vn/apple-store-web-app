@extends('layouts.admin')

@section('title', 'Sửa sản phẩm - Quản trị')
@section('heading', 'Sửa sản phẩm')

@section('content')
    <form method="post" action="{{ route('admin.products.update', $product->id) }}" class="max-w-4xl space-y-5 rounded-lg border border-gray-200 bg-white p-6">
        @csrf
        @method('PATCH')
        @include('admin.products.form', ['product' => $product])
    </form>
@endsection
