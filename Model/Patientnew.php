<?php
App::uses('AppModel', 'Model'); 
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
class Patientsnew extends AppModel
{
	public $table = 'patients';
	public $name = 'Patientsnew';
	public $useTable='patients'; 

	
	
	public $hasMany = array(
        'Pointdata' => array(
            'className' => 'Pointdata',
            'foreignKey' => 'patient_id'
		)
    );
    public $belongsTo = array(
		'Office' => array(
			'className'     => 'Office',
			'foreignKey'    => 'office_id', 
		),
	);
	public $validate = array(
		  'unique_id2' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter Id number.'
			), 
			'unique'=>array(
				'rule' => 'isUnique',
				'message' => 'Please enter another unique id it is already taken.'
			),
		)
    );
	
	/* public function beforeSave($options = array()){
		if(isset($this->data[$this->alias]['password'])){
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
        }
        return true;
    }*/
	
	/* public function beforeFind($queryData){
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