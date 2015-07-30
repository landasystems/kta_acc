<?php

$this->setPageTitle('Grup Hak Akses');
?>

<?php

$visible = landa()->checkAccess('GroupUser', 'c');

$this->beginWidget('zii.widgets.CPortlet', array(
    'htmlOptions' => array(
        'class' => ''
    )
));
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('visible' => $visible, 'label' => 'Tambah', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create', array('type' => $sType)), 'linkOptions' => array()),
        array('label' => 'Daftar', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index', array('type' => $sType)), 'active' => true, 'linkOptions' => array()),
    ),
));
$this->endWidget();
?>

<?php

$buton = "";

if (landa()->checkAccess('GroupSupplier', 'r')) {
    $buton .= '{view}';
}
if (landa()->checkAccess('GroupSupplier', 'u')) {
    $buton .= '{update}';
}
if (landa()->checkAccess('GroupSupplier', 'd')) {
    $buton .= '{delete}';
}

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'roles-grid',
    'dataProvider' => $model->search($sType),
    'type' => 'striped bordered condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(
        'name',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => $buton,
            'buttons' => array(
                'view' => array(
                    'label' => 'Lihat',
                    'url' => 'Yii::app()->createUrl("roles/view", array("id"=>$data->id,"type"=>"' . $sType . '"))',
                    'options' => array(
                        'class' => 'btn btn-small view'
                    )
                ),
                'update' => array(
                    'label' => 'Edit',
                    'url' => 'Yii::app()->createUrl("roles/update", array("id"=>$data->id,"type"=>"' . $sType . '"))',
                    'options' => array(
                        'class' => 'btn btn-small update'
                    )
                ),
                'delete' => array(
                    'label' => 'Hapus',
                    'url' => 'Yii::app()->createUrl("roles/delete", array("id"=>$data->id,"type"=>"' . $sType . '"))',
                    'options' => array(
                        'class' => 'btn btn-small delete'
                    )
                )
            ),
            'htmlOptions' => array('style' => 'width: 125px'),
        )
    ),
));
?>

