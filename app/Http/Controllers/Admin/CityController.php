<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CityController extends Controller
{
    public function index(Request $request): View
    {
        $cities = City::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhereHas('country', function ($countryQuery) use ($search) {
                            $countryQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($request->filled('country_id'), function ($query) use ($request) {
                $query->where('country_id', $request->country_id);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                if ($request->status === 'active') {
                    $query->where('is_active', true);
                } elseif ($request->status === 'inactive') {
                    $query->where('is_active', false);
                }
            })
            ->latest()
            ->paginate($this->perPage($request))
            ->withQueryString();

        $countries = Country::where('is_active', true)->get();

        return $this->view('admin.cities.index', [
            'cities' => $cities,
            'countries' => $countries,
        ]);
    }

    public function create(): View
    {
        $countries = Country::where('is_active', true)->get();

        return $this->view('admin.cities.create', compact('countries'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'country_id' => ['nullable', 'integer'],
            'name' => ['nullable', 'string'],
            'slug' => ['nullable', 'string'],
        ]);

        City::create($data);

        return $this->success('admin.cities.index', 'City created.');
    }

    public function show(City $city): View
    {
        return $this->view('admin.cities.show', compact('city'));
    }

    public function edit(City $city): View
    {
        $countries = Country::where('is_active', true)->get();
        return $this->view('admin.cities.edit', compact('city', 'countries'));
    }

    public function update(Request $request, City $city): RedirectResponse
    {
        $data = $request->validate([
            'country_id' => ['nullable', 'integer'],
            'name' => ['nullable', 'string'],
            'slug' => ['nullable', 'string'],
        ]);

        $city->update($data);

        return $this->success('admin.cities.index', 'City updated.');
    }

    public function destroy(City $city): RedirectResponse
    {
        $city->delete();

        return $this->success('admin.cities.index', 'City deleted.');
    }
}
