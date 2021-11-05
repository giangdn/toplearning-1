<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('el_notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type', 150);
            $table->text('data');
            $table->timestamp('read_at')->nullable();
    
            $table->string("notifiable_type", 150);
            $table->unsignedBigInteger("notifiable_id");
            $table->index(["notifiable_type", "notifiable_id"], 'notifiable_type_notifiable_id_index');
            
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_notifications');
    }
}
