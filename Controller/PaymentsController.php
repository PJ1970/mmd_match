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
class PaymentsController extends AppController {
	public $uses = array('Admin','User', 'Module', 'AssignModule','AssignCoach','Office','Patient','Test','Payment');
			
	var $helpers = array('Html', 'Form','Js' => array('Jquery'), 'Custom');

    public $components = array('Auth'=>array('authorize'=>array('Controller')),'Session','Email','Common','RememberMe','Paypal');
	public $allowedActions =array('admin_forgot_password','admin_login','admin_logout','admin_updatePayment','admin_cronAmount');
    
	
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
	function admin_purchase_credit($id = null){
		$Admin = $this->Session->read('Auth.Admin');
		$this->loadModel('Office');
		$this->loadModel('User');
		$dataOffice =  $this->Office->findById($Admin['office_id']);
		//pr($dataOffice);die;
		$user_data =  $this->User->findById(1);
		$data = $dataOffice['Office'];
		$data['paypal_id'] = $user_data['User']['paypal_id'];
		$data['options'] = array('50'=>'50','100'=>'100','500'=>'500');
		//echo "<pre>";
		//print_r($data);die;
		$this->set('data',$data);
		
	}
	function admin_index($id = null){
        if(!empty($this->request->data)){
			$this->request->data = @$this->request->data['Office'];
			pr($this->request->data);die;
			if(/* !empty($this->request->data['paypal_id']) && */ !empty($this->request->data['credits'])  && !empty($this->request->data['amount']) && $this->request->data['amount']>0 ){
				
				$get_session_id=$this->Session->read('Auth.Admin.id');
				$get_user_data = $this->User->findById($get_session_id);
				//pr($get_user_data['Office']['credits']);die;
				if($get_user_data['Office']['credits']>$this->request->data['amount']){
					$this->detuctAmountFormCreditCard($this->request->data,$get_user_data);
				}else{
					$this->processPaypalPayment($this->request->data);
				}
				
			}else{
				$this->Session->setFlash(__('Something went wrong, Please try again.'),'message');
				return $this->redirect(array('controller'=>'dashboards','action'=>'index'));
			}
			
			
		}
		
	}
	private function processPaypalPayment($data){
		//pr(WWW_BASE.'admin/payments/updatePayment');die;
		//Log::write('info', 'PAYPAL REQUEST ',['scope'=>['stripe']]);
		//Log::write('info', $data->toArray(),['scope'=>['stripe']]);
		try{
			$this->Paypal->paypal_url = /* 'https://www.paypal.com/cgi-bin/webscr' */'https://www.sandbox.paypal.com/cgi-bin/webscr';
			//$this->Paypal->add_field('cmd', '_cart');
			$this->Paypal->add_field('cmd', '_xclick-subscriptions'); // subscription 
			
			$this->Paypal->add_field('no_note ', '1'); //RequiredDo not prompt buyers to include a note with their payments. Valid value is from Subscribe buttons:
			
			$this->Paypal->add_field('upload', '1');
			$this->Paypal->add_field('business','abhinav-business@braintechnosys.com' /* $data['paypal_id'] */);
			$this->Paypal->add_field('item_name_1','Credits');
			$this->Paypal->add_field('custom',$this->Session->read('Auth.Admin.id'));
			$this->Paypal->add_field('amount_1', $data['amount']);
			$this->Paypal->add_field('a3',$data['amount']); // Regular Subscription Price
			$this->Paypal->add_field('p3',"1"); //Subscription duration
			$this->Paypal->add_field('t3',"M"); //Regular subscription units of duration.(D, W ,M ,Y )
			/* Set recurring payments until canceled..*/
			$this->Paypal->add_field('src','1'); //subscription payments recur(1)/subscription payments not recur(0)
			
			$this->Paypal->add_field('currency_code', 'USD'); 
			$this->Paypal->add_field('return', Router::url(['controller' => 'dashboards', 'action' => 'index','Thanks for purchasing credits.'], true));
			$this->Paypal->add_field('cancel_return', Router::url(['controller' => 'payments', 'action' => 'purchase_credit',''], true));
			$this->Paypal->add_field('notify_url',Router::url(['controller' => 'payments', 'action' => 'updatePayment','?cdt='.$data['credits']], true), true);
			$this->Paypal->submit_paypal_post();
		} catch(Exception $e) {
			return $e->getMessage();
		}
		
	}
	
