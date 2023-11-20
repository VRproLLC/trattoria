<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlertTimeInOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->bigInteger('payment_type_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.s
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->dropColumn('time');
            $table->dropColumn('payment_type_id');
        });
    }
}
