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
        Schema::create('wishlist', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger("user_id")->unsigned();
            $table->bigInteger("room_id")->unsigned();
            $table->bigInteger("coupon_id")->unsigned()->nullable();
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade")->onUpdate('cascade');
            $table->foreign("room_id")->references("id")->on("rooms")->onDelete("cascade")->onUpdate('cascade');
            $table->foreign("coupon_id")->references("id")->on("coupons")->onDelete("cascade")->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlist');
    }
};
