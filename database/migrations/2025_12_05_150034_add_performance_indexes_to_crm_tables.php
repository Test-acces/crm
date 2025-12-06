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
        // Index pour les clients
        Schema::table('clients', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'clients_user_status_index');
            $table->index('created_at', 'clients_created_at_index');
        });

        // Index pour les contacts
        Schema::table('contacts', function (Blueprint $table) {
            $table->index('client_id', 'contacts_client_id_index');
            $table->index(['client_id', 'name'], 'contacts_client_name_index');
        });

        // Index pour les tâches
        Schema::table('tasks', function (Blueprint $table) {
            $table->index(['client_id', 'status'], 'tasks_client_status_index');
            $table->index(['user_id', 'status'], 'tasks_user_status_index');
            $table->index('due_date', 'tasks_due_date_index');
            $table->index(['status', 'due_date'], 'tasks_status_due_date_index');
            $table->index('created_at', 'tasks_created_at_index');
        });

        // Index pour les activités
        Schema::table('activities', function (Blueprint $table) {
            $table->index(['client_id', 'date'], 'activities_client_date_index');
            $table->index(['user_id', 'date'], 'activities_user_date_index');
            $table->index('date', 'activities_date_index');
            $table->index(['type', 'date'], 'activities_type_date_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les index pour les clients
        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex('clients_user_status_index');
            $table->dropIndex('clients_created_at_index');
        });

        // Supprimer les index pour les contacts
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropIndex('contacts_client_id_index');
            $table->dropIndex('contacts_client_name_index');
        });

        // Supprimer les index pour les tâches
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex('tasks_client_status_index');
            $table->dropIndex('tasks_user_status_index');
            $table->dropIndex('tasks_due_date_index');
            $table->dropIndex('tasks_status_due_date_index');
            $table->dropIndex('tasks_created_at_index');
        });

        // Supprimer les index pour les activités
        Schema::table('activities', function (Blueprint $table) {
            $table->dropIndex('activities_client_date_index');
            $table->dropIndex('activities_user_date_index');
            $table->dropIndex('activities_date_index');
            $table->dropIndex('activities_type_date_index');
        });
    }
};
