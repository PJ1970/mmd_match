<?php 
App::uses('AppModel', 'Model');
class DarkAdaptionnew extends AppModel {
	public $table = 'dark_adaptions';
    public $name = 'DarkAdaptionnew';
	 public $useTable = 'dark_adaptions';
		public $validate = array(
		 'unique_id' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter Id number.'
			), 
			'unique'=>array(
				'rule' => 'isUnique',
				'message' => 'Unique Id it is already taken.'
			),
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