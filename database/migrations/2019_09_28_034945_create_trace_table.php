<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Malbrandt\Laravel\Trace\Contracts\TraceInterface;

class CreateTraceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'trace',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('source')->index();
                $table->string('type')->default(TraceInterface::TYPE_INFO)->index();
                $table->string('message');
                $table->text('context');
                $table->timestamps();
            }
        );
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
