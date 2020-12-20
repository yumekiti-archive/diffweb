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
            $table->bigInteger('author_id')->unsigned()->nullable()->index();
        
            $table->bigInteger('invited_partner_id')->unsigned()->index();

            $table->foreign('invited_partner_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');

            /**
             * usersはあくまでも作成者情報であってどのメンバーによって発行されているので、membersには属さない。
             */
            $table->foreign('author_id')->references('id')->on('users')
                ->onDelete('set null')->onUpdate('cascade');

            // 同じメンバーが同じユーザーに対して複数回招待できないようにする。
            $table->unique('author_id', 'invited_partner_id');
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
