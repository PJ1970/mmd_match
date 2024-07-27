<?php
App::uses('AppModel', 'Model'); 

class PatientDiagnosis extends AppModel
{
	public $table = 'patient_diagnosis';
	public $useTable='patient_diagnosis'; 
	public $validate = array(
		'diagnosis_id' => array(
		   'rule' => 'notBlank',
			'message' => 'Please select diagnosis.'
		)
	);
	public $hasOne = array(
		'Diagnosis' => array(
			'className'     => 'Diagnosis',
			'foreignKey'    => false,
			'conditions' => array('Diagnosis.id=PatientDiagnosis.diagnosis_id')  
		),
	);
}