<style>
    input, .select2-container, .input-prepend{
        margin:0 !important;
    }
</style>
<?php
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}
?>
<div class="form">
    <?php
    $siteConfig = SiteConfig::model()->findByPk(1);
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'acc-cash-out-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'vertical',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data',
        )
    ));
    ?>  
    <div class="box invoice">

        <div class="title clearfix">   
            <h4 class="left">
                <span class="number"><?php echo (isset($model->code_acc)) ? '#' . $model->code_acc : ''; ?></span>
                <br><span class="data gray"><?php echo (isset($model->date_posting)) ? date('d-M-Y', strtotime($model->date_posting)) : ''; ?></span>
            </h4>
        </div>

        <div class="content">
            <?php
            if (isset($_POST['AccCashOut']['code'])) {
                $code = $_POST['AccCashOut']['code'];
            } else {
                $code = $model->code;
            }
            if (isset($_POST['AccCashOut']['description'])) {
                $desc = $_POST['AccCashOut']['description'];
            } else {
                $desc = $model->description;
            }
            if (isset($_POST['AccCashOut']['date_trans'])) {
                $date = $_POST['AccCashOut']['date_trans'];
            } else {
                $date = $model->date_trans;
            }
            if (isset($_POST['AccCashOut']['accCoa'])) {
                $accCoa = $_POST['AccCashOut']['accCoa'];
            } else {
                $accCoa = $model->acc_coa_id;
            }
            if (isset($_POST['totalKredit'])) {
                $totalKredit = $_POST['totalKredit'];
            } else {
                $totalKredit = $model->total;
            }
            ?>
            <fieldset>

                <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error')); ?>

                <br>
                <div class="row-fluid" style="margin-left: 0px;">
                    <div class="control-group span6">
                            <div class="control-group">
                                <label class="control-label span3">Tgl Pembuatan</label>
                                <?php echo $form->hiddenField($model, 'code', array('class' => 'span4', 'maxlength' => 255, 'value' => $code, 'readonly' => true)); ?>
                                 <?php echo $form->textFieldRow($model, 'date_trans', array('class' => 'span6', 'readonly' => true, 'maxlength' => 255,'prepend' => '<i class="icon-calendar"></i>','labelOptions' => array('label' => false))); ?>
                           
                            </div>
                           <div class="control-group">
                                <label class="control-label span3">Dibayar Kepada</label>
                                <?php echo $form->textFieldRow($model, 'description_to', array('class' => 'span6', 'maxlength' => 255, 'labelOptions' => array('label' => false))); ?>
                            </div>
                            <div class="control-group">
                                <label class="control-label span3">Keterangan</label>
                                <?php echo $form->textAreaRow($model, 'description', array('class' => 'span6', 'value' => $desc, 'maxlength' => 255, 'labelOptions' => array('label' => false))); ?>
                            </div>
                    </div>
                    <div class="control-group span6">
                            <div class="control-group">
                                <label class="control-label span3">Keluar Dari</label>
                                <?php
                                $accessCoa = AccCoa::model()->accessCoa();

                                $data = array(0 => 'Pilih') + CHtml::listData(AccCoa::model()->findAll(array('condition' => $accessCoa, 'order' => 'root, lft')), 'id', 'fullName');
                                $this->widget('bootstrap.widgets.TbSelect2', array(
                                    'asDropDownList' => TRUE,
                                    'data' => $data,
                                    'name' => 'AccCashOut[accCoa]',
                                    'value' => (isset($accCoa) ? $accCoa : ''),
                                    'options' => array(
                                        "placeholder" => 'Pilih',
                                        "allowClear" => true,
                                    ),
                                    'htmlOptions' => array(
                                        'id' => 'AccCashOut_account',
                                        'style' => 'width:350px;'
                                    ), 'events' => array('change' => 'js: function(){
                                            checkSelected();

                                        }')
                                ));
                                ?>
                            </div>
                            <div class="control-group">
                                <label class="control-label span3" style="margin-left: -115px;">Total Kredit</label>
                                <div class="input-prepend">
                                    <span class="add-on">Rp.</span>
                                    <?php echo CHtml::textfield('totalDebit', $totalKredit, array('class' => 'angka', 'maxlength' => 255)); ?>
                                </div>
                            </div>
                           <div class="control-group">
                                <label class="control-label span3" >Giro A.N</label>
                                <?php echo $form->textFieldRow($model, 'description_giro_an', array('class' => 'span6', 'maxlength' => 255, 'labelOptions' => array('label' => false))); ?>
                            </div>
                    </div>
                </div>
            </fieldset>
        </div>
        <?php
        $this->beginWidget(
                'bootstrap.widgets.TbModal', array(
            'id' => 'modalSub',
            'htmlOptions' => array(
                'style' => 'width:1000px;margin-left:-500px;'
            )
                )
        );
        ?>

        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <h4>Select Subledger</h4>
        </div>

        <div class="modal-body isiModal">

        </div>

        <div class="modal-footer">
        </div>

        <?php $this->endWidget(); ?>
    </div>
    <div class="box invoice">
        <div class="title clearfix">
            <h4 class="left">
                Detail Dana
            </h4>
        </div>
        <div class="content" style="padding: 0 !important">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="250">Kode Rekening</th>
                        <th width="150">Sub Ledger</th>
                        <th width="300">Keterangan</th>
                        <th style="width:5%">Debit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class='insertNew'>
                        <td style="text-align: center">
                            <input type="hidden" name="subledgerid" value="" class="inVoiceDet">
                            <?php
                            echo CHtml::ajaxLink(
                                    $text = '<i class="icon-plus-sign"></i>', $url = url('AccCashOut/addRow'), $ajaxOptions = array(
                                'type' => 'POST',
                                'success' => 'function(data){ 
                                                        calculate();
                                                        $("#addRow").replaceWith(data);   
                                                        clear();
                                                        $(".totalDet").on("keyup", function() {
                                                         var subTotal=0;
                                                         $(".totalDet").each(function() {
                                                            subTotal += parseInt($(this).val());
                                                         });
                                                            $("#AccCashOut_total").val(subTotal);
                                                         var selisih = parseInt($("#totalKredit").val()) - parseInt(subTotal);
                                                         $("#difference").val(selisih);
                                                         });
    
                                                         $("#account").select2("focus");
                                                         $(".newRow").find(".selectDua").select2();
                                                            $(".newRow").removeClass("newRow");
                                                            $(".insertNew").find(".inVoiceDet").val("0");
                                                            $(".insertNew").find(".subLedgerField").html("<a style=\"display:none\" class=\"btn showModal\">Sub Ledger</a>");
                                                           
                                        }'), $htmlOptions = array('id' => 'btnAdd', 'class' => 'btn')
                            );
                            ?>
                        </td>
                        <td>
                            <?php
                            $data = array(0 => 'Pilih') + CHtml::listData(AccCoa::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
                            $this->widget('bootstrap.widgets.TbSelect2', array(
                                'asDropDownList' => TRUE,
                                'data' => $data,
                                'name' => 'account',
                                'options' => array(
                                    "placeholder" => 'Pilih',
                                    "allowClear" => true,
                                ),
                                'htmlOptions' => array(
                                    'id' => 'account',
                                    'style' => 'width:100%;',
                                    'class' => 'subLedger'
                                ), 'events' => array('change' => 'js: function() {
                                                     var elements = $(this).parent().parent().find(".showModal");
                                                    checkSelected();
                                                    retAccount($(this).val(),elements);
                                            }')
                            ));
                            ?>
                        </td>
                        <td class="subLedgerField" style="text-align: center">
                            <?php
                            $this->widget(
                                    'bootstrap.widgets.TbButton', array(
                                'label' => 'Sub Ledger',
                                'htmlOptions' => array(
                                    'style' => 'display:none',
                                    'class' => 'showModal',
                                ),
                                    )
                            );
                            ?>
                        </td>
                        <td>
                            <?php echo CHtml::textfield('costDescription', '', array('style' => 'width:95%;', 'maxlength' => 255)); ?>
                        </td>
                        <td>
                            <div class="input-prepend">
                                <span class="add-on">Rp.</span>
                                <?php
                                echo CHtml::textField('debit', '0', array(
                                    'class' => 'angka',
                                    'maxlength' => 60,
                                    'prepend' => 'Rp',
                                ));
                                ?>
                            </div>
                        </td>
                    </tr>
                    <?php
                    $invoiceName = '';
                    if ($model->isNewRecord == false and ! isset($_POST['AccCashOutDet'])) {
                        $i = 0;
                        $name = '';
                        $id = 0;
                        foreach ($cashOutDet as $viewCashOutDet) {
                            $invoice = (!empty($viewCashOutDet->invoice_det_id)) ? $viewCashOutDet->invoice_det_id : 0;
                            $code = (!empty($viewCashOutDet->InvoiceDet->code)) ? $viewCashOutDet->InvoiceDet->code : "-";

                            if ($viewCashOutDet->AccCoa !== NULL) {
                                $accCoaName = $viewCashOutDet->AccCoa->code . ' - ' . $viewCashOutDet->AccCoa->name;
                            } else {
                                $accCoaName = ' - ';
                            }

                            if (!empty($viewCashOutDet->invoice_det_id)) {
//                                $account = InvoiceDet::model()->findByPk($viewCashOutDet->ar_id);
                                if (!empty($viewCashOutDet->InvoiceDet->id) && $viewCashOutDet->InvoiceDet->type == "customer") {
                                    $name = empty($viewCashOutDet->InvoiceDet->Customer->name) ? '-' : $viewCashOutDet->InvoiceDet->Customer->name;
                                    $id = $viewCashOutDet->InvoiceDet->user_id;
                                } elseif (!empty($viewCashOutDet->InvoiceDet->id) && $viewCashOutDet->InvoiceDet->type == "supplier") {
                                    $name = empty($viewCashOutDet->InvoiceDet->Supplier->name) ? '-' : $viewCashOutDet->InvoiceDet->Supplier->name;
                                    $id = $viewCashOutDet->InvoiceDet->user_id;
                                }
                            } else {
                                $name = "-";
                                $id = 0;
                            }
                            $invoiceName = (!empty($viewCashOutDet->InvoiceDet->code) && !empty($name)) ? '<a class="btn btn-mini removeSub"><i class=" icon-remove-circle"></i></a>[' . $viewCashOutDet->InvoiceDet->code . ']' . $name : '';
                            if (isset($_POST['AccCashOutDet'])) {
                                $amount = $_POST['AccCashOutDet']['amount'][$i];
                            } else {
                                $amount = $viewCashOutDet->amount;
                            }
                            $disp = ($viewCashOutDet->AccCoa->type_sub_ledger == 'ar' || $viewCashOutDet->AccCoa->type_sub_ledger == 'as' || $viewCashOutDet->AccCoa->type_sub_ledger == 'ap') ? true : false;
                            $display = (empty($invoiceName) && $disp) ? '' : 'none';
                            $i++;

                            echo '  <tr>
                                            <td style="text-align:center">
                                                <input type="hidden" class="nameAccount" name="nameAccount[]" id="nameAccount[]" value="' . $id . '"/>
                                                <input type="hidden" class="inVoiceDet" name="inVoiceDet[]" class="inVoiceDet" id="inVoiceDet[]" value="' . $invoice . '"/>
                                                <span class="btn"><i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i></span>
                                                        <td>';
                            echo '<select class="selectDua subLedger" style="width:100%" name="AccCashOutDet[acc_coa_id][]" id="AccCashOutDet[acc_coa_id][]">';

                            foreach ($data as $key => $val) {
                                $value = ($key == $viewCashOutDet->acc_coa_id) ? 'selected="selected"' : '';
                                echo '<option ' . $value . ' value="' . $key . '">' . $val . '</option>';
                            }
                            echo '</select>';
                            echo '</td>
                                            <td style="text-align:center"  class="subLedgerField">' . $invoiceName . '<a style="display:' . $display . '" class="btn showModal">Sub Ledger</a></td>
                                            <td><input type="text" name="AccCashOutDet[description][]" id="AccCashOutDet[description][]" style="width:95%" value="' . $viewCashOutDet->description . '"/></td>
                                            <td><div class="input-prepend"> <span class="add-on">Rp.</span><input type="text" name="AccCashOutDet[amount][]" id="AccCashOutDet[amount][]" class="angka totalDet" value="' . $amount . '"/></div></td>
                                        </tr>';
                        }
                    }if (isset($_POST['AccCashOutDet'])) {
                        for ($i = 0; $i < count($_POST['AccCashOutDet']['acc_coa_id']); $i++) {
                            $accCoa = AccCoa::model()->find(array('condition' => 'id=' . $_POST['AccCashOutDet']['acc_coa_id'][$i]));
                            $name = $accCoa->name;
                            $id = $accCoa->id;
                            if (!empty($viewCashOutDet->invoice_det_id)) {
//                                $account = InvoiceDet::model()->findByPk($viewCashOutDet->ar_id);
                                if (!empty($viewCashOutDet->InvoiceDet->id) && $viewCashOutDet->InvoiceDet->type == "customer") {
                                    $name = empty($viewCashOutDet->InvoiceDet->Customer->name) ? '-' : $viewCashOutDet->InvoiceDet->Customer->name;
                                    $id = $viewCashOutDet->InvoiceDet->user_id;
                                } elseif (!empty($viewCashOutDet->InvoiceDet->id) && $viewCashOutDet->InvoiceDet->type == "supplier") {
                                    $name = empty($viewCashOutDet->InvoiceDet->Supplier->name) ? '-' : $viewCashOutDet->InvoiceDet->Supplier->name;
                                    $id = $viewCashOutDet->InvoiceDet->user_id;
                                }
                            } else {
                                $name = "-";
                                $id = 0;
                            }

                            echo '  <tr>
                                            <td>
                                                <input type="hidden" class="nameAccount" name="nameAccount[]" id="nameAccount[]" value="' . $id . '"/>
                                                <input type="hidden" class="inVoiceDet" name="inVoiceDet[]" id="inVoiceDet[]" value="' . $_POST['inVoiceDet'][$i] . '"/>
                                                <span class="btn"><i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i></span>
                                            </td>';

                            echo '<td><select class="selectDua subLedger" style="width:100%" name="AccCashOutDet[acc_coa_id][]" id="AccCashOutDet[acc_coa_id][]">';
                            foreach ($data as $key => $val) {
                                $value = ($key == $_POST['AccCashOutDet']['acc_coa_id'][$i]) ? 'selected="selected"' : '';
                                echo '<option ' . $value . ' value="' . $key . '">' . $val . '</option>';
                            }
                            echo '<option value="0">Pilih</option>';
                            echo '</select></td>
                                            <td style="text-align:center"  class="subLedgerField">' . $name . '<a style="display:none" class="btn showModal">Sub Ledger</a></td>
                                            <td><input type="text" name="AccCashOutDet[description][]" id="AccCashOutDet[description][]" style="width:95%"  value="' . $_POST['AccCashOutDet']['description'][$i] . '"/></td>
                                            <td><div class="input-prepend"> <span class="add-on">Rp.</span><input type="text" name="AccCashOutDet[amount][]" id="AccCashOutDet[amount][]" class="angka totalDet" value="' . $_POST['AccCashOutDet']['amount'][$i] . '"/></div></td>
                                        </tr>';
                        }
                    }
                    ?>
                    <tr id="addRow" style="display:none">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr id="deletedRow" style="display:none">
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" valign="middle" align="center"><b>Total Debit</b></td>
                        <td><?php
                            if (isset($_POST['AccCashOut']['total'])) {
                                $total = $_POST['AccCashOut']['total'];
                            } else {
                                $total = $model->total;
                            }
                            echo $form->textFieldRow($model, 'total', array('class' => 'angka', 'readonly' => true, 'maxlength' => 60, 'value' => $total, 'prepend' => 'Rp.', 'label' => false));
                            ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" valign="middle" align="center"><b>Selisih</b></td>
                        <td>
                            <div class="input-prepend">
                                <?php
                                if (isset($_POST['AccCashOut'])) {
                                    $diff = $_POST['totalKredit'] - $_POST['AccCashOut']['total'];
                                } else {
                                    $diff = 0;
                                }
                                ?>
                                <span class="add-on">Rp.</span>
                                <?php
                                echo CHtml::textField('difference', $diff, array(
                                    'class' => 'angka',
                                    'maxlength' => 60,
                                    'prepend' => 'Rp',
                                    'readonly' => true,
                                    'id' => 'difference'
                                ));
                                ?>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class = "form-actions">
        <?php
        $act = (isset($_GET['act'])) ? $_GET['act'] : '';
        if ($model->isNewRecord || ($act != "approve" && empty($model->date_posting))) {
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'icon' => 'ok white',
                'label' => 'Simpan Perubahan',
            ));
        } else {

            $this->beginWidget(
                    'bootstrap.widgets.TbModal', array('id' => 'myModal', 'htmlOptions' => array('style' => 'width:450px;left:60%;'))
            );
            ?>
            <div class="modal-header">
                <a class="close" data-dismiss="modal">&times;</a>
                <h4>Persetujuan</h4>
            </div>
            <div class="modal-body" align="left">
                <table>
                    <tr>
                        <th>
                            <label for="Date_Post">Tanggal Posting</label>
                        </th>
                        <th>
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-calendar"></i></span>
                        <?php
                        if ($siteConfig->date_system != "0000-00-00") {
                            $dateSystem = $siteConfig->date_system;
                        } else {
                            $dateSystem = date("Y-m-d");
                        }

                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'name' => 'date_post',
                            'value' => (empty($model->date_posting)) ? date("Y-m-d") : $model->date_posting,
                            'options' => array(
                                'minDate' => $dateSystem,
                                'showAnim' => 'fold',
                                'changeMonth' => 'true',
                                'changeYear' => 'true',
                                'dateFormat' => 'yy-mm-dd'
                            ),
                            'htmlOptions' => array(
                                'style' => 'height:20px;',
                                'class' => 'span2',
                            ),
                        ));
                        ?>
                    </div>
                    </th>

                    </tr>
                    <?php
