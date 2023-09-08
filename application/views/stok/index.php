<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Daftar Produk</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active">Daftar Produk</li>
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
                        <h3 class="card-title">Data Daftar Produk Item</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body tabelData">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <select name="fTipe" id="fTipe" class="form-control">
                                        <option value=""></option>
                                        <option value="xx">Semua Tipe Item</option>
                                        <option value=""></option>
                                        <?php foreach ($tipe->result() as $key => $dt) { ?>
                                            <option value="<?= $dt->id_type ?>"><?= $dt->name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <select name="fStok" id="fStok" class="form-control">
                                        <option value=""></option>
                                        <option value="xx">Semua Stok</option>
                                        <option value="tipis">Stok Menipis</option>
                                        <option value="habis">Stok Habis</option>
                                        <option value="negatif">Stok Negatif</option>
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
                                        <th>Lokasi Item</th>
                                        <th>Stok</th>
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


<script>
    var table = $('#tabelsupplier')
    var fTipe = $('#fTipe').val()
    var fStok = $('#fStok').val()
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

    $(document).ready(function() {
        //Initialize Select2 Elements
        $('#fTipe').select2({
            theme: 'bootstrap4',
            placeholder: 'Filter Tipe Item'
        })
        $('#fStok').select2({
            theme: 'bootstrap4',
            placeholder: 'Filter Stok'
        })

        table.DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "ordering": false,
            "order": [],
            "ajax": {
                "url": "<?= site_url('stok/getStok') ?>",
                "type": "POST",
                "data": function(d) {
                    d.tipe = fTipe
                    d.stok = fStok
                    return d
                }
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
                "searchPlaceholder": "Nama item,"
            }
        });

    });

    $('#fTipe').change(function(){
        fTipe = $(this).val()
        reloadTable()
    })

    $('#fStok').change(function(){
        fStok = $(this).val()
        reloadTable()
    })


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
</script>