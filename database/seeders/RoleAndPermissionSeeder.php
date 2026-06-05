<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $guruRole = Role::create(['name' => 'guru']);
        $siswaRole = Role::create(['name' => 'siswa']);

        // Create default users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@lms.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole($adminRole);

        $guru = User::create([
            'name' => 'Guru User',
            'email' => 'guru@lms.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $guru->assignRole($guruRole);

        $siswa = User::create([
            'name' => 'Siswa User',
            'email' => 'siswa@lms.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $siswa->assignRole($siswaRole);
    }
}
