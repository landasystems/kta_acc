<?php

$parent = "";
$acc = AccCoa::model()->findByPk($id);
if ($acc->type == "detail") {
//    $sWhere = (!empty($pada)) ? ' AND ' : '';
    $lawan = '';
    $accCoaDet = AccCoaDet::model()->findAll(array('order' => 'date_coa , code', 'condition' => '(date_coa >="' . $start . '" and date_coa <="' . $end . '") and acc_coa_id =' . $id));
    if (!empty($pada)) {
        $detPada = array();
        foreach ($accCoaDet as $val) {
            $det = AccCoaDet::model()->findAll(array('order' => 'date_coa , code', 'condition' => '(date_coa >="' . $start . '" and date_coa <="' . $end . '") and acc_coa_id =' . $pada . ' and code = "' . $val->code . '"'));
            if (!empty($det)) {
                foreach ($det as $valPada) {
                    $detPada[] = $valPada;
                }
            }
        }
        $accCoaDet = $detPada;
    }
    $beginingBalance = AccCoaDet::model()->beginingBalance(date('Y-m-d', strtotime($start)), $id, FALSE);
    $this->renderPartial('_generalLedgerDetail', array('start' => $start, 'pada' => $pada, 'end' => $end, 'acc' => $acc, 'beginingBalance' => $beginingBalance, 'accCoaDet' => $accCoaDet, 'start' => $start, 'checked' => $checked));
} else {
    ?>
<div id='printableArea'>
    <div class="img-polaroid" style="border: none">
        <style type="text/css" media="print">
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
        <table class="table table-bordered" border="1" style="width:100%;">
                <thead>
                    <tr>
                        <td  style="text-align: center;border:none;border-right: none;" colspan="6">
                            <h2 style="margin-bottom: 0px">LEDGER</h2>
                            <h4 style="margin-bottom: 0px"><?php echo date('d-M-Y', strtotime($start)) . " - " . date('d-M-Y', strtotime($end)); ?></h4>
                            Disiapkan Tanggal : <?= date('d F Y, H:i') ?>
                            <hr style="margin: 10px"/>
                        </td>
                    </tr>
                </thead>
        </table>
<?php
    $children = $acc->descendants()->findAll();
    foreach ($children as $vals) {
        
        $ids = $vals->id;
        $acc = AccCoa::model()->findByPk($ids);
        $lawan = '';
    $accCoaDet = AccCoaDet::model()->findAll(array('order' => 'date_coa , code', 'condition' => '(date_coa >="' . $start . '" and date_coa <="' . $end . '") and acc_coa_id =' . $ids));
    if (!empty($pada)) {
        $detPada = array();
        foreach ($accCoaDet as $val) {
            $det = AccCoaDet::model()->findAll(array('order' => 'date_coa , code', 'condition' => '(date_coa >="' . $start . '" and date_coa <="' . $end . '") and acc_coa_id =' . $pada . ' and code = "' . $val->code . '"'));
            if (!empty($det)) {
                foreach ($det as $valPada) {
                    $detPada[] = $valPada;
                }
            }
        }
        $accCoaDet = $detPada;
    }
    $beginingBalance = AccCoaDet::model()->beginingBalance(date('Y-m-d', strtotime($start)), $id, FALSE);
    $this->renderPartial('_generalLedgerDetail2', array('start' => $start, 'pada' => $pada, 'end' => $end, 'acc' => $acc, 'beginingBalance' => $beginingBalance, 'accCoaDet' => $accCoaDet, 'start' => $start, 'checked' => $checked));

//        logs($val->id);
//        $accCoaDet = AccCoaDet::model()->findAll(array('order' => 'date_coa , code', 'condition' => '(date_coa >="' . $start . '" and date_coa <="' . $end . '") and acc_coa_id =' . $_POST['pada']));
//        $beginingBalance = AccCoaDet::model()->beginingBalance(date('Y-m-d', strtotime($start)), $id, FALSE);
//        $this->renderPartial('_generalLedgerDetail', array('start' => $start, 'pada' => $pada, 'end' => $end, 'acc' => $val, 'beginingBalance' => $beginingBalance, 'accCoaDet' => $accCoaDet, 'checked' => $checked));
    }
    ?>
    </div>
</div>
        <?php
}
?>