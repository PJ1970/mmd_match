<?php
App::uses('AppModel', 'Model'); 
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
class Office extends AppModel {
	
	public $hasMany = array(
        'Officereport' => array(
            'className' => 'Officereport',
			'foreignKey' => 'office_id'
		)
    );
     public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'rep_admin',
        )
    );
	public $name = 'Office'; 
	public $table = 'offices';
	
	public $validate = array(
		'name' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter name of an office.'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'on' => array('create','update'),
				'message' => 'Office name already exists.'
			)
		),'per_use_cost' =>array(
			'numeric' => array(
				'rule' => 'numeric',
				'message' => 'Please enter value numeric.'
			)
		),'email' =>array(
			'email' => array(
				'rule' => '/^[A-Za-z0-9._%+-]+@([A-Za-z0-9-]+\.)+([A-Za-z0-9]{2,4})$/',
				'message' => 'Please enter a valid email address'
			),
			'unique' => array( 
				'rule' => 'isUnique',
				'on' => array('create','update'),
				'message' => 'Email already exists.'
			),			   
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
		public function beforeSave($options = array()){
		if(isset($this->data[$this->alias]['password'])){
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
        }
        return true;
    }
}
?>