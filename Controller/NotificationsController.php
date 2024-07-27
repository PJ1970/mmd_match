<?php
class NotificationsController extends AppController
{ 
	public $uses = array();
	var $helpers = array('Html', 'Form','Js' => array('Jquery'));
    public $components = array('Email','RequestHandler','Common');
	public $allowedActions =array();
	
	
	public function beforeFilter(){
        parent::beforeFilter();
		//$this->Auth->allow();
	}
	
	public function beforeRender(){
        parent::beforeRender();
    }
	
	// code for notification list
	public function admin_notification_list($id=null) {
		$this->loadModel('UserNotification');
		$Admin = $this->Auth->user();
		if(!empty($Admin) && $Admin['user_type'] == "Admin"){
			 $user_notifications = $this->UserNotification->find('all',array('order'=>array('UserNotification.id DESC'),'limit'=>4));
			//echo "<pre/>";print_r($user_notifications);die;
		} else if(!empty($Admin) && $Admin['user_type'] == "Subadmin"){
			
			$staffuser = $this->User->find('list',array('conditions'=>array('User.user_type'=>'Staffuser','User.office_id'=>$Admin['office_id'])));
			//pr($staffuser); die;
			$user_notifications = $this->UserNotification->find('all', array('conditions'=>array('UserNotification.user_id'=>$staffuser),'order' => array('UserNotification.id DESC'),'limit'=>4));
			
		} else if(!empty($Admin) && $Admin['user_type'] == "Staffuser"){
			
			$user_notifications = $this->UserNotification->find('all', array('conditions'=>array('UserNotification.user_id'=>$Admin['id']),'order' => array('UserNotification.id DESC'),'limit'=>4));
			
		}
		//print_r($user_notifications);die;
		$this->set('user_notifications',$user_notifications); 	
	}
	
	//This code for sending notification by admin on web
	public function admin_notification_send($id=null){
		$this->loadModel('UserDevice');
		$this->loadModel('UserNotification');
		$this->set('user_id',$id);
		if($this->request->is('post')){
			if(!empty($this->request->data['UserNotification']['text'])) {
				//$user_devices=$this->UserDevice->find('list',array('conditions'=>array('UserDevice.device_type'=>'A'),'fields'=>array('id','device_token')));
				$user_devices=$this->UserDevice->find('list',array('conditions'=>array('UserDevice.device_type'=>'A','UserDevice.user_id'=>$id),'fields'=>array('id','device_token')));
				//pr($user_devices); die;
				if(!empty($user_devices)) {
					if(!empty($user_devices)&& !empty($this->request->data['UserNotification']['text'])){
						$i=0;
						foreach($user_devices as $userdevice){
							$this->sendPushNotification($userdevice,$this->request->data['UserNotification']['text']);
						   $i++;
						}	
					}
					if(!empty($this->request->data['UserNotification']['text'])){
					   $this->notifications($this->request->data['UserNotification']['text']);
					} 
					$this->request->data['UserNotification']['user_id'] = $id;
					$this->UserNotification->save($this->request->data);
					$this->Session->setFlash('Notification has been sent successfully.','message',array('class' => 'message'));
					$this->redirect(array('controller'=>'notifications','action'=>'admin_notification_send',$id,'admin'=>true));
				} else {
					$this->Session->setFlash('Notification can\'t be sent to this staff due to invalid device information','message',array('class' => 'message'));
					$this->redirect(array('controller'=>'notifications','action'=>'notification_list'));
				}
			} else {
				$this->UserNotification->validationErrors['text'] = "Please enter some text to push notification";
			}
		}
	}
	/* public function admin_notification_resend($id=null){
		$this->loadModel('UserDevice');
		$this->loadModel('UserNotification');
		$user_text=$this->UserNotification->find('first',array('conditions'=>array('UserNotification.id'=>$id),'recursive'=>-1));
		$user_devices=$this->UserDevice->find('list',array('conditions'=>array('UserDevice.device_type'=>'A'),'fields'=>array('id','device_token')));
		 if(!empty($user_devices)&& !empty($user_text)){
			foreach($user_devices as $userdevice){
				$this->sendPushNotification($userdevice,$user_text['UserNotification']['text']);
			}
		}
		if(!empty($user_text)){
		   $this->notifications($user_text['UserNotification']['text']);
		} 
		$save_data['UserNotification']['text']=$user_text['UserNotification']['text'];
		$this->UserNotification->save($save_data);
		$this->Session->setFlash('Notification has been sent successfully.'); 
		$this->redirect($this->referer()); 
	} */
	
