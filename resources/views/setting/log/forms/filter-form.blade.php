<div class="row">
    <div class="col col-12 col-md-4 col-sm-12 col-xs-12">
        <label for="idmenu" class="col-form-label col-form-label-sm">Menu</label>
        <select name="idmenu" id="idmenu" class="form-control form-control-sm">
            <option></option>
        </select>
    </div>
    <div class="col col-12 col-md-2 col-sm-12 col-xs-12">
        <label for="idmenu" class="col-form-label col-form-label-sm">Tanggal Awal</label>
        <input type="text" class="form-control form-control-sm custom-sm" name="tgl_awal" placeholder="Tanggal Awal" id="tgl_awal" autocomplete="off" value=""/>
    </div>
    <div class="col col-12 col-md-2 col-sm-12 col-xs-12">
        <label for="idmenu" class="col-form-label col-form-label-sm">Tanggal Akhir</label>
        <input type="text" class="form-control form-control-sm custom-sm" name="tgl_akhir" placeholder="Tanggal Akhir" id="tgl_akhir" autocomplete="off" value=""/>
    </div>
    <div class="col col-12 col-md-2 col-sm-12 col-xs-12">
        <label for="idmenu" class="col-form-label col-form-label-sm" style="opacity: 0;">.</label>
        <a onclick="{{ $menuparam['mainjsroute'] }}.list();" class="btn btn-block btn-sm btn-outline-info"><i class="fa-solid fa-search"></i> Filter</a>
    </div>
    <div class="col col-12 col-md-2 col-sm-12 col-xs-12">
        <label for="idmenu" class="col-form-label col-form-label-sm" style="opacity: 0;">.</label>
        <a href="{{ url('/') }}?page={{ $menuparam['menu'] }}&action=add" class="btn btn-block btn-sm bg-gradient-info text-light"><i class="fa-solid fa-plus"></i> Tambah</a>
    </div>
</div>