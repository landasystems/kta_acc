<?php

class BuyOrderController extends Controller {

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
                'expression' => 'app()->controller->isValidAccess("BuyOrder","c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("BuyOrder","r")'
            ),
            array('allow', // u
                'actions' => array('update'),
                'expression' => 'app()->controller->isValidAccess("BuyOrder","u")'
            ),
            array('allow', // d
                'actions' => array('delete'),
                'expression' => 'app()->controller->isValidAccess("BuyOrder","d")'
            )
        );
    }

    public function actionInfo() {
        $model = User::model()->findByPk($_POST['BuyOrder']['supplier_id']);
        echo $this->renderPartial('_customerInfo', array('model' => $model));
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
                            
                            $("#BuyOrder_subtotal").val(subTotal);
                            $("#BuyOrder_ppn").val(subTotal*(10/100));
                            
                                                        
                            var grandTotal;                             
                            var other = $("#BuyOrder_other").val();
                            var discount = $("#BuyOrder_discount").val();
                            
                            grandTotal = subTotal+ ((10/100)*subTotal); 
                            if (other!="")                            
                                grandTotal = grandTotal + parseInt($("#BuyOrder_other").val());
                            if (discount!="")
                                grandTotal = grandTotal - parseInt($("#BuyOrder_discount").val());

                            $("#grandTotal").html("Rp. " + rp(grandTotal)); 
                            $("#allTotal").val(grandTotal); 
                    
                        }
                        function NoPPN(total){
                            var subTotal = total;
                            $(".detTotal").each(function() {
                                 subTotal += parseInt($(this).val());
                            });
                            $(".rowPPN").hide();                                                          
                            $("#subtotal").html("Rp. " + rp(subTotal));                                                          
                            $("#BuyOrder_subtotal").val(subTotal);
                            $("#BuyOrder_ppn").val(0);
                            
                                                        
                            var grandTotal;                             
                            var other = $("#BuyOrder_other").val();
                            var discount = $("#BuyOrder_discount").val();
                            
                            grandTotal = subTotal; 
                            if (other!="")                            
                                grandTotal = grandTotal + parseInt($("#BuyOrder_other").val());
                            if (discount!="")
                                grandTotal = grandTotal - parseInt($("#BuyOrder_discount").val());

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
                            $("#BuyOrder_subtotal").val("");
                            
                        }
                        
                        $("#BuyOrder_departement_id").on("change", function() {  
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
                        
                        $("#BuyOrder_other").on("input", function() {
                            calculate();
                        });
                        
                        $("#BuyOrder_discount").on("input", function() {
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
        $mBuyOrderDet = BuyOrderDet::model()->findAll(array('condition' => 'buy_order_id=' . $model->id));
        $this->render('view', array(
            'mBuyOrderDet' => $mBuyOrderDet,
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $this->cssJs();
        $model = new BuyOrder;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['BuyOrder'])) {
            $model->attributes = $_POST['BuyOrder'];
            $model->code = SiteConfig::model()->formatting('buyorder', false);
            if (!empty($_POST['BuyOrderDet'])) {
                $model->date_delivery = date('Y-m-d', strtotime($model->date_delivery));
                $model->status = 'process';
                $model->total = $_POST['allTotal'];
                if ($model->save()) {
                    for ($i = 0; $i < count($_POST['BuyOrderDet']['product_id']); $i++) {
                        $mInDet = new BuyOrderDet;
                        $mInDet->buy_order_id = $model->id;
                        $mInDet->product_id = $_POST['BuyOrderDet']['product_id'][$i];
                        $mInDet->qty = $_POST['BuyOrderDet']['qty'][$i];
                        $mInDet->price = $_POST['BuyOrderDet']['price'][$i];
                        $mInDet->save();
                    }
                    $this->redirect(array('view', 'id' => $model->id));
                }
            } else {
                Yii::app()->user->setFlash('error', '<strong>Error! </strong>No product added.');
            }
        }
        $model->code = SiteConfig::model()->formatting('buyorder');
        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionAddRow() {

        $model = Product::model()->findByPk($_POST['product_id']);
        $measure = (isset($model->ProductMeasure->name)) ? $model->ProductMeasure->name : '';

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
                            <input type="hidden" name="BuyOrderDet[product_id][]" id="' . $model->id . '" value="' . $model->id . '"/>
                            <input type="hidden" name="BuyOrderDet[price][]" id="detPrice" value="' . $_POST['price_buy'] . '"/>
                            <input type="hidden" name="BuyOrderDet[qty][]" id="detQty" value="' . $_POST['amount'] . '"/>
                            <input type="hidden" name="BuyOrderDet[total][]" id="detTotalq" class="detTotal" value="' . $_POST['price_buy'] * $_POST['amount'] . '"/>
                            <i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i>
                        </td>
                        <td>' . $model->code . '</td>
                        <td colspan="2">' . $model->name . '</td>                        
                        <td><span id="detAmount">' . $_POST['amount'] . '</span> ' . $measure . '</td>
                        <td>' . landa()->rp($_POST['price_buy']) . '</td>
                        <td>' . landa()->rp($_POST['amount'] * $_POST['price_buy']) . '</td>
                    </tr>';
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $this->cssJs();

        $model = $this->loadModel($id);
        $mBuyOrderDet = BuyOrderDet::model()->findAll(array('condition' => 'buy_order_id=' . $model->id));
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['BuyOrder'])) {
            $model->attributes = $_POST['BuyOrder'];
            $model->total = $_POST['allTotal'];
            if (!empty($_POST['BuyOrderDet'])) {
                $model->date_delivery = date('Y-m-d', strtotime($model->date_delivery));
                if ($model->save()) {
                    BuyOrderDet::model()->deleteAll(array('condition' => 'buy_order_id=' . $id)); //delete first all record who related in IN
                    for ($i = 0; $i < count($_POST['BuyOrderDet']['product_id']); $i++) {
                        $mInDet = new BuyOrderDet;
                        $mInDet->buy_order_id = $model->id;
                        $mInDet->product_id = $_POST['BuyOrderDet']['product_id'][$i];
                        $mInDet->qty = $_POST['BuyOrderDet']['qty'][$i];
                        $mInDet->price = $_POST['BuyOrderDet']['price'][$i];
                        $mInDet->save();
                    }
                    $this->redirect(array('view', 'id' => $model->id));
                }
            } else {
                Yii::app()->user->setFlash('error', '<strong>Error! </strong>No product added.');
            }
        }

        $this->render('update', array(
            'mBuyOrderDet' => $mBuyOrderDet,
            'model' => $model,
        ));
    }

    public function actionUpdateDiscount() {
        //print_r($_POST);
        $this->renderPartial('_form_discount', array('status' => $_POST['status']));
        //echo $form->textFieldRow($model, 'discount', array('class' => 'span1', 'append' => 'Rp')); 
        // echo 'aaaaaaa';
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
            BuyOrderDet::model()->deleteAll('buy_order_id=' . $id);
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

        $criteria = new CDbCriteria();

        $model = new BuyOrder('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['BuyOrder'])) {
            $model->attributes = $_GET['BuyOrder'];



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


            if (!empty($model->supplier_id))
                $criteria->addCondition('supplier_id = "' . $model->supplier_id . '"');


            if (!empty($model->description))
                $criteria->addCondition('description = "' . $model->description . '"');


            if (!empty($model->subtotal))
                $criteria->addCondition('subtotal = "' . $model->subtotal . '"');


            if (!empty($model->discount))
                $criteria->addCondition('discount = "' . $model->discount . '"');


            if (!empty($model->ppn))
                $criteria->addCondition('ppn = "' . $model->ppn . '"');


            if (!empty($model->other))
                $criteria->addCondition('other = "' . $model->other . '"');


            if (!empty($model->date_delivery))
                $criteria->addCondition('date_delivery = "' . $model->date_delivery . '"');


            if (!empty($model->dp))
                $criteria->addCondition('dp = "' . $model->dp . '"');


            if (!empty($model->credit))
                $criteria->addCondition('credit = "' . $model->credit . '"');


            if (!empty($model->payment))
                $criteria->addCondition('payment = "' . $model->payment . '"');
        }
//        $session['BuyOrder_records'] = BuyOrder::model()->findAll($criteria);


        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new BuyOrder('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['BuyOrder']))
            $model->attributes = $_GET['BuyOrder'];

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
        $model = BuyOrder::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'buy-order-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
