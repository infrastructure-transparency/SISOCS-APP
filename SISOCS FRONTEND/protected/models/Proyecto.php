<?php

/**
 * This is the model class for table "{{proyecto}}".
 *
 * The followings are the available columns in table '{{proyecto}}':
 * @property integer $idProyecto
 * @property string $codigo
 * @property string $nombre_proyecto
 * @property string $proposito
 * @property string $descrip
 * @property integer $idSector
 * @property integer $idSubSector
 * @property integer $idEnte
 * @property integer $idFuncionario
 * @property integer $idRol
 * @property double $presupuesto
 * @property string $fechaaprob
 * @property string $codsefin
 * @property string $descambiental
 * @property string $descreasentamiento
 * @property string $especiplano
 * @property string $presuprogra
 * @property string $estudiofact
 * @property string $estudioimpact
 * @property string $licambi
 * @property string $planreasea
 * @property string $acuerdofinan
 * @property string $notaprioridad
 * @property string $otro
 * @property double $lat1
 * @property double $lon1
 * @property double $lat2
 * @property double $lon2
 * @property string $estado
 * @property string $fechacreacion
 * @property string $fechapublicado
 * @property int $eje
 *
 * The followings are the available model relations:
 * @property Entes $idEnte0
 * @property Funcionarios $idFuncionario0
 * @property Rol $idRol0
 * @property Sector $idSector0
 * @property Subsector $idSubSector0
 * @property Fuentesfinan[] $csFuentesfinans
 * @property ProyectoMunicipio[] $proyectoMunicipios
 */
class Proyecto extends CActiveRecord {

