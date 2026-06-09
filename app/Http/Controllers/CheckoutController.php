<?php

namespace App\Http\Controllers;

use App\Actions\Orders\PlaceOrder;
use App\Http\Requests\Checkout\CheckoutRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutSummaryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(CartService $cart, CheckoutSummaryService $checkoutSummary): View|RedirectResponse
    {
        if ($cart->count() === 0) {
            return redirect()
                ->route('cart.index')
                ->with('warning', 'Giỏ hàng trống. Vui lòng thêm sản phẩm trước khi thanh toán.');
        }

        try {
            $summary = $checkoutSummary->build();
        } catch (ValidationException $exception) {
            return redirect()
                ->route('cart.index')
                ->withErrors($exception->errors());
        }

        return view('checkout.create', [
            'summary' => $summary,
            'user' => auth()->user(),
        ]);
    }

    public function summary(CheckoutSummaryService $checkoutSummary): JsonResponse
    {
        try {
            $data = $checkoutSummary->build();
        } catch (ValidationException $exception) {
            return response()->json([
                'success' => false,
                'message' => collect($exception->errors())->flatten()->first() ?? 'Giỏ hàng không hợp lệ.',
                'errors' => $exception->errors(),
            ], 422);
        }

        if ($checkoutSummary->hasConflict()) {
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

    public function store(CheckoutRequest $request, PlaceOrder $placeOrder): RedirectResponse
    {
        try {
            $order = $placeOrder->execute($request->user(), $request->shippingData());
        } catch (ValidationException $exception) {
            return redirect()
                ->route('checkout.create')
                ->withErrors($exception->errors())
                ->withInput();
        }

        return redirect()
            ->route('checkout.success', $order)
            ->with('success', 'Đặt hàng thành công.');
    }

    public function success(Order $order): View
    {
        $this->authorize('view', $order);

        $order->load('items');

        return view('checkout.success', [
            'order' => $order,
        ]);
    }
}
