<?php

namespace App\Http\Controllers\Main;

// libs
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Facades\View;
// ====
// controller
    use App\Http\Controllers\Controller;
    use App\Http\Controllers\Main\SistemController as SistemApi;
    use App\Http\Controllers\Main\MainController as MainApi;
// ==========
// model

// =====

class PageController extends Controller
{
    public function __construct(SistemApi $sistemapi, MainApi $mainapi){
        $this->middleware('auth');
        $this->SistemApi = $sistemapi;
        $this->MainApi = $mainapi;
    }

    public function page(Request $request){
        $requestdata = $request->all();
        $menuparam = [];
        // mengambil user yg sedang login
            $userauth = Auth::user()->toarray();
        // ==============================
        // mengambil param menu
            $dataparam = [];
            $dataparam['iduser'] = $userauth['id'];
            if (isset($requestdata['page'])) {
                $dataparam['page'] = $requestdata['page'];
            }
            if (isset($requestdata['action'])) {
                $dataparam['action'] = $requestdata['action'];
            }
            if (isset($requestdata['id'])) {
                $dataparam['id'] = $requestdata['id'];
            }
            $menuparam = $this->MainApi->getmenuparam($dataparam);
            if (!$menuparam['is_valid']) {
                return redirect('/home')->withErrors(['error' => $menuparam['message']]);
            }
            if (!empty($menuparam['data'])) {
                $menuparam = $menuparam['data'];
            }
        // ====================
        // cek view
            if (!View::exists($menuparam['view'])) {
                return redirect('/home')->withErrors(['error' => 'Halaman tidak ditemukan']);
            }
        // ====================
        return view('layouts.main',compact('menuparam'));
    }
    public function getmodal(Request $request){
        $requestdata = $request->all();
        // cek param
            if (!isset($requestdata['page'])) {
                return 'Param page is required';
            }
            if (!isset($requestdata['form'])) {
                return 'Param form is required';
            }
        // ==========
        $modalparam = [];
        // ambil form
            $modaldata = $this->MainApi->getmodalparam($requestdata);
            if (empty($modaldata['data'])) {
                return 'Halaman TIdak Ditemukan '.$modaldata['message'];
            }
            $modalparam = $modaldata['data'];
        // ==========
        return view($modalparam['viewparent'].'.modals.forms.'.$requestdata['form'],compact('modalparam'));
    }
}
