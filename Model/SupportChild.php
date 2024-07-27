<?php
App::uses('AppModel', 'Model'); 
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
class SupportChild extends AppModel
{

 
    public $useTable = 'supports';
	public $name = 'SupportChild';
	
		public $belongsTo = array(
		'User' => array(
			'className'     => 'User',
			'foreignKey'    => false,
			'conditions' => array('SupportChild.user_id = User.id')  
		)
	);
	
}