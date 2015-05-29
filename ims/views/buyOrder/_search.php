<?php  $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
'id'=>'search-buy-order-form',
'action'=>Yii::app()->createUrl($this->route),
'method'=>'get',
));  ?>


<table>
    <tr>
        <td><?php echo $form->textFieldRow($model,'code',array('class'=>'span3','maxlength'=>255)); ?>

        <?php echo $form->textFieldRow($model,'departement_id',array('class'=>'span4')); ?></td>
         
        <td><?php echo $form->textFieldRow($model,'supplier_id',array('class'=>'span4')); ?>
        <?php echo $form->datepickerRow(
            $model,
            'date_delivery',
            array(
                'options' => array('language' => 'es'),
               
                'prepend' => '<i class="icon-calendar"></i>'
            )
        ); ?></td>
    </tr>
</table>

        

        

        

    

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'icon'=>'search white', 'label'=>'Pencarian')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'button', 'icon'=>'icon-remove-sign white', 'label'=>'Reset', 'htmlOptions'=>array('class'=>'btnreset btn-small'))); ?>
</div>

<?php $this->endWidget(); ?>


<?php $cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.ui');
$cs->registerCssFile(Yii::app()->request->baseUrl.'/css/bootstrap/jquery-ui.css');
?>	
<script type="text/javascript">
    jQuery(function($) {
        $(".btnreset").click(function() {
            $(":input", "#search-buy-order-form").each(function() {
                var type = this.type;
                var tag = this.tagName.toLowerCase(); // normalize case
                if (type == "text" || type == "password" || tag == "textarea")
                    this.value = "";
                else if (type == "checkbox" || type == "radio")
                    this.checked = false;
                else if (tag == "select")
                    this.selectedIndex = "";
            });
        });
    })
</script>

