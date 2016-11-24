<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'supplier-form',
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

        <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a href="#personal">Personal</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="personal">

                <table>
                    <tr>
                        <td style="vertical-align: top;">
                            <?php echo $form->textFieldRow($model, 'code', array('class' => 'span3', 'maxlength' => 25)); ?>

                            <?php echo $form->textFieldRow($model, 'name', array('class' => 'span3', 'maxlength' => 255)); ?> 
                            <?php echo $form->textFieldRow($model, 'email', array('class' => 'span3', 'maxlength' => 255)); ?> 


                        </td>
                        <td>
                            <?php
                            echo $form->textFieldRow(
                                    $model, 'phone', array('prepend' => '+62', 'class' => 'span2')
                            );
                            ?>

                            <div class="control-group ">
                                <?php
                                echo CHtml::activeLabel($model, 'city_id', array('class' => 'control-label'));
                                ?>
                                <div class="controls">
                                    <?php
                                    $city = City::model()->findByPk($model->city_id);
                                    if (isset($city)) {
                                        $city_id = $city->id;
                                        $city_name = $city->name;
                                    } else {
                                        $city_id = 0;
                                        $city_name = '';
                                    }
                                    $this->widget(
                                            'bootstrap.widgets.TbSelect2', array(
                                        'name' => "Supplier[city_id]",
                                        'val' => $model->city_id,
                                        'asDropDownList' => false,
                                        'options' => array(
                                            'allowClear' => true,
                                            'minimumInputLength' => 3,
                                            'width' => '350px;margin:0px;text-align:left',
                                            'minimumInputLength' => '3',
                                            'initSelection' => 'js:function(element, callback) 
                                                                { 
                                                                    data = {"id": ' . $city_id . ',"text": "' . $city_name . '"}
                                                                    callback(data);   
                                                                }',
                                            'ajax' => array(
                                                'url' => Yii::app()->createUrl('city/listajax'),
                                                'dataType' => 'json',
                                                'data' => 'js:function(term, page) { 
                                                       return {
                                                           q: term 
                                                       }; 
                                                   }',
                                                'results' => 'js:function(data) { 
                                                       return {
                                                           results: data
                                                       };
                                                   }',
                                            ),
                                        ),
                                            )
                                    );
                                    ?>
                                </div>
                            </div>
                            <?php echo $form->textAreaRow($model, 'address', array('class' => 'span4', 'maxlength' => 255)); ?>

                        </td>
                    </tr>
                </table>

            </div> 
        </div>
        <?php if (!isset($_GET['v'])) { ?>
            <div class="form-actions">
                <?php
                $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'submit',
                    'type' => 'primary',
                    'icon' => 'ok white',
                    'label' => $model->isNewRecord ? 'Tambah' : 'Simpan',
                ));
                ?>
            </div>
        <?php } ?>
    </fieldset>

    <?php $this->endWidget(); ?>

</div>
