<?php

namespace App\Http\Controllers\Main;

// libs
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Session;
    use Carbon\Carbon;
// ====
// controller
    use App\Http\Controllers\Controller;
    use App\Http\Controllers\Api\Setting\MenuController as MenuApi;
    use App\Http\Controllers\Api\Setting\UserController as UserApi;
    
    use App\Http\Controllers\Main\SistemController as SistemApi;
// ==========
// model

// =====

class MainController extends Controller
{
    public function __construct(MenuApi $menuapi, UserApi $userapi, SistemApi $sistemapi){
        $this->MenuApi = $menuapi;
        $this->UserApi = $userapi;
        $this->SistemApi = $sistemapi;
    }
    // get default menu param
        public function getmenuparam($requestdata = null){
            // initiate return value
                $result = [];
                $result['is_valid'] = false;
                $result['status'] = 500;
                $result['message'] = '';
                $result['execution_time'] = 0;
                $result['data'] = [];
            // =====================
            
            try {
                // set start time
                    $startTime = microtime(true);
                // ==============
                // action
                    $menuparam = [];
                    // ambil data user
                        $dataparam = [];
                        $dataparam['id'] = $requestdata['iduser'];
                        $userdata = $this->UserApi->getdatadetail($dataparam);
                        if (empty($userdata['data'])) {
                            // set end time
                                $endTime = microtime(true);
                                $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
                            // =================
                            $result['message'] = 'Data User Tidak Ditemukan '.$userdata['message'];
                            return $result;
                        }
                    // ================
                    // set user data
                        $menuparam['userdata'] = $userdata['data'];
                    // ===============
                    // set default menu param
                        // jika 
                        if (!empty($requestdata) && isset($requestdata['page'])) {
                            $menudata = [];
                            // ambil detail menu
                                $dataparam = [];
                                $dataparam['idrole'] = $menuparam['userdata']['user_role']['idrole'];
                                $dataparam['kode'] = $requestdata['page'];
                                $menudata = $this->MenuApi->getdatadetail($dataparam);
                                if (empty($menudata['data'])) {
                                    // set end time
                                        $endTime = microtime(true);
                                        $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
                                    // =================
                                    $result['message'] = 'Halaman Tidak Ditemukan '.$menudata['message'];
                                    return $result;
                                }
                                // set base menu param
                                    $menuparam['menu_parent1'] = !empty($menudata['data']['parent']) ? $menudata['data']['parent']['kode'] : '';
                                    $menuparam['menu_parent1_name'] = !empty($menudata['data']['parent']) ? $menudata['data']['parent']['name'] : '';
                                    $menuparam['menu_parent2'] = !empty($menudata['data']['parent']) && !empty($menudata['data']['parent']['parent']) ? $menudata['data']['parent']['parent']['kode'] : '';
                                    $menuparam['menu_parent2_name'] =!empty($menudata['data']['parent']) && !empty($menudata['data']['parent']['parent']) ? $menudata['data']['parent']['parent']['kode'] : '';
                                    $menuparam['menu'] = $menudata['data']['kode'];
                                    $menuparam['menu_name'] = $menudata['data']['name'];
                                    $menuparam['viewparent'] = $menudata['data']['viewparent'];
                                // ===================
                                if (!isset($requestdata['action'])) {
                                    $menuparam['view'] = $menudata['data']['viewparent'].'.pages.index';
                                }else{
                                    if (strtoupper($requestdata['action']) == 'ADD' || strtoupper($requestdata['action']) == 'UPDATE') {
                                        $menuparam['view'] = $menudata['data']['viewparent'].'.pages.action';
                                        $menuparam['action'] = strtoupper($requestdata['action']) == 'ADD' ? 'Tambah' : 'Ubah';
                                        $menuparam['form-id'] = strtoupper($requestdata['action']) == 'ADD' ? 'form-tambah' : 'form-ubah';

                                        // ambil item data jika action update
                                            if (strtoupper($requestdata['action']) == 'UPDATE') {
                                                $api = str_replace('.','\\',$menudata['data']['mainapicontroller']);
                                                $dataparam = [];
                                                // set parameter
                                                    if (isset($requestdata['id'])) {
                                                        $dataparam['id'] = $requestdata['id'];
                                                    }
                                                // =============
                                                $itemdata = app()->call('App\\Http\\Controllers\\'.$api.'@'.'getdatadetail', ['requestdata' => $dataparam]);
                                                if (empty($itemdata['data'])) {
                                                    // set end time
                                                        $endTime = microtime(true);
                                                        $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
                                                    // =================
                                                    $result['message'] = 'Data tidak ditemukan '.$itemdata['message'];
                                                    return $result;
                                                }
                                                $menuparam['itemdata'] = $itemdata['data'];
                                            }
                                        // ==================================
                                    }
                                }
                                $menuparam['mainapiroute'] = $menudata['data']['mainapiroute'];
                                $menuparam['mainjsroute'] = $menudata['data']['mainjsroute'];
                                $menuparam['mainjs'] = $menudata['data']['mainjs'];
                                $menuparam['tingkatapprove'] = $menudata['data']['tingkatapprove'];
                            // =================
                            $menuparam['menupermission'] = [];
                            // ambil user permission
                                if (empty($menudata['data']['menu_role'])) {
                                    // set end time
                                        $endTime = microtime(true);
                                        $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
                                    // =================
                                    $result['message'] = 'Anda tidak memiliki akses ke halaman ini';
                                    return $result;
                                }
                                if ($menudata['data']['menu_role'][0]['is_read'] == '0') {
                                    // set end time
                                        $endTime = microtime(true);
                                        $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
                                    // =================
                                    $result['message'] = 'Anda tidak memiliki akses ke halaman ini';
                                    return $result;
                                }
                                $menuparam['menupermission'] = $menudata['data']['menu_role'][0];
                            // =====================
                            $menuparam['menu_action'] = [];
                            // ambil action
                                $dataparam = [];
                                $dataparam['menu'] = $menuparam['menu'];
                                $dataparam['jenis'] = $menudata['data']['jenis'];
                                $dataparam['idtransaksi'] = isset($requestdata['id']) ? $requestdata['id'] : '0';
                                $dataparam['idtag'] = isset($itemdata['data']['idtag']) ? $itemdata['data']['idtag'] : '0';
                                $dataparam['status'] = isset($itemdata['data']['status']) ? $itemdata['data']['status'] : '0';
                                $dataparam['created_by'] = isset($itemdata['data']['created_by']) && !empty($itemdata['data']['created_by']) ? $itemdata['data']['created_by'] : '0';
                                $dataparam['max_approve'] = $menuparam['tingkatapprove'];
                                $dataparam['menupermission'] = $menuparam['menupermission'];

                                $action = $this->getaction($dataparam);
                                $menuparam['menu_action'] = $action['data'];
                            // =====================
                        }
                        else {
                            $menuparam['menu_parent1'] = '';
                            $menuparam['menu_parent1_name'] = '';
                            $menuparam['menu_parent2'] = '';
                            $menuparam['menu_parent2_name'] = '';
                            $menuparam['menu'] = 'dashboard';
                            $menuparam['menu_name'] = 'Dashboard';
                            $menuparam['viewparent'] = '';
                            $menuparam['view'] = 'home';
                        }
                    // ======================
                    // ambil list menu
                        $dataparam = [];
                        $dataparam['idrole'] = $menuparam['userdata']['user_role']['idrole'];

                        $usermenulist = $this->MenuApi->usermenulist($dataparam);
                        $menuparam['menulist'] = $usermenulist['data'];
                    // ===============
                    $menuparam['id'] = isset($requestdata['id']) ? $requestdata['id'] : '0';
                    $result['data'] = $menuparam;
                // ==============
                // set end time
                    $endTime = microtime(true); 
                // =================
                // set return value lainnya
                    $result['is_valid'] = true;
                    $result['status'] = 200;
                    $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
                // =================
            } catch (\Throwable $th) {
                $result['message'] = $th->getmessage();
            }

            return $result;
        }
        public function getmodalparam($requestdata = null){
            // initiate return value
                $result = [];
                $result['is_valid'] = false;
                $result['status'] = 500;
                $result['message'] = '';
                $result['execution_time'] = 0;
                $result['data'] = [];
            // =====================
            
            try {
                // set start time
                    $startTime = microtime(true);
                // ==============
                // action
                    $menuparam = [];
                    $menudata = [];
                    // ambil detail menu
                        $dataparam = [];
                        $dataparam['kode'] = $requestdata['page'];
                        $menudata = $this->MenuApi->getdatadetail($dataparam);
                        if (empty($menudata['data'])) {
                            // set end time
                                $endTime = microtime(true);
                                $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
                            // =================
                            $result['message'] = 'Halaman Tidak Ditemukan '.$menudata['message'];
                            return $result;
                        }
                        $menuparam['menu_parent1'] = !empty($menudata['data']['parent']) ? $menudata['data']['parent']['kode'] : '';
                        $menuparam['menu_parent1_name'] = !empty($menudata['data']['parent']) ? $menudata['data']['parent']['name'] : '';
                        $menuparam['menu_parent2'] = !empty($menudata['data']['parent']) && !empty($menudata['data']['parent']['parent']) ? $menudata['data']['parent']['parent']['kode'] : '';
                        $menuparam['menu_parent2_name'] =!empty($menudata['data']['parent']) && !empty($menudata['data']['parent']['parent']) ? $menudata['data']['parent']['parent']['kode'] : '';
                        $menuparam['menu'] = $menudata['data']['kode'];
                        $menuparam['menu_name'] = $menudata['data']['name'];
                        $menuparam['viewparent'] = $menudata['data']['viewparent'];
                        $menuparam['mainapiroute'] = $menudata['data']['mainapiroute'];
                        $menuparam['mainjsroute'] = $menudata['data']['mainjsroute'];
                        $menuparam['mainjs'] = $menudata['data']['mainjs'];
                    // ===============
                    $result['data'] = $menuparam;
                // ==============
                // set end time
                    $endTime = microtime(true); 
                // =================
                // set return value lainnya
                    $result['is_valid'] = true;
                    $result['status'] = 200;
                    $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
                // =================
            } catch (\Throwable $th) {
                $result['message'] = $th->getmessage();
            }

            return $result;
        }
        public function getaction($requestdata){
            // initiate return value
                $result = [];
                $result['is_valid'] = false;
                $result['status'] = 500;
                $result['message'] = '';
                $result['execution_time'] = 0;
                $result['data'] = [];
            // =====================
            $currentdatetime = Carbon::now()->addHours(7)->toDateTimeString();
            try {
                // set start time
                    $startTime = microtime(true);
                // ==============
                // action
                    $actioncount = 0;
                    $iscreate = 0;
                    $isupdate = 0;
                    $isreset = 0;
                    $isreject = 0;
                    $isclose = 0;
                    $isapprove = 0;
                    // jika jenis by menu ( 1 )
                        if ($requestdata['jenis'] == 1) {
                            // jika idtransaksi = 0
                                if ($requestdata['idtransaksi'] == 0) {
                                    $iscreate = 1;
                                    $actioncount++;
                                }
                            // ====================
                            // jika idtransaksi > 0
                                else{
                                    // cek action
                                        // jika module tidak memiliki rule approval
                                            if ($requestdata['max_approve'] == '0') {
                                                // jika is_update = 1
                                                    if (isset($requestdata['menupermission']) && $requestdata['menupermission']['is_update'] == 1) {
                                                        $isupdate = 1;
                                                        $actioncount++;
                                                    }
                                                // ==================
                                            }
                                        // ========================================
                                        // jika module memiliki rule approval
                                            else{
                                                // jika status 0
                                                    if ($requestdata['status'] == '0' && isset($requestdata['menupermission']) && $requestdata['menupermission']['is_update'] == 1 ) {
                                                        $isupdate = 1;
                                                        $actioncount++;
                                                    }
                                                // =============
                                                // jika status > 0
                                                    else{
                                                        // cek rule approval
                                                            // jika tingkat approve >= max approve dan status 1,8,9,10,11
                                                                if (isset($requestdata['menupermission']) && $requestdata['menupermission']['tingkatapprove'] >= $requestdata['max_approve'] && ( $requestdata['status'] == 1 || $requestdata['status'] == 8 || $requestdata['status'] == 9 || $requestdata['status'] == 10 || $requestdata['status'] == 11 ) ) {
                                                                    $isapprove = 1;
                                                                    $actioncount++;            
                                                                }
                                                            // ===================================
                                                            // jika tidak
                                                                else{
                                                                    // jika tingkat approve = 1 dan status = 1
                                                                        if (isset($requestdata['menupermission']) && $requestdata['menupermission']['tingkatapprove'] == 1 && ( $requestdata['status'] == 1 )) {
                                                                            $isapprove = 1;
                                                                            $actioncount++;            
                                                                        }
                                                                    // =======================================
                                                                    // jika tingkat approve = 2 dan status = 1,8
                                                                        elseif (isset($requestdata['menupermission']) && $requestdata['menupermission']['tingkatapprove'] == 2 && ( $requestdata['status'] == 1 || $requestdata['status'] == 8 )) {
                                                                            $isapprove = 1;
                                                                            $actioncount++;
                                                                        }
                                                                    // =========================================
                                                                    // jika tingkat approve = 3 dan status = 1,8,9
                                                                        elseif (isset($requestdata['menupermission']) && $requestdata['menupermission']['tingkatapprove'] == 3 && ( $requestdata['status'] == 1 || $requestdata['status'] == 8 || $requestdata['status'] == 9 )) {
                                                                            $isapprove = 1;
                                                                            $actioncount++;
                                                                        }
                                                                    // =======================================
                                                                    // jika tingkat approve = 4 dan status = 1,8,9,10
                                                                        elseif (isset($requestdata['menupermission']) && $requestdata['menupermission']['tingkatapprove'] == 4 && ( $requestdata['status'] == 1 || $requestdata['status'] == 8 || $requestdata['status'] == 9 || $requestdata['status'] == 10 )) {
                                                                            $isapprove = 1;
                                                                            $actioncount++;
                                                                        }
                                                                    // =======================================
                                                                    // jika tingkat approve = 5 dan status = 1,8,9,10,11
                                                                        elseif (isset($requestdata['menupermission']) && $requestdata['menupermission']['tingkatapprove'] == 5 && ( $requestdata['status'] == 1 || $requestdata['status'] == 8 || $requestdata['status'] == 9 || $requestdata['status'] == 10 || $requestdata['status'] == 11 )) {
                                                                            $isapprove = 1;
                                                                            $actioncount++;
                                                                        }
                                                                    // =======================================
                                                                }
                                                            // ==========
                                                        // =================
                                                        // cek rule reset
                                                            // jika tingkat approve >= max approve dan status 1,8,9,10,11,2
                                                                if (isset($requestdata['menupermission']) && $requestdata['menupermission']['tingkatapprove'] >= $requestdata['max_approve'] && ( $requestdata['status'] == 1 || $requestdata['status'] == 8 || $requestdata['status'] == 9 || $requestdata['status'] == 10 || $requestdata['status'] == 11 || $requestdata['status'] == 2 ) ) {
                                                                    $isreset = 1;
                                                                    $actioncount++;            
                                                                }
                                                            // ============================================================
                                                            // jika tidak
                                                                else{
                                                                    // jika tingkat approve = 0 dan status = 1
                                                                        if (isset($requestdata['menupermission']) && $requestdata['menupermission']['tingkatapprove'] == 0 && ( $requestdata['status'] == 1 )) {
                                                                            $isreset = 1;
                                                                            $actioncount++;            
                                                                        }
                                                                    // =======================================
                                                                    // jika tingkat approve = 1 dan status = 1,8
                                                                        elseif (isset($requestdata['menupermission']) && $requestdata['menupermission']['tingkatapprove'] == 1 && ( $requestdata['status'] == 1 || $requestdata['status'] == 8 )) {
                                                                            $isreset = 1;
                                                                            $actioncount++;            
                                                                        }
                                                                    // =======================================
                                                                    // jika tingkat approve = 2 dan status = 1,8,9
                                                                        elseif (isset($requestdata['menupermission']) && $requestdata['menupermission']['tingkatapprove'] == 2 && ( $requestdata['status'] == 1 || $requestdata['status'] == 8 || $requestdata['status'] == 9 )) {
                                                                            $isreset = 1;
                                                                            $actioncount++;
                                                                        }
                                                                    // =========================================
                                                                    // jika tingkat approve = 3 dan status = 1,8,9,10
                                                                        elseif (isset($requestdata['menupermission']) && $requestdata['menupermission']['tingkatapprove'] == 3 && ( $requestdata['status'] == 1 || $requestdata['status'] == 8 || $requestdata['status'] == 9 || $requestdata['status'] == 10 )) {
                                                                            $isreset = 1;
                                                                            $actioncount++;
                                                                        }
                                                                    // =======================================
                                                                    // jika tingkat approve = 4 dan status = 1,8,9,10,11
                                                                        elseif (isset($requestdata['menupermission']) && $requestdata['menupermission']['tingkatapprove'] == 4 && ( $requestdata['status'] == 1 || $requestdata['status'] == 8 || $requestdata['status'] == 9 || $requestdata['status'] == 10 || $requestdata['status'] == 11 )) {
                                                                            $isreset = 1;
                                                                            $actioncount++;
                                                                        }
                                                                    // =======================================
                                                                    // jika tingkat approve = 5 dan status = 1,8,9,10,11,2
                                                                        elseif (isset($requestdata['menupermission']) && $requestdata['menupermission']['tingkatapprove'] == 5 && ( $requestdata['status'] == 1 || $requestdata['status'] == 8 || $requestdata['status'] == 9 || $requestdata['status'] == 10 || $requestdata['status'] == 11 || $requestdata['status'] == 2 )) {
                                                                            $isreset = 1;
                                                                            $actioncount++;
                                                                        }
                                                                    // =======================================
                                                                }
                                                            // ==========
                                                        // ==============
                                                        // cek rule reject
                                                            // jika tingkat approve >= max approve dan status 1,8,9,10,11,2
                                                                if (isset($requestdata['menupermission']) && $requestdata['menupermission']['tingkatapprove'] >= $requestdata['max_approve'] && ( $requestdata['status'] == 1 || $requestdata['status'] == 8 || $requestdata['status'] == 9 || $requestdata['status'] == 10 || $requestdata['status'] == 11 || $requestdata['status'] == 2 ) ) {
                                                                    $isreject = 1;
                                                                    $actioncount++;            
                                                                }
                                                            // ============================================================
                                                            // jika tidak
                                                                else{
                                                                    // jika tingkat approve = 0 dan status = 1
                                                                        if (isset($requestdata['menupermission']) && $requestdata['menupermission']['tingkatapprove'] == 0 && ( $requestdata['status'] == 1 )) {
                                                                            $isreject = 1;
                                                                            $actioncount++;            
                                                                        }
                                                                    // =======================================
                                                                    // jika tingkat approve = 1 dan status = 1,8
                                                                        elseif (isset($requestdata['menupermission']) && $requestdata['menupermission']['tingkatapprove'] == 1 && ( $requestdata['status'] == 1 || $requestdata['status'] == 8 )) {
                                                                            $isreject = 1;
                                                                            $actioncount++;            
                                                                        }
                                                                    // =======================================
                                                                    // jika tingkat approve = 2 dan status = 1,8,9
                                                                        elseif (isset($requestdata['menupermission']) && $requestdata['menupermission']['tingkatapprove'] == 2 && ( $requestdata['status'] == 1 || $requestdata['status'] == 8 || $requestdata['status'] == 9 )) {
                                                                            $isreject = 1;
                                                                            $actioncount++;
                                                                        }
                                                                    // =========================================
                                                                    // jika tingkat approve = 3 dan status = 1,8,9,10
                                                                        elseif (isset($requestdata['menupermission']) && $requestdata['menupermission']['tingkatapprove'] == 3 && ( $requestdata['status'] == 1 || $requestdata['status'] == 8 || $requestdata['status'] == 9 || $requestdata['status'] == 10 )) {
                                                                            $isreject = 1;
                                                                            $actioncount++;
                                                                        }
                                                                    // =======================================
                                                                    // jika tingkat approve = 4 dan status = 1,8,9,10,11
                                                                        elseif (isset($requestdata['menupermission']) && $requestdata['menupermission']['tingkatapprove'] == 4 && ( $requestdata['status'] == 1 || $requestdata['status'] == 8 || $requestdata['status'] == 9 || $requestdata['status'] == 10 || $requestdata['status'] == 11 )) {
                                                                            $isreject = 1;
                                                                            $actioncount++;
                                                                        }
                                                                    // =======================================
                                                                    // jika tingkat approve = 5 dan status = 1,8,9,10,11,2
                                                                        elseif (isset($requestdata['menupermission']) && $requestdata['menupermission']['tingkatapprove'] == 5 && ( $requestdata['status'] == 1 || $requestdata['status'] == 8 || $requestdata['status'] == 9 || $requestdata['status'] == 10 || $requestdata['status'] == 11 || $requestdata['status'] == 2 )) {
                                                                            $isreject = 1;
                                                                            $actioncount++;
                                                                        }
                                                                    // =======================================
                                                                }
                                                            // ==========
                                                        // ==============
                                                        // cek rule close
                                                            // jika status 3 dan is_close = 1
                                                                if (isset($requestdata['menupermission']) && $requestdata['menupermission']['is_close'] == 1 && $requestdata['status'] == 3 ) {
                                                                    $isclose = 1;
                                                                    $actioncount++;            
                                                                }
                                                            // ============================================================
                                                        // ==============
                                                    }
                                                // ===============
                                            }
                                        // ==================================
                                    // ==========
                                }
                            // ====================
                        }
                    // ========================
                    $result['data']['actioncount'] = $actioncount;
                    $result['data']['iscreate'] = $iscreate;
                    $result['data']['isupdate'] = $isupdate;
                    $result['data']['isreset'] = $isreset;
                    $result['data']['isreject'] = $isreject;
                    $result['data']['isclose'] = $isclose;
                    $result['data']['isapprove'] = $isapprove;
                // ==============
                // set end time
                    $endTime = microtime(true); 
                // =================
                // set return value lainnya
                    $result['is_valid'] = true;
                    $result['status'] = 200;
                    $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
                // =================
            } catch (\Throwable $th) {
                $result['message'] = $th->getmessage();
            }

            return $result;
        }
    // ================
}
