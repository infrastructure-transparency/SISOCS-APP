<?php

class MunicipioController extends Controller
{
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
				'actions'=>array('index','view'),
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
	public function actionView($idMunicipio,$idDepartamento)
	{
		$model=$this->loadModel($idMunicipio,$idDepartamento);
		$this->render('view',array(
			'model'=>$model
		));
	}

	function showError(Exception $e)
		{
		// Error: 1022 SQLSTATE: 23000 (ER_DUP_KEY)
		if($e->getCode()==23000)
		{
		$message = "This operation is not permitted due to an existing foreign key reference.";
		}
		else
		{
		$message = "Invalid operation.";
		}
		throw new CHttpException($e->getCode(), $message);
		} 

	 public function saveModel($model)
		{
		try
		{
		$model->save();
		}
		catch(Exception $e)
		{
		$this->showError($e);
		}
		} 
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Municipio;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Municipio']))
		{
			$model->attributes=$_POST['Municipio'];
			if($model->save()){
				$this->redirect(array('view','idMunicipio'=>$model->idMunicipio,'idDepartamento'=>$model->idDepartamento));
			}
				//$this->redirect(array('view', 'model'=>$model, 'idProyecto'=>$id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($idMunicipio,$idDepartamento)
	{
		$model=$this->loadModel($idMunicipio,$idDepartamento);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Municipio']))
		{
			$model->attributes=$_POST['Municipio'];
			if($model->validate() && $model->save()){
				$this->redirect(array('view','idMunicipio'=>$model->idMunicipio,'idDepartamento'=>$model->idDepartamento));
				//$this->redirect(array('view','id'=>$model->primaryKey));
				}
			else{
				print_r($model->getErrors());}
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
	public function actionDelete($idMunicipio,$idDepartamento)
	{
		//$this->loadModel($id)->delete();
		$this->loadModel($idMunicipio,$idDepartamento)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Municipio');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Municipio('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Municipio']))
			$model->attributes=$_GET['Municipio'];
		
		//$records=  Municipio::model()->findAll();
        $this->render('admin',array(
                    'model'=>$model
                ));
		
		/*
		$records=Municipio::model()->findAll();

		$this->render('admin',array(
			'records'=>$records,
		));*/
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Municipio the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($idMunicipio,$idDepartamento)
	{
		//$model=Municipio::model()->findByPk($id);
		$model=Municipio::model()->findByPk(array('idMunicipio'=>$idMunicipio, 'idDepartamento'=>$idDepartamento));
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Municipio $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='municipio-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
