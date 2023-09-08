<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .select-wrapper {
        position: relative;
    }

    .pelapis {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background-color: rgba(255, 255, 255, 0.5);
        z-index: 1;
        display: none;
    }

    .spinner {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Laporan Penjualan</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active">Laporan Penjualan</li>
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
                        <h3 class="card-title">Data Laporan Penjualan</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-export"><i class="fas fa-file-signature mr-1"></i> Export</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body tabelData">
                        <div class="row">
                            <div class="col-lg-9 col-md-8">

                            </div>
                            <div class="col-lg-3 col-md-4 form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input class="form-control form-control-sm float-right" id="reportrange">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="tabelsupplier" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Invoice</th>
                                        <th>Tanggal</th>
                                        <th>Kasir</th>
                                        <th>Pelanggan</th>
                                        <th>Jumlah Barang</th>
                                        <th>Uang Tunai</th>
                                        <th>Kembalian</th>
                                        <th>Total Harga</th>
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
                        <h3 class="card-title cardUbah-title">Tambah Data Penjualan</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-sm" id="btnBack"><i class="fas fa-arrow-circle-left"></i> Kembali</button>
                            <!-- <button type="button" class="btn btn-primary btn-sm" id="btnAddBarang"><i class="fas fa-plus-circle"></i> Tambah Barang</button> -->
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tabelbarang" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Kode Item</th>
                                        <th>Nama Item</th>
                                        <th>Kadaluarsa</th>
                                        <th>Kode Batch</th>
                                        <th>Jumlah</th>
                                        <th>Satuan</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>Total</th>
                                        <th id="total"></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <!-- <button class="btn btn-primary btn-sm float-right" id="save-transaksi" onclick="stoptransaksi()"><i class="fas fa-save"></i> Simpan Transaksi</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modalTambahBarang">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title modalTambahBarang-title">Tambah Barang / Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" id="formData">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="kode">Kode Item</label>
                                <div class="input-group">
                                    <input type="text" name="kode" id="kode" class="form-control" placeholder="Kode item / barang">
                                    <span class="input-group-append">
                                        <button type="button" class="btn bg-blue btn-cari" onclick="cari()"><i class="fas fa-search"></i></button>
                                    </span>
                                </div>
                                <span class="text-danger msgkode"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group select-wrapper">
                                <label for="nama">Nama Item</label>
                                <input type="text" name="nama" id="nama" class="form-control" readonly>
                                <input type="hidden" name="id_item" id="id_item">
                                <input type="text" name="id_transaksi" id="id_transaksi">
                                <input type="text" name="id_transaksi_d" id="id_transaksi_d">
                                <div class="pelapis">
                                    <div class="spinner"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group select-wrapper">
                                <label for="satuan">Satuan</label>
                                <input type="text" name="satuan" id="satuan" class="form-control" readonly>
                                <span class="text-danger msgsatuan"></span>
                                <div class="pelapis">
                                    <div class="spinner"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pabrik">Pabrik</label>
                                <input type="text" name="pabrik" id="pabrik" class="form-control" placeholder="Pabrik pembuat item">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="batch">No Batch</label>
                                <input type="text" name="batch" id="batch" class="form-control" placeholder="Nomor batch item">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="qty">Qty</label>
                                <input type="number" name="qty" id="qty" class="form-control" placeholder="Jumlah item" style="text-align:right;">
                                <span class="text-danger msgqty"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group select-wrapper">
                                <label for="harga">Harga Item</label>
                                <input type="text" name="harga" id="harga" class="form-control number-format" placeholder="Harga item" style="text-align:right;">
                                <div class="pelapis">
                                    <div class="spinner"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="total">Total Harga</label>
                                <input type="text" name="total" id="total_price" class="form-control number-format" placeholder="Total Harga Item" style="text-align:right;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="kadaluarsa">Tanggal Kadaluarsa</label>
                                <input type="date" name="kadaluarsa" id="kadaluarsa" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-save" style="width: 100%;" onclick="save()"><i class="fas fa-save"></i> Simpan Barang</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCariItem">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cari Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="filter row mb-3">
                    <div class="col-md-3">
                        <select id="ftipeModal" name="ftipeModal" class="form-control" style="width: 100%;">
                            <option value="">Pilih</option>
                            <option value="1">Semua Tipe Item</option>
                            <option value="obat">Obat</option>
                            <option value="alkes">Alat Kesehatan</option>
                            <option value="umum">Umum</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="fkategoriModal" name="fkategoriModal" class="form-control" style="width: 100%;">
                            <option value="">Pilih</option>
                            <option value="1">Semua Kategori Item</option>
                            <?php foreach ($kategori->result() as $key => $dt) { ?>
                                <option value="<?= $dt->id_category ?>"><?= $dt->name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <table class="table table-bordered table-hover" id="tabelCariItem" style="width: 100%;">
                    <thead>
                        <tr class="text-center">
                            <th>Kode Item</th>
                            <th>Nama Item</th>
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

<div class="modal fade" id="modalAkhiriTransaksi">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Simpan Transaksi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="formAkhiriTransaksi">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="no_faktur">No Faktur</label>
                            <input type="text" name="id_transaksi" id="id_transaksi">
                            <input type="text" name="no_faktur" id="no_faktur" class="form-control" placeholder="Nomor faktur transaksi">
                            <span class="text-danger msgno_faktur"></span>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="tgl">Tanggal</label>
                            <input type="date" name="tgl" id="tgl" class="form-control" placeholder="Tanggal transaksi / pembelian">
                            <span class="text-danger msgtgl"></span>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="supplier">Supplier</label>
                            <select name="supplier" id="supplier" class="form-control" style="width: 100%;">
                                <?php foreach ($supplier->result() as $key => $dt) { ?>
                                    <option value="<?= $dt->id_supplier ?>"><?= $dt->name ?></option>
                                <?php } ?>
                            </select>
                            <span class="text-danger msgsupplier"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="desc">Keterangan</label>
                        <textarea name="desc" id="desc" rows="2" class="form-control" placeholder="Keterangan transaksi pembelian"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-akhiri-transaksi" style="width: 100%;" onclick="akhiritransaksi()"><i class="fas fa-save"></i> Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-export">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Export Data Penjualan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <a href="<?= base_url('sales_report/exportpdf') ?>" target="_blank" class="btn btn-danger btn-sm" style="width: 100%;"><i class="fas fa-file-pdf mr-1"></i> PDF</a>
                    </div>
                    <div class="col-lg-6">
                        <a href="<?= base_url('sales_report/excel') ?>" class="btn btn-success btn-sm" style="width: 100%;"><i class="fas fa-file-excel mr-1"></i> Excel</a>
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

<script src="<?= base_url('assets') ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/inputmask/jquery.inputmask.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/daterangepicker/daterangepicker.js"></script>

<script>
    var table = $('#tabelsupplier')
    var tabletransaksilist = $('#tabelbarang')
    var formData = $('#formData')
    var index = $('.uiIndex')
    var form = $('.uiForm')
    var modal = $('#modalCariItem')
    var modalTambahBarang = $('#modalTambahBarang')
    var modalAkhiriTransaksi = $('#modalAkhiriTransaksi')
    var formAkhiriTransaksi = $('#formAkhiriTransaksi')
    var tableCariItem = $('#tabelCariItem')
    var id_transaksi = $('#id_transaksi').val()
    var saveData
    var saveTransaksi
    var fAwal
    var fAkhir
    var tipeModal = $('#ftipeModal').val()
    var kategoriModal = $('#fkategoriModal').val()
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

    function resetForm() {
        var id_transaksi = $('input#id_transaksi').val()
        var id_transaksi_d = $('input#id_transaksi_d').val()
        formData[0].reset()
        formAkhiriTransaksi[0].reset()
        $('input#id_transaksi').val(id_transaksi)
        $('input#id_transaksi_d').val(id_transaksi_d)
    }

    function resetValidation() {
        $('#kode').removeClass('is-invalid');
        $('.msgkode').html('');
        $('#qty').removeClass('is-invalid');
        $('.msgqty').html('');
        $('#no_faktur').removeClass('is-invalid');
        $('.msgno_faktur').html('');
        $('#tgl').removeClass('is-invalid');
        $('.msgtgl').html('');
        $('#supplier').removeClass('is-invalid');
        $('.msgsupplier').html('');
    }

    $(function() {
        $('#ftipeModal').select2({
            theme: 'bootstrap4',
            placeholder: "Filter Tipe Item"
        })
        $('#fkategoriModal').select2({
            theme: 'bootstrap4',
            placeholder: "Filter Kategori Item"
        })
        $('#supplier').select2({
            theme: 'bootstrap4',
            placeholder: "Pilih Supplier"
        })

        table.DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "ordering": false,
            "order": [],
            "ajax": {
                "url": "<?= site_url('sales_report/getStokIn') ?>",
                "type": "POST",
                "data": function(d) {
                    d.awal = fAwal
                    d.akhir = fAkhir
                    return d;
                }
            },
            "columnDefs": [{
                    "targets": [0, -1, -5],
                    "className": "text-center"
                },
                {
                    "targets": [-2, -3, -4],
                    "className": "text-right"
                },
                {
                    "targets": [0],
                    width: 6
                },
                {
                    "targets": [-5],
                    width: 6
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
                "lengthMenu": "Lihat _MENU_ data",
                "searchPlaceholder": "Nama item"
            }
        });
    });

    function transaksiList() {
        tabletransaksilist.DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "ordering": false,
            "bDestroy": true,
            "order": [],
            "ajax": {
                "url": "<?= site_url('sales_report/getTransaksiList') ?>",
                "type": "POST",
                "data": function(d) {
                    d.id_transaksi = id_transaksi;
                    d.status = 'out'
                    return d;
                }
            },
            "columnDefs": [{
                    "targets": [0, -1],
                    "className": "text-center"
                },
                {
                    "targets": [-1, -2],
                    "width": "15%"
                },
                {
                    "targets": [1, 3, 4],
                    "width": "10%"
                },
                {
                    "targets": [0],
                    "width": "1%"
                },
                {
                    "targets": [2],
                    "width": "15%"
                },
                {
                    "targets": [-2, -3, 5],
                    "className": "text-right"
                },
            ],
            "language": {
                "processing": '<i class="fa fa-spin fa-spinner"></i>',
                "emptyTable": "Tidak ada data",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ total data",
                "infoFiltered": "(terfilter dari _MAX_ total data)",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 total data",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "<i class='fa fa-caret-right'></i>",
                    "previous": "<i class='fa fa-caret-left'></i>"
                },
                "search": "Cari:",
                "lengthMenu": "Lihat _MENU_ data ",
                "searchPlaceholder": "Nama barang ..."
            },
            "footerCallback": function(row, data, start, end, display) {
                var totalHarga = 0;
                for (var i = 0; i < data.length; i++) {
                    var total = data[i][8];

                    totalHarga += parseInt(total.replace(/[^\d-]/g, ''));
                }
                // console.log(totalGapok);
                $('#total').text(number_format(totalHarga.toString(), 'Rp'));
            }
        })
    }

    $(document).on('click', '#btnAdd', function() {
        $.ajax({
            type: "POST",
            url: "<?= site_url('sales_report/getDraft') ?>",
            dataType: "JSON",

            beforeSend: function() {
                $('#btnAdd').attr('disabled', true);
                $('#btnAdd').html('<i class="fas fa-spin fa-circle-notch"></i> Tunggu...');
            },

            complete: function() {
                $('#btnAdd').attr('disabled', false);
                $('#btnAdd').html('<i class="fas fa-plus-circle"></i> Tambah Transaksi');
            },

            success: function(response) {
                saveData = 'add'
                modalTambahBarang.modal('show')
                $('input#id_transaksi').val(response)
                id_transaksi = response
                tabletransaksilist.DataTable().ajax.reload()
                $('#save-transaksi').html('<i class="fas fa-save"></i> Simpan Transaksi');
                saveTransaksi = 'tambah'
                index.hide()
                form.show()
                $('.modalTambahBarang-title').text('Tambah Barang / Item')
                $('.cardUbah-title').text('Tambah Data Pembelian')
            }
        })

    })

    $(document).on('click', '#btnBack', function() {
        index.show()
        form.hide()
    })

    $(document).on('click', '#btnAddBarang', function() {
        saveData = 'add'
        modalTambahBarang.modal('show')
        $('.btn-save').html('<i class="fas fa-save"></i> Simpan Barang')
        $('.modalTambahBarang-title').text('Tambah Barang / Item')
    })

    function save() {
        resetValidation()
        if (saveData == 'add') {
            url = "<?= site_url('sales_report/addBarang') ?>"
        } else if (saveData == 'edit') {
            url = "<?= site_url('sales_report/updateBarang') ?>"
        }

        $.ajax({
            type: "POST",
            url: url,
            data: formData.serialize(),
            dataType: "JSON",

            beforeSend: function() {
                $('.btn-save').attr('disabled', true);
                $('.btn-save').html('<i class="fas fa-spin fa-circle-notch"></i> Tunggu...');
            },

            complete: function() {
                $('.btn-save').attr('disabled', false);
                $('.btn-save').html('<i class="fas fa-save"></i> Simpan Barang');
            },

            success: function(response) {
                if (response.success) {
                    if (saveData == 'add') {
                        id_transaksi = response.id_trans
                        $('input#id_transaksi').val(response.id_trans)
                    } else {
                        modalTambahBarang.modal('hide')
                    }

                    Toast.fire({
                        icon: 'success',
                        title: response.success
                    })

                    tabletransaksilist.DataTable().ajax.reload()
                    resetForm()
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
                    if (response.error.kode) {
                        $('#kode').addClass('is-invalid');
                        $('.msgkode').html(response.error.kode);
                    } else {
                        $('#kode').removeClass('is-invalid');
                        $('.msgkode').html('');
                    }
                    if (response.error.qty) {
                        $('#qty').addClass('is-invalid');
                        $('.msgqty').html(response.error.qty);
                    } else {
                        $('#qty').removeClass('is-invalid');
                        $('.msgqty').html('');
                    }
                }
            }
        })
    }

    function stoptransaksi() {
        modalAkhiriTransaksi.modal('show');
    }

    function akhiritransaksi() {
        resetValidation()
        $.ajax({
            type: "POST",
            url: "<?= site_url('sales_report/akhiriTransaksi') ?>",
            data: formAkhiriTransaksi.serialize(),
            dataType: "JSON",

            beforeSend: function() {
                $('.btn-akhiri-transaksi').attr('disabled', true);
                $('.btn-akhiri-transaksi').html('<i class="fas fa-spin fa-circle-notch"></i> Tunggu...');
            },

            complete: function() {
                $('.btn-akhiri-transaksi').attr('disabled', false);
                $('.btn-akhiri-transaksi').html('<i class="fas fa-save"></i> Simpan Barang');
            },

            success: function(response) {
                if (response.kosong) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Transaksi Kosong',
                        text: response.kosong,
                    })
                    modalAkhiriTransaksi.modal('hide')
                }
                if (response.success) {
                    if (saveTransaksi == 'tambah') {
                        Toast.fire({
                            icon: 'success',
                            title: response.success
                        })
                    } else {
                        Toast.fire({
                            icon: 'success',
                            title: 'Data berhasil diupdate'
                        })
                    }
                    id_transaksi = null
                    $('input#id_transaksi').val(null)
                    tabletransaksilist.DataTable().ajax.reload()
                    reloadTable()
                    modalAkhiriTransaksi.modal('hide')
                    index.show()
                    form.hide()
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
                    if (response.error.no_faktur) {
                        $('#no_faktur').addClass('is-invalid');
                        $('.msgno_faktur').html(response.error.no_faktur);
                    } else {
                        $('#no_faktur').removeClass('is-invalid');
                        $('.msgno_faktur').html('');
                    }
                    if (response.error.tgl) {
                        $('#tgl').addClass('is-invalid');
                        $('.msgtgl').html(response.error.tgl);
                    } else {
                        $('#tgl').removeClass('is-invalid');
                        $('.msgtgl').html('');
                    }
                    if (response.error.supplier) {
                        $('#supplier').addClass('is-invalid');
                        $('.msgsupplier').html(response.error.supplier);
                    } else {
                        $('#supplier').removeClass('is-invalid');
                        $('.msgsupplier').html('');
                    }
                }
            }
        })
    }

    function byidBarang(id, type) {
        saveData = 'edit'
        $.ajax({
            type: "GET",
            url: "<?= site_url('sales_report/byidBarang/') ?>" + id,
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
                    $('#btnEdit' + id).html('<i class="fas fa-pen"></i> Edit');
                }
            },

            success: function(response) {
                if (type == 'edit') {
                    modalTambahBarang.modal('show')
                    $('#id_transaksi_d').val(response.id_transaksi_d)
                    $('#kode').val(response.item_code)
                    $('#nama').val(response.nama_item)
                    $('#satuan').val(response.nama_satuan)
                    $('#batch').val(response.batch)
                    $('#qty').val(response.qty)
                    $('#id_item').val(response.id_item)
                    $('#harga').val(number_format(response.price))
                    $('#total_price').val(number_format(response.total_price))
                    $('#kadaluarsa').val(response.expired)
                    $('.modalTambahBarang-title').text('Ubah Barang / Item')
                    $('.btn-save').html('<i class="fas fa-save"></i> Ubah Barang')
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Yakin hapus data ini?',
                        text: 'Menghapus data ini akan mempengaruhi jumlah stok barang!',
                        showCancelButton: true,
                        confirmButtonText: 'Ya',
                        cancelButtonText: `Tidak`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteDataBarang(response.id_transaksi_d);
                        } else if (result.isDismissed) {
                            $('#btnDelete' + id).attr('disabled', false);
                            $('#btnDelete' + id).html('<span class="fas fa-trash"></span> Hapus');
                        }
                    });
                }
            }
        })
    }

    function byid(id, type) {
        $.ajax({
            type: "GET",
            url: "<?= site_url('sales_report/byid/') ?>" + id,
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
                    $('#btnEdit' + id).html('<i class="fas fa-tasks"></i>');
                }
            },

            success: function(response) {
                if (type == 'edit') {
                    id_transaksi = response.id_transaksi
                    transaksiList()
                    $('input#id_transaksi').val(response.id_transaksi)
                    index.hide()
                    form.show()
                    $('#no_faktur').val(response.no_faktur);
                    $('#tgl').val(response.tgl);
                    $('#supplier').val(response.id_supplier).trigger('change');
                    $('#desc').val(response.description);
                    $('#save-transaksi').html('<i class="fas fa-save"></i> Ubah Transaksi');
                    $('.cardUbah-title').html('Manage Data Pembelian | <i>' + response.no_faktur + '</i>')
                    saveTransaksi = 'edit'
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Yakin hapus data ini?',
                        text: 'Menghapus data ini akan mempengaruhi jumlah stok barang!',
                        showCancelButton: true,
                        confirmButtonText: 'Ya',
                        cancelButtonText: `Tidak`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteData(response.id_transaksi);
                        } else if (result.isDismissed) {
                            $('#btnDelete' + id).attr('disabled', false);
                            $('#btnDelete' + id).html('<span class="fas fa-trash"></span>');
                        }
                    });
                }
            }
        })
    }

    function deleteData(id, qty) {
        $.ajax({
            type: "post",
            url: "<?= site_url('sales_report/delete/') ?>",
            data: {
                id_transaksi: id,
            },
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

    function deleteDataBarang(id) {
        $.ajax({
            type: "post",
            url: "<?= site_url('sales_report/deleteBarang/') ?>",
            data: {
                id_transaksi_d: id,
            },
            dataType: "json",
            success: function(response) {
                $('#btnDelete' + id).attr('disabled', false);
                $('#btnDelete' + id).html('<span class="fas fa-trash"></span>');
                if (response.success) {
                    reloadTable();
                    tabletransaksilist.DataTable().ajax.reload()
                    Toast.fire({
                        icon: 'success',
                        text: response.success,
                    })
                } else {
                    reloadTable();
                    tabletransaksilist.DataTable().ajax.reload()
                    Toast.fire({
                        icon: 'error',
                        text: response.error,
                    })
                }
            }
        })
    }


    function cari() {
        $('#ftipeModal').change(function() {
            tipeModal = $(this).val()
            if (tipeModal == 1) {
                $(this).val([]).trigger('change')
            }
            tableCariItem.DataTable().ajax.reload();
        })
        $('#fkategoriModal').change(function() {
            kategoriModal = $(this).val()
            if (kategoriModal == 1) {
                $(this).val([]).trigger('change')
            }
            tableCariItem.DataTable().ajax.reload();
        })
        modal.modal('show')
        // $("#tableModal").width($("#dataTable").width());
        // $("#tableModal").height($("#dataTable").height());
        tableCariItem.DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "ordering": false,
            "scrollY": '50vh',
            "scrollCollapse": true,
            "paging": false,
            "destroy": true,
            "order": [],
            "ajax": {
                "url": "<?= site_url('sales_report/getItem') ?>",
                "type": "POST"
            },
            "columnDefs": [{
                "targets": [0, -1],
                "className": "text-center"
            }],
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
                "searchPlaceholder": "Nama item"
            }
        })
        $('.dataTables_info').parent().removeClass('col-md-5')
    }

    function pilih(id_item, nama_item, kode_item) {
        $('#nama').val(nama_item)
        $('#kode').val(kode_item)
        $('#id_item').val(id_item)
        $.ajax({
            type: "POST",
            url: "<?= site_url('sales_report/pilihItem') ?>",
            data: {
                id_item: id_item,
            },
            dataType: "JSON",

            beforeSend: function() {
                $('#btnPilih' + id_item + '').attr('disabled', true);
                $('#btnPilih' + id_item + '').html('<i class="fas fa-spin fa-circle-notch"></i> Tunggu');
            },

            complete: function() {
                $('#btnPilih' + id_item + '').attr('disabled', false);
                $('#btnPilih' + id_item + '').html('<i class="fas fa-check-square"></i> Pilih');
            },

            success: function(response) {
                $('#satuan').val(response.satuan_name)
                $('#harga').val(number_format(response.harga))
                modal.modal('hide')
            }
        })
    }

    $('#kode').keyup(function() {
        var kode = $(this).val()
        $.ajax({
            type: "POST",
            url: "<?= site_url('sales_report/cariItem') ?>",
            data: {
                kode: kode,
            },
            dataType: "JSON",

            beforeSend: function() {
                $('.pelapis').show()
            },

            success: function(response) {
                $('.pelapis').hide()
                if (response != null) {
                    $('#satuan').val(response.satuan_name)
                    $('#harga').val(number_format(response.harga))
                    $('#nama').val(response.name)
                    $('#id_item').val(response.id_item)
                } else {
                    $('#satuan').val(null)
                    $('#harga').val(null)
                    $('#id_item').val(null)
                    $('#nama').val(null)
                }
            }
        })
    })

    $('#harga').keyup(function() {
        var rawHarga = $(this).val()
        var harga = rawHarga.replace(/\D/g, '');
        var qty = $('#qty').val()
        total = parseInt(harga) * parseInt(qty)
        $('#total_price').val(number_format(total.toString()))
    })

    $('#qty').keyup(function() {
        var rawHarga = $('#harga').val()
        var harga = rawHarga.replace(/\D/g, '');
        var qty = $(this).val()
        total = parseInt(harga) * parseInt(qty)
        $('#total_price').val(number_format(total.toString()))
    })

    $(document).on('keyup', '.number-format', function() {
        var me = $(this);
        var number = number_format(me.val());
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

    modal.on('shown.bs.modal', function(e) {
        $.fn.dataTable.tables({
            visible: true,
            api: true
        }).columns.adjust();
        $('.dataTables_info').parent().addClass('col-md-5')
        modalTambahBarang.modal('hide')
    });

    modalTambahBarang.on('hidden.bs.modal', function(e) {
        resetForm()
        resetValidation()
    })

    modal.on('hidden.bs.modal', function(e) {
        modalTambahBarang.modal('show')
    })

    modalAkhiriTransaksi.on('hidden.bs.modal', function(e) {
        resetForm()
        resetValidation()
    })

    $(function() {
        var start = moment().add(0, 'day');
        var end = moment();

        function cb(start, end) {
            $('#reportrange span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
        }

        $('input#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Hari ini': [moment(), moment()],
                'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 Hari yang lalu': [moment().subtract(6, 'days'), moment()],
                '30 Hari yang lalu': [moment().subtract(29, 'days'), moment()],
                'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
                'Bulan lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            }
        }, cb);
    })

    $('#reportrange').change(function() {
        var isi = $(this).val()

        let [start, end] = isi.split(' - ');

        fAwal = start;
        fAkhir = end;

        table.DataTable().ajax.reload()
    })
</script>