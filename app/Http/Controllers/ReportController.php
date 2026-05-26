<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Rental;
use App\Models\ReturnTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'this_month');
        [$start, $end] = $this->resolvePeriod($period, $request);

        $rentalsQuery = Rental::whereBetween('rental_date', [$start, $end]);
        $returnsQuery = ReturnTransaction::whereBetween('actual_return_date', [$start, $end]);

        $stats = [
            'total_revenue' => (int) $rentalsQuery->sum('total_cost') + (int) $returnsQuery->sum('total_fine'),
            'total_rentals' => (int) $rentalsQuery->count(),
            'avg_per_transaction' => (int) ($rentalsQuery->count() > 0
                ? $rentalsQuery->sum('total_cost') / $rentalsQuery->count() : 0),
            'late_returns' => (int) $returnsQuery->where('late_days', '>', 0)->count(),
            'damaged_items' => (int) DB::table('damaged_items')->whereBetween('created_at', [$start, $end])->count(),
            'unique_customers' => (int) Rental::whereBetween('rental_date', [$start, $end])
                ->distinct('customer_id')->count('customer_id'),
        ];

        $revenueOverTime = Rental::select(
                DB::raw('DATE(rental_date) as date'),
                DB::raw('SUM(total_cost) as revenue'),
                DB::raw('COUNT(*) as transactions'),
            )
            ->whereBetween('rental_date', [$start, $end])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topItems = DB::table('rental_details')
            ->join('rentals', 'rental_details.rental_id', '=', 'rentals.id')
            ->join('items', 'rental_details.item_id', '=', 'items.id')
            ->whereBetween('rentals.rental_date', [$start, $end])
            ->select(
                'items.name',
                'items.item_code',
                DB::raw('SUM(rental_details.quantity) as total_rented'),
                DB::raw('SUM(rental_details.subtotal) as total_revenue'),
            )
            ->groupBy('items.id', 'items.name', 'items.item_code')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        $categoryBreakdown = DB::table('rental_details')
            ->join('rentals', 'rental_details.rental_id', '=', 'rentals.id')
            ->join('items', 'rental_details.item_id', '=', 'items.id')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->whereBetween('rentals.rental_date', [$start, $end])
            ->select(
                'categories.name',
                DB::raw('SUM(rental_details.subtotal) as revenue'),
                DB::raw('SUM(rental_details.quantity) as quantity'),
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')
            ->get();

        $damageReport = DB::table('damaged_items')
            ->join('items', 'damaged_items.item_id', '=', 'items.id')
            ->whereBetween('damaged_items.created_at', [$start, $end])
            ->select(
                'items.name',
                DB::raw('COUNT(*) as damage_count'),
                DB::raw('SUM(damaged_items.repair_cost) as total_repair'),
            )
            ->groupBy('items.id', 'items.name')
            ->orderByDesc('total_repair')
            ->limit(10)
            ->get();

        return view('admin.reports.index', compact(
            'stats', 'revenueOverTime', 'topItems', 'categoryBreakdown', 'damageReport',
            'start', 'end', 'period'
        ));
    }

    public function export(Request $request)
    {
        $period = $request->input('period', 'this_month');
        [$start, $end] = $this->resolvePeriod($period, $request);

        $rentals = Rental::with(['customer', 'details.item', 'returnTransaction'])
            ->whereBetween('rental_date', [$start, $end])
            ->orderBy('rental_date')
            ->get();

        $filename = 'sabana_rentals_' . $start->format('Y-m-d') . '_to_' . $end->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($rentals) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Kode Sewa', 'Tanggal Sewa', 'Tanggal Kembali', 'Customer', 'Telepon',
                'Total Biaya', 'Status', 'Denda', 'Tanggal Kembali Aktual',
            ]);

            foreach ($rentals as $rental) {
                $return = $rental->returnTransaction;
                fputcsv($handle, [
                    $rental->rental_code,
                    $rental->rental_date->format('Y-m-d'),
                    $rental->return_date->format('Y-m-d'),
                    $rental->customer->name,
                    $rental->customer->phone,
                    $rental->total_cost,
                    $rental->statusLabel(),
                    $return?->total_fine ?? 0,
                    $return?->actual_return_date?->format('Y-m-d'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    protected function resolvePeriod(string $period, Request $request): array
    {
        $now = Carbon::now();
        return match ($period) {
            'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'this_week' => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()],
            'last_month' => [
                $now->copy()->subMonth()->startOfMonth(),
                $now->copy()->subMonth()->endOfMonth(),
            ],
            'this_year' => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
            'custom' => [
                Carbon::parse($request->input('start_date', $now->copy()->startOfMonth()))->startOfDay(),
                Carbon::parse($request->input('end_date', $now))->endOfDay(),
            ],
            default => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
        };
    }
}
