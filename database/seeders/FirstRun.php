<?php

namespace Database\Seeders;

// libs
    use Illuminate\Database\Seeder;

    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Hash;

    use Carbon\Carbon;
// ====
// models
    use App\Models\User;
    use App\Models\System\Role;
    use App\Models\System\UserRole;
    use App\Models\System\Menu;
    use App\Models\System\MenuRole;
// ====

class FirstRun extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // initiate return value
            $result = [];
            $result['is_valid'] = false;
            $result['status'] = 500;
            $result['message'] = '';
            $result['execution_time'] = 0;
            $result['data'] = [];
        // =====================
        $currentdatetime = Carbon::now()->addHours(7)->toDateTimeString();
        DB::connection('mysql')->beginTransaction();
        try {
            // set start time
                $startTime = microtime(true);
            // ==============
            $superadminrole = [];
            // cek if super admin role data allready exist
                $superadminrole = Role::wherenull('deleted_at')->where('kode','SA')->where('name','Super Admin')->first();
                if (empty($superadminrole)) {

                    $role = [];
                    $role['kode'] = 'SA';
                    $role['name'] = 'Super Admin';
                    $role['created_at'] = $currentdatetime;

                    $superadminrole = Role::create($role)->toarray();
                }else{
                    $superadminrole = $superadminrole->toarray();
                }
            // ==============
            $superadminuser = [];
            // cek super admin user
                $superadminuser =   User::with([
                                        'UserRole' => function($q){
                                            $q->wherenull('deleted_at');
                                        }
                                    ])
                                    ->wherenull('deleted_at')
                                    ->wherehas('UserRole', function($q) use($superadminrole){
                                        $q->wherenull('deleted_at')->where('idrole', $superadminrole['id']);
                                    })
                                    ->first();
                if (empty($superadminuser)) {
                    // create user
                        $user = [];
                        $user['name'] = 'Super Admin';
                        $user['username'] = 'admin';
                        $user['countrycode'] = '62';
                        $user['telp'] = '0';
                        $user['email'] = 'admin@mail.com';
                        $user['password'] = Hash::make('admin');
                        $user['created_at'] = $currentdatetime;

                        $superadminuser = User::create($user)->toarray();
                    // ===========
                    // insert user role
                        $userrole = [];
                        $userrole['iduser'] = $superadminuser['id'];
                        $userrole['idrole'] = $superadminrole['id'];
                        $userrole['created_at'] = $currentdatetime;

                        UserRole::create($userrole);
                    // ===========
                }
            // ==============
            // cek master menu
                $mainmenu = Menu::wherenull('deleted_at')->where('kode', 'MENU')->first();
                if (empty($mainmenu)) {
                    $settingmenu = [];
                    // create setting menu
                        $settingmenu['kode'] = 'setting';
                        $settingmenu['name'] = 'Setting';
                        $settingmenu['icon'] = 'fa-cog';
                        $settingmenu['urutan'] = 1;
                        $settingmenu['created_at'] = $currentdatetime;

                        $settingmenu['id'] = Menu::create($settingmenu)->id;
                    // ===================
                    // insert setting menu role
                        $menurole = [];
                        $menurole['idmenu'] = $settingmenu['id'];
                        $menurole['idrole'] = $superadminrole['id'];
                        $menurole['is_create'] = '1';
                        $menurole['is_read'] = '1';
                        $menurole['is_update'] = '1';
                        $menurole['is_delete'] = '1';
                        $menurole['is_reset'] = '1';
                        $menurole['is_reject'] = '1';
                        $menurole['is_close'] = '1';
                        $menurole['is_admin'] = '1';
                        $menurole['tingkatapprove'] = '1';
                        $menurole['created_at'] = $currentdatetime;

                        $menurole['id'] = MenuRole::create($menurole);
                    // ===================
                    // insert menu detail setting
                        // insert menu
                            // insert main menu
                                $mainmenu = [];
                                $mainmenu['idmenu'] = $settingmenu['id'];
                                $mainmenu['kode'] = 'menu';
                                $mainmenu['name'] = 'Menu';
                                $mainmenu['icon'] = 'fa-bars';
                                $mainmenu['urutan'] = 1;
                                $mainmenu['jenis'] = 1;
                                $mainmenu['tingkatapprove'] = '0';
                                $mainmenu['created_at'] = $currentdatetime;
                                $mainmenu['viewparent'] = 'setting.menu';
                                $mainmenu['mainapiroute'] = 'api.setting.menu';
                                $mainmenu['mainapicontroller'] = 'Api.Setting.MenuController';
                                $mainmenu['mainjsroute'] = 'Menu';
                                $mainmenu['mainjs'] = 'setting.menu';
                                $mainmenu['tingkatapprove'] = '0';
                                $mainmenu['id'] = Menu::create($mainmenu)->id;
                            // ===================
                            // insert menu role
                                $menurole = [];
                                $menurole['idmenu'] = $mainmenu['id'];
                                $menurole['idrole'] = $superadminrole['id'];
                                $menurole['is_create'] = '1';
                                $menurole['is_read'] = '1';
                                $menurole['is_update'] = '1';
                                $menurole['is_delete'] = '1';
                                $menurole['is_reset'] = '1';
                                $menurole['is_reject'] = '1';
                                $menurole['is_close'] = '1';
                                $menurole['is_admin'] = '1';
                                $menurole['tingkatapprove'] = '0';
                                $menurole['created_at'] = $currentdatetime;

                                $menurole['id'] = MenuRole::create($menurole);
                            // ===================
                        // ===========
                        // insert role
                            // insert main menu
                                $mainmenu = [];
                                $mainmenu['idmenu'] = $settingmenu['id'];
                                $mainmenu['kode'] = 'role';
                                $mainmenu['name'] = 'Role';
                                $mainmenu['icon'] = 'fa-cog';
                                $mainmenu['urutan'] = 2;
                                $mainmenu['jenis'] = 1;
                                $mainmenu['tingkatapprove'] = '0';
                                $mainmenu['created_at'] = $currentdatetime;
                                $mainmenu['viewparent'] = 'setting.role';
                                $mainmenu['mainapiroute'] = 'api.setting.role';
                                $mainmenu['mainapicontroller'] = 'Api.Setting.RoleController';
                                $mainmenu['mainjsroute'] = 'Role';
                                $mainmenu['mainjs'] = 'setting.role';
                                $mainmenu['tingkatapprove'] = '0';
                                $mainmenu['id'] = Menu::create($mainmenu)->id;
                            // ===================
                            // insert menu role
                                $menurole = [];
                                $menurole['idmenu'] = $mainmenu['id'];
                                $menurole['idrole'] = $superadminrole['id'];
                                $menurole['is_create'] = '1';
                                $menurole['is_read'] = '1';
                                $menurole['is_update'] = '1';
                                $menurole['is_delete'] = '1';
                                $menurole['is_reset'] = '1';
                                $menurole['is_reject'] = '1';
                                $menurole['is_close'] = '1';
                                $menurole['is_admin'] = '1';
                                $menurole['tingkatapprove'] = '0';
                                $menurole['created_at'] = $currentdatetime;

                                $menurole['id'] = MenuRole::create($menurole);
                            // ===================
                        // ===========
                        // insert user
                            // insert main menu
                                $mainmenu = [];
                                $mainmenu['idmenu'] = $settingmenu['id'];
                                $mainmenu['kode'] = 'user';
                                $mainmenu['name'] = 'user';
                                $mainmenu['icon'] = 'fa-user';
                                $mainmenu['urutan'] = 3;
                                $mainmenu['jenis'] = 1;
                                $mainmenu['tingkatapprove'] = '0';
                                $mainmenu['created_at'] = $currentdatetime;
                                $mainmenu['viewparent'] = 'setting.user';
                                $mainmenu['mainapiroute'] = 'api.setting.user';
                                $mainmenu['mainapicontroller'] = 'Api.Setting.UserController';
                                $mainmenu['mainjsroute'] = 'User';
                                $mainmenu['mainjs'] = 'setting.user';
                                $mainmenu['tingkatapprove'] = '0';
                                $mainmenu['id'] = Menu::create($mainmenu)->id;
                            // ===================
                            // insert menu role
                                $menurole = [];
                                $menurole['idmenu'] = $mainmenu['id'];
                                $menurole['idrole'] = $superadminrole['id'];
                                $menurole['is_create'] = '1';
                                $menurole['is_read'] = '1';
                                $menurole['is_update'] = '1';
                                $menurole['is_delete'] = '1';
                                $menurole['is_reset'] = '1';
                                $menurole['is_reject'] = '1';
                                $menurole['is_close'] = '1';
                                $menurole['is_admin'] = '1';
                                $menurole['tingkatapprove'] = '0';
                                $menurole['created_at'] = $currentdatetime;

                                $menurole['id'] = MenuRole::create($menurole);
                            // ===================
                        // ===========
                    // ===================
                }
            // ==============
            DB::connection('mysql')->commit();
            // set end time
                $endTime = microtime(true); 
            // =================
            // set return value lainnya
                $result['is_valid'] = true;
                $result['status'] = 200;
                $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
            // =================
        } catch (\Throwable $th) {
            DB::connection('mysql')->rollback();
            echo $th->getmessage();
        }
    }
}
