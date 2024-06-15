<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SentimentController;

Route::get('/upload', [SentimentController::class, 'showUploadForm']);
Route::post('/upload', [SentimentController::class, 'handleUpload']);