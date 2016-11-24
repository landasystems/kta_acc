<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'User-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <fieldset>
        <legend>
            <p class="note">Fields dengan <span class="required">*</span> harus di isi.</p>
        </legend>

        <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12')); ?>
        <div class="clearfix"></div>
        <div class="box">
            <div class="title">
                <h4>
                    <?php
                    echo 'Group Akses <span class="required">*</span> :    ';
                    if ($model->id == User()->id) { //if same id, cannot change role it self
                        $listRoles = Roles::model()->findAll(array('index' => 'id', 'order' => 'name'));
                        if (User()->roles_id == -1) {
                            echo 'Super User';
                        } elseif (isset($listRoles[User()->roles_id])) {
                            echo $listRoles[User()->roles_id]['name'];
                        }
                    } else {

                        $array = Roles::model()->findAll(array('index' => 'id', 'order' => 'name'));
                        if (!empty($array)) {
                            echo CHtml::dropDownList('User[roles_id]', $model->roles_id, CHtml::listData($array, 'id', 'name'), array(
                                'empty' => 'Pilih',
                            ));
                        } else {
                            echo'Data is empty please insert data group';
                        }
                    }
                    ?>  
                </h4>
            </div>
        </div>


        <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a href="#personal">Personal</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="personal">

                <table>
                    <tr>
                        <td style="vertical-align: top;">                                
                            <h3>Login Information</h3>
                            <hr/>
                            <?php echo $form->textFieldRow($model, 'username', array('class' => 'span3', 'maxlength' => 20)); ?>

                            <?php echo $form->textFieldRow($model, 'email', array('class' => 'span3', 'maxlength' => 100)); ?>

                            <?php echo $form->passwordFieldRow($model, 'password', array('class' => 'span3', 'maxlength' => 255, 'hint' => 'Fill the password, to change',)); ?>

                            
                        </td>
                        <td style="vertical-align: top">
                           <h3>Profile Information</h3>
                            <hr/>
                            
                            <?php echo $form->textFieldRow($model, 'name', array('class' => 'span3', 'maxlength' => 255)); ?> 

                            <?php
                            $mDepartement = Departement::model()->findAll(array());
                            echo $form->dropDownListRow($model, 'departement_id', CHtml::listData($mDepartement, 'id', 'name'), array(
                                'empty' => 'Pilih',
                            ));
                            ?>
                            <?php echo $form->toggleButtonRow($model, 'enabled'); ?>
                            <?php
                            echo $form->textFieldRow(
                                    $model, 'phone', array('prepend' => '+62')
                            );
                            ?>
                        </td>
                    </tr>
                </table>

            </div> 
        </div>
</div>


<div class="form-actions">
    <?php
    if (!isset($_GET['v'])) {
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'icon' => 'ok white',
            'label' => $model->isNewRecord ? 'Tambah' : 'Simpan',
        ));
    }
    ?>
</div>
</fieldset>

<?php $this->endWidget(); ?>

</div>
