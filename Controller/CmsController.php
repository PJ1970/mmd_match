<?php

App::uses('AppController','Controller');
App::uses('CakeEmail','Network/Email');
App::import('Controller','ChatApi');

class CmsController extends AppController {
	public $uses = array('Admin','User','Patient','Office', 'NewUserDevice', 'Cms');
			
	var $helpers = array('Html', 'Form','Js' => array('Jquery'), 'Custom', 'Dropdown');

    public $components = array('Auth'=>array('authorize'=>array('Controller')),'Session','Email','Common','RememberMe');
	public $allowedActions =array();
    
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->deny();
		$this->Auth->allow($this->allowedActions);	
		if ($this->request->is('ajax')){
			$this->layout = '';
		}
	}
	public function admin_index(){  
		if($this->Session->read('Auth.Admin.user_type') == 'Admin'){
		$conditions = array('Cms.is_delete' =>'0');
		if(!empty($this->request->query['search'])){
			//echo "yes";die;
			$search = strtolower(trim($this->request->query['search']));
			$conditions['OR'][] = array('Lower(Cms.page_name) like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(Cms.title) like'=> '%'.$search.'%');
			$this->set(compact('search'));
		}
		
		$params = array(
			'conditions' => $conditions,
			'limit'=>10,
			'order'=>array('Cms.id'=>'DESC')
		);
		
		$this->paginate=array('Cms'=>$params);
		$datas = $this->paginate('Cms');
		$this->set(compact('datas'));
		//pr($datas); die;
		}else{
			$this->redirect(WWW_BASE.'admin/dashboards/index');
		}
		 
	} 
	function admin_edit($id = null) {
		if($this->Session->read('Auth.Admin.user_type') == 'Admin'){
		$this->loadModel('AppConstant');
		$data = $this->Cms->find('first',array('conditions'=>array('Cms.id'=>$id)));
		$this->set(compact('data'));
		if (!$id && emptyempty($this->request->data)) {  
			$this->Session->setFlash('Invalid Cms');  
			$this->redirect(array('action' => 'index'));  
		}  
		if (!empty($this->request->data)) {  
			if ($this->Cms->save($this->request->data)) {  
				$this->Session->setFlash('The cms has been updated.','message',array('class' => 'message'));
				$this->redirect(array('action' => 'index'));  
			} else {  
				$this->Session->setFlash('The cms has nat been updated. Please, try again.','message',array('class' => 'message'));  
			}  
		}  
		if (empty($this->request->data)) {  
			$this->request->data = $data;  
			//pr($this->request->data); die;
		}
		}else{
			$this->redirect(WWW_BASE.'admin/dashboards/index');
		}  
	} 
}
?>