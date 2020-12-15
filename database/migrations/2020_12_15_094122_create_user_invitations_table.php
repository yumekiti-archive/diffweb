<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_invitations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('invited_user_id')->index();
            $table->bigInteger('invited_partner_id')->index();
            $table->bigInteger('diff_id')->index();

            $table->foreign('invited_user_id')->references('id')->on('users')
                ->onDelete('cascade')->onDelete('cascade');
            $table->foreign('invited_partner_id')->references('id')->on('users');
            $table->foreign('diff_id')->references('diff_id')->on('diffs')
                ->onDelete('cascade')->onDelete('cascade');

            // 同じDiffに対して同じユーザーが同じユーザーを複数回招待できないようにする。
            $table->unique('invited_user_id', 'invited_partner_id', 'diff_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_invitations');
    }
}
