<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $clients = Client::query()
            ->when($request->filled('q'), fn ($q) => $q->where('id', 'like', '%' . $request->string('q') . '%'))
            ->latest()
            ->paginate($this->perPage($request));

        return $this->view('admin.clients.index', ['clients' => $clients]);
    }

    public function create(): View
    {
        return $this->view('admin.clients.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['nullable', 'email'],
            'first_name' => ['nullable', 'string'],
            'last_name' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'country_id' => ['nullable', 'integer'],
            'date_of_birth' => ['nullable', 'date'],
            'passport_number' => ['nullable', 'string'],
            'passport_expiry' => ['nullable', 'date'],
            'nationality' => ['nullable', 'string'],
            'newsletter_subscribed' => ['nullable', 'boolean'],
            'total_bookings' => ['nullable', 'integer'],
            'total_spent' => ['nullable', 'numeric'],
            'last_activity' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        Client::create($data);

        return $this->success('admin.clients.index', 'Client created.');
    }

    public function show(Client $client): View
    {
        return $this->view('admin.clients.show', compact('client'));
    }

    public function edit(Client $client): View
    {
        return $this->view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['nullable', 'email'],
            'first_name' => ['nullable', 'string'],
            'last_name' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'country_id' => ['nullable', 'integer'],
            'date_of_birth' => ['nullable', 'date'],
            'passport_number' => ['nullable', 'string'],
            'passport_expiry' => ['nullable', 'date'],
            'nationality' => ['nullable', 'string'],
            'newsletter_subscribed' => ['nullable', 'boolean'],
            'total_bookings' => ['nullable', 'integer'],
            'total_spent' => ['nullable', 'numeric'],
            'last_activity' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $client->update($data);

        return $this->success('admin.clients.index', 'Client updated.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return $this->success('admin.clients.index', 'Client deleted.');
    }

    public function export()
    {
        return response()->json(Client::latest()->get());
    }

    public function bookings(Client $client): View
    {
        $bookings = $client->bookings()->latest()->paginate(20);

        return $this->view('admin.clients.bookings', compact('client', 'bookings'));
    }

    public function inquiries(Client $client): View
    {
        $inquiries = $client->inquiries()->latest()->paginate(20);

        return $this->view('admin.clients.inquiries', compact('client', 'inquiries'));
    }

    public function toggleStatus(Client $client): RedirectResponse
    {
        $client->update(['is_active' => ! (bool) ($client->is_active ?? true)]);

        return back()->with('success', 'Client status updated.');
    }

}
