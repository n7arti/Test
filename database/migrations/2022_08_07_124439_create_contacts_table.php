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
        Schema::create('contacts', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique();
            $table->string('name');
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->unsignedBigInteger('responsibleUserId');
            $table->unsignedBigInteger('groupId')->nullable();
            $table->unsignedBigInteger('createdBy');
            $table->unsignedBigInteger('updatedBy');
            $table->unsignedBigInteger('createdAt');
            $table->unsignedBigInteger('updatedAt');
            $table->unsignedBigInteger('closestTaskAt')->nullable();
            $table->unsignedBigInteger('accountId')->nullable();
            $table->boolean('isMain');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('requestId')->nullable();
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
        Schema::dropIfExists('contacts');
    }
};
