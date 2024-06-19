<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Product([
            'wb_id' => $row['id_wildberries'],
            'name' => $row['artikul'],
            'cost_price' => $row['sebestoimost_edinicy_tovara_rub'],
            'min_markup' => $row['nacenka_minimalnaya'],
            'max_markup' => $row['nacenka_maksimalnaya'],
        ]);
    }
}
