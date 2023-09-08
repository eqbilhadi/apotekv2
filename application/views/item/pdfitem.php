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
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            font-size: 10pt;
            width: 100%;
        }

        .table1 td,
        .table1 th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table1 tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table1 tr:hover {
            background-color: #ddd;
        }

        .table1 th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #454545;
            color: white;
        }
    </style>
    <title>Apotek</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url() ?>/assets/img/users/logo.jpg">
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
                        Daftar Item
                    </span>
                </div>
            </td>
        </tr>
    </table>
    <br>
    <table class="table1">
        <tr>
            <th style="text-align: center;">No</th>
            <th style="text-align: left;">Nama Item</th>
            <th style="text-align: left;">Kode Item</th>
            <th style="text-align: left;">Satuan</th>
            <th style="text-align: right;">Harga Beli</th>
            <th style="text-align: right;">Harga Jual</th>
            <th style="text-align: left;">Lokasi Item</th>
            <th align="center">Barcode</th>
        </tr>
        <?php
        $no = 1;
        foreach ($data as $value) { ?>
            <tr>
                <td style="text-align: center;"><?= $no++; ?></td>
                <td style="text-align: left;"><?= $value->item_name ?></td>
                <td style="text-align: left;"><?= $value->item_code ?></td>
                <td style="text-align: left;"><?= $value->satuan_name ?></td>
                <td style="text-align: right;"><?= number_format($value->h_beli ?? 0, 0, ',', '.') ?></td>
                <td style="text-align: right;"><?= number_format($value->h_jual ?? 0, 0, ',', '.') ?></td>
                <td style="text-align: left;"><?= $value->lokasi ?></td>
                <?php if ($value->barcode != '') { ?>
                    <td align="center">
                        <div style="margin: 0;">
                            <?php
                            require 'vendor/autoload.php';

                            // This will output the barcode as HTML output to display in the browser
                            $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
                            echo $generator->getBarcode($value->barcode, $generator::TYPE_CODE_128, 1, 22);
                            ?>
                        </div>
                    </td>
                <?php } else { ?>
                    <td>-</td>
                <?php } ?>
            </tr>
        <?php } ?>

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