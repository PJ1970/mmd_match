<?php
App::uses('AppModel', 'Model'); 

class Support extends AppModel {
	public $useTable='supports';
 
 	public $belongsTo = array(
		'User' => array(
			'className'     => 'User',
			'foreignKey'    => false,
			'conditions' => array('Support.user_id = User.id')  
		),
		'Category' => array(
			'className'     => 'Category',
			'foreignKey'    => false,
			'conditions' => array('Support.caregory_id = Category.id')  
		),
		'CloseUser' => array(
			'className'     => 'CloseUser',
			'foreignKey'    => false,
			'conditions' => array('Support.closed_by = CloseUser.id')  
		), 
		'Office' => array(
			'className'     => 'Office',
			'foreignKey'    => false,
			'conditions' => array('Support.office_id = Office.id')  
		)
	);
// 	public $hasMany = array(
// 		'SupportChild' => array(
// 			'className' => 'SupportChild',
// 			'foreignKey' => 'parent_id',
// 			'order' => array('SupportChild.id' => 'desc'), 
// 		)
// 	);
	
	public $validate = array( 
		'message' =>array(
			'message' => array(
				'rule' => 'notBlank',
				'message' => 'Please Enter message'
			), 
		),
		'office_id' =>array(
			'message' => array(
				'rule' => 'notBlank',
				'message' => 'Please select office'
			), 
		),
		'user_id' =>array(
			'message' => array(
				'rule' => 'notBlank',
				'message' => 'Please select staff'
			), 
		),
		'device_serial_no' =>array(
			'message' => array(
				'rule' => 'notBlank',
				'message' => 'Please Enter device serial no'
			), 
		),
		'title' =>array(
			'message' => array(
				'rule' => 'notBlank',
				'message' => 'Please Enter title'
			), 
		),
    );
// 	public $hasOne = array(
//     'Support_chield' => array(
//         'className'     => 'Support',
//         'foreignKey'    => false,
//         'conditions' => array('Support_chield.chield_id = Support.id')  
//     ));

// public $hasMany = array(
//         'Support_chield' => array(
//             'className' => 'Support',
// 			'foreignKey' => 'chield_id'
// 		)
//     );
	 /// validation and relation ship is remain
}
?>