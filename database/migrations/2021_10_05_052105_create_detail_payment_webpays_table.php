<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPaymentWebpaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_payment_webpays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_webpay_id');
            $table->unsignedBigInteger('product_id');
            $table->string('quantity');
            $table->string('observations');
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
        Schema::dropIfExists('detail_payment_webpays');
    }
}
