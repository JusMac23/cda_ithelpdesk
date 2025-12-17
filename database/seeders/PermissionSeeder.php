<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_dashboard',

            'view_profile',
            'edit_profile',
            'update_password',
            'delete_profile',

            'view_all_tickets',
            'create_ticket',
            'reassign_ticket',
            'update_status_ticket',
            'delete_ticket',
            'search_ticket',
            'generate_tsar',
            'generate_report',

            'view_myrequested_tickets',
            'create_myrequested_tickets',
            'reassign_myrequested_tickets',
            'update_status_myrequested_tickets',
            'delete_myrequested_tickets',
            'search_myrequested_tickets',

            'view_assignedtome_tickets',
            'create_assignedtome_tickets',
            'reassign_assignedtome_tickets',
            'update_status_assignedtome_tickets',
            'delete_assignedtome_tickets',
            'search_assignedtome_tickets',

            'view_reassigned_tickets',
            'create_reassigned_tickets',
            'reassign_reassigned_tickets',
            'update_status_reassigned_tickets',
            'delete_reassigned_tickets',
            'search_reassigned_tickets',

            'view_all_databreach',
            'view_overview_databreach',
            'create_databreach',
            'view_databreach',
            'assess_databreach',
            'evaluate_databreach',
            'report_databreach',
            'delete_databreach',
            'search_databreach',
            'filter_databreach',
            'generate_databreach',

            'view_dbrt',
            'create_dbrt',
            'edit_dbrt',
            'delete_dbrt',

            'view_technical_personnel',
            'create_technical_personnel',
            'edit_technical_personnel',
            'delete_technical_personnel',
            'search_technical_personnel',

            'view_technical_services',
            'create_technical_services',
            'edit_technical_services',
            'delete_technical_services',
            'search_technical_services',

            'view_tech_users',
            'create_tech_users',
            'edit_tech_users',
            'delete_tech_users',
            'search_tech_users',

            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'search_roles',
        ];

        foreach ($permissions as $key => $value) {
            Permission::firstOrCreate(['name' => $value]);
        }
    }
}
