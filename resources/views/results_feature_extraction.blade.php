<!DOCTYPE html>
<html>
<head>
    <title>Results</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script> <!-- Include Chart.js -->
</head>
<body>
    <div class="container mt-4">
        <div class="box-result">
            <h3 class="mb-3 text-center">Result Feature Extraction</h3>
            <div class="button-action text-end mt-3">
                <a href="/upload-feature-extraction" class="btn btn-sm btn-warning">Back To Main Menu</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Features</th>
                            <th>Labels</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results['features'] as $index => $feature)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ implode(', ', $feature) }}</td>
                            <td>{{ $results['labels'][$index] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <p>Total Samples: <b>{{ $results_feature_extraction['overall']['num_samples'] }}</b></p>
                <p>Total Features: <b>{{ $results_feature_extraction['overall']['num_features'] }}</b></p>
            </div>

            <!-- Chart Container -->
            <div class="mt-5">
                <canvas id="barChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('barChart').getContext('2d');
            const barChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Samples', 'Features'],
                    datasets: [{
                        label: 'Count',
                        data: [
                            {{ $results_feature_extraction['overall']['num_samples'] }},
                            {{ $results_feature_extraction['overall']['num_features'] }}
                        ],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
