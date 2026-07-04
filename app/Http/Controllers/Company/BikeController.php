<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Bike;
use App\Models\BikeCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BikeController extends Controller
{
    public function index(): View
    {
        $company = Auth::user()->company;
        $bikes = $company->bikes()->with('category', 'images')->latest()->get();
        return view('company.bikes.index', compact('bikes'));
    }

    public function create(): View
    {
        $categories = BikeCategory::where('is_active', true)->orderBy('name')->get();
        return view('company.bikes.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $company = Auth::user()->company;

        if ($company->verification_status !== 'verified') {
            return back()->with('error', 'Your company must be verified before you can list bikes.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:bike_categories,id'],
            'year' => ['nullable', 'integer', 'min:2000', 'max:' . date('Y') + 1],
            'engine_capacity' => ['nullable', 'string', 'max:20'],
            'fuel_type' => ['nullable', 'string', 'max:30'],
            'transmission' => ['nullable', 'string', 'max:30'],
            'mileage' => ['nullable', 'string', 'max:20'],
            'color' => ['nullable', 'string', 'max:50'],
            'bike_number' => ['nullable', 'string', 'max:50'],
            'registration_number' => ['nullable', 'string', 'max:50'],
            'vin' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:5000'],
            'features' => ['nullable', 'string'],
            'specifications' => ['nullable', 'string'],
            'rental_rules' => ['nullable', 'string'],
            'hourly_price' => ['nullable', 'numeric', 'min:0'],
            'daily_price' => ['required', 'numeric', 'min:0'],
            'weekly_price' => ['nullable', 'numeric', 'min:0'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:4096'],
        ]);

        $bike = $company->bikes()->create([
            'name' => $request->name,
            'brand' => $request->brand,
            'model' => $request->model,
            'category_id' => $request->category_id,
            'year' => $request->year,
            'engine_capacity' => $request->engine_capacity,
            'fuel_type' => $request->fuel_type,
            'transmission' => $request->transmission,
            'mileage' => $request->mileage,
            'color' => $request->color,
            'bike_number' => $request->bike_number,
            'registration_number' => $request->registration_number,
            'vin' => $request->vin,
            'description' => $request->description,
            'features' => $request->features ? array_map('trim', explode("\n", $request->features)) : null,
            'specifications' => $request->specifications ? array_map('trim', explode("\n", $request->specifications)) : null,
            'rental_rules' => $request->rental_rules ? array_map('trim', explode("\n", $request->rental_rules)) : null,
            'hourly_price' => $request->hourly_price,
            'daily_price' => $request->daily_price,
            'weekly_price' => $request->weekly_price,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('bikes/' . $bike->id, 'public');
                $bike->images()->create([
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('company.bikes.index')->with('success', 'Bike listed successfully.');
    }

    public function edit(Bike $bike): View
    {
        $this->authorize('update', $bike);
        $categories = BikeCategory::where('is_active', true)->orderBy('name')->get();
        return view('company.bikes.edit', compact('bike', 'categories'));
    }

    public function update(Request $request, Bike $bike): RedirectResponse
    {
        $this->authorize('update', $bike);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:bike_categories,id'],
            'year' => ['nullable', 'integer', 'min:2000', 'max:' . date('Y') + 1],
            'engine_capacity' => ['nullable', 'string', 'max:20'],
            'fuel_type' => ['nullable', 'string', 'max:30'],
            'transmission' => ['nullable', 'string', 'max:30'],
            'mileage' => ['nullable', 'string', 'max:20'],
            'color' => ['nullable', 'string', 'max:50'],
            'bike_number' => ['nullable', 'string', 'max:50'],
            'registration_number' => ['nullable', 'string', 'max:50'],
            'vin' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:5000'],
            'features' => ['nullable', 'string'],
            'specifications' => ['nullable', 'string'],
            'rental_rules' => ['nullable', 'string'],
            'hourly_price' => ['nullable', 'numeric', 'min:0'],
            'daily_price' => ['required', 'numeric', 'min:0'],
            'weekly_price' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'in:active,inactive,maintenance'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:4096'],
        ]);

        $bike->update([
            'name' => $request->name,
            'brand' => $request->brand,
            'model' => $request->model,
            'category_id' => $request->category_id,
            'year' => $request->year,
            'engine_capacity' => $request->engine_capacity,
            'fuel_type' => $request->fuel_type,
            'transmission' => $request->transmission,
            'mileage' => $request->mileage,
            'color' => $request->color,
            'bike_number' => $request->bike_number,
            'registration_number' => $request->registration_number,
            'vin' => $request->vin,
            'description' => $request->description,
            'features' => $request->features ? array_map('trim', explode("\n", $request->features)) : null,
            'specifications' => $request->specifications ? array_map('trim', explode("\n", $request->specifications)) : null,
            'rental_rules' => $request->rental_rules ? array_map('trim', explode("\n", $request->rental_rules)) : null,
            'hourly_price' => $request->hourly_price,
            'daily_price' => $request->daily_price,
            'weekly_price' => $request->weekly_price,
            'status' => $request->status,
        ]);

        if ($request->hasFile('images')) {
            $existingCount = $bike->images()->count();
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('bikes/' . $bike->id, 'public');
                $bike->images()->create([
                    'image_path' => $path,
                    'is_primary' => $existingCount === 0 && $index === 0,
                    'sort_order' => $existingCount + $index,
                ]);
            }
        }

        return redirect()->route('company.bikes.index')->with('success', 'Bike updated successfully.');
    }

    public function destroy(Bike $bike): RedirectResponse
    {
        $this->authorize('update', $bike);
        $bike->update(['status' => 'inactive', 'is_available' => false]);
        $bike->delete();

        return redirect()->route('company.bikes.index')->with('success', 'Bike removed successfully.');
    }

    public function toggleAvailability(Bike $bike): RedirectResponse
    {
        $this->authorize('update', $bike);
        $bike->update(['is_available' => !$bike->is_available]);

        return back()->with('success', 'Bike availability updated.');
    }
}
