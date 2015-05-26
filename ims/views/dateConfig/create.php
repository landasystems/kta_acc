<?php
/* @var $this DateConfigController */
/* @var $model DateConfig */

$this->breadcrumbs=array(
	'Date Configs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List DateConfig', 'url'=>array('index')),
	array('label'=>'Manage DateConfig', 'url'=>array('admin')),
);
?>

<h1>Create DateConfig</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>