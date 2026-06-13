<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductDescriptionImageStoreRequest;
use App\Support\PublicStorageMirror;
use App\Support\ProductPublicUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ProductDescriptionImageController extends Controller
{
    public function store(ProductDescriptionImageStoreRequest $request): JsonResponse
    {
        $file = $request->file('image');

        try {
            $path = ProductPublicUpload::store($file, 'products/description');
        } catch (RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        PublicStorageMirror::mirror($path);

        return response()->json([
            'url' => Storage::disk('public')->url($path),
        ]);
    }
}
