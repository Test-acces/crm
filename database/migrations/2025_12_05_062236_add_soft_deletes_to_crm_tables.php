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
        Schema::table('clients', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
