<?php
App::uses('AppModel', 'Model'); 

class Officereport extends AppModel
{
	public $validate = array(
		'office_report' => array(
		   'rule' => 'notBlank',
			'message' => 'Please select Office report.'
		)
	);
	public $hasOne = array(
		'Tests' => array(
			'className'     => 'Tests',
			'foreignKey'    => false,
			'conditions' => array('Tests.id=Officereport.office_report')  
		),
	);
}