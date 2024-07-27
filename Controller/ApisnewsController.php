<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email'); 
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
//App::import('Vendor','twilio-php-master',array('file' => 'twilio-php-master/Services/Twilio.php'));
ini_set('max_execution_time', 0);

class ApisnewsController  extends AppController {
	
	public $uses = array('User','Patients','Tests','UserNotification','UserDevice','TestReport','TestDevice','Practice','Office','Files','VfPointdata','Pointdata','Masterdata','MasterPointdata', 'DevicePreference', 'Diagnosis', 'Cms', 'DarkAdaption', 'DaPointData','UserPreset','DeviceMessage','UserPresetData','Apk','CsPointdata','ReportRequestBackup','DevicMessage','StbTest','StbPointdata','DarkAdaptionnew','Patientsnew'); 
	   
	var $helpers = array('Html', 'Form');	
	public $components = array('Auth','Session','Email','Common');
	
	function beforeFilter(){		
		parent::beforeFilter();	
		$this->Auth->allow('*');	
	} 
	
	//this function for image uploading 
	public function base64_to_jpeg($base64_string,$folder_name){ 
			
		$file_name=time().'.jpg';	 
		$output_file=getcwd().'/'.$folder_name.'/'.$file_name;
		$ifp=fopen($output_file,'wb');
		fwrite($ifp,base64_decode($base64_string)); 
		fclose($ifp);
		return($file_name);		
	}
	
	//This function for PDF uploading 
	public function base64_to_pdf($base64_string,$folder_name,$file_unique_no=0){
			
		$file_name=time().'_'.$file_unique_no.'.pdf';	 
		$output_file=getcwd().'/app/webroot/'.$folder_name.'/'.$file_name;
		$ifp=@fopen($output_file,'wb');
		//print_r($ifp);die; 
		@fwrite($ifp,base64_decode($base64_string)); 
		@fclose($ifp);
		return($file_name);		
	}
	public function base64_to_pdf2($base64_string,$folder_name,$file_unique_no=0){
			
		$file_name=time().'_'.$file_unique_no.'.pdf';	 
		$output_file=getcwd().'/app/webroot/'.$folder_name.'/'.$file_name;
		$ifp=@fopen($output_file,'wb');
		//print_r($ifp);die; 
		@fwrite($ifp,base64_decode($base64_string)); 
		@fclose($ifp);
		return($file_name);		
	}
	//This function is for converting image into base 64
	public function jpeg_to_base64($image_name, $folder_name){
		$image = $folder_name.$image_name;
		$arr = explode('.', $image_name);
		$extension = end($arr);
		$base64Image = "data:image/".$extension.";base64,".base64_encode(file_get_contents($image));
		return $base64Image;
	}
	
	//This method check Get request
	public function validateGetRequest(){
		if($_SERVER['REQUEST_METHOD'] == 'GET'){
			return true;
		} else {
			$response_array = array('message' => 'Invalid request.','status'=>0);
			header('Content-Type: application/json');
			echo json_encode($response_array);die;
			exit;
		}
	}
	
	//This method check post request
	public function validatePostRequest(){
		if($_SERVER['REQUEST_METHOD']=='POST'){
			return true;
		} else {
			$response_array = array('message' => 'Invalid request2.','status'=>0);
			header('Content-Type: application/json');
			echo json_encode($response_array);die;
			exit;
		}
	}
	//Check whether admin block the user or not
	public function isAuthorized($user){ 
		$userDeatils = $this->User->find('first', ['conditions' => array('User.username' =>@$user['username'])]);
		if(!empty($userDeatils)){
			if($userDeatils['User']['user_type']==='Admin'){
				return true;
			}else if($userDeatils['Office']['status']==1){
				return true;
			}else {
				return false;
			}
		}else{
			return true;
		}
	}
	//This is the function for securing API'same
	public function check_key(){
		 
		if(isset($_SERVER['HTTP_APPKEY'])){
			$headerValue = $_SERVER['HTTP_APPKEY'];
				if(app_key!=$headerValue){
					// not validate for unity # to validate app key comment the return true.
					return true;
					$response_array = array('message' => 'Invalid security key.','status'=>0);
					echo json_encode($response_array);die;
				}else{
					return true;
				}
		}else{
			// not validate for unity # to validate app key comment the return true.
			return true;
			$response_array = array('message' => 'Please send your security key.','status'=>0);
			echo json_encode($response_array);die;
		}
	}
	
 
	 
