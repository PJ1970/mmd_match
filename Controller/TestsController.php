<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController','Controller');
App::uses('CakeEmail','Network/Email');
App::import('Controller','ChatApi');
/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class TestsController extends AppController {
	public $uses = array('Admin','User', 'Module', 'AssignModule','AssignCoach','Office','Patient','Test');
			
	var $helpers = array('Html', 'Form','Js' => array('Jquery'), 'Custom');

    public $components = array('Auth'=>array('authorize'=>array('Controller')),'Session','Email','Common','RememberMe');
	public $allowedActions =array('admin_forgot_password','admin_login','admin_logout');
    
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow($this->allowedActions);	
	}
	
	protected function _setCookie($options = array(), $cookieKey = 'rememberMe') {
		$this->RememberMe->settings['cookieKey'] = $cookieKey;
		$this->RememberMe->configureCookie($options);
		$this->RememberMe->setCookie();
	}
	
	public function isAuthorized($user){ 
	//pr($user); die;
	    parent::beforeFilter();
		if(isset($user['user_type']) && (($user['user_type'] == 'Admin') || ($user['user_type'] == 'Subadmin') || ($user['user_type'] == 'Staffuser')) && isset($this->request->prefix) && ($this->request->prefix == 'admin')){
			return true;
		}else{
			$this->redirect($this->referer());
		}
    }
	/*Test List,Edit,Add*/
	function admin_index($id = null){
		$this->loadModel('AppConstant');
        $editData = "";
		if($id){
		$editData = $this->Test->find('first',array('conditions'=>array('Test.id'=>$id)));
		}
		if($this->request->is(array('post','put'))){
			if($this->Test->save($this->request->data)){
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
						if(!empty($device_token) && $this->checkNotification($val['NewUserDevice']['device_id'])){							$res = $this->sendPushNotificationNewAdminDataUpdateV2($device_token);
							//$res = $this->sendPushNotificationNewAdminDataUpdate($device_token);
						}
					}
					
				}
				$this->Session->setFlash("Test has been created/updated successfully.",'message',array('class' => 'message'));
				$this->redirect(array('controller' => 'tests', 'action' => 'index'));
			} else  {
				$this->Session->setFlash('Some error found.Please try again.','message',array('class' => 'message'));
			}
		}
		else{
			$this->request->data = $editData;
		}
		
		$user_type=$this->Auth->user('user_type');
		
		
		if($user_type!='Admin'){
			$conditions = array('Test.status'=>1,'Test.is_delete' => '0');
			//$data = $this->Test->find('all',array('conditions'=>array(),'order'=>array('Test.id DESC')));
		}else{
			$conditions = array('Test.is_delete' => '0');
			//$data = $this->Test->find('all',array('order'=>array('Test.id DESC')));
		}
		if(!empty($this->request->query['search'])){
			//echo "yes";die;
			$search = strtolower(trim($this->request->query['search'])); 
			$conditions['OR'][] = array('Lower(Test.name) like'=> '%'.$search.'%');
			 
			$this->set(compact('search'));
		} 
		
		$params = array(
			'conditions' => $conditions,
			'limit'=>10,
			'order'=>array('Test.id'=>'DESC')
		);
		
		$this->paginate=array('Test'=>$params);
		$datas = $this->paginate('Test');
		$this->set(compact('datas'));
	 
		
	}
	
	/*Test delete*/
	function admin_delete($id = null){
		//$this->loadModel('TestReport');
		$this->loadModel('AppConstant');
		if(!empty($id)){
			$result = $this->Test->query("update mmd_tests set is_delete='1' where id=".$id);
			$result = $this->Test->query("update mmd_test_reports set is_delete='1' where test_id=".$id);
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
					if(!empty($device_token) && $this->checkNotification($val['NewUserDevice']['device_id'])){						$res = $this->sendPushNotificationNewAdminDataUpdateV2($device_token);
						//$res = $this->sendPushNotificationNewAdminDataUpdate($device_token);
					}
				}
					
			}
			//$tis->TestReport->updateAll(array('is_delete' => '1'),array('test_id' => $id));
			$this->Session->setFlash("Test deleted successfully.",'message',array('class' => 'message'));	
		}else{
			$this->Session->setFlash("Unable to delete.",'message',array('class' => 'message'));
		}
		$this->redirect(array('controller' => 'tests', 'action' => 'index'));
	}
	
	
	/*This function for changing status of Test*/
	public function admin_changeStatus($id=null) {
		$this->layout=false;
		$data=$this->Test->find('first',array('conditions'=>array('Test.id'=>$id)));
		if($data['Test']['status']==1) {
			$this->Test->id = $id;
			$this->Test->saveField('status',0);	
		} else {
			$this->Test->id = $id;
			$this->Test->saveField('status',1);	
		}
		$this->Session->setFlash('Test status has been updated successfully.','message',array('class' => 'message'));
		$this->redirect(array('controller'=>'tests','action'=>'admin_index'));
	}
}

?>