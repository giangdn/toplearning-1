<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMdlElPromotionOrders extends Migration
{
    public function up()
    {
        Schema::create('el_promotion_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('orders_id', 150)->unique();
            $table->bigInteger('user_id')->index();
            $table->integer('point');
            $table->integer('quantity');
            $table->integer('promotion_id');
            $table->string('status')->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('unit_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_promotion_orders');
    }
}
