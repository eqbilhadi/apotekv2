<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Lokasi</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active">Lokasi</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content uiIndex">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Lokasi</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-sm" id="btnAdd"><i class="fas fa-plus-circle"></i> Tambah data</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body tabelData">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="tabelsupplier" style="width: 100%;">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content uiForm" style="display: none;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tambah Data Lokasi</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-sm" id="btnBack"><i class="fas fa-arrow-circle-left"></i> Kembali</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="#" id="formData">
                            <div class="row">
                                <div class="col-md-6 mx-auto">
                                    <input type="hidden" name="id_location" id="id_location">
                                    <table class="table table-bordered table-hover" id="tabelform" style="width: 100%;">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Nama Lokasi</th>
                                                <th class="aksi">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="paste-form">
                                            <tr class="text-center">
                                                <td><input type="text" name="nama[]" id="nama" data-index="nama[0]" class="form-control"></td>
                                                <td class="aksi"><button type="button" class="btn bg-blue" onclick="addForm()"><i class="fas fa-plus-circle"></i></button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary btn-sm float-right btn-save" onclick="save()"><i class="fas fa-save"></i> Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    var table = $('#tabelsupplier')
    var formData = $('#formData')
    var index = $('.uiIndex')
    var form = $('.uiForm')
    var saveData
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    function reloadTable() {
        table.DataTable().ajax.reload();
    }

    function resetAll() {
        $('.aksi').show()
        $('#nama').removeClass('is-invalid');
        $('#tabelform tbody .append-form').remove();
        $('.msgnama').html('');
        formData[0].reset()
    }

    $(function() {
        table.DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "ordering": false,
            "order": [],
            "ajax": {
                "url": "<?= site_url('location/getLocation') ?>",
                "type": "POST"
            },
            "columnDefs": [{
                    "targets": [0, -1],
                    "className": "text-center"
                },
                {
                    "targets": [0],
                    width: 6
                },
            ],
            "language": {
                "processing": '<i class="fas fa-spin fa-circle-notch"></i> Tunggu',
                "emptyTable": "Tidak ada data lokasi",
                "zeroRecords": "Data lokasi tidak ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ total lokasi",
                "infoFiltered": "(terfilter dari _MAX_ total lokasi)",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 total lokasi",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "<i class='fas fa-caret-right'></i>",
                    "previous": "<i class='fas fa-caret-left'></i>"
                },
                "search": "Cari:",
                "lengthMenu": "Lihat _MENU_ data lokasi",
                "searchPlaceholder": "Nama lokasi"
            }
        });

    });

    $(document).on('click', '#btnAdd', function() {
        resetAll()
        saveData = 'add'
        index.hide()
        form.show()
    })
    $(document).on('click', '#btnBack', function() {
        index.show()
        form.hide()
    })

    function save() {
        console.log(saveData)
        if (saveData == 'add') {
            url = "<?= site_url('location/add') ?>"
        } else if (saveData == 'edit') {
            url = "<?= site_url('location/update') ?>"
        }

        $.ajax({
            type: "POST",
            url: url,
            data: formData.serialize(),
            dataType: "JSON",

            beforeSend: function() {
                $('.btn-save').attr('disabled', true);
                $('.btn-save').html('<i class="fas fa-spin fa-circle-notch"></i> Tunggu');
            },

            complete: function() {
                $('.btn-save').attr('disabled', false);
                $('.btn-save').html('<i class="fas fa-save"></i> Simpan');
            },

            success: function(response) {
                if (response.success) {
                    Toast.fire({
                        icon: 'success',
                        title: response.success
                    })
                    index.show()
                    form.hide()
                    reloadTable();
                }
                if (response.failed) {
                    Toast.fire({
                        icon: 'error',
                        title: response.failed
                    })
                    index.show()
                    form.hide()
                    reloadTable();
                }
                if (response.error) {
                    Toast.fire({
                        icon: 'error',
                        html: response.error
                    })
                }
            }
        })
    }

    function byid(id, type) {
        resetAll()
        if (type == 'edit') {
            saveData = 'edit';
        }

        $.ajax({
            type: "GET",
            url: "<?= site_url('location/byid/') ?>" + id,
            dataType: "JSON",

            beforeSend: function() {
                if (type == 'edit') {
                    $('#btnEdit' + id).attr('disabled', true);
                    $('#btnEdit' + id).html('<i class="fas fa-spin fa-circle-notch"></i>');
                }
                if (type == 'delete') {
                    $('#btnDelete' + id).attr('disabled', true);
                    $('#btnDelete' + id).html('<i class="fas fa-spin fa-circle-notch"></i>');
                }
            },

            complete: function() {
                if (type == 'edit') {
                    $('#btnEdit' + id).attr('disabled', false);
                    $('#btnEdit' + id).html('<i class="fas fa-pen"></i>');
                }
            },

            success: function(response) {
                if (type == 'edit') {
                    $('.aksi').hide()
                    index.hide()
                    form.show()
                    $('.title').text('Edit Data Lokasi');
                    $('#id_location').val(response.id_location);
                    $('#nama').val(response.name);
                    $('#phone').val(response.phone);
                    $('#address').val(response.address);
                    $("input[name=gender][value=" + response.gender + "]").prop('checked', true);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Yakin hapus data lokasi ini?',
                        showCancelButton: true,
                        confirmButtonText: 'Ya',
                        cancelButtonText: `Tidak`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteData(response.id_location);
                        } else if (result.isDismissed) {
                            $('#btnDelete' + id).attr('disabled', false);
                            $('#btnDelete' + id).html('<span class="fas fa-trash"></span>');
                        }
                    });
                }
            }
        })
    }

    function deleteData(id) {
        $.ajax({
            type: "post",
            url: "<?= site_url('location/delete/') ?>" + id,
            dataType: "json",
            success: function(response) {
                $('#btnDelete' + id).attr('disabled', false);
                $('#btnDelete' + id).html('<span class="fas fa-trash"></span>');
                if (response.success) {
                    reloadTable();
                    Toast.fire({
                        icon: 'success',
                        text: response.success,
                    })
                } else {
                    reloadTable();
                    Toast.fire({
                        icon: 'error',
                        text: response.error,
                    })
                }
            }
        })
    }
    var i = 0

    function addForm() {
        $('.paste-form').append("\
            <tr class='text-center append-form'>\
                <td><input type='text' name='nama[]' id='nama' data-index='nama[" + i + "]' class='form-control'></td>\
                <td><button type='button' class='btn bg-red remove-btn'><i class='fas fa-trash'></i></button></td>\
            </tr>\
        ")
    }

    $(document).on('click', '.remove-btn', function() {
        // $('.append-form' + i + '').remove()
        i = i - 1
        $(this).closest('.append-form').remove()
    })
</script>