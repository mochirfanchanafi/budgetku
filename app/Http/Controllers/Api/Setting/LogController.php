<?php

namespace App\Http\Controllers\Api\Setting;
// libs
    use Illuminate\Http\Request;
    use Route;
    use Redirect;
    use DB;
    use Validator;
// ====
// controller
    use App\Http\Controllers\Controller;
    use App\Http\Controllers\Main\SistemController as SistemApi;
// ==========
// model
    use App\Models\System\Log;
// =====

class LogController extends Controller
{
    public function __construct(SistemApi $sistemapi){
        $this->SistemApi = $sistemapi;
    }

    // function simpan data
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
                
                try {
                    // set start time
                        $startTime = microtime(true);
                    // ==============
                    // action
                        $log = Log::create($requestdata);
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
    // fungsi mengambil data
        // json response
            public function listapi(Request $request){
                $requestdata = $request->all();
                $requestdata['iduser'] = !empty($request->user()) ? $request->user()->id : ( isset($requestdata['iduser']) ? $requestdata['iduser'] : null );
                $result = $this->list($requestdata);
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
                    // action
                        $datalist = Log::with([
                                        'User' => function($q){
                                            $q->selectraw('id, username, name');
                                        }
                                    ])
                                    ->wherenull('deleted_at');
                        // filters
                            // search
                                if (isset($requestdata['search']) && !empty($requestdata['search']['value']) && $requestdata['search']['value']!= '') {
                                    $datalist = $datalist->whereraw('( menu like "%'.$requestdata['search']['value'].'%" or notransaksi like "%'.$requestdata['search']['value'].'%" or action like "%'.$requestdata['search']['value'].'%" or keterangan like "%'.$requestdata['search']['value'].'%" )');
                                }
                            // ======
                            // term
                                if (isset($requestdata['term'])) {
                                    $datalist = $datalist->whereraw('( menu like "%'.$requestdata['term'].'%" or notransaksi like "%'.$requestdata['term'].'%" or action like "%'.$requestdata['term'].'%" or keterangan like "%'.$requestdata['term'].'%" )');
                                }
                            // ======
                            // tgl_awal
                                if (isset($requestdata['tgl_awal'])) {
                                    $datalist = $datalist->where('created_at','>=',$requestdata['tgl_awal']);
                                }
                            // ======
                            // tgl_akhir
                                if (isset($requestdata['tgl_akhir'])) {
                                    $datalist = $datalist->where('created_at','<=',$requestdata['tgl_akhir']);
                                }
                            // ======
                            // idmenu
                                if (isset($requestdata['menu'])) {
                                    $datalist = $datalist->where('menu',$requestdata['menu']);
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
                        $datalist = $datalist->selectraw('id, menu, notransaksi, action, keterangan, DATE_FORMAT(created_at, "%d %M %Y %H:%i") as action_time, created_by')->get()->toarray();
                        if (!empty($datalist)) {
                            foreach ($datalist as $key => $data) {
                                $datalist[$key] = $this->SistemApi->utf8encodeonarray($data);
                                if (!empty($data['user'])) {
                                    $datalist[$key]['user'] = $this->SistemApi->utf8encodeonarray($data['user']);
                                }
                            }
                            $result['data'] = $datalist;
                        }
                    // ======
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
        // ==============
    // =====================
}
