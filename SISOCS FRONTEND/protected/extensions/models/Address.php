<?php

/**
 * This is the model class for table "{{address}}".
 *
 * The followings are the available columns in table '{{address}}':
 * @property double $latitude
 * @property double $longitude
 * @property integer $mapZoomLevel
 * @property integer $id
 */
class Address extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
        public $latitude;
        public $address;
        public $longitude;
        public $mapZoomLevel;
	public function tableName()
	{
		return '{{address}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mapZoomLevel', 'numerical', 'integerOnly'=>true),
			array('latitude, longitude,latitude2, longitude2', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('latitude, longitude, mapZoomLevel, id', 'safe', 'on'=>'search'),
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
                    'proyecto0' => array(self::BELONGS_TO, 'Proyecto', 'proyecto'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'latitude' => 'latitude',
			'longitude' => 'longitude',
			'mapZoomLevel' => 'mapZoomLevel',
			'id' => 'ID',
                        'latitude2' => 'Latitude2',
			'longitude2' => 'Longitude2',
			'proyecto' => 'Proyecto',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('latitude',$this->latitude);
		$criteria->compare('longitude',$this->longitude);
		$criteria->compare('mapZoomLevel',$this->mapZoomLevel);
		$criteria->compare('id',$this->id);
                $criteria->compare('latitude2',$this->latitude2);
		$criteria->compare('longitude2',$this->longitude2);
		$criteria->compare('proyecto',$this->proyecto,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Address the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
