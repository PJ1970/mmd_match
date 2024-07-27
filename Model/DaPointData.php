<?php 
App::uses('AppModel', 'Model');
class DaPointData extends AppModel {
    public $name = 'DaPointData';
	public $table = 'da_pointdata';
	public $validate = array(
	
		'dark_adaption_id' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter your page name.'
			)
		),
		'timeMin' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter your page title.'
			)
		),
		'Decibles' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter your page title.'
			)
		),
		'x' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter your page title.'
			)
		),
		'y' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter your page title.'
			)
		),
		
    );
}