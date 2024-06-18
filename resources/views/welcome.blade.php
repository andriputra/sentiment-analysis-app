<!DOCTYPE html>
<html>
<head>
    <title>Data Model Sentiment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container-sm">
        <div class="box">
            <div class="title-app text-center mb-4">
                <h4>Data Model Sentiment</h4>
                <h5>Pemilihan Umum Presiden dan Wakil Presiden Republik Indonesia</h5>
                <h5>Periode 2024-2029</h5>
            </div>
            <hr>
            <div class="box-action d-flex justify-content-center gap-3">
                <a href="/upload" class="btn btn-md btn-primary" id="button-1">Data Processing</a>
                <a href="/upload-feature-extraction" class="btn btn-md btn-warning" id="button-2">Feature Extraction</a>
                <a href="/upload-data-classification" class="btn btn-md btn-success" id="button-3">Classification</a>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Peringatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-message">
                    <!-- Modal message content -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
