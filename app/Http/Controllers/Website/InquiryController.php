<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class InquiryController extends BaseWebsiteController
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'nationality' => ['nullable', 'string', 'max:120'],
            'travel_date' => ['nullable', 'date'],
            'daterange-single' => ['nullable', 'string', 'max:120'],
            'adults' => ['nullable', 'integer', 'min:1'],
            'child' => ['nullable', 'integer', 'min:0'],
            'children' => ['nullable', 'integer', 'min:0'],
            'comment' => ['nullable', 'string'],
            'message' => ['nullable', 'string'],
            'title' => ['nullable', 'string', 'max:255'],
        ]);

        $travelDate = $request->input('travel_date');
        if (!$travelDate && $request->filled('daterange-single')) {
            try {
                $travelDate = date('Y-m-d', strtotime($request->input('daterange-single')));
            } catch (\Throwable $e) {
                $travelDate = null;
            }
        }

        $data = [
            'package_id' => $request->input('package_id'),
            'inquiry_type' => 'package',
            'full_name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $request->input('phone'),
            'country_name' => $request->input('nationality'),
            'travel_date' => $travelDate,
            'adults' => (int) $request->input('adults', 1),
            'children' => (int) $request->input('children', $request->input('child', 0)),
            'source' => url()->previous(),
            'message' => trim(($request->input('comment') ?: $request->input('message') ?: '') . "\n\nTour: " . $request->input('title')),
            'status' => 'new',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        /* حماية لو نسخة قاعدة البيانات عندك ناقصة أعمدة */
        $columns = Schema::getColumnListing('inquiries');
        $data = array_intersect_key($data, array_flip($columns));

        DB::table('inquiries')->insert($data);

        return back()->with('success', 'Your enquiry has been sent successfully. We will contact you soon.');
    }
}
