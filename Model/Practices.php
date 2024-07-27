<?php
App::uses('AppModel', 'Model'); 
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
class Practices extends AppModel
{
	public $name = 'Practices';
	public $userTable='Practices';
	
	public $validate = array(
		'name' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter name.'
			),
			'unique'=>array(
				'rule' => 'isUnique',
				'message' => 'Please enter another name it is already taken.'
			),
		),
		'phone' => array(
            'numeric'=>array(
			    'rule' => 'numeric',
				'allowEmpty' => true, 
				'message'=>'phone must be numeric.'
			),
			'maxLength'=>array(
			    'rule' => array('maxLength', 10),
				'message'=>'phone should not be more than 10 digit.'
			)
		),
    );
}