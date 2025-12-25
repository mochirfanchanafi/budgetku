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
                    <div class="col col-12 col-md-6 col-sm-12 col-xs-12">
                        <label for="kode" class="col-form-label col-form-label-sm">Kode</label>
                        <input required type="text" name="kode" id="kode" class="form-control form-control-sm" placeholder="Kode" autocomplete="off" value="{{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) ? $menuparam['itemdata']['kode'] : '' }}">
                        <div class="invalid-feedback">
                            Kode Wajib Ditambahkan
                        </div>
                    </div>
                    <div class="col col-12 col-md-6 col-sm-12 col-xs-12">
                        <label for="name" class="col-form-label col-form-label-sm">Nama</label>
                        <input required type="text" name="name" id="name" class="form-control form-control-sm" placeholder="Nama" autocomplete="off" value="{{ isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) ? $menuparam['itemdata']['name'] : '' }}">
                        <div class="invalid-feedback">
                            Nama Wajib Ditambahkan
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