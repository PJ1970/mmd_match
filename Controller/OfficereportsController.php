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
class OfficereportsController extends AppController {
	public $uses = array('Admin','User', 'Module', 'AssignModule','AssignCoach','Office','Patient');
			
	var $helpers = array('Html', 'Form','Js' => array('Jquery'), 'Custom');

    public $components = array('Auth'=>array('authorize'=>array('Controller')),'Paginator','Session','Email','Common','RememberMe');
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
	    parent::beforeFilter();
		if(isset($user['user_type']) && (($user['user_type'] == 'Admin') || ($user['user_type'] == 'Subadmin') || ($user['user_type'] == 'Staffuser')) && isset($this->request->prefix) && ($this->request->prefix == 'admin')){
			return true;
		}else{
			$this->redirect($this->referer());
		}
    }
	//This function for listing of offices
	public function admin_index(){ 
				
		$this->loadModel('Officereports');
		$this->loadModel('Office');
		$datas = $this->Office->find('all');
		if($this->request->is(array('post','put'))){
			#pr($this->request->data);die;
			if ($this->Office->save($this->request->data)) {
				$this->Session->setFlash("Updated successfully.",'message',array('class' => 'message'));
				$this->redirect($this->referer());
			}
		} else {
			#$this->Flash->error('Updation failed');
		}
		$this->set(compact('datas'));
		
	}
}

?>