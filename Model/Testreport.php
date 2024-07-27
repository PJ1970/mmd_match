<?php
App::uses('AppModel', 'Model'); 

class Testreport extends AppModel
{
	public $useTable='test_reports';
	public $belongsTo = array(
		'User' => array(
			'className'     => 'User',
			'foreignKey'    => false,
			'conditions' => array('Testreport.staff_id = User.id')  
		),
		'Test' => array(
			'className'     => 'Test',
			'foreignKey'    => false,
			'conditions' => array('Testreport.test_id = Test.id')  
		),
		'Patient' => array(
			'className'     => 'Patient',
			'foreignKey'    => false,
			'conditions' => array('Testreport.patient_id = Patient.id')  
		),
		
		/*  'File' => array(
			'className'     => 'File',
			'foreignKey'    => false,
			'conditions' => array('Testreport.id = File.test_report_id')  
		),  */
	);
	public $validate = array(
		
    );
	
	public function beforeFind($queryData){
		if(parent::beforeFind($queryData) !== false){
			if(!is_array($queryData['conditions'])){
				$queryData['conditions'] = array('0'=>$queryData['conditions']);
			}
			$defaultConditions = array($this->alias . '.is_delete' => 0);
			$queryData['conditions'] = array_merge($defaultConditions, $queryData['conditions']);
			return $queryData;
		}
		return false;
	}
}