	public function admin_updatePayment(){
		//$this->viewBuilder()->layout('ajax');
		$this->render = false;
		//CakeLog::write('info', 'PAYPAL RESPONSE');
		//CakeLog::write('info', json_encode($this->request->data));
		//mail('akash@braintechnosys.com','mesgagds',json_encode($this->request->data));				
		if(!empty($this->request->data)){
			$this->loadModel('User');
			$user_data =  $this->User->findById($this->request->data['custom']);
			$paymentStatus =  $this->getPaymentStatus($this->request->data['payment_status']);
			$record['Payment']['payment_status'] = $paymentStatus; 
			$record['Payment']['paid_amount'] = $this->request->data['payment_gross'];
			$record['Payment']['txn_id'] = $this->request->data['txn_id'];
			$record['Payment']['payment_type'] = 'Paypal';
			$record['Payment']['payment_date'] = date('Y-m-d H:i:s');
			$today_date = date('Y-m-d h:i:s');
			$record['Payment']['expiry_date'] = date('Y-m-d h:i:s', strtotime($today_date . " + 30 day"));
			$record['Payment']['admin_paypal_id'] = /* $user_data['User']['paypal_id'] */'abhinav-business@braintechnosys.com';
			$record['Payment']['user_id'] = $this->request->data['custom'];
			$this->loadModel('Payment');
			//mail('gaurav@braintechnosys.biz','mesgagds',json_encode($record));	
			//mail('gaurav@braintechnosys.biz','user',json_encode($user_data));	
			//mail('gaurav@braintechnosys.biz','credits',json_encode($this->request->query['cdt']));	
			if($this->Payment->save($record)){
				//if($record['Payment']['payment_status'] =='Success'){
					$this->loadModel('Office');
					$dataOffice =  $this->Office->findById($user_data['User']['office_id']);
					$update_data['Office']['credits'] = $dataOffice['Office']['credits'] + ($record['Payment']['paid_amount'] / $dataOffice['Office']['per_use_cost']);
					$update_data['Office']['id'] = $user_data['User']['office_id'];
					//mail('gaurav@braintechnosys.biz','office',json_encode($dataOffice));
					//mail('gaurav@braintechnosys.biz','officedatabase',json_encode($update_data));
					if(!empty($update_data['Office']['id'])){
						$this->Office->save($update_data);
					}
				//}
			}
		}
		exit();
	}
	private function getPaymentStatus($paymentStatus=null){
		switch ($paymentStatus) {
			case 'Completed':
				return 'Success';
				break;
			default:
				return 'Pending';
				break;
		}
	}

	private function detuctAmountFormCreditCard($data,$get_user_data){
		//pr($get_user_data);die;
		$record['Payment']['payment_status'] = 'Success'; 
		$record['Payment']['paid_amount'] = $data['amount'];
		$record['Payment']['txn_id'] = '';
		$record['Payment']['payment_type'] = 'Account Credits';
		$record['Payment']['payment_date'] = date('Y-m-d H:i:s');
		$today_date = date('Y-m-d h:i:s');
		$record['Payment']['expiry_date'] = date('Y-m-d h:i:s', strtotime($today_date . " + 30 day"));
		$record['Payment']['admin_paypal_id'] = '';
		$record['Payment']['user_id'] = $get_user_data['User']['id'];
		$this->loadModel('Payment');
		if($this->Payment->save($record)){
			$this->loadModel('Office');
			$dataOffice =  $this->Office->findById($get_user_data['User']['id']);
			$update_data['Office']['credits'] = $get_user_data['Office']['credits'] - $data['amount'];
			$update_data['Office']['id'] = $get_user_data['User']['office_id'];
			if(!empty($update_data['Office']['id'])){
				$this->Office->save($update_data);
				$this->Session->setFlash("Payment paid successfully .",'message',array('class' => 'message'));
				$this->redirect(array('controller'=>'dashboards','action'=>'admin_index'));
			}
		}
	}
	
	// cron credit detuct amount 
	public function admin_cronAmount(){
		$this->loadModel('User');
		$this->loadModel('Office');
		$this->loadModel('Payment');
		$user = $this->User->find('all',['conditions'=>['User.user_type'=>'Subadmin', 'User.id'=>632]]);
		$abc=0;
		//pr($user);die;
		
		foreach($user as $val){
			$abc++;
			if($val['Office']['payable']=='yes' && $val['Office']['credits']>0 && $val['Office']['is_delete']==0){
				$get_payment = $this->Payment->find('first',[
										'conditions'=>['Payment.user_id'=>$val['User']['id']],
										'order'=>['Payment.id'=>'desc']
									]);
				//echo date("Y-m-d",strtotime("-1 month")); die;
				$modifiedDate = date('Y-m-d', strtotime($get_payment['Payment']['modified']));
				if(!empty($get_payment) && $modifiedDate==date("Y-m-d",strtotime("-1 month"))){
					//die('Hiii1');
					$record['Payment']['id'] = $get_payment['Payment']['id'];
					$record['Payment']['modified'] = date('Y-m-d H:i:s');
					
					//pr($val);die;
					if($this->Payment->save($record)){
						$update_data['Office']['credits'] = $val['Office']['credits'] - $val['Office']['monthly_package'];
						$update_data['Office']['id'] = $val['Office']['id'];
						if(!empty($update_data['Office']['id'])){
							$this->Office->save($update_data);
							echo 'Cron works fine';
						}
					}
				}
			}
		}
	}
	
}

?>