    public $image1, $image2, $image3, $image4, $image5, $image6, $image7, $image8, $image9, $image10, $image11, $image12;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{proyecto}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('codigo, nombre_proyecto', 'required'),
            array('idSector,usuario_creacion, usuario_publicacion, idSubSector, idEnte,idUnidad, idFuncionario, idRol', 'numerical', 'integerOnly' => true),
            array('presupuesto, lat1, lon1,lat2, lon2', 'numerical'),
            array('codigo', 'length', 'max' => 30),
            array('codsefin', 'length', 'max' => 20),
            array('nombre_proyecto', 'length', 'max' => 2000),
			//array('descambiental,descreasentamiento', 'length', 'max' => 10000),
            array('especiplano, presuprogra, estudiofact, estudioimpact, licambi, planreasea, acuerdofinan, notaprioridad, otro', 'length', 'max' => 255),
            //array('fechaaprob'),
            array('estado', 'length', 'max' => 25),
            array('fechaaprob,descrip, fecha_creacion, fecha_publicacion, proposito', 'safe'),
            array('image1, image2, image3, image4, image5, image6, image7, image8, image9, image10, image11, image12', 'file', 'types' => 'jpg, png, pdf, doc, docx, txt, xlsx, xls', 'allowEmpty' => true, 'maxSize' => 1024 * 1024 * 200, 'tooLarge' => 'El archivo es mas largo de 200MB, por favor seleccione un archivo mas pequeño.'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('image1, image2, image3, image4, image5, image6, image7, image8, image9, image10, image11, image12, idProyecto, codigo, nombre_proyecto,usuario_creacion, usuario_publicacion, Proposito, descrip, idSector, idSubSector, idEnte,idUnidad, idFuncionario, idRol, presupuesto, 
            fechaaprob, codsefin, proposito,descambiental, descreasentamiento, especiplano, presuprogra, estudiofact, estudioimpact, licambi, planreasea, acuerdofinan, notaprioridad, otro, lat1, lon1, lat2, lon2, estado, fecha_creacion, fecha_publicacion', 'safe', 'on' => 'search, published'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'idEnte0' => array(self::BELONGS_TO, 'Entes', 'idEnte'),
            'idFuncionario0' => array(self::BELONGS_TO, 'Funcionarios', 'idFuncionario'),
            'idRol0' => array(self::BELONGS_TO, 'Rol', 'idRol'),
            'idSector0' => array(self::BELONGS_TO, 'Sector', 'idSector'),
            'idSubSector0' => array(self::BELONGS_TO, 'Subsector', 'idSubSector'),
            'csFuentesfinans' => array(self::MANY_MANY, 'Fuentesfinan', '{{proyecto_fuente}}(idProyecto, idFuente)'),
            'proyectoMunicipios' => array(self::HAS_MANY, 'ProyectoMunicipio', 'idProyecto'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'idProyecto' => 'Id Proyecto',
            'codigo' => 'Código',
            'nombre_proyecto' => 'Nombre del Proyecto',
            'Proposito' => 'Proposito u Objetivo',
            'descrip' => 'Descripción detallada y alcances del proyecto',
            'idSector' => 'Sector',
            'idSubSector' => 'Sub sector',
            'idEnte' => 'Autoridad Pública (Entidad Patrocinadora)',
            'idUnidad'=>'Unidad Responsable',
            'idFuncionario' => 'Funcionario responsable',
            'idRol' => 'Rol del funcionario',
            'presupuesto' => 'Presupuesto en LPS',
            'fechaaprob' => 'Fecha de aprobacion del presupuesto',
            'codsefin' => 'Codigo BIP (SEFIN)',
            'descambiental' => 'Descripción del impacto ambiental',
            'descreasentamiento' => 'Descripción de reasentamiento ',
            'especiplano' => 'Especificaciones y planos de la obra',
            'presuprogra' => 'Presupuesto y programa multianual del proyecto',
            'estudiofact' => 'Estudio de factibilidad o Perfil del proyecto',
            'estudioimpact' => 'Estudio de Impacto Ambiental (Diagnóstico o Evaluación)',
            'licambi' => 'Licencia Ambiental y Contrato de Medidas de Mitigación',
            'planreasea' => 'Plan de Reasentamiento y Compensación',
            'acuerdofinan' => 'Acuerdo de Financiamiento',
            'notaprioridad' => 'Descripción de aprobación del proyecto (Nota de prioridad o similar)',
            'otro' => 'Otro',
            'lat1' => 'Latitud inicial',
            'lon1' => 'Longitud inicial',
            'lat2' => 'Latitud final',
            'lon2' => 'Longitud final',
            'estado' => 'Estado',
            'fecha_creacion' => 'Fecha en que se creo la información',
            'fecha_publicacion' => 'Fecha en que se publicó la información',
            'usuario_creacion' => 'Usuario Creacion',
	    'usuario_publicacion' => 'Usuario Publicacion',
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

        $criteria->compare('idProyecto', $this->idProyecto);
        $criteria->compare('codigo', $this->codigo, true);
        $criteria->compare('nombre_proyecto', $this->nombre_proyecto, true);
        $criteria->compare('Proposito', $this->Proposito, true);
        $criteria->compare('descrip', $this->descrip, true);
        $criteria->compare('idSector', $this->idSector);
        $criteria->compare('idSubSector', $this->idSubSector);
        $criteria->compare('idEnte', $this->idEnte);
        $criteria->compare('idUnidad', $this->idUnidad);
        $criteria->compare('idFuncionario', $this->idFuncionario);
        $criteria->compare('idRol', $this->idRol);
        $criteria->compare('presupuesto', $this->presupuesto);
        $criteria->compare('fechaaprob', $this->fechaaprob, true);
        $criteria->compare('codsefin', $this->codsefin, true);
        $criteria->compare('descambiental', $this->descambiental, true);
        $criteria->compare('descreasentamiento', $this->descreasentamiento, true);
        $criteria->compare('especiplano', $this->especiplano, true);
        $criteria->compare('presuprogra', $this->presuprogra, true);
        $criteria->compare('estudiofact', $this->estudiofact, true);
        $criteria->compare('estudioimpact', $this->estudioimpact, true);
        $criteria->compare('licambi', $this->licambi, true);
        $criteria->compare('planreasea', $this->planreasea, true);
        $criteria->compare('acuerdofinan', $this->acuerdofinan, true);

        if (!Yii::app()->user->isSuperAdmin) {
            $criteria->addSearchCondition('t.usuario_creacion', Yii::app()->user->id, true, 'AND');
            $criteria->addSearchCondition('t.estado', 'BORRADOR', true, 'AND');
            if (Yii::app()->user->isInRole(Yii::app()->user->id, 'Publicador')) {
                $criteria->addSearchCondition('t.estado', 'REVISIÓN', true, 'OR');
            }

        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort'=>array(
              'defaultOrder'=>'t.idProyecto DESC',
            ),
        ));
    }

    public function published() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('idProyecto', $this->idProyecto);
        $criteria->compare('codigo', $this->codigo, true);
        $criteria->compare('nombre_proyecto', $this->nombre_proyecto, true);