	public function time() {
		 $datetime = '08/22/2015 10:56 PM';
$tz_from = 'America/Los_Angeles';
$tz_to = 'UTC';
$format = 'Y-m-d H:i:s';

$dt = new DateTime($datetime, new DateTimeZone($tz_from));
$dt->setTimeZone(new DateTimeZone($tz_to));
echo $dt->format($format) . "\n";
    die();
	}
	public function setUtc($time,$time_zone)
	{
		$datetime = $time;
		$tz_from = $time_zone;
		$tz_to = 'UTC';
		$format = 'Y-m-d H:i:s';
		$dt = new DateTime($datetime, new DateTimeZone($tz_from));
		$dt->setTimeZone(new DateTimeZone($tz_to));
		return  $dt->format($format);
	}
	 
 
	public function addPatients_v3(){
		if($this->check_key()){
			$this->layout=false;
			if($this->validatePostRequest()){
				$input_data  = json_decode(file_get_contents("php://input"),true);
				if(empty($input_data)){
					$input_data =  $_POST;
				}
				 
				
				@$input_data['od_left'] = isset($input_data['od_left'])?$input_data['od_left']:'';
				@$input_data['od_right'] = isset($input_data['od_right'])?$input_data['od_right']:'';
				@$input_data['os_left'] = isset($input_data['os_left'])?$input_data['os_left']:'';
				@$input_data['os_right'] = isset($input_data['os_right'])?$input_data['os_right']:'';
				@$input_data['unique_id'] = isset($input_data['unique_id'])?$input_data['unique_id']:null;

				$input_data['first_name']=preg_replace('/[^A-Za-z0-9\-]/', '_', $input_data['first_name']);
            	$input_data['middle_name']=preg_replace('/[^A-Za-z0-9\-]/', '_', $input_data['middle_name']);
            	$input_data['last_name']=preg_replace('/[^A-Za-z0-9\-]/', '_', $input_data['last_name']);
            	$input_data['first_name']=str_replace('-','_',$input_data['first_name']);
           		$input_data['middle_name']=str_replace('-','_',$input_data['middle_name']); 
            	$input_data['last_name']=str_replace("-","_",$input_data['last_name']);
 
				$save_data =  array(
					'user_id' => @$input_data['user_id'],
					'first_name' => @$input_data['first_name'],
					'middle_name' => @$input_data['middle_name'],
					'last_name' => @$input_data['last_name'],
					'id_number' => @$input_data['id_number'],
					'dob' => @$input_data['dob'],
					'notes' => @$input_data['notes'],
					'email' => @$input_data['email'],
					'phone' => @$input_data['phone'],
					'od_left' => @$input_data['od_left'],
					'od_right' => @$input_data['od_right'],
					'os_left' => @$input_data['os_left'],
					'os_right' => @$input_data['os_right'],
					'unique_id' => @$input_data['unique_id'],
					'race' => trim(@$input_data['race']),
					'created' => (!empty($input_data['created_date'])) ? date('Y-m-d H:i:s',strtotime($input_data['created_date'])) : date('Y-m-d H:i:s')
					);
				//pr($save_data); die;
				if(isset($input_data['first_name']) && !isset($input_data['patient_id'])){
					$user_office=$this->User->find('first',array('conditions'=>array('User.id'=>@$input_data['user_id']),'fields'=>array('User.office_id')));
					$save_data['office_id']=$user_office['User']['office_id']; 
				/*  $this->Patientsnew->table='patients';
						 $this->Patientsnew->useTable='patients';
						 $this->Patientsnew->validate=array(
		  'unique_id' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter Id number.'
			), 
			'unique'=>array(
				'rule' => 'isUnique',
				'message' => 'Please enter another unique id it is already taken.'
			),
		)
    );*/print_r($this->Patientsnew);die();
						$this->Patientsnew->set($save_data);
						
						if ($this->Patientsnew->validates()) {
						

					$result = $this->Patients->save($save_data);
					 
					if(count($result['Patients']))
					{
						$result['Patients']['patient_id'] = $result['Patients']['id'];
						unset($result['Patients']['id']);
						$response_array = array('message'=>'Patients Added successfully.','status'=>1,'data' => $result['Patients']);
						header('Content-Type: application/json');
						echo json_encode($response_array);die;
					}
					else{
						$response_array = array('message'=>'Some problems occured during process. Please try again.','status'=>0);
						header('Content-Type: application/json');
						echo json_encode($response_array);die;
					}
					}else{
						$response_array = array('message'=>'Unique id allready taken.','status'=>0);
						header('Content-Type: application/json');
						echo json_encode($response_array);die;
					}
				}
				else{
					$user_office=$this->User->find('first',array('conditions'=>array('User.id'=>@$input_data['user_id']),'fields'=>array('User.office_id')));
					$save_data['office_id']=$user_office['User']['office_id'];
					$save_data['id'] = $input_data['patient_id'];
					$save_data['created'] = (!empty($input_data['created_date'])) ? date('Y-m-d H:i:s',strtotime($input_data['created_date'])) : date('Y-m-d H:i:s');
					$result = $this->Patients->save($save_data);
					if($result){
						$result['Patients']['patient_id'] = $result['Patients']['id'];
						unset($result['Patients']['id']);
						$response_array = array('message'=>'Patients Updated successfully.','status'=>1,'data' => $result['Patients']);
						header('Content-Type: application/json');
						echo json_encode($response_array);die;
					}
					else
					{
						$response_array = array('message'=>'Some problems occured during process. Please try again.','status'=>0);
						header('Content-Type: application/json');
						echo json_encode($response_array);die;
					}	
						
				}
			}
		}
	}
	 
