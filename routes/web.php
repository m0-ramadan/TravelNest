<?php

use App\Http\Controllers\Website\BlogController;
use App\Http\Controllers\Website\ContactController;
use App\Http\Controllers\Website\DestinationController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\InquiryController;
use App\Http\Controllers\Website\NewsletterController;
use App\Http\Controllers\Website\PackageController;
use App\Http\Controllers\Website\PageController;
use App\Http\Controllers\Website\SearchController;
use App\Http\Controllers\Website\SitemapController;
use App\Http\Controllers\Website\TailorMadeController;
use App\Http\Controllers\Website\TourController;
use App\Http\Controllers\Website\TripController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Website Routes
|--------------------------------------------------------------------------
| Clean routes without duplication
| Includes legacy SEO support + aliases
*/
// use Spatie\Sitemap\SitemapGenerator;

// SitemapGenerator::create('https://etrotours.com')->writeToFile(public_path('sitemap.xml'));

Route::name('website.')->group(function () {
    Route::get('/lang/{locale}', function (Illuminate\Http\Request $request, $locale) {
        $supportedLocales = \Illuminate\Support\Facades\Cache::remember('supported_locales', 3600, function () {
            return \App\Models\Language::where('is_active', true)->pluck('code')->toArray();
        });
        if (in_array($locale, $supportedLocales)) {
            app(\App\Services\JsonTranslationFileService::class)->ensureLocaleFile($locale);
            $request->session()->put('locale', $locale);
        }

        return redirect()->back();
    })->name('lang.switch');

    /*
    |--------------------------------------------------------------------------
    | Offers
    |--------------------------------------------------------------------------
    */
    Route::get('/latest-offers', [TourController::class, 'offers'])->name('offers');

    /*
    |--------------------------------------------------------------------------
    | Home
    |--------------------------------------------------------------------------
    */
    Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

    Route::get('/', [HomeController::class, 'index'])->name('home');

    /*
    |--------------------------------------------------------------------------
    | Static Pages
    |--------------------------------------------------------------------------
    */
    Route::controller(PageController::class)->group(function () {
        Route::get('/multi-country', 'multiCountry')
            ->name('multi_country');

        Route::get('/multi-country-tours', 'multiCountry')
            ->name('multi_country_tours');

        Route::get('/services', 'services')
            ->name('services');

        Route::get('/about-us', 'redirectLegacy')
            ->defaults('slug', 'about-etrotours')
            ->name('pages.about.legacy');

        Route::get('/why-luxor-and-aswan-travel', 'redirectLegacy')
            ->defaults('slug', 'why-etrotours')
            ->name('pages.why.legacy');
    });

    Route::controller(ContactController::class)->group(function () {
        Route::get('/contact-us', 'index')
            ->name('contact.index');

        Route::post('/contact-us', 'store')
            ->name('contact.store');
    });

    /*
    |--------------------------------------------------------------------------
    | Destinations
    |--------------------------------------------------------------------------
    */
    Route::prefix('destinations')->name('destinations.')->group(function () {

        Route::get('/', [DestinationController::class, 'index'])->name('index');

        Route::get('/{slug}', [DestinationController::class, 'show'])->name('show');
    });

    /*
    |--------------------------------------------------------------------------
    | Alias Support
    |--------------------------------------------------------------------------
    | Fix old blade calls:
    | route('website.destinations')
    | route('website.blogs')
    | route('website.trips')
    | route('website.tours')
    */
    // Route::get('/destinations', [DestinationController::class, 'index'])
    //     ->name('destinations');

    /*
    |--------------------------------------------------------------------------
    | Blogs
    |--------------------------------------------------------------------------
    */
    Route::prefix('blogs')->name('blogs.')->group(function () {

        Route::get('/', [BlogController::class, 'index'])
            ->name('index');

        Route::get('/{slug}', [BlogController::class, 'show'])
            ->name('show');

        Route::get('/{categorySlug}/{slug}', [BlogController::class, 'show'])
            ->name('show.legacy');
    });
    Route::get('blog/{slug}', [BlogController::class, 'category'])
        ->name('blogs.category');
    // Route::get('/blogs', [BlogController::class, 'index'])
    //     ->name('blogs');

    /*
    |--------------------------------------------------------------------------
    | Trips
    |--------------------------------------------------------------------------
    */
    Route::prefix('trips')->name('trips.')->group(function () {

        Route::get('/', [PackageController::class, 'index'])
            ->name('all');

        Route::get('/{slug}', [PackageController::class, 'show'])
            ->name('show');
    });

    Route::get('/trips', [PackageController::class, 'index'])
        ->name('trips');

    /*
    |--------------------------------------------------------------------------
    | Tours
    |--------------------------------------------------------------------------
    */
    Route::prefix('tours')->name('tours.')->group(function () {

        Route::get('/', [PackageController::class, 'tours'])
            ->name('all');

        Route::get('/offers', [TourController::class, 'offers'])
            ->name('show.offers');

        Route::get('/{slug}', [TourController::class, 'show'])
            ->name('show');
    });

    // Route::get('/tours', [TourController::class, 'index'])
    //     ->name('tours');

    /*
    |--------------------------------------------------------------------------
    | Package Short URLs
    |--------------------------------------------------------------------------
    */
    Route::get('/package/{slug}', [TripController::class, 'show'])
        ->name('packages.show.simple');

    Route::get('/{country}/package/{slug}', [TripController::class, 'show'])
        ->name('packages.show');

    /*
    |--------------------------------------------------------------------------
    | Forms
    |--------------------------------------------------------------------------
    */
    Route::post('/newsletter', [NewsletterController::class, 'store'])
        ->name('newsletter.store');

    Route::post('/inquiries', [InquiryController::class, 'store'])
        ->name('inquiries.store');

    Route::post('/enquiry-confirmation', [InquiryController::class, 'store'])
        ->name('enquiries.store');

    /*
    |--------------------------------------------------------------------------
    | Legacy SEO URLs
    |--------------------------------------------------------------------------
    */
    Route::get('/{country}/package/{slug}.html', [TripController::class, 'legacyShow'])
        ->name('legacy.package.show');

    Route::get('/{country}/cruise/{slug}.html', [TourController::class, 'legacyShow'])
        ->name('legacy.cruise.show');

    Route::get('/{slug}.html', [DestinationController::class, 'legacyShow'])
        ->name('legacy.destination.show');

    Route::get('/tailor-made', [TailorMadeController::class, 'index'])
        ->name('tailor_made.index');

    Route::post('/tailor-made', [TailorMadeController::class, 'store'])
        ->name('tailor_made.store');

    Route::get('search/suggestions', [SearchController::class, 'suggestions'])
        ->name('search.suggestions');

    Route::get('search', [SearchController::class, 'index'])
        ->name('search.index');

    Route::get('/{slug}', [PageController::class, 'show'])
        ->where('slug', '[A-Za-z0-9\-]+')
        ->name('pages.show');
});