//                    if ($siteConfig->autopostnumber == 0) {
                    ?>
                    <tr>
                        <th>
                            <label>No Posting </label>
                        </th>
                        <th>
                            <?php
                            echo CHtml::textfield('codeAcc', (isset($model->code_acc)) ? $model->code_acc : '', array('maxlength' => 255, 'placeholder' => 'Kosongkan untuk generate otomatis'));
                            ?>
                        </th>
                    </tr>
                    <?php
//                    }
                    ?>
                </table>
            </div>

            <div class="modal-footer">
                <?php
                $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'submit',
                    'type' => 'primary',
                    'icon' => 'ok white',
                    'label' => $model->isNewRecord ? 'Approve' : 'Simpan',
                ));
                ?>
                <?php
                $this->widget(
                        'bootstrap.widgets.TbButton', array(
                    'label' => 'Close',
                    'url' => '#',
                    'htmlOptions' => array('data-dismiss' => 'modal'),
                        )
                );
                ?>
            </div>
            <?php
            $this->endWidget();
            $this->widget(
                    'bootstrap.widgets.TbButton', array(
                'label' => 'Simpan',
                'type' => 'primary',
                'icon' => 'ok white',
                'htmlOptions' => array(
                    'data-toggle' => 'modal',
                    'data-target' => '#myModal',
                ),
                    )
            );
        }
        ?>
    </div>
    <?php $this->endWidget(); ?>
