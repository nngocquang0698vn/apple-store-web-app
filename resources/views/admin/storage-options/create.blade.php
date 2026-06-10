@extends('layouts.admin')

@section('title', 'Thêm dung lượng - Quản trị')
@section('heading', 'Thêm dung lượng')

@section('content')
    <form method="post" action="{{ route('admin.storage-options.store') }}" class="max-w-2xl space-y-5 rounded-lg border border-gray-200 bg-white p-6">
        @csrf
        @include('admin.storage-options.form')
    </form>
@endsection
