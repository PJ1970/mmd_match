<?php
App::uses('AppModel', 'Model'); 
class Module extends AppModel
{
		
	public $name = 'Module';
    public $validate = array(
       'name'=>array(
			'rule'=>'notBlank',
			'required'=>true,
			'message'=>'Please enter module name'
		),
		'description' => array(
			'rule' => 'notBlank',
			'message' => 'Please enter module description'
		)
    );
}