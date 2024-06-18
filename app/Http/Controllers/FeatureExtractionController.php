<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Tambahkan ini
use Illuminate\Support\Facades\File;    // Jika Anda menggunakan File facade juga

class FeatureExtractionController extends Controller
{
    public function showUploadFeatureExtractionForm()
    {
        return view('feature-extraction');
    }

    public function handleUploadFeatureExtraction(Request $request)
    {
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
        $output = shell_exec($command . ' 2>&1');

        // Debug: Cek output dari skrip Python
        if (empty($output)) {
            return response()->json(['error' => 'No output from Python script', 'command' => $command], 500);
        }

        // Decode hasil dari skrip Python
        $results_feature_extraction = json_decode($output, true);

        // Debug: Cek apakah decoding berhasil
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'JSON decode error', 'output' => $output], 500);
        }

        // Pastikan $results memiliki struktur yang benar sebelum dikirimkan ke view
        if (!is_array($results_feature_extraction) || !isset($results_feature_extraction['overall'])) {
            return response()->json(['error' => 'Invalid format in results', 'results_feature_extraction' => $results_feature_extraction], 500);
        }

        return view('results_feature_extraction', ['results_feature_extraction' => $results_feature_extraction]);
    }
}
