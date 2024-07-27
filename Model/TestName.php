<?php
class TestName extends AppModel {
	public $name = 'TestName'; 
	public $table = 'tests_name';
	
	 
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