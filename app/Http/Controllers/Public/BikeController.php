<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Bike;
use App\Models\BikeCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BikeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Bike::available()
            ->with(['category', 'images' => fn($q) => $q->where('is_primary', true), 'company']);

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhereHas('company', fn($q) => $q->where('company_name', 'like', "%{$search}%"));
            });
        }

        if ($brand = $request->brand) {
            $query->where('brand', $brand);
        }

        if ($category = $request->category) {
            $query->whereHas('category', fn($q) => $q->where('slug', $category));
        }

        if ($fuelType = $request->fuel_type) {
            $query->where('fuel_type', $fuelType);
        }

        if ($transmission = $request->transmission) {
            $query->where('transmission', $transmission);
        }

        if ($minPrice = $request->min_price) {
            $query->where('daily_price', '>=', $minPrice);
        }

        if ($maxPrice = $request->max_price) {
            $query->where('daily_price', '<=', $maxPrice);
        }

        $sort = $request->sort ?? 'newest';
        match ($sort) {
            'price_low' => $query->orderBy('daily_price'),
            'price_high' => $query->orderByDesc('daily_price'),
            'name' => $query->orderBy('name'),
            default => $query->latest(),
        };

        $bikes = $query->paginate(12)->withQueryString();

        $brands = Bike::available()->select('brand')->distinct()->orderBy('brand')->pluck('brand');
        $categories = BikeCategory::where('is_active', true)->orderBy('name')->get();

        return view('public.bikes.index', compact('bikes', 'brands', 'categories'));
    }

    public function show(Bike $bike): View
    {
        $bike->load([
            'category',
            'images' => fn($q) => $q->orderBy('sort_order'),
            'company',
            'reviews' => fn($q) => $q->latest(),
            'reviews.customer.user',
        ]);

        abort_if($bike->status !== 'active' || !$bike->is_available, 404);

        $relatedBikes = Bike::available()
            ->where('id', '!=', $bike->id)
            ->where(function ($q) use ($bike) {
                $q->where('brand', $bike->brand)
                  ->orWhere('category_id', $bike->category_id);
            })
            ->with(['images' => fn($q) => $q->where('is_primary', true)])
            ->limit(4)
            ->get();

        return view('public.bikes.show', compact('bike', 'relatedBikes'));
    }
}
