<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop Nova-related tables that exist in the digitization_academy database.
        // Dropping attachments first, then notifications, then action log table.
        $tables = [
            'nova_pending_field_attachments',
            'nova_field_attachments',
            'nova_notifications',
            'action_events',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }

    public function down(): void
    {
        // Intentionally left blank:
        // Recreating Nova tables requires matching Nova's exact schema/version.
        // If you want reversibility, tell me your previous Nova version and I’ll write the table definitions.
    }
};
