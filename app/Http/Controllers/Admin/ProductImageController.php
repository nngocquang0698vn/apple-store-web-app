<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductImageMoveRequest;
use App\Http\Requests\Admin\ProductImageStoreRequest;
use App\Http\Requests\Admin\ProductImageUpdateRequest;
use App\Models\Product;
use App\Models\ProductImage;
use App\Support\AdminProductImagePayload;
use App\Support\ProductImageOrdering;
use App\Support\ProductPublicUpload;
use App\Support\PublicStorageMirror;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function __construct(
        private readonly ProductImageOrdering $imageOrdering,
    ) {}

    public function store(ProductImageStoreRequest $request, string $product): JsonResponse|RedirectResponse
    {
        $product = Product::query()->findOrFail($product);
        $data = $request->validated();
        $file = $request->file('image');

        try {
            $path = ProductPublicUpload::store($file, "products/{$product->id}");
        } catch (RuntimeException $exception) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $exception->getMessage()], 422);
            }

            return back()->withErrors(['image' => $exception->getMessage()]);
        }

        PublicStorageMirror::mirror($path);

        $hasImages = $product->images()->exists();
        $isPrimary = $data['is_primary'] ?? false;

        if (! $hasImages) {
            $isPrimary = true;
        }

        $sortOrder = $data['sort_order'] ?? ((int) $product->images()->max('sort_order') + 1);

        DB::transaction(function () use ($data, $path, $product, $isPrimary, $sortOrder): void {
            if ($isPrimary) {
                $product->images()->update(['is_primary' => false]);
            }

            $product->images()->create([
                'path' => $path,
                'alt_text' => filled($data['alt_text'] ?? null) ? $data['alt_text'] : $product->name,
                'sort_order' => $sortOrder,
                'is_primary' => $isPrimary,
            ]);
        });

        $this->imageOrdering->normalize($product);

        return $this->respond($product, 'Đã tải ảnh sản phẩm.');
    }

    public function update(ProductImageUpdateRequest $request, ProductImage $image): JsonResponse|RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $image): void {
            if (($data['is_primary'] ?? false) === true) {
                ProductImage::query()
                    ->where('product_id', $image->product_id)
                    ->where('id', '!=', $image->id)
                    ->update(['is_primary' => false]);
            }

            $image->update($data);
        });

        return $this->respond($image->product, 'Đã cập nhật ảnh sản phẩm.');
    }

    public function setPrimary(ProductImage $image): JsonResponse|RedirectResponse
    {
        DB::transaction(function () use ($image): void {
            ProductImage::query()
                ->where('product_id', $image->product_id)
                ->update(['is_primary' => false]);

            $image->update(['is_primary' => true]);
        });

        return $this->respond($image->product, 'Đã đặt làm ảnh chính.');
    }

    public function move(ProductImageMoveRequest $request, ProductImage $image): JsonResponse|RedirectResponse
    {
        $direction = $request->validated('direction');
        $moved = $this->imageOrdering->move($image, $direction);

        $message = $moved
            ? 'Đã cập nhật thứ tự ảnh.'
            : 'Ảnh đã ở vị trí này, không thể đổi thêm.';

        return $this->respond($image->product, $message, movedImageId: $moved ? $image->id : null);
    }

    public function destroy(ProductImage $image): JsonResponse|RedirectResponse
    {
        $product = $image->product;
        $wasPrimary = $image->is_primary;

        Storage::disk('public')->delete($image->path);
        PublicStorageMirror::remove($image->path);
        $image->delete();

        if ($wasPrimary) {
            $nextPrimary = ProductImage::query()
                ->where('product_id', $product->id)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->first();

            if ($nextPrimary) {
                $nextPrimary->update(['is_primary' => true]);
            }
        }

        return $this->respond($product, 'Đã xóa ảnh sản phẩm.');
    }

    private function respond(Product $product, string $message, ?int $movedImageId = null): JsonResponse|RedirectResponse
    {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'images' => AdminProductImagePayload::forProduct($product),
                    'moved_image_id' => $movedImageId,
                ],
            ]);
        }

        return to_route('admin.products.show', $product->id)
            ->with('success', $message);
    }
}
