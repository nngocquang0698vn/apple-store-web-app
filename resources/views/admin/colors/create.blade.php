@extends('layouts.admin')

@section('title', 'Thêm màu - Quản trị')
@section('heading', 'Thêm màu')

@section('content')
    <form method="post" action="{{ route('admin.colors.store') }}" class="max-w-2xl space-y-5 rounded-lg border border-gray-200 bg-white p-6">
        @csrf
        @include('admin.colors.form')
    </form>
@endsection
