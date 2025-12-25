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
                    <div class="col col-12 col-md-4 col-sm-12 col-xs-12">
                        <label for="kode" class="col-form-label col-form-label-sm">Kode</label>
                        <input required type="text" name="kode" id="kode" class="form-control form-control-sm" placeholder="Kode Menu" autocomplete="off" value="{{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) ? $menuparam['itemdata']['kode'] : '' }}">
                        <div class="invalid-feedback">
                            Kode Menu Wajib Ditambahkan
                        </div>
                    </div>
                    <div class="col col-12 col-md-4 col-sm-12 col-xs-12">
                        <label for="name" class="col-form-label col-form-label-sm">Nama</label>
                        <input required type="text" name="name" id="menuname" class="form-control form-control-sm" placeholder="Nama Menu" autocomplete="off" value="{{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) ? $menuparam['itemdata']['name'] : '' }}">
                        <div class="invalid-feedback">
                            Nama Menu Wajib Ditambahkan
                        </div>
                    </div>
                    <div class="col col-12 col-md-4 col-sm-12 col-xs-12">
                        <label for="name" class="col-form-label col-form-label-sm">Menu Parent</label>
                        <select name="idmenu" id="idmenu" class="form-control form-control-sm">
                            @if(isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) && isset($menuparam['itemdata']['parent']) && !empty($menuparam['itemdata']['parent']))
                                <option selected value="{{ $menuparam['itemdata']['parent']['id'] }}">{{ $menuparam['itemdata']['parent']['name'] }}</option>
                            @endif
                        </select>
                        <div class="invalid-feedback">
                            Menu Parent Wajib Ditambahkan
                        </div>
                    </div>
                </div>
            <!-- ============ -->
            <!-- second row -->
                <div class="row">
                    <div class="col col-12 col-md-3 col-sm-12 col-xs-12">
                        <label for="icon" class="col-form-label col-form-label-sm"> Icon Menu (Font Awesome)</label>
                        <input required type="text" name="icon" id="icon" class="form-control form-control-sm" placeholder="Tambahkan Icon Menu" value="{{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) ? $menuparam['itemdata']['icon'] : '' }}">
                        <div class="invalid-feedback">
                            Icon Menu Wajib Ditambahkan
                        </div>
                    </div>
                    <div class="col col-12 col-md-3 col-sm-12 col-xs-12">
                        <label for="urutan" class="col-form-label col-form-label-sm"> Urutan</label>
                        <input required type="text" name="urutan" id="urutan" class="form-control form-control-sm" placeholder="Tambahkan Urutan Menu" onchange="Main.inputnumberonly(this,event);" value="{{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) ? $menuparam['itemdata']['urutan'] : '0' }}">
                        <div class="invalid-feedback">
                            Urutan Menu Wajib Ditambahkan
                        </div>
                    </div>
                    <div class="col col-12 col-md-3 col-sm-12 col-xs-12">
                        <label for="jenis" class="col-form-label col-form-label-sm"> Jenis Transaksi</label>
                        <select required name="jenis" id="jenis" class="form-control form-control-sm">
                            <option></option>
                            <option {{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) && $menuparam['itemdata']['jenis'] == 1 ? 'selected' : '' }} value="1">By Menu</option>
                            <option {{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) && $menuparam['itemdata']['jenis'] == 2 ? 'selected' : '' }} value="2">By User</option>
                            <option {{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) && $menuparam['itemdata']['jenis'] == 3 ? 'selected' : '' }} value="3">By Transaksi</option>
                        </select>
                        <div class="invalid-feedback">
                            Jenis Menu Wajib Ditambahkan
                        </div>
                    </div>
                    <div class="col col-12 col-md-3 col-sm-12 col-xs-12">
                        <label for="urutan" class="col-form-label col-form-label-sm"> Approve Lvl</label>
                        <input required type="text" name="tingkatapprove" id="approvemenu" class="form-control form-control-sm" placeholder="Tambahkan Tingkat Approve" onchange="Main.inputnumberonly(this,event);" value="{{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) ? $menuparam['itemdata']['tingkatapprove'] : '0' }}">
                        <div class="invalid-feedback">
                            Approve Lvl Wajib Ditambahkan
                        </div>
                    </div>
                </div>
            <!-- ============ -->
            <!-- third row -->
                <div class="row">
                    <div class="col col-12 col-md-4 col-sm-12 col-xs-12">
                        <label for="icon" class="col-form-label col-form-label-sm"> Parent View</label>
                        <input type="text" name="viewparent" id="viewparent" class="form-control form-control-sm" placeholder="Tambahkan Parent View ( gunakan titik '.' untuk prefik Parent View )" value="{{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) ? $menuparam['itemdata']['viewparent'] : '' }}">
                        <div class="invalid-feedback">
                            Parent Menu Wajib Ditambahkan
                        </div>
                    </div>
                    <div class="col col-12 col-md-4 col-sm-12 col-xs-12">
                        <label for="icon" class="col-form-label col-form-label-sm"> URL API</label>
                        <input required type="text" name="mainapiroute" id="apiroute" class="form-control form-control-sm" placeholder="Tambahkan URL API ( gunakan titik '.' untuk prefik URL )" value="{{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) ? $menuparam['itemdata']['mainapiroute'] : '' }}">
                        <div class="invalid-feedback">
                            URL API Wajib Ditambahkan
                        </div>
                    </div>
                    <div class="col col-12 col-md-4 col-sm-12 col-xs-12">
                        <label for="icon" class="col-form-label col-form-label-sm"> Path API Controller</label>
                        <input required type="text" name="mainapicontroller" id="mainapicontroller" class="form-control form-control-sm" placeholder="Tambahkan URL API ( gunakan titik '.' untuk prefik URL )" value="{{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) ? $menuparam['itemdata']['mainapicontroller'] : '' }}">
                        <div class="invalid-feedback">
                            Path API Controller Wajib Ditambahkan
                        </div>
                    </div>
                </div>
            <!-- ============ -->
            <!-- forth row -->
                <div class="row">
                    <div class="col col-12 col-md-6 col-sm-12 col-xs-12">
                        <label for="icon" class="col-form-label col-form-label-sm"> File JS</label>
                        <input required type="text" name="mainjs" id="mainjs" class="form-control form-control-sm" placeholder="Tambahkan File JS ( gunakan titik '.' untuk prefik File JS )" value="{{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) ? $menuparam['itemdata']['mainjs'] : '' }}">
                        <div class="invalid-feedback">
                            File JS Wajib Ditambahkan
                        </div>
                    </div>
                    <div class="col col-12 col-md-6 col-sm-12 col-xs-12">
                        <label for="icon" class="col-form-label col-form-label-sm"> Module JS</label>
                        <input required type="text" name="mainjsroute" id="jsroute" class="form-control form-control-sm" placeholder="Tambahkan URL JS" value="{{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) ? $menuparam['itemdata']['mainjsroute'] : '' }}">
                        <div class="invalid-feedback">
                            Module JS Wajib Ditambahkan
                        </div>
                    </div>
                </div>
            <!-- ============ -->
        </fieldset>
        <fieldset class="border rounded-5 p-3">
            <legend class="float-none w-auto px-3" style="font-size: 14px; font-weight:bold;">Detail Role</legend>
            <div class="row">
                <div class="col col-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col col-12 col-md-3 col-sm-12 col-xs-12">
                            <button onclick="{{ $menuparam['mainjsroute'] }}.getaction(this,event);" data-form="role-form" data-modal="true" data-modal-title="Daftar Role" data-modal-dialog="modal-dialog modal-md" class="btn btn-block btn-sm btn-outline-info"><i class="fa-regular fa-square-plus"></i> Tambah Detail</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-12 col-md-12 col-sm-12 col-xs-12">
                            @include($menuparam['viewparent'].'.tables.detail-table')
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <br>
        <div class="row">
            <div class="col col-12 col-md-12 col-sm-12 col-xs-12">
                @include('components.action-button')
            </div>
        </div>
    </form>
</div>