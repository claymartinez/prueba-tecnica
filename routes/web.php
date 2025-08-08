<?php

use App\Http\Controllers\EmpleadoController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/empleados');
Route::resource('empleados', EmpleadoController::class)->except(['show']);
