<?php 
App::uses('AppModel', 'Model');
class Diagnosis extends AppModel {
    public $name = 'Diagnosis';
	public $useTable = 'diagnosis';
	
	public $validate = array(
        'name' => array(
            'notBlank' => array(
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'Diagnosis is required'
            )
        ),
    );
}

?>