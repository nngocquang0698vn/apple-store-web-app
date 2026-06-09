<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\AddCartItemRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(CartService $cart): View
    {
        $items = $cart->getItems();

        return view('cart.index', [
            'items' => $items,
            'subtotal' => $cart->subtotal(),
            'cartCount' => $cart->count(),
        ]);
    }

    public function store(AddCartItemRequest $request, CartService $cart): RedirectResponse
    {
        try {
            $cart->add(
                $request->integer('variant_id'),
                $request->integer('quantity'),
            );
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

    public function update(UpdateCartItemRequest $request, ProductVariant $variant, CartService $cart): RedirectResponse
    {
        try {
            $cart->update($variant->id, $request->integer('quantity'));
        } catch (ValidationException $exception) {
            return redirect()
                ->route('cart.index')
                ->withErrors($exception->errors());
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Đã cập nhật giỏ hàng.');
    }

    public function destroyItem(ProductVariant $variant, CartService $cart): RedirectResponse
    {
        $cart->remove($variant->id);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    public function destroy(CartService $cart): RedirectResponse
    {
        $cart->clear();

        return redirect()
            ->route('cart.index')
            ->with('success', 'Đã xóa toàn bộ giỏ hàng.');
    }
}
