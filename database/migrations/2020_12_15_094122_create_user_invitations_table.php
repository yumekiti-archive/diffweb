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
            $table->bigInteger('invited_member_id')->index();
            $table->bigInteger('invited_partner_id')->index();

            $table->foreign('invited_partner_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('invited_member_id')->references('id')->on('members')
                ->onDelete('cascade')->onUpdate('cascade');

            // 同じメンバーが同じユーザーに対して複数回招待できないようにする。
            $table->unique('invited_member_id', 'invited_partner_id');
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
