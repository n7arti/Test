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
        Schema::create('catalog_elements', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique();
            $table->string('name');
            $table->unsignedBigInteger('catalogId')->nullable();
            $table->unsignedBigInteger('createdBy');
            $table->unsignedBigInteger('updatedBy');
            $table->unsignedBigInteger('createdAt');
            $table->unsignedBigInteger('updatedAt');
            $table->boolean('isDeleted');
            $table->unsignedBigInteger('quantity')->nullable();
            $table->unsignedBigInteger('priceId')->nullable();
            $table->unsignedBigInteger('accountId')->nullable();
            $table->string('invoiceLink')->nullable();
            $table->string('requestId')->nullable();
            $table->unsignedBigInteger('lead_id');
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
        Schema::dropIfExists('catalog_elements');
    }
};
