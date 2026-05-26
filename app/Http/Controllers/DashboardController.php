<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Rental;
use App\Models\ReturnTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        $now = Carbon::now();
        $startMonth = $now->copy()->startOfMonth();
        $endMonth = $now->copy()->endOfMonth();

        $stats = [
            'total_items' => Item::sum('stock'),
            'total_items_unique' => Item::count(),
            'items_rented' => Item::sum(DB::raw('stock - available_stock')),
            'items_available' => Item::sum('available_stock'),
            'late_returns' => Rental::active()->where('return_date', '<', $now->toDateString())->count(),
            'damaged_items' => Item::whereIn('condition', [Item::CONDITION_MINOR, Item::CONDITION_HEAVY])->count(),
            'revenue_this_month' => Rental::whereBetween('rental_date', [$startMonth, $endMonth])->sum('total_cost')
                + ReturnTransaction::whereBetween('actual_return_date', [$startMonth, $endMonth])->sum('total_fine'),
            'transactions_this_month' => Rental::whereBetween('rental_date', [$startMonth, $endMonth])->count(),
            'total_customers' => Customer::count(),
            'total_staff' => User::whereIn('role', [User::ROLE_STAFF, User::ROLE_ADMIN])->count(),
        ];

        $rentalTrend = Rental::select(
                DB::raw('DATE(rental_date) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_cost) as revenue'),
            )
            ->where('rental_date', '>=', $now->copy()->subDays(29)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $popularItems = Item::select('items.*')
            ->withCount(['rentalDetails as rented_count' => function ($query) use ($startMonth) {
                $query->whereHas('rental', function ($q) use ($startMonth) {
                    $q->where('rental_date', '>=', $startMonth->copy()->subMonths(5));
                });
            }])
            ->orderByDesc('rented_count')
            ->limit(5)
            ->get();

        $recentRentals = Rental::with(['customer', 'details.item'])
            ->latest('rental_date')
            ->limit(8)
            ->get();

        $monthlyRevenue = Rental::select(
                DB::raw("DATE_FORMAT(rental_date, '%Y-%m') as month"),
                DB::raw('SUM(total_cost) as revenue'),
                DB::raw('COUNT(*) as transactions'),
            )
            ->where('rental_date', '>=', $now->copy()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'rentalTrend', 'popularItems', 'recentRentals', 'monthlyRevenue'
        ));
    }

    public function staff()
    {
        $now = Carbon::now();

        $stats = [
            'active_rentals' => Rental::active()->count(),
            'returns_today' => Rental::active()->whereDate('return_date', $now->toDateString())->count(),
            'late_rentals' => Rental::active()->where('return_date', '<', $now->toDateString())->count(),
            'items_available' => Item::sum('available_stock'),
        ];

        $todayReturns = Rental::with(['customer', 'details.item'])
            ->active()
            ->where('return_date', '<=', $now->copy()->addDay()->toDateString())
            ->orderBy('return_date')
            ->limit(10)
            ->get();

        $recentRentals = Rental::with(['customer', 'staff'])
            ->latest()
            ->limit(8)
            ->get();

        return view('staff.dashboard', compact('stats', 'todayReturns', 'recentRentals'));
    }

    public function customer()
    {
        $user = auth()->user();
        $customer = Customer::where('user_id', $user->id)->first();

        $rentals = collect();
        $stats = [
            'active' => 0,
            'completed' => 0,
            'total_spent' => 0,
        ];

        if ($customer) {
            $rentals = Rental::with(['details.item', 'returnTransaction'])
                ->where('customer_id', $customer->id)
                ->latest()
                ->limit(10)
                ->get();

            $stats = [
                'active' => $customer->activeRentalsCount(),
                'completed' => $customer->rentals()->where('rental_status', Rental::STATUS_COMPLETED)->count(),
                'total_spent' => $customer->totalSpent(),
            ];
        }

        return view('customer.dashboard', compact('customer', 'rentals', 'stats'));
    }
}
