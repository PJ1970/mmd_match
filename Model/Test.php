<?php
class Test extends AppModel {
	public $name = 'Test'; 
	public $table = 'tests';
	
	public $validate = array(
		'name' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter a test name'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'on' => array('create','update'),
				'message' => 'Test name already exists.'
			)
		)
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
?>