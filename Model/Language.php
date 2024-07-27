<?php
class Language extends AppModel {
	public $name = 'Language';
	public $useTable='languages';
	
		public $hasMany = array(
		'LanguageFile' => array(
            'className' => 'LanguageFile'
		)
    );
    
     public $validate = array(
		'code' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter Code'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'on' => array('create','update'),
				'message' => 'Code already exists.'
			),
		),
		'l_id' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter Language Id'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'on' => array('create','update'),
				'message' => 'Language Id already exists.'
			),
			'numeric' => array(
				'rule' => 'numeric',
				'on' => array('create','update'),
				'message' => 'Language Id Number only allowed.'
			),
		),
		 
		
    );
}
?>