<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'salary-out-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    if ($model->isNewRecord == TRUE) {
        $user = '';
        $desc = '';
        $date = date('d M Y');
    } else {
        $user = $model->created_user_id;
        $desc = $model->description;
        $date = date('d M Y', strtotime($model->created));
    }
    ?>
    <fieldset>
        <legend>
            <p class="note">Fields dengan <span class="required">*</span> harus di isi.</p>
        </legend>

        <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12')); ?>

        <div class="box gradient invoice">
            <div class="title clearfix">

                <h4 class="left">
                    <span class="icon16 icomoon-icon-newspaper"></span>
                    <span>Salary</span>
                </h4>
                <?php
                if ($model->isNewRecord == FALSE) {
                    ?>
                    <div class="print">
                    <a onclick="js:printDiv();
        return false;" class="btn "><span class="icon24 entypo-icon-printer"></span></a>
                   
                </div>
               <?php }
                ?>
               
                <div class="invoice-info">
                    <span class="number">
                        <strong class="red">                        
                            <?php echo (isset($model->code)) ? $model->Salary->code : ''; ?>
                        </strong>
                    </span><br>
                    <span class="data gray"><?php echo $date; ?></span>

                    <div class="clearfix"></div>
                </div>



            </div>
            <div class="content "> 
                <?php if ($model->isNewRecord == TRUE) { ?>
                <table style="width: 100%">
                    <tr>
                        <td style="width: 50%;vertical-align: top">
                            <div class="row-fluid">
                                <div class="span3">
                                    Employment
                                </div>
                                <div class="span1">:</div>
                                <div class="span8" style="text-align:left">
                                    <?php
//                                    $listUser = User::model()->listUsers('user');
//                                    $this->widget(
//                                            'bootstrap.widgets.TbSelect2', array(
//                                        'asDropDownList' => true,
//                                        'name' => 'Salary[user_id]',
//                                        'data' => array(0 => t('choose', 'global')) + CHtml::listData($listUser, 'id', 'name'),
//                                        'value' => $user,
//                                        'options' => array(
//                                            'width' => '100%',
//                                        ))
//                                    );
                                    ?>
                                    <?php
                                    $listUser = User::model()->listUsers('user');
                                    $this->widget('bootstrap.widgets.TbSelect2', array(
                                        'asDropDownList' => TRUE,
                                        'data' => CHtml::listData($listUser, 'id', 'name'),
                                        'name' => 'Salary[user_id]',
                                        'options' => array(
                                            'placeholder' => yii::t('global', 'choose'),
                                            'width' => '100%',
                                            'tokenSeparators' => array(',', ' ')
                                        ),
                                        'htmlOptions' => array(
                                            'multiple' => 'multiple',
                                        )
                                    ));
                                    ?>
                                </div>
                            </div> 
                            <div class="row-fluid">
                                <div class="span3">
                                    Max Date
                                </div>
                                <div class="span1">:</div>
                                <div class="span8" style="text-align:left">
                                    <div class="input-prepend" style="margin-bottom: 10px">
                                        <span class="add-on">
                                            <i class="icon-calendar"></i></span>
                                        <?php
                                        $this->widget(
                                                'bootstrap.widgets.TbDatePicker', array(
                                            'name' => 'max_date',
                                            'htmlOptions' => array('class' => 'max_date'),
                                            'value' => date('m/d/Y'),
                                                )
                                        );
                                        ?>
                                    </div>
                                </div>
                            </div>                         
                            <div class="row-fluid">
                                <div class="span3">
                                    Description
                                </div>
                                <div class="span1">:</div>
                                <div class="span8" style="text-align:left">
                                    <?php
                                    echo Chtml::textArea('SalaryOut[description]', $desc, array('style' => 'width:95%;height:50px'));
                                    ?>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span3">
                                </div>           
                                <div class="span1"></div>
                                <div class="span8" style="text-align:left">
                                    <br>
                                    <?php
                                    
                                    echo CHtml::ajaxLink(
                                            $text = '<button class="btn btn-primary" type="button"><i class="icon-search icon-white"></i> Filter </button>', $url = url('salaryOut/detail'), $ajaxOptions = array(
                                        'type' => 'POST',
                                        'data' => array('user_id' => 'js:$("#Salary_user_id").val()',
                                            'max_date' => 'js:$("#max_date").val()'
                                        ),
                                        'success' => 'function(data){ 
                                        $(".content_detail").html(data);   

                                    }'));
                                    
                                    ?>                               

                                </div>
                            </div>
                        </td>                            
                        <td style="width: 50%;vertical-align: top;text-align: center">

                        </td>                            
                    </tr>
                </table> 
                <?php } ?>
                <div class="content_detail">
                    <?php
                    if ($model->isNewRecord == TRUE) {
                        echo $this->renderPartial('_salaryDetail', array());
                    } else {
                        $salary = Salary::model()->findByAttributes(array('salary_out_id' => $model->id));

                        echo $this->renderPartial('_salaryDetail', array('salary' => $salary,'model'=>$model));
                    }
                    ?>
                </div>
            </div>

        </div>


        <div class="form-actions">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'icon' => 'ok white',
                'label' => $model->isNewRecord ? 'Tambah' : 'Simpan',
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
