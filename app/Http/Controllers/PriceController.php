<?php

namespace App\Http\Controllers;

use App\Console\Commands\UpdateProductsPrice;
use App\Events\ProductsUpdated;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Maatwebsite\Excel\Facades\Excel;

class PriceController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        return view('price.upload');
    }

    public function start(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        Product::query()->delete();

        try {
            Excel::import(new ProductsImport, $request->file('file'));
        } catch (\Exception $e) {
            return redirect('/import')->with('error', "Не удалось загрузить файл {$e->getMessage()}");
        }

        return $this->getFile();
    }

    public function getFile()
    {
        $exitCode = Artisan::call('products:update-price');

        if ($exitCode == 0) {
            try {
                $filenameExport = 'products' . now()->format('Y-m-d_H:i:s') . '.xlsx';
                return Excel::download(new ProductsExport(), $filenameExport);
            } catch (\Exception $e) {
                return redirect('/import')->with('message', "Не удалось выгрузить файл: {$e->getMessage()}");
            }
        } else {
            return redirect('/import')->with('message', 'Не удалось обновить данные с WB');
        }
    }

}
