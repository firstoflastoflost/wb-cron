<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('wb_id')->comment('WB номер товара');
            $table->string('name');
            $table->decimal('cost_price', 8, 2)->comment('себестоимость');
            $table->decimal('discounted_price', 8, 2)->comment('цена из вб')->default(0.0);
            $table->integer('min_markup')->comment('мин наценка(разы)');
            $table->integer('max_markup')->comment('макс наценка(разы)');
            $table->tinyInteger('price_status')
                ->nullable()
                ->comment('1 - ниже минимальной наценки, 2 - в пределах нормы, 3 - выше максимальной наценки');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
