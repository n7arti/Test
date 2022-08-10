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
        Schema::create('leads', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique();
            $table->string('name');
            $table->unsignedBigInteger('responsibleUserId');
            $table->unsignedBigInteger('groupId')->nullable();
            $table->unsignedBigInteger('createdBy');
            $table->unsignedBigInteger('updatedBy');
            $table->unsignedBigInteger('createdAt');
            $table->unsignedBigInteger('updatedAt');
            $table->unsignedBigInteger('accountId')->nullable();
            $table->unsignedBigInteger('pipelineId')->nullable();
            $table->unsignedBigInteger('statusId')->nullable();
            $table->unsignedBigInteger('closedAt')->nullable();
            $table->unsignedBigInteger('closestTaskAt')->nullable();
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('lossReasonId')->nullable();
            $table->boolean('isDeleted');
            $table->unsignedBigInteger('sourceId')->nullable();
            $table->unsignedBigInteger('sourceExternalId')->nullable();
            $table->unsignedBigInteger('score')->nullable();
            $table->boolean('isPriceModifiedByRobot');
            $table->unsignedBigInteger('company_id');
            $table->string('visitorUid')->nullable();
            $table->string('metadata_uid')->nullable();
            $table->string('complexRequestIds')->nullable();
            $table->string('requestId')->nullable();
            $table->timestamps();

            $table->index('company_id', 'lead_company_idx');
            $table->foreign('company_id', 'lead_company_fk')->on('companies')->references('id');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
};
