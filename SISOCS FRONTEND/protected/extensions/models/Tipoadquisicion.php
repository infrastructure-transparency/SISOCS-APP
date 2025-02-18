<?php

/**
 * This is the model class for table "{{tiposadquisicion}}".
 *
 * The followings are the available columns in table '{{tiposadquisicion}}':
 * @property string $adquisicion
 * @property string $siglas
 */
class Tipoadquisicion extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Tipoadquisicion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{tiposadquisicion}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('adquisicion', 'required'),
			array('adquisicion', 'length', 'max'=>100),
			array('siglas', 'length', 'max'=>6),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('adquisicion, siglas', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'adquisicion' => 'Método de Adquisición / Proceso de Contratación',
			'siglas' => 'Siglas',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('adquisicion',$this->adquisicion,true);
		$criteria->compare('siglas',$this->siglas,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}