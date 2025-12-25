<!-- user data -->
    <input type="hidden" name="iduser" id="iduser" value="{{ isset($menuparam['userdata']) ? $menuparam['userdata']['id'] : '0' }}">
    <input type="hidden" name="username" id="username" value="{{ isset($menuparam['userdata']) ? $menuparam['userdata']['username'] : '' }}">
    <input type="hidden" name="nama_user" id="nama_user" value="{{ isset($menuparam['userdata']) ? $menuparam['userdata']['name'] : '' }}">
    <input type="hidden" name="sanctum_token" id="sanctum_token" value="{{ Session::get('sanctum_token') }}">
<!-- ========= -->
<!-- menu permission -->
    <input type="hidden" name="iscreate" id="iscreate" value="{{ isset($menuparam['menupermission']) ? $menuparam['menupermission']['is_create'] : '0' }}">
    <input type="hidden" name="isread" id="isread" value="{{ isset($menuparam['menupermission']) ? $menuparam['menupermission']['is_read'] : '0' }}">
    <input type="hidden" name="isupdate" id="isupdate" value="{{ isset($menuparam['menupermission']) ? $menuparam['menupermission']['is_update'] : '0' }}">
    <input type="hidden" name="isdelete" id="isdelete" value="{{ isset($menuparam['menupermission']) ? $menuparam['menupermission']['is_delete'] : '0' }}">
    <input type="hidden" name="isreset" id="isreset" value="{{ isset($menuparam['menupermission']) ? $menuparam['menupermission']['is_reset'] : '0' }}">
    <input type="hidden" name="isreject" id="isreject" value="{{ isset($menuparam['menupermission']) ? $menuparam['menupermission']['is_reject'] : '0' }}">
    <input type="hidden" name="isclose" id="isclose" value="{{ isset($menuparam['menupermission']) ? $menuparam['menupermission']['is_close'] : '0' }}">
    <input type="hidden" name="isadmin" id="isadmin" value="{{ isset($menuparam['menupermission']) ? $menuparam['menupermission']['is_admin'] : '0' }}">
    <input type="hidden" name="maxapprove" id="maxapprove" value="{{ isset($menuparam['tingkatapprove']) ? $menuparam['tingkatapprove'] : '0' }}">
    <input type="hidden" name="tingkatapprove" id="tingkatapprove" value="{{ isset($menuparam['menupermission']) ? $menuparam['menupermission']['tingkatapprove'] : '0' }}">
<!-- =============== -->
<!-- menu base url -->
    <input type="hidden" name="menu" id="menu" value="{{ isset($menuparam['menu']) ? $menuparam['menu'] : '' }}">
    <input type="hidden" name="menujs" id="menujs" value="{{ isset($menuparam['menujs']) ? $menuparam['menujs'] : '' }}">
    <input type="hidden" name="mainapiroute" id="mainapiroute" value="{{ isset($menuparam['mainapiroute']) ? $menuparam['mainapiroute'] : '' }}">
    <input type="hidden" name="mainjsroute" id="mainjsroute" value="{{ isset($menuparam['mainjsroute']) ? $menuparam['mainjsroute'] : '' }}">
<!-- =============== -->