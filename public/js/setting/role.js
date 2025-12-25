let Role = {
    // main module
        moduleapi: () => {
            return $('#mainapiroute').val().replaceAll('.','/');
        },
        modulejs: () => {
            return $('#menujs').val();
        },
    // ===========
    // ajax
        // get data
            list:()=>{
                $('#index-table').DataTable().destroy();
                $('#index-table tbody').empty();

                let token = 'Bearer '+$('#sanctum_token').val();
                // cek url api
                    if (Role.moduleapi() == null || Role.moduleapi() == '') {
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
                        url: window.location.origin+'/' + Role.moduleapi(),
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
                            data: 'kode',
                            
                        },
                        {
                            data: 'name',
                            
                        },
                        {
                            data: 'id',
                            render: (data, type, row, meta) => {
                                let deleteBtn = "";
                                let updateBtn = `<a href="${window.location.origin}?page=${$('#menu').val()}&action=UPDATE&id=${row.id}" class="btn bg-gradient-info btn-sm"><i class="fa fa-pencil"></i></a>&nbsp;`;
                                if ($('#isdelete').val() == 1) {
                                    deleteBtn = `<a data-id="${row.id}" onclick="Role.hapus(this, event)" class="btn bg-danger btn-sm text-light"><i class="fa fa-trash"></i></a>&nbsp;`;
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
                    url: window.location.origin+'/'+Role.moduleapi()+'/store',
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
                    url: window.location.origin+'/'+Role.moduleapi()+'/update',
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
                        Role.destroy(formData);
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
                    url: window.location.origin+'/'+Role.moduleapi()+'/destroy',
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
                                Role.list();
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
}
$(function () {
    // jika halaman index onload
        if ($('#index').is(':visible')) {
            Role.list();      
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
                    Role.store(formData);
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
                    Role.update(formData);
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })
        })
    // ================
});