<?php

class SupplierController extends Controller {

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
//            array('allow', // c
//                'actions' => array('index', 'create'),
//                'expression' => 'app()->controller->isValidAccess(1,"c")'
//            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("Supplier","r")'
            ),
//            array('allow', // u
//                'actions' => array('index', 'update'),
//                'expression' => 'app()->controller->isValidAccess(1,"u")'
//            ),
//            array('allow', // d
//                'actions' => array('index', 'delete'),
//                'expression' => 'app()->controller->isValidAccess(1,"d")'
//            )
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        cs()->registerScript('read', '
                    $("form input, form textarea, form select").each(function(){
                    $(this).prop("disabled", true);
                });');
        $_GET['v'] = true;
        $this->actionUpdate($id);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Supplier;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Supplier'])) {
            $model->attributes = $_POST['Supplier'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Supplier'])) {
            $model->attributes = $_POST['Supplier'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
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

        $model = new Supplier('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['Supplier'])) {
            $model->attributes = $_GET['Supplier'];
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Supplier::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'supplier-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionPayment() {
        $header = Supplier::model()->findAll(array(
            'order' => 't.name',
        ));
        $alert = false;
        if (isset($_POST['code'])) {
            for ($i = 0; $i < count($_POST['code']); $i++) {
                if (empty($_POST['id'][$i])) {
                    $payment = new InvoiceDet();
                } else {
                    $payment = InvoiceDet::model()->findByPk($_POST['id'][$i]);
                }
                $payment->code = (!empty($_POST['code'][$i])) ? $_POST['code'][$i] : null;
                $payment->description = (!empty($_POST['description'][$i])) ? $_POST['description'][$i] : null;
                $payment->user_id = (!empty($_POST['user_id'][$i])) ? $_POST['user_id'][$i] : null;
                $payment->payment = (!empty($_POST['payment'][$i])) ? $_POST['payment'][$i] : null;
                $payment->type = 'supplier';
                if(!empty($_POST['term_date'][$i]))
                    $payment->term_date = date('Y-m-d', strtotime($_POST['term_date'][$i]));
                
                if ($payment->save()) {
                    if (empty($_POST['id_coaDet'][$i])) {
                        $coaDet = new AccCoaDet();
                    } else {
                        $coaDet = AccCoaDet::model()->findByPk($_POST['id_coaDet'][$i]);
                    }
                    $coaDet->description = $payment->description;
                    $coaDet->reff_type = "invoice";
                    if ($payment->is_new_invoice == 1) {
                        $coaDet->credit = 0;
                        $coaDet->debet = 0;
                    } else {
                        $coaDet->credit = $_POST['payment'][$i];
                    }
                    $coaDet->invoice_det_id = $payment->id;
                    $coaDet->date_coa = date('Y-m-d', strtotime($_POST['date_coa'][$i]));
                    $coaDet->save();
                }
                $alert = true;
            }
        }
        $this->render('payment', array(
            'header' => $header,
            'alert' => $alert
        ));
    }

    public function actionAddRow() {
        if (!empty($_POST['code'])) {
            echo '<tr>'
            . '<td>'
            . '<input type="text" class="code" style="width:94%" name="code[]" value="' . $_POST['code'] . '">'
            . '</td>'
            . '<td>'
            . '<input type="text" class="dateStart" style="width:90%" name="date_coa[]" value="' . $_POST['date_coa'] . '">'
            . '</td>'
            . '<td>'
            . '<input type="text" class="term" style="width:90%" name="term_date[]" value="' . $_POST['terms'] . '">'
            . '</td>'
            . '<td>'
            . '<input type="text" style="width:98%" name="description[]" value="' . $_POST['desc'] . '">'
            . '</td>'
            . '<td style="text-align:center">
                <div class="input-prepend">
                    <span class="add-on">Rp.</span>
                    <input type="text" class="angka charge nilai" name="payment[]" value="' . $_POST['payment'] . '">
                </div>
            </td>'
            . '<td>'
            . '<span style="width:12px" class="btn delInv"><i class="icon-trash"></i></span>'
            . '<input type="hidden" class="user" name="user_id[]" value="' . $_POST['sup_id'] . '">'
            . '<input type="hidden" class="id_invoice" name="id[]" value="">'
            . '<input type="hidden" class="id_coaDet" name="id_coaDet[]" value="">'
            . '</td>'
            . '</tr>'
            . '<tr class="addRows" style="display:none;">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>';
        }
    }
    public function actionInvoiceDetail() {
        $userInvoice = AccCoaDet::model()->findAll(array(
            'with' => array('InvoiceDet'),
            'condition' => 'InvoiceDet.user_id=' . $_POST['id'] . ' AND reff_type="invoice"'
        ));
        $ambil = ($_POST['id'] == 0) ? false : true;
        $balance = InvoiceDet::model()->findAllByAttributes(array('user_id' => $_POST['id']));
        echo $this->renderPartial('_payment', array(
            'userInvoice' => $userInvoice,
            'ambil' => $ambil,
            'alert' => false,
            'balance' => $balance
                ), true);
    }

}
