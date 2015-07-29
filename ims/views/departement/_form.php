
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
                <!--                <li class="">
                                    <a data-toggle="tab" href="#autonumber">Auto Number</a>
                                </li>-->
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
                <div id="formatting" class="tab-pane fade">
                    <div class="control-group">
                        <?php echo $form->textFieldRow($format, 'cash_in', array('size' => 11, 'maxlength' => 11, 'class' => 'span3 formatting')); ?>

                        <?php echo $form->textFieldRow($format, 'cash_in_approval', array('size' => 11, 'maxlength' => 11, 'class' => 'span3 formatting')); ?>

                        <?php echo $form->textFieldRow($format, 'bank_in_approval', array('size' => 11, 'maxlength' => 11, 'class' => 'span3 formatting')); ?>

                        <?php echo $form->textFieldRow($format, 'cash_out', array('size' => 11, 'maxlength' => 11, 'class' => 'span3 formatting')); ?>

                        <?php echo $form->textFieldRow($format, 'cash_out_approval', array('size' => 11, 'maxlength' => 11, 'class' => 'span3 formatting')); ?>

                        <?php echo $form->textFieldRow($format, 'bank_out_approval', array('size' => 11, 'maxlength' => 11, 'class' => 'span3 formatting')); ?>

                        <?php echo $form->textFieldRow($format, 'journal', array('size' => 11, 'maxlength' => 11, 'class' => 'span3 formatting')); ?>

                        <?php echo $form->textFieldRow($format, 'journal_approval', array('size' => 11, 'maxlength' => 11, 'class' => 'span3 formatting')); ?>
                    </div>
                    <div class="well">
                        <ul>
                            <li>Isikan formating code, agar sistem dapat melakukan generate kode untuk module - module yang sudah tersedia</li>
                            <li><b>{ai|<em>3</em>}</b> / <b>{ai|<em>4</em>}</b>  / <b>{ai|<em>5</em>}</b> / <b>{ai|<em>6</em>}</b> : berikan format berikut untuk generate Auto Increase Numbering, contoh {ai|5} untuk 5 digit angka, {ai|3} untuk 3 digit angka</li>
                            <li><b>{dd}</b>/<b>{mm}</b>/<b>{yy}</b> : berikan format berikut untuk melakukan generate tanggal, bulan, dan tahun </li>
                            <li>Contoh Formating : <b>PO/{dd}/{mm}{yy}/{ai|5}</b>, Hasil Generate : <b>PO/14/0713/00001</b></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!isset($_GET['v'])) { ?>
            <div class="form-actions">
                <?php
                $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'submit',
                    'type' => 'primary',
                    'icon' => 'ok white',
                    'label' => $model->isNewRecord ? 'Create' : 'Save',
//                    'htmlOptions' => array(
//                        'onSubmit' => 'clicked();return false()'
//                    )
                ));
                ?>
            </div>
        <?php } ?>
    </fieldset>

    <?php $this->endWidget(); ?>

</div>
<script>
//    function clicked() {
//
//    }
    $("body").on("click","#yw2", function () {
        var jml = 0;
        $(".formatting").each(function () {
            var leng = $(this).val();
            if (leng.length > 0) {
                jml++;
            }
        });
        if (jml == 8) {
            return true;
        } else {
            alert("Account Formatting belum terisi dengan benar~!");
            return false;
        }
    });
</script>