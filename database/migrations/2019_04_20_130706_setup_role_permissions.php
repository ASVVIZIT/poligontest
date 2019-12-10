<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Laravue\Models\Role;
use App\Laravue\Models\Permission;
use App\Laravue\Acl;

class SetupRolePermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (Acl::roles() as $role) {
            Role::findOrCreate($role, 'api');
        }
        $adminRole = Role::findByName(Acl::ROLE_ADMIN);
        $moderatorRole = Role::findByName(Acl::ROLE_MODERATOR);
        $managerRole = Role::findByName(Acl::ROLE_MANAGER);
        $editorRole = Role::findByName(Acl::ROLE_EDITOR);
        $userRole = Role::findByName(Acl::ROLE_USER);
        $visitorRole = Role::findByName(Acl::ROLE_VISITOR);
        $guestRole = Role::findByName(Acl::ROLE_GUEST);

        foreach (Acl::permissions() as $permission) {
            Permission::findOrCreate($permission, 'api');
        }

        // Setup basic permission
        $adminRole->givePermissionTo(Acl::menuPermissions());
        $adminRole->givePermissionTo(Acl::permissions());

        $moderatorRole->givePermissionTo(Acl::menuPermissions());
        $moderatorRole->givePermissionTo(Acl::permissions([
            Acl::PERMISSION_PERMISSION_MODERATOR,
            Acl::PERMISSION_USER_MODERATOR,
        ]));

        $managerRole->givePermissionTo(Acl::menuPermissions());
        $managerRole->givePermissionTo(Acl::permissions([
            Acl::PERMISSION_PERMISSION_MANAGE,
            Acl::PERMISSION_USER_MANAGE,
        ]));


        $editorRole->givePermissionTo(Acl::menuPermissions());
        $editorRole->givePermissionTo(Acl::permissions([
            Acl::PERMISSION_PERMISSION_EDITOR,
            Acl::PERMISSION_USER_EDITOR,
        ]));

        $userRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_ELEMENT_UI,
            Acl::PERMISSION_VIEW_MENU_ORDER,
        ]);

        $visitorRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_ELEMENT_UI,
        ]);

        $guestRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_ELEMENT_UI,
        ]);

        foreach (Acl::roles() as $role) {
            /** @var \App\User[] $users */
            $users = \App\Laravue\Models\User::where('role', $role)->get();
            $role = Role::findByName($role);
            foreach ($users as $user) {
                $user->syncRoles($role);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('editor');
            });
        }

        /** @var \App\User[] $users */
        $users = \App\Laravue\Models\User::all();
        foreach ($users as $user) {
            $roles = array_reverse(Acl::roles());
            foreach ($roles as $role) {
                if ($user->hasRole($role)) {
                    $user->role = $role;
                    $user->save();
                }
            }
        }
    }
}
