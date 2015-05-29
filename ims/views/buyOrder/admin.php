<?php
$this->breadcrumbs=array(
	'Buy Orders'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List BuyOrder','url'=>array('index')),
	array('label'=>'Create BuyOrder','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('buy-order-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Buy Orders</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'buy-order-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'code',
		'departement_id',
		'created',
		'created_user_id',
		'modified',
		/*
		'supplier_id',
		'description',
		'subtotal',
		'discount',
		'discount_type',
		'ppn',
		'other',
		'date_delivery',
		'dp',
		'credit',
		'payment',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
