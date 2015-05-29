<?php

class SalaryOutController extends Controller {

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
                'actions' => array('index', 'create'),
                'expression' => 'app()->controller->isValidAccess(1,"c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess(1,"r")'
            ),
            array('allow', // u
                'actions' => array('index', 'update'),
                'expression' => 'app()->controller->isValidAccess(1,"u")'
            ),
            array('allow', // d
                'actions' => array('index', 'delete'),
                'expression' => 'app()->controller->isValidAccess(1,"d")'
            )
        );
    }

    public function actionDetail() {
        $user_id = (!empty($_POST['user_id'])) ? $_POST['user_id'] : '';
        $max_date = date('Y/m/d', strtotime($_POST['max_date']));
        $criteria = new CDbCriteria();
        $criteria->with = array('StartUser');
        $criteria->addCondition('end_user_id !=0');
        $criteria->addCondition('is_payment =0');
//        logs($user_id);
        if (!empty($user_id)) {
            $criteria->addInCondition('start_user_id',$user_id);
        }
        if (!empty($max_date)) {
            $criteria->addCondition('time_end <="' . $max_date . '"');
        }
           $criteria->order = 'StartUser.name';
        $process = WorkorderProcess::model()->findAll($criteria);
        echo $this->renderPartial('_salaryDetail', array('process' => $process));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        cs()->registerScript('', '$("#salary-out-form input").prop("disabled", true);');
        $this->render('update', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new SalaryOut;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (!empty($_POST['SalaryOut'])) {
            if (!empty($_POST['process_id'])) {
                $model->attributes = $_POST['SalaryOut'];
                if ($model->save()) {

                    $salarydet = new Salary;
                    $salarydet->salary_out_id = $model->id;
//                    $salarydet->other = $_POST['Salary']['other'];
                    $salarydet->total = $_POST['Salary']['total'];
                    $salarydet->total_loss_charge = $_POST['Salary']['total_loss_charge'];
                    $salarydet->created = $model->created;
                    $salarydet->created_user_id = $model->created_user_id;
                    $salarydet->code = SiteConfig::model()->formatting('salary', false);
                    $salarydet->save();

                    foreach ($_POST['process_id'] as $process) {
                        $detail = new SalaryDet;
                        $detail->salary_id = $salarydet->id;
                        $detail->workorder_process_id = $process;
                        $detail->save();

                        $workorderProcess = WorkorderProcess::model()->findByPk($process);
                        $workorderProcess->is_payment = 1;
                        $workorderProcess->save();
                    }
                }
            }
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

        if (isset($_POST['SalaryOut'])) {
            $model->attributes = $_POST['SalaryOut'];
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
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
//        $session = new CHttpSession;
//        $session->open();
        $criteria = new CDbCriteria();

        $model = new SalaryOut('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['SalaryOut'])) {
            $model->attributes = $_GET['SalaryOut'];



            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');


            if (!empty($model->created))
                $criteria->addCondition('created = "' . $model->created . '"');


            if (!empty($model->created_user_id))
                $criteria->addCondition('created_user_id = "' . $model->created_user_id . '"');


            if (!empty($model->modified))
                $criteria->addCondition('modified = "' . $model->modified . '"');


            if (!empty($model->description))
                $criteria->addCondition('description = "' . $model->description . '"');
        }
//        $session['SalaryOut_records'] = SalaryOut::model()->findAll($criteria);


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
        $model = SalaryOut::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'salary-out-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
