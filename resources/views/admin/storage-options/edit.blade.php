@extends('layouts.admin')

@section('title', 'Sửa dung lượng - Quản trị')
@section('heading', 'Sửa dung lượng')

@section('content')
    <form method="post" action="{{ route('admin.storage-options.update', $storageOption) }}" class="max-w-2xl space-y-5 rounded-lg border border-gray-200 bg-white p-6">
        @csrf
        @method('PATCH')
        @include('admin.storage-options.form', ['storageOption' => $storageOption])
    </form>
@endsection
