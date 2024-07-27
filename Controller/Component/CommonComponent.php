<?php
App::uses('TestName', 'Model');
class CommonComponent  extends Component{
	public function  __construct(){ 
			$this->TestName = new TestName();
		}
	public function resize_image($upload_file_path,$extension,$image_name){
		//phpinfo();die;
		if($extension=="jpg" || $extension=="jpeg"){
			$uploadedfile = $upload_file_path;//$_FILES['file']['tmp_name'];
			$src = imagecreatefromjpeg($uploadedfile);
		}
		else if($extension=="png")
		{
			$uploadedfile =$upload_file_path; //$_FILES['file']['tmp_name'];
			$src = imagecreatefrompng($uploadedfile);
		}
		else
		{
			$src = imagecreatefromgif($uploadedfile);
		}

			list($width,$height)=getimagesize($uploadedfile);
			if($width<500){
				$newwidth=$width;
			}else{
				$newwidth=500;
			}
			if($height<500){
				$newheight=$width;
			}else{
				$newheight=500;
			}
			//$newwidth=60;
			//$newheight=($height/$width)*$newwidth;
			$tmp=imagecreatetruecolor($newwidth,$newheight);

			$newwidth1=150;
			//$newheight=($height/$width)*$newwidth1;
			$newheight1=150;
			$tmp1=imagecreatetruecolor($newwidth1,$newheight1);

			imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);

			imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);

			$filename = "http://s2s.dev.pogo-digital.co.uk/img/uploads/". $image_name;
			$filename1 = "http://s2s.dev.pogo-digital.co.uk/img/uploads/". $image_name;
			
			//print_r();
			imagejpeg($tmp,$filename,100);
			imagejpeg($tmp1,$filename1,100);
			imagedestroy($src);
			imagedestroy($tmp);
			imagedestroy($tmp1);
	}
	
	
	function callApi($post_body = ''){	
		// Application credentials
		DEFINE('APPLICATION_ID', 56102);
		DEFINE('AUTH_KEY', "zUey3V3hqAx8UGw");
		DEFINE('AUTH_SECRET', "sMCJyaxcTkqfqqk");

		// User credentials
		DEFINE('USER_LOGIN',"s2s");
		DEFINE('USER_PASSWORD',"P0g0d1g1tal!");

		// Quickblox endpoints
		DEFINE('QB_API_ENDPOINT', "https://api.quickblox.com");
		DEFINE('QB_PATH_SESSION', "session.json");
		
		$timestamp = time();
		$signature='';
		$nonce=rand();
		$post_body = "application_id=" . APPLICATION_ID . "&auth_key=" . AUTH_KEY . "&timestamp=" . $timestamp . "&nonce=" . $nonce . "&signature=" . $signature . "&user[login]=" . USER_LOGIN . "&user[password]=" . USER_PASSWORD;

		 //echo "postBody: " . $post_body . "<br><br>";
		// Configure cURL
		$response = json_decode($this->curlRequest($post_body, QB_PATH_SESSION));	
		//print_r($response);die;
		$token = $response->session->token;
		//pr($response);die;
		return $token ;
		//return $response;
		
	}
	function curlRequest($post_body = null, $action, $headers = array()){
		// Generate signature
		$nonce = rand();
		$timestamp = time(); // time() method must return current timestamp in UTC but seems like hi is return timestamp in current time zone
		$signature_string = "application_id=".APPLICATION_ID."&auth_key=".AUTH_KEY."&nonce=".$nonce."&timestamp=".$timestamp."&user[login]=".USER_LOGIN."&user[password]=".USER_PASSWORD;

		//echo "stringForSignature: " . $signature_string . "<br><br>";
		$signature = hash_hmac('sha1', $signature_string , AUTH_SECRET);

		// Build post body
		$post_body = http_build_query(array(
						'application_id' => APPLICATION_ID,
						'auth_key' => AUTH_KEY,
						'timestamp' => $timestamp,
						'nonce' => $nonce,
						'signature' => $signature,
						'user[login]' => USER_LOGIN,
						'user[password]' => USER_PASSWORD
						));
		return $this->callWebServices($post_body, $action, $headers);
	
	}
	function getUserList($token = null){
		if($token){
			$header = array('QB-Token' => $token);
			$action = 'users.json';
			$post_body = http_build_query(array(
						  'login' => USER_LOGIN,
						'password' => USER_PASSWORD, 
						'token' => $token
						));
			$response = $this->callWebServices($post_body, $action, $header);	
			return $response;	

			
		}
	}
	function callWebServices($post_body, $action, $headers){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, QB_API_ENDPOINT . '/' . $action); // Full path is - https://api.quickblox.com/session.json
	//	curl_setopt($curl, CURLOPT_POST, array());
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_body); // Setup post body
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Receive server response

		// Execute request and read responce
		$responce = curl_exec($curl);
		// Check errors
		if ($responce) {
				return $responce . "\n";
		} else {
				$error = curl_error($curl). '(' .curl_errno($curl). ')';
				return  $error . "\n";
		}

		// Close connection
		curl_close($curl);
	}
	function getAccountDetails($token = null){
			$header = array('QB-Account-Key' => AUTH_KEY);
			$action = 'account_settings.json';
			$post_body = http_build_query(array(
							'token' => $token
						));
			$response = $this->curlRequest($post_body, $action, $header);	
			return $response;	
	}
	
	function call_save_user($save_data,$post_body=null){
		$token=($this->callApi($post_body));
		//$name=explode(" ",$save_data['User']['name']);
		//$first_name=$name[0];
		$post_body=http_build_query(array(
						'user[login]'=>$save_data['User']['email'],
						'user[password]'=>$save_data['User']['password'], 
						'user[email]'=>$save_data['User']['email'],
						'user[full_name]'=>$save_data['User']['name'],
						'user[tag_list]'=>'S2S',
						'token'=>$token					
					));
		$responseUsers = $this->save_user($token,$post_body);
		return $responseUsers ;
	}
	function save_user($token,$post_data){
		
		if($token){
			$header=array('Content-Type'=>'application/json','QuickBlox-REST-API-Version'=>'0.1.0','QB-Token'=>$token);
			$action='users.json';
			$response=$this->callWebServices($post_data, $action, $header);	
			//print_r($response);die;
			return $response;	
		}
	}
	
	function getCurl($action=null,$headers=null){
		
		$curl = curl_init();
		
		curl_setopt($curl,CURLOPT_URL, QB_API_ENDPOINT.'/'.$action);
		curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);

		// Execute request and read responce
		$responce = curl_exec($curl);
		// Check errors
		//print_r($responce);die;
		if($responce){
			return $responce . "\n";
		}else{
			$error = curl_error($curl). '(' .curl_errno($curl). ')';
			return  $error . "\n";
		}
		curl_close($curl);
	}
	
	
	function getDialog($coachId,$studentId,$useremail,$userpassword){
		
		DEFINE('APPLICATION_ID', 56102);
		DEFINE('AUTH_KEY', "zUey3V3hqAx8UGw");
		DEFINE('AUTH_SECRET', "sMCJyaxcTkqfqqk");
		// User credentials
		DEFINE('USER_LOGIN',$useremail);
		DEFINE('USER_PASSWORD',$userpassword);
		// Quickblox endpoints
		DEFINE('QB_API_ENDPOINT', "https://api.quickblox.com");
		DEFINE('QB_PATH_SESSION', "session.json");
		
		$timestamp = time();
		$signature='';
		$nonce=rand();
		$post_body="application_id=" . APPLICATION_ID . "&auth_key=" . AUTH_KEY . "&timestamp=" . $timestamp . "&nonce=" . $nonce . "&signature=" . $signature . "&user[login]=" . USER_LOGIN . "&user[password]=" . USER_PASSWORD;
		$response=json_decode($this->curlRequest($post_body, QB_PATH_SESSION));	
		$token = isset($response->session->token)?$response->session->token:'';
		//print_r($token);die;
		if($token){
			$occupaintIds[0]=$studentId;
			$occupaintIds[1]=$coachId;
			$header=array('Content-Type'=>'application/json','QuickBlox-REST-API-Version'=>'0.1.0','QB-Token'=>$token);
			//$action='chat/Dialog.json?token='.$token.'&'.'occupants_ids='.$coachId.','.$studentId;
			$action='chat/Dialog.json?token='.$token;
			$response=json_decode($this->getCurl($action,$header));
			//print_r($response);die;
			if(isset($response->items)&&(!empty($response->items))){
				foreach($response->items as $occupants_ids){
				
					$result = array_diff($occupants_ids->occupants_ids, $occupaintIds);
					if(empty($result)){
						$dialogId=$occupants_ids->_id;
						break;
					}
				
				} 
			}else{
				return "no record";
			}
			if(isset($dialogId)&&!empty($dialogId)){
				$response=$this->getDialogMessage($dialogId,$token);
			}else{
				return "no record";
			}
			return $response;	
		}else{
			return "no record";
		}
	}
	
	 function getDialogMessage($dialogId = null,$token = null){
		$curl = curl_init();
		$headers=array('Content-Type'=>'application/json','QuickBlox-REST-API-Version'=>'0.1.0','QB-Token'=>$token);
		$header=array('Content-Type'=>'application/json','QuickBlox-REST-API-Version'=>'0.1.0','QB-Token'=>$token);
		$action='chat/Message.json?chat_dialog_id='.$dialogId.'&'.'token='.$token;
		$response=json_decode($this->getCurl($action,$header));
		
		if($response){
			return $response->items;
		}else{
			return "no record";
			
		}
	}
	
	
	public function rotate_Add($image_type,$tmp_name,$name,$rotate_value){
		 $rotate_value = 360 - (int)$rotate_value;  
		if(($image_type=='jpg')||($image_type=='jpeg')){
						
			$imageResource = imagecreatefromjpeg($tmp_name);
			$tmp_name = imagerotate($imageResource, $rotate_value, 0);
			imagejpeg($tmp_name, "img/uploads/$name", 90);
			
		}elseif($image_type=='png'){
			$imageResource = imagecreatefrompng($tmp_name);
			$tmp_name = imagerotate($imageResource, $rotate_value, 0);
			imagepng($tmp_name, "img/uploads/$name", 9);
			
		}elseif($image_type=='gif'){
			$imageResource = imagecreatefromgif($tmp_name);
			$tmp_name = imagerotate($imageResource, $rotate_value, 0);
			imagegif($tmp_name, "img/uploads/$name", 90);
		}
	}
	
	public function rotate_Edit($image_type,$file_path,$file_base_path,$rotate_value){
		 $rotate_value = 360 - (int)$rotate_value;  
		if(($image_type=='jpg')||($image_type=='jpeg')){
						
			$imageResource = imagecreatefromjpeg($file_path);
			$new_image = imagerotate($imageResource, $rotate_value, 0);
			//$new_image=WWW_BASE.'/img/uploads/'.'new.jpg';
			$path=getcwd().'/img/uploads/';
			chmod($path, 0777);
			imagejpeg($new_image, $file_base_path,90);
		
		}elseif($image_type=='png'){
			$imageResource = imagecreatefrompng($file_path);
			$new_image = imagerotate($imageResource, $rotate_value, 0);
			$path=getcwd().'/img/uploads/';
			chmod($path, 0777);
			imagepng($new_image, $file_base_path, 9);
		
		}elseif($image_type=='gif'){
			$imageResource = imagecreatefromgif($file_path);
			$new_image = imagerotate($imageResource, $rotate_value, 0);
			$path=getcwd().'/img/uploads/';
			chmod($path, 0777);
			imagegif($new_image, $file_base_path, 90);
		}
	}
	public function get_office_reports(){
		return array(
			'0'=>array('key'=>'ALL','value'=>'All'),
			'1' => array('key'=>'VF','value'=>'VF Reports'),
			'2' => array('key'=>'VA','value'=>'VA Reports')
		); 
	} 
	public function testNameArray(){
		 $data = $this->TestName->find('list',array('fields' => array('name','name'),'conditions'=> array('TestName.is_delete' => '0'),'order'=>array('TestName.name' =>'asc')));
		return $data;
	}
	public function testNameVisualFieldsArray(){

		 $data = $this->TestName->find('list',array('fields' => array('name','name'),'conditions'=> array('TestName.is_delete' => '0','TestName.test_type' => 'Visual_Fields'),'order'=>array('TestName.name' =>'asc')));

		return $data;

	}
	public function testNameFDTArray(){

		 $data = $this->TestName->find('list',array('fields' => array('name','name'),'conditions'=> array('TestName.is_delete' => '0','TestName.test_type' => 'F_D_T'),'order'=>array('TestName.name' =>'asc')));

		return $data;

	}

	public function TestTypeArray(){  

		return [ 

					'Visual_Fields'=> 'Visual Fields',
					'F_D_T'=> 'FDT',
					'Dark_Adaption'=>'Dark Adaption',
					'Visual_Screening'=>'Visual Screening',
					'Pupolimeter'=>'Pupolimeter',
					'ACT'=>'ACT',
					'Strabismus_Screening'=>'Strabismus Screening',
					'Vision_Therapy_Test'=>'Vision Therapy Test'

				];

	}
		public function TestTypethresholdArray(){  

		return [ 

					'Central_10_2'=> 'Central_10_2',
					'Central_24_1'=> 'Central_24_1',
					'Central_24_2'=>'Central_24_2',
					'Central_30_1'=>'Central_30_1',
					'Central_30_2'=>'Central_30_2',
				];

	}
}
