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
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->string('id', 55)->unique();
            $table->string('name', 255);
            $table->tinyInteger('type')->default(1)->comment('1 offline, 2 online');
            $table->decimal('price_online', 9,2)->nullable()->comment('Giá tiền online');
            $table->decimal('price_offline',9,2)->nullable()->comment('Giá tiền offline');
            $table->tinyInteger('status')->default(1)->comment('1 active, 0 inactive');
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
        Schema::dropIfExists('ticket_types');
    }
};
