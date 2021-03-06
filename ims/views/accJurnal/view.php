<?php
$siteConfig = SiteConfig::model()->findByPk(1);
$this->setPageTitle('Lihat Jurnal | : ' . $model->code);
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
        <div class="content">
            <fieldset>
                <?php
                if (isset($_GET['berhasil'])) {
                    echo '<div class="alert alert-success" role="alert">Data Berhasil Disimpan!</div>';
                } else {
                    echo '';
                }
                $date = AccCoaDet::model()->find(array('condition' => 'reff_type="jurnal" and reff_id= ' . $model->id));
                if (isset($date)) {
                    $datePost = $date->date_coa;
                } else {
                    $datePost = '';
                }
                ?>
                <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12', 'disabled' => true)); ?>
                <br>
                <div class="row-fluid" style="margin-left: 0px;">
                    <div class="span12 tab-pane fade active in">
                        <div class="control-group span6">
                            <div class="control-group">
                                <label class="control-label span3">No Transaksi</label>
                                <?php echo $form->textFieldRow($model, 'code', array('class' => 'span6', 'maxlength' => 255, 'disabled' => true, 'labelOptions' => array('label' => false))); ?>
                            </div>
                            <div class="control-group">
                                <label class="control-label span3">Keterangan</label>
                                <?php echo $form->textAreaRow($model, 'description', array('class' => 'span6', 'maxlength' => 255, 'disabled' => true, 'labelOptions' => array('label' => false))); ?>
                            </div>
                        </div>
                        <div class="control-group span6">
                            <div class="control-group">
                                <label class="control-label span3">Tgl Pembuatan</label>
                                <?php echo $form->datepickerRow($model, 'date_trans', array('class' => 'span6', 'maxlength' => 255, 'prepend' => '<i class="icon-calendar"></i>', 'labelOptions' => array('label' => false), 'disabled' => true, 'options' => array('todayBtn' => true, 'todayHighlight' => true, 'startDate' => date('j/m/Y'), 'format' => 'yyyy/m/d'))); ?>

                            </div>
                            <div class="control-group">
                                <label class="control-label span3">Tgl Posting</label>
                                <div class="input-prepend">
                                    <span class="add-on"><i class="icon-calendar"></i></span>
                                    <?php
                                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                        'name' => 'date_post',
                                        'value' => $datePost,
                                        'options' => array(
                                            'showAnim' => 'fold',
                                            'changeMonth' => 'true',
                                            'changeYear' => 'true',
                                            'dateFormat' => 'yy-mm-dd'
                                        ),
                                        'htmlOptions' => array(
                                            'disabled' => true,
                                            'style' => 'height:20px;',
                                            'id' => 'acccoa',
                                            'class' => 'span6',
                                        ),
                                    ));
                                    ?>
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
                                <th>Kredit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($detailJurnal as $val) {
                                if (isset($val->AccCoa->name)) {
                                    $accCoaName = $val->AccCoa->code . ' - ' . $val->AccCoa->name;
                                } else {
                                    $accCoaName = '-';
                                }

                                if (!empty($val->invoice_det_id)) {
                                    if (!empty($val->InvoiceDet->id) && $val->InvoiceDet->type == "customer") {
                                        $customer = empty($val->InvoiceDet->Customer->name) ? '-' : $val->InvoiceDet->Customer->name;
                                        $name = '[' . $val->InvoiceDet->code . '] ' . $customer;
                                    } elseif (!empty($val->InvoiceDet->id) && $val->InvoiceDet->type == "supplier") {
                                        $supplier = empty($val->InvoiceDet->Supplier->name) ? '-' : $val->InvoiceDet->Supplier->name;
                                        $name = '[' . $val->InvoiceDet->code . '] ' . $supplier;
                                    }
                                } else {
                                    $name = "-";
                                }

                                echo '
                                    <tr>
                                        <td>' . $no . '</td>
                                        <td>' . $accCoaName . '</td>
                                        <td>' . $name . '</td>
                                        <td>' . $val->description . '</td>
                                        <td>' . landa()->rp($val->debet, true, 2) . '</td>
                                        <td>' . landa()->rp($val->credit, true, 2) . '</td>
                                    </tr>';
                                $no++;
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4"><b>Total</b></td>
                                <td><?php echo landa()->rp($model->total_debet, true, 2) ?></td>
                                <td><?php echo landa()->rp($model->total_credit, true, 2) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                    <br>

                    </fieldset>
                </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>

    <!--------------------------print------------------------------------->
    <div id="printableArea" style="width: 100%;display: none">
        <?php
        $no = 1;
        $jmlCash = count($detailJurnal);
        $jmlTable = (int) count($detailJurnal) / 8;
        if ($jmlCash <= 8) {
            $batas = 8;
        } else {
            $batas = 8;
        }
        $jmlDebet = 0;
        $jmlCredit = 0;
        $indeks = 0;
        for ($a = 0; $a < $jmlTable; $a++) {
            $detailCash = ' <table class="tbPrint">
                    <tr>
                        <th class="print" width="50" style="text-align:center">#</th>
                        <th class="print" width="130" style="text-align:center">Perkiraan </th>
                        <th class="print" style="text-align:center">Keterangan</th>
                        <th class="print" width="200" style="text-align:center">Debet</th>
                        <th class="print" width="200" style="text-align:center">Credit</th>
                    </tr>';
            if ($a > 0) {
                $detailCash .= '<tr>                        
                                <td class="print" style="text-align:center;border-bottom:none;border-top:none"></td>
                                <td class="print" style="text-align:center;border-bottom:none;border-top:none"></td>                                            
                                <td class="print" style="border-bottom:none;border-top:none">Saldo Lanjutan</td>
                                <td class="print" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($jmlDebet, false) . '</td>    
                                <td class="print" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($jmlCredit, false) . '</td>    
                           </tr>';
            }
            for ($i = 0; $i < $batas; $i++) {
                $val = $detailJurnal;

                if (!empty($val[$indeks])) {
                    if ($val[$indeks]->AccCoa !== NULL) {
                        $accCoaName = $val[$i]->AccCoa->code;
                    } else {
                        $accCoaName = '-';
                    }
                    $detailCash .= '<tr>                        
                                <td class="print" style="text-align:center;border-bottom:none;border-top:none">' . $no . '</td>
                                <td class="print" style="text-align:center;border-bottom:none;border-top:none">' . $accCoaName . '</td>                                            
                                <td class="print" style="border-bottom:none;border-top:none">' . $val[$indeks]->description . '</td>
                                <td class="print" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($val[$indeks]->debet, false) . '</td>    
                                <td class="print" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($val[$indeks]->credit, false) . '</td>    
                           </tr>';
                    $jmlDebet += $val[$indeks]->debet;
                    $jmlCredit += $val[$indeks]->credit;
                } else {
                    $detailCash .= '<tr>                        
                                <td class="print" style="border-bottom:none;border-top:none">&nbsp;</td>
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
                        <td class="print" colspan="3"></td>
                        <td class="print" style="text-align:right">' . landa()->rp($jmlDebet, false) . '</td>
                        <td class="print" style="text-align:right">' . landa()->rp($jmlCredit, false) . '</td>
                    </tr>
                    </table>';

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
            ?>
            <table class="tbPrint">
                <tbody>
                    <tr>
                        <td class="print" colspan="3">
                            <h3 style="margin: 0px">PT. KARYA TUGAS ANDA</h3> </td>
                    </tr>
                    <tr class="print" style="padding:0px">
                        <td width="35%">&nbsp;</td>
                        <td colspan="2">
                            <h2 style="margin: 0px">Jurnal Memorial</h2> </td>
                    </tr>
                    <tr class="print" style="padding:0px">
                        <td>&nbsp;</td>
                        <td width="10">NO</td>
                        <td>: <?php echo $model->code?></td>
                    </tr>
                    <tr class="print" style="padding:0px">
                        <td>&nbsp;</td>
                        <td width="10">TGL.</td>
                        <td>: <?php echo date('d M Y', strtotime($model->date_trans))?></td>
                    </tr>
                </tbody>
            </table>
            <div><?php echo $detailCash?></div>
            <table class="tbPrint">
                <tbody>
                    <tr class="print">
                        <td height="70" valign="top">Penjelasan :</td>
                    </tr>
                </tbody>
            </table>
            <table class="tbPrint">
                <tbody>
                    <tr>
                        <td class="print" style="text-align:center" width="30%">Pimpinan</td>
                        <td class="print" style="text-align:center">Administrasi</td>
                        <td class="print" style="text-align:center" width="30%">Disetujui Oleh</td>
                    </tr>
                    <tr height="60">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr class="print">
                        <td>TGL.</td>
                        <td>TGL.</td>
                        <td>TGL.</td>
                    </tr>
                </tbody>
            </table>

            <?php
//            $content = $siteConfig->report_jurnal;
//            $content = str_replace('{jurnal}', $model->code, $content);
//            $content = str_replace('{date}', date('d M Y', strtotime($model->date_trans)), $content);
//            $content = str_replace('{detail_cash}', $detailCash, $content);
//            $content = str_replace('{tellerName}', (isset($model->User->name)) ? $model->User->name : '', $content);
//            $content = str_replace('{tellerApprove}', date('d M Y', strtotime($model->created)), $content);
//            $content = str_replace('{adminName}', $adminName, $content);
//            $content = str_replace('{adminApprove}', $adminDate, $content);
//            $content = str_replace('{managerName}', $managerName, $content);
//            $content = str_replace('{managerApprove}', $managerDate, $content);
//            $content = str_replace("{no_approval}", $noApprove, $content);
//            $content = str_replace("{date_approval}", $dateApprove, $content);
//            echo $content . "<br>";
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
