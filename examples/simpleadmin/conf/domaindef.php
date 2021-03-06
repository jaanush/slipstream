<?php
$domaindef=array(
	'legacy'=>array(
		'type'=>'doctrine',
		'class'=>'\demo\Entities\User'
	),
	'ppl'=>array(
		'type'=>'dataobject',
		'class'=>'\data\DataObj\User',
		'schema'=>array(
			'idAttribute'=>'id',
			'labelAttribute'=>'name')
	),
	'user'=>array(
		'type'=>'dataobject',
		'class'=>'\data\DataObj\User',
		'schema'=>array(
			'idAttribute'=>'id',
			'labelAttribute'=>'name')
	),
	'entry'=>array(
		'type'=>'dataobject',
		'class'=>'\data\DataObj\User',
		'schema'=>array(
			'idAttribute'=>'id',
			'labelAttribute'=>'name')
	),
	'feature'=>array(
		'type'=>'dataobject',
		'class'=>'\data\DataObj\Feature',
		'schema'=>array(
			'idAttribute'=>'id',
			'labelAttribute'=>'name')
	)
);
