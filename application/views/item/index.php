<!-- Content Header (Page header) -->
<style>
    @media print {
        body {
            visibility: hidden;
        }

        svg {
            visibility: visible;
            width: 100%;
            height: 100%;
            max-width: 100%;
            max-height: 100%;
        }
    }
</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Item</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active">Item</li>
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
                        <h3 class="card-title">Data Item</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-export"><i class="fas fa-file-signature mr-1"></i> Export</button>
                            <button type="button" class="btn btn-success btn-sm" onclick="importData()"><i class="fas fa-file-excel mr-1"></i> Import Excel</button>
                            <button type="button" class="btn btn-primary btn-sm" id="btnAdd"><i class="fas fa-plus-circle mr-1"></i> Tambah data</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body tabelData">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <select name="fTipe" id="fTipe" class="form-control">
                                        <option value=""></option>
                                        <option value="xx">Semua Tipe Item</option>
                                        <?php foreach ($tipe->result() as $key => $dt) { ?>
                                            <option value="<?= $dt->id_type ?>"><?= $dt->name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <select name="fKategori" id="fKategori" class="form-control">
                                        <option value=""></option>
                                        <option value="xx">Semua Kategori Item</option>
                                        <?php foreach ($kategori->result() as $key => $dt) { ?>
                                            <option value="<?= $dt->id_category ?>"><?= $dt->name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <select name="fLokasi" id="fLokasi" class="form-control">
                                        <option value=""></option>
                                        <option value="xx">Semua Lokasi Item</option>
                                        <?php foreach ($lokasi->result() as $key => $dt) { ?>
                                            <option value="<?= $dt->id_location ?>"><?= $dt->name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="tabelsupplier" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Nama Item</th>
                                        <th>Barcode Item</th>
                                        <th>Kode Item</th>
                                        <th>Satuan</th>
                                        <th>Harga Beli</th>
                                        <th>Harga Jual</th>
                                        <th>Lokasi Item</th>
                                        <th class="text-center">Aksi</th>
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
                        <h3 class="card-title">Tambah Data Item</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-sm" id="btnBack"><i class="fas fa-arrow-circle-left"></i> Kembali</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="#" id="formData">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="tipe">Tipe Item</label>
                                    <select class="form-control" name="tipe" id="tipe" style="width: 100%;">
                                        <option value=""></option>
                                        <?php foreach ($tipe->result() as $key => $dt) { ?>
                                            <option value="<?= $dt->id_type ?>"><?= $dt->name ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger msgtipe"></span>
                                    <input type="hidden" name="id_item" id="id_item">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="nama">Nama Item</label>
                                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama item / barang">
                                    <span class="text-danger msgnama"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="kategori">Kategori Item</label>
                                    <select class="form-control" name="kategori" id="kategori" style="width: 100%;">
                                        <option value=""></option>
                                        <?php foreach ($kategori->result() as $key => $dt) { ?>
                                            <option value="<?= $dt->id_category ?>"><?= $dt->name ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger msgkategori"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="kode">Kode Item</label>
                                    <div class="input-group">
                                        <input type="text" name="kode" id="kode" class="form-control" placeholder="Kode item / barang">
                                        <span class="input-group-append">
                                            <button type="button" class="btn bg-blue" onclick="generate()"><i class="fas fa-random"></i></button>
                                        </span>
                                    </div>
                                    <span class="text-danger msgkode"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="barcode">Barcode Item</label>
                                    <div class="input-group">
                                        <input class="form-control" name="barcode" id="barcode" placeholder="Barcode item">
                                        <span class="input-group-append">
                                            <button type="button" class="btn bg-blue" onclick="window.print()"><i class="fas fa-print"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-8 align-self-center">
                                    <svg id="barcode-img" style="height: 50;"></svg>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="hrg_beli">Harga Beli</label>
                                        <input type="text" name="hrg_beli" id="hrg_beli" class="form-control number-format" placeholder="Harga beli item / barang">
                                        <span class="text-danger msghrg_beli"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="hrg_jual">Harga Jual</label>
                                        <input type="text" name="hrg_jual" id="hrg_jual" class="form-control number-format" placeholder="Harga jual item / barang">
                                        <span class="text-danger msghrg_jual"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 append-satuan">
                                    <div class="form-group">
                                        <label for="satuan">Satuan</label>
                                        <select name="satuan" id="satuanUtama" class="form-control">
                                            <option value=""></option>
                                            <?php foreach ($satuan->result() as $key => $dt) { ?>
                                                <option value="<?= $dt->id_satuan ?>"><?= $dt->name ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger msgsatuan"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="min_stok">Minimal Stok</label>
                                        <input type="number" name="min_stok" id="min_stok" class="form-control" placeholder="Minimal stok yang ada pada item / barang">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="lokasi">Lokasi</label>
                                        <select name="lokasi" id="lokasi" class="form-control">
                                            <option value=""></option>
                                            <?php foreach ($lokasi->result() as $key => $dt) { ?>
                                                <option value="<?= $dt->id_location ?>"><?= $dt->name ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
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

<div class="modal fade" id="modalImport">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Import Data Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" id="formImport">
                    <div class="form-group">
                        <label for="excel">File Excel</label>
                        <input type="file" name="excel" id="excel" class="form-control">
                    </div>
                    <a href="<?= site_url('item/downloadFormat'); ?>" class="btn btn-sm btn-primary"><i class="fas fa-download"></i> Download Format</a>
                </form>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success" name="import" id="btnImport" onclick="preview()">Preview</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modalPreview">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Import Data Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" style="width: 100%;" id="tablepreview">
                        <thead>
                            <tr class="text-center">
                                <th class="text-center">No.</th>
                                <th class="text-center">Nama Item</th>
                                <th class="text-center">Tipe Item</th>
                                <th class="text-center">Kode Item</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center">Harga Jual</th>
                                <th class="text-center">Harga Beli</th>
                                <th class="text-center">Stok Minimal</th>
                                <th class="text-center">Lokasi Item</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="btnSavePreview" onclick="savePreview()">Simpan</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-export">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Export Data Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <a href="<?=base_url('item/exportpdf')?>" target="_blank" class="btn btn-danger btn-sm" style="width: 100%;"><i class="fas fa-file-pdf mr-1"></i> PDF</a>
                    </div>
                    <div class="col-lg-6">
                        <a href="<?=base_url('item/excel')?>" class="btn btn-success btn-sm" style="width: 100%;"><i class="fas fa-file-excel mr-1"></i> Excel</a>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    var table = $('#tabelsupplier')
    var tablePreview = $('#tablepreview')
    var formData = $('#formData')
    var index = $('.uiIndex')
    var form = $('.uiForm')
    var satuan = $("#satuan option:selected").text();
    var saveData

    var fTipe = $('#fTipe').val()
    var fKategori = $('#fKategori').val()
    var fLokasi = $('#fLokasi').val()
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
        table.DataTable().ajax.reload(function() {
            JsBarcode(".barcode").init();
        }, false);
    }

    function resetAll() {
        $('.append-form').remove()
        $("#satuan").val([]).trigger("change");
        $("#kategori").val([]).trigger("change");
        $("#tipe").val([]).trigger("change");
        $("#lokasi").val([]).trigger("change");
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
        $('#satuanUtama').removeClass('is-invalid');
        $('.msgsatuan').html('');
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
        $('#lokasi').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih Lokasi Item'
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
                "url": "<?= site_url('item/getItem') ?>",
                "type": "POST",
                "data": function(d) {
                    d.tipe = fTipe
                    d.kategori = fKategori
                    d.lokasi = fLokasi
                    return d
                }
            },
            "columnDefs": [{
                    "targets": [0, 2, -1],
                    "className": "text-center"
                },
                {
                    "targets": [-4, -3],
                    "className": "text-right"
                },
                {
                    "targets": [0],
                    width: 6
                },
            ],
            "language": {
                "processing": '<i class="fas fa-spin fa-circle-notch"></i> Tunggu',
                "emptyTable": "Tidak ada data item",
                "zeroRecords": "Data item tidak ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ total item",
                "infoFiltered": "(terfilter dari _MAX_ total item)",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 total item",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "<i class='fas fa-caret-right'></i>",
                    "previous": "<i class='fas fa-caret-left'></i>"
                },
                "search": "Cari:",
                "lengthMenu": "Lihat _MENU_ data item",
                "searchPlaceholder": "Nama item, kode item"
            },
            "drawCallback": function(settings, json) {
                JsBarcode(".barcode").init();
            }
        });

    });

    $(document).on('click', '#btnAdd', function() {
        resetAll()
        saveData = 'add'
        $('#barcode-img').hide()
        index.hide()
        form.show()
    })
    $(document).on('click', '#btnBack', function() {
        reloadValidate()
        index.show()
        form.hide()
    })

    function save() {
        reloadValidate()
        if (saveData == 'add') {
            url = "<?= site_url('item/add') ?>"
        } else if (saveData == 'edit') {
            url = "<?= site_url('item/update') ?>"
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

    function byid(id, type) {
        resetAll()
        if (type == 'edit') {
            saveData = 'edit';
        }

        $.ajax({
            type: "POST",
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
                    $('#barcode').val(response.barcode);
                    $('#tipe').val(response.item_type).trigger('change')
                    $('#satuanUtama').val(response.id_satuan).trigger('change')
                    $('#kategori').val(response.id_category).trigger('change')
                    $('#lokasi').val(response.id_location).trigger('change')
                    $('#hrg_jual').val(number_format(response.selling_price, 'Rp'));
                    $('#hrg_beli').val(number_format(response.purchase_price, 'Rp'));
                    if (response.barcode != null) {
                        $('#barcode-img').show()
                        JsBarcode("#barcode-img", response.barcode, {
                            format: "CODE128",
                            displayValue: false,
                            height: 50
                        });
                    } else {
                        $('#barcode-img').hide()
                    }
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Yakin hapus data item ini?',
                        showCancelButton: true,
                        confirmButtonText: 'Ya',
                        cancelButtonText: `Tidak`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteData(response.id_item);
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
            if (tipe == '1') {
                tp = 'OB'
            } else if (tipe == '2') {
                tp = 'AK'
            } else if (tipe == '3') {
                tp = 'UM'
            } else {
                tp = 'XW'
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

    $(document).ready(function() {
        $('#fTipe').select2({
            theme: 'bootstrap4',
            placeholder: 'Filter Tipe Item'
        })
        $('#fKategori').select2({
            theme: 'bootstrap4',
            placeholder: 'Filter Kategori Item'
        })
        $('#fLokasi').select2({
            theme: 'bootstrap4',
            placeholder: 'Filter Lokasi Item'
        })

        $('#fTipe').change(function() {
            val = $(this).val()

            fTipe = val
            reloadTable()
        })
        $('#fKategori').change(function() {
            val = $(this).val()

            fKategori = val
            reloadTable()
        })
        $('#fLokasi').change(function() {
            val = $(this).val()

            fLokasi = val
            reloadTable()
        })
    })

    function importData() {
        $('#modalImport').modal('show');
    }

    function preview() {
        var form = $('#formImport')[0];
        var dataImport = new FormData(form);
        $.ajax({
            type: "POST",
            url: "<?= site_url('item/import') ?>",
            data: dataImport,
            contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
            processData: false,
            dataType: "JSON",

            beforeSend: function() {
                $('#btnImport').attr('disabled', true);
                $('#btnImport').html('<span class="fa fa-spin fa-spinner"></span> Preview');
            },

            complete: function() {
                $('#btnImport').attr('disabled', false);
                $('#btnImport').html('<span class="fa fa-save"></span> Preview');
            },

            success: function(response) {
                if (response.success) {
                    Toast.fire({
                        icon: 'success',
                        text: response.success,
                    })
                    $('#modalPreview').modal('show')
                    $('#modalImport').modal('hide')
                }
            }
        })
    }

    $("#modalPreview").on("shown.bs.modal", function() {
        tablePreview.DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "destroy": true,
            "paging": false,
            "ordering": false,
            "searching": false,
            "info": false,
            "order": [],
            "ajax": {
                "url": "<?= site_url('item/preview') ?>",
                "type": "POST",
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
                "processing": '<i class="fa fa-spin fa-refresh"></i>',
            }
        });

    });

    function byidPreview(id, type) {
        if (type == 'edit') {
            saveData = 'editPreview';
        }
        $.ajax({
            type: "POST",
            url: "<?= site_url('item/byidPreview/') ?>" + id,
            data: {
                id: id
            },
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

                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Yakin hapus data item ini?',
                        showCancelButton: true,
                        confirmButtonText: 'Ya',
                        cancelButtonText: `Tidak`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteDataPreview(response.no);
                        } else if (result.isDismissed) {
                            $('#btnDelete' + id).attr('disabled', false);
                            $('#btnDelete' + id).html('<span class="fas fa-trash"></span>');
                        }
                    });
                }
            }
        })
    }

    function deleteDataPreview(id) {
        $.ajax({
            type: "post",
            url: "<?= site_url('item/deletePreview/') ?>" + id,
            dataType: "json",
            success: function(response) {
                tablePreview.DataTable().ajax.reload();
                $('#btnDelete' + id).attr('disabled', false);
                $('#btnDelete' + id).html('<span class="fas fa-trash"></span>');
                if (response.success) {
                    Toast.fire({
                        icon: 'success',
                        text: response.success,
                    })
                }
            }
        })
    }

    function savePreview() {
        $.ajax({
            type: "POST",
            url: "<?= site_url('item/savePreview') ?>",
            dataType: "JSON",

            beforeSend: function() {
                $('#btnSavePreview').attr('disabled', true);
                $('#btnSavePreview').html('<span class="fa fa-spin fa-spinner"></span> Simpan');
            },

            complete: function() {
                $('#btnSavePreview').attr('disabled', false);
                $('#btnSavePreview').html('<span class="fa fa-save"></span> Simpan');
            },

            success: function(response) {
                if (response.success) {
                    Toast.fire({
                        icon: 'success',
                        text: response.success,
                    })
                    $('#modalPreview').modal('hide')
                    reloadTable()
                }

                if (response.error) {
                    Toast.fire({
                        icon: 'success',
                        text: response.error,
                    })
                    $('#modalPreview').modal('hide')
                }
            }
        })
    }

    $('#barcode').keyup(function() {
        var isi = $(this).val()
        if (isi == '') {
            $('#barcode-img').hide()
        } else {
            JsBarcode("#barcode-img", isi, {
                format: "CODE128",
                displayValue: false,
                height: 50
            });
        }
    })
</script>