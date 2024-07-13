<!DOCTYPE html>
<html>
<head>
    <title>Results Feature Extraction</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.debug.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.16/jspdf.plugin.autotable.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <div class="box-result">
        <div class="table-responsive border p-3 mb-4">
                <h4 class="mb-3 text-center">Data CSV yang di Olah</h4>
                <div class="data-preview mt-3">
                    <div class="data-preview-csv table-responsive">
                        @isset($csvData)
                            @if(count($csvData) > 0)
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            @foreach(array_keys($csvData[0]) as $header)
                                                <th>{{ $header }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($csvData as $row)
                                            <tr>
                                                @foreach($row as $cell)
                                                    <td>{{ $cell }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>No data found in the CSV file.</p>
                            @endif
                        @endisset
                    </div>
                </div>
            </div>
            <h3 class="mb-3 text-center">Result Feature Extraction</h3>
            <div class="mt-4 feature-results">
                <p>Total Samples: <span id="total-samples"><b>{{ $results_feature_extraction['overall']['num_samples'] }}</b></span></p>
                <p>Total Features: <span id="total-features"><b>{{ $results_feature_extraction['overall']['num_features'] }}</b></span></p>
            </div>

            <!-- Chart Container -->
            <div class="mt-5">
                <canvas id="barChart"></canvas>
            </div>

            <div class="button-action mt-3 d-flex justify-content-between">
                <button onclick="downloadPDF()" class="btn btn-sm btn-primary">Download PDF</button>
                <a href="/" class="btn btn-sm btn-warning">Back To Main Menu</a>
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

        function downloadPDF() {
            // Pengaturan PDF dengan ukuran A4
            const doc = new jsPDF('p', 'pt', 'a4');

            // Tambahkan judul
            doc.setFontSize(16);
            doc.text("Feature Extraction Results", 40, 40);

            // Ambil elemen yang akan di-render ke PDF
            const chartCanvas = document.getElementById('barChart');
            const chartDataURL = chartCanvas.toDataURL(); // Dapatkan data URL dari chart

            // Tambahkan chart ke PDF
            doc.addImage(chartDataURL, 'PNG', 40, 100, 500, 200); // Ubah posisi chart

            // Tambahkan informasi total samples dan total features ke PDF
            const totalSamples = document.getElementById('total-samples').querySelector('b').textContent.trim();
            const totalFeatures = document.getElementById('total-features').querySelector('b').textContent.trim();
            doc.setFontSize(12);

            // Tentukan margin bottom yang diinginkan dan posisi Y untuk pencetakan 'Total Samples' dan 'Total Features'
            const marginBottom = 500; // Anda bisa menyesuaikan jarak ini sesuai kebutuhan
            const yPositionSamples = doc.internal.pageSize.height - marginBottom + 10; // Posisi Y untuk 'Total Samples'
            const yPositionFeatures = yPositionSamples + 20; // Jarak antara 'Total Samples' dan 'Total Features'

            doc.text(`Total Samples: ${totalSamples}`, 40, yPositionSamples);
            doc.text(`Total Features: ${totalFeatures}`, 40, yPositionFeatures);

            // Download PDF dengan nama file
            doc.save('feature_extraction_results.pdf');
        }
    </script>
</body>
</html>
