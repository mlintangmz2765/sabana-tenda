<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Services\ReturnService;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function __construct(protected ReturnService $returnService) {}

    public function index(Request $request)
    {
        $returns = \App\Models\ReturnTransaction::with(['rental.customer', 'staff'])
            ->when($request->search, function ($q, $term) {
                $q->whereHas('rental', fn ($r) => $r->where('rental_code', 'like', "%{$term}%"))
                    ->orWhere('return_code', 'like', "%{$term}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $pendingReturns = Rental::with(['customer', 'details.item'])
            ->active()
            ->orderBy('return_date')
            ->limit(8)
            ->get();

        return view('staff.returns.index', compact('returns', 'pendingReturns'));
    }

    public function create(Request $request)
    {
        $rental = null;
        if ($request->rental_code) {
            $rental = Rental::with(['customer', 'details.item'])
                ->where('rental_code', $request->rental_code)
                ->whereIn('rental_status', [Rental::STATUS_ACTIVE, Rental::STATUS_LATE])
                ->first();
        }

        return view('staff.returns.create', compact('rental'));
    }

    public function store(Request $request, Rental $rental)
    {
        $data = $request->validate([
            'actual_return_date' => ['required', 'date'],
            'condition_check' => ['nullable', 'string', 'max:500'],
            'payment_status' => ['required', 'in:paid,unpaid,waived'],
            'damages' => ['nullable', 'array'],
            'damages.*.item_id' => ['nullable', 'exists:items,id'],
            'damages.*.damage_level' => ['nullable', 'in:minor,heavy,lost'],
            'damages.*.description' => ['nullable', 'string', 'max:300'],
            'damages.*.repair_cost' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['damages'] = collect($data['damages'] ?? [])
            ->filter(fn ($row) => ! empty($row['item_id']) && ! empty($row['damage_level']))
            ->values()
            ->all();

        $data['user_id'] = $request->user()->id;

        $returnTx = $this->returnService->processReturn($rental, $data);

        return redirect()
            ->route('staff.rentals.show', $rental)
            ->with('success', "Pengembalian {$returnTx->return_code} berhasil diproses.");
    }
}
