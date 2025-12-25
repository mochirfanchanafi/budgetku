<div id="action-form">
    <form id="{{ $menuparam['form-id'] }}" method="post"  enctype="multipart/form-data"  class="needs-validation" novalidate action="{{ url(str_replace('.','/',$menuparam['mainapiroute'])) }}/{{ strtoupper($menuparam['action']) == 'TAMBAH' ? 'store' : ( strtoupper($menuparam['action']) == 'UBAH' ? 'update' : '' ) }}">
        @csrf
        <fieldset class="border rounded-5 p-3">
            <legend class="float-none w-auto px-3" style="font-size: 14px; font-weight:bold;">Data Utama</legend>
            <!-- input hidden -->
                <input type="hidden" name="id" id="id" value="{{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) ? $menuparam['itemdata']['id'] : '0' }}">
                <input type="hidden" name="iduser" id="iduser" value="{{ isset($menuparam['userdata']) ? $menuparam['userdata']['id'] : '0' }}">
            <!-- ============ -->
            <!-- first row -->
                <div class="row">
                    <div class="col col-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="name" class="col-form-label col-form-label-sm">Nama</label>
                        <input required type="text" name="name" id="name" class="form-control form-control-sm" placeholder="Nama" autocomplete="off" value="{{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) ? $menuparam['itemdata']['name'] : '' }}">
                        <div class="invalid-feedback">
                            Nama Wajib Ditambahkan
                        </div>
                    </div>
                </div>
            <!-- ========= -->
            <!-- second row -->
                <div class="row">
                    <div class="col col-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="username" class="col-form-label col-form-label-sm">Username</label>
                        <input required type="text" name="username" id="username" class="form-control form-control-sm" placeholder="Username" autocomplete="off" value="{{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) ? $menuparam['itemdata']['username'] : '' }}">
                        <div class="invalid-feedback">
                            Username Wajib Ditambahkan
                        </div>
                    </div>
                </div>
            <!-- ========= -->
            <!-- third row -->
                <div class="row">
                    <div class="col col-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="username" class="col-form-label col-form-label-sm">Email</label>
                        <input required type="email" name="email" id="useemailrname" class="form-control form-control-sm" placeholder="Email" autocomplete="off" value="{{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) ? $menuparam['itemdata']['email'] : '' }}">
                        <div class="invalid-feedback">
                            Email Wajib Ditambahkan
                        </div>
                    </div>
                </div>
            <!-- ========= -->
            <!-- fourth row -->
                <div class="row">
                    <div class="col col-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="countrycode" class="col-form-label col-form-label-sm">Telp</label>
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend" style="width: 10%;">
                                <select name="countrycode" id="countrycode" class="form-control form-control-sm">
                                    <option selected value="62">+62</option>
                                </select>
                            </div>
                            <input type="text" name="telp" id="telp" class="form-control form-control-sm" placeholder="No. Telp" autocomplete="off" value="{{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) ? $menuparam['itemdata']['telp'] : '' }}">
                            <div class="invalid-feedback">
                                Telp Wajib Ditambahkan
                            </div>
                        </div>
                    </div>
                </div>
            <!-- ========= -->
            <!-- fift row -->
                <div class="row">
                    <div class="col col-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="idrole" class="col-form-label col-form-label-sm">Role</label>
                        <select required name="idrole" id="idrole" class="form-control form-control-sm">
                            <option></option>
                            @if(isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) && isset($menuparam['itemdata']['user_role']) && !empty($menuparam['itemdata']['user_role']))
                                <option selected value="{{ $menuparam['itemdata']['user_role']['idrole'] }}">{{ isset($menuparam['itemdata']['user_role']['role']) && !empty($menuparam['itemdata']['user_role']['role']) ? $menuparam['itemdata']['user_role']['role']['name'] : '' }}</option>
                            @endif
                        </select>
                        <div class="invalid-feedback">
                            Role Wajib Ditambahkan
                        </div>
                    </div>
                </div>
            <!-- ========= -->
            <!-- six row -->
                <div class="row">
                    <div class="col col-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="password" class="col-form-label col-form-label-sm">Password</label>
                        @if(isset($menuparam['itemdata']) && !empty($menuparam['itemdata']))
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="isubahpassword" onchange="{{ $menuparam['mainjsroute'] }}.setaktifpassword(this, event);">
                                <label class="form-check-label" for="isubahpassword">
                                    Ubah Password
                                </label>
                            </div>
                        @endif
                        <div class="input-group" id="show_hide_password">
                            <input {{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) ? 'disabled':'required' }} type="password" id="password" name="password" class="form-control" id="inlineFormInputGroupUsername2" placeholder="Password" value="">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <a href="" style="color: black; font-size:12px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <div class="invalid-feedback">
                                Password Wajib Ditambahkan
                            </div>
                        </div>
                    </div>
                </div>
            <!-- ========= -->
            <!-- fift row -->
                <div class="row">
                    <div class="col col-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="active" class="col-form-label col-form-label-sm">Status Aktif</label>
                        <select required name="active" id="active" class="form-control form-control-sm">
                            <option {{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) && $menuparam['itemdata']['active'] == '0' ? 'selected' : '' }} value="0">Tidak</option>
                            <option {{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) && $menuparam['itemdata']['active'] == '1' ? 'selected' : '' }} value="1">Ya</option>
                        </select>
                        <div class="invalid-feedback">
                            Status Aktif Wajib Ditambahkan
                        </div>
                    </div>
                </div>
            <!-- ========= -->
        </fieldset>
        <br>
        <div class="row">
            <div class="col col-12 col-md-12 col-sm-12 col-xs-12">
                @include('components.action-button')
            </div>
        </div>
    </form>
</div>