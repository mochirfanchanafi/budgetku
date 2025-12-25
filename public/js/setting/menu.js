let Menu = {
    // main module
        moduleapi: () => {
            return $('#mainapiroute').val().replaceAll('.','/');
        },
        modulejs: () => {
            return $('#menujs').val();
        },
    // ===========
    // role module
        moduleroleapi: () => {
            return 'api/setting/role';
        },
    // ===========
    // ajax
        // get data
            list:()=>{
                $('#index-table').DataTable().destroy();
                $('#index-table tbody').empty();

                let token = 'Bearer '+$('#sanctum_token').val();
                // cek url api
                    if (Menu.moduleapi() == null || Menu.moduleapi() == '') {
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
                    aLengthMenu: [
                        [10, 50, 100, -1],
                        [10, 50, 100, 'All'],
                    ],
                    pageLength: 10,
                    ajax: {
                        headers: {
                            "Authorization": token
                        },
                        url: window.location.origin+'/' + Menu.moduleapi(),
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
                            data: 'name',
                            
                        },
                        {
                            data: 'id',
                            render:(data,type, row, meta) =>{
                                return `${ row.parent != null && row.parent != '' ? row.parent.name : ''}`
                            },
                            
                        },
                        {
                            data: 'id',
                            render: (data, type, row, meta) => {
                                let deleteBtn = "";
                                let updateBtn = `<a href="${window.location.origin}?page=${$('#menu').val()}&action=UPDATE&id=${row.id}" class="btn bg-gradient-info btn-sm text-light"><i class="fa fa-pencil"></i></a>&nbsp;`;
                                if ($('#isdelete').val() == 1) {
                                    deleteBtn = `<a data-id="${row.id}" onclick="Menu.hapus(this, event)" class="btn bg-danger btn-sm text-light"><i class="fa fa-trash"></i></a>&nbsp;`;
                                }
                                let buttons = `<div class="btn-group">${updateBtn}${deleteBtn}</div>`
                                return buttons;
                            },
                        },
                    ],
                    fnServerParams: function(data) {
                        data['order'].forEach(function(items, index) {
                            data['order'][index]['column'] = data['columns'][items.column]['data'];
                    });
                    },
                });            
            },
            listrole:()=>{
                $('#role-table').DataTable().destroy();
                $('#role-table tbody').empty();

                let token = 'Bearer '+$('#sanctum_token').val();
                // cek url api
                    if (Menu.moduleroleapi() == null || Menu.moduleroleapi() == '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'URL belum ditambahkan!',
                            confirmButtonColor: '#0ea5e9',
                        })
                        return false;
                    }
                // ===========
                let param = {};
                $('#role-table').DataTable({
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
                    aLengthMenu: [
                        [10, 50, 100, -1],
                        [10, 50, 100, 'All'],
                    ],
                    pageLength: 10,
                    ajax: {
                        headers: {
                            "Authorization": token
                        },
                        url: window.location.origin+'/' + Menu.moduleroleapi() + '/select',
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
                            data: 'name',
                            
                        },
                        {
                            data: 'id',
                            render: (data, type, row, meta) => {
                                return `<a data-id="${row.id}" data-kode="${row.kode}" data-name="${row.name}" onclick="Menu.getrole(this,event);" class="btn bg-gradient-info btn-sm"><i class="fa-solid fa-check"></i></a>&nbsp;`;
                            },
                        },
                    ],
                    fnServerParams: function(data) {
                        data['order'].forEach(function(items, index) {
                            data['order'][index]['column'] = data['columns'][items.column]['data'];
                    });
                    },
                }); 
            },
        // ========
        // form action
            store:(formData)=>{
                let token = 'Bearer '+$('#sanctum_token').val();
                formData.append('menu', $('#menu').val());
                $.ajax({
                    headers: {
                        "Authorization": token
                    },
                    type: 'POST',
                    url: window.location.origin+'/'+Menu.moduleapi()+'/store',
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend: () => {
                        Swal.fire({
                            title: 'Harap Tunggu!',
                            html: 'Proses Menyimpan Data',
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            },
                        });
                    },

                    error: function(response) {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message,
                            confirmButtonColor: '#0ea5e9',
                        })
                    },
                    
                    success: function(response){
                        swal.close();
                        if (response.is_valid == true) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Menyimpan Data',
                                text: 'Data Berhasil Disimpan',
                                confirmButtonColor: '#0ea5e9',
                            })
                            setTimeout(function () {
                                window.location.href = window.location.origin+'?page='+$('#menu').val();
                            }, 1000);
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message,
                                confirmButtonColor: '#0ea5e9',
                            })
                        }
                    }
                });
            },
            update:(formData)=>{
                let token = 'Bearer '+$('#sanctum_token').val();
                formData.append('menu', $('#menu').val());
                $.ajax({
                    headers: {
                        "Authorization": token
                    },
                    type: 'POST',
                    url: window.location.origin+'/'+Menu.moduleapi()+'/update',
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend: () => {
                        Swal.fire({
                            title: 'Harap Tunggu!',
                            html: 'Proses Menyimpan Data',
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            },
                        });
                    },

                    error: function(response) {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message,
                            confirmButtonColor: '#0ea5e9',
                        })
                    },
                    
                    success: function(response){
                        swal.close();
                        if (response.is_valid == true) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Menyimpan Data',
                                text: 'Data Berhasil Disimpan',
                                confirmButtonColor: '#0ea5e9',
                            })
                            setTimeout(function () {
                                window.location.href = window.location.origin+'?page='+$('#menu').val();
                            }, 1000);
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message,
                                confirmButtonColor: '#0ea5e9',
                            })
                        }
                    }
                });
            },
            hapus:(elm,e)=>{
                e.preventDefault();
                let id = $(elm).data('id');
                var formData = new FormData();
                formData.append('id', id); 
                formData.append('iduser', $('#userid').val());
                Swal.fire({
                    icon: 'question',
                    title: 'Apakah Anda Yakin Menyimpan Data Ini?',
                    showDenyButton: false,
                    showCancelButton: true,
                    confirmButtonText: 'Simpan',
                    confirmButtonColor: '#0ea5e9',
                    denyButtonText: `Batal`,
                }).then((result) => {
                    if (result.value) {
                        Menu.destroy(formData);
                    } else if (result.isDenied) {
                        Swal.fire('Changes are not saved', '', 'info')
                    }
                })
            },
            destroy:(formData)=>{
                let token = 'Bearer '+$('#sanctum_token').val();
                formData.append('menu', $('#menu').val());
                $.ajax({
                    headers: {
                        "Authorization": token,
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    type: 'POST',
                    url: window.location.origin+'/'+Menu.moduleapi()+'/destroy',
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend: () => {
                        Swal.fire({
                            title: 'Harap Tunggu!',
                            html: 'Proses Menyimpan Data',
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            },
                        });
                    },

                    error: function(response) {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message,
                            confirmButtonColor: '#0ea5e9',
                        })
                    },
                    
                    success: function(response){
                        swal.close();
                        if (response.is_valid == true) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Menyimpan Data',
                                text: 'Data Berhasil Disimpan',
                                confirmButtonColor: '#0ea5e9',
                            })
                            setTimeout(function () {
                                Menu.list();
                            }, 1000);
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message,
                                confirmButtonColor: '#0ea5e9',
                            })
                        }
                    }
                });
            },
        // ========
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
                    url: window.location.origin+'/' + Menu.moduleapi() +'/select',
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
                                obj.id = obj.id;
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
            $('#jenis').select2({
                placeholder: "-- Pilih --",
                allowClear: true,
            });
            $('.action-role').select2({
                placeholder: "-- Pilih --",
            });
        },
    // ===========
    // action
        getaction:(elm,e)=>{
            e.preventDefault();
            let ismodal = $(elm).data('modal');

            if (ismodal) {
                let param = {};
                param.iduser = $('#iduser').val();
                param.page = $('#menu').val();
                param.form = $(elm).data('form');                

                $("#modal-body").html('');
                $('#modal-title').text($(elm).data('modal-title'));
                $('#modal-dialog').removeClass();
                $('#modal-dialog').addClass($(elm).data('modal-dialog'));

                $.ajax({
                    type: 'get',
                    data: param,
                    url: window.location.origin+'/getmodal',
                    beforeSend: () => {
                        Swal.fire({
                            title: 'Harap Tunggu!',
                            html: 'Proses Pengambilan Data',
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            confirmButtonColor: '#0ea5e9',
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            },
                        });
                    },
                    error: function(response) {
                        swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            confirmButtonColor: '#0ea5e9',
                        })
                    },
                    success: function(response) {
                        swal.close();
                        $("#modal-body").html(response);
                        $('#main-modal').modal('show');

                        $('#main-modal').on('shown.bs.modal', function() {
                            // modal role
                                if ($('#role-table').is(':visible')) {
                                    Menu.listrole();
                                }
                            // ==========
                        })
                    },
                });
            }
        },
        getrole:(elm,e)=>{
            e.preventDefault();
            let id = $(elm).data('id');
            let kode = $(elm).data('kode');
            let name = $(elm).data('name');

            let isavailable = true;
            $('#detail-table tbody tr').each(function(){
                let idrole = $(this).find('.idrole').val();
                if (idrole == id) {
                    isavailable = false;
                }
            });

            if (isavailable) {
                let newrow = `  <tr>
                                    <td></td>
                                    <td>
                                        ${name}
                                        <input type="hidden" class="iddetail" name="iddetail[]" value="0">
                                        <input type="hidden" class="idrole" name="idrole[]" value="${id}">
                                    </td>
                                    <td>
                                        <select required name="is_create[]" class="form-control form-control-sm action-role is_create">
                                            <option value="0">Tidak</option>
                                            <option value="1">Ya</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select required name="is_read[]" class="form-control form-control-sm action-role is_read">
                                            <option value="0">Tidak</option>
                                            <option value="1">Ya</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select required name="is_update[]" class="form-control form-control-sm action-role is_update">
                                            <option value="0">Tidak</option>
                                            <option value="1">Ya</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select required name="is_delete[]" class="form-control form-control-sm action-role is_delete">
                                            <option value="0">Tidak</option>
                                            <option value="1">Ya</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select required name="is_reset[]" class="form-control form-control-sm action-role is_reset">
                                            <option value="0">Tidak</option>
                                            <option value="1">Ya</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select required name="is_reject[]" class="form-control form-control-sm action-role is_reject">
                                            <option value="0">Tidak</option>
                                            <option value="1">Ya</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select required name="is_close[]" class="form-control form-control-sm action-role is_close">
                                            <option value="0">Tidak</option>
                                            <option value="1">Ya</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select required name="is_admin[]" class="form-control form-control-sm action-role" onchange="Menu.setroleaction(this,event);" >
                                            <option value="0">Tidak</option>
                                            <option value="1">Ya</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input class="form-control form-control-sm" name="tingkat_approve_role[]" class="tingkat_approve_role" type="text" onchange="Main.inputnumberonly(this,event);" value="0">
                                    </td>
                                    <td>
                                        &nbsp
                                            <button type="button" onclick="Menu.deleterow(this, event)" class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        &nbsp
                                    </td>
                                </tr>`;
                $('#detail-table tbody').append(newrow);
                Menu.setSelect2();
                Menu.setnumber();
            }
            $('#main-modal').modal('hide');
        },
        deleterow:(elm, e) =>{
            e.preventDefault();
            $(elm).closest('tr').remove();
            Menu.setnumber();
        },
        setnumber:() =>{
            let idx = 1;
            $('#detail-table tbody tr').each(function(){
                $(this).find('td:eq(0)').text(idx);
                idx++;
            });
        },
        setroleaction:(elm,e)=>{
            e.preventDefault();
            let value = $(elm).val();

            $(elm).closest('tr').find('.is_create').val(value);
            $(elm).closest('tr').find('.is_create').trigger('change');

            $(elm).closest('tr').find('.is_read').val(value);
            $(elm).closest('tr').find('.is_read').trigger('change');

            $(elm).closest('tr').find('.is_update').val(value);
            $(elm).closest('tr').find('.is_update').trigger('change');

            $(elm).closest('tr').find('.is_delete').val(value);
            $(elm).closest('tr').find('.is_delete').trigger('change');

            $(elm).closest('tr').find('.is_reset').val(value);
            $(elm).closest('tr').find('.is_reset').trigger('change');

            $(elm).closest('tr').find('.is_reject').val(value);
            $(elm).closest('tr').find('.is_reject').trigger('change');

            $(elm).closest('tr').find('.is_close').val(value);
            $(elm).closest('tr').find('.is_close').trigger('change');

        },
    // ===========
}
$(function () {
    // jika halaman index onload
        if ($('#index').is(':visible')) {
            Menu.list();      
        }
    // =========================
    // jika halaman action onload
        if ($('#action-form').is(':visible')) {
            Menu.setSelect2();
            Menu.setnumber();
        }
    // =========================
    // submit form tambah
        $('#form-tambah').on('submit', function(e){
            e.preventDefault();
            let status = Main.validateform('form-tambah');            
            if (!status) {
                return 0;
            }
            var formData = new FormData(this);
            formData.append('menu', $('#menu').val());
            Swal.fire({
                icon: 'question',
                title: 'Apakah Anda Yakin Menyimpan Data Ini?',
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                confirmButtonColor: '#0ea5e9',
                denyButtonText: `Batal`,
            }).then((result) => {
                if (result.value) {
                    Menu.store(formData);
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })
        })
    // ================
    // submit form ubah
        $('#form-ubah').on('submit', function(e){
            e.preventDefault();            
            let status = Main.validateform('form-ubah');            
            if (!status) {
                return 0;
            }
            var formData = new FormData(this);
            formData.append('menu', $('#menu').val());
            Swal.fire({
                icon: 'question',
                title: 'Apakah Anda Yakin Menyimpan Data Ini?',
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                confirmButtonColor: '#0ea5e9',
                denyButtonText: `Batal`,
            }).then((result) => {
                if (result.value) {
                    Menu.update(formData);
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })
        })
    // ================
});