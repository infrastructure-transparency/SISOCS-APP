<?php

class ProgramaController extends Controller
{
             public $programafuente="";
    /**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','ListaSubsector','ListaFuncionario',
                                   'ViewDet','DeleteGet','ViewDetpropositos','DeleteGetproposito','ListaEstados'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
        public function actionViewDet()
	{
            $mf=array();
            $mf= ProgramaFuente::model()->findAll('idPrograma=:pf',array(':pf'=>$_GET['idPrograma']));
            //$mf=(count($mf)>0)?$mf:$mf->attributeLabels();
           // $list=CHtml::listData($mf,'idPrograma','idPrograma');
            $f=new ProgramaFuente;
            $a=$f->attributeLabels();
            $this->renderpartial('_filas',array('datos'=>$mf));
	}
        
         public function actionViewDet2()
	{
            $mf= ProgramaFuente::model()->findAll('idPrograma=:pf',array(':pf'=>$_GET['idPrograma']));
            //$mf=(count($mf)>0)?$mf:$mf->attributeLabels();
            $list=CHtml::listData($mf,'idPrograma','idPrograma');
            $f=new ProgramaFuente;
            $a=$f->attributeLabels();
		echo '
            <div class="view">
                    <table>
                    <tr>
                        <th>'.$a['idFuente'].'</th>
                        <th>'.$a['monto'].'</th>
                        <th>'.$a['tasa_cambio'].'</th>
                        <th>'.$a['idMoneda'].'</th>
                        <th></th>    
                    </tr>';
                //numero de columnas o celdas
                $cd1=(count($list)>0)?count($list):0;                
                    if ($cd1>0) {
                    foreach ($mf as $v) {
                       echo '<tr>';
                       echo '<td>'.$this->nombreFuente($v->idFuente).'</td>';
                       echo '<td>'.$v->monto.'</td>';
                       echo '<td>'.$v->tasa_cambio.'</td>';
                       echo '<td>'.$v->idMoneda.'</td>';
                     // echo '<td>'.CHtml::link('Eliminar','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?'));
                     echo '<td>'.CHtml::link('Eliminar',array('programa/DeleteGet',array('idPrograma'=>$v->idPrograma,'idFuente'=>$v->idFuente)),array('class'=>'del'));//, 'confirm' => 'Esta seguro de quitar el registro?
                       echo '</tr>';
                       }   
                    }
             echo ' </table>
              </div>        
            ';
	}
        
        public function actionViewDetpropositos()
	{
            $mp= ProgramaProposito::model()->findAll('idprograma=:en',array(':en'=>$_GET['id']));
            //$mf=(count($mf)>0)?$mf:$mf->attributeLabels();
            $list=CHtml::listData($mp,'idprograma','idprograma');
            $f=new ProgramaProposito;
            $a=$f->attributeLabels();
		echo '
            <div class="view">
                    <table>
                    <tr>
                        <th>'.$a['idprograma'].'</th>
                        <th>'.$a['idproposito'].'</th>
                        <th></th>
                    </tr>';
                //numero de columnas o celdas
                $cd=(count($list)>0)?count($list):0;                
                    if ($cd>0) {
                            foreach ($mp as $v) {
                               echo '<tr>';
                               echo '<td>'.$v->idprograma.'</td>';
                               echo '<td>'.$v->idproposito.'</td>';
                             // echo '<td>'.CHtml::link('Eliminar','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?'));
                             echo '<td>'.CHtml::link('Eliminar',array('programa/DeleteGetproposito',array('id'=>$v->idprograma,'propo'=>$v->idproposito)),array('class'=>'del'));//, 'confirm' => 'Esta seguro de quitar el registro?
                                     echo '</tr>';
                               }   
                    }

                       echo '
                    </table>
              </div>        
            ';
	}
        
       public function actionListaEstados()
        {
            $dat= Estado::model()->findAll();
            $list = CHtml::listData($dat,'estado', 'estado');
            return $list ;
        }
        
        public function nombreFuente($p) {
            $dt=Fuentesfinan::model()->findByPk($p);
            return $dt->fuente;
        }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
               $this->programafuente = array();
		$model=new Programa;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);
                $dir = Yii::getPathOfAlias('webroot.images');          
                $uploaded = false;
		if(isset($_POST['Programa']))
		{
		 	
                    $model->attributes=$_POST['Programa'];
                    $image1=CUploadedFile::getInstance($model,'image1');
                    $image2=CUploadedFile::getInstance($model,'image2');
                    $image3=CUploadedFile::getInstance($model,'image3');
                    $image4=CUploadedFile::getInstance($model,'image4');
                    $image5=CUploadedFile::getInstance($model,'image5');
                    $image6=CUploadedFile::getInstance($model,'image6');

                        if (strlen($image1)>0){
                           $uploaded =$image1->saveAs($dir.'/'.$image1->getName());
                            $model->cartaconvenio=$image1->getName();
                            $uploaded=true; 
                        }
                        if (strlen($image2)>0){
                           $uploaded =$image2->saveAs($dir.'/'.$image2->getName());
                            $model->otro1=$image2->getName();
                            $uploaded=true; 
                        }
                        if (strlen($image3)>0){
                           $uploaded =$image3->saveAs($dir.'/'.$image3->getName());
                            $model->planope=$image3->getName();
                            $uploaded=true; 
                        }
                        if (strlen($image4)>0){
                           $uploaded =$image4->saveAs($dir.'/'.$image4->getName());
                            $model->presupuesto=$image4->getName();
                            $uploaded=true; 
                        }if (strlen($image5)>0){
                           $uploaded =$image5->saveAs($dir.'/'.$image5->getName());
                            $model->perfildelprogra=$image5->getName();
                            $uploaded=true; 
                        }if (strlen($image6)>0){
                           $uploaded =$image6->saveAs($dir.'/'.$image6->getName());
                            $model->otro2=$image6->getName();
                            $uploaded=true; 
                        }
                        try {
                            $model->programa= $this->crearCodigo("PMRC",rand(1,1000),$model->idSector);  
			if($model->save()){
                              if ($model->estado==strtoupper("Publicado")) 
                                {
                                    $this->enviarCorreo();  
                                }
                                //$this->redirect(array('view','id'=>$model->idPrograma));
                                $this->redirect(array('update','id'=>$model->idPrograma));
                          }
                        } catch (Exception $ex) {
                            $model->addError(null, $ex->getMessage());
                        }		
		}

		$this->render('create',array(
			'model'=>$model,'programafuente'=> $this->programafuente));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
                $this->performAjaxValidation($model);
                $dir = Yii::getPathOfAlias('webroot.images');          
                $uploaded = false;
		if(isset($_POST['Programa']))
		{
				
                    $model->attributes=$_POST['Programa'];
                     $image1=CUploadedFile::getInstance($model,'image1');
                        $image2=CUploadedFile::getInstance($model,'image2');
                        $image3=CUploadedFile::getInstance($model,'image3');
                        $image4=CUploadedFile::getInstance($model,'image4');
                        $image5=CUploadedFile::getInstance($model,'image5');
                        $image6=CUploadedFile::getInstance($model,'image6');

                        if (strlen($image1)>0){
                           $uploaded =$image1->saveAs($dir.'/'.$image1->getName());
                            $model->cartaconvenio=$image1->getName();
                            $uploaded=true; 
                        }
                        if (strlen($image2)>0){
                           $uploaded =$image2->saveAs($dir.'/'.$image2->getName());
                            $model->otro1=$image2->getName();
                            $uploaded=true; 
                        }
                        if (strlen($image3)>0){
                           $uploaded =$image3->saveAs($dir.'/'.$image3->getName());
                            $model->planope=$image3->getName();
                            $uploaded=true; 
                        }
                        if (strlen($image4)>0){
                           $uploaded =$image4->saveAs($dir.'/'.$image4->getName());
                            $model->presupuesto=$image4->getName();
                            $uploaded=true; 
                        }if (strlen($image5)>0){
                           $uploaded =$image5->saveAs($dir.'/'.$image5->getName());
                            $model->perfildelprogra=$image5->getName();
                            $uploaded=true; 
                        }if (strlen($image6)>0){
                           $uploaded =$image6->saveAs($dir.'/'.$image6->getName());
                            $model->otro2=$image6->getName();
                            $uploaded=true; 
                        }
                       //$model->programa= $this->crearCodigo("PMRC","fuentefinan",$model->idSector);  
			if($model->save()){
                              if ($model->estado==strtoupper("Publicado")) 
                                {
                                    $this->enviarCorreo();  
                                }
                                $this->redirect(array('view','id'=>$model->idPrograma));
                          }
				
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
        
        public function actionDeleteGet()
	{  //$data=Municipio::model()->findAll('depto=:dep',array(':dep'=>$_POST['id']));
            try {
                 //$d= ProgramaFuente::model()->findAll('idPrograma=:p and idFuente=:f',array(':p'=>$_GET[0]['idPrograma'],':f'=>$_GET[0]['idFuente']));
                 $command=Yii::app()->db->createCommand();
                 $pro=$command->delete('cs_programa_fuente','idPrograma=:p and idFuente=:f',array(':p'=>$_GET[0]['idPrograma'],':f'=>$_GET[0]['idFuente']));
                 $this->actionUpdate($_GET[0]['idPrograma']);
            } catch (Exception $ex) {
               $model->addError(null, $ex->getMessage());
            }

	}
            
        public function actionDeleteGetproposito()
	{  //$data=Municipio::model()->findAll('depto=:dep',array(':dep'=>$_POST['id']));
            try {
                 //$d= ProgramaFuente::model()->findAll('idPrograma=:p and idFuente=:f',array(':p'=>$_GET[0]['idPrograma'],':f'=>$_GET[0]['idFuente']));
                 $command=Yii::app()->db->createCommand();
                 $pro=$command->delete('cs_programa_proposito','idprograma=:p and idproposito=:f',array(':p'=>$_GET[0]['id'],':f'=>$_GET[0]['propo']));
                 $this->actionUpdate($_GET[0]['id']);
            } catch (Exception $ex) {
               $model->addError(null, $ex->getMessage());
            }

	}
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{  
		$dataProvider=new CActiveDataProvider('Programa');
		$this->render('index',array(
			'dataProvider'=>$dataProvider
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Programa('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Programa']))
			$model->attributes=$_GET['Programa'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Programa the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Programa::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Programa $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='programa-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
         protected function crearCodigo($cod,$fuente,$sector) {
            return $cod."-".$fuente."-".$sector;
        }
        public function actionListaSubsector()
        {
            $dat= Subsector::model()->findAll('idsector=:sec',array(':sec'=>$_POST['id']));
            $list =CHtml::listData($dat,'idSubSector','subsector');
            $this->renderpartial('resultadosubcod',array('datos'=>$list,));
           // Yii::app()->end();
        }
        public function actionListaFuncionario()
        {
            $dat= Funcionarios::model()->findAll('idEnte=:en',array(':en'=>$_POST['id']));
            $list =CHtml::listData($dat,'idfuncionario','nombre');
            $this->renderpartial('resultadocod',array('datos'=>$list,));
           // Yii::app()->end();
                      
        }
}