	//This function for deleting notification 
	public function admin_notification_delete($id=null){
        $this->loadModel('UserNotification');
		if($this->UserNotification->delete($id)){
			$this->Session->setFlash('Notification has been deleted successfully.','message',array('class' => 'message'));
			$this->redirect($this->referer());
		}
	} 
	
	
	//This function for sending Push notifications
	function notifications($msg=null){	
		$message = $msg;
		$this->UserDevice = ClassRegistry::init('UserDevice');
		$device_token = $this->UserDevice->find('list',array('fields'=>array('id','device_token'),'conditions'=>array('UserDevice.device_type'=>"I"),'recursive'=>-1));			
		$deviceToken = array_values($device_token);			
		$deviceToken = array_unique($deviceToken);
		$deviceToken = array_filter($deviceToken);			
		if($key = array_search('(null)',$deviceToken) !== false){
			unset($deviceToken[$key]);
		}
		if(!empty($deviceToken)){
			foreach($deviceToken as $devTkn)
			{
				$result = self::push_notification($devTkn, $message);
			}
			return $result;
		}
		else{
			return false;
		}	
	}
	function push_notification($deviceToken=null, $build_msg){
		$PATH = '../Vendor/';		
		$PASSWORD_APNS_PEM = '';	
		$FILE_APNS_PEM = 'producePush.pem';
		$APNS_HOST = 'ssl://gateway.push.apple.com:2195';
		$unread = 1;

		$deviceToken = str_replace("(null)", '', $deviceToken);
		$deviceArr = explode(',', $deviceToken);
		$deviceArr = array_unique($deviceArr);
		$deviceArr = array_filter($deviceArr);
		
		if($key = array_search('(null)',$deviceArr) !== false)
		{
			unset($deviceArr[$key]);
		}
		
		$arrContextOptions=array(
			    "ssl"=>array(
			        "verify_peer"=>false,
			        "verify_peer_name"=>false
			    )
			); 
		$ctx = stream_context_create($arrContextOptions);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $PASSWORD_APNS_PEM);
		stream_context_set_option($ctx, 'ssl', 'local_cert', $PATH.$FILE_APNS_PEM);
		$fp = stream_socket_client($APNS_HOST, $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
		stream_set_blocking ($fp, 0); 
		$root = '../tmp/cache/views/apns-errors.log';
		$date = date('Y-m-d H:i:s');
		if (!$fp) {	
		
			//...........create log			
			$r = "[$date]: ";
			$r .= "Failed to connect (stream_socket_client): $err $errstr";
			error_log($r, 3, $root);
			//.........end of log
			
			return $r;
		} 
		else 
		{
			// Keep push alive (waiting for delivery) for 30 days
			$apple_expiry = time() + (30 * 24 * 60 * 60); 

			if(!empty($deviceArr))
			{
				//echo "<pre>";print_r($deviceArr);die;
				$i = 0;
				foreach($deviceArr as $eachDevice)
				{
					//echo $eachDevice."@";
					$eachDevice = trim($eachDevice);
					//echo $eachDevice."@";die;
					$body = array();
					$body['aps'] = array('alert' => $build_msg);
					//$body['aps']['notifurl'] = 'http://www.mydomain.com';					
					$body['aps']['sound'] = 'default';
					//$body['aps']['badge'] = $unread;
					//$body['aps']['content-available'] = '1';
					
					$apple_identifier = rand(1, 32);
					$payload = json_encode($body);			            
					
					$msg = pack("C", 1) . pack("N", $apple_identifier) . pack("N", $apple_expiry) . pack("n", 32) . pack('H*', str_replace(' ', '', $eachDevice)) . pack("n", strlen($payload)) . $payload; 					
					fwrite($fp, $msg);
					$r = self::checkAppleErrorResponse($fp); 			
					
					if($r)
					{
						$r1 = "[$date]: ";
						$r1 .= $r;
						error_log($r1, 3, $root);
					}
					else
					{
						$r = 'Completed';
					}
					$i++;
				}
			}
			else
			{
				$r = false;
			}			
			fclose($fp);
			
			return $r;
		}
		
	}
	
	function admin_linked_device(){
		if($this->Session->read('Auth.Admin.user_type')=='Admin'){
		$this->loadModel('NewUserDevices');
		$new_user_devices = $this->NewUserDevices->find('all');
		//pr($new_user_devices);die;
		$this->set(compact('new_user_devices'));
		}else{
            $this->redirect(WWW_BASE.'admin/dashboards/index');
        }
	}
}
?>