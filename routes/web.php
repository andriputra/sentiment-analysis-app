<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SentimentController;
use App\Http\Controllers\FeatureExtractionController;
use App\Http\Controllers\ClassificationController;

Route::get('/upload', [SentimentController::class, 'showUploadForm']);
Route::post('/upload', [SentimentController::class, 'handleUpload']);

Route::get('/download/{filename}', function ($filename) {
    $filePath = storage_path('app/uploads/' . $filename);

    if (!file_exists($filePath)) {
        abort(404, 'File not found');
    }

    return response()->download($filePath);
})->name('download');

Route::get('/upload-feature-extraction', [FeatureExtractionController::class, 'showUploadFeatureExtractionForm']);
Route::post('/upload-feature-extraction', [FeatureExtractionController::class, 'handleUploadFeatureExtraction']);

Route::get('/upload-data-classification', [ClassificationController::class, 'showUploadClassificationForm']);
Route::post('/upload-data-classification', [ClassificationController::class, 'handleUploadClassification']);
