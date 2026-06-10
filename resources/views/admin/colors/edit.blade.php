@extends('layouts.admin')

@section('title', 'Sửa màu - Quản trị')
@section('heading', 'Sửa màu')

@section('content')
    <form method="post" action="{{ route('admin.colors.update', $color) }}" class="max-w-2xl space-y-5 rounded-lg border border-gray-200 bg-white p-6">
        @csrf
        @method('PATCH')
        @include('admin.colors.form', ['color' => $color])
    </form>
@endsection
