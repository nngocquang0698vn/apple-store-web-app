@extends('layouts.admin')

@section('title', 'Thêm danh mục - Quản trị')
@section('heading', 'Thêm danh mục')

@section('content')
    <form method="post" action="{{ route('admin.categories.store') }}" class="max-w-2xl space-y-5 rounded-lg border border-gray-200 bg-white p-6">
        @csrf
        @include('admin.categories.form')
    </form>
@endsection
