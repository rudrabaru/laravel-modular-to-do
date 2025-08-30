<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Dashboard permissions
            'view-admin-dashboard',
            'view-manager-dashboard',
            'view-user-dashboard',
            
            // User management permissions
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'assign-roles',
            
            // Role and permission management (Admin only)
            'manage-roles',
            'manage-permissions',
            
            // Task management permissions
            'view-all-tasks',
            'view-own-tasks',
            'create-tasks',
            'edit-tasks',
            'delete-tasks',
            'assign-tasks',
            'complete-tasks',
            
            // Reminder management permissions
            'view-all-reminders',
            'view-own-reminders',
            'create-reminders',
            'edit-reminders',
            'delete-reminders',
            
            // System permissions
            'view-system-stats',
            'export-data',
            'import-data',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $this->createAdminRole();
        $this->createManagerRole();
        $this->createUserRole();
        
        // Create default admin user if none exists
        $this->createDefaultAdmin();
        
        // Create default manager user if none exists
        $this->createDefaultManager();
    }

    /**
     * Create Admin role with all permissions
     */
    private function createAdminRole(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // Admin gets all permissions
        $adminRole->givePermissionTo(Permission::all());
    }

    /**
     * Create Manager role with limited permissions
     */
    private function createManagerRole(): void
    {
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        
        // Manager permissions (similar to admin but without role/permission management)
        $managerPermissions = [
            'view-manager-dashboard',
            'view-users',
            'view-all-tasks',
            'create-tasks',
            'edit-tasks',
            'delete-tasks',
            'assign-tasks',
            'complete-tasks',
            'view-all-reminders',
            'create-reminders',
            'edit-reminders',
            'delete-reminders',
            'view-system-stats',
            'export-data',
        ];
        
        $managerRole->givePermissionTo($managerPermissions);
    }

    /**
     * Create User role with basic permissions
     */
    private function createUserRole(): void
    {
        $userRole = Role::firstOrCreate(['name' => 'user']);
        
        // User permissions (basic task management)
        $userPermissions = [
            'view-user-dashboard',
            'view-own-tasks',
            'create-tasks',
            'edit-tasks',
            'complete-tasks',
            'view-own-reminders',
            'create-reminders',
            'edit-reminders',
            'delete-reminders',
        ];
        
        $userRole->givePermissionTo($userPermissions);
    }

    /**
     * Create default admin user if none exists
     */
    private function createDefaultAdmin(): void
    {
        $adminUser = User::where('email', 'admin@example.com')->first();
        
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
        }
        
        // Assign admin role
        $adminUser->assignRole('admin');
    }

    /**
     * Create default manager user if none exists
     */
    private function createDefaultManager(): void
    {
        $managerUser = User::where('email', 'manager@example.com')->first();
        
        if (!$managerUser) {
            $managerUser = User::create([
                'name' => 'Manager User',
                'email' => 'manager@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
        }
        
        // Assign manager role
        $managerUser->assignRole('manager');
    }
}
