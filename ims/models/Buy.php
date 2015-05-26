<?php



/**
 * This is the model class for table "{{buy}}".
 *
 * The followings are the available columns in table '{{buy}}':
 * @property integer $id
 * @property string $code
 * @property integer $departement_id
 * @property string $created
 * @property integer $created_user_id
 * @property string $modified
 * @property integer $supplier_id
 * @property string $description
 * @property double $subtotal
 * @property double $discount
 * @property double $ppn
 * @property double $other
 * @property integer $dp
 * @property integer $credit
 * @property integer $payment
 */
class Buy extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{buy}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('departement_id, created_user_id, supplier_id, dp, credit, payment', 'numerical', 'integerOnly' => true),
            array('departement_id, supplier_id', 'required'),
            array('total', 'numerical'),
            array('subtotal, discount, ppn, other', 'numerical'),
            array('code, description', 'length', 'max' => 255),
            array('created, modified', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, code, buy_order_id, departement_id, created, created_user_id, modified, supplier_id, description, subtotal, discount, ppn, other, dp, credit, payment', 'safe', 'on' => 'search'),
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'code' => 'Code',    
            'buy_order_id'=>'Code Buy Order',
            'departement_id' => 'Departement',
            'created' => 'Created',
            'created_user_id' => 'Created User',
            'modified' => 'Modified',
            'supplier_id' => 'Supplier',
            'description' => 'Description',
            'subtotal' => 'Subtotal',
            'discount' => 'Discount',
            'ppn' => 'Ppn',
            'other' => 'Tax',
            'dp' => 'Dp',
            'credit' => 'Credit',
            'payment' => 'Payment',
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
        $criteria->with = array('Departement','ProductSupplier');
        $criteria->together = true;
        
        $criteria->compare('id', $this->id);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('Departement.name',$this->departement_id,true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_user_id', $this->created_user_id);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('ProductSupplier.name',$this->supplier_id,true);               
        $criteria->compare('description', $this->description, true);
        $criteria->compare('subtotal', $this->subtotal);
        $criteria->compare('discount', $this->discount);
        $criteria->compare('ppn', $this->ppn);
        $criteria->compare('other', $this->other);
        $criteria->compare('dp', $this->dp);
        $criteria->compare('credit', $this->credit);
        $criteria->compare('payment', $this->payment);

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
     * @return Buy the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function getName(){
        $name = (isset($this->ProductSupplier->name)) ? $this->ProductSupplier->name : '-';
        return $name;
    }

}
