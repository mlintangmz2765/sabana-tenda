<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->withCount('items')->get();
        $popularItems = Item::active()->with('category')
            ->withCount(['rentalDetails as rented_count'])
            ->orderByDesc('rented_count')
            ->limit(8)
            ->get();
        $featuredItems = Item::active()->available()->with('category')->latest()->limit(4)->get();

        return view('home', compact('categories', 'popularItems', 'featuredItems'));
    }

    public function catalog()
    {
        $categories = Category::where('is_active', true)->get();
        $items = Item::active()
            ->with('category')
            ->when(request('search'), fn ($q, $term) => $q->search($term))
            ->when(request('category'), function ($q, $slug) {
                $q->whereHas('category', fn ($c) => $c->where('slug', $slug));
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('catalog', compact('categories', 'items'));
    }

    public function itemDetail(Item $item)
    {
        $item->load('category');
        $relatedItems = Item::active()
            ->where('category_id', $item->category_id)
            ->where('id', '!=', $item->id)
            ->limit(4)
            ->get();

        return view('item-detail', compact('item', 'relatedItems'));
    }

    public function about()
    {
        return view('about');
    }
}
