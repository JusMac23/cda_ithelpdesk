<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Fixes `id` on `reassigned_tickets` to use AUTO_INCREMENT.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE `reassigned_tickets` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE `reassigned_tickets` MODIFY `id` BIGINT UNSIGNED NOT NULL;');
    }
};
