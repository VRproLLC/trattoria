<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ikko_account_id');
            $table->string('iiko_id');
            $table->boolean('isActive')->default(0);
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('fullName')->nullable();
            $table->text('description')->nullable();
            $table->text('address')->nullable();
            $table->string('name')->nullable();
            $table->string('organizationType')->nullable();
            $table->string('timezone')->nullable();
            $table->string('workTime')->nullable();
            $table->string('email')->nullable();
            $table->string('location')->nullable();
            $table->string('phone')->nullable();
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
        Schema::dropIfExists('organizations');
    }
}
