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
        Schema::table('todos', function (Blueprint $table) {
            // Modify title to be max 100 characters
            $table->string('title', 100)->change();

            // Modify body to be required and max 300 characters
            $table->string('body', 300)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            // Revert title back to default string (255 characters)
            $table->string('title')->change();

            // Revert body back to nullable text
            $table->text('body')->nullable()->change();
        });
    }
};
