<?php
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}
?>
<div class="form">
    <?php
    $siteConfig = SiteConfig::model()->findByPk(param('id'));
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
    <div class="box gradient invoice">

        <div class="title clearfix">

            <h4 class="left">
                <span class="number"><?php echo (isset($model->code_acc)) ? '#' . $model->code_acc : ''; ?></span></br>
                <span class="data gray"><?php echo (isset($model->date_posting)) ? date('d-M-Y', strtotime($model->date_posting)) : ''; ?></span>
            </h4>
            <div class="invoice-info">
                <span class="number"></span>
                <span class="data gray"></span>
                <div class="clearfix"></div>
            </div>

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
                <legend>
                    <p class="note">Fields dengan <span class="required">*</span> harus di isi.</p>
                </legend>

                <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error')); ?>

                <br>
                <div class="row" style="margin-left: 0px;">
                    <table width="100%">
                        <tr>
                            <td width="50%"><?php echo $form->textFieldRow($model, 'code', array('class' => 'span2', 'maxlength' => 255, 'value' => $code, 'readonly' => true)); ?></td>
                            <td width="50%"> <label for="AccCashOut_accCoa">Keluar dari</label>
                                <?php
                                $accessCoa = AccCoa::model()->accessCoa();

                                $data = array(0 => t('choose', 'global')) + CHtml::listData(AccCoa::model()->findAll(array('condition' => $accessCoa, 'order' => 'root, lft')), 'id', 'nestedname');
                                $this->widget('bootstrap.widgets.TbSelect2', array(
                                    'asDropDownList' => TRUE,
                                    'data' => $data,
                                    'name' => 'AccCashOut[accCoa]',
                                    'value' => (isset($accCoa) ? $accCoa : ''),
                                    'options' => array(
                                        "placeholder" => t('choose', 'global'),
                                        "allowClear" => true,
                                    ),
                                    'htmlOptions' => array(
                                        'id' => 'AccCashOut_account',
                                        'style' => 'width:250px;'
                                    ), 'events' => array('change' => 'js: function(){
                                            checkSelected();

                                        }')
                                ));
                                ?></td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo $form->textFieldRow($model, 'date_trans', array('class' => 'span2', 'readonly' => true, 'maxlength' => 255)); ?>
                            </td>
                            <td><label for="TotalKredit">Total Kredit</label>
                                <div class="input-prepend">
                                    <span class="add-on">Rp.</span>
                                    <?php echo CHtml::textfield('totalKredit', $totalKredit, array('class' => 'angka', 'maxlength' => 255)); ?>
                                </div></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->textAreaRow($model, 'description', array('class' => 'span4', 'maxlength' => 255, 'value' => $desc)); ?></td>
                            <td><?php echo $form->textFieldRow($model, 'description_to', array('class' => 'span4', 'maxlength' => 255)); ?></td>

                        </tr>
                        <tr>
                            <td></td>
                            <td><?php echo $form->textFieldRow($model, 'description_giro_an', array('class' => 'span4', 'maxlength' => 255)); ?></td>
                        </tr>
                    </table>
                </div>
                <h4>Detail Dana</h4>
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
                        <tr>
                            <td style="text-align: center"><?php
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
                                                         removeSub();
                                                          $("#showModal").attr("style","display:none");
                                        }'), $htmlOptions = array('id' => 'btnAdd', 'class' => 'btn')
                                );
                                ?>
                            </td>
                            <td>
                                <?php
                                $data = array(0 => t('choose', 'global')) + CHtml::listData(AccCoa::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
                                $this->widget('bootstrap.widgets.TbSelect2', array(
                                    'asDropDownList' => TRUE,
                                    'data' => $data,
                                    'name' => 'account',
                                    'options' => array(
                                        "placeholder" => t('choose', 'global'),
                                        "allowClear" => true,
                                    ),
                                    'htmlOptions' => array(
                                        'id' => 'account',
                                        'style' => 'width:100%;'
                                    ), 'events' => array('change' => 'js: function() {
                                                     checkSelected();
                                                     $.ajax({
                                                        url : "' . url('accCoa/retAccount') . '",
                                                        type : "POST",
                                                        data :  { ledger :  $(this).val()},
                                                        success : function(data){
                                                                $("#s2id_accountName").select2("data", "0")
                                                                $("#accountName").html(data);
                                                                if(data != ""){
                                                                        $("#showModal").attr("style","display:");
                                                                    }else {
                                                                        $("#showModal").attr("style","display:none");
                                                                    }
                                                                }
                                                     });
                                            }')
                                ));
                                ?>
                            </td>
                            <td id="subLedgers" style="text-align: center">
                                <?php
                                $this->widget(
                                        'bootstrap.widgets.TbButton', array(
                                    'label' => 'Select Sub-Ledger',
                                    'id' => 'showModal',
                                    'htmlOptions' => array(
                                        'data-toggle' => 'modal',
                                        'data-target' => '#modalSub',
                                        'style' => 'display:none'
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
                        <tr id="addRow" style="display:none">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
                        if ($model->isNewRecord == false and ! isset($_POST['AccCashOutDet'])) {
                            $i = 0;
                            foreach ($cashOutDet as $viewCashOutDet) {
                                $invoice = (!empty($viewCashOutDet->invoice_det_id)) ? $viewCashOutDet->invoice_det_id : 0;
                                $code = (!empty($viewCashOutDet->InvoiceDet->code)) ? $viewCashOutDet->InvoiceDet->code : "-";

                                if ($viewCashOutDet->AccCoa !== NULL) {
                                    $accCoaName = $viewCashOutDet->AccCoa->code . ' - ' . $viewCashOutDet->AccCoa->name;
                                } else {
                                    $accCoaName = ' - ';
                                }

                                if (!empty($viewCashOutDet->ar_id)) {
                                    $account = User::model()->findByPk($viewCashOutDet->ar_id);
                                    $name = $account->name;
                                    $id = $account->id;
                                } else if (!empty($viewCashOutDet->ap_id)) {
                                    $account = User::model()->findByPk($viewCashOutDet->ap_id);
                                    $name = $account->name;
                                    $id = $account->id;
                                } else if (!empty($viewCashOutDet->as_id)) {
                                    $account = Product::model()->findByPk($viewCashOutDet->as_id);
                                    $name = $account->name;
                                    $id = $account->id;
                                } else {
                                    $account = (object) array('id' => '', 'name' => '');
                                    $name = "-";
                                    $id = "0";
                                }

                                if (isset($_POST['AccCashOutDet'])) {
                                    $amount = $_POST['AccCashOutDet']['amount'][$i];
                                } else {
                                    $amount = $viewCashOutDet->amount;
                                }

                                $i++;

                                echo '  <tr>
                                            <td style="text-align:center">
                                                <input type="hidden" name="AccCashOutDet[acc_coa_id][]" id="AccCashOutDet[acc_coa_id][]" value="' . $viewCashOutDet->acc_coa_id . '"/>
                                                <input type="hidden" class="nameAccount" name="nameAccount[]" id="nameAccount[]" value="' . $id . '"/>
                                                <input type="hidden" class="inVoiceDet" name="inVoiceDet[]" id="inVoiceDet[]" value="' . $invoice . '"/>
                                                <span class="btn"><i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i></span>
                                            </td>
                                            <td>' . $accCoaName . '</td>
                                            <td>[ ' . $code . ' ]' . $name . '</td>
                                            <td><input type="text" name="AccCashOutDet[description][]" id="AccCashOutDet[description][]" style="width:95%" value="' . $viewCashOutDet->description . '"/></td>
                                            <td><div class="input-prepend"> <span class="add-on">Rp.</span><input type="text" name="AccCashOutDet[amount][]" id="AccCashOutDet[amount][]" class="angka totalDet" value="' . $amount . '"/></div></td>
                                        </tr>';
                            }
                        }if (isset($_POST['AccCashOutDet'])) {
                            for ($i = 0; $i < count($_POST['AccCashOutDet']['acc_coa_id']); $i++) {
                                $accCoa = AccCoa::model()->find(array('condition' => 'id=' . $_POST['AccCashOutDet']['acc_coa_id'][$i]));
                                $name = $accCoa->name;
                                $id = $accCoa->id;
                                if (!empty($_POST['nameAccount'][$i])) {

                                    if ($accCoa->type_sub_ledger == "ar")
                                        $account = User::model()->findByPk($_POST['nameAccount'][$i]);

                                    if ($accCoa->type_sub_ledger == "ap")
                                        $account = User::model()->findByPk($_POST['nameAccount'][$i]);

                                    if ($accCoa->type_sub_ledger == "as")
                                        $account = Product::model()->findByPk($_POST['nameAccount'][$i]);

                                    $name = $account->name;
                                    $id = $account->id;
                                } else {
                                    $name = "-";
                                    $id = "";
                                }

                                echo '  <tr>
                                            <td>
                                                <input type="hidden" name="AccCashOutDet[acc_coa_id][]" id="AccCashOutDet[acc_coa_id][]" value="' . $_POST['AccCashOutDet']['acc_coa_id'][$i] . '"/>
                                                <input type="hidden" class="nameAccount" name="nameAccount[]" id="nameAccount[]" value="' . $id . '"/>
                                                <input type="hidden" class="inVoiceDet" name="inVoiceDet[]" id="inVoiceDet[]" value="' . $_POST['inVoiceDet'][$i] . '"/>
                                                <span class="btn"><i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i></span>
                                            </td>
                                            <td>' . $accCoa->code . ' - ' . $accCoa->name . '</td>
                                            <td>' . $name . '</td>
                                            <td><input type="text" name="AccCashOutDet[description][]" id="AccCashOutDet[description][]" style="width:95%"  value="' . $_POST['AccCashOutDet']['description'][$i] . '"/></td>
                                            <td><div class="input-prepend"> <span class="add-on">Rp.</span><input type="text" name="AccCashOutDet[amount][]" id="AccCashOutDet[amount][]" class="angka totalDet" value="' . $_POST['AccCashOutDet']['amount'][$i] . '"/></div></td>
                                        </tr>';
                            }
                        }
                        ?>
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


                <div class = "form-actions">
                    <?php
                    $act = (isset($_GET['act'])) ? $_GET['act'] : '';
                    if ($model->isNewRecord || ($act != "approve" && empty($model->date_posting))) {
                        $this->widget('bootstrap.widgets.TbButton', array(
                            'buttonType' => 'submit',
                            'type' => 'primary',
                            'icon' => 'ok white',
                            'label' => $model->isNewRecord ? 'Tambah' : 'Simpan',
                        ));

                        $this->widget('bootstrap.widgets.TbButton', array(
                            'buttonType' => 'reset',
                            'icon' => 'remove',
                            'label' => 'Reset',
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
                                if ($siteConfig->autopostnumber == 0) {
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
                                }
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


            </fieldset>
        </div>
        <?php
        $this->beginWidget(
                'bootstrap.widgets.TbModal', array(
            'id' => 'modalSub',
            'htmlOptions' => array(
                'style' => 'width:700px'
            )
                )
        );
        ?>

        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <h4>Select Subledger</h4>
        </div>

        <div class="modal-body">
            <div class="well">
                Supplier / Customer's Name : 
                <?php
                $data = array(0 => t('choose', 'global'));
                $this->widget('bootstrap.widgets.TbSelect2', array(
                    'asDropDownList' => TRUE,
                    'data' => $data,
                    'name' => 'accountName',
                    'options' => array(
                        "placeholder" => t('choose', 'global'),
                        "allowClear" => true,
                    ),
                    'htmlOptions' => array(
                        'id' => 'accountName',
                        'style' => 'width:100%;'
                    ),
                ));
                ?>
            </div>
            <div class="well">
                Supplier / Customer's Invoices Description: 
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="text-align: center;width:10%;">Code</th>
                            <th style="text-align: center">Keterangan</th>
                            <th style="text-align: center;width:20%">Nilai</th>
                            <th style="text-align: center;width:20%">Balance</th>
                            <th style="text-align: center;width:10%">#</th>
                        </tr>
                    </thead>
                    <tbody id="detail">

                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal-footer">
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>
<?php $this->endWidget(); ?>

<script type="text/javascript">
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
        var val1 = $("#AccCashOut_account").val();
        var val2 = $("#account").val();
        var nol = 0;

        if (val1 === val2) {
            alert('debet dan kredit tidak boleh sama!!');
            $("#account").select2('val', nol);
        } else {
            //do nothing
        }
    }
    $("body").on("click", ".ambil", function () {
        var id = $(this).attr("det_id");
        var userId = $(this).attr("user_id");
        var acc = $(this).attr("account");
        var code = $(this).attr("code");
        /*var subledger = $("#subLedgers").html();*/
        var dell = '<a class="btn btn-mini" onclick="removeSub();"><i class=" icon-remove-circle"></i></a>';
        var ids = '<input type="hidden" id="subledgerid" name="subledgerid" value="' + id + '">';

        $("#subLedgers").html(ids + dell + '[ ' + code + ' ]' + acc);
        $("#modalSub").modal("hide");
    });

    function removeSub() {
        $("#subLedgers").html('<a data-toggle="modal" data-target="#modalSub" style="display:" class="btn" id="showModal">Select Sub-Ledger</a>');
    }
    $("body").on("change", "#accountName", function () {
        selectInvoice();
    });
    function selectInvoice() {
        var id = $("#accountName").val();
        $.ajax({
            type: 'POST',
            url: "<?php echo url('accCoa/selectInvoice') ?>",
            data: {id: id},
            success: function (data) {
                $("#detail").html(data);
            }
        });
    }
    $("body").on("click", ".delRow", function () {
        /* var idSub = $(this).parent().parent().parent().find(".inVoiceDet").val();
         var totalDet = $(this).parent().parent().parent().find(".totalDet").val();
         var mAccount = $(this).parent().parent().find(".nameAccount").val();
         if (mAccount != "") {
         $.ajax({
         type: 'POST',
         url: "<?php //echo url('accCoa/deletedRow')                        ?>",
         data: {idSub: idSub, totalDet: totalDet},
         success: function (data) {
         $("#deletedRow").replaceWith(data);
         }
         });
         }*/
        $(this).parent().parent().parent().remove();
        calculateMin();
    });
    $("body").on("keyup", "#invoice_amount", function () {
        var value = parseInt($(this).val());
        $("#debit").val(value);
    });


    $("body").on("click", ".addNewInvoice", function () {
        var code = $("#code_invoice").val();
        var user_id = $("#accountName").val();
        var type = $("#type_invoice").val();
        var term_date = $("#AccCashOut_date_trans").val();
        var description = $("#invoice_description").val();
        var amount = parseInt($("#invoice_amount").val());
        if (amount !== 0 || amount !== "" || code !== "") {
            $.ajax({
                type: 'post',
                data: {code: code, description: description, user_id: user_id, amount: amount, type : type,term_date : term_date},
                url: "<?php echo url('accCoa/newInvoice'); ?>",
                success: function (data) {
                    if (data == 1) {
                        selectInvoice();
                    }
                }
            });
        } else {
            alert("code dan/atau nilai belum di inputkan!");
        }
    });
</script>