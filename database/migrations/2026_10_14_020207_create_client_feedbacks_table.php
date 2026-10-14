<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('client_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('ticket_id')->index();
            $table->string('client_type')->nullable();
            $table->string('feedback_date')->nullable();
            $table->string('sex')->nullable();
            $table->string('age')->nullable();
            $table->string('region')->nullable();
            $table->text('services')->nullable();
            $table->string('service_other')->nullable();
            $table->string('cc1')->nullable();
            $table->string('cc2')->nullable();
            $table->string('cc3')->nullable();
            $table->string('sqd1')->nullable();
            $table->string('sqd2')->nullable();
            $table->string('sqd3')->nullable();
            $table->string('sqd4')->nullable();
            $table->string('sqd5')->nullable();
            $table->string('sqd6')->nullable();
            $table->string('sqd7')->nullable();
            $table->string('sqd8')->nullable();
            $table->text('suggestions')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_feedbacks');
    }
};
