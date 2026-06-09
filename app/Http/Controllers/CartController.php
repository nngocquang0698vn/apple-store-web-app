<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\RespondsWithCart;
use App\Http\Requests\Cart\AddCartItemRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Models\ProductVariant;
use App\Services\CartService;
use App\Services\ShippingFeeCalculator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CartController extends Controller
{
    use RespondsWithCart;

    public function index(CartService $cart): View
    {
        $items = $cart->getItems();
        $subtotal = $cart->subtotal();
        $shippingFee = ShippingFeeCalculator::calculate($subtotal);

        return view('cart.index', [
            'items' => $items,
            'subtotal' => $subtotal,
            'shippingFee' => $shippingFee,
            'grandTotal' => $subtotal + $shippingFee,
            'cartCount' => $cart->count(),
        ]);
    }

    public function summary(CartService $cart): JsonResponse
    {
        $data = $cart->buildSummary();

        if ($cart->hasPurchasabilityConflict()) {
            return response()->json([
                'success' => false,
                'message' => 'Một số sản phẩm trong giỏ đã thay đổi giá hoặc tồn kho.',
                'data' => $data,
            ], 409);
        }

        return response()->json([
            'success' => true,
            'message' => 'OK',
            'data' => $data,
        ]);
    }

    public function store(AddCartItemRequest $request, CartService $cart): JsonResponse|RedirectResponse
    {
        $variantId = $request->integer('variant_id');
        $quantity = $request->integer('quantity');

        if ($this->isAjaxRequest($request)) {
            $cart->add($variantId, $quantity);

            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng.',
                'data' => $cart->buildSummary($variantId, $quantity),
            ]);
        }

        try {
            $cart->add($variantId, $quantity);
        } catch (ValidationException $exception) {
            return redirect()
                ->back()
                ->withErrors($exception->errors())
                ->withInput();
        }

        return redirect()
            ->back()
            ->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function update(UpdateCartItemRequest $request, ProductVariant $variant, CartService $cart): JsonResponse|RedirectResponse
    {
        $quantity = $request->integer('quantity');

        if ($this->isAjaxRequest($request)) {
            $cart->update($variant->id, $quantity);

            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật giỏ hàng.',
                'data' => $cart->buildSummary($variant->id, $quantity),
            ]);
        }

        try {
            $cart->update($variant->id, $quantity);
        } catch (ValidationException $exception) {
            return redirect()
                ->route('cart.index')
                ->withErrors($exception->errors());
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Đã cập nhật giỏ hàng.');
    }

    public function destroyItem(Request $request, ProductVariant $variant, CartService $cart): JsonResponse|RedirectResponse
    {
        $cart->remove($variant->id);

        return $this->respondCartMutation(
            $request,
            $cart,
            'Đã xóa sản phẩm khỏi giỏ hàng.',
            redirect()->route('cart.index'),
        );
    }

    public function destroy(Request $request, CartService $cart): JsonResponse|RedirectResponse
    {
        $cart->clear();

        return $this->respondCartMutation(
            $request,
            $cart,
            'Đã xóa toàn bộ giỏ hàng.',
            redirect()->route('cart.index'),
        );
    }
}
