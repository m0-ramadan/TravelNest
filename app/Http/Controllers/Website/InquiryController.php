<?php

namespace App\Http\Controllers\Website;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InquiryController extends BaseWebsiteController
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_id' => ['nullable', 'integer', 'exists:packages,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'nationality' => ['nullable', 'string', 'max:120'],
            'travel_date' => ['nullable', 'date'],
            'daterange-single' => ['nullable', 'string', 'max:120'],
            'adults' => ['nullable', 'integer', 'min:1'],
            'child' => ['nullable', 'integer', 'min:0'],
            'children' => ['nullable', 'integer', 'min:0'],
            'infants' => ['nullable', 'integer', 'min:0'],
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

        $package = !empty($validated['package_id'])
            ? Package::query()->with('currency')->find($validated['package_id'])
            : null;

        $adults = (int) $request->input('adults', 1);
        $children = (int) $request->input('children', $request->input('child', 0));
        $infants = (int) $request->input('infants', 0);

        $adultPrice = (float) ($package?->adult_price ?? 0);
        $childPrice = (float) ($package?->child_price ?? 0);
        $infantPrice = (float) ($package?->infant_price ?? 0);
        $calculatedTotal = ($adults * $adultPrice) + ($children * $childPrice) + ($infants * $infantPrice);

        $data = [
            'package_id' => $request->input('package_id'),
            'inquiry_type' => 'package',
            'full_name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $request->input('phone'),
            'country_name' => $request->input('nationality'),
            'travel_date' => $travelDate,
            'budget' => $calculatedTotal > 0 ? $calculatedTotal : null,
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
            'source' => url()->previous(),
            'message' => trim(collect([
                $request->input('comment') ?: $request->input('message') ?: '',
                'Tour: ' . $request->input('title'),
                $package ? sprintf(
                    'Calculated total: %s%s',
                    $package->currency?->symbol ?? '$',
                    number_format($calculatedTotal, 2)
                ) : null,
            ])->filter()->implode("\n\n")),
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
