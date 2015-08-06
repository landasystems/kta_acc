<?php
$this->setPageTitle('Site Config');
$this->beginWidget('zii.widgets.CPortlet', array(
	'htmlOptions'=>array(
		'class'=>''
	)
));

$this->endWidget();
?>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
