
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
class DiagnosisController extends AppController {
	public $uses = array('Admin','User', 'Module', 'AssignModule','AssignCoach','Office','Diagnosis');
			
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
	/*Diagnosis List,Edit,Add*/
	function admin_index($id = null){
		$this->loadModel('AppConstant');
        $editData = "";
		if($id){
		$editData = $this->Diagnosis->find('first',array('conditions'=>array('Diagnosis.id'=>$id)));
		}
		if($this->request->is(array('post','put'))){
			//pr($this->request->data);die;
			$data['Diagnosis'] = $this->request->data['Diagnosi'];
			$data['type'] = $this->request->data['type'];
			if($this->Diagnosis->save($data)){
				$this->Session->setFlash("Diagnosis has been created/updated successfully.",'message',array('class' => 'message'));
				$this->redirect(array('controller' => 'diagnosis', 'action' => 'index'));
			} else  {
				$this->Session->setFlash('Some error found.Please try again.','message',array('class' => 'message'));
			}
		}
		else{
			$this->request->data = $editData;
		}
		
		$user_type=$this->Auth->user('user_type');
		
		
		if($user_type!='Admin'){
			$conditions['Diagnosis.status'] = 1;
			$conditions['Diagnosis.is_delete'] = 0;
		}else{
			$conditions['Diagnosis.is_delete'] = 0;
		}
		if(!empty($this->request->query['search'])){
			$search = strtolower(trim($this->request->query['search'])); 
			$conditions['OR'][] = array('Lower(Diagnosis.name) like'=> '%'.$search.'%');
			 
			$this->set(compact('search'));
		} 
		
		$params = array(
			'conditions' => $conditions,
			'limit'=>10,
			'order'=>array('Diagnosis.id'=>'DESC')
		);
		
		$this->paginate=array('Diagnosis'=>$params);
		$datas = $this->paginate('Diagnosis');
		$this->set(compact('datas'));
	 
		
	}
	
	/*Diagnosis delete*/
	function admin_delete($id = null){
		if(!empty($id)){
			$result = $this->Diagnosis->query("update mmd_diagnosis set is_delete='1' where id=".$id);
			  
			$this->Session->setFlash("Diagnosis deleted successfully.",'message',array('class' => 'message'));	
		}else{
			$this->Session->setFlash("Unable to delete.",'message',array('class' => 'message'));
		}
		$this->redirect(array('controller' => 'diagnosis', 'action' => 'index'));
	}
	
	
	/*This function for changing status of Diagnosis*/
	public function admin_changeStatus($id=null) {
		$this->layout=false;
		$data=$this->Diagnosis->find('first',array('conditions'=>array('Diagnosis.id'=>$id)));
		if($data['Diagnosis']['status']==1) {
			$this->Diagnosis->id = $id;
			$this->Diagnosis->saveField('status',0);	
		} else {
			$this->Diagnosis->id = $id;
			$this->Diagnosis->saveField('status',1);	
		}
		$this->Session->setFlash('Diagnosis status has been updated successfully.','message',array('class' => 'message'));
		$this->redirect(array('controller'=>'diagnosis','action'=>'admin_index'));
	}
}

?>