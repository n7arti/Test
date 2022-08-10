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
        Schema::create('fields', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique();
            $table->string('fieldCode');
            $table->string('fieldName');
            $table->unsignedBigInteger('lead_id');
            $table->timestamps();

            $table->index('lead_id', 'field_lead_idx');
            $table->foreign('lead_id', 'field_lead_fk')->on('leads')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fields');
    }
};
