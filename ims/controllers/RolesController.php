<?php

class RolesController extends Controller {

    public $breadcrumbs;
    public $layout = '//layouts/main';

//    public function filters() {
//        return array(
//            'accessControl', // perform access control for CRUD operations
//        );
//    }

    public function accessRules() {
//        return array(
//            array('allow', // c
//                'actions' => array('create'),
//                'expression' => 'app()->controller->isValidAccess("GroupUser","c")',
//                'expression' => 'app()->controller->isValidAccess("GroupCLient","c")',
//                'expression' => 'app()->controller->isValidAccess("GroupGuest","c")',
//                'expression' => 'app()->controller->isValidAccess("GroupSupplier","c")',
//                'expression' => 'app()->controller->isValidAccess("GroupEmplyoment","c")',
//                'expression' => 'app()->controller->isValidAccess("GroupContact","c")',
//                'expression' => 'app()->controller->isValidAccess("GroupCustomer","c")'
//            ),
//            array('allow', // r
//                'actions' => array('index', 'view'),
//                'expression' => 'app()->controller->isValidAccess("GroupUser","r")',
//                'expression' => 'app()->controller->isValidAccess("GroupCLient","r")',
//                'expression' => 'app()->controller->isValidAccess("GroupGuest","r")',
//                'expression' => 'app()->controller->isValidAccess("GroupSupplier","r")',
//                'expression' => 'app()->controller->isValidAccess("GroupEmplyoment","r")',
//                'expression' => 'app()->controller->isValidAccess("GroupContact","r")',
//                'expression' => 'app()->controller->isValidAccess("GroupCustomer","r")'
//            ),
//            array('allow', // u
//                'actions' => array('update'),
//                'expression' => 'app()->controller->isValidAccess("GroupUser","u")',
//                'expression' => 'app()->controller->isValidAccess("GroupCLient","u")',
//                'expression' => 'app()->controller->isValidAccess("GroupGuest","u")',
//                'expression' => 'app()->controller->isValidAccess("GroupSupplier","u")',
//                'expression' => 'app()->controller->isValidAccess("GroupEmplyoment","u")',
//                'expression' => 'app()->controller->isValidAccess("GroupContact","u")',
//                'expression' => 'app()->controller->isValidAccess("GroupCustomer","u")'
//            ),
//            array('allow', // d
//                'actions' => array('delete'),
//                'expression' => 'app()->controller->isValidAccess("GroupUser","d")',
//                'expression' => 'app()->controller->isValidAccess("GroupCLient","d")',
//                'expression' => 'app()->controller->isValidAccess("GroupGuest","d")',
//                'expression' => 'app()->controller->isValidAccess("GroupSupplier","d")',
//                'expression' => 'app()->controller->isValidAccess("GroupEmplyoment","d")',
//                'expression' => 'app()->controller->isValidAccess("GroupContact","d")',
//                'expression' => 'app()->controller->isValidAccess("GroupCustomer","d")'
//            )
//        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {

        cs()->registerScript('', '$("#roles-form input").prop("disabled", true);');
        $type = $_GET['type'];
        cs()->registerScript('', '$("#roles-form input").prop("disabled", true);');
        $this->render('view', array(
            'model' => $this->loadModel($id),
            'type' => $type,
        ));
    }

    public function actionCreate() {
        $model = new Roles;
        if (isset($_POST['Roles'])) {
            $model->attributes = $_POST['Roles'];
            if ($model->save()) {
                $this->saveRolesAuth($model->id);
                $this->redirect(array('view', 'id' => $model->id, 'type' => $type));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if (isset($_POST['Roles'])) {
            $model->attributes = $_POST['Roles'];
            if ($model->save()) {
                //delete roles auth
                RolesAuth::model()->deleteAll(array('condition' => 'roles_id=' . $model->id));
                $this->saveRolesAuth($model->id);

                $this->redirect(array('view', 'id' => $model->id, 'type' => $type));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function saveRolesAuth($roles_id) {
        foreach ($_POST['auth_id'] as $arrAuth) {
            $crud = array();
            if (isset($_POST[$arrAuth]['c']))
                $crud['c'] = 1;
            if (isset($_POST[$arrAuth]['r']))
                $crud['r'] = 1;
            if (isset($_POST[$arrAuth]['u']))
                $crud['u'] = 1;
            if (isset($_POST[$arrAuth]['d']))
                $crud['d'] = 1;

            if (count($crud) > 0) {
                $mRolesAuth = new RolesAuth();
                $mRolesAuth->roles_id = $roles_id;
                $mRolesAuth->auth_id = $arrAuth;
                $mRolesAuth->crud = json_encode($crud);
                $mRolesAuth->save();
            }
        }

        //------------menyimpan hak akses kas/bank------------------------------
        if (isset($_POST['accesskb'])) {
            $mRolesAuth = new RolesAuth();
            $mRolesAuth->roles_id = $roles_id;
            $mRolesAuth->auth_id = 'accesskb';
            $mRolesAuth->crud = json_encode($_POST['accesskb']);
            $mRolesAuth->save();
        }
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            //delete roles auth
            RolesAuth::model()->deleteAll(array('condition' => 'roles_id=' . $id));
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionIndex() {
        
        $criteria = new CDbCriteria();
        $model = new Roles('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['Roles'])) {
            $model->attributes = $_GET['Roles'];

            if (!empty($model->name))
                $criteria->addCondition('name = "' . $model->name . '"');
        }
      
        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function loadModel($id) {
        $model = Roles::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'roles-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
