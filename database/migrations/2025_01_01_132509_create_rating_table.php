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
        Schema::create('rating', function (Blueprint $table) {
            $table->id();
            $table->integer('rating');
            $table->text('your_review')->nullable();
            $table->bigInteger("room_id")->unsigned();
            $table->bigInteger("user_id")->unsigned();
            $table->timestamps();


            $table->foreign("room_id")->references("id")->on("rooms")->onDelete("cascade")->onUpdate('cascade');
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade")->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating');
    }
};
