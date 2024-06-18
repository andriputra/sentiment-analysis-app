<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SentimentController extends Controller
{
    public function showUploadForm()
    {
        return view('upload');
    }

    public function handleUpload(Request $request)
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
        $escapedScriptPath = escapeshellarg(base_path('process_sentiment.py'));
        $command = "python $escapedScriptPath $escapedFilePath";

        // Panggil skrip Python untuk memproses file
        $output = shell_exec($command . ' 2>&1');

        // Debug: Cek output dari skrip Python
        if (empty($output)) {
            return response()->json(['error' => 'No output from Python script', 'command' => $command], 500);
        }

        // Decode hasil dari skrip Python
        $results = json_decode($output, true);

        // Debug: Cek apakah decoding berhasil
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'JSON decode error', 'output' => $output], 500);
        }

        // Pastikan $results memiliki struktur yang benar sebelum dikirimkan ke view
        if (!is_array($results) || !isset($results['overall'])) {
            return response()->json(['error' => 'Invalid format in results', 'results' => $results], 500);
        }

        // File CSV yang telah ditambahkan label sentimen
        $csvFilePathWithSentiment = str_replace('.csv', '_with_sentiment.csv', $filePath);

        // Buat URL untuk mengunduh file
        $downloadUrl = route('download', ['filename' => basename($csvFilePathWithSentiment)]);

        return view('results', ['results' => $results, 'downloadUrl' => $downloadUrl]);
    }

}
