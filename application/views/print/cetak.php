<html>

<head>
    <title>Faktur Pembayaran</title>
    <style>
        #tabel {
            font-size: 15px;
            border-collapse: collapse;
        }

        #tabel td {
            padding-left: 5px;
            border: 1px solid black;
        }

        .horizontal_dotted_line_top {
            border-top: 1px dashed black;
            width: 80%;
        }

        .horizontal_dotted_line_bottom {
            border-bottom: 1px dashed black;
            width: 80%;
        }
    </style>
</head>

<body style='font-family:tahoma; padding-top:100px' onload="javascript:window.print()">
    <center>
        <table style='width:80%; font-family:arial; border-collapse: collapse;' border='0'>
            <td width='70%' align='left' style='padding-right:80px; vertical-align:top'>
                <span style='font-size:16pt'><b>Apotek Wonokromo</b></span></br>
                <p style="margin-top: auto;"> Jl. Imogiri Tim. No.KM. 10, Ketongo, Wonokromo, Kec. Pleret, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55791</p>
            </td>
            <td style='vertical-align:top' width='30%' align='left'>
                <span style='font-size:16pt'><b>&nbsp;</b></span></br>
                <p style="margin-top: auto;"><?= $data[0]->no_faktur; ?></p></br>
                <p style="margin-top: -34px;"><?= $data[0]->tgl; ?></p></br>
            </td>
        </table>
        <div class="horizontal_dotted_line_top" style="padding-bottom: 20px"></div>
        <table style='width:80%; font-family:arial; border-collapse: collapse;' border='0'>
            <tr>
                <td width='20%' align='left' style='vertical-align:top'>Invoice</td>
                <td width='15px'>:</td>
                <td><?= $data[0]->no_faktur; ?></td>
            </tr>
            <tr>
                <td width='20%' align='left' style='vertical-align:top'>Tanggal</td>
                <td width='15px'>:</td>
                <td><?= $data[0]->tgl; ?></td>
            </tr>
            <tr>
                <td width='20%' align='left' style='vertical-align:top'>Kasir</td>
                <td width='15px'>:</td>
                <td><?= $data[0]->kasir; ?></td>
            </tr>
        </table>
        <div class="horizontal_dotted_line_bottom" style="padding-top: 15px; margin-bottom: 15px"></div>
        <table style='width:80%; font-family:arial;  border-collapse: collapse;' border='1'>

            <tr align='center'>
                <td>Kode Barang</td>
                <td>Nama Barang</td>
                <td>Harga</td>
                <td>Qty</td>
                <td>Discount</td>
                <td>Total Harga</td>
            </tr>
            <?php foreach ($data as $val) { ?>
                <tr>
                    <td style="width: 20%;"><?= $val->item_code; ?></td>
                    <td style="width: 20%;"><?= $val->item_name; ?></td>
                    <td style='text-align:right'><?= number_format($val->item_price, 0, ',', '.'); ?></td>
                    <td style='text-align:center'><?= $val->qty . ' ' . $val->satuan_name; ?></td>
                    <td style='text-align:center'>-</td>
                    <td style='text-align:right'><?= number_format($val->item_totalprice, 0, ',', '.'); ?></td>
                </tr>
            <?php } ?>
        </table>
        <div class="horizontal_dotted_line_bottom" style="padding-top: 15px; margin-bottom: 15px"></div>
        <table style='width:80%; font-family:arial;  border-collapse: collapse;' border='0'>
            <tr>
                <td colspan='4'>
                    <div style='text-align:right'>Total</div>
                </td>
                <td style='text-align:center'>:</td>
                <td style='text-align:right; width:20%;'><?= number_format($totalprice, 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td colspan='4'>
                    <div style='text-align:right'>Cash</div>
                </td>
                <td style='text-align:center'>:</td>
                <td style='text-align:right; width:20%;'><?= number_format($data[0]->cash, 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td colspan='4'>
                    <div style='text-align:right'>Kembalian</div>
                </td>
                <td style='text-align:center'>:</td>
                <td style='text-align:right; width:20%;'><?= number_format($data[0]->change, 0, ',', '.'); ?></td>
            </tr>
        </table>

        <table style='width:650; font-size:7pt;' cellspacing='2'>
            <tr>
                <!-- <td align='center'>Diterima Oleh,</br></br><u>(............)</u></td>
                <td style='border:1px solid black; padding:5px; text-align:left; width:30%'></td>
                <td align='center'>TTD,</br></br><u>(...........)</u></td> -->
            </tr>
        </table>
    </center>
</body>

</html>