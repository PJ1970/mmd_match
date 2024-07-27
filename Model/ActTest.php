<?php
App::uses('AppModel', 'Model');

class ActTest extends AppModel
{
	public $useTable = 'act_test';

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => false,
			'conditions' => array('ActTest.staff_id = User.id')
		),
		'Patient' => array(
			'className' => 'Patient',
			'foreignKey' => false,
			'conditions' => array('ActTest.patient_id = Patient.id')
		),

	);

	public $hasMany = array(
		'ActPointdata' => array(
			'className' => 'ActPointdata',
			'foreignKey' => 'act_point_data_id'
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

?>
