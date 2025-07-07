<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->bigInteger('reservation_id');
            $table->bigInteger('reservation_number');
            $table->bigInteger('external_performance_id');
            $table->bigInteger('structure_element_id');
            $table->enum('payment_status', ['NO_PAID', 'PAID','RETURN'])->default('NO_PAID');
            $table->enum('repayment_status', ['PENDING', 'ACCEPT'])->default('PENDING');
            $table->dateTime('date');
            $table->json('seats');
            $table->string('qr')->nullable();
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
        Schema::dropIfExists('sales');
    }
}
