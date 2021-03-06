<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'results',
    'enableAjaxValidation' => false,
    'method' => 'post',
    'type' => 'horizontal',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
        ));
?>
<?php
$this->setPageTitle('Kartu Hutang');
$this->breadcrumbs = array(
    'Kartu Hutang',
);
?>
<script>
    function hide() {
        $(".well").hide();
        $(".form-horizontal").hide();
    }

</script>
<div class="well">
    <div class="row-fluid">
        <div class="span11">
            <div class="control-group ">
                <label class="control-label">Nama Supplier</label>
                <div class="controls">
                    <?php
                    $data = array(0 => 'Pilih') + CHtml::listData(Supplier::model()->findAll(), 'id', 'name');
                    $this->widget('bootstrap.widgets.TbSelect2', array(
                        'asDropDownList' => TRUE,
                        'data' => $data,
                        'value' => (isset($_POST['listUser']) ? $_POST['listUser'] : ''),
                        'name' => 'listUser',
                        'options' => array(
                            "placeholder" => 'Pilih',
                            "allowClear" => true,
                            'width' => '50%',
                        ),
                        'htmlOptions' => array(
                            'id' => 'listUser',
                        ),
                    ));
                    ?>
                </div>
            </div>
            <?php
            echo $form->dateRangeRow(
                    $mCoaDet, 'created', array(
                'prepend' => '<i class="icon-calendar"></i>',
                'options' => array('callback' => 'js:function(start, end){console.log(start.toString("MMMM d, yyyy") + " - " + end.toString("MMMM d, yyyy"));}'),
                'value' => (isset($_POST['AccCoaDet']['created'])) ? $_POST['AccCoaDet']['created'] : ''
                    )
            );
            ?>
        </div>
    </div>
    <div class="form-actions">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'icon' => 'ok white',
            'label' => 'View Report',
        ));
        ?>
        <?php
        if (isset($_POST['AccCoaDet']['created'])) {
            $this->widget(
                    'bootstrap.widgets.TbButtonGroup', array(
                'buttons' => array(
                    array(
                        'label' => 'Report',
                        'icon' => 'print',
                        'htmlOptions' => array("style" => "height:15px;"),
                        'items' => array(
                            array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printElement("printableArea");return false;')),
                        )
                    ),
                ),
                    )
            );
        } else {
            echo '';
        }
        ?>
    </div>
    <?php $this->endWidget(); ?>
</div>

<?php
if (isset($_POST['AccCoaDet']['created'])) {

    if (!empty($_POST['AccCoaDet']['created'])) {
        $a = explode('-', $_POST['AccCoaDet']['created']);
        $start = date('Y/m/d', strtotime($a[0]));
        $end = date('Y/m/d', strtotime($a[1]));

        $accCoaDet = AccCoaDet::model()->findAll(array(
            'with' => 'InvoiceDet',
            'order' => 'date_coa',
            'condition' => 'InvoiceDet.user_id=' . $_POST['listUser'] . ' AND (date_coa>="' . date('Y-m-d', strtotime($start)) . '" AND date_coa<="' . date('Y-m-d', strtotime($end)) . '")'
        ));

//        $cOutId = array();
//        $cInId = array();
//
//        foreach ($accCoaDet as $val) {
//            if ($val->reff_type == "cash_out")
//                $cOutId[] = $val->reff_id;
//            else if ($val->reff_type == "cash_in")
//                $cInId[] = $val->reff_id;
//        }
//        $giroOut = array();
//        if (!empty($cOutId)) {
//            $cashout = AccCashOut::model()->findAll(array('condition' => 'id IN (' . implode(',', $cOutId) . ')'));
//            foreach ($cashout as $v) {
//                $giroOut[$v->id] = $v->description_giro_an;
//            }
//        }
//
//        $giroIn = array();
//        if (!empty($cInId)) {
//            $cashIn = AccCashIn::model()->findAll(array('condition' => 'id IN (' . implode(',', $cInId) . ')'));
//            foreach ($cashIn as $v) {
//                $giroIn[$v->id] = $v->description_giro_an;
//            }
//        }

        $this->renderPartial('_kartuHutangResult', array(
            'start' => $start,
            'end' => $end,
            'accCoaDet' => $accCoaDet,
//            'giroIn' => $giroIn,
//            'giroOut' => $giroOut,
            'id' => $_POST['listUser']
        ));
    }
}
?>

