<?php
App::uses('AppModel', 'Model'); 
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
class User extends AppModel
{
	public $name = 'User';
	public $userTable='users';
	public $hasOne = array(
    'Office' => array(
        'className'     => 'Office',
        'foreignKey'    => false,
        'conditions' => array('User.office_id = Office.id')  
    ));
	public $virtualFields = array(
    'complete_name' => 'CONCAT(User.first_name, " ", User.last_name)'
);
    public $validate = array(
		'username' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter your username'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'on' => array('create','update'),
				'message' => 'Username already exists.'
			),
		),
		'created_by' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please select sub admin for this staff.'
			)
		),
		'office_id' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please select office.'
			)
		),
		'practice_id' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please select practice.'
			)
		),
		/* 'dob' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter date of birth.'
			)
		), */
		/* 'gender' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please select gender.'
			)
		), */
		'first_name' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter your first name'
			)
		),
		'last_name' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter your last name'
			)
		),
		'password' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter your password.'

			),
			'complexity' => array(
                'rule' => array('custom', '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,16}$/'),
                'message' => 'Please follow the password rule.'
            ),
            'length' => array(
                'rule'=> array('between',8,16),
                'message'=> 'Please follow the password rule.',
            ),
            'unique' => array(
                'rule' => 'isUniquePassword',
                'message' => 'You have already used this password. Please choose a different password.'
            )
        ),
		'email' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter your email address'

			),
			'email' => array(
				'rule' => '/^[A-Za-z0-9._%+-]+@([A-Za-z0-9-]+\.)+([A-Za-z0-9]{2,4})$/',
				'message' => 'Please enter a valid email address'
			)/*,
			'unique' => array(
				'rule' => 'isUnique',
				'on' => array('create','update'),
				'message' => 'Email already exists.'
			),	*/		   
		)
		
    );
	public function checkpasswords(){

		if(strcmp($this->data['User']['password'],$this->data['User']['confirm_password']) == 0 )
		{
			return true;
		}
		return false;
	}
	public function isUniquePassword($check) {
		if(empty($this->data[$this->alias]['id'])){
			return true;
		} 
		$password = array_values($check)[0];
		$passwordHasher = new BlowfishPasswordHasher(); 
            $oldPasswords = $this->find('first', array(
            'conditions' => array('User.id' => $this->data[$this->alias]['id']),
            'fields' => array('User.password')
        ));
         
        if(empty($oldPasswords)){
        	return true;
        }else{
        	if ($passwordHasher->check($this->data[$this->alias]['password'], $oldPasswords['User']['password'])) {
        		return false;
        	}
        	return true;
        }
	}
	public function beforeSave($options = array()){
		if(isset($this->data[$this->alias]['password'])){
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
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