@extends('layouts.admin')

@section('title', 'Thêm sản phẩm - Quản trị')
@section('heading', 'Thêm sản phẩm')

@section('content')
    <form method="post" action="{{ route('admin.products.store') }}" class="max-w-4xl space-y-5 rounded-lg border border-gray-200 bg-white p-6">
        @csrf
        @include('admin.products.form')
    </form>
@endsection

@push('vite')
    @vite(['resources/js/admin/product-editor.js'])
@endpush
