<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::all();
    }

    public function headings(): array
    {
        return [
            'Артикул на WB',
            'Название',
            'Себестоимость',
            'Цена на вб(со скидкой)',
            'Мин наценка',
            'Макс наценка',
            'Статус цены',
            'Обновлено'
        ];
    }

    public function map($product): array
    {
        return [
            (string)$product->wb_id,
            $product->name,
            $product->cost_price,
            $product->discounted_price,
            $product->min_markup,
            $product->max_markup,
            $product->price_status,
            $product->updated_at,
        ];
    }

}
