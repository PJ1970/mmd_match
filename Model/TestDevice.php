<?php
App::uses('AppModel', 'Model'); 

class TestDevice extends AppModel
{
	public $useTable='test_devices';
	public $validate = array(
		/* 'ip_address' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter IP address.'
			),
			  'ip_address' => array(
				'rule' => '/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/',
				'message' => 'Please enter a valid ip address'
			),  
			  'unique' => array(
				'rule' => 'isUnique',
				'on' => array('create','update'),
				'message' => 'IP address already exists.'
			),  
		), */
		'office_id' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please select office.'
			)
		),
		
		'device_level' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter device level.'
			)
		),
		'name' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter name.'
			),
			'unique' => array(
				'rule' => 'uniqueName',
				//'on' => 'create',
				'message' => 'name already exists.'
			),
		),
    );

    public $belongsTo = array( 
		'Office' => array(
			'className'     => 'Office',
			'foreignKey'    => false,
			'conditions' => array('TestDevice.office_id = Office.id')  
		)
	);
	public function afterSave($created, $options = Array()){ 
		Cache::delete('test_device_list'.$this->data['TestDevice']['office_id'], '_cake_model_');
	}
	public function beforeFind($queryData){
		if(parent::beforeFind($queryData) !== false){
			if(!is_array($queryData['conditions'])){
				$queryData['conditions'] = array('0'=>$queryData['conditions']);
			}
			$defaultConditions = array($this->alias . '.is_delete' => 0);
			$queryData['conditions'] = array_merge($defaultConditions, $queryData['conditions']);
			return $queryData;
		}
		return false;
	}
	public function uniqueName(){
		$count =0; 
		if(!empty($this->data[$this->alias]['id'])){
			 $count = $this->find('count',array('conditions'=>array('TestDevice.name'=>$this->data[$this->alias]['name'],'office_id'=>$this->data[$this->alias]['office_id'],'TestDevice.id !=' => $this->data[$this->alias]['id'])));
		}else{
			 $count  = $this->find('count',array('conditions'=>array('TestDevice.name'=>$this->data[$this->alias]['name'],'office_id'=>$this->data[$this->alias]['office_id'])));
		}
		return  ($count >0)? false :true;
	}
	public function devicelist($office_id) {
     // $model = $this; 
        $result = Cache::read('test_device_list'.$office_id, '_cake_model_');
        if (!$result) {
            $result = $this->find('all', array('conditions' => array('TestDevice.mac_address NOT' => '', 'TestDevice.office_id' => $office_id)));
            Cache::write('test_device_list'.$office_id, $result, '_cake_model_');
        }
        return $result;
      /*  return Cache::remember('devicelistNew', function () use ($model){
            return $model->find('all', array('conditions' => array('TestDevice.mac_address NOT' => '', 'TestDevice.office_id' => $user_id)));
        }, '_cake_model_');*/
    }
}