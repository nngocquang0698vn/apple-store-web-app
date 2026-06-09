<?php

namespace App\Http\Controllers\Concerns;

use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait RespondsWithCart
{
    protected function respondCartMutation(
        Request $request,
        CartService $cart,
        string $message,
        RedirectResponse $redirect,
        ?int $variantId = null,
        ?int $quantity = null,
    ): JsonResponse|RedirectResponse {
        if ($this->isAjaxRequest($request)) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $cart->buildSummary($variantId, $quantity),
            ]);
        }

        return $redirect->with('success', $message);
    }

    protected function respondCartValidationError(
        Request $request,
        ValidationException $exception,
        RedirectResponse $redirect,
    ): JsonResponse|RedirectResponse {
        if ($this->isAjaxRequest($request)) {
            throw $exception;
        }

        return $redirect
            ->withErrors($exception->errors())
            ->withInput();
    }

    protected function isAjaxRequest(Request $request): bool
    {
        return $request->ajax() || $request->wantsJson();
    }
}
