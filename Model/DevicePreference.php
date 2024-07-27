<?php
	App::uses('AppModel', 'Model');
	class DevicePreference extends AppModel {
		public $useTable='device_preference';
		public $validate = array(
			'id' => array(
				'rule' => array('minLength', 8),
				'required' => true,
				'message' => 'Please fill the mac address'
			),
			'FocusMin' => array(
				'required' => array(
					'rule' => 'notBlank', // use notBlank as of CakePHP 2.7
					'required' => true,
					'message' => 'Please fill FocusMin.'
				),
				'numeric' => array(
					'rule' => 'numeric',
					'message' => 'FocusMin should be integer'
				)
			),
			'FocusMax' => array(
				'required' => array(
					'rule' => 'notBlank', // use notBlank as of CakePHP 2.7
					'required' => true,
					'message' => 'Please fill FocusMax.'
				),
				'numeric' => array(
					'rule' => 'numeric',
					'message' => 'FocusMax should be integer'
				)
			),
			'FocusStep' => array(
				'required' => array(
					'rule' => 'notBlank', // use notBlank as of CakePHP 2.7
					'required' => true,
					'message' => 'Please fill FocusStep.'
				),
				'numeric' => array(
					'rule' => 'numeric',
					'message' => 'FocusStep should be integer only'
				)
			),
			'LimitX1' => array(
				'required' => array(
					'rule' => 'notBlank', // use notBlank as of CakePHP 2.7
					'required' => true,
					'message' => 'Please fill the LimitX1.'
				),
				'numeric' => array(
					'rule' => 'numeric',
					'message' => 'LimitX1 should be integer only'
				)
			),
			'LimitY1' => array(
				'required' => array(
					'rule' => 'notBlank', // use notBlank as of CakePHP 2.7
					'required' => true,
					'message' => 'Please fill the LimitY1.'
				),
				'numeric' => array(
					'rule' => 'numeric',
					'message' => 'LimitY1 should be integer only'
				)
			),
			'LimitX2' => array(
				'required' => array(
					'rule' => 'notBlank', // use notBlank as of CakePHP 2.7
					'required' => true,
					'message' => 'Please fill the LimitX2.'
				),
				'numeric' => array(
					'rule' => 'numeric',
					'message' => 'LimitX2 should be integer only'
				)
			),
			'LimitY2' => array(
				'required' => array(
					'rule' => 'notBlank', // use notBlank as of CakePHP 2.7
					'required' => true,
					'message' => 'Please fill the LimitY2.'
				),
				'numeric' => array(
					'rule' => 'numeric',
					'message' => 'LimitY2 should be integer only'
				)
			),
			'FocusThreshold' => array(
				'required' => array(
					'rule' => 'notBlank', // use notBlank as of CakePHP 2.7
					'required' => true,
					'message' => 'Please fill the FocusThreshold.'
				),
				'numeric' => array(
					'rule' => 'numeric',
					'message' => 'FocusThreshold should be integer only'
				)
			)
		);
	}