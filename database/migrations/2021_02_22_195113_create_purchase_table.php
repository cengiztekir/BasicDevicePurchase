<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger("Status")->default("1");
            $table->dateTime("ExpireDate");
            $table->string("ClientToken",60);
            $table->string("receipt",100);
            $table->string("Message",250);
            $table->unique(['ClientToken', 'ExpireDate']);
            $table->index(['ClientToken', 'ExpireDate']);
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
        Schema::dropIfExists('purchase');
    }
}
