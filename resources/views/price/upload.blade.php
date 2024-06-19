<!DOCTYPE html>
<html>
<head>
    <title>Import Products</title>
</head>
<body>
@if (session('success'))
    <div>
        {{ session('success') }}
    </div>
@endif

<form action="/upload-excel" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file">
    <button type="submit">Загрузить</button>
</form>
</body>
</html>
