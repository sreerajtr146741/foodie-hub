<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('table_type')->nullable(); // indoor/outdoor
            $table->string('seating_preference')->nullable(); // AC/Non-AC
            $table->boolean('window_side')->default(false);
            $table->string('occasion')->nullable(); // Birthday, Anniversary, etc.
            $table->string('table_number')->nullable();
            $table->text('admin_notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['table_type', 'seating_preference', 'window_side', 'occasion', 'table_number', 'admin_notes']);
        });
    }
};
