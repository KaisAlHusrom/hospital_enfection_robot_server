<?php

use App\Enum\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role as SpatieRole;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Define your guard name
        $guardName = 'api';

        // Create the roles with guard names if they don't already exist
        foreach (Role::cases() as $role) {
            SpatieRole::firstOrCreate([
                'name' => $role->value,
                'guard_name' => $guardName,
            ]);
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            //
        });
    }
};
