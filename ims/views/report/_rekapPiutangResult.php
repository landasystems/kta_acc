<div class='printableArea'>
    <style type="text/css" media="print">
        body {visibility:hidden;}
        .printableArea{visibility:visible;position: absolute;top:0;left:0px;width: 100%;font-size:14px}
        table{width: 100%}
    </style>
    <table class="table table-bordered table">
        <thead>
            <tr>
                <td  style="text-align: center" colspan="6"><h2>REKAP KARTU PIUTANG</h2>
                    <?php echo date('d-M-Y', strtotime($start)) . " - " . date('d-M-Y', strtotime($end)); ?>
                    <hr></td>
            </tr>  
            <tr> 
                <th width="10%" rowspan="2">Kode. Customer</th>
                <th width="30%" rowspan="2">Nama Customer</th>
                <th width="15%" rowspan="2">Saldo Awal</th>
                <th width="30%" colspan="2">Mutasi</th>
                <th width="15%" rowspan="2">Saldo Akhir</th>
            </tr>
            <tr>
                <th width="15%">Debet</th>
                <th width="15%">Credit</th>
            </tr>
        </thead>
        <tbody>

            <tr class="table-bordered">
                <?php
                $tbalance = "";
                $tdebet = "";
                $tcredit = "";
                $takhir = "";
                $SaldoAkhir = "";
                foreach ($supplier as $b) {
                    $balance = AccCoaDet::model()->total($end, $start, $b->id);
                    $saldoawal = AccCoaDet::model()->saldoKartu($start, $b->id);
                    $salakhir = $saldoawal + $balance->sumDebet - $balance->sumCredit;

                    //total
                    $tbalance += $saldoawal;
                    $takhir += $salakhir;
                    $tdebet += $balance->sumDebet;
                    $tcredit += $balance->sumCredit;
                    if ($balance->sumDebet != 0 or $balance->sumCredit != 0 or $saldoawal != 0 or $salakhir != 0) {
                        echo'<tr>
			<td width="10%" style="text-align:center">' . $b->code . '</td>
			<td>' . $b->name . '</td>
			<td style="text-align:right">' . landa()->rp($saldoawal) . ' </td>
			<td style="text-align:right">' . landa()->rp($balance->sumDebet) . ' </td>
			<td style="text-align:right">' . landa()->rp($balance->sumCredit) . ' </td>
			<td style="text-align:right">' . landa()->rp($salakhir) . ' </td>
                        </tr>';
                    }
                }
                ?>
            </tr>
        </tbody>
        <footer>
            <tr>
                <th colspan="2">Saldo Total</th>
                <th style="text-align:right !important"><?php echo landa()->rp($tbalance); ?></th>
                <th style="text-align:right !important"><?php echo landa()->rp($tdebet); ?></th>
                <th style="text-align:right !important"><?php echo landa()->rp($tcredit); ?></th>
                <th style="text-align:right !important"><?php echo landa()->rp($takhir); ?></th>
            </tr>
        </footer>
    </table>
</div>


<script type="text/javascript">
    function printDiv()
    {

        window.print();

    }
</script>