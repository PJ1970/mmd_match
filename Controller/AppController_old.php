<?php

App::uses('Controller','Controller');
App::uses('BlowfishPasswordHasher','Controller/Component/Auth');

ini_set('upload_max_filesize','50M');
class AppController extends Controller {
	public $components = array('Acl','Auth'=>array('authorize' => array('Controller')),'Cookie','Session','RememberMe');
	public $uses = array('User','Admin','AppConstant');
    var $helpers = array('Html','Form'); 
	public $BlowFish;
	
	public function beforeRender(){
		$user = $this->Session->read('Auth.User');
		$admin_id = $this->Session->read('Auth.Admin');
		$this->set('check_login',$admin_id);
		$currentUrl = $this->getCompleteUrl();
		$this->loadModel('Office');
		$officesList = $this->Office->find('list',array('fields' => array('id','name'),'conditions'=> array('Office.is_delete' => '0'),'order'=>array('Office.name' =>'asc')));
		$this->set(compact('officesList'));
	}
	
	function beforeFilter()
	{   
		$this->setConstants();
		
		if(isset($this->params->query['lang']) && $this->params->query['lang'] == 'kor'){
				$this->Session->write('Config.language', 'kor');	
		} elseif((isset($this->params->query['lang']) && $this->params->query['lang'] == 'eng') || !($this->Session->check('Config.language'))) {
				$this->Session->write('Config.language', 'eng');
		}
		
	    if($this->Session->check('Config.language')){
            Configure::write('Config.language', $this->Session->read('Config.language'));
        }
		
		$this->BlowFish = new BlowfishPasswordHasher();
		if($this->request->prefix=="admin"){
			AuthComponent::$sessionKey = 'Auth.Admin';
			$this->Auth->loginAction = array('controller' => 'users','action'=> 'login');
			$this->Auth->loginRedirect = array('controller' => 'dashboards','action'=> 'index');
			$this->Auth->logoutRedirect = array('controller' => 'users','action'=> 'login');
			$this->Auth->authenticate = array(
				'Form' => array(				
					'userModel' => 'User',
					'passwordHasher' => 'Blowfish',
					'fields'=> array(
						'username' => 'username',
						'password' => 'password'
					)/* ,
                    'scope'=>array('User.user_type' => 'Admin') */
				)
			);
			$this->RememberMe->restoreLoginFromCookie();
			//pr($this->Session->read());die;
			$this->Session->write('profile_pic',$this->Session->read('Auth.Admin.profile_pic'));
			$this->Session->write('username',$this->Session->read('Auth.Admin.first_name'));
			$this->Session->write('user_id',$this->Session->read('Auth.Admin.id'));
			$this->Session->write('office_name',$this->Session->read('Auth.Admin.Office.name'));
            if(!$this->Auth->loggedIn()){
				$this->Auth->authError = false;
				$this->layout = 'admin_default';
			}else{
				$this->layout = 'admin';
				
			}
			
			
			//$this->Auth->allow('admin_login');
		}
		elseif($this->request->prefix==""){
			$this->layout = 'front';
			$this->Auth->allow();
            			
		}
	}
	
	public function isAuthorized($user) {
		if(($this->Auth->loggedIn())){
			return true;
		}
		return false;
	}
	function getCompleteUrl(){
		 $pageURL = 'http';
		 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
		 return $pageURL;
	}
	
	function check_user_type(){
		if($this->Auth->loggedIn()  && $this->Session->check('Auth.User')){
			//pr($this->Session->read('Auth.User'));
			$usertype = $this->Session->read('Auth.User.user_type');
			if($usertype == 0){
				$this->check_type = 1;
				$this->set('checkusertype',true);
				return true;
			}			
		}
		return false;
	}
	
