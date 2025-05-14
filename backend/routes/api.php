<?php

use App\Http\Controllers\GlobalPoolController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/globalPool/balance', [GlobalPoolController::class, 'index']);
