<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Stok Opname</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active">Stok Opname</li>
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
                        <h3 class="card-title">Stok Opname</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body tabelData">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="tabelsupplier" style="width: 100%;">
                                <thead>
                                    <tr class="text-center">
                                        <th>Kode Item</th>
                                        <th>Nama Item</th>
                                        <th>Stok Total</th>
                                        <th>Lokasi Item</th>
                                        <th>Terakhir di Opname</th>
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
                        <h3 class="card-title opname-title">Stock Opname</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-sm" id="btnBack"><i class="fas fa-arrow-circle-left"></i> Kembali</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="#" id="formData">
                            <table class="table table-striped" id="tabelopname" style="width: 100%;">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>Kadaluarsa</th>
                                        <th>Kode Batch</th>
                                        <th>Harga</th>
                                        <th>Stok Sistem</th>
                                        <th>Stok Real</th>
                                        <th>Penyesuaian</th>
                                        <th>Catatan</th>
                                        <th>Verifikasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
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
    var tabelopname = $('#tabelopname')
    var formData = $('#formData')
    var index = $('.uiIndex')
    var form = $('.uiForm')
    var satuan = $("#satuan option:selected").text();
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
        $('.append-form').remove()
        $("#satuan").val([]).trigger("change");
        $("#kategori").val([]).trigger("change");
        $("#tipe").val([]).trigger("change");
        $("#satuanUtama").val([]).trigger("change");
        formData[0].reset()
    }

    function reloadValidate() {
        $('#nama').removeClass('is-invalid');
        $('.msgnama').html('');
        $('#tipe').removeClass('is-invalid');
        $('.msgtipe').html('');
        $('#kategori').removeClass('is-invalid');
        $('.msgkategori').html('');
        $('#kode').removeClass('is-invalid');
        $('.msgkode').html('');
        $('#hrg_beli').removeClass('is-invalid');
        $('.msghrg_beli').html('');
        $('#hrg_jual').removeClass('is-invalid');
        $('.msghrg_jual').html('');
    }

    $(document).ready(function() {
        //Initialize Select2 Elements
        $('#tipe').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Tipe Item'
        })
        $('#kategori').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Kategori Item'
        })
        $('#satuanUtama').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Satuan'
        })

        table.DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "ordering": false,
            "order": [],
            "ajax": {
                "url": "<?= site_url('opname/getIndex') ?>",
                "type": "POST",
            },
            "columnDefs": [{
                    "targets": [0, -1],
                    "className": "text-center"
                },
                {
                    "targets": [-2],
                    "className": "text-right"
                },
                {
                    "targets": [0],
                    "width": "24%"
                },
            ],
            "language": {
                "processing": '<i class="fas fa-spin fa-circle-notch"></i> Tunggu',
                "emptyTable": "Tidak ada data",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ total data",
                "infoFiltered": "(terfilter dari _MAX_ total data)",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 total data",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "<i class='fas fa-caret-right'></i>",
                    "previous": "<i class='fas fa-caret-left'></i>"
                },
                "search": "Cari:",
                "lengthMenu": "Lihat _MENU_ data ",
                "searchPlaceholder": "Nama item, kode item"
            }
        })
    });

    $(document).on('click', '#btnAdd', function() {
        resetAll()
        saveData = 'add'
        index.hide()
        form.show()
    })
    $(document).on('click', '#btnBack', function() {
        reloadValidate()
        index.show()
        form.hide()
    })

    function opname(id, nama_brg) {
        var id_item = id
        $('.opname-title').html('Stok Opname | <i>' + nama_brg + '</i>')
        tabelopname.DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "paging": false,
            "bDestroy": true,
            "info": false,
            "ordering": false,
            "order": [],
            "ajax": {
                "url": "<?= site_url('opname/getOpnameItem') ?>",
                "type": "POST",
                "data": function(d) {
                    d.id_item = id_item
                    return d
                }
            },
            "columnDefs": [{
                    "targets": '_all',
                    "className": "text-center"
                },
                {
                    "targets": [5],
                    "width": "8%"
                },
                {
                    "targets": [0],
                    "width": "1%"
                },
            ],
            "language": {
                "processing": '<i class="fas fa-spin fa-circle-notch"></i> Tunggu',
                "emptyTable": "Tidak ada data",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ total data",
                "infoFiltered": "(terfilter dari _MAX_ total data)",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 total data",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "<i class='fas fa-caret-right'></i>",
                    "previous": "<i class='fas fa-caret-left'></i>"
                },
                "search": "Cari:",
                "lengthMenu": "Lihat _MENU_ data ",
                "searchPlaceholder": "Nama item, kode item"
            }
        })
        index.hide()
        form.show()
    }

    function getSisa(stok, id_transaksi_d, satuan) {
        stok_real = $('#stok_real' + id_transaksi_d).val()
        sisa = stok_real - stok
        console.log(sisa)
        $('#sisa' + id_transaksi_d).val(sisa)
        $('#sisaText' + id_transaksi_d).text(sisa + ' ' + satuan)
    }

    function save() {
        if ($('input[type="checkbox"]').is(":not(:checked)")) {
            alert('Verf terlebih dahulu')
        } else {
            $.ajax({
                type: "POST",
                url: "<?= site_url('opname/save') ?>",
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
                        if (response.error.tipe) {
                            $('#tipe').addClass('is-invalid');
                            $('.msgtipe').html(response.error.tipe);
                        } else {
                            $('#tipe').removeClass('is-invalid');
                            $('.msgtipe').html('');
                        }
                        if (response.error.kategori) {
                            $('#kategori').addClass('is-invalid');
                            $('.msgkategori').html(response.error.kategori);
                        } else {
                            $('#kategori').removeClass('is-invalid');
                            $('.msgkategori').html('');
                        }
                        if (response.error.kode) {
                            $('#kode').addClass('is-invalid');
                            $('.msgkode').html(response.error.kode);
                        } else {
                            $('#kode').removeClass('is-invalid');
                            $('.msgkode').html('');
                        }
                        if (response.error.hrg_beli) {
                            $('#hrg_beli').addClass('is-invalid');
                            $('.msghrg_beli').html(response.error.hrg_beli);
                        } else {
                            $('#hrg_beli').removeClass('is-invalid');
                            $('.msghrg_beli').html('');
                        }
                        if (response.error.hrg_jual) {
                            $('#hrg_jual').addClass('is-invalid');
                            $('.msghrg_jual').html(response.error.hrg_jual);
                        } else {
                            $('#hrg_jual').removeClass('is-invalid');
                            $('.msghrg_jual').html('');
                        }
                        if (response.error.satuan) {
                            $('#satuanUtama').addClass('is-invalid');
                            $('.msgsatuan').html(response.error.satuan);
                        } else {
                            $('#satuanUtama').removeClass('is-invalid');
                            $('.msgsatuan').html('');
                        }


                    }
                }
            })
        }
    }

    function byid(id, type) {
        resetAll()
        if (type == 'edit') {
            saveData = 'edit';
        }

        $.ajax({
            type: "GET",
            url: "<?= site_url('item/byid/') ?>" + id,
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
                    $('.add-satuan').hide()
                    $('.title').text('Edit Data Item');
                    $('#id_item').val(response.id_item);
                    $('#nama').val(response.name);
                    $('#kode').val(response.item_code);
                    $('#min_stok').val(response.min_stok);
                    $('#tipe').val(response.item_type).trigger('change')
                    $('#satuanUtama').val(response.id_satuan).trigger('change')
                    $('#kategori').val(response.id_category).trigger('change')
                    $('#hrg_jual').val(number_format(response.selling_price, 'Rp'));
                    $('#hrg_beli').val(number_format(response.purchase_price, 'Rp'));
                    $('#lokasi').val(response.location);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Yakin hapus data item ini?',
                        showCancelButton: true,
                        confirmButtonText: 'Ya',
                        cancelButtonText: `Tidak`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteData(response[0].id_item);
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
            url: "<?= site_url('item/delete/') ?>" + id,
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

    function generate() {
        let tp
        tipe = $("#tipe").val();
        console.log(tipe)
        if (tipe == '') {
            Toast.fire({
                icon: 'warning',
                text: 'Pilih terlebih dahulu tipe itemnya!',
            })
        } else {
            if (tipe == 'obat') {
                tp = 'OB'
            } else if (tipe == 'alkes') {
                tp = 'AK'
            } else if (tipe == 'umum') {
                tp = 'UM'
            }

            id = makeid(5)
            $('#kode').val(tp + '-' + id)
        }
    }

    $(document).on('keyup', '.number-format', function() {
        var me = $(this);
        var number = number_format(me.val(), 'Rp');
        me.val(number);
    })

    function number_format(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1].substr(0, 2) : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    function makeid(length) {
        let result = '';
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        const charactersLength = characters.length;
        let counter = 0;
        while (counter < length) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
            counter += 1;
        }
        return result;
    }
</script>