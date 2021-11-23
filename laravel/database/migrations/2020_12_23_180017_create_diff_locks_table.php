<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiffLocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diff_locks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->bigInteger('diff_id')->unsigned()->index();
            $table->bigInteger('member_id')->unsigned()->index();

            $table->unique(['diff_id']);
            $table->foreign('diff_id')->references('id')->on('diffs')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diff_locks');
    }
}
