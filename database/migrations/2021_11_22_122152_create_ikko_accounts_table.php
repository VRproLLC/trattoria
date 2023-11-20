<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIkkoAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iiko_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->string('login')->nullable();
            $table->string('password')->nullable();
            $table->boolean('is_iiko')->default(1);
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
        Schema::dropIfExists('iiko_accounts');
    }
}
