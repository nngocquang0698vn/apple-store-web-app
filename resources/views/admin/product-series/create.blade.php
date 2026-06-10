@extends('layouts.admin')

@section('title', 'Thêm dòng sản phẩm - Quản trị')
@section('heading', 'Thêm dòng sản phẩm')

@section('content')
    <form method="post" action="{{ route('admin.product-series.store') }}" class="max-w-2xl space-y-5 rounded-lg border border-gray-200 bg-white p-6">
        @csrf
        @include('admin.product-series.form')
    </form>
@endsection
