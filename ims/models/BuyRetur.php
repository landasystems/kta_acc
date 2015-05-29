<?php



/**
 * This is the model class for table "{{buy_retur}}".
 *
 * The followings are the available columns in table '{{buy_retur}}':
 * @property integer $id
 * @property string $code
 * @property integer $buy_id
 * @property integer $departement_id
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 * @property integer $supplier_id
 * @property string $description
 * @property integer $subtotal
 * @property integer $discount
 * @property integer $ppn
 * @property integer $other
 * @property integer $dp
 * @property integer $credit
 * @property integer $payment
 */
class BuyRetur extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{buy_retur}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('buy_id, departement_id, created_user_id, supplier_id, subtotal,other', 'numerical', 'integerOnly' => true),            
            array('total', 'numerical'),
            array('code, description', 'length', 'max' => 255),
            array('departement_id, supplier_id', 'required'),
            array('created, modified', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, code, buy_id, departement_id, created, created_user_id, modified, supplier_id, description, subtotal, discount, ppn, other, dp, credit, payment', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'User' => array(self::BELONGS_TO, 'User', 'created_user_id'),
            'Departement' => array(self::BELONGS_TO, 'Departement', 'departement_id'),
            'ProductSupplier' => array(self::BELONGS_TO, 'User', 'supplier_id'),
            'Buy' => array(self::BELONGS_TO, 'Buy', 'buy_id'), 
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'code' => 'Code',
            'buy_id' => 'Buy Code',
            'departement_id' => 'Departement',
            'created' => 'Created',
            'created_user_id' => 'Created User',
            'modified' => 'Modified',
            'supplier_id' => 'Supplier',
            'description' => 'Description',
            'subtotal' => 'Subtotal',
            'other' => 'Other',

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
        $criteria->with = array('Departement','ProductSupplier','Buy');
        $criteria->together = true;
        
        $criteria->compare('id', $this->id);
        $criteria->compare('t.code', $this->code, true);
        
        
        $criteria->compare('Buy.code',$this->buy_id,true); 
        
        $criteria->compare('Departement.name',$this->departement_id,true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('ProductSupplier.name',$this->supplier_id,true);   
        $criteria->compare('description', $this->description, true);
        $criteria->compare('subtotal', $this->subtotal);
        $criteria->compare('other', $this->other);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }


    public function behaviors() {
        return array(
            'timestamps' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created',
                'updateAttribute' => 'modified',
                'setUpdateOnCreate' => true,
            ),
        );
    }

    protected function beforeValidate() {
        if (empty($this->created_user_id))
            $this->created_user_id = Yii::app()->user->id;
        return parent::beforeValidate();
    }    
    
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return BuyRetur the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function getName(){
        $name = (isset($this->ProductSupplier->name)) ? $this->ProductSupplier->name : '-';
        return $name;
    }

}
