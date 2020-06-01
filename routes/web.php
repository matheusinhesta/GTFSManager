<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'documentation.index-swagger');

// GTFS Files Routes
Route::get('agency/{agency_id}/gtfs', 'GtfsFileController@generateGtfsFiles');
