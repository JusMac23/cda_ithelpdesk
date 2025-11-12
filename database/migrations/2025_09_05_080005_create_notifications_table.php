<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Who should receive the notification
            $table->unsignedBigInteger('ticket_id')->nullable(); // Related ticket
            $table->string('type'); // e.g., 'ticket_created', 'ticket_assigned', 'ticket_updated'
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users_tbl')
                  ->onDelete('cascade');

            $table->foreign('ticket_id')
                  ->references('ticket_id')
                  ->on('tickets_tbl')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
