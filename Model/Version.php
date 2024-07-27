<?php 
App::uses('AppModel', 'Model');
class Version extends AppModel {
    public $name = 'Version';
	public $table = 'version';
	public $validate = array(
	
		'version' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter version.'
			)
		)
    );
}