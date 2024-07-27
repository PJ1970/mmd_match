<?php
App::uses('AppModel', 'Model'); 
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
class Patient extends AppModel
{
	public $name = 'Patients';
	public $userTable='patients';
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
		/* 'id_number' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter Id number.'
			), 
			'unique'=>array(
				'rule' => 'isUnique',
				'message' => 'Please enter another Id number it is already taken.'
			),
		), */
		'first_name' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter your first name.'
			)
		),
		'last_name' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter your last name.'
			)
		),
		'office_id' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please select office.'
			)
		),
		/* 'user_id' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please select Staff for this patient.'
			)
		), */
		/*'dob' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter date of birth.'
			)
		),*/ 
		'mm' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter month of birth.'
			)
		),
		'dd' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter date of birth.'
			)
		),
		'yy' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter year of birth.'
			)
		),
		'email' =>array(
			 
			'email' => array(
				'rule' => '/^[A-Za-z0-9._%+-]+@([A-Za-z0-9-]+\.)+([A-Za-z0-9]{2,4})$/',
				'message' => 'Please enter a valid email address.'
			),
			'unique'=>array(
				'rule' => array('isUnique','update'),
				'message' => 'Email address already exists.'
			),			   

		)
    );
	
	 public function beforeSave($options = array()){
	     
	       //date_default_timezone_set("Asia/Kolkata");
	       //$this->data[$this->alias]['created_at_for_archive']=date('Y-m-d H:i:s');
	     
		/*if(isset($this->data[$this->alias]['password'])){
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
        }
        return true;*/
        return true;
    }
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