</div>
<script type="text/javascript">
    $("body").on("click", ".delRow", function () {
        $(this).parent().parent().parent().remove();
        calculateMin();
    });
    function calculate() {
        var debet = $("#debit").val();
        if (!debet.length) {
            debet = 0;
        }
        var totalAkhir = parseFloat($("#AccCashOut_total").val()) + parseFloat(debet);
        $("#AccCashOut_total").val(totalAkhir);
        var selisih = parseFloat($("#totalKredit").val()) - parseFloat(totalAkhir);
        $("#difference").val(selisih);
    }

    function calculateMin() {
        var subTotal = 0;
        $(".totalDet").each(function () {
            subTotal += parseFloat($(this).val());
        });
        $("#AccCashOut_total").val(subTotal);
        var selisih = parseFloat($("#totalKredit").val()) - parseFloat(subTotal);
        $("#difference").val(selisih);
    }

    $("#totalKredit").on("keyup", function () {
        var subTotal = 0;
        $(".totalDet").each(function () {
            subTotal += parseFloat($(this).val());
        });
        $("#AccCashOut_total").val(subTotal);
        var selisih = parseFloat($("#totalKredit").val()) - parseFloat(subTotal);
        $("#difference").val(selisih);
    });

    $(".totalDet").on("keyup", function () {
        var subTotal = 0;
        $(".totalDet").each(function () {
            subTotal += parseFloat($(this).val());
        });
        $("#AccCashOut_total").val(subTotal);
        var selisih = parseFloat($("#totalKredit").val()) - parseFloat(subTotal);
        $("#difference").val(selisih);
    });

    function rp(angka) {
        var rupiah = "";
        var angkarev = angka.toString().split("").reverse().join("");
        for (var i = 0; i < angkarev.length; i++)
            if (i % 3 == 0)
                rupiah += angkarev.substr(i, 3) + ".";
        return rupiah.split("", rupiah.length - 1).reverse().join("");
    }

    function clear() {
        $("#debit").val("0");
        $("#costDescription").val("");
        $("#s2id_account").select2("data", "0");
        $("#s2id_accountName").select2("data", "0");
    }

    $("#costDescription,#debit").keypress(function (e) {
        if (e.which == 13) {
            $("#btnAdd").trigger("click");
            e.preventDefault();
        }
    });

    function checkSelected() {
        var val1 = $("#AccCashIn_account").val();
        var val2 = $("#account").val();
        var nol = 0;

        if (val1 === val2) {
            $.toaster({priority: 'error', message: "debet dan kredit tidak boleh sama"});
            $("#account").select2('val', nol);
        } else {
            //do nothing
        }
    }

    $("body").on("click", ".ambil", function () {
        var id = $(this).attr("det_id");
        var userId = $(this).attr("user_id");
        var nilai = $(this).attr("nilai");
        var acc = $(this).attr("account");
        var code = $(this).attr("code");
        /*var subledger = $("#subLedgers").html();*/
        var dell = '<a class="btn btn-mini removeSub"><i class=" icon-remove-circle"></i></a>';

        $(".appeared").find(".subLedgerField").html(dell + '[ ' + code + ' ]' + acc);
        $(".appeared").find(".inVoiceDet").val(id);
//        $(".appeared").find(".totalDet").val(nilai);
        $("#modalSub").modal("hide");
        $(".appeared").removeClass('appeared');
        calculate();
        calculateMin();
    });

    function removeSub(elements) {
        $(elements).html('<a style="display:" class="btn showModal">Sub Ledger</a>');
    }
    $("body").on("click", ".removeSub", function () {
        $(this).parent().parent().find(".inVoiceDet").val(0);
        var elements = $(this).parent();
        removeSub(elements);
    });
    $("body").on("change", "#accountName", function () {
        selectInvoice();
    });
    function selectInvoice() {
        var id = $("#accountName").val();
        var type = $("#type_account").val();
        $.ajax({
            type: 'POST',
            url: "<?php echo url('accCoa/selectInvoice') ?>",
            data: {id: id, type: type},
            success: function (data) {
                $("#detail").html(data);
            }
        });
    }

    $("body").on("keyup", "#invoice_amount", function () {
        var value = parseInt($(this).val());
        $("#credit").val(value);
    });

    $("body").on("click", ".addNewInvoice", function () {
        var code = $("#code_invoice").val();
        var user_id = $("#accountName").val();
        var type = $("#type_invoice").val();
        var term_date = $("#AccCashIn_date_trans").val();
        var description = $("#invoice_description").val();
        var amount = parseInt($("#invoice_amount").val());
        if (amount !== 0 || amount !== "" || code !== "") {
            $.ajax({
                type: 'post',
                data: {code: code, description: description, user_id: user_id, amount: amount, type: type, term_date: term_date},
                url: "<?php echo url('accCoa/newInvoice'); ?>",
                success: function (data) {
                    if (data == 1) {
                        selectInvoice();
                    }
                }
            });
        } else {
            $.toaster({priority: 'error', message: "Code atau nilai belum di inputkan"});
        }
    });
    $(document).ready(function () {
        selectDua();
    });

    function selectDua() {
        $(".selectDua").select2();
    }

    function retAccount(id, elements) {

        $.ajax({
            url: "<?php echo url('accCoa/retAccount') ?>",
            type: "POST",
            data: {ledger: id},
            success: function (data) {
//                console.log(data);
                obj = JSON.parse(data);
                $(".isiModal").html(obj.render);
                $("#type_account").val(obj.type);
                if (obj.tampil == true) {
                    elements.attr('style', 'display:');
                } else {
                    elements.attr('style', 'display:none');
                }
            }
        });
    }

    $("body").on("change", ".subLedger", function () {
        var id = $(this).val();
        var elements = $(this).parent().parent().find(".showModal");
        retAccount(id, elements);
    });
    $("body").on("click", ".showModal", function () {
        var id = $(this).parent().parent().find("select.subLedger").val();
        var elements = $(this);
        retAccount(id, elements);
        $(this).parent().parent().addClass("appeared");
        $("#modalSub").modal("show");
    });
    $("#modalSub").on('hidden', function () {
        $(".appeared").removeClass('appeared');
    });

    $("#yw5").on("click", function () {
        if ($("#difference").val() == 0) {
            return true;
        } else {
            $.toaster({priority: 'error', message: "Total Debet dan Kredit Harus Sama"});
            return false;
        }
    });
</script>
