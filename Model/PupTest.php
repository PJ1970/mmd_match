<?php
App::uses('AppModel', 'Model');

class PupTest extends AppModel
{
	public $useTable = 'pup_test';

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => false,
			'conditions' => array('PupTest.staff_id = User.id')
		),
		'Patient' => array(
			'className' => 'Patient',
			'foreignKey' => false,
			'conditions' => array('PupTest.patient_id = Patient.id')
		)
	);

	public $hasMany = array(
		'PupPointdata' => array(
			'className' => 'PupPointdata',
			'foreignKey' => 'pup_test_id'
		),
		'PupDiagnosis' => array(
			'className' => 'PupDiagnosis',
			'foreignKey' => 'pup_id'
		)
	);


	
	public $validate = array(
		'unique_id' => array(
			'rule' => 'isUnique',
			'message' => 'Unique Id alreday exists.'
		)
	);

	public function beforeFind($queryData)
	{
		if (parent::beforeFind($queryData) !== false) {
			if (!is_array($queryData['conditions'])) {
				$queryData['conditions'] = array('0' => $queryData['conditions']);
			}
			$defaultConditions = array($this->alias . '.is_delete' => 0);
			$queryData['conditions'] = array_merge($defaultConditions, $queryData['conditions']);
			return $queryData;
		}
		return false;
	}
}
