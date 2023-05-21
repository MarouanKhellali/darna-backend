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
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->boolean('accepted')->default(false);
            $table->unsignedBigInteger('seller_id')->unique();
            $table->text('bio')->nullable();
            $table->string('phone');
            $table->string('city');
            $table->string('address');
            $table->integer('oneStar')->default(0);
            $table->integer('twoStars')->default(0);
            $table->integer('threeStars')->default(0);
            $table->integer('fourStars')->default(0);
            $table->integer('fiveStars')->default(0);
            $table->decimal('rating')->default(0.00);
            $table->timestamps();

            $table->foreign('seller_id')->references('userid')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};
