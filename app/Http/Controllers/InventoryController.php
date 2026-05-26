<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::with('category')
            ->when($request->search, fn ($q, $term) => $q->search($term))
            ->when($request->category, function ($q, $slug) {
                $q->whereHas('category', fn ($c) => $c->where('slug', $slug));
            })
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->when($request->condition, fn ($q, $condition) => $q->where('condition', $condition))
            ->orderBy('item_code')
            ->paginate(15)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('admin.inventory.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.form', [
            'item' => new Item(),
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $data['item_code'] = sprintf('ITM-%03d', (Item::max('id') ?? 0) + 1);
        $data['slug'] = Str::slug($data['name'] . '-' . $data['item_code']);
        $data['available_stock'] = $data['stock'];
        $data['status'] = $data['available_stock'] > 0 ? Item::STATUS_AVAILABLE : Item::STATUS_UNAVAILABLE;
        $data['is_active'] = true;

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('items', 'public');
        }

        Item::create($data);

        return redirect()->route('admin.inventory.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Item $item)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.form', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $data = $this->validateData($request, $item);

        $stockDelta = (int) $data['stock'] - (int) $item->stock;
        if ($stockDelta !== 0) {
            $data['available_stock'] = max(0, (int) $item->available_stock + $stockDelta);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($item->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }
            $data['image_path'] = $request->file('image')->store('items', 'public');
        }

        // Handle image removal
        if ($request->boolean('remove_image') && ! $request->hasFile('image')) {
            if ($item->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }
            $data['image_path'] = null;
        }

        $item->update($data);

        return redirect()->route('admin.inventory.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Item $item)
    {
        if ($item->rentalDetails()->whereHas('rental', fn ($q) => $q->active())->exists()) {
            return back()->with('error', 'Barang sedang disewa, tidak dapat dihapus.');
        }

        $item->update(['is_active' => false]);
        return back()->with('success', 'Barang berhasil dinonaktifkan.');
    }

    protected function validateData(Request $request, ?Item $item = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'specifications' => ['nullable', 'string'],
            'stock' => ['required', 'integer', 'min:0'],
            'condition' => ['required', 'in:good,minor_damage,heavy_damage'],
            'price_per_day' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ]);
    }
}