        $criteria->addSearchCondition('t.estado', 'PUBLICADO', true, 'AND');

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort'=>array(
              'defaultOrder'=>'t.idProyecto DESC',
            ),'pagination'=>false
        ));
    }

    public function buscarFuenteFinanciamiento($iDP) {

        $criteria=new CDbCriteria;
        $criteria->select= 't.idFuente, t.fuente, t.monto, t.moneda, t.tasa_cambio, t.idProyecto';
        $criteria->condition='t.idProyecto='.$iDP;
        $modelFinan= new VProyectoFuenteFinanciamiento();
        return  new CActiveDataProvider($modelFinan, array(
            'criteria' => $criteria
        ));
    }

    public function buscarBudgetBreakdown($iDP) {

        $criteria=new CDbCriteria;
        $criteria->select= 't.*';
        $criteria->condition='t.idProyecto='.$iDP;
        $modelFinan= new BudgetBreakdown();
        return  new CActiveDataProvider($modelFinan, array(
            'criteria' => $criteria
        ));
    }

    public function buscarPrequalification($iDP) {

        $criteria=new CDbCriteria;
        $criteria->select= 't.*';
        $criteria->condition='t.idProyecto='.$iDP;
        $modelFinan= new Prequalification();
        return  new CActiveDataProvider($modelFinan, array(
            'criteria' => $criteria
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Proyecto the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //nuevas funciones
    private static $_items=array();
    public static function items($tipo){
        if(!isset(self::$_items[$tipo])){
            self::loadItems($tipo);
        }
        return self::loadItems($tipo);;
    }

     public static function item($tipo, $id)
     {
            if(!isset(self::$_items[$tipo]))
            self::loadItems($tipo);
            return isset(self::$_items[$tipo][$id]) ? self::$_items[$tipo][$id] : false;
      }

       private static function loadItems($tipo)
        {
         self::$_items[$tipo]=array();
         $models=self::model()->findAll(array(
          'order'=>'sector '.$tipo,
         ));
         foreach($models as $model)
          self::$_items[$tipo][$model->idSector]=$model->sector;
        }

    // Funciones personalizadas
    public function listaEntes() {
        $dat = Entes::model()->findAll();
        $dat = Entes::model()->findAll(array("order"=>"descripcion asc"));
        $list = CHtml::listData($dat, 'idEnte', 'descripcion');
        return $list;
    }

    public function listaUnidad(){
        $dat = EntesUnidad::model()->findAll();
        $list = CHtml::listData($dat, 'idUnidad', 'nombre');
        return $list;

    }


    public function listaSector() {
        /*$sectores=  Yii::app()->db->createCommand()
                                     ->select('*')
                                      ->from('cs_sector')
                                      ->order('idSector')
                                      ->query();
        $dat = Sector::model()->find();
        $list = CHtml::listData($dat,'idsector','sector');
        return $list;*/
    }
    //nueva Funcion
    public function listaSectores(){
        $dat=  Sector::model()->findAll();
        $list=CHtml::listData($dat,'idSector','sector');
        return $list;
    }


    public function listaSubSector(){
        $dat=  Subsector::model()->findAll();
        $list = CHtml::listData($dat, 'idSubSector', 'subsector');

        return $list;
    }



    public function listaRoles() {
        $dat = Rol::model()->findAll(array("order"=>"rol asc"));
        $list = CHtml::listData($dat, 'idRol', 'rol');
        return $list;
    }

    public function listaFuncionarios($idEnte) {
        /*
        if (isset($idEnte)) {
            $dat = Funcionarios::model()->findByAttributes(array('idEnte' => $idEnte));
        } else {
            $dat = Funcionarios::model()->findAll();
        }*/
        $dat = Funcionarios::model()->findAll();
        $dat = Funcionarios::model()->findAll(array("order"=>"nombre asc"));
        $list = CHtml::listData($dat, 'idFuncionario', 'nombre');
        return $list;
    }

    public function listaCalificaciones($id){

        $criteria = new CDbCriteria;
        $criteria->condition = "idProyecto=$id";
        $dat = Calificacion::model()->findAll($criteria);
        $list = array();
        if(count($dat)>0){
            $list = CHtml::listData($dat, 'idCalificacion', 'numproceso');
        }

        return $list;
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

    public function Usuario($id){
        $nameUser="";
        if($id==0){
            $nameUser="NO Asignado";
        }else{
            try{
            $usuario = Yii::app()->user->um->loadUserById($id);
            $nameUser=$usuario->username;
        }catch(Exception $ex){
            $nameUser="No Asignado";
        }
        }

        return $nameUser ;
    }

}
