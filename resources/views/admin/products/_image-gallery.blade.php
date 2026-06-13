@php
    use App\Support\AdminProductImagePayload;

    $imageRoutes = [
        'upload' => route('admin.products.images.store', $product->id),
        'update' => route('admin.product-images.update', ['image' => '__ID__']),
        'primary' => route('admin.product-images.primary', ['image' => '__ID__']),
        'move' => route('admin.product-images.move', ['image' => '__ID__']),
        'destroy' => route('admin.product-images.destroy', ['image' => '__ID__']),
    ];
    $initialImages = AdminProductImagePayload::forProduct($product);
@endphp

<section
    class="mt-6 rounded-2xl border border-gray-200 bg-white p-6"
    data-image-upload
    data-product-name="{{ $product->name }}"
    data-routes='@json($imageRoutes)'
    data-initial-images='@json($initialImages)'
>
    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-900">Hình ảnh sản phẩm</h2>
            <p class="mt-1 text-sm text-gray-600">
                Thêm ảnh để sản phẩm hiển thị đẹp trên trang khách hàng. Ảnh chính dùng cho thẻ sản phẩm và kết quả tìm kiếm.
            </p>
        </div>
    </div>

    <div
        class="mt-5 rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 p-6 text-center transition duration-200 hover:border-blue-400 hover:bg-blue-50"
        data-image-dropzone
        role="button"
        tabindex="0"
        aria-label="Kéo thả ảnh vào đây hoặc bấm để chọn ảnh"
    >
        <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400" aria-hidden="true"></i>
        <p class="mt-3 text-sm font-medium text-gray-800">Kéo thả ảnh vào đây hoặc bấm để chọn ảnh</p>
        <p class="mt-2 text-xs text-gray-500">
            Hỗ trợ JPG, PNG, WebP · Tối đa 4MB mỗi ảnh · Nên dùng ảnh vuông, nền sáng, tỷ lệ 1:1
        </p>
        <button
            type="button"
            class="mt-4 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:cursor-not-allowed disabled:opacity-60"
            data-image-upload-trigger
        >
            <i class="fa-solid fa-image" aria-hidden="true"></i>
            Chọn ảnh sản phẩm
        </button>
        <input
            type="file"
            class="sr-only"
            data-image-input
            accept="image/jpeg,image/png,image/webp"
            multiple
        >
    </div>

    <div class="mt-3 hidden rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" data-image-error role="alert"></div>
    <div class="mt-3 hidden rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800" data-image-success role="status"></div>

    @error('image')
        <p class="mt-3 text-sm text-red-600" role="alert">{{ $message }}</p>
    @enderror

    <div class="mt-5 hidden" data-image-preview-section>
        <h3 class="text-sm font-semibold text-gray-900">Ảnh sắp tải lên</h3>
        <ul class="mt-3 grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4" data-image-preview-list></ul>
        <div class="mt-4 flex flex-wrap items-center gap-3">
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:cursor-not-allowed disabled:opacity-60"
                data-image-upload-submit
            >
                <i class="fa-solid fa-upload" aria-hidden="true"></i>
                <span data-image-upload-submit-label>Tải ảnh lên</span>
            </button>
            <button
                type="button"
                class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50"
                data-image-clear-previews
            >
                Xóa danh sách chờ
            </button>
        </div>
    </div>

    <div class="mt-8">
        <h3 class="text-sm font-semibold text-gray-900">Ảnh hiện có</h3>
        <p class="mt-1 text-sm text-gray-600">
            Thứ tự hiển thị: <strong>trái → phải</strong>, tự xuống dòng khi hết chỗ — giống trang chi tiết sản phẩm.
            Dùng <strong>Hiển thị trước</strong> / <strong>Hiển thị sau</strong> để đổi vị trí; số thứ tự trên ảnh sẽ cập nhật ngay.
        </p>
        <div class="mt-4" data-existing-images-root>
            <div
                class="hidden rounded-2xl border border-dashed border-gray-200 bg-gray-50 px-6 py-10 text-center"
                data-image-empty-state
            >
                <i class="fa-regular fa-images text-3xl text-gray-300" aria-hidden="true"></i>
                <p class="mt-3 text-sm font-medium text-gray-700">Chưa có hình ảnh cho sản phẩm này.</p>
                <p class="mt-1 text-sm text-gray-500">Hãy thêm ít nhất một ảnh để sản phẩm hiển thị đẹp hơn ở trang khách hàng.</p>
                <button
                    type="button"
                    class="mt-4 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700"
                    data-image-empty-trigger
                >
                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                    Chọn ảnh sản phẩm
                </button>
            </div>
            <div
                class="flex flex-wrap items-start gap-4"
                data-existing-images-grid
                aria-label="Dãy ảnh sản phẩm theo thứ tự hiển thị"
            ></div>
        </div>
    </div>
</section>
