<!DOCTYPE html>
<html>
<head>
    <title>Results Classification</title>
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
        <div class="box-result mb-2">
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

            <h3 class="mb-3 text-center">Classification Results</h3>
            <div class="mt-4">
                <h5><span id="Accuracy"><b>Accuracy: {{ number_format($results_classification['accuracy'], 3) }}</b></span></h5>
                <table class="table table-bordered data-classification">
                    <thead>
                        <tr>
                            <th>Label</th>
                            <th>Precision</th>
                            <th>Recall</th>
                            <th>F1-score</th>
                            <th>Support</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results_classification['report'] as $label => $metrics)
                            @if(is_array($metrics) && $label !== 'neutral')
                            <tr>
                                <td>{{ $label }}</td>
                                <td>{{ number_format($metrics['precision'], 3) }}</td>
                                <td>{{ number_format($metrics['recall'], 3) }}</td>
                                <td>{{ number_format($metrics['f1-score'], 3) }}</td>
                                <td>{{ $metrics['support'] }}</td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="box-result-diagram d-flex gap-4 justify-content-center" style="border: 1px solid #d1d1d1; padding: 10px 10px 0px; border-radius: 10px;">
                    <div class="item-box">
                        <table class="table table-bordered before-sample">
                            <thead>
                                <tr>
                                    <th colspan="2">Sentiment Distribution Before Oversampling</th>
                                </tr>
                                <tr>
                                    <th>Sentiment</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results_classification['label_distribution_before'] as $label => $count)
                                    @if($label!== 'neutral')
                                        <tr>
                                            <td>{{ $label }}</td>
                                            <td>{{ $count }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="item-box">
                        <table class="table table-bordered after-sample">
                            <thead>
                                <tr>
                                    <th colspan="2">Sentiment Distribution After Oversampling</th>
                                </tr>
                                <tr>
                                    <th>Sentiment</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($results_classification['label_distribution_after'] as $label => $count)
                                @if($label!== 'neutral')
                                    <tr>
                                        <td>{{ $label }}</td>
                                        <td>{{ $count }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="button-action mt-3 d-flex justify-content-between">
                    <button onclick="downloadPDF()" class="btn btn-sm btn-primary">Download PDF</button>
                    <a href="/" class="btn btn-sm btn-warning">Back To Main Menu</a>
                </div>
            </div>
        </div>
    </div>
        <script>
            function downloadPDF() {
                const doc = new jsPDF('p', 'pt', 'a4');

                // Add title
                doc.setFontSize(22);
                doc.text("Results Classification", 40, 40);

                // Get the table for Classification Results
                const accuracy = document.getElementById('Accuracy').querySelector('b').textContent.trim();
                doc.setFontSize(12);
                doc.text(`Total Features: ${accuracy}`, 350, 70);
                

                const classificationTable = document.querySelector('.table-bordered');
                if (classificationTable) {
                    doc.setFontSize(12);
                    doc.text("Classification Results", 40, 70);
                    doc.autoTable({ html: classificationTable, startY: 80 });
                }

                // Add sentiment distribution before oversampling
                const beforeOversampling = document.querySelector('.before-sample');
                if (beforeOversampling) {
                    doc.setFontSize(12);
                    doc.text("Classification Results", 40, 70);
                    doc.autoTable({ html: beforeOversampling, startY: 250 });
                }

                // Add sentiment distribution after oversampling
                const afterOversampling = document.querySelector('.after-sample');
                if (afterOversampling) {
                    doc.setFontSize(12);
                    doc.text("Classification Results", 40, 70);
                    doc.autoTable({ html: afterOversampling, startY: 350 });
                }

                // Save the PDF
                doc.save('classification_results.pdf');
            }
        </script>
</body>
</html>
