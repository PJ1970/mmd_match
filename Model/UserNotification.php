<?php
App::uses('AppModel', 'Model'); 
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
class UserNotification extends AppModel
{
	public $name = 'UserNotification';
	public $userTable='user_notifications';
	public $belongsTo = array(
    'User' => array(
        'className'     => 'User',
        'foreignKey'    => 'user_id',
       // 'conditions' => array('UserNotification.user_id = User.id')  
    ));

}