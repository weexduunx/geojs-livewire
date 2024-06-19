<?php

use App\Livewire\GeoLocation;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/ip', GeoLocation::class);