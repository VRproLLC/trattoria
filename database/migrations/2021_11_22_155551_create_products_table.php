<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('organization_id');
            $table->bigInteger('category_id');
            $table->string('iiko_id');
            $table->boolean('isIncludedInMenu');
            $table->boolean('isDeleted');
            $table->string('code')->nullable();
            $table->integer('price')->default(0);
            $table->string('parentGroup')->nullable();
            $table->string('energyAmount')->nullable();
            $table->string('energyFullAmount')->nullable();
            $table->string('fatAmount')->nullable();
            $table->string('fatFullAmount')->nullable();
            $table->string('fiberAmount')->nullable();
            $table->string('fiberFullAmount')->nullable();
            $table->string('weight')->nullable();
            $table->string('image')->nullable();
            $table->integer('sort')->default(0);

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
        Schema::dropIfExists('products');
    }
}
