var table = $('#tabeluser');

$(document).ready(function(){
    table.DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?= site_url('auth/process') ?>",
            "type": "POST"
        },
        "columnDefs": [
            { "targets": [0, 2], "orderable": false, "className": "text-center" },
            { "targets": [0], width: 6 }
        ],
        "language": {
            "processing": '<i class="fa fa-spin fa-spinner"></i>',
            // "emptyTable": "Tidak ada data materi ujian",
            // "zeroRecords": "Tidak ada data yang sesuai"
        }
    })
})