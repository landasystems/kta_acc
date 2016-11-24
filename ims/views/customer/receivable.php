<?php
$this->setPageTitle('Customer Invoice');
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'userInvoice',
    'enableAjaxValidation' => false,
    'method' => 'post',
    'type' => 'horizontal',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
        ));
?>
<div class="form">

    <div class="col-xs-12 col-md-6">

        <legend>
            <p class="note">
                <span class="required">*</span> Berikut adalah daftar Piutang<?php
//                echo $title . ' ';
                Yii::app()->name;
                echo param('clientName');
                ?><br/>
                <!--<span class="required">-</span> Saldo awal akan di setting pada tanggal <span class="label label-info"><?php // echo $siteConfig->date_system                               ?></span>-->
            </p>
        </legend>

        <div class="well">
            <div class="control-group ">
                <label class="control-label">Pilih Nama Supplier</label>
                <div class="controls">
                    <?php
                    $data = array(0 => 'Pilih') + CHtml::listData($header, 'id', 'name');
                    $this->widget('bootstrap.widgets.TbSelect2', array(
                        'asDropDownList' => TRUE,
                        'data' => $data,
                        'value' => (isset($_POST['supplier_list']) ? $_POST['supplier_list'] : ''),
                        'name' => 'supplier_list',
                        'options' => array(
                            "placeholder" => 'Pilih',
                            "allowClear" => true,
                            'width' => '300px',
                        ),
                        'htmlOptions' => array(
                            'id' => 'supplier_list',
                        ),
                    ));
                    ?>
                </div>
            </div>
            <?php
            echo $form->dateRangeRow(
                    $tanggal, 'rentang', array(
                'prepend' => '<i class="icon-calendar"></i>',
                'options' => array('callback' => 'js:function(start, end){console.log(start.toString("MMMM d, yyyy") + " - " + end.toString("MMMM d, yyyy"));}'),
                'value' => (isset($_POST['Customer']['rentang'])) ? $_POST['Customer']['rentang'] : ''
                    )
            );
            ?>
            <div class="form-group center">
                <button class="btn btn-primary" onclick="return false;" id="cari-data"><i class="icon icon-search"></i> Cari</button>
            </div>
        </div>
        <div class="detailInvoice">
            <?php
            if (!empty($_POST['supplier_list'])) {
                $tanggal = (!empty($_POST['Customer']['rentang'])) ? $_POST['Customer']['rentang'] : "";
                $andDate = '';
                $andDate2 = '';
                if (!empty($tanggal) || $tanggal != "") {
                    $dateCondition = explode(' - ', $tanggal);
                    $andDate = " AND (AccCoaDet.date_coa between '" . date('Y-m-d', strtotime($dateCondition[0])) . "' AND '" . date('Y-m-d', strtotime($dateCondition[1])) . "')";
                    $andDate2 = " AND (t.date_coa between '" . date('Y-m-d', strtotime($dateCondition[0])) . "' AND '" . date('Y-m-d', strtotime($dateCondition[1])) . "')";
                }

                $userInvoice = AccCoaDet::model()->findAll(array(
                    'with' => array('InvoiceDet'),
                    'condition' => 'InvoiceDet.user_id=' . $_POST['supplier_list'] . ' AND (reff_type="invoice" OR InvoiceDet.is_new_invoice=1)' . $andDate2
                ));
                $balance = InvoiceDet::model()->findAll(array(
                    'with' => array('AccCoaDet'),
                    'condition' => 'user_id = ' . $_POST['supplier_list'] . $andDate
                ));
            } else {
                $userInvoice = '';
                $balance = '';
            }
//            $alert = false;
            $this->renderPartial('_receivable', array(
//                'sTot' => $sTot,
                'userInvoice' => $userInvoice,
                'ambil' => false,
                'alert' => $alert,
                'balance' => $balance
            ));
            ?>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    $("#cari-data").on("click", function () {
        var idd = $("#supplier_list").val();
        var tanggal = $("#Customer_rentang").val();
        
        if (idd.length > 0 || idd != 0) {
            $.ajax({
                type: 'POST',
                data: {id: idd : tanggal : tanggal},
                url: "<?php echo url('customer/invoiceDetail'); ?>",
                success: function (data) {
                    $(".detailInvoice").html(data);
                    $('.datepicker').datepicker({format: 'mm/dd/yyyy', startDate: '-3d'});
                    hitung();
                    $(".term").datepicker();
                    $(".dateStart").datepicker();
                }
            });
        }
    });
    function hitung() {
        var total = 0;
        $('.nilai').each(function () {
            total += parseInt($(this).val());
        });
        $('#total_charge').val(total);
    }

    $("body").on('click', ".addRow", function () {
        var aa = $(this).parent().parent().parent().parent().find(".addRows");
        var desc = $(this).parent().parent().parent().find(".description").val();
        var code = $(this).parent().parent().parent().find(".codes").val();
        var date_coa = $(this).parent().parent().parent().find(".dateCoa").val();
        var terms = $(this).parent().parent().parent().find(".terms").val();
        var payment = $(this).parent().parent().parent().find(".payment").val();
        var sup_id = $("#supplier_list").val();
        $.ajax({
            type: 'POST',
            data: {code: code, terms: terms, payment: payment, desc: desc, sup_id: sup_id, date_coa: date_coa},
            url: "<?php echo url('supplier/addRow'); ?>",
            success: function (data) {
                aa.replaceWith(data);
                $(".addRows").val("");
                $(".description").val("");
                $(".codes").val("");
                $(".dateCoa").val("");
                $(".terms").val("");
                $(".payment").val("0");
                $(".sup_id").val("");
                $(".kosong").remove();
                hitung();
                $(".dateStart").datepicker();
                $(".term").datepicker();
            }
        });
    });
    $("body").on('click', '.delInv', function () {
        var id = $(this).parent().find(".id_invoice").val();
        var dell = $(this).parent().parent();
        if (id == "") {
            $(this).parent().parent().remove();
        } else {
            var answer = confirm("Are you sure want to delete this Invoice? If you do that, all of approved transaction related with this invoce won't deleted!");
            if (answer) {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo url('invoiceDet/dellInv'); ?>",
                    data: {id: id},
                    success: function (data) {
                        dell.remove();
                        hitung();
                    }
                });
            } else {
                // do nothing
            }
        }
    });
    function rp(angka) {
        var rupiah = "";
        var angkarev = angka.toString().split("").reverse().join("");
        for (var i = 0; i < angkarev.length; i++)
            if (i % 3 == 0)
                rupiah += angkarev.substr(i, 3) + ".";
        return rupiah.split("", rupiah.length - 1).reverse().join("");
    }
    $("body").on('keyup', ".nilai", function () {
        hitung();
    });
</script>
