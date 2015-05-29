<?php

$returnValue['top'] = "";
$returnValue['button'] = "";

if (!empty($idBuy)) {
    $model = Buy::model()->findByPk($idBuy);
    $mBuyDet = BuyDet::model()->findAll(array('condition' => 'buy_id=' . $model->id));

    $returnValue['top'] .=
            '<input type = "hidden" name = "BuyRetur[departement_id]" value = "' . $model->departement_id . '"/>
             <input type = "hidden" name = "BuyRetur[supplier_id]"  value = "' . $model->supplier_id . '"/>
             <input type = "hidden" name = "BuyRetur[description]"  value = "' . $model->description . '"/>';

    $returnValue['top'] .= '<table>
                <tr>
                    <td style="vertical-align: top !important" class="span6">                        
                        <div class="row-fluid">
                            <div class="span3">
                                Supplier
                            </div>
                            <div class="span1">:</div>
                            <div class="span8" style="text-align:left">'
            . CHtml::dropDownList('Buy[supplier_id]', $model->supplier_id, CHtml::listData(User::model()->findAll(array()), 'id', 'name'), array(
                'empty' => t('choose', 'global'),
                'class' => 'span10',
                'disabled' => true,
            ))
            . '</div></div><hr style="margin-bottom:0px">';
    $modelUser = User::model()->findByPk($model->supplier_id);
    $returnValue['top'] .= '<span class="info"' . $this->renderPartial('_customerInfo', array('model' => $modelUser), true) . '</span>';
    $returnValue['top'] .='</td>
                    <td style="vertical-align: top !important" class="span6">
                        <div class="control-group "><label class="control-label" for="Buy_retur_description">Description</label><div class="controls"><textarea disabled="true" class="span3" rows="4" name="description" id="Buy_retur_description">' . $model->description . '</textarea></div></div>
<div class="control-group "><label class="control-label required" for="Buy_retur_departement_id">Departement <span class="required">*</span></label>
                            <div class="controls">' . CHtml::dropDownList('departement', $model->departement_id, CHtml::listData(Departement::model()->findAll(), 'id', 'name'), array(
                'empty' => t('choose', 'global'), 'class' => 'span3', 'disabled' => true,
            )) . '</div></div>                            
                    </td>
                </tr>
           </table>';



    $returnValue['button'].= '<tr id="addRow" style="display:none">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>';
    foreach ($mBuyDet as $o) {
        $returnValue['button'] .= '<tr class="button_form" id="' . $o->id . '">                               
                        <td>
                            <input type="hidden" name="BuyReturDet[product_id][]" value="' . $o->product_id . '"/>
                            <input type="hidden" name="BuyReturDet[price][]" class="detPrice" value="' . $o->price . '"/>                            
                            <input type="hidden" name="BuyReturDet[total][]" class="detTotal" value="' . $o->price * $o->qty . '"/>
                            <i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i>
                        </td>
                        <td>' . $o->Product->code . '</td>
                        <td>' . $o->Product->name . '</td>                        
                        <td> <input type="text" class="amount span1" name="BuyReturDet[qty][]" value="' . $o->qty . '"/>    ' . $o->Product->ProductMeasure->name . '</td>
                        <input type="hidden" id="realQty" value="' . $o->qty . '" />
                        <td>' . landa()->rp($o->price) . '</td>
                        <td>' . landa()->rp($o->qty * $o->price) . '</td>
                    </tr>';
    }


    $returnValue['button'] .= '<tr class="button_form">
                    <td colspan="5" style="text-align: right;padding-right: 15px"><b>Sub Total : </b></td>
                    <td>';
    $returnValue['button'] .= '<input type="hidden" name="BuyRetur[subtotal]" value="' . $model->subtotal . '"/>';
    if ($model->subtotal != "")
        $subtot = $model->subtotal;
    else
        $subtot = 0;

    $returnValue['button'] .= '<span id="subtotal">' . landa()->rp($subtot) . '</span>';
    $returnValue['button'] .= '
                        </td>
                    <tr class="button_form">
                        <td colspan="5" style="text-align: right;padding-right: 15px"><b>Other Cost : </b></td>
                        <td>';
    $returnValue['button'] .= '<div class="input-prepend"><span class="add-on">Rp</span><input name="BuyRetur[other]" value="" id="Buy_retur_other" type="text"></div>';

    $returnValue['button'].= '</td>
                    </tr>
                   
                    <tr class="button_form">
                        <td colspan="5" style="text-align: right;padding-right: 15px"><b>Total : </b></td>
                        <td>
                            <span id="grandTotal">' . landa()->rp($model->subtotal + $model->ppn + $model->other - $model->discount) . '</span>
                        </td>
                    </tr>';
}else {

   

    $returnValue['top'] .= '<table>
                <tr>
                    <td style="vertical-align: top !important" class="span6">                        
                        <div class="row-fluid">
                            <div class="span3">
                                Supplier
                            </div>
                            <div class="span1">:</div>
                            <div class="span8" style="text-align:left">'
            . CHtml::dropDownList('Buy[supplier_id]', '', CHtml::listData(User::model()->findAll(array()), 'id', 'name'), array(
                'empty' => t('choose', 'global'),
                'class' => 'span10',
                'disabled' => true,
            ))
            . '</div></div><hr style="margin-bottom:0px">';

    $returnValue['top'] .= '<span class="info"' . $this->renderPartial('_customerInfo', array('model' => ''), true) . '</span>';
    $returnValue['top'] .='</td>
                    <td style="vertical-align: top !important" class="span6">
                        <div class="control-group "><label class="control-label" for="Buy_retur_description">Description</label><div class="controls"><textarea disabled="true" class="span3" rows="4" name="description" id="Buy_retur_description">' . '' . '</textarea></div></div>
<div class="control-group "><label class="control-label required" for="Buy_retur_departement_id">Departement <span class="required">*</span></label>
                            <div class="controls">' . CHtml::dropDownList('departement', '', CHtml::listData(Departement::model()->findAll(), 'id', 'name'), array(
                'empty' => t('choose', 'global'), 'class' => 'span3', 'disabled' => true,
            )) . '</div></div>                            
                    </td>
                </tr>
           </table>';



    $returnValue['button'].= '<tr id="addRow" style="display:none">
                        <td></td>
                        <td></td>
                        <td></td>        
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>';


    $returnValue['button'] .= '<tr class="button_form">
                    <td colspan="5" style="text-align: right;padding-right: 15px"><b>Sub Total : </b></td>
                    <td>';
    $returnValue['button'] .= '<input type="hidden" name="BuyRetur[subtotal]" value=""/>';

    $returnValue['button'] .= '<span id="subtotal"></span>';
    $returnValue['button'] .= '
                        </td>
                        </tr>                        
                    <tr class="button_form">
                        <td colspan="5" style="text-align: right;padding-right: 15px"><b>Other Cost : </b></td>
                        <td>';
    $returnValue['button'] .= '<div class="input-prepend"><span class="add-on">Rp</span><input name="BuyRetur[other]" value="" id="Buy_retur_other" type="text"></div>';

    $returnValue['button'].= '</td>
                    </tr>                    
                    <tr class="button_form">
                        <td colspan="5" style="text-align: right;padding-right: 15px"><b>Total : </b></td>
                        <td>
                            <span id="grandTotal"></span>
                        </td>
                    </tr>';
}
$returnValue['button'] .= '<script>function rp(angka){
            var rupiah = "";
            var angkarev = angka.toString().split("").reverse().join("");
            for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+".";
            return rupiah.split("",rupiah.length-1).reverse().join("");
            };
            function calculate(){                
            $("#total").html("Rp. " + rp($("#price_buy").val() * $("#amount").val()));
            subtotal($("#price_buy").val() * $("#amount").val());
            };

            function subtotal(total){
            var subTotal = total;
            $(".detTotal").each(function() {
            subTotal += parseInt($(this).val());
            });
            $("#subtotal").html("Rp. " + rp(subTotal));  

            $("#Buy_retur_subtotal").val(subTotal);

            var grandTotal;                             
            var other = $("#Buy_retur_other").val();

            grandTotal = subTotal; 
            if (other!="")                            
            grandTotal = grandTotal + parseInt($("#Buy_retur_other").val());
            
            $("#grandTotal").html("Rp. " + rp(grandTotal));             
            }

            function clearField(){
            $("#total").html("");
            $("#stock").html("");
            $("#amount").val("");
            $("#price_buy").val("");
            $("#s2id_product_id").select2("data", null)
            $(".measure").html("");
            }


            $("#price_buy").on("input", function() {
            calculate();
            });
            $("#amount").on("input", function() {
            calculate();
            });

            $("#Buy_retur_other").on("input", function() {
           subtotal(0);
            });

            $(".amount").on("input", function() {  
                var realQty = parseInt($(this).parent().parent().find("#realQty").val());
                var thisQty = parseInt($(this).val());
                if (realQty < thisQty){
                    alert("Amount is too much!");
                    $(this).val(realQty);
                }
                    var price = parseInt($(this).parent().parent().find(".detPrice").val());
                    var subtot = parseInt($(this).val())*price;

                    $(this).parent().parent().find("td:eq(5)").html("Rp. " + rp(subtot));
                    $(this).parent().parent().find(".detTotal").val(subtot);
                    subtotal(0);

            });

            $("#Buy_retur_discount").on("input", function() {
            calculate();
            });

            $(".delRow").on("click", function() {
            $(this).parent().parent().remove();
            subtotal(0);
            });</script>';
echo json_encode($returnValue);
?>