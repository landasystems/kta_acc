<div id='printableArea'>
    <style type="text/css">
        .table td, th {
            padding: 0px;
            margin: 0px;
            border-collapse: collapse;
            font-size:11px;
        }
        .table{
            border-collapse: collapse;
        }
        /*body {}*/

    </style>
    <table width="100%">
        <tr>
            <td></td>
            <td></td>
            <td  style="text-align: center" colspan="2"><h2>KARTU HUTANG REPORT</h2>
                <?php echo date('d F Y', strtotime($start)) . " - " . date('d F Y', strtotime($end)); ?>
                <hr></td>
        </tr>    

    </table>

    <table class="table table-bordered" border="1">
        <thead>
            <tr>
                <th colspan="2" width="5%" style="width:5%;text-align: center;"></th>
                <th width="25%" style="text-align: center;">Description</th>
                <th width="5%" style="text-align: center;">Reff</th>
                <th width="5%" style="text-align: center;">Invoice</th>
                <th width="20%" style="text-align: center;">Debet</th>
                <th  width="20%" style="text-align: center;">Credit</th>
                <th width="20%" style="text-align: center;">Saldo</th>
            </tr>


        </thead>
        <tbody>
            <tr>
                <?php
                $balance = AccCoaDet::model()->saldoKartu(date('Y-m-d', strtotime($start)), $id);
                ?>
                <th></th>
                <th></th>
                <th>Saldo Awal</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th style="text-align:right;"><?php echo landa()->rp($balance); ?></th>
            </tr>
            <?php
            $total = 0;
            $monthYear = "";
            $i = 0;
            $saldo = $balance;
            $tDebet = 0;
            $tCredit = 0;
            foreach ($accCoaDet as $a) {
                $sDate = ($monthYear == date('M Y', strtotime($a->date_coa))) ? "" : date('M Y', strtotime($a->date_coa));
                $monthYear = date('M Y', strtotime($a->date_coa));

                $saldo = $saldo + $a->debet - $a->credit;
                $tDebet += $a->debet;
                $tCredit += $a->credit;
                echo '<tr>
			<td>' . $sDate . '</td>
                        <td>' . date('d', strtotime($a->date_coa)) . '</td>
			<td>' . $a->description . '</td>
			<td>' . $a->code . '</td>
			<td>' . $a->InvoiceDet->code . '</td>
			<td name="deb" style="text-align:right">' . landa()->rp($a->debet, false) . ',- </td>
			<td name="cred" style="text-align:right">' . landa()->rp($a->credit, false) . ',- </td>
			<td name="tdeb" style="text-align:right">' . landa()->rp($saldo) . ',- </td>
			</tr>';
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5">Saldo Akhir</th>
                <th style="text-align: right;"><?php echo landa()->rp($tDebet, false) . ',-' ?></th>
                <th style="text-align: right;"><?php echo landa()->rp($tCredit, false) . ',-' ?></th>
                <th style="text-align: right;"><?php echo landa()->rp($saldo, false) . ',-' ?></th>
            </tr>
        </tfoot>
    </table>
</div>

