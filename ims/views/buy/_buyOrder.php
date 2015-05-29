<?php

$returnValue['top'] = "";
$returnValue['button'] = "";
$returnValue['info'] = '';
if (!empty($idBuyOrder)) {
    $model = BuyOrder::model()->findByPk($idBuyOrder);
    $mBuyDet = BuyOrderDet::model()->findAll(array('condition' => 'buy_order_id=' . $model->id));

    $returnValue['supplier_id'] = $model->supplier_id;
    $modelUser = User::model()->findByPk($model->supplier_id);
    $returnValue['info'] .=  $this->renderPartial('_customerInfo', array('model' => $modelUser), true);

    $returnValue['top'] .= '
                        <div class="control-group "><label class="control-label" for="Buy_description">Description</label><div class="controls"><textarea class="span3" rows="4" name="Buy[description]" id="Buy_description">' . $model->description . '</textarea></div></div>
                        <div class="control-group "><label class="control-label required" for="Buy_supplier_id">Supplier <span class="required">*</span></label>
                            <div class="controls">' .
                            CHtml::dropDownList('Buy[departement_id]', $model->departement_id, CHtml::listData(Departement::model()->findAll(), 'id', 'name'), array(
                                'empty' => t('choose', 'global'), 'class' => 'span3',
                            )) .
                            '</div>
                        </div>';





    $returnValue['button'].= '<tr id="addRow" style="display:none">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>                        
                        <td></td>
                    </tr>';
    foreach ($mBuyDet as $o) {
        $measure= (!empty($o->product_measure_id))?$o->Product->ProductMeasure->name:"";
        $returnValue['button'] .= '<tr class="button_form">                               
                        <td>
                            <input type="hidden" id="' . $o->product_id . '" name="BuyDet[product_id][]" value="' . $o->product_id . '"/>
                            <input type="hidden" name="BuyDet[price][]" value="' . $o->price . '"/>
                            <input type="hidden" id="detQty" name="BuyDet[qty][]" value="' . $o->qty . '"/>
                            <input type="hidden" id="detTotalq" name="BuyDet[total][]" class="detTotal" value="' . $o->price * $o->qty . '"/>
                            <i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i>
                        </td>
                        <td>' . $o->Product->code . '</td>
                        <td colspan="2">' . $o->Product->name . '</td>                        
                        <td><span id="detAmount">' . $o->qty . '</span> ' . $measure . '</td>
                        <td>' . landa()->rp($o->price) . '</td>
                        <td>' . landa()->rp($o->qty * $o->price) . '</td>
                    </tr>';
    }


    $returnValue['button'] .= '<tr class="button_form">
                    <td colspan="6" style="text-align: right;padding-right: 15px"><b>Sub Total : </b></td>
                    <td>';
    $returnValue['button'] .= '<input type="hidden" name="Buy[subtotal]" value="' . $model->subtotal . '"/>';
    if ($model->subtotal != "")
        $subtot = $model->subtotal;
    else
        $subtot = 0;
    if ($model->ppn != "")
        $ppn = $model->ppn;
    else
        $ppn = 0;

    $returnValue['button'] .= '<span id="subtotal">' . landa()->rp($subtot) . '</span>';
    $returnValue['button'] .= '
                        </td>
                        </tr>
                        <tr class="button_form rowPPN">
                            <td colspan="6" style="text-align: right;padding-right: 15px"><b>PPN : </b></td>
                            <td>';
    $returnValue['button'] .= '<input type="hidden" name="Buy[ppn]" id="Buy_ppn" value="' . $model->ppn . '"/>';
    $returnValue['button'] .= '<span id="ppn">' . landa()->rp($ppn);
    $returnValue['button'] .= '</span>
                    </td>
                    </tr>
                    <tr class="button_form">
                        <td colspan="6" style="text-align: right;padding-right: 15px"><b>Other Cost : </b></td>
                        <td>';
    $returnValue['button'] .= '<div class="input-prepend"><span class="add-on">Rp</span><input name="Buy[other]" value="' . $model->other . '" id="Buy_other" type="text"></div>';

    $returnValue['button'].= '</td>
                    </tr>
                    <tr class="button_form">
                        <td colspan="6" style="text-align: right;padding-right: 15px"><b>Diskon</td><td>';
    $returnValue['button'] .= '<div class="input-prepend"><span class="add-on">Rp</span><input name="Buy[discount]" value="' . $model->discount . '" id="Buy_discount" type="text"></div>';

    $returnValue['button'].='</td>
                    </tr>
                    <tr class="button_form">
                        <td colspan="6" style="text-align: right;padding-right: 15px"><b>Total : </b></td>
                        <td>
                            <span id="grandTotal">' . landa()->rp($model->subtotal + $model->ppn + $model->other - $model->discount) . '</span>
                            <input type="hidden" id="allTotal" name="allTotal" value="" />
                        </td>
                    </tr>
                    <tr class="button_form">
                        <td colspan="6" style="text-align: right;padding-right: 15px"><b>Payment : </b></td>
                        <td>';

    $returnValue['button'] .= '<div class="input-prepend"><span class="add-on">Rp</span><input name="Buy[payment]" value="' . $model->payment . '" id="Buy_payment" type="text"></div>';


    $returnValue['button'].= '</td>
                    </tr>';
}else {

    $returnValue['supplier_id'] = 0; 
    $returnValue['info'] .=  $this->renderPartial('_customerInfo', array('model' => ''), true);

    $returnValue['top'] .= '
                        <div class="control-group "><label class="control-label" for="Buy_description">Description</label><div class="controls"><textarea class="span3" rows="4" name="Buy[description]" id="Buy_description"></textarea></div></div>
                        <div class="control-group "><label class="control-label required" for="Buy_supplier_id">Supplier <span class="required">*</span></label>
                            <div class="controls">' .
                            CHtml::dropDownList('Buy[departement_id]', '', CHtml::listData(Departement::model()->findAll(), 'id', 'name'), array(
                                'empty' => t('choose', 'global'), 'class' => 'span3',
                            )) .
                            '</div>
                        </div>';

    $returnValue['button'].= '<tr id="addRow" style="display:none">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>';
    $returnValue['button'] .= '<tr class="button_form">
                    <td colspan="6" style="text-align: right;padding-right: 15px"><b>Sub Total : </b></td>
                    <td>';
    $returnValue['button'] .= '<input type="hidden" name="Buy[subtotal]" value=""/>';

    $returnValue['button'] .= '<span id="subtotal"></span>';
    $returnValue['button'] .= '
                        </td>
                        </tr>
                        <tr class="button_form rowPPN">
                            <td colspan="6" style="text-align: right;padding-right: 15px"><b>PPN : </b></td>
                            <td>';
    $returnValue['button'] .= '<input type="hidden" name="Buy[ppn]" value=""/>';
    $returnValue['button'] .= '<span id="ppn">';
    $returnValue['button'] .= '</span>
                    </td>
                    </tr>
                    <tr class="button_form">
                        <td colspan="6" style="text-align: right;padding-right: 15px"><b>Other Cost : </b></td>
                        <td>';
    $returnValue['button'] .= '<div class="input-prepend"><span class="add-on">Rp</span><input name="Buy[other]" value="" id="Buy_other" type="text"></div>';

    $returnValue['button'].= '</td>
                    </tr>
                    <tr class="button_form">
                        <td colspan="6" style="text-align: right;padding-right: 15px"><b>Diskon</td><td>';
    $returnValue['button'] .= '<div class="input-prepend"><span class="add-on">Rp</span><input name="Buy[discount]" value="" id="Buy_discount" type="text"></div>';

    $returnValue['button'].='</td>
                    </tr>
                    <tr class="button_form">
                        <td colspan="6" style="text-align: right;padding-right: 15px"><b>Total : </b></td>
                        <td>
                            <span id="grandTotal"></span>
                        </td>
                    </tr>
                    <tr class="button_form">
                        <td colspan="6" style="text-align: right;padding-right: 15px"><b>Payment : </b></td>
                        <td>';

    $returnValue['button'] .= '<div class="input-prepend"><span class="add-on">Rp</span><input name="Buy[payment]" value="" id="Buy_payment" type="text"></div>';


    $returnValue['button'].= '</td>
                    </tr>';
}
echo json_encode($returnValue);
?>