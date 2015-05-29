<?php
$abc = "";
$type = $_GET['type'];
if ($type == 'supplier') {
    $title = 'Supplier Payment';
    $sTot = 'Hutang';
} else {
    $title = 'Customer Invoice';
    $sTot = 'Piutang';
}

$this->setPageTitle($title);
$this->breadcrumbs = array(
    $title,
);
?>
<?php
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}
?>

<div class="form">

    <div class="col-xs-12 col-md-6">

        <legend>
            <p class="note">
                <span class="required">*</span> Berikut adalah daftar <?php
                echo $title . ' ';
                Yii::app()->name;
                echo param('clientName');
                ?><br/>
                <!--<span class="required">-</span> Saldo awal akan di setting pada tanggal <span class="label label-info"><?php // echo $siteConfig->date_system                       ?></span>-->
            </p>
        </legend>

        <div id="yw22" class="tabs-left">
            <ul id="yw23" class="nav nav-tabs">
                <?php
                $a = 0;
                foreach ($header as $head) {
//                    
                    ?>
                    <li class="<?php echo ($a == 0) ? "active" : ""?>">
                        <a data-toggle="tab" href="#tab<?php echo $head->id; ?>"><?php echo $head->name ?></a>
                    </li>
                    <?php
                    $a++;
                }
                ?>
            </ul>
            <?php
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'supplier-payment-form',
                'enableAjaxValidation' => false,
                'method' => 'post',
                'type' => 'vertical',
                'htmlOptions' => array(
                    'enctype' => 'multipart/form-data',
                    'name' => 'supplier-payment',
                )
            ));
            ?>
            <div class="tab-content">
                <?php
                $no = 0;
                $valDebet = 0;
                $valCredit = 0;
                $charge = 0;
                $payments = 0;
                $totalPerUser = 0;
                foreach ($header as $head) {
                    if ($no == 0) {
                        $status = "active in";
                    } else {
                        $status = "";
                    }
                    ?>
                    <div id="tab<?php echo $head->id; ?>" class="tab-pane fade <?php echo $status ?>">
                        <h3><?php echo $head->name; ?></h3>
                        <table class="responsive table table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%" style="max-width:10%">Code Invoice</th>
                                    <th width="20%" >Tgl. Awal</th>
                                    <th width="20%" >Tgl. Jatuh Tempo</th>
                                    <th width="70%" style="max-width:75%">Keterangan</th>
                                    <th>Total</th>
                                    <th width="5%">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="5%">
                                        <input type="text" class="codes span1" value="">
                                    </td>
                                    <td>
                                        <?php
                                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                            'name' => 'dateCoa',
                                            'value' => (isset($_POST['dateCoa'])) ? $_POST['dateCoa'] : '',
                                            // additional javascript options for the date picker plugin
                                            'options' => array(
                                                'showAnim' => 'fold',
                                                'changeMonth' => 'true',
                                                'changeYear' => 'true',
                                            ),
                                            'htmlOptions' => array(
                                                'id' => 'dateCoa' . $no,
                                                'class' => 'dateCoa',
                                                'style' => 'width:100px'
                                            ),
                                        ));
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                            'name' => 'terms',
                                            'value' => (isset($_POST['terms'])) ? $_POST['terms'] : '',
                                            // additional javascript options for the date picker plugin
                                            'options' => array(
                                                'showAnim' => 'fold',
                                                'changeMonth' => 'true',
                                                'changeYear' => 'true',
                                            ),
                                            'htmlOptions' => array(
                                                'id' => 'terms' . $no,
                                                'class' => 'terms',
                                                'style' => 'width:100px'
                                            ),
                                        ));
                                        ?>
                                    </td>
                                    <td>
                                        <input type="text" class="span4 description" value="">
                                    </td>
                                    <td style="text-align:center">
                                        <div class="input-prepend">
                                            <span class="add-on">Rp.</span>
                                            <input type="text" class="angka payment" value="0">
                                        </div>
                                    </td>
                                    <td style="text-align:center">
                                        <a class="btn"><div class="addRow"><i class="icon-plus-sign"></i></div></a>
                                        <input type="hidden" class="angka sup_id" value="<?php echo $head->id ?>">
                                    </td>
                                </tr>
                                <?php
                                $list = InvoiceDet::model()->findAllByAttributes(array(
                                    'user_id' => $head->id,
                                    'type' => $_GET['type'],
                                ));
                                $totalPerUser = 0;
                                if (!empty($list)) {
                                    foreach ($list as $val) {
                                        $charge = (!empty($val->charge)) ? $val->charge : 0;
                                        $payments = $val->payment;
                                        $mCoaDet = AccCoaDet::model()->findAll(array(
                                            'condition' => 'invoice_det_id=' . $val->id . ' AND reff_type="invoice"'
                                        ));
                                        foreach ($mCoaDet as $value) {
                                            if (!empty($value->debet))
                                                $paymentss = $value->debet;
                                            else
                                                $paymentss = $value->credit;
                                            
                                            $totalPerUser += $paymentss;
                                            ?>
                                            <tr>
                                                <td><input type="text" class="code span1" name="code[]" value="<?php echo $val->code ?>"></td>
                                                <td width="5%">
                                                    <input type="text" readonly="readonly" class="dateStart" style="width:100px" name="date_coa[]" value="<?php echo date('d-M-Y', strtotime($value->date_coa)); ?>">
                                                </td>
                                                <td width="5%">
                                                    <input type="text" readonly="readonly" class="term" style="width:100px" name="term_date[]" value="<?php echo date('d-M-Y', strtotime($val->term_date)); ?>">
                                                </td>
                                                <td><input type="text" class="description span4" name="description[]" value="<?php echo $val->description ?>"></td>
                                                <td width="125" style="text-align:center">
                                                    <div class="input-prepend">
                                                        <span class="add-on">Rp.</span>
                                                        <?php echo CHtml::textField('payment[]', $paymentss, array('class' => 'angka payment', 'style' => 'width:75px;')); ?>
                                                    </div>                                                
                                                </td>
                                                <td>
                                                    <span style="width:12px" class="btn delInv"><i class="cut-icon-trashcan"></i></span>
                                                    <input type="hidden" class="user" name="user_id[]" value="<?php echo $head->id ?>">
                                                    <input type="hidden" class="id_invoice" name="id[]" value="<?php echo $val->id ?>">
                                                    <input type="hidden" class="id_coaDet" name="id_coaDet[]" value="<?php echo $value->id ?>">
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                                <tr class="addRows" style="display:none;">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" style="text-align:center;font-weight:bold"><?php echo 'Total ' . $sTot . ' ' . $head->name ?></td>    
                                    <td class="span2" style="text-align:center">
                                        <div class="input-prepend">
                                            <span class="add-on">Rp.</span>
                                            <input value="<?php echo $totalPerUser ?>" style="width:75px;" type="text" readonly="readonly"/>
                                        </div>
                                    </td>
                                    <td width="5%"><?php 
                                    $abc .= $totalPerUser.'+';
                                   ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <?php
                    $no++;
                }
                ?>
                <table class="responsive table table-bordered">
                    <tfoot>
                        <tr>
                            <td colspan="4" style="text-align:center;font-weight:bold"><?php echo 'Total Keseluruhan ' . $sTot; ?></td>    
                            <td class="span2" style="text-align:center">
                                <div class="input-prepend">
                                    <span class="add-on">Rp.</span>
                                    <?php echo CHtml::textField('total_payment', $valDebet, array('id' => 'total_charge', 'maxlength' => 60, 'readonly' => true, 'style' => 'width:75px;')); ?>
                                </div>
                            </td>
                            <td width="5%"></td>
                        </tr>
                    </tfoot>
                </table>
                <div class = "form-actions">
                    <?php
                    $this->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'submit',
                        'type' => 'primary',
                        'icon' => 'ok white',
                        'label' => 'simpan',
                    ));
                    ?>
                </div>

            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>

