<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'Departement-form',
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
            <p class="note">Fields with <span class="required">*</span> are required.</p>
        </legend>

        <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12')); ?>
        <div id="yw0">
            <ul id="yw1" class="nav nav-tabs">
                <li class="active">
                    <a data-toggle="tab" href="#departement">Departement</a>
                </li>
                <li class="">
                    <a data-toggle="tab" href="#autonumber">Auto Number</a>
                </li>
                <li class="">
                    <a data-toggle="tab" href="#formatting">Account Formatting</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="departement" class="tab-pane fade active in">
                    <div class="control-group">		
                        <div class="span4">
                            <?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 100)); ?>

                            <?php echo $form->textFieldRow($model, 'address', array('class' => 'span5', 'maxlength' => 100)); ?>

                            <div class="control-group ">
                                <?php
                                echo CHtml::activeLabel($model, 'province_id', array('class' => 'control-label'));
                                ?>
                                <div class="controls">
                                    <?php
                                    echo CHtml::dropDownList('province_id', $model->City->province_id, CHtml::listData(Province::model()->findAll(), 'id', 'name'), array(
                                        'empty' => 'Silahkan Pilih',
                                        'class' => 'span5',
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('landa/city/dynacities'),
                                            'update' => '#Departement_city_id',
                                        ),
                                            )
                                    );
                                    ?>  
                                </div>
                            </div>

                            <?php echo $form->dropDownListRow($model, 'city_id', CHtml::listData(City::model()->findAll('province_id=:province_id', array(':province_id' => (int) $model->City->province_id)), 'id', 'name'), array('class' => 'span5')); ?>

                            <?php echo $form->textFieldRow($model, 'phone', array('class' => 'span5', 'maxlength' => 100)); ?>

                            <?php echo $form->textFieldRow($model, 'email', array('class' => 'span5', 'maxlength' => 250)); ?>

                            <?php echo $form->textFieldRow($model, 'fax', array('class' => 'span5', 'maxlength' => 100)); ?>

                        </div>   
                    </div>
                </div>
                <div id="autonumber" class="tab-pane fade">Profile Content</div>
                <div id="formatting" class="tab-pane fade">Messages Content</div>
            </div>
        </div>


        <div class="form-actions">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'icon' => 'ok white',
                'label' => $model->isNewRecord ? 'Create' : 'Save',
            ));
            ?>
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'reset',
                'icon' => 'remove',
                'label' => 'Reset',
            ));
            ?>
        </div>
    </fieldset>

    <?php $this->endWidget(); ?>

</div>
