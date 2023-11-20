<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_types', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('organization_id');
            $table->string('iiko_id');
            $table->string('code');
            $table->string('name');
            $table->text('comment')->nullable();
            $table->string('combinable')->nullable();
            $table->string('applicableMarketingCampaigns')->nullable();
            $table->boolean('isDeleted')->default(0);

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
        Schema::dropIfExists('payment_types');
    }
}
