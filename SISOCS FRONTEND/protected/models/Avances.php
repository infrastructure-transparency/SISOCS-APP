<?php

    /**
        * This is the model class for table "{{avances}}".
        *
        * The followings are the available columns in table '{{avances}}':
        * @property integer $idAvances
        * @property string $idInicioEjecucion
        * @property integer $porcent_programado
        * @property integer $porcent_real
        * @property string $finan_programado
        * @property string $finan_real
        * @property string $fecha_registro
        * @property string $user_registro
        * @property string $fecha_avance
        * @property string $desc_problemas
        * @property string $desc_temas
        * @property string $adj_garantias
        * @property string $adj_avances
        * @property string $adj_supervicion
        * @property string $adj_evaluacion
        * @property string $adj_tecnica
        * @property string $adj_financiero
        * @property string $adj_recepcion
        * @property string $adj_disconformidad
        * The followings are the available model relations:
        * @property ActAvances[] $actAvances
    */
    class Avances extends CActiveRecord
    {

        public $image1, $image2, $image3, $image4, $image5, $image6, $image7, $image8;
        /**
            * @return string the associated database table name
        */
        public function tableName()
        {
            return '{{avances}}';
        }

        /**
            * @return array validation rules for model attributes.
        */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
			          array('idInicioEjecucion,porcent_programado,porcent_real, fecha_avance', 'required'),
          			array('porcent_programado, porcent_real, idInicioEjecucion, finan_programado, finan_real', 'length', 'max'=>15),
          			array('usuario_creacion', 'length', 'max'=>16),
          			array('estado', 'length', 'max'=>25),
          			array('desc_problemas, desc_temas', 'length', 'max'=>200),
                array('adj_garantias, adj_avances, adj_supervicion, adj_evaluacion, adj_tecnica, adj_financiero, adj_recepcion, adj_disconformidad', 'length', 'max'=>150),
          			array('fecha_avance', 'safe'),

                array('image1, image2, image3, image4, image5, image6, image7, image8', 'file', 'types' => 'jpg, png, pdf, doc, docx, txt, xlsx, xls', 'allowEmpty' => true, 'maxSize' => 1024 * 1024 * 200, 'tooLarge' => 'El archivo es mas largo de 200MB, por favor seleccione un archivo mas pequeño.'),

          			// The following rule is used by search().
          			// @todo Please remove those attributes that should not be searched.
          			array('image1, image2, image3, image4, image5, image6, image7, image8, codigo, 	idInicioEjecucion, porcent_programado, porcent_real, finan_programado, finan_real, fecha_avance,estado, usuario_creacion, fecha_avance, desc_problemas, desc_temas, adj_garantias, adj_avances, adj_supervicion, adj_evaluacion, adj_tecnica, adj_financiero, adj_recepcion, adj_disconformidad', 'safe', 'on'=>'search'),
            );
        }

        /**
            * @return array relational rules.
        */
        public function relations()
        {
            // NOTE: you may need to adjust the relation name and the related
            // class name for the relations automatically generated below.
            return array();
        }

        /**
            * @return array customized attribute labels (name=>label)
        */
        public function attributeLabels()
        {
            return array(
          			'idAvances' => 'Id Avance',
          			'idInicioEjecucion' => 'Código Implementación',
          			'porcent_programado' => 'Físico Programado',
          			'porcent_real' => 'Físico Real',
          			'finan_programado' => 'Financiero Programado',
          			'finan_real' => 'Financiero Real',
          			'fecha_avance' => 'Fecha Registro',
          			'estado'=> 'Estado Actual de Solicitud',
          			'usuario_creacion' => 'Usuario Registro',
          			'fecha_avance' => 'Fecha de Avance',
          			'desc_problemas' => 'Descripción de Problemas',
          			'desc_temas' => 'Descripción de Temas',
                'adj_garantias' => 'Doc1 Garantias',
          			'adj_avances' => 'Doc2 Avan Info',
          			'adj_supervicion' => 'Doc3 Super Info',
          			'adj_evaluacion' => 'Doc4 Evalu Info',
          			'adj_tecnica' => 'Doc5 Audit Info',
          			'adj_financiero' => 'Doc6 Audif Info',
          			'adj_recepcion' => 'Doc7 Recep Acta',
          			'adj_disconformidad' => 'Doc8 Discon Info',
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

            $criteria=new CDbCriteria;

            $criteria->compare('idAvances',$this->idAvances);
            $criteria->compare('idInicioEjecucion',$this->idInicioEjecucion,true);
            $criteria->compare('porcent_programado',$this->porcent_programado,true);
            $criteria->compare('finan_programado',$this->finan_programado,true);
            $criteria->compare('porcent_real',$this->porcent_real,true);
            $criteria->compare('finan_real',$this->finan_real,true);
            $criteria->compare('estado',$this->estado,true);

            $criteria->order = 't.idAvances ASC';

            if (!Yii::app()->user->isSuperAdmin) {
                $criteria->addSearchCondition('t.usuario_creacion', Yii::app()->user->id, true, 'AND');
                $criteria->addSearchCondition('t.estado', 'BORRADOR', true, 'AND');
                if (Yii::app()->user->isInRole(Yii::app()->user->id, 'Publicador')) {
                    $criteria->addSearchCondition('t.estado', 'REVISIÓN', true, 'OR');
                }

            }

            return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
              'defaultOrder'=>'t.idAvances DESC',
            ),'pagination'=>false
            ));
        }

        public function searchWithInicioEjecucion($id) {
            // @todo Please modify the following code to remove attributes that should not be searched.

            $criteria=new CDbCriteria;

            $criteria->compare('idAvances',$this->idAvances);
            $criteria->compare('idInicioEjecucion',$this->idInicioEjecucion,true);
            $criteria->compare('porcent_programado',$this->porcent_programado,true);
            $criteria->compare('finan_programado',$this->finan_programado,true);
            $criteria->compare('porcent_real',$this->porcent_real,true);
            $criteria->compare('finan_real',$this->finan_real,true);
            $criteria->compare('estado',$this->estado,true);

            $criteria->order = 't.idAvances ASC';
            $criteria->addSearchCondition('t.idInicioEjecucion', $id, false, 'AND');

            if (!Yii::app()->user->isSuperAdmin) {
                $criteria->addSearchCondition('t.usuario_creacion', Yii::app()->user->id, true, 'AND');
                $criteria->addSearchCondition('t.estado', 'BORRADOR', true, 'AND');
                if (Yii::app()->user->isInRole(Yii::app()->user->id, 'Publicador')) {
                    $criteria->addSearchCondition('t.estado', 'REVISIÓN', true, 'OR');
                }
            }

            return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
              'defaultOrder'=>'t.idAvances DESC',
            ),'pagination'=>false
            ));
        }

        public function published()
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            $criteria=new CDbCriteria;

            $criteria->compare('idAvances',$this->idAvances);
            $criteria->compare('idInicioEjecucion',$this->idInicioEjecucion,true);
            $criteria->compare('porcent_programado',$this->porcent_programado,true);
            $criteria->compare('finan_programado',$this->finan_programado,true);
            $criteria->compare('porcent_real',$this->porcent_real,true);
            $criteria->compare('finan_real',$this->finan_real,true);
            $criteria->compare('estado',$this->estado,true);

            $criteria->order = 't.idInicioEjecucion ASC, t.idAvances ASC';
            $criteria->addSearchCondition('t.estado', 'PUBLICADO', true, 'AND');

            return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>false
            ));
        }

        /**
            * Returns the static model of the specified AR class.
            * Please note that you should have this exact method in all your CActiveRecord descendants!
            * @param string $className active record class name.
            * @return Avances the static model class
        */
        public static function model($className=__CLASS__)
        {
            return parent::model($className);
        }


        public function listaEstados() {
          $criteria = new CDbCriteria;
          $criteria->condition = '1';
          if (!Yii::app()->user->isSuperAdmin) {
            if (!Yii::app()->user->isInRole(Yii::app()->user->id, 'Publicador')) {
              $criteria->condition = "(estado = 'BORRADOR' OR estado = 'REVISION' OR estado = 'REVISÓN')";
            }
          }

          $dat = Estado::model()->findAll($criteria);
          $list = CHtml::listData($dat, 'estado', 'estado');
          return $list;
        }

        public function getPorsentageReal($id){
            $pReal=$this->model()->findByPk($id);
            return $pReal['porcent_real'];
        }

        public function getPorsentageProgramado($id){
            $pReal=$this->model()->findByPk($id);
            return $pReal['porcent_programado'];
        }



    }
