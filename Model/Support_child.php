<?php
App::uses('AppModel', 'Model'); 
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
class SupportChild extends AppModel
{
	public $belongsTo = array(
		'SupportChildUser' => array(
			'className'     => 'CloseUser',
			'foreignKey'    => false,
			'conditions' => array('SupportChild.user_id = CloseUser.id1')  
		)
	);
 
    public $useTable = 'supports';
	public $name = 'SupportChild';
	
	
	
}