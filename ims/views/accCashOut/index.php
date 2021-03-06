<?php
$this->setPageTitle('Kas Keluar');
Yii::app()->clientScript->registerScript('search', "
        $('.search-button').click(function(){
            $('.search-form').slideToggle('fast');
            return false;
        });
        $('.search-form form').submit(function(){
            $.fn.yiiGridView.update('acc-cash-out-grid', {
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
        array('label' => 'Tambah', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('label' => 'Daftar', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'active' => true, 'linkOptions' => array()),
        array('label' => 'Pencarian & Eksport Excel', 'icon' => 'icon-search', 'url' => '#', 'linkOptions' => array('class' => 'search-button')),
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
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'acc-cash-out-grid',
    'dataProvider' => $model->search(),
    'type' => 'striped bordered condensed',
    'template' => '{summary}{items}{pager}',
    'columns' => array(
        'code',
        array(
            'name' => 'date_trans',
            'type' => 'raw',
            'value' => 'date("d-M-Y", strtotime($data->date_trans))',
            'htmlOptions' => array('style' => 'text-align:center'),
        ),
        'code_acc',
        array(
            'name' => 'date_posting',
            'type' => 'raw',
            'value' => '(isset($data->date_posting)) ? date("d-M-Y", strtotime($data->date_posting)) : ""',
            'htmlOptions' => array('style' => 'text-align:center'),
        ),
        array(
            'header' => 'Rekening',
            'value' => '(isset($data->AccCoa->name)) ? $data->AccCoa->name : "-"',
        ),
        array(
            'name' => 'Total',
            'header' => 'Total',
            'value' => 'landa()->rp($data->total)',
            'htmlOptions' => array('style' => 'text-align:right'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{approval} {view} {update} {delete}',
            'buttons' => array(
                'approval' => array(
                    'label' => 'Approve',
                    'icon' => 'icon-ok',
                    'visible' => 'empty($data->code_acc)',
                    'url' => 'Yii::app()->createUrl("accCashOut/update", array("id"=>$data->id,"act" => "approve"))',
                    'options' => array(
                        'class' => 'btn btn-small ',
                    )
                ),
                'cancel' => array(
                    'label' => 'Cancel Approve',
                    'icon' => 'brocco-icon-cancel',
                    'visible' => '(isset($data->AccManager->status) and $data->AccManager->status == \'confirm\')',
                    'url' => 'Yii::app()->createUrl("accCashOut/unapprove", array("id"=>$data->id))',
                    'options' => array(
                        'class' => 'btn btn-small ',
                    )
                ),
                'view' => array(
                    'label' => 'Lihat',
                    'options' => array(
                        'class' => 'btn btn-small view'
                    )
                ),
                'update' => array(
                    'label' => 'Edit',
                    'url' => 'Yii::app()->createUrl("accCashOut/update", array("id"=>$data->id,"act" => "edit"))',
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
            'htmlOptions' => array('style' => 'width: 150px'),
        )
    ),
));
?>

