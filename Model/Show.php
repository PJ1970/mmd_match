<?php
class Show extends AppModel {
	public $name = 'Show';
	public $useTable='shows';
	public $validate = array(
		'name' => array(
			'rule' => 'notBlank',
			'message' => 'Please enter name.'
		),
		'description' => array(
			'rule' => 'notBlank',
			'message' => 'Please enter description.'
		),
		'start_date' => array(
			'rule' => 'notBlank',
			'message' => 'Please select start date .'
		),
		'end_date' => array(
			'rule' => 'notBlank',
			'message' => 'Please select end date .'
		)
	);
}