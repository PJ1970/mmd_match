<?php

App::uses('AppController','Controller');
App::uses('CakeEmail','Network/Email');
App::import('Controller','ChatApi');
class TermsConditionsController extends AppController {
	public $uses = array('TestDevice', 'DevicePreference', 'Cms');
			
	var $helpers = array('Html', 'Form','Js' => array('Jquery'), 'Custom');

    public $components = array('Auth'=>array('authorize'=>array('Controller')),'Session','Email','Common','RememberMe');
	public $allowedActions =array('index');
    
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->deny();
		$this->Auth->allow($this->allowedActions);	
	}
	public function index(){
		$this->layout = 'default';
		$termsAndConditions = $this->Cms->find('first', array('conditions' => array('Cms.page_name LIKE'=> '%terms-conditions%'), 'fields' => array('Cms.page_name','Cms.title', 'Cms.content')));
		$title = "Terms & Conditions";
		$this->set(compact(['termsAndConditions', 'title']));
		//pr($termsAndConditions); die;
	}
}
?>