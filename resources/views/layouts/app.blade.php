<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Table</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

    <style>
        /* Добавим пару простых стилей */
        table.dataTable {
            border-collapse: collapse;
        }
        table.dataTable thead th {
            background-color: #f5f5f5;
            color: #333;
        }
        table.dataTable tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>

</head>
<body>

<div class="container mt-5">
    @yield('content')
</div>

</body>
</html>
