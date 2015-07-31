<?php  $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
'id'=>'search-invoice-det-form',
'action'=>Yii::app()->createUrl($this->route),
'method'=>'get',
));  ?>


        <?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

        <?php echo $form->textFieldRow($model,'description',array('class'=>'span5','maxlength'=>45)); ?>

        <?php echo $form->textFieldRow($model,'user_id',array('class'=>'span5')); ?>

        <?php echo $form->textFieldRow($model,'payment',array('class'=>'span5')); ?>

        <?php echo $form->textFieldRow($model,'charge',array('class'=>'span5')); ?>

        <?php echo $form->textFieldRow($model,'type',array('class'=>'span5','maxlength'=>15)); ?>

        <?php echo $form->textFieldRow($model,'code',array('class'=>'span5','maxlength'=>9)); ?>

        <?php echo $form->textFieldRow($model,'term_date',array('class'=>'span5')); ?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'icon'=>'search white', 'label'=>'Pencarian')); ?>
</div>

<?php $this->endWidget(); ?>


