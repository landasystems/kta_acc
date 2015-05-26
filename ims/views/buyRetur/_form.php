<?php
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}
?>
<div class="form">    
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'buy-retur-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <?php
    if ($model->isNewRecord == TRUE) {
        ?>

        <div class="box">
            <div class="title">

                <h4>

                    <?php
                    echo "Retrive From Buy      : " . CHtml::dropDownList('Buy', '', CHtml::listData(Buy::model()->findAll(), 'id', 'code'), array(
                        'empty' => t('choose', 'global'), 'class' => 'span3',
                        'ajax' => array(
                            'type' => 'POST',
                            'url' => url('buyRetur/getBuy'),
                            'success' => 'function(data){
                                if (data!=""){                                                                                                                                                    
                                    obj = JSON.parse(data);                                        
                                    $(".button_form").remove();  
                                    $("#top_form").html(obj["top"]);  
                                    $("#addRow").replaceWith(obj["button"]);                                                                        
                                }
                         }',
                        ),
                    ));
                    ?>
                </h4>
            </div>
        </div>
        <?php
    }
    ?>
    <div class="box gradient invoice">
        <div class="title clearfix">

            <h4 class="left">
                <span>Add Retur From Buy</span>
            </h4>
<!--            <div class="print">
                <a href="#" class="tip" oldtitle="Print invoice" title=""><span class="icon24 entypo-icon-printer"></span></a>
            </div>-->
            <div class="invoice-info">
                <span class="number"><strong class="red">
                        <?php
                        echo $model->code;
                        echo $form->hiddenField($model, 'code');
                        ?>
                    </strong></span>
                <span class="data gray"><?php echo date('d M Y') ?></span>
                <div class="clearfix"></div>
            </div>

        </div>
        <div class="content">
            <div id ="top_form">
                <table>
                    <tr>
                        <td style="vertical-align: top !important" class="span6">

                            <div class="row-fluid">
                                <div class="span3">
                                    Supplier
                                </div>
                                <div class="span1">:</div>
                                <div class="span8" style="text-align:left">
                                    <?php
                                    echo CHtml::dropDownList('Buy[supplier_id]', $model->supplier_id, CHtml::listData(User::model()->findAll(array()), 'id', 'name'), array(
                                        'empty' => t('choose', 'global'),
                                        'class' => 'span10',
                                        'disabled' => true,
                                    ));
                                    ?>
                                </div>
                            </div> 
                            <?php
                            if (!empty($model->supplier_id)) {
                                $modelUser = User::model()->findByPk($model->supplier_id);
                                $this->renderPartial('_customerInfo', array('model' => $modelUser));
                            }else{
                                $this->renderPartial('_customerInfo', array('model' => ''));
                            }
                            ?>  
                        </td>
                        <td style="vertical-align: top !important" class="span6">

                            <?php
                            echo $form->textAreaRow(
                                    $model, 'description', array('class' => 'span3', 'disabled' => true, 'rows' => 4)
                            );
                            ?>
                            <?php echo $form->dropDownListRow($model, 'departement_id', CHtml::listData(Departement::model()->findAll(), 'id', 'name'), array('class' => 'span3', 'disabled' => true, 'empty' => t('choose', 'global'))); ?>                            

                        </td>
                    </tr>

                </table>
            </div>

            <div class="clearfix"></div>

            <table class="responsive table table-bordered">
                <thead>
                    <tr>
                        <th width="20">#</th>
                        <th>Code</th>
                        <th>Item</th>

                        <th class="span2">Amount</th>
                        <th>Price</th>
                        <th class="span3">Total</th>
                    </tr>
                </thead>
                <tbody>                   
                    <tr id="addRow">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php
                    if ($model->isNewRecord == FALSE) {
                        foreach ($mBuyReturDet as $o) {
                            echo '<tr class="button_form" id="' . $o->id . '">                               
                        <td>
                            <input type="hidden" name="BuyReturDet[product_id][]" value="' . $o->product_id . '"/>
                            <input type="hidden" name="BuyReturDet[price][]" class="detPrice" value="' . $o->price . '"/>                            
                            <input type="hidden" name="BuyReturDet[total][]" class="detTotal" value="' . $o->price * $o->qty . '"/>
                            <i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i>
                        </td>
                        <td>' . $o->Product->code . '</td>
                        <td>' . $o->Product->name . '</td>                        
                        <td> <input type="text" class="amount span1" name="BuyReturDet[qty][]" value="' . $o->qty . '"/>    ' . $o->Product->ProductMeasure->name . '</td>
                        <td>' . landa()->rp($o->price) . '</td>
                        <td>' . landa()->rp($o->qty * $o->price) . '</td>
                    </tr>';
                        }
                    }
                    ?>   

                    <tr class="button_form">
                        <td colspan="5" style="text-align: right;padding-right: 15px"><b>Sub Total : </b></td>
                        <td>
                            <?php echo $form->hiddenField($model, 'subtotal'); ?>
                            <span id="subtotal"><?php echo ($model->subtotal != "") ? landa()->rp($model->subtotal) : ""; ?></span>
                        </td>
                    </tr>
                    <tr class="button_form">
                        <td colspan="5" style="text-align: right;padding-right: 15px"><b>Other Cost : </b></td>
                        <td>
                            <?php
                            echo $form->textFieldRow(
                                    $model, 'other', array('prepend' => 'Rp', 'label' => false)
                            );
                            ?>
                        </td>
                    </tr>                   
                    <tr class="button_form">
                        <td colspan="5" style="text-align: right;padding-right: 15px"><b>Total : </b></td>
                        <td>
                            <span id="grandTotal"><?php echo landa()->rp($model->subtotal + $model->other); ?></span>
                        </td>
                    </tr>                   
                </tbody>
            </table>


            <div class="clearfix"></div>

            <div class="invoice-footer" style="padding-left: 30px">
                <?php
                $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'submit',
                    'type' => 'primary',
                    'icon' => 'ok white',
                    'label' => $model->isNewRecord ? 'Tambah' : 'Simpan',
                ));
                ?>
                <?php
                $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'reset',
                    'icon' => 'remove',
                    'label' => 'Reset',
                ));
                ?>
            </div>

        </div>

    </div>

    <?php $this->endWidget(); ?>

</div>
