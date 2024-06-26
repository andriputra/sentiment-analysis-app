<!DOCTYPE html>
<html>
<head>
    <title>Upload CSV</title>
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
            <form id="upload-form" action="/upload" method="post" enctype="multipart/form-data">
                @csrf
                <div class="input-group">
                    <input type="file" name="csv_file" accept=".csv" class="form-control">
                    <button type="submit" class="btn btn-primary">Data Labeling</button>
                </div>
            </form>
            <div class="loader mt-3" id="loader" style="display: none;"></div>
        </div>
    </div>

    <!-- Modal error jika terjadi kesalahan pada skrip Python -->
    <div class="modal fade" id="pythonErrorModal" tabindex="-1" aria-labelledby="pythonErrorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pythonErrorModalLabel">Error Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="pythonErrorText"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#upload-form').submit(function(event) {
                $('#loader').show();
                var maxFileSize = 6048 * 1024;
                var fileSize = this['csv_file'].files[0].size;
                if (fileSize > maxFileSize) {
                    event.preventDefault();
                    $('#loader').hide();
                    $('#pythonErrorText').text('File size exceeds the limit (6 MB). Please upload a smaller file.');
                    var errorModal = new bootstrap.Modal(document.getElementById('pythonErrorModal'));
                    errorModal.show();
                }
            });

            // Tampilkan modal jika terjadi error dari skrip Python
            @if (isset($error) && $error)
                $(function() {
                    $('#pythonErrorText').text('Sorry, your file uploaded not valid, please try again!');
                    var pythonErrorModal = new bootstrap.Modal(document.getElementById('pythonErrorModal'));
                    pythonErrorModal.show();
                });
            @endif
        });
    </script>
</body>
</html>
