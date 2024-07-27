<?php 
App::uses('AppModel', 'Model');
class DarkAdaption extends AppModel {
    public $name = 'DarkAdaption';
	public $table = 'dark_adaption';
		public $validate = array(
	
			'patient_name' =>array(
				'notBlank' => array(
					'rule' => 'notBlank',
					'message' => 'Please enter your page name.'
				)
			),
			'patient_id' =>array(
				'notBlank' => array(
					'rule' => 'notBlank',
					'message' => 'Please enter your page title.'
				)
			),
			'staff_name' =>array(
				'notBlank' => array(
					'rule' => 'notBlank',
					'message' => 'Please enter your page title.'
				)
			),
			'staff_id' =>array(
				'notBlank' => array(
					'rule' => 'notBlank',
					'message' => 'Please enter your page title.'
				)
			),
			'office_id' =>array(
				'notBlank' => array(
					'rule' => 'notBlank',
					'message' => 'Please enter your page title.'
				)
			),
			'test_date_time' =>array(
				'notBlank' => array(
					'rule' => 'notBlank',
					'message' => 'Please enter your page title.'
				)
			),
			'eye_select' =>array(
				'notBlank' => array(
					'rule' => 'notBlank',
					'message' => 'Please enter your page title.'
				)
			),
		);
		public $hasOne = array(
			'Office' => array(
				'className'     => 'Office',
				'foreignKey'    => false,
				'conditions' => array('Office.id=DarkAdaption.office_id')  
			),
			'User' => array(
				'className'     => 'User',
				'foreignKey'    => false,
				'conditions' => array('User.id=DarkAdaption.staff_id')  
			),
			'Patient' => array(
				'className'     => 'Patient',
				'foreignKey'    => false,
				'conditions' => array('Patient.id=DarkAdaption.patient_id')  
			)
		);
		
		public $hasMany = array(
			'DaPointData' => array(
				'className' => 'DaPointData',
				'foreignKey' => 'dark_adaption_id',
				'conditions' => array('DaPointData.is_delete' => '0'),
				'order' => 'DaPointData.created DESC',
				'dependent' => true
			)
		);
		
}