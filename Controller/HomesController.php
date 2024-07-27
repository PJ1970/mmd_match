<?php

App::uses('AppController','Controller');
App::uses('CakeEmail','Network/Email');
App::import('Controller','ChatApi');
class HomesController extends AppController {
	public $uses = array('TestDevice', 'DevicePreference', 'Cms');
			
	var $helpers = array('Html', 'Form','Js' => array('Jquery'), 'Custom');

    public $components = array('Auth'=>array('authorize'=>array('Controller')),'Session','Email','Common','RememberMe');
	public $allowedActions =array('index');
    
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->deny();
		$this->Auth->allow($this->allowedActions);	
	}
	public function index($cmspagename=null){
		$this->layout = 'default';
		//echo $cmspagename; die;
		$cmsData = $this->Cms->find('first', array('conditions' => array('Cms.page_name LIKE'=> '%'.$cmspagename.'%'), 'fields' => array('Cms.page_name','Cms.title', 'Cms.content')));
		$title = $cmsData['Cms']['title'];
		$this->set(compact(['cmsData', 'title']));
		//pr($cmsData); die;
	}
}
?>