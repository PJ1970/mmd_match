<?php 
App::uses('AppModel', 'Model');
class Apk extends AppModel {
    public $name = 'Apk';
	public $table = 'apks';
	public $validate = array( 
		'apk' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please Upload APK file.'
			), 
		),
		'version' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter version.'
			),
		),
		'build' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter build.'
			)
		),
		'device_type' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please select device type.'
			)
		),
		'minimum_version' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter minimum version.'
			)
		),
		'apk_type' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter apk type.'
			)
		),
    );
}