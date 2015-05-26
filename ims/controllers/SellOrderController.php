<?php

class SellOrderController extends Controller {

    public $breadcrumbs;

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'main';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow', // c
                'actions' => array('create'),
                'expression' => 'app()->controller->isValidAccess("SellOrder","c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("SellOrder","r")'
            ),
            array('allow', // u
                'actions' => array('update'),
                'expression' => 'app()->controller->isValidAccess("SellOrder","u")'
            ),
            array('allow', // d
                'actions' => array('delete'),
                'expression' => 'app()->controller->isValidAccess("SellOrder","d")'
            )
        );
    }
    public function actionGetSellInfo(){
          $model = User::model()->findByPk($_POST['SellOrder']['customer_user_id']);
          echo $this->renderPartial('_customerInfo',array('model'=>$model));
    }    

    public function cssJs() {
        cs()->registerCss('', '
                .measure{margin-left: 5px;}
                #addRow{display:none}
                ');
        cs()->registerScript('', '
                        function rp(angka){
                            var rupiah = "";
                            var angkarev = angka.toString().split("").reverse().join("");
                            for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+".";
                            return rupiah.split("",rupiah.length-1).reverse().join("");
                        };
                        function calculate(){
                            $("#total").html("Rp. " + rp($("#price_buy").val() * $("#amount").val()));
                            changePPN();
                        };
                        
                        function subtotal(total){
                            var subTotal = total;
                            $(".rowPPN").show(); 
                            $(".detTotal").each(function() {
                                 subTotal += parseInt($(this).val());
                            });
                            $("#subtotal").html("Rp. " + rp(subTotal));  
                            $("#ppn").html("Rp. " + rp(subTotal*(10/100),0));  
                            
                            $("#SellOrder_subtotal").val(subTotal);
                            $("#SellOrder_ppn").val(subTotal*(10/100));
                            
                                                        
                            var grandTotal;                             
                            var other = $("#SellOrder_other").val();
                            var discount = $("#SellOrder_discount").val();
                            
                            grandTotal = subTotal+ ((10/100)*subTotal); 
                            if (other!="")                            
                                grandTotal = grandTotal + parseInt($("#SellOrder_other").val());
                            if (discount!="")
                                grandTotal = grandTotal - parseInt($("#SellOrder_discount").val());

                            $("#grandTotal").html("Rp. " + rp(grandTotal)); 
                    
                        }
                        
                        function NoPPN(total){
                            var subTotal = total;
                            $(".detTotal").each(function() {
                                 subTotal += parseInt($(this).val());
                            });
                            $(".rowPPN").hide();                                                          
                            $("#subtotal").html("Rp. " + rp(subTotal));                                                          
                            $("#SellOrder_subtotal").val(subTotal);
                            $("#SellOrder_ppn").val(0);
                            
                                                        
                            var grandTotal;                             
                            var other = $("#SellOrder_other").val();
                            var discount = $("#SellOrder_discount").val();
                            
                            grandTotal = subTotal; 
                            if (other!="")                            
                                grandTotal = grandTotal + parseInt($("#SellOrder_other").val());
                            if (discount!="")
                                grandTotal = grandTotal - parseInt($("#SellOrder_discount").val());

                            $("#grandTotal").html("Rp. " + rp(grandTotal)); 
                            $("#allTotal").val(grandTotal); 
                    
                        }
                        
                        function changePPN(){
                            if($("#checkPPN").is(":checked"))
                                subtotal(0);
                            else
                                NoPPN(0);
                        }    
                        
                        function clearField(){
                            $("#total").html("");
                            $("#stock").html("");
                            $("#amount").val("");
                            $("#price_buy").val("");
                            $("#s2id_product_id").select2("data", null)
                            $(".measure").html("");
                        }
                        
                        $("#SellOrder_departement_id").on("change", function() {  
                            if ($(".delRow")[0]){
                                var x=window.confirm("Data inserted will be lost. Are you sure?")
                                if (x)
                                {
                                    $(".delRow").parent().parent().remove();
                                    $("#subtotal").html("");
                                    clearField();
                                    calculate();
                                }
                            }
                        });  

                        $("#price_buy").on("input", function() {
                            calculate();
                        });
                        $("#amount").on("input", function() {
                            calculate();
                        });
                        
                        $("#SellOrder_other").on("input", function() {
                            calculate();
                        });
                        
                        $("#SellOrder_discount").on("input", function() {
                            calculate();
                        });
                        
                        $(".delRow").on("click", function() {
                              $(this).parent().parent().remove();
                              changePPN();
                          });
                        
                    ');
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model = $this->loadModel($id);
        $mSellOrderDet = SellOrderDet::model()->findAll(array('condition' => 'sell_order_id=' . $model->id));
        $this->render('view', array(
            'mSellOrderDet' => $mSellOrderDet,
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $this->cssJs();
        $model = new SellOrder;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['SellOrder'])) {
            $model->attributes = $_POST['SellOrder'];
            $model->code = SiteConfig::model()->formatting('sellorder', false);
            if (!empty($_POST['SellOrderDet'])) {
                $model->term = date('Y-m-d', strtotime($model->term));
                $model->status = 'process';
                $model->total = (int)$_POST['SellOrder']['subtotal']+(int)$_POST['SellOrder']['ppn']-(int)$_POST['SellOrder']['discount']+(int)$_POST['SellOrder']['other'];
                if ($model->save()) {
                    for ($i = 0; $i < count($_POST['SellOrderDet']['product_id']); $i++) {
                        $mInDet = new SellOrderDet;
                        $mInDet->sell_order_id = $model->id;
                        $mInDet->product_id = $_POST['SellOrderDet']['product_id'][$i];
                        $mInDet->qty = $_POST['SellOrderDet']['qty'][$i];
                        $mInDet->price = $_POST['SellOrderDet']['price'][$i];
                        $mInDet->save();
                    }
                    $this->redirect(array('view', 'id' => $model->id));
                }
            } else {
                Yii::app()->user->setFlash('error', '<strong>Error! </strong>No product added.');
            }
        }
        $model->code = SiteConfig::model()->formatting('sellorder');
        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionAddRow() {

        $model = Product::model()->findByPk($_POST['product_id']);
        $measure = (isset($model->ProductMeasure->name)) ? $model->ProductMeasure->name : '';
//        if ($_POST['stock'] - $_POST['amount'] < 1) {
//            echo 'error';
//        } else {
            echo '
                <tr id="addRow">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>                        
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="SellOrderDet[product_id][]" id="' . $model->id . '" value="' . $model->id . '"/>
                            <input type="hidden" name="SellOrderDet[price][]" id="detPrice" value="' . $_POST['price_buy'] . '"/>
                            <input type="hidden" name="SellOrderDet[qty][]" id="detQty" value="' . $_POST['amount'] . '"/>
                            <input type="hidden" name="SellOrderDet[total][]" id="detTotalq" class="detTotal" value="' . $_POST['price_buy'] * $_POST['amount'] . '"/>
                            <i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i>
                        </td>
                        <td>' . $model->code . '</td>
                        <td colspan="2">' . $model->name . '</td>                        
                        <td><span id="detAmount">' . $_POST['amount'] . '</span> ' . $measure . '</td>
                        <td>' . landa()->rp($_POST['price_buy']) . '</td>
                        <td>' . landa()->rp($_POST['amount'] * $_POST['price_buy']) . '</td>
                    </tr>';
//        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $this->cssJs();

        $model = $this->loadModel($id);
        $mSellOrderDet = SellOrderDet::model()->findAll(array('condition' => 'sell_order_id=' . $model->id));
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['SellOrder'])) {
            $model->attributes = $_POST['SellOrder'];
            $model->total = (int)$_POST['SellOrder']['subtotal']+(int)$_POST['SellOrder']['ppn']-(int)$_POST['SellOrder']['discount']+(int)$_POST['SellOrder']['other'];
            if (!empty($_POST['SellOrderDet'])) {
                $model->term = date('Y-m-d', strtotime($model->term));
                if ($model->save()) {
                    SellOrderDet::model()->deleteAll(array('condition' => 'sell_order_id=' . $id)); //delete first all record who related in IN
                    for ($i = 0; $i < count($_POST['SellOrderDet']['product_id']); $i++) {
                        $mInDet = new SellOrderDet;
                        $mInDet->sell_order_id = $model->id;
                        $mInDet->product_id = $_POST['SellOrderDet']['product_id'][$i];
                        $mInDet->qty = $_POST['SellOrderDet']['qty'][$i];
                        $mInDet->price = $_POST['SellOrderDet']['price'][$i];
                        $mInDet->save();
                    }
                    $this->redirect(array('view', 'id' => $model->id));
                }
            } else {
                Yii::app()->user->setFlash('error', '<strong>Error! </strong>No product added.');
            }
        }

        $this->render('update', array(
            'mSellOrderDet' => $mSellOrderDet,
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();
            SellOrderDet::model()->deleteAll('sell_order_id='.$id);
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
//        $session = new CHttpSession;
//        $session->open();
        $criteria = new CDbCriteria();

        $model = new SellOrder('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['SellOrder'])) {
            $model->attributes = $_GET['SellOrder'];



            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');


            if (!empty($model->code))
                $criteria->addCondition('code = "' . $model->code . '"');


            if (!empty($model->departement_id))
                $criteria->addCondition('departement_id = "' . $model->departement_id . '"');


            if (!empty($model->created))
                $criteria->addCondition('created = "' . $model->created . '"');


            if (!empty($model->created_user_id))
                $criteria->addCondition('created_user_id = "' . $model->created_user_id . '"');


            if (!empty($model->modified))
                $criteria->addCondition('modified = "' . $model->modified . '"');


            if (!empty($model->description))
                $criteria->addCondition('description = "' . $model->description . '"');


            if (!empty($model->subtotal))
                $criteria->addCondition('subtotal = "' . $model->subtotal . '"');


            if (!empty($model->discount))
                $criteria->addCondition('discount = "' . $model->discount . '"');

            if (!empty($model->ppn))
                $criteria->addCondition('ppn = "' . $model->ppn . '"');


            if (!empty($model->tax))
                $criteria->addCondition('tax = "' . $model->tax . '"');


            if (!empty($model->term))
                $criteria->addCondition('term = "' . $model->term . '"');


            if (!empty($model->dp))
                $criteria->addCondition('dp = "' . $model->dp . '"');


            if (!empty($model->credit))
                $criteria->addCondition('credit = "' . $model->credit . '"');


            if (!empty($model->payment))
                $criteria->addCondition('payment = "' . $model->payment . '"');
        }
//        $session['SellOrder_records'] = SellOrder::model()->findAll($criteria);


        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new SellOrder('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['SellOrder']))
            $model->attributes = $_GET['SellOrder'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = SellOrder::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'sell-order-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
