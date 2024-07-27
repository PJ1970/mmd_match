<?php
App::uses('AppModel', 'Model'); 

class VaData extends AppModel {
	public $useTable='va_data';

	var $inserted_ids = array();
		public $belongsTo = array(
		'User' => array(
			'className'     => 'User',
			'foreignKey'    => false,
			'conditions' => array('VaData.staff_id = User.id')  
		),
		'Patient' => array(
			'className'     => 'Patient',
			'foreignKey'    => false,
			'conditions' => array('VaData.patient_id = Patient.id')  
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