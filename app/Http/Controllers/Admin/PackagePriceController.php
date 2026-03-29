<?php

namespace App\Http\Controllers\Admin;

use App\Models\Package;
use App\Models\PackagePrice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackagePriceController extends Controller
{
    public function index(Request $request): View
    {
        $packagePrices = PackagePrice::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = '%' . $request->string('q') . '%';
                $query->where('label', 'like', $search)
                    ->orWhere('season_name', 'like', $search)
                    ->orWhere('room_type', 'like', $search)
                    ->orWhere('price_type', 'like', $search);
            })
            ->latest()
            ->paginate($this->perPage($request));

        return $this->view('admin.package-prices.index', compact('packagePrices'));
    }

    public function create(): View
    {
        $packages = Package::orderBy('title')->get();

        return $this->view('admin.package-prices.create', compact('packages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'package_id' => ['required', 'integer', 'exists:packages,id'],
            'label' => ['nullable', 'string', 'max:255'],
            'season_name' => ['nullable', 'string', 'max:255'],
            'price_type' => ['nullable', 'string', 'max:100'],
            'room_type' => ['nullable', 'string', 'max:100'],
            'pax_min' => ['nullable', 'integer'],
            'pax_max' => ['nullable', 'integer'],
            'group_size_min' => ['nullable', 'integer'],
            'group_size_max' => ['nullable', 'integer'],
            'amount' => ['required', 'numeric'],
            'currency_id' => ['nullable', 'integer', 'exists:currencies,id'],
            'valid_from' => ['nullable', 'date'],
            'valid_to' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        PackagePrice::create($data);

        return redirect()->route('admin.package-prices.index')->with('success', 'Package price created.');
    }

    public function show(PackagePrice $packagePrice): View
    {
        return $this->view('admin.package-prices.show', compact('packagePrice'));
    }

    public function edit(PackagePrice $packagePrice): View
    {
        $packages = Package::orderBy('title')->get();

        return $this->view('admin.package-prices.edit', compact('packagePrice', 'packages'));
    }

    public function update(Request $request, PackagePrice $packagePrice): RedirectResponse
    {
        $data = $request->validate([
            'package_id' => ['required', 'integer', 'exists:packages,id'],
            'label' => ['nullable', 'string', 'max:255'],
            'season_name' => ['nullable', 'string', 'max:255'],
            'price_type' => ['nullable', 'string', 'max:100'],
            'room_type' => ['nullable', 'string', 'max:100'],
            'pax_min' => ['nullable', 'integer'],
            'pax_max' => ['nullable', 'integer'],
            'group_size_min' => ['nullable', 'integer'],
            'group_size_max' => ['nullable', 'integer'],
            'amount' => ['required', 'numeric'],
            'currency_id' => ['nullable', 'integer', 'exists:currencies,id'],
            'valid_from' => ['nullable', 'date'],
            'valid_to' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $packagePrice->update($data);

        return redirect()->route('admin.package-prices.index')->with('success', 'Package price updated.');
    }

    public function destroy(PackagePrice $packagePrice): RedirectResponse
    {
        $packagePrice->delete();

        return redirect()->route('admin.package-prices.index')->with('success', 'Package price deleted.');
    }

    public function byPackage(Package $package): View
    {
        $packagePrices = $package->prices()->latest()->paginate(20);

        return $this->view('admin.package-prices.by-package', compact('package', 'packagePrices'));
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        if ($request->input('action') === 'delete') {
            PackagePrice::whereIn('id', (array) $request->input('ids', []))->delete();
        }

        return back()->with('success', 'Bulk action applied.');
    }
}
