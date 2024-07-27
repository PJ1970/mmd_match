<?php
App::uses('AppModel', 'Model'); 

class Pointdata extends AppModel {
	public $useTable='pointdatas';

	var $inserted_ids = array();
		public $belongsTo = array(
		'User' => array(
			'className'     => 'User',
			'foreignKey'    => false,
			'conditions' => array('Pointdata.staff_id = User.id')  
		),
		'Patient' => array(
			'className'     => 'Patient',
			'foreignKey'    => false,
			'conditions' => array('Pointdata.patient_id = Patient.id')  
		),
		'Test'=>array(
			'className'=>'Test',
			'foreignKey'=>'test_type_id' 
		),
		
		/*  'File' => array(
			'className'     => 'File',
			'foreignKey'    => false,
			'conditions' => array('Testreport.id = File.test_report_id')  
		),  */
	);
	
	public $hasMany = array(
		'VfPointdata'=>array(
			'className'=>'VfPointdata',
			'foreignKey'=>'point_data_id',
			'order' => 'VfPointdata.index ASC'
		)
			
	);

	public $validate = array(
        'unique_id' => array(
								'rule' => 'isUnique', 
								'message' => 'Unique Id alreday exists.'
							)
    );

	function afterSave($created , $option=array()) {
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
	}
}
?>