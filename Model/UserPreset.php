<?php
App::uses('AppModel', 'Model'); 
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
class UserPreset extends AppModel
{
	public $name = 'UserPreset';
	public $userTable='mmd_user_presets';

    public $validate = array(
		'user_id' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'User Id is required.'
			)
		),
		'presetA' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'PresetA is required.'
			)
		),
		'presetB' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'PresetB is required.'
			)
		),
		'presetC' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'PresetC is required.'
			)
		),
		'presetD' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'PresetD is required.'
			)
		),
		'presetE' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'PresetE is required.'
			)
		),
		'presetF' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'PresetF is required.'
			)
		)
	);

}