<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrateDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('Status')->default('1');
            $table->string('uid',100)->index();
            $table->string('appId',100);
            $table->string('language',50);
            $table->string('OpSys',100);
            $table->string('ClientToken',60)->index();
            $table->unique(['uid', 'appId']);
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
        Schema::dropIfExists('device');
    }
}
