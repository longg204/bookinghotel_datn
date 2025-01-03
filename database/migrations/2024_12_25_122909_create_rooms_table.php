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
            Schema::create('rooms', function (Blueprint $table) {
                $table->id();
                $table->string("name");
                $table->string("slug")->unique();
                $table->string("short_description")->nullable();
                $table->text("description");
                $table->decimal("regular_price");
                $table->decimal("sale_price")->nullable();
                $table->enum("stock_status", ["instock", "outofstock"]);
                $table->boolean("featured")->default(false);
                $table->unsignedInteger("quantity")->default(10);
                $table->string("image")->nullable();
                $table->text("images")->nullable();
                $table->integer("rating")->nullable();
                $table->bigInteger("category_id")->unsigned();
                $table->timestamps();

                $table->foreign("category_id")->references("id")->on("categories")->onDelete("cascade")->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
