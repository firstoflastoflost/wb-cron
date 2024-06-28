<!DOCTYPE html>
<html>
<head>
    <title>Import Products</title>
    <!-- Подключение Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
@if (session('message'))
    <div>
        {{ session('message') }}
    </div>
@endif
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Загрузка файла с товарами</h3>
                </div>
                <div class="card-body">
                    <!-- Форма импорта продуктов -->
                    <form action="/upload-excel" method="POST" enctype="multipart/form-data">
                        <!-- CSRF Token -->
                        @csrf
                        <div class="form-group">
                            <label for="file">Выберите файл</label>
                            <input type="file" name="file" class="form-control-file" id="file">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Загрузить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Подключение Bootstrap JS и jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
