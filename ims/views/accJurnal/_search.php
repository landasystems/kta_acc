<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'search-acc-jurnal-form',
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
        ));
?>

<?php echo $form->textFieldRow($model, 'code', array('class' => 'span3', 'maxlength' => 255)); ?>
<?php echo $form->textFieldRow($model, 'code_acc', array('class' => 'span3', 'maxlength' => 255)); ?>
<?php
echo $form->dateRangeRow(
        $model, 'date_posting', array(
    'prepend' => '<i class="icon-calendar"></i>',
        )
);
?>
<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'icon' => 'search white', 'label' => 'Pencarian')); ?>
    <?php
    $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'icon' => 'icon', 'label' => 'Export Excel', 'htmlOptions' => array(
            'onclick' => 'submitForm()'
    )));
    ?>
</div>

<?php $this->endWidget(); ?>

<script type="text/javascript">
   

    function submitForm() {
        var condition = $("#search-acc-jurnal-form").serialize();
        var date = $("#AccJurnal_date_posting").val();
        if (date != "") {
            window.open("<?php echo url('accJurnal/generateExcel') ?>?" + condition);
        } else {
            $.toaster({priority : 'error', message : "Rentang Tgl Posting tidak boleh kosong"});
        }
    }
</script>

