let Log = {
    // main module
        moduleapi: () => {
            return $('#mainapiroute').val().replaceAll('.','/');
        },
        modulejs: () => {
            return $('#menujs').val();
        },
    // ===========
    // menu module
        modulemenu: () => {
            return 'setting/menu';
        },
        modulemenuapi: () => {
            return 'api/'+Log.modulemenu();
        },
    // ===========
    // set select2
        setSelect2:()=>{
            $('#idmenu').select2({
                placeholder: "-- Pilih --",
                allowClear: true,
                ajax: {
                    headers: {
                        "Authorization": 'Bearer '+$('#sanctum_token').val(),
                    },
                    url: window.location.origin+'/' + Log.modulemenuapi() +'/select',
                    data: function (params) {
                        var query = {
                            term: params.term,
                            page: params.page || 1,
                            length: 10,
                            start: params.page ? (params.page - 1) * 10 : 0
                        }                               
                        return query;
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;                        
                        return {
                            results: $.map(data.data, function (obj) {
                                obj.id = obj.kode;
                                obj.text = `${ obj.name } `;
                                return obj;
                            }),
                            pagination: {
                                more: (params.page * 10) < data.recordsTotal
                            }
                        };
                    }
                }
            });
        },
    // ===========
    // ajax
        list:()=>{
            $('#index-table').DataTable().destroy();
            $('#index-table tbody').empty();

            let token = 'Bearer '+$('#sanctum_token').val();
            // cek url api
                if (Log.moduleapi() == null || Log.moduleapi() == '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'URL API belum ditambahkan!',
                        confirmButtonColor: '#0ea5e9',
                    })
                    return false;
                }
            // ===========
            let param = {};
            param.menu = $('#idmenu').val();
            param.tgl_awal = $('#tgl_awal').val();
            param.tgl_akhir = $('#tgl_akhir').val();
            $('#index-table').DataTable({
                "fixedHeader": true,
                scrollX: true,
                responsive: true,
                processing: true,
                serverSide: false,
                processing: true,
                serverSide: true,
                order: [
                    [0, 'asc']
                ],
                aLengthLog: [
                    [10, 50, 100, -1],
                    [10, 50, 100, 'All'],
                ],
                pageLength: 10,
                ajax: {
                    headers: {
                        "Authorization": token
                    },
                    url: window.location.origin+'/' + Log.moduleapi(),
                    type: "GET",
                    data: param,
                },
                columns: [
                    {
                        "data": "id",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'menu',
                        
                    },
                    {
                        data: 'notransaksi',
                        
                    },
                    {
                        data: 'action',
                        
                    },
                    {
                        data: 'action_time',
                        
                    },
                    {
                        data: 'created_by',
                        render: function (data, type, row, meta) {
                            return row.user != null && row.user != '' ? `( ${ row.user.username } ) ${ row.user.name }` : '';
                        }
                    },
                    {
                        data: 'keterangan',
                        
                    },
                ],
                fnServerParams: function(data) {
                    data['order'].forEach(function(items, index) {
                        data['order'][index]['column'] = data['columns'][items.column]['data'];
                });
                },
            });            
        },
    // ====
}
$(function () {
    // jika halaman index onload
        if ($('#index').is(':visible')) {
            $("#tgl_awal" ).datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
            });
            $("#tgl_akhir" ).datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
            });
            Log.setSelect2();
            Log.list();
        }
    // =========================
});