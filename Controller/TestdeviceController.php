<?php
App::uses('AppController','Controller');
App::uses('CakeEmail','Network/Email');
App::import('Controller','ChatApi');

class TestdeviceController extends AppController {
	public $uses = array('TestDevice', 'DevicePreference');
			
	var $helpers = array('Html', 'Form','Js' => array('Jquery'), 'Custom');

    public $components = array('Auth'=>array('authorize'=>array('Controller')),'Session','Email','Common','RememberMe');
	public $allowedActions =array();
    
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->deny();
		$this->Auth->allow($this->allowedActions);	
	}
	/*public function isAuthorized($user){ 
	    parent::beforeFilter();
		if(isset($user['user_type']) && (($user['user_type'] == 'Admin') && isset($this->request->prefix) && ($this->request->prefix == 'admin'))){
			return true;
		}else{
			$this->redirect($this->referer());
		}
    }*/
	
	/*Test device list*/
	public function admin_test_device_list(){
		if($this->Session->read('Auth.Admin.user_type')=='Admin'){
		$conditions = array('TestDevice.is_delete' =>'0');
		if(!empty($this->Session->read('Search.office'))){
			$conditions[] = array('TestDevice.office_id' => $this->Session->read('Search.office'));
		}
		if(!empty($this->request->query['search'])){
			
			$search = strtolower(trim($this->request->query['search']));
			$conditions['OR'][] = array('Lower(TestDevice.name) like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(TestDevice.ip_address) like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(TestDevice.bt_mac_address) like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(TestDevice.mac_address) like'=> '%'.$search.'%');
		 	$conditions['OR'][] = array('Lower(TestDevice.deviceSeraial) like'=> '%'.$search.'%');
			$this->set(compact('search'));
		}
		$this->paginate=array('conditions'=>$conditions,
		'limit'=>10,
		'order'=>array('TestDevice.id'=>'DESC')); 
		$datas=$this->paginate('TestDevice');
		// print_r($datas);
		// die();
		$this->set(compact('datas'));
		}else{
            $this->redirect(WWW_BASE.'admin/dashboards/index');
        }
	}
	
	public function admin_test_device_home_use_list(){ 
		$this->loadModel('Patient');
		$Admin = $this->Session->read('Auth.Admin');
		if($this->Session->read('Auth.Admin.user_type')=='Admin'){
			$OfficeId = '' ;
		}else{
			$OfficeId = $Admin['office_id'] ;
			$conditions = array('TestDevice.office_id' =>$OfficeId);
		}
		//$conditions = array('TestDevice.is_delete' =>'0');
		$conditions['AND'] = array('TestDevice.is_delete' =>'0');
		$conditions['AND'] = array('TestDevice.device_type' =>'6'); //pr($conditions); die;
		//$conditions = array('TestDevice.device_type' =>'6'); //pr($conditions); die;
		if(!empty($this->Session->read('Search.office'))){
			$conditions[] = array('TestDevice.office_id' => $this->Session->read('Search.office'));
		}
		if(!empty($this->request->query['search'])){
			
			$search = strtolower(trim($this->request->query['search']));
			$conditions['OR'][] = array('Lower(TestDevice.name) like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(TestDevice.ip_address) like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(TestDevice.bt_mac_address) like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(TestDevice.mac_address) like'=> '%'.$search.'%');
		 
			$this->set(compact('search'));
		}
		$this->loadModel('Patient');
		$this->TestDevice->bindModel(
			array(
				'belongsTo' => array(
					'Patient' => array(
						'className' => 'Patient',
						'foreignKey' => false,
						'fields' => array('Patient.first_name','Patient.middle_name','Patient.last_name','Patient.id'),
						'conditions' => array('TestDevice.name = Patient.device_type')
					)
				)
			)
		);
		$this->paginate=array('conditions'=>$conditions,
		'limit'=>10,
		'order'=>array('TestDevice.id'=>'DESC')); 
		$datas=$this->paginate('TestDevice'); //pr($datas);
		$this->set(compact('datas'));
	}
	/*Test Device view*/
	public function admin_view($id=null){
		$this->layout=false;
		$this->loadModel('User');
		if(!empty($id)) {
			//$data = $this->TestDevice->findById($id);
			$this->Office->unbindModel(array('hasMany' => array('Officereport')));
			$this->TestDevice->bindModel(
			array(
				'belongsTo' => array(
					'User' => array(
						'className' => 'User',
						'foreignKey' => false,
						'conditions' => array('TestDevice.office_id = User.office_id')
						)
					)
				)
			);
			$data = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id'=>$id), 'recursive' => 2));
			//pr($data);die;
			$this->set(compact('data'));
		}	
	}
	
	
	/*Test Device edit*/
	public function admin_edit($id=null) {
		if($this->Session->read('Auth.Admin.user_type')=='Admin'){
		$this->loadModel('AppConstant');
		$this->loadModel('Patient');
		if(!empty($id)) {
			$data = $this->TestDevice->findById($id);
			if($this->request->is(array('post','put'))) {
				if(empty($this->request->data['TestDevice']['status'])){
				$this->request->data['TestDevice']['status'] = 1;
			}
			if(!empty($this->request->data['TestDevice']['mac_address'])){
				$mac_address = $this->request->data['TestDevice']['mac_address'];
				$this->request->data['TestDevice']['mac_address'] = str_replace(' ', '', $mac_address);
			}
			if(!empty($this->request->data['TestDevice']['bt_mac_address'])){
				$bt_mac_address = $this->request->data['TestDevice']['bt_mac_address'];
				$this->request->data['TestDevice']['bt_mac_address'] = str_replace(' ', '', $bt_mac_address);
			}
				if($this->request->data['TestDevice']['device_type'] !== 6){
					$this->Patient->updateAll(array('Patient.device_type' => NULL,'Patient.ihuunassigntime' => NULL,'Patient.progression_deatild' => NULL,'Patient.language' => NULL,'Patient.test_name_ihu' => NULL,'Patient.eye_type' => NULL),array('Patient.device_type'=> $this->request->data['TestDevice']['name']));
				}
				if($this->TestDevice->save($this->request->data)) {
					$Admin = $this->Session->read('Auth.Admin');
					$role_constant = Configure::read('role_constant');
					if(in_array($Admin['user_type'],$role_constant)){
						$status_1 = 1;
						$this->AppConstant->updateAll(array('AppConstant.is_update'=> "'".$status_1."'"),array('AppConstant.id'=>1));
						$this->loadModel('NewUserDevice');
						$allOfficeUser = $this->User->find('list', array(
							'fields' => array('User.id'),
							'conditions' => array('User.office_id' => $this->request->data['TestDevice']['office_id'])
						));
						$new_user_device = $this->NewUserDevice->find('all', array('conditions' => array('NewUserDevice.user_id' => $allOfficeUser)));
						//pr($new_user_device);die;
						foreach($new_user_device as $key => $val){
							$device_token = $val['NewUserDevice']['device_token'];
							if(!empty($device_token) && $this->checkNotification($val['NewUserDevice']['device_id'])){
								$res = $this->sendPushNotificationNewAdminDataUpdateV2($device_token);
								//$res = $this->sendPushNotificationNewAdminDataUpdate($device_token);
							}
						}
					}
					
					$this->Session->setFlash('Device has been updated successfully.','message',array('class'=>'message'));
					$this->redirect(array('controller'=>'testdevice','action'=>'admin_test_device_list'));
				}
			} else {
			    $devicePreference = $this->DevicePreference->find('first', array('conditions' => array('DevicePreference.id' => $data['TestDevice']['mac_address'])));
				$this->request->data = $data;
				if(!empty($devicePreference['DevicePreference'])){
					$this->request->data['devicePreference'] = $devicePreference['DevicePreference'];
				}
			}
		}
		}else{
            $this->redirect(WWW_BASE.'admin/dashboards/index');
        }
	}
	
	/*Test Device add*/
	public function admin_add($id=null) {
		if($this->Session->read('Auth.Admin.user_type')=='Admin'){
		if($this->request->is(array('post'))) { 
			if(empty($this->request->data['TestDevice']['status'])){
				$this->request->data['TestDevice']['status'] = 1;
			}
			if(!empty($this->request->data['TestDevice']['mac_address'])){
				$mac_address = $this->request->data['TestDevice']['mac_address'];
				$this->request->data['TestDevice']['mac_address'] = str_replace(' ', '', $mac_address);
			}
			if(!empty($this->request->data['TestDevice']['bt_mac_address'])){
				$bt_mac_address = $this->request->data['TestDevice']['bt_mac_address'];
				$this->request->data['TestDevice']['bt_mac_address'] = str_replace(' ', '', $bt_mac_address);
			}
			if($this->TestDevice->save($this->request->data)) {
				$this->Session->setFlash('Device has been added successfully.','message',array('class'=>'message'));
				$this->redirect(array('controller'=>'testdevice','action'=>'admin_test_device_list'));
			}
		}
		$data['TestDevice']['device_type']=0;
		$this->request->data = $data;
		}else{
            $this->redirect(WWW_BASE.'admin/dashboards/index');
        }
	}
	
	/* Add Device Preference */
	public function admin_add_device_preference($id=null){
		if($this->request->is(array('post'))) {
			if($this->DevicePreference->save($this->request->data)) {
				$this->Session->setFlash('Device has been added successfully.','message',array('class'=>'message'));
				$this->redirect($this->referer());
				//$this->redirect(array('controller'=>'testdevice','action'=>'admin_test_device_list'));
			}else{
				//$errors = $this->DevicePreference->validationErrors;
				//pr($errors); die;
			}
			$this->redirect($this->referer());
		}
	}
	
	/*Test Device delete*/
	public function admin_delete($id=null) {
		$this->loadModel('AppConstant');
		if(!empty($id)) {
			if($this->TestDevice->delete($id)) {
				
				$Admin = $this->Session->read('Auth.Admin');
				$role_constant = Configure::read('role_constant');
				//pr($Admin['user_type']);die;
				if(in_array($Admin['user_type'],$role_constant)){
					$status_1 = 1;
					$this->AppConstant->updateAll(array('AppConstant.is_update'=> "'".$status_1."'"),array('AppConstant.id'=>1));
					$this->loadModel('NewUserDevice');
					$new_user_device = $this->NewUserDevice->find('all');
					//pr($new_user_device);die;
					foreach($new_user_device as $key => $val){
						$device_token = $val['NewUserDevice']['device_token'];
						if(!empty($device_token) && $this->checkNotification($val['NewUserDevice']['device_id'])){
							$res = $this->sendPushNotificationNewAdminDataUpdateV2($device_token);
							//$res = $this->sendPushNotificationNewAdminDataUpdate($device_token);
						}
					}
			
				}
				
				$this->Session->setFlash('Device has been deleted successfully.','message',array('class'=>'message'));
				$this->redirect(array('controller'=>'testdevice','action'=>'admin_test_device_list'));
			}
			
		}
	}

	public function admin_unlocl($id=null){
		//if($this->Session->read('Auth.Admin.user_type')=='Admin'){
		$data=$this->TestDevice->find('first',array('conditions'=>array('TestDevice.id'=>$id)));
		$data['TestDevice']['lock_counter'] =0;
		$data['TestDevice']['lock_counter_time'] = null;
		$data['TestDevice']['lock_time'] = null;
		$this->TestDevice->save($data);
		$this->Session->setFlash('Device Unlock successfully.','message',array('class' => 'message'));
		$this->redirect($this->referer());
		 
		
	}
}

?>