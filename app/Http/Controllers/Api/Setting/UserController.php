<?php

namespace App\Http\Controllers\Api\Setting;

// libs
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Route;
    use Redirect;
    use DB;
    use Validator;
    use Carbon\Carbon;
// ====
// controller
    use App\Http\Controllers\Controller;
    use App\Http\Controllers\Main\SistemController as SistemApi;
    use App\Http\Controllers\Api\Setting\LogController as LogApi;
// ==========
// model
    use App\Models\User;
    use App\Models\System\UserRole;
// =====

class UserController extends Controller
{
    public function __construct(SistemApi $sistemapi, LogApi $logapi){
        $this->SistemApi = $sistemapi;
        $this->LogApi = $logapi;
    }
    // function mengambil data
        // json response
            public function listapi(Request $request){
                $requestdata = $request->all();
                $requestdata['iduser'] = !empty($request->user()) ? $request->user()->id : ( isset($requestdata['iduser']) ? $requestdata['iduser'] : null );
                $result = $this->list($requestdata);
                return response()->json($result);
            }
            public function getdatadetailapi(Request $request){
                $requestdata = $request->all();
                $requestdata['iduser'] = !empty($request->user()) ? $request->user()->id : ( isset($requestdata['iduser']) ? $requestdata['iduser'] : null );
                $result = $this->getdatadetail($requestdata);
                return response()->json($result);
            }
        // =============
        // array response
            public function list($requestdata){
                // initiate return value
                    $result = [];
                    $result['is_valid'] = false;
                    $result['status'] = 500;
                    $result['message'] = '';
                    $result['recordsTotal'] = 0;
                    $result['recordsFiltered'] = 0;
                    $result['execution_time'] = 0;
                    $result['data'] = [];
                // =====================

                $start = isset($requestdata['start']) ? $requestdata['start'] : 0;
                $length = isset($requestdata['length']) ? $requestdata['length'] : 0;

                $pageNumber = $start != 0 && $length != 0 ? ( $start / $length )+1 : 1;
                $pageLength = $length;
                $skip       = ($pageNumber-1) * $pageLength;
                
                try {
                    // set start time
                        $startTime = microtime(true);
                    // ==============
                    // ambil data
                        $datalist = User::wherenull('deleted_at');
                        // filters
                            // search
                                if (isset($requestdata['search']) && !empty($requestdata['search']['value']) && $requestdata['search']['value']!= '') {
                                    $datalist = $datalist->whereraw('( name like "%'.$requestdata['search']['value'].'%" or username like "%'.$requestdata['search']['value'].'%" or email like "%'.$requestdata['search']['value'].'%" or countrycode like "%'.$requestdata['search']['value'].'%" or telp like "%'.$requestdata['search']['value'].'%" )');
                                }
                            // ======
                            // term
                                if (isset($requestdata['term'])) {
                                    $datalist = $datalist->whereraw('( name like "%'.$requestdata['term'].'%" or username like "%'.$requestdata['term'].'%" or email like "%'.$requestdata['term'].'%" or countrycode like "%'.$requestdata['term'].'%" or telp like "%'.$requestdata['term'].'%" )');
                                }
                            // ======
                            // order
                                if (isset($requestdata['order']) && isset($requestdata['order'][0]['column']) && isset($requestdata['order'][0]['dir'])) {
                                    $datalist = $datalist->orderby($requestdata['order'][0]['column'],$requestdata['order'][0]['dir']);
                                }
                            // ==============
                        // =======
                        $result['recordsFiltered'] = $result['recordsTotal'] = $datalist->count();
                        if ($length > 0) {
                            $datalist = $datalist->skip($skip)->take($pageLength);
                        }
                        $datalist = $datalist->selectraw('id, name, username, email, countrycode, telp')->get()->toarray();
                        if (!empty($datalist)) {
                            foreach ($datalist as $key => $data) {
                                $datalist[$key] = $this->SistemApi->utf8encodeonarray($data);
                                if (!empty($data['parent'])) {
                                    $datalist[$key]['parent'] = $this->SistemApi->utf8encodeonarray($data['parent']);
                                }
                            }
                            $result['data'] = $datalist;
                        } 
                    // ==============
                    // set end time
                        $endTime = microtime(true); 
                    // =================
                    // set return value lainnya
                        $result['draw'] = isset($requestdata['draw']) ? $requestdata['draw'] : '';
                        $result['is_valid'] = true;
                        $result['status'] = 200;
                        $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
                    // =================
                } catch (\Throwable $th) {
                    $result['message'] = $th->getmessage();
                }

                return $result;
            }
            public function getdatadetail($requestdata){
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
                    // cek param
                        if (!isset($requestdata['id'])) {
                            // set end time
                                $endTime = microtime(true);
                                $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
                            // =================
                            $result['message'] = 'Parameter id is required';
                            return $result;
                        }
                    // =========
                    // action
                        $data = User::with([
                                    'UserRole' => function($q){
                                        $q->wherenull('deleted_at');
                                    },
                                    'UserRole.Role' => function($q){
                                        $q->wherenull('deleted_at');
                                    }
                                ])
                                ->wherenull('deleted_at')
                                ->where('id', $requestdata['id'])
                                ->first();
                        if (!empty($data)) {
                            $data = $data->toarray();
                            $data = $this->SistemApi->utf8encodeonarray($data);
                            if (isset($data['user_role']) && !empty($data['user_role'])) {
                                $data['user_role'] = $this->SistemApi->utf8encodeonarray($data['user_role']);
                                if (isset($data['user_role']['role']) && !empty($data['user_role']['role'])) {
                                    $data['user_role']['role'] = $this->SistemApi->utf8encodeonarray($data['user_role']['role']);
                                }
                            }
                            $result['data'] = $data;
                        }
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
        // ==============
    // =====================
    // function simpan data
        // json response
            public function storeapi(Request $request){
                $requestdata = $request->all();
                $requestdata['iduser'] = !empty($request->user()) ? $request->user()->id : ( isset($requestdata['iduser']) ? $requestdata['iduser'] : null );
                $result = $this->store($requestdata);
                return response()->json($result);
            }
            public function updateapi(Request $request){
                $requestdata = $request->all();
                $requestdata['iduser'] = !empty($request->user()) ? $request->user()->id : ( isset($requestdata['iduser']) ? $requestdata['iduser'] : null );
                $result = $this->update($requestdata);
                return response()->json($result);
            }
            public function destroyapi(Request $request){
                $requestdata = $request->all();
                $requestdata['iduser'] = !empty($request->user()) ? $request->user()->id : ( isset($requestdata['iduser']) ? $requestdata['iduser'] : null );
                $result = $this->destroy($requestdata);
                return response()->json($result);
            }
        // =============
        // array response
            public function store($requestdata){
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
                    // validate request
                        $validatestatus = $this->validateform($requestdata);
                        if (!$validatestatus['is_valid']) {
                            DB::connection('mysql')->rollback();
                            // set end time
                                $endTime = microtime(true);
                                $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
                            // =================
                            $result['message'] = $validatestatus['message'];
                            return $result;
                        }
                    // ==============
                    // action
                        // simpan s_user
                            $user = [];
                            $user['name'] = $requestdata['name'];
                            $user['username'] = $requestdata['username'];
                            $user['email'] = $requestdata['email'];
                            $user['countrycode'] = $requestdata['countrycode'];
                            $user['telp'] = $requestdata['telp'];
                            $user['password'] = Hash::make($requestdata['password']);
                            $user['active'] = $requestdata['active'];
                            $user['created_at'] = $currentdatetime;
                            $user['created_by'] = $requestdata['iduser'];

                            $user['id'] = User::create($user)->id;
                        // =============
                        // simpan s_user_role
                            $userrole = [];
                            $userrole['iduser'] = $user['id'];
                            $userrole['idrole'] = $requestdata['idrole'];
                            $userrole['created_at'] = $currentdatetime;
                            $userrole['created_by'] = $requestdata['iduser'];

                            $userrole['id'] = UserRole::create($userrole)->id;
                        // =============
                        // simpan log
                            $log = [];
                            $log['menu'] = $requestdata['menu'];
                            $log['idtransaksi'] = $user['id'];
                            $log['notransaksi'] = null;
                            $log['action'] = 'ADD';
                            $log['keterangan'] = null;
                            $log['created_at'] = $currentdatetime;
                            $log['created_by'] = $requestdata['iduser'];

                            $storelogstatus = $this->LogApi->store($log);
                        // ==================
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
                    $result['message'] = $th->getmessage();
                }

                return $result;
            }
            public function update($requestdata){
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
                    // validate request
                        $validatestatus = $this->validateform($requestdata);
                        if (!$validatestatus['is_valid']) {
                            DB::connection('mysql')->rollback();
                            // set end time
                                $endTime = microtime(true);
                                $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
                            // =================
                            $result['message'] = $validatestatus['message'];
                            return $result;
                        }
                    // ==============
                    // ambil data existing
                        $dataparam = [];
                        $dataparam['id'] = $requestdata['id'];
                        $existingdata = $this->getdatadetail($dataparam);
                        if (empty($existingdata['data'])) {
                            DB::connection('mysql')->rollback();
                            // set end time
                                $endTime = microtime(true);
                                $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
                            // =================
                            $result['message'] = 'Data tidak ditemukan'.$existingdata['message'];
                            return $result;
                        }
                    // ===================
                    // action
                        // simpan s_user
                            $user = [];
                            $user['name'] = $requestdata['name'];
                            $user['username'] = $requestdata['username'];
                            $user['email'] = $requestdata['email'];
                            $user['countrycode'] = $requestdata['countrycode'];
                            $user['telp'] = $requestdata['telp'];
                            if (isset($requestdata['password'])) {
                                $user['password'] = Hash::make($requestdata['password']);
                            }
                            $user['active'] = $requestdata['active'];
                            $user['updated_at'] = $currentdatetime;
                            $user['updated_by'] = $requestdata['iduser'];

                            User::where('id', $requestdata['id'])->update($user);
                            $user['id'] = $requestdata['id'];
                        // =============
                        // simpan s_user_role
                            $userrole = [];
                            $userrole['iduser'] = $user['id'];
                            $userrole['idrole'] = $requestdata['idrole'];
                            $userrole['updated_at'] = $currentdatetime;
                            $userrole['updated_by'] = $requestdata['iduser'];

                            $userrole['id'] = UserRole::where('iduser', $requestdata['id'])->update($userrole);
                        // =============
                        // simpan log
                            $log = [];
                            $log['menu'] = $requestdata['menu'];
                            $log['idtransaksi'] = $user['id'];
                            $log['notransaksi'] = null;
                            $log['action'] = 'UPDATE';
                            $log['keterangan'] = null;
                            $log['created_at'] = $currentdatetime;
                            $log['created_by'] = $requestdata['iduser'];

                            $storelogstatus = $this->LogApi->store($log);
                        // ==================
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
                    $result['message'] = $th->getmessage();
                }

