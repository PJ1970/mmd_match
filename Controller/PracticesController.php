<?php
App::uses('AppController','Controller');

class PracticesController extends AppController {
	public $uses = array('Admin','User','Practices');
			
	var $helpers = array('Html', 'Form','Js' => array('Jquery'), 'Custom');

    public $components = array('Auth'=>array('authorize'=>array('Controller')),'Session','Email','Common','RememberMe');
	public $allowedActions =array();
    
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->deny();
		$this->Auth->allow($this->allowedActions);	
	}
	public function isAuthorized($user){ 
	    parent::beforeFilter();
		if(isset($user['user_type']) && (($user['user_type'] == 'Admin'))){
			return true;
		}else{
			$this->redirect($this->referer());
		}
    }
	
	
	/*This function for practice list*/
	public function admin_practice_list() {
		$datas = $this->Practices->find('all');
		$this->set(compact('datas'));
	}
	
	/*This function edit practice*/
	public function admin_edit($id=null) {
		$datas = $this->Practices->find('first',array('conditions'=>array('Practices.id'=>$id)));
		$this->set(compact('datas'));
		if($this->request->is(array('post','put'))) {
			if($this->Practices->save($this->request->data)) {
				$this->Session->setFlash("New Practice has been updated successfully.",'message',array('class' => 'message'));
				$this->redirect(array('controller'=>'practices','action'=>'admin_practice_list'));
			}
		} else {
			$this->request->data = $datas;
		}
	}
	
	/*This function for delete practice*/
	public function admin_delete($id=null) {
		$this->autoRender = false;
		if($id){
			if($this->Practices->delete($id)){
				$this->Session->setFlash('Practice has been deleted successfully.','message',array('class' => 'message'));
				$this->redirect(array('controller'=>'practices','action'=>'admin_practice_list'));
			}else{
				$this->Session->setFlash('Oops! There is some problem in deleting practice.','message',array('class' => 'message'));
				$this->redirect(array('controller'=>'practices','action'=>'admin_practice_list'));
			}
		}
	}
	
	/*Add practice*/
	public function admin_addPractice() {
		if($this->request->is('post')) {
			if($this->Practices->save($this->request->data)) {
				$this->Session->setFlash("New Practice has been created successfully.",'message',array('class' => 'message'));
				$this->redirect(array('controller'=>'practices','action'=>'admin_practice_list'));
			}
		}
	}
	
	/*Practive view*/
	public function admin_view($id=null) {
		$this->layout=false;
		$data = $this->Practices->find('first',array('conditions'=>array('Practices.id'=>$id)));
		$this->set(compact('data'));
	}
}

?>