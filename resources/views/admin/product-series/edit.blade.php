@extends('layouts.admin')

@section('title', 'Sửa dòng sản phẩm - Quản trị')
@section('heading', 'Sửa dòng sản phẩm')

@section('content')
    <form method="post" action="{{ route('admin.product-series.update', $series) }}" class="max-w-2xl space-y-5 rounded-lg border border-gray-200 bg-white p-6">
        @csrf
        @method('PATCH')
        @include('admin.product-series.form', ['series' => $series])
    </form>
@endsection
