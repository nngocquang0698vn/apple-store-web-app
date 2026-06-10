<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductImageStoreRequest;
use App\Http\Requests\Admin\ProductImageUpdateRequest;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImageController extends Controller
{
    public function store(ProductImageStoreRequest $request, string $product): RedirectResponse
    {
        $product = Product::query()->findOrFail($product);
        $data = $request->validated();
        $file = $request->file('image');
        $filename = Str::ulid().'.'.$file->extension();
        $path = $file->storeAs("products/{$product->id}", $filename, 'public');

        DB::transaction(function () use ($data, $path, $product): void {
            if ($data['is_primary']) {
                $product->images()->update(['is_primary' => false]);
            }

            $product->images()->create([
                'path' => $path,
                'alt_text' => $data['alt_text'] ?: $product->name,
                'sort_order' => $data['sort_order'],
                'is_primary' => $data['is_primary'],
            ]);
        });

        return to_route('admin.products.show', $product->id)
            ->with('success', 'Đã tải ảnh sản phẩm.');
    }

    public function update(ProductImageUpdateRequest $request, ProductImage $image): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $image): void {
            if ($data['is_primary']) {
                ProductImage::query()
                    ->where('product_id', $image->product_id)
                    ->where('id', '!=', $image->id)
                    ->update(['is_primary' => false]);
            }

            $image->update($data);
        });

        return to_route('admin.products.show', $image->product_id)
            ->with('success', 'Đã cập nhật ảnh sản phẩm.');
    }

    public function destroy(ProductImage $image): RedirectResponse
    {
        $productId = $image->product_id;

        Storage::disk('public')->delete($image->path);
        $image->delete();

        return to_route('admin.products.show', $productId)
            ->with('success', 'Đã xóa ảnh sản phẩm.');
    }
}
