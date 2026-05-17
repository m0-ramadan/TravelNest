<?php

namespace App\Http\Controllers\Website;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TripController extends BaseWebsiteController
{
    public function index(Request $request)
    {
        $packages = Package::query()
            ->with(['currency', 'category', 'primaryCountry'])
            ->where('is_active', true)
            ->whereIn('package_type', ['travel_package', 'nile_cruise', 'deal', 'multi_country', 'custom'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = '%' . $request->q . '%';
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', $search)
                        ->orWhere('subtitle', 'like', $search)
                        ->orWhere('short_description', 'like', $search)
                        ->orWhere('description', 'like', $search);
                });
            })
            ->when($request->filled('type'), fn($query) => $query->where('package_type', $request->type))
            ->orderByDesc('is_featured')
            ->orderByRaw('sort_order IS NULL, sort_order ASC')
            ->paginate(12)
            ->withQueryString();

        return view('website.pages.trips.index', compact('packages'));
    }

    public function show(string $slug)
    {
        $package = Package::query()
            ->with([
                'currency',
                'category',
                'primaryCountry',
                'highlights',
                'itineraries',
                'inclusions',
                'prices.currency',
                'packageAttractions',
                'cruise',
                'reviews',
                'testimonials',
            ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $title = $this->translated($package->getRawOriginal('title') ?? $package->title);
        $subtitle = $this->translated($package->getRawOriginal('subtitle') ?? $package->subtitle);
        $shortDescription = $this->translated($package->getRawOriginal('short_description') ?? $package->short_description);
        $description = $this->translated($package->getRawOriginal('description') ?? $package->description) ?: $shortDescription;
        $schedule = $this->translated($package->getRawOriginal('schedule_text') ?? $package->schedule_text);
        $pickup = $this->translated($package->getRawOriginal('pickup_location') ?? $package->pickup_location);
        $dropoff = $this->translated($package->getRawOriginal('dropoff_location') ?? $package->dropoff_location);
        $destinations = $this->translated($package->getRawOriginal('destinations_text') ?? $package->destinations_text) ?: (string) ($package->route_text ?? '');
        $locationSummary = $this->translated($package->getRawOriginal('location_summary') ?? $package->location_summary);
        $heroImage = $this->imageUrl($package->featured_image, asset('website/photos/home2.webp'));

        $gallery = [];
        $galleryImages = $package->getRawOriginal('gallery_images') ?? $package->gallery_images;

        if (!empty($galleryImages)) {
            $decodedGallery = is_array($galleryImages) ? $galleryImages : json_decode((string) $galleryImages, true);

            if (is_array($decodedGallery)) {
                foreach ($decodedGallery as $img) {
                    $gallery[] = $this->imageUrl(
                        is_array($img) ? ($img['path'] ?? $img['url'] ?? null) : $img,
                        $heroImage
                    );
                }
            }
        }

        $imageRows = DB::table('images')
            ->where('imageable_type', 'App\\Models\\Package')
            ->where('imageable_id', $package->id)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->get();

        foreach ($imageRows as $img) {
            $gallery[] = $this->imageUrl($img->path, $heroImage);
        }

        $gallery = array_values(array_unique(array_filter($gallery)));

        if (empty($gallery)) {
            $gallery[] = $heroImage;
        }

        $itineraries = $package->itineraries
            ->map(function ($item) {
                $item->display_title = $this->transValue(
                    $item->getRawOriginal('title') ?? $item->title,
                    __('Day') . ' ' . $item->day_number
                );
                $item->display_description = $this->transValue($item->getRawOriginal('description') ?? $item->description, '');
                $item->display_overnight = $this->transValue($item->getRawOriginal('overnight_location') ?? ($item->overnight_location ?? ''), '');
                return $item;
            })
            ->values();

        $inclusions = $package->inclusions
            ->map(function ($item) {
                $rawContent = method_exists($item, 'getRawOriginal')
                    ? ($item->getRawOriginal('content') ?? $item->content)
                    : $item->content;

                $fallback = $item->description ?? $item->title ?? '';
                $item->display_content = __($this->transValue($rawContent, $fallback));

                return $item;
            })
            ->values();

        $included = $inclusions
            ->filter(fn($item) => in_array(($item->item_type ?? $item->type), ['included', null], true) && trim((string) $item->display_content) !== '')
            ->values();

        $excluded = $inclusions
            ->filter(fn($item) => in_array(($item->item_type ?? $item->type), ['excluded'], true) && trim((string) $item->display_content) !== '')
            ->values();

        $prices = $package->prices
            ->map(function ($price) use ($package) {
                $rawLabel = method_exists($price, 'getRawOriginal') ? ($price->getRawOriginal('label') ?? $price->label) : $price->label;
                $rawNotes = method_exists($price, 'getRawOriginal') ? ($price->getRawOriginal('notes') ?? $price->notes) : $price->notes;

                $price->display_label = $this->transValue($rawLabel, $price->room_type ?: $price->price_type ?: __('Package Price'));
                $price->display_notes = $this->transValue($rawNotes, '');
                $price->formatted_amount = $this->money($price->amount, $price->currency?->symbol ?: ($package->currency?->symbol ?: '$'));

                return $price;
            })
            ->values();

        $testimonials = $package->testimonials
            ->where('is_active', true)
            ->sortByDesc('is_featured')
            ->take(6)
            ->values();

        $reviews = $package->reviews
            ->where('is_approved', true)
            ->sortByDesc('id')
            ->take(6)
            ->values();

        $countries = DB::table('countries')
            ->orderBy('name')
            ->pluck('name')
            ->map(fn($name) => $this->transValue($name, $name))
            ->filter()
            ->values();

        if ($countries->isEmpty()) {
            $countries = collect([
                'Egypt',
                'United States',
                'United Kingdom',
                'Canada',
                'Australia',
                'Germany',
                'France',
                'Italy',
                'Spain',
                'Japan',
                'China',
                'India',
                'Saudi Arabia',
                'United Arab Emirates',
                'Morocco',
                'Jordan',
                'Turkey',
            ]);
        }

        $relatedPackages = Package::query()
            ->with(['currency'])
            ->where('is_active', true)
            ->where('id', '!=', $package->id)
            ->when($package->category_id, fn($query) => $query->where('category_id', $package->category_id))
            ->limit(3)
            ->get();

        return view('website.pages.packages.show', compact(
            'package',
            'relatedPackages',
            'title',
            'subtitle',
            'shortDescription',
            'description',
            'schedule',
            'pickup',
            'dropoff',
            'destinations',
            'locationSummary',
            'heroImage',
            'gallery',
            'itineraries',
            'included',
            'excluded',
            'prices',
            'testimonials',
            'reviews',
            'countries'
        ));
    }

    public function legacyShow(string $country, string $slug)
    {
        return $this->show($slug);
    }
}
