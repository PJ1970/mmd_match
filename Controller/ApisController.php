<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email'); 
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
//App::import('Vendor','twilio-php-master',array('file' => 'twilio-php-master/Services/Twilio.php'));

ini_set('max_execution_time', 1200);
class ApisController  extends AppController {
	
	public $uses = array('User','Patients','Tests','UserNotification','UserDevice','TestReport','TestDevice','Practice','Office','Files');  
	var $helpers = array('Html', 'Form');	
	public $components = array('Auth','Session','Email','Common');
	
	function beforeFilter(){
		parent::beforeFilter();	
		$this->Auth->allow('*');	
		//echo phpinfo(); die;
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
	public function base64_to_pdf($base64_string,$folder_name){
			
		$file_name=time().'.pdf';	 
		$output_file=getcwd().'/'.$folder_name.'/'.$file_name;
		$ifp=fopen($output_file,'wb');
		//print_r($ifp);die;
		fwrite($ifp,base64_decode($base64_string)); 
		fclose($ifp);
		return($file_name);		
	}
	
	//This method check Get request
	public function validateGetRequest(){
		if($_SERVER['REQUEST_METHOD'] == 'GET'){
			return true;
		}else {
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
			$response_array = array('message' => 'Invalid request.','status'=>0);
			header('Content-Type: application/json');
			echo json_encode($response_array);die;
			exit;
		}
	}
	
	//This is the function for securing API'same
	public function check_key(){
		 
		if(isset($_SERVER['HTTP_APPKEY'])){
			$headerValue = $_SERVER['HTTP_APPKEY'];
				if(app_key!=$headerValue){
					$response_array = array('message' => 'Invalid security key.','status'=>0);
					echo json_encode($response_array);die;
				}else{
					return true;
				}
		}else{
			$response_array = array('message' => 'Please send your security key.','status'=>0);
			echo json_encode($response_array);die;
		}
	}
	//This API for login of staff.
	public function login(){
		if($this->check_key()){
				$save_data = array();
				$this->layout = false;
				if($this->validatePostRequest()){
				$input_data = file_get_contents('php://input');
			    $input_data =  json_decode($input_data,true);
				if(isset($input_data['username']) && isset($input_data['password']) && !empty($input_data['password'])){
					$username = trim($input_data['username']);
					$user_detail=$this->User->find('first',array(
						'conditions'=>array('User.username'=>$username),
						'fields'=>array('User.id','User.first_name','User.password', 'User.middle_name', 'User.last_name','User.username','User.user_type','User.email','User.dob','User.phone','User.gender','User.office_id','User.id_no','User.notes','User.created','User.modified')
					));
						
						if(empty($user_detail))
						{
							$response_array=array('message'=>'Invalid username or password.','status'=>0);
							header('Content-Type: application/json');
							echo json_encode($response_array);
							die;
						}elseif($user_detail['User']['user_type']!='Staffuser'){
							$response_array=array('message'=>'You are not staff. Please try again.','status'=>0);
							header('Content-Type: application/json');
							echo json_encode($response_array);
							die;
						}
						//Use Blowfishpassword hasher algorithm
						$passwordHasher = new BlowfishPasswordHasher();
						$match = $passwordHasher->check($input_data['password'],@$user_detail['User']['password']);
						if(!$match){
							$response_array=array('message'=>'Invalid username or password.','status'=>0);
							header('Content-Type: application/json');
							echo json_encode($response_array);
							die;
						}
						$data = $user_detail['User'];
						/* if(!empty($user_detail['User']['profile_pic'])){
							$data['profile_pic'] = WWW_BASE . 'img/uploads/'. $user_detail['User']['profile_pic'];
						}
						*/
						$data['user_id'] = $data['id'];
						$this->Office->id=$data['office_id'];
						$office_name=$this->Office->field('name');
						$data['office_name'] =$office_name;
						unset($data['id']);
						unset($data['password']);
						
						
						$response_array=array('message'=>'Login successfull.','status'=>1,'data'=>$data);	
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
				}else{
					$response_array=array('message'=>'Please send valid input data.','status'=>0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
	
	
	// This API for forgot password for Admin
	
	public function forgot_password(){
	    if($this->check_key()){
			$this->layout=false;
			if($this->validatePostRequest()){
			$input_data = file_get_contents('php://input');
				//$input_data = $_POST;
			$input_data =  get_object_vars(json_decode($input_data));
			
			
			if(isset($input_data['email']) && !empty($input_data['email'])){
				$chekc_email = $this->User->find('first',array('conditions'=>array('User.email'=>$input_data['email']),'fields'=>array('User.id','User.email','User.first_name','User.last_name')));
				if(empty($chekc_email)){
					$response_array = array('message'=>'Email address does not exist.','status'=>0);
					header('Content-Type: application/json');
					echo json_encode($response_array);die;
				}
				$user = array();
				 
				
				$password = $this->generateRandomString();
					$this->User->id = $chekc_email['User']['id'];
					$this->User->saveField('password', $password);		
					
					$name = $chekc_email['User']['first_name'];
					$user['User']['first_name'] = $name;
					$user['User']['custom_password'] = $password;
					
					$user['User']['email'] = $chekc_email['User']['email'];	
				 
					
					$subject = "Admin Password Reset";

					try{
						$Email = new CakeEmail();
						$Email->viewVars($user['User']);
						$Email->template('forgot_password');
						$Email->from(array(FROM_EMAIL => 'MMD'));
						$Email->to($user['User']['email']);
						$Email->subject($subject);
						$Email->emailFormat('html');
						$Email->send();
						$response_array = array('message' =>'New password generated successfully. Please check your email inbox.' , 'status' => 1);
						header('Content-Type: application/json');
						echo json_encode($response_array);die;
					
					}catch(Exception $e){
						$response_array = array('message'=> 'Falied to update password. Try again.' , 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);die;
					}
				}else{
					$response_array = array('message'=> 'Falied to update password. Try again.' , 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);die;
				}
			}
		}
	}
			
	/*This API for add and update patient*/		
	public function addPatients(){
		if($this->check_key()){
			$this->layout=false;
			if($this->validatePostRequest()){ 
				$input_data = file_get_contents('php://input');
				$input_data =  get_object_vars(json_decode($input_data));
				
				/*$chekc_ID_number = $this->Patients->find('first',array('conditions'=>array('Patients.id_number'=>$input_data['id_number']),'fields'=>array('Patients.id')));
					if(!empty($chekc_ID_number)){
						$response_array = array('message'=>'Patient already exists with provided Id number.','status'=>0);
						header('Content-Type: application/json');
						echo json_encode($response_array);die;
					}
				 */
				$save_data =  array(
					'user_id' => @$input_data['user_id'],
					'first_name' => @$input_data['first_name'],
					'middle_name' => @$input_data['middle_name'],
					'last_name' => @$input_data['last_name'],
					'id_number' => @$input_data['id_number'],
					'dob' => @$input_data['dob'],
					'notes' => @$input_data['notes'],
					'email' => @$input_data['email'],
					'phone' => @$input_data['phone']
					);
					
				if(isset($input_data['first_name']) && !isset($input_data['patient_id'])){
					$user_office=$this->User->find('first',array('conditions'=>array('User.id'=>@$input_data['user_id']),'fields'=>array('User.office_id')));
					$save_data['office_id']=$user_office['User']['office_id'];
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
				}
				else{
					$user_office=$this->User->find('first',array('conditions'=>array('User.id'=>@$input_data['user_id']),'fields'=>array('User.office_id')));
					$save_data['office_id']=$user_office['User']['office_id'];
					$save_data['id'] = $input_data['patient_id'];
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
	
	
	/*This API for updated version of addPatients*/
	public function addPatients_v1(){
		if($this->check_key()){
			$this->layout=false;
			if($this->validatePostRequest()){ 
				$getjson =file_get_contents('php://input');
				$input_data = (json_decode($getjson,true));
				if(!isset($input_data['patient_id'])){
					$i=0;
					if(!empty($input_data['data'])){
						
						foreach($input_data['data'] as $key=>$patient){
							
							$data['Patients'][$i]['user_id']=$input_data['user_id'];
							$data['Patients'][$i]['first_name']=$patient['first_name'];
							$data['Patients'][$i]['middle_name']=isset($patient['middle_name'])?$patient['middle_name']:'';
							$data['Patients'][$i]['last_name']=$patient['last_name'];
							$data['Patients'][$i]['id_number']=isset($patient['id_number'])?$patient['id_number']:'';
							$data['Patients'][$i]['dob']=isset($patient['dob'])?$patient['dob']:'';
							$data['Patients'][$i]['notes']=isset($patient['notes'])?$patient['notes']:'';
							$data['Patients'][$i]['email']=isset($patient['email'])?$patient['email']:'';
							$data['Patients'][$i]['phone']=isset($patient['phone'])?$patient['phone']:'';
							$data['Patients'][$i]['office_id']=$input_data['office_id'];
							$i++;
						}
						if($this->Patients->saveAll($data['Patients'])){
							
							$response_array = array('message'=>'Patients Added successfully.','status'=>1);
						}else{
							$response_array = array('message'=>'Error in adding patients.','status'=>0);
						}
						
					}else{
						$response_array = array('message'=>'Please send patients.','status'=>0);
						
					}
					
					echo json_encode($response_array);die;
				}
				else{
					$data['id']=$input_data['patient_id'];
					$data['user_id']=$input_data['user_id'];
					$data['first_name']=$input_data['first_name'];
					$data['middle_name']=isset($input_data['middle_name'])?$input_data['middle_name']:'';
					$data['last_name']=$input_data['last_name'];
					$data['id_number']=isset($input_data['id_number'])?$input_data['id_number']:'';
					$data['dob']=isset($input_data['dob'])?$input_data['dob']:'';
					$data['notes']=isset($input_data['notes'])?$input_data['notes']:'';
					$data['email']=isset($input_data['email'])?$input_data['email']:'';
					$data['phone']=isset($input_data['phone'])?$input_data['phone']:'';
					$data['office_id']=$input_data['office_id'];
					$result = $this->Patients->save($data);
					if($result){
						$result['Patients']['patient_id'] = $result['Patients']['id'];
						unset($result['Patients']['id']);
						$response_array = array('message'=>'Patients Updated successfully.','status'=>1,'data' => $result['Patients']);
						header('Content-Type: application/json');
						echo json_encode($response_array);die;
					}
					else{
						$response_array = array('message'=>'Some problems occured during process. Please try again.','status'=>0);
						header('Content-Type: application/json');
						echo json_encode($response_array);die;
					}		
				}
			}
		}
		
	}
	
	
	
	
	/* Save staff */
	public function saveStaff(){
		if($this->check_key()){
			$this->layout=false;
			if($this->validatePostRequest()){ 
				$input_data = file_get_contents('php://input');
				$input_data =  get_object_vars(json_decode($input_data));
				
				$save_data =  array(
					//'username' => @$input_data['username'],
					'first_name' => @$input_data['first_name'],
					'middle_name' => @$input_data['middle_name'],
					'last_name' => @$input_data['last_name'],
					'phone' => @$input_data['phone'],
					'gender' => @$input_data['gender'],
					'dob' => @$input_data['dob'],
					
					);
					
					
				if(@$input_data['email'] !='')
				{
					$check_email = $this->User->find('count', array('conditions'=>array('User.email'=>trim($input_data['email']), 'User.id !=' =>$input_data['user_id'])));
					if($check_email!=0){
						$response_array=array('message'=>'Email already registered.','status'=>0);
						header('Content-Type: application/json');
						echo json_encode($response_array);die;
					}
					$email_okay=preg_match('/^[\w\d-_]+@[\w\d-_]+\.ac\.uk$/',trim($input_data['email']));
					if(!$email_okay){
						$response_array = array('message'=>'Your email is invalid.','status'=>0);
						header('Content-Type: application/json');
						echo json_encode($response_array);die;
					}
					$save_data['email'] = @$input_data['email'];
				}
				$save_data =  array(
					//'username' => @$input_data['username'],
					'first_name' => @$input_data['first_name'],
					'middle_name' => @$input_data['middle_name'],
					'last_name' => @$input_data['last_name'],
					'phone' => @$input_data['phone'],
					'gender' => @$input_data['gender'],
					'dob' => @$input_data['dob'],
					
					);
					
				if(!isset($input_data['user_id'])){
					$response_array = array('message'=>'We need user id to precess this request.','status'=>0);
					header('Content-Type: application/json');
					echo json_encode($response_array);die;
					 
				}
				else{ 
					if(isset($input_data['password'])){
						$pass_length = strlen(@$input_data['password']);
						if(!(($pass_length >= 8 )&&($pass_length <= 16))){
							$response_array=array('message'=>'Your password length should be between 8 and 16 characters.','status'=>0);
							header('Content-Type: application/json');
							echo json_encode($response_array);die;
						}
						if(@$input_data['password'] != @$input_data['confirm_password']){
							$response_array=array('message'=>'Your password and confirm password does not match.','status'=>0);
							header('Content-Type: application/json');
							echo json_encode($response_array);die;
						}
						$save_data['password'] = @$input_data['password'];
					}
					
					$save_data['id'] = $input_data['user_id'];
					$result = $this->User->save($save_data);
					if($result){
						$result['User']['user_id'] = $result['User']['id'];
						unset($result['User']['id']);
						$this->User->id=$result['User']['user_id'];
						$office_id=$this->User->field('office_id');
						$result['User']['office_id']=$office_id;
						$response_array = array('message'=>'User Updated successfully.','status'=>1,'data' => $result['User']);
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
	
	/* API for changing staff password */
	public function staff_change_password(){
		if($this->check_key()){
			$this->layout=false;
			if($this->validatePostRequest()){ 
				$input_data = file_get_contents('php://input');
				$input_data =  get_object_vars(json_decode($input_data));
				 
				if(!isset($input_data['user_id'])){
					$response_array = array('message'=>'We need user id to precess this request.','status'=>0);
					header('Content-Type: application/json');
					echo json_encode($response_array);die;
				}
				$check_staff=$this->User->find('first',array('conditions'=>array('User.id'=>$input_data['user_id'],'User.is_delete'=>0)));
				if(empty($check_staff)){
					$response_array=array('message'=>'Invalid staff.','status'=>0);
					header('Content-Type: application/json');
					echo json_encode($response_array);die;
				}
				$this->User->id = $input_data['user_id'];
				$save_password = $this->User->field('password');
				
				$newHash = Security::hash(@$input_data['old_password'],'blowfish',$save_password);
				if($save_password!=$newHash){
					$response_array=array('message'=>'Your old password did not match.','status'=>0);
					header('Content-Type: application/json');
					echo json_encode($response_array);die;
				}
				$pass_length = strlen(@$input_data['password']);
				if(!(($pass_length >= 8 )&&($pass_length <= 16))){
					$response_array=array('message'=>'Your password length should be between 8 and 16 characters.','status'=>0);
					header('Content-Type: application/json');
					echo json_encode($response_array);die;
				}
			
				if(@$input_data['password'] != @$input_data['confirm_password']){
					$response_array=array('message'=>'Your password and confirm password does not match.','status'=>0);
					header('Content-Type: application/json');
					echo json_encode($response_array);die;
				}
						
				$save_data =  array(
					'password' => @$input_data['password'],
					'id' => $input_data['user_id']
					);
					 
				$result = $this->User->save($save_data);
				if($result){
					/* $result['User']['user_id'] = $result['User']['id'];
					unset($result['User']['id']); */
					$response_array = array('message'=>'Your password has been updated successfully.','status'=>1);
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
	die;
	
	}
	
	
	
	//API for changing patient status
	public function change_status(){
		if($this->check_key()){
			$this->layout=false;
		if($this->validatePostRequest()){ 
			$input_data = file_get_contents('php://input');
			$input_data =  get_object_vars(json_decode($input_data));
			
			 if(@$input_data['status']==1){
				 @$input_data['status']=0;
			 }elseif(@$input_data['status']==0){
				  @$input_data['status']=1;
			 }else{
				 $response_array = array('message'=>'Invalid status.','status'=>0);
				header('Content-Type: application/json');
				echo json_encode($response_array);die;
			 }
			$save_data['Patients'] =  array(
				'id' => @$input_data['patient_id'],
				'status' => @$input_data['status']
				);
			$result = $this->Patients->save($save_data);
			if($result){
				$result['Patients']['patient_id'] = $result['Patients']['id'];
				unset($result['Patients']['id']);
				$response_array = array('message'=>'Patients Status Changed successfully.','status'=>1,'data' => $result['Patients']);
				header('Content-Type: application/json');
				echo json_encode($response_array);die;
			}
			else{
				$response_array = array('message'=>'Some problems occured during process. Please try again.','status'=>0);
				header('Content-Type: application/json');
				echo json_encode($response_array);die;
			}	
				
		}
		}
	}
	
	 
	/* API for all test detail */
	public function test_list(){
		if($this->check_key()){
			$this->layout=false;
			if($this->validateGetRequest()){
				$result = $this->Tests->find('all',array('conditions'=>array('Tests.status'=>1),'order'=>array('Tests.id DESC')));
				if(count($result)){
					$data = array();
					foreach($result as $key => $value){
						$value['Tests']['test_id'] = $value['Tests']['id'];
						unset($value['Tests']['id']);
						$data[] = $value['Tests'];
					}
					$response_array = array('message'=>'Get tests information.','status'=>1,'data' => $data);
					header('Content-Type: application/json');
					echo json_encode($response_array);die;
				}else{
					$response_array = array('message'=>'No Record found.','status'=>0);
					header('Content-Type: application/json');
					echo json_encode($response_array);die;
				}
			}
		}
	}
	
	/* API for List patients by staff */
	public function listPatients(){
		if($this->check_key()){
			$this->layout=false;
			if($this->validatePostRequest()){
			$getjson = file_get_contents('php://input');
			$data_arrays = json_decode($getjson, true);
			// staff ID
			if(isset($data_arrays['page'])){
				if($data_arrays['page']==0){
					$limit='';
					$start=0;
				}elseif($data_arrays['page']==1){
					$limit=$data_arrays['page']*25+1;
					$start=0;
					$end=$data_arrays['page']*25-1;
				}else{
					$limit=$data_arrays['page']*25+1;
					$start=($data_arrays['page']-1)*25;
					$end=$data_arrays['page']*25-1;
				}
			}else{
				$limit='';
				$start=0;
			}
			if(isset($data_arrays['staff_id'])){
				$staff_id=$data_arrays['staff_id'];
				$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$staff_id),'fields'=>array('User.office_id')));
				if(!empty($office_id)){
					$all_staff_ids=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id']),'fields'=>array('User.id')));
					$all_staff_ids=implode(",",$all_staff_ids); 
					$all_staff_ids=explode(',',$all_staff_ids);
					$result =$this->Patients->find('all',array('conditions'=>array('Patients.user_id'=>$all_staff_ids,'Patients.is_delete'=>0,'Patients.status'=>1),'order'=>array('Patients.id DESC'),'limit'=>$limit));
				}else{
					$response_array['message']='Invalid staff.';
					$response_array['result']=0;
					echo json_encode($response_array);die;
				}
			}else{
				$response_array=array('message'=>'Please send staff id.','status'=>0);
				header('Content-Type: application/json');
				echo json_encode($response_array);die;
				//$result = $this->Patients->find('all',array('conditions'=>array('Patients.is_delete'=>0),'order'=>array('Patients.first_name ASC','Patients.middle_name ASC','Patients.last_name ASC'),'limit'=>$limit));
			}				
				
			$all_result_count=count($result);
					
			if(isset($data_arrays['page'])&&($data_arrays['page']==0)){
				$end=$all_result_count;
			}
			if(!isset($data_arrays['page'])){
				
				$end=$all_result_count;
			}
			if(isset($data_arrays['page'])){
				if($data_arrays['page']!=0){
					if(($all_result_count>$data_arrays['page']*25)){
						$more_data=1;
					}else{
						$more_data=0;
					}
				}else{
					$more_data=0;
				}
			}else{
				$more_data=0;
			}
			if(count($result))
			{
				$data = array();
				foreach($result as $key => $value){
					if($key>=$start && $key<=$end){
						$value['Patients']['patient_id'] = $value['Patients']['id'];
						$value['Patients']['middle_name']=($value['Patients']['middle_name']!=null)?($value['Patients']['middle_name']):'';
						$value['Patients']['phone']=($value['Patients']['phone']!=null)?($value['Patients']['phone']):'';
						$value['Patients']['id_number']=($value['Patients']['id_number']!=null)?($value['Patients']['id_number']):'';
						$value['Patients']['notes']=($value['Patients']['notes']!=null)?($value['Patients']['notes']):'';
						unset($value['Patients']['id']);
						$data[] = $value['Patients'];
					}
					
				}
				if(!empty($data)){
					$response_array=array('message'=>'Get patients information.','status'=>1,'more_data'=>$more_data,'data' => $data);
					header('Content-Type: application/json');
					echo json_encode($response_array);die;
				}else{
					$response_array = array('message'=>'No record found.','status'=>0);
					header('Content-Type: application/json');
					echo json_encode($response_array);die;
					
				}
					
			}
			else{
					$response_array = array('message'=>'No Record found.','status'=>0);
					header('Content-Type: application/json');
					echo json_encode($response_array);die;
			}
			}
		}

	}
	
	/* API for get patient detail */
	public function getPatients(){
		 
		 if($this->check_key()){
			$this->layout=false;
			if($this->validateGetRequest()){ 
				$input_data =  $this->request->params['pass'][0]; 
				if(isset($input_data)){
					$result = $this->Patients->find('first',array('conditions'=>array('Patients.id' => $input_data,'Patients.is_delete'=>0)));
 
					if(count($result)){
						$result['Patients']['patient_id'] = $result['Patients']['id'];
						unset($result['Patients']['id']);
						$response_array = array('message'=>'Patient Information.','status'=>1,'data' => $result['Patients'] );
						header('Content-Type: application/json');
						echo json_encode($response_array);die;
					}else{
						$response_array = array('message'=>'No Record found.','status'=>0);
						header('Content-Type: application/json');
						echo json_encode($response_array);die;
					}
				}else{
					$response_array = array('message'=>'please send required parameter.','status'=>0);
					header('Content-Type: application/json');
					echo json_encode($response_array);die;
				}
			}
		 }
	}
	
	
	
	/* API for Delete patients */
	public function deletePatients(){
		if($this->check_key()){
		$this->layout=false;
		 if($this->validatePostRequest()){
			$input_datas = file_get_contents('php://input');
			$input_data =  get_object_vars(json_decode($input_datas));
			  $input_data = @$input_data['patient_id'];  
			if(isset($input_data)){
				$delete_patient['Patients']['id']=$input_data;
				$delete_patient['Patients']['is_delete']=1;
				if($this->Patients->save($delete_patient))
				{
					$response_array['status'] = 1;
					$response_array['message'] = 'Record deleted Successfully';	
					header('Content-Type: application/json');
					echo json_encode($response_array);die;
				}
				else{
					$response_array['status'] = 0;
					$response_array['message'] = 'No record found';	
					header('Content-Type: application/json');
					echo json_encode($response_array);die;
				}
				 
			}
			else{
					$response_array = array('message'=>'Something went wrong.  Please try again.','status'=>0);
					header('Content-Type: application/json');
					echo json_encode($response_array);die;
				}
			die;
		 }
	}
	}
	
	//This API for saving and updating device id and device token of user
	public function get_device(){	
		$this->autoRender=false;
		$this->loadModel('UserDevice');	
		$response=array();
		$getjson = file_get_contents('php://input');
		$data_arrays = json_decode($getjson, true);
		
		$save_data['UserDevice']['user_id']=isset($data_arrays['user_id'])?$data_arrays['user_id']:'';
		$save_data['UserDevice']['device_type']=isset($data_arrays['device_type'])?$data_arrays['device_type']:'';
		$save_data['UserDevice']['device_id']=isset($data_arrays['device_id'])?$data_arrays['device_id']:'';
		$save_data['UserDevice']['device_token']=isset($data_arrays['device_token'])?$data_arrays['device_token']:'';
		if((!empty($save_data['UserDevice']['user_id']))&&(!empty($save_data['UserDevice']['device_id']))&&(!empty($save_data['UserDevice']['device_type']))&&(!empty($save_data['UserDevice']['device_token']))){
			
			$check=$this->UserDevice->find('first',array('conditions'=>array('UserDevice.device_token'=>$save_data['UserDevice']['device_token'],'UserDevice.device_type'=>$save_data['UserDevice']['device_type'])));
			if(empty($check)){
				
				$this->UserDevice->save($save_data);
				$id=$this->UserDevice->id;
				$response['message']='Device id saved successfully.';
				$response['result']=1;
				$response['data']=array('id'=>$id);
			}else{
				$this->UserDevice->id = $check['UserDevice']['id'];
				$this->UserDevice->save($save_data); 
				$response['message']='Device id saved successfully.';
				$response['result']=1;
			}
			
		} else  {
			$response['message']='Some fields empty.';
			$response['result']=0;
		}
		echo json_encode($response);
		
		exit();
	}
	
	
	//This Api for getting Patients data by office(staff_id given)
	public function get_Patients_by_office(){	
	  if($this->check_key()){
		$this->layout=false;
		if($this->validatePostRequest()){
			$this->autoRender=false;
			$response=array();
			$getjson = file_get_contents('php://input');
			$data_arrays = json_decode($getjson, true);
			if((isset($data_arrays['staff_id']))&&(!empty($data_arrays['staff_id']))){
				$staff_id=$data_arrays['staff_id'];
				$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$staff_id),'fields'=>array('User.office_id')));
				if(!empty($office_id)){
					$all_staff_ids=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id']),'fields'=>array('User.id')));
					$all_staff_ids=implode(",",$all_staff_ids); 
					//$all_staff_ids=$sub_admin_id['User']['created_by'].','.$all_staff_ids;
					$all_staff_ids=explode(',',$all_staff_ids);
					if(isset($data_arrays['page_count'])){
						$page_count=$data_arrays['page_count'];
					}else{
						$page_count=10;
					}
					if(isset($data_arrays['page'])){
						if($data_arrays['page']==0){
							$limit='';
							$start=0;
						}elseif($data_arrays['page']==1){
							$limit=$data_arrays['page']*$page_count+1;
							$start=0;
							$end=$data_arrays['page']*$page_count-1;
						}else{
							$limit=$data_arrays['page']*$page_count+1;
							$start=($data_arrays['page']-1)*$page_count;
							$end=$data_arrays['page']*$page_count-1;
						}
					}else{
						$limit='';
						$start=0;
					}
					
					
					$all_patients=$this->Patients->find('all',array('conditions'=>array('Patients.user_id'=>$all_staff_ids,'Patients.is_delete'=>0),'fields'=>array('Patients.id','Patients.first_name','Patients.middle_name','Patients.last_name'),'order'=>array('Patients.first_name ASC','Patients.middle_name ASC','Patients.last_name ASC'),'limit'=>$limit));
					
					$all_patients_count=count($all_patients);
					
					if(isset($data_arrays['page'])&&($data_arrays['page']==0)){
						$end=$all_patients_count;
					}
					if(!isset($data_arrays['page'])){
				
						$end=$all_patients_count;
					}
					if(isset($data_arrays['page'])){
						if($data_arrays['page']!=0){
							if(($all_patients_count>$data_arrays['page']*$page_count)){
								$more_data=1;
							}else{
								$more_data=0;
							}
						}else{
							$more_data=0;
						}
					}else{
						$more_data=0;
					}
					
					if(!empty($all_patients)){
						$data=array();
						$i=0;
						foreach($all_patients as $key=>$patient){
							if($key>=$start && $key<=$end){
								$data[$i]['patient_id']=$patient['Patients']['id'];
								$data[$i]['patient_first_name']=($patient['Patients']['first_name']!=null)?$patient['Patients']['first_name']:'';
								$data[$i]['patient_middle_name']=($patient['Patients']['middle_name']!=null)?$patient['Patients']['middle_name']:'';
								$data[$i]['patient_last_name']=($patient['Patients']['last_name']!=null)?$patient['Patients']['last_name']:'';
								$i++;
							}
						}
						if(!empty($data)){
							$response['message']='All Patients found successfully.';
							$response['result']=1;
							$response['more_data']=$more_data;
							$response['data']=$data;
						
						}else{
							$response['message']='No patient found.';
							$response['more_data']=$more_data;
							$response['result']=0;
						}
					
					}else{
						$response['message']='No patient found.';
						$response['result']=0;
					}
				}else{
					$response['message']='Invalid staff.';
					$response['result']=0;
				}
			
			} else  {
				$response['message']='Some fields empty.';
				$response['result']=0;
			}
			echo json_encode($response);
		
			exit();
		}
	  }
	}
	
	
	//This API for all notifications by staff_id
	public function get_staff_notification(){	
	  if($this->check_key()){
		$this->layout=false;
		if($this->validatePostRequest()){
			$this->autoRender=false;
			$response=array();
			$getjson = file_get_contents('php://input');
			$data_arrays = json_decode($getjson, true);
			if((isset($data_arrays['staff_id']))&&(!empty($data_arrays['staff_id']))){
				$staff_id=$data_arrays['staff_id'];
				$check_staff=$this->User->find('first',array('conditions'=>array('User.id'=>$staff_id)));
				if(!empty($check_staff)){
					if(isset($data_arrays['page'])){
						if($data_arrays['page']==0){
							$limit='';
							$start=0;
						}elseif($data_arrays['page']==1){
							$limit=$data_arrays['page']*10+1;
							$start=0;
							$end=$data_arrays['page']*10-1;
						}else{
							$limit=$data_arrays['page']*10+1;
							$start=($data_arrays['page']-1)*10;
							$end=$data_arrays['page']*10-1;
						}
					}else{
						$limit='';
						$start=0;
					}
					$all_notifications=$this->UserNotification->find('all',array('conditions'=>array('UserNotification.user_id'=>$staff_id),'fields'=>array('UserNotification.id','UserNotification.text','UserNotification.created','UserNotification.status')));
					$all_notification_count=count($all_notifications);
					
					if(isset($data_arrays['page'])&&($data_arrays['page']==0)){
						$end=$all_notification_count-1;
					}
					if(!isset($data_arrays['page'])){
						
						$end=$all_notification_count-1;
					}
					if(isset($data_arrays['page'])){
						if($data_arrays['page']!=0){
							if(($all_notification_count>$data_arrays['page']*10)){
								$more_data=1;
							}else{
								$more_data=0;
							}
						}else{
							$more_data=0;
						}
					}else{
						$more_data=0;
					}
					if(!empty($all_notifications)){
						$data=array();
						$i=0;
						foreach($all_notifications as $key=>$notification){
							if($key>=$start && $key<=$end){
								$data[$i]['id']=$notification['UserNotification']['id'];
								$data[$i]['text']=$notification['UserNotification']['text'];
								$data[$i]['created']=$notification['UserNotification']['created'];
								$data[$i]['status']=$notification['UserNotification']['status'];
								$i++;
							}
						}
						if(!empty($data)){
							$response['message']='All notification found successfully.';
							$response['result']=1;
							$response['more_data']=$more_data;
							$response['data']=$data;
						
						}else{
							$response['message']='No notification found.';
							$response['more_data']=$more_data;
							$response['result']=0;
						}
					
					}else{
						$response['message']='No notification found.';
						$response['more_data']=$more_data;
						$response['result']=0;
					}
				}else{
					$response['message']='Invalid staff.';
					$response['result']=0;
				}
			
			} else  {
				$response['message']='Some fields empty.';
				$response['result']=0;
			}
			echo json_encode($response);
		
			exit();
		}
	  }
	}
	
	
	
	/*API for staff detail*/
	public function get_staff_detail(){
		if($this->check_key()){
		 $this->layout=false;
		 if($this->validatePostRequest()){
			$this->autoRender=false;
			$response=array();
			$getjson = file_get_contents('php://input');
			$data_arrays = json_decode($getjson, true);
			if((isset($data_arrays['staff_id']))&&(!empty($data_arrays['staff_id']))){
				$staff_id=$data_arrays['staff_id'];
				$check_staff=$this->User->find('first',array('conditions'=>array('User.id'=>$staff_id,'User.user_type'=>'Staffuser','User.is_delete'=>0)));
				//print_r($check_staff);die;
				if(!empty($check_staff)){
					$data=array();
					$data['staff_first_name']=$check_staff['User']['first_name'];
					$data['staff_middle_name']=$check_staff['User']['middle_name'];
					$data['staff_last_name']=$check_staff['User']['last_name'];
					$data['staff_username']=$check_staff['User']['username'];
					$data['staff_email']=$check_staff['User']['email'];
					$data['staff_phone']=$check_staff['User']['phone'];
					$data['staff_gender']=$check_staff['User']['gender'];
					$data['staff_office_id']=$check_staff['User']['office_id'];
					$data['staff_dob']=$check_staff['User']['dob'];
					$data['staff_created']=$check_staff['User']['created'];
					$data['staff_office']=$check_staff['Office']['name'];
					$response['message']='Staff Detail.';
					$response['result']=1;
					$response['data']=$data;
					
				}else{
					$response['message']='Invalid staff.';
					$response['result']=0;
				}
			
			} else  {
				$response['message']='Some fields empty.';
				$response['result']=0;
			}
			echo json_encode($response);
		
			exit();
		}
	  }
	}
	
	
	/*API for update notification status*/
	public function update_notification_status(){
		if($this->check_key()){
			$this->layout=false;
			if($this->validatePostRequest()){
				$this->autoRender=false;
				$response=array();
				$getjson = file_get_contents('php://input');
				$data_arrays = json_decode($getjson, true);
				if(((isset($data_arrays['notification_id']))&&(!empty($data_arrays['notification_id'])))&&
					((isset($data_arrays['staff_id']))&&(!empty($data_arrays['staff_id'])))){
					$staff_id=$data_arrays['staff_id'];
					$check_staff=$this->User->find('first',array('conditions'=>array('User.id'=>$staff_id,'User.user_type'=>'Staffuser')));
					if(!empty($check_staff)){
						$notification_id=$data_arrays['notification_id'];
						$check_notification=$this->UserNotification->find('first',array('conditions'=>array('UserNotification.id'=>$notification_id,'UserNotification.user_id'=>$staff_id)));
						if(!empty($check_notification)){
							$data=array();
							$save_data=array(
								'id' => $notification_id,
								'status' => 1
							);
							$restult=$this->UserNotification->save($save_data);
							if($restult){
								$response['message']='You read Notification.';
								$response['result']=1;
							}else{
								$response['message']='Some error occured in reading notification.';
								$response['result']=0;
							}
						
					
						}else{
							$response['message']='Invalid notification.';
							$response['result']=0;
						}
					}else{
						$response['message']='Invalid Staff.';
						$response['result']=0;
					}
			
				} else  {
					$response['message']='Some fields empty.';
					$response['result']=0;
				}
				echo json_encode($response);
			
				exit();
			}
		}
	}
	
	/*API for Delete multiple notification*/
	public function delete_notifications(){
		if($this->check_key()){
			$this->layout=false;
			if($this->validatePostRequest()){
				$this->autoRender=false;
				$response=array();
				$getjson = file_get_contents('php://input');
				$data_arrays = json_decode($getjson, true);
				if(((isset($data_arrays['data']))&&(!empty($data_arrays['data'])))&&
					((isset($data_arrays['staff_id']))&&(!empty($data_arrays['staff_id'])))){
					$staff_id=$data_arrays['staff_id'];
					$check_staff=$this->User->find('first',array('conditions'=>array('User.id'=>$staff_id,'User.user_type'=>'Staffuser')));
					if(!empty($check_staff)){
						foreach($data_arrays['data'] as $data){
							$this->UserNotification->delete($data['id']);
						}
						
						$response['message']='Notifications deleted successfully.';
						$response['result']=1;
					}else{
						$response['message']='Invalid Staff.';
						$response['result']=0;
					}
				} else  {
					$response['message']='Some fields empty.';
					$response['result']=0;
				}
				echo json_encode($response);
			
				exit();
			}
		}
	}
	
	/*API for logout*/
	public function logout(){
		if($this->check_key()){
			$this->layout=false;
			if($this->validatePostRequest()){
				$this->autoRender=false;
				$response=array();
				$getjson = file_get_contents('php://input');
				$data_arrays = json_decode($getjson, true);
				if(((isset($data_arrays['user_id']))&&(!empty($data_arrays['user_id'])))&&
					((isset($data_arrays['device_id']))&&(!empty($data_arrays['device_id'])))){
					
					$check_user=$this->User->find('first',array('conditions'=>array('User.id'=>$data_arrays['user_id'])));
					if(!empty($check_user)){
						
						$check_login_status=$this->UserDevice->find('first',array('conditions'=>array('UserDevice.user_id'=>$data_arrays['user_id'],'UserDevice.device_id'=>$data_arrays['device_id']),'fields'=>array('UserDevice.id')));
						if(!empty($check_login_status)){
							$result=$this->UserDevice->delete($check_login_status['UserDevice']['id']);
							if($result){
								$response['message']='Logout successfully.';
								$response['result']=1;
							}else{
								$response['message']='Error occured in logout';
								$response['result']=1;
							}
						}else{
							$response['message']='Invalid Detail.';
							$response['result']=0;
						}
					}else{
						$response['message']='Invalid user.';
						$response['result']=0;
					}
				} else  {
					$response['message']='Some fields empty.';
					$response['result']=0;
				}
				echo json_encode($response);
			
				exit();
			}
		}
	}
	
	
	/*API for saving test report*/
	public function save_test_report(){
		if($this->check_key()){
			$this->layout=false;
			if($this->validatePostRequest()){
				$this->autoRender=false;
				$response=array();
				$getjson = file_get_contents('php://input');
				$data_arrays = json_decode($getjson, true);
				if(((isset($data_arrays['test_id']))&&(!empty($data_arrays['test_id'])))&&
					((isset($data_arrays['staff_id']))&&(!empty($data_arrays['staff_id'])))&&
					((isset($data_arrays['patient_id']))&&(!empty($data_arrays['patient_id'])))&&
					((isset($data_arrays['result']))&&(!empty($data_arrays['result'])))){
					
					$data['TestReport']['test_id']=@$data_arrays['test_id'];
					//$data['TestReport']['practice_id']=@$data_arrays['practice_id'];
					$data['TestReport']['staff_id']=@$data_arrays['staff_id'];
					$data['TestReport']['patient_id']=@$data_arrays['patient_id'];
					$data['TestReport']['result']=@$data_arrays['result'];
					$result=$this->TestReport->save($data);
					if($result){
						$response['message']='Test report saved successfully.';
						$response['result']=1;
					}else{
						$response['message']='Some error occured in saving report.';
						$response['result']=0;
					}
					
				} else {
					$response['message']='Some fields empty.';
					$response['result']=0;
				}
				echo json_encode($response);
			
				exit();
			}
		}
	}
	
	
	/*Updated version of API for saving test report*/
	public function save_test_report_V1(){
		if($this->check_key()){
			$this->layout=false;
			if($this->validatePostRequest()){
				$this->autoRender=false;
				$response=array();
				$getjson = file_get_contents('php://input');
				$data_arrays = json_decode($getjson, true);
				if(((isset($data_arrays['test_id']))&&(!empty($data_arrays['test_id'])))&&
					((isset($data_arrays['staff_id']))&&(!empty($data_arrays['staff_id'])))&&
					((isset($data_arrays['patient_id']))&&(!empty($data_arrays['patient_id'])))&&
					((isset($data_arrays['result']))&&(!empty($data_arrays['result'])))){
					
					$data['TestReport']['test_id']=@$data_arrays['test_id'];
					//$data['TestReport']['practice_id']=@$data_arrays['practice_id'];
					$data['TestReport']['staff_id']=@$data_arrays['staff_id'];
					$data['TestReport']['patient_id']=@$data_arrays['patient_id'];
					$data['TestReport']['result']=@$data_arrays['result'];
					//
					if(isset($data_arrays['pdf'])&&(!empty($data_arrays['pdf']))){
						$folder_name="uploads/pdf";
						$data['TestReport']['pdf']=$this->base64_to_pdf($data_arrays['pdf'],$folder_name);
					}
					//print_r($data);die;
					$result=$this->TestReport->save($data);
					if($result){
						$response['message']='Test report saved successfully.';
						$response['result']=1;
					}else{
						$response['message']='Some error occured in saving report.';
						$response['result']=0;
					}
					
				} else {
					$response['message']='Some fields empty.';
					$response['result']=0;
				}
				echo json_encode($response);
			
				exit();
			}
		}
	}
	
	
	/* Updated version of save_test_report_V1 API */
	public function save_test_report_V2(){
		if($this->check_key()){
			$this->layout=false;
			if($this->validatePostRequest()){
				$this->autoRender=false;
				$response=array();
				$getjson = file_get_contents('php://input');
				$data_arrays = json_decode($getjson, true);
				if((isset($data_arrays['data']))&&(!empty($data_arrays['data']))){
					$i=0;
					foreach($data_arrays['data'] as $key=>$report){
						$data['TestReport'][$i]['test_id']=@$report['test_id'];
						$data['TestReport'][$i]['staff_id']=@$report['staff_id'];
						$data['TestReport'][$i]['patient_id']=@$report['patient_id'];
						$data['TestReport'][$i]['result']=@$report['result'];
						if(isset($report['pdf'])&&(!empty($report['pdf']))){
							$folder_name="uploads/pdf";
							$data['TestReport'][$i]['pdf']=$this->base64_to_pdf($report['pdf'],$folder_name);
						}
						$i++;
					}
					if($this->TestReport->saveAll($data['TestReport'])){
						$response['message']='Test reports saved successfully.';
						$response['result']=1;
					}else{
						$response['message']='Some error occured in saving report.';
						$response['result']=0;
					}
					
				} else {
					$response['message']='Please send some reports.';
					$response['result']=0;
				}
				echo json_encode($response);
			
				exit();
			}
		}
	}
	
	/*API for getting test report by office(staff_id given)*/
	public function get_test_report(){
		if($this->check_key()){
			$this->layout=false;
			if($this->validatePostRequest()){
				$this->autoRender=false;
				$response=array();
				$getjson = file_get_contents('php://input');
				$data_arrays = json_decode($getjson, true);
				if(isset($data_arrays['page'])&&(isset($data_arrays['staff_id'])&&(!empty($data_arrays['staff_id'])))){
					if($data_arrays['page']==0){
						$limit='';
						$start=0;
					}elseif($data_arrays['page']==1){
						$limit=$data_arrays['page']*10+1;
						$start=0;
						$end=$data_arrays['page']*10-1;
					}else{
						$limit=$data_arrays['page']*10+1;
						$start=($data_arrays['page']-1)*10;
						$end=$data_arrays['page']*10-1;
					}
					
					$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$data_arrays['staff_id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.office_id')));
					if(empty($office_id)){
						$response['message']='Invalid staff.';
						$response['result']=0;
						echo json_encode($response);die;
					}
					$all_staff_ids=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.id')));
					 
					$this->TestReport->virtualFields['test_name'] = 'select name from mmd_tests as tests where TestReport.test_id = tests.id';
					//$this->TestReport->virtualFields['practice_name'] = 'select name from mmd_practices as practices where TestReport.practice_id = practices.id';
					$this->TestReport->virtualFields['patient_name'] = 'select concat(first_name," ",middle_name," ",last_name) from mmd_patients as patients where TestReport.patient_id = patients.id';
					$this->TestReport->virtualFields['staff_name'] = 'select concat(first_name," ",middle_name," ",last_name) as name from mmd_users as users where TestReport.staff_id = users.id';
					
					$condition['TestReport.staff_id'] = $all_staff_ids;
					if(isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])){
						$condition['TestReport.patient_id'] = $data_arrays['patient_id'];
					}
					if(isset($data_arrays['master_key'])){
						$condition['TestReport.master_key'] = $data_arrays['master_key'];
					}
					
					$results=$this->TestReport->find('all',array('conditions'=>$condition,'fields'=>array('id','test_name','created','result','patient_name','staff_name','pdf'),'order'=>array('TestReport.id DESC'),'limit'=>$limit));
					
					if($data_arrays['page']!=0){
						if((count($results)>$data_arrays['page']*10)){
							$more_data=1;
						}else{
							$more_data=0;
						}
					}else{
						$more_data=0;
					}
					if(!empty($results)){
						$data=array();
						if($data_arrays['page']==0){
							$end=count($results)-1;
						}
						$i=0;
						foreach($results as $key=>$result){
							if($key>=$start && $key<=$end){
								$data[$i]=$result['TestReport'];
								$data[$i]['patient_name']=($result['TestReport']['patient_name']!=null)?($result['TestReport']['patient_name']):'';
								if(!empty($result['TestReport']['pdf'])){
									$data[$i]['pdf']=WWW_BASE.'uploads/pdf/'.$result['TestReport']['pdf'];
								}
							
								$i++;
							}
							
						}
						if(!empty($data)){
							$response['message']='All test report list.';
							$response['result']=1;
							$response['more_data']=$more_data;
							$response['data']=$data;
						}else{
							$response['message']='No test report found.';
							$response['more_data']=$more_data;
							$response['result']=0;
						}
					}else{
						$response['message']='NO test report found.';
						$response['result']=0;
					}
				}else{
					$response['message']='Some fields empty.';
					$response['result']=0;
				}
					
				
				echo json_encode($response);
			
				exit();
			}
		}
	}
	
	/*API for fetching test device */
	public function get_test_device(){
		if($this->check_key()){
			$this->layout=false;
			if($this->validateGetRequest()){
				$this->autoRender=false;
				$response=array();
				$input_data['office_id'] = @$this->request->params['pass'][0];
				if(isset($input_data['office_id'])&&(!empty($input_data['office_id']))){
					$result=$this->TestDevice->find('all',array('conditions'=>array('TestDevice.office_id'=>$input_data['office_id'],'TestDevice.status'=>1),'order'=>array('TestDevice.id DESC')));
						
					if(!empty($result)){
						$data=array();
						foreach($result as $key=>$result){
							$data[]=$result['TestDevice'];
						}
						if(!empty($data)){
							$response['message']='All test device list.';
							$response['result']=1;
							$response['data']=$data;
						}else{
							$response['message']='No test device found.';
							$response['result']=0;
						}
					}else{
						$response['message']='No test device found.';
						$response['result']=0;
					}
				}else{
					$response['message']='Please send office id.';
					$response['result']=0;
				}
				
				echo json_encode($response);
			
				exit();
			}
		}
	}
	
	/*API for fetching practice name*/
	public function get_practices(){
		if($this->check_key()){
			$this->layout=false;
			if($this->validateGetRequest()){
				$this->autoRender=false;
				$response=array();
				
					$result=$this->Practice->find('all');
					
					if(!empty($result)){
						$data=array();
						foreach($result as $key=>$result){
							$data[]=$result['Practice'];
						}
						if(!empty($data)){
							$response['message']='All practice list.';
							$response['result']=1;
							$response['data']=$data;
						}else{
							$response['message']='No practice found.';
							$response['result']=0;
						}
					}else{
						$response['message']='NO practice found.';
						$response['result']=0;
					}
				
				echo json_encode($response);
			
				exit();
			}
		}
	}
	
	
	/*API for adding device by staff*/
	
	public function add_test_device(){
		if($this->check_key()){
			$this->layout=false;
			if($this->validatePostRequest()){
				$this->autoRender=false;
				$response=array();
				$getjson = file_get_contents('php://input');
				$data_arrays = json_decode($getjson, true);
				if(((!empty($data_arrays['name'])))&&(!empty($data_arrays['ip_address']))&&
					(!empty($data_arrays['status']))&&(!empty($data_arrays['office_id']))){
					
					//$ipaddress_okay=preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/',trim($data_arrays['ip_address']));
					$check_name_ipadd=$this->TestDevice->find('first',array('conditions'=>array('TestDevice.name'=>$data_arrays['name'])));
					/* if(($ipaddress_okay)){ */
						if(empty($check_name_ipadd)){
							$data['TestDevice']['name']=@$data_arrays['name'];
							$data['TestDevice']['ip_address']=@$data_arrays['ip_address'];
							$data['TestDevice']['status']=@$data_arrays['status'];
							$data['TestDevice']['office_id']=@$data_arrays['office_id'];
							$result=$this->TestDevice->save($data,false);
							if($result){
								$response['message']='Test Device has been saved successfully.';
								$response['result']=1;
							}else{
								$response['message']='Some error occured in saving test device.';
								$response['result']=0;
							}
						}else{
							$response['message']='Device name already exits.';
							$response['result']=0;
						}	
					/* }else{
						$response['message']='Ip address is invalid.';
						$response['result']=0;
					} */
					
				} else {
					$response['message']='Some fields empty.';
					$response['result']=0;
				}
				
				echo json_encode($response);
			
				exit();
			}
		}
	}
	
	
	
	/*API for Editing device by staff*/
	
	public function Edit_test_device(){
		if($this->check_key()){
			$this->layout=false;
			if($this->validatePostRequest()){
				$this->autoRender=false;
				$response=array();
				$getjson = file_get_contents('php://input');
				$data_arrays = json_decode($getjson, true);
				if(((isset($data_arrays['device_id'])))&&(!empty($data_arrays['device_id']))){
					
					//$ipaddress_okay=preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/',trim($data_arrays['ip_address']));
					$check_device=$this->TestDevice->find('first',array('conditions'=>array('TestDevice.id'=>$data_arrays['device_id'])));
					/* if(($ipaddress_okay)){ */
						if(!empty($check_device)){
							$condition=array();
							$data['TestDevice']['id']=@$data_arrays['device_id'];
							if(isset($data_arrays['name'])&&(!empty($data_arrays['name']))){
								$data['TestDevice']['name']=@$data_arrays['name'];
								$condition[]=array('TestDevice.name'=>$data_arrays['name']);
							}
							if(isset($data_arrays['ip_address'])&&(!empty($data_arrays['ip_address']))){
								$data['TestDevice']['ip_address']=@$data_arrays['ip_address'];
								//$condition['OR']=array('TestDevice.ip_address'=>$data_arrays['ip_address']);
							}
							if(!empty($condition)){
								$condition['AND']=array('TestDevice.id !='=>$data_arrays['device_id']);
								$check_device=$this->TestDevice->find('first',array('conditions'=>$condition));
								if(!empty($check_device)){
									$response['message']='Device name already exist.Please try again.';
									$response['result']=0;
									echo json_encode($response);die;
								}
							}
							if(isset($data_arrays['status'])&&(!empty($data_arrays['status']))){
								$data['TestDevice']['status']=@$data_arrays['status'];
							}
							if(isset($data_arrays['office_id'])&&(!empty($data_arrays['office_id']))){
								$data['TestDevice']['office_id']=@$data_arrays['office_id'];
							}
							$result=$this->TestDevice->save($data);
							if($result){
								$response['message']='Test Device has been Updated successfully.';
								$response['result']=1;
							}else{
								$response['message']='Some error occured in updating test device.';
								$response['result']=0;
							}
						}else{
							$response['message']='Invalid device.';
							$response['result']=0;
						}	
					/* }else{
						$response['message']='Ip address is invalid.';
						$response['result']=0;
					} */
					
				} else {
					$response['message']='Please send device id.';
					$response['result']=0;
				}
				
				echo json_encode($response);
			
				exit();
			}
		}
	}
	
	/* Uploads Files For test report */
	public function save_test_report_files_v3() {
		
		$this->layout=false;
		$response=array();

		$id = @$this->request->data['test_report_id'];
		$testtype = @$this->request->data['testType'];
		
		if(!empty($id)) {
			 
			if( (!empty($_FILES['file']['name'])) ) {
				if(!is_dir('uploads/files')){
				  mkdir('uploads/files',0777,true);
				}
				//$data_to_save = array();
				if(isset($_FILES['file']['name'])&&(!empty($_FILES['file']['name']))){
					$tmp_name =  $_FILES['file']['tmp_name'];
					$name	  =  $_FILES['file']['name'];
					move_uploaded_file($tmp_name,"uploads/files/$name");
				} 
				
				if(!file_exists($tmp_name) || !is_uploaded_file($tmp_name)) {					
					$file_data = array();
					$file_data['test_report_id'] = $id;
					$file_data['eye'] = @$testtype;
					$file_data['file_path'] = "uploads/files/$name";
					$insert_res = $this->Files->save($file_data);
					if($insert_res) {
						$response['message'] = "File has been uploaded successfully.";
						$response['code'] = 1;
					} else {
						$response['message'] = "Something went wrong while uploading file.";
						$response['code'] = 0;
					}
				} else {
					$response['message'] = "Something went wrong while uploading file.";
					$response['code'] = 404;
				}
			} else {
				$response['message']='File can\'t be empty .';
				$response['result']=0;
			}
		} else {
			$response['message']='Test report id can\'t be empty.';
			$response['result']=0;
		}
	
		echo json_encode($response); die;
	}
	
	/*API for getting test report and files by office(staff_id given)*/
	public function get_test_report_v3(){
		 if($this->check_key()){
			$this->layout=false;
			if($this->validatePostRequest()){
				$this->autoRender=false;
				$response=array();
				$getjson = file_get_contents('php://input');
				$data_arrays = json_decode($getjson, true);
				if(isset($data_arrays['page'])&&(isset($data_arrays['staff_id'])&&(!empty($data_arrays['staff_id'])))){
					if($data_arrays['page']==0){
						$limit='';
						$start=0;
					}elseif($data_arrays['page']==1){
						$limit=$data_arrays['page']*10+1;
						$start=0;
						$end=$data_arrays['page']*10-1;
					}else{
						$limit=$data_arrays['page']*10+1;
						$start=($data_arrays['page']-1)*10;
						$end=$data_arrays['page']*10-1;
					}
					
					$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$data_arrays['staff_id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.office_id')));
					if(empty($office_id)){
						$response['message']='Invalid staff.';
						$response['result']=0;
						echo json_encode($response);die;
					}
					$all_staff_ids=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.id')));
					 
					$this->TestReport->virtualFields['test_name'] = 'select name from mmd_tests as tests where TestReport.test_id = tests.id';
					//$this->TestReport->virtualFields['practice_name'] = 'select name from mmd_practices as practices where TestReport.practice_id = practices.id';
					$this->TestReport->virtualFields['patient_name'] = 'select concat(first_name," ",middle_name," ",last_name) from mmd_patients as patients where TestReport.patient_id = patients.id';
					$this->TestReport->virtualFields['staff_name'] = 'select concat(first_name," ",middle_name," ",last_name) as name from mmd_users as users where TestReport.staff_id = users.id';
					
					$condition['TestReport.staff_id'] = $all_staff_ids;
					if(isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])){
						$condition['TestReport.patient_id'] = $data_arrays['patient_id'];
					}
					if(isset($data_arrays['master_key'])){
						$condition['TestReport.master_key'] = $data_arrays['master_key'];
					}
					
					$results=$this->TestReport->find('all',array('conditions'=>$condition,'fields'=>array('id','test_name','result','created','patient_name','staff_name','pdf'),'order'=>array('TestReport.id DESC'),'limit'=>$limit));
					
					if($data_arrays['page']!=0){
						if((count($results)>$data_arrays['page']*10)){
							$more_data=1;
						}else{
							$more_data=0;
						}
					}else{
						$more_data=0;
					}
					if(!empty($results)){
						$data=array();
						if($data_arrays['page']==0){
							$end=count($results)-1;
						}
						$i=0;
						foreach($results as $key=>$result){
							if($key >= $start && $key <= $end){
								$data[$i]=$result['TestReport'];
								$data[$i]['patient_name']=($result['TestReport']['patient_name']!=null)?($result['TestReport']['patient_name']):'';
								if(!empty($result['TestReport']['pdf'])){
									$data[$i]['pdf']=WWW_BASE.'uploads/pdf/'.$result['TestReport']['pdf'];
								}
								
								
								$files = $this->Files->find('all',array('conditions'=>array('Files.test_report_id'=>$result['TestReport']['id']),'fields'=>array('Files.id','Files.file_path')));
								
								
								if(count($files)) {
									$file_arr = array();
									foreach($files as $fi) {
										$fi['Files']['file_path'] = WWW_BASE.$fi['Files']['file_path'];
										$file_arr[] = $fi['Files'];
									}
									$data[$i]['files'] =$file_arr;
								} else {
									
									$data[$i]['files']  = array();
									
								}
								
							
								$i++;
							}
							
						}
						if(!empty($data)){
							$response['message']='All test report list.';
							$response['result']=1;
							$response['more_data']=$more_data;
							$response['data']=$data;
						}else{
							$response['message']='No test report found.';
							$response['more_data']=$more_data;
							$response['result']=0;
						}
					}else{
						$response['message']='NO test report found.';
						$response['result']=0;
					}
				}else{
					$response['message']='Some fields empty.';
					$response['result']=0;
				}
					
				header('Content-Type: application/json');
				
				echo json_encode($response);
			
				exit();
			}
		 }
	}
	
	/*API for saving test report and file written BY prince kumar dwivedi*/
	
	public function save_test_report_v3() {
		
		$this->layout=false;
		$response=array();
		
		if($this->check_key()){
			
			if($this->validatePostRequest()){
             $getjson = file_get_contents('php://input');
				$data_arrays = json_decode($getjson, true);
				//$data_arrays =$this->request->data;
				
				if(((isset($data_arrays['testType'])))&&
					((isset($data_arrays['staffId']))&&(!empty($data_arrays['staffId'])))&&
					((isset($data_arrays['patientId']))&&(!empty($data_arrays['patientId'])))){
					
					$data['TestReport']['test_id']=@$data_arrays['testType'];
					$data['TestReport']['staff_id']=@$data_arrays['staffId'];
					$data['TestReport']['patient_id']=@$data_arrays['patientId'];
					
					$result=$this->TestReport->save($data);
							
					if($result){
						
						$response['message']='Test report saved successfully.';
						$response['test_report_id']=$result['TestReport']['id'];
						$response['result']=1; 
						
					} else {
						
						$response['message']='Some error occured in saving report.';
						$response['result']=0;
					}
				} else {
					$response['message']='Some required fields is empty.';
					$response['result']=0;
				}
				
				header('Content-Type: application/json');
				echo json_encode($response); die;
			}
		}
		exit();
	}
		/* Uploads Files For patient report */
	public function patient_profile_pic() {
		
		$this->layout=false;
		$response=array();
		$id = @$this->request->data['patient_id'];
		if(empty($id)){
			$response['message']=' Id can\'t be empty.';
			$response['result']=0;
			echo json_encode($response); die;
			
		}
		if(empty($_FILES['file']['name'])){
			$response['message']=' file can\'t be empty.';
			$response['result']=0;
			echo json_encode($response); die;
			
		}
		if(!empty($id)) {
			 
		/* 	if( (!empty($_FILES['file']['name'])) ) {
				if(!is_dir('uploads/files')){
				  mkdir('uploads/files',0777,true);
				} */
				//$data_to_save = array();
				if(isset($_FILES['file']['name'])&&(!empty($_FILES['file']['name']))){
					$tmp_name =  $_FILES['file']['tmp_name'];
					$name	  =  $id .'_'. $_FILES['file']['name'];
					$x = move_uploaded_file($tmp_name,"uploads/profile/patient/$name");
				} 

				if(!file_exists($tmp_name) || !is_uploaded_file($tmp_name)) {
					
					//$file_data = array();
					//$file_data['patient_id'] = $id;
					$ppic_path = "uploads/profile/patient/$name";
						
					$this->Patients->id=$id;                
					$this->Patients->set(array('p_profilepic'=>$ppic_path)); 
					//$insert_res = $this->Patients->save($file_data);
					$insert_res = $this->Patients->save();
					if($insert_res) {
						$response['message'] = "File has been uploaded successfully.";
						$response['code'] = 1;
					} else {
						$response['message'] = "Something went wrong while uploading file.";
						$response['code'] = 0;
					}
				} else {
					$response['message'] = "Something went wrong while uploading file.";
					$response['code'] = 404;
				}
			} else {
				$response['message']='File can\'t be empty .';
				$response['result']=0;
			}
			echo json_encode($response); die;
		}
	/* public function delete_test_report_files_v3() {
		
		$this->autoRender =false;
		$response=array();
		
		if($this->check_key()){
			if($this->validatePostRequest()){
				$getjson = file_get_contents('php://input');
				$data_arrays = json_decode($getjson, true);
				if((isset($data_arrays['file_id']))&&(!empty($data_arrays['file_id']))) {
					$result = $this->Files->findById($data_arrays['file_id']);
					if(!empty($result)) {
						pr($result); die;
						$response['message']='Test report file has been deleted successfully.';
						$response['result']=1; 
					} else {
						$response['message']='Test report file unable to delete due to invalid request.';
						$response['result']=0; 
					}
					header('Content-Type: application/json');
					echo json_encode($response); die;
				}
			}
		}
		exit();   
	} */
} 

?>