<?php
class Interest extends AppModel {
	public $name = 'Interest';
	public $useTable='interests';
	
	public $validate = array(
		'name' => array(
			'rule' => 'notBlank',
			'message' => 'Please enter name.'
		)
	);
}
?>