</div>
<script type="text/javascript">
    function hitung() {
        var total = 0;
        $('.payment').each(function () {
            total += parseInt($(this).val());
        });
        $('#total_charge').val(total);
    }
    hitung();
    $("body").on('click', ".addRow", function () {
        var aa = $(this).parent().parent().parent().parent().find(".addRows");
        var desc = $(this).parent().parent().parent().find(".description").val();
        var code = $(this).parent().parent().parent().find(".codes").val();
        var date_coa = $(this).parent().parent().parent().find(".dateCoa").val();
        var terms = $(this).parent().parent().parent().find(".terms").val();
        var payment = $(this).parent().parent().parent().find(".payment").val();
        var sup_id = $(this).parent().parent().parent().find(".sup_id").val();
        $.ajax({
            type: 'POST',
            data: {code: code, terms: terms, payment: payment, desc: desc, sup_id: sup_id, date_coa: date_coa},
            url: "<?php echo url('user/addRow'); ?>",
            success: function (data) {
                aa.replaceWith(data);
                hitung();
            }
        });
    });
    $("body").on('click', '.delInv', function () {
        var id = $(this).parent().find(".id_invoice").val();
        var dell = $(this).parent().parent();
//        alert(id);
        if (id == "") {
            $(this).parent().parent().remove();
        } else {
            var answer = confirm("Are you sure want to delete this Invoice? If you do that, all of approved transaction related with this invoce won't deleted!")
            if (answer) {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo url('invoiceDet/dellInv'); ?>",
                    data: {id: id},
                    success: function (data) {
                        alert(data);
                        dell.remove();
                        hitung();
                    }
                });
            } else {
                // do nothing
            }
        }
    });
    $("body").on('input', ".payment", function () {
        hitung();
    });
</script>