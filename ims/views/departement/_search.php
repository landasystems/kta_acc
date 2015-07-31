<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'search-Departement-form',
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
        ));
?>
<?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 100)); ?>

<?php echo $form->textFieldRow($model, 'city_id', array('class' => 'span5')); ?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'icon' => 'search white', 'label' => 'Search')); ?>
</div>

<?php $this->endWidget(); ?>


<?php
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.ui');
$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/bootstrap/jquery-ui.css');
?>
