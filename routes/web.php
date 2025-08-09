<?php

use App\Http\Controllers\EmpleadoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EmpleadoController::class, 'index']);
Route::resource('empleados', EmpleadoController::class)->except(['show']);
