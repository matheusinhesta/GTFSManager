<?php

use Illuminate\Support\Facades\Route;

// Documentation Route
Route::view('/', 'documentation.index-swagger');

// GTFS Files Routes
Route::get('agency/{agency_id}/gtfs', 'GtfsFileController@generateGtfsFiles');
