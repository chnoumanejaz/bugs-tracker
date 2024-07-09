<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::redirect('/', '/projects');

Auth::routes();

Route::resource('projects','App\Http\Controllers\ProjectsController');
Route::resource('bugs','App\Http\Controllers\BugsController');