	/*This API for add and update patient*/		
	public function addPatients_v4(){
		if($this->check_key()){
			$this->layout=false;
			if($this->validatePostRequest()){ 
				$input_data  = json_decode(file_get_contents("php://input"),true);
				//CakeLog::write('info',json_encode($input_data));
				$save_data = $saved_data = array();
				if(!empty($input_data['data']) && isset($input_data['user_id']) && !empty($input_data['user_id']) && isset($input_data['office_id']) && !empty($input_data['user_id'])){
					$i=0;
					foreach($input_data['data'] as $data){
						 //pr($data);die;
						$patients = $data;
						if(!empty($data['patient_id'])){
							$patients['id'] = $patients['patient_id'];
						}else{
							//$patients['unique_id'] = $input_data['unique_id'];
						}
						$patients['race'] = trim(@$data['race']);
						$patients['office_id'] = $input_data['office_id'];
						$patients['user_id'] = $input_data['user_id']; 
						$patients['created'] = (isset($data['created_date']) && !empty($data['created_date']))? date('Y-m-d H:i:s',strtotime($data['created_date'])) : date('Y-m-d H:i:s');	
						//echo "<pre>";
						 $this->Patientsnew->table='patients';
						 $this->Patientsnew->useTable='patients';
						 $this->Patientsnew->validate=array(
		  'unique_id' =>array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'message' => 'Please enter Id number.'
			), 
			'unique'=>array(
				'rule' => 'isUnique',
				'message' => 'Please enter another unique id it is already taken.'
			),
		)
    );
						//print_r($this->Patientsnew);die(); 
						$this->Patientsnew->set($patients);
						$r=$this->Patientsnew->validates();
						//print_r($this->Patientsnew);
						if ($this->Patientsnew->validates()) {
						
						$rs = $this->Patientsnew->save($patients);
						if($rs){
							$saved_data[$i]['id'] =  $this->Patientsnew->id;
						}
					 }
					
						$i++;
					}
					if(!empty($saved_data)){
						$response_array = array('message'=>'Patients Saved successfully.','status'=>1,'data' => $saved_data);
					} else {
						$response_array = array('message'=>'Error in adding patients.','status'=>0,'data' => $saved_data);
					}
				} else {
					$response_array = array('message'=>'Please send all parameters.','status'=>0);
				}
				echo json_encode($response_array);die; 
			}
		}
	}
	 

	
 }

?>