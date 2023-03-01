<?php

namespace Database\Seeders;
use App\Models\UserWorkspace;
use App\Models\Workspace;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Utility;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminUser = User::create([
                                      'name' => 'Admin',
                                      'email' => 'admin@example.com',
                                      'password' => Hash::make('1234'),
                                      'type' => 'admin',
                                  ]);

         User::defaultEmail();

        $objWorkspace = Workspace::create([
            'created_by'=>$adminUser->id,
            'name'=>'default',
            'currency_code' => 'USD',
            'paypal_mode' => 'sandbox'
        ]);
        $adminUser->current_workspace = $objWorkspace->id;
        $adminUser->save();

        UserWorkspace::create([
            'user_id' => $adminUser->id,
            'workspace_id' => $objWorkspace->id,
            'permission' => 'Owner'
        ]);
        }
}
