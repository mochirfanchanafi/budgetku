<?php

namespace App\Http\Controllers\Auth;

// libs
    use Illuminate\Http\Request;
    use Illuminate\Foundation\Auth\AuthenticatesUsers;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;
    use Redirect;
// ====
// controllers
    use App\Http\Controllers\Controller;
    use App\Http\Controllers\Api\Auth\AuthController as AuthApi;
// ===========
// model
    use App\Models\User;
// ===========

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuthApi $authapi)
    {
        $this->AuthApi = $authapi;
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function login(Request $request){
        $requestdata = $request->all();
        try {
            // action
                $authstatus = [];
                // ambil data
                    $authstatus = $this->AuthApi->login($requestdata);
                    if ($authstatus['is_valid'] == false) {
                        return Redirect::back()->withErrors(['error' => $authstatus['message']]);
                    }
                // ===============
                // set auth user
                    $user = new User($authstatus['data']);
                    $user->id = $authstatus['data']['id'];
                    $user->name = $authstatus['data']['name'];
                    $user->username = $authstatus['data']['username'];
                    $user->countrycode = $authstatus['data']['countrycode'];
                    $user->telp = $authstatus['data']['telp'];
                    $user->email = $authstatus['data']['email'];
                    $user->email_verified_at = $authstatus['data']['email_verified_at'];
                    $user->password = $authstatus['data']['password'];
                    $user->remember_token = $authstatus['data']['remember_token'];
                    $user->active = $authstatus['data']['active'];
                    $user->created_at = $authstatus['data']['created_at'];
                    $user->created_by = $authstatus['data']['created_by'];
                    $user->updated_at = $authstatus['data']['updated_at'];
                    $user->updated_by = $authstatus['data']['updated_by'];
                    $user->deleted_at = $authstatus['data']['deleted_at'];
                    $user->deleted_by = $authstatus['data']['deleted_by'];
                    Auth::login($user);
                // ===============
                // set token
                    Session::put('sanctum_token', $authstatus['token']);
                // ===============
            // ==============
        } catch (\Throwable $th) {
            return Redirect::back()->withErrors(['error' => 'Gagal Login '.$th->getmessage()]);
        }
        return Redirect::intended('/home');
    }
}
