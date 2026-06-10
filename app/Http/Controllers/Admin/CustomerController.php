<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerIndexRequest;
use App\Http\Requests\Admin\UpdateCustomerStatusRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
    private const PER_PAGE = 15;

    public function index(CustomerIndexRequest $request): View
    {
        $filters = $request->filters();
        $search = trim((string) ($filters['q'] ?? ''));

        $customers = User::query()
            ->where('role', UserRole::Customer)
            ->withCount('orders')
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $inner) use ($search): void {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('admin.customers.index', [
            'customers' => $customers,
            'filters' => $filters,
        ]);
    }

    public function show(User $user): View
    {
        abort_unless($user->role === UserRole::Customer, 404);

        $user->loadCount('orders');
        $recentOrders = $user->orders()
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.customers.show', [
            'customer' => $user,
            'recentOrders' => $recentOrders,
        ]);
    }

    public function updateStatus(UpdateCustomerStatusRequest $request, User $user): RedirectResponse
    {
        abort_unless($user->role === UserRole::Customer, 404);

        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Không thể thay đổi trạng thái tài khoản của chính bạn.');
        }

        $user->status = $request->status();
        $user->save();

        $message = $request->status() === UserStatus::Blocked
            ? 'Đã khóa tài khoản khách hàng.'
            : 'Đã mở khóa tài khoản khách hàng.';

        return back()->with('success', $message);
    }
}
