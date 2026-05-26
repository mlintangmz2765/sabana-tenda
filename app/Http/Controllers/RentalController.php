<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Rental;
use App\Services\RentalService;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function __construct(protected RentalService $rentalService) {}

    public function index(Request $request)
    {
        $rentals = Rental::with(['customer', 'staff', 'details.item', 'returnTransaction'])
            ->when($request->status, fn ($q, $s) => $q->where('rental_status', $s))
            ->when($request->search, function ($q, $term) {
                $q->where(function ($inner) use ($term) {
                    $inner->where('rental_code', 'like', "%{$term}%")
                        ->orWhereHas('customer', fn ($c) => $c->where('name', 'like', "%{$term}%")
                            ->orWhere('phone', 'like', "%{$term}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('staff.rentals.index', compact('rentals'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $items = Item::active()->available()->with('category')->orderBy('name')->get();
        return view('staff.rentals.create', compact('customers', 'items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'new_customer_name' => ['required_without:customer_id', 'string', 'max:120'],
            'new_customer_phone' => ['required_without:customer_id', 'string', 'max:20'],
            'new_customer_address' => ['required_without:customer_id', 'string'],
            'new_customer_id_card' => ['required_without:customer_id', 'string', 'max:30'],
            'rental_date' => ['required', 'date'],
            'return_date' => ['required', 'date', 'after:rental_date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'exists:items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        if (empty($data['customer_id'])) {
            $customer = Customer::create([
                'customer_code' => sprintf('CUST-%03d', (Customer::max('id') ?? 0) + 1),
                'name' => $data['new_customer_name'],
                'phone' => $data['new_customer_phone'],
                'address' => $data['new_customer_address'],
                'id_card_type' => 'KTP',
                'id_card_number' => $data['new_customer_id_card'],
            ]);
            $data['customer_id'] = $customer->id;
        }

        $data['user_id'] = $request->user()->id;

        $rental = $this->rentalService->createRental($data);

        return redirect()
            ->route('staff.rentals.show', $rental)
            ->with('success', "Transaksi {$rental->rental_code} berhasil dibuat.");
    }

    public function show(Rental $rental)
    {
        $rental->load(['customer', 'staff', 'details.item.category', 'returnTransaction.damagedItems.item']);
        return view('staff.rentals.show', compact('rental'));
    }

    public function invoice(Rental $rental)
    {
        $rental->load(['customer', 'details.item']);
        return view('staff.rentals.invoice', compact('rental'));
    }

    /**
     * Customer-facing booking form.
     */
    public function customerBooking()
    {
        $items = Item::active()->available()->with('category')->orderBy('name')->get();
        return view('customer.booking', compact('items'));
    }

    /**
     * Process customer booking — creates a rental via the same RentalService.
     */
    public function customerBookingStore(Request $request)
    {
        $data = $request->validate([
            'rental_date' => ['required', 'date', 'after_or_equal:today'],
            'return_date' => ['required', 'date', 'after:rental_date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'exists:items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        // Find or create the customer record linked to this user
        $user = $request->user();
        $customer = Customer::where('user_id', $user->id)->first();

        if (! $customer) {
            $customer = Customer::create([
                'customer_code' => sprintf('CUST-%03d', (Customer::max('id') ?? 0) + 1),
                'user_id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone ?? '-',
                'email' => $user->email,
                'address' => '-',
                'id_card_type' => 'KTP',
                'id_card_number' => '-',
            ]);
        }

        $data['customer_id'] = $customer->id;
        $data['user_id'] = $user->id;

        $rental = $this->rentalService->createRental($data);

        return redirect()
            ->route('customer.dashboard')
            ->with('success', "Booking berhasil! Kode transaksi: {$rental->rental_code}. Silakan datang ke outlet untuk pengambilan barang.");
    }
}
