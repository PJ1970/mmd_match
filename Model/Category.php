<?php
class Category extends AppModel {
	public $name = 'Category'; 
	public $table = 'categories';
	
	public $validate = array(
		'name' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter a category name'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'on' => array('create','update'),
				'message' => 'Category name already exists.'
			)
		)
	);
}
?>