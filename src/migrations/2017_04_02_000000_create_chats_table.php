<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatsTable extends Migration
{
    public function up()
    {
        Schema::create('bot_chats', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->primary('id');
            $table->string('type');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('bot_chats');
    }
}
