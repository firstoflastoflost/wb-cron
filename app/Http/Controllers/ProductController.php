<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function index()
    {
        $lastUpdatePricesDate = json_decode(Storage::get('schedule-updates.json'), true)['last_update'] ?? null;
        $commandIsRunningNow = Cache::has('update-products:price-running');
        return view('products.index', [
            'lastUpdatePricesDate' => $lastUpdatePricesDate,
            'commandIsRunningNow' => $commandIsRunningNow
        ]);
    }

    /**
     * @throws Exception
     */
    public function getProducts(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return response()->json(['error' => 'Unauthorised'], 403);
    }
}
