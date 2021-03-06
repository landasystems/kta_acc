<?php

class AccCashInController extends Controller {

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
            array('allow', // r
                'actions' => array('create','index', 'view','update','delete'),
                'expression' => 'app()->controller->isValidAccess("AccCashIn","r")'
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        cs()->registerCss('', '@page { size:22cm 14cm;margin: 0.4cm;}');

        $cashInDetail = AccCashInDet::model()->findAll(array(
            'condition' => 'acc_cash_in_id= ' . $id,
            'order' => 'id ASC'
        ));
        $approveDetail = AccApproval::model()->findAll(array(
            'condition' => 'acc_cash_in_id= ' . $id,
        ));
        $this->render('view', array(
            'model' => $this->loadModel($id),
            'cashInDet' => $cashInDetail,
            'approveDetail' => $approveDetail,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $this->cssJs();
        $model = new AccCashIn;
        $model->code = SiteConfig::model()->formatting('cashin', TRUE);
        $model->total = 0;
        $model->date_trans = date("Y-m-d");


        if (isset($_POST['AccCashIn'])) {
            $year = date('Y', strtotime($_POST['AccCashIn']['date_trans']));
            if ($_POST['totalDebit'] == $_POST['AccCashIn']['total']) {
                $model->attributes = $_POST['AccCashIn'];
                $model->code = SiteConfig::model()->formatting('cashin', False, False, False, False, $year);
                $model->date_trans = $_POST['AccCashIn']['date_trans'];
                $model->acc_coa_id = $_POST['AccCashIn']['accCoa'];

                if (isset($data)) {
                    $lastApprove = AccApproval::model()->find(array('condition' => 'acc_cash_in_id=' . $data->id, 'order' => 'id DESC'));
                    if (!empty($lastApprove)) {
                        $model->acc_approval_id = $lastApprove->id;
                        $model->code_acc = $data->no_acc;
                    }
                }
                if (isset($_POST['AccCashInDet'])) {
//                if (isset($_POST['AccCashInDet']) and !empty($_POST['AccCashIn']['accCoa'])) {
                    if ($model->save()) {
                        $credit = array();
                        for ($i = 0; $i < count($_POST['AccCashInDet']['acc_coa_id']); $i++) {

                            if (isset($_POST['nameAccount'][$i])) {
                                $ar = 0;
                                $as = 0;
                                $ap = 0;
                                $ledger = AccCoa::model()->findByPk($_POST['AccCashInDet']['acc_coa_id'][$i]);
                                if ($ledger->type_sub_ledger == "ap")
                                    $ap = $_POST['nameAccount'][$i];

                                if ($ledger->type_sub_ledger == "as")
                                    $as = $_POST['nameAccount'][$i];

                                if ($ledger->type_sub_ledger == "ar")
                                    $ar = $_POST['nameAccount'][$i];
                            }

                            $cashInDet = new AccCashInDet;
                            $cashInDet->acc_cash_in_id = $model->id;
                            $cashInDet->acc_coa_id = $_POST['AccCashInDet']['acc_coa_id'][$i];
                            $cashInDet->amount = $_POST['AccCashInDet']['amount'][$i];
                            $cashInDet->description = $_POST['AccCashInDet']['description'][$i];
                            $cashInDet->invoice_det_id = $_POST['inVoiceDet'][$i];
                            $cashInDet->ap_id = $ap;
                            $cashInDet->as_id = $as;
                            $cashInDet->ar_id = $ar;
                            $cashInDet->save();

//                            if (isset($_POST['inVoiceDet'][$i]) && $_POST['inVoiceDet'][$i] != 0) {
//                                $invoiceDet = InvoiceDet::model()->findByPk($_POST['inVoiceDet'][$i]);
//                                if ($invoiceDet->type == 'supplier') {
//                                    $invoiceDet->payment = $invoiceDet->payment + $_POST['AccCashInDet']['amount'][$i];
//                                } else {
//                                    $invoiceDet->payment = $invoiceDet->payment - $_POST['AccCashInDet']['amount'][$i];
//                                }
//                                $invoiceDet->charge = $invoiceDet->charge + $_POST['AccCashInDet']['amount'][$i];
//                                $invoiceDet->save();
//                            }
//                            $valSub[] = (object) array("id" => $model->id, "acc_coa_id" => $cashInDet->acc_coa_id, "date_trans" => $model->date_trans, "description" => $cashInDet->description, "credit" => $cashInDet->amount, "code" => $model->code, "reff_type" => "cash_in", "ar" => $ar, "as" => $as, "ap" => $ap);

                            if ($cashInDet->amount < 0)
                                $debet[] = (object) array("id" => $model->id, "acc_coa_id" => $cashInDet->acc_coa_id, "date_trans" => $model->date_trans, "description" => $cashInDet->description, "total" => $cashInDet->amount, "code" => $model->code, "reff_type" => "cash_in");
                            else
                                $credit[] = (object) array("id" => $model->id, "acc_coa_id" => $cashInDet->acc_coa_id, "date_trans" => $model->date_trans, "description" => $cashInDet->description, "total" => $cashInDet->amount, "code" => $model->code, "reff_type" => "cash_in");
                        }
                        //simpan kedalam saldo coa
                        $debet[] = (object) array("id" => $model->id, "acc_coa_id" => $model->acc_coa_id, "date_trans" => $model->date_trans, "description" => $model->description, "total" => $model->total, "code" => $model->code, "reff_type" => "cash_in");

                        $siteConfig = SiteConfig::model()->findByPk(1);
//                        if ($siteConfig->is_approval == "no") {
////                            AccCoa::model()->transLedger(array(), $valSub);
//                            AccCoa::model()->trans($debet, $credit);
//                        } else {
                            $status = new AccApproval;
                            $status->status = "open";
                            $status->acc_cash_in_id = $model->id;
                            $status->save();
//                        }
                        $berhasil = true;
                        $this->redirect(array('view', 'id' => $model->id, 'berhasil' => $berhasil));
                    }
                } else {
                    Yii::app()->user->setFlash('error', '<strong>Error! </strong>No account added.');
                }
            } else {
                Yii::app()->user->setFlash('error', '<strong>Error! </strong>Total Debit dan Total Kredit harus sama.');
            }
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
        $this->cssJs();
        $accCoaSub = array();
        $cashin = $this->loadModel($id);
        $cashInDetail = AccCashInDet::model()->findAll(array('condition' => 'acc_cash_in_id= ' . $cashin->id,'order' => 'id ASC'));

        // load model approve
        $act = (isset($_GET['act'])) ? $_GET['act'] : '';
        $approveDetail = AccApproval::model()->findAll(array('condition' => 'acc_cash_in_id= ' . $id));
        $admin = array();
        $manager = array();
        $getModel = array();
        $siteConfig = SiteConfig::model()->findByPk(1);
        $cekApprove = AccCoaDet::model()->find(array('condition' => 'reff_type="cash_in" and reff_id=' . $id));

        //cek apakah data sudah diapprove
        $isAfterApprove = (isset($cashin->AccManager->status)) ? $cashin->AccManager->status : '';
        if (isset($isAfterApprove) and $isAfterApprove == "confirm") {
            $afterApprove = 1;
        } else {
            $afterApprove = 0;
        }

        //pengecekan jika sudah di approve, tidak boleh di approve lagi
        if ($act == 'approve' && $afterApprove == 1)
            throw new CHttpException(500, 'Data sudah disetujui, system tidak dapat melanjutkan proses.');

        //---------------------proses update----------------------------------------------
        if (isset($_POST['AccCashIn'])) {
            if (isset($_POST['AccCashInDet'])) {
                if ($_POST['totalDebit'] == $_POST['AccCashIn']['total']) {
                    if (empty($_POST['AccCashIn']['accCoa']) && $act == 'approve') {
                        Yii::app()->user->setFlash('error', '<strong>Error! </strong>Kode Rekening belum terpilih');
                    } else {
                        //update cash in
                        $cashin->attributes = $_POST['AccCashIn'];
                        $cashin->acc_coa_id = $_POST['AccCashIn']['accCoa'];
                        if (isset($_POST['date_post']))
                            $cashin->date_posting = $_POST['date_post'];
                        if (!empty($_POST['codeAcc']))
                            $cashin->code_acc = $_POST['codeAcc'];

                        if ($act == 'approve' && empty($_POST['codeAcc'])) { //hanya waktu action approve generate code acc
//                            $format = json_decode($siteConfig->autonumber);
                            if ($cashin->AccCoa->type_sub_ledger == 'ks') {
                                DateConfig::model()->addYear($_POST['date_post'], 'cash_in');
                                $cashin->code_acc = SiteConfig::model()->formatting('cashinks_acc', False, '', '', $_POST['date_post']);
                            } elseif ($cashin->AccCoa->type_sub_ledger == 'bk') {
                                DateConfig::model()->addYear($_POST['date_post'], 'bk_in');
                                $cashin->code_acc = SiteConfig::model()->formatting('cashinbk_acc', False, '', '', $_POST['date_post']);
                            }
                            //tambah autonumber
//                            $siteConfig->autonumber = json_encode($format);
//                            $siteConfig->save();
                        }
                        $cashin->save();

                        //update cashout detail
                        AccCashInDet::model()->deleteAll(array('condition' => 'acc_cash_in_id = ' . $cashin->id));
                        $debet = array();
                        $sDesc = '';
                        $valSub = array();
//                        logs(count($_POST['AccCashInDet']['amount']));
                        for ($i = 0; $i < count($_POST['AccCashInDet']['acc_coa_id']); $i++) {
                            if (isset($_POST['nameAccount'][$i])) {
                                $ar = 0;
                                $as = 0;
                                $ap = 0;
                                $ledger = AccCoa::model()->findByPk($_POST['AccCashInDet']['acc_coa_id'][$i]);

                                if ($ledger->type_sub_ledger == "ap")
                                    $ap = $_POST['nameAccount'][$i];

                                if ($ledger->type_sub_ledger == "as")
                                    $as = $_POST['nameAccount'][$i];

                                if ($ledger->type_sub_ledger == "ar")
                                    $ar = $_POST['nameAccount'][$i];
                            }

                            $cashInDet = new AccCashInDet;
                            $cashInDet->acc_cash_in_id = $cashin->id;
                            $cashInDet->acc_coa_id = $_POST['AccCashInDet']['acc_coa_id'][$i];
                            $cashInDet->amount = $_POST['AccCashInDet']['amount'][$i];
                            $cashInDet->description = $_POST['AccCashInDet']['description'][$i];
                            $cashInDet->invoice_det_id = (isset($_POST['inVoiceDet'][$i]) && $_POST['inVoiceDet'][$i] != 0) ? $_POST['inVoiceDet'][$i] : 0;
                            $cashInDet->ap_id = $ap;
                            $cashInDet->as_id = $as;
                            $cashInDet->ar_id = $ar;
                            $cashInDet->save();

//                            if (isset($_POST['inVoiceDet'][$i]) && $_POST['inVoiceDet'][$i] != 0) {
//                                $invoiceDet = InvoiceDet::model()->findByPk($_POST['inVoiceDet'][$i]);
//                                if ($invoiceDet->type == 'supplier') {
//                                    $invoiceDet->payment = $invoiceDet->payment + $_POST['AccCashInDet']['amount'][$i];
//                                } else {
//                                    $invoiceDet->payment = $invoiceDet->payment - $_POST['AccCashInDet']['amount'][$i];
//                                }
//                                $invoiceDet->charge = $invoiceDet->charge + $_POST['AccCashInDet']['amount'][$i];
//                                $invoiceDet->save();
//                            }

                            $sDesc .= '<br/>' . $cashInDet->description;

                            //**********edit*****************
//                            if (isset($_POST['nameAccount'][$i])) {
//                                $valSub[] = (object) array("id" => $cashin->id, "acc_coa_id" => $cashInDet->acc_coa_id, "date_trans" => (isset($_POST['date_post'])) ? $_POST['date_post'] : '', "description" => $cashInDet->description, "credit" => $cashInDet->amount, "code" => $cashin->code_acc, "reff_type" => "cash_in", "ar" => $ar, "as" => $as, "ap" => $ap);
//                            }
                            if ($cashInDet->amount < 0)
                                $debet[] = (object) array("id" => $cashin->id, "acc_coa_id" => $cashInDet->acc_coa_id, "date_trans" => (isset($_POST['date_post'])) ? $_POST['date_post'] : '', "description" => $cashInDet->description, "total" => $cashInDet->amount * -1, "code" => $cashin->code_acc, "reff_type" => "cash_in", "invoice_det_id" => (isset($_POST['inVoiceDet'][$i])) ? $_POST['inVoiceDet'][$i] : null);
                            else
                                $credit[] = (object) array("id" => $cashin->id, "acc_coa_id" => $cashInDet->acc_coa_id, "date_trans" => (isset($_POST['date_post'])) ? $_POST['date_post'] : '', "description" => $cashInDet->description, "total" => $cashInDet->amount, "code" => $cashin->code_acc, "reff_type" => "cash_in", "invoice_det_id" => (isset($_POST['inVoiceDet'][$i])) ? $_POST['inVoiceDet'][$i] : null);
                        }

                        //jika description kosong, br di hilangkan
                        if (empty($cashin->description))
                            $sDesc = substr($sDesc, 5);
                        else
                            $sDesc = $cashin->description . $sDesc;

                        $debet[] = (object) array("id" => $cashin->id, "acc_coa_id" => $cashin->acc_coa_id, "date_trans" => (isset($_POST['date_post'])) ? $_POST['date_post'] : '', "description" => $sDesc, "total" => $cashin->total, "code" => $cashin->code_acc, "reff_type" => "cash_in", "invoice_det_id" => NULL);

                        // --------------------------Function Approve----------------------------

                        if ($act == 'approve' || ($act != 'approve' && $afterApprove == 1)) { //approve dijalankan ketika act approve / edit dan sudah di approve
                            AccCoaDet::model()->deleteAll(array('condition' => 'reff_type = "cash_in" and reff_id=' . $cashin->id));
//                            AccCoaSub::model()->deleteAll(array('condition' => 'reff_type="cash_in" and reff_id = ' . $cashin->id));

                            AccCoa::model()->trans($debet, $credit);
//                            AccCoa::model()->transLedger(array(), $valSub);

                            $manager = (object) array("status" => 'confirm', "description" => 'approve');
                            if ($afterApprove == 1) {
                                $manager = (object) array("status" => 'confirm', "description" => 'edit');
                            }
                            $getModel = (object) array('table' => 'AccCashIn', 'field' => 'acc_cash_in_id', 'id' => $id);
                            AccApproval::model()->saveApproval($admin, $manager, $getModel);
                        }
                        //----------------------------------END APPROVE---------------------------------------------------

                        $berhasil = true;
                        $this->redirect(array('view', 'id' => $cashin->id, 'berhasil' => $berhasil));
                    }
                } else {
                    Yii::app()->user->setFlash('error', '<strong>Error! </strong>Total Kredit dan Total Debit harus sama.');
                }
            } else {
                Yii::app()->user->setFlash('error', '<strong>Error! </strong>Detail dana masih kosong');
            }
        }

        $this->render('update', array(
            'model' => $cashin,
            'cashInDetail' => $cashInDetail,
            'approveDetail' => $approveDetail,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $model = $this->loadModel($id);

        if (Yii::app()->request->isPostRequest) {
            if (!empty($model->code_acc)) {
                $this->actionDeleteApproved();
            } else {
                $this->loadModel($id)->delete();
                AccCashInDet::model()->deleteAll(array('condition' => 'acc_cash_in_id = ' . $id));
            }


//            }
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            //  if (!isset($_GET['ajax']))
            //            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else {
            throw new CHttpException(
            400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {

//        $session = new CHttpSession;
//        $session->open();
        $criteria = new CDbCriteria ();

        if (isset(user()->roles['accesskb'])) {
            $idData = user()->roles['accesskb']->crud;
            $sWhere = json_decode($idData);
        } else {
            $sWhere = array(0);
        }
        $criteria->addInCondition('acc_coa_id', $sWhere);

        $model = new AccCashIn('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['AccCashIn'])) {
            $model->attributes = $_GET ['AccCashIn'];
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
        $model = AccCashIn::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJA
      X validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) &&
                $_POST['ajax'] === 'acc-cash-in-form') {
            echo CActiveForm:: validate($model);

            Yii::app()->end();
        }
    }

    public function cssJs() {
        cs()->registerScript('', '
            function calculate(){
            var credit = $("#credit").val();
            if(!credit.length){
                credit = 0;
            }
            var totalAkhir = parseFloat($("#AccCashIn_total").val()) + parseFloat(credit);
            $("#AccCashIn_total").val(totalAkhir);
            var selisih = parseFloat($("#totalDebit").val()) - parseFloat(totalAkhir);
            $("#difference").val(selisih);
            }
            
            function calculateMin(){
            var subTotal=0;
            $(".totalDet").each(function() {
               subTotal += parseFloat($(this).val());
            });
               $("#AccCashIn_total").val(subTotal);
            var selisih = parseFloat($("#totalDebit").val()) - parseFloat(subTotal);
            $("#difference").val(selisih);
            }
            
            $("#totalDebit").on("keyup", function() {
             var subTotal=0;
             $(".totalDet").each(function() {
               subTotal += parseFloat($(this).val());
            });
            var selisih = parseFloat($("#totalDebit").val()) - parseFloat(subTotal);
            $("#difference").val(selisih);
            });
            
            $(".totalDet").on("keyup", function() {
            var subTotal=0;
            $(".totalDet").each(function() {
               subTotal += parseFloat($(this).val());
            });
            var selisih = parseFloat($("#totalDebit").val()) - parseFloat(subTotal);
            $("#difference").val(selisih);
            $("#AccCashIn_total").val(subTotal);
            });
            
            function rp(angka){
                            var rupiah = "";
                            var angkarev = angka.toString().split("").reverse().join("");
                            for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+".";
                            return rupiah.split("",rupiah.length-1).reverse().join("");
                        };

            function clear(){
                $("#credit").val("0");
                $("#costDescription").val("");
                $("#s2id_account").select2("data", "0");
                $("#s2id_accountName").select2("data", "0");
            }
            
            $(".delRow").on("click", function() {
                 $(this).parent().parent().parent().remove();
                 calculateMin();
            });
            
            $("#costDescription,#credit").keypress(function (e) {
                if (e.which == 13) {
                  $("#btnAdd").trigger("click");
                  e.preventDefault();
                }
         
                           });
    ');
    }

    public function actionAddRow() {
        Yii::app()->clientScript->reset();
        Yii::app()->clientScript->corePackages = array();
        $acca = AccCoa::model()->findByPk($_POST['account']);
        $data = array(0 => t('choose', 'global')) + CHtml::listData(AccCoa::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
        $subId = (isset($_POST['subledgerid'])) ? $_POST['subledgerid'] : 0;
        $mInvoce = InvoiceDet::model()->findByPk($subId);
        if(!empty($mInvoce)){
            if($mInvoce->type== "supplier"){
                $name = (!empty($mInvoce->Supplier->name)) ? $mInvoce->Supplier->name : '';
            }elseif($mInvoce->type == "customer"){
                $name = (!empty($mInvoce->Customer->name) ? $mInvoce->Customer->name : '');
            }
        }
        $invoiceName = (!empty($mInvoce->code) && !empty($name)) ? '<a class="btn btn-mini removeSub"><i class=" icon-remove-circle"></i></a>[' . $mInvoce->code . ']' . $name : '';
        if ($acca->type != "general") {
            if (isset($_POST['accountName']) and ! empty($_POST['accountName'])) {

                if ($acca->type_sub_ledger == "ar")
                    $account = Customer::model()->findByPk($_POST['accountName']);

                else if ($acca->type_sub_ledger == "ap")
                    $account = Supplier::model()->findByPk($_POST['accountName']);

                else if ($acca->type_sub_ledger == "as")
                    $account = Product::model()->findByPk($_POST['accountName']);

                $name = $account->name;
                $id = $account->id;
            } else {
                $name = "-";
                $id = "";
            }

            echo '<tr class="newRow">
                        <td style="text-align:center">
                            <input type="hidden" name="nameAccount[]" id="nameAccount[]" value="' . $id . '"/>
                            <input type="hidden" name="inVoiceDet[]" id="inVoiceDet[]" class="inVoiceDet" value="' . $subId . '"/>
                            <span class="btn"><i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i></span> 
                        </td>';
            echo '<td><select class="selectDua subLedger" style="width:100%" name="AccCashInDet[acc_coa_id][]" id="AccCashInDet[acc_coa_id][]">';
            foreach ($data as $key => $val) {
                $value = ($key == $_POST['account']) ? 'selected="selected"' : '';
                echo '<option ' . $value . ' value="' . $key . '">' . $val . '</option>';
            }
            echo '<option value="0">Pilih</option>';
            echo '</select></td>
                        <td style="text-align:center" class="subLedgerField"> ' . $invoiceName . '<a style="display:none" class="btn showModal">Select Sub-Ledger</a></td>
                        <td><input type="text" name="AccCashInDet[description][]" id="AccCashInDet[description][]"  value="' . $_POST['costDescription'] . '" style="width:95%;"/></td>
                        <td><div class="input-prepend"> <span class="add-on">Rp.</span><input type="text" name="AccCashInDet[amount][]" id="AccCashInDet[amount][]" class="angka totalDet" value="' . $_POST['credit'] . '"/></div></td>
                    </tr>
                    <tr id="addRow" style="display:none">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>';
        }
    }

    public function actionDeleteApproved() {
        $idCash = $_GET['id'];
        AccCashIn::model()->deleteByPk($idCash);
        AccCashInDet::model()->deleteAll(array('condition' => 'acc_cash_in_id = ' . $idCash));
        AccCoaDet::model()->deleteAll(array('condition' => 'reff_type="cash_in" AND reff_id=' . $idCash));
//        AccCoaSub::model()->deleteAll(array('condition' => 'reff_type="cash_in" AND reff_id=' . $idCash));
//        echo 'data berhasil dihapus';
    }

    public function actionFixNumber() {
        $cashIn = AccCashIn::model()->findAll(array('condition' => 'date_posting>="2015-01-01" AND code_acc LIKE "BKM%"', 'order' => 'code_acc'));
        $det = AccCoaDet::model()->findAll(array('condition' => 'date_coa>="2015-01-01" AND code LIKE "BKM%"', 'order' => 'code'));
        $angka = 0;
        foreach ($cashIn as $arr) {
            $angka++;
            $arr->code_acc = "BKM" . substr("000000" . $angka, -5);
            $arr->save();

            $mCoaDet = AccCoaDet::model()->findAll(array('condition' => 'reff_type="cash_in" and reff_id=' . $arr->id));
            foreach ($mCoaDet as $arrCoaDet) {
                $arrCoaDet->code = $arr->code_acc;
                $arrCoaDet->save();
            }
        }
    }

    public function actionGenerateExcel() {
        $model = new AccCashIn;
        $model->attributes = $_GET['AccCashIn'];
        $data = $model->search(true);
        $a = explode('-', $model->date_posting);
        return Yii::app()->request->sendFile('excelReport.xls', $this->renderPartial('excelReport', array(
                            'model' => $data,
                            'start' => $a[0],
                            'end' => $a[1],
                                ), true)
        );
    }

}
