<?php

App::uses('Controller','Controller');
App::uses('BlowfishPasswordHasher','Controller/Component/Auth');

ini_set('upload_max_filesize','512M');
ini_set('memory_limit', '-1');
class AppController extends Controller {
	public $components = array('Acl','Auth'=>array('authorize' => array('Controller')),'Cookie','Session','RememberMe');
	public $uses = array('User','Admin','AppConstant', 'Officereport','PushNotificationLog','Office');
    var $helpers = array('Html','Form');
	public $BlowFish;

	public function beforeRender(){
		$user = $this->Session->read('Auth.User');
		$admin_id = $this->Session->read('Auth.Admin');
		$this->set('check_login',$admin_id);
		if(!empty($admin_id) || !empty($user)){
			$currentUrl = $this->getCompleteUrl();
			$this->loadModel('Office');
			$officesList = $this->Office->find('list',array('fields' => array('id','name'),'conditions'=> array('Office.is_delete' => '0'),'order'=>array('Office.name' =>'asc')));
			$officeReport = $this->Officereport->find('all', array('conditions'=> array('Officereport.office_id' => $admin_id['Office']['id']),'order'=>array('Officereport.office_report' =>'asc')));
			$activeSideMenu=[];
			//pr($officeReport); die;
			foreach($officeReport as $key=>$value){
				$activeSideMenu[$value['Tests']['id']]=trim($value['Tests']['name']);
			}
			$this->set(compact('officesList', 'activeSideMenu'));
		}
		
	}

	/*  public function forceSSL() {
		if(preg_match('/www/', env('SERVER_NAME')) == 0){
			return $this->redirect('https://www.' . env('SERVER_NAME') . $this->here);
		} else{
			return $this->redirect('https://' . env('SERVER_NAME') . $this->here);
		}
	} */

