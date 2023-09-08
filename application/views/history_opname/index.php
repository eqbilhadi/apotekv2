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
                <h4>Riwayat Stok Opname</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active">Riwayat Stok Opname</li>
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
                        <h3 class="card-title">Riwayat Stok Opname</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body tabelData">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="tabelsupplier" style="width: 100%;">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama Item</th>
                                        <th>Petugas</th>
                                        <th>Selisih</th>
                                        <th>Nilai Penyesuaian</th>
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
                                        <th>Penyesuaian Stok</th>
                                        <th>Penyesuaian Harga</th>
                                        <th>Catatan</th>
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
                "url": "<?= site_url('history_opname/getIndex') ?>",
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
                    "width": "2%"
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
                "searchPlaceholder": "Nama item"
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

    function opname(id, nama_brg, type) {
        if (type == 'detail') {
            var id_item = id
            console.log(id_item)
            $('.opname-title').html('Riwayat Stok Opname | <i>' + nama_brg + '</i>')
            tabelopname.DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "paging": false,
                "bDestroy": true,
                "info": false,
                "ordering": false,
                "searching": false,
                "order": [],
                "ajax": {
                    "url": "<?= site_url('history_opname/getRiwayatOpnameItem') ?>",
                    "type": "POST",
                    "data": function(d) {
                        d.id_transaksi = id_item
                        return d
                    }
                },
                "columnDefs": [{
                        "targets": [-2],
                        "className": "text-right"
                    },
                    {
                        "targets": [0, 1, 2, 3, 4],
                        "className": "text-center"
                    },
                    {
                        "targets": [0],
                        "width": "1%"
                    },
                    {
                        "targets": [-1],
                        "width": "30%"
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
        } else {
            $('#btnCancel' + id).attr('disabled', true);
            $('#btnCancel' + id).html('<i class="fas fa-spin fa-circle-notch"></i>');
            Swal.fire({
                icon: 'warning',
                title: 'Yakin membatalkan stok opname ini?',
                html: '<p>Membatalkan akan mempengaruhi stok item nantinya<p>',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: `Tidak`,
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteData(id);
                } else {
                    $('#btnCancel' + id).attr('disabled', false);
                    $('#btnCancel' + id).html('<span class="fas fa-ban"></span>');
                }
            });
        }
    }

    function deleteData(id) {
        $.ajax({
            type: "post",
            url: "<?= site_url('history_opname/delete/') ?>" + id,
            dataType: "json",
            success: function(response) {
                $('#btnCancel' + id).attr('disabled', false);
                $('#btnCancel' + id).html('<span class="fas fa-ban"></span>');
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