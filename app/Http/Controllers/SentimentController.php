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

        // Panggil skrip Python untuk memproses file
        $command = escapeshellcmd("python " . base_path('process_sentiment.py') . " " . storage_path('app/' . $filePath));
        $output = shell_exec($command . ' 2>&1');

        // Debug: Cek output dari skrip Python
        if (empty($output)) {
            return response()->json(['error' => 'No output from Python script'], 500);
        }

        // Decode hasil dari skrip Python
        $results = json_decode($output, true);

        // Debug: Cek apakah decoding berhasil
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'JSON decode error', 'output' => $output], 500);
        }

        // Pastikan $results memiliki struktur yang benar sebelum dikirimkan ke view
        if (!is_array($results) || 
            !isset($results['precision_positive'], $results['recall_positive'], $results['f1_score_positive'], 
                    $results['precision_negative'], $results['recall_negative'], $results['f1_score_negative'], 
                    $results['accuracy'])) {
            return response()->json(['error' => 'Invalid format in results'], 500);
        }

        return view('results', ['results' => $results]);
    }
}