<?php
$this->setPageTitle('Products');
$this->breadcrumbs = array(
    'Products',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('product-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<?php
$this->beginWidget('zii.widgets.CPortlet', array(
    'htmlOptions' => array(
        'class' => ''
    )
));
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('visible' => landa()->checkAccess('Product', 'c'),'label' => 'Tambah', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('label' => 'Daftar', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'active' => true, 'linkOptions' => array()),
        array('label' => 'Pencarian', 'icon' => 'icon-search', 'url' => '#', 'linkOptions' => array('class' => 'search-button')),
        array('label' => 'Export ke PDF', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GeneratePdf'), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
        array('label' => 'Export ke Excel', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GenerateExcel'), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
    ),
));
$this->endWidget();
?>



<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->


<?php
$buton="";
if(landa()->checkAccess('Product', 'r')){
   $buton .= '{view}'; 
}
if(landa()->checkAccess('Product', 'd')){
   $buton .= '{delete}'; 
}
if(landa()->checkAccess('Product', 'u')){
   $buton .= '{update}'; 
}
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'product-grid',
    'dataProvider' => $model->search(),
    'type' => 'striped bordered condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(
        array(
            'header'=>'Image',
            'name' => 'product_photo_id',
            'type' => 'raw',
            'value' => '"$data->tagImg"',
            'htmlOptions' => array('style' => 'text-align: center;width:110px;'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
        ),
//        array('header' => 'Type',
//            'name' => 'type',
//            'type' => 'raw',
//            'value' => '($data->type==\'inv\') ? "<span class=\"label label-info\">Inventory</span>" : (($data->type==\'srv\')? "<span class=\"label label-info\">Services</span>" : "<span class=\"label label-info\">Assembly</span>")',
//            'headerHtmlOptions' => array('style' => 'text-align: center;width:75px'),
//        ),
        array(
            'header'=>'Public',
            'name' => 'type',
            'type' => 'raw',
            'value' => '"$data->tagPublic"',
            'htmlOptions' => array('style' => 'text-align: center;')
        ),
        array(
            'name' => 'product_brand_id',
            'type' => 'raw',
            'value' => '"$data->tagProduct"',
            'htmlOptions' => array('style' => 'text-align: center;')
        ),
        array(
            'header'=>'Size',
            'name' => 'weight',
            'type' => 'raw',
            'value' => '"$data->tagDImension"',
            'htmlOptions' => array('style' => 'text-align: center;')
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => $buton,
            'buttons' => array(
                'view' => array(
                    'label' => 'Lihat',
                    'options' => array(
                        'class' => 'btn btn-small view'
                    )
                ),
                'update' => array(
                    'label' => 'Edit',
                    'options' => array(
                        'class' => 'btn btn-small update'
                    )
                ),
                'delete' => array(
                    'label' => 'Hapus',
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

