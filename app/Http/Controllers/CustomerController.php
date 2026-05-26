<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::withCount('rentals')
            ->when($request->search, function ($q, $term) {
                $q->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', "%{$term}%")
                        ->orWhere('phone', 'like', "%{$term}%")
                        ->orWhere('customer_code', 'like', "%{$term}%")
                        ->orWhere('id_card_number', 'like', "%{$term}%");
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        $customer->load(['rentals.details.item', 'rentals.returnTransaction']);
        return view('admin.customers.show', compact('customer'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:160'],
            'address' => ['required', 'string'],
            'id_card_type' => ['required', 'in:KTP,SIM,Passport'],
            'id_card_number' => ['required', 'string', 'max:30'],
        ]);

        $data['customer_code'] = sprintf('CUST-%03d', (Customer::max('id') ?? 0) + 1);

        Customer::create($data);

        return back()->with('success', 'Customer berhasil ditambahkan.');
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:160'],
            'address' => ['required', 'string'],
            'id_card_type' => ['required', 'in:KTP,SIM,Passport'],
            'id_card_number' => ['required', 'string', 'max:30'],
        ]);

        $customer->update($data);
        return back()->with('success', 'Customer berhasil diperbarui.');
    }
}
