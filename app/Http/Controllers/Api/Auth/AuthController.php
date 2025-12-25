<?php

namespace App\Http\Controllers\Api\Auth;


// libs
    use Illuminate\Http\Request;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Session;

    use Validator;
// ====
// controllers
    use App\Http\Controllers\Controller;
    use App\Http\Controllers\Api\BaseController as BaseController;

    use App\Http\Controllers\Main\SistemController as SistemApi;
// ==========
// models
    use App\Models\User;
// ==========

class AuthController extends BaseController
{
    public function __construct(SistemApi $sistemapi){
        $this->SistemApi = $sistemapi;
    }
    // fungsi mengambil data
        // json response
            public function loginapi(Request $request): JsonResponse{
                $requestdata = $request->all();
                $result = $this->login($requestdata);
                if ($result['is_valid'] == false) {
                    return $this->sendError($result);
                }
                return $this->sendResponse($result);
            }
            public function getuserdetailapi(Request $request): JsonResponse{
                $requestdata = $request->all();
                $requestdata['id'] = !empty($request->user()) ? $request->user()->id : ( isset($requestdata['id']) ? $requestdata['id'] : null );
                $result = $this->getuserdetail($requestdata);
                if ($result['is_valid'] == false) {
                    return $this->sendError($result);
                }
                return $this->sendResponse($result);
            }
        // =============
        // array response
            public function login($requestdata){
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
                    // form validate
                        $validatestatus = $this->loginformvalidate($requestdata);
                        if (!$validatestatus['is_valid']) {
                            // set end time
                                $endTime = microtime(true);
                                $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
                            // =================
                            $result['message'] = $validatestatus['message'];
                            return $result;
                        }
                    // ==============
                    // action
                        $user = User::wherenull('deleted_at')->where('active','1')->whereraw('( username = "'.$requestdata['username'].'" or email="'.$requestdata['username'].'" )' )->first();
                        if (empty($user)) {
                            // set end time
                                $endTime = microtime(true);
                                $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
                            // =================
                            $result['message'] = 'User Not Found';
                            return $result;
                        }
                        // cek password user
                            if(!Hash::check($requestdata['password'], $user->password)){
                                // set end time
                                    $endTime = microtime(true);
                                    $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
                                // =================
                                $result['message'] = 'Wrong Password';
                                return $result;
                            }
                        // ===============
                    // ==============
                    $user = $user->makeVisible(['id','password','remember_token']);
                    $result['token'] =  $user->createToken('BASIC_SYSTEM')->plainTextToken; 
                    $result['data'] =  $user->toarray();
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
            public function getuserdetail($requestdata){
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
                        $data = user::with([
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
        // form validation
            public function loginformvalidate($requestdata){
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
                            'username' => 'required',
                            'password' => 'required',
                        ]);
                        if($validator->fails()) {
                            // set end time
                                $endTime = microtime(true);
                                $result['execution_time'] = number_format(($endTime - $startTime),2,'.',',') .' Seconds';
                            // ============
                            $failedRules = $validator->failed();
                            // set message
                                if(isset($failedRules['username']['Required'])) {
                                    $result['message'] = 'Parameter Username is required';
                                    return $result;
                                }
                                if(isset($failedRules['password']['Required'])) {
                                    $result['message'] = 'Parameter Password is required';
                                    return $result;
                                }
                            // ===========
                        }
                    // ==============
                    // set end time
                        $endTime = microtime(true); 
                    // ============
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
}
