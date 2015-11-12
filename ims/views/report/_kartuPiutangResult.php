<div id='printableArea'>
    <style type="text/css">
        .table td, th {
            padding: 3px;
            margin: 0px;
            border-collapse: collapse;
            font-size:12px;
        }
        .table{
            border-collapse: collapse;
        }
        /*body {}*/

    </style>

    <table class="table table-bordered" style="border-collapse: separate" border="1">
        <thead>
            <tr>
                <th colspan="8" style="text-align: center;background-color: #FFFFFF">
        <h2 style="margin-bottom: 0px">KARTU PIUTANG</h2>
        <?php echo date('d F Y', strtotime($start)) . " - " . date('d F Y', strtotime($end)); ?>
        <hr style="margin: 10px">
        </th>
        </tr>
        <tr>
            <th colspan="2" width="5%">Date</th>
            <th width="25%" style="background-color: #dcdcdc;-webkit-print-color-adjust: exact; text-align: center">Description</th>
            <th width="5%" style="background-color: #dcdcdc;-webkit-print-color-adjust: exact; text-align: center">Reff</th>
            <th width="5%" style="background-color: #dcdcdc;-webkit-print-color-adjust: exact; text-align: center">Invoice</th>
            <th width="20%" style="background-color: #dcdcdc;-webkit-print-color-adjust: exact; text-align: center">Debet</th>
            <th  width="20%" style="background-color: #dcdcdc;-webkit-print-color-adjust: exact; text-align: center">Credit</th>
            <th width="20%" style="background-color: #dcdcdc;-webkit-print-color-adjust: exact; text-align: center">Saldo</th>
        </tr>


        </thead>
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
            <th style="text-align:right !important"><?php echo landa()->rp($balance, false); ?></th>
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
			<td name="deb" style="text-align:right">' . landa()->rp($a->debet, false) . ' </td>
			<td name="cred" style="text-align:right">' . landa()->rp($a->credit, false) . ' </td>
			<td name="tdeb" style="text-align:right">' . landa()->rp($saldo, false) . '</td>
			</tr>';
        }
        ?>
        <tfoot>
            <tr>
                <th colspan="5">Saldo Akhir</th>
                <th style="text-align:right !important"><?php echo landa()->rp($tDebet, false) ?></th>
                <th style="text-align:right !important"><?php echo landa()->rp($tCredit, false) ?></th>
                <th style="text-align:right !important"><?php echo landa()->rp($saldo, false) ?></th>
            </tr>
        </tfoot>
    </table>
</div>

<script type="text/javascript">
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
