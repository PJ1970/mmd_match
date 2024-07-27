<?php
App::uses('AppModel', 'Model'); 
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
class UserLoginLog extends AppModel
{
	public $name = 'UserLoginLog';
	public $userTable='user_login_logs';
	public $belongsTo = array(
		'Office' => array(
			'className'     => 'Office',
			'foreignKey'    => 'office_id', 
		),
	); 

}