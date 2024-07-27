<?php
class Contact extends AppModel {
	public $name = 'Contact';
	 public $belongsTo = array(
        'Show' => array(
            'className' => 'Show',
            'foreignKey' => 'show_id',
           	
        )
    ); 
	public $validate = array(
		'name' => array(
			'rule' => 'notBlank',
			'message' => 'Please enter name'
		),
		'email' => array('notBlank' => array(
					'rule' => 'notBlank',
					'message' => 'Please enter an email address.'
			),
			'email' => array(
			'rule' => '/^[A-Za-z0-9._%+-]+@([A-Za-z0-9-]+\.)+([A-Za-z0-9]{2,4}|museum)$/',
			'message' => 'Please enter a valid email address.'
			)			   
		),	
		'company_name' => array(
			'rule1' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter company name'
			),	
		),
		'mobile' => array(
			'rule1' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter mobile number'
			),
			'rule2' => array(
				'rule' => 'numeric',
				'message' => 'Only numbers are allowed'
			)	
		),
		'interest' => array(
			'rule1' => array(
				'rule' => 'notBlank',
				'message' => 'Please check at least one checkbox.'
			),	
		)
	);
}