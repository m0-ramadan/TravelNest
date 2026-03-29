<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = Booking::query()
            ->when($request->filled('q'), fn ($q) => $q->where('id', 'like', '%' . $request->string('q') . '%'))
            ->latest()
            ->paginate($this->perPage($request));

        return $this->view('admin.bookings.index', ['bookings' => $bookings]);
    }

    public function create(): View
    {
        return $this->view('admin.bookings.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'client_id' => ['nullable', 'integer'],
            'inquiry_id' => ['nullable', 'integer'],
            'package_id' => ['nullable', 'integer'],
            'booking_number' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'total_amount' => ['nullable', 'numeric'],
            'paid_amount' => ['nullable', 'numeric'],
            'payment_status' => ['nullable', 'string'],
            'booking_date' => ['nullable', 'date'],
            'travel_date' => ['nullable', 'date'],
            'adults' => ['nullable', 'integer'],
            'children' => ['nullable', 'integer'],
            'special_requests' => ['nullable', 'string'],
            'confirmed_at' => ['nullable', 'date'],
            'cancelled_at' => ['nullable', 'date'],
        ]);

        Booking::create($data);

        return $this->success('admin.bookings.index', 'Booking created.');
    }

    public function show(Booking $booking): View
    {
        return $this->view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking): View
    {
        return $this->view('admin.bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'client_id' => ['nullable', 'integer'],
            'inquiry_id' => ['nullable', 'integer'],
            'package_id' => ['nullable', 'integer'],
            'booking_number' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'total_amount' => ['nullable', 'numeric'],
            'paid_amount' => ['nullable', 'numeric'],
            'payment_status' => ['nullable', 'string'],
            'booking_date' => ['nullable', 'date'],
            'travel_date' => ['nullable', 'date'],
            'adults' => ['nullable', 'integer'],
            'children' => ['nullable', 'integer'],
            'special_requests' => ['nullable', 'string'],
            'confirmed_at' => ['nullable', 'date'],
            'cancelled_at' => ['nullable', 'date'],
        ]);

        $booking->update($data);

        return $this->success('admin.bookings.index', 'Booking updated.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        return $this->success('admin.bookings.index', 'Booking deleted.');
    }

    public function statistics()
    {
        return response()->json([
            'total' => Booking::count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'pending' => Booking::where('status', 'pending')->count(),
        ]);
    }

    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $request->validate(['status' => ['required', 'string']]);
        $booking->update(['status' => $request->input('status')]);

        return back()->with('success', 'Booking status updated.');
    }

    public function print(Booking $booking): View
    {
        return $this->view('admin.bookings.print', compact('booking'));
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        if ($request->input('action') === 'delete') {
            Booking::whereIn('id', (array) $request->input('ids', []))->delete();
        }

        return back()->with('success', 'Bulk action applied.');
    }

}
