@extends('layouts.admin')

@section('title', 'Sửa danh mục - Quản trị')
@section('heading', 'Sửa danh mục')

@section('content')
    <form method="post" action="{{ route('admin.categories.update', $category) }}" class="max-w-2xl space-y-5 rounded-lg border border-gray-200 bg-white p-6">
        @csrf
        @method('PATCH')
        @include('admin.categories.form', ['category' => $category])
    </form>
@endsection
