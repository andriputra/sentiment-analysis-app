<!DOCTYPE html>
<html>
<head>
    <title>Results</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container mt-4">
        <div class="box-result">
            <h3 class="mb-3 text-center">Sentiment Analysis Results</h3>
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
                        <tr>
                            <td>Overall</td>
                            <td>{{ isset($results['overall']['pos']['precision']) ? number_format($results['overall']['pos']['precision'], 2) : '-' }}</td>
                            <td>{{ isset($results['overall']['pos']['recall']) ? number_format($results['overall']['pos']['recall'], 2) : '-' }}</td>
                            <td>{{ isset($results['overall']['pos']['f1']) ? number_format($results['overall']['pos']['f1'], 2) : '-' }}</td>
                            <td>{{ isset($results['overall']['neg']['precision']) ? number_format($results['overall']['neg']['precision'], 2) : '-' }}</td>
                            <td>{{ isset($results['overall']['neg']['recall']) ? number_format($results['overall']['neg']['recall'], 2) : '-' }}</td>
                            <td>{{ isset($results['overall']['neg']['f1']) ? number_format($results['overall']['neg']['f1'], 2) : '-' }}</td>
                            <td>{{ number_format($results['overall']['accuracy'], 4) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="button-action text-end">
                <a href="/upload" class="btn btn-sm btn-warning">Back To Upload</a>
            </div>
        </div>        
    </div>
</body>
</html>
