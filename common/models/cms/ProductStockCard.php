<?php

/**
 * This is the model class for table "{{product_stock_card}}".
 *
 * The followings are the available columns in table '{{product_stock_card}}':
 * @property integer $id
 * @property string $product_id
 * @property string $description
 * @property integer $created
 * @property integer $created_user_id
 * @property string $in
 * @property string $out
 * @property string $balance
 */
class ProductStockCard extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{product_stock_card}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('created, created_user_id, departement_id', 'numerical', 'integerOnly' => true),
            array('product_id', 'length', 'max' => 45),
            array('description', 'length', 'max' => 255),
            array('in, out, balance', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, product_id, description, created, created_user_id, in, out, balance', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'product_id' => 'Product',
            'description' => 'Description',
            'created' => 'Created',
            'created_user_id' => 'Created User',
            'in' => 'In',
            'out' => 'Out',
            'balance' => 'Balance',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('product_id', $this->product_id, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('created', $this->created);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('in', $this->in, true);
        $criteria->compare('out', $this->out, true);
        $criteria->compare('balance', $this->balance, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ProductStockCard the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function process($description, $product_id, $in, $out, $balance, $departement_id) {
        $m = new ProductStockCard();
        $m->product_id = $product_id;
        $m->description = $description;
        $m->in = json_encode($in);
        $m->out = json_encode($out);
        $m->balance = json_encode($balance);
        $m->departement_id = $departement_id;
        $m->save();
    }

    public function behaviors() {
        return array(
            'timestamps' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created',
            ),
        );
    }

    protected function beforeValidate() {
        if (empty($this->created_user_id))
            $this->created_user_id = Yii::app()->user->id;
        return parent::beforeValidate();
    }

}
