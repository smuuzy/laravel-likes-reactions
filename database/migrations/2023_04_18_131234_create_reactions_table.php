<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('reactions.table'), function (Blueprint $table) {
            $table->bigIncrements('id');

            $table
                ->{config('reactions.reactor.primary_key_type')}(config('reactions.reactor.foreign_key'))
                ->index();
            $table->morphs('likeable');
            $table->boolean('value');
            $table->timestamps();

            $table
                ->foreign(config('reactions.reactor.foreign_key'))
                ->on(config('reactions.reactor.table'))
                ->references(config('reactions.reactor.primary_key'))
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('reactions.table'));
    }
}
