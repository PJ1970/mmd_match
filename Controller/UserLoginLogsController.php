<?php
App::uses('AppController','Controller');
App::uses('CakeEmail','Network/Email');
App::import('Controller','ChatApi');

class UserLoginLogsController extends AppController {
	public $uses = array('Admin','User','Patient');
			
	var $helpers = array('Html', 'Form','Js' => array('Jquery'), 'Custom');

    public $components = array('Auth'=>array('authorize'=>array('Controller')),'Session','Email','Common','RememberMe','Upload');
	public $allowedActions =array();
    
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->deny();
		$this->Auth->allow($this->allowedActions);	
	}
	public function isAuthorized($user){ 
	    parent::beforeFilter();
		if($user['user_type'] == 'Staffuser' && $this->params['action']=='admin_staffView'){
			return true;
		}
		if(isset($user['user_type']) && (($user['user_type'] == 'Admin') || ($user['user_type'] == 'Subadmin') && isset($this->request->prefix) && ($this->request->prefix == 'admin'))){
			return true;
		}else{
			$this->redirect($this->referer());
		}
    }
	
	
	 
	   
	
	/*Staff listing*/
	public function admin_index() { 
		$this->loadModel('UserLoginLog');
		$Admin = $this->Session->read('Auth.Admin');
		if($Admin['user_type'] == 'Admin') {
			$conditions = array();
		}
		if($Admin['user_type'] == 'Subadmin') {	
			$conditions = array('UserLoginLog.office_id'=>$Admin['office_id']);
		}
		$office_id ='';
	   if(!empty($this->request->query['office_id'])){
			$office_id =$conditions[] = array('UserLoginLog.office_id' =>$this->request->query['office_id']);
			
		}
		$this->set(compact('office_id'));
		if(!empty($this->request->query['search'])){
			//echo "yes";die;
			$search = strtolower(trim($this->request->query['search']));
			$conditions['OR'][] = array('UserLoginLog.ip_address like'=> '%'.$search.'%');
			$conditions['OR'][] = array('UserLoginLog.username like'=> '%'.$search.'%');
			$conditions['OR'][] = array('UserLoginLog.action like'=> '%'.$search.'%');
			$conditions['OR'][] = array('UserLoginLog.source like'=> '%'.$search.'%');
			$this->set(compact('search'));
		}
		$this->paginate=array('conditions'=>$conditions,
		'limit'=>10,
		'order'=>array('UserLoginLog.id'=>'DESC')); 
		$datas=$this->paginate('UserLoginLog');
		$this->set(compact('datas')); 
	}
	
   
}

?>