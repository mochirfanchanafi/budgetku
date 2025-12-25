<?php

namespace App\Http\Controllers;

// libs
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Http\Request;
// ====
// controller
    use App\Http\Controllers\Main\SistemController as SistemApi;
    use App\Http\Controllers\Main\MainController as MainApi;
// ==========

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SistemApi $sistemapi, MainApi $mainapi)
    {
        $this->middleware('auth');
        $this->SistemApi = $sistemapi;
        $this->MainApi = $mainapi;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        $menuparam = [];
        // mengambil user yg sedang login
            $userauth = Auth::user()->toarray();
        // ==============================
        // mengambil param menu
            $dataparam = [];
            $dataparam['iduser'] = $userauth['id'];
            $menuparam = $this->MainApi->getmenuparam($dataparam);
            if (!empty($menuparam['data'])) {
                $menuparam = $menuparam['data'];
            }
        // =========================
        return view('layouts.main',compact('menuparam'));
    }
}
