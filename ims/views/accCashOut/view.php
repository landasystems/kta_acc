
<?php
$siteConfig = SiteConfig::model()->findByPk(1);
$this->setPageTitle('Lihat Kas Keluar | : ' . $model->code);
?>

<?php
$this->beginWidget('zii.widgets.CPortlet', array(
    'htmlOptions' => array(
        'class' => ''
    )
));
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Tambah', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('label' => 'Daftar', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
        array('label' => 'Edit', 'icon' => 'icon-edit', 'url' => Yii::app()->controller->createUrl('update', array('id' => $model->id)), 'linkOptions' => array()),
    //array('label'=>'Print', 'icon'=>'icon-print', 'url'=>'javascript:void(0);return false', 'linkOptions'=>array('onclick'=>'printDiv();return false;')),
)));
$this->endWidget();
$date = AccCoaDet::model()->find(array('condition' => 'reff_type="cash_out" and reff_id= ' . $model->id));
if (isset($date)) {
    $datePost = $date->date_coa;
} else {
    $datePost = '';
}
?>
<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'acc-cash-in-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'vertical',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <div class="box gradient invoice">
        <div class="title clearfix">
            <h4 class="left">
                <span class="number"><?php echo (isset($model->code_acc)) ? '#' . $model->code_acc : ''; ?></span>
                <br><span class="data gray"><?php echo (isset($model->date_posting)) ? date('d-M-Y', strtotime($model->date_posting)) : ''; ?></span>
            </h4>

            <div class="print">
                <a href="#" onclick="js:printDiv('printableArea');
                        return false;">
                    <span class="icon24 icon-print"></span>
                </a>
            </div>
        </div>
        <div>
        </div>
        <div class="content">
            <fieldset>
                <?php
                if (isset($_GET['berhasil'])) {
                    echo '<div class="alert alert-success" role="alert">Data Berhasil Disimpan!</div>';
                } else {
                    echo '';
                }
                ?>
                <div class="row-fluid" style="margin-left: 0px;">
                    <div class="span12 tab-pane fade active in">
                        <div class="control-group span6">
                            <div class="control-group">
                                <label class="control-label span3">Tgl Pembuatan</label>
                                <?php echo $form->textFieldRow($model, 'date_trans', array('class' => 'span6', 'maxlength' => 255, 'disabled' => true, 'prepend' => '<i class="icon-calendar"></i>', 'labelOptions' => array('label' => false))); ?>
                            </div>
                            <div class="control-group">
                                <label class="control-label span3">Dibayar Kepada</label>
                                <?php echo $form->textFieldRow($model, 'description_to', array('class' => 'span6', 'maxlength' => 255, 'disabled' => true, 'labelOptions' => array('label' => false))); ?>
                            </div>
                            <div class="control-group">
                                <label class="control-label span3">Keterangan</label>
                                <?php echo $form->textAreaRow($model, 'description', array('class' => 'span6', 'maxlength' => 255, 'disabled' => true, 'labelOptions' => array('label' => false))); ?>
                            </div>
                        </div>
                        <div class="control-group span6">
                            <div class="control-group">
                                <label class="control-label span3">Keluar dari</label>
                                <?php
                                $data = array(0 => 'Pilih') + CHtml::listData(AccCoa::model()->findAll(array('condition' => 'type_sub_ledger="ks" OR type_sub_ledger="bk"', 'order' => 'root, lft')), 'id', 'nestedname');
                                $this->widget('bootstrap.widgets.TbSelect2', array(
                                    'asDropDownList' => TRUE,
                                    'data' => $data,
                                    'name' => 'AccCashIn[accCoa]',
                                    'value' => (isset($model->acc_coa_id) ? $model->acc_coa_id : ''),
                                    'options' => array(
                                        "placeholder" => 'Pilih',
                                        "allowClear" => true,
                                    ),
                                    'htmlOptions' => array(
                                        'id' => 'AccCashOut_account',
                                        'style' => 'width:250px;',
                                        'disabled' => true
                                    ),
                                ));
                                ?>
                            </div>
                            <div class="control-group">
                                <label class="control-label span3">Total Kredit</label>
                                <div class="input-prepend">
                                    <span class="add-on">Rp.</span>
                                    <?php echo CHtml::textfield('totalDebit', $model->total, array('class' => 'span5', 'maxlength' => 255, 'disabled' => true, 'onkeyup' => 'this.value=this.value.replace(/[^\d]/,\'\')')); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label span3">Giro A.N</label>
                                <?php echo $form->textFieldRow($model, 'description_giro_an', array('class' => 'span6', 'disabled' => true, 'maxlength' => 255, 'labelOptions' => array('label' => false))); ?>

                            </div>
                        </div>
                    </div>

                </div>
                <br>
                <h4>Detail Dana</h4>
                <table class="responsive table table-bordered">
                    <thead>
                        <tr>
                            <th width="20">#</th>
                            <th>Kode Akun</th>
                            <th>Sub Ledger</th>
                            <th>Keterangan</th>
                            <th>Debit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $name = '';
                        foreach ($cashOutDet as $viewCashOutDet) {
                            if ($viewCashOutDet->AccCoa !== NULL) {
                                $accCoaName = $viewCashOutDet->AccCoa->code . ' - ' . $viewCashOutDet->AccCoa->name;
                            } else {
                                $accCoaName = '-';
                            }

                            if (!empty($viewCashOutDet->invoice_det_id)) {
//                                $account = InvoiceDet::model()->findByPk($viewCashOutDet->ar_id);
                                if (!empty($viewCashOutDet->InvoiceDet->id) && $viewCashOutDet->InvoiceDet->type == "customer") {
                                    $customer = empty($viewCashOutDet->InvoiceDet->Customer->name) ? '-' : $viewCashOutDet->InvoiceDet->Customer->name;
                                    $name = '[' . $viewCashOutDet->InvoiceDet->code . '] ' . $customer;
                                } elseif (!empty($viewCashOutDet->InvoiceDet->id) && $viewCashOutDet->InvoiceDet->type == "supplier") {
                                    $supplier = empty($viewCashOutDet->InvoiceDet->Supplier->name) ? '-' : $viewCashOutDet->InvoiceDet->Supplier->name;
                                    $name = '[' . $viewCashOutDet->InvoiceDet->code . '] ' . $supplier;
                                }
                            } else {
                                $name = "-";
                            }

                            echo '  <tr>
                                            <td>' . $no . '</td>
                                            <td>' . $accCoaName . '</td>
                                            <td>' . $name . '</td>
                                            <td>' . $viewCashOutDet->description . '</td>
                                            <td>' . landa()->rp($viewCashOutDet->amount, true, 2) . '</td>
                                        </tr>';
                            $no++;
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" valign="middle" align="center"><b>Total Debit</b></td>
                            <td><?php
                                echo landa()->rp($model->total, true, 2)
                                ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <br>

            </fieldset>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>

<hr/>
<!--------------------------print------------------------------------->
<style>
    .tbPrint td{
        background: #e8edff;
        border-bottom: none ;
        border-left: none;
        border-right: none;
        color: #669;
        border-top: none;
    }
</style>
<div id="printableArea" style="width: 100%;display:none">
    <?php
    $no = 1;
    $jmlCash = count($cashOutDet);
    $jmlTable = (int) count($cashOutDet) / 8;
    if ($jmlCash <= 8) {
        $batas = 8;
    } else {
        $batas = 8;
    }
    $jumlahRp = 0;
    $indeks = 0;
    for ($a = 0; $a < $jmlTable; $a++) {
        $detailCash = '<table class="tbPrint">
                                            <tr>
                                                <th class="print" width="50" style="text-align:center">No.</th>
                                                <th class="print" width="130" style="text-align:center">Account </th>
                                                <th class="print" style="text-align:center">Description </th>
                                                <th class="print" width="200" style="text-align:center">Amount </th>
                                            </tr>';
        if ($a > 0) {
            $detailCash .= '<tr>
                                                 <td class="print" style="text-align:center;border-bottom:none;border-top:none"></td>
                                                 <td class="print" style="text-align:center;border-bottom:none;border-top:none"></td>
                                                 <td class="print" style="border-bottom:none;border-top:none">Continued</td>
                                                 <td class="print" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($jumlahRp, false) . '</td>
                                            </tr>';
        }
        for ($i = 0; $i < $batas; $i++) {
            $viewCashOutDet = $cashOutDet;
            if (!empty($viewCashOutDet[$indeks])) {
                if ($viewCashOutDet[$indeks]->AccCoa !== NULL) {
                    $accCoaName = $viewCashOutDet[$i]->AccCoa->code;
                } else {
                    $accCoaName = '-';
                }
                $detailCash .= '
                                            <tr>
                                                 <td class="print" style="text-align:center;border-bottom:none;border-top:none">' . $no . '</td>
                                                 <td class="print" style="text-align:center;border-bottom:none;border-top:none">' . $accCoaName . '</td>
                                                 <td class="print" style="border-bottom:none;border-top:none">' . $viewCashOutDet[$indeks]->description . '</td>
                                                 <td class="print" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($viewCashOutDet[$indeks]->amount, false) . '</td>
                                            </tr>';
                $jumlahRp += $viewCashOutDet[$indeks]->amount;
            } else {
                $detailCash .= '
                                            <tr>
                                                <td class="print" style="border-bottom:none;border-top:none">&nbsp;</td>
                                                <td class="print" style="border-bottom:none;border-top:none">&nbsp;</td>
                                                <td class="print" style="border-bottom:none;border-top:none">&nbsp;</td>
                                                <td class="print" style="border-bottom:none;border-top:none">&nbsp;</td>
                                            </tr>';
            }
            $no++;
            $indeks++;
        }

        $detailCash .= '<tr>
                        <td class="print" colspan="3" style="text-align:center">' . AccCoa::model()->terbilangEn($jumlahRp) . ' rupiah</td>
                        <td class="print" style="text-align:right">' . landa()->rp($jumlahRp, false) . '</td>
                    </tr>
                    </table>';

//        $detailCash .= '<tr>
//                        <td class="print" colspan="4" style="text-align:center">' . AccCoa::model()->angkaTerbilang($jumlahRp) . '</td>
//                    </tr>
//                    </table>';

        $adminStatus = (isset($model->AccAdmin->status)) ? $model->AccAdmin->status : '';
        $managerStatus = (isset($model->AccManager->status)) ? $model->AccManager->status : '';

        $adminName = (isset($model->AccAdmin->User->name) and $adminStatus == "confirm") ? $model->AccAdmin->User->name : '';
        $adminDate = (isset($model->AccAdmin->created) and $adminStatus == "confirm") ? date('d M Y', strtotime($model->AccAdmin->created)) : '';

        $managerName = (isset($model->AccManager->User->name) and $managerStatus == "confirm") ? $model->AccManager->User->name : '';
        $managerDate = (isset($model->AccManager->created) and $managerStatus == "confirm") ? date('d M Y', strtotime($model->AccManager->created)) : '';

        $dateApprove = ".........";
        $noApprove = ".........";
        if (!empty($model->code_acc))
            $noApprove = $model->code_acc;

        if (!empty($datePost))
            $dateApprove = date('d M Y', strtotime($datePost));

        $accCoaName = (isset($model->AccCoa->name)) ? $model->AccCoa->name : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

//        $content = $siteConfig->report_cash_out;
//        $content = str_replace('{account}', $accCoaName, $content);
//        $content = str_replace('{cash_out}', $model->code, $content);
//        $content = str_replace('{date}', date('d M Y', strtotime($model->date_trans)), $content);
//        $content = str_replace('{detail_cash}', $detailCash, $content);
//        $content = str_replace('{tellerName}', (isset($model->User->name) ? $model->User->name : '-'), $content);
//        $content = str_replace('{tellerApprove}', date('d M Y', strtotime($model->created)), $content);
//        $content = str_replace('{adminName}', $adminName, $content);
//        $content = str_replace('{adminApprove}', $adminDate, $content);
//        $content = str_replace('{managerName}', $managerName, $content);
//        $content = str_replace('{managerApprove}', $managerDate, $content);
//        $content = str_replace("{no_approval}", $noApprove, $content);
//        $content = str_replace("{date_approval}", $dateApprove, $content);
//        $content = str_replace("{description_to}", $model->description_to, $content);
//        $content = str_replace("{description_giro_an}", $model->description_giro_an, $content);
       
        //jika ada halaman berikutnya print br 3, agar pas cucok em
        if (($a + 1 ) < $jmlTable)
            $content .= '';

         ?>
        <table class="tbPrint">
            <tbody>
                <tr>
                    <td class="print" style="text-align:center;" width="20%"><?php echo $model->code?>
                        <br /> <?php echo date('d M Y', strtotime($model->date_trans)) ?></td>
                    <td class="print" style="text-align: center" width="60%">
                        <h4 style="line-height: 10px">PAYMENT VOUCHER</h4> <b>Cash / Bank [ <?php echo $accCoaName?> ]</b></td>
                    <td class="print" style="text-align:center;" width="20%"><?php echo $noApprove ?>
                        <br /> <?php echo $dateApprove ?></td>
                </tr>
                <tr>
                    <td align="left" class="print" colspan="3">Paid To : <?php echo $model->description_to ?></td>
                </tr>
            </tbody>
        </table>
        <div><?php echo $detailCash ?></div>
        <table class="tbPrint">
            <tbody>
                <tr>
                    <td class="print" rowspan="2" style="vertical-align: top" width="180">Giro p p : <?php echo $model->description_giro_an ?></td>
                    <td class="print" width="200">|&nbsp;&nbsp;|Cash|&nbsp;&nbsp;|Cheque|&nbsp;&nbsp;|B. Giro</td>
                    <td class="print">No. :</td>
                </tr>
                <tr align="left">
                    <td class="print">Bank :</td>
                    <td class="print">Date. :</td>
                </tr>
                <tr>
                    <td class="print" colspan="2" style="text-align:center" width="50%">Approved</td>
                    <td class="print" style="text-align:center" width="33%">Received</td>
                </tr>
                <tr height="100">
                    <td class="print" colspan="2" style="border-right:none">&nbsp;</td>
                    <td class="print" style="border-left:none">&nbsp;</td>
                </tr>
            </tbody>
        </table>
        <?php
    }
    ?>
</div>
<script type="text/javascript">
    function printDiv(divName)
    {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
