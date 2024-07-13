<!DOCTYPE html>
<html>
<head>
    <title>Results</title>
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

            <h3 class="mb-3 text-center">Sentiment Analysis Results</h3>
            <div class="table-responsive">
                <table class="table table-bordered analys-results">
                    <thead>
                        <tr>
                            <th>Tweet Positif</th>
                            <th>Tweet Netral</th>
                            <th>Tweet Negatif</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$results['overall']['pos']['count']}}</td>
                            <td>{{$results['overall']['neutral']['count']}}</td>
                            <td>{{$results['overall']['neg']['count']}}</td>
                            <td>{{$results['overall']['total']}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <canvas id="sentimentChart" style="height: 300px; width: 100%;"></canvas> <!-- Canvas for Chart.js -->
            </div>
            <div class="button-action mt-3 d-flex justify-content-between">
                @if(isset($downloadUrl))
                    <a href="{{ $downloadUrl }}" class="btn btn-sm btn-success">Download CSV with Sentiment Labels</a>
                @endif
                <button onclick="downloadPDF()" class="btn btn-sm btn-primary">Download PDF</button>
                <a href="/" class="btn btn-sm btn-warning">Back To Main Menu</a>
            </div>
        </div>        
    </div>

    <script>
        // Ambil data sentimen dari PHP/Blade
        const positiveCount = {{$results['overall']['pos']['count']}};
        const neutralCount = {{$results['overall']['neutral']['count']}};
        const negativeCount = {{$results['overall']['neg']['count']}};

        // Inisialisasi Chart.js
        var ctx = document.getElementById('sentimentChart').getContext('2d');
        var sentimentChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Positif', 'Netral', 'Negatif'],
                datasets: [{
                    label: 'Jumlah Tweet',
                    data: [positiveCount, neutralCount, negativeCount],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.5)',  // Warna untuk positif
                        'rgba(54, 162, 235, 0.5)',  // Warna untuk netral
                        'rgba(255, 99, 132, 0.5)'   // Warna untuk negatif
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
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

        function downloadPDF() {
            // Pengaturan PDF dengan ukuran A4
            const doc = new jsPDF('l', 'pt', 'a4');

            // Tambahkan judul
            doc.setFontSize(16);
            doc.text("Sentiment Analysis Results", 40, 40);

            // Ambil elemen yang akan di-render ke PDF
            const chartCanvas = document.getElementById('sentimentChart');
            const chartDataURL = chartCanvas.toDataURL(); // Dapatkan data URL dari chart

            // Tambahkan chart ke PDF
            doc.addImage(chartDataURL, 'PNG', 40, 100, 700, 200); // Ubah posisi chart

            // Tambahkan tabel secara manual
            const table = document.querySelector('.analys-results');
            if (table) {
                const tableData = [];
                const rows = table.querySelectorAll('tr');
                
                rows.forEach(row => {
                    const rowData = [];
                    const cells = row.querySelectorAll('td');
                    
                    cells.forEach(cell => {
                        rowData.push(cell.textContent.trim());
                    });

                    // Perbaiki penambahan rowData
                    if (rowData.length > 0) {
                        tableData.push(rowData);
                    }
                });

                // Gambar tabel manual ke PDF
                doc.autoTable({
                    head: [['Tweet Positif', 'Tweet Netral', 'Tweet Negatif', 'Total']],
                    body: tableData,
                    startY: 380
                });
            }

            // Download PDF dengan nama file
            doc.save('sentiment_analysis_results.pdf');
        }
    </script>
    
</body>
</html>
