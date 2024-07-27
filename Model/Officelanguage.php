<?php
App::uses('AppModel', 'Model'); 

class Officelanguage extends AppModel
{
	public $validate = array(
		'office_report' => array(
		   'rule' => 'notBlank',
			'message' => 'Please select Office report.'
		)
	);
	public $hasOne = array(
		'Language' => array(
			'className'     => 'Language',
			'foreignKey'    => false,
			'conditions' => array('Language.id=Officelanguage.language_id')  
		),
	);
}