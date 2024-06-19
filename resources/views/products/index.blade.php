@extends('layouts.app')

@section('content')
    <h3 class="text-center">Список товаров. Последнее обновление цен: {{$lastUpdatePricesDate}}</h3>
    <h4>
        Обновление запущено в текущий момент:
        @if($commandIsRunningNow)
            <span style="color: red; font-weight: bold">Да</span>
        @else
            <span style="color: green; font-weight: normal">Нет</span>
        @endif
    </h4>
    <table class="table table-bordered data-table" id="products">
        <thead>
        <tr>
            <th>ID</th>
            <th>Номер на WB</th>
            <th>Название</th>
            <th>Мин наценка</th>
            <th>Макс наценка</th>
            <th>Статус цены</th>
            <th>Себестоимость</th>
            <th>Цена на WB</th>
            <th>Последнее обновление</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.data-table').DataTable({
                "language": {
                    "lengthMenu": "Показать _MENU_ записей на странице",
                    "zeroRecords": "Ничего не найдено",
                    "info": "Показано _PAGE_ из _PAGES_",
                    "infoEmpty": "Нет доступной информации",
                    "infoFiltered": "(отфильтровано из _MAX_ записей)",
                    "search": "Поиск:",
                    "paginate": {
                        "first": "Первая",
                        "last": "Последняя",
                        "next": "Следующая",
                        "previous": "Предыдущая"
                    },
                    "loadingRecords": "Загрузка...",
                    "processing": "Обработка...",
                    "emptyTable": "Нет данных для отображения",
                    "aria": {
                        "sortAscending": ": активируйте для сортировки по возрастанию",
                        "sortDescending": ": активируйте для сортировки по убыванию"
                    }
                },

                processing: true,
                serverSide: true,
                ajax: "{{ route('products.getProducts') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'wb_id', name: 'wb_id'},
                    {data: 'name', name: 'name'},
                    {data: 'min_markup', name: 'min_markup'},
                    {data: 'max_markup', name: 'max_markup'},
                    {data: 'price_status', name: 'price_status'},
                    {data: 'cost_price', name: 'cost_price'},
                    {data: 'discounted_price', name: 'discounted_price'},
                    {data: 'updated_at', name: 'updated_at'},
                ]
            });
        });
    </script>
@endsection
