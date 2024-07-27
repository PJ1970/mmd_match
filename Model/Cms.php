<?php 
App::uses('AppModel', 'Model');
class Cms extends AppModel {
    public $name = 'Cms';
	public $table = 'cms';
	public $validate = array(
	
		'page_name' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter your page name.'
			),
			'unique'=>array(
				'rule' => array('isUnique','update'),
				'message' => 'page name address already exists.'
			),
		),
		'title' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter your page title.'
			),
			'unique'=>array(
				'rule' => array('isUnique','update'),
				'message' => 'page tiltl address already exists.'
			),
		),
		'content' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter your page title.'
			)
		),
    );
}