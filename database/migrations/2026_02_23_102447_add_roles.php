<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Role::findOrCreate('admin');
        Role::findOrCreate('trainer');
        Role::findOrCreate('dtc');
        Role::findOrCreate('jury');
        Role::findOrCreate('user');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
