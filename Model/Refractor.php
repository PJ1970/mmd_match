<?php
App::uses('AppModel', 'Model'); 

class Refractor extends AppModel {
	public $useTable='refractors';

	var $inserted_ids = array();
		public $belongsTo = array(
		'User' => array(
			'className'     => 'User',
			'foreignKey'    => false,
			'conditions' => array('Refractor.staff_id = User.id')  
		),
		'Patient' => array(
			'className'     => 'Patient',
			'foreignKey'    => false,
			'conditions' => array('Refractor.patient_id = Patient.id')  
		)
	);
	
	


	/* function afterSave($created , $option=array()) {
		if($created) {
			$this->inserted_ids[]['id'] = $this->getInsertID();
		}
		return true;
	}
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
	} */
}
?>