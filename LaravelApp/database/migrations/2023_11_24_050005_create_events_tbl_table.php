<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('events_tbl', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('event_name');
            $table->text('description');
            $table->dateTime('event_datetime');
            $table->string('venue');
            $table->string('template_path');
            $table->integer('persons_per_row');
            $table->integer('vip_seats');
            $table->integer('regular_seats');
            $table->decimal('vip_prices', 10, 2);
            $table->decimal('regular_prices', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('events_tbl');
    }
};