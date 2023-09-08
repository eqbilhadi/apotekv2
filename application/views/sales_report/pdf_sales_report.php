<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .line-title {
            border: 0;
            border-style: inset;
            border-top: 1px solid #000;
        }

        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }

        .table1 {
            font-family: sans-serif;
            color: #444;
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #f2f5f7;
        }

        .table1 tr th {
            background: #A9A9A9;
            color: #fff;
            font-weight: normal;
        }

        .table1,
        th,
        td {
            padding: 8px 5px;
        }

        .table1 tr:hover {
            background-color: #f5f5f5;
        }

        .table1 tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
    <title>Document</title>
</head>

<body>
    <div style="text-align: left;">
        <img src="<?= base_url() ?>/assets/img/users/logo.jpg" alt="" style="position: absolute; width: 70px: height: auto;">
    </div>
    <table style="width: 100%;">
        <tr>
            <td align="center">
                <div style="margin-left: 50px;">
                    <span style="line-height: 1.6; font-weight: bold; font-size: 14pt">
                        APOTEK WONOKROMO
                    </span>
                    <br>
                    <span style="font-size: 10pt">
                        Sahabat Terbaik Menuju Sehat
                    </span>
                    <br>
                    <span style="font-size: 9pt">
                        Jl. Imogiri Tim. No.KM. 10, Ketongo, Wonokromo, Kec. Pleret, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55791
                    </span>
                </div>
            </td>
        </tr>
    </table>
    <hr class="line-title">
    <table style="width: 100%;">
        <tr>
            <td align="center">
                <div style="margin-left: 50px;">
                    <span style="font-weight: bold; font-size: 12pt">
                        Daftar Penjualan
                    </span>
                </div>
            </td>
        </tr>
    </table>
    <?php
    $total_jml = 0;
    $total_beli = 0;

    // Looping untuk menjumlahkan nilai jml
    foreach ($data as $obj) {
        $total_jml += $obj->jml;
        $total_beli += $obj->total;
    }
    ?>
    <table width='70%' style='margin-bottom:14px; font-size:10pt;'>
        <tr>
            <td width='40%'>Tanggal</td>
            <td width='1%' style="text-align: right;">: </td>
            <td width='30%' style="text-align: left;"><?= longdate_indo($start); ?> - <?= longdate_indo($end); ?></td>
        </tr>
        <tr>
            <td width='40%'>Total Jumlah Item </td>
            <td width='1%' style="text-align: right;">: </td>
            <td width='30%' style="text-align: left;"><?= $total_jml; ?></td>
        </tr>
        <tr>
            <td width='40%'>Total</td>
            <td width='1%' style="text-align: right;">: </td>
            <td width='30%' style="text-align: left; font-weight: bold;"><?= "Rp. " . number_format($total_beli ?? 0, 0, ',', '.'); ?></td>
        </tr>
    </table>
    <table class="table1" style="font-size:10pt;">
        <tr>
            <th style="text-align: center; width: 20px;">No</th>
            <th style="text-align: left; width: 130px">Nomor Faktur</th>
            <th style="text-align: left; width: 150px">Tanggal</th>
            <th style="text-align: left;">Kasir</th>
            <th style="text-align: left;">Pelanggan</th>
            <th style="text-align: right;">Jumlah Item</th>
            <th style="text-align: right;">Total</th>
            <th style="text-align: right;">Cash</th>
            <th style="text-align: right;">Kembalian</th>
        </tr>
        <?php
        $no = 1;
        foreach ($data as $value) {
            if ($value->is_draft != 1) { ?>
                <tr>
                    <td style="text-align: center;"><?= $no++; ?></td>
                    <td style="text-align: left;"><?= $value->no_faktur ?></td>
                    <td style="text-align: left;"><?= longdate_indo($value->tgl) ?></td>
                    <td style="text-align: left;"><?= $value->kasir ?? '-' ?></td>
                    <td style="text-align: left;"><?= $value->customer ?? '-' ?></td>
                    <td style="text-align: right;"><?= $value->jml ?></td>
                    <td style="text-align: right;"><?= number_format($value->total ?? 0, 0, ',', '.') ?></td>
                    <td style="text-align: right;"><?= number_format($value->cash ?? 0, 0, ',', '.') ?></td>
                    <td style="text-align: right;"><?= number_format($value->change ?? 0, 0, ',', '.') ?></td>
                </tr>
        <?php }
        } ?>

    </table>
</body>

</html>
<!-- <td>
    <script src="<?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets') ?>/bower_components/jsBarcode/JsBarcode.all.min.js"></script>

    <svg id="svg<?= $value->barcode ?>"></svg>
    <img id="img<?= $value->barcode ?>">
    <script>
        var ln = '<?= $value->barcode ?>'
        JsBarcode("#svg<?= $value->barcode ?>", ln);
        var svg = $("#svg<?= $value->barcode ?>")[0];

        var xml = new XMLSerializer().serializeToString(svg);

        var base64 = 'data:image/svg+xml;base64,' + btoa(xml);

        var img = $("#img<?= $value->barcode ?>")[0];

        img.src = base64;
    </script>
</td> -->