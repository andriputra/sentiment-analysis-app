<!DOCTYPE html>
<html>
<head>
    <title>Results</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <h1>Sentiment Analysis Results</h1>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Opsi Dataset</th>
                        <th colspan="3">Tweet Positif</th>
                        <th colspan="3">Tweet Negatif</th>
                        <th>Akurasi</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>Presisi</th>
                        <th>Recall</th>
                        <th>F1-Score</th>
                        <th>Presisi</th>
                        <th>Recall</th>
                        <th>F1-Score</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($results as $split => $metrics)
                        @if (is_array($metrics) && isset($metrics['pos'], $metrics['neg'], $metrics['accuracy']))
                            <tr>
                                <td>{{ $split }}</td>
                                <td>{{ isset($metrics['pos']['precision']) ? number_format($metrics['pos']['precision'], 2) : '-' }}</td>
                                <td>{{ isset($metrics['pos']['recall']) ? number_format($metrics['pos']['recall'], 2) : '-' }}</td>
                                <td>{{ isset($metrics['pos']['f1']) ? number_format($metrics['pos']['f1'], 2) : '-' }}</td>
                                <td>{{ isset($metrics['neg']['precision']) ? number_format($metrics['neg']['precision'], 2) : '-' }}</td>
                                <td>{{ isset($metrics['neg']['recall']) ? number_format($metrics['neg']['recall'], 2) : '-' }}</td>
                                <td>{{ isset($metrics['neg']['f1']) ? number_format($metrics['neg']['f1'], 2) : '-' }}</td>
                                <td>{{ number_format($metrics['accuracy'], 4) }}</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="8">Data tidak valid untuk split: {{ $split }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
