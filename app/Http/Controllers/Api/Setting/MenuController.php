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
    use App\Models\System\Menu;
    use App\Models\System\MenuRole;
// =====

class MenuController extends Controller
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
                        $datalist = Menu::with([
                                        'Parent' => function($q){
                                            $q->wherenull('deleted_at')->selectraw('id, idmenu, kode, name');
                                        }
                                    ])
                                    ->wherenull('deleted_at');
                        // filters
                            // search
                                if (isset($requestdata['search']) && !empty($requestdata['search']['value']) && $requestdata['search']['value']!= '') {
                                    $datalist = $datalist->whereraw('( kode like "%'.$requestdata['search']['value'].'%" or name like "%'.$requestdata['search']['value'].'%" )')
                                                ->orWhereHas('Parent', function($q)use($requestdata){
                                                    $q->whereraw('(name like "%'.$requestdata['search']['value'].'%" )');
                                                });
                                }
                            // ======
                            // term
                                if (isset($requestdata['term'])) {
                                    $datalist = $datalist->whereraw('( kode like "%'.$requestdata['term'].'%" or name like "%'.$requestdata['term'].'%" )')
                                                ->orWhereHas('Parent', function($q)use($requestdata){
                                                    $q->whereraw('(name like "%'.$requestdata['term'].'%" )');
                                                });
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
                        $datalist = $datalist->selectraw('id, idmenu, kode, name')->get()->toarray();
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
                        $datalist = Menu::wherenull('deleted_at');
                        // filters
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
                        $datalist = $datalist->selectraw('id, idmenu, kode, name')->get()->toarray();
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
            public function usermenulist($requestdata){
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
                        if (!isset($requestdata['idrole'])) {
                            // set end time
                                $endTime = microtime(true);
                                $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
                            // =================
                            $result['message'] = 'Parameter idrole is required';
                            return $result;
                        }
                    // ==============
                    // action
                        $datalist = Menu::with([
                                        'Children' => function($q)use($requestdata){
                                            $q->wherenull('deleted_at')
                                            ->wherehas('MenuRole', function($qchild) use($requestdata){
                                                $qchild->wherenull('deleted_at')->where('idrole', $requestdata['idrole'])->where('is_read','1');
                                            })
                                            ->orderby('urutan','asc');
                                        },
                                        'Children.Children' => function($q)use($requestdata){
                                            $q->wherenull('deleted_at')
                                            ->wherehas('Menurole', function($qchild2)use($requestdata){
                                                $qchild2->wherenull('deleted_at')->where('idrole', $requestdata['idrole'])->where('is_read','1');
                                            })
                                            ->orderby('urutan','asc');
                                        }
                                    ])
                                    ->wherehas('MenuRole', function($q) use($requestdata){
                                        $q->wherenull('deleted_at')->where('idrole', $requestdata['idrole'])->where('is_read','1');
                                    })
                                    ->wherenull('deleted_at')
                                    ->wherenull('idmenu')
                                    ->orderby('urutan','asc')
                                    ->get()
                                    ->toarray();
                        if (!empty($datalist)) {
                            foreach ($datalist as $key => $lvl1) {
                                $datalist[$key] = $this->SistemApi->utf8encodeonarray($lvl1);
                                if (isset($lvl1['children']) && !empty($lvl1['children'])) {
                                    foreach ($lvl1['children'] as $keylvl2 => $lvl2) {
                                        $datalist[$key]['children'][$keylvl2] = $this->SistemApi->utf8encodeonarray($lvl2);
                                        if (isset($lvl2['children']) && !empty($lvl2['children'])) {
                                            foreach ($lvl2['children'] as $keylvl3 => $lvl3) {
                                                $datalist[$key]['children'][$keylvl2]['children'][$keylvl3] = $this->SistemApi->utf8encodeonarray($lvl3);
                                            }
                                        }
                                    }
                                }
                            }
                            $result['data'] = $datalist;
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
                        $data = Menu::with([
                                        'Parent' => function($q)use($requestdata){
                                            $q->wherenull('deleted_at');
                                        },
                                        'Parent.Parent' => function($q)use($requestdata){
                                            $q->wherenull('deleted_at');
                                        },
                                        'Children' => function($q)use($requestdata){
                                            $q->wherenull('deleted_at')->orderby('urutan','asc');
                                        },
                                        'Children.Children' => function($q)use($requestdata){
                                            $q->wherenull('deleted_at')->orderby('urutan','asc');
                                        },
                                        'MenuRole' => function($q)use($requestdata){
                                            $q->wherenull('deleted_at');
                                            if (isset($requestdata['idrole'])) {
                                                $q = $q->where('idrole', $requestdata['idrole'])->where('is_read','1');
                                            }
                                        },
                                        'MenuRole.Role' => function($q)use($requestdata){
                                            $q->wherenull('deleted_at');
                                        }
                                    ])
                                    ->wherenull('deleted_at');
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
                            if (isset($data['parent']) && !empty($data['parent'])) {
                                $data['parent'] = $this->SistemApi->utf8encodeonarray($data['parent']);
                                if (isset($data['parent']['parent']) && !empty($data['parent']['parent'])) {
                                    $data['parent']['parent'] = $this->SistemApi->utf8encodeonarray($data['parent']['parent']);
                                }
                            }
                            if (isset($data['children']) && !empty($data['children'])) {
                                foreach ($data['children'] as $key => $child) {
                                    $data['children'][$key] = $this->SistemApi->utf8encodeonarray($child);
                                    if (isset($child['children']) && !empty($child['children'])) {
                                        foreach ($child['children'] as $keydetail => $childdetail) {
                                            $data['children'][$key]['children'][$keydetail] = $this->SistemApi->utf8encodeonarray($childdetail);
                                        }
                                    }
                                }
                            }
                            if (isset($data['menu_role']) && !empty($data['menu_role'])) {
                                foreach ($data['menu_role'] as $key => $detail) {
                                    $data['menu_role'][$key] = $this->SistemApi->utf8encodeonarray($detail);
                                    if (isset($detail['role']) && !empty($detail['role'])) {
                                        $data['menu_role'][$key]['role'] = $this->SistemApi->utf8encodeonarray($detail['role']);
                                    }
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
        // =============
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
                        // simpan s_menu
                            $menu = [];
                            $menu['idmenu'] = isset($requestdata['idmenu']) ? $requestdata['idmenu'] : null;
                            $menu['kode'] = $requestdata['kode'];
                            $menu['name'] = $requestdata['name'];
                            $menu['icon'] = $requestdata['icon'];
                            $menu['urutan'] = $requestdata['urutan'];
                            $menu['viewparent'] = $requestdata['viewparent'];
                            $menu['mainjsroute'] = $requestdata['mainjsroute'];
                            $menu['mainjs'] = $requestdata['mainjs'];
                            $menu['mainapiroute'] = $requestdata['mainapiroute'];
                            $menu['mainapicontroller'] = $requestdata['mainapicontroller'];
                            $menu['tingkatapprove'] = $requestdata['tingkatapprove'];
                            $menu['jenis'] = $requestdata['jenis'];
                            $menu['created_at'] = $currentdatetime;
                            $menu['created_by'] = $requestdata['iduser'];

                            $menu['id'] = Menu::create($menu)->id;
                        // =============
                        // simpan s_menu_role
                            // hapus data lama
                                if (isset($requestdata['iddetail'])) {
                                    MenuRole::wherenull('deleted_at')
                                    ->where('idmenu', $menu['id'])
                                    ->wherenotin('id', $requestdata['iddetail'])
                                    ->update([
                                        'deleted_at' => $currentdatetime,
                                        'deleted_by' => $requestdata['iduser'],
                                    ]);
                                }else{
                                    MenuRole::wherenull('deleted_at')
                                    ->where('idmenu', $menu['id'])
                                    ->update([
                                        'deleted_at' => $currentdatetime,
                                        'deleted_by' => $requestdata['iduser'],
                                    ]);
                                }
                            // ===============
                            $menurolelist = [];
                            if (isset($requestdata['iddetail'])) {
                                foreach ($requestdata['iddetail'] as $key => $detail) {
                                    if ($detail != '0') {
                                        MenuRole::wherenull('deleted_at')
                                        ->where('id', $detail)
                                        ->update([
                                            'updated_at' => $currentdatetime,
                                            'updated_by' => $requestdata['iduser'],
                                            'is_create' => $requestdata['is_create'],
                                            'is_read' => $requestdata['is_read'][$key],
                                            'is_update' => $requestdata['is_update'][$key],
                                            'is_delete' => $requestdata['is_delete'][$key],
                                            'is_reset' => $requestdata['is_reset'][$key],
                                            'is_reject' => $requestdata['is_reject'][$key],
                                            'is_close' => $requestdata['is_close'][$key],
                                            'is_admin' => $requestdata['is_admin'][$key],
                                            'tingkatapprove' => $requestdata['tingkat_approve_role'][$key],
                                        ]);
                                    }else{
                                        $menurole = [];
                                        $menurole['idmenu'] = $menu['id'];
                                        $menurole['idrole'] = $requestdata['idrole'][$key];
                                        $menurole['is_create'] = $requestdata['is_create'][$key];
                                        $menurole['is_read'] = $requestdata['is_read'][$key];
                                        $menurole['is_update'] = $requestdata['is_update'][$key];
                                        $menurole['is_delete'] = $requestdata['is_delete'][$key];
                                        $menurole['is_reset'] = $requestdata['is_reset'][$key];
                                        $menurole['is_reject'] = $requestdata['is_reject'][$key];
                                        $menurole['is_close'] = $requestdata['is_close'][$key];
                                        $menurole['is_admin'] = $requestdata['is_admin'][$key];
                                        $menurole['tingkatapprove'] = $requestdata['tingkat_approve_role'][$key];
                                        $menurole['created_at'] = $currentdatetime;
                                        $menurole['created_by'] = $requestdata['idrole'][$key];

                                        $menurolelist[] = $menurole;
                                    }
                                }
                            }
                            if (!empty($menurolelist)) {
                                MenuRole::insert($menurolelist);
                            }
                        // ==================
                        // simpan log
                            $log = [];
                            $log['menu'] = $requestdata['menu'];
                            $log['idtransaksi'] = $menu['id'];
                            $log['notransaksi'] = $menu['name'];
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
                        // simpan s_menu
                            $menu = [];
                            $menu['idmenu'] = isset($requestdata['idmenu']) ? $requestdata['idmenu'] : null;
                            $menu['kode'] = $requestdata['kode'];
                            $menu['name'] = $requestdata['name'];
                            $menu['icon'] = $requestdata['icon'];
                            $menu['urutan'] = $requestdata['urutan'];
                            $menu['viewparent'] = $requestdata['viewparent'];
                            $menu['mainjsroute'] = $requestdata['mainjsroute'];
                            $menu['mainjs'] = $requestdata['mainjs'];
                            $menu['mainapiroute'] = $requestdata['mainapiroute'];
                            $menu['mainapicontroller'] = $requestdata['mainapicontroller'];
                            $menu['tingkatapprove'] = $requestdata['tingkatapprove'];
                            $menu['jenis'] = $requestdata['jenis'];
                            $menu['updated_at'] = $currentdatetime;
                            $menu['updated_by'] = $requestdata['iduser'];
                            Menu::where('id', $requestdata['id'])->update($menu);

                            $menu['id'] = $requestdata['id'];
                        // =============
                        // simpan s_menu_role
                            // hapus data lama
                                if (isset($requestdata['iddetail'])) {
                                    MenuRole::wherenull('deleted_at')
                                    ->where('idmenu', $menu['id'])
                                    ->wherenotin('id', $requestdata['iddetail'])
                                    ->update([
                                        'deleted_at' => $currentdatetime,
                                        'deleted_by' => $requestdata['iduser'],
                                    ]);
                                }else{
                                    MenuRole::wherenull('deleted_at')
                                    ->where('idmenu', $menu['id'])
                                    ->update([
                                        'deleted_at' => $currentdatetime,
                                        'deleted_by' => $requestdata['iduser'],
                                    ]);
                                }
                            // ===============
                            $menurolelist = [];
                            if (isset($requestdata['iddetail'])) {
                                foreach ($requestdata['iddetail'] as $key => $detail) {
                                    if ($detail != '0') {
                                        MenuRole::wherenull('deleted_at')
                                        ->where('id', $detail)
                                        ->update([
                                            'updated_at' => $currentdatetime,
                                            'updated_by' => $requestdata['iduser'],
                                            'is_create' => $requestdata['is_create'][$key],
                                            'is_read' => $requestdata['is_read'][$key],
                                            'is_update' => $requestdata['is_update'][$key],
                                            'is_delete' => $requestdata['is_delete'][$key],
                                            'is_reset' => $requestdata['is_reset'][$key],
                                            'is_reject' => $requestdata['is_reject'][$key],
                                            'is_close' => $requestdata['is_close'][$key],
                                            'is_admin' => $requestdata['is_admin'][$key],
                                            'tingkatapprove' => $requestdata['tingkat_approve_role'][$key],
                                        ]);
                                    }else{
                                        $menurole = [];
                                        $menurole['idmenu'] = $menu['id'];
                                        $menurole['idrole'] = $requestdata['idrole'][$key];
                                        $menurole['is_create'] = $requestdata['is_create'][$key];
                                        $menurole['is_read'] = $requestdata['is_read'][$key];
                                        $menurole['is_update'] = $requestdata['is_update'][$key];
                                        $menurole['is_delete'] = $requestdata['is_delete'][$key];
                                        $menurole['is_reset'] = $requestdata['is_reset'][$key];
                                        $menurole['is_reject'] = $requestdata['is_reject'][$key];
                                        $menurole['is_close'] = $requestdata['is_close'][$key];
                                        $menurole['is_admin'] = $requestdata['is_admin'][$key];
                                        $menurole['tingkatapprove'] = $requestdata['tingkat_approve_role'][$key];
                                        $menurole['created_at'] = $currentdatetime;
                                        $menurole['created_by'] = $requestdata['idrole'][$key];

                                        $menurolelist[] = $menurole;
                                    }
                                }
                            }
                            if (!empty($menurolelist)) {
                                MenuRole::insert($menurolelist);
                            }
                        // ==================
                        // simpan log
                            $log = [];
                            $log['menu'] = $requestdata['menu'];
                            $log['idtransaksi'] = $menu['id'];
                            $log['notransaksi'] = $menu['name'];
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
                            $menu = [];
                            $menu['deleted_at'] = $currentdatetime;
                            $menu['deleted_by'] = $requestdata['iduser'];
                            Menu::where('id', $requestdata['id'])->update($menu);

                            $menu['id'] = $requestdata['id'];
                        // =============
                        // simpan s_menu_role
                            MenuRole::wherenull('deleted_at')
                                    ->where('idmenu', $menu['id'])
                                    ->update([
                                        'deleted_at' => $currentdatetime,
                                        'deleted_by' => $requestdata['iduser'],
                                    ]);
                        // ==================
                        // simpan log
                            $log = [];
                            $log['menu'] = $requestdata['menu'];
                            $log['idtransaksi'] = $menu['id'];
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
                            'menu' => 'required',
                            'kode' => 'required|unique:s_menu,kode,'.$requestdata['id'].',id,deleted_at,NULL',
                            'name' => 'required|unique:s_menu,name,'.$requestdata['id'].',id,deleted_at,NULL',
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
    // =======================
}
