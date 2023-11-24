<?php

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'trainer']);
        Role::create(['name' => 'dtc']);
        Role::create(['name' => 'jury']);
        Role::create(['name' => 'user']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system', function (Blueprint $table) {
            //
        });
    }
};
