<!DOCTYPE html>
<html>
<head>
    <title>Upload Classification CSV</title>
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
            <form id="upload-form" action="{{ url('/upload-data-classification') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="input-group">
                    <input type="file" name="csv_file" accept=".csv" class="form-control">
                    <button type="submit" class="btn btn-primary">Data Classification</button>
                </div>
            </form>
            <div class="loader mt-3" id="loader"></div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#upload-form').submit(function() {
                $('#loader').show();
            });
        });
    </script>
</body>
</html>