                return $result;
            }
            public function destroy($requestdata){
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
                    // ambil data existing
                        $dataparam = [];
                        $dataparam['id'] = $requestdata['id'];
                        $existingdata = $this->getdatadetail($dataparam);
                        if (empty($existingdata['data'])) {
                            DB::connection('mysql')->rollback();
                            // set end time
                                $endTime = microtime(true);
                                $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
                            // =================
                            $result['message'] = 'Data tidak ditemukan'.$existingdata['message'];
                            return $result;

                        }
                    // ===================
                    // action
                        // simpan s_menu
                            $user = [];
                            $user['deleted_at'] = $currentdatetime;
                            $user['deleted_by'] = $requestdata['iduser'];
                            User::where('id', $requestdata['id'])->update($user);

                            $user['id'] = $requestdata['id'];
                        // =============
                        // simpan s_menu_role
                            UserRole::wherenull('deleted_at')
                                    ->where('iduser', $user['id'])
                                    ->update([
                                        'deleted_at' => $currentdatetime,
                                        'deleted_by' => $requestdata['iduser'],
                                    ]);
                        // ==================
                        // simpan log
                            $log = [];
                            $log['menu'] = $requestdata['menu'];
                            $log['idtransaksi'] = $user['id'];
                            $log['notransaksi'] = null;
                            $log['action'] = 'DELETE';
                            $log['keterangan'] = null;
                            $log['created_at'] = $currentdatetime;
                            $log['created_by'] = $requestdata['iduser'];

                            $storelogstatus = $this->LogApi->store($log);
                        // ==================
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
                    $result['message'] = $th->getmessage();
                }

                return $result;
            }
            public function validateform($requestdata){
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
                        $validator = Validator::make($requestdata, [
                            'name' => 'required|unique:s_user,name,'.$requestdata['id'].',id,deleted_at,NULL',
                            'username' => 'required|unique:s_user,username,'.$requestdata['id'].',id,deleted_at,NULL',
                            'email' => 'required|email|unique:s_user,email,'.$requestdata['id'].',id,deleted_at,NULL',
                        ]);
                        if($validator->fails()) {
                            $failedRules = $validator->failed();
                            // ==================
                            // pesan untuk name
                                if(isset($failedRules['name']['Unique'])) {
                                    $result['message'] = 'Nama Telah Digunakan Sebelumnya';
                                
                                }if(isset($failedRules['name']['Required'])) {
                                    $result['message'] = 'Tambahkan Nama Terlebih Dahulu';
                                    return $result;
                                }
                            // ==================
                            // pesan untuk username
                                if(isset($failedRules['username']['Unique'])) {
                                    $result['message'] = 'Username Telah Digunakan Sebelumnya';
                                
                                }if(isset($failedRules['username']['Required'])) {
                                    $result['message'] = 'Tambahkan Username Terlebih Dahulu';
                                    return $result;
                                }
                            // ==================
                            // pesan untuk email
                                if(isset($failedRules['email']['Unique'])) {
                                    $result['message'] = 'Email Telah Digunakan Sebelumnya';
                                
                                }if(isset($failedRules['email']['Required'])) {
                                    $result['message'] = 'Tambahkan Email Terlebih Dahulu';
                                    return $result;
                                }if(isset($failedRules['email']['Email'])) {
                                    $result['message'] = 'Format Email Salah';
                                    return $result;
                                }
                            // ==================
                            return $result;
                        }
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
        // ==============
    // ====================
}
