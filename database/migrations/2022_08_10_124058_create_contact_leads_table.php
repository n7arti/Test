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
        Schema::create('contact_leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id');
            $table->unsignedBigInteger('lead_id');

            $table->index('contact_id', 'contact_lead_contact_idx');
            $table->index('lead_id', 'contact_lead_lead_idx');

            $table->foreign('contact_id', 'contact_lead_contact_fk')->on('contacts')->references('id');
            $table->foreign('lead_id', 'contact_lead_lead_fk')->on('leads')->references('id');
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
        Schema::dropIfExists('contact_leads');
    }
};
