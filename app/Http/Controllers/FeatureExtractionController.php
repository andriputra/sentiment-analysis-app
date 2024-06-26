<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 
use League\Csv\Reader;
use Illuminate\Support\Facades\File;    
use Illuminate\Support\Facades\Log;

class FeatureExtractionController extends Controller
{
    public function showUploadFeatureExtractionForm()
    {
        return view('feature-extraction');
    }

    public function handleUploadFeatureExtraction(Request $request)
    {
        try {
            // Validasi file yang diunggah
            $request->validate([
                'csv_file' => 'required|mimes:csv,txt|max:6048',
            ]);

            // Simpan file yang diunggah
            $filePath = $request->file('csv_file')->store('uploads');

            // Debug: Pastikan file tersimpan dengan benar
            if (!Storage::exists($filePath)) {
                return response()->json(['error' => 'File upload failed'], 500);
            }

            // Escape shell arguments to handle spaces and special characters
            $escapedFilePath = escapeshellarg(storage_path('app/' . $filePath));
            $escapedScriptPath = escapeshellarg(base_path('process_feature_extraction.py'));
            $command = "python $escapedScriptPath $escapedFilePath";

            // Panggil skrip Python untuk memproses file
            Log::info("Running command: $command");
            $output = shell_exec($command . ' 2>&1');
            Log::info("Command output: $output");

            // Debug: Cek output dari skrip Python
            if (empty($output)) {
                return response()->json(['error' => 'No output from Python script', 'command' => $command], 500);
            }

            // Decode hasil dari skrip Python
            $results_feature_extraction = json_decode($output, true);

            // Debug: Cek apakah decoding berhasil
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errorMessage = 'Unknown error';
                if (strpos($output, 'Error:') !== false) {
                    $errorMessage = substr($output, strpos($output, 'Error:'));
                }
                return view('upload', ['error' => $errorMessage]);
            }

            // Pastikan $results memiliki struktur yang benar sebelum dikirimkan ke view
            if (!is_array($results_feature_extraction) || !isset($results_feature_extraction['overall'])) {
                return response()->json(['error' => 'Invalid format in results', 'results_feature_extraction' => $results_feature_extraction], 500);
            }

            // Baca isi CSV yang diunggah
            $csv = Reader::createFromPath(storage_path('app/' . $filePath), 'r');
            $csv->setHeaderOffset(0); // Set header

            // Convert CSV data to an array
            $csvData = iterator_to_array($csv, true);

            // Reindex the array starting from 0
            $csvData = array_values($csvData);

            // Check if the CSV data is empty
            if (empty($csvData)) {
                return view('upload', ['error' => 'CSV file is empty or improperly formatted']);
            }

            return view('results_feature_extraction', ['results_feature_extraction' => $results_feature_extraction, 'csvData' => $csvData]);
        
        } catch (\Exception $e) {
            // Tangani kesalahan umum
            Log::error("Error in handleUpload: " . $e->getMessage());
            return view('upload', ['error' => $e->getMessage()]);
        }
    }
}