	// define dynamic constants
	public function setConstants(){
		$constant = $this->AppConstant->findById(1);
		unset($constant['AppConstant']['id']);
		if(!defined('NOTIFICATION_SERVER_KEY')){define('NOTIFICATION_SERVER_KEY',$constant['AppConstant']['NOTIFICATION_SERVER_KEY']);}
		if(!defined('NOTIFICATION_SERVER_KEY_NEW')){define('NOTIFICATION_SERVER_KEY_NEW',$constant['AppConstant']['NOTIFICATION_SERVER_KEY_NEW']);}
		if(!defined('SIGNATURE')){define('SIGNATURE',"MMD");}
		
	}
	
	public function sendPushNotification($registration_ids, $message,$type='') {
		/* url for push notification		http://stackoverflow.com/questions/25859898/sending-push-notifications-to-multiple-android-devices-using-gcm
		*/
		
		$url = 'https://fcm.googleapis.com/fcm/send';    
		$fields = array(
				'to' => $registration_ids,
				'notification' => array('title' => SIGNATURE, 'body' =>$message,'icon'=> 'appicon'),
				'data' => array('message' => $message)
		);
		
		$headers = array(
			'Authorization:key=' . NOTIFICATION_SERVER_KEY,
			'Content-Type: application/json'
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, false);		
		$result = curl_exec($ch);
		if($result === false)
			die('Curl failed ' . curl_error());
		curl_close($ch);
		//$data_arrays=json_decode($result,true);
		//pr($data_arrays);die;
		return 1;
	}
	public function sendPushNotificationNEw($registration_ids, $message,$type='',$ip_address) {
		/* url for push notification		http://stackoverflow.com/questions/25859898/sending-push-notifications-to-multiple-android-devices-using-gcm
		*/
		
		$url = 'https://fcm.googleapis.com/fcm/send';    
		$fields = array(
				'to' => $registration_ids,
				'data' => array('title' => SIGNATURE, 'body' =>$message,'icon'=> 'appicon','message' => $message,'type' => @$type,'ip_address' => @$ip_address)
				 
		);
		
		$headers = array(
			'Authorization:key=' . NOTIFICATION_SERVER_KEY,
			'Content-Type: application/json'
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, false);		
		$result = curl_exec($ch);
		if($result === false)
			die('Curl failed ' . curl_error());
		curl_close($ch);
		//$data_arrays=json_decode($result,true);
		//pr($data_arrays);die;
		return $ip_address;
	}
	
	public function sendPushNotificationNew1($registration_ids, $message,$type='',$ip_address) {
		/* url for push notification		http://stackoverflow.com/questions/25859898/sending-push-notifications-to-multiple-android-devices-using-gcm
		*/
		
		$url = 'https://fcm.googleapis.com/fcm/send';    
		$fields = array(
				'to' => $registration_ids,
				'notification' => array('title' => trim($message), 'body' =>$ip_address,'icon'=> 'appicon')
				 
		);
		$headers = array(
			'Authorization:key=' . NOTIFICATION_SERVER_KEY_NEW,
			'Content-Type: application/json'
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);		 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, false);		
		$result = curl_exec($ch);
		if($result === false)
			die('Curl failed ' . curl_error());
		curl_close($ch);
		//$data_arrays=json_decode($result,true);
		//pr($data_arrays);die;
		return $ip_address;
	}
	
	public function sendPushNotificationNewAdminDataUpdate($registration_ids) {
		/* url for push notification		http://stackoverflow.com/questions/25859898/sending-push-notifications-to-multiple-android-devices-using-gcm
		*/
		
		$url = 'https://fcm.googleapis.com/fcm/send';    
		$fields = array(
				'to' => $registration_ids,
				'notification' => array('title' => '-1', 'body' =>'1','icon'=> '')
				 
		);
		$headers = array(
			'Authorization:key=' . NOTIFICATION_SERVER_KEY_NEW,
			'Content-Type: application/json'
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);		 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, false);		
		$result = curl_exec($ch);
		if($result === false)
			die('Curl failed ' . curl_error());
		curl_close($ch);
		//$data_arrays=json_decode($result,true);
		//pr($data_arrays);die;
		return 1;
	}
	
	function generateRandomString($length = null) {
		if(empty($length)){
			$length = 10;
		}
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}