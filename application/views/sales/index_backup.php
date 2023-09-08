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
                <h4>Kasir</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active">Kasir</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content uiIndex">
    <form action="#" id="formAddItem">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-outline card-primary">
                        <div class="card-body">
                            <div class="filter">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select name="kategoriF" id="kategoriF" class="form-control" style="width: 100%;">
                                                <option value=""></option>
                                                <option value="xx">Semua Kategori</option>
                                                <?php foreach ($kategori->result() as $key => $dt) { ?>
                                                    <option value="<?= $dt->id_category ?>"><?= $dt->name ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select name="tipeF" id="tipeF" class="form-control" style="width: 100%;">
                                                <option value=""></option>
                                                <option value="xx">Semua Tipe</option>
                                                <option value=""></option>
                                                <?php foreach ($tipe->result() as $key => $dt) { ?>
                                                    <option value="<?= $dt->id_type ?>"><?= $dt->name ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped" id="tabelCariItem" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Kode Item</th>
                                            <th>Nama Item</th>
                                            <th>Stok</th>
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
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-outline card-primary">
                                <div class="card-body">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="vertical-align: top;">
                                                <label for="date">Tanggal</label>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="date" name="date" id="date" value="<?= date('Y-m-d') ?>" class="form-control form-control-sm">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align: top;">
                                                <label for="kasir">Kasir</label>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" name="kasir" id="kasir" value="<?= $this->fungsi->user_login()->name ?>" class="form-control form-control-sm" readonly>
                                                    <input type="hidden" name="user_id" id="user_id" value="<?= $this->fungsi->user_login()->user_id ?>" class="form-control form-control-sm" readonly>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align: top;">
                                                <label for="customer">Pelanggan</label>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <select name="customer" id="customer" class="form-control form-control-sm" style="width: 100%;">
                                                        <option value="0">Umum</option>
                                                        <?php foreach ($customer->result() as $key => $dt) { ?>
                                                            <option value="<?= $dt->id_customer ?>"><?= $dt->name ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-outline card-primary">
                                <div class="card-body">
                                    <div align="right">
                                        <h5>Invoice <b><span id="no_invoice"><?= $invoice ?></span></b></h5>
                                        <h1><b><span id="grand_total" style="font-size: 35pt;">0</span></b></h1>
                                        <input type="hidden" name="invoice" id="invoice" value="<?= $invoice ?>">
                                    </div>
                                    <hr>
                                    <table style="width: 100%;">
                                        <tr class="text-center">
                                            <td style="vertical-align: top;">
                                                <button type="button" class="btn btn-sm btn-warning btn-cancel" onclick="cancel()" style="width: 90%;"><i class="fas fa-times"></i> Cancel</button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-success btn-process" onclick="bayar()" style="width: 90%;"><i class="fas fa-cash-register"></i> Process</button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="tableKeranjang" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th>Kode Item</th>
                                                    <th>Nama Item</th>
                                                    <th>Harga</th>
                                                    <th>Jumlah</th>
                                                    <th>Total</th>
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
            </div>
        </div>
    </form>
</section>

<div class="modal fade" id="modaleditKeranjang">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title modaleditKeranjang-title">Tambah Barang / Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="formEditFromBasket" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="kode">Kode Item</label>
                                <input type="text" name="kode" id="kode" class="form-control" placeholder="Kode item / barang" readonly>
                                <span class="text-danger msgkode"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nama">Nama Item</label>
                                <input type="text" name="nama" id="nama" class="form-control" readonly>
                                <input type="hidden" name="id_item" id="id_item">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="stok">Stok</label>
                                <input type="text" name="stok" id="stok" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="satuan">Satuan</label>
                                <input type="text" name="satuan" id="satuan" class="form-control" readonly>
                                <span class="text-danger msgsatuan"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="qty">Qty</label>
                                <input type="number" name="qty" id="qty" class="form-control" placeholder="Jumlah item" style="text-align:right;">
                                <span class="text-danger msgqty"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group select-wrapper">
                                <label for="harga">Harga Item</label>
                                <input type="text" name="harga" id="harga" class="form-control number-format" placeholder="Harga item" style="text-align:right;">
                                <div class="pelapis">
                                    <div class="spinner"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="total">Total Harga</label>
                                <input type="text" name="total" id="total_price" class="form-control number-format" placeholder="Total Harga Item" style="text-align:right;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-save" style="width: 100%;" onclick="editFromBasket()"><i class="fas fa-save"></i> Ubah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBayar">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title modaleditKeranjang-title">Bayar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="formBayar" method="POST">
                <div class="modal-body">
                    <table style="width: 100%;">
                        <tr>
                            <td style="vertical-align: top; width:40%">
                                <label for="final_total">Total yang harus dibayar</label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" name="final_total" id="final_total" class="form-control number-format" style="text-align:right;" readonly>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">
                                <label for="cash">Cash</label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" name="cash" id="cash" class="form-control number-format" style="text-align:right;">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">
                                <label for="change">Change</label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" name="change" id="change" class="form-control number-format" style="text-align:right;" readonly>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">
                                <label for="note">Catatan</label>
                            </td>
                            <td>
                                <div class="form-group">
                                    <textarea type="text" name="note" id="note" class="form-control" rows="3" style="text-align:right;"></textarea>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-bayar" style="width: 100%;" onclick="processPembayaran()"><i class="fas fa-cash-register"></i> Process</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var table = $('#tableKeranjang')
    var modal = $('#modalCariItem')
    var modalBayar = $('#modalBayar')
    var tableCari = $('#tabelCariItem')
    var tipeF = $('#tipeF').val()
    var kategoriF = $('#kategoriF').val()
    var modaleditKeranjang = $('#modaleditKeranjang')
    var grand_total = $('#grand_total').text()
    var barcode = []
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

    $('#tipeF').change(function() {
        tipeF = $(this).val()
        tableCari.DataTable().ajax.reload()
    })
    $('#kategoriF').change(function() {
        kategoriF = $(this).val()
        tableCari.DataTable().ajax.reload()
    })

    $(function() {
        $(".sidebar-mini").addClass("sidebar-collapse");

        $('#customer').select2({
            theme: 'bootstrap4'
        })
        $('#kategoriF').select2({
            theme: 'bootstrap4',
            placeholder: "Filter Kategori"
        })
        $('#tipeF').select2({
            theme: 'bootstrap4',
            placeholder: "Filter Tipe"
        })

        grandTotal()

        table.DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "ordering": false,
            "searching": false,
            "scrollY": '40vh',
            "scrollCollapse": true,
            "paging": false,
            "info": false,
            "order": [],
            "ajax": {
                "url": "<?= site_url('sales/getKeranjang') ?>",
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
                {
                    "targets": [3, -2],
                    "className": "text-right"
                },
                {
                    "targets": [-1],
                    "width": "20%"
                },
                {
                    "targets": [1],
                    "width": "12%"
                }
            ],
            "language": {
                "processing": '<i class="fas fa-spin fa-circle-notch"></i> Tunggu',
                "emptyTable": "Tidak ada data item",
                "zeroRecords": "Belum ada barang yang dibeli",
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
                "searchPlaceholder": "Nama item"
            }
        })

        tableCari.DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "ordering": false,
            "info": false,
            "scrollY": '50vh',
            "scrollCollapse": true,
            "paging": false,
            "destroy": true,
            "order": [],
            "ajax": {
                "url": "<?= site_url('sales/getItem') ?>",
                "type": "POST",
                "data": function(d) {
                    d.tipe = tipeF
                    d.kategori = kategoriF
                    return d
                }
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
                    "width": "28%"
                },
                {
                    "targets": [1],
                    "width": "50%"
                },
                {
                    "targets": [2, 3],
                    "width": "11%"
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
                "searchPlaceholder": "Nama item, kode item, barcode"
            },
            initComplete: function(settings, json) {
                $('#tabelCariItem_filter label input').focus();
                $.each(json.data, function(key, value) {
                    barcode[key] = value[0]
                });
            }
        })
    })

    modal.on('shown.bs.modal', function(e) {
        $.fn.dataTable.tables({
            visible: true,
            api: true
        }).columns.adjust();
    });

    function pilih(id_item, nm_brg, saldo) {
        Swal.fire({
            title: nm_brg,
            html: "<b>Stok : " + saldo + "</b><p>Jumlah yang dibeli</p>",
            input: 'number',
            inputValue: '1',
            showCancelButton: true
        }).then((result) => {
            var jml = result.value
            if (parseInt(jml) > parseInt(saldo)) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Stok tidak cukup'
                })
            } else if (parseInt(jml) <= parseInt(saldo)) {
                $.ajax({
                    type: "POST",
                    data: {
                        id_item: id_item,
                        qty: jml,
                    },
                    url: "<?= site_url('sales/addItemtoBasket') ?>",
                    dataType: "JSON",

                    beforeSend: function() {
                        $('#btnPilih' + id_item + '').attr('disabled', true);
                        $('#btnPilih' + id_item + '').html('<i class="fas fa-spin fa-circle-notch"></i>');
                    },

                    complete: function() {
                        $('#btnPilih' + id_item + '').attr('disabled', false);
                        $('#btnPilih' + id_item + '').html('<i class="fas fa-cart-plus"></i>');
                    },

                    success: function(response) {
                        if (response.success) {
                            Toast.fire({
                                icon: 'success',
                                title: response.success
                            })
                        } else {
                            Toast.fire({
                                icon: 'warning',
                                title: response.failed
                            })
                        }
                        grandTotal()
                        tableCari.DataTable().ajax.reload()
                        table.DataTable().ajax.reload()
                    }
                })
            }
        });
    }

    function byidFromBasket(id_basket, id_item) {
        $.ajax({
            type: "POST",
            url: "<?= site_url('sales/byidFromBasket') ?>",
            data: {
                id_basket: id_basket,
                id_item: id_item,
            },
            dataType: "JSON",

            beforeSend: function() {
                $('#btnEditFromBasket' + id_basket + '').attr('disabled', true);
                $('#btnEditFromBasket' + id_basket + '').html('<i class="fas fa-spin fa-circle-notch"></i> Tunggu');
            },

            complete: function() {
                $('#btnEditFromBasket' + id_basket + '').attr('disabled', false);
                $('#btnEditFromBasket' + id_basket + '').html('<i class="fas fa-pen"></i> Edit');
            },

            success: function(response) {
                modaleditKeranjang.modal('show')
                $('.modaleditKeranjang-title').html('Edit | <i>' + response.nm_brg + '</i>')
                $('#nama').val(response.nm_brg)
                $('#kode').val(response.kode_brg)
                $('#satuan').val(response.satuan)
                $('#id_item').val(response.id_item)
                $('#qty').val(response.jml_beli)
                $('#stok').val(response.saldo)
                $('#harga').val(number_format(response.harga))
                $('#total_price').val(number_format(response.total_price))
            }
        })
    }

    function editFromBasket() {
        $.ajax({
            type: "POST",
            url: "<?= site_url('sales/editFromBasket') ?>",
            data: $('#formEditFromBasket').serialize(),
            dataType: "JSON",

            beforeSend: function() {
                $('.btn-save').attr('disabled', true);
                $('.btn-save').html('<i class="fas fa-spin fa-circle-notch"></i> Tunggu');
            },

            complete: function() {
                $('.btn-save').attr('disabled', false);
                $('.btn-save').html('<i class="fas fa-cash-register"></i> Process');
            },

            success: function(response) {
                if (response.stok) {
                    Toast.fire({
                        icon: 'warning',
                        title: response.stok
                    })
                }
                if (response.success) {
                    Toast.fire({
                        icon: 'success',
                        title: response.success
                    })
                    modaleditKeranjang.modal('hide')
                    table.DataTable().ajax.reload();
                    grandTotal()
                }
                if (response.failed) {
                    Toast.fire({
                        icon: 'warning',
                        title: response.failed
                    })
                }
            }
        })
    }

    function removeFromBasket(id_basket, nm_brg) {
        $.ajax({
            type: "POST",
            url: "<?= site_url('sales/removeFromBasket') ?>",
            data: {
                id_basket: id_basket,
            },
            dataType: "JSON",

            beforeSend: function() {
                $('#btnRemove' + id_basket + '').attr('disabled', true);
                $('#btnRemove' + id_basket + '').html('<i class="fas fa-spin fa-circle-notch"></i> Tunggu');
            },

            complete: function() {
                $('#btnRemove' + id_basket + '').attr('disabled', false);
                $('#btnRemove' + id_basket + '').html('<i class="fas fa-trash"></i> Hapus');
            },

            success: function(response) {
                if (response.failed) {
                    Toast.fire({
                        icon: 'warning',
                        title: response.failed
                    })
                }
                if (response.success) {
                    grandTotal()
                    table.DataTable().ajax.reload();
                    tableCari.DataTable().ajax.reload();
                }
            }
        })
    }

    function grandTotal() {
        $.ajax({
            type: "POST",
            url: "<?= site_url('sales/grandTotal') ?>",
            dataType: "JSON",

            success: function(response) {
                if (response == null) {
                    $('#grand_total').text('0')
                    $('#sub_total').val(null)
                    $('#final_total').val(null)
                } else {
                    $('#grand_total').text(number_format(response, "Rp"))
                    $('#sub_total').val(number_format(response))
                    $('#final_total').val(number_format(response))
                }
            }
        })
    }

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

    $(document).on('keyup', '#cash', function() {
        var rawTotal = $('#grand_total').text()
        var rawCash = $(this).val()
        var total = rawTotal.replace(/\D/g, '');
        var cash = rawCash.replace(/\D/g, '');

        change = parseInt(cash) - parseInt(total)
        if (change > 0) {
            $('#change').val(number_format(change.toString()))
        } else {
            $('#change').val(null)

        }
    })

    function bayar() {
        modalBayar.modal('show');
    }

    function processPembayaran() {
        $.ajax({
            type: "POST",
            url: "<?= site_url('sales/processPembayaran') ?>",
            data: $('#formBayar, #formAddItem').serialize(),
            dataType: "JSON",

            beforeSend: function() {
                $('.btn-bayar').attr('disabled', true);
                $('.btn-bayar').html('<i class="fas fa-spin fa-circle-notch"></i> Tunggu');
            },

            complete: function() {
                $('.btn-bayar').attr('disabled', false);
                $('.btn-bayar').html('<i class="fas fa-cash-register"></i> Process');
            },

            success: function(response) {
                if (response.failed) {
                    Toast.fire({
                        icon: 'warning',
                        title: response.failed
                    })
                }
                if (response.success) {
                    grandTotal()
                    var formData = $('#formAddItem')
                    var formBayar = $('#formBayar')
                    formData[0].reset()
                    formBayar[0].reset()
                    table.DataTable().ajax.reload();
                    tableCari.DataTable().ajax.reload();
                    $.ajax({
                        type: "POST",
                        url: "<?= site_url('sales/getNoInvoice') ?>",
                        dataType: "JSON",

                        success: function(response) {
                            $('#no_invoice').text(response.invoice)
                        }
                    })
                    Swal.fire({
                        title: 'Pembayaran berhasil',
                        icon: 'success',
                        showCancelButton: true,
                        focusConfirm: false,
                        confirmButtonText: 'Print Nota?',
                        cancelButtonText: 'Tidak',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = "<?= site_url('sales/cetak/') ?>" + response.id_trans
                        }
                    })
                    modalBayar.modal('hide');
                }
            }
        })
    }

    function cancel() {
        $.ajax({
            type: "POST",
            url: "<?= site_url('sales/cancelPembayaran') ?>",
            dataType: "JSON",

            beforeSend: function() {
                $('.btn-cancel').attr('disabled', true);
                $('.btn-cancel').html('<i class="fas fa-spin fa-circle-notch"></i> Tunggu');
            },

            complete: function() {
                $('.btn-cancel').attr('disabled', false);
                $('.btn-cancel').html('<i class="fas fa-times"></i> Cancel');
            },

            success: function(response) {
                table.DataTable().ajax.reload()
                tableCari.DataTable().ajax.reload()
                grandTotal()
            }
        })
    }

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

    $(document).ready(function() {
        $('#modaleditKeranjang').on('shown.bs.modal', function() {
            $('#qty').trigger('focus');
        });

        modalBayar.on('shown.bs.modal', function() {
            $('#cash').trigger('focus');
        });
    });

    $(document).ready(function() {
        // Mendengarkan event input pada elemen input menggunakan jQuery
        $("#tabelCariItem_filter label input").on("input", function(e) {
            var inputValue = $(this).val();
            if (check_barcode(barcode, inputValue)) {
                $(this).val(null)
                $.ajax({
                    type: "POST",
                    data: {
                        barcode: inputValue,
                        qty: 1,
                    },
                    url: "<?= site_url('sales/addItemtoBasket_by_barcode') ?>",
                    dataType: "JSON",

                    success: function(response) {
                        if (response.success) {
                            Toast.fire({
                                icon: 'success',
                                title: response.success
                            })
                        } else {
                            Toast.fire({
                                icon: 'warning',
                                title: response.failed
                            })
                        }
                        grandTotal()
                        tableCari.DataTable().ajax.reload()
                        table.DataTable().ajax.reload()
                    }
                })
            } 
        });
    });

    function check_barcode(arr, value) {
        return arr.includes(value);
    }
</script>