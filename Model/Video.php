<?php 
App::uses('AppModel', 'Model');
class Video extends AppModel {
    public $name = 'Video';
	public $table = 'videos';
	public $validate = array( 
		'video' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please Upload APK file.'
			), 
		),
		'name' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter name.'
			),
		), 
    );
}