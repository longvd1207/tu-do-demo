<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_status', function (Blueprint $table) {
            $table->string('id', 55)->unique();
            $table->tinyInteger('status')->default(0)->comment('1 đã thanh toán, 2 hoàn thành, 3 đã hủy, 4 đã hoàn thành');
            $table->text('note')->nullable();
            $table->string('order_id', 55);
            $table->softDeletes();
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
        Schema::dropIfExists('payment_status');
    }
};
