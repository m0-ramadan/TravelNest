<?php

namespace App\Http\Controllers\Website;

use App\Models\Article;
use App\Models\City;
use App\Models\Package;
use App\Models\Testimonial;

class HomeController extends BaseWebsiteController
{
    public function index()
    {
        $packages = Package::query()
            ->with(['currency', 'highlights', 'tags', 'prices'])
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->orderByDesc('is_best_seller')
            ->orderByRaw('sort_order IS NULL, sort_order ASC')
            ->latest('id')
            ->limit(6)
            ->get();

        $featuredPackages = $packages->map(fn(Package $package) => $this->packageCard($package));

        $destinations = City::query()
            ->with('country')
            ->withCount(['attractions', 'packages'])
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit(6)
            ->get()
            ->map(function (City $city) {
                return [
                    'title' => $this->translated($city->getRawOriginal('name') ?? $city->name),
                    'description' => $this->shortText($city->getRawOriginal('short_description') ?: $city->getRawOriginal('description'), 190),
                    'image' => $this->imageUrl($city->featured_image ?: $city->hero_image, 'website/photos/Dest/Egypt.jpg'),
                    'url' => route('website.destinations.show', $city->slug),
                    'country' => $this->translated($city->country?->getRawOriginal('name') ?? $city->country?->name),
                    'sites_count' => $city->attractions_count,
                    'packages_count' => $city->packages_count,
                ];
            });

        $latestArticles = Article::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('published_at')->orWhere('published_at', '<=', now());
            })
            ->orderByDesc('is_featured')
            ->latest('published_at')
            ->latest('id')
            ->limit(3)
            ->get()
            ->map(function (Article $article) {
                return [
                    'title' => $this->translated($article->getRawOriginal('title') ?? $article->title),
                    'excerpt' => $this->shortText($article->getRawOriginal('excerpt') ?: $article->getRawOriginal('content'), 170),
                    'image' => $this->imageUrl('storage/' . $article->featured_image, 'website/photos/home2.webp'),
                    'url' => route('website.blogs.show', $article->slug),
                    'date' => optional($article->published_at ?: $article->created_at)->format('M d, Y'),
                ];
            });

        $testimonials = Testimonial::query()
            ->where(function ($query) {
                $query->where('is_active', true)->orWhereNull('is_active');
            })
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->latest('id')
            ->limit(6)
            ->get()
            ->map(function (Testimonial $testimonial) {
                $name = $testimonial->customer_name ?: 'Guest';

                return [
                    'name' => $name,
                    'initials' => $testimonial->customer_initials ?: collect(explode(' ', $name))->map(fn($part) => mb_substr($part, 0, 1))->take(2)->implode(''),
                    'rating' => (int) ($testimonial->rating ?: 5),
                    'content' => $this->shortText($testimonial->getRawOriginal('content') ?? $testimonial->content, 260),
                    'is_verified' => (bool) $testimonial->is_verified,
                    'source_url' => $testimonial->source_url,
                    'avatar' => $testimonial->avatar ? $this->imageUrl($testimonial->avatar) : null,
                ];
            });

        return view('website.pages.home', compact(
            'featuredPackages',
            'destinations',
            'latestArticles',
            'testimonials'
        ));
    }
}
