<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'roles-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <fieldset>

        <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12')); ?>

        <?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 255)); ?>

        <div class="well elek">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active"><a href="#module" data-toggle="tab">Module</a></li>
                <li><a href="#extended" data-toggle="tab">Hak Akses Akun</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="module">
                    <table class="table">
                        <thead> 
                            <tr>
                                <th></th>
                                <th>Access</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $arrMenu = Auth::model()->modules();
                            if ($model->isNewRecord == false) {
                                $mRolesAuth = RolesAuth::model()->findAll(array('condition' => 'roles_id=' . $model->id, 'select' => 'id,auth_id,crud', 'index' => 'auth_id'));
                            } else {
                                $mRolesAuth = array();
                            }
                            $this->renderPartial('_menuSub', array('arrMenu' => $arrMenu, 'mRolesAuth' => $mRolesAuth, 'model' => $model, 'space' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'));
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="extended">
                    
                    <table class="table">
                        <tr>
                            <td width="15%">Hak Akses Akun</td>
                            <td>
                                <?php
                                $sWhere = '';
                                if (isset($model->id)) {
                                    $roles = RolesAuth::model()->find(array('condition' => 'roles_id=' . $model->id . ' AND auth_id="accesskb"'));
                                    if (isset($roles->auth_id)) {
                                        $idData = $roles->crud;
                                        $sWhere = json_decode($idData);
                                    } else {
                                        $sWhere = '';
                                    }
                                }
                                $data = AccCoa::model()->findAll(array(
                                                'condition' => '(type_sub_ledger ="ks") OR (type_sub_ledger="bk")'
                                            ));
                                $this->widget('bootstrap.widgets.TbSelect2', array(
                                    'asDropDownList' => TRUE,
                                    'data' => CHtml::listData(AccCoa::model()->findAll(array(
                                                'condition' => 'type="detail" AND ((type_sub_ledger ="ks") OR (type_sub_ledger="bk"))'
                                            )), 'id', 'nestedname'),
                                    'name' => 'accesskb[]',
                                    'value' => ($model->isNewRecord == true)? $data : $sWhere,
                                    'options' => array(
                                        'placeholder' => 'Data belum diisi',
                                        'width' => '100%',
                                        'tokenSeparators' => array(',', ' ')
                                    ),
                                    'htmlOptions' => array(
                                        'multiple' => 'multiple',
                                    )
                                ));
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>


        <div class="form-actions">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'icon' => 'ok white',
                'visible' => !isset($_GET['v']),
                'label' => $model->isNewRecord ? 'Tambah' : 'Simpan',
            ));
            ?>
        </div>
    </fieldset>

    <?php $this->endWidget(); ?>

</div>
