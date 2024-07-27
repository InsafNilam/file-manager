<?php

use Illuminate\Support\Facades\Route;
use LaravelGuru\LaravelFilehandler\Http\Controllers\FileController;

Route::apiResource('/files', FileController::class);
