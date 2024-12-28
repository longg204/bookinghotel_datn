<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pay', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('vat', 5, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('room_id')->unsigned();
            $table->bigInteger('order_item_id')->unsigned();


            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade")->onUpdate('cascade');
            $table->foreign("room_id")->references("id")->on("rooms")->onDelete("cascade")->onUpdate('cascade');
            $table->foreign("order_item_id")->references("id")->on("order_items")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay');
    }
};
