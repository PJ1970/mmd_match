<?php
App::uses('AppModel', 'Model'); 
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
class Admin extends AppModel
{
	
    public $name = 'Admin';
	public $hasOne = 'AdminRole';
    public $validate = array(
       'first_name'=>array(
			'rule'=>'notBlank',
			'required'=>true,
			'message'=>'Input your first name'
		),	
        'last_name' => array(
            'rule' => 'notBlank',
            'required' => true,
			'message' => 'Input your Last name'
        ),
		 'email' => array(
			  
			 'email' => array(
				'rule' => '/^[A-Za-z0-9._%+-]+@([A-Za-z0-9-]+\.)+([A-Za-z0-9]{2,4}|museum)$/',
				'message' => 'Please enter a valid email address.'
				), 
			 'unique' => array(
              'rule' => 'isUnique',
			  'message' => 'Email address already exists.',
             
			 )
		), 
		
		
		/* 'password' => array(
            'length' => array(
                'rule'      => array('between', 8, 40),
                'message'   => 'Your password must be between 8 and 40 characters.',
            ),
        ),
		'cpassword' => array(
			'length' => array(
                'rule'      => array('between', 8, 40),
                'message'   => 'Your password must be between 8 and 40 characters.',
            ),
           'passwordequal' => array('rule' => 'checkpasswords' , 'message' => 'Passwords Do Not Match')
		) */		
    );
	public function checkpasswords(){

    if(strcmp($this->data['Admin']['password'],$this->data['Admin']['cpassword']) == 0 )
    {
        return true;
    }
    return false;
}

    function validateLogin($data) 
    { 
        $user = $this->find(array('username' => $data['username'], 'password' => md5($data['password'])), array('id', 'username')); 
        if(empty($user) == false) 
            return $user['Admin']; 
        return false; 
    }
	
    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
        }
		
		if (!empty($this->data[$this->alias]['first_name']) && empty($this->data[$this->alias]['slug'])) {
			if(!isset($this->data[$this->alias]['id'])){
				$this->data[$this->alias]['slug']=$this->stringToSlug($this->data[$this->alias]['first_name']);
			}else{
				$this->data[$this->alias]['slug']=$this->stringToSlug($this->data[$this->alias]['first_name'],$this->data[$this->alias]['id']);
			}
                
        }
        return true;
    }
	function check_unique($field = array(), $compareField = null){
		$condition = array(
		        $this->name.".".$compareField => $this->data[$this->name][$compareField]            
                );
    if (isset($this->data[$this->name]["id"])) {
        $condition[$this->name.".id <>"] = $this->data[$this->name]['id'];
        //your query will be against id different than this one when 
        //updating 
    }
    $result = $this->find("count", array("conditions" => $condition));
	//pr($result);die;
    return ($result == 0);
	}
	
	
	function setPass($field = array(), $compareField = null) {
		if($compareField =='conf_password')  {
			if($this->data[$this->name]['password'] != $this->data[$this->name]['conf_password'])
			{
				$this->validationErrors['conf_password'] = 'Passwords doesn\'t match.';
				return $this->validationErrors;
			}
			else{
				//$this->data[$this->name]['password'] = md5($this->data[$this->name]['password']);
				$this->data[$this->name]['password'] = $this->data[$this->name]['password'];
			}			
		}
		return true;
	}
}