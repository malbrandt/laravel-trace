<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trace', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('message');
            $table->string('type');
            $table->text('context');
            $table->string('source')->nullable();

            // Morph
            $table->string('parent_id')->nullable();
            $table->string('parent_type')->nullable();

            // Morph
            $table->string('author_id')->nullable();
            $table->string('author_type')->nullable();

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
        Schema::dropIfExists('trace');
    }
}
