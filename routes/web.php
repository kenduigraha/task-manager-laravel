<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', fn() => redirect()->route('tasks.index'));
Route::resource('tasks', TaskController::class);
