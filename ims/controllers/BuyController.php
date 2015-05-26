<?php

class BuyController extends Controller {

    public $breadcrumbs;

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'main';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow', // c
                'actions' => array('create'),
                'expression' => 'app()->controller->isValidAccess("Buy","c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("Buy","r")'
            ),
            array('allow', // u
                'actions' => array('update'),
                'expression' => 'app()->controller->isValidAccess("Buy","u")'
            ),
            array('allow', // d
                'actions' => array('delete'),
                'expression' => 'app()->controller->isValidAccess("Buy","d")'
            )
        );
    }

    public function actionInfo() {
        $model = User::model()->findByPk($_POST['Buy']['supplier_id']);
        echo $this->renderPartial('_customerInfo', array('model' => $model));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model = $this->loadModel($id);
        $mBuyDet = BuyDet::model()->findAll(array('condition' => 'buy_id=' . $model->id));
        $this->render('view', array(
            'mBuyDet' => $mBuyDet,
            'model' => $model,
        ));
    }

    public function actionGetBuyOrder() {
        $idBuyOrder = $_POST['BuyOrder'];
        $js = $this->js();
        $a = $this->renderPartial('_buyOrder', array('idBuyOrder' => $idBuyOrder), true);
        $b = json_decode($a);
        $b->button .= '<script>' . $js . '</script>';
        echo json_encode($b);
    }

    public function js() {
        return '
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
                 $(".rowPPN").show();  
                var subTotal = total;
                $(".detTotal").each(function() {
                subTotal += parseInt($(this).val());
                });
                $("#subtotal").html("Rp. " + rp(subTotal));  
                $("#ppn").html("Rp. " + rp(subTotal*(10/100),0));  

                $("#Buy_subtotal").val(subTotal);
                $("#Buy_ppn").val(subTotal*(10/100));


                var grandTotal;                             
                var other = $("#Buy_other").val();
                var discount = $("#Buy_discount").val();

                grandTotal = subTotal+ ((10/100)*subTotal); 
                if (other!="")                            
                grandTotal = grandTotal + parseInt($("#Buy_other").val());
                if (discount!="")
                grandTotal = grandTotal - parseInt($("#Buy_discount").val());

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
                $("#Buy_subtotal").val(subTotal);
                $("#Buy_ppn").val(0);                


                var grandTotal;                             
                var other = $("#Buy_other").val();
                var discount = $("#Buy_discount").val();

                grandTotal = subTotal; 
                if (other!="")                            
                    grandTotal = grandTotal + parseInt($("#Buy_other").val());
                if (discount!="")
                    grandTotal = grandTotal - parseInt($("#Buy_discount").val());

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

            $("#Buy_departement_id").on("change", function() {  
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

            $("#Buy_other").on("input", function() {
            calculate();
            });

            $("#Buy_discount").on("input", function() {
            calculate();
            });

            $(".delRow").on("click", function() {
            $(this).parent().parent().remove();
            changePPN();
            });';
    }

    public function cssJs() {
        $js = $this->js();
        cs()->registerCss('', '
            .measure{margin-left: 5px;}
            #addRow{display:none}
            ');
        cs()->registerScript('', $js);
    }

    public function actionCreate() {
        $this->cssJs();
        $model = new Buy;

        if (isset($_POST['Buy'])) {
            $model->attributes = $_POST['Buy'];
            $model->code = SiteConfig::model()->formatting('buy', false);
            if (!empty($_POST['BuyDet'])) {
                $model->buy_order_id = $_POST['BuyOrder'];
                $model->total = $_POST['allTotal'];
                if ($model->save()) {
                    if ($_POST['BuyOrder'] != "") {
                        $model_buy_order = BuyOrder::model()->findByPk($_POST['BuyOrder']);
                        $model_buy_order->status = "confirm";
                        $model_buy_order->save();
                    }
                    for ($i = 0; $i < count($_POST['BuyDet']['product_id']); $i++) {
                        $mInDet = new BuyDet;
                        $mInDet->buy_id = $model->id;
                        $mInDet->product_id = $_POST['BuyDet']['product_id'][$i];
                        $mInDet->qty = $_POST['BuyDet']['qty'][$i];
                        $mInDet->price = $_POST['BuyDet']['price'][$i];
                        $mInDet->save();

                        //update price buy product
                        $product = Product::model()->findByPk($_POST['BuyDet']['product_id'][$i]);
                        $product->price_buy = $_POST['BuyDet']['price'][$i];
                        $product->save();

                        //update stock
                        ProductStock::model()->process('in', $mInDet->product_id, $mInDet->qty, $model->departement_id, $mInDet->price, 'Buy');
                    }
                    $this->redirect(array('view', 'id' => $model->id));
                }
            } else {
                Yii::app()->user->setFlash('error', '<strong>Error! </strong>No product added.');
            }
        }

        $model->code = SiteConfig::model()->formatting('buy');
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
                            <input type="hidden" name="BuyDet[product_id][]" id="' . $model->id . '" value="' . $model->id . '"/>
                            <input type="hidden" name="BuyDet[price][]" id="detPrice" value="' . $_POST['price_buy'] . '"/>
                            <input type="hidden" name="BuyDet[qty][]" id="detQty" value="' . $_POST['amount'] . '"/>
                            <input type="hidden" name="BuyDet[total][]" id="detTotalq" class="detTotal" value="' . $_POST['price_buy'] * $_POST['amount'] . '"/>
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
        $mBuyDet = BuyDet::model()->findAll(array('condition' => 'buy_id=' . $model->id));

        if (isset($_POST['Buy'])) {
            $model->attributes = $_POST['Buy'];
            $model->total = $_POST['allTotal'];
            if (!empty($_POST['BuyDet'])) {
                if ($model->save()) {
                    BuyDet::model()->deleteAll(array('condition' => 'buy_id=' . $id)); //delete first all record who related in IN
                    for ($i = 0; $i < count($_POST['BuyDet']['product_id']); $i++) {
                        $mInDet = new BuyDet;
                        $mInDet->buy_id = $model->id;
                        $mInDet->product_id = $_POST['BuyDet']['product_id'][$i];
                        $mInDet->qty = $_POST['BuyDet']['qty'][$i];
                        $mInDet->price = $_POST['BuyDet']['price'][$i];
                        $mInDet->save();
                                                                        
                        
                    }
                    $this->redirect(array('view', 'id' => $model->id));
                }
            } else {
                Yii::app()->user->setFlash('error', '<strong>Error! </strong>No product added.');
            }
        }

        $this->render('update', array(
            'mBuyDet' => $mBuyDet,
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
            BuyDet::model()->deleteAll('buy_id=' . $id);
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

        $model = new Buy('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['Buy'])) {
            $model->attributes = $_GET['Buy'];



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


            if (!empty($model->dp))
                $criteria->addCondition('dp = "' . $model->dp . '"');


            if (!empty($model->credit))
                $criteria->addCondition('credit = "' . $model->credit . '"');


            if (!empty($model->payment))
                $criteria->addCondition('payment = "' . $model->payment . '"');
        }
//        $session['Buy_records'] = Buy::model()->findAll($criteria);


        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Buy('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Buy']))
            $model->attributes = $_GET['Buy'];

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
        $model = Buy::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'buy-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionGenerateExcel() {
        $session = new CHttpSession;
        $session->open();

        if (isset($session['Buy_records'])) {
            $model = $session['Buy_records'];
        } else
            $model = Buy::model()->findAll();


        Yii::app()->request->sendFile(date('YmdHis') . '.xls', $this->renderPartial('excelReport', array(
                    'model' => $model
                        ), true)
        );
    }

    public function actionGeneratePdf() {

        $session = new CHttpSession;
        $session->open();
        Yii::import('application.modules.admin.extensions.giiplus.bootstrap.*');
        require_once(Yii::getPathOfAlias('common') . '/extensions/tcpdf/tcpdf.php');
        require_once(Yii::getPathOfAlias('common') . '/extensions/tcpdf/config/lang/eng.php');

        if (isset($session['Buy_records'])) {
            $model = $session['Buy_records'];
        } else
            $model = Buy::model()->findAll();



        $html = $this->renderPartial('expenseGridtoReport', array(
            'model' => $model
                ), true);

//die($html);

        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(Yii::app()->name);
        $pdf->SetTitle('Laporan Buy');
        $pdf->SetSubject('Laporan Buy Report');
//$pdf->SetKeywords('example, text, report');
        $pdf->SetHeaderData('', 0, "Report", '');
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "Laporan" Buy, "");
        $pdf->SetHeaderData("", "", "Laporan Buy", "");
        $pdf->setHeaderFont(Array('helvetica', '', 8));
        $pdf->setFooterFont(Array('helvetica', '', 6));
        $pdf->SetMargins(15, 18, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
        $pdf->SetAutoPageBreak(TRUE, 0);
        $pdf->SetFont('dejavusans', '', 7);
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->LastPage();
        $pdf->Output("Buy_002.pdf", "I");
    }

}
