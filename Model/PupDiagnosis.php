<?php
App::uses('AppModel', 'Model'); 

class PupDiagnosis extends AppModel
{
	public $table = 'pup_diagnosis';
	public $useTable='pup_diagnosis'; 
	public $validate = array(
		'pup_id' => array(
		   'rule' => 'notBlank',
			'message' => 'Please select diagnosis.'
		)
	);
	 
	 public $belongsTo = array(
		'Diagnosis' => array(
			'className'     => 'Diagnosis',
			'foreignKey'    => 'diagnosis_id', 
		),
	);
}