	function beforeFilter()
	{
		//echo $_SERVER['SERVER_NAME']; die;
		/* if(preg_match('/https/', env('SERVER_NAME')) == 0 && preg_match('/www/', env('SERVER_NAME')) == 0){
			return $this->redirect('https://www.' . env('SERVER_NAME') . $this->here);
		}  */
		/* $this->Security->csrfCheck=false;
        $this->Security->validatePost = false;
		$this->Security->blackHoleCallback = 'forceSSL';
        $this->Security->requireSecure();  */

		$this->setConstants();

		if(!empty($this->params->query)){
			if(isset($this->params->query['lang']) && $this->params->query['lang'] == 'kor'){
				//$this->Session->write('Config.language', 'kor');
				Configure::write('Config.language', 'kor');
			}
		}
		else{
			//$this->Session->write('Config.language', 'eng');
			Configure::write('Config.language', 'eng');
		}

		if(empty($this->Session->check('Config.language'))){
			Configure::write('Config.language', 'eng');
		}	

		$this->BlowFish = new BlowfishPasswordHasher();

		if(empty($this->request->prefix)){
			$this->layout = 'front';
			$this->Auth->allow();
		}else{
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
				// pr($this->Session->read());die;
				if(!empty($this->Session->read('Auth.Admin'))){
					$this->Session->write('profile_pic',$this->Session->read('Auth.Admin.profile_pic'));
					$this->Session->write('username',$this->Session->read('Auth.Admin.first_name'));
					$this->Session->write('user_id',$this->Session->read('Auth.Admin.id'));
					$this->Session->write('office_name',$this->Session->read('Auth.Admin.Office.name'));
				}
				
	            if(!$this->Auth->loggedIn()){
					$this->Auth->authError = false;
					$this->layout = 'admin_default';
				}else{
					$this->layout = 'admin';

				}
				$this->Auth->allow('admin_login');
			}
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
	public function sendPushNotificationNew($registration_ids, $message,$type='',$ip_address) {
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

	public function sendPushNotificationNewV2($registration_ids, $message,$type='',$ip_address) {
		/* url for push notification		http://stackoverflow.com/questions/25859898/sending-push-notifications-to-multiple-android-devices-using-gcm
		*/
		$url = 'https://fcm.googleapis.com/fcm/send';
		$fields = array(
				'to' => $registration_ids,
				'data' => array('title' => trim($message), 'body' =>$ip_address,'icon'=> 'appicon')

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
				'notification' => array('title' => 'Updated Information on VF2000 portal', 'body' =>'Sync your VF2000 app','icon'=> '')

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

	public function sendPushNotificationNewAdminDataUpdateV2($registration_ids) {
		/* url for push notification		http://stackoverflow.com/questions/25859898/sending-push-notifications-to-multiple-android-devices-using-gcm
		*/

		$url = 'https://fcm.googleapis.com/fcm/send';
		$fields = array(
				'to' => $registration_ids,
				'data' => array('title' => 'Updated Information on VF2000 portal', 'body' =>'Sync your VF2000 app','icon'=> '')

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
	public function sendPushNotificationtestStatus($registration_ids,$message) {
		/* url for push notification		http://stackoverflow.com/questions/25859898/sending-push-notifications-to-multiple-android-devices-using-gcm
		*/

		$url = 'https://fcm.googleapis.com/fcm/send';
		$fields = array(
				'to' => $registration_ids,
				'data' => array('title' => 'Test Status', 'body' =>$message,'icon'=> '')

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

	public function update_master_app_constant($id){
		//echo 'jhg';pr($id);die;
		$this->loadModel('AppConstant');
		$save_data['AppConstant']['id']=1;
		$save_data['AppConstant']['master_updated']=$id;
		if($this->AppConstant->save($save_data)){
			/* $get_staff_id=$this->Masterdata->find('first',array(
				'conditions'=>array('Masterdata.id'=>$id),
				'fields'=>array('Masterdata.staff_id')
			));
			$get_staff_detail=$this->User->find('first',array(
				'conditions'=>array('User.id'=>$get_staff_id)
			)); */

			//  send notification
			/*$this->loadModel('NewUserDevice');
			$record=$this->NewUserDevice->find('all');
			foreach($record as $key => $rec){
				//pr($rec);die;
				$device_token = $rec['NewUserDevice']['device_token'];
				#$ip_address = $rec['NewUserDevice']['ip_address'];
				$ip_address = 2;
				$message = 'new master record';
				$type = 'master_update';
				$get_result = $this->sendPushNotificationNew1($device_token,$message,@$type,@$ip_address);
			}*/
			/* $device_token = 'cYM6aXwXtbQ:APA91bEr4NSDgqWPYAaPswZ757ldfU8AVyRzBStuyzEXRSvxD4rf8yD_GOg95WJsej5D7sbyPMu1SYAHVOojSr5HwN1TJRHd30Q6tE439znnuldUAzOGgWRLM7yZZX2kZEIk8_pvj1cR';
			$message = 'Master data has been updated.';
			$type = 'master_update';
			$ip_address = '192.168.0.31'; */
		}
	}
	public function notify_master_app_constant(){
			$this->loadModel('NewUserDevice');
			$record=$this->NewUserDevice->find('all');

			foreach($record as $key => $rec){
				$device_token = $rec['NewUserDevice']['device_token'];
				$ip_address = 2;
				$message = 'new master record';
				$type = 'master_update';
				$get_result = $this->sendPushNotificationNew1($device_token,$message,@$type,@$ip_address);
			}
	}
	  public function checkNotification_status($id)
    {
        $count = $this->Office->find('count', array(
        'conditions' => array('Office.id' => $id, 'Office.last_notification ' => date('Y-m-d')),
        ));

         if($count>0){
             return 0;
        }else if($id==null){
             return 0;
        }else{
             $data['Office']['id']=$id;
             $data['Office']['last_notification']=date('Y-m-d');
             $this->Office->create();
             $result_p = $this->Office->save($data);
             return 1;
        }
    }
    public function checkNotification($id)
    {

        $this->PushNotificationLog->primaryKey = 'device_id';
        $count = $this->PushNotificationLog->find('count', array(
        'conditions' => array('PushNotificationLog.device_id' => $id, 'PushNotificationLog.created ' => date('Y-m-d')),
        ));
         if($count>0){
             return 0;
        }else{
             $data['PushNotificationLog']['device_id']=$id;
             $data['PushNotificationLog']['created']=date('Y-m-d');
             $this->PushNotificationLog->create();
             $result_p = $this->PushNotificationLog->save($data);
             return 1;
        }
    }
}

?>
