<?php
App::uses('AppModel', 'Model'); 
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
class AdminRole extends AppModel
{
    public $name = 'AdminRole';
	public $belongsTo = array(
			'Admin'=>array(
				'className'=>'Admin',
				'foreignKey'=>'admin_id'
			),
		);
	public function beforeSave($options = array()){
		if(!empty($this->data[$this->alias]['modules'])) {
					$this->data[$this->alias]['modules'] = implode(',', $this->data[$this->alias]['modules']);
		}
	}	
	
}