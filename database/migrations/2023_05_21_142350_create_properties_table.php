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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id')->unique();
            $table->unsignedBigInteger('property_seller_id');
            $table->string('title');
            $table->string('propertytype');
            $table->string('actiontype');
            $table->decimal('price');
            $table->string('city');
            $table->string('image');
            $table->string('area');
            $table->integer('bedrooms');
            $table->string('address');
            $table->integer('isactive')->default(1);
            $table->integer('oneStar')->default(0);
            $table->integer('twoStars')->default(0);
            $table->integer('threeStars')->default(0);
            $table->integer('fourStars')->default(0);
            $table->integer('fiveStars')->default(0);
            $table->decimal('rating')->default(0.00);
            $table->timestamps();

            $table->foreign('property_seller_id')->references('seller_id')->on('sellers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
