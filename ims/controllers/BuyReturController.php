<?php

class BuyReturController extends Controller {

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
                'expression' => 'app()->controller->isValidAccess("BuyRetur","c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("BuyRetur","r")'
            ),
            array('allow', // u
                'actions' => array('update'),
                'expression' => 'app()->controller->isValidAccess("BuyRetur","u")'
            ),
            array('allow', // d
                'actions' => array('delete'),
                'expression' => 'app()->controller->isValidAccess("BuyRetur","d")'
            )
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model = $this->loadModel($id);
        $mBuyReturDet = BuyReturDet::model()->findAll(array('condition' => 'buy_retur_id=' . $model->id));
        $this->render('view', array(
            'mBuyReturDet' => $mBuyReturDet,
            'model' => $model,
        ));
    }

    public function actionGetBuy() {
        $idBuy = $_POST['Buy'];
        $a = $this->renderPartial('_buy', array('idBuy' => $idBuy),true);
        echo ($a);             
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
            subtotal($("#price_buy").val() * $("#amount").val());
            };

            function subtotal(total){
            var subTotal = total;
            $(".detTotal").each(function() {
            subTotal += parseInt($(this).val());
            });
            $("#subtotal").html("Rp. " + rp(subTotal));  

            $("#BuyRetur_subtotal").val(subTotal);

            var grandTotal;                             
            var other = $("#BuyRetur_other").val();

            grandTotal = subTotal; 
            if (other!="")                            
            grandTotal = grandTotal + parseInt($("#BuyRetur_other").val());
            
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

            $("#BuyRetur_other").on("input", function() {
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


            $(".delRow").on("click", function() {
            $(this).parent().parent().remove();
            subtotal(0);
            });
            ');
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $this->cssJs();
        $model = new BuyRetur;

        if (isset($_POST['BuyRetur'])) {
            $model->attributes = $_POST['BuyRetur'];
            $model->code = SiteConfig::model()->formatting('buyretur', false);
            if (!empty($_POST['BuyReturDet'])) {
                $model->buy_id = $_POST['Buy'];
                $model->total = (int)$_POST['BuyRetur']['subtotal']+(int)$_POST['BuyRetur']['other'];
                if ($model->save()) {
                    for ($i = 0; $i < count($_POST['BuyReturDet']['product_id']); $i++) {
                        $mInDet = new BuyReturDet;
                        $mInDet->buy_retur_id = $model->id;
                        $mInDet->product_id = $_POST['BuyReturDet']['product_id'][$i];
                        $mInDet->qty = $_POST['BuyReturDet']['qty'][$i];
                        $mInDet->price = $_POST['BuyReturDet']['price'][$i];
                        $mInDet->save();
                        
                        //update stock
                        ProductStock::model()->process('out', $mInDet->product_id, $mInDet->qty, $model->departement_id, $mInDet->price, 'Retur Buy');
                    }
                    $this->redirect(array('view', 'id' => $model->id));
                }
            } else {
                Yii::app()->user->setFlash('error', '<strong>Error! </strong>No transaction added.');
            }
        }

        $model->code = SiteConfig::model()->formatting('buyretur');
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
                    <td></td>
                </tr>
                <tr class="button_form">
                    <td>
                        <input type="hidden" name="BuyReturDet[product_id][]" value="' . $model->id . '"/>
                        <input type="hidden" name="BuyReturDet[price][]" value="' . $_POST['price_buy'] . '"/>
                        <input type="hidden" name="BuyReturDet[qty][]" value="' . $_POST['amount'] . '"/>
                        <input type="hidden" name="BuyReturDet[total][]" class="detTotal" value="' . $_POST['price_buy'] * $_POST['amount'] . '"/>
                        <i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i>
                    </td>
                    <td>' . $model->code . '</td>
                    <td>' . $model->name . '</td>
                    <td>' . $model->stock . ' ' . $measure . '</td>
                    <td>' . $_POST['amount'] . ' ' . $measure . '</td>
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
        $mBuyReturDet = BuyReturDet::model()->findAll(array('condition' => 'buy_retur_id=' . $model->id));

        if (isset($_POST['BuyRetur'])) {
            $model->attributes = $_POST['BuyRetur'];
            if (!empty($_POST['BuyReturDet'])) {
                if ($model->save()) {
                    BuyReturDet::model()->deleteAll(array('condition' => 'buy_retur_id=' . $id)); //delete first all record who related in IN
                    for ($i = 0; $i < count($_POST['BuyReturDet']['product_id']); $i++) {
                        $mInDet = new BuyReturDet;
                        $mInDet->buy_retur_id = $model->id;
                        $mInDet->product_id = $_POST['BuyReturDet']['product_id'][$i];
                        $mInDet->qty = $_POST['BuyReturDet']['qty'][$i];
                        $mInDet->price = $_POST['BuyReturDet']['price'][$i];
                        $mInDet->save();
                    }
                    $this->redirect(array('view', 'id' => $model->id));
                }
            } else {
                Yii::app()->user->setFlash('error', '<strong>Error! </strong>No product added.');
            }
        }

        $this->render('update', array(
            'mBuyReturDet' => $mBuyReturDet,
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
            BuyReturDet::model()->deleteAll('buy_retur_id='.$id);

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

        $model = new BuyRetur('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['BuyRetur'])) {
            $model->attributes = $_GET['BuyRetur'];
            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');


            if (!empty($model->code))
                $criteria->addCondition('code = "' . $model->code . '"');


            if (!empty($model->buy_id))
                $criteria->addCondition('buy_id = "' . $model->buy_id . '"');


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



            if (!empty($model->other))
                $criteria->addCondition('other = "' . $model->other . '"');


        }
//        $session['BuyRetur_records'] = BuyRetur::model()->findAll($criteria);
        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new BuyRetur('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['BuyRetur']))
            $model->attributes = $_GET['BuyRetur'];

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
        $model = BuyRetur::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'buy-retur-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
