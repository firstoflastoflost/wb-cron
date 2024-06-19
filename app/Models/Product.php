<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'wb_id',
        'name',
        'cost_price',
        'discounted_price',
        'min_markup',
        'max_markup',
        'price_status'
    ];

    public function getPriceStatusAttribute($attribute): string
    {
        switch ($attribute){
            case 1:
                $priceStatusString = "Меньше минимальной";
                break;
            case 2:
                $priceStatusString = "Соответствует";
                break;
            case 3:
                $priceStatusString = "Больше максимальной";
                break;
            default:
                $priceStatusString = "-";
                break;
        }

        return $priceStatusString;
    }

    public function getUpdatedAtAttribute($attribute): string
    {
        return Carbon::parse($attribute)->setTimezone('Europe/Moscow')->format('Y-m-d H:i:s');
    }
}
