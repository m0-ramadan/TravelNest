<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('website.pages.home');
});

Route::middleware([])->name('website.')->group(function () {
    Route::get('/', function () {
        return view('website.pages.home');
    })->name('home');


    Route::get('/multi-country', function () {
        return view('website.pages.multi-country');
    })->name('multi_country');

    Route::get('/destinations', function () {
        return view('website.pages.destinations.index');
    })->name('destinations');

    Route::get('/blogs', function () {
        return view('website.pages.blogs.index');
    })->name('blogs.index');

    //trips
    Route::prefix('trips')->name('trips.')->group(function () {
        Route::get('/', function () {
            return view('website.pages.trips.index');
        })->name('all');
        Route::get('/{slug}', function () {
            return view('website.pages.trips.show');
        })->name('show');
    });

    //tours
    Route::prefix('tours')->name('tours.')->group(function () {
        Route::get('/all', function () {
            return view('website.pages.tours.index');
        })->name('all');
        Route::get('/{slug}', function () {
            return view('website.pages.tours.show');
        })->name('show');
    });

    Route::get('/services', function () {
        return view('website.pages.services');
    })->name('services');

    Route::get('/services', function () {
        return view('website.pages.services');
    })->name('services');
});
