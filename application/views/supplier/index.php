<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Pemasok</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pemasok</li>
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
                        <h3 class="card-title">Data Pemasok</h3>
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
                                        <th>No Telepon</th>
                                        <th>Alamat</th>
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
                        <h3 class="card-title">Tambah Data Pemasok</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-sm" id="btnBack"><i class="fas fa-arrow-circle-left"></i> Kembali</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="#" id="formData">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="nama">Nama Pemasok</label>
                                    <input type="text" name="nama" id="nama" class="form-control">
                                    <input type="hidden" name="id_supplier" id="id_supplier">
                                    <span class="text-danger msgnama"></span>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="phone">No Telepon</label>
                                    <input type="number" name="phone" id="phone" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="desc">Deskripsi</label>
                                    <textarea name="desc" id="desc" cols="30" rows="3" class="form-control"></textarea>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="address">Alamat</label>
                                    <textarea name="address" id="address" cols="30" rows="3" class="form-control"></textarea>
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
        formData[0].reset()
        $('#nama').removeClass('is-invalid');
        $('.msgnama').html('');
    }

    $(function() {
        table.DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "ordering": false,
            "order": [],
            "ajax": {
                "url": "<?= site_url('supplier/getSupplier') ?>",
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
                "emptyTable": "Tidak ada data pemasok",
                "zeroRecords": "Data pemasok tidak ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ total pemasok",
                "infoFiltered": "(terfilter dari _MAX_ total pemasok)",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 total pemasok",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "<i class='fas fa-caret-right'></i>",
                    "previous": "<i class='fas fa-caret-left'></i>"
                },
                "search": "Cari:",
                "lengthMenu": "Lihat _MENU_ data pemasok",
                "searchPlaceholder": "Nama pemasok"
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
            url = "<?= site_url('supplier/add') ?>"
        } else if (saveData == 'edit') {
            url = "<?= site_url('supplier/update') ?>"
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
                    if (response.error.nama) {
                        $('#nama').addClass('is-invalid');
                        $('.msgnama').html(response.error.nama);
                    } else {
                        $('#nama').removeClass('is-invalid');
                        $('.msgnama').html('');
                    }
                }
            }
        })
    }

    function byid(id, type) {
        if (type == 'edit') {
            saveData = 'edit';
        }

        $.ajax({
            type: "GET",
            url: "<?= site_url('supplier/byid/') ?>" + id,
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
                    index.hide()
                    form.show()
                    $('.title').text('Edit Data Supplier');
                    $('#id_supplier').val(response.id_supplier);
                    $('#nama').val(response.name);
                    $('#phone').val(response.phone);
                    $('#address').val(response.address);
                    $('#desc').val(response.description);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Yakin hapus data supplier ini?',
                        showCancelButton: true,
                        confirmButtonText: 'Ya',
                        cancelButtonText: `Tidak`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteData(response.id_supplier);
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
            url: "<?= site_url('supplier/delete/') ?>" + id,
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
</script>