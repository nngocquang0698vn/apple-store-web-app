<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductDescriptionImageStoreRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ProductDescriptionImageController extends Controller
{
    public function store(ProductDescriptionImageStoreRequest $request): JsonResponse
    {
        $file = $request->file('image');
        $filename = Str::ulid().'.'.$file->extension();
        $path = $file->storeAs('products/description', $filename, 'public');

        return response()->json([
            'url' => '/storage/'.$path,
        ]);
    }
}
