<?php
/* @var $this TenderDocumentsController */
/* @var $model TenderDocuments */

$this->breadcrumbs=array(
	'Tender Documents'=>array('index'),
	'Gestionar',
);

$this->menu=array(
	array('label'=>'Listar TenderDocuments', 'url'=>array('index')),
	array('label'=>'Crear TenderDocuments', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#tender-documents-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Gestionar Tender Documents</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'tender-documents-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'idCalificacion',
		'documentType',
		'title',
		'description',
		'pageStart',
		/*
		'pageEnd',
		'url',
		'datePublished',
		'dateModified',
		'accessDetails',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
