<?php
App::uses('AppModel', 'Model'); 
//App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
class PushNotificationsLog extends AppModel
{
	public $name = 'PushNotificationLog';
	//public $userTable='push_notification_logs';
	/*public $belongsTo = array(
    'User' => array(
        'className'     => 'User',
        'foreignKey'    => 'user_id',
       // 'conditions' => array('UserNotification.user_id = User.id')  
    ));*/

}