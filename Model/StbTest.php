<?php
App::uses('AppModel', 'Model');

class StbTest extends AppModel
{
	public $useTable = 'stb_test';

	var $inserted_ids = array();
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => false,
			'conditions' => array('StbTest.staff_id = User.id')
		),
		'Patient' => array(
			'className' => 'Patient',
			'foreignKey' => false,
			'conditions' => array('StbTest.patient_id = Patient.id')
		),
		'Test' => array(
			'className' => 'Test',
			'foreignKey' => 'test_type_id'
		),

		/*  'File' => array(
			'className'     => 'File',
			'foreignKey'    => false,
			'conditions' => array('Testreport.id = File.test_report_id')
		),  */
	);

	public $hasMany = array(
		'StbPointdata' => array(
			'className' => 'StbPointdata',
			'foreignKey' => 'stb_data_id',
			'order' => 'StbPointdata.id ASC'
		)

	);

	public $hasOne = array(
		'Office' => array(
			'className' => 'Office',
			'foreignKey' => false,
			'conditions' => array('Office.id=StbTest.office_id')
		)
	);

	public $validate = array(
		'unique_id' => array(
			'rule' => 'isUnique',
			'message' => 'Unique Id alreday exists.'
		)
	);

	function afterSave($created, $option = array())
	{
		if ($created) {
			$this->inserted_ids[]['id'] = $this->getInsertID();
		}
		return true;
	}

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
