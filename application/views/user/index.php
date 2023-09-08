<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Pengaturan Pengguna</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pengaturan Pengguna</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Pengguna</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-sm" onclick="add()" id="btnAdd">Tambah data</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="back()" style="display: none;" id="btnBack">Kembali</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body tabelData">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="tabeluser" style="width: 100%;">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>Foto Profil</th>
                                        <th>Nama</th>
                                        <th>Role</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-body form" style="display: none;">
                        <form action="#" id="formData" enctype="multipart/form-data">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nama">Nama Lengkap</label>
                                    <input type="text" name="nama" id="nama" class="form-control">
                                    <input type="hidden" name="id" id="id" class="form-control">
                                    <span class="text-danger msgnama"></span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" id="username" class="form-control">
                                    <span class="text-danger msgusername"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" id="password" class="form-control">
                                    <span class="text-danger msgpassword"></span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="konfpass">Konfirmasi Password</label>
                                    <input type="password" name="konfpass" id="konfpass" class="form-control">
                                    <span class="text-danger msgkonfpass"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <input type="text" name="alamat" id="alamat" class="form-control">
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <div style="margin-bottom: 7px;" id="showImg">
                                        <img style="width: 19vw;" id="img">
                                    </div>
                                    <label for="img">Foto</label>
                                    <input type="file" name="img" id="img" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <div style="margin-bottom: 7px;" id="showImg">&nbsp;
                                    </div>
                                    <label for="level">Role</label>
                                    <select name="level" id="level" class="form-control">
                                        <option value="1">Admin</option>
                                        <option value="2">Karyawan</option>
                                    </select>
                                    <span class="text-danger msglevel"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col text-right">
                                    <button type="button" class="btn btn-primary pull-right" id="btnSave" onclick="save()">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    var saveData;
    var table = $('#tabeluser');
    var form = $('.form')
    var formData = $('#formData');
    var tabelData = $('.tabelData');
    var btnAdd = $('#btnAdd');
    var btnBack = $('#btnBack');
    var btnSave = $('#btnSave');

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

    function reloadAll() {
        formData[0].reset();
        $("#img").removeAttr("src", "src");
        $('#nama').removeClass('is-invalid');
        $('.msgnama').html('');
        $('#username').removeClass('is-invalid');
        $('.msgusername').html('');
        $('#password').removeClass('is-invalid');
        $('.msgpassword').html('');
        $('#konfpass').removeClass('is-invalid');
        $('.msgkonfpass').html('');
        $("#level option").removeAttr("selected", "selected");
        $('#showImg').addClass('hidden');
    }

    $(function() {
        $('#tabeluser').DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= site_url('user/getUser') ?>",
                "type": "POST"
            },
            "columnDefs": [{
                    "targets": [0, 1, 3, 4],
                    "orderable": false,
                    "className": "text-center"
                },
                {
                    "targets": [0],
                    width: 6
                },
                {
                    "targets": [1, 3],
                    "className": "text-center"
                }
            ],
            "language": {
                "processing": '<i class="fa fa-spin fa-spinner"></i> Tunggu',
                // "emptyTable": "Tidak ada data materi ujian",
                // "zeroRecords": "Tidak ada data yang sesuai"
            }
        });

    });

    function add() {
        reloadAll();
        saveData = 'add'
        form.show();
        tabelData.hide();
        btnAdd.hide();
        btnBack.show();
        $('.card-title').text('Tambah Data User');

    }

    function back() {
        form.hide();
        tabelData.show();
        btnAdd.show();
        btnBack.hide();
        $('.card-title').text('Data User')
    }

    function save() {
        var a = $('form')[0];
        var formData = new FormData(a);
        console.log(formData)
        if (saveData == 'add') {
            url = "<?= site_url('user/add') ?>"
        } else if (saveData == 'edit') {
            url = "<?= site_url('user/update') ?>"
        }

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
            processData: false,
            dataType: "JSON",

            beforeSend: function() {
                btnSave.attr('disabled', true);
                btnSave.html('<i class="fa fa-spin fa-spinner"></i>');
            },

            complete: function() {
                btnSave.attr('disabled', false);
                btnSave.text('Simpan');
            },

            success: function(response) {
                if (response.success) {
                    Toast.fire({
                        icon: 'success',
                        title: response.success
                    })
                    form.hide();
                    btnAdd.show();
                    btnBack.hide();
                    tabelData.show();
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
                    if (response.error.username) {
                        $('#username').addClass('is-invalid');
                        $('.msgusername').html(response.error.username);
                    } else {
                        $('#username').removeClass('is-invalid');
                        $('.msgusername').html('');
                    }
                    if (response.error.password) {
                        $('#password').addClass('is-invalid');
                        $('.msgpassword').html(response.error.password);
                    } else {
                        $('#password').removeClass('is-invalid');
                        $('.msgpassword').html('');
                    }
                    if (response.error.konfpass) {
                        $('#konfpass').addClass('is-invalid');
                        $('.msgkonfpass').html(response.error.konfpass);
                    } else {
                        $('#konfpass').removeClass('is-invalid');
                        $('.msgkonfpass').html('');
                    }
                }
            }
        });
    }

    function byid(id, type) {
        reloadAll();

        if (type == 'edit') {
            saveData = 'edit';
        }

        $.ajax({
            type: "GET",
            url: "<?= site_url('user/byid/') ?>" + id,
            dataType: "JSON",

            beforeSend: function() {
                if (type == 'edit') {
                    $('#btnEdit' + id).attr('disabled', true);
                    $('#btnEdit' + id).html('<i class="fa fa-spin fa-spinner"></i>');
                }
                if (type == 'delete') {
                    $('#btnDelete' + id).attr('disabled', true);
                    $('#btnDelete' + id).html('<span class="fa fa-spin fa-spinner"></span>');
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
                    var src = "<?= base_url('assets/img/users/') ?>" + response.avatar;
                    form.show();
                    tabelData.hide();
                    btnAdd.hide();
                    btnBack.show();
                    if (response.avatar != null) {
                        $('#showImg').removeClass('hidden');
                        $("#img").attr("src", src);
                    }
                    $('.title').text('Update Data User');
                    $('#id').val(response.user_id);
                    $('#nama').val(response.name);
                    $('#username').val(response.username);
                    $('#alamat').val(response.address);
                    $("#level option[value=" + '"' + response.level + '"' + "]").attr("selected", "selected");
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Yakin hapus data ini?',
                        showCancelButton: true,
                        confirmButtonText: 'Ya',
                        cancelButtonText: `Tidak`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteData(response.user_id);
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
            url: "<?= site_url('user/delete/') ?>" + id,
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