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
        Schema::create('tickets', function (Blueprint $table) {
            $table->string('id', 55)->unique();
            $table->string('ticket_type_name', 55)->nullable();
            $table->string('ticket_type_id', 55);
            $table->dateTime('use_date')->nullable();
            $table->string('order_id',55);
            $table->string('qr_code', 255);
            $table->decimal('price', 14, 2);
            $table->tinyInteger('status')->default(1);
            $table->boolean('is_delete')->default(0);
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
        Schema::dropIfExists('order_details');
    }
};
