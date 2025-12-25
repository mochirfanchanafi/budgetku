<?php

namespace App\Http\Controllers\Main;

// libs
    use Illuminate\Http\Request;
    use Carbon\Carbon;
// ====
// controller
    use App\Http\Controllers\Controller;
// ==========
// model

// =====
class SistemController extends Controller
{
    public function __construct(){
    }
    // template function
        public function templatelist($requestdata){
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
        public function template($requestdata){
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
    // action function
        public function utf8encodeonarray($arraydata){
            $attributes = array_keys($arraydata);
            foreach ($attributes as $keyattr => $attribute) {
                if (strtoupper(gettype($arraydata[$attribute])) != 'ARRAY') {
                    $arraydata[$attribute] = utf8_encode($arraydata[$attribute]);
                }
            }
            return $arraydata;
        }
    // ================
}
