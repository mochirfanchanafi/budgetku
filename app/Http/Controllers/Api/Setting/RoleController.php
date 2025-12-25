<?php

namespace App\Http\Controllers\Api\Setting;

// libs
    use Illuminate\Http\Request;
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
    use App\Models\System\Role;
// =====

class RoleController extends Controller
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
            public function selectlistapi(Request $request){
                $requestdata = $request->all();
                $requestdata['iduser'] = !empty($request->user()) ? $request->user()->id : ( isset($requestdata['iduser']) ? $requestdata['iduser'] : null );
                $result = $this->selectlist($requestdata);
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
                        $datalist = Role::wherenull('deleted_at');
                        // filters
                            // search
                                if (isset($requestdata['search']) && !empty($requestdata['search']['value']) && $requestdata['search']['value']!= '') {
                                    $datalist = $datalist->whereraw('( kode like "%'.$requestdata['search']['value'].'%" or name like "%'.$requestdata['search']['value'].'%" )');
                                }
                            // ======
                            // term
                                if (isset($requestdata['term'])) {
                                    $datalist = $datalist->whereraw('( kode like "%'.$requestdata['term'].'%" or name like "%'.$requestdata['term'].'%" )');
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
                        $datalist = $datalist->selectraw('id, kode, name')->get()->toarray();
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
            public function selectlist($requestdata){
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
                        $datalist = Role::wherenull('deleted_at');
                        // filters
                            // search
                                if (isset($requestdata['search']) && !empty($requestdata['search']['value']) && $requestdata['search']['value']!= '') {
                                    $datalist = $datalist->whereraw('( kode like "%'.$requestdata['search']['value'].'%" or name like "%'.$requestdata['search']['value'].'%" )');
                                }
                            // ======
                            // term
                                if (isset($requestdata['term'])) {
                                    $datalist = $datalist->whereraw('( kode like "%'.$requestdata['term'].'%" or name like "%'.$requestdata['term'].'%" )');
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
                        $datalist = $datalist->selectraw('id, kode, name')->get()->toarray();
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
                        if (!isset($requestdata['id']) && !isset($requestdata['kode'])) {
                            // set end time
                                $endTime = microtime(true);
                                $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
                            // =================
                            $result['message'] = 'Parameter id or kode is required';
                            return $result;
                        }
                    // ==============
                    // action
                        $data = Role::wherenull('deleted_at');
                        // filters
                            if (isset($requestdata['id'])) {
                                $data = $data->where('id', $requestdata['id']);
                            }
                            if (isset($requestdata['kode'])) {
                                $data = $data->where('kode', $requestdata['kode']);
                            }
                        // =======
                        $data = $data->first();
                        if (!empty($data)) {
                            $data = $data->toarray();
                            $data = $this->SistemApi->utf8encodeonarray($data);
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
    // =======================
    // fungsi simpan
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
                        // simpan s_role
                            $role = [];
                            $role['kode'] = $requestdata['kode'];
                            $role['name'] = $requestdata['name'];
                            $role['created_at'] = $currentdatetime;
                            $role['created_by'] = $requestdata['iduser'];

                            $role['id'] = Role::create($role)->id;
                        // =============
                        // simpan log
                            $log = [];
                            $log['menu'] = $requestdata['menu'];
                            $log['idtransaksi'] = $role['id'];
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
                        // simpan s_role
                            $role = [];
                            $role['kode'] = $requestdata['kode'];
                            $role['name'] = $requestdata['name'];
                            $role['updated_at'] = $currentdatetime;
                            $role['updated_by'] = $requestdata['iduser'];

                            Role::where('id', $requestdata['id'])->update($role);
                            $role['id'] = $requestdata['id'];
                        // =============
                        // simpan log
                            $log = [];
                            $log['menu'] = $requestdata['menu'];
                            $log['idtransaksi'] = $role['id'];
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
                            $role = [];
                            $role['deleted_at'] = $currentdatetime;
                            $role['deleted_by'] = $requestdata['iduser'];
                            Role::where('id', $requestdata['id'])->update($role);

                            $role['id'] = $requestdata['id'];
                        // =============
                        // simpan log
                            $log = [];
                            $log['menu'] = $requestdata['menu'];
                            $log['idtransaksi'] = $role['id'];
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
                            'kode' => 'required|unique:s_role,kode,'.$requestdata['id'].',id,deleted_at,NULL',
                            'name' => 'required|unique:s_role,name,'.$requestdata['id'].',id,deleted_at,NULL',
                        ]);
                        if($validator->fails()) {
                            $failedRules = $validator->failed();
                            // ==================
                            // pesan untuk kode
                                if(isset($failedRules['kode']['Unique'])) {
                                    $result['message'] = 'Kode Telah Digunakan Sebelumnya';
                                
                                }if(isset($failedRules['kode']['Required'])) {
                                    $result['message'] = 'Tambahkan Kode Terlebih Dahulu';
                                    return $result;
                                }
                            // ==================
                            // pesan untuk name
                                if(isset($failedRules['name']['Unique'])) {
                                    $result['message'] = 'Nama Telah Digunakan Sebelumnya';
                                
                                }if(isset($failedRules['name']['Required'])) {
                                    $result['message'] = 'Tambahkan Nama Terlebih Dahulu';
                                    return $result;
                                }
                            // ==================
                            // pesan untuk menu
                                if(isset($failedRules['menu']['Required'])) {
                                    $result['message'] = 'Tambahkan Menu Terlebih Dahulu';
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
    // =============
}
