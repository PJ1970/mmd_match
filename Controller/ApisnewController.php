<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
//App::import('Vendor','twilio-php-master',array('file' => 'twilio-php-master/Services/Twilio.php'));
ini_set('max_execution_time', 0);
class ApisnewController extends AppController
{
	public $uses = array('User', 'Patients', 'Tests', 'UserNotification', 'UserDevice', 'TestReport', 'TestDevice', 'Practice', 'Office', 'Files', 'VfPointdata', 'Pointdata', 'Masterdata', 'MasterPointdata', 'DevicePreference', 'Diagnosis', 'Cms', 'DarkAdaption', 'DaPointData', 'UserPreset', 'DeviceMessage', 'UserPresetData', 'Apk', 'CsPointdata', 'ReportRequestBackup', 'DevicMessage', 'StbTest', 'StbPointdata', 'DarkAdaptionnew', 'Patientsnew', 'ActPointdata', 'ActTest', 'VtTest', 'Video', 'PatientVideoViews');
	var $helpers = array('Html', 'Form');
	public $components = array('Auth', 'Session', 'Email', 'Common');
	function beforeFilter()
	{
		parent::beforeFilter(); 
		$this->Auth->allow('*');
	}
	//this function for image uploading
	public function base64_to_jpeg($base64_string, $folder_name)
	{
		$file_name = time() . '.jpg';
		$output_file = getcwd() . '/' . $folder_name . '/' . $file_name;
		$ifp = fopen($output_file, 'wb');
		fwrite($ifp, base64_decode($base64_string));
		fclose($ifp);
		return ($file_name);
	}
	//This function for PDF uploading
	public function base64_to_pdf($base64_string, $folder_name, $file_unique_no = 0)
	{
		$file_name = time() . '_' . $file_unique_no . '.pdf';
		if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'localhost') {
			$output_file = ROOT . '/app/webroot/' . $folder_name . '/' . $file_name;
		} else {
			$output_file = getcwd() . '/app/webroot/' . $folder_name . '/' . $file_name;
		}
		//echo $output_file;die;
		$ifp = @fopen($output_file, 'wb');
		//print_r($ifp);die;
		@fwrite($ifp, base64_decode($base64_string));
		@fclose($ifp);
		return ($file_name);
	}
	public function base64_to_pdf2($base64_string, $folder_name, $file_unique_no = 0)
	{
		$file_name = time() . '_' . $file_unique_no . '.pdf';
		$output_file = getcwd() . '/app/webroot/' . $folder_name . '/' . $file_name;
		$ifp = @fopen($output_file, 'wb');
		//print_r($ifp);die;
		@fwrite($ifp, base64_decode($base64_string));
		@fclose($ifp);
		return ($file_name);
	}
	//This function is for converting image into base 64
	public function jpeg_to_base64($image_name, $folder_name)
	{
		$image = $folder_name . $image_name;
		$arr = explode('.', $image_name);
		$extension = end($arr);
		$base64Image = "data:image/" . $extension . ";base64," . base64_encode(file_get_contents($image));
		return $base64Image;
	}
	//This method check Get request
	public function validateGetRequest()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			return true;
		} else {
			$response_array = array('message' => 'Invalid request.', 'status' => 0);
			header('Content-Type: application/json');
			echo json_encode($response_array);
			die;
			exit;
		}
	}
	//This method check post request
	public function validatePostRequest()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			return true;
		} else {
			$response_array = array('message' => 'Invalid request2.', 'status' => 0);
			header('Content-Type: application/json');
			echo json_encode($response_array);
			die;
			exit;
		}
	}
	//Check whether admin block the user or not
	public function isAuthorized($user)
	{
		$userDeatils = $this->User->find('first', ['conditions' => array('User.username' => @$user['username'])]);
		if (!empty($userDeatils)) {
			if ($userDeatils['User']['user_type'] ==='Admin'){
				return true;
			} else if ($userDeatils['Office']['status'] == 1) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
	//This is the function for securing API'same
	public function check_key()
	{
		if (isset($_SERVER['HTTP_APPKEY'])) {
			$headerValue = $_SERVER['HTTP_APPKEY'];
			if (app_key != $headerValue) {
				// not validate for unity # to validate app key comment the return true.
				return true;
				$response_array = array('message' => 'Invalid security key.', 'status' => 0);
				echo json_encode($response_array);
				die;
			} else {
				return true;
			}
		} else {
			// not validate for unity # to validate app key comment the return true.
			return true;
			$response_array = array('message' => 'Please send your security key.', 'status' => 0);
			echo json_encode($response_array);
			die;
		}
	}
	public function newlogin()
	{
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				}
				if (!empty($input_data) && !$this->isAuthorized($input_data)) {
					$response_array = array('message' => 'Your office status is inactive. Please contact to admin.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
				if (isset($input_data['username']) && isset($input_data['password']) && !empty($input_data['password'])) {
					$username = trim($input_data['username']);
					$user_detail = $this->User->find('first', array(
						'conditions' => array('User.username' => $username),
						'fields' => array('User.id', 'User.first_name', 'User.password', 'User.middle_name', 'User.last_name', 'User.username', 'User.user_type', 'User.email', 'User.dob', 'User.phone', 'User.gender', 'User.office_id', 'User.id_no', 'User.notes', 'User.created', 'User.modified', 'Office.address', 'Office.phone', 'User.first_consent')
					));
					if (empty($user_detail)) {
						$response_array = array('message' => 'Invalid username or password.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} elseif ($user_detail['User']['user_type'] != 'Staffuser' && $user_detail['User']['user_type'] != 'Subadmin') {
						$response_array = array('message' => 'Invalid username.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
					//Use Blowfishpassword hasher algorithm
					$passwordHasher = new BlowfishPasswordHasher();
					$match = $passwordHasher->check($input_data['password'], @$user_detail['User']['password']);
					if (!$match) {
						$response_array = array('message' => 'Invalid username or password.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
					if (empty($user_detail['User']['first_consent'])) {
						$this->User->id = $user_detail['User']['id'];
						$result = $this->User->saveField('first_consent', date('Y-m-d H:i:s', strtotime($input_data['first_consent'])));
						if ($result) {
							$user_detail['User']['first_consent'] = $result['User']['first_consent'];
						}
					}
					$data = $user_detail['User'];
					/* if(!empty($user_detail['User']['profile_pic'])){
						$data['profile_pic'] = WWW_BASE . 'img/uploads/'. $user_detail['User']['profile_pic'];
					}
					*/
					$data['user_id'] = $data['id'];
					$this->Office->id = $data['office_id'];
					$office_name = $this->Office->field('name');
					$data['office_name'] = $office_name;
					$data['office_address'] = isset($user_detail['Office']['address']) ? $user_detail['Office']['address'] : '';
					$data['office_phone'] = isset($user_detail['Office']['phone']) ? $user_detail['Office']['phone'] : '';
					unset($data['id']);
					unset($data['password']);
					$response_array = array('message' => 'Login successfull.', 'status' => 1, 'data' => $data);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				} else {
					$response_array = array('message' => 'Please send valid input data.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
	public function download_user_login()
	{
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				}
				if (!empty($input_data) && !$this->isAuthorized($input_data)) {
					$response_array = array('message' => 'Your office status is inactive. Please contact to admin.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
				if (isset($input_data['username']) && isset($input_data['password']) && !empty($input_data['password'])) {
					$username = trim($input_data['username']);
					$user_detail = $this->User->find('first', array(
						'conditions' => array('User.username' => $username),
						'fields' => array('User.id', 'User.first_name', 'User.password', 'User.middle_name', 'User.last_name', 'User.username', 'User.user_type', 'User.email', 'User.dob', 'User.phone', 'User.gender', 'User.office_id', 'User.id_no', 'User.notes', 'User.created', 'User.modified', 'Office.address', 'Office.phone', 'User.first_consent')
					));
					if (empty($user_detail)) {
						$response_array = array('message' => 'Invalid username or password.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} elseif ($user_detail['User']['user_type'] != 'SuperSubadmin') {
						$response_array = array('message' => 'Invalid username.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
					//Use Blowfishpassword hasher algorithm
					$passwordHasher = new BlowfishPasswordHasher();
					$match = $passwordHasher->check($input_data['password'], @$user_detail['User']['password']);
					if (!$match) {
						$response_array = array('message' => 'Invalid username or password.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
					if (empty($user_detail['User']['first_consent'])) {
						$this->User->id = $user_detail['User']['id'];
						$result = $this->User->saveField('first_consent', date('Y-m-d H:i:s', strtotime($input_data['first_consent'])));
						if ($result) {
							$user_detail['User']['first_consent'] = $result['User']['first_consent'];
						}
					}
					$data = $user_detail['User'];
					/* if(!empty($user_detail['User']['profile_pic'])){
						$data['profile_pic'] = WWW_BASE . 'img/uploads/'. $user_detail['User']['profile_pic'];
					}
					*/
					$data['user_id'] = $data['id'];
					$this->Office->id = $data['office_id'];
					$office_name = $this->Office->field('name');
					$data['office_name'] = $office_name;
					$data['office_address'] = isset($user_detail['Office']['address']) ? $user_detail['Office']['address'] : '';
					$data['office_phone'] = isset($user_detail['Office']['phone']) ? $user_detail['Office']['phone'] : '';
					unset($data['id']);
					unset($data['password']);
					$response_array = array('message' => 'Login successfull.', 'status' => 1, 'data' => $data);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				} else {
					$response_array = array('message' => 'Please send valid input data.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}


	/*Dicom amd pdf API*/
		public function check_file_both()
	{
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->loadModel('OfficeReportBackup');
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST; 
				} 
			 $staffuserAdmin = $this->User->find('list', array('conditions' => array('User.office_id' => $input_data['office_id']), 'fields' => array('User.id')));
			  
			$conditions['Pointdata.staff_id'] = $staffuserAdmin;
			$officereport = $this->OfficeReportBackup->find('first', array('conditions' => array('OfficeReportBackup.office_id' => $input_data['office_id'],'OfficeReportBackup.testtype'=>'VF'))); 
			if(!isset($officereport['OfficeReportBackup']['last_backup'])){
					$officereport['OfficeReportBackup']['last_backup']=0;
					$officereport['OfficeReportBackup']['office_id']=$input_data['office_id'];
					$officereport['OfficeReportBackup']['testtype']='VF';
					$this->OfficeReportBackup->save($officereport);
				}
			if($input_data['isDownload'] == false){
			$conditions['Pointdata.id >'] = $officereport['OfficeReportBackup']['last_backup'];
			}else if($input_data['isDownload'] == true){
			$conditions['Pointdata.id >'] =  0; 
			}
			$this->Pointdata->unbindModel(array('hasMany' => array('VfPointdata')),false);
			$data = $this->Pointdata->find('all', array('conditions' => $conditions, 'order' => 'Pointdata.id ASC','fields' => array('Pointdata.file','Pointdata.id','Pointdata.patient_id','Pointdata.test_name','Pointdata.eye_select','Patient.id_number','Patient.first_name','Patient.last_name'))); 
			if (isset($data[0]['Pointdata'])) { 
				$response['status'] = 1;
				$response['data'] = $data;
			} else {
				$response['status'] = 0; 
				$response['data'] = null;
			}
			
			if($input_data['istype'] == 'pdf'){
				$file_url = WWW_BASE.'pointData/';
			}else if($input_data['istype'] == 'dicom'){
				$file_url = WWW_BASE.'admin/unityreports/exportDicom/';
			}
			$response_array = array('message' => 'Pending Download Report.', 'status' => 1, 'data' => $data,'path'=>$file_url);
			header('Content-Type: application/json');
			echo json_encode($response_array);
			die;
				  
			}
		}
	}
	/*Dicom amd pdf API*/
	/*Dicom amd pdf API*/
		public function check_file_both_V1()
	{
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->loadModel('OfficeReportBackup');
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST; 
				} 
			 $staffuserAdmin = $this->User->find('list', array('conditions' => array('User.office_id' => $input_data['office_id']), 'fields' => array('User.id')));
			  
			$conditions['Pointdata.staff_id'] = $staffuserAdmin;
			$condition['Pointdata.staff_id'] = $staffuserAdmin;
			$officereport = $this->OfficeReportBackup->find('first', array('conditions' => array('OfficeReportBackup.office_id' => $input_data['office_id'],'OfficeReportBackup.testtype'=>'VF'))); 
			if(!isset($officereport['OfficeReportBackup']['last_backup'])){
					$officereport['OfficeReportBackup']['last_backup']=0;
					$officereport['OfficeReportBackup']['office_id']=$input_data['office_id'];
					$officereport['OfficeReportBackup']['testtype']='VF';
					$this->OfficeReportBackup->save($officereport);
				}
			if($input_data['isDownload'] == 'True'){
				$conditions['Pointdata.id >'] =  0; 
			}elseif(!empty($input_data['start_date']) && !empty($input_data['end_date']) && $input_data['isDownload'] == 'False'){
				$conditions['Pointdata.created >'] = $input_data['start_date'];
				$conditions['Pointdata.created <'] = $input_data['end_date'];
			}elseif($input_data['isDownload'] == 'False'){
				$conditions['Pointdata.id >'] = $officereport['OfficeReportBackup']['last_backup'];
			}
			$this->Pointdata->unbindModel(array('hasMany' => array('VfPointdata')),false);
			$data = $this->Pointdata->find('all', array('conditions' => $conditions, 'order' => 'Pointdata.id ASC','fields' => array('Pointdata.file','Pointdata.id','Pointdata.patient_id','Pointdata.test_name','Pointdata.eye_select','Patient.id_number','Patient.first_name','Patient.last_name'))); 
			$datas = $this->Pointdata->find('first', array('conditions' => $condition, 'order' => 'Pointdata.id DESC'));
			if (isset($data[0]['Pointdata'])) { 
				$response['status'] = 1;
				$response['data'] = $data;
			} else {
				$response['status'] = 0; 
				$response['data'] = null;
			}
			
			if($input_data['istype'] == 'pdf'){
				$file_url = WWW_BASE.'pointData/';
			}else if($input_data['istype'] == 'dicom'){
				$file_url = WWW_BASE.'admin/unityreports/exportDicom/';
			}
			$response_array = array('message' => 'Pending Download Report.', 'status' => 1, 'data' => $data,'path'=>$file_url,'lastUploadedId'=>$datas['Pointdata']['id']);
			header('Content-Type: application/json');
			echo json_encode($response_array);
			die;
				  
			}
		}
	}
	/*Dicom amd pdf API*/

	/*STB autodownload report WPF*/
	public function check_file_stb(){
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->loadModel('OfficeReportBackup');
				$this->loadModel('StbTest');
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST; 
				} 
				$staffuserAdmin = $this->User->find('list', array('conditions' => array('User.office_id' => $input_data['office_id']), 'fields' => array('User.id')));
				$conditions['StbTest.staff_id'] = $staffuserAdmin;
				$officereport = $this->OfficeReportBackup->find('first', array('conditions' => array('OfficeReportBackup.office_id' => $input_data['office_id'],'OfficeReportBackup.testtype'=>'STB'))); 
				if(!isset($officereport['OfficeReportBackup']['last_backup'])){
					$officereport['OfficeReportBackup']['last_backup']=0;
					$officereport['OfficeReportBackup']['office_id']=$input_data['office_id'];
					$officereport['OfficeReportBackup']['testtype']='STB';
					$this->OfficeReportBackup->save($officereport);
				}
				if($input_data['isDownload'] == false){
					$conditions['StbTest.id >'] = $officereport['OfficeReportBackup']['last_backup'];
				}else if($input_data['isDownload'] == true){
					$conditions['StbTest.id >'] =  0; 
				}
				$this->StbTest->unbindModel(array('hasMany' => array('StbPointdata')),false);
				$data = $this->StbTest->find('all', array('conditions' => $conditions, 'order' => 'StbTest.id ASC','fields' => array('StbTest.file','StbTest.id','StbTest.patient_id','StbTest.test_name','StbTest.eye_select','Patient.id_number','Patient.first_name','Patient.last_name'))); 
				if (isset($data[0]['StbTest'])) { 
					$response['status'] = 1;
					$response['data'] = $data;
				}else{
					$response['status'] = 0; 
					$response['data'] = null;
				}  
				$file_url = WWW_BASE.'uploads/stbdata/';
				$response_array = array('message' => 'Pending Download Report.', 'status' => 1, 'data' => $data,'path'=>$file_url);
				header('Content-Type: application/json');
				echo json_encode($response_array);
				die;
			}
		}
	}

	public function check_file_stb_V1(){
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->loadModel('OfficeReportBackup');
				$this->loadModel('StbTest');
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST; 
				} 
				$staffuserAdmin = $this->User->find('list', array('conditions' => array('User.office_id' => $input_data['office_id']), 'fields' => array('User.id')));
				$conditions['StbTest.staff_id'] = $staffuserAdmin;
				$condition['StbTest.staff_id'] = $staffuserAdmin;
				$officereport = $this->OfficeReportBackup->find('first', array('conditions' => array('OfficeReportBackup.office_id' => $input_data['office_id'],'OfficeReportBackup.testtype'=>'STB'))); 
				if(!isset($officereport['OfficeReportBackup']['last_backup'])){
					$officereport['OfficeReportBackup']['last_backup']=0;
					$officereport['OfficeReportBackup']['office_id']=$input_data['office_id'];
					$officereport['OfficeReportBackup']['testtype']='STB';
					$this->OfficeReportBackup->save($officereport);
				}
				if($input_data['isDownload'] == 'True'){
					$conditions['StbTest.id >'] =  0; 
				}elseif(!empty($input_data['start_date']) && !empty($input_data['end_date']) && $input_data['isDownload'] == 'False'){
					$conditions['StbTest.created >'] = $input_data['start_date'];
					$conditions['StbTest.created <'] = $input_data['end_date'];
				}elseif($input_data['isDownload'] == 'False'){
					$conditions['StbTest.id >'] = $officereport['OfficeReportBackup']['last_backup'];
				}
				$this->StbTest->unbindModel(array('hasMany' => array('StbPointdata')),false);
				$data = $this->StbTest->find('all', array('conditions' => $conditions, 'order' => 'StbTest.id ASC','fields' => array('StbTest.file','StbTest.id','StbTest.patient_id','StbTest.test_name','StbTest.eye_select','Patient.id_number','Patient.first_name','Patient.last_name'))); 
				$datas = $this->StbTest->find('first', array('conditions' => $condition, 'order' => 'StbTest.id DESC'));
				if (isset($data[0]['StbTest'])) { 
					$response['status'] = 1;
					$response['data'] = $data;
				}else{
					$response['status'] = 0; 
					$response['data'] = null;
				}  
				$file_url = WWW_BASE.'uploads/stbdata/';
				$response_array = array('message' => 'Pending Download Report.', 'status' => 1, 'data' => $data,'path'=>$file_url,'lastUploadedId'=>$datas['StbTest']['id']);
				header('Content-Type: application/json');
				echo json_encode($response_array);
				die;
			}
		}
	}
	/*STB autodownload report WPF*/

	/*ACT auto downlaod for WPF*/
		public function check_file_act(){
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->loadModel('OfficeReportBackup');
				$this->loadModel('ActTest');
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST; 
				} 
				$staffuserAdmin = $this->User->find('list', array('conditions' => array('User.office_id' => $input_data['office_id']), 'fields' => array('User.id')));
				//pr($staffuserAdmin);die;
				$conditions['ActTest.staff_id'] = $staffuserAdmin;
				$officereport = $this->OfficeReportBackup->find('first', array('conditions' => array('OfficeReportBackup.office_id' => $input_data['office_id'],'OfficeReportBackup.testtype'=>'ACT'))); 
				if(!isset($officereport['OfficeReportBackup']['last_backup'])){
					$officereport['OfficeReportBackup']['last_backup']=0;
					$officereport['OfficeReportBackup']['office_id']=$input_data['office_id'];
					$officereport['OfficeReportBackup']['testtype']='ACT';
					$this->OfficeReportBackup->save($officereport);
				}
				if($input_data['isDownload'] == false){
					$conditions['ActTest.id >'] = $officereport['OfficeReportBackup']['last_backup'];
				}else if($input_data['isDownload'] == true){
					$conditions['ActTest.id >'] =  0; 
				}
				$this->ActTest->unbindModel(array('hasMany' => array('ActPointdata')),false);
				$data = $this->ActTest->find('all', array('conditions' => $conditions, 'order' => 'ActTest.id ASC','fields' => array('ActTest.file','ActTest.id','ActTest.patient_id','ActTest.test_name','Patient.id_number','Patient.first_name','Patient.last_name'))); 
				//pr($data); die;
				if (isset($data[0]['ActTest'])) { 
					$response['status'] = 1;
					$response['data'] = $data;
				}else{
					$response['status'] = 0; 
					$response['data'] = null;
				}  
				$file_url = WWW_BASE.'app/webroot/ActTestControllerData/';
				$response_array = array('message' => 'Pending Download Report.', 'status' => 1, 'data' => $data,'path'=>$file_url);
				header('Content-Type: application/json');
				echo json_encode($response_array);
				die;
			}
		}
	}
	public function check_file_act_V1(){
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->loadModel('OfficeReportBackup');
				$this->loadModel('ActTest');
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST; 
				} 
				$staffuserAdmin = $this->User->find('list', array('conditions' => array('User.office_id' => $input_data['office_id']), 'fields' => array('User.id')));
				$conditions['ActTest.staff_id'] = $staffuserAdmin;
				$condition['ActTest.staff_id'] = $staffuserAdmin;
				$officereport = $this->OfficeReportBackup->find('first', array('conditions' => array('OfficeReportBackup.office_id' => $input_data['office_id'],'OfficeReportBackup.testtype'=>'ACT'))); 
				if(!isset($officereport['OfficeReportBackup']['last_backup'])){
					$officereport['OfficeReportBackup']['last_backup']=0;
					$officereport['OfficeReportBackup']['office_id']=$input_data['office_id'];
					$officereport['OfficeReportBackup']['testtype']='ACT';
					$this->OfficeReportBackup->save($officereport);
				}
				if($input_data['isDownload'] == 'True'){
					$conditions['ActTest.id >'] =  0; 
				}elseif(!empty($input_data['start_date']) && !empty($input_data['end_date']) && $input_data['isDownload'] == 'False'){
					$conditions['ActTest.created >'] = $input_data['start_date'];
					$conditions['ActTest.created <'] = $input_data['end_date'];
				}elseif($input_data['isDownload'] == 'False'){
					$conditions['ActTest.id >'] = $officereport['OfficeReportBackup']['last_backup'];
				}
				$this->ActTest->unbindModel(array('hasMany' => array('ActPointdata')),false);
				$data = $this->ActTest->find('all', array('conditions' => $conditions, 'order' => 'ActTest.id ASC','fields' => array('ActTest.file','ActTest.id','ActTest.patient_id','ActTest.test_name','Patient.id_number','Patient.first_name','Patient.last_name'))); 
				$datas = $this->ActTest->find('first', array('conditions' => $condition, 'order' => 'ActTest.id DESC'));
				//pr($data); die;
				if (isset($data[0]['ActTest'])) { 
					$response['status'] = 1;
					$response['data'] = $data;
				}else{
					$response['status'] = 0; 
					$response['data'] = null;
				}  
				$file_url = WWW_BASE.'app/webroot/ActTestControllerData/';
				$response_array = array('message' => 'Pending Download Report.', 'status' => 1, 'data' => $data,'path'=>$file_url,'lastUploadedId'=>$datas['ActTest']['id']);
				header('Content-Type: application/json');
				echo json_encode($response_array);
				die;
			}
		}
	}
	/*ACT auto downlaod for WPF*/
	/*VT auto download report*/
	public function check_file_vt(){
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->loadModel('OfficeReportBackup');
				$this->loadModel('VtTest');
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST; 
				} 
				$staffuserAdmin = $this->User->find('list', array('conditions' => array('User.office_id' => $input_data['office_id']), 'fields' => array('User.id')));
				//pr($staffuserAdmin);die;
				$conditions['VtTest.staff_id'] = $staffuserAdmin;
				$officereport = $this->OfficeReportBackup->find('first', array('conditions' => array('OfficeReportBackup.office_id' => $input_data['office_id'],'OfficeReportBackup.testtype'=>'VT'))); 
				if(!isset($officereport['OfficeReportBackup']['last_backup'])){
					$officereport['OfficeReportBackup']['last_backup']=0;
					$officereport['OfficeReportBackup']['office_id']=$input_data['office_id'];
					$officereport['OfficeReportBackup']['testtype']='VT';
					$this->OfficeReportBackup->save($officereport);
				}
				if($input_data['isDownload'] == false){
					$conditions['VtTest.id >'] = $officereport['OfficeReportBackup']['last_backup'];
				}else if($input_data['isDownload'] == true){
					$conditions['VtTest.id >'] =  0; 
				}
				//$this->DarkAdaption->unbindModel(array('hasMany' => array('DaPointData')),false);
				$data = $this->VtTest->find('all', array('conditions' => $conditions, 'order' => 'VtTest.id ASC','fields' => array('VtTest.file','VtTest.id','VtTest.patient_id','VtTest.test_name','Patient.id_number','Patient.first_name','Patient.last_name'))); 
				//pr($data); die;
				if (isset($data[0]['VtTest'])) { 
					$response['status'] = 1;
					$response['data'] = $data;
				}else{
					$response['status'] = 0; 
					$response['data'] = null;
				}  
				$file_url = WWW_BASE.'app/webroot/VtTestControllerData/';
				$response_array = array('message' => 'Pending Download Report.', 'status' => 1, 'data' => $data,'path'=>$file_url);
				header('Content-Type: application/json');
				echo json_encode($response_array);
				die;
			}
		}
	}
	public function check_file_vt_V1(){
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->loadModel('OfficeReportBackup');
				$this->loadModel('VtTest');
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST; 
				} 
				$staffuserAdmin = $this->User->find('list', array('conditions' => array('User.office_id' => $input_data['office_id']), 'fields' => array('User.id')));
				//pr($staffuserAdmin);die;
				$conditions['VtTest.staff_id'] = $staffuserAdmin;
				$condition['VtTest.staff_id'] = $staffuserAdmin;
				$officereport = $this->OfficeReportBackup->find('first', array('conditions' => array('OfficeReportBackup.office_id' => $input_data['office_id'],'OfficeReportBackup.testtype'=>'VT'))); 
				if(!isset($officereport['OfficeReportBackup']['last_backup'])){
					$officereport['OfficeReportBackup']['last_backup']=0;
					$officereport['OfficeReportBackup']['office_id']=$input_data['office_id'];
					$officereport['OfficeReportBackup']['testtype']='VT';
					$this->OfficeReportBackup->save($officereport);
				}
				if($input_data['isDownload'] == 'True'){
					$conditions['VtTest.id >'] =  0; 
				}elseif(!empty($input_data['start_date']) && !empty($input_data['end_date']) && $input_data['isDownload'] == 'False'){
					$conditions['VtTest.created >'] = $input_data['start_date'];
					$conditions['VtTest.created <'] = $input_data['end_date'];
				}elseif($input_data['isDownload'] == 'False'){
					$conditions['VtTest.id >'] = $officereport['OfficeReportBackup']['last_backup'];
				}
				$data = $this->VtTest->find('all', array('conditions' => $conditions, 'order' => 'VtTest.id ASC','fields' => array('VtTest.file','VtTest.id','VtTest.patient_id','VtTest.test_name','Patient.id_number','Patient.first_name','Patient.last_name'))); 
				$datas = $this->VtTest->find('first', array('conditions' => $condition, 'order' => 'VtTest.id DESC'));
				//pr($data); die;
				if (isset($data[0]['VtTest'])) { 
					$response['status'] = 1;
					$response['data'] = $data;
				}else{
					$response['status'] = 0; 
					$response['data'] = null;
				}  
				$file_url = WWW_BASE.'app/webroot/VtTestControllerData/';
				$response_array = array('message' => 'Pending Download Report.', 'status' => 1, 'data' => $data,'path'=>$file_url,'lastUploadedId'=>$datas['VtTest']['id']);
				header('Content-Type: application/json');
				echo json_encode($response_array);
				die;
			}
		}
	}
	/*VT auto download report*/
	/*Pupilometer report auto download*/ 
	public function check_file_pup(){
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->loadModel('OfficeReportBackup');
				$this->loadModel('PupTest');
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST; 
				} 
				$staffuserAdmin = $this->User->find('list', array('conditions' => array('User.office_id' => $input_data['office_id']), 'fields' => array('User.id')));
				//pr($staffuserAdmin);die;
				$conditions['PupTest.staff_id'] = $staffuserAdmin;
				$officereport = $this->OfficeReportBackup->find('first', array('conditions' => array('OfficeReportBackup.office_id' => $input_data['office_id'],'OfficeReportBackup.testtype'=>'PUP'))); 
				
				if(!isset($officereport['OfficeReportBackup']['last_backup'])){
					$officereport['OfficeReportBackup']['last_backup']=0;
					$officereport['OfficeReportBackup']['office_id']=$input_data['office_id'];
					$officereport['OfficeReportBackup']['testtype']='PUP';
					$this->OfficeReportBackup->save($officereport);
				}
				if($input_data['isDownload'] == false){  
					$conditions['PupTest.id >'] = $officereport['OfficeReportBackup']['last_backup'];
				}else if($input_data['isDownload'] == true){
					$conditions['PupTest.id >'] =  0; 
				}
				$this->PupTest->unbindModel(array('hasMany' => array('PupPointdata')),false);
				$this->loadModel('PupTest');
				$data = $this->PupTest->find('all', array('conditions' => $conditions, 'order' => 'PupTest.id ASC','fields' => array('PupTest.file','PupTest.id','PupTest.patient_id','PupTest.test_name','Patient.id_number','Patient.first_name','Patient.last_name'))); 
				//pr($data); die;
				if (isset($data[0]['PupTest'])) { 
					$response['status'] = 1;
					$response['data'] = $data;
				}else{
					$response['status'] = 0; 
					$response['data'] = null;
				}  
				$file_url = WWW_BASE.'app/webroot/PupTestControllerData/';
				$response_array = array('message' => 'Pending Download Report.', 'status' => 1, 'data' => $data,'path'=>$file_url);
				header('Content-Type: application/json');
				echo json_encode($response_array);
				die;
			}
		}
	}
	public function check_file_pup_V1(){
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->loadModel('OfficeReportBackup');
				$this->loadModel('PupTest');
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST; 
				} 
				$staffuserAdmin = $this->User->find('list', array('conditions' => array('User.office_id' => $input_data['office_id']), 'fields' => array('User.id')));
				//pr($staffuserAdmin);die;
				$conditions['PupTest.staff_id'] = $staffuserAdmin;
				$condition['PupTest.staff_id'] = $staffuserAdmin;
				$officereport = $this->OfficeReportBackup->find('first', array('conditions' => array('OfficeReportBackup.office_id' => $input_data['office_id'],'OfficeReportBackup.testtype'=>'PUP'))); 
				
				if(!isset($officereport['OfficeReportBackup']['last_backup'])){
					$officereport['OfficeReportBackup']['last_backup']=0;
					$officereport['OfficeReportBackup']['office_id']=$input_data['office_id'];
					$officereport['OfficeReportBackup']['testtype']='PUP';
					$this->OfficeReportBackup->save($officereport);
				}
				if($input_data['isDownload'] == 'True'){
					$conditions['PupTest.id >'] =  0; 
				}elseif(!empty($input_data['start_date']) && !empty($input_data['end_date']) && $input_data['isDownload'] == 'False'){
					$conditions['PupTest.created >'] = $input_data['start_date'];
					$conditions['PupTest.created <'] = $input_data['end_date'];
				}elseif($input_data['isDownload'] == 'False'){
					$conditions['PupTest.id >'] = $officereport['OfficeReportBackup']['last_backup'];
				}
				$this->PupTest->unbindModel(array('hasMany' => array('PupPointdata')),false);
				$this->loadModel('PupTest');
				$data = $this->PupTest->find('all', array('conditions' => $conditions, 'order' => 'PupTest.id ASC','fields' => array('PupTest.file','PupTest.id','PupTest.patient_id','PupTest.test_name','Patient.id_number','Patient.first_name','Patient.last_name'))); 
				$datas = $this->PupTest->find('first', array('conditions' => $condition, 'order' => 'PupTest.id DESC'));
				if (isset($data[0]['PupTest'])) { 
					$response['status'] = 1;
					$response['data'] = $data;
				}else{
					$response['status'] = 0; 
					$response['data'] = null;
				}  
				$file_url = WWW_BASE.'app/webroot/PupTestControllerData/';
				$response_array = array('message' => 'Pending Download Report.', 'status' => 1, 'data' => $data,'path'=>$file_url,'lastUploadedId'=>$datas['PupTest']['id']);
				header('Content-Type: application/json');
				echo json_encode($response_array);
				die;
			}
		}
	}
/*	DarkAdaption Report Auto download */
	public function check_file_darkadpation(){
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->loadModel('OfficeReportBackup');
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST; pr($_POST); die;
				} 
				$staffuserAdmin = $this->User->find('list', array('conditions' => array('User.office_id' => $input_data['office_id']), 'fields' => array('User.id')));
				$conditions['DarkAdaption.staff_id'] = $staffuserAdmin;
				$officereport = $this->OfficeReportBackup->find('first', array('conditions' => array('OfficeReportBackup.office_id' => $input_data['office_id'],'OfficeReportBackup.testtype'=>'DA'))); 
				if(!isset($officereport['OfficeReportBackup']['last_backup'])){
					$officereport['OfficeReportBackup']['last_backup']=0;
					$officereport['OfficeReportBackup']['office_id']=$input_data['office_id'];
					$officereport['OfficeReportBackup']['testtype']='DA';
					$this->OfficeReportBackup->save($officereport);
				}
				if($input_data['isDownload'] == false){
					$conditions['DarkAdaption.id >'] = $officereport['OfficeReportBackup']['last_backup'];
				}else if($input_data['isDownload'] == true){
					$conditions['DarkAdaption.id >'] =  0; 
				}
				$this->DarkAdaption->unbindModel(array('hasMany' => array('DaPointData')),false);
				/*$this->DarkAdaption->bindModel(array(
								'belongsTo' => array(
									'Patient' => array(
									'className'     => 'Patient',
									'foreignKey'    => false,
									'conditions' => array('Patient.id=DarkAdaption.patient_id')
									)
								)
							)
				);*/
				$data = $this->DarkAdaption->find('all', array('conditions' => $conditions, 'order' => 'DarkAdaption.id ASC','fields' => array('DarkAdaption.pdf','DarkAdaption.id','DarkAdaption.patient_id','DarkAdaption.test_name','DarkAdaption.eye_select','Patient.id_number','Patient.first_name','Patient.last_name'))); 
				if (isset($data[0]['DarkAdaption'])) { 
					$response['status'] = 1;
					$response['data'] = $data;
				}else{
					$response['status'] = 0; 
					$response['data'] = null;
				}  
				$file_url = WWW_BASE.'uploads/darkadaption/';
				$response_array = array('message' => 'Pending Download Report.', 'status' => 1, 'data' => $data,'path'=>$file_url);
				header('Content-Type: application/json');
				echo json_encode($response_array);
				die;
			}
		}
	}
	public function check_file_darkadpation_V1(){
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->loadModel('OfficeReportBackup');
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				} 
				$staffuserAdmin = $this->User->find('list', array('conditions' => array('User.office_id' => $input_data['office_id']), 'fields' => array('User.id')));
				$conditions['DarkAdaption.staff_id'] = $staffuserAdmin;
				$condition['DarkAdaption.staff_id'] = $staffuserAdmin;
				$officereport = $this->OfficeReportBackup->find('first', array('conditions' => array('OfficeReportBackup.office_id' => $input_data['office_id'],'OfficeReportBackup.testtype'=>'DA'))); 
				if(!isset($officereport['OfficeReportBackup']['last_backup'])){
					$officereport['OfficeReportBackup']['last_backup']=0;
					$officereport['OfficeReportBackup']['office_id']=$input_data['office_id'];
					$officereport['OfficeReportBackup']['testtype']='DA';
					$this->OfficeReportBackup->save($officereport);
				}
				if($input_data['isDownload'] == 'True'){
					$conditions['DarkAdaption.id >'] =  0; 
				}elseif(!empty($input_data['start_date']) && !empty($input_data['end_date']) && $input_data['isDownload'] == 'False'){
					$conditions['DarkAdaption.created >'] = $input_data['start_date'];
					$conditions['DarkAdaption.created <'] = $input_data['end_date'];
				}elseif($input_data['isDownload'] == 'False'){
				$conditions['DarkAdaption.id >'] = $officereport['OfficeReportBackup']['last_backup'];
			}
				$this->DarkAdaption->unbindModel(array('hasMany' => array('DaPointData')),false);
				/*$this->DarkAdaption->bindModel(array(
								'belongsTo' => array(
									'Patient' => array(
									'className'     => 'Patient',
									'foreignKey'    => false,
									'conditions' => array('Patient.id=DarkAdaption.patient_id')
									)
								)
							)
				);*/
				$data = $this->DarkAdaption->find('all', array('conditions' => $conditions, 'order' => 'DarkAdaption.id ASC','fields' => array('DarkAdaption.pdf','DarkAdaption.id','DarkAdaption.patient_id','DarkAdaption.test_name','DarkAdaption.eye_select','Patient.id_number','Patient.first_name','Patient.last_name'))); 
				$datas = $this->DarkAdaption->find('first', array('conditions' => $condition, 'order' => 'DarkAdaption.id DESC')); 
				if (isset($data[0]['DarkAdaption'])) { 
					$response['status'] = 1;
					$response['data'] = $data;
				}else{
					$response['status'] = 0; 
					$response['data'] = null;
				}  
				$file_url = WWW_BASE.'uploads/darkadaption/';
				$response_array = array('message' => 'Pending Download Report.', 'status' => 1, 'data' => $data,'path'=>$file_url,'lastUploadedId'=>$datas['DarkAdaption']['id']);
				header('Content-Type: application/json');
				echo json_encode($response_array);
				die;
			}
		}
	}
/*	DarkAdaption Report Auto download */
	
	public function check_file()
	{
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->loadModel('OfficeReportBackup');
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST; 
				} 
			 $staffuserAdmin = $this->User->find('list', array('conditions' => array('User.office_id' => $input_data['office_id']), 'fields' => array('User.id')));
			  
			$conditions['Pointdata.staff_id'] = $staffuserAdmin;
			$officereport = $this->OfficeReportBackup->find('first', array('conditions' => array('OfficeReportBackup.office_id' => $input_data['office_id'],'OfficeReportBackup.testtype'=>'VF'))); 
			if(!isset($officereport['OfficeReportBackup']['last_backup'])){
					$officereport['OfficeReportBackup']['last_backup']=0;
					$officereport['OfficeReportBackup']['office_id']=$input_data['office_id'];
					$officereport['OfficeReportBackup']['testtype']='VF';
					$this->OfficeReportBackup->save($officereport);
				}
			if($input_data['isDownload'] == 'false' || $input_data['isDownload'] == false){
			$conditions['Pointdata.id >'] = $officereport['OfficeReportBackup']['last_backup'];
			}else if($input_data['isDownload'] == 'true' || $input_data['isDownload'] == true){
			$conditions['Pointdata.id >'] =  0; 
			} 
			$this->Pointdata->unbindModel(array('hasMany' => array('VfPointdata')),false);
			$data = $this->Pointdata->find('all', array('conditions' => $conditions, 'order' => 'Pointdata.id ASC','fields' => array('Pointdata.file','Pointdata.id','Pointdata.patient_id','Pointdata.test_name','Pointdata.eye_select','Patient.id_number','Patient.first_name','Patient.last_name'))); 
			if (isset($data[0]['Pointdata'])) { 
				$response['status'] = 1;
				$response['data'] = $data;
			} else {
				$response['status'] = 0; 
				$response['data'] = null;
			} 
			if(($input_data['istype'] == 'pdf') || ($input_data['istype'] == 'dicom')){  
				$file_url = WWW_BASE.'pointData/';
			}
			$response_array = array('message' => 'Pending Download Report.', 'status' => 1, 'data' => $data,'path'=>$file_url);
			header('Content-Type: application/json');
			echo json_encode($response_array);
			die;
				  
			}
		}
	}
	public function check_file_V1()
	{
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->loadModel('OfficeReportBackup');
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST; 
				} 
			 $staffuserAdmin = $this->User->find('list', array('conditions' => array('User.office_id' => $input_data['office_id']), 'fields' => array('User.id')));
			  
			$conditions['Pointdata.staff_id'] = $staffuserAdmin;
			$condition['Pointdata.staff_id'] = $staffuserAdmin;
			$officereport = $this->OfficeReportBackup->find('first', array('conditions' => array('OfficeReportBackup.office_id' => $input_data['office_id'],'OfficeReportBackup.testtype'=>'VF'))); 
			if(!isset($officereport['OfficeReportBackup']['last_backup'])){
					$officereport['OfficeReportBackup']['last_backup']=0;
					$officereport['OfficeReportBackup']['office_id']=$input_data['office_id'];
					$officereport['OfficeReportBackup']['testtype']='VF';
					$this->OfficeReportBackup->save($officereport);
				}
			if($input_data['isDownload'] == 'True'){
				$conditions['Pointdata.id >'] =  0; 
			}elseif(!empty($input_data['start_date']) && !empty($input_data['end_date']) && $input_data['isDownload'] == 'False'){
				$conditions['Pointdata.created >'] = $input_data['start_date'];
				$conditions['Pointdata.created <'] = $input_data['end_date'];
			}elseif($input_data['isDownload'] == 'False'){
				$conditions['Pointdata.id >'] = $officereport['OfficeReportBackup']['last_backup'];
			}
			$this->Pointdata->unbindModel(array('hasMany' => array('VfPointdata')),false);
			$data = $this->Pointdata->find('all', array('conditions' => $conditions, 'order' => 'Pointdata.id ASC','fields' => array('Pointdata.file','Pointdata.id','Pointdata.patient_id','Pointdata.test_name','Pointdata.eye_select','Patient.id_number','Patient.first_name','Patient.last_name'))); 
			$datas = $this->Pointdata->find('first', array('conditions' => $condition, 'order' => 'Pointdata.id DESC')); 
			if (isset($data[0]['Pointdata'])) { 
				$response['status'] = 1;
				$response['data'] = $data;
			} else {
				$response['status'] = 0; 
				$response['data'] = null;
			} 
			if(($input_data['istype'] == 'pdf') || ($input_data['istype'] == 'dicom')){  
				$file_url = WWW_BASE.'pointData/';
			}
			$response_array = array('message' => 'Pending Download Report.', 'status' => 1, 'data' => $data,'path'=>$file_url,'lastUploadedId'=>$datas['Pointdata']['id']);
			header('Content-Type: application/json');
			echo json_encode($response_array);
			die;
				  
			}
		}
	}
	public function update_dwnload_id()
	{
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->loadModel('OfficeReportBackup');
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				}    
			$officereport = $this->OfficeReportBackup->find('first', array('conditions' => array('OfficeReportBackup.office_id' => $input_data['office_id'],'OfficeReportBackup.testtype' => $input_data['testtype']))); 
			if(!isset($officereport['OfficeReportBackup'])){
				$response_array = array('message' => 'Please send valid office id.', 'status' => 0);
				header('Content-Type: application/json');
				echo json_encode($response_array);
				die;
			}
			$officereport['OfficeReportBackup']['last_backup']=$input_data['reort_id']; 
			$this->OfficeReportBackup->save($officereport);
			$response_array = array('message' => 'Data Updated successfully.', 'status' => 1, 'data'=>$officereport);
			header('Content-Type: application/json');
			echo json_encode($response_array);
			die;
				  
			}
		}
	}
	public function time()
	{
		$datetime = '08/22/2015 10:56 PM';
		$tz_from = 'America/Los_Angeles';
		$tz_to = 'UTC';
		$format = 'Y-m-d H:i:s';
		$dt = new DateTime($datetime, new DateTimeZone($tz_from));
		$dt->setTimeZone(new DateTimeZone($tz_to));
		echo $dt->format($format) . "\n";
		die();
	}
	public function setUtc($time, $time_zone)
	{
		$datetime = $time;
		$tz_from = $time_zone;
		$tz_to = 'UTC';
		$format = 'Y-m-d H:i:s';
		$dt = new DateTime($datetime, new DateTimeZone($tz_from));
		$dt->setTimeZone(new DateTimeZone($tz_to));
		return $dt->format($format);
	}

	/*create new Api for new patients and test type*/
	public function devicelogin3($mac_address = 'FC:DB:B3:C1:E6:29', $password = '26036590')
	{
		$this->loadModel('Patient');
		if ($this->check_key()) {
			$input_data = json_decode(file_get_contents("php://input"), true);
			if (empty($input_data)) {
				$input_data = $_POST;
			}
			$IHU_Type ='';
			if (isset($input_data['mac_address']) && isset($input_data['password'])) {
				$c = str_split(strtoupper($input_data['mac_address']));
				$tszMacAddress = array();
				foreach ($c as $key => $value) {
					$tszMacAddress[$key] = ord($value);
				}
				$Key = (59 * $tszMacAddress[0]) + 67 * $tszMacAddress[1] + 47 * pow($tszMacAddress[2], 2) + 11 * pow($tszMacAddress[3], 3) + 37 * pow($tszMacAddress[4], 2) + 19 * pow($tszMacAddress[5], 3)
					+ 17 * $tszMacAddress[6] + 29 * pow($tszMacAddress[7], 3) + 31 * $tszMacAddress[8] + 43 * pow($tszMacAddress[9], 3) + 7 * pow($tszMacAddress[10], 3) + 89 * pow($tszMacAddress[11], 2)
					+ (97 * $tszMacAddress[12] + 1) + (49 * $tszMacAddress[13] + 1) + 79 * pow($tszMacAddress[14], 2) + 37 * pow($tszMacAddress[15], 2) + 83 * pow($tszMacAddress[16], 2);
				if ($input_data['password'] == $Key) {
					$this->TestDevice->bindModel(
						array(
							'belongsTo' => array(
								'Office' => array(
									'className' => 'Office',
									'foreignKey' => 'office_id',
								)
							)
						)
					);
					$this->TestDevice->bindModel(
						array(
							'belongsTo' => array(
								'Patient' => array(
									'className' => 'Patient',
									'foreignKey' => false,
									'conditions' => array('TestDevice.name = Patient.device_type')
								)
							)
						)
					);
					$testDevice = $this->TestDevice->find('first', ['conditions' => array('TestDevice.mac_address' => $input_data['mac_address'])]);
					if (!empty($testDevice)) {
						if(isset($input_data['version'])){ 
						$testDevice['TestDevice']['version'] = $input_data['version'];
						$data_new = $this->TestDevice->save($testDevice);
						}
						if(!empty($testDevice['Patient']['first_name'])){
							$IHU_Type = true;
							$patientName = @$testDevice['Patient']['first_name'].' '.@$testDevice['Patient']['last_name'];
							$patienttestname = @$testDevice['Patient']['test_name_ihu'];
						}else{
							$patientName = null;
							$patienttestname = null;
							$IHU_Type = false;
						}
						if ($testDevice['Office']['server_test'] == 1) {
							$response_array = array('message' => 'Login successfully.', 'status' => 1, 'office' => $testDevice['Office'], 'device' => $testDevice['TestDevice'],'patientName'=>$patientName,'TestName'=>$patienttestname, 'IHU_Type' =>$IHU_Type,'PatientID'=>@$testDevice['Patient']['id'],'EYE'=>@$testDevice['Patient']['eye_type'],'Progression'=>@$testDevice['Patient']['progression_deatild'],'Language'=>@$testDevice['Patient']['language']);
							header('Content-Type: application/json');
							echo json_encode($response_array);
							die;
						} else {
							$response_array = array('message' => 'Denied. Enable Web Controller to operate headset from the Web.', 'status' => 0);
							header('Content-Type: application/json');
							echo json_encode($response_array);
							die;
						}
					} else {
						$response_array = array('message' => 'Invalid mac address.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'Invalid password.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			} else {
				$response_array = array('message' => 'Please send valid input data.', 'status' => 0);
				header('Content-Type: application/json');
				echo json_encode($response_array);
				die;
			}
		} else {
			$response_array = array('message' => 'Please send valid input data.', 'status' => 0);
			header('Content-Type: application/json');
			echo json_encode($response_array);
			die;
		}
		die();
	}
	/*create new Api for new patients and test type*/

	/*for home use only*/
	public function Test_start_data_save(){
		$this->loadModel('UserDefault');
		$this->loadModel('TestStart');
		$this->loadModel('Patient');
		$this->loadModel('TestDevice');
		$this->loadModel('Masterdata');
		$this->loadModel('User');
		$input_data = json_decode(file_get_contents("php://input"), true); 
		if (empty($input_data)) {
			$input_data = $_POST; 
			$_POST = $this->request->data; 
		}
		$deviceCheck=$this->TestDevice->find('first', array('conditions' => array('TestDevice.id' =>$_POST['device_id'])));
		if($deviceCheck['TestDevice']['device_type'] == 6){
		$data = $this->Patient->find('first', array('conditions' => array('Patient.id' => $this->request->data['patient_id'])));
		if(!empty($data['Patient']['weektime'])){
			$weektime = $data['Patient']['weektime'] * 7;
		}else{
			$weektime = 14;

		}
		date_default_timezone_set("UTC"); 
		$currentDate = date('Y-m-d H:i:s'); 
		$last_date=date('Y-m-d H:i:s', strtotime("+".$weektime." Days"));
		if($last_date > $currentDate){
		$data['Patient']['dob'] = date('d-m-Y', strtotime($data['Patient']['dob']));
		if(!empty($data['Patient']['device_type'])){
		$user = $this->User->find('first', array('conditions' => array('User.id' => $data['Patient']['user_id'])));
		$device=$this->TestDevice->find('first', array('conditions' => array('TestDevice.id' =>$_POST['device_id'])));
    	$type="";
		if($device['TestDevice']['device_type']==1){
		    $type="_Go"; 
		}else if($device['TestDevice']['device_type']==2  || $device['TestDevice']['device_type']==4){
			$type="_PICO";   
		}else if($device['TestDevice']['device_type']==3){
			$type="_Quest";   
		}
		$data2=array();
		$datavf=array();
		$ageGroup = $data['Patient']['dob'] ;
		$res = explode("-",$ageGroup);
		$diff = date('Y')-$res[2];
		if (($diff > 0) && ($diff <= 30)) {
			$agegroup = 1;
		} else if (($diff > 30) && ($diff <= 50)) {
			$agegroup = 2;
		} else if (($diff > 50) && ($diff <= 70)) {
			$agegroup = 3;
		} else {
			$agegroup = 4;
		}
		$dataByAgeGroup=$this->Masterdata->find('first',array('conditions'=>array('Masterdata.age_group'=>$agegroup,'Masterdata.test_name'=>$data['Patient']['test_name_ihu'].''.$type)));
		$this->Masterdata->unbindModel(array('belongsTo' => array('User','Patient','Test')));
		$datamaster = $this->Masterdata->find('all',array('order' => 'Masterdata.age_group ASC','conditions'=>array('Masterdata.test_name'=>$data['Patient']['test_name_ihu'].''.$type))); 
		$this->Masterdata->bindModel(array(
				'hasMany'=>array(
					'VfMasterdata'=>array(
						'foreignKey'=>'master_data_id',
						'order'=>'VfMasterdata.index ASC',
						'fields' => array('x','y','intensity','size','STD','index'),
					)
				)
			)); 
		foreach($datamaster as $val){ 
			array_push($data2, $val);
		} 
		$totalvf =  count($data2[0]['VfMasterdata']);
		$this->loadModel('Pointdata');
		$this->Pointdata->unbindModel(array('belongsTo' => array('User','Patient','Test')));    
      	$datapoint= $this->Pointdata->find('all', array('order' => 'Pointdata.created ASC','conditions' => array('Pointdata.patient_id' => $_POST['patient_id'],'Pointdata.test_name' => $data['Patient']['test_name_ihu'],'Pointdata.eye_select' =>  $this->request->data['select_eye'],'Pointdata.baseline'=>1))); 
      	$datapointa= $this->Pointdata->find('first', array('order' => 'Pointdata.created ASC','conditions' => array('Pointdata.patient_id' => $_POST['patient_id'],'Pointdata.test_name' => $data['Patient']['test_name_ihu'],'Pointdata.eye_select' =>  $this->request->data['select_eye'],'Pointdata.baseline'=>1))); 
		if($datapointa){
			$datapoint1 = $datapointa;
		}else{
			$datapoint1 = null;
		}
		if ($this->request->data['device_id']) {
			$result = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $this->request->data['device_id'])));
			$result['TestDevice']['current_status'];
			if ($result['TestDevice']['current_status'] == 0) {
				$res =  0;
			}else{
				$res =  1;
			}
		}
		if($data['Patient']['progression_deatild'] == 0){
			$progression_deatild = false;
		}else{
			$progression_deatild = true;
		}
		/*create json*/
			$posts = Array(
			"previous_test" =>$datapoint,
			"StartTest" => Array(
			"test_id"=>"2",
			"unique_id"=>$data['Patient']['unique_id'],
			"staff_name"=>$user['User']['first_name'].' '.$user['User']['middle_name'].' '.$user['User']['last_name'],
			"staff_id"=>$data['Patient']['user_id'],
			"zoomLevel"=>3.5,
			"Patient_Name"=>$data['Patient']['first_name'].' '.$data['Patient']['middle_name'].' '.$data['Patient']['last_name'],
			"DOB"=>$data['Patient']['dob'],
			"pid"=>$data['Patient']['id'],
			"od_left"=>$data['Patient']['od_left'],
			"od_right"=>$data['Patient']['od_right'],
			"os_left"=>$data['Patient']['os_left'],
			"os_right"=>$data['Patient']['os_right'],
			"eyeTaped"=>false,
			"GazeTracking"=>false,
			"testBothEyes"=>false,
			"backgroundcolor"=>34,
			"OfficePateintID"=>"",
			"autoPtosisReport"=>false,
			"DisplaySelect"=>1,
			"REACTION_TIME"=>true,
			"PATIENT_TRAINING"=>"0",
			"PTOSIS_INDEX"=>false,
			"LANGUAGE_SEL"=>"1",
			"DELETE_STM"=>"0",
			"DARK_ADAPTATION"=>"0",
			"AUTO_FIXATION"=>"0",
			"WIDE_FIELD_PARAMS"=>"0",
			"EYE"=>"1",
			"TOP_LEVEL_TEST_NAME"=>"1",
			"TEST_TYPE"=>"2",
			"THRESHOLD_TYPE"=>"4",
			"TEST_SUB_TYPE"=>$data['Patient']['test_name_ihu'],
			"TEST_SPEED"=>0.5,
			"VOLUME"=>0.4,
			"STM_SIZE"=>3,
			"STM_INTENSITY"=>"10",
			"WALL_COLOR"=>"0",
			"BKG_INTENSITY"=>"34",
			"TEST_COLOR"=>"0",
			"PID"=>$data['Patient']['id'],
			"START"=>1,
			"voice_instractuin"=>true,
			"progression_analysis"=>$progression_deatild,
			"bilateralfixation"=>false,
			"zeroDbCutoff"=>""),
			"MasterRecord" => Array(
			"test_type_id"=>"4",
			"test_name"=>$data['Patient']['test_name_ihu'],
			"eye_select"=>$this->request->data['select_eye'],
			"age_group"=> $agegroup,
			"numpoints"=> $totalvf,
			"color"=>"30",
			"test_color_fg"=>0,
			"test_color_bg"=>0,
			"stmSize"=>3,
			"master_key"=>"1",
			"created_date"=>$dataByAgeGroup['Masterdata']['created'],
			"threshold"=>"Threshold",
			"strategy"=>"FULL Threshold Fast",
			"backgroundcolor"=>"34",
			"publicList" => $data2[0]['VfMasterdata']),
			"MasterRecordList" =>$data2);
			$djson = json_encode($posts); //echo $djson; die;
		/*create json*/
			$other_data = $this->TestStart->find('first', array('conditions' => array('TestStart.office_id' => $data['Patient']['office_id'], 'TestStart.device_id' => $this->request->data['device_id'], 'TestStart.patient_id NOT' => $this->request->data['patient_id'])));
			if (!empty($other_data)){
				$last_activity_validate = date("Y-m-d H:i:s", strtotime($other_data['TestStart']['updated_at'] . "+ 60 minute"));
				$cureent_time = date("Y-m-d H:i:s");
				if ($last_activity_validate < $cureent_time) {
					$this->TestStart->delete($other_data['TestStart']['id']);
					$olddata = $this->TestStart->find('first', array('conditions' => array('TestStart.office_id' => $data['Patient']['office_id'], 'TestStart.device_id' => $this->request->data['device_id'])));
					if (!empty($olddata)) {
						// update data
						$data['TestStart']['id'] = $olddata['TestStart']['id'];
						$data['TestStart']['staff_id'] = $data['Patient']['user_id'];
						$data['TestStart']['office_id'] = $data['Patient']['office_id'];
						$data['TestStart']['device_id'] = $this->request->data['device_id'];
						$data['TestStart']['patient_id'] = $this->request->data['patient_id'];
						$data['TestStart']['status'] = $res;
						$data['TestStart']['testData'] = $djson;
						$data['TestStart']['updated_at'] = date("Y-m-d H:i:s");
						if ($this->TestStart->save($data)) {
							$response['status'] = 1;
							echo json_encode($response);
							exit();
						}
					} else {
						//insert data
						$data['TestStart']['office_id'] = $data['Patient']['office_id'];
						$data['TestStart']['staff_id'] = $data['Patient']['user_id'];
						$data['TestStart']['device_id'] = $this->request->data['device_id'];
						$data['TestStart']['patient_id'] = $this->request->data['patient_id'];
						$data['TestStart']['status'] = $res;
						$data['TestStart']['testData'] = $djson;
						$data['TestStart']['created_at'] = date("Y-m-d H:i:s");
						$data['TestStart']['updated_at'] = date("Y-m-d H:i:s");
						if ($this->TestStart->save($data)) {
							$response['status'] = 1;
							echo json_encode($response);
							exit();
						}
					}
				} else {
					$response['staff_id'] = $other_data['TestStart']['staff_id'];
					$response['status'] = 2;
					echo json_encode($response);
					exit();
				}
			}
			if (empty($other_data)) {
				$olddata = $this->TestStart->find('first', array('conditions' => array('TestStart.office_id' => $data['Patient']['office_id'], 'TestStart.device_id' => $this->request->data['device_id'])));
				if (!empty($olddata)) {
						// update data
					$data['TestStart']['id'] = $olddata['TestStart']['id'];
					$data['TestStart']['staff_id'] = $data['Patient']['user_id'];
					$data['TestStart']['office_id'] = $data['Patient']['office_id'];
					$data['TestStart']['device_id'] = $this->request->data['device_id'];
					$data['TestStart']['patient_id'] = $this->request->data['patient_id'];
					$data['TestStart']['status'] = $res;
					$data['TestStart']['testData'] = $djson;
					$data['TestStart']['updated_at'] = date("Y-m-d H:i:s");
					if ($this->TestStart->save($data)) {
						$response['status'] = 1;
						echo json_encode($response);
						exit();
					}
					
				} else {
					//insert data
					$data['TestStart']['staff_id'] = $data['Patient']['user_id'];
					$data['TestStart']['office_id'] = $data['Patient']['office_id'];
					$data['TestStart']['device_id'] = $this->request->data['device_id'];
					$data['TestStart']['patient_id'] = $this->request->data['patient_id'];
					$data['TestStart']['status'] = $res;
					$data['TestStart']['testData'] = $djson;
					$data['TestStart']['created_at'] = date("Y-m-d H:i:s");
					$data['TestStart']['updated_at'] = date("Y-m-d H:i:s");
					if ($this->TestStart->save($data)) {
						$response['status'] = 1;
						echo json_encode($response);
						exit();
					}
				}
			} else {
				$response['staff_id'] = $other_data['TestStart']['staff_id'];
				$response['status'] = 2;
				echo json_encode($response);
				exit();
			}
			$response['status'] = 0;
			echo json_encode($response);
			exit();
		}else{
			$response['status'] = 0;
			echo json_encode($response);
			exit();
		}
	}else{
		$response['status'] = 0;
		$response['message'] = 'You can’t start a new test so please contact to  office';
		echo json_encode($response);
		exit();
	}
	}else{
		$this->TestStart->deleteAll($this->request->data['patient_id']);
		$response['status'] = 0;
		$response['message'] = 'Please Choose IHU Device Type';
		echo json_encode($response);
		exit();
	}
	}
	/*for home use only*/

	public function devicelogin2($mac_address = 'FC:DB:B3:C1:E6:29', $password = '26036590')
	{
		if ($this->check_key()) {
			$input_data = json_decode(file_get_contents("php://input"), true);
			if (empty($input_data)) {
				$input_data = $_POST;
			}
			//print_r($input_data);
			//die;
			if (isset($input_data['mac_address']) && isset($input_data['password'])) {
				$c = str_split(strtoupper($input_data['mac_address']));
				$tszMacAddress = array();
				foreach ($c as $key => $value) {
					$tszMacAddress[$key] = ord($value);
				}
				$Key = (59 * $tszMacAddress[0]) + 67 * $tszMacAddress[1] + 47 * pow($tszMacAddress[2], 2) + 11 * pow($tszMacAddress[3], 3) + 37 * pow($tszMacAddress[4], 2) + 19 * pow($tszMacAddress[5], 3)
					+ 17 * $tszMacAddress[6] + 29 * pow($tszMacAddress[7], 3) + 31 * $tszMacAddress[8] + 43 * pow($tszMacAddress[9], 3) + 7 * pow($tszMacAddress[10], 3) + 89 * pow($tszMacAddress[11], 2)
					+ (97 * $tszMacAddress[12] + 1) + (49 * $tszMacAddress[13] + 1) + 79 * pow($tszMacAddress[14], 2) + 37 * pow($tszMacAddress[15], 2) + 83 * pow($tszMacAddress[16], 2);
				if ($input_data['password'] == $Key) {
					$this->TestDevice->bindModel(
						array(
							'belongsTo' => array(
								'Office' => array(
									'className' => 'Office',
									'foreignKey' => 'office_id',
								)
							)
						)
					);
					$testDevice = $this->TestDevice->find('first', ['conditions' => array('TestDevice.mac_address' => $input_data['mac_address'])]);
					if (!empty($testDevice)) {
						if(isset($input_data['version'])){ 
						$testDevice['TestDevice']['version'] = $input_data['version'];
						
					
							$data_new = $this->TestDevice->save($testDevice);
						}
						if ($testDevice['Office']['server_test'] == 1) {
							$response_array = array('message' => 'Login successfully.', 'status' => 1, 'office' => $testDevice['Office'], 'device' => $testDevice['TestDevice']);
							header('Content-Type: application/json');
							echo json_encode($response_array);
							die;
						} else {
							$response_array = array('message' => 'Denied. Enable Web Controller to operate headset from the Web.', 'status' => 0);
							header('Content-Type: application/json');
							echo json_encode($response_array);
							die;
						}
					} else {
						$response_array = array('message' => 'Invalid mac address.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'Invalid password.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			} else {
				$response_array = array('message' => 'Please send valid input data.', 'status' => 0);
				header('Content-Type: application/json');
				echo json_encode($response_array);
				die;
			}
		} else {
			$response_array = array('message' => 'Please send valid input data.', 'status' => 0);
			header('Content-Type: application/json');
			echo json_encode($response_array);
			die;
		}
		die();
	}
	public function devicelogin($mac_address = '2C:0E:3D:5D:84:C1', $password = 'r4ucK9nhS1Y3')
	{
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			//  if ($this->validatePostRequest()) {
			if (1) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				}
				/* $input_data['password']=$password;
                $input_data['mac_address']=$mac_address;*/
				if (isset($input_data['mac_address']) && isset($input_data['password'])) {
					//Use Blowfishpassword hasher algorithm
					$passwordHasher = new BlowfishPasswordHasher();
					$officeDeatils = $this->Office->find('first', ['conditions' => array('Office.password2' => $input_data['password'])]);
					// $officeDeatils = $this->Office->find('first', ['conditions' => array('Office.password' => passwordHasher)]);
					if (!empty($officeDeatils)) {
						$testDevice = $this->TestDevice->find('first', ['conditions' => array('TestDevice.mac_address' => $input_data['mac_address'], 'TestDevice.office_id' => $officeDeatils['Office']['id'])]);
						if (!empty($testDevice)) {
							$response_array = array('message' => 'Login successfully.', 'status' => 1, 'office' => $officeDeatils['Office'], 'device' => $testDevice['TestDevice']);
							header('Content-Type: application/json');
							echo json_encode($response_array);
							die;
						} else {
							$response_array = array('message' => 'Invalid mac address.', 'status' => 0);
							header('Content-Type: application/json');
							echo json_encode($response_array);
							die;
						}
					} else {
						$response_array = array('message' => 'Invalid password.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
					/*  $match = $passwordHasher->check($input_data['password'], @$user_detail['User']['password']);
                    if (!$match) {
                        $response_array = array('message' => 'Invalid username or password.', 'status' => 0);
                        header('Content-Type: application/json');
                        echo json_encode($response_array);
                        die;
                    }*/
				} else {
					$response_array = array('message' => 'Please send valid input data.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
	public function newlogin2()
	{
		if (2) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				}
				if (!empty($input_data) && !$this->isAuthorized($input_data)) {
					$response_array = array('message' => 'Your office status is inactive. Please contact to admin.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
				if (isset($input_data['username']) && isset($input_data['password']) && !empty($input_data['password'])) {
					$username = trim($input_data['username']);
					$user_detail = $this->User->find('first', array(
						'conditions' => array('User.username' => $username),
						'fields' => array('User.id', 'User.first_name', 'User.password', 'User.middle_name', 'User.last_name', 'User.username', 'User.user_type', 'User.email', 'User.dob', 'User.phone', 'User.gender', 'User.office_id', 'User.id_no', 'User.notes', 'User.created', 'User.modified', 'Office.address', 'Office.phone', 'User.first_consent')
					));
					if (empty($user_detail['User']['first_consent']) && !empty($input_data['first_consent'])) {
						$this->User->id = $user_detail['User']['id'];
						$result = $this->User->saveField('first_consent', date('Y-m-d H:i:s', strtotime($input_data['first_consent'])));
						if ($result) {
							$user_detail['User']['first_consent'] = $result['User']['first_consent'];
						}
					}
					if (empty($user_detail)) {
						$response_array = array('message' => 'Invalid username or password.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} elseif ($user_detail['User']['user_type'] != 'Staffuser' && $user_detail['User']['user_type'] != 'Subadmin') {
						$response_array = array('message' => 'Invalid Username', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
					//Use Blowfishpassword hasher algorithm
					$passwordHasher = new BlowfishPasswordHasher();
					$match = $passwordHasher->check($input_data['password'], @$user_detail['User']['password']);
					if (!$match) {
						$response_array = array('message' => 'Invalid username or password.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
					$data = $user_detail['User'];
					//pr($user_detail); die;
					/* if(!empty($user_detail['User']['profile_pic'])){
                      $data['profile_pic'] = WWW_BASE . 'img/uploads/'. $user_detail['User']['profile_pic'];
                      }
                     */
					$data['user_id'] = $data['id'];
					$this->Office->id = $data['office_id'];
					$office_name = $this->Office->field('name');
					$data['office_name'] = $office_name;
					$data['office_address'] = isset($user_detail['Office']['address']) ? $user_detail['Office']['address'] : '';
					$data['office_phone'] = isset($user_detail['Office']['phone']) ? $user_detail['Office']['phone'] : '';
					unset($data['id']);
					unset($data['password']);
					$response_array = array('message' => 'Login successfull.', 'status' => 1, 'data' => $data);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				} else {
					$response_array = array('message' => 'Please send valid input data.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
	//This API for login of staff.
	public function login()
	{
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				}
				if (!empty($input_data) && !$this->isAuthorized($input_data)) {
					$response_array = array('message' => 'Your office status is inactive. Please contact to admin.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
				if (isset($input_data['username']) && isset($input_data['password']) && !empty($input_data['password'])) {
					$username = trim($input_data['username']);
					$user_detail = $this->User->find('first', array(
						'conditions' => array('User.username' => $username),
						'fields' => array('User.id', 'User.first_name', 'User.password', 'User.middle_name', 'User.last_name', 'User.username', 'User.user_type', 'User.email', 'User.dob', 'User.phone', 'User.gender', 'User.office_id', 'User.id_no', 'User.notes', 'User.created', 'User.modified')
					));
					if (empty($user_detail)) {
						$response_array = array('message' => 'Invalid username or password.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} elseif ($user_detail['User']['user_type'] != 'Staffuser') {
						$response_array = array('message' => 'You are not staff. Please try again.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
					//Use Blowfishpassword hasher algorithm
					$passwordHasher = new BlowfishPasswordHasher();
					$match = $passwordHasher->check($input_data['password'], @$user_detail['User']['password']);
					if (!$match) {
						$response_array = array('message' => 'Invalid username or password.', 'status' => 0);
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
					$this->Office->id = $data['office_id'];
					$office_name = $this->Office->field('name');
					$data['office_name'] = $office_name;
					unset($data['id']);
					unset($data['password']);
					$response_array = array('message' => 'Login successfull.', 'status' => 1, 'data' => $data);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				} else {
					$response_array = array('message' => 'Please send valid input data.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
	// This API for forgot password for Admin
	public function forgot_password()
	{
		// if($this->check_key()){
		$this->layout = false;
		//if($this->validatePostRequest()){
		$input_data = json_decode(file_get_contents("php://input"), true);
		if (empty($input_data)) {
			$input_data = $_POST;
		}
		//$input_data =  get_object_vars(json_decode($input_data));
		if (!empty($input_data['username'])) {
			$chekc_email = $this->User->find('first', array('conditions' => array('User.username' => $input_data['username'])));
			//pr($chekc_email);die;
			if (empty($chekc_email)) {
				$response_array = array('message' => 'Username address does not exist.', 'status' => 0);
				header('Content-Type: application/json');
				echo json_encode($response_array);
				die;
			}
			$user = array();
			$password = $this->generateRandomString();
			$this->User->id = $chekc_email['User']['id'];
			$this->User->saveField('password', $password);
			$name = $chekc_email['User']['first_name'];
			$user['User']['first_name'] = $name;
			$chekc_email['User']['custom_password'] = $user['User']['custom_password'] = $password;
			$user['User']['email'] = $chekc_email['User']['email'];
			$subject = "Admin Password Reset";
			//echo '-->'. FROM_EMAIL;die;
			try {
				$Email = new CakeEmail();
				$Email->viewVars($chekc_email['User']);
				$Email->template('forgot_password');
				$Email->from(array(FROM_EMAIL => 'MMD Vision'));
				$Email->to($user['User']['email']);
				$Email->subject($subject);
				$Email->emailFormat('html');
				$rs = $Email->send();
				//pr($Email);
				//pr($rs);
				//die;
				$response_array = array('message' => 'New password generated successfully. Please check your email inbox.', 'status' => 1);
				header('Content-Type: application/json');
				echo json_encode($response_array);
				die;
			} catch (Exception $e) {
				$response_array = array('message' => 'Falied to update password. Try again.', 'status' => 0);
				header('Content-Type: application/json');
				echo json_encode($response_array);
				die;
			}
		} else {
			$response_array = array('message' => 'Falied to update password. Try again.', 'status' => 0);
			header('Content-Type: application/json');
			echo json_encode($response_array);
			die;
		}
		//}
		//}
	}
	/*This API for add and update patient*/
	public function addPatients()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				}
				/*$chekc_ID_number = $this->Patients->find('first',array('conditions'=>array('Patients.id_number'=>$input_data['id_number']),'fields'=>array('Patients.id')));
					if(!empty($chekc_ID_number)){
						$response_array = array('message'=>'Patient already exists with provided Id number.','status'=>0);
						header('Content-Type: application/json');
						echo json_encode($response_array);die;
					}
				 */
				@$input_data['od_left'] = isset($input_data['od_left']) ? $input_data['od_left'] : '';
				@$input_data['od_right'] = isset($input_data['od_right']) ? $input_data['od_right'] : '';
				@$input_data['os_left'] = isset($input_data['os_left']) ? $input_data['os_left'] : '';
				@$input_data['os_right'] = isset($input_data['os_right']) ? $input_data['os_right'] : '';
				@$input_data['unique_id'] = isset($input_data['unique_id']) ? $input_data['unique_id'] : null;
				$input_data['first_name'] = preg_replace('/[^A-Za-z0-9\-]/', '_', $input_data['first_name']);
				$input_data['middle_name'] = preg_replace('/[^A-Za-z0-9\-]/', '_', $input_data['middle_name']);
				$input_data['last_name'] = preg_replace('/[^A-Za-z0-9\-]/', '_', $input_data['last_name']);
				$input_data['first_name'] = str_replace('-', '_', $input_data['first_name']);
				$input_data['middle_name'] = str_replace('-', '_', $input_data['middle_name']);
				$input_data['last_name'] = str_replace("-", "_", $input_data['last_name']);
				$save_data = array(
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
					'created' => (!empty($input_data['created_date'])) ? date('Y-m-d H:i:s', strtotime($input_data['created_date'])) : date('Y-m-d H:i:s')
				);
				//pr($save_data); die;
				if (isset($input_data['first_name']) && !isset($input_data['patient_id'])) {
					$user_office = $this->User->find('first', array('conditions' => array('User.id' => @$input_data['user_id']), 'fields' => array('User.office_id')));
					$save_data['office_id'] = $user_office['User']['office_id'];
					$result = $this->Patients->save($save_data);
					if (count($result['Patients'])) {
						$result['Patients']['patient_id'] = $result['Patients']['id'];
						unset($result['Patients']['id']);
						$response_array = array('message' => 'Patients Added successfully.', 'status' => 1, 'data' => $result['Patients']);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} else {
						$response_array = array('message' => 'Some problems occured during process. Please try again.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				} else {
					$user_office = $this->User->find('first', array('conditions' => array('User.id' => @$input_data['user_id']), 'fields' => array('User.office_id')));
					$save_data['office_id'] = $user_office['User']['office_id'];
					$save_data['id'] = $input_data['patient_id'];
					$save_data['created'] = (!empty($input_data['created_date'])) ? date('Y-m-d H:i:s', strtotime($input_data['created_date'])) : date('Y-m-d H:i:s');
					$result = $this->Patients->save($save_data);
					if ($result) {
						$result['Patients']['patient_id'] = $result['Patients']['id'];
						unset($result['Patients']['id']);
						$response_array = array('message' => 'Patients Updated successfully.', 'status' => 1, 'data' => $result['Patients']);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} else {
						$response_array = array('message' => 'Some problems occured during process. Please try again.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				}
			}
		}
	}
	public function addPatients_v3()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				}
				/*$chekc_ID_number = $this->Patients->find('first',array('conditions'=>array('Patients.id_number'=>$input_data['id_number']),'fields'=>array('Patients.id')));
					if(!empty($chekc_ID_number)){
						$response_array = array('message'=>'Patient already exists with provided Id number.','status'=>0);
						header('Content-Type: application/json');
						echo json_encode($response_array);die;
					}
				 */
				@$input_data['od_left'] = isset($input_data['od_left']) ? $input_data['od_left'] : '';
				@$input_data['od_right'] = isset($input_data['od_right']) ? $input_data['od_right'] : '';
				@$input_data['os_left'] = isset($input_data['os_left']) ? $input_data['os_left'] : '';
				@$input_data['os_right'] = isset($input_data['os_right']) ? $input_data['os_right'] : '';
				@$input_data['unique_id'] = isset($input_data['unique_id']) ? $input_data['unique_id'] : null;
				$input_data['first_name'] = preg_replace('/[^A-Za-z0-9\-]/', '_', $input_data['first_name']);
				$input_data['middle_name'] = preg_replace('/[^A-Za-z0-9\-]/', '_', $input_data['middle_name']);
				$input_data['last_name'] = preg_replace('/[^A-Za-z0-9\-]/', '_', $input_data['last_name']);
				$input_data['first_name'] = str_replace('-', '_', $input_data['first_name']);
				$input_data['middle_name'] = str_replace('-', '_', $input_data['middle_name']);
				$input_data['last_name'] = str_replace("-", "_", $input_data['last_name']);
				$save_data = array(
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
					'created' => (!empty($input_data['created_date'])) ? date('Y-m-d H:i:s', strtotime($input_data['created_date'])) : date('Y-m-d H:i:s')
				);
				//pr($save_data); die;
				if (isset($input_data['first_name']) && !isset($input_data['patient_id'])) {
					$user_office = $this->User->find('first', array('conditions' => array('User.id' => @$input_data['user_id']), 'fields' => array('User.office_id')));
					$save_data['office_id'] = $user_office['User']['office_id'];
					$this->Patientsnew->table = 'patients';
					$this->Patientsnew->useTable = 'patients';
					$this->Patientsnew->validate = array(
						'unique_id' => array(
							'notBlank' => array(
								'rule' => 'notBlank',
								'message' => 'Please enter Id number.'
							),
							'unique' => array(
								'rule' => 'isUnique',
								'message' => 'Please enter another unique id it is already taken.'
							),
						)
					);
					$this->Patientsnew->set($save_data);
					if ($this->Patientsnew->validates()) {
 						date_default_timezone_set("America/Los_Angeles");
						$save_data['created'] = date('Y-m-d H:i:s');
	                    $save_data['created_at_for_archive']=date('Y-m-d H:i:s');
						$result = $this->Patients->save($save_data);
						if (count($result['Patients'])) {
							$result['Patients']['patient_id'] = $result['Patients']['id'];
							unset($result['Patients']['id']);
							$response_array = array('message' => 'Patients Added successfully.', 'status' => 1, 'data' => $result['Patients']);
							header('Content-Type: application/json');
							echo json_encode($response_array);
							die;
						} else {
							$response_array = array('message' => 'Some problems occured during process. Please try again.', 'status' => 0);
							header('Content-Type: application/json');
							echo json_encode($response_array);
							die;
						}
					} else {
						$response_array = array('message' => 'Unique id allready taken.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				} else {
					$user_office = $this->User->find('first', array('conditions' => array('User.id' => @$input_data['user_id']), 'fields' => array('User.office_id')));
					$save_data['office_id'] = $user_office['User']['office_id'];
					$save_data['id'] = $input_data['patient_id'];
					$save_data['created'] = (!empty($input_data['created_date'])) ? date('Y-m-d H:i:s', strtotime($input_data['created_date'])) : date('Y-m-d H:i:s');
					$result = $this->Patients->save($save_data);
					if ($result) {
						$result['Patients']['patient_id'] = $result['Patients']['id'];
						unset($result['Patients']['id']);
						$response_array = array('message' => 'Patients Updated successfully.', 'status' => 1, 'data' => $result['Patients']);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} else {
						$response_array = array('message' => 'Some problems occured during process. Please try again.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				}
			}
		}
	}

	/*Add single patient with UTC time 22-11-2022 by Madan*/
	public function addPatient_v6()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				}
				/*$chekc_ID_number = $this->Patients->find('first',array('conditions'=>array('Patients.id_number'=>$input_data['id_number']),'fields'=>array('Patients.id')));
					if(!empty($chekc_ID_number)){
						$response_array = array('message'=>'Patient already exists with provided Id number.','status'=>0);
						header('Content-Type: application/json');
						echo json_encode($response_array);die;
					}
				 */
				@$input_data['od_left'] = isset($input_data['od_left']) ? $input_data['od_left'] : '';
				@$input_data['od_right'] = isset($input_data['od_right']) ? $input_data['od_right'] : '';
				@$input_data['os_left'] = isset($input_data['os_left']) ? $input_data['os_left'] : '';
				@$input_data['os_right'] = isset($input_data['os_right']) ? $input_data['os_right'] : '';
				@$input_data['unique_id'] = isset($input_data['unique_id']) ? $input_data['unique_id'] : null;
				$input_data['first_name'] = preg_replace('/[^A-Za-z0-9\-]/', '_', $input_data['first_name']);
				$input_data['middle_name'] = preg_replace('/[^A-Za-z0-9\-]/', '_', $input_data['middle_name']);
				$input_data['last_name'] = preg_replace('/[^A-Za-z0-9\-]/', '_', $input_data['last_name']);
				$input_data['first_name'] = str_replace('-', '_', $input_data['first_name']);
				$input_data['middle_name'] = str_replace('-', '_', $input_data['middle_name']);
				$input_data['last_name'] = str_replace("-", "_", $input_data['last_name']);
	            date_default_timezone_set('UTC');
            	$UTCDate = date('Y-m-d H:i:s');
				$save_data = array(
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
					'created' => @$input_data['created'],
					'created_date_utc' => @$input_data['created_date_utc'],
					'created_at_for_archive' => @$UTCDate
				);
				//pr($save_data); die;
				if (isset($input_data['first_name']) && !isset($input_data['patient_id'])) {
					$user_office = $this->User->find('first', array('conditions' => array('User.id' => @$input_data['user_id']), 'fields' => array('User.office_id')));
					$save_data['office_id'] = $user_office['User']['office_id'];
					$this->Patientsnew->table = 'patients';
					$this->Patientsnew->useTable = 'patients';
					$this->Patientsnew->validate = array(
						'unique_id' => array(
							'notBlank' => array(
								'rule' => 'notBlank',
								'message' => 'Please enter Id number.'
							),
							'unique' => array(
								'rule' => 'isUnique',
								'message' => 'Please enter another unique id it is already taken.'
							),
						)
					);
					$this->Patientsnew->set($save_data);
					if ($this->Patientsnew->validates()) {
 						//date_default_timezone_set("America/Los_Angeles");
						//$save_data['created'] = date('Y-m-d H:i:s');
						//$save_data['created'] = $input_data['created'];
	                    //$save_data['created_at_for_archive']= date('Y-m-d H:i:s');
	                    /*date_default_timezone_set('UTC');
            			$UTCDate = date('Y-m-d H:i:s');*/
            			//$save_data['created_date_utc']= $input_data['created_date_utc'];
						$result = $this->Patients->save($save_data);
						if (count($result['Patients'])) {
							$result['Patients']['patient_id'] = $result['Patients']['id'];
							unset($result['Patients']['id']);
							$response_array = array('message' => 'Patients Added successfully.', 'status' => 1, 'data' => $result['Patients']);
							header('Content-Type: application/json');
							echo json_encode($response_array);
							die;
						} else {
							$response_array = array('message' => 'Some problems occured during process. Please try again.', 'status' => 0);
							header('Content-Type: application/json');
							echo json_encode($response_array);
							die;
						}
					} else {
						$response_array = array('message' => 'Unique id allready taken.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				} else {
					$user_office = $this->User->find('first', array('conditions' => array('User.id' => @$input_data['user_id']), 'fields' => array('User.office_id')));
					$save_data['office_id'] = $user_office['User']['office_id'];
					$save_data['id'] = $input_data['patient_id'];
					$save_data['created'] = (!empty($input_data['created_date'])) ? date('Y-m-d H:i:s', strtotime($input_data['created_date'])) : date('Y-m-d H:i:s');
					$result = $this->Patients->save($save_data);
					if ($result) {
						$result['Patients']['patient_id'] = $result['Patients']['id'];
						unset($result['Patients']['id']);
						$response_array = array('message' => 'Patients Updated successfully.', 'status' => 1, 'data' => $result['Patients']);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} else {
						$response_array = array('message' => 'Some problems occured during process. Please try again.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				}
			}
		}
	}
	/*Add single patient with UTC time 22-11-2022 by Madan*/

	/*This API for updated version of addPatients*/
	public function addPatients_v1()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = $_POST;
				if (!isset($input_data['patient_id'])) {
					$i = 0;
					if (!empty($input_data['data'])) {
						foreach ($input_data['data'] as $key => $patient) {
							$patient['first_name'] = preg_replace('/[^A-Za-z0-9\-]/', '_', $patient['first_name']);
							$patient['middle_name'] = preg_replace('/[^A-Za-z0-9\-]/', '_', $patient['middle_name']);
							$patient['last_name'] = preg_replace('/[^A-Za-z0-9\-]/', '_', $patient['last_name']);
							$patient['first_name'] = str_replace('-', '_', $patient['first_name']);
							$patient['middle_name'] = str_replace('-', '_', $patient['middle_name']);
							$patient['last_name'] = str_replace("-", "_", $patient['last_name']);
							$data['Patients'][$i]['user_id'] = $input_data['user_id'];
							$data['Patients'][$i]['first_name'] = $patient['first_name'];
							$data['Patients'][$i]['middle_name'] = isset($patient['middle_name']) ? $patient['middle_name'] : '';
							$data['Patients'][$i]['last_name'] = $patient['last_name'];
							$data['Patients'][$i]['id_number'] = isset($patient['id_number']) ? $patient['id_number'] : '';
							$data['Patients'][$i]['dob'] = isset($patient['dob']) ? $patient['dob'] : '';
							$data['Patients'][$i]['notes'] = isset($patient['notes']) ? $patient['notes'] : '';
							$data['Patients'][$i]['email'] = isset($patient['email']) ? $patient['email'] : '';
							$data['Patients'][$i]['phone'] = isset($patient['phone']) ? $patient['phone'] : '';
							$data['Patients'][$i]['office_id'] = $input_data['office_id'];
							if (isset($patient['os_left'])) {
								$data['Patients'][$i]['os_left'] = isset($patient['os_left']) ? $patient['os_left'] : '';
							}
							if (isset($patient['os_right'])) {
								$data['Patients'][$i]['os_right'] = isset($patient['os_right']) ? $patient['os_right'] : '';
							}
							if (isset($patient['od_left'])) {
								$data['Patients'][$i]['od_left'] = isset($patient['od_left']) ? $patient['od_left'] : '';
							}
							if (isset($patient['od_right'])) {
								$data['Patients'][$i]['od_right'] = isset($patient['od_right']) ? $patient['od_right'] : '';
							}
							$i++;
						}
						if ($this->Patients->saveAll($data['Patients'])) {
							$response_array = array('message' => 'Patients Added successfully.', 'status' => 1);
						} else {
							$response_array = array('message' => 'Error in adding patients.', 'status' => 0);
						}
					} else {
						$response_array = array('message' => 'Please send patients.', 'status' => 0);
					}
					echo json_encode($response_array);
					die;
				} else {
					$data['id'] = $input_data['patient_id'];
					$data['user_id'] = $input_data['user_id'];
					$data['first_name'] = $input_data['first_name'];
					$data['middle_name'] = isset($input_data['middle_name']) ? $input_data['middle_name'] : '';
					$data['last_name'] = $input_data['last_name'];
					$data['id_number'] = isset($input_data['id_number']) ? $input_data['id_number'] : '';
					$data['dob'] = isset($input_data['dob']) ? $input_data['dob'] : '';
					$data['notes'] = isset($input_data['notes']) ? $input_data['notes'] : '';
					$data['email'] = isset($input_data['email']) ? $input_data['email'] : '';
					$data['phone'] = isset($input_data['phone']) ? $input_data['phone'] : '';
					$data['os_left'] = isset($input_data['os_left']) ? $input_data['os_left'] : '';
					$data['os_right'] = isset($input_data['os_right']) ? $input_data['os_right'] : '';
					$data['od_left'] = isset($input_data['od_left']) ? $input_data['od_left'] : '';
					$data['od_right'] = isset($input_data['od_right']) ? $input_data['od_right'] : '';
					$data['office_id'] = $input_data['office_id'];
					$result = $this->Patients->save($data);
					if ($result) {
						$result['Patients']['patient_id'] = $result['Patients']['id'];
						unset($result['Patients']['id']);
						$response_array = array('message' => 'Patients Updated successfully.', 'status' => 1, 'data' => $result['Patients']);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} else {
						$response_array = array('message' => 'Some problems occured during process. Please try again.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				}
			}
		}
	}
	/*This API for add and update patient*/
	public function addPatients_v2()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				//CakeLog::write('info',json_encode($input_data));
				$save_data = $saved_data = array();
				if (!empty($input_data['data']) && isset($input_data['user_id']) && !empty($input_data['user_id']) && isset($input_data['office_id']) && !empty($input_data['user_id'])) {
					$i = 0;
					foreach ($input_data['data'] as $data) {
						//pr($data);die;
						$patients = $data;
						if (!empty($data['patient_id'])) {
							$patients['id'] = $patients['patient_id'];
						} else {
							//$patients['unique_id'] = $input_data['unique_id'];
						}
						$patients['race'] = trim(@$data['race']);
						$patients['office_id'] = $input_data['office_id'];
						$patients['user_id'] = $input_data['user_id'];
						$patients['created'] = (isset($data['created_date']) && !empty($data['created_date'])) ? date('Y-m-d H:i:s', strtotime($data['created_date'])) : date('Y-m-d H:i:s');
						//echo $patients['patient_id'];die;
						/* if(isset($patients['patient_id'])){
							echo $this->Patient->find('list', array('conditions'=>array( 'Patients.id' => $patients['patient_id'])));die;
							/* if ($this->Patients->exists($conditions)){
								echo 'exist';die;
								$rs = $this->Patients->updateAll($patients);
							}
						}else{
							echo 'Not exist';die;
							$rs = $this->Patients->saveAll($patients);
						} */
						//pr($patients); die;
						$rs = $this->Patients->save($patients);
						if ($rs) {
							$saved_data[$i]['id'] = $this->Patients->id;
						}
						$i++;
					}
					if (!empty($saved_data)) {
						$response_array = array('message' => 'Patients Saved successfully.', 'status' => 1, 'data' => $saved_data);
					} else {
						$response_array = array('message' => 'Error in adding patients.', 'status' => 0, 'data' => $saved_data);
					}
				} else {
					$response_array = array('message' => 'Please send all parameters.', 'status' => 0);
				}
				echo json_encode($response_array);
				die;
				/* if(!isset($input_data['patient_id'])){
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
							if(isset($patient['os_left'])){
							$data['Patients'][$i]['os_left']=isset($patient['os_left'])?$patient['os_left']:'';
							}
							if(isset($patient['os_right'])){
							$data['Patients'][$i]['os_right']=isset($patient['os_right'])?$patient['os_right']:'';
							}
							if(isset($patient['od_left'])){
							$data['Patients'][$i]['od_left']=isset($patient['od_left'])?$patient['od_left']:'';
							}
							if(isset($patient['od_right'])){
							$data['Patients'][$i]['od_right']=isset($patient['od_right'])?$patient['od_right']:'';
							}
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
					$data['os_left']=isset($input_data['os_left'])?$input_data['os_left']:'';
					$data['os_right']=isset($input_data['os_right'])?$input_data['os_right']:'';
					$data['od_left']=isset($input_data['od_left'])?$input_data['od_left']:'';
					$data['od_right']=isset($input_data['od_right'])?$input_data['od_right']:'';
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
				} */
			}
		}
	}
	/*This API for add and update patient*/
	public function addPatients_v4()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				//CakeLog::write('info',json_encode($input_data));
				$save_data = $saved_data = array();
				if (!empty($input_data['data']) && isset($input_data['user_id']) && !empty($input_data['user_id']) && isset($input_data['office_id']) && !empty($input_data['user_id'])) {
					$i = 0;
					foreach ($input_data['data'] as $data) {
						//pr($data);die;
						$patients = $data;
						if (!empty($data['patient_id'])) {
							$patients['id'] = $patients['patient_id'];
							unset($patients['unique_id']);
						} else {
							//$patients['unique_id'] = $input_data['unique_id'];
						}
						$patients['race'] = trim(@$data['race']);
						$patients['office_id'] = $input_data['office_id'];
						$patients['user_id'] = $input_data['user_id'];
						$patients['created'] = (isset($data['created_date']) && !empty($data['created_date'])) ? date('Y-m-d H:i:s', strtotime($data['created_date'])) : date('Y-m-d H:i:s');
						$this->Patientsnew->table = 'patients';
						$this->Patientsnew->useTable = 'patients';
						$this->Patientsnew->validate = array(
							'unique_id' => array(
								'notBlank' => array(
									'rule' => 'notBlank',
									'message' => 'Please enter Id number.'
								),
								'unique' => array(
									'rule' => 'isUnique',
									'message' => 'Please enter another unique id it is already taken.'
								),
							)
						);
						$this->Patientsnew->set($patients);
						if ($this->Patientsnew->validates()) {
							$rs = $this->Patients->save($patients);
							if ($rs) {
								$saved_data[$i]['id'] = $this->Patients->id;
								$saved_data[$i]['unique_id'] = $this->Patients->unique_id;
							}
						}
						$i++;
					}
					foreach($saved_data as $key => $value){
						date_default_timezone_set("America/Los_Angeles");
					   // $conditions['Patient.id']=$value['id'];
						
					    $patients2['id'] = $value['id'];
						$patients2['created_at_for_archive'] = date('Y-m-d H:i:s');
						$patients2['created'] = date('Y-m-d H:i:s');
						 $this->Patients->save($patients2); 
					}
					if (!empty($saved_data)) {
						$response_array = array('message' => 'Patients Saved successfully.', 'status' => 1, 'data' => $saved_data);
					} else {
						$response_array = array('message' => 'Error in adding patients.', 'status' => 0, 'data' => $saved_data);
					}
				} else {
					$response_array = array('message' => 'Please send all parameters.', 'status' => 0);
				}
				echo json_encode($response_array);
				die;
			}
		}
	}


	/*add patients 21-11-2022*/
		 public function addPatients_v5()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				$save_data = $saved_data = array();
				if (!empty($input_data['data']) && isset($input_data['user_id']) && !empty($input_data['user_id']) && isset($input_data['office_id']) && !empty($input_data['user_id'])) {
					
					$i = 0;
					foreach ($input_data['data'] as $data) {
						$patients = $data; 
						if (!empty($data['patient_id'])) {
							$patients['id'] = $patients['patient_id'];
							//unset($patients['unique_id']);
						} else {
							//$patients['unique_id'] = $input_data['unique_id'];
						}
						if($patients['unique_id']){
							$patients['unique_id'] = $data['unique_id'];
						}
						date_default_timezone_set("America/Los_Angeles");
						$patients['race'] = trim(@$data['race']);
						$patients['office_id'] = $input_data['office_id'];
						$patients['user_id'] = $input_data['user_id'];
						$patients['created_at_for_archive'] = date('Y-m-d H:i:s');
						$patients['created'] = date('Y-m-d H:i:s');
						//$patients['created'] = (isset($data['created_date']) && !empty($data['created_date'])) ? date('Y-m-d H:i:s', strtotime($data['created_date'])) : date('Y-m-d H:i:s');
						$this->Patientsnew->table = 'patients';
						$this->Patientsnew->useTable = 'patients';
						$this->Patientsnew->validate = array(
							'unique_id' => array(
								'notBlank' => array(
									'rule' => 'notBlank',
									'message' => 'Please enter Id number.'
								),
								'unique' => array(
									'rule' => 'isUnique',
									'message' => 'Please enter another unique id it is already taken.'
								),
							)
						);
						$this->Patientsnew->set($patients);
						if ($this->Patientsnew->validates()) {
							/*$rs = $this->Patients->save($patients);
							if ($rs) {
								$saved_data[$i]['id'] = $this->Patients->id;
								//$saved_data[$i]['unique_id'] = $this->Patients->unique_id;
								$saved_data[$i]['unique_id'] = $data['unique_id'];
							} */
						}
						if(!empty($data['unique_id'])){
							$result =$this->Patients->find('all', array('conditions' => array('Patients.unique_id' => $data['unique_id']), 'fields' => array('Patients.id'))); //pr($result); 
							if(empty($result)){
								$rs = $this->Patients->save($patients);
								if ($rs) {
									$countIds =$this->Patients->find('all', array('conditions' => array('Patients.unique_id' => $data['unique_id']), 'fields' => array('Patients.id')));
									if(count($countIds) > 1){
										foreach($countIds as $key=>$countId){ 
											if($key !== 0){ 
												$this->Patients->delete($countId['Patients']['id']);
											}else{
												$patientsId = $countId['Patients']['id'];
											}
										}
									}
									foreach($countId['Patients']['id'] as $patientDelete){
										$patientsId = $patientDelete ;
									}
									if(empty($patientsId)){
										$patientsId =  $this->Patients->id;
									}
									$saved_data[$i]['id'] = $patientsId;
									//$saved_data[$i]['id'] = $this->Patients->id;
									//$saved_data[$i]['unique_id'] = $this->Patients->unique_id;
									$saved_data[$i]['unique_id'] = $data['unique_id'];
								}
							}else{

							}
							foreach($result as $key=>$val){
								
								/*$this->Patients->save($patients);
									$this->Patients->delete($age_group_data['Masterdata']['id']);*/
								$alreadyinsertid[$i]['id'] = $val['Patients']['id'];
								$alreadyinsertid[$i]['unique_id'] = $data['unique_id'];
							}
						}
						$i++;
					}
					foreach($saved_data as $key => $value){ //pr($value); die;
					   // $conditions['Patient.id']=$value['id'];
					    	//date_default_timezone_set("Asia/Kolkata");
						date_default_timezone_set("America/Los_Angeles");
					    $patients2['id'] = $value['id'];
						$patients2['created_at_for_archive'] = date('Y-m-d H:i:s');
						$patients2['created'] = date('Y-m-d H:i:s');
						 //$this->Patients->save($patients2); 
					}
					if (!empty($saved_data)) {
						$response_array = array('message' => 'Patients Saved successfully.', 'status' => 1, 'data' => $saved_data);
					}else if(empty($saved_data)){
						$response_array = array('message' => 'Already patients added.', 'status' => 1, 'data' => $alreadyinsertid);
					} else {
						$response_array = array('message' => 'Error in adding patients.', 'status' => 0, 'data' => $saved_data);
					}
				} else {
					$response_array = array('message' => 'Please send all parameters.', 'status' => 0);
				}
				echo json_encode($response_array);
				die;
			}
		}
	}
	/*add patients 21-11-2022*/

	/*Add patients by Utc time 22-11-2022 by Madan*/
	 public function addMultiplePatients_v6()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				$save_data = $saved_data = array();
				if (!empty($input_data['data']) && isset($input_data['user_id']) && !empty($input_data['user_id']) && isset($input_data['office_id']) && !empty($input_data['user_id'])) {		
					
					$i = 0;
					foreach ($input_data['data'] as $data) { 
						$patients = $data; 
						if (!empty($data['patient_id'])) {
							$patients['id'] = $patients['patient_id'];
						} 
						if($patients['unique_id']){
							$patients['unique_id'] = $data['unique_id'];
						}
						//date_default_timezone_set("America/Los_Angeles");
						$patients['race'] = trim(@$data['race']);
						$patients['office_id'] = @$input_data['office_id'];
						$patients['user_id'] = @$input_data['user_id'];
						$patients['created_date_utc'] = @$data['created_date_utc'];
						$patients['created'] = @$data['created'];
						//date_default_timezone_set('UTC');
            			//$UTCDate = date('Y-m-d H:i:s');
            			//$patients['created_at_for_archive'] = @$UTCDate;
						//$patients['created'] = (isset($data['created_date']) && !empty($data['created_date'])) ? date('Y-m-d H:i:s', strtotime($data['created_date'])) : date('Y-m-d H:i:s');
						$this->Patientsnew->table = 'patients';
						$this->Patientsnew->useTable = 'patients';
						$this->Patientsnew->validate = array(
							'unique_id' => array(
								'notBlank' => array(
									'rule' => 'notBlank',
									'message' => 'Please enter Id number.'
								),
								'unique' => array(
									'rule' => 'isUnique',
									'message' => 'Please enter another unique id it is already taken.'
								),
							)
						);
						$this->Patientsnew->set($patients);
						if ($this->Patientsnew->validates()) {
							/*$rs = $this->Patients->save($patients);
							if ($rs) {
								$saved_data[$i]['id'] = $this->Patients->id;
								//$saved_data[$i]['unique_id'] = $this->Patients->unique_id;
								$saved_data[$i]['unique_id'] = $data['unique_id'];
							} */
						}
						if(!empty($data['unique_id'])){
							$result =$this->Patients->find('all', array('conditions' => array('Patients.unique_id' => $data['unique_id']), 'fields' => array('Patients.id')));
							if(empty($result)){
								$rs = $this->Patients->save($patients);
								if ($rs) {
									$countIds =$this->Patients->find('all', array('conditions' => array('Patients.unique_id' => $data['unique_id']), 'fields' => array('Patients.id')));
									if(count($countIds) > 1){
										foreach($countIds as $key=>$countId){
											if($key !== 0){ 
												$this->Patients->delete($countId['Patients']['id']);
											}else{
												$patientsId = $countId['Patients']['id'];
											}
										}
									}
									if(empty($patientsIds)){

											$saved_data[$i]['id'] = $this->Patients->id;
											$saved_data[$i]['unique_id'] = $data['unique_id'];
									}else{
										$saved_data[$i]['id'] = $patientsIds;
										$saved_data[$i]['unique_id'] = $data['unique_id'];
									}
									//$saved_data[$i]['unique_id'] = $this->Patients->unique_id;
								}
							}else{

							}
							foreach($result as $key=>$val){
								
								/*$this->Patients->save($patients);
									$this->Patients->delete($age_group_data['Masterdata']['id']);*/
								$alreadyinsertid[$i]['id'] = $val['Patients']['id'];
								$alreadyinsertid[$i]['unique_id'] = $data['unique_id'];
							}
						}
						$i++;
					}

					foreach($saved_data as $key => $value){
					  	$saved_datas[] = $value;
					}
					if (!empty($saved_data)) {

						$response_array = array('message' => 'Patients Saved successfully.', 'status' => 1, 'data' => $saved_datas);
					}else if(empty($saved_data)){
						$response_array = array('message' => 'Already patients added.', 'status' => 1, 'data' => $alreadyinsertid);
					} else {
						$response_array = array('message' => 'Error in adding patients.', 'status' => 0, 'data' => $saved_datas);
					}
				} else {
					$response_array = array('message' => 'Please send all parameters.', 'status' => 0);
				}
				echo json_encode($response_array);
				die;
			}
		}
	}
	/*Add patients by Utc time 22-11-2022 by Madan*/
		 /*Add pateint version five 29-08-2022*/
	 public function addPatients_v6_bkp()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				$save_data = $saved_data = array();
				if (!empty($input_data['data']) && isset($input_data['user_id']) && !empty($input_data['user_id']) && isset($input_data['office_id']) && !empty($input_data['user_id'])) {
					
					$i = 0;
					foreach ($input_data['data'] as $data) {
						$patients = $data; 
						if (!empty($data['patient_id'])) {
							$patients['id'] = $patients['patient_id'];
							//unset($patients['unique_id']);
						} else {
							//$patients['unique_id'] = $input_data['unique_id'];
						}
						/*if($patients['unique_id']){
							$patients['unique_id'] = $data['unique_id'];
						}*/
						$patients['race'] = trim(@$data['race']);
						$patients['office_id'] = $input_data['office_id'];
						$patients['user_id'] = $input_data['user_id'];
						$patients['created'] = (isset($data['created_date']) && !empty($data['created_date'])) ? date('Y-m-d H:i:s', strtotime($data['created_date'])) : date('Y-m-d H:i:s');
						$this->Patientsnew->table = 'patients';
						$this->Patientsnew->useTable = 'patients';
						$this->Patientsnew->validate = array(
							'unique_id' => array(
								'notBlank' => array(
									'rule' => 'notBlank',
									'message' => 'Please enter Id number.'
								),
								'unique' => array(
									'rule' => 'isUnique',
									'message' => 'Please enter another unique id it is already taken.'
								),
							)
						);
						$this->Patientsnew->set($patients);
						if ($this->Patientsnew->validates()) {
							$rs = $this->Patients->save($patients);
							if ($rs) {
								$saved_data[$i]['id'] = $this->Patients->id;
								//$saved_data[$i]['unique_id'] = $this->Patients->unique_id;
								$saved_data[$i]['unique_id'] = $data['unique_id'];
							} 
						}
						if(!empty($data['unique_id'])){
						$result =$this->Patients->find('all', array('conditions' => array('Patients.unique_id' => $data['unique_id']), 'fields' => array('Patients.id')));
						foreach($result as $val){
							$alreadyinsertid[$i]['id'] = $val['Patients']['id'];
							$alreadyinsertid[$i]['unique_id'] = $data['unique_id'];
						}
					}
						$i++;
					}
					foreach($saved_data as $key => $value){ //pr($value); die;
					   // $conditions['Patient.id']=$value['id'];
					    	//date_default_timezone_set("Asia/Kolkata");
						date_default_timezone_set("America/Los_Angeles");
					    $patients2['id'] = $value['id'];
						$patients2['created_at_for_archive'] = date('Y-m-d H:i:s');
						$patients2['created'] = date('Y-m-d H:i:s');
						 $this->Patients->save($patients2); 
					}
					if (!empty($saved_data)) {
						$response_array = array('message' => 'Patients Saved successfully.', 'status' => 1, 'data' => $saved_data);
					}else if(empty($saved_data)){
						$response_array = array('message' => 'Already patients added.', 'status' => 1, 'data' => $alreadyinsertid);
					} else {
						$response_array = array('message' => 'Error in adding patients.', 'status' => 0, 'data' => $saved_data);
					}
				} else {
					$response_array = array('message' => 'Please send all parameters.', 'status' => 0);
				}
				echo json_encode($response_array);
				die;
			}
		}
	}
	/* Save staff */
	public function saveStaff()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				}
				$save_data = array(
					//'username' => @$input_data['username'],
					'first_name' => @$input_data['first_name'],
					'middle_name' => @$input_data['middle_name'],
					'last_name' => @$input_data['last_name'],
					'phone' => @$input_data['phone'],
					'gender' => @$input_data['gender'],
					'dob' => @$input_data['dob'],
				);
				if (@$input_data['email'] != '') {
					$check_email = $this->User->find('count', array('conditions' => array('User.email' => trim($input_data['email']), 'User.id !=' => $input_data['user_id'])));
					if ($check_email != 0) {
						$response_array = array('message' => 'Email already registered.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
					//$email_okay=preg_match('/^[\w\d-_]+@[\w\d-_]+\.ac\.uk$/',trim($input_data['email']));
					//echo $email_okay; die;
					if (!filter_var(trim($input_data['email']), FILTER_VALIDATE_EMAIL)) {
						$response_array = array('message' => 'Your email is invalid.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
					$save_data['email'] = @$input_data['email'];
				}
				$save_data = array(
					//'username' => @$input_data['username'],
					'first_name' => @$input_data['first_name'],
					'middle_name' => @$input_data['middle_name'],
					'last_name' => @$input_data['last_name'],
					'phone' => @$input_data['phone'],
					'gender' => @$input_data['gender'],
					'dob' => !empty($input_data['dob']) ? date('Y-m-d', strtotime(@$input_data['dob'])) : null,
				);
				if (!isset($input_data['user_id'])) {
					$response_array = array('message' => 'We need user id to precess this request.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				} else {
					if (isset($input_data['password'])) {
						$pass_length = strlen(@$input_data['password']);
						if (!(($pass_length >= 8) && ($pass_length <= 16))) {
							$response_array = array('message' => 'Your password length should be between 8 and 16 characters.', 'status' => 0);
							header('Content-Type: application/json');
							echo json_encode($response_array);
							die;
						}
						if (@$input_data['password'] != @$input_data['confirm_password']) {
							$response_array = array('message' => 'Your password and confirm password does not match.', 'status' => 0);
							header('Content-Type: application/json');
							echo json_encode($response_array);
							die;
						}
						$save_data['password'] = @$input_data['password'];
					}
					$save_data['id'] = $input_data['user_id'];
					$result = $this->User->save($save_data);
					if ($result) {
						$result['User']['user_id'] = $result['User']['id'];
						unset($result['User']['id']);
						$this->User->id = $result['User']['user_id'];
						$office_id = $this->User->field('office_id');
						$result['User']['office_id'] = $office_id;
						$response_array = array('message' => 'User Updated successfully.', 'status' => 1, 'data' => $result['User']);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} else {
						$response_array = array('message' => 'Some problems occured during process. Please try again.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				}
			}
		}
	}
	/* API for changing staff password */
	public function staff_change_password()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				}
				if (!isset($input_data['user_id'])) {
					$response_array = array('message' => 'We need user id to precess this request.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
				$check_staff = $this->User->find('first', array('conditions' => array('User.id' => $input_data['user_id'], 'User.is_delete' => 0)));
				if (empty($check_staff)) {
					$response_array = array('message' => 'Invalid staff.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
				$this->User->id = $input_data['user_id'];
				$save_password = $this->User->field('password');
				$newHash = Security::hash(@$input_data['old_password'], 'blowfish', $save_password);
				if ($save_password != $newHash) {
					$response_array = array('message' => 'Your old password did not match.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
				$pass_length = strlen(@$input_data['password']);
				if (!(($pass_length >= 8) && ($pass_length <= 16))) {
					$response_array = array('message' => 'Your password length should be between 8 and 16 characters.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
				if (@$input_data['password'] != @$input_data['confirm_password']) {
					$response_array = array('message' => 'Your password and confirm password does not match.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
				$save_data = array(
					'password' => @$input_data['password'],
					'id' => $input_data['user_id']
				);
				$result = $this->User->save($save_data);
				if ($result) {
					/* $result['User']['user_id'] = $result['User']['id'];
					unset($result['User']['id']); */
					$response_array = array('message' => 'Your password has been updated successfully.', 'status' => 1);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				} else {
					$response_array = array('message' => 'Some problems occured during process. Please try again.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
		die;
	}
	//API for changing patient status
	public function change_status()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				}
				if (@$input_data['status'] == 1) {
					@$input_data['status'] = 0;
				} elseif (@$input_data['status'] == 0) {
					@$input_data['status'] = 1;
				} else {
					$response_array = array('message' => 'Invalid status.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
				$save_data['Patients'] = array(
					'id' => @$input_data['patient_id'],
					'status' => @$input_data['status']
				);
				$result = $this->Patients->save($save_data);
				if ($result) {
					$result['Patients']['patient_id'] = $result['Patients']['id'];
					unset($result['Patients']['id']);
					$response_array = array('message' => 'Patients Status Changed successfully.', 'status' => 1, 'data' => $result['Patients']);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				} else {
					$response_array = array('message' => 'Some problems occured during process. Please try again.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
	//API for changing multiple patient status
	public function change_status_multiple()
	{
		if ($this->check_key()) {
			$this->layout = false;
			$this->loadModel('Patient');
			if ($this->validatePostRequest()) {
				//$input_data =  $_POST;
				$input_data2 = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data2)) {
					$input_data2 = $_POST;
				}
				if (!empty($input_data2['data'])) {
					foreach ($input_data2['data'] as $input_data) {
						if (@$input_data['status'] == 1) {
							@$input_data['status'] = 0;
						} elseif (@$input_data['status'] == 0) {
							@$input_data['status'] = 1;
						}
						$save_data['Patient'] = array(
							'id' => @$input_data['patient_id'],
							'status' => @$input_data['status']
						);
						$result = $this->Patient->save($save_data);
					}
					if ($result) {
						$response_array = array('message' => 'Patients Status Changed successfully.', 'status' => 1);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} else {
						$response_array = array('message' => 'Some problems occured during process. Please try again.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'Please send all parameters.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
	/* API for all test detail */
	public function test_list()
	{
		$this->loadModel('Office');
		if ($this->check_key()) {
			$this->layout = false;
			$this->loadModel('Test');
			//officereports
			$office_test = array();
			//if($this->validateGetRequest()){
			$test_list = $this->Test->find('list', array('conditions' => array('Test.is_delete' => '0')));
			if (!empty($test_list)) {
				$ii = 0;
				foreach ($test_list as $k => $tests) {
					$office_test[$ii]['key'] = $k;
					$office_test[$ii]['value'] = $tests;
					$ii++;
				}
			}
			if (!empty($this->request->data['office_id'])) {
				$this->loadModel('Officereport');
				$office_report = $this->Officereport->find('list', array('fields' => array('Officereport.office_report'), 'conditions' => array('Officereport.office_id' => $this->request->data['office_id'])));
			}
			$result = $this->Tests->find('all', array('conditions' => array('Tests.id' => @$office_report, 'Tests.status' => 1), 'order' => array('Tests.id DESC')));
			if (count($result)) {
				$data = array();
				foreach ($result as $key => $value) {
					$value['Tests']['test_id'] = $value['Tests']['id'];
					unset($value['Tests']['id']);
					$data[] = $value['Tests'];
				}
				$response_array = array(
					'message' => 'Get tests information.',
					'status' => 1,
					'data' => $data,
					/* 'reports'=> $office_test, */
					/* 'office_report' => !empty($office_report)?array_values($office_report):'' */
				);
				header('Content-Type: application/json');
				echo json_encode($response_array);
				die;
			} else {
				$response_array = array('message' => 'No Record found.', 'status' => 0);
				header('Content-Type: application/json');
				echo json_encode($response_array);
				die;
			}
			//}
		}
	}
	/* API for List patients by staff */
	public function listPatients()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				if (empty($data_arrays)) {
					$data_arrays = $_POST;
				}
				// staff ID
				if (isset($data_arrays['page'])) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 25 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 25 - 1;
					} else {
						$limit = $data_arrays['page'] * 25 + 1;
						$start = ($data_arrays['page'] - 1) * 25;
						$end = $data_arrays['page'] * 25 - 1;
					}
				} else {
					$limit = '';
					$start = 0;
				}
				if (isset($data_arrays['staff_id'])) {
					$staff_id = $data_arrays['staff_id'];
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $staff_id), 'fields' => array('User.office_id')));
					if (!empty($office_id)) {
						$patient_staus = isset($data_arrays['showActiveOnly']) ? $data_arrays['showActiveOnly'] : array(0, 1);
						$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
						$all_staff_ids = implode(",", $all_staff_ids);
						$all_staff_ids = explode(',', $all_staff_ids);
						$result = $this->Patients->find('all', array('conditions' => array('Patients.user_id' => $all_staff_ids, 'Patients.is_delete' => 0, 'Patients.status' => $patient_staus), 'order' => array('Patients.id DESC'), 'limit' => $limit));
					} else {
						$response_array['message'] = 'Invalid staff.';
						$response_array['result'] = 0;
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'Please send staff id.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
					//$result = $this->Patients->find('all',array('conditions'=>array('Patients.is_delete'=>0),'order'=>array('Patients.first_name ASC','Patients.middle_name ASC','Patients.last_name ASC'),'limit'=>$limit));
				}
				$all_result_count = count($result);
				if (isset($data_arrays['page']) && ($data_arrays['page'] == 0)) {
					$end = $all_result_count;
				}
				if (!isset($data_arrays['page'])) {
					$end = $all_result_count;
				}
				if (isset($data_arrays['page'])) {
					if ($data_arrays['page'] != 0) {
						if (($all_result_count > $data_arrays['page'] * 25)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
				} else {
					$more_data = 0;
				}
				if (count($result)) {
					$data = array();
					foreach ($result as $key => $value) {
						if ($key >= $start && $key <= $end) {
							$value['Patients']['patient_id'] = $value['Patients']['id'];
							$value['Patients']['middle_name'] = ($value['Patients']['middle_name'] != null) ? ($value['Patients']['middle_name']) : '';
							$value['Patients']['phone'] = ($value['Patients']['phone'] != null) ? ($value['Patients']['phone']) : '';
							$value['Patients']['id_number'] = ($value['Patients']['id_number'] != null) ? ($value['Patients']['id_number']) : '';
							$value['Patients']['notes'] = ($value['Patients']['notes'] != null) ? ($value['Patients']['notes']) : '';
							$value['Patients']['created'] = ($value['Patients']['created'] != null) ? ($value['Patients']['created']) : '';
							if (!empty($value['Patients']['p_profilepic'])) {
								$value['Patients']['p_profilepic'] = WWW_BASE . $value['Patients']['p_profilepic'];
							} else {
								$value['Patients']['p_profilepic'] = WWW_BASE . 'img/uploads/no-user.png';
							}
							$this->loadModel('TestReports');
							$report_status = $this->TestReports->find('all', array('conditions' => array('patient_id' => $value['Patients']['patient_id'])));
							if (count($report_status) > 0) {
								#$report_status = 1;
								$value['Patients']['patient_report_status'] = 1;
							} else {
								$value['Patients']['patient_report_status'] = 0;
							}
							unset($value['Patients']['id']);
							$data[] = $value['Patients'];
						}
					}
					if (!empty($data)) {
						$response_array = array('message' => 'Get patients information.', 'status' => 1, 'more_data' => $more_data, 'data' => $data);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} else {
						$response_array = array('message' => 'No record found.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'No Record found.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
	/* API for List patients by staff and names*/
	public function searchlistPatients()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				if (empty($data_arrays)) {
					$data_arrays = $_POST;
				}
				// staff ID
				if (isset($data_arrays['page'])) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 25 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 25 - 1;
					} else {
						$limit = $data_arrays['page'] * 25 + 1;
						$start = ($data_arrays['page'] - 1) * 25;
						$end = $data_arrays['page'] * 25 - 1;
					}
				} else {
					$limit = '';
					$start = 0;
				}
				if (isset($data_arrays['staff_id'])) {
					$staff_id = $data_arrays['staff_id'];
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $staff_id), 'fields' => array('User.office_id')));
					if (!empty($office_id)) {
						$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
						$all_staff_ids = implode(",", $all_staff_ids);
						$all_staff_ids = explode(',', $all_staff_ids);
						$patient_staus = isset($data_arrays['showActiveOnly']) ? $data_arrays['showActiveOnly'] : array(0, 1);
						//pr($patient_staus);
						if (isset($data_arrays['search_key']) && !empty($data_arrays['search_key'])) {
							$condition = array(
								'OR' => array(
									array(
										"Patients.first_name LIKE" => '%' . $data_arrays['search_key'],
									),
									array(
										"Patients.middle_name LIKE" => '%' . $data_arrays['search_key'],
									),
									array(
										"Patients.last_name LIKE" => '%' . $data_arrays['search_key'],
									),
									array(
										"Patients.email LIKE" => '%' . $data_arrays['search_key'],
									)
								),
								'Patients.user_id' => $all_staff_ids,
								'Patients.is_delete' => 0,
								'Patients.status' => $patient_staus
							);
						} else {
							$condition = array(
								'Patients.user_id' => $all_staff_ids,
								'Patients.is_delete' => 0,
								'Patients.status' => $patient_staus
							);
						}
						$result = $this->Patients->find('all', array('conditions' => $condition, 'order' => array('Patients.id DESC'), 'limit' => $limit));
						//pr($result);die;
					} else {
						$response_array['message'] = 'Invalid staff.';
						$response_array['result'] = 0;
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'Please send staff id.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
					//$result = $this->Patients->find('all',array('conditions'=>array('Patients.is_delete'=>0),'order'=>array('Patients.first_name ASC','Patients.middle_name ASC','Patients.last_name ASC'),'limit'=>$limit));
				}
				$all_result_count = count($result);
				if (isset($data_arrays['page']) && ($data_arrays['page'] == 0)) {
					$end = $all_result_count;
				}
				if (!isset($data_arrays['page'])) {
					$end = $all_result_count;
				}
				if (isset($data_arrays['page'])) {
					if ($data_arrays['page'] != 0) {
						if (($all_result_count > $data_arrays['page'] * 25)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
				} else {
					$more_data = 0;
				}
				if (count($result)) {
					$data = array();
					foreach ($result as $key => $value) {
						if ($key >= $start && $key <= $end) {
							$value['Patients']['patient_id'] = $value['Patients']['id'];
							$value['Patients']['middle_name'] = ($value['Patients']['middle_name'] != null) ? ($value['Patients']['middle_name']) : '';
							$value['Patients']['phone'] = ($value['Patients']['phone'] != null) ? ($value['Patients']['phone']) : '';
							$value['Patients']['id_number'] = ($value['Patients']['id_number'] != null) ? ($value['Patients']['id_number']) : '';
							$value['Patients']['notes'] = ($value['Patients']['notes'] != null) ? ($value['Patients']['notes']) : '';
							$value['Patients']['created'] = ($value['Patients']['created'] != null) ? ($value['Patients']['created']) : '';
							if (!empty($value['Patients']['p_profilepic'])) {
								$value['Patients']['p_profilepic'] = WWW_BASE . $value['Patients']['p_profilepic'];
							} else {
								$value['Patients']['p_profilepic'] = WWW_BASE . 'img/uploads/no-user.png';
							}
							$this->loadModel('TestReports');
							$report_status = $this->TestReports->find('all', array('conditions' => array('patient_id' => $value['Patients']['patient_id'])));
							if (count($report_status) > 0) {
								#$report_status = 1;
								$value['Patients']['patient_report_status'] = 1;
							} else {
								$value['Patients']['patient_report_status'] = 0;
							}
							unset($value['Patients']['id']);
							$data[] = $value['Patients'];
						}
					}
					if (!empty($data)) {
						$response_array = array('message' => 'Get patients information.', 'status' => 1, 'more_data' => $more_data, 'data' => $data);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} else {
						$response_array = array('message' => 'No record found.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'No Record found.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
	/* API for get patient detail */
	public function getPatients()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validateGetRequest()) {
				$input_data = $this->request->params['pass'][0];
				if (isset($input_data)) {
					$result = $this->Patients->find('first', array('conditions' => array('Patients.id' => $input_data, 'Patients.is_delete' => 0)));
					if (count($result)) {
						$result['Patients']['patient_id'] = $result['Patients']['id'];
						if (!empty($result['Patients']['p_profilepic'])) {
							$result['Patients']['p_profilepic'] = WWW_BASE . $result['Patients']['p_profilepic'];
						}
						unset($result['Patients']['id']);
						$response_array = array('message' => 'Patient Information.', 'status' => 1, 'data' => $result['Patients']);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} else {
						$response_array = array('message' => 'No Record found.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'please send required parameter.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
	/* API for Delete patients */
	public function deletePatients()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				}
				$delete_type = @$input_data['delete_type'];
				$input_data = @$input_data['patient_id'];
				if (isset($input_data)) {
					//pr($input_data);  die;
					$delete_patient['Patients']['id'] = $input_data;
					$delete_patient['Patients']['delete_date'] = date('Y-m-d H:i:s');
					$delete_patient['Patients']['is_delete'] = 1;
					if ($this->Patients->save($delete_patient)) {
						$this->loadModel('Testreport');
						$this->loadModel('Pointdata');
						$this->loadModel('VaData');
						$this->loadModel('CsData');
						$this->Testreport->updateAll(array('Testreport.is_delete' => '1'), array('Testreport.patient_id' => $input_data));
						$this->Pointdata->updateAll(array('Pointdata.is_delete' => '1'), array('Pointdata.patient_id' => $input_data));
						$this->VaData->updateAll(array('VaData.is_delete' => '1'), array('VaData.patient_id' => $input_data));
						$this->CsData->updateAll(array('CsData.is_delete' => '1'), array('CsData.patient_id' => $input_data));
						/* if($delete_type==0){
							$this->Patients->updateAll(array('Patients.delete_date' => date('Y-m-d H:i:s')),array('Patients.patient_id' => $input_data));
						} */
						$response_array['status'] = 1;
						$response_array['message'] = 'Record deleted Successfully';
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} else {
						$response_array['status'] = 0;
						$response_array['message'] = 'No record found';
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'Something went wrong.  Please try again.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
				die;
			}
		}
	}
	/* API for Delete Multiple patients */
	public function deletePatients_multiple()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				//$input_data =  $_POST;
				$input_data2 = json_decode(file_get_contents('php://input'), true);
				if (!empty($input_data2['data'])) {
					foreach ($input_data2['data'] as $input_data) {
						$delete_type = @$input_data['patient_status'];
						$input_data = @$input_data['patient_id'];
						if (isset($input_data)) {
							$delete_patient['Patients']['id'] = $input_data;
							$delete_patient['Patients']['is_delete'] = 1;
							if ($delete_type == 0) {
								$delete_patient['Patients']['delete_date'] = date('Y-m-d H:i:s');
							} else {
								$delete_patient['Patients']['delete_date'] = date('Y-m-d H:i:s');
							}
							if ($this->Patients->save($delete_patient)) {
								$this->loadModel('Testreport');
								$this->loadModel('Pointdata');
								$this->loadModel('VaData');
								$this->loadModel('CsData');
								$this->Testreport->updateAll(array('Testreport.is_delete' => '1'), array('Testreport.patient_id' => $input_data));
								$this->Pointdata->updateAll(array('Pointdata.is_delete' => '1'), array('Pointdata.patient_id' => $input_data));
								$this->VaData->updateAll(array('VaData.is_delete' => '1'), array('VaData.patient_id' => $input_data));
								$this->CsData->updateAll(array('CsData.is_delete' => '1'), array('CsData.patient_id' => $input_data));
								$response_array['status'] = 1;
								$response_array['delete_date'] = date('Y-m-d H:i:s');
								$response_array['message'] = 'Record deleted Successfully';
								header('Content-Type: application/json');
								echo json_encode($response_array);
								die;
							} else {
								$response_array['status'] = 0;
								$response_array['message'] = 'No record found';
								header('Content-Type: application/json');
								echo json_encode($response_array);
								die;
							}
						} else {
							$response_array = array('message' => 'Something went wrong.  Please try again.', 'status' => 0);
							header('Content-Type: application/json');
							echo json_encode($response_array);
							die;
						}
						die;
					}
				} else {
					$response_array = array('message' => 'Please send all parameter.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
	//This API for saving and updating device id and device token of user
	public function get_device()
	{
		$this->autoRender = false;
		$this->loadModel('UserDevice');
		$response = array();
		$data_arrays = json_decode(file_get_contents("php://input"), true);
		if (empty($data_arrays)) {
			$data_arrays = $_POST;
		}
		$save_data['UserDevice']['user_id'] = isset($data_arrays['user_id']) ? $data_arrays['user_id'] : '';
		$save_data['UserDevice']['device_type'] = isset($data_arrays['device_type']) ? $data_arrays['device_type'] : '';
		$save_data['UserDevice']['device_id'] = isset($data_arrays['device_id']) ? $data_arrays['device_id'] : '';
		$save_data['UserDevice']['device_token'] = isset($data_arrays['device_token']) ? $data_arrays['device_token'] : '';
		if ((!empty($save_data['UserDevice']['user_id'])) && (!empty($save_data['UserDevice']['device_id'])) && (!empty($save_data['UserDevice']['device_type'])) && (!empty($save_data['UserDevice']['device_token']))) {
			$check = $this->UserDevice->find('first', array('conditions' => array('UserDevice.device_token' => $save_data['UserDevice']['device_token'], 'UserDevice.device_type' => $save_data['UserDevice']['device_type'])));
			if (empty($check)) {
				$this->UserDevice->save($save_data);
				$id = $this->UserDevice->id;
				$response['message'] = 'Device id saved successfully.';
				$response['result'] = 1;
				$response['data'] = array('id' => $id);
			} else {
				$this->UserDevice->id = $check['UserDevice']['id'];
				$this->UserDevice->save($save_data);
				$response['message'] = 'Device id saved successfully.';
				$response['result'] = 1;
			}
		} else {
			$response['message'] = 'Some fields empty.';
			$response['result'] = 0;
		}
		echo json_encode($response);
		exit();
	}
	//This Api for getting Patients data by office(staff_id given)
	public function get_Patients_by_office()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				if (empty($data_arrays)) {
					$data_arrays = $_POST;
				}
				if ((isset($data_arrays['staff_id'])) && (!empty($data_arrays['staff_id']))) {
					$staff_id = $data_arrays['staff_id'];
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $staff_id), 'fields' => array('User.office_id')));
					if (!empty($office_id)) {
						$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
						$all_staff_ids = implode(",", $all_staff_ids);
						//$all_staff_ids=$sub_admin_id['User']['created_by'].','.$all_staff_ids;
						$all_staff_ids = explode(',', $all_staff_ids);
						if (isset($data_arrays['page_count'])) {
							$page_count = $data_arrays['page_count'];
						} else {
							$page_count = 10;
						}
						if (isset($data_arrays['page'])) {
							if ($data_arrays['page'] == 0) {
								$limit = '';
								$start = 0;
							} elseif ($data_arrays['page'] == 1) {
								$limit = $data_arrays['page'] * $page_count + 1;
								$start = 0;
								$end = $data_arrays['page'] * $page_count - 1;
							} else {
								$limit = $data_arrays['page'] * $page_count + 1;
								$start = ($data_arrays['page'] - 1) * $page_count;
								$end = $data_arrays['page'] * $page_count - 1;
							}
						} else {
							$limit = '';
							$start = 0;
						}
						$all_patients = $this->Patients->find('all', array('conditions' => array('Patients.user_id' => $all_staff_ids, 'Patients.is_delete' => 0), 'fields' => array('Patients.id', 'Patients.first_name', 'Patients.middle_name', 'Patients.last_name', 'Patients.created'), 'order' => array('Patients.created DESC', 'Patients.first_name ASC', 'Patients.middle_name ASC', 'Patients.last_name ASC'), 'limit' => $limit));
						$all_patients_count = count($all_patients);
						if (isset($data_arrays['page']) && ($data_arrays['page'] == 0)) {
							$end = $all_patients_count;
						}
						if (!isset($data_arrays['page'])) {
							$end = $all_patients_count;
						}
						if (isset($data_arrays['page'])) {
							if ($data_arrays['page'] != 0) {
								if (($all_patients_count > $data_arrays['page'] * $page_count)) {
									$more_data = 1;
								} else {
									$more_data = 0;
								}
							} else {
								$more_data = 0;
							}
						} else {
							$more_data = 0;
						}
						if (!empty($all_patients)) {
							$data = array();
							$i = 0;
							foreach ($all_patients as $key => $patient) {
								if ($key >= $start && $key <= $end) {
									$data[$i]['patient_id'] = $patient['Patients']['id'];
									$data[$i]['patient_first_name'] = ($patient['Patients']['first_name'] != null) ? $patient['Patients']['first_name'] : '';
									$data[$i]['patient_middle_name'] = ($patient['Patients']['middle_name'] != null) ? $patient['Patients']['middle_name'] : '';
									$data[$i]['patient_last_name'] = ($patient['Patients']['last_name'] != null) ? $patient['Patients']['last_name'] : '';
									$data[$i]['created'] = date('Y-m-d h:i:s', strtotime($patient['Patients']['created']));
									$i++;
								}
							}
							if (!empty($data)) {
								$response['message'] = 'All Patients found successfully.';
								$response['result'] = 1;
								$response['more_data'] = $more_data;
								$response['data'] = $data;
							} else {
								$response['message'] = 'No patient found.';
								$response['more_data'] = $more_data;
								$response['result'] = 0;
							}
						} else {
							$response['message'] = 'No patient found.';
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	//This Api for search Patients data by office(staff_id given and search key)
	public function search_Patients_by_office()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				//$data_arrays = $_POST;
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				if (empty($data_arrays)) {
					$data_arrays = $_POST;
				}
				if ((isset($data_arrays['staff_id'])) && (!empty($data_arrays['staff_id']))) {
					$staff_id = $data_arrays['staff_id'];
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $staff_id), 'fields' => array('User.office_id')));
					if (!empty($office_id)) {
						$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
						$all_staff_ids = implode(",", $all_staff_ids);
						//$all_staff_ids=$sub_admin_id['User']['created_by'].','.$all_staff_ids;
						$all_staff_ids = explode(',', $all_staff_ids);
						if (isset($data_arrays['page_count'])) {
							$page_count = $data_arrays['page_count'];
						} else {
							$page_count = 10;
						}
						if (isset($data_arrays['page'])) {
							if ($data_arrays['page'] == 0) {
								$limit = '';
								$start = 0;
							} elseif ($data_arrays['page'] == 1) {
								$limit = $data_arrays['page'] * $page_count + 1;
								$start = 0;
								$end = $data_arrays['page'] * $page_count - 1;
							} else {
								$limit = $data_arrays['page'] * $page_count + 1;
								$start = ($data_arrays['page'] - 1) * $page_count;
								$end = $data_arrays['page'] * $page_count - 1;
							}
						} else {
							$limit = '';
							$start = 0;
						}
						if (isset($data_arrays['search_key']) && !empty($data_arrays['search_key'])) {
							$data_arrays['search_key'] = strtolower($data_arrays['search_key']);
							$condition = array(
								'OR' => array(
									array(
										"LOWER(Patients.first_name) LIKE" => $data_arrays['search_key'] . '%'
									),
									array(
										"LOWER(Patients.middle_name) LIKE" => $data_arrays['search_key'] . '%'
									),
									array(
										"LOWER(Patients.last_name) LIKE" => $data_arrays['search_key'] . '%'
									),
									array(
										"LOWER(Patients.email) LIKE" => $data_arrays['search_key'] . '%'
									)
								),
								'Patients.user_id' => $all_staff_ids,
								'Patients.is_delete' => 0
							);
						} else {
							$condition = array(
								'Patients.user_id' => $all_staff_ids,
								'Patients.is_delete' => 0
							);
						}
						$all_patients = $this->Patients->find('all', array('conditions' => $condition, 'fields' => array('Patients.id', 'Patients.first_name', 'Patients.middle_name', 'Patients.last_name', 'Patients.created'), 'order' => array('Patients.created DESC', 'Patients.first_name ASC', 'Patients.middle_name ASC', 'Patients.last_name ASC'), 'limit' => $limit));
						$all_patients_count = count($all_patients);
						if (isset($data_arrays['page']) && ($data_arrays['page'] == 0)) {
							$end = $all_patients_count;
						}
						if (!isset($data_arrays['page'])) {
							$end = $all_patients_count;
						}
						if (isset($data_arrays['page'])) {
							if ($data_arrays['page'] != 0) {
								if (($all_patients_count > $data_arrays['page'] * $page_count)) {
									$more_data = 1;
								} else {
									$more_data = 0;
								}
							} else {
								$more_data = 0;
							}
						} else {
							$more_data = 0;
						}
						if (!empty($all_patients)) {
							$data = array();
							$i = 0;
							foreach ($all_patients as $key => $patient) {
								if ($key >= $start && $key <= $end) {
									$data[$i]['patient_id'] = $patient['Patients']['id'];
									$data[$i]['patient_first_name'] = ($patient['Patients']['first_name'] != null) ? $patient['Patients']['first_name'] : '';
									$data[$i]['patient_middle_name'] = ($patient['Patients']['middle_name'] != null) ? $patient['Patients']['middle_name'] : '';
									$data[$i]['patient_last_name'] = ($patient['Patients']['last_name'] != null) ? $patient['Patients']['last_name'] : '';
									$data[$i]['created'] = $patient['Patients']['created'];
									$i++;
								}
							}
							if (!empty($data)) {
								$response['message'] = 'All Patients found successfully.';
								$response['result'] = 1;
								$response['more_data'] = $more_data;
								$response['data'] = $data;
							} else {
								$response['message'] = 'No patient found.';
								$response['more_data'] = $more_data;
								$response['result'] = 0;
							}
						} else {
							$response['message'] = 'No patient found.';
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	public function search_Patients_by_office_new()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				//$data_arrays = $_POST;
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				if (empty($data_arrays)) {
					$data_arrays = $_POST;
				}
				if ((isset($data_arrays['staff_id'])) && (!empty($data_arrays['staff_id']))) {
					$staff_id = $data_arrays['staff_id'];
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $staff_id), 'fields' => array('User.office_id')));
					if (!empty($office_id)) {
						$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
						$all_staff_ids = implode(",", $all_staff_ids);
						//$all_staff_ids=$sub_admin_id['User']['created_by'].','.$all_staff_ids;
						$all_staff_ids = explode(',', $all_staff_ids);
						if (isset($data_arrays['page_count'])) {
							$page_count = $data_arrays['page_count'];
						} else {
							$page_count = 10;
						}
						if (isset($data_arrays['page'])) {
							if ($data_arrays['page'] == 0) {
								$limit = '';
								$start = 0;
							} elseif ($data_arrays['page'] == 1) {
								$limit = $data_arrays['page'] * $page_count + 1;
								$start = 0;
								$end = $data_arrays['page'] * $page_count - 1;
							} else {
								$limit = $data_arrays['page'] * $page_count + 1;
								$start = ($data_arrays['page'] - 1) * $page_count;
								$end = $data_arrays['page'] * $page_count - 1;
							}
						} else {
							$limit = '';
							$start = 0;
						}
						$this->Patients->virtualFields['patient_name'] = "CONCAT(Patients.first_name, ' ', Patients.last_name)";
						if (isset($data_arrays['search_key']) && !empty($data_arrays['search_key'])) {
							$data_arrays['search_key'] = strtolower($data_arrays['search_key']);
							$condition = array(
								'OR' => array(
									array(
										"LOWER(Patients.first_name) LIKE" => $data_arrays['search_key'] . '%'
									),
									array(
										"LOWER(Patients.middle_name) LIKE" => $data_arrays['search_key'] . '%'
									),
									array(
										"LOWER(Patients.last_name) LIKE" => $data_arrays['search_key'] . '%'
									),
									array(
										"LOWER(Patients.email) LIKE" => $data_arrays['search_key'] . '%'
									)
								),
								'Patients.user_id' => $all_staff_ids,
								'Patients.is_delete' => 0
							);
						} else {
							$condition = array(
								'Patients.user_id' => $all_staff_ids,
								'Patients.is_delete' => 0
							);
						}
						if (isset($data_arrays['dob']) && !empty($data_arrays['dob'])) {
							$condition['AND']['Patients.dob'] = $data_arrays['dob'];
						}
						$all_patients = $this->Patients->find('all', array('conditions' => $condition, 'fields' => array('Patients.*'), 'order' => array('Patients.created DESC', 'Patients.first_name ASC', 'Patients.middle_name ASC', 'Patients.last_name ASC'), 'limit' => $limit));
						$all_patients_count = count($all_patients);
						if (isset($data_arrays['page']) && ($data_arrays['page'] == 0)) {
							$end = $all_patients_count;
						}
						if (!isset($data_arrays['page'])) {
							$end = $all_patients_count;
						}
						if (isset($data_arrays['page'])) {
							if ($data_arrays['page'] != 0) {
								if (($all_patients_count > $data_arrays['page'] * $page_count)) {
									$more_data = 1;
								} else {
									$more_data = 0;
								}
							} else {
								$more_data = 0;
							}
						} else {
							$more_data = 0;
						}
						if (!empty($all_patients)) {
							$data = array();
							$i = 0;
							foreach ($all_patients as $key => $patient) {
								if ($key >= $start && $key <= $end) {
									$data[$i]['patient_id'] = $patient['Patients']['id'];
									$data[$i]['patient_first_name'] = ($patient['Patients']['first_name'] != null) ? $patient['Patients']['first_name'] : '';
									$data[$i]['patient_middle_name'] = ($patient['Patients']['middle_name'] != null) ? $patient['Patients']['middle_name'] : '';
									$data[$i]['patient_last_name'] = ($patient['Patients']['last_name'] != null) ? $patient['Patients']['last_name'] : '';
									$data[$i]['created'] = $patient['Patients']['created'];
									$data[$i]['dob'] = ($patient['Patients']['dob'] != null) ? $patient['Patients']['dob'] : '';
									$data[$i]['first_name'] = ($patient['Patients']['first_name'] != null) ? $patient['Patients']['first_name'] : '';
									$data[$i]['middle_name'] = ($patient['Patients']['middle_name'] != null) ? $patient['Patients']['middle_name'] : '';
									$data[$i]['last_name'] = ($patient['Patients']['last_name'] != null) ? $patient['Patients']['last_name'] : '';
									$data[$i]['id_number'] = ($patient['Patients']['id_number'] != null) ? $patient['Patients']['id_number'] : '';
									$data[$i]['email'] = ($patient['Patients']['email'] != null) ? $patient['Patients']['email'] : '';
									$data[$i]['user_id'] = ($patient['Patients']['user_id'] != null) ? $patient['Patients']['user_id'] : '';
									$data[$i]['notes'] = ($patient['Patients']['notes'] != null) ? $patient['Patients']['notes'] : '';
									$data[$i]['phone'] = ($patient['Patients']['phone'] != null) ? $patient['Patients']['phone'] : '';
									$data[$i]['office_id'] = ($patient['Patients']['office_id'] != null) ? $patient['Patients']['office_id'] : '';
									$data[$i]['status'] = ($patient['Patients']['status'] != null) ? $patient['Patients']['status'] : '';
									$data[$i]['is_deleted'] = ($patient['Patients']['is_delete'] != null) ? $patient['Patients']['is_delete'] : '';
									$data[$i]['patient_id'] = ($patient['Patients']['id'] != null) ? $patient['Patients']['id'] : '';
									$data[$i]['od_left'] = ($patient['Patients']['od_left'] != null) ? $patient['Patients']['od_left'] : '';
									$data[$i]['od_right'] = ($patient['Patients']['od_right'] != null) ? $patient['Patients']['od_right'] : '';
									$data[$i]['os_left'] = ($patient['Patients']['os_left'] != null) ? $patient['Patients']['os_left'] : '';
									$data[$i]['os_right'] = ($patient['Patients']['os_right'] != null) ? $patient['Patients']['os_right'] : '';
									$data[$i]['p_profilepic'] = ($patient['Patients']['p_profilepic'] != null) ? WWW_BASE . '' . $patient['Patients']['p_profilepic'] : WWW_BASE . 'img/uploads/no-user.png';
									$data[$i]['race'] = ($patient['Patients']['race'] != null) ? $patient['Patients']['race'] : '';
									$i++;
								}
							}
							if (!empty($data)) {
								$response['message'] = 'All Patients found successfully.';
								$response['status'] = 1;
								$response['more_data'] = $more_data;
								$response['data'] = $data;
							} else {
								$response['message'] = 'No patient found.';
								$response['more_data'] = $more_data;
								$response['status'] = 0;
							}
						} else {
							$response['message'] = 'No patient found.';
							$response['status'] = 0;
						}
					} else {
						$response['message'] = 'Invalid staff.';
						$response['status'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['status'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	//This API for all notifications by staff_id
	public function get_staff_notification()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				if (empty($data_arrays)) {
					$data_arrays = $_POST;
				}
				if ((isset($data_arrays['staff_id'])) && (!empty($data_arrays['staff_id']))) {
					$staff_id = $data_arrays['staff_id'];
					$check_staff = $this->User->find('first', array('conditions' => array('User.id' => $staff_id)));
					if (!empty($check_staff)) {
						if (isset($data_arrays['page'])) {
							if ($data_arrays['page'] == 0) {
								$limit = '';
								$start = 0;
							} elseif ($data_arrays['page'] == 1) {
								$limit = $data_arrays['page'] * 10 + 1;
								$start = 0;
								$end = $data_arrays['page'] * 10 - 1;
							} else {
								$limit = $data_arrays['page'] * 10 + 1;
								$start = ($data_arrays['page'] - 1) * 10;
								$end = $data_arrays['page'] * 10 - 1;
							}
						} else {
							$limit = '';
							$start = 0;
						}
						$all_notifications = $this->UserNotification->find('all', array('conditions' => array('UserNotification.user_id' => $staff_id), 'fields' => array('UserNotification.id', 'UserNotification.text', 'UserNotification.created', 'UserNotification.status')));
						$all_notification_count = count($all_notifications);
						if (isset($data_arrays['page']) && ($data_arrays['page'] == 0)) {
							$end = $all_notification_count - 1;
						}
						if (!isset($data_arrays['page'])) {
							$end = $all_notification_count - 1;
						}
						if (isset($data_arrays['page'])) {
							if ($data_arrays['page'] != 0) {
								if (($all_notification_count > $data_arrays['page'] * 10)) {
									$more_data = 1;
								} else {
									$more_data = 0;
								}
							} else {
								$more_data = 0;
							}
						} else {
							$more_data = 0;
						}
						if (!empty($all_notifications)) {
							$data = array();
							$i = 0;
							foreach ($all_notifications as $key => $notification) {
								if ($key >= $start && $key <= $end) {
									$data[$i]['id'] = $notification['UserNotification']['id'];
									$data[$i]['text'] = $notification['UserNotification']['text'];
									$data[$i]['created'] = $notification['UserNotification']['created'];
									$data[$i]['status'] = $notification['UserNotification']['status'];
									$i++;
								}
							}
							if (!empty($data)) {
								$response['message'] = 'All notification found successfully.';
								$response['result'] = 1;
								$response['more_data'] = $more_data;
								$response['data'] = $data;
							} else {
								$response['message'] = 'No notification found.';
								$response['more_data'] = $more_data;
								$response['result'] = 0;
							}
						} else {
							$response['message'] = 'No notification found.';
							$response['more_data'] = $more_data;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*API for staff detail*/
	public function get_staff_detail()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				if (empty($data_arrays)) {
					$data_arrays = $_POST;
				}
				if ((isset($data_arrays['staff_id'])) && (!empty($data_arrays['staff_id']))) {
					$staff_id = $data_arrays['staff_id'];
					$check_staff = $this->User->find('first', array('conditions' => array('User.id' => $staff_id, 'User.user_type' => 'Staffuser', 'User.is_delete' => 0)));
					//print_r($check_staff);die;
					if (!empty($check_staff)) {
						$data = array();
						$data['staff_first_name'] = $check_staff['User']['first_name'];
						$data['staff_middle_name'] = $check_staff['User']['middle_name'];
						$data['staff_last_name'] = $check_staff['User']['last_name'];
						$data['staff_username'] = $check_staff['User']['username'];
						$data['staff_email'] = $check_staff['User']['email'];
						$data['staff_phone'] = $check_staff['User']['phone'];
						$data['staff_gender'] = $check_staff['User']['gender'];
						$data['staff_office_id'] = $check_staff['User']['office_id'];
						$data['staff_dob'] = $check_staff['User']['dob'];
						$data['staff_created'] = $check_staff['User']['created'];
						$data['staff_office'] = $check_staff['Office']['name'];
						$response['message'] = 'Staff Detail.';
						$response['result'] = 1;
						$response['data'] = $data;
					} else {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*API for update notification status*/
	public function update_notification_status()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				if (empty($data_arrays)) {
					$data_arrays = $_POST;
				}
				if (((isset($data_arrays['notification_id'])) && (!empty($data_arrays['notification_id']))) &&
					((isset($data_arrays['staff_id'])) && (!empty($data_arrays['staff_id'])))) {
					$staff_id = $data_arrays['staff_id'];
					$check_staff = $this->User->find('first', array('conditions' => array('User.id' => $staff_id, 'User.user_type' => 'Staffuser')));
					if (!empty($check_staff)) {
						$notification_id = $data_arrays['notification_id'];
						$check_notification = $this->UserNotification->find('first', array('conditions' => array('UserNotification.id' => $notification_id, 'UserNotification.user_id' => $staff_id)));
						if (!empty($check_notification)) {
							$data = array();
							$save_data = array(
								'id' => $notification_id,
								'status' => 1
							);
							$restult = $this->UserNotification->save($save_data);
							if ($restult) {
								$response['message'] = 'You read Notification.';
								$response['result'] = 1;
							} else {
								$response['message'] = 'Some error occured in reading notification.';
								$response['result'] = 0;
							}
						} else {
							$response['message'] = 'Invalid notification.';
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'Invalid Staff.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*API for Delete multiple notification*/
	public function delete_notifications()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				if (empty($data_arrays)) {
					$data_arrays = $_POST;
				}
				if (((isset($data_arrays['data'])) && (!empty($data_arrays['data']))) &&
					((isset($data_arrays['staff_id'])) && (!empty($data_arrays['staff_id'])))) {
					$staff_id = $data_arrays['staff_id'];
					$check_staff = $this->User->find('first', array('conditions' => array('User.id' => $staff_id, 'User.user_type' => 'Staffuser')));
					if (!empty($check_staff)) {
						foreach ($data_arrays['data'] as $data) {
							$this->UserNotification->delete($data['id']);
						}
						$response['message'] = 'Notifications deleted successfully.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'Invalid Staff.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*API for logout*/
	/*
		API Name:  https://www.portal.micromedinc.com/apisnew/logout
		Request Parameter: user_id
		Date: 28 March, 2018
	*/
		public function logout()
	{
		header("Content-Type: application/json; charset=UTF-8");
		$response['message'] = 'Invalid Request.';
		$response['result'] = 0;
		$checkUniqueName = '';
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$input_data = $_POST;
				if (empty($input_data)) {
					$input_data = json_decode(file_get_contents('php://input'), true);
					$user_id = $input_data['user_id'];
					//echo $office_id; die;
					$countConsent = $this->User->find('count',
						array(
							'conditions' => ['User.id' => $user_id, 'User.first_consent !=' => NULL]
						)
					);
					//pr($countConsent); die;
					$data = [];

					if (!empty($countConsent)) {
						$response['result'] = 1;
					} else {
						$response['message'] = 'Check your input and try again';
						$response['result'] = 0;
					}
				}
			}
		}
		//pr($response); die;
		echo json_encode($response);
		exit;
	}
	/*API for saving test report*/
	public function save_test_report()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (((isset($data_arrays['test_id'])) && (!empty($data_arrays['test_id']))) &&
					((isset($data_arrays['staff_id'])) && (!empty($data_arrays['staff_id']))) &&
					((isset($data_arrays['patient_id'])) && (!empty($data_arrays['patient_id']))) &&
					((isset($data_arrays['result'])) && (!empty($data_arrays['result'])))) {
					$data['TestReport']['test_id'] = @$data_arrays['test_id'];
					//$data['TestReport']['practice_id']=@$data_arrays['practice_id'];
					$data['TestReport']['staff_id'] = @$data_arrays['staff_id'];
					$data['TestReport']['patient_id'] = @$data_arrays['patient_id'];
					$data['TestReport']['result'] = @$data_arrays['result'];
					$result = $this->TestReport->save($data);
					if ($result) {
						$response['message'] = 'Test report saved successfully.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'Some error occured in saving report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*Updated version of API for saving test report*/
	public function save_test_report_V1()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (((isset($data_arrays['test_id'])) && (!empty($data_arrays['test_id']))) &&
					((isset($data_arrays['staff_id'])) && (!empty($data_arrays['staff_id']))) &&
					((isset($data_arrays['patient_id'])) && (!empty($data_arrays['patient_id']))) &&
					((isset($data_arrays['result'])) && (!empty($data_arrays['result'])))) {
					$data['TestReport']['test_id'] = @$data_arrays['test_id'];
					//$data['TestReport']['practice_id']=@$data_arrays['practice_id'];
					$data['TestReport']['staff_id'] = @$data_arrays['staff_id'];
					$data['TestReport']['patient_id'] = @$data_arrays['patient_id'];
					$data['TestReport']['result'] = @$data_arrays['result'];
					//
					if (isset($data_arrays['pdf']) && (!empty($data_arrays['pdf']))) {
						$folder_name = "uploads/pdf";
						$data['TestReport']['pdf'] = $this->base64_to_pdf($data_arrays['pdf'], $folder_name);
					}
					//print_r($data);die;
					$result = $this->TestReport->save($data);
					if ($result) {
						$response['message'] = 'Test report saved successfully.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'Some error occured in saving report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/* Updated version of save_test_report_V1 API */
	public function save_test_report_V2()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if ((isset($data_arrays['data'])) && (!empty($data_arrays['data']))) {
					$i = 0;
					foreach ($data_arrays['data'] as $key => $report) {
						$data['TestReport'][$i]['test_id'] = @$report['test_id'];
						$data['TestReport'][$i]['staff_id'] = @$report['staff_id'];
						$data['TestReport'][$i]['patient_id'] = @$report['patient_id'];
						$data['TestReport'][$i]['result'] = @$report['result'];
						if (isset($report['pdf']) && (!empty($report['pdf']))) {
							$folder_name = "uploads/pdf";
							$data['TestReport'][$i]['pdf'] = $this->base64_to_pdf($report['pdf'], $folder_name, $key);
						}
						$i++;
					}
					if ($this->TestReport->saveAll($data['TestReport'])) {
						$response['message'] = 'Test reports saved successfully.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'Some error occured in saving report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Please send some reports.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*API for getting test report by office(staff_id given)*/
	public function get_test_report()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				if (empty($data_arrays)) {
					$data_arrays = $_POST;
				}
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 10 - 1;
					} else {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = ($data_arrays['page'] - 1) * 10;
						$end = $data_arrays['page'] * 10 - 1;
					}
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id'], 'User.user_type' => 'Staffuser'), 'fields' => array('User.office_id')));
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id'], 'User.user_type' => 'Staffuser'), 'fields' => array('User.id')));
					$this->TestReport->virtualFields['test_name'] = 'IF(EXISTS(select name from mmd_tests as tests where TestReport.test_id = tests.id),(select name from mmd_tests as tests where TestReport.test_id = tests.id),"")';
					//$this->TestReport->virtualFields['practice_name'] = 'select name from mmd_practices as practices where TestReport.practice_id = practices.id';
					$this->TestReport->virtualFields['patient_name'] = 'select concat(first_name," ",middle_name," ",last_name) from mmd_patients as patients where TestReport.patient_id = patients.id';
					$this->TestReport->virtualFields['staff_name'] = 'select concat(first_name," ",middle_name," ",last_name) as name from mmd_users as users where TestReport.staff_id = users.id';
					/* if(isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])){
						$condition =  array(
								'TestReport.staff_id'=>$all_staff_ids,
								'TestReport.patient_id'=>$data_arrays['patient_id']
							  );
					} else{
						$condition =  array(
								'TestReport.staff_id'=>$all_staff_ids
							  );
					} */
					$condition['TestReport.staff_id'] = $all_staff_ids;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['TestReport.patient_id'] = $data_arrays['patient_id'];
					}
					if (isset($data_arrays['master_key'])) {
						$condition['TestReport.master_key'] = $data_arrays['master_key'];
					}
					$results = $this->TestReport->find('all', array('conditions' => $condition, 'fields' => array('id', 'test_name', 'created', 'result', 'patient_name', 'staff_name', 'pdf', 'patient_id', 'master_key'), 'order' => array('TestReport.id DESC'), 'limit' => $limit));
					if ($data_arrays['page'] != 0) {
						if ((count($results) > $data_arrays['page'] * 10)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
					if (!empty($results)) {
						$data = array();
						if ($data_arrays['page'] == 0) {
							$end = count($results) - 1;
						}
						$i = 0;
						foreach ($results as $key => $result) {
							if ($key >= $start && $key <= $end) {
								$data[$i] = $result['TestReport'];
								$data[$i]['patient_name'] = ($result['TestReport']['patient_name'] != null) ? ($result['TestReport']['patient_name']) : '';
								if (!empty($result['TestReport']['pdf'])) {
									$data[$i]['pdf'] = WWW_BASE . 'uploads/pdf/' . $result['TestReport']['pdf'];
								}
								$i++;
							}
						}
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['result'] = 1;
							$response['more_data'] = $more_data;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $more_data;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*API for getting test report by office search(staff_id & patient namegiven)*/
	public function searchGetTestReport()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				//$data_arrays = $_POST;
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				if (empty($data_arrays)) {
					$data_arrays = $_POST;
				}
				//pr($data_arrays);die;
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 10 - 1;
					} else {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = ($data_arrays['page'] - 1) * 10;
						$end = $data_arrays['page'] * 10 - 1;
					}
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id'], 'User.user_type' => 'Staffuser'), 'fields' => array('User.office_id')));
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id'], 'User.user_type' => 'Staffuser'), 'fields' => array('User.id')));
					$this->TestReport->virtualFields['test_name'] = 'IF(EXISTS(select name from mmd_tests as tests where TestReport.test_id = tests.id),(select name from mmd_tests as tests where TestReport.test_id = tests.id),"")';
					$this->TestReport->virtualFields['patient_name'] = "select replace(CONCAT(Patient.first_name,' ',Patient.middle_name,' ',Patient.last_name),'  ',' ') from mmd_patients as Patient where TestReport.patient_id = Patient.id";
					$this->TestReport->virtualFields['staff_name'] = 'select concat(first_name," ",middle_name," ",last_name) as name from mmd_users as users where TestReport.staff_id = users.id';
					$condition['TestReport.staff_id'] = $all_staff_ids;
					if (isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])) {
						$condition["TestReport.patient_name LIKE"] = '%' . $data_arrays['patient_name'] . "%";
					}
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['TestReport.patient_id'] = $data_arrays['patient_id'];
					}
					if (isset($data_arrays['master_key'])) {
						$condition['TestReport.master_key'] = $data_arrays['master_key'];
					}
					$results = $this->TestReport->find('all', array('conditions' => $condition, 'fields' => array('id', 'test_name', 'created', 'result', "patient_name", 'staff_name', 'pdf', 'patient_id', 'master_key'),
						'order' => array('TestReport.id DESC'),
						'limit' => $limit));
					if ($data_arrays['page'] != 0) {
						if ((count($results) > $data_arrays['page'] * 10)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
					if (!empty($results)) {
						$data = array();
						if ($data_arrays['page'] == 0) {
							$end = count($results) - 1;
						}
						$i = 0;
						foreach ($results as $key => $result) {
							if ($key >= $start && $key <= $end) {
								$data[$i] = $result['TestReport'];
								$data[$i]['patient_name'] = ($result['TestReport']['patient_name'] != null) ? ($result['TestReport']['patient_name']) : '';
								if (!empty($result['TestReport']['pdf'])) {
									$data[$i]['pdf'] = WWW_BASE . 'uploads/pdf/' . $result['TestReport']['pdf'];
								}
								$i++;
							}
						}
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['result'] = 1;
							$response['more_data'] = $more_data;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $more_data;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/* Updated version of save_test_report_V4 API */
	public function save_test_report_V4()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if ((isset($data_arrays)) && (!empty($data_arrays))) {
					$i = 0;
					//foreach($data_arrays['data'] as $report){
					$data['TestReport'][$i]['test_id'] = @$data_arrays['test_id'];
					$data['TestReport'][$i]['staff_id'] = @$data_arrays['staff_id'];
					$data['TestReport'][$i]['patient_id'] = @$data_arrays['patient_id'];
					$data['TestReport'][$i]['result'] = @$data_arrays['result'];
					//pr($data_arrays); die;
					if (isset($_FILES['pdf']['name']) && (!empty($_FILES['pdf']['name']))) {
						if (!is_dir('uploads/pdf')) {
							mkdir('uploads/pdf', 0777, true);
						}
						//$data_to_save = array();
						if (isset($_FILES['pdf']['name']) && (!empty($_FILES['pdf']['name']))) {
							$tmp_name = $_FILES['pdf']['tmp_name'];
							$name = $_FILES['pdf']['name'];
							move_uploaded_file($tmp_name, "uploads/pdf/$name");
						}
						$data['TestReport'][$i]['pdf'] = $name;
						//$folder_name="uploads/pdf";
						//$data['TestReport'][$i]['pdf']=$this->base64_to_pdf($_FILES['pdf']['tmp_name'],$folder_name);
					}
					$i++;
					//}
					//pr($data); die;
					if ($this->TestReport->saveAll($data['TestReport'])) {
						$response['message'] = 'Test reports saved successfully.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'Some error occured in saving report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Please send some reports.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*API for fetching test device */
	public function get_test_device()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$input_data['office_id'] = @$this->request->data['office_id'];
				if (isset($input_data['office_id']) && (!empty($input_data['office_id']))) {
					$result = $this->TestDevice->find('all', array('conditions' => array('TestDevice.office_id' => $input_data['office_id'], 'TestDevice.status' => 1), 'order' => array('TestDevice.id DESC')));
					if (!empty($result)) {
						$data = array();
						foreach ($result as $key => $result) {
							$data[] = $result['TestDevice'];
						}
						if (!empty($data)) {
							$response['message'] = 'All test device list.';
							$response['result'] = 1;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test device found.';
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Please send office id.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*API for fetching practice name*/
	public function get_practices()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validateGetRequest()) {
				$this->autoRender = false;
				$response = array();
				$result = $this->Practice->find('all');
				if (!empty($result)) {
					$data = array();
					foreach ($result as $key => $result) {
						$data[] = $result['Practice'];
					}
					if (!empty($data)) {
						$response['message'] = 'All practice list.';
						$response['result'] = 1;
						$response['data'] = $data;
					} else {
						$response['message'] = 'No practice found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'NO practice found.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*API for adding device by staff*/
	public function add_test_device()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				if (empty($data_arrays)) {
					$data_arrays = $_POST;
				}
				if (((!empty($data_arrays['name']))) && (!empty($data_arrays['ip_address'])) &&
					(!empty($data_arrays['status'])) && (!empty($data_arrays['office_id']))) {
					//$ipaddress_okay=preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/',trim($data_arrays['ip_address']));
					$check_name_ipadd = $this->TestDevice->find('first', array('conditions' => array('TestDevice.name' => $data_arrays['name'])));
					/* if(($ipaddress_okay)){ */
					if (empty($check_name_ipadd)) {
						$data['TestDevice']['name'] = @$data_arrays['name'];
						$data['TestDevice']['ip_address'] = @$data_arrays['ip_address'];
						$data['TestDevice']['status'] = @$data_arrays['status'];
						$data['TestDevice']['office_id'] = @$data_arrays['office_id'];
						$result = $this->TestDevice->save($data, false);
						if ($result) {
							$response['message'] = 'Test Device has been saved successfully.';
							$response['result'] = 1;
						} else {
							$response['message'] = 'Some error occured in saving test device.';
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'Device name already exits.';
						$response['result'] = 0;
					}
					/* }else{
						$response['message']='Ip address is invalid.';
						$response['result']=0;
					} */
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*API for Editing device by staff*/
	public function Edit_test_device()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				//$data_arrays = $_POST;
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				if (empty($data_arrays)) {
					$data_arrays = $_POST;
				}
				if (((isset($data_arrays['device_id']))) && (!empty($data_arrays['device_id']))) {
					//$ipaddress_okay=preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/',trim($data_arrays['ip_address']));
					$check_device = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $data_arrays['device_id'])));
					/* if(($ipaddress_okay)){ */
					if (!empty($check_device)) {
						$condition = array();
						$data['TestDevice']['id'] = @$data_arrays['device_id'];
						if (isset($data_arrays['name']) && (!empty($data_arrays['name']))) {
							$data['TestDevice']['name'] = @$data_arrays['name'];
							$condition[] = array('TestDevice.name' => $data_arrays['name']);
						}
						if (isset($data_arrays['ip_address']) && (!empty($data_arrays['ip_address']))) {
							$data['TestDevice']['ip_address'] = @$data_arrays['ip_address'];
							//$condition['OR']=array('TestDevice.ip_address'=>$data_arrays['ip_address']);
						}
						if (!empty($condition)) {
							$condition['AND'] = array('TestDevice.id !=' => $data_arrays['device_id']);
							$check_device = $this->TestDevice->find('first', array('conditions' => $condition));
							if (!empty($check_device)) {
								$response['message'] = 'Device name already exist.Please try again.';
								$response['result'] = 0;
								echo json_encode($response);
								die;
							}
						}
						if (isset($data_arrays['status']) && (!empty($data_arrays['status']))) {
							$data['TestDevice']['status'] = @$data_arrays['status'];
						}
						if (isset($data_arrays['office_id']) && (!empty($data_arrays['office_id']))) {
							$data['TestDevice']['office_id'] = @$data_arrays['office_id'];
						}
						$result = $this->TestDevice->save($data);
						if ($result) {
							$response['message'] = 'Test Device has been Updated successfully.';
							$response['result'] = 1;
						} else {
							$response['message'] = 'Some error occured in updating test device.';
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'Invalid device.';
						$response['result'] = 0;
					}
					/* }else{
						$response['message']='Ip address is invalid.';
						$response['result']=0;
					} */
				} else {
					$response['message'] = 'Please send device id.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/* Uploads Files For test report */
	public function save_test_report_files_v3()
	{
		$this->layout = false;
		$response = array();
		$id = @$this->request->data['test_report_id'];
		if (!empty($id)) {
			if ((!empty($_FILES['file']['name']))) {
				if (!is_dir('uploads/files')) {
					mkdir('uploads/files', 0777, true);
				}
				//$data_to_save = array();
				if (isset($_FILES['file']['name']) && (!empty($_FILES['file']['name']))) {
					$tmp_name = $_FILES['file']['tmp_name'];
					$name = $_FILES['file']['name'];
					move_uploaded_file($tmp_name, "uploads/files/$name");
				}
				if (!file_exists($tmp_name) || !is_uploaded_file($tmp_name)) {
					$file_data = array();
					$file_data['test_report_id'] = $id;
					$file_data['file_path'] = "uploads/files/$name";
					$insert_res = $this->Files->save($file_data);
					if ($insert_res) {
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
				$response['message'] = 'File can\'t be empty .';
				$response['result'] = 0;
			}
		} else {
			$response['message'] = 'Test report id can\'t be empty.';
			$response['result'] = 0;
		}
		echo json_encode($response);
		die;
	}
	/*API for getting test report and files by office(staff_id given)*/
	public function get_test_report_v3()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 10 - 1;
					} else {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = ($data_arrays['page'] - 1) * 10;
						$end = $data_arrays['page'] * 10 - 1;
					}
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id'], 'User.user_type' => 'Staffuser'), 'fields' => array('User.office_id')));
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id'], 'User.user_type' => 'Staffuser'), 'fields' => array('User.id')));
					$this->TestReport->virtualFields['test_name'] = 'IF(EXISTS(select name from mmd_tests as tests where TestReport.test_id = tests.id),(select name from mmd_tests as tests where TestReport.test_id = tests.id),"")';
					//$this->TestReport->virtualFields['test_name'] = 'select name from mmd_tests as tests where TestReport.test_id = tests.id';
					//$this->TestReport->virtualFields['practice_name'] = 'select name from mmd_practices as practices where TestReport.practice_id = practices.id';
					$this->TestReport->virtualFields['patient_name'] = 'select concat(first_name," ",middle_name," ",last_name) from mmd_patients as patients where TestReport.patient_id = patients.id';
					$this->TestReport->virtualFields['staff_name'] = 'select concat(first_name," ",middle_name," ",last_name) as name from mmd_users as users where TestReport.staff_id = users.id';
					/* if(isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])){
						$condition =  array(
								'TestReport.staff_id'=>$all_staff_ids,
								'TestReport.patient_id'=>$data_arrays['patient_id']
							  );
					} else{
						$condition =  array(
								'TestReport.staff_id'=>$all_staff_ids
							  );
					} */
					$condition['TestReport.staff_id'] = $all_staff_ids;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['TestReport.patient_id'] = $data_arrays['patient_id'];
					}
					if (isset($data_arrays['master_key'])) {
						$condition['TestReport.master_key'] = $data_arrays['master_key'];
					}
					$results = $this->TestReport->find('all', array('conditions' => $condition, 'fields' => array('id', 'test_name', 'test_id', 'result', 'created', 'patient_name', 'staff_name', 'pdf', 'patient_id', 'master_key'), 'order' => array('TestReport.id DESC'), 'limit' => $limit));
					if ($data_arrays['page'] != 0) {
						if ((count($results) > $data_arrays['page'] * 10)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
					if (!empty($results)) {
						$data = array();
						if ($data_arrays['page'] == 0) {
							$end = count($results) - 1;
						}
						$i = 0;
						foreach ($results as $key => $result) {
							if ($key >= $start && $key <= $end) {
								$data[$i] = $result['TestReport'];
								$data[$i]['testType'] = $result['TestReport']['test_id'];
								unset($data[$i]['test_id']);
								$data[$i]['patient_name'] = ($result['TestReport']['patient_name'] != null) ? ($result['TestReport']['patient_name']) : '';
								if (!empty($result['TestReport']['pdf'])) {
									$data[$i]['pdf'] = WWW_BASE . 'uploads/pdf/' . $result['TestReport']['pdf'];
								}
								$files = $this->Files->find('all', array('conditions' => array('Files.test_report_id' => $result['TestReport']['id']), 'fields' => array('Files.id', 'Files.file_path')));
								if (count($files)) {
									$file_arr = array();
									foreach ($files as $fi) {
										$fi['Files']['file_path'] = WWW_BASE . $fi['Files']['file_path'];
										$file_arr[] = $fi['Files'];
									}
									$data[$i]['files'] = $file_arr;
								} else {
									$data[$i]['files'] = array();
								}
								$i++;
							}
						}
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['result'] = 1;
							$response['more_data'] = $more_data;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $more_data;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}
	/*API for saving test report and file BY prince kumar dwivedi*/
	public function save_test_report_v3()
	{
		$this->layout = false;
		$response = array();
		if ($this->check_key()) {
			if ($this->validatePostRequest()) {
				$data_arrays = $_POST;
				//$data_arrays =$this->request->data;
				if (((isset($data_arrays['testType'])) && (!empty($data_arrays['testType']))) &&
					((isset($data_arrays['staffId'])) && (!empty($data_arrays['staffId']))) &&
					((isset($data_arrays['patientId'])) && (!empty($data_arrays['patientId'])))) {
					$data['TestReport']['test_id'] = @$data_arrays['testType'];
					$data['TestReport']['staff_id'] = @$data_arrays['staffId'];
					$data['TestReport']['patient_id'] = @$data_arrays['patientId'];
					$result = $this->TestReport->save($data);
					if ($result) {
						$response['message'] = 'Test report saved successfully.';
						$response['test_report_id'] = $result['TestReport']['id'];
						$response['result'] = 1;
					} else {
						$response['message'] = 'Some error occured in saving report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some required fields is empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				die;
			}
		}
		exit();
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
	/* Uploads Files For patient */
	public function patient_profile_pic()
	{
		$this->layout = false;
		$response = array();
		$id = @$this->request->data['patient_id'];
		if (empty($id)) {
			$response['message'] = ' Id can\'t be empty.';
			$response['result'] = 0;
			echo json_encode($response);
			die;
		}
		if (empty($_FILES['file']['name'])) {
			$response['message'] = ' file can\'t be empty.';
			$response['result'] = 0;
			echo json_encode($response);
			die;
		}
		if (!empty($id)) {
			/* 	if( (!empty($_FILES['file']['name'])) ) {
				if(!is_dir('uploads/files')){
				  mkdir('uploads/files',0777,true);
				} */
			//$data_to_save = array();
			if (isset($_FILES['file']['name']) && (!empty($_FILES['file']['name']))) {
				$tmp_name = $_FILES['file']['tmp_name'];
				$name = $id . '_' . $_FILES['file']['name'];
				$x = move_uploaded_file($tmp_name, "uploads/profile/patient/$name");
			}
			if (!file_exists($tmp_name) || !is_uploaded_file($tmp_name)) {
				//$file_data = array();
				//$file_data['patient_id'] = $id;
				$ppic_path = "uploads/profile/patient/$name";
				$this->Patients->id = $id;
				$this->Patients->set(array('p_profilepic' => $ppic_path));
				//$insert_res = $this->Patients->save($file_data);
				$insert_res = $this->Patients->save();
				if ($insert_res) {
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
			$response['message'] = 'File can\'t be empty .';
			$response['result'] = 0;
		}
		echo json_encode($response);
		die;
	}
	/*API for check/uncheck master key in test report*/
	public function checkTestreportByMaster()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if ((isset($data_arrays['test_report_id'])) && (!empty($data_arrays['test_report_id'])) && (isset($data_arrays['master_key']))) {
					$data['TestReport']['id'] = @$data_arrays['test_report_id'];
					$data['TestReport']['master_key'] = @$data_arrays['master_key'];
					$result = $this->TestReport->save($data);
					if ($result) {
						$response['message'] = 'Test Report has been Updated successfully.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'Some error occured in updating test report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Please send test report id.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*     * ****************************VisPointData_Headset ******************************** */
	/*
      API Name: http://www.vibesync.com/apisnew/VisPointData_Headset
      Request Parameter: office_id
      Date: 25 March, 2019
     */
	public function VisPointData_Headset()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'VisPointData_Headset';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				$data_arrays['test_report_id'] = 1;
				//pr($data_arrays);die;
				// CakeLog::write('info', "pointData: ".print_r($data_arrays));
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$foldername = "pointData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername);
					}
					//pr($data_arrays);die();
					$data['Pointdata']['test_id'] = isset($data_arrays['test_id']) ? $data_arrays['test_id'] : '';
					$data['Pointdata']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
					$data['Pointdata']['numpoints'] = isset($data_arrays['numpoints']) ? $data_arrays['numpoints'] : '0';
					$data['Pointdata']['color'] = isset($data_arrays['color']) ? $data_arrays['color'] : '';
					$data['Pointdata']['backgroundcolor'] = isset($data_arrays['backgroundcolor']) ? $data_arrays['backgroundcolor'] : '';
					$data['Pointdata']['stmsize'] = isset($data_arrays['stmSize']) ? $data_arrays['stmSize'] : 0;
					$data['Pointdata']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['Pointdata']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['Pointdata']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['Pointdata']['master_key'] = isset($data_arrays['master_key']) ? $data_arrays['master_key'] : 0;
					if ($data['Pointdata']['master_key'] == "") {
						$data['Pointdata']['master_key'] = 0;
					}
					$data['Pointdata']['eye_select'] = isset($data_arrays['eye_select']) ? $data_arrays['eye_select'] : 0;
					if ($data['Pointdata']['eye_select'] == 'OU') {
						$data['Pointdata']['eye_select'] = 2;
					}
					$data['Pointdata']['test_type_id'] = isset($data_arrays['test_type_id']) ? $data_arrays['test_type_id'] : '';
					$data['Pointdata']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : '';
					$data['Pointdata']['mean_dev'] = @$data_arrays['mean_dev'];
					$data['Pointdata']['pattern_std'] = @$data_arrays['pattern_std'];
					$data['Pointdata']['mean_sen'] = @$data_arrays['mean_sen'];
					$data['Pointdata']['mean_def'] = @$data_arrays['mean_def'];
					$data['Pointdata']['pattern_std_hfa'] = @$data_arrays['pattern_std_hfa'];
					$data['Pointdata']['loss_var'] = @$data_arrays['loss_var'];
					$data['Pointdata']['mean_std'] = @$data_arrays['mean_std'];
					$data['Pointdata']['psd_hfa_2'] = @$data_arrays['psd_hfa_2'];
					$data['Pointdata']['psd_hfa'] = @$data_arrays['psd_hfa'];
					$data['Pointdata']['vission_loss'] = @$data_arrays['vission_loss'];
					$data['Pointdata']['false_p'] = @$data_arrays['false_p'];
					$data['Pointdata']['false_n'] = @$data_arrays['false_n'];
					$data['Pointdata']['false_f'] = @$data_arrays['false_f'];
					$data['Pointdata']['ght'] = @$data_arrays['ght'];
					$data['Pointdata']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['Pointdata']['threshold'] = @$data_arrays['threshold'];
					$data['Pointdata']['strategy'] = @$data_arrays['strategy'];
					$data['Pointdata']['test_color_fg'] = isset($data_arrays['test_color_fg']) ? $data_arrays['test_color_fg'] : 0;
					$data['Pointdata']['test_color_bg'] = isset($data_arrays['test_color_bg']) ? $data_arrays['test_color_bg'] : 0;
					$data['Pointdata']['latitude'] = @$data_arrays['latitude'];
					$data['Pointdata']['longitude'] = @$data_arrays['longitude'];
					$data['Pointdata']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['Pointdata']['version'] = @$data_arrays['version'];
					$data['Pointdata']['diagnosys'] = @$data_arrays['diagnosys'];
					$data['Pointdata']['stereopsis'] = @$data_arrays['Stereopsis'];
					// $count_baseline = $this->Pointdata->find('count', array(
					//     'conditions' => array(
					//         'test_name' => $data['Pointdata']['test_name'],
					//         'eye_select' => $data['Pointdata']['eye_select'], 'patient_id' => $data['Pointdata']['patient_id'], 'Pointdata.baseline' => '1'
					//     )
					// ));
					// if ($count_baseline < 2) {
					//     $data['Pointdata']['baseline'] = 1;
					// }
					//pr($count_baseline);die;
					//pr($data); die;
					$data['Pointdata']['baseline'] = (isset($data_arrays['baseline']) && !empty($data_arrays['baseline'])) ? $data_arrays['baseline'] : 0;
					$result = $this->Pointdata->save($data);
					$lastId = $this->Pointdata->id;
					if ($result) {
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						if ($this->ReportRequestBackup->save($result2)) {
						}
						if (!empty($data_arrays['file'])) {
							$response['pdf'] = WWW_BASE . 'pointData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						CakeLog::write('info', "Test Device Message file upload : VF|VF_FILE_UPLOADED|" . $lastId);
						$data_device_message['DeviceMessage']['office_id'] = $data_arrays['office_id'];
						$data_device_message['DeviceMessage']['device_id'] = $data_arrays['device_id'];
						$data_device_message['DeviceMessage']['message'] = 'VF|VF_FILE_UPLOADED|' . $lastId;
						$data_device_message['DeviceMessage']['craeted_at'] = date("Y-m-d H:i:s");
						$data_device_message['DeviceMessage']['updated_at'] = date("Y-m-d H:i:s");
						$this->DeviceMessage->save($data_device_message);
						foreach ($data_arrays['cspointdata_od'] as $pdatas) {
							$pdata['CsPointdata']['point_data_id'] = @$lastId;
							$pdata['CsPointdata']['eye_select'] = 1;
							$pdata['CsPointdata']['freq'] = isset($pdatas['freq']) ? $pdatas['freq'] : '';
							$pdata['CsPointdata']['amp'] = isset($pdatas['Amp']) ? $pdatas['Amp'] : '';
							$pdata['CsPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
							$this->CsPointdata->create();
							$result_p = $this->CsPointdata->save($pdata);
						}
						foreach ($data_arrays['cspointdata_os'] as $pdatas) {
							$pdata['CsPointdata']['point_data_id'] = @$lastId;
							$pdata['CsPointdata']['eye_select'] = 0;
							$pdata['CsPointdata']['freq'] = isset($pdatas['freq']) ? $pdatas['freq'] : '';
							$pdata['CsPointdata']['amp'] = isset($pdatas['Amp']) ? $pdatas['Amp'] : '';
							$pdata['CsPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
							$this->CsPointdata->create();
							$result_p = $this->CsPointdata->save($pdata);
						}
						// foreach ($data_arrays['vfpointdata'] as $pdatas) {
						//     $pdata['VfPointdata']['report_id'] = @$data_arrays['test_report_id'];
						//     $pdata['VfPointdata']['point_data_id'] = @$lastId;
						//     $pdata['VfPointdata']['x'] = isset($pdatas['x']) ? $pdatas['x'] : '';
						//     $pdata['VfPointdata']['y'] = isset($pdatas['y']) ? $pdatas['y'] : '';
						//     $pdata['VfPointdata']['intensity'] = isset($pdatas['intensity']) ? $pdatas['intensity'] : '';
						//     ;
						//     $pdata['VfPointdata']['size'] = isset($pdatas['size']) ? $pdatas['size'] : '';
						//     $pdata['VfPointdata']['zPD'] = isset($pdatas['zPD']) ? $pdatas['zPD'] : '';
						//     $pdata['VfPointdata']['STD'] = isset($pdatas['STD']) ? $pdatas['STD'] : '';
						//     $pdata['VfPointdata']['index'] = isset($pdatas['index']) ? $pdatas['index'] : '';
						//     $pdata['VfPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
						//     $this->VfPointdata->create();
						//     $result_p = $this->VfPointdata->save($pdata);
						// }
						$response['message'] = 'Success.';
						$response['result'] = 1;
						//update credits------
						$this->loadModel('User');
						$this->User->id = $data['Pointdata']['staff_id'];
						$credits = $this->User->field('credits');
						$new_credit = $credits - 1;
						$this->User->updateAll(array('User.credits' => $new_credit), array('User.id' => $data['Pointdata']['staff_id']));
					} else {
						$response['message'] = 'Some error occured in updating report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*
      API Name: http://www.vibesync.com/apisnew/VisPointData_Controller
      Request Parameter: office_id
      Date: 25 March, 2019
     */
	public function VisPointData_Controller()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'VisPointData_Controller';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				$data_arrays['test_report_id'] = 1;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "pointData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['Pointdata']['test_id'] = isset($data_arrays['test_id']) ? $data_arrays['test_id'] : '';
					$data['Pointdata']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'S';
					$data['Pointdata']['numpoints'] = isset($data_arrays['numpoints']) ? $data_arrays['numpoints'] : '0';
					$data['Pointdata']['color'] = isset($data_arrays['color']) ? $data_arrays['color'] : '';
					$data['Pointdata']['backgroundcolor'] = isset($data_arrays['backgroundcolor']) ? $data_arrays['backgroundcolor'] : '';
					$data['Pointdata']['stmsize'] = isset($data_arrays['stmSize']) ? $data_arrays['stmSize'] : 0;
					$data['Pointdata']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['Pointdata']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['Pointdata']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['Pointdata']['master_key'] = isset($data_arrays['master_key']) ? $data_arrays['master_key'] : 0;
					if ($data['Pointdata']['master_key'] == "") {
						$data['Pointdata']['master_key'] = 0;
					}
					$data['Pointdata']['eye_select'] = isset($data_arrays['eye_select']) ? $data_arrays['eye_select'] : 1;
					if ($data['Pointdata']['eye_select'] == 'OU') {
						$data['Pointdata']['eye_select'] = 2;
					}
					$data['Pointdata']['test_type_id'] = isset($data_arrays['test_type_id']) ? $data_arrays['test_type_id'] : '';
					$data['Pointdata']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : '';
					$data['Pointdata']['mean_dev'] = @$data_arrays['mean_dev'];
					$data['Pointdata']['pattern_std'] = @$data_arrays['pattern_std'];
					$data['Pointdata']['mean_sen'] = @$data_arrays['mean_sen'];
					$data['Pointdata']['mean_def'] = @$data_arrays['mean_def'];
					$data['Pointdata']['pattern_std_hfa'] = @$data_arrays['pattern_std_hfa'];
					$data['Pointdata']['loss_var'] = @$data_arrays['loss_var'];
					$data['Pointdata']['mean_std'] = @$data_arrays['mean_std'];
					$data['Pointdata']['psd_hfa_2'] = @$data_arrays['psd_hfa_2'];
					$data['Pointdata']['psd_hfa'] = @$data_arrays['psd_hfa'];
					$data['Pointdata']['vission_loss'] = @$data_arrays['vission_loss'];
					$data['Pointdata']['false_p'] = @$data_arrays['false_p'];
					$data['Pointdata']['false_n'] = @$data_arrays['false_n'];
					$data['Pointdata']['false_f'] = @$data_arrays['false_f'];
					$data['Pointdata']['ght'] = @$data_arrays['ght'];
					$data['Pointdata']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['Pointdata']['threshold'] = @$data_arrays['threshold'];
					$data['Pointdata']['strategy'] = @$data_arrays['strategy'];
					$data['Pointdata']['test_color_fg'] = isset($data_arrays['test_color_fg']) ? $data_arrays['test_color_fg'] : 0;
					$data['Pointdata']['test_color_bg'] = isset($data_arrays['test_color_bg']) ? $data_arrays['test_color_bg'] : 0;
					$data['Pointdata']['latitude'] = @$data_arrays['latitude'];
					$data['Pointdata']['longitude'] = @$data_arrays['longitude'];
					$data['Pointdata']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['Pointdata']['version'] = @$data_arrays['version'];
					$data['Pointdata']['diagnosys'] = @$data_arrays['diagnosys'];
					$data['Pointdata']['stereopsis'] = @$data_arrays['Stereopsis'];
					// $count_baseline = $this->Pointdata->find('count', array(
					//     'conditions' => array(
					//         'test_name' => $data['Pointdata']['test_name'],
					//         'eye_select' => $data['Pointdata']['eye_select'], 'patient_id' => $data['Pointdata']['patient_id'], 'Pointdata.baseline' => '1'
					//     )
					// ));
					// if ($count_baseline < 2) {
					//     $data['Pointdata']['baseline'] = 1;
					// }
					$data['Pointdata']['baseline'] = (isset($data_arrays['baseline']) && !empty($data_arrays['baseline'])) ? $data_arrays['baseline'] : 0;
					$result = $this->Pointdata->save($data);
					$lastId = $this->Pointdata->id;
					$lastFile = $this->Pointdata->file;
					if ($result) {
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						if ($this->ReportRequestBackup->save($result2)) {
						}
						if (!empty($data_arrays['file'])) {
							$response['pdf'] = WWW_BASE . 'pointData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						CakeLog::write('info', "Test Device Message file upload : VF|VF_FILE_UPLOADED|" . $lastId);
						$data_device_message['DeviceMessage']['office_id'] = $data_arrays['office_id'];
						$data_device_message['DeviceMessage']['device_id'] = $data_arrays['device_id'];
						$data_device_message['DeviceMessage']['message'] = 'VF|VF_FILE_UPLOADED|' . $lastId;
						$data_device_message['DeviceMessage']['craeted_at'] = date("Y-m-d H:i:s");
						$data_device_message['DeviceMessage']['updated_at'] = date("Y-m-d H:i:s");
						$this->DeviceMessage->save($data_device_message);
						foreach ($data_arrays['cspointdata_od'] as $pdatas) {
							$pdata['CsPointdata']['point_data_id'] = @$lastId;
							$pdata['CsPointdata']['eye_select'] = 1;
							$pdata['CsPointdata']['freq'] = isset($pdatas['freq']) ? $pdatas['freq'] : '';
							$pdata['CsPointdata']['amp'] = isset($pdatas['Amp']) ? $pdatas['Amp'] : '';
							$pdata['CsPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
							$this->CsPointdata->create();
							$result_p = $this->CsPointdata->save($pdata);
						}
						foreach ($data_arrays['cspointdata_os'] as $pdatas) {
							$pdata['CsPointdata']['point_data_id'] = @$lastId;
							$pdata['CsPointdata']['eye_select'] = 0;
							$pdata['CsPointdata']['freq'] = isset($pdatas['freq']) ? $pdatas['freq'] : '';
							$pdata['CsPointdata']['amp'] = isset($pdatas['Amp']) ? $pdatas['Amp'] : '';
							$pdata['CsPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
							$this->CsPointdata->create();
							$result_p = $this->CsPointdata->save($pdata);
						}
						$pdata = "";
						// foreach ($data_arrays['vfpointdata'] as $pdatas) {
						//     $pdata['VfPointdata']['report_id'] = @$data_arrays['test_report_id'];
						//     $pdata['VfPointdata']['point_data_id'] = @$lastId;
						//     $pdata['VfPointdata']['x'] = isset($pdatas['x']) ? $pdatas['x'] : '';
						//     $pdata['VfPointdata']['y'] = isset($pdatas['y']) ? $pdatas['y'] : '';
						//     $pdata['VfPointdata']['intensity'] = isset($pdatas['intensity']) ? $pdatas['intensity'] : '';
						//     $pdata['VfPointdata']['size'] = isset($pdatas['size']) ? $pdatas['size'] : '';
						//     $pdata['VfPointdata']['zPD'] = isset($pdatas['zPD']) ? $pdatas['zPD'] : '';
						//     $pdata['VfPointdata']['STD'] = isset($pdatas['STD']) ? $pdatas['STD'] : '';
						//     $pdata['VfPointdata']['index'] = isset($pdatas['index']) ? $pdatas['index'] : '';
						//     $pdata['VfPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
						//     $this->VfPointdata->create();
						//     $result_p = $this->VfPointdata->save($pdata);
						// }
						$response['message'] = 'Success.';
						$response['result'] = 1;
						CakeLog::write('info', "Test Device Message file upload : VF|VF_FILE_UPLOADED|" . $lastId);
						$data_device_message['DeviceMessage']['office_id'] = $data_arrays['office_id'];
						$data_device_message['DeviceMessage']['device_id'] = $data_arrays['device_id'];
						$data_device_message['DeviceMessage']['message'] = 'VF|VF_FILE_UPLOADED|' . $lastId;
						$data_device_message['DeviceMessage']['craeted_at'] = date("Y-m-d H:i:s");
						$data_device_message['DeviceMessage']['updated_at'] = date("Y-m-d H:i:s");
						$this->DeviceMessage->save($data_device_message);
						$this->loadModel('User');
						$this->User->id = $data['Pointdata']['staff_id'];
						$credits = $this->User->field('credits');
						$new_credit = $credits - 1;
						$this->User->updateAll(array('User.credits' => $new_credit), array('User.id' => $data['Pointdata']['staff_id']));
					} else {
						$response['message'] = 'Some error occured in updating report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*     * ****************************pointData ******************************** */
	/*Save  vs report create new Api by Madan 24-11-2022*/
	public function saveVSReport_v6()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'saveVSReport_v6';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				$data_arrays['test_report_id'] = 1;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "pointData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['Pointdata']['test_id'] = isset($data_arrays['test_id']) ? $data_arrays['test_id'] : '';
					$data['Pointdata']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'S';
					$data['Pointdata']['numpoints'] = isset($data_arrays['numpoints']) ? $data_arrays['numpoints'] : '0';
					$data['Pointdata']['color'] = isset($data_arrays['color']) ? $data_arrays['color'] : '';
					$data['Pointdata']['backgroundcolor'] = isset($data_arrays['backgroundcolor']) ? $data_arrays['backgroundcolor'] : '';
					$data['Pointdata']['stmsize'] = isset($data_arrays['stmSize']) ? $data_arrays['stmSize'] : 0;
					$data['Pointdata']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['Pointdata']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['Pointdata']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['Pointdata']['master_key'] = isset($data_arrays['master_key']) ? $data_arrays['master_key'] : 0;
					if ($data['Pointdata']['master_key'] == "") {
						$data['Pointdata']['master_key'] = 0;
					}
					$data['Pointdata']['eye_select'] = isset($data_arrays['eye_select']) ? $data_arrays['eye_select'] : 1;
					if ($data['Pointdata']['eye_select'] == 'OU') {
						$data['Pointdata']['eye_select'] = 2;
					}
					$data['Pointdata']['test_type_id'] = isset($data_arrays['test_type_id']) ? $data_arrays['test_type_id'] : '';
					$data['Pointdata']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : '';
					$data['Pointdata']['mean_dev'] = @$data_arrays['mean_dev'];
					$data['Pointdata']['pattern_std'] = @$data_arrays['pattern_std'];
					$data['Pointdata']['mean_sen'] = @$data_arrays['mean_sen'];
					$data['Pointdata']['mean_def'] = @$data_arrays['mean_def'];
					$data['Pointdata']['pattern_std_hfa'] = @$data_arrays['pattern_std_hfa'];
					$data['Pointdata']['loss_var'] = @$data_arrays['loss_var'];
					$data['Pointdata']['mean_std'] = @$data_arrays['mean_std'];
					$data['Pointdata']['psd_hfa_2'] = @$data_arrays['psd_hfa_2'];
					$data['Pointdata']['psd_hfa'] = @$data_arrays['psd_hfa'];
					$data['Pointdata']['vission_loss'] = @$data_arrays['vission_loss'];
					$data['Pointdata']['false_p'] = @$data_arrays['false_p'];
					$data['Pointdata']['false_n'] = @$data_arrays['false_n'];
					$data['Pointdata']['false_f'] = @$data_arrays['false_f'];
					$data['Pointdata']['ght'] = @$data_arrays['ght'];
					$data['Pointdata']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['Pointdata']['created_date_utc'] = $data_arrays['created_date_utc'];
					$data['Pointdata']['threshold'] = @$data_arrays['threshold'];
					$data['Pointdata']['strategy'] = @$data_arrays['strategy'];
					$data['Pointdata']['test_color_fg'] = isset($data_arrays['test_color_fg']) ? $data_arrays['test_color_fg'] : 0;
					$data['Pointdata']['test_color_bg'] = isset($data_arrays['test_color_bg']) ? $data_arrays['test_color_bg'] : 0;
					$data['Pointdata']['latitude'] = @$data_arrays['latitude'];
					$data['Pointdata']['longitude'] = @$data_arrays['longitude'];
					$data['Pointdata']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['Pointdata']['version'] = @$data_arrays['version'];
					$data['Pointdata']['diagnosys'] = @$data_arrays['diagnosys'];
					$data['Pointdata']['stereopsis'] = @$data_arrays['Stereopsis'];
					// $count_baseline = $this->Pointdata->find('count', array(
					//     'conditions' => array(
					//         'test_name' => $data['Pointdata']['test_name'],
					//         'eye_select' => $data['Pointdata']['eye_select'], 'patient_id' => $data['Pointdata']['patient_id'], 'Pointdata.baseline' => '1'
					//     )
					// ));
					// if ($count_baseline < 2) {
					//     $data['Pointdata']['baseline'] = 1;
					// }
					$data['Pointdata']['baseline'] = (isset($data_arrays['baseline']) && !empty($data_arrays['baseline'])) ? $data_arrays['baseline'] : 0;
					$result = $this->Pointdata->save($data);
					$lastId = $this->Pointdata->id;
					$lastFile = $this->Pointdata->file;
					if ($result) {
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						if ($this->ReportRequestBackup->save($result2)) {
						}
						if (!empty($data_arrays['file'])) {
							$response['pdf'] = WWW_BASE . 'pointData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						CakeLog::write('info', "Test Device Message file upload : VF|VF_FILE_UPLOADED|" . $lastId);
						$data_device_message['DeviceMessage']['office_id'] = $data_arrays['office_id'];
						$data_device_message['DeviceMessage']['device_id'] = $data_arrays['device_id'];
						$data_device_message['DeviceMessage']['message'] = 'VF|VF_FILE_UPLOADED|' . $lastId;
						$data_device_message['DeviceMessage']['craeted_at'] = date("Y-m-d H:i:s");
						$data_device_message['DeviceMessage']['updated_at'] = date("Y-m-d H:i:s");
						$this->DeviceMessage->save($data_device_message);
						foreach ($data_arrays['cspointdata_od'] as $pdatas) {
							$pdata['CsPointdata']['point_data_id'] = @$lastId;
							$pdata['CsPointdata']['eye_select'] = 1;
							$pdata['CsPointdata']['freq'] = isset($pdatas['freq']) ? $pdatas['freq'] : '';
							$pdata['CsPointdata']['amp'] = isset($pdatas['Amp']) ? $pdatas['Amp'] : '';
							$pdata['CsPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
							$this->CsPointdata->create();
							$result_p = $this->CsPointdata->save($pdata);
						}
						foreach ($data_arrays['cspointdata_os'] as $pdatas) {
							$pdata['CsPointdata']['point_data_id'] = @$lastId;
							$pdata['CsPointdata']['eye_select'] = 0;
							$pdata['CsPointdata']['freq'] = isset($pdatas['freq']) ? $pdatas['freq'] : '';
							$pdata['CsPointdata']['amp'] = isset($pdatas['Amp']) ? $pdatas['Amp'] : '';
							$pdata['CsPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
							$this->CsPointdata->create();
							$result_p = $this->CsPointdata->save($pdata);
						}
						$pdata = "";
						// foreach ($data_arrays['vfpointdata'] as $pdatas) {
						//     $pdata['VfPointdata']['report_id'] = @$data_arrays['test_report_id'];
						//     $pdata['VfPointdata']['point_data_id'] = @$lastId;
						//     $pdata['VfPointdata']['x'] = isset($pdatas['x']) ? $pdatas['x'] : '';
						//     $pdata['VfPointdata']['y'] = isset($pdatas['y']) ? $pdatas['y'] : '';
						//     $pdata['VfPointdata']['intensity'] = isset($pdatas['intensity']) ? $pdatas['intensity'] : '';
						//     $pdata['VfPointdata']['size'] = isset($pdatas['size']) ? $pdatas['size'] : '';
						//     $pdata['VfPointdata']['zPD'] = isset($pdatas['zPD']) ? $pdatas['zPD'] : '';
						//     $pdata['VfPointdata']['STD'] = isset($pdatas['STD']) ? $pdatas['STD'] : '';
						//     $pdata['VfPointdata']['index'] = isset($pdatas['index']) ? $pdatas['index'] : '';
						//     $pdata['VfPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
						//     $this->VfPointdata->create();
						//     $result_p = $this->VfPointdata->save($pdata);
						// }
						$response['message'] = 'Success.';
						$response['result'] = 1;
						CakeLog::write('info', "Test Device Message file upload : VF|VF_FILE_UPLOADED|" . $lastId);
						$data_device_message['DeviceMessage']['office_id'] = $data_arrays['office_id'];
						$data_device_message['DeviceMessage']['device_id'] = $data_arrays['device_id'];
						$data_device_message['DeviceMessage']['message'] = 'VF|VF_FILE_UPLOADED|' . $lastId;
						$data_device_message['DeviceMessage']['craeted_at'] = date("Y-m-d H:i:s");
						$data_device_message['DeviceMessage']['updated_at'] = date("Y-m-d H:i:s");
						$this->DeviceMessage->save($data_device_message);
						$this->loadModel('User');
						$this->User->id = $data['Pointdata']['staff_id'];
						$credits = $this->User->field('credits');
						$new_credit = $credits - 1;
						$this->User->updateAll(array('User.credits' => $new_credit), array('User.id' => $data['Pointdata']['staff_id']));
					} else {
						$response['message'] = 'Some error occured in updating report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*Save  vs report create new Api by Madan 24-11-2022*/

	/*
      API Name: http://www.vibesync.com/apisnew/pointDataNew
      Request Parameter: office_id
      Date: 25 March, 2019
     */
	public function pointDataNew()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				CakeLog::write('info', "pointDataNew :" . json_encode($data_arrays));
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'pointDataNew';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata, array(
					'validate' => false,
					'callbacks' => false,
					'counterCache' => false,
				));
				$lastId_bpk = $this->ReportRequestBackup->id;
				$data_arrays['test_report_id'] = 1;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "pointData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['Pointdata']['test_id'] = isset($data_arrays['test_id']) ? $data_arrays['test_id'] : '';
					$data['Pointdata']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'S';
					$data['Pointdata']['numpoints'] = isset($data_arrays['numpoints']) ? $data_arrays['numpoints'] : '';
					$data['Pointdata']['color'] = isset($data_arrays['color']) ? $data_arrays['color'] : '';
					$data['Pointdata']['backgroundcolor'] = isset($data_arrays['backgroundcolor']) ? $data_arrays['backgroundcolor'] : '';
					$data['Pointdata']['stmsize'] = isset($data_arrays['stmSize']) ? $data_arrays['stmSize'] : '';
					$data['Pointdata']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['Pointdata']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['Pointdata']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['Pointdata']['master_key'] = isset($data_arrays['master_key']) ? $data_arrays['master_key'] : '';
					$data['Pointdata']['eye_select'] = isset($data_arrays['eye_select']) ? $data_arrays['eye_select'] : '';
					$data['Pointdata']['test_type_id'] = isset($data_arrays['test_type_id']) ? $data_arrays['test_type_id'] : '';
					$data['Pointdata']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : '';
					$data['Pointdata']['mean_dev'] = @$data_arrays['mean_dev'];
					$data['Pointdata']['pattern_std'] = @$data_arrays['pattern_std'];
					$data['Pointdata']['mean_sen'] = @$data_arrays['mean_sen'];
					$data['Pointdata']['mean_def'] = @$data_arrays['mean_def'];
					$data['Pointdata']['pattern_std_hfa'] = @$data_arrays['pattern_std_hfa'];
					$data['Pointdata']['loss_var'] = @$data_arrays['loss_var'];
					$data['Pointdata']['mean_std'] = @$data_arrays['mean_std'];
					$data['Pointdata']['psd_hfa_2'] = @$data_arrays['psd_hfa_2'];
					$data['Pointdata']['psd_hfa'] = @$data_arrays['psd_hfa'];
					$data['Pointdata']['vission_loss'] = @$data_arrays['vission_loss'];
					$data['Pointdata']['false_p'] = @$data_arrays['false_p'];
					$data['Pointdata']['false_n'] = @$data_arrays['false_n'];
					$data['Pointdata']['false_f'] = @$data_arrays['false_f'];
					$data['Pointdata']['ght'] = @$data_arrays['ght'];
					$data['Pointdata']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					// $tz=(!empty($data_arrays['time_zone'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['time_zone'])) : 'America/Los_Angeles';
					// $data['Pointdata']['created']=$this->setUtc($data['Pointdata']['created'],$tz);
					$data['Pointdata']['threshold'] = @$data_arrays['threshold'];
					$data['Pointdata']['strategy'] = @$data_arrays['strategy'];
					$data['Pointdata']['test_color_fg'] = $data_arrays['test_color_fg'];
					$data['Pointdata']['test_color_bg'] = $data_arrays['test_color_bg'];
					$data['Pointdata']['latitude'] = @$data_arrays['latitude'];
					$data['Pointdata']['longitude'] = @$data_arrays['longitude'];
					$data['Pointdata']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['Pointdata']['version'] = @$data_arrays['version'];
					$data['Pointdata']['diagnosys'] = @$data_arrays['diagnosys'];
					/*$count_baseline = $this->Pointdata->find('count', array(
                        'conditions' => array(
                            'test_name' => $data['Pointdata']['test_name'],
                            'eye_select' => $data['Pointdata']['eye_select'], 'patient_id' => $data['Pointdata']['patient_id'], 'Pointdata.baseline' => '1'
                        )
                    ));
                    if ($count_baseline < 2) {
                        $data['Pointdata']['baseline'] = 1;
                    }*/
					$data['Pointdata']['baseline'] = (isset($data_arrays['baseline']) && !empty($data_arrays['baseline'])) ? $data_arrays['baseline'] : 0;
					//pr($count_baseline);die;
					//pr($data); die;
					$result = $this->Pointdata->save($data);
					$lastId = $this->Pointdata->id;
					$lastFile = $this->Pointdata->file;
					if ($result) {
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						if (!empty($data_arrays['file'])) {
							$response['pdf'] = WWW_BASE . 'pointData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						$pdata = array();
						foreach ($data_arrays['vfpointdata'] as $indexNew => $pdatas) {
							$pdata[$indexNew]['report_id'] = @$data_arrays['test_report_id'];
							$pdata[$indexNew]['point_data_id'] = @$lastId;
							$pdata[$indexNew]['x'] = isset($pdatas['x']) ? $pdatas['x'] : '';
							$pdata[$indexNew]['y'] = isset($pdatas['y']) ? $pdatas['y'] : '';
							$pdata[$indexNew]['intensity'] = isset($pdatas['intensity']) ? $pdatas['intensity'] : '';;
							$pdata[$indexNew]['size'] = isset($pdatas['size']) ? $pdatas['size'] : '';
							$pdata[$indexNew]['zPD'] = isset($pdatas['zPD']) ? $pdatas['zPD'] : '';
							$pdata[$indexNew]['STD'] = isset($pdatas['STD']) ? (float)$pdatas['STD'] : '';
							$pdata[$indexNew]['index'] = isset($pdatas['index']) ? $pdatas['index'] : '';
							$pdata[$indexNew]['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
						}
						if (!empty($pdata)) {
							$this->VfPointdata->create();
							$result_p = $this->VfPointdata->saveMany($pdata, array(
								'validate' => false,
								'callbacks' => false,
								'counterCache' => false,
							));
						}
						$response['message'] = 'Success.';
						$response['result'] = 1;
						CakeLog::write('info', "Test Device Message file upload : VF|VF_FILE_UPLOADED|" . $lastId);
						$data_device_message['DeviceMessage']['office_id'] = $data_arrays['office_id'];
						$data_device_message['DeviceMessage']['device_id'] = $data_arrays['device_id'];
						$data_device_message['DeviceMessage']['message'] = 'VF|VF_FILE_UPLOADED|' . $lastId;
						$data_device_message['DeviceMessage']['craeted_at'] = date("Y-m-d H:i:s");
						$data_device_message['DeviceMessage']['updated_at'] = date("Y-m-d H:i:s");
						$this->DeviceMessage->save($data_device_message, array(
							'validate' => false,
							'callbacks' => false,
							'counterCache' => false,
						));
						$data_devic_message['DevicMessage']['office_id'] = $data_arrays['office_id'];
						$data_devic_message['DevicMessage']['device_id'] = $data_arrays['device_id'];
						$data_devic_message['DevicMessage']['message'] = 'VF|VF_FILE_UPLOADED|' . $lastId;
						$data_devic_message['DevicMessage']['craeted_at'] = date("Y-m-d H:i:s");
						$data_devic_message['DevicMessage']['updated_at'] = date("Y-m-d H:i:s");
						$this->DevicMessage->save($data_devic_message, array(
							'validate' => false,
							'callbacks' => false,
							'counterCache' => false,
						));
						//update credits------
						$this->loadModel('User');
						$result2['ReportRequestBackup']['status'] = 1;
						if ($this->ReportRequestBackup->save($result2)) {
						}
						/*$this->User->id = $data['Pointdata']['staff_id'];
                        $credits = $this->User->field('credits');
                        $new_credit = $credits - 1;
                        $this->User->updateAll(array('User.credits' => $new_credit), array('User.id' => $data['Pointdata']['staff_id']));*/
					} else {
						$response['message'] = 'Some error occured in updating report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	public function pointDataNew2()
	{
		echo "hello";
		die();
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'pointDataNew';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				$data_arrays['test_report_id'] = 1;
				//pr($data_arrays);die;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "pointData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['Pointdata']['test_id'] = isset($data_arrays['test_id']) ? $data_arrays['test_id'] : '';
					$data['Pointdata']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'S';
					$data['Pointdata']['numpoints'] = isset($data_arrays['numpoints']) ? $data_arrays['numpoints'] : '';
					$data['Pointdata']['color'] = isset($data_arrays['color']) ? $data_arrays['color'] : '';
					$data['Pointdata']['backgroundcolor'] = isset($data_arrays['backgroundcolor']) ? $data_arrays['backgroundcolor'] : '';
					$data['Pointdata']['stmsize'] = isset($data_arrays['stmSize']) ? $data_arrays['stmSize'] : '';
					$data['Pointdata']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['Pointdata']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['Pointdata']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['Pointdata']['master_key'] = isset($data_arrays['master_key']) ? $data_arrays['master_key'] : '';
					$data['Pointdata']['eye_select'] = isset($data_arrays['eye_select']) ? $data_arrays['eye_select'] : '';
					$data['Pointdata']['test_type_id'] = isset($data_arrays['test_type_id']) ? $data_arrays['test_type_id'] : '';
					$data['Pointdata']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : '';
					$data['Pointdata']['mean_dev'] = @$data_arrays['mean_dev'];
					$data['Pointdata']['pattern_std'] = @$data_arrays['pattern_std'];
					$data['Pointdata']['mean_sen'] = @$data_arrays['mean_sen'];
					$data['Pointdata']['mean_def'] = @$data_arrays['mean_def'];
					$data['Pointdata']['pattern_std_hfa'] = @$data_arrays['pattern_std_hfa'];
					$data['Pointdata']['loss_var'] = @$data_arrays['loss_var'];
					$data['Pointdata']['mean_std'] = @$data_arrays['mean_std'];
					$data['Pointdata']['psd_hfa_2'] = @$data_arrays['psd_hfa_2'];
					$data['Pointdata']['psd_hfa'] = @$data_arrays['psd_hfa'];
					$data['Pointdata']['vission_loss'] = @$data_arrays['vission_loss'];
					$data['Pointdata']['false_p'] = @$data_arrays['false_p'];
					$data['Pointdata']['false_n'] = @$data_arrays['false_n'];
					$data['Pointdata']['false_f'] = @$data_arrays['false_f'];
					$data['Pointdata']['ght'] = @$data_arrays['ght'];
					$data['Pointdata']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['Pointdata']['threshold'] = @$data_arrays['threshold'];
					$data['Pointdata']['strategy'] = @$data_arrays['strategy'];
					$data['Pointdata']['test_color_fg'] = $data_arrays['test_color_fg'];
					$data['Pointdata']['test_color_bg'] = $data_arrays['test_color_bg'];
					$data['Pointdata']['latitude'] = @$data_arrays['latitude'];
					$data['Pointdata']['longitude'] = @$data_arrays['longitude'];
					$data['Pointdata']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['Pointdata']['version'] = @$data_arrays['version'];
					$data['Pointdata']['diagnosys'] = @$data_arrays['diagnosys'];
					/*$count_baseline = $this->Pointdata->find('count', array(
                        'conditions' => array(
                            'test_name' => $data['Pointdata']['test_name'],
                            'eye_select' => $data['Pointdata']['eye_select'], 'patient_id' => $data['Pointdata']['patient_id'], 'Pointdata.baseline' => '1'
                        )
                    ));
                    if ($count_baseline < 2) {
                        $data['Pointdata']['baseline'] = 1;
                    }*/
					$data['Pointdata']['baseline'] = (isset($data_arrays['baseline']) && !empty($data_arrays['baseline'])) ? $data_arrays['baseline'] : 0;
					//pr($count_baseline);die;
					//pr($data); die;
					$result = $this->Pointdata->save($data);
					$lastId = $this->Pointdata->id;
					$lastFile = $this->Pointdata->file;
					if ($result) {
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						if ($this->ReportRequestBackup->save($result2)) {
						}
						if (!empty($data_arrays['file'])) {
							$response['pdf'] = WWW_BASE . 'pointData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						$pdata = array();
						foreach ($data_arrays['vfpointdata'] as $pdatas) {
							$pdata['VfPointdata']['report_id'] = @$data_arrays['test_report_id'];
							$pdata['VfPointdata']['point_data_id'] = @$lastId;
							$pdata['VfPointdata']['x'] = isset($pdatas['x']) ? $pdatas['x'] : '';
							$pdata['VfPointdata']['y'] = isset($pdatas['y']) ? $pdatas['y'] : '';
							$pdata['VfPointdata']['intensity'] = isset($pdatas['intensity']) ? $pdatas['intensity'] : '';;
							$pdata['VfPointdata']['size'] = isset($pdatas['size']) ? $pdatas['size'] : '';
							$pdata['VfPointdata']['zPD'] = isset($pdatas['zPD']) ? $pdatas['zPD'] : '';
							$pdata['VfPointdata']['STD'] = isset($pdatas['STD']) ? (float)$pdatas['STD'] : '';
							$pdata['VfPointdata']['index'] = isset($pdatas['index']) ? $pdatas['index'] : '';
							$pdata['VfPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
							$this->VfPointdata->create();
							$result_p = $this->VfPointdata->save($pdata);
						}
						$response['message'] = 'Success.';
						$response['result'] = 1;
						CakeLog::write('info', "Test Device Message file upload : VF|VF_FILE_UPLOADED|" . $lastId);
						$data_device_message['DeviceMessage']['office_id'] = $data_arrays['office_id'];
						$data_device_message['DeviceMessage']['device_id'] = $data_arrays['device_id'];
						$data_device_message['DeviceMessage']['message'] = 'VF|VF_FILE_UPLOADED|' . $lastId;
						$data_device_message['DeviceMessage']['craeted_at'] = date("Y-m-d H:i:s");
						$data_device_message['DeviceMessage']['updated_at'] = date("Y-m-d H:i:s");
						$this->DeviceMessage->save($data_device_message);
						//update credits------
						$this->loadModel('User');
						/*$this->User->id = $data['Pointdata']['staff_id'];
                        $credits = $this->User->field('credits');
                        $new_credit = $credits - 1;
                        $this->User->updateAll(array('User.credits' => $new_credit), array('User.id' => $data['Pointdata']['staff_id']));*/
					} else {
						$response['message'] = 'Some error occured in updating report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/******************************pointData *********************************/
	public function pointData()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'pointData';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				$data_arrays['test_report_id'] = 1;
				//echo "<pre>";
				//$response['request_data']=$data_arrays;
				//pr($data_arrays['vfpointdata']);die;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "pointData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['Pointdata']['test_id'] = isset($data_arrays['test_id']) ? $data_arrays['test_id'] : '';
					$data['Pointdata']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
					$data['Pointdata']['numpoints'] = isset($data_arrays['numpoints']) ? $data_arrays['numpoints'] : '';
					$data['Pointdata']['color'] = isset($data_arrays['color']) ? $data_arrays['color'] : '';
					$data['Pointdata']['backgroundcolor'] = isset($data_arrays['backgroundcolor']) ? $data_arrays['backgroundcolor'] : '';
					$data['Pointdata']['stmsize'] = isset($data_arrays['stmSize']) ? $data_arrays['stmSize'] : '';
					$data['Pointdata']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['Pointdata']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['Pointdata']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['Pointdata']['master_key'] = isset($data_arrays['master_key']) ? $data_arrays['master_key'] : '';
					$data['Pointdata']['eye_select'] = isset($data_arrays['eye_select']) ? $data_arrays['eye_select'] : 0;
					if ($data['Pointdata']['eye_select'] == "") {
						$data['Pointdata']['eye_select'] = 0;
					}
					$data['Pointdata']['test_type_id'] = isset($data_arrays['test_type_id']) ? $data_arrays['test_type_id'] : '';
					$data['Pointdata']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : '';
					$data['Pointdata']['mean_dev'] = @$data_arrays['mean_dev'];
					$data['Pointdata']['pattern_std'] = @$data_arrays['pattern_std'];
					$data['Pointdata']['mean_sen'] = @$data_arrays['mean_sen'];
					$data['Pointdata']['mean_def'] = @$data_arrays['mean_def'];
					$data['Pointdata']['pattern_std_hfa'] = @$data_arrays['pattern_std_hfa'];
					$data['Pointdata']['loss_var'] = @$data_arrays['loss_var'];
					$data['Pointdata']['mean_std'] = @$data_arrays['mean_std'];
					$data['Pointdata']['psd_hfa_2'] = @$data_arrays['psd_hfa_2'];
					$data['Pointdata']['psd_hfa'] = @$data_arrays['psd_hfa'];
					$data['Pointdata']['vission_loss'] = @$data_arrays['vission_loss'];
					$data['Pointdata']['false_p'] = @$data_arrays['false_p'];
					$data['Pointdata']['false_n'] = @$data_arrays['false_n'];
					$data['Pointdata']['false_f'] = @$data_arrays['false_f'];
					$data['Pointdata']['ght'] = @$data_arrays['ght'];
					$data['Pointdata']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['Pointdata']['threshold'] = @$data_arrays['threshold'];
					$data['Pointdata']['strategy'] = @$data_arrays['strategy'];
					$data['Pointdata']['test_color_fg'] = $data_arrays['test_color_fg'];
					$data['Pointdata']['test_color_bg'] = $data_arrays['test_color_bg'];
					$data['Pointdata']['latitude'] = @$data_arrays['latitude'];
					$data['Pointdata']['longitude'] = @$data_arrays['longitude'];
					$data['Pointdata']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['Pointdata']['version'] = @$data_arrays['version'];
					$data['Pointdata']['diagnosys'] = @$data_arrays['diagnosys'];
					// $count_baseline = $this->Pointdata->find('count',array(
					// 	'conditions'=>array(
					// 		'test_name'=>$data['Pointdata']['test_name'],
					// 		'eye_select'=>$data['Pointdata']['eye_select'],'patient_id'=>$data['Pointdata']['patient_id'],'Pointdata.baseline'=>'1'
					// 	)
					// ));
					// if($count_baseline<2){
					// 	$data['Pointdata']['baseline'] = 1;
					// }
					$data['Pointdata']['baseline'] = (isset($data_arrays['baseline']) && !empty($data_arrays['baseline'])) ? $data_arrays['baseline'] : 0;
					//pr($count_baseline);die;
					$result = $this->Pointdata->save($data);
					$lastId = $this->Pointdata->id;
					if ($result) {
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						if ($this->ReportRequestBackup->save($result2)) {
						}
						if (!empty($data_arrays['file'])) {
							//$response['pdf'] = WWW_BASE . 'apisnew/fileDownloadUrl/' . $data_arrays['file'];
							$response['pdf'] = WWW_BASE . 'pointData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						//$pdata ="";
						foreach ($data_arrays['vfpointdata'] as $pdatas) {
							$pdata['VfPointdata']['report_id'] = @$data_arrays['test_report_id'];
							$pdata['VfPointdata']['point_data_id'] = @$lastId;
							$pdata['VfPointdata']['x'] = isset($pdatas['x']) ? $pdatas['x'] : '';
							$pdata['VfPointdata']['y'] = isset($pdatas['y']) ? $pdatas['y'] : '';
							$pdata['VfPointdata']['intensity'] = isset($pdatas['intensity']) ? $pdatas['intensity'] : '';;
							$pdata['VfPointdata']['size'] = isset($pdatas['size']) ? $pdatas['size'] : '';
							$pdata['VfPointdata']['zPD'] = isset($pdatas['zPD']) ? $pdatas['zPD'] : '';
							$pdata['VfPointdata']['STD'] = isset($pdatas['STD']) ? (float)$pdatas['STD'] : '';
							$pdata['VfPointdata']['index'] = isset($pdatas['index']) ? $pdatas['index'] : '';
							$pdata['VfPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
							//pr($pdata); die;
							$this->VfPointdata->create();
							$result_p = $this->VfPointdata->save($pdata);
						}
						$response['message'] = 'Success.';
						$response['result'] = 1;
						//update credits------
						/*$this->loadModel('User');
						$this->User->id = $data['Pointdata']['staff_id'];
						$credits = $this->User->field('credits');
						$new_credit = $credits-1;
						$this->User->updateAll(array('User.credits'=>$new_credit),array('User.id' =>$data['Pointdata']['staff_id']));*/
					} else {
						$response['message'] = 'Some error occured in updating report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}

	/*V6 for uplaod single report 22-11-2022*/
	/******************************pointData *********************************/
	public function pointData_v6()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'pointData';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				$data_arrays['test_report_id'] = 1;
				//echo "<pre>";
				//$response['request_data']=$data_arrays;
				
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "pointData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['Pointdata']['test_id'] = isset($data_arrays['test_id']) ? $data_arrays['test_id'] : '';
					$data['Pointdata']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
					$data['Pointdata']['numpoints'] = isset($data_arrays['numpoints']) ? $data_arrays['numpoints'] : '';
					$data['Pointdata']['color'] = isset($data_arrays['color']) ? $data_arrays['color'] : '';
					$data['Pointdata']['backgroundcolor'] = isset($data_arrays['backgroundcolor']) ? $data_arrays['backgroundcolor'] : '';
					$data['Pointdata']['stmsize'] = isset($data_arrays['stmSize']) ? $data_arrays['stmSize'] : '';
					$data['Pointdata']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['Pointdata']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['Pointdata']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['Pointdata']['master_key'] = isset($data_arrays['master_key']) ? $data_arrays['master_key'] : '';
					$data['Pointdata']['eye_select'] = isset($data_arrays['eye_select']) ? $data_arrays['eye_select'] : 0;
					if ($data['Pointdata']['eye_select'] == "") {
						$data['Pointdata']['eye_select'] = 0;
					}
					$data['Pointdata']['test_type_id'] = isset($data_arrays['test_type_id']) ? $data_arrays['test_type_id'] : '';
					$data['Pointdata']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : '';
					$data['Pointdata']['mean_dev'] = @$data_arrays['mean_dev'];
					$data['Pointdata']['pattern_std'] = @$data_arrays['pattern_std'];
					$data['Pointdata']['mean_sen'] = @$data_arrays['mean_sen'];
					$data['Pointdata']['mean_def'] = @$data_arrays['mean_def'];
					$data['Pointdata']['pattern_std_hfa'] = @$data_arrays['pattern_std_hfa'];
					$data['Pointdata']['loss_var'] = @$data_arrays['loss_var'];
					$data['Pointdata']['mean_std'] = @$data_arrays['mean_std'];
					$data['Pointdata']['psd_hfa_2'] = @$data_arrays['psd_hfa_2'];
					$data['Pointdata']['psd_hfa'] = @$data_arrays['psd_hfa'];
					$data['Pointdata']['vission_loss'] = @$data_arrays['vission_loss'];
					$data['Pointdata']['false_p'] = @$data_arrays['false_p'];
					$data['Pointdata']['false_n'] = @$data_arrays['false_n'];
					$data['Pointdata']['false_f'] = @$data_arrays['false_f'];
					$data['Pointdata']['ght'] = @$data_arrays['ght'];
					$data['Pointdata']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['Pointdata']['created_date_utc'] = @$data_arrays['created_date_utc'];
					$data['Pointdata']['threshold'] = @$data_arrays['threshold'];
					$data['Pointdata']['strategy'] = @$data_arrays['strategy'];
					$data['Pointdata']['test_color_fg'] = $data_arrays['test_color_fg'];
					$data['Pointdata']['test_color_bg'] = $data_arrays['test_color_bg'];
					$data['Pointdata']['latitude'] = @$data_arrays['latitude'];
					$data['Pointdata']['longitude'] = @$data_arrays['longitude'];
					$data['Pointdata']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['Pointdata']['version'] = @$data_arrays['version'];
					$data['Pointdata']['diagnosys'] = @$data_arrays['diagnosys'];
					// $count_baseline = $this->Pointdata->find('count',array(
					// 	'conditions'=>array(
					// 		'test_name'=>$data['Pointdata']['test_name'],
					// 		'eye_select'=>$data['Pointdata']['eye_select'],'patient_id'=>$data['Pointdata']['patient_id'],'Pointdata.baseline'=>'1'
					// 	)
					// ));
					// if($count_baseline<2){
					// 	$data['Pointdata']['baseline'] = 1;
					// }
					$data['Pointdata']['baseline'] = (isset($data_arrays['baseline']) && !empty($data_arrays['baseline'])) ? $data_arrays['baseline'] : 0;
					//pr($count_baseline);die;

					$result = $this->Pointdata->save($data);
					$lastId = $this->Pointdata->id;
					if ($result) {
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						if ($this->ReportRequestBackup->save($result2)) {
						}
						if (!empty($data_arrays['file'])) {
							//$response['pdf'] = WWW_BASE . 'apisnew/fileDownloadUrl/' . $data_arrays['file'];
							$response['pdf'] = WWW_BASE . 'pointData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						//$pdata ="";
						foreach ($data_arrays['vfpointdata'] as $pdatas) {
							$pdata['VfPointdata']['report_id'] = @$data_arrays['test_report_id'];
							$pdata['VfPointdata']['point_data_id'] = @$lastId;
							$pdata['VfPointdata']['x'] = isset($pdatas['x']) ? $pdatas['x'] : '';
							$pdata['VfPointdata']['y'] = isset($pdatas['y']) ? $pdatas['y'] : '';
							$pdata['VfPointdata']['intensity'] = isset($pdatas['intensity']) ? $pdatas['intensity'] : '';;
							$pdata['VfPointdata']['size'] = isset($pdatas['size']) ? $pdatas['size'] : '';
							$pdata['VfPointdata']['zPD'] = isset($pdatas['zPD']) ? $pdatas['zPD'] : '';
							$pdata['VfPointdata']['STD'] = isset($pdatas['STD']) ? (float)$pdatas['STD'] : '';
							$pdata['VfPointdata']['index'] = isset($pdatas['index']) ? $pdatas['index'] : '';
							$pdata['VfPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
							//pr($pdata); die;
							$this->VfPointdata->create();
							$result_p = $this->VfPointdata->save($pdata);
						}
						$response['message'] = 'Success.';
						$response['result'] = 1;
						//update credits------
						/*$this->loadModel('User');
						$this->User->id = $data['Pointdata']['staff_id'];
						$credits = $this->User->field('credits');
						$new_credit = $credits-1;
						$this->User->updateAll(array('User.credits'=>$new_credit),array('User.id' =>$data['Pointdata']['staff_id']));*/
					} else {
						$response['message'] = 'Some error occured in updating report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*V6 for uplaod single report 22-11-2022*/
	/******************************VtTestControllerData *********************************/
	public function VtTestControllerData()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'VtTestControllerData';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "VtTestControllerData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['VtTest']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
					$data['VtTest']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['VtTest']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['VtTest']['staff_name'] = isset($data_arrays['staff_name']) ? $data_arrays['staff_name'] : '';
					$data['VtTest']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['VtTest']['patient_name'] = isset($data_arrays['patient_name']) ? $data_arrays['patient_name'] : '';
					$data['VtTest']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : 'VT';
					$data['VtTest']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['VtTest']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['VtTest']['device_id'] = isset($data_arrays['device_id']) ? $data_arrays['device_id'] : 0;
					$data['VtTest']['office_id'] = isset($data_arrays['office_id']) ? $data_arrays['office_id'] : 0;
					$data['VtTest']['age_group'] = isset($data_arrays['age_group']) ? $data_arrays['age_group'] : 1;
					$data['VtTest']['version'] = isset($data_arrays['version']) ? $data_arrays['version'] : '1.0';
					$data['VtTest']['testId'] = isset($data_arrays['testId']) ? $data_arrays['testId'] : 0;
					$result = $this->VtTest->save($data);
					$lastId = $this->VtTest->id;
					if ($result) {
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						if ($this->ReportRequestBackup->save($result2)) {
						}
						if (!empty($data_arrays['file'])) {
							$response['pdf'] = WWW_BASE . 'app/webroot/VtTestControllerData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						$response['message'] = 'Success.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'Some error occured in updating report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}

	/*Save Report Report create new api by Madan 24-11-2022*/
	public function saveVTReport_v6()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'saveVTReport_v6';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "VtTestControllerData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['VtTest']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
					$data['VtTest']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['VtTest']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['VtTest']['staff_name'] = isset($data_arrays['staff_name']) ? $data_arrays['staff_name'] : '';
					$data['VtTest']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['VtTest']['patient_name'] = isset($data_arrays['patient_name']) ? $data_arrays['patient_name'] : '';
					$data['VtTest']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : 'VT';
					$data['VtTest']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['VtTest']['created_date_utc'] = $data_arrays['created_date_utc'];
					$data['VtTest']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['VtTest']['device_id'] = isset($data_arrays['device_id']) ? $data_arrays['device_id'] : 0;
					$data['VtTest']['office_id'] = isset($data_arrays['office_id']) ? $data_arrays['office_id'] : 0;
					$data['VtTest']['age_group'] = isset($data_arrays['age_group']) ? $data_arrays['age_group'] : 1;
					$data['VtTest']['version'] = isset($data_arrays['version']) ? $data_arrays['version'] : '1.0';
					$data['VtTest']['testId'] = isset($data_arrays['testId']) ? $data_arrays['testId'] : 0;
					$result = $this->VtTest->save($data);
					$lastId = $this->VtTest->id;
					if ($result) {
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						if ($this->ReportRequestBackup->save($result2)) {
						}
						if (!empty($data_arrays['file'])) {
							$response['pdf'] = WWW_BASE . 'app/webroot/VtTestControllerData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						$response['message'] = 'Success.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'Some error occured in updating report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*Save Report Report create new api by Madan 24-11-2022*/

	/*Multiple report faild data 04-11-2022*/
		public function VtTestControllerData_V4()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$faildRequest = array();
				$resultData = $saved_data = $faild_data = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'VtTestControllerData_V4';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "VtTestControllerData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['VtTest']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
					$data['VtTest']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['VtTest']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['VtTest']['staff_name'] = isset($data_arrays['staff_name']) ? $data_arrays['staff_name'] : '';
					$data['VtTest']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['VtTest']['patient_name'] = isset($data_arrays['patient_name']) ? $data_arrays['patient_name'] : '';
					$data['VtTest']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : 'VT';
					$data['VtTest']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['VtTest']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['VtTest']['device_id'] = isset($data_arrays['device_id']) ? $data_arrays['device_id'] : 0;
					$data['VtTest']['office_id'] = isset($data_arrays['office_id']) ? $data_arrays['office_id'] : 0;
					$data['VtTest']['age_group'] = isset($data_arrays['age_group']) ? $data_arrays['age_group'] : 1;
					$data['VtTest']['version'] = isset($data_arrays['version']) ? $data_arrays['version'] : '1.0';
					$data['VtTest']['testId'] = isset($data_arrays['testId']) ? $data_arrays['testId'] : 0;
					$result = $this->VtTest->save($data);
					if ($result) {
						$saved_data[]['id'] = $this->VtTest->id;
					}else{
					  	$fail=array(); 
							$errors = $this->VtTest->validationErrors;
							$response['message']='Some error occured in updating report.';
							$result2 = $this->VtTest->find('first', array('conditions' => array('VtTest.unique_id' => $data_arrays['unique_id'])));
							$response['result']=0;
							$fail['id']=$result2['VtTest']['id']; 
							$fail['unique_id']=$resultData['unique_id'];
							$name=$name=$result2['Patient']['first_name'];
							if($result2['Patient']['middle_name']!=""){
								$name=$name.' '.$result2['Patient']['middle_name'];
							}
							if($result2['Patient']['last_name']!=""){
								$name=$name.' '.$result2['Patient']['last_name'];
							}
							$fail['patient_name']=$name; 
							$fail['message']=$errors[array_keys($errors)[0]][0];
							$faild_data[]=$fail; 
						} 
					$lastId = $this->VtTest->id;
					if ($result) {
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						if ($this->ReportRequestBackup->save($result2)) {
						}
						if (!empty($data_arrays['file'])) {
							$response['pdf'] = WWW_BASE . 'app/webroot/VtTestControllerData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						$response['data'] = $saved_data;
						$response['message'] = 'Success.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'Some error occured in updating report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				$response['failed_data'] = $faild_data;
				echo json_encode($response);
				exit();
			}
		}
	}
	/*Multiple report faild data 04-11-2022*/


	/*Upload multiple VT report create new API by Madan 24-11-2022*/
	public function saveMultipleVTReport_v6()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$faildRequest = array();
				$resultData = $saved_data = $faild_data = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'saveMultipleVTReport_v6';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "VtTestControllerData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['VtTest']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
					$data['VtTest']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['VtTest']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['VtTest']['staff_name'] = isset($data_arrays['staff_name']) ? $data_arrays['staff_name'] : '';
					$data['VtTest']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['VtTest']['patient_name'] = isset($data_arrays['patient_name']) ? $data_arrays['patient_name'] : '';
					$data['VtTest']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : 'VT';
					$data['VtTest']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['VtTest']['created_date_utc'] = $data_arrays['created_date_utc'];
					$data['VtTest']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['VtTest']['device_id'] = isset($data_arrays['device_id']) ? $data_arrays['device_id'] : 0;
					$data['VtTest']['office_id'] = isset($data_arrays['office_id']) ? $data_arrays['office_id'] : 0;
					$data['VtTest']['age_group'] = isset($data_arrays['age_group']) ? $data_arrays['age_group'] : 1;
					$data['VtTest']['version'] = isset($data_arrays['version']) ? $data_arrays['version'] : '1.0';
					$data['VtTest']['testId'] = isset($data_arrays['testId']) ? $data_arrays['testId'] : 0;
					$result = $this->VtTest->save($data);
					if ($result) {
						$saved_data[]['id'] = $this->VtTest->id;
					}else{
					  	$fail=array(); 
							$errors = $this->VtTest->validationErrors;
							$response['message']='Some error occured in updating report.';
							$result2 = $this->VtTest->find('first', array('conditions' => array('VtTest.unique_id' => $data_arrays['unique_id'])));
							$response['result']=0;
							$fail['id']=$result2['VtTest']['id']; 
							$fail['unique_id']=$data_arrays['unique_id'];
							$name=$name=$result2['Patient']['first_name'];
							if($result2['Patient']['middle_name']!=""){
								$name=$name.' '.$result2['Patient']['middle_name'];
							}
							if($result2['Patient']['last_name']!=""){
								$name=$name.' '.$result2['Patient']['last_name'];
							}
							$fail['patient_name']=$name; 
							$fail['message']=$errors[array_keys($errors)[0]][0];
							$faild_data[]=$fail; 
						} 
					$lastId = $this->VtTest->id;
					if ($result) {
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						if ($this->ReportRequestBackup->save($result2)) {
						}
						if (!empty($data_arrays['file'])) {
							$response['pdf'] = WWW_BASE . 'app/webroot/VtTestControllerData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						$response['data'] = $saved_data;
						$response['message'] = 'Success.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'Some error occured in updating report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				$response['failed_data'] = $faild_data;
				echo json_encode($response);
				exit();
			}
		}
	}
	/*Upload multiple VT report create new API by Madan 24-11-2022*/

	public function get_vt_report()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				//pr($data_arrays);die;
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = 100000;
					} else {
						$limit = 10;
					}
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id'], 'User.user_type' => array('Staffuser', 'Subadmin')), 'fields' => array('User.office_id')));
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id'], 'User.user_type' => array('Staffuser', 'Subadmin')), 'fields' => array('User.id')));
					$condition['VtTest.staff_id'] = $all_staff_ids;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['VtTest.patient_id'] = $data_arrays['patient_id'];
					}
					if (isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["VtTest.patient_name LIKE"] = '%' . $data_arrays['patient_name'] . "%";
					}
					if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['VtTest.created >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}
					$this->loadModel('VtTest');
					$this->loadModel('User');
					//$this->VtTest->unbindModel(array('belongsTo' => array('Patient')),false);
					$results = $this->VtTest->find('all', array(
						'conditions' => $condition,
						'order' => array('VtTest.id DESC'),
						'limit' => $limit,
						'page' => $data_arrays['page'],
					));
					$nextPageData = 0;
					if($data_arrays['page']  != 0 ){
						$nextPageData = $this->VtTest->find('count', array(
							'conditions' => $condition,
							'order' => array('VtTest.id DESC'),
						));
						$nextPageData = $nextPageData ? 1 : 0;
					}
					$last_sync_time = "";
					$i = 0;
					if(!empty($results)) {
						$last_sync_time = $results[0]['VtTest']['created'];
						foreach ($results as $key => $result) { 
							$data[$i] = $result['VtTest'];
							$data[$i]['test_id'] = $result['VtTest']['id'];
							$data[$i]['unique_id'] = $result['VtTest']['unique_id'];
							$data[$i]['staff_name'] = @$result['User']['complete_name'];
							$data[$i]['created_date'] = ($result['VtTest']['created'] != null) ? ($result['VtTest']['created']) : '';
							$data[$i]['patient_name'] = $result['Patient']['first_name'].''.$result['Patient']['middle_name'].' '.$result['Patient']['last_name'];
							if (!empty($result['VtTest']['file'])) {
								$data[$i]['pdf'] = WWW_BASE . 'app/webroot/VtTestControllerData/' . $result['VtTest']['file'];
							} else {
								$data[$i]['pdf'] = '';
							}
							$data[$i]['patient_id'] = isset($result['VtTest']['patient_id']) ? ($result['VtTest']['patient_id']) : '';
							unset($data[$i]['id']);
							unset($data[$i]['created']);
							unset($data[$i]['file']);
							$i++;
						}
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['result'] = 1;
							$response['more_data'] = $nextPageData;
							$response['last_sync_time'] = $last_sync_time;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $nextPageData;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}

	/*Get Vt report create new version 6 API by Madan 24-11-2022*/
	public function get_vt_report_v6(){
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = 100000;
					} else {
						$limit = 10;
					}
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id'], 'User.user_type' => array('Staffuser', 'Subadmin')), 'fields' => array('User.office_id')));
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id'], 'User.user_type' => array('Staffuser', 'Subadmin')), 'fields' => array('User.id')));
					$condition['VtTest.staff_id'] = $all_staff_ids;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['VtTest.patient_id'] = $data_arrays['patient_id'];
					}
					if (isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["VtTest.patient_name LIKE"] = '%' . $data_arrays['patient_name'] . "%";
					}
					if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['VtTest.created_date_utc >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}
					$this->loadModel('VtTest');
					$this->loadModel('User');
					//$this->VtTest->unbindModel(array('belongsTo' => array('Patient')),false);
					$results = $this->VtTest->find('all', array(
						'conditions' => $condition,
						'order' => array('VtTest.id DESC'),
						'limit' => $limit,
						'page' => $data_arrays['page'],
					));
					$nextPageData = 0;
					if($data_arrays['page']  != 0 ){
						$nextPageData = $this->VtTest->find('count', array(
							'conditions' => $condition,
							'order' => array('VtTest.id DESC'),
						));
						$nextPageData = $nextPageData ? 1 : 0;
					}
					$last_sync_time = "";
					$i = 0;
					if(!empty($results)) {
						$last_sync_time = $results[0]['VtTest']['created_date_utc'];
						foreach ($results as $key => $result) { 
							$data[$i] = $result['VtTest'];
							$data[$i]['test_id'] = $result['VtTest']['id'];
							$data[$i]['unique_id'] = $result['VtTest']['unique_id'];
							$data[$i]['staff_name'] = @$result['User']['complete_name'];
							$data[$i]['created_date'] = ($result['VtTest']['created'] != null) ? ($result['VtTest']['created']) : '';
							$data[$i]['patient_name'] = $result['Patient']['first_name'].''.$result['Patient']['middle_name'].' '.$result['Patient']['last_name'];
							if (!empty($result['VtTest']['file'])) {
								$data[$i]['pdf'] = WWW_BASE . 'app/webroot/VtTestControllerData/' . $result['VtTest']['file'];
							} else {
								$data[$i]['pdf'] = '';
							}
							$data[$i]['patient_id'] = isset($result['VtTest']['patient_id']) ? ($result['VtTest']['patient_id']) : '';
							unset($data[$i]['id']);
							unset($data[$i]['created']);
							unset($data[$i]['file']);
							$i++;
						}
						if($data_arrays['sync_start_time'] == ''){
							date_default_timezone_set('UTC');
            				$UTCDate = date('Y-m-d H:i:s');
							$last_sync_time = $UTCDate;
						}
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['result'] = 1;
							$response['more_data'] = $nextPageData;
							$response['last_sync_time'] = $last_sync_time;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $nextPageData;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}
	/*Get Vt report create new version 6 API by Madan 24-11-2022*/

	/******************************ActTestControllerData *********************************/
	public function ActTestControllerData()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'ActTestControllerData';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "ActTestControllerData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['ActTest']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
					$data['ActTest']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['ActTest']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['ActTest']['staff_name'] = isset($data_arrays['staff_name']) ? $data_arrays['staff_name'] : '';
					$data['ActTest']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['ActTest']['patient_name'] = isset($data_arrays['patient_name']) ? $data_arrays['patient_name'] : '';
					$data['ActTest']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : 'VT';
					$data['ActTest']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['ActTest']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['ActTest']['device_id'] = isset($data_arrays['device_id']) ? $data_arrays['device_id'] : 0;
					$data['ActTest']['office_id'] = isset($data_arrays['office_id']) ? $data_arrays['office_id'] : 0;
					$data['ActTest']['age_group'] = isset($data_arrays['age_group']) ? $data_arrays['age_group'] : 1;
					$data['ActTest']['version'] = isset($data_arrays['version']) ? $data_arrays['version'] : '1.0';
					$data['ActTest']['testId'] = isset($data_arrays['testId']) ? $data_arrays['testId'] : 0;
					$result = $this->ActTest->save($data);
					$lastId = $this->ActTest->id;
					if ($result) {
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						if ($this->ReportRequestBackup->save($result2)) {
						}
						if (!empty($data_arrays['file'])) {
							$response['pdf'] = WWW_BASE . 'app/webroot/ActTestControllerData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						foreach ($data_arrays['actPointData'] as $pdatas) {
							$pdata['ActPointdata']['act_point_data_id'] = @$lastId;
							$pdata['ActPointdata']['eye'] = isset($pdatas['eye']) ? $pdatas['eye'] : 0;
							$pdata['ActPointdata']['ContrastDB'] = isset($pdatas['ContrastDB']) ? $pdatas['ContrastDB'] : 0.00;
							$pdata['ActPointdata']['testColor'] = isset($pdatas['testColor']) ? $pdatas['testColor'] : 0;
							$pdata['ActPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
							$this->ActPointdata->create();
							$result_p = $this->ActPointdata->save($pdata);
						}
						$response['message'] = 'Success.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'Some error occured in updating report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}

	/*SAve single ACT report create new API v6 by Madan 24-11-2022*/
	public function saveACTReport_v6()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'saveACTReport_v6';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "ActTestControllerData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['ActTest']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
					$data['ActTest']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['ActTest']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['ActTest']['staff_name'] = isset($data_arrays['staff_name']) ? $data_arrays['staff_name'] : '';
					$data['ActTest']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['ActTest']['patient_name'] = isset($data_arrays['patient_name']) ? $data_arrays['patient_name'] : '';
					$data['ActTest']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : 'VT';
					$data['ActTest']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['ActTest']['created_date_utc'] = $data_arrays['created_date_utc'];
					$data['ActTest']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['ActTest']['device_id'] = isset($data_arrays['device_id']) ? $data_arrays['device_id'] : 0;
					$data['ActTest']['office_id'] = isset($data_arrays['office_id']) ? $data_arrays['office_id'] : 0;
					$data['ActTest']['age_group'] = isset($data_arrays['age_group']) ? $data_arrays['age_group'] : 1;
					$data['ActTest']['version'] = isset($data_arrays['version']) ? $data_arrays['version'] : '1.0';
					$data['ActTest']['testId'] = isset($data_arrays['testId']) ? $data_arrays['testId'] : 0;
					$result = $this->ActTest->save($data);
					$lastId = $this->ActTest->id;
					if ($result) {
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						if ($this->ReportRequestBackup->save($result2)) {
						}
						if (!empty($data_arrays['file'])) {
							$response['pdf'] = WWW_BASE . 'app/webroot/ActTestControllerData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						foreach ($data_arrays['actPointData'] as $pdatas) {
							$pdata['ActPointdata']['act_point_data_id'] = @$lastId;
							$pdata['ActPointdata']['eye'] = isset($pdatas['eye']) ? $pdatas['eye'] : 0;
							$pdata['ActPointdata']['ContrastDB'] = isset($pdatas['ContrastDB']) ? $pdatas['ContrastDB'] : 0.00;
							$pdata['ActPointdata']['testColor'] = isset($pdatas['testColor']) ? $pdatas['testColor'] : 0;
							$pdata['ActPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
							//$pdata['ActPointdata']['created_date_utc'] = $pdatas['created_date_utc'];
							$this->ActPointdata->create();
							$result_p = $this->ActPointdata->save($pdata);
						}
						$response['message'] = 'Success.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'Some error occured in updating report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*SAve single ACT report create new API v6 by Madan 24-11-2022*/

	/*Multiple report faild data 04-11-2022*/
		public function ActTestControllerData_V4()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$faildRequest = array();
				$resultData = $saved_data = $faild_data = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'ActTestControllerData_V4';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "ActTestControllerData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['ActTest']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
					$data['ActTest']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['ActTest']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['ActTest']['staff_name'] = isset($data_arrays['staff_name']) ? $data_arrays['staff_name'] : '';
					$data['ActTest']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['ActTest']['patient_name'] = isset($data_arrays['patient_name']) ? $data_arrays['patient_name'] : '';
					$data['ActTest']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : 'VT';
					$data['ActTest']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['ActTest']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['ActTest']['device_id'] = isset($data_arrays['device_id']) ? $data_arrays['device_id'] : 0;
					$data['ActTest']['office_id'] = isset($data_arrays['office_id']) ? $data_arrays['office_id'] : 0;
					$data['ActTest']['age_group'] = isset($data_arrays['age_group']) ? $data_arrays['age_group'] : 1;
					$data['ActTest']['version'] = isset($data_arrays['version']) ? $data_arrays['version'] : '1.0';
					$data['ActTest']['testId'] = isset($data_arrays['testId']) ? $data_arrays['testId'] : 0;
					$result = $this->ActTest->save($data);
					if ($result) {
						$saved_data[]['id'] = $this->ActTest->id;
					}else{
					  	$fail=array(); 
							$errors = $this->ActTest->validationErrors;
							$response['message']='Some error occured in updating report.';
							$result2 = $this->ActTest->find('first', array('conditions' => array('ActTest.unique_id' => $data_arrays['unique_id'])));
							$response['result']=0;
							$fail['id']=$result2['ActTest']['id']; 
							$fail['unique_id']=$data_arrays['unique_id'];
							$name=$name=$result2['Patient']['first_name'];
							if($result2['Patient']['middle_name']!=""){
								$name=$name.' '.$result2['Patient']['middle_name'];
							}
							if($result2['Patient']['last_name']!=""){
								$name=$name.' '.$result2['Patient']['last_name'];
							}
							$fail['patient_name']=$name; 
							$fail['message']=$errors[array_keys($errors)[0]][0];
							$faild_data[]=$fail; 
						}
					$lastId = $this->ActTest->id;
					if ($result) {
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						if ($this->ReportRequestBackup->save($result2)) {
						}
						if (!empty($data_arrays['file'])) {
							$response['pdf'] = WWW_BASE . 'app/webroot/ActTestControllerData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						foreach ($data_arrays['actPointData'] as $pdatas) {
							$pdata['ActPointdata']['act_point_data_id'] = @$lastId;
							$pdata['ActPointdata']['eye'] = isset($pdatas['eye']) ? $pdatas['eye'] : 0;
							$pdata['ActPointdata']['ContrastDB'] = isset($pdatas['ContrastDB']) ? $pdatas['ContrastDB'] : 0.00;
							$pdata['ActPointdata']['testColor'] = isset($pdatas['testColor']) ? $pdatas['testColor'] : 0;
							$pdata['ActPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
							$this->ActPointdata->create();
							$result_p = $this->ActPointdata->save($pdata);
						}
						$response['data'] = $saved_data;
						$response['message'] = 'Success.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'Some error occured in updating report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				$response['failed_data'] = $faild_data;
				echo json_encode($response);
				exit();
			}
		}
	}
	/*Multiple report faild data 04-11-2022*/

	/*Multiple upload ACT report create new API by Madan 24-11-2022*/
	public function saveMultipleACTReport_v6()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$faildRequest = array();
				$resultData = $saved_data = $faild_data = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'saveMultipleACTReport_v6';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "ActTestControllerData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['ActTest']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
					$data['ActTest']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['ActTest']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['ActTest']['staff_name'] = isset($data_arrays['staff_name']) ? $data_arrays['staff_name'] : '';
					$data['ActTest']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['ActTest']['patient_name'] = isset($data_arrays['patient_name']) ? $data_arrays['patient_name'] : '';
					$data['ActTest']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : 'VT';
					$data['ActTest']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['ActTest']['created_date_utc'] = $data_arrays['created_date_utc'];
					$data['ActTest']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['ActTest']['device_id'] = isset($data_arrays['device_id']) ? $data_arrays['device_id'] : 0;
					$data['ActTest']['office_id'] = isset($data_arrays['office_id']) ? $data_arrays['office_id'] : 0;
					$data['ActTest']['age_group'] = isset($data_arrays['age_group']) ? $data_arrays['age_group'] : 1;
					$data['ActTest']['version'] = isset($data_arrays['version']) ? $data_arrays['version'] : '1.0';
					$data['ActTest']['testId'] = isset($data_arrays['testId']) ? $data_arrays['testId'] : 0;
					$result = $this->ActTest->save($data);
					if ($result) {
						$saved_data[]['id'] = $this->ActTest->id;
					}else{
					  	$fail=array(); 
							$errors = $this->ActTest->validationErrors;
							$response['message']='Some error occured in updating report.';
							$result2 = $this->ActTest->find('first', array('conditions' => array('ActTest.unique_id' => $data_arrays['unique_id'])));
							$response['result']=0;
							$fail['id']=$result2['ActTest']['id']; 
							$fail['unique_id']=$data_arrays['unique_id'];
							$name=$name=$result2['Patient']['first_name'];
							if($result2['Patient']['middle_name']!=""){
								$name=$name.' '.$result2['Patient']['middle_name'];
							}
							if($result2['Patient']['last_name']!=""){
								$name=$name.' '.$result2['Patient']['last_name'];
							}
							$fail['patient_name']=$name; 
							$fail['message']=$errors[array_keys($errors)[0]][0];
							$faild_data[]=$fail; 
						}
					$lastId = $this->ActTest->id;
					if ($result) {
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						if ($this->ReportRequestBackup->save($result2)) {
						}
						if (!empty($data_arrays['file'])) {
							$response['pdf'] = WWW_BASE . 'app/webroot/ActTestControllerData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						foreach ($data_arrays['actPointData'] as $pdatas) {
							$pdata['ActPointdata']['act_point_data_id'] = @$lastId;
							$pdata['ActPointdata']['eye'] = isset($pdatas['eye']) ? $pdatas['eye'] : 0;
							$pdata['ActPointdata']['ContrastDB'] = isset($pdatas['ContrastDB']) ? $pdatas['ContrastDB'] : 0.00;
							$pdata['ActPointdata']['testColor'] = isset($pdatas['testColor']) ? $pdatas['testColor'] : 0;
							$pdata['ActPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
							$this->ActPointdata->create();
							$result_p = $this->ActPointdata->save($pdata);
						}
						$response['data'] = $saved_data;
						$response['message'] = 'Success.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'Some error occured in updating report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				$response['failed_data'] = $faild_data;
				echo json_encode($response);
				exit();
			}
		}
	}
	/*Multiple upload ACT report create new API by Madan 24-11-2022*/

	public function get_act_report()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				//pr($data_arrays);die;
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = 100000;
					} else {
						$limit = 10;
					}
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id'], 'User.user_type' => array('Staffuser', 'Subadmin')), 'fields' => array('User.office_id')));
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id'], 'User.user_type' => array('Staffuser', 'Subadmin')), 'fields' => array('User.id')));
					$condition['ActTest.staff_id'] = $all_staff_ids;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['ActTest.patient_id'] = $data_arrays['patient_id'];
					}
					if (isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["ActTest.patient_name LIKE"] = '%' . $data_arrays['patient_name'] . "%";
					}
					if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['ActTest.created >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}
					$this->loadModel('ActTest');
					$this->loadModel('User');
					//$this->ActTest->unbindModel(array('belongsTo' => array('Patient')),false);
					$results = $this->ActTest->find('all', array(
						'conditions' => $condition,
						'order' => array('ActTest.id DESC'),
						'limit' => $limit,
						'page' => $data_arrays['page'],
					));
					$nextPageData = 0;
					if($data_arrays['page']  != 0 ){
						$nextPageData = $this->ActTest->find('count', array(
							'conditions' => $condition,
							'order' => array('ActTest.id DESC'),
						));
						$nextPageData = $nextPageData ? 1 : 0;
					}
					$last_sync_time = "";
					$i = 0;
					if(!empty($results)) {
						$last_sync_time = $results[0]['ActTest']['created'];
						foreach ($results as $key => $result) { //pr($result); die;
							$data[$i] = $result['ActTest'];
							$data[$i]['test_id'] = $result['ActTest']['id'];
							$data[$i]['unique_id'] = $result['ActTest']['unique_id'];
							$data[$i]['staff_name'] = @$result['User']['complete_name'];
							$data[$i]['created_date'] = ($result['ActTest']['created'] != null) ? ($result['ActTest']['created']) : '';
							$data[$i]['patient_name'] = $result['Patient']['first_name'].' '.$result['Patient']['middle_name'].' '.$result['Patient']['last_name'];
							if (!empty($result['ActTest']['file'])) {
								$data[$i]['pdf'] = WWW_BASE . 'app/webroot/ActTestControllerData/' . $result['ActTest']['file'];
							} else {
								$data[$i]['pdf'] = '';
							}
							$data[$i]['patient_id'] = isset($result['ActTest']['patient_id']) ? ($result['ActTest']['patient_id']) : '';
							unset($data[$i]['id']);
							unset($data[$i]['created']);
							unset($data[$i]['file']);
							$i++;
						}
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['result'] = 1;
							$response['more_data'] = $nextPageData;
							$response['last_sync_time'] = $last_sync_time;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $nextPageData;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}

	/*Get all ACT report create new API by Madan 24-11-2022*/
	public function get_act_report_v6()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				//pr($data_arrays);die;
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = 100000;
					} else {
						$limit = 10;
					}
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id'], 'User.user_type' => array('Staffuser', 'Subadmin')), 'fields' => array('User.office_id')));
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id'], 'User.user_type' => array('Staffuser', 'Subadmin')), 'fields' => array('User.id')));
					$condition['ActTest.staff_id'] = $all_staff_ids;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['ActTest.patient_id'] = $data_arrays['patient_id'];
					}
					if (isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["ActTest.patient_name LIKE"] = '%' . $data_arrays['patient_name'] . "%";
					}
					if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['ActTest.created_date_utc >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}
					$this->loadModel('ActTest');
					$this->loadModel('User');
					//$this->ActTest->unbindModel(array('belongsTo' => array('Patient')),false);
					$results = $this->ActTest->find('all', array(
						'conditions' => $condition,
						'order' => array('ActTest.id DESC'),
						'limit' => $limit,
						'page' => $data_arrays['page'],
					));
					$nextPageData = 0;
					if($data_arrays['page']  != 0 ){
						$nextPageData = $this->ActTest->find('count', array(
							'conditions' => $condition,
							'order' => array('ActTest.id DESC'),
						));
						$nextPageData = $nextPageData ? 1 : 0;
					}
					$last_sync_time = "";
					$i = 0;
					if(!empty($results)) {
						$last_sync_time = $results[0]['ActTest']['created_date_utc'];
						foreach ($results as $key => $result) { //pr($result); die;
							$data[$i] = $result['ActTest'];
							$data[$i]['test_id'] = $result['ActTest']['id'];
							$data[$i]['unique_id'] = $result['ActTest']['unique_id'];
							$data[$i]['staff_name'] = @$result['User']['complete_name'];
							$data[$i]['created_date'] = ($result['ActTest']['created'] != null) ? ($result['ActTest']['created']) : '';
							$data[$i]['patient_name'] = $result['Patient']['first_name'].' '.$result['Patient']['middle_name'].' '.$result['Patient']['last_name'];
							if (!empty($result['ActTest']['file'])) {
								$data[$i]['pdf'] = WWW_BASE . 'app/webroot/ActTestControllerData/' . $result['ActTest']['file'];
							} else {
								$data[$i]['pdf'] = '';
							}
							$data[$i]['patient_id'] = isset($result['ActTest']['patient_id']) ? ($result['ActTest']['patient_id']) : '';
							unset($data[$i]['id']);
							unset($data[$i]['created']);
							unset($data[$i]['file']);
							$i++;
						}
						if($data_arrays['sync_start_time'] == ''){
							date_default_timezone_set('UTC');
            				$UTCDate = date('Y-m-d H:i:s');
							$last_sync_time = $UTCDate;
						}
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['result'] = 1;
							$response['more_data'] = $nextPageData;
							$response['last_sync_time'] = $last_sync_time;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $nextPageData;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}
	/*Get all ACT report create new API by Madan 24-11-2022*/

	/******************************PupTestControllerData START*********************************/
	public function PupTestControllerData()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$this->loadModel('PupTest');
				$this->loadModel('PupPointdata');
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'PupTestControllerData';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "PupTestControllerData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['PupTest']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
					$data['PupTest']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['PupTest']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['PupTest']['staff_name'] = isset($data_arrays['staff_name']) ? $data_arrays['staff_name'] : '';
					$data['PupTest']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['PupTest']['patient_name'] = isset($data_arrays['patient_name']) ? $data_arrays['patient_name'] : '';
					$data['PupTest']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : 'VT';
					$data['PupTest']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['PupTest']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['PupTest']['device_id'] = isset($data_arrays['device_id']) ? $data_arrays['device_id'] : 0;
					$data['PupTest']['office_id'] = isset($data_arrays['office_id']) ? $data_arrays['office_id'] : 0;
					$data['PupTest']['age_group'] = isset($data_arrays['age_group']) ? $data_arrays['age_group'] : 1;
					$data['PupTest']['version'] = isset($data_arrays['version']) ? $data_arrays['version'] : '1.0';
					$result = $this->PupTest->save($data);
					if ($result) {
						$lastId = $this->PupTest->id;
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						$this->ReportRequestBackup->save($result2);
						if (!empty($data_arrays['file'])) {
							$response['pdf'] = WWW_BASE . 'app/webroot/PupTestControllerData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						foreach ($data_arrays['pupPointData'] as $pdatas) {
							$pdata['PupPointdata']['pup_test_id'] = $lastId;
							$pdata['PupPointdata']['time'] = isset($pdatas['time']) ? $pdatas['time'] : 0;
							$pdata['PupPointdata']['pupilDiam_OS'] = isset($pdatas['pupilDiam_OS']) ? $pdatas['pupilDiam_OS'] : 0.00;
							$pdata['PupPointdata']['pupilDiam_OD'] = isset($pdatas['pupilDiam_OD']) ? $pdatas['pupilDiam_OD'] : 0;
							$pdata['PupPointdata']['testState'] = isset($pdatas['testState']) ? $pdatas['testState'] : 0;
							$this->PupPointdata->create();
							$result_p = $this->PupPointdata->save($pdata);
						}
						$response['message'] = 'Success.';
						$response['result'] = 1;
					} else {
						$response['message'] = $this->getFirstError($this->PupTest->validationErrors);
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}


	/*Save single report PUP Create new API by MAdan 24-11-2022*/
	public function savePUPReport_v6()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$this->loadModel('PupTest');
				$this->loadModel('PupPointdata');
				$response =$faild_data= array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'savePUPReport_v6';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "PupTestControllerData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['PupTest']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
					$data['PupTest']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['PupTest']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['PupTest']['staff_name'] = isset($data_arrays['staff_name']) ? $data_arrays['staff_name'] : '';
					$data['PupTest']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['PupTest']['patient_name'] = isset($data_arrays['patient_name']) ? $data_arrays['patient_name'] : '';
					$data['PupTest']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : 'Pupolimeter';
					$data['PupTest']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['PupTest']['created_date_utc'] = $data_arrays['created_date_utc'];
					$data['PupTest']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['PupTest']['device_id'] = isset($data_arrays['device_id']) ? $data_arrays['device_id'] : 0;
					$data['PupTest']['office_id'] = isset($data_arrays['office_id']) ? $data_arrays['office_id'] : 0;
					$data['PupTest']['age_group'] = isset($data_arrays['age_group']) ? $data_arrays['age_group'] : 1;
					$data['PupTest']['version'] = isset($data_arrays['version']) ? $data_arrays['version'] : '1.0';
					$result = $this->PupTest->save($data);
					if ($result) {
						$lastId = $this->PupTest->id;
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						$this->ReportRequestBackup->save($result2);
						if (!empty($data_arrays['file'])) {
							$response['pdf'] = WWW_BASE . 'app/webroot/PupTestControllerData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						foreach ($data_arrays['pupPointData'] as $pdatas) {
							$pdata['PupPointdata']['pup_test_id'] = $lastId;
							$pdata['PupPointdata']['time'] = isset($pdatas['time']) ? $pdatas['time'] : 0;
							$pdata['PupPointdata']['pupilDiam_OS'] = isset($pdatas['pupilDiam_OS']) ? $pdatas['pupilDiam_OS'] : 0.00;
							$pdata['PupPointdata']['pupilDiam_OD'] = isset($pdatas['pupilDiam_OD']) ? $pdatas['pupilDiam_OD'] : 0;
							$pdata['PupPointdata']['testState'] = isset($pdatas['testState']) ? $pdatas['testState'] : 0;
							$this->PupPointdata->create();
							$result_p = $this->PupPointdata->save($pdata);
						}
						$response['message'] = 'Success.';
						$response['result'] = 1;
					} else {
						$fail=array(); 
						$errors = $this->PupTest->validationErrors;
						$response['message']='Some error occured in updating report.';
						$result2 = $this->PupTest->find('first', array('conditions' => array('PupTest.unique_id' => $data_arrays['PupTest']['unique_id'])));
						$response['result']=0;
						$fail['id']=$result2['PupTest']['id']; 
						$fail['unique_id']=$resultData['PupTest']['unique_id'];
						//pr($result2['Patient']);die; 
						$name=$name=$result2['Patient']['first_name'];
						if($result2['Patient']['middle_name']!=""){
							$name=$name.' '.$result2['Patient']['middle_name'];
						}
						if($result2['Patient']['last_name']!=""){
							$name=$name.' '.$result2['Patient']['last_name'];
						}
						$fail['patient_name']=$name; 
						$fail['message']=$errors[array_keys($errors)[0]][0];
						$faild_data[]=$fail; 
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				/*echo json_encode($response);
				exit();*/
				$response['failed_data'] = $faild_data;
				echo json_encode($response);
				exit;
			}
		}
	}
	/*Save single report PUP Create new API by MAdan 24-11-2022*/
	/*Multiple upload report faild data 04-11-2022*/
	public function PupTestControllerData_V4()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$this->loadModel('PupTest');
				$this->loadModel('PupPointdata');
				$response = array();
				$faildRequest = array();
				$resultData = $saved_data = $faild_data = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'PupTestControllerData_V4';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "PupTestControllerData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['PupTest']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
					$data['PupTest']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['PupTest']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['PupTest']['staff_name'] = isset($data_arrays['staff_name']) ? $data_arrays['staff_name'] : '';
					$data['PupTest']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['PupTest']['patient_name'] = isset($data_arrays['patient_name']) ? $data_arrays['patient_name'] : '';
					$data['PupTest']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : 'VT';
					$data['PupTest']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['PupTest']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['PupTest']['device_id'] = isset($data_arrays['device_id']) ? $data_arrays['device_id'] : 0;
					$data['PupTest']['office_id'] = isset($data_arrays['office_id']) ? $data_arrays['office_id'] : 0;
					$data['PupTest']['age_group'] = isset($data_arrays['age_group']) ? $data_arrays['age_group'] : 1;
					$data['PupTest']['version'] = isset($data_arrays['version']) ? $data_arrays['version'] : '1.0';
					$result = $this->PupTest->save($data);
					if ($result) {
						$saved_data[]['id'] = $this->PupTest->id;
					}else{
					  	$fail=array(); 
							$errors = $this->PupTest->validationErrors;
							$response['message']='Some error occured in updating report.';
							$result2 = $this->PupTest->find('first', array('conditions' => array('PupTest.unique_id' => $data_arrays['unique_id'])));
							$response['result']=0;
							$fail['id']=$result2['PupTest']['id']; 
							$fail['unique_id']=$resultData['unique_id'];
							$name=$name=$result2['Patient']['first_name'];
							if($result2['Patient']['middle_name']!=""){
								$name=$name.' '.$result2['Patient']['middle_name'];
							}
							if($result2['Patient']['last_name']!=""){
								$name=$name.' '.$result2['Patient']['last_name'];
							}
							$fail['patient_name']=$name; 
							$fail['message']=$errors[array_keys($errors)[0]][0];
							$faild_data[]=$fail; 
						} 
					if ($result) {
						$lastId = $this->PupTest->id;
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						$this->ReportRequestBackup->save($result2);
						if (!empty($data_arrays['file'])) {
							$response['pdf'] = WWW_BASE . 'app/webroot/PupTestControllerData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						foreach ($data_arrays['pupPointData'] as $pdatas) {
							$pdata['PupPointdata']['pup_test_id'] = $lastId;
							$pdata['PupPointdata']['time'] = isset($pdatas['time']) ? $pdatas['time'] : 0;
							$pdata['PupPointdata']['pupilDiam_OS'] = isset($pdatas['pupilDiam_OS']) ? $pdatas['pupilDiam_OS'] : 0.00;
							$pdata['PupPointdata']['pupilDiam_OD'] = isset($pdatas['pupilDiam_OD']) ? $pdatas['pupilDiam_OD'] : 0;
							$pdata['PupPointdata']['testState'] = isset($pdatas['testState']) ? $pdatas['testState'] : 0;
							$this->PupPointdata->create();
							$result_p = $this->PupPointdata->save($pdata);
						}
						$response['data'] = $saved_data;
						$response['message'] = 'Success.';
						$response['result'] = 1;
					} else {
						$response['message'] = $this->getFirstError($this->PupTest->validationErrors);
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				$response['failed_data'] = $faild_data;
				echo json_encode($response);
				exit();
			}
		}
	}
	/*Multiple upload report faild data 04-11-2022*/
	/*Save multiple PUP report Craete Api by Madan 24-11-2022*/
	public function saveMultiplePUPReport_v6()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$this->loadModel('PupTest');
				$this->loadModel('PupPointdata');
				$response = array();
				$faildRequest = array();
				$resultData = $saved_data = $faild_data = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'saveMultiplePUPReport_v6';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "PupTestControllerData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['PupTest']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
					$data['PupTest']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['PupTest']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['PupTest']['staff_name'] = isset($data_arrays['staff_name']) ? $data_arrays['staff_name'] : '';
					$data['PupTest']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['PupTest']['patient_name'] = isset($data_arrays['patient_name']) ? $data_arrays['patient_name'] : '';
					$data['PupTest']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : 'VT';
					$data['PupTest']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['PupTest']['created_date_utc'] = $data_arrays['created_date_utc'] ;
					$data['PupTest']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['PupTest']['device_id'] = isset($data_arrays['device_id']) ? $data_arrays['device_id'] : 0;
					$data['PupTest']['office_id'] = isset($data_arrays['office_id']) ? $data_arrays['office_id'] : 0;
					$data['PupTest']['age_group'] = isset($data_arrays['age_group']) ? $data_arrays['age_group'] : 1;
					$data['PupTest']['version'] = isset($data_arrays['version']) ? $data_arrays['version'] : '1.0';
					$result = $this->PupTest->save($data);
					if ($result) {
						$saved_data[]['id'] = $this->PupTest->id;
					}else{
					  	$fail=array(); 
							$errors = $this->PupTest->validationErrors;
							$response['message']='Some error occured in updating report.';
							$result2 = $this->PupTest->find('first', array('conditions' => array('PupTest.unique_id' => $data_arrays['unique_id'])));
							$response['result']=0;
							$fail['id']=$result2['PupTest']['id']; 
							$fail['unique_id']=$data_arrays['unique_id'];
							$name=$name=$result2['Patient']['first_name'];
							if($result2['Patient']['middle_name']!=""){
								$name=$name.' '.$result2['Patient']['middle_name'];
							}
							if($result2['Patient']['last_name']!=""){
								$name=$name.' '.$result2['Patient']['last_name'];
							}
							$fail['patient_name']=$name; 
							$fail['message']=$errors[array_keys($errors)[0]][0];
							$faild_data[]=$fail; 
						} 
					if ($result) {
						$lastId = $this->PupTest->id;
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						$this->ReportRequestBackup->save($result2);
						if (!empty($data_arrays['file'])) {
							$response['pdf'] = WWW_BASE . 'app/webroot/PupTestControllerData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						foreach ($data_arrays['pupPointData'] as $pdatas) {
							$pdata['PupPointdata']['pup_test_id'] = $lastId;
							$pdata['PupPointdata']['time'] = isset($pdatas['time']) ? $pdatas['time'] : 0;
							$pdata['PupPointdata']['pupilDiam_OS'] = isset($pdatas['pupilDiam_OS']) ? $pdatas['pupilDiam_OS'] : 0.00;
							$pdata['PupPointdata']['pupilDiam_OD'] = isset($pdatas['pupilDiam_OD']) ? $pdatas['pupilDiam_OD'] : 0;
							$pdata['PupPointdata']['testState'] = isset($pdatas['testState']) ? $pdatas['testState'] : 0;
							$this->PupPointdata->create();
							$result_p = $this->PupPointdata->save($pdata);
						}
						$response['data'] = $saved_data;
						$response['message'] = 'Success.';
						$response['result'] = 1;
					} else {
						$response['message'] = $this->getFirstError($this->PupTest->validationErrors);
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				$response['failed_data'] = $faild_data;
				echo json_encode($response);
				exit();
			}
		}
	}
	/*Save multiple PUP report Craete Api by Madan 24-11-2022*/
	public function get_pup_report()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				//pr($data_arrays);die;
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = 100000;
					} else {
						$limit = 10;
					}
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id'], 'User.user_type' => array('Staffuser', 'Subadmin')), 'fields' => array('User.office_id')));
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id'], 'User.user_type' => array('Staffuser', 'Subadmin')), 'fields' => array('User.id')));
					$condition['PupTest.staff_id'] = $all_staff_ids;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['PupTest.patient_id'] = $data_arrays['patient_id'];
					}
					if (isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["PupTest.patient_name LIKE"] = '%' . $data_arrays['patient_name'] . "%";
					}
					if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['PupTest.created >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}
					$this->loadModel('PupTest');
					$this->loadModel('User');
					$results = $this->PupTest->find('all', array(
						'conditions' => $condition,
						'order' => array('PupTest.id DESC'),
						'limit' => $limit,
						'page' => $data_arrays['page'],
					));
					$nextPageData = 0;
					if($data_arrays['page']  != 0 ){
						$nextPageData = $this->PupTest->find('count', array(
							'conditions' => $condition,
							'order' => array('PupTest.id DESC'),
						));
						$nextPageData = $nextPageData ? 1 : 0;
					}
					$last_sync_time = "";
					$i = 0;
					if(!empty($results)) {
						$last_sync_time = $results[0]['PupTest']['created'];
						foreach ($results as $key => $result) { //pr($result); die;
							$data[$i] = $result['PupTest'];
							$data[$i]['test_id'] = $result['PupTest']['id'];
							$data[$i]['unique_id'] = $result['PupTest']['unique_id'];
							$data[$i]['staff_name'] = @$result['User']['complete_name'];
							$data[$i]['created_date'] = ($result['PupTest']['created'] != null) ? ($result['PupTest']['created']) : '';
							$data[$i]['patient_name'] = $result['Patient']['first_name'].' '.$result['Patient']['middle_name'].' '.$result['Patient']['last_name'];
							if (!empty($result['PupTest']['file'])) {
								$data[$i]['pdf'] = WWW_BASE . 'app/webroot/PupTestControllerData/' . $result['PupTest']['file'];
							} else {
								$data[$i]['pdf'] = '';
							}
							$data[$i]['patient_id'] = isset($result['PupTest']['patient_id']) ? ($result['PupTest']['patient_id']) : '';
							unset($data[$i]['id']);
							unset($data[$i]['created']);
							unset($data[$i]['file']);
							$i++;
						}
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['result'] = 1;
							$response['more_data'] = $nextPageData;
							$response['last_sync_time'] = $last_sync_time;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $nextPageData;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}
	/*Get PUP test report V6 create new Api by Madan 24-11-2022*/
	public function get_pup_report_v6()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = 100000;
					} else {
						$limit = 10;
					}
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id'], 'User.user_type' => array('Staffuser', 'Subadmin')), 'fields' => array('User.office_id')));
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id'], 'User.user_type' => array('Staffuser', 'Subadmin')), 'fields' => array('User.id')));
					$condition['PupTest.staff_id'] = $all_staff_ids;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['PupTest.patient_id'] = $data_arrays['patient_id'];
					}
					if (isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["PupTest.patient_name LIKE"] = '%' . $data_arrays['patient_name'] . "%";
					}
					if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['PupTest.created_date_utc >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}
					$this->loadModel('PupTest');
					$this->loadModel('User');
					$results = $this->PupTest->find('all', array(
						'conditions' => $condition,
						'order' => array('PupTest.id DESC'),
						'limit' => $limit,
						'page' => $data_arrays['page'],
					));
					$nextPageData = 0;
					if($data_arrays['page']  != 0 ){
						$nextPageData = $this->PupTest->find('count', array(
							'conditions' => $condition,
							'order' => array('PupTest.id DESC'),
						));
						$nextPageData = $nextPageData ? 1 : 0;
					}
					$last_sync_time = "";
					$i = 0;
					if(!empty($results)) {
						$last_sync_time = $results[0]['PupTest']['created_date_utc'];
						foreach ($results as $key => $result) { //pr($result); die;
							$data[$i] = $result['PupTest'];
							$data[$i]['test_id'] = $result['PupTest']['id'];
							$data[$i]['unique_id'] = $result['PupTest']['unique_id'];
							$data[$i]['staff_name'] = @$result['User']['complete_name'];
							$data[$i]['created_date'] = ($result['PupTest']['created'] != null) ? ($result['PupTest']['created']) : '';
							$data[$i]['patient_name'] = $result['Patient']['first_name'].' '.$result['Patient']['middle_name'].' '.$result['Patient']['last_name'];
							if (!empty($result['PupTest']['file'])) {
								$data[$i]['pdf'] = WWW_BASE . 'app/webroot/PupTestControllerData/' . $result['PupTest']['file'];
							} else {
								$data[$i]['pdf'] = '';
							}
							$data[$i]['patient_id'] = isset($result['PupTest']['patient_id']) ? ($result['PupTest']['patient_id']) : '';
							unset($data[$i]['id']);
							unset($data[$i]['created']);
							unset($data[$i]['file']);
							$i++;
						}
						if($data_arrays['sync_start_time'] == ''){
							date_default_timezone_set('UTC');
            				$UTCDate = date('Y-m-d H:i:s');
							$last_sync_time = $UTCDate;
						}
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['result'] = 1;
							$response['more_data'] = $nextPageData;
							$response['last_sync_time'] = $last_sync_time;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $nextPageData;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}
	/*Get PUP test report V6 create new Api by Madan 24-11-2022*/

	/******************************PupTestControllerData END *********************************/
	public function StbTestControllerData()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'StbTestData';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				$data_arrays['test_report_id'] = 1;
				//pr($data_arrays);die;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "uploads/stbData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['StbTest']['test_id'] = isset($data_arrays['test_id']) ? $data_arrays['test_id'] : '';
					$data['StbTest']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
					$data['StbTest']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['StbTest']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['StbTest']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['StbTest']['master_key'] = isset($data_arrays['master_key']) ? $data_arrays['master_key'] : 0;
					$data['StbTest']['eye_select'] = isset($data_arrays['eye_select']) ? $data_arrays['eye_select'] : '';
					if ($data['StbTest']['eye_select'] == 'OU') {
						$data['StbTest']['eye_select'] = 2;
					}
					$data['StbTest']['test_type_id'] = isset($data_arrays['test_type_id']) ? $data_arrays['test_type_id'] : '';
					$data['StbTest']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : '';
					$data['StbTest']['patient_name'] = isset($data_arrays['patient_name']) ? $data_arrays['patient_name'] : '';
					$data['StbTest']['age_group'] = isset($data_arrays['age_group']) ? $data_arrays['age_group'] : 0;
					$data['StbTest']['device_id'] = isset($data_arrays['device_id']) ? $data_arrays['device_id'] : '';
					$data['StbTest']['office_id'] = isset($data_arrays['office_id']) ? $data_arrays['office_id'] : '';
					$data['StbTest']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['StbTest']['latitude'] = @$data_arrays['latitude'];
					$data['StbTest']['longitude'] = @$data_arrays['longitude'];
					$data['StbTest']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['StbTest']['version'] = @$data_arrays['version'];
					$data['StbTest']['diagnosys'] = @$data_arrays['diagnosys'];
					$data['StbTest']['stereopsis'] = @$data_arrays['Stereopsis'];
					$count_baseline = $this->StbTest->find('count', array(
						'conditions' => array(
							'test_name' => $data['StbTest']['test_name'],
							'eye_select' => $data['StbTest']['eye_select'], 'patient_id' => $data['StbTest']['patient_id'], 'StbTest.baseline' => '1'
						)
					));
					if ($count_baseline < 2) {
						$data['StbTest']['baseline'] = 1;
					}
					//pr($count_baseline);die;
					//pr($data); die;
					$result = $this->StbTest->save($data);
					$lastId = $this->StbTest->id;
					$lastFile = $this->StbTest->file;
					if ($result) {
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						if ($this->ReportRequestBackup->save($result2)) {
						}
						if (!empty($data_arrays['pdf'])) {
							$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
							$foldername = "uploads/stbdata";
							$imgstring = $data_arrays['pdf'];
							$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
							$response['pdf'] = WWW_BASE . 'uploads/stbdata/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						// if (!empty($data_arrays['file'])) {
						//     $response['pdf'] = WWW_BASE . 'stbData/' . $data_arrays['file'];
						//     $response['new_id'] = $lastId;
						// } else {
						//     $response['pdf'] = '';
						// }
						if (isset($data_arrays['stbPointData'])) {
							foreach ($data_arrays['stbPointData'] as $pdatas) {
								$pdata = array();
								$pdata['StbPointdata']['report_id'] = @$data_arrays['test_report_id'];
								$pdata['StbPointdata']['stb_data_id'] = @$lastId;
								$pdata['StbPointdata']['x'] = isset($pdatas['x']) ? $pdatas['x'] : 0.00;
								$pdata['StbPointdata']['y'] = isset($pdatas['y']) ? $pdatas['y'] : 0.00;
								$pdata['StbPointdata']['z'] = isset($pdatas['z']) ? $pdatas['z'] : 0.00;
								$pdata['StbPointdata']['eye'] = isset($pdatas['eye']) ? $pdatas['eye'] : 0;
								$pdata['StbPointdata']['testState'] = isset($pdatas['testState']) ? $pdatas['testState'] : 0;
								$pdata['StbPointdata']['locationX'] = isset($pdatas['locationX']) ? $pdatas['locationX'] : 0.00;
								$pdata['StbPointdata']['locationY'] = isset($pdatas['locationY']) ? $pdatas['locationY'] : 0.00;
								$pdata['StbPointdata']['locationZ'] = isset($pdatas['locationZ']) ? $pdatas['locationZ'] : 0.00;
								$pdata['StbPointdata']['TargetSize'] = isset($pdatas['TargetSize']) ? $pdatas['TargetSize'] : 0.00;
								$pdata['StbPointdata']['intensity'] = isset($pdatas['intensity']) ? $pdatas['intensity'] : 0.00;;
								$pdata['StbPointdata']['size'] = isset($pdatas['size']) ? $pdatas['size'] : 0;
								$pdata['StbPointdata']['zPD'] = isset($pdatas['zPD']) ? $pdatas['zPD'] : '';
								$pdata['StbPointdata']['STD'] = isset($pdatas['STD']) ? $pdatas['STD'] : 0.00;
								$pdata['StbPointdata']['index'] = isset($pdatas['index']) ? $pdatas['index'] : 0.00;
								$pdata['StbPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
								$this->StbPointdata->create();
								$result_p = $this->StbPointdata->save($pdata);
							}
						}
						$response['message'] = 'Success.';
						$response['result'] = 1;
						CakeLog::write('info', "Test Device Message file upload : VF|VF_FILE_UPLOADED|" . $lastId);
						$data_device_message['DeviceMessage']['office_id'] = $data_arrays['office_id'];
						$data_device_message['DeviceMessage']['device_id'] = $data_arrays['device_id'];
						$data_device_message['DeviceMessage']['message'] = 'VF|VF_FILE_UPLOADED|' . $lastId;
						$data_device_message['DeviceMessage']['craeted_at'] = date("Y-m-d H:i:s");
						$data_device_message['DeviceMessage']['updated_at'] = date("Y-m-d H:i:s");
						$this->DeviceMessage->save($data_device_message);
						//update credits------
						$this->loadModel('User');
						$this->User->id = $data['StbTest']['staff_id'];
						$credits = $this->User->field('credits');
						$new_credit = $credits - 1;
						$this->User->updateAll(array('User.credits' => $new_credit), array('User.id' => $data['StbTest']['staff_id']));
					} else {
						$response['message'] = 'Some error occured in updating report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}

	/*Save STB single report create new api by Madan 24-11-2022*/
	public function saveSTBReport_v6()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'saveSTBReport_v6';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				$data_arrays['test_report_id'] = 1;
				//pr($data_arrays);die;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "uploads/stbData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['StbTest']['test_id'] = isset($data_arrays['test_id']) ? $data_arrays['test_id'] : '';
					$data['StbTest']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
					$data['StbTest']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['StbTest']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['StbTest']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['StbTest']['master_key'] = isset($data_arrays['master_key']) ? $data_arrays['master_key'] : 0;
					$data['StbTest']['eye_select'] = isset($data_arrays['eye_select']) ? $data_arrays['eye_select'] : '';
					if ($data['StbTest']['eye_select'] == 'OU') {
						$data['StbTest']['eye_select'] = 2;
					}
					$data['StbTest']['test_type_id'] = isset($data_arrays['test_type_id']) ? $data_arrays['test_type_id'] : '';
					$data['StbTest']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : '';
					$data['StbTest']['patient_name'] = isset($data_arrays['patient_name']) ? $data_arrays['patient_name'] : '';
					$data['StbTest']['age_group'] = isset($data_arrays['age_group']) ? $data_arrays['age_group'] : 0;
					$data['StbTest']['device_id'] = isset($data_arrays['device_id']) ? $data_arrays['device_id'] : '';
					$data['StbTest']['office_id'] = isset($data_arrays['office_id']) ? $data_arrays['office_id'] : '';
					$data['StbTest']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['StbTest']['created_date_utc'] = $data_arrays['created_date_utc'];
					$data['StbTest']['latitude'] = @$data_arrays['latitude'];
					$data['StbTest']['longitude'] = @$data_arrays['longitude'];
					$data['StbTest']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['StbTest']['version'] = @$data_arrays['version'];
					$data['StbTest']['diagnosys'] = @$data_arrays['diagnosys'];
					$data['StbTest']['stereopsis'] = @$data_arrays['Stereopsis'];
					$count_baseline = $this->StbTest->find('count', array(
						'conditions' => array(
							'test_name' => $data['StbTest']['test_name'],
							'eye_select' => $data['StbTest']['eye_select'], 'patient_id' => $data['StbTest']['patient_id'], 'StbTest.baseline' => '1'
						)
					));
					if ($count_baseline < 2) {
						$data['StbTest']['baseline'] = 1;
					}
					//pr($count_baseline);die;
					//pr($data); die;
					$result = $this->StbTest->save($data);
					$lastId = $this->StbTest->id;
					$lastFile = $this->StbTest->file;
					if ($result) {
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						if ($this->ReportRequestBackup->save($result2)) {
						}
						if (!empty($data_arrays['pdf'])) {
							$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
							$foldername = "uploads/stbdata";
							$imgstring = $data_arrays['pdf'];
							$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
							$response['pdf'] = WWW_BASE . 'uploads/stbdata/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						// if (!empty($data_arrays['file'])) {
						//     $response['pdf'] = WWW_BASE . 'stbData/' . $data_arrays['file'];
						//     $response['new_id'] = $lastId;
						// } else {
						//     $response['pdf'] = '';
						// }
						if (isset($data_arrays['stbPointData'])) {
							foreach ($data_arrays['stbPointData'] as $pdatas) {
								$pdata = array();
								$pdata['StbPointdata']['report_id'] = @$data_arrays['test_report_id'];
								$pdata['StbPointdata']['stb_data_id'] = @$lastId;
								$pdata['StbPointdata']['x'] = isset($pdatas['x']) ? $pdatas['x'] : 0.00;
								$pdata['StbPointdata']['y'] = isset($pdatas['y']) ? $pdatas['y'] : 0.00;
								$pdata['StbPointdata']['z'] = isset($pdatas['z']) ? $pdatas['z'] : 0.00;
								$pdata['StbPointdata']['eye'] = isset($pdatas['eye']) ? $pdatas['eye'] : 0;
								$pdata['StbPointdata']['testState'] = isset($pdatas['testState']) ? $pdatas['testState'] : 0;
								$pdata['StbPointdata']['locationX'] = isset($pdatas['locationX']) ? $pdatas['locationX'] : 0.00;
								$pdata['StbPointdata']['locationY'] = isset($pdatas['locationY']) ? $pdatas['locationY'] : 0.00;
								$pdata['StbPointdata']['locationZ'] = isset($pdatas['locationZ']) ? $pdatas['locationZ'] : 0.00;
								$pdata['StbPointdata']['TargetSize'] = isset($pdatas['TargetSize']) ? $pdatas['TargetSize'] : 0.00;
								$pdata['StbPointdata']['intensity'] = isset($pdatas['intensity']) ? $pdatas['intensity'] : 0.00;;
								$pdata['StbPointdata']['size'] = isset($pdatas['size']) ? $pdatas['size'] : 0;
								$pdata['StbPointdata']['zPD'] = isset($pdatas['zPD']) ? $pdatas['zPD'] : '';
								$pdata['StbPointdata']['STD'] = isset($pdatas['STD']) ? $pdatas['STD'] : 0.00;
								$pdata['StbPointdata']['index'] = isset($pdatas['index']) ? $pdatas['index'] : 0.00;
								$pdata['StbPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
								
								$this->StbPointdata->create();
								$result_p = $this->StbPointdata->save($pdata);
							}
						}
						$response['message'] = 'Success.';
						$response['result'] = 1;
						CakeLog::write('info', "Test Device Message file upload : VF|VF_FILE_UPLOADED|" . $lastId);
						$data_device_message['DeviceMessage']['office_id'] = $data_arrays['office_id'];
						$data_device_message['DeviceMessage']['device_id'] = $data_arrays['device_id'];
						$data_device_message['DeviceMessage']['message'] = 'VF|VF_FILE_UPLOADED|' . $lastId;
						$data_device_message['DeviceMessage']['craeted_at'] = date("Y-m-d H:i:s");
						$data_device_message['DeviceMessage']['updated_at'] = date("Y-m-d H:i:s");
						$this->DeviceMessage->save($data_device_message);
						//update credits------
						$this->loadModel('User');
						$this->User->id = $data['StbTest']['staff_id'];
						$credits = $this->User->field('credits');
						$new_credit = $credits - 1;
						$this->User->updateAll(array('User.credits' => $new_credit), array('User.id' => $data['StbTest']['staff_id']));
					} else {
						$response['message'] = 'Some error occured in updating report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*Save STB single report create new api by Madan 24-11-2022*/

	public function StbTestControllerDataAll()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays_all = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'StbTestDataAll';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				//pr($data_arrays);die;
				if (!empty($data_arrays_all['data'])) {
					foreach ($data_arrays_all['data'] as $key => $data_arrays) {
						$data_arrays['test_report_id'] = 1;
						if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
							if (!empty($data_arrays['pdf'])) {
								$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
								$foldername = "uploads/stbdata";
								$imgstring = $data_arrays['pdf'];
								$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
							}
							$data['StbTest']['test_id'] = isset($data_arrays['test_id']) ? $data_arrays['test_id'] : '';
							$data['StbTest']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
							$data['StbTest']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
							$data['StbTest']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
							$data['StbTest']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
							$data['StbTest']['master_key'] = isset($data_arrays['master_key']) ? $data_arrays['master_key'] : '';
							$data['StbTest']['eye_select'] = isset($data_arrays['eye_select']) ? $data_arrays['eye_select'] : '';
							if ($data['StbTest']['eye_select'] == 'OU') {
								$data['StbTest']['eye_select'] = 2;
							}
							$data['StbTest']['test_type_id'] = isset($data_arrays['test_type_id']) ? $data_arrays['test_type_id'] : '';
							$data['StbTest']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : '';
							$data['StbTest']['patient_name'] = isset($data_arrays['patient_name']) ? $data_arrays['patient_name'] : '';
							$data['StbTest']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
							$data['StbTest']['latitude'] = @$data_arrays['latitude'];
							$data['StbTest']['longitude'] = @$data_arrays['longitude'];
							$data['StbTest']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
							$data['StbTest']['version'] = @$data_arrays['version'];
							$data['StbTest']['diagnosys'] = @$data_arrays['diagnosys'];
							$data['StbTest']['stereopsis'] = @$data_arrays['Stereopsis'];
							$data['StbTest']['age_group'] = isset($data_arrays['age_group']) ? $data_arrays['age_group'] : 0;
							$data['StbTest']['device_id'] = isset($data_arrays['device_id']) ? $data_arrays['device_id'] : '';
							$data['StbTest']['office_id'] = isset($data_arrays['office_id']) ? $data_arrays['office_id'] : '';
							$count_baseline = $this->StbTest->find('count', array(
								'conditions' => array(
									'test_name' => $data['StbTest']['test_name'],
									'eye_select' => $data['StbTest']['eye_select'], 'patient_id' => $data['StbTest']['patient_id'], 'StbTest.baseline' => '1'
								)
							));
							if ($count_baseline < 2) {
								$data['StbTest']['baseline'] = 1;
							}
							$this->StbTest->create();
							$result = $this->StbTest->save($data);
							$lastId = $this->StbTest->id;
							$lastFile = $this->StbTest->file;
							if ($result) {
								$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
								$result2['ReportRequestBackup']['status'] = 1;
								if ($this->ReportRequestBackup->save($result2)) {
								}
								if (!empty($data_arrays['file'])) {
									$response['pdf'] = WWW_BASE . 'uploads/stbdata/' . $data_arrays['file'];
									$response['new_id'] = $lastId;
								} else {
									$response['pdf'] = '';
								}
								$pdata = "";
								if (isset($data_arrays['stbPointData'])) {
									foreach ($data_arrays['stbPointData'] as $pdatas) {
										$pdata = array();
										$pdata['StbPointdata']['report_id'] = @$data_arrays['test_report_id'];
										$pdata['StbPointdata']['stb_data_id'] = @$lastId;
										$pdata['StbPointdata']['x'] = isset($pdatas['x']) ? $pdatas['x'] : 0.00;
										$pdata['StbPointdata']['y'] = isset($pdatas['y']) ? $pdatas['y'] : 0.00;
										$pdata['StbPointdata']['z'] = isset($pdatas['z']) ? $pdatas['z'] : 0.00;
										$pdata['StbPointdata']['eye'] = isset($pdatas['eye']) ? $pdatas['eye'] : 0;
										$pdata['StbPointdata']['testState'] = isset($pdatas['testState']) ? $pdatas['testState'] : 0;
										$pdata['StbPointdata']['locationX'] = isset($pdatas['locationX']) ? $pdatas['locationX'] : 0.00;
										$pdata['StbPointdata']['locationY'] = isset($pdatas['locationY']) ? $pdatas['locationY'] : 0.00;
										$pdata['StbPointdata']['locationZ'] = isset($pdatas['locationZ']) ? $pdatas['locationZ'] : 0.00;
										$pdata['StbPointdata']['TargetSize'] = isset($pdatas['TargetSize']) ? $pdatas['TargetSize'] : 0.00;
										$pdata['StbPointdata']['intensity'] = isset($pdatas['intensity']) ? $pdatas['intensity'] : 0.00;;
										$pdata['StbPointdata']['size'] = isset($pdatas['size']) ? $pdatas['size'] : 0;
										$pdata['StbPointdata']['zPD'] = isset($pdatas['zPD']) ? $pdatas['zPD'] : '';
										$pdata['StbPointdata']['STD'] = isset($pdatas['STD']) ? $pdatas['STD'] : 0.00;
										$pdata['StbPointdata']['index'] = isset($pdatas['index']) ? $pdatas['index'] : 0.00;
										$pdata['StbPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
										$this->StbPointdata->create();
										$result_p = $this->StbPointdata->save($pdata);
									}
								}
								$response['message'] = 'Success.';
								$response['result'] = 1;
								CakeLog::write('info', "Test Device Message file upload : VF|VF_FILE_UPLOADED|" . $lastId);
								$data_device_message['DeviceMessage']['office_id'] = $data_arrays['office_id'];
								$data_device_message['DeviceMessage']['device_id'] = $data_arrays['device_id'];
								$data_device_message['DeviceMessage']['message'] = 'VF|VF_FILE_UPLOADED|' . $lastId;
								$data_device_message['DeviceMessage']['craeted_at'] = date("Y-m-d H:i:s");
								$data_device_message['DeviceMessage']['updated_at'] = date("Y-m-d H:i:s");
								$this->DeviceMessage->save($data_device_message);
								$this->loadModel('User');
								$this->User->id = $data['StbTest']['staff_id'];
								$credits = $this->User->field('credits');
								$new_credit = $credits - 1;
								$this->User->updateAll(array('User.credits' => $new_credit), array('User.id' => $data['StbTest']['staff_id']));
							} else {
								$response['message'] = 'Some error occured in updating report.';
								$response['result'] = 0;
							}
						} else {
							$response['message'] = 'Patient id can\'t be empty.';
							$response['result'] = 0;
						}
					}
				} else {
					$response['message'] = 'Test Data can\'t be empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}

	/*Multiple upload report faild data 04-11-2022*/
	public function StbTestControllerDataAll_V4()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$faildRequest = array();
				$resultData = $saved_data = $faild_data = array();
				$data_arrays_all = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'StbTestDataAll';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				//pr($data_arrays);die;
				if (!empty($data_arrays_all['data'])) {
					foreach ($data_arrays_all['data'] as $key => $data_arrays) {
						$data_arrays['test_report_id'] = 1;
						if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
							if (!empty($data_arrays['pdf'])) {
								$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
								$foldername = "uploads/stbdata";
								$imgstring = $data_arrays['pdf'];
								$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
							}
							$data['StbTest']['test_id'] = isset($data_arrays['test_id']) ? $data_arrays['test_id'] : '';
							$data['StbTest']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
							$data['StbTest']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
							$data['StbTest']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
							$data['StbTest']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
							$data['StbTest']['master_key'] = isset($data_arrays['master_key']) ? $data_arrays['master_key'] : '';
							$data['StbTest']['eye_select'] = isset($data_arrays['eye_select']) ? $data_arrays['eye_select'] : '';
							if ($data['StbTest']['eye_select'] == 'OU') {
								$data['StbTest']['eye_select'] = 2;
							}
							$data['StbTest']['test_type_id'] = isset($data_arrays['test_type_id']) ? $data_arrays['test_type_id'] : '';
							$data['StbTest']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : '';
							$data['StbTest']['patient_name'] = isset($data_arrays['patient_name']) ? $data_arrays['patient_name'] : '';
							$data['StbTest']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
							$data['StbTest']['latitude'] = @$data_arrays['latitude'];
							$data['StbTest']['longitude'] = @$data_arrays['longitude'];
							$data['StbTest']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
							$data['StbTest']['version'] = @$data_arrays['version'];
							$data['StbTest']['diagnosys'] = @$data_arrays['diagnosys'];
							$data['StbTest']['stereopsis'] = @$data_arrays['Stereopsis'];
							$data['StbTest']['age_group'] = isset($data_arrays['age_group']) ? $data_arrays['age_group'] : 0;
							$data['StbTest']['device_id'] = isset($data_arrays['device_id']) ? $data_arrays['device_id'] : '';
							$data['StbTest']['office_id'] = isset($data_arrays['office_id']) ? $data_arrays['office_id'] : '';
							$count_baseline = $this->StbTest->find('count', array(
								'conditions' => array(
									'test_name' => $data['StbTest']['test_name'],
									'eye_select' => $data['StbTest']['eye_select'], 'patient_id' => $data['StbTest']['patient_id'], 'StbTest.baseline' => '1'
								)
							));
							if ($count_baseline < 2) {
								$data['StbTest']['baseline'] = 1;
							}
							$this->StbTest->create();
							$result = $this->StbTest->save($data);
							if ($result) {
								$saved_data[]['id'] = $this->StbTest->id;
							}else{
							  	$fail=array(); 
									$errors = $this->StbTest->validationErrors;
									$response['message']='Some error occured in updating report.';
									$result2 = $this->StbTest->find('first', array('conditions' => array('StbTest.unique_id' => $data_arrays['unique_id'])));
									$response['result']=0;
									$fail['id']=$result2['StbTest']['id']; 
									$fail['unique_id']=$resultData['unique_id'];
									$name=$name=$result2['Patient']['first_name'];
									if($result2['Patient']['middle_name']!=""){
										$name=$name.' '.$result2['Patient']['middle_name'];
									}
									if($result2['Patient']['last_name']!=""){
										$name=$name.' '.$result2['Patient']['last_name'];
									}
									$fail['patient_name']=$name; 
									$fail['message']=$errors[array_keys($errors)[0]][0];
									$faild_data[]=$fail; 
								} 
							$lastId = $this->StbTest->id;
							$lastFile = $this->StbTest->file;
							if ($result) {
								$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
								$result2['ReportRequestBackup']['status'] = 1;
								if ($this->ReportRequestBackup->save($result2)) {
								}
								if (!empty($data_arrays['file'])) {
									$response['pdf'] = WWW_BASE . 'uploads/stbdata/' . $data_arrays['file'];
									$response['new_id'] = $lastId;
								} else {
									$response['pdf'] = '';
								}
								$pdata = "";
								if (isset($data_arrays['stbPointData'])) {
									foreach ($data_arrays['stbPointData'] as $pdatas) {
										$pdata = array();
										$pdata['StbPointdata']['report_id'] = @$data_arrays['test_report_id'];
										$pdata['StbPointdata']['stb_data_id'] = @$lastId;
										$pdata['StbPointdata']['x'] = isset($pdatas['x']) ? $pdatas['x'] : 0.00;
										$pdata['StbPointdata']['y'] = isset($pdatas['y']) ? $pdatas['y'] : 0.00;
										$pdata['StbPointdata']['z'] = isset($pdatas['z']) ? $pdatas['z'] : 0.00;
										$pdata['StbPointdata']['eye'] = isset($pdatas['eye']) ? $pdatas['eye'] : 0;
										$pdata['StbPointdata']['testState'] = isset($pdatas['testState']) ? $pdatas['testState'] : 0;
										$pdata['StbPointdata']['locationX'] = isset($pdatas['locationX']) ? $pdatas['locationX'] : 0.00;
										$pdata['StbPointdata']['locationY'] = isset($pdatas['locationY']) ? $pdatas['locationY'] : 0.00;
										$pdata['StbPointdata']['locationZ'] = isset($pdatas['locationZ']) ? $pdatas['locationZ'] : 0.00;
										$pdata['StbPointdata']['TargetSize'] = isset($pdatas['TargetSize']) ? $pdatas['TargetSize'] : 0.00;
										$pdata['StbPointdata']['intensity'] = isset($pdatas['intensity']) ? $pdatas['intensity'] : 0.00;;
										$pdata['StbPointdata']['size'] = isset($pdatas['size']) ? $pdatas['size'] : 0;
										$pdata['StbPointdata']['zPD'] = isset($pdatas['zPD']) ? $pdatas['zPD'] : '';
										$pdata['StbPointdata']['STD'] = isset($pdatas['STD']) ? $pdatas['STD'] : 0.00;
										$pdata['StbPointdata']['index'] = isset($pdatas['index']) ? $pdatas['index'] : 0.00;
										$pdata['StbPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
										$this->StbPointdata->create();
										$result_p = $this->StbPointdata->save($pdata);
									}
								}
								$response['data'] = $saved_data;
								$response['message'] = 'Success.';
								$response['result'] = 1;
								CakeLog::write('info', "Test Device Message file upload : VF|VF_FILE_UPLOADED|" . $lastId);
								$data_device_message['DeviceMessage']['office_id'] = $data_arrays['office_id'];
								$data_device_message['DeviceMessage']['device_id'] = $data_arrays['device_id'];
								$data_device_message['DeviceMessage']['message'] = 'VF|VF_FILE_UPLOADED|' . $lastId;
								$data_device_message['DeviceMessage']['craeted_at'] = date("Y-m-d H:i:s");
								$data_device_message['DeviceMessage']['updated_at'] = date("Y-m-d H:i:s");
								$this->DeviceMessage->save($data_device_message);
								$this->loadModel('User');
								$this->User->id = $data['StbTest']['staff_id'];
								$credits = $this->User->field('credits');
								$new_credit = $credits - 1;
								$this->User->updateAll(array('User.credits' => $new_credit), array('User.id' => $data['StbTest']['staff_id']));
							} else {
								$response['message'] = 'Some error occured in updating report.';
								$response['result'] = 0;
							}
						} else {
							$response['message'] = 'Patient id can\'t be empty.';
							$response['result'] = 0;
						}
					}
				} else {
					$response['message'] = 'Test Data can\'t be empty.';
					$response['result'] = 0;
				}
				$response['failed_data'] = $faild_data;
				echo json_encode($response);
				exit();
			}
		}
	}

	/*Upload multiple STB report create new API v6 by Madan 24-11-2022*/
	public function saveMultipleSTBReport_v6()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$faildRequest = array();
				$resultData = $saved_data = $faild_data = array();
				$data_arrays_all = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'saveMultipleSTBReport_v6';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				//pr($data_arrays);die;
				if (!empty($data_arrays_all['data'])) {
					foreach ($data_arrays_all['data'] as $key => $data_arrays) {
						$data_arrays['test_report_id'] = 1;
						if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
							if (!empty($data_arrays['pdf'])) {
								$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
								$foldername = "uploads/stbdata";
								$imgstring = $data_arrays['pdf'];
								$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
							}
							$data['StbTest']['test_id'] = isset($data_arrays['test_id']) ? $data_arrays['test_id'] : '';
							$data['StbTest']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
							$data['StbTest']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
							$data['StbTest']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
							$data['StbTest']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
							$data['StbTest']['master_key'] = isset($data_arrays['master_key']) ? $data_arrays['master_key'] : '';
							$data['StbTest']['eye_select'] = isset($data_arrays['eye_select']) ? $data_arrays['eye_select'] : '';
							if ($data['StbTest']['eye_select'] == 'OU') {
								$data['StbTest']['eye_select'] = 2;
							}
							$data['StbTest']['test_type_id'] = isset($data_arrays['test_type_id']) ? $data_arrays['test_type_id'] : '';
							$data['StbTest']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : '';
							$data['StbTest']['patient_name'] = isset($data_arrays['patient_name']) ? $data_arrays['patient_name'] : '';
							$data['StbTest']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
							$data['StbTest']['created_date_utc'] = $data_arrays['created_date_utc'];
							$data['StbTest']['latitude'] = @$data_arrays['latitude'];
							$data['StbTest']['longitude'] = @$data_arrays['longitude'];
							$data['StbTest']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
							$data['StbTest']['version'] = @$data_arrays['version'];
							$data['StbTest']['diagnosys'] = @$data_arrays['diagnosys'];
							$data['StbTest']['stereopsis'] = @$data_arrays['Stereopsis'];
							$data['StbTest']['age_group'] = isset($data_arrays['age_group']) ? $data_arrays['age_group'] : 0;
							$data['StbTest']['device_id'] = isset($data_arrays['device_id']) ? $data_arrays['device_id'] : '';
							$data['StbTest']['office_id'] = isset($data_arrays['office_id']) ? $data_arrays['office_id'] : '';
							$count_baseline = $this->StbTest->find('count', array(
								'conditions' => array(
									'test_name' => $data['StbTest']['test_name'],
									'eye_select' => $data['StbTest']['eye_select'], 'patient_id' => $data['StbTest']['patient_id'], 'StbTest.baseline' => '1'
								)
							));
							if ($count_baseline < 2) {
								$data['StbTest']['baseline'] = 1;
							}
							$this->StbTest->create();
							$result = $this->StbTest->save($data);
							if ($result) {
								$saved_data[]['id'] = $this->StbTest->id;
							}else{
							  	$fail=array(); 
									$errors = $this->StbTest->validationErrors;
									$response['message']='Some error occured in updating report.';
									$result2 = $this->StbTest->find('first', array('conditions' => array('StbTest.unique_id' => $data_arrays['unique_id'])));
									$response['result']=0;
									$fail['id']=$result2['StbTest']['id']; 
									$fail['unique_id']=$data_arrays['unique_id'];
									$name=$name=$result2['Patient']['first_name'];
									if($result2['Patient']['middle_name']!=""){
										$name=$name.' '.$result2['Patient']['middle_name'];
									}
									if($result2['Patient']['last_name']!=""){
										$name=$name.' '.$result2['Patient']['last_name'];
									}
									$fail['patient_name']=$name; 
									$fail['message']=$errors[array_keys($errors)[0]][0];
									$faild_data[]=$fail; 
								} 
							$lastId = $this->StbTest->id;
							$lastFile = $this->StbTest->file;
							if ($result) {
								$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
								$result2['ReportRequestBackup']['status'] = 1;
								if ($this->ReportRequestBackup->save($result2)) {
								}
								if (!empty($data_arrays['file'])) {
									$response['pdf'] = WWW_BASE . 'uploads/stbdata/' . $data_arrays['file'];
									$response['new_id'] = $lastId;
								} else {
									$response['pdf'] = '';
								}
								$pdata = "";
								if (isset($data_arrays['stbPointData'])) {
									foreach ($data_arrays['stbPointData'] as $pdatas) {
										$pdata = array();
										$pdata['StbPointdata']['report_id'] = @$data_arrays['test_report_id'];
										$pdata['StbPointdata']['stb_data_id'] = @$lastId;
										$pdata['StbPointdata']['x'] = isset($pdatas['x']) ? $pdatas['x'] : 0.00;
										$pdata['StbPointdata']['y'] = isset($pdatas['y']) ? $pdatas['y'] : 0.00;
										$pdata['StbPointdata']['z'] = isset($pdatas['z']) ? $pdatas['z'] : 0.00;
										$pdata['StbPointdata']['eye'] = isset($pdatas['eye']) ? $pdatas['eye'] : 0;
										$pdata['StbPointdata']['testState'] = isset($pdatas['testState']) ? $pdatas['testState'] : 0;
										$pdata['StbPointdata']['locationX'] = isset($pdatas['locationX']) ? $pdatas['locationX'] : 0.00;
										$pdata['StbPointdata']['locationY'] = isset($pdatas['locationY']) ? $pdatas['locationY'] : 0.00;
										$pdata['StbPointdata']['locationZ'] = isset($pdatas['locationZ']) ? $pdatas['locationZ'] : 0.00;
										$pdata['StbPointdata']['TargetSize'] = isset($pdatas['TargetSize']) ? $pdatas['TargetSize'] : 0.00;
										$pdata['StbPointdata']['intensity'] = isset($pdatas['intensity']) ? $pdatas['intensity'] : 0.00;;
										$pdata['StbPointdata']['size'] = isset($pdatas['size']) ? $pdatas['size'] : 0;
										$pdata['StbPointdata']['zPD'] = isset($pdatas['zPD']) ? $pdatas['zPD'] : '';
										$pdata['StbPointdata']['STD'] = isset($pdatas['STD']) ? $pdatas['STD'] : 0.00;
										$pdata['StbPointdata']['index'] = isset($pdatas['index']) ? $pdatas['index'] : 0.00;
										$pdata['StbPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
										$this->StbPointdata->create();
										$result_p = $this->StbPointdata->save($pdata);
									}
								}
								$response['data'] = $saved_data;
								$response['message'] = 'Success.';
								$response['result'] = 1;
								CakeLog::write('info', "Test Device Message file upload : VF|VF_FILE_UPLOADED|" . $lastId);
								$data_device_message['DeviceMessage']['office_id'] = $data_arrays['office_id'];
								$data_device_message['DeviceMessage']['device_id'] = $data_arrays['device_id'];
								$data_device_message['DeviceMessage']['message'] = 'VF|VF_FILE_UPLOADED|' . $lastId;
								$data_device_message['DeviceMessage']['craeted_at'] = date("Y-m-d H:i:s");
								$data_device_message['DeviceMessage']['updated_at'] = date("Y-m-d H:i:s");
								$this->DeviceMessage->save($data_device_message);
								$this->loadModel('User');
								$this->User->id = $data['StbTest']['staff_id'];
								$credits = $this->User->field('credits');
								$new_credit = $credits - 1;
								$this->User->updateAll(array('User.credits' => $new_credit), array('User.id' => $data['StbTest']['staff_id']));
							} else {
								$response['message'] = 'Some error occured in updating report.';
								$response['result'] = 0;
							}
						} else {
							$response['message'] = 'Patient id can\'t be empty.';
							$response['result'] = 0;
						}
					}
				} else {
					$response['message'] = 'Test Data can\'t be empty.';
					$response['result'] = 0;
				}
				$response['failed_data'] = $faild_data;
				echo json_encode($response);
				exit();
			}
		}
	}
	/*Upload multiple STB report create new API v6 by Madan 24-11-2022*/

	/*Multiple upload report faild data 04-11-2022*/

	public function VSTestControllerDataAll()
	{
		$db = $this->ReportRequestBackup;
		$results = $db->query("SET session wait_timeout=28800", FALSE);
		$results = $db->query("SET session interactive_timeout=28800", FALSE);
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays_all = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'VSTestControllerDataAll';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				$data_arrays['test_report_id'] = 1;
				if (!empty($data_arrays_all['data'])) {
					foreach ($data_arrays_all['data'] as $key => $data_arrays) {
						if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
							if (!empty($data_arrays['pdf'])) {
								$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
								$foldername = "pointData";
								$imgstring = $data_arrays['pdf'];
								$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
							}
							$data['Pointdata']['test_id'] = isset($data_arrays['test_id']) ? $data_arrays['test_id'] : '';
							$data['Pointdata']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'S';
							$data['Pointdata']['numpoints'] = isset($data_arrays['numpoints']) ? $data_arrays['numpoints'] : '0';
							$data['Pointdata']['color'] = isset($data_arrays['color']) ? $data_arrays['color'] : '';
							$data['Pointdata']['backgroundcolor'] = isset($data_arrays['backgroundcolor']) ? $data_arrays['backgroundcolor'] : '';
							$data['Pointdata']['stmsize'] = isset($data_arrays['stmSize']) ? $data_arrays['stmSize'] : 0;
							$data['Pointdata']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
							$data['Pointdata']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
							$data['Pointdata']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
							$data['Pointdata']['master_key'] = isset($data_arrays['master_key']) ? $data_arrays['master_key'] : 0;
							if ($data['Pointdata']['master_key'] == "") {
								$data['Pointdata']['master_key'] = 0;
							}
							$data['Pointdata']['eye_select'] = isset($data_arrays['eye_select']) ? $data_arrays['eye_select'] : 1;
							if ($data['Pointdata']['eye_select'] == 'OU') {
								$data['Pointdata']['eye_select'] = 2;
							}
							$data['Pointdata']['test_type_id'] = isset($data_arrays['test_type_id']) ? $data_arrays['test_type_id'] : '';
							$data['Pointdata']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : '';
							$data['Pointdata']['mean_dev'] = @$data_arrays['mean_dev'];
							$data['Pointdata']['pattern_std'] = @$data_arrays['pattern_std'];
							$data['Pointdata']['mean_sen'] = @$data_arrays['mean_sen'];
							$data['Pointdata']['mean_def'] = @$data_arrays['mean_def'];
							$data['Pointdata']['pattern_std_hfa'] = @$data_arrays['pattern_std_hfa'];
							$data['Pointdata']['loss_var'] = @$data_arrays['loss_var'];
							$data['Pointdata']['mean_std'] = @$data_arrays['mean_std'];
							$data['Pointdata']['psd_hfa_2'] = @$data_arrays['psd_hfa_2'];
							$data['Pointdata']['psd_hfa'] = @$data_arrays['psd_hfa'];
							$data['Pointdata']['vission_loss'] = @$data_arrays['vission_loss'];
							$data['Pointdata']['false_p'] = @$data_arrays['false_p'];
							$data['Pointdata']['false_n'] = @$data_arrays['false_n'];
							$data['Pointdata']['false_f'] = @$data_arrays['false_f'];
							$data['Pointdata']['ght'] = @$data_arrays['ght'];
							$data['Pointdata']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
							$data['Pointdata']['threshold'] = @$data_arrays['threshold'];
							$data['Pointdata']['strategy'] = @$data_arrays['strategy'];
							$data['Pointdata']['test_color_fg'] = isset($data_arrays['test_color_fg']) ? $data_arrays['test_color_fg'] : 0;
							$data['Pointdata']['test_color_bg'] = isset($data_arrays['test_color_bg']) ? $data_arrays['test_color_bg'] : 0;
							$data['Pointdata']['latitude'] = @$data_arrays['latitude'];
							$data['Pointdata']['longitude'] = @$data_arrays['longitude'];
							$data['Pointdata']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
							$data['Pointdata']['version'] = @$data_arrays['version'];
							$data['Pointdata']['diagnosys'] = @$data_arrays['diagnosys'];
							$data['Pointdata']['stereopsis'] = @$data_arrays['Stereopsis'];
							$data['Pointdata']['baseline'] = (isset($data_arrays['baseline']) && !empty($data_arrays['baseline'])) ? $data_arrays['baseline'] : 0;
							$this->Pointdata->create();
							$result = $this->Pointdata->save($data);
							$lastId = $this->Pointdata->id;
							$lastFile = $this->Pointdata->file;
							if ($result) {
								$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
								$result2['ReportRequestBackup']['status'] = 1;
								if ($this->ReportRequestBackup->save($result2)) {
								}
								if (!empty($data_arrays['file'])) {
									$response['pdf'] = WWW_BASE . 'pointData/' . $data_arrays['file'];
									$response['new_id'] = $lastId;
								} else {
									$response['pdf'] = '';
								}
								foreach ($data_arrays['cspointdata_od'] as $pdatas) {
									$pdata = array();
									$pdata['CsPointdata']['point_data_id'] = @$lastId;
									$pdata['CsPointdata']['eye_select'] = 1;
									$pdata['CsPointdata']['freq'] = isset($pdatas['freq']) ? $pdatas['freq'] : '';
									$pdata['CsPointdata']['amp'] = isset($pdatas['Amp']) ? $pdatas['Amp'] : '';
									$pdata['CsPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
									$this->CsPointdata->create();
									$result_p = $this->CsPointdata->save($pdata);
								}
								foreach ($data_arrays['cspointdata_os'] as $pdatas) {
									$pdata = array();
									$pdata['CsPointdata']['point_data_id'] = @$lastId;
									$pdata['CsPointdata']['eye_select'] = 0;
									$pdata['CsPointdata']['freq'] = isset($pdatas['freq']) ? $pdatas['freq'] : '';
									$pdata['CsPointdata']['amp'] = isset($pdatas['Amp']) ? $pdatas['Amp'] : '';
									$pdata['CsPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
									$this->CsPointdata->create();
									$result_p = $this->CsPointdata->save($pdata);
								}
								$pdata = "";
								$response['message'] = 'Success.';
								$response['result'] = 1;
								$this->loadModel('User');
								$this->User->id = $data['Pointdata']['staff_id'];
								$credits = $this->User->field('credits');
								$new_credit = $credits - 1;
								$this->User->updateAll(array('User.credits' => $new_credit), array('User.id' => $data['Pointdata']['staff_id']));
							} else {
								$response['message'] = 'Some error occured in updating report.';
								$response['result'] = 0;
							}
						} else {
							$response['message'] = 'Patient id can\'t be empty.';
							$response['result'] = 0;
						}
					}
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*Multiple faild VS report */
	public function VSTestControllerDataAll_V4()
	{
		$db = $this->ReportRequestBackup;
		$results = $db->query("SET session wait_timeout=28800", FALSE);
		$results = $db->query("SET session interactive_timeout=28800", FALSE);
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$faildRequest = array();
				$resultData = $saved_data = $faild_data = array();
				$data_arrays_all = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'VSTestControllerDataAll_V4';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				$data_arrays['test_report_id'] = 1;
				if (!empty($data_arrays_all['data'])) {
					foreach ($data_arrays_all['data'] as $key => $data_arrays) {
						if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
							if (!empty($data_arrays['pdf'])) {
								$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
								$foldername = "pointData";
								$imgstring = $data_arrays['pdf'];
								$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
							}
							$data['Pointdata']['test_id'] = isset($data_arrays['test_id']) ? $data_arrays['test_id'] : '';
							$data['Pointdata']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'S';
							$data['Pointdata']['numpoints'] = isset($data_arrays['numpoints']) ? $data_arrays['numpoints'] : '0';
							$data['Pointdata']['color'] = isset($data_arrays['color']) ? $data_arrays['color'] : '';
							$data['Pointdata']['backgroundcolor'] = isset($data_arrays['backgroundcolor']) ? $data_arrays['backgroundcolor'] : '';
							$data['Pointdata']['stmsize'] = isset($data_arrays['stmSize']) ? $data_arrays['stmSize'] : 0;
							$data['Pointdata']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
							$data['Pointdata']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
							$data['Pointdata']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
							$data['Pointdata']['master_key'] = isset($data_arrays['master_key']) ? $data_arrays['master_key'] : 0;
							if ($data['Pointdata']['master_key'] == "") {
								$data['Pointdata']['master_key'] = 0;
							}
							$data['Pointdata']['eye_select'] = isset($data_arrays['eye_select']) ? $data_arrays['eye_select'] : 1;
							if ($data['Pointdata']['eye_select'] == 'OU') {
								$data['Pointdata']['eye_select'] = 2;
							}
							$data['Pointdata']['test_type_id'] = isset($data_arrays['test_type_id']) ? $data_arrays['test_type_id'] : '';
							$data['Pointdata']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : '';
							$data['Pointdata']['mean_dev'] = @$data_arrays['mean_dev'];
							$data['Pointdata']['pattern_std'] = @$data_arrays['pattern_std'];
							$data['Pointdata']['mean_sen'] = @$data_arrays['mean_sen'];
							$data['Pointdata']['mean_def'] = @$data_arrays['mean_def'];
							$data['Pointdata']['pattern_std_hfa'] = @$data_arrays['pattern_std_hfa'];
							$data['Pointdata']['loss_var'] = @$data_arrays['loss_var'];
							$data['Pointdata']['mean_std'] = @$data_arrays['mean_std'];
							$data['Pointdata']['psd_hfa_2'] = @$data_arrays['psd_hfa_2'];
							$data['Pointdata']['psd_hfa'] = @$data_arrays['psd_hfa'];
							$data['Pointdata']['vission_loss'] = @$data_arrays['vission_loss'];
							$data['Pointdata']['false_p'] = @$data_arrays['false_p'];
							$data['Pointdata']['false_n'] = @$data_arrays['false_n'];
							$data['Pointdata']['false_f'] = @$data_arrays['false_f'];
							$data['Pointdata']['ght'] = @$data_arrays['ght'];
							$data['Pointdata']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
							$data['Pointdata']['threshold'] = @$data_arrays['threshold'];
							$data['Pointdata']['strategy'] = @$data_arrays['strategy'];
							$data['Pointdata']['test_color_fg'] = isset($data_arrays['test_color_fg']) ? $data_arrays['test_color_fg'] : 0;
							$data['Pointdata']['test_color_bg'] = isset($data_arrays['test_color_bg']) ? $data_arrays['test_color_bg'] : 0;
							$data['Pointdata']['latitude'] = @$data_arrays['latitude'];
							$data['Pointdata']['longitude'] = @$data_arrays['longitude'];
							$data['Pointdata']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
							$data['Pointdata']['version'] = @$data_arrays['version'];
							$data['Pointdata']['diagnosys'] = @$data_arrays['diagnosys'];
							$data['Pointdata']['stereopsis'] = @$data_arrays['Stereopsis'];
							$data['Pointdata']['baseline'] = (isset($data_arrays['baseline']) && !empty($data_arrays['baseline'])) ? $data_arrays['baseline'] : 0;
							$this->Pointdata->create();
							$result = $this->Pointdata->save($data);
							if ($result) {
								$saved_data[]['id'] = $this->Pointdata->id;
							}else{
							  	$fail=array(); 
									$errors = $this->Pointdata->validationErrors;
									$response['message']='Some error occured in updating report.';
									$result2 = $this->Pointdata->find('first', array('conditions' => array('Pointdata.unique_id' => $data_arrays['unique_id'])));
									$response['result']=0;
									$fail['id']=$result2['Pointdata']['id']; 
									$fail['unique_id']=$data_arrays['unique_id'];
									//pr($result2['Patient']);die; 
									$name=$name=$result2['Patient']['first_name'];
									if($result2['Patient']['middle_name']!=""){
										$name=$name.' '.$result2['Patient']['middle_name'];
									}
									if($result2['Patient']['last_name']!=""){
										$name=$name.' '.$result2['Patient']['last_name'];
									}
									$fail['patient_name']=$name; 
									$fail['message']=$errors[array_keys($errors)[0]][0];
									$faild_data[]=$fail; 
								} 
							$lastId = $this->Pointdata->id;
							$lastFile = $this->Pointdata->file;
							if ($result) {
								$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
								$result2['ReportRequestBackup']['status'] = 1;
								if ($this->ReportRequestBackup->save($result2)) {
								}
								if (!empty($data_arrays['file'])) {
									$response['pdf'] = WWW_BASE . 'pointData/' . $data_arrays['file'];
									$response['new_id'] = $lastId;
								} else {
									$response['pdf'] = '';
								}
								foreach ($data_arrays['cspointdata_od'] as $pdatas) {
									$pdata = array();
									$pdata['CsPointdata']['point_data_id'] = @$lastId;
									$pdata['CsPointdata']['eye_select'] = 1;
									$pdata['CsPointdata']['freq'] = isset($pdatas['freq']) ? $pdatas['freq'] : '';
									$pdata['CsPointdata']['amp'] = isset($pdatas['Amp']) ? $pdatas['Amp'] : '';
									$pdata['CsPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
									$this->CsPointdata->create();
									$result_p = $this->CsPointdata->save($pdata);
								}
								foreach ($data_arrays['cspointdata_os'] as $pdatas) {
									$pdata = array();
									$pdata['CsPointdata']['point_data_id'] = @$lastId;
									$pdata['CsPointdata']['eye_select'] = 0;
									$pdata['CsPointdata']['freq'] = isset($pdatas['freq']) ? $pdatas['freq'] : '';
									$pdata['CsPointdata']['amp'] = isset($pdatas['Amp']) ? $pdatas['Amp'] : '';
									$pdata['CsPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
									$this->CsPointdata->create();
									$result_p = $this->CsPointdata->save($pdata);
								}
								$pdata = "";
								$response['data'] = $saved_data;
								$response['message'] = 'Success.';
								$response['result'] = 1;
								$this->loadModel('User');
								$this->User->id = $data['Pointdata']['staff_id'];
								$credits = $this->User->field('credits');
								$new_credit = $credits - 1;
								$this->User->updateAll(array('User.credits' => $new_credit), array('User.id' => $data['Pointdata']['staff_id']));
							} else {
								$response['message'] = 'Some error occured in updating report.';
								$response['result'] = 0;
							}
						} else {
							$response['message'] = 'Patient id can\'t be empty.';
							$response['result'] = 0;
						}
					}
				}
				$response['failed_data'] = $faild_data;
				echo json_encode($response);
				exit();
			}
		}
	}

	/*Save multiple VS report create new API by Madan 24-11-2022*/
	public function saveMultipleVSReport_v6(){
		$db = $this->ReportRequestBackup;
		$results = $db->query("SET session wait_timeout=28800", FALSE);
		$results = $db->query("SET session interactive_timeout=28800", FALSE);
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$faildRequest = array();
				$resultData = $saved_data = $faild_data = array();
				$data_arrays_all = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'saveMultipleVSReport_v6';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				$data_arrays['test_report_id'] = 1;
				if (!empty($data_arrays_all['data'])) {
					foreach ($data_arrays_all['data'] as $key => $data_arrays) {
						if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
							if (!empty($data_arrays['pdf'])) {
								$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
								$foldername = "pointData";
								$imgstring = $data_arrays['pdf'];
								$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
							}
							$data['Pointdata']['test_id'] = isset($data_arrays['test_id']) ? $data_arrays['test_id'] : '';
							$data['Pointdata']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'S';
							$data['Pointdata']['numpoints'] = isset($data_arrays['numpoints']) ? $data_arrays['numpoints'] : '0';
							$data['Pointdata']['color'] = isset($data_arrays['color']) ? $data_arrays['color'] : '';
							$data['Pointdata']['backgroundcolor'] = isset($data_arrays['backgroundcolor']) ? $data_arrays['backgroundcolor'] : '';
							$data['Pointdata']['stmsize'] = isset($data_arrays['stmSize']) ? $data_arrays['stmSize'] : 0;
							$data['Pointdata']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
							$data['Pointdata']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
							$data['Pointdata']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
							$data['Pointdata']['master_key'] = isset($data_arrays['master_key']) ? $data_arrays['master_key'] : 0;
							if ($data['Pointdata']['master_key'] == "") {
								$data['Pointdata']['master_key'] = 0;
							}
							$data['Pointdata']['eye_select'] = isset($data_arrays['eye_select']) ? $data_arrays['eye_select'] : 1;
							if ($data['Pointdata']['eye_select'] == 'OU') {
								$data['Pointdata']['eye_select'] = 2;
							}
							$data['Pointdata']['test_type_id'] = isset($data_arrays['test_type_id']) ? $data_arrays['test_type_id'] : '';
							$data['Pointdata']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : '';
							$data['Pointdata']['mean_dev'] = @$data_arrays['mean_dev'];
							$data['Pointdata']['pattern_std'] = @$data_arrays['pattern_std'];
							$data['Pointdata']['mean_sen'] = @$data_arrays['mean_sen'];
							$data['Pointdata']['mean_def'] = @$data_arrays['mean_def'];
							$data['Pointdata']['pattern_std_hfa'] = @$data_arrays['pattern_std_hfa'];
							$data['Pointdata']['loss_var'] = @$data_arrays['loss_var'];
							$data['Pointdata']['mean_std'] = @$data_arrays['mean_std'];
							$data['Pointdata']['psd_hfa_2'] = @$data_arrays['psd_hfa_2'];
							$data['Pointdata']['psd_hfa'] = @$data_arrays['psd_hfa'];
							$data['Pointdata']['vission_loss'] = @$data_arrays['vission_loss'];
							$data['Pointdata']['false_p'] = @$data_arrays['false_p'];
							$data['Pointdata']['false_n'] = @$data_arrays['false_n'];
							$data['Pointdata']['false_f'] = @$data_arrays['false_f'];
							$data['Pointdata']['ght'] = @$data_arrays['ght'];
							$data['Pointdata']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
							$data['Pointdata']['created_date_utc'] = $data_arrays['created_date_utc'];
							$data['Pointdata']['threshold'] = @$data_arrays['threshold'];
							$data['Pointdata']['strategy'] = @$data_arrays['strategy'];
							$data['Pointdata']['test_color_fg'] = isset($data_arrays['test_color_fg']) ? $data_arrays['test_color_fg'] : 0;
							$data['Pointdata']['test_color_bg'] = isset($data_arrays['test_color_bg']) ? $data_arrays['test_color_bg'] : 0;
							$data['Pointdata']['latitude'] = @$data_arrays['latitude'];
							$data['Pointdata']['longitude'] = @$data_arrays['longitude'];
							$data['Pointdata']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
							$data['Pointdata']['version'] = @$data_arrays['version'];
							$data['Pointdata']['diagnosys'] = @$data_arrays['diagnosys'];
							$data['Pointdata']['stereopsis'] = @$data_arrays['Stereopsis'];
							$data['Pointdata']['baseline'] = (isset($data_arrays['baseline']) && !empty($data_arrays['baseline'])) ? $data_arrays['baseline'] : 0;
							$this->Pointdata->create();
							$result = $this->Pointdata->save($data);
							if ($result) {
								$saved_data[]['id'] = $this->Pointdata->id;
							}else{
							  	$fail=array(); 
									$errors = $this->Pointdata->validationErrors;
									$response['message']='Some error occured in updating report.';
									$result2 = $this->Pointdata->find('first', array('conditions' => array('Pointdata.unique_id' => $data_arrays['unique_id'])));
									$response['result']=0;
									$fail['id']=$result2['Pointdata']['id']; 
									$fail['unique_id']=$data_arrays['unique_id'];
									//pr($result2['Patient']);die; 
									$name=$name=$result2['Patient']['first_name'];
									if($result2['Patient']['middle_name']!=""){
										$name=$name.' '.$result2['Patient']['middle_name'];
									}
									if($result2['Patient']['last_name']!=""){
										$name=$name.' '.$result2['Patient']['last_name'];
									}
									$fail['patient_name']=$name; 
									$fail['message']=$errors[array_keys($errors)[0]][0];
									$faild_data[]=$fail; 
								} 
							$lastId = $this->Pointdata->id;
							$lastFile = $this->Pointdata->file;
							if ($result) {
								$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
								$result2['ReportRequestBackup']['status'] = 1;
								if ($this->ReportRequestBackup->save($result2)) {
								}
								if (!empty($data_arrays['file'])) {
									$response['pdf'] = WWW_BASE . 'pointData/' . $data_arrays['file'];
									$response['new_id'] = $lastId;
								} else {
									$response['pdf'] = '';
								}
								foreach ($data_arrays['cspointdata_od'] as $pdatas) {
									$pdata = array();
									$pdata['CsPointdata']['point_data_id'] = @$lastId;
									$pdata['CsPointdata']['eye_select'] = 1;
									$pdata['CsPointdata']['freq'] = isset($pdatas['freq']) ? $pdatas['freq'] : '';
									$pdata['CsPointdata']['amp'] = isset($pdatas['Amp']) ? $pdatas['Amp'] : '';
									$pdata['CsPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
									$this->CsPointdata->create();
									$result_p = $this->CsPointdata->save($pdata);
								}
								foreach ($data_arrays['cspointdata_os'] as $pdatas) {
									$pdata = array();
									$pdata['CsPointdata']['point_data_id'] = @$lastId;
									$pdata['CsPointdata']['eye_select'] = 0;
									$pdata['CsPointdata']['freq'] = isset($pdatas['freq']) ? $pdatas['freq'] : '';
									$pdata['CsPointdata']['amp'] = isset($pdatas['Amp']) ? $pdatas['Amp'] : '';
									$pdata['CsPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
									$this->CsPointdata->create();
									$result_p = $this->CsPointdata->save($pdata);
								}
								$pdata = "";
								$response['data'] = $saved_data;
								$response['message'] = 'Success.';
								$response['result'] = 1;
								$this->loadModel('User');
								$this->User->id = $data['Pointdata']['staff_id'];
								$credits = $this->User->field('credits');
								$new_credit = $credits - 1;
								$this->User->updateAll(array('User.credits' => $new_credit), array('User.id' => $data['Pointdata']['staff_id']));
							} else {
								$response['message'] = 'Some error occured in updating report.';
								$response['result'] = 0;
							}
						} else {
							$response['message'] = 'Patient id can\'t be empty.';
							$response['result'] = 0;
						}
					}
				}
				$response['failed_data'] = $faild_data;
				echo json_encode($response);
				exit();
			}
		}
	}
	/*Save multiple VS report create new API by Madan 24-11-2022*/

	/*Multiple faild report */
	public function get_pointData_report_stb_v2()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				//pr($data_arrays);die;
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 10 - 1;
					} else {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = ($data_arrays['page'] - 1) * 10;
						$end = $data_arrays['page'] * 10 - 1;
					}
					//$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$data_arrays['staff_id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.office_id')));
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id']), 'fields' => array('User.office_id')));
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
					$this->StbTest->virtualFields['patient_name'] = 'select concat(first_name," ",middle_name," ",last_name) from mmd_patients as patients where StbTest.patient_id = patients.id';
					/* $this->Pointdata->virtualFields['patient_id'] = 'select id from mmd_patients as patients where Pointdata.patient_id = patients.id'; */
					$this->StbTest->virtualFields['staff_name'] = 'select concat(first_name," ",middle_name," ",last_name) as name from mmd_users as users where StbTest.staff_id = users.id';
					//$this->VfPointdata->virtualFields['test_id'] = 'VfPointdata.point_data_id';
					$condition['StbTest.staff_id'] = $all_staff_ids;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['StbTest.patient_id'] = $data_arrays['patient_id'];
					}
					if (isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["StbTest.patient_name LIKE"] = '%' . $data_arrays['patient_name'] . "%";
					}
					if (isset($data_arrays['test_name']) && !empty($data_arrays['test_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["StbTest.test_name LIKE"] = '%' . $data_arrays['test_name'] . "%";
					}
					/*if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['StbTest.created >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}*/
					if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['StbTest.merge_report_date >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}
					$condition["StbTest.test_name LIKE"] = '%Strabismus Screening%';
					//  $this->StbTest->unbindModel(array('hasMany' => array('StbPointdata')));
					$this->StbTest->unbindModel(
						array('hasMany' => array('StbPointdata'), 'belongsTo' => array('User', 'Patient', 'Test', 'Office'))
					);
					$results = $this->StbTest->find('all', array('conditions' => $condition, 'order' => array('StbTest.id DESC'), 'limit' => $limit));
					// $results = $this->StbTest->find('all', array('conditions' => $condition,  'order' => array('StbTest.id DESC'), 'limit' => 1));
					//  pr($results); die;
					if ($data_arrays['page'] != 0) {
						if ((count($results) > $data_arrays['page'] * 10)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
					if (!empty($results)) {
						$data = array();
						if ($data_arrays['page'] == 0) {
							$end = count($results) - 1;
						}
						$i = 0;
						$last_sync_time = '';
						foreach ($results as $key => $result) {
							if ($key >= $start && $key <= $end) {
								//pr($result);die;
								$data[$i] = $result['StbTest'];
								$data[$i]['test_id'] = $result['StbTest']['id'];
								unset($data[$i]['id']);
								$data[$i]['created_date'] = ($result['StbTest']['created'] != null) ? ($result['StbTest']['created']) : '';
								$data[$i]['patient_name'] = ($result['StbTest']['patient_name'] != null) ? ($result['StbTest']['patient_name']) : '';
								if (!empty($result['StbTest']['file'])) {
									$data[$i]['pdf'] = WWW_BASE . 'uploads/stbdata/' . $result['StbTest']['file'];
								}
								//  $data[$i]['color'] = ($result['StbTest']['color'] != null) ? ($result['StbTest']['color']) : '';
								$data[$i]['patient_id'] = isset($result['StbTest']['patient_id']) ? ($result['StbTest']['patient_id']) : '';
								$data[$i]['test_name'] = ($result['StbTest']['test_name'] != null) ? ($result['StbTest']['test_name']) : '';
								// $data[$i]['backgroundcolor'] = ($result['StbTest']['backgroundcolor'] != null) ? ($result['StbTest']['backgroundcolor']) : '';
								$data[$i]['threshold'] = @$result['StbTest']['threshold'];
								$data[$i]['strategy'] = @$result['StbTest']['strategy'];
								$data[$i]['test_color_fg'] = @$result['StbTest']['test_color_fg'];
								$data[$i]['test_color_bg'] = @$result['StbTest']['test_color_bg'];
								// $data[$i]['stmsize'] = ($result['StbTest']['stmsize'] != null) ? ($result['StbTest']['stmsize']) : '';
								/* $data[$i]['stmsize']=($result['Pointdata']['test_name']!=null)?($result['Pointdata']['test_name']):''; */
								$data[$i]['master_key'] = ($result['StbTest']['master_key'] != null) ? ($result['StbTest']['master_key']) : '';
								$data[$i]['test_type_id'] = ($result['StbTest']['test_type_id'] != null) ? ($result['StbTest']['test_type_id']) : '';
								// $data[$i]['numpoints'] = ($result['StbTest']['numpoints'] != null) ? ($result['StbTest']['numpoints']) : '';
								$data[$i]['eye_select'] = ($result['StbTest']['eye_select'] != null) ? ($result['StbTest']['eye_select']) : '';
								$data[$i]['latitude'] = !empty($result['StbTest']['latitude']) ? ($result['StbTest']['latitude']) : '';
								$data[$i]['longitude'] = !empty($result['StbTest']['longitude']) ? ($result['StbTest']['longitude']) : '';
								$data[$i]['unique_id'] = !empty($result['StbTest']['unique_id']) ? ($result['StbTest']['unique_id']) : '';
								$data[$i]['version'] = !empty($result['StbTest']['version']) ? ($result['StbTest']['version']) : '';
								$data[$i]['age_group'] = !empty($result['StbTest']['age_group']) ? ($result['StbTest']['age_group']) : 0;
								$data[$i]['staff_id'] = !empty($result['StbTest']['staff_id']) ? ($result['StbTest']['staff_id']) : '';
								$data[$i]['device_id'] = !empty($result['StbTest']['device_id']) ? ($result['StbTest']['device_id']) : '';
								$data[$i]['office_id'] = !empty($result['StbTest']['office_id']) ? ($result['StbTest']['office_id']) : '';
								$data[$i]['stereopsis'] = !empty($result['StbTest']['stereopsis']) ? ($result['StbTest']['stereopsis']) : '';
								$data[$i]['diagnosys'] = !empty($result['StbTest']['diagnosys']) ? ($result['StbTest']['diagnosys']) : '';
								// $data[$i]['StbPointdata'] = !empty($result['StbPointdata']) ? ($result['StbPointdata']) : [];
								//$data[$i]['latitude']= $result['Pointdata']['latitude'];
								//$data[$i]['longitude']= $result['Pointdata']['longitude'];
								if (isset($data[$i]['file']))
									unset($data[$i]['file']);
								if (isset($data[$i]['created']))
									unset($data[$i]['created']);
								$last_sync_time = !empty($last_sync_time) ? $last_sync_time : $result['StbTest']['created'];
								$i++;
							}
						}
						//pr($data);die;
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['last_sync_time'] = $last_sync_time;
							$response['result'] = 1;
							$response['more_data'] = $more_data;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $more_data;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}

	/*get STB report create new API by Madan 24-11-2022*/
	public function get_pointData_report_stb_v6()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 10 - 1;
					} else {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = ($data_arrays['page'] - 1) * 10;
						$end = $data_arrays['page'] * 10 - 1;
					}
					//$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$data_arrays['staff_id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.office_id')));
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id']), 'fields' => array('User.office_id')));
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
					$this->StbTest->virtualFields['patient_name'] = 'select concat(first_name," ",middle_name," ",last_name) from mmd_patients as patients where StbTest.patient_id = patients.id';
					/* $this->Pointdata->virtualFields['patient_id'] = 'select id from mmd_patients as patients where Pointdata.patient_id = patients.id'; */
					$this->StbTest->virtualFields['staff_name'] = 'select concat(first_name," ",middle_name," ",last_name) as name from mmd_users as users where StbTest.staff_id = users.id';
					//$this->VfPointdata->virtualFields['test_id'] = 'VfPointdata.point_data_id';
					$condition['StbTest.staff_id'] = $all_staff_ids;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['StbTest.patient_id'] = $data_arrays['patient_id'];
					}
					if (isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["StbTest.patient_name LIKE"] = '%' . $data_arrays['patient_name'] . "%";
					}
					if (isset($data_arrays['test_name']) && !empty($data_arrays['test_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["StbTest.test_name LIKE"] = '%' . $data_arrays['test_name'] . "%";
					}
					/*if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['StbTest.created >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}*/
					if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['StbTest.created_date_utc >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}
					$condition["StbTest.test_name LIKE"] = '%Strabismus Screening%';
					//  $this->StbTest->unbindModel(array('hasMany' => array('StbPointdata')));
					$this->StbTest->unbindModel(
						array('hasMany' => array('StbPointdata'), 'belongsTo' => array('User', 'Patient', 'Test', 'Office'))
					);
					$results = $this->StbTest->find('all', array('conditions' => $condition, 'order' => array('StbTest.id DESC'), 'limit' => $limit));
					// $results = $this->StbTest->find('all', array('conditions' => $condition,  'order' => array('StbTest.id DESC'), 'limit' => 1));
					//  pr($results); die;
					if ($data_arrays['page'] != 0) {
						if ((count($results) > $data_arrays['page'] * 10)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
					if (!empty($results)) {
						$data = array();
						if ($data_arrays['page'] == 0) {
							$end = count($results) - 1;
						}
						$i = 0;
						$last_sync_time = '';
						foreach ($results as $key => $result) {
							if ($key >= $start && $key <= $end) {
								//pr($result);die;
								$data[$i] = $result['StbTest'];
								$data[$i]['test_id'] = $result['StbTest']['id'];
								unset($data[$i]['id']);
								$data[$i]['created_date'] = ($result['StbTest']['created'] != null) ? ($result['StbTest']['created']) : '';
								$data[$i]['patient_name'] = ($result['StbTest']['patient_name'] != null) ? ($result['StbTest']['patient_name']) : '';
								if (!empty($result['StbTest']['file'])) {
									$data[$i]['pdf'] = WWW_BASE . 'uploads/stbdata/' . $result['StbTest']['file'];
								}
								//  $data[$i]['color'] = ($result['StbTest']['color'] != null) ? ($result['StbTest']['color']) : '';
								$data[$i]['patient_id'] = isset($result['StbTest']['patient_id']) ? ($result['StbTest']['patient_id']) : '';
								$data[$i]['test_name'] = ($result['StbTest']['test_name'] != null) ? ($result['StbTest']['test_name']) : '';
								// $data[$i]['backgroundcolor'] = ($result['StbTest']['backgroundcolor'] != null) ? ($result['StbTest']['backgroundcolor']) : '';
								$data[$i]['threshold'] = @$result['StbTest']['threshold'];
								$data[$i]['strategy'] = @$result['StbTest']['strategy'];
								$data[$i]['test_color_fg'] = @$result['StbTest']['test_color_fg'];
								$data[$i]['test_color_bg'] = @$result['StbTest']['test_color_bg'];
								// $data[$i]['stmsize'] = ($result['StbTest']['stmsize'] != null) ? ($result['StbTest']['stmsize']) : '';
								/* $data[$i]['stmsize']=($result['Pointdata']['test_name']!=null)?($result['Pointdata']['test_name']):''; */
								$data[$i]['master_key'] = ($result['StbTest']['master_key'] != null) ? ($result['StbTest']['master_key']) : '';
								$data[$i]['test_type_id'] = ($result['StbTest']['test_type_id'] != null) ? ($result['StbTest']['test_type_id']) : '';
								// $data[$i]['numpoints'] = ($result['StbTest']['numpoints'] != null) ? ($result['StbTest']['numpoints']) : '';
								$data[$i]['eye_select'] = ($result['StbTest']['eye_select'] != null) ? ($result['StbTest']['eye_select']) : '';
								$data[$i]['latitude'] = !empty($result['StbTest']['latitude']) ? ($result['StbTest']['latitude']) : '';
								$data[$i]['longitude'] = !empty($result['StbTest']['longitude']) ? ($result['StbTest']['longitude']) : '';
								$data[$i]['unique_id'] = !empty($result['StbTest']['unique_id']) ? ($result['StbTest']['unique_id']) : '';
								$data[$i]['version'] = !empty($result['StbTest']['version']) ? ($result['StbTest']['version']) : '';
								$data[$i]['age_group'] = !empty($result['StbTest']['age_group']) ? ($result['StbTest']['age_group']) : 0;
								$data[$i]['staff_id'] = !empty($result['StbTest']['staff_id']) ? ($result['StbTest']['staff_id']) : '';
								$data[$i]['device_id'] = !empty($result['StbTest']['device_id']) ? ($result['StbTest']['device_id']) : '';
								$data[$i]['office_id'] = !empty($result['StbTest']['office_id']) ? ($result['StbTest']['office_id']) : '';
								$data[$i]['stereopsis'] = !empty($result['StbTest']['stereopsis']) ? ($result['StbTest']['stereopsis']) : '';
								$data[$i]['diagnosys'] = !empty($result['StbTest']['diagnosys']) ? ($result['StbTest']['diagnosys']) : '';
								// $data[$i]['StbPointdata'] = !empty($result['StbPointdata']) ? ($result['StbPointdata']) : [];
								//$data[$i]['latitude']= $result['Pointdata']['latitude'];
								//$data[$i]['longitude']= $result['Pointdata']['longitude'];
								if (isset($data[$i]['file']))
									unset($data[$i]['file']);
								if (isset($data[$i]['created_date_utc']))
									unset($data[$i]['created_date_utc']);
								$last_sync_time = !empty($last_sync_time) ? $last_sync_time : $result['StbTest']['created_date_utc'];
								$i++;
							}
						}
						if($data_arrays['sync_start_time'] == ''){
							date_default_timezone_set('UTC');
            				$UTCDate = date('Y-m-d H:i:s');
							$last_sync_time = $UTCDate;
						}
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['last_sync_time'] = $last_sync_time;
							$response['result'] = 1;
							$response['more_data'] = $more_data;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $more_data;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}
	/*get STB report create new API by Madan 24-11-2022*/

	public function get_pointData_report_stb()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				//pr($data_arrays);die;
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 10 - 1;
					} else {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = ($data_arrays['page'] - 1) * 10;
						$end = $data_arrays['page'] * 10 - 1;
					}
					//$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$data_arrays['staff_id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.office_id')));
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id']), 'fields' => array('User.office_id')));
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
					$this->Pointdata->virtualFields['patient_name'] = 'select concat(first_name," ",middle_name," ",last_name) from mmd_patients as patients where Pointdata.patient_id = patients.id';
					/* $this->Pointdata->virtualFields['patient_id'] = 'select id from mmd_patients as patients where Pointdata.patient_id = patients.id'; */
					$this->Pointdata->virtualFields['staff_name'] = 'select concat(first_name," ",middle_name," ",last_name) as name from mmd_users as users where Pointdata.staff_id = users.id';
					//$this->VfPointdata->virtualFields['test_id'] = 'VfPointdata.point_data_id';
					$condition['Pointdata.staff_id'] = $all_staff_ids;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['Pointdata.patient_id'] = $data_arrays['patient_id'];
					}
					if (isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["Pointdata.patient_name LIKE"] = '%' . $data_arrays['patient_name'] . "%";
					}
					if (isset($data_arrays['test_name']) && !empty($data_arrays['test_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						// $condition["Pointdata.test_name LIKE"] = '%Strabismus Screening%';
					}
					/*if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['Pointdata.created >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}*/
					if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['Pointdata.merge_report_date >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}
					$condition["Pointdata.test_name LIKE"] = '%Strabismus Screening%';
					$this->Pointdata->unbindModel(array('hasMany' => array('VfPointdata')));
					$results = $this->Pointdata->find('all', array('conditions' => $condition, 'fields' => array('Pointdata.age_group', 'Pointdata.staff_id', 'Pointdata.device_id', 'Pointdata.office_id', 'Pointdata.stereopsis', 'Pointdata.id', 'Pointdata.created', 'Pointdata.patient_name', 'Pointdata.staff_name', 'Pointdata.file', 'Pointdata.color', 'Pointdata.backgroundcolor', 'Pointdata.stmsize', 'Pointdata.patient_id', 'Pointdata.eye_select', 'Pointdata.baseline', 'Pointdata.numpoints', 'Pointdata.test_type_id', 'Pointdata.master_key', 'Test.name', 'Pointdata.test_name', 'Pointdata.threshold', 'Pointdata.strategy', 'Pointdata.test_color_fg', 'Pointdata.test_color_bg', 'Pointdata.mean_dev', 'Pointdata.pattern_std', 'Pointdata.mean_sen', 'Pointdata.mean_def', 'Pointdata.pattern_std_hfa', 'Pointdata.psd_hfa', 'Pointdata.vission_loss', 'Pointdata.false_p', 'Pointdata.false_n', 'Pointdata.false_f', 'Pointdata.psd_hfa_2', 'Pointdata.loss_var', 'Pointdata.mean_std', 'Pointdata.ght', 'Pointdata.latitude', 'Pointdata.longitude', 'Pointdata.unique_id', 'Pointdata.version', 'Pointdata.diagnosys'), 'order' => array('Pointdata.id DESC'), 'limit' => $limit));
					//pr($results); die;
					if ($data_arrays['page'] != 0) {
						if ((count($results) > $data_arrays['page'] * 10)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
					if (!empty($results)) {
						$data = array();
						if ($data_arrays['page'] == 0) {
							$end = count($results) - 1;
						}
						$i = 0;
						$last_sync_time = '';
						foreach ($results as $key => $result) {
							if ($key >= $start && $key <= $end) {
								//pr($result);die;
								$data[$i] = $result['Pointdata'];
								$data[$i]['test_id'] = $result['Pointdata']['id'];
								unset($data[$i]['id']);
								$data[$i]['created_date'] = ($result['Pointdata']['created'] != null) ? ($result['Pointdata']['created']) : '';
								$data[$i]['patient_name'] = ($result['Pointdata']['patient_name'] != null) ? ($result['Pointdata']['patient_name']) : '';
								if (!empty($result['Pointdata']['file'])) {
									$data[$i]['pdf'] = WWW_BASE . 'pointData/' . $result['Pointdata']['file'];
								}
								$data[$i]['color'] = ($result['Pointdata']['color'] != null) ? ($result['Pointdata']['color']) : '';
								$data[$i]['patient_id'] = isset($result['Pointdata']['patient_id']) ? ($result['Pointdata']['patient_id']) : '';
								$data[$i]['test_name'] = ($result['Pointdata']['test_name'] != null) ? ($result['Pointdata']['test_name']) : '';
								$data[$i]['backgroundcolor'] = ($result['Pointdata']['backgroundcolor'] != null) ? ($result['Pointdata']['backgroundcolor']) : '';
								$data[$i]['threshold'] = @$result['Pointdata']['threshold'];
								$data[$i]['strategy'] = @$result['Pointdata']['strategy'];
								$data[$i]['test_color_fg'] = @$result['Pointdata']['test_color_fg'];
								$data[$i]['test_color_bg'] = @$result['Pointdata']['test_color_bg'];
								$data[$i]['stmsize'] = ($result['Pointdata']['stmsize'] != null) ? ($result['Pointdata']['stmsize']) : '';
								/* $data[$i]['stmsize']=($result['Pointdata']['test_name']!=null)?($result['Pointdata']['test_name']):''; */
								$data[$i]['master_key'] = ($result['Pointdata']['master_key'] != null) ? ($result['Pointdata']['master_key']) : '';
								$data[$i]['test_type_id'] = ($result['Pointdata']['test_type_id'] != null) ? ($result['Pointdata']['test_type_id']) : '';
								$data[$i]['numpoints'] = ($result['Pointdata']['numpoints'] != null) ? ($result['Pointdata']['numpoints']) : '';
								$data[$i]['eye_select'] = ($result['Pointdata']['eye_select'] != null) ? ($result['Pointdata']['eye_select']) : '';
								$data[$i]['latitude'] = !empty($result['Pointdata']['latitude']) ? ($result['Pointdata']['latitude']) : '';
								$data[$i]['longitude'] = !empty($result['Pointdata']['longitude']) ? ($result['Pointdata']['longitude']) : '';
								$data[$i]['unique_id'] = !empty($result['Pointdata']['unique_id']) ? ($result['Pointdata']['unique_id']) : '';
								$data[$i]['version'] = !empty($result['Pointdata']['version']) ? ($result['Pointdata']['version']) : '';
								$data[$i]['age_group'] = !empty($result['Pointdata']['age_group']) ? ($result['Pointdata']['age_group']) : '';
								$data[$i]['staff_id'] = !empty($result['Pointdata']['staff_id']) ? ($result['Pointdata']['staff_id']) : '';
								$data[$i]['device_id'] = !empty($result['Pointdata']['device_id']) ? ($result['Pointdata']['device_id']) : '';
								$data[$i]['office_id'] = !empty($result['Pointdata']['office_id']) ? ($result['Pointdata']['office_id']) : '';
								$data[$i]['stereopsis'] = !empty($result['Pointdata']['stereopsis']) ? ($result['Pointdata']['stereopsis']) : '';
								$data[$i]['diagnosys'] = !empty($result['Pointdata']['diagnosys']) ? ($result['Pointdata']['diagnosys']) : '';
								//$data[$i]['latitude']= $result['Pointdata']['latitude'];
								//$data[$i]['longitude']= $result['Pointdata']['longitude'];
								if (isset($data[$i]['file']))
									unset($data[$i]['file']);
								if (isset($data[$i]['created']))
									unset($data[$i]['created']);
								$last_sync_time = !empty($last_sync_time) ? $last_sync_time : $result['Pointdata']['created'];
								$i++;
							}
						}
						//pr($data);die;
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['last_sync_time'] = $last_sync_time;
							$response['result'] = 1;
							$response['more_data'] = $more_data;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $more_data;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}
	public function pointDatadummy($value)
	{
		if ($this->check_key()) {
			$this->layout = false;
			if (1) {
				$this->autoRender = false;
				$response = array();
				$uniqueid = '7630220211121261' . strtotime(date('YmdHis')) . $value;
				$payload = '{"test_id":"","unique_id":"' . $uniqueid . '","staff_name":"","age_group":0,"staff_id":"744","patient_id":"7630","patient_name":"test  bbb","numpoints":"0","color":"10","backgroundcolor":"36","stmSize":"3","test_type_id":"","test_name":"Central_20_Point","master_key":"0","eye_select":"0","created_date":"2021-02-11 21:26:17","threshold":"Screening","strategy":"Single Intensity","test_color_fg":0,"test_color_bg":0,"baseline":"1","latitude":"","longitude":"","version":"1.2.129","diagnosys":"","mean_dev":0.0,"pattern_std":"0.00","mean_sen":"0.00","mean_def":"0.00","pattern_std_hfa":"0.00","loss_var":"0.00","mean_std":"0.00","psd_hfa_2":"0.00","psd_hfa":"0.00","vission_loss":"0","false_p":"0/0","false_n":"0/0","false_f":"0/0","ght":"","source":"C","vfpointdata":[],"pdf":"JVBERi0xLjQKJeLjz9MKMiAwIG9iago8PC9UeXBlL1hPYmplY3QvU3VidHlwZS9JbWFnZS9XaWR0aCAxMDAwL0hlaWdodCAxMDAwL0xlbmd0aCA5OTEvQ29sb3JTcGFjZS9EZXZpY2VHcmF5L0JpdHNQZXJDb21wb25lbnQgOC9GaWx0ZXIvRmxhdGVEZWNvZGU+PnN0cmVhbQp4nO3BAQEAAACCoP+rbUhAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAArwY4Q+G+CmVuZHN0cmVhbQplbmRvYmoKMyAwIG9iago8PC9UeXBlL1hPYmplY3QvU3VidHlwZS9JbWFnZS9XaWR0aCAxMDAwL0hlaWdodCAxMDAwL1NNYXNrIDIgMCBSL0xlbmd0aCAxNDE0NS9Db2xvclNwYWNlL0RldmljZVJHQi9CaXRzUGVyQ29tcG9uZW50IDgvRmlsdGVyL0ZsYXRlRGVjb2RlPj5zdHJlYW0KeJzt3OtuG02WLNB5dL+5zkETTbBFkSxW5WVf1vrVmDE+yIrIzAAt++cHgDr+78HurwUAAPib3Q4AAPHZ7QAAEJ/dDgAA8dntAAAQn90OAADx2e0AABCf3Q4AAPHZ7QAAEJ/dDgAA8dntAAAQn90OAADx2e0AABCf3Q4AAPHZ7QAAEJ/dDgAA8dntAAAQn90OAADx2e0AABCf3Q4AAPHZ7QAAEJ/dDgAA8dntAAAQn90OAADx2e0AABCf3Q4AAPHZ7QAAEJ/dDgAA8dntAAAQn90OAADx2e0AABCf3Q4AAPHZ7QAAEJ/dDgAA8dntAAAQn90OAADx2e0AABCf3Q4AAPHZ7QAAEJ/dDgAA8dntAAAQn90OAADx2e0AABCf3Q4AAPHZ7QAAEJ/dDgAA8dntAAAQn90OAADx2e0AABCf3Q4AAPHZ7QAAEJ/dDgAA8dntAAAQn90OAADx2e0AABCf3Q4AAPHZ7QAAEJ/dDgAA8dntAAAQn90OAADx2e0AABCf3Q4AAPHZ7QAAEJ/dDgAA8dntAAAQn90OAAAr/dtn928dAAC2WTOeh3zebucDAFBYhHG75edkIvzGAQDgl8gDNeDPt0f+dgEAUEDGwRlwt7+S8dsLAMB2NQZkot3+So0gAAAYouo4LLDbn1UNCwCAX/oMv5K7/VmfQAEAams76prs9mdtEwcAyMVsu2m723/RBwCAOAyzZ3b7n1QFAGAlH6J+ZLd/pEUAADOYWF+x27+lYAAAp5lSp9ntVygeAMBHJtMQdvsoCgkA8Mg0Gstun0FLAYCefJI5j90+leoCAB0YPAvY7cvoMwBQic8nF7Pb11NyACAvM2YXu30vzQcAUjBatrPbg3AWAICATJQ47PZonA4AYDuDJCC7PSznBQBYzPyIzG6PzwkCAKYyNlKw2xNxpgCAgUyLXOz2jJwyAOA0QyIpuz015w4AOM5sSM1ur8ExBABe8UFfDXZ7JU4lAHBnGBRjt5fknAJAZ2ZASXZ7bY4tAPThg7va7PYOnGIAqM1D34Hd3opDDQCV+GiuFbu9IWccALLzlDdkt3fmyANAOp7vtux2HH8AiM8fl2O3c+M2AICYPNDc2O384nIAgCA8yjyy2/mTiwIANvIQ88xu5w2XBgAs5vHlFbudj1wgALCAB5f37HYOcpkAwAz+aQgOstv5irsFAEbxpPIVu51zXDUAcJpnlBPsdq5w7QDAVzydnGa3c50rCAA+8lxykd3OKK4jAPiTJ5Ih7HbGcjUBwJ1nkYHsdmZwTQHQnKeQ4ex25nFlAdCT548Z7HZmc3cB0IfPrJjHbmcBlxgA5XnsmM1uZxkXGgAleeBYw25nMZcbAGV41FjJbmcLFx0AqXnIWM9uZyOXHgAZebzYwm5nO7cfAFn4xImN7HYicA0CEJyniu3sduJwJQIQkOeJIOx2onE9AhCHJ4k47HZick8CsJfPkYjGbicsFyYAW3iAiMluJziXJwAreXQIy24nBbcoALP5pIjg7HaycJ0CMI8nhvjsdnJxrwIwls+FyMJuJx0XLABDeFDIxW4nKZctAFd4REjHbic1ty4A3/LJD0nZ7WTn+gXgOE8Gednt1OAeBuA9n/OQnd1OGS5kAF7xQFCA3U4xbmYAHvlUhzLsdupxRQNw4zmgErudqtzVAJ35DId67HYKc2kD9OTypyS7nfLc3gB9+MSGwux2OnCNA3Tgqqc2u50+3OcAVfl8hg7sdlpxsQPU42KnCbudhtzwAGW40unDbqcn9zxAdv4IlW7sdtpy4QPk5QKnIbud5tz8AOm4uunJbgf3P0AW/qiUzux2+PEQAGTgoqY5ux3uvAgAYbmiwW6HR94FgGj8kSjc2O3wiwcCIA4XMtzZ7fAnLwXAdq5ieGS3wyveC4Bd/NEnPLPb4Q0PB8B6Ll74k90OH3lBAJZx5cIrdjsc4R0BmM0fccJ7djsc5EEBmMcFCx/Z7fAVLwvAcK5WOMJuh295XwAGcqnCQXY7nOCVARjCdQrH2e1wjrcG4Ap/aQi+ZbfDaR4dgHNcnnCC3Q4XeX0AvuLahHPsdrjOGwRwkAsTTrPbYQgvEcBHrkq4wm6HUbxHAK/4C0Fwnd0OA3mYAJ65GGEIux2G80IB3LkSYRS7HWbwTgH8uAxhKLsdJvFaAc25BmEsux3m8WYBbbkAYTi7HabycgENufpgBrsdZvN+AX34Z7VgHrsdFvCQAR246GAqux2W8aIBhbniYDa7HVbyrgEludxgAbsdFvO6AcW41mANux3W88YBZbjQYBm7Hbbw0gEFuMpgJbsddvHeAam5xGAxux028uoBSbm+YD27Hfby9gHpuLhgC7sdtvMCAom4smAXux0i8A4CKbisYCO7HYLwGgLBuaZgL7sd4vAmAmG5oGA7ux1C8TICAbmaIAK7HaLxPgKhuJQgCLsdAvJKAkG4jiAOux1i8lYC27mIIBS7HcLyYgIbuYIgGrsdIvNuAlu4fCAgux2C83oCi7l2ICa7HeLzhgLLuHAgLLsdUvCSAgu4aiAyux2y8J4CU7lkIDi7HRLxqgKTuF4gPrsdcvG2AsO5WCAFux3S8cICA7lSIAu7HTLyzgJDuEwgEbsdkvLaAhe5RiAXux3y8uYCp7lAIB27HVLz8gInuDogI7sdsvP+Al9xaUBSdjsU4BUGDnJdQF52O9TgLQaOcFdAXnY71OAtBj5yUUBqdjuU4UUG3nBFQHZ2O1TiXQb+5HKAAux2KMbrDPziWoAa7HaoxxsN3LkQoAy7HUryUgM/rgKoxW6HqrzX0JxLAIqx26EwrzZ05gaAYux2KMyrDW05/lCP3Q61ebuhIQcfSrLboTwvOLTiyENVdjt04B2HJhx2KMxuhya85lCeYw612e3QhzcdanPGoTa7HfrwpkNhDjiUZ7dDK152KMnRhrz+zbf7twic5PxCMQ41hDV2VJ/+vN22h7wcTyjDcYYI1ozhqT8nY89DWE4iFOAgw3obx+2Wn2835iEC5w6yc4phtlCTNc7fSw31bYEOnDJIzRGGGSLP0Ti7/Vnk7xvU4GRBUg4vjJJocEbe7b8k+q5CIk4TpOPYwkVJJ2Wi3f5L0m84BOQQQS7OLJxQYDrm3e2PCgQBGzk4kIgDC8cVm4g1dvujYgHBGs4LpOCowhFVp2C93f6oamowg5MCwTmk8EaHT25r7/a7DlHCdQ4IROaEwrNWA6/Jbn/UKl/4inMBYTme8KjnnGu42+96Jg7vOREQkIMJN83HW+fdfte8A/CLswChOJJgqt3Y7Y+0Am6cAojDeaQtw+wXu/1PekJzyg9BOIw0ZIa9Yre/pzm0pfawnWNIN0bXe3b7QYpEQzoPGzmA9OFj0oPs9q/oFd1oO+zi9NGBWfUVu/0cNaMJPYctHD1q80HoOXb7FVpHBxoOizl0FGY4XWG3D6GE1KbesIzjRlXG0nV2+0AKSWG6DWs4axTjhxMGstuH009KUmlYwEGjEnNoOLt9HnWlGH2GqRwxyjCBJrHbZ1NdKlFmmMf5ogCzZyq7fQ01pgY1hkkcLrIzdRaw21dSaQrQYRjOsSI182YZu3099SY7BYaxnCmSMmkWs9t3UXXyUl0YyIEiIzNmC7t9L7UnKb2FIRwl0jFdNrLbI3AEyEhp4TrniETMle3s9jgcB3JRV7jIISIRdY3Abo/GuSARdYXTHB+y8LliHHZ7QA4IiegqnOPsEJ9BEo3dHpbDQgpaCic4OARnhMRktwfn4BCfisJXHBmCU9Gw7PYUnCCCU1E4znkhLJ8WBme3Z+EoEZlywkEOCzGZGSnY7bk4VoSlmfCRY0JMmpmF3Z6R80VMmgnvOSNE4/PAXOz2pBw0AtJJeMMBIRqdTMduT82JIxqdhFecDuLw6V9Sdnt2jh6haCP8ydEgDm3My26vwRkkDm2EXxwKgvBZX3Z2exkOI3GoIjxyIohADwuw24txKolAD+HOcWA7n+yVYbfX43gSgRLCj4NAAEpYid1elXPKdkoITgEb+RyvHru9MAeWvdSP5hwBNlK/kuz28pxcNlI/OtN/dtG9quz2DpxfdtE92lJ+tvBH7bXZ7U04yOyieDSk9myheOXZ7a040WyheHSj86yndR3Y7d0416yndbSi8Czmj9T7sNsbcsBZT+XoQ9tZSd9asdvbctJZSd9oQtVZSd+6sds7c95ZSd/oQM9Zwx+d92S3N+fgs4ymUZ6Ss4amtWW38+MGYBVNozD1Zg1N68xu58Y9wBqaRlW6zQJq1pzdzp3bgAXUjJIUm9n8XCs/djv/y7XAAjpGPVrNVArGjd3OM/cDUykYxag0UykYd3Y7f3JLMJWCUYk+M4928chu5xV3BfNoF2UoM/NoF7/Y7bzhxmAe7aIGTWYS1eKZ3c577g0mUS0KUGMmUS3+ZLfzkduDSVSL7HSY4fzDbrxht3OEa4QZlIrUFJjhlIr37HaOc58wnFKRlOoynFLxkd3OV9wqDKdUZKS3jKVRHGG38y13C2NpFOkoLWNpFAfZ7ZzghmEsjSIXjWUgdeI4u51z3DMMpE4koq4MpE58xW7nNLcNA6kTWegqo+gS37LbucKdwyi6RAqKyii6xAl2Oxe5eRhFl4hPSxlCkTjHbuc69w9DKBLBqShDKBKn2e0M4RZiCEUiMv3kOi3iCrudUdxFXKdFhKWcXKdFXGS3M5Abieu0iJg0k4tUiOvsdsZyL3GRChGQWnKRCjGE3c5wbicuUiGi0Umu0B9GsduZwR3FFfpDKArJFfrDQHY7k7ipuEJ/iEMbOU15GMtuZx73FacpD3FoI+doDsPZ7Uzl1uIczSEIVeQczWEGu53Z3F2cozlEoIecoDZMYrezgBuME9SG7ZSQczSHSex2FnCDcY7msJcGcoLaMI/dzhruMU5QGzZSP05QG6ay21nGbcYJasMuuse3dIbZ7HZWcqfxLZ1hC8XjWzrDAnY7i7nZ+JbOsJ7W8RWFYQ27nfXcb3xFYVhM5fiKwrCM3c4Wbjm+ojCspG8cpy2sZLezi7uO47SFZZSNrygMK9nt7OKu4ysKwxqaxnHawmJ2Oxu58ThOW1hD0zhIVVjPbmcv9x4HqQoLqBkHqQpb2O1s5/bjIFVhNh3jCD1hF7udCNyBHKEnTKVgHKQq7GK3E4E7kINUhXm0iyP0hI3sdoJwE3KEnjCJanGEnrCX3U4c7kOO0BNm0Cs+UhK2s9sJxa3IR0rCDHrFR0rCdnY7obgV+UhJGE6p+EhJiMBuJxp3Ix8pCWNpFO9pCEHY7QTkhuQ9DWEgdeI9DSEOu52Y3JO8pyGMoku8pyHEYbcTk3uS9zSEUXSJN9SDUOx2wnJb8oZ6MIQi8YZ6EI3dTmTuTN5QD67TIl7RDQKy2wnOzckrusFFKsQb6kFAdjvBuTl5Qz24Qn94RTeIyW4nPvcnr+gGV+gPf1IMwrLbScEtyp8Ug9OUh1d0g7DsdlJwi/KKbnCO5vAnxSAyu50s3KX8STE4R3N4phUEZ7eTiBuVZ1rBCWrDnxSD4Ox2EnGj8ifF4Fs6wzOtID67nVzcqzzTCr6iMDzTClKw20nH7cozreA4beGZVpCC3U46bleeaQXHaQu/qARZ2O1k5I7lF5XgIFXhF5UgEbudpNy0/KISHKEn/KISJGK3k5Sbll9UgiP0hEf6QC52O3m5b3mkD3ykJDzSB9Kx20nNrcsjfeA9DeGRPpCO3U5qbl0e6QPvaQh3ykBGdjvZuXu5UwbeUA8e6QMZ2e1k5+7lkT7wim5wpwwkZbdTgBuYO2XgFd3gRhPIy26nBvcwN5rAnxSDO2UgL7udGtzD3CkDz7SCG00gNbudMtzG3GgCz7SCG00gNbudMtzG3GgCv6gEN5pAdnY7lbiTudEEHukDN5pAdnY7lbiTudEEHukDP2pAHv9G2P2bgEN0lR814IEy8KMGhHRich//vN2eJwu15EcN+C9N4EcNCGDIhL7+czKWPNEoIT9qwH9pAjrAFjPm8Yyfbzfj2U7x0AF+1ID/UAOWmT2AF/y9VBue9ZSNHzVAB9ABllg2dBf/ezIGPMuoGTqADqADzLN+1u76dyANeGbTLnSgOQVAB5hh44jd/u+3G/DMo1foQGfSRwcYKMJk3b7b7yJ8NyhGndCBzqTfnAIwRKiBGme334X6/pCdIjWnAJ1JvzkF4KKAczTgbr8L+O0iHRVqTgHaEn1zCsAVYSdo5N1+E/ZbRxb605wC9CT35hSAE+L/yEf83X4T/ztJWGrTnAL0JPfOpM+3sozMLLv9Lss3llB0pjPpNyT05hSA43INy3S7/SbXN5nttKU5BehG4p1Jn4Myjsmku/0m4zecXVSlM+l3I/HOpM8RSXuSerffJP3Os5iedCb9biTeluj5KPWnvgV2+0/yCFhGSdoSfSvi7kz6vFFgLtbY7TcF4mAq9ehM+n3Iui3R80qZiVhpt9+UiYYZdKMt0fch67ZEz58qFaPebr+plBEDKUZbom9C0J1Jn1/qfZZbdbf/VAyL61SiM+l3IOW2RM8vJStReLfflEyNK1SiLdF3IOW2RM9d4U9uy+/2n9LxcYIytCX6DqTck9y5q12GDrv9pnaOfEUZepJ7eSJuS/TclG9Cn93+0yBNDtKEtkRfm3x7kjs/bX64otVu/2kTKx+pQU9yr02+PcmdPh3otttv+uTLKzrQk9xrk29Pcm+uVQF67vafZinzTAF6knthwu1J7p01/CGKtrv9p2XcPJJ+T3KvSrI9yb2tntF33u03PXPnR/Rdyb0qyTYk9LbaRm+3/zROH9E3JPSqJNuQ0HvqnLvdftO5A53JvSGhlyTWnuTeUPPQ7fa75k3oSeg9yb0emTYk9IaEbrc/0oeGhN6Q0OuRaUNCb8W/JXJjt/+iGN2IuyGh1yPTbiTeirjv7PY/aUgr4u5G4vXItBuJ9yHrR3b7K3rSh6y7kXgxAm1I6E0I+he7/Q1taULQDQm9Eml2I/EmBP3Mbn9PZ5oQdDcSr0Sa3Ui8Ayn/yW7/SHM6kHI3Eq9Emt1IvDwRv2K3H6E/5Ym4G4mXIcpuJF6eiN+w2w/SovJE3I3Ea5BjNxIvT8Rv2O0HaVF5Iu5G4jXIsRuJ1ybf9+z243SpNvl2I/Ea5NiKuGuT70d2+1c0qjb5tiLuGuTYirgLE+4Rdvu39Kow4bYi7gKE2I3Eq5LsQXb7CdpVlWS7kXh2EmxF3FVJ9ji7/Rwdq0qyrYg7Owm2Iu6SxPoVu/00TStJrK2IOzsJtiLuksT6Fbv9NE0rSaytiDs7CfYh65LE+i27/Qp9K0msfcg6NfG1Iu56ZHqC3X6R1tUj01bEnZfsWhF3MQI9x26/TveKEWgr4s5Ldn3Iuh6ZnmO3X6d79ci0D1nnJbs+ZF2MQE+z24fQwGIE2oes85JdH7KuRJpX2O2j6GEl0uxD1nnJrg9ZlyHKi+z2gbSxDFH2IeukBNeHrCuR5kV2+0DaWIk0+5B1RlLrQ9ZliPI6u30snSxDlH3IOiOp9SHrGuQ4hN0+nGbWIMc+ZJ2R1JoQdBmiHMJuH04zyxBlE4LOSGpNCLoGOY5it8+gnzXIsQlBZyS1JgRdgBAHstsn0dIChNiEoNMRWR+yLkCIA9ntk2hpAULsQ9a5yKsJQRcgxLHs9nl0tQAhNiHoXOTVhKALEOJYdvs8ulqAEJsQdC7yakLQ2UlwOLt9Ko3NToJNCDoXeTUh6OwkOJzdPpXGZifBJgSdi7w6kHJ2EpzBbp9Nb7OTYAdSTkRYTQg6NfFNYrcvoL2pia8JQWchqSYEnZr4JrHbF9De1MTXhKCzkFQTgs5LdvPY7WvocF6ya0LQWUiqAymnJr557PY1dDg18XUg5Swk1YGU85LdVHb7Mpqcl+w6kHIWkupAynnJbiq7fRlNzkt2HUg5C0l1IOWkBDeb3b6SPicluA6knIWkOpByUoKbzW5fSZ+TElwHUk5BTB1IOS/ZzWa3r6TPecmuAynHJ6MOpJyU4Baw2xfT6qQE14GU45NRB1JOSnAL2O2LaXVSgutAyvHJqAMpZyS1Nez29XQ7I6l1IOX4ZNSBlDOS2hp2+3q6nZHUOpByfDIqT8QZSW0Zu30LDc9IauWJOD4ZlSfijKS2jN2+hYZnJLXyRByfjMoTcUZSW8Zu30LDM5JaeSIOTkAdSDkdka1kt++i5+mIrAMpRyadDqScjshWstt30fN0RNaBlCOTTgdSTkdkK9ntu+h5OiLrQMqRSac8EacjssXs9o20PR2RlSfiyKRTnojTEdlidvtG2p6OyMoTcWTSKU/E6YhsMbt9I21PR2TliTgy6ZQn4lzktZ7dvpfO5yKv8kQcmXTKE3Eu8lrPbt9L53ORV3kijkw65Yk4F3mtZ7fvpfO5yKs8EUcmnfJEnIiwtrDbt9P8RIRVnojDEk15Is5FXlvY7dtpfi7yKk/EMcmlPBHnIq8t7PbtND8XeZUn4pjkUp6IExHWLnZ7BPqfiLDKE3FMcilPxIkIaxe7PQL9T0RY5Yk4JrmUJ+JEhLWL3R6B/icirPJEHJNcyhNxIsLaxW6PQP8TEVZ5Io5JLuWJOAtJbWS3B+EUZCGp8kQck1zKE3EWktrIbg/CKchCUuWJOCa51CbfRIS1kd0ehFOQiLBqk29McqlNvokIayO7PQinIBFh1SbfmORSm3wTEdZGdnsQTkEiwqpNvjHJpTb5ZiGpvez2OJyFLCRVm3xjkktt8s1CUnvZ7XE4C1lIqjb5xiSX2uSbhaT2stvj2HgWdOAru5IS0xryjclaqE2+WUhqL7s9jo1r4c//zStbkhLTMvKNyVqoTb5ZSGovuz2O7Wvh1f+FX9YnJaaV5BuQqVCeiLOQ1F52exx2exZ2XXmLI5bvR6ZCeSJOQUzb2e2hGIQpiKk8uz0aa6E8Eacgpu3s9lCOn4gjkf3fC8+/7M//zSvvYzr4bf/214tppTcRy3cLa6E8Eacgpu2OvDsss2W3P/7Kr7/ilrbs9h8xLbR+t//I9y1roTb5ZiGp7Y68Oyxz5EQcnwrn/r8c8WdS337bxRSWfKOxFmqTbxaS2s5uD8Vuz8Kuq02+0VgLtck3C0ltZ7eHcuQHMJ7/9/tf+e3/lyPsutrkG421UJt8s5DUdnZ7KHZ7FnZdbfKNxlqoTb5ZSGo7uz2UUX8v1WCY7cTdZdclIt9orIXa5JuCmCKw26M5eC7s9r3sutrkG43BUJt8UxBTBHZ7NHZ7CnZdbfKNxmCoTb4piCkCuz0auz0Fu642+UZjMNQm3xTEFIHdHo3dnsJX19erb7iYwvqY7/89efVr3v8Xrn6hbRgMtck3BTFFYLdHY7enYLfXZrdHYzDUJt8UxBSB3R6N3Z6CmGrzczLRGAy1yTcFMUVgt0djEKYgptrs9mgMhtrkm4KYIrDbozEIUzj4cxRXfo2YNrLbozEYapNvCmKKwG6Pxm5PwW6vzW6PxmCoTb4piCkCuz0auz2FNzEd//aKKaw/8/02rwj5/l8V/z+R3V8CE8k3BTHBM+ciBTHVJt9oJFKbfFMQEzxzLlIQU23yjUYitck3BTHBM+ciBTHVJt9oJFKbfFMQEzxzLlIQU21l8p398/PL+Atxtck3BTFFUPKGT23I30t9/DWFn/KN3vy9xW93lJgC+vj3jo/nJd8hDIba5JuCmCLwjkTjXKQgptrkG41EapNvCmKKwG6PxrlIQUy1yTcaidQm3xTEFIHdHo1zkYKYapNvNBKpTb4piCkCuz0a5yIFMdUm32gkUpt8UxBTBHZ7NM5FCmKqTb7RSKQ2+aYgpgjs9micixTEVJt8o5FIbfJNQUwR2O3ROBcpiKk2+UYjkdrkm4KYIrDbo3EuUhBTbfKNRiK1yTcLSW1nt4fiRGQhqdrkG41EapNvFpLazm4PxYnIQlK1yTcaidQm3ywktZ3dHooTkYWkapNvNBKpTb5ZSGo7uz0UJyILSdUm32gkUpt8s5DUdnZ7KE5EFpKqTb7RSKQ8Eacgpu3s9lCciBTEVJ6Io5FIeSJOQUzb2e2hOBEpiKk8EUcjkfJEnIKYtrPbQ3EiUhBTeSKORiLliTgLSe1lt8fhLGQhqfJEHJBQapNvFpLay26Pw1nIQlK1yTcmudQm3ywktZfdHoezkIWkapNvTHKpTb5ZSGovuz0OZyELSdUm35jkUpt8s5DUXnZ7HM5CFpKqTb4xyaU2+WYhqb3s9jichSwkVZt8Y5JLbfJNRFgb2e1BOAWJCKs2+cYkl9rkm4iwNrLbg3AKEhFWbfKNSS61yTcRYW1ktwfhFCQirNrkG5NcyhNxFpLayG4PwinIQlLliTgmuZQn4iwktZHdHoRTkIWkyhNxTHIpT8SJCGsXuz0C/U9EWOWJOCa5lCfiRIS1i90egf4nIqzyRByTXMoTcSLC2sVuj0D/ExFWeSKOSS7liTgRYe1it0eg/4kIqzwRxySX8kSci7y2sNu30/xc5FWeiGOSS3kizkVeW9jt22l+LvIqT8RhiaY8EScirC3s9u00PxFhlSfiyKRTnohzkdd6dvteOp+LvMoTcWTSKU/EuchrPbt9L53PRV7liTgy6ZQn4lzktZ7dvpfO5yKv8kQcmXTKE3E6IlvMbt9I29MRWXkijkw65Yk4HZEtZrdvpO3piKw8EUcmnfJEnI7IFrPbN9L2dERWnogjk04HUk5HZCvZ7bvoeToi60DKkUmnAymnI7KV7PZd9DwdkXUg5cik04GU0xHZSnb7Lnqejsg6kHJwAipPxBlJbRm7fQsNz0hq5Yk4PhmVJ+KMpLaM3b6FhmcktfJEHJ+MyhNxRlJbxm7fQsMzklp5Io5PRh1IOSOprWG3r6fbGUmtAynHJ6MOpJyR1Naw29fT7Yyk1oGU45NRB1JOSnAL2O2LaXVSgutAyvHJqAMpJyW4Bez2xbQ6KcF1IOX4ZNSBlPOS3Wx2+0r6nJfsOpByCmLqQMpJCW42u30lfU5KcB1IOQtJdSDlpAQ3m92+kj4nJbgOpJyFpDqQcl6ym8puX0aT85JdB1LOQlIdSDkv2U1lty+jyXnJrgMpZyGpDqScmvjmsdvX0OHUxNeBlLOQVBOCzkt289jta+hwXrJrQtBZSKoJQacmvkns9gW0NzXxNSHoLCTVhKBTE98kdvsC2pua+JoQdCLC6kDK2UlwBrt9Nr3NToIdSDkXeTUh6OwkOJzdPpXGZifBJgSdi7yaEHR2EhzObp9KY7OTYBOCzkVeTQi6ACGOZbfPo6sFCLEJQeciryYEXYAQx7Lb59HVAoTYhKBzkVcfsi5AiAPZ7ZNoaQFC7EPW6YisCUEXIMSB7PZJtLQAITYh6Iyk1oSga5DjKHb7DPpZgxybEHRGUmtC0GWIcgi7fTjNLEOUTQg6I6n1Iesa5DiE3T6cZtYgxz5knZHU+pB1GaK8zm4fSyfLEGUfss5Ian3IuhJpXmS3D6SNlUizD1knJbg+ZF2GKC+y2wfSxjJE2Yes85JdH7KuRJpX2O2j6GEl0uxD1nnJrg9ZFyPQ0+z2ITSwGIH2Ieu8ZNeHrOuR6Tl2+3W6V49M+5B1XrJrRdzFCPQcu/063StGoK2IOy/ZtSLuemR6gt1+kdbVI9NWxJ2a+PqQdUli/ZbdfoW+lSTWPmSdnQRbEXdJYv2K3X6appUk1lbEnZ0EWxF3SWL9it1+mqaVJNZWxJ2dBFsRd1WSPc5uP0fHqpJsK+LOToLdSLwqyR5kt5+gXVVJthuJFyDEVsRdmHCPsNu/pVeFCbcVcdcgx1bEXZt8P7Lbv6JRtcm3FXHXIMduJF6bfN+z24/Tpdrk243Ea5BjNxIvT8Rv2O0HaVF5Iu5G4jXIsRuJlyfiN+z2g7SoPBF3I/EyRNmNxMsT8St2+xH6U56Iu5F4JdLsRuIdSPlPdvtHmtOBlLuReCXS7EbiTQj6md3+ns40IehuJF6JNBsSehOC/sVuf0NbmhB0Q0IvRqDdSLwPWT+y21/Rkz5k3Y3E65FpNxJvRdx3dvufNKQVcXcj8Xpk2pDQW/n3H7u/iv3s9l8UoxtxNyT0emTakNAbErrd/kgfGhJ6Q0KvR6Y9yb2h5qHb7XfNm9CT0HuSe0libUjoPXXO3W6/6dyBzuTekNCrkmxDQm+rbfR2+0/j9BF9Q0KvSrI9yb2tntHb7T1z50f0Xcm9Ksn2JPfOGv5bIp13e8O4eST9nuRemHB7kntzrQrQdre3SplnCtCT3GuTb09yp08Heu72Pvnyig70JPfa5NuT3Plp80MU3XZ7k1j5SA16kntt8m1L9NyUb0Kr3V4+TQ7ShLZEX56Ie5I7d7XL0Ge3186RryhDT3LvQMptiZ67wj9c0WG3F46PE5ShLdF3IOW2RM8vJStRfreXTI0rVKIt0Xcg5c6kzy/1PrktvNvrhcV1KtGZ9JsQdFui50+VilF1t1fKiIEUoy3R9yHrtkTPK2U+y62328tEwwy60Zbo+5B1Z9LnjQITsdJuLxAHU6lHZ9JvRdxtiZ6PUs/FGrs9dQQsoyRtib4biXcmfY5I2pMCuz3pd57F9KQz6Xcj8c6kz0EZP/VNvdszfsPZRVU6k343Em9OATgu15hMuttzfZPZTluaU4CGhN6Z9PlWlmGZbrdn+cYSis50Jv2e5N6cAnDCv//a/YW8lGW3x/9OEpbaNKcAPcm9OQXgirCbM/5uD/utIwv9aU4B2hJ9cwrARQEnaOTdHvDbRToq1JwCdCb95hSAIUL9yEfA3R7q+0N2itScAnQmfXSAgSIM1Di7PcJ3g2LUCR3oTProADNsnKzbd7u5zjx6hQ40pwDoAPOsH7G7dru5zmzahQ6gA+gACyybtYt3u7nOMmqGDqAD/KgBC/17MOO/v2C3z/4twDNl40cN+A81QAfY4t//GvLfnLHbZ3yd8BXFQwe40QR+1IAA/j058R+5vtuHfBkwkBLyowb8lybwowaE9DyhPw7p47v9xH8ctlBLftSAB8rAjxqQx6vJ/ZXdvwk4RFf5UQP+lz5wowlkt/3fb4eB3MncaAKP9IEbTSA7u51K3MncaAK/qAQ3mkBqdjtluI250QSeaQU3mkBqdjtluI250QSeaQV3ykBedjs1uIe5Uwb+pBjcaAJ52e3U4B7mRhN4RTe4UwaSstspwA3MnTLwim7wSB/IyG4nO3cvj/SBN9SDO2UgI7ud7Ny93CkD72kIj/SBdOx2UnPr8kgfeE9DeKQPpGO3k5pbl0f6wEdKwiN9IBe7nbzctzzSB47QE35RCRKx20nKTcsvKsEResIvKkEidjtJuWn5RSU4SFX4RSXIwm4nI3csv6gEx2kLz7SCFOx20nG78kwrOE5beKYVpGC3k47blWdawVcUhmdaQXx2O7m4V3mmFXxLZ/iTYhCc3U4iblT+pBicoDY80wqCs9tJxI3KM63gHM3hT4pBZHY7WbhL+ZNicI7m8IpuEJbdTgpuUV7RDU5THv6kGIRlt5OCW5Q/KQZX6A+v6AYx2e3E5/7kFd3gCv3hDfUgILud4NycvKEeXKRCvKIbBGS3E5ybk1d0g+u0iDfUg2jsdiJzZ/KGejCEIvGGehCK3U5YbkveUA9G0SXe0xDisNuJyT3JexrCKLrEexpCHHY7MbkneU9DGEideE9DCMJuJyA3JO9pCGNpFB8pCRHY7UTjbuQjJWE4peIjJWE7u51Q3Ip8pCTMoFd8pCRsZ7cTiluRj5SEGfSKI/SEvex24nAfcoSeMIlqcYSesJHdThBuQo7QE+bRLg5SFXax24nAHchBqsJUCsYResIudjsRuAM5Qk+YTcc4SFXYwm5nO7cfB6kKC6gZB6kK69nt7OXe4yBVYQ1N4zhtYTG7nY3ceBynLayhaXxFYVjJbmcXdx1fURiWUTaO0xZWstvZxV3HcdrCSvrGVxSGZex2tnDL8RWFYTGV4ysKwxp2O+u53/iKwrCe1vEtnWEBu53F3Gx8S2fYQvH4ls4wm93OSu40vqUz7KJ7nKA2TGW3s4zbjBPUho3UjxPUhnnsdtZwj3GC2rCXBnKO5jCJ3c4CbjDO0Ry2U0JOUBsmsdtZwA3GCWpDBHrIOZrDDHY7s7m7OEdzCEIVOUdzGM5uZyq3FudoDnFoI6cpD2PZ7czjvuI05SEObeQK/WEgu51J3FRcoT+EopBcoT+MYrczgzuKK/SHaHSSi1SIIex2hnM7cZEKEZBacpEKcZ3dzljuJS5SIWLSTK7TIi6y2xnIjcR1WkRYysl1WsQVdjujuIu4TouITD8ZQpE4zW5nCLcQQygSwakoQygS59jtXOf+YQhFIj4tZRRd4gS7nYvcPIyiS6SgqIyiS3zLbucKdw6j6BJZ6CoDqRNfsds5zW3DQOpEIurKQOrEcXY757hnGEidyEVjGUujOMhu5wQ3DGNpFOkoLWNpFEfY7XzL3cJYGkVGestwSsVHdjtfcaswnFKRlOoynFLxnt3Oce4ThlMqUlNghvv3H7u/CoKy2znCNcIMSkV2OswkqsWf7HY+cnswiWpRgBoziWrxzG7nPfcGk6gWNWgy82gXv9jtvOHGYB7togxlZh7t4pHdzivuCubRLirRZ6ZSMO7sdv7klmAqBaMYlWYqBePGbueZ+4GpFIx6tJrZ/MNu/Njt/C/XAgvoGCUpNguoWXN2O3duAxZQM6rSbdbQtM7sdm7cA6yhaRSm3qyhaW3Z7fy4AVhF0yhPyVnDz7X2ZLc35+CzjKbRgZ6zkr51Y7d35ryzkr7RhKqzkr61Yre35aSzkr7Rh7azmD8678Nub8gBZz2VoxWFZz2t68Bu78a5Zj2toxudZwvFK89ub8WJZgvFoyG1Zwt/pF6b3d6Eg8wuikdbys8uuleV3d6B88suukdn+s9G6leS3V6ek8tG6kdzjgAb+aP2euz2whxY9lI/cArYTgkrsdurck7ZTgnhx0EgAJ/jlWG31+N4EoESwp3jQAR6WIDdXoxTSQR6CI+cCILwyV52dnsZDiNxqCL84lAQhzbmZbfX4AwShzbCnxwN4vBZX1J2e3aOHqFoI7zidBCNTqZjt6fmxBGNTsIbDgjR+PQvF7s9KQeNgHQS3nNGiEkzs7DbM3K+iEkz4SPHhJh8HpiC3Z6LY0VYmgkHOSyEZWYEZ7dn4SgRmXLCcc4LwaloWHZ7Ck4QwakofMWRITifFsZktwfn4BCfisIJDg7xGSHR2O1hOSykoKVwjrNDFgZJHHZ7QA4IiegqnOb4kIi6RmC3R+NckIi6wkUOEYn4XHE7uz0Ox4Fc1BWuc45Ix1zZyG6PwBEgI6WFIRwlMjJdtrDb91J7ktJbGMiBIikzZjG7fRdVJy/VhbGcKVIzaZax29dTb7JTYBjOsSI782YBu30llaYAHYZJHC4KMHWmstvXUGNqUGOYx/miDLNnErt9NtWlEmWGqRwxKjGBhrPb51FXitFnWMBBo5h//7X7C6nAbh9OPylJpWENZ42qrKPr7PaBFJLCdBuWcdwozFi6wm4fQgmpTb1hMYeO2vxwwjl2+xVaRwcaDls4enRgR33Fbj9HzWhCz2EXp48+fBB6kN3+Fb2iG22HjRxAurGy3rPbD1IkGtJ52M4xpCEfk75it7+nObSl9hCEw0hbZtgvdvuf9ITmlB/icB7BMLux2x9pBdw4BRCKIwk3zaea3f7TvgPwi7MAATmY8KjneOu823smDu85ERCW4wnPWs25hru9Vb7wFecCInNC4Y1/D3Z/LbM02e0dooTrHBAIziGFI6quvtq7vWpqMIOTAik4qnBcsU9u6+32YgHBGs4LJOLAwgkFJmKN3V4gCNjIwYFcnFm4KOl0zLvbk37DISCHCNJxbGGUf/9r95fzTqLdnui7Cok4TZCUwwszRB6ckXd75O8b1OBkQWqOMMz278nGLybObg/1bYEOnDLIzimG9Z4n67KTuGW3b/z9AnfOHRTgIEMEf47b4cdz6m5f81sATnASoQzHGcJ6NYbPDePTu33slwGs5HhCMQ415HVkVF+0+7cInOT8QkmONpQX5++lAgt42aEwBxxqs9uhD2861OaMQ212O/ThTYfyHHMozG6HJrzm0ITDDlXZ7dCBdxxaceShJLsdyvOCQ0MOPtRjt0Nt3m5oy/GHYux2KMyrDZ25AaAYux0K82pDcy4BqMRuh6q818CPqwAKsduhJC81cOdCgBrsdqjHGw384lqAAux2KMbrDPzJ5QDZ2e1QiXcZeMMVAanZ7VCGFxn4yEUBedntUIO3GDjCXQF52e1Qg7cYOMh1AUnZ7VCAVxj4iksDMrLbITvvL3CCqwPSsdshNS8vcJoLBHKx2yEvby5wkWsEErHbISmvLTCEywSysNshI+8sMJArBVKw2yEdLywwnIsF4rPbIRdvKzCJ6wWCs9shEa8qMJVLBiKz2yEL7ymwgKsGwrLbIQUvKbCMCwdistshPm8osJhrBwKy2yE4ryewhcsHorHbITLvJrCRKwhCsdshLC8msJ2LCOKw2yEmbyUQhOsIgrDbISCvJBCKSwkisNshGu8jEJCrCbaz2yEULyMQlgsK9rLbIQ5vIhCcawo2stshCK8hkILLCnax2yEC7yCQiCsLtrDbYTsvIJCOiwvWs9thL28fkJTrCxaz22Ejrx6QmksMVrLbYRfvHVCAqwyWsdthCy8dUIYLDdaw22E9bxxQjGsNFrDbYTGvG1CSyw1ms9thJe8aUJgrDqay22EZLxpQ3r//2P1VQE12OyzgIQNacePBDHY7zOb9Ahpy9cFwdjtM5eUC2nIBwlh2O8zjzQKacw3CQHY7TOK1AvhxGcI4djvM4J0CuHMlwhB2OwznhQL4xT+rBdfZ7TCQhwngDTckXGG3wyjeI4CPXJVwmt0OQ3iJAA5yYcI5djtc5w0C+IprE06w2+Eirw/ACf5CEHzLbofTPDoAF7lF4Ti7Hc7x1gAM4TqFg+x2OMErAzCQSxWOsNvhW94XgOFcrfCR3Q5f8bIATOIvDcF7djsc5EEBWMBNC6/Y7XCEdwRgGVcu/Mluh4+8IACL+SNOeGa3wxseDoCN3MDwyG6HV7wXANu5iuHOboc/eSkAgvBHn3Bjt8MvHgiAgNzMYLfDI+8CQFiuaJqz2+HOiwAQnD8SpTO7HX48BACpuLHpyW4H9z9AOq5uGrLbac7ND5CUPyqlG7udtlz4AAW4yenDbqcn9zxAGa50mrDbacgND1CMP0KlA7udVlzsAIW54anNbqcP9zlAeT6foTC7nQ5c4wCtuPMpyW6nPLc3QEM+saEeu53CXNoAzXkFqMRupyp3NQA/PsOhELudelzRAPziXaAAu51i3MwA/MmnOmRnt1OGCxmAj7wU5GW3U4N7GICDfM5DUnY72bl+ATjB20E6djupuXUBOM0nP+Rit5OUyxaAITwoZGG3k44LFoDhvCzEZ7eTi3sVgEl8LkRwdjtZuE4BWMBbQ1h2Oym4RQFYxidFxGS3E5zLE4AtPEBEY7cTlgsTgO28RMRhtxOTexKAIHyORBB2O9G4HgEIyPPEdnY7cbgSAQjOU8VGdjsRuAYBSMSbxRZ2O9u5/QBIxydOrGe3s5FLD4DUPGSsZLezhYsOgDI8aqxht7OYyw2AkjxwzGa3s4wLDYDyPHbMY7ezgEsMgFa8esxgtzObuwuAhnxmxXB2O/O4sgBozlPIQHY7M7imAODOs8gQdjtjuZoA4E+eSC6y2xnFdQQAH3kuOc1u5zpXEAB8xdPJCXY7V7h2AOA0zyhfsds5x1UDAEP8+6/dXwjR2e18xd0CAJN4YXnPbucglwkALODB5RW7nY9cIACwmMeXZ3Y7b7g0AGAjDzGP7Hb+5KIAgCA8ytzY7fzicgCAgPzTENjt3LgNACAF73VbdjuOPwCk4/luyG7vzJEHgNT8cXkrdntDzjgAFONl78Bub8WhBoDCfDRXm93egVMMAK1490uy22tzbAGgLR/cFWO3l+ScAgB3hkENdnslTiUA8IadkJrdXoNjCAAc5IO+pOz21Jw7AOA0QyIXuz0jpwwAGMi0SMFuT8SZAgCmMjYis9vjc4IAgMXMj4Ds9rCcFwBgO4MkDrs9GqcDAAjIRNnObg/CWQAAUjBadrHb99J8ACCpfw92fy0t2O3rKTkAUIxts4Ddvow+AwDl+XxyHrt9KtUFANqygsay22fQUgCAO59kDmG3j6KQAAAfmUyn2e1XKB4AwGmm1Ffs9m8pGADAcP/+1+4vJyK7/SMtAgBYzPp6Zrf/SVUAAILwIeqN3X6jDwAAKbSdbW13e9vEAQAq+fdk91c0S5Pd3idQAIDmqg6/kru9algAAJxQYxwW2O01ggAAYJnnARl/Riba7Rm/vQAAJBJ5cAbc7ZG/XQAANPRqoK6cqVt2e4TfOAAADPF+3I6au0N2+5ovFQAACvh2PA+0+7cOAADFBfz5dgAA4Be7HQAA4rPbAQAgPrsdAADis9sBACA+ux0AAOKz2wEAID67HQAA4rPbAQAgPrsdAADis9sBACA+ux0AAOKz2wEAID67HQAA4rPbAQAgPrsdAADis9sBACA+ux0AAOKz2wEAID67HQAA4rPbAQAgPrsdAADis9sBACA+ux0AAOKz2wEAID67HQAA4rPbAQAgPrsdAADis9sBACA+ux0AAOKz2wEAID67HQAA4rPbAQAgPrsdAADis9sBACA+ux0AAOKz2wEAID67HQAA4rPbAQAgPrsdAADis9sBACA+ux0AAOKz2wEAID67HQAA4rPbAQAgPrsdAADis9sBACA+ux0AAOKz2wEAID67HQAA4rPbAQAgPrsdAADis9sBACA+ux0AAOKz2wEAID67HQAA4rPbAQAgPrsdAADis9sBACA+ux0AAOKz2wEAID67HQAA4rPbAQAgPrsdAADis9sBACA+ux0AAOKz2wGq+n97oswmCmVuZHN0cmVhbQplbmRvYmoKNCAwIG9iago8PC9UeXBlL1hPYmplY3QvU3VidHlwZS9JbWFnZS9XaWR0aCA2MDAvSGVpZ2h0IDI1MC9MZW5ndGggMTY4L0NvbG9yU3BhY2UvRGV2aWNlR3JheS9CaXRzUGVyQ29tcG9uZW50IDgvRmlsdGVyL0ZsYXRlRGVjb2RlPj5zdHJlYW0KeJztwTEBAAAAwqD+qWcLL6AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAeBiXOsg6CmVuZHN0cmVhbQplbmRvYmoKNSAwIG9iago8PC9UeXBlL1hPYmplY3QvU3VidHlwZS9JbWFnZS9XaWR0aCA2MDAvSGVpZ2h0IDI1MC9TTWFzayA0IDAgUi9MZW5ndGggMjU4MS9Db2xvclNwYWNlL0RldmljZVJHQi9CaXRzUGVyQ29tcG9uZW50IDgvRmlsdGVyL0ZsYXRlRGVjb2RlPj5zdHJlYW0KeJzt3NGS5LYNBdD+/5/e2Bk/dG2vtCBIUFDrnPJDahoiIQybN1nb+fULAAAAAAAAAAAAAACAOa/X1R0AwEX+CUE5CMBjyUEAHusnBEUhAM8kBwF4rPf4E4UAPI0cBOCxPoNvVxS+wt7rR9evro90Pi+y/ug8R3eZWX+0E4B95OBc/Wgi5ETWH53n6C4z6492ArDJUeRticL4TZi7OXP1o12tXXN0l8+cGl0z0uFRTXwCuU8BysnBiV3koBwE7u087OqjsGcOxp9dm5jz68/kYK6fVRknDYFryMHjejkY6UcOAjcWibniKOycg5EV9vezqj43z89d1s4HYCs5GKiXg+e7yEHgruIBVxmFkXsyfuvm1j+vvzYHR5/qloOjXQHsIwdj9XLwfBc5CNzSaLSVReEr7L1+dP35+tGfr+rn89mjyZzXrJrn51PxFSL9A2wiBwfrR3++qp/PZ89zpHqen0/FV4j0D7BDLtRqojB3i1asH6k/yp26fkbX3NPPfJLO9wCQJwez9XJwfsdVPQAkzcRZQRTKwVX2zOfzqVVvJA2BTeTgRL0c/HxKDgJ3Mh9kq6PwXjn4WTPaf7yT3Pp7dok/Wz0fgDFycEW9HIw/KweBRlZF2NIovGMOjvYzk1CRrnK5PNrhUc3of3MY/RRgGTl4UT9yUA4C11v755nrVnuFxes/1x/tZ23/8dVye830n9slt2P1fADOyMGRftb2H18tt9dM/7ldcjtWzwfgUMG/71CyJgBUkIMAPFZdYIlCAPr7Sau6vwCgreoQFIUAdLYnB0UhAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAcOD163XJX1e/NwD8Sw4C8GRyEIAnk4MAPJkcBODJ5CAATyYHAXgyOQjAk8lBAJ5MDgLwZHIQgCeTgwA8mRwE4MnkIABP1jkHX2G5+shey+YcWD/e/4zRnldNYJXce8XfZX5uHUTeYvTMzMyh5wzjJ6fiPMQnH6/vOef+5OD5s8vmHFg/3v+M0Z5XTWCV3HvF32V+bh1E3mL0zMzMoecM4yen4jzEJx+v7znn/vrn4Exl/JzP9zC//uheufr5qV5l5jfV4STsUTel3E3b836OdHXtzfBec/dvbn9y8NrT/v5p7oYZrb/vt6n61qo+CXvUTWn0/Mw8VS3S1bU3w3vN3b+5/T05B6vP1ej6uRsmVz+fC/vlfpurfsvdpvFpz/cld+r6TC/+Lag+D3X3Q59p34UcXNvJzPq5GyZXH3m227cp99tc9VvuNo1Pe74vuVPXZ3rxb0H1eai7H/pM+y7kYGT9nJ25lqtfO59XWHzN+W5X3R7975bqm/n908heufq683Pe4dpP53efqe9/VruRg5H1c+rO+ar6tfOpvsdy3a66PfrfLdU38/unkb1y9XXn57zDtZ/O7z5T3/+sdvPkHBzdJafu9M7X3+vmz3W7av7dpnFkps/4BEa/WT2n93052HPO/clBObhq/WpyMEIOxslBfvTPwYjzFWb2is5xxfqj75urP+ot/vOrnPdzNM+69Ts7PwnnT8U/jZ+cntOb+Y3Pv1HufEbMdPVMcvB8r+gcV6w/+r65+qPe4j+/ynk/R/OsW7+z85Nw/lT80/jJ6Tm9md/4/BvlzmfETFfP1D8Hp96uwQrx9dfe26P1/e+u6v7r7r0+t9bMO8Yn3P8s/Vg7jbW7z9T3nHZncrB6hfj6dd+LSH3/u6u6/7p77zgDd0947c1/VN//LP1YO421u8/U95x2Z3Jw5zrn69d9LyL1q3IkIr7m/v5zn97L0bvkJvD+89zK7zV152e0q+rzsPZ8zqyMHNy5zvn6dd+LSP3np7n16+6xPf3nPr2Xo3fJTeD957mV32vqzs9oV9XnYe35nFmZJ+dg9bkaXb/uexGvj9xpVxn9ba6dT7dpfJo/z7kJRM5Mz+ndJQdH74ee0+5MDtZ1IgfXkoPn5OAoOciPJ+dgpKa6h5ncqasf/d7tUTfJ0fU7q7vbr02NCv1z8PVh1cq8k4Ny8Kiy27dJDkb0TLqe05OD/JCD75W5U7dq/aOatUZ7XjWBVeKTrFu/v9xbnNfUfXqV+EwqzsPRyuenevQpIuTge2XdiVp7zmeM9rxqAqvEJ1m3fn+5tzivqfv0KvGZVJyHo5XPT/XoU0R0zkEAqCYHAXgyOQjAk8lBAAAAAAAAAAAAAAAAAAAAAIDb++3/4uXr9wWAd5fkkRAEoI/NqSQEAehmWzYJQQB62pBQQhCAzkpzSggC0F9RWglBAO5ieWYJQQDuZWFyCUEA7mhJfglBAO5rMsWEIAB3l84yIQjAd0gkmhAE4JsM5ZoQBOD7BNNNCALwrf6acTUh+ArL1Uf2mu8/V597Nt5/pD4+z7j4G10l13n87e47GXi6k6Qr+1+Co7frzG1ccTuNrvD5LqPPxvuP1MfnGRd/o6vkOo+/3X0nA/w57yr/OHT0bsmtcF4zc0eN3nK5u3G0/3j96LvHp93z5p85CdeeNGCf31Kv+O8JysH4U/FP5eAROQiE7ArBX/U5uCprzp+qy8HcyhX1uX7iK++ROy2r3rrnTIA/2xKCv+SgHNxLDgJRz8vBnPf1R3NtbQ5+U31EfM14P3IQ+M8j/1w0Rw5W1MtB4Eon/5BMwVe4OgdHdxlVnYOjlX1WztVXk4PAX/wx9SqjUA6O7rK2Xg5GPpWD8BQneVcWha+w8xVm9prvP9LP5465TBmdzHl9bPazPcfrq0UmFq+fXx9o5K9JVxOFo/fw0Qoze833H+lnVaaMTua8Pjb72Z7j9dUiE4vXz68PdBHMuIIonL8Zrl0hfnPOZ0q8h3h9da51u/nlIPAHQ+m2OgrlYG7fyI6Rejl4/qkchO+XyLWlUdghB2fWOU+9+H+el7uxu+XgKyy+ZrwfOQiPk060dVEoB7P9Rjs5r5eD55/KQfhmk1m2KAqrczC+fs8cHO2/rj7XT3zlPeQg8J8lKbZiETm4qis5GCEHgX8t/Bt800tV52CkZqaHmXt1VaaM3t65HuL1uYTdo24yo+sDl1kYgisWlIPVu5zXy8GjT+UgfKflITi97J4cfK/8VLf7qhx8r4/3H6k/qpkRf6OrxCdTtz5wgaIQnFt8/n6Ir1BxO52vcH67ju4+2n/8tl8r/kZXiU+mbn1gt9IQ3LYFACRsSyhRCEA3m7NJFALQxyWp9Num0hAAAAAAAAAAAAAAAAAAAAAAAAD4v/8Bw2H7lQplbmRzdHJlYW0KZW5kb2JqCjYgMCBvYmoKPDwvTGVuZ3RoIDU4Ni9GaWx0ZXIvRmxhdGVEZWNvZGU+PnN0cmVhbQp4nJ2VWXPaMBDH3/0p9rF9iKPDF34DDGk6hRz2kGcBwqjx0dhK2+TTd22SBiaRYOJjrLF3f7v/XUl+cB6AhwwInt2TUgacMFiVcK7KnEJSww3aMC/qbSjhLufABhHQMHo14zszNHRGmUMgIgFka2eS9a+6m8F3fH+BYzSgOxT4DEIEZaVzPqWIhmyDhlnjEIyBB/xxSG96e/EyaHLny1yUMgYtWw2wXC6/Zj/xY45354ghKLph5L04LAxcHn0mFmTbRrbbulifEManxGX0M2EmT6joKjklBpYs8g75b5xXwHufkJh8Ut0ILfOnGC4rLRux0uq3hKnA8r4Tf8jt5okVXEKqnlEZ/9jfY6HNP7kaxUAHgX9GKF5mbX5gQkzVX6FVXUEmmlzqGMayQrmFRY8Z1ukZ10XdxHC3VVpaRJkhl0lsFuKxo0JmdaV0l8KoUNUa0l81tgmXgkWRmTq6zyHBIvPAosXsnuC8sahhkcnx5hEzRjUtDNt7uY6BWNI3Y7r0XxoyrytbP8yMTJU2DdS41LqiQ/LY9G0xEPr8zYjxZI6ZDy15m30XwxgYOTcUrkudGBfW/8n0o25b2WL5TZxegAUkilbCdd2qbs+wcHoxxzhzmYuPOPYfCA2Ma+1WFkosVaE0bm7fVL49DeqFzMVf28BY+9ks2d/RfIKNgtLxd4PCSY+k/LY7RIfga1GU6UpUsJgyguU6g/QOFrJpuykG1GUuZQNDp2jgUm5Gp1psNjE0sDV0yB+4IfhGzRGNPM49b7DfmX9bQtTMCmVuZHN0cmVhbQplbmRvYmoKOCAwIG9iago8PC9UeXBlL1BhZ2UvTWVkaWFCb3hbMCAwIDU5NSA4NDJdL1Jlc291cmNlczw8L1Byb2NTZXQgWy9QREYgL1RleHQgL0ltYWdlQiAvSW1hZ2VDIC9JbWFnZUldL0ZvbnQ8PC9GMSAxIDAgUj4+L1hPYmplY3Q8PC9pbWcwIDIgMCBSL2ltZzEgMyAwIFIvaW1nMiA0IDAgUi9pbWczIDUgMCBSPj4+Pi9Db250ZW50cyA2IDAgUi9QYXJlbnQgNyAwIFI+PgplbmRvYmoKMSAwIG9iago8PC9UeXBlL0ZvbnQvU3VidHlwZS9UeXBlMS9CYXNlRm9udC9IZWx2ZXRpY2EvRW5jb2RpbmcvV2luQW5zaUVuY29kaW5nPj4KZW5kb2JqCjcgMCBvYmoKPDwvVHlwZS9QYWdlcy9Db3VudCAxL0tpZHNbOCAwIFJdL0lUWFQoNS4wLjUpPj4KZW5kb2JqCjkgMCBvYmoKPDwvVHlwZS9DYXRhbG9nL1BhZ2VzIDcgMCBSPj4KZW5kb2JqCjEwIDAgb2JqCjw8L1Byb2R1Y2VyKGlUZXh0U2hhcnAgNS4wLjUgXChjXCkgMVQzWFQgQlZCQSkvQ3JlYXRpb25EYXRlKEQ6MjAyMTAyMTEyMTI2MTYtMDgnMDAnKS9Nb2REYXRlKEQ6MjAyMTAyMTEyMTI2MTYtMDgnMDAnKT4+CmVuZG9iagp4cmVmCjAgMTEKMDAwMDAwMDAwMCA2NTUzNSBmIAowMDAwMDE5NDE5IDAwMDAwIG4gCjAwMDAwMDAwMTUgMDAwMDAgbiAKMDAwMDAwMTE2NCAwMDAwMCBuIAowMDAwMDE1NDgwIDAwMDAwIG4gCjAwMDAwMTU4MDQgMDAwMDAgbiAKMDAwMDAxODU1MyAwMDAwMCBuIAowMDAwMDE5NTA3IDAwMDAwIG4gCjAwMDAwMTkyMDYgMDAwMDAgbiAKMDAwMDAxOTU3MCAwMDAwMCBuIAowMDAwMDE5NjE1IDAwMDAwIG4gCnRyYWlsZXIKPDwvU2l6ZSAxMS9Sb290IDkgMCBSL0luZm8gMTAgMCBSL0lEIFs8ZWZmZDBiODNlZjUwN2MyOThjMWJmYjRlZTRkNTBjMTM+PDI4MTlkODhjZDYzNTRhMjRjNzM1OTEyYmJjMGQ4OWFmPl0+PgpzdGFydHhyZWYKMTk3NTEKJSVFT0YK"}';
				$data_arrays = json_decode($payload, true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'pointData';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				$data_arrays['test_report_id'] = 1;
				//echo "<pre>";
				//$response['request_data']=$data_arrays;
				//pr($data_arrays['vfpointdata']);die;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "pointData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['Pointdata']['test_id'] = isset($data_arrays['test_id']) ? $data_arrays['test_id'] : '';
					$data['Pointdata']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
					$data['Pointdata']['numpoints'] = isset($data_arrays['numpoints']) ? $data_arrays['numpoints'] : '';
					$data['Pointdata']['color'] = isset($data_arrays['color']) ? $data_arrays['color'] : '';
					$data['Pointdata']['backgroundcolor'] = isset($data_arrays['backgroundcolor']) ? $data_arrays['backgroundcolor'] : '';
					$data['Pointdata']['stmsize'] = isset($data_arrays['stmSize']) ? $data_arrays['stmSize'] : '';
					$data['Pointdata']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['Pointdata']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['Pointdata']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['Pointdata']['master_key'] = isset($data_arrays['master_key']) ? $data_arrays['master_key'] : '';
					$data['Pointdata']['eye_select'] = isset($data_arrays['eye_select']) ? $data_arrays['eye_select'] : 0;
					if ($data['Pointdata']['eye_select'] == "") {
						$data['Pointdata']['eye_select'] = 0;
					}
					$data['Pointdata']['test_type_id'] = isset($data_arrays['test_type_id']) ? $data_arrays['test_type_id'] : '';
					$data['Pointdata']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : '';
					$data['Pointdata']['mean_dev'] = @$data_arrays['mean_dev'];
					$data['Pointdata']['pattern_std'] = @$data_arrays['pattern_std'];
					$data['Pointdata']['mean_sen'] = @$data_arrays['mean_sen'];
					$data['Pointdata']['mean_def'] = @$data_arrays['mean_def'];
					$data['Pointdata']['pattern_std_hfa'] = @$data_arrays['pattern_std_hfa'];
					$data['Pointdata']['loss_var'] = @$data_arrays['loss_var'];
					$data['Pointdata']['mean_std'] = @$data_arrays['mean_std'];
					$data['Pointdata']['psd_hfa_2'] = @$data_arrays['psd_hfa_2'];
					$data['Pointdata']['psd_hfa'] = @$data_arrays['psd_hfa'];
					$data['Pointdata']['vission_loss'] = @$data_arrays['vission_loss'];
					$data['Pointdata']['false_p'] = @$data_arrays['false_p'];
					$data['Pointdata']['false_n'] = @$data_arrays['false_n'];
					$data['Pointdata']['false_f'] = @$data_arrays['false_f'];
					$data['Pointdata']['ght'] = @$data_arrays['ght'];
					$data['Pointdata']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['Pointdata']['threshold'] = @$data_arrays['threshold'];
					$data['Pointdata']['strategy'] = @$data_arrays['strategy'];
					$data['Pointdata']['test_color_fg'] = $data_arrays['test_color_fg'];
					$data['Pointdata']['test_color_bg'] = $data_arrays['test_color_bg'];
					$data['Pointdata']['latitude'] = @$data_arrays['latitude'];
					$data['Pointdata']['longitude'] = @$data_arrays['longitude'];
					$data['Pointdata']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['Pointdata']['version'] = @$data_arrays['version'];
					$data['Pointdata']['diagnosys'] = @$data_arrays['diagnosys'];
					// $count_baseline = $this->Pointdata->find('count',array(
					// 	'conditions'=>array(
					// 		'test_name'=>$data['Pointdata']['test_name'],
					// 		'eye_select'=>$data['Pointdata']['eye_select'],'patient_id'=>$data['Pointdata']['patient_id'],'Pointdata.baseline'=>'1'
					// 	)
					// ));
					// if($count_baseline<2){
					// 	$data['Pointdata']['baseline'] = 1;
					// }
					$data['Pointdata']['baseline'] = (isset($data_arrays['baseline']) && !empty($data_arrays['baseline'])) ? $data_arrays['baseline'] : 0;
					//pr($count_baseline);die;
					$result = $this->Pointdata->save($data);
					$lastId = $this->Pointdata->id;
					if ($result) {
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						if ($this->ReportRequestBackup->save($result2)) {
						}
						if (!empty($data_arrays['file'])) {
							$response['pdf'] = WWW_BASE . 'apisnew/fileDownloadUrl/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						//$pdata ="";
						foreach ($data_arrays['vfpointdata'] as $pdatas) {
							$pdata['VfPointdata']['report_id'] = @$data_arrays['test_report_id'];
							$pdata['VfPointdata']['point_data_id'] = @$lastId;
							$pdata['VfPointdata']['x'] = isset($pdatas['x']) ? $pdatas['x'] : '';
							$pdata['VfPointdata']['y'] = isset($pdatas['y']) ? $pdatas['y'] : '';
							$pdata['VfPointdata']['intensity'] = isset($pdatas['intensity']) ? $pdatas['intensity'] : '';;
							$pdata['VfPointdata']['size'] = isset($pdatas['size']) ? $pdatas['size'] : '';
							$pdata['VfPointdata']['zPD'] = isset($pdatas['zPD']) ? $pdatas['zPD'] : '';
							$pdata['VfPointdata']['STD'] = isset($pdatas['STD']) ? (float)$pdatas['STD'] : '';
							$pdata['VfPointdata']['index'] = isset($pdatas['index']) ? $pdatas['index'] : '';
							$pdata['VfPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
							//pr($pdata); die;
							$this->VfPointdata->create();
							$result_p = $this->VfPointdata->save($pdata);
						}
						$response['message'] = 'Success.';
						$response['result'] = 1;
						//update credits------
						/*$this->loadModel('User');
						$this->User->id = $data['Pointdata']['staff_id'];
						$credits = $this->User->field('credits');
						$new_credit = $credits-1;
						$this->User->updateAll(array('User.credits'=>$new_credit),array('User.id' =>$data['Pointdata']['staff_id']));*/
					} else {
						$response['message'] = 'Some error occured in updating report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	public function fileDownloadUrl($pdf = null)
	{
		$fileName = $pdf_file = '../../../../inetpub/wwwroot/portalmi2/app/webroot/pointData/' . $pdf;
		if (file_exists($fileName)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header("Content-Transfer-Encoding: Binary");
			header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($fileName));
			while (ob_get_level()) {
				ob_end_clean();
			}
			readfile($fileName);
			exit;
		}
	}
	/****************************pointData *********************************/
	/******************************pointData backup *********************************/
	public function pointData_v2()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				//$data_arrays  =  $this->request->data;
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$data_arrays['test_report_id'] = 1;
				if (!empty($data_arrays['pdf'])) {
					$foldername = "pointData";
					$imgstring = $data_arrays['pdf'];
					$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername);
				}
				$data['Pointdata']['report_id'] = @$data_arrays['test_report_id'];
				$data['Pointdata']['numpoints'] = isset($data_arrays['numpoints']) ? $data_arrays['numpoints'] : '';
				$data['Pointdata']['color'] = isset($data_arrays['color']) ? $data_arrays['color'] : '';
				$data['Pointdata']['backgroundcolor'] = isset($data_arrays['backgroundcolor']) ? $data_arrays['backgroundcolor'] : '';
				$data['Pointdata']['stmsize'] = isset($data_arrays['stmSize']) ? $data_arrays['stmSize'] : '';
				$data['Pointdata']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
				$data['Pointdata']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
				$data['Pointdata']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
				$data['Pointdata']['master_key'] = isset($data_arrays['master_key']) ? $data_arrays['master_key'] : '';
				$data['Pointdata']['eye_select'] = isset($data_arrays['eye_select']) ? $data_arrays['eye_select'] : '';
				$data['Pointdata']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : '';
				$result = $this->Pointdata->save($data);
				if ($result) {
					if (!empty($data_arrays['file'])) {
						//$response['pdf'] = WWW_BASE . 'apisnew/fileDownloadUrl/' . $data_arrays['file'];
						$response['pdf'] = WWW_BASE . 'pointData/' . $data_arrays['file'];
						$response['new_report_id'] = $lastId;
					} else {
						$response['pdf'] = '';
					}
					$lastId = $this->Pointdata->id;
					//$pdata[] ="";
					if (!empty($data_arrays['vfpointdata'])) {
						foreach ($data_arrays['vfpointdata'] as $pdatas) {
							$pdata['VfPointdata']['report_id'] = $data_arrays['test_report_id'];
							$pdata['VfPointdata']['point_data_id'] = @$lastId;
							$pdata['VfPointdata']['x'] = isset($pdatas['x']) ? $pdatas['x'] : '';
							$pdata['VfPointdata']['y'] = isset($pdatas['y']) ? $pdatas['y'] : '';
							$pdata['VfPointdata']['intensity'] = isset($pdatas['intensity']) ? $pdatas['intensity'] : '';;
							$pdata['VfPointdata']['size'] = isset($pdatas['size']) ? $pdatas['size'] : '';
							$pdata['VfPointdata']['STD'] = isset($pdatas['STD']) ? $pdatas['STD'] : '';
							$pdata['VfPointdata']['index'] = isset($pdatas['index']) ? $pdatas['index'] : '';
							$this->VfPointdata->create();
							$result_p = $this->VfPointdata->save($pdata);
							$response['message'] = 'Success.';
							$response['result'] = 1;
							$response['new_report_id'] = $lastId;
						}
					}
				} else {
					$response['message'] = 'Some error occured in updating report.';
					$response['result'] = 0;
					//$response['new_report_id']=$lastId;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/****************************pointData *********************************/
	/******************************Masterdata *********************************/
	public function masterData()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$this->loadModel('VfMasterdata');
				$data_arrayss = json_decode(file_get_contents("php://input"), true);
				//pr($data_arrayss);die;
				if (!empty($data_arrayss['data'])) {
					foreach ($data_arrayss['data'] as $key => $data_arrays) {
						$data_arrays['test_report_id'] = 1;
						//pr($data_arrays);
						$none_data = 0;
						if (!empty($data_arrays['pdf'])) {
							$foldername = "Masterdata";
							$imgstring = $data_arrays['pdf'];
							$data_arraysdata_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $key);
						}
						$age_group_data = $this->Masterdata->find('first', array('conditions' => array('Masterdata.age_group' => @$data_arrays['age_group'], 'Masterdata.test_name' => @$data_arrays['test_name'])));
						if (!empty($age_group_data)) {
							//$data['Masterdata']['id'] = $age_group_data['Masterdata']['id'];
							$this->Masterdata->delete($age_group_data['Masterdata']['id']);
							$this->VfMasterdata->deleteAll(array('VfMasterdata.master_data_id' => $age_group_data['Masterdata']['id']));
						}
						$data['Masterdata']['test_id'] = isset($data_arrays['test_id']) ? $data_arrays['test_id'] : '';
						$data['Masterdata']['age_group'] = isset($data_arrays['age_group']) ? $data_arrays['age_group'] : '';
						$data['Masterdata']['numpoints'] = isset($data_arrays['numpoints']) ? $data_arrays['numpoints'] : '';
						$data['Masterdata']['color'] = isset($data_arrays['color']) ? $data_arrays['color'] : '';
						$data['Masterdata']['backgroundcolor'] = isset($data_arrays['backgroundcolor']) ? $data_arrays['backgroundcolor'] : '';
						$data['Masterdata']['stmsize'] = isset($data_arrays['stmSize']) ? $data_arrays['stmSize'] : $none_data;
						if (($data['Masterdata']['stmsize']) == '') {
							$data['Masterdata']['stmsize'] = $none_data;
						}
						$data['Masterdata']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
						$data['Masterdata']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
						$data['Masterdata']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : $none_data;
						if ($data['Masterdata']['patient_id'] == '') {
							$data['Masterdata']['patient_id'] = $none_data;
						}
						$data['Masterdata']['master_key'] = isset($data_arrays['master_key']) ? $data_arrays['master_key'] : '';
						$data['Masterdata']['eye_select'] = isset($data_arrays['eye_select']) ? $data_arrays['eye_select'] : '';
						$data['Masterdata']['test_type_id'] = isset($data_arrays['test_type_id']) ? $data_arrays['test_type_id'] : $none_data;
						if ($data['Masterdata']['test_type_id'] == '') {
							$data['Masterdata']['test_type_id'] = $none_data;
						}
						$data['Masterdata']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : '';
						$data['Masterdata']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
						$data['Masterdata']['threshold'] = @$data_arrays['threshold'];
						$data['Masterdata']['strategy'] = @$data_arrays['strategy'];
						$data['Masterdata']['test_color_fg'] = @$data_arrays['test_color_fg'];
						$data['Masterdata']['test_color_bg'] = @$data_arrays['test_color_bg'];
						//pr($data);die;
						$result = $this->Masterdata->save($data);
						$lastId = $this->Masterdata->id;
						if ($result) {
							if (!empty($data_arrays['file'])) {
								$response['pdf'] = WWW_BASE . 'pointData/' . $data_arrays['file'];
								$response['new_id'] = $lastId;
							} else {
								$response['pdf'] = '';
							}
							$pdata = [];
							foreach ($data_arrays['vfpointdata'] as $pdatas) {
								$pdata['VfMasterdata']['report_id'] = @$data_arrays['test_report_id'];
								$pdata['VfMasterdata']['master_data_id'] = @$lastId;
								$pdata['VfMasterdata']['x'] = isset($pdatas['x']) ? $pdatas['x'] : '';
								$pdata['VfMasterdata']['y'] = isset($pdatas['y']) ? $pdatas['y'] : '';
								$pdata['VfMasterdata']['intensity'] = isset($pdatas['intensity']) ? $pdatas['intensity'] : '';;
								$pdata['VfMasterdata']['size'] = isset($pdatas['size']) ? $pdatas['size'] : '';
								$pdata['VfMasterdata']['STD'] = isset($pdatas['STD']) ? $pdatas['STD'] : '';
								$pdata['VfMasterdata']['index'] = isset($pdatas['index']) ? $pdatas['index'] : '';
								$pdata['VfMasterdata']['age_group'] = 0;
								$pdata['VfMasterdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
								/* pr($pdata);
						 die();*/
								$this->VfMasterdata->create();
								$result_p = $this->VfMasterdata->save($pdata);
							}
							$response['message'] = 'Success.';
							$response['result'] = 1;
							//update credits------
							/*$this->loadModel('User');
						$this->User->id = $data['Masterdata']['staff_id'];
						$credits = $this->User->field('credits');
						$new_credit = $credits-1;
						$this->User->updateAll(array('User.credits'=>$new_credit),array('User.id' =>$data['Masterdata']['staff_id']));*/
						}
					}
				} else {
					$response['message'] = 'Some error occured in updating report.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				$this->update_master_app_constant($lastId);
				exit();
			}
		}
	}
	public function get_masterdata_report()
	{
		if ($this->check_key()) {
			//echo 'hello';die;
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				if (empty($data_arrays)) {
					$data_arrays = $_POST;
				}
				//pr($data_arrays);die;
				if (isset($data_arrays['page']) && isset($data_arrays['age_group'])) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 10 - 1;
					} else {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = ($data_arrays['page'] - 1) * 10;
						$end = $data_arrays['page'] * 10 - 1;
					}
					$this->loadModel('VfMasterdata');
					$this->VfMasterdata->virtualFields['test_id'] = 'VfMasterdata.master_data_id';
					$condition['Masterdata.age_group'] = $data_arrays['age_group'];
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['Masterdata.patient_id'] = $data_arrays['patient_id'];
					}
					/* if(isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])){
						//$condition['Masterdata.patient_name'] = $data_arrays['patient_name'];
						$condition["Masterdata.patient_name LIKE"] = '%'.$data_arrays['patient_name']."%";
					}
					 */
					if (isset($data_arrays['test_name']) && !empty($data_arrays['test_name'])) {
						//$condition['Masterdata.patient_name'] = $data_arrays['patient_name'];
						$condition["Masterdata.test_name LIKE"] = '%' . $data_arrays['test_name'] . "%";
					}
					$results = $this->Masterdata->find('all', array('conditions' => $condition, 'fields' => array('Masterdata.id', 'Masterdata.created'/* ,'Masterdata.patient_name' ,'Masterdata.staff_name'*/, 'Masterdata.file', 'Masterdata.color', 'Masterdata.backgroundcolor', 'Masterdata.stmsize', 'Masterdata.patient_id', 'Masterdata.eye_select', 'Masterdata.numpoints', 'Masterdata.test_type_id', 'Masterdata.master_key', 'Test.name', 'Masterdata.test_name', 'Masterdata.age_group', 'Masterdata.threshold', 'Masterdata.strategy', 'Masterdata.test_color_fg', 'Masterdata.test_color_bg'), 'order' => array('Masterdata.id DESC'), 'limit' => $limit));
					//pr($results);die;
					if ($data_arrays['page'] != 0) {
						if ((count($results) > $data_arrays['page'] * 10)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
					if (!empty($results)) {
						$data = array();
						if ($data_arrays['page'] == 0) {
							$end = count($results) - 1;
						}
						$i = 0;
						foreach ($results as $key => $result) {
							if ($key >= $start && $key <= $end) {
								//pr($result);die;
								$data[$i] = $result['Masterdata'];
								$data[$i]['test_id'] = $result['Masterdata']['id'];
								$data[$i]['age_group'] = $result['Masterdata']['age_group'];
								unset($data[$i]['id']);
								$data[$i]['created_date'] = ($result['Masterdata']['created'] != null) ? ($result['Masterdata']['created']) : '';
								$data[$i]['patient_name'] = !empty($result['Masterdata']['patient_name']) ? ($result['Masterdata']['patient_name']) : '';
								if (!empty($result['Masterdata']['file'])) {
									$data[$i]['pdf'] = WWW_BASE . 'Masterdata/' . $result['Masterdata']['file'];
								}
								$data[$i]['color'] = ($result['Masterdata']['color'] != null) ? ($result['Masterdata']['color']) : '';
								$data[$i]['patient_id'] = isset($result['Masterdata']['patient_id']) ? ($result['Masterdata']['patient_id']) : '';
								$data[$i]['test_name'] = ($result['Masterdata']['test_name'] != null) ? ($result['Masterdata']['test_name']) : '';
								$data[$i]['backgroundcolor'] = ($result['Masterdata']['backgroundcolor'] != null) ? ($result['Masterdata']['backgroundcolor']) : '';
								$data[$i]['threshold'] = @$result['Masterdata']['threshold'];
								$data[$i]['strategy'] = @$result['Masterdata']['strategy'];
								$data[$i]['test_color_fg'] = @$result['Masterdata']['test_color_fg'];
								$data[$i]['test_color_bg'] = @$result['Masterdata']['test_color_bg'];
								$data[$i]['stmsize'] = ($result['Masterdata']['stmsize'] != null) ? ($result['Masterdata']['stmsize']) : '';
								/* $data[$i]['stmsize']=($result['Masterdata']['test_name']!=null)?($result['Masterdata']['test_name']):''; */
								$data[$i]['master_key'] = ($result['Masterdata']['master_key'] != null) ? ($result['Masterdata']['master_key']) : '';
								$data[$i]['test_type_id'] = ($result['Masterdata']['test_type_id'] != null) ? ($result['Masterdata']['test_type_id']) : '';
								$data[$i]['numpoints'] = ($result['Masterdata']['numpoints'] != null) ? ($result['Masterdata']['numpoints']) : '';
								$data[$i]['eye_select'] = ($result['Masterdata']['eye_select'] != null) ? ($result['Masterdata']['eye_select']) : '';
								if (isset($data[$i]['file'])) unset($data[$i]['file']);
								if (isset($data[$i]['created'])) unset($data[$i]['created']);
								if (!empty($result['VfMasterdata'])) {
									$k = 0;
									foreach ($result['VfMasterdata'] as $value) {
										$data[$i]['vfpointdata'][$k]['id'] = $value['id'];
										$data[$i]['vfpointdata'][$k]['report_id'] = $value['report_id'];
										$data[$i]['vfpointdata'][$k]['master_data_id'] = $value['master_data_id'];
										$data[$i]['vfpointdata'][$k]['x'] = $value['x'];
										$data[$i]['vfpointdata'][$k]['y'] = $value['y'];
										$data[$i]['vfpointdata'][$k]['intensity'] = $value['intensity'];
										$data[$i]['vfpointdata'][$k]['size'] = $value['size'];
										$data[$i]['vfpointdata'][$k]['STD'] = $value['STD'];
										$data[$i]['vfpointdata'][$k]['index'] = $value['index'];
										$data[$i]['vfpointdata'][$k]['test_id'] = $value['test_id'];
										$k++;
									}
								}
								$i++;
							}
						}
						//pr($data);die;
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['result'] = 1;
							$response['more_data'] = $more_data;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $more_data;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}
	public function get_masterdata_reportAll()
	{
		if ($this->check_key()) {
			//echo 'hello';die;
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				if (empty($data_arrays)) {
					$data_arrays = $_POST;
				}
				//pr($data_arrays);die;
				if (isset($data_arrays['page'])) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 10 - 1;
					} else {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = ($data_arrays['page'] - 1) * 10;
						$end = $data_arrays['page'] * 10 - 1;
					}
					$this->loadModel('VfMasterdata');
					$this->VfMasterdata->virtualFields['test_id'] = 'VfMasterdata.master_data_id';
					$condition = array();
					if (isset($data_arrays['device_type'])) {
						if ($data_arrays['device_type'] == 1) {
							$condition["Masterdata.test_name LIKE"] = '%_go%';
						} else if ($data_arrays['device_type'] == 2) {
							$condition["Masterdata.test_name LIKE"] = '%_PICO%';
						} else if ($data_arrays['device_type'] == 3) {
							$condition["Masterdata.test_name LIKE"] = '%_Quest%';
						} else if ($data_arrays['device_type'] == 0) {
							$condition = array(
								'AND' => array(
									"Masterdata.test_name NOT LIKE '%_go%'",
									"Masterdata.test_name NOT LIKE '%_PICO%'",
									"Masterdata.test_name NOT LIKE '%_Quest%'",
								)
							);
						}
					}
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['Masterdata.patient_id'] = $data_arrays['patient_id'];
					}
					/* if(isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])){
                      //$condition['Masterdata.patient_name'] = $data_arrays['patient_name'];
                      $condition["Masterdata.patient_name LIKE"] = '%'.$data_arrays['patient_name']."%";
                      }
                     */
					if (isset($data_arrays['test_name']) && !empty($data_arrays['test_name'])) {
						//$condition['Masterdata.patient_name'] = $data_arrays['patient_name'];
						$condition["Masterdata.test_name LIKE"] = '%' . $data_arrays['test_name'] . "%";
					}
					/* if(isset($data_arrays['go'])){
						if($data_arrays['go']==1){
							$condition["Masterdata.test_name LIKE"] = '%_go%';
						}else if($data_arrays['go']==0){
							$condition["Masterdata.test_name NOT LIKE"] = '%_go%';
						}
					}*/
					$results = $this->Masterdata->find('all', array('conditions' => $condition, 'fields' => array('Masterdata.id', 'Masterdata.created'/* ,'Masterdata.patient_name' ,'Masterdata.staff_name' */, 'Masterdata.file', 'Masterdata.color', 'Masterdata.backgroundcolor', 'Masterdata.stmsize', 'Masterdata.patient_id', 'Masterdata.eye_select', 'Masterdata.numpoints', 'Masterdata.test_type_id', 'Masterdata.master_key', 'Test.name', 'Masterdata.test_name', 'Masterdata.age_group', 'Masterdata.threshold', 'Masterdata.strategy', 'Masterdata.test_color_fg', 'Masterdata.test_color_bg'), 'order' => array('Masterdata.id DESC'), 'limit' => $limit));
					//pr($results);die;
					if ($data_arrays['page'] != 0) {
						if ((count($results) > $data_arrays['page'] * 10)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
					if (!empty($results)) {
						$data = array();
						if ($data_arrays['page'] == 0) {
							$end = count($results) - 1;
						}
						$i = 0;
						foreach ($results as $key => $result) {
							if ($key >= $start && $key <= $end) {
								//pr($result);die;
								$data[$i] = $result['Masterdata'];
								$data[$i]['test_id'] = $result['Masterdata']['id'];
								$data[$i]['age_group'] = $result['Masterdata']['age_group'];
								unset($data[$i]['id']);
								$data[$i]['created_date'] = ($result['Masterdata']['created'] != null) ? ($result['Masterdata']['created']) : '';
								$data[$i]['patient_name'] = !empty($result['Masterdata']['patient_name']) ? ($result['Masterdata']['patient_name']) : '';
								if (!empty($result['Masterdata']['file'])) {
									$data[$i]['pdf'] = WWW_BASE . 'Masterdata/' . $result['Masterdata']['file'];
								}
								$data[$i]['color'] = ($result['Masterdata']['color'] != null) ? ($result['Masterdata']['color']) : '';
								$data[$i]['patient_id'] = isset($result['Masterdata']['patient_id']) ? ($result['Masterdata']['patient_id']) : '';
								$data[$i]['test_name'] = ($result['Masterdata']['test_name'] != null) ? ($result['Masterdata']['test_name']) : '';
								$data[$i]['backgroundcolor'] = ($result['Masterdata']['backgroundcolor'] != null) ? ($result['Masterdata']['backgroundcolor']) : '';
								$data[$i]['threshold'] = @$result['Masterdata']['threshold'];
								$data[$i]['strategy'] = @$result['Masterdata']['strategy'];
								$data[$i]['test_color_fg'] = @$result['Masterdata']['test_color_fg'];
								$data[$i]['test_color_bg'] = @$result['Masterdata']['test_color_bg'];
								$data[$i]['stmsize'] = ($result['Masterdata']['stmsize'] != null) ? ($result['Masterdata']['stmsize']) : '';
								/* $data[$i]['stmsize']=($result['Masterdata']['test_name']!=null)?($result['Masterdata']['test_name']):''; */
								$data[$i]['master_key'] = ($result['Masterdata']['master_key'] != null) ? ($result['Masterdata']['master_key']) : '';
								$data[$i]['test_type_id'] = ($result['Masterdata']['test_type_id'] != null) ? ($result['Masterdata']['test_type_id']) : '';
								$data[$i]['numpoints'] = ($result['Masterdata']['numpoints'] != null) ? ($result['Masterdata']['numpoints']) : '';
								$data[$i]['eye_select'] = ($result['Masterdata']['eye_select'] != null) ? ($result['Masterdata']['eye_select']) : '';
								if (isset($data[$i]['file']))
									unset($data[$i]['file']);
								if (isset($data[$i]['created']))
									unset($data[$i]['created']);
								if (!empty($result['VfMasterdata'])) {
									$k = 0;
									foreach ($result['VfMasterdata'] as $value) {
										$data[$i]['vfpointdata'][$k]['id'] = $value['id'];
										$data[$i]['vfpointdata'][$k]['report_id'] = $value['report_id'];
										$data[$i]['vfpointdata'][$k]['master_data_id'] = $value['master_data_id'];
										$data[$i]['vfpointdata'][$k]['x'] = $value['x'];
										$data[$i]['vfpointdata'][$k]['y'] = $value['y'];
										$data[$i]['vfpointdata'][$k]['intensity'] = $value['intensity'];
										$data[$i]['vfpointdata'][$k]['size'] = $value['size'];
										$data[$i]['vfpointdata'][$k]['STD'] = $value['STD'];
										$data[$i]['vfpointdata'][$k]['index'] = $value['index'];
										$data[$i]['vfpointdata'][$k]['test_id'] = $value['test_id'];
										$k++;
									}
								}
								$i++;
							}
						}
						//pr($data);die;
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['result'] = 1;
							$response['more_data'] = $more_data;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $more_data;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}
	/****************************Masterdata *********************************/
	public function get_pointData_report()
	{
		if ($this->check_key()) {
			//echo 'hello';die;
			$this->layout = false;
			//echo 'fg';
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST; 
				//echo 'ff';
				//print_r($_POST); die;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				//print_r($data_arrays);die('sfds');
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 10 - 1;
					} else {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = ($data_arrays['page'] - 1) * 10;
						$end = $data_arrays['page'] * 10 - 1;
					}
					//$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$data_arrays['staff_id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.office_id')));
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id']), 'fields' => array('User.office_id')));
					//print_r($office_id); die;
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
					//print_r($all_staff_ids); die('id');
					$this->Pointdata->virtualFields['patient_name'] = 'select concat(first_name," ",middle_name," ",last_name) from mmd_patients as patients where Pointdata.patient_id = patients.id';
					/* $this->Pointdata->virtualFields['patient_id'] = 'select id from mmd_patients as patients where Pointdata.patient_id = patients.id'; */
					$this->Pointdata->virtualFields['staff_name'] = 'select concat(first_name," ",middle_name," ",last_name) as name from mmd_users as users where Pointdata.staff_id = users.id';
					//$this->VfPointdata->virtualFields['test_id'] = 'VfPointdata.point_data_id';
					$condition['Pointdata.staff_id'] = $all_staff_ids;
					$vfTestNames = array("Central_20_Point", "Central_40_Point", "Esterman_120_point","Estrman_120_point", "76_Point_Pattern", "Central_80_Point", "Central_166_Point", "Armally_Central","Central_10_2", "Central_24_1", "Central_24_2", "Central_30_1", "Central_30_2","Superior_24_2", "Superior_30_2", "Superior_50_1", "Superior_64", "Neuro_20", "Neuro_35", "Full_Field_120_PTS","Kinetic_60_16", "Kinetic_30_16", "Kinetic_60_28", "Kinetic_30_28", "Ptosis_9_PT", "Ptosis_Auto_9_PT");
					$condition[] = array('Pointdata.test_name' => $vfTestNames);
					//print_r($data_arrays['patient_id']); die;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['Pointdata.patient_id'] = $data_arrays['patient_id'];
					}
					//print_r($data_arrays['patient_name']); die;
					if (isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["Pointdata.patient_name LIKE"] = '%' . $data_arrays['patient_name'] . "%";
					}
					if (isset($data_arrays['test_name']) && !empty($data_arrays['test_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["Pointdata.test_name LIKE"] = '%' . $data_arrays['test_name'] . "%";
					}
					$condition["Pointdata.test_name NOT LIKE"] = '%Vision Screening%';
					/*if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['Pointdata.created >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}*/
					if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['Pointdata.merge_report_date >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}
					//$this->Pointdata->unbindModel(array('hasMany' => array('VfPointdata')),false);
					/*$this->Pointdata->bindModel(
						array(
							'belongsTo' => array(
								'Pointdata' => array(
									'className' => 'Pointdata',
									'foreignKey' => 'patient_id',
								)
							)
						)
					);*/
					//$this->loadModel('Pointdata');
					$results = $this->Pointdata->find('all', array('conditions' => $condition, 'fields' => array('Pointdata.id', 'Pointdata.created', 'Pointdata.patient_name', 'Pointdata.staff_name', 'Pointdata.file', 'Pointdata.color', 'Pointdata.backgroundcolor', 'Pointdata.stmsize', 'Pointdata.patient_id', 'Pointdata.eye_select', 'Pointdata.baseline', 'Pointdata.numpoints', 'Pointdata.test_type_id', 'Pointdata.master_key', 'Test.name', 'Pointdata.test_name', 'Pointdata.threshold', 'Pointdata.strategy', 'Pointdata.test_color_fg', 'Pointdata.test_color_bg', 'Pointdata.mean_dev', 'Pointdata.pattern_std', 'Pointdata.mean_sen', 'Pointdata.mean_def', 'Pointdata.pattern_std_hfa', 'Pointdata.psd_hfa', 'Pointdata.vission_loss', 'Pointdata.false_p', 'Pointdata.false_n', 'Pointdata.false_f', 'Pointdata.psd_hfa_2', 'Pointdata.loss_var', 'Pointdata.mean_std', 'Pointdata.ght', 'Pointdata.latitude', 'Pointdata.longitude', 'Pointdata.unique_id', 'Pointdata.version', 'Pointdata.diagnosys'), 'order' => array('Pointdata.id DESC'), 'limit' => $limit));
					//pr($results); die;
					if ($data_arrays['page'] != 0) {
						if ((count($results) > $data_arrays['page'] * 10)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
					if (!empty($results)) {
						$data = array();
						if ($data_arrays['page'] == 0) {
							$end = count($results) - 1;
						}
						$i = 0;
						$last_sync_time = '';
						foreach ($results as $key => $result) {
							if ($key >= $start && $key <= $end) {
								//pr($result);die;
								$data[$i] = $result['Pointdata'];
								$data[$i]['test_id'] = $result['Pointdata']['id'];
								unset($data[$i]['id']);
								$data[$i]['created_date'] = ($result['Pointdata']['created'] != null) ? ($result['Pointdata']['created']) : '';
								$data[$i]['patient_name'] = ($result['Pointdata']['patient_name'] != null) ? ($result['Pointdata']['patient_name']) : '';
								if (!empty($result['Pointdata']['file'])) {
									$data[$i]['pdf']=WWW_BASE.'pointData/'.$result['Pointdata']['file'];
									//$data[$i]['pdf'] = WWW_BASE . 'app/webroot/fileDownloadUrl/' . $result['Pointdata']['file'];
								}
								$data[$i]['color'] = ($result['Pointdata']['color'] != null) ? ($result['Pointdata']['color']) : '';
								$data[$i]['patient_id'] = isset($result['Pointdata']['patient_id']) ? ($result['Pointdata']['patient_id']) : '';
								$data[$i]['test_name'] = ($result['Pointdata']['test_name'] != null) ? ($result['Pointdata']['test_name']) : '';
								$data[$i]['backgroundcolor'] = ($result['Pointdata']['backgroundcolor'] != null) ? ($result['Pointdata']['backgroundcolor']) : '';
								$data[$i]['threshold'] = @$result['Pointdata']['threshold'];
								$data[$i]['strategy'] = @$result['Pointdata']['strategy'];
								$data[$i]['test_color_fg'] = @$result['Pointdata']['test_color_fg'];
								$data[$i]['test_color_bg'] = @$result['Pointdata']['test_color_bg'];
								$data[$i]['stmsize'] = ($result['Pointdata']['stmsize'] != null) ? ($result['Pointdata']['stmsize']) : '';
								/* $data[$i]['stmsize']=($result['Pointdata']['test_name']!=null)?($result['Pointdata']['test_name']):''; */
								$data[$i]['master_key'] = ($result['Pointdata']['master_key'] != null) ? ($result['Pointdata']['master_key']) : '';
								$data[$i]['test_type_id'] = ($result['Pointdata']['test_type_id'] != null) ? ($result['Pointdata']['test_type_id']) : '';
								$data[$i]['numpoints'] = ($result['Pointdata']['numpoints'] != null) ? ($result['Pointdata']['numpoints']) : '';
								$data[$i]['eye_select'] = ($result['Pointdata']['eye_select'] != null) ? ($result['Pointdata']['eye_select']) : '';
								$data[$i]['latitude'] = !empty($result['Pointdata']['latitude']) ? ($result['Pointdata']['latitude']) : '';
								$data[$i]['longitude'] = !empty($result['Pointdata']['longitude']) ? ($result['Pointdata']['longitude']) : '';
								$data[$i]['unique_id'] = !empty($result['Pointdata']['unique_id']) ? ($result['Pointdata']['unique_id']) : '';
								$data[$i]['version'] = !empty($result['Pointdata']['version']) ? ($result['Pointdata']['version']) : '';
								$data[$i]['diagnosys'] = !empty($result['Pointdata']['diagnosys']) ? ($result['Pointdata']['diagnosys']) : '';
								if (isset($data[$i]['file'])) unset($data[$i]['file']);
								if (isset($data[$i]['created'])) unset($data[$i]['created']);
								$last_sync_time = !empty($last_sync_time) ? $last_sync_time : $result['Pointdata']['created'];
								$i++;
							}
						}
						//pr($data);die;
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['last_sync_time'] = $last_sync_time;
							$response['result'] = 1;
							$response['more_data'] = $more_data;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $more_data;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}

	/*for only VF/VS/FDT v6 for UTC time 22-11-2022 by Madan*/
	/****************************Masterdata *********************************/
	public function get_pointData_report_v6()
	{
		if ($this->check_key()) {
			//echo 'hello';die;
			$this->layout = false;
			//echo 'fg';
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST; 
				//echo 'ff';
				//print_r($_POST); die;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				//print_r($data_arrays);die('sfds');
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 10 - 1;
					} else {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = ($data_arrays['page'] - 1) * 10;
						$end = $data_arrays['page'] * 10 - 1;
					}
					//$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$data_arrays['staff_id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.office_id')));
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id']), 'fields' => array('User.office_id')));
					//print_r($office_id); die;
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
					//print_r($all_staff_ids); die('id');
					$this->Pointdata->virtualFields['patient_name'] = 'select concat(first_name," ",middle_name," ",last_name) from mmd_patients as patients where Pointdata.patient_id = patients.id';
					/* $this->Pointdata->virtualFields['patient_id'] = 'select id from mmd_patients as patients where Pointdata.patient_id = patients.id'; */
					$this->Pointdata->virtualFields['staff_name'] = 'select concat(first_name," ",middle_name," ",last_name) as name from mmd_users as users where Pointdata.staff_id = users.id';
					//$this->VfPointdata->virtualFields['test_id'] = 'VfPointdata.point_data_id';
					$condition['Pointdata.staff_id'] = $all_staff_ids;
					$vfTestNames = array("Central_20_Point", "Central_40_Point", "Esterman_120_point","Estrman_120_point", "76_Point_Pattern", "Central_80_Point", "Central_166_Point", "Armally_Central","Central_10_2", "Central_24_1", "Central_24_2", "Central_30_1", "Central_30_2","Superior_24_2", "Superior_30_2", "Superior_50_1", "Superior_64", "Neuro_20", "Neuro_35", "Full_Field_120_PTS","Kinetic_60_16", "Kinetic_30_16", "Kinetic_60_28", "Kinetic_30_28", "Ptosis_9_PT", "Ptosis_Auto_9_PT");
					$condition[] = array('Pointdata.test_name' => $vfTestNames);
					//print_r($data_arrays['patient_id']); die;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['Pointdata.patient_id'] = $data_arrays['patient_id'];
					}
					//print_r($data_arrays['patient_name']); die;
					if (isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["Pointdata.patient_name LIKE"] = '%' . $data_arrays['patient_name'] . "%";
					}
					if (isset($data_arrays['test_name']) && !empty($data_arrays['test_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["Pointdata.test_name LIKE"] = '%' . $data_arrays['test_name'] . "%";
					}
					$condition["Pointdata.test_name NOT LIKE"] = '%Vision Screening%';
					/*if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['Pointdata.created >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}*/
					/*if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['Pointdata.merge_report_date >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}*/
					if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['Pointdata.created_date_utc >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}
					//$this->Pointdata->unbindModel(array('hasMany' => array('VfPointdata')),false);
					/*$this->Pointdata->bindModel(
						array(
							'belongsTo' => array(
								'Pointdata' => array(
									'className' => 'Pointdata',
									'foreignKey' => 'patient_id',
								)
							)
						)
					);*/
					//$this->loadModel('Pointdata');
					$results = $this->Pointdata->find('all', array('conditions' => $condition, 'fields' => array('Pointdata.id', 'Pointdata.created','Pointdata.created_date_utc', 'Pointdata.patient_name', 'Pointdata.staff_name', 'Pointdata.file', 'Pointdata.color', 'Pointdata.backgroundcolor', 'Pointdata.stmsize', 'Pointdata.patient_id', 'Pointdata.eye_select', 'Pointdata.baseline', 'Pointdata.numpoints', 'Pointdata.test_type_id', 'Pointdata.master_key', 'Test.name', 'Pointdata.test_name', 'Pointdata.threshold', 'Pointdata.strategy', 'Pointdata.test_color_fg', 'Pointdata.test_color_bg', 'Pointdata.mean_dev', 'Pointdata.pattern_std', 'Pointdata.mean_sen', 'Pointdata.mean_def', 'Pointdata.pattern_std_hfa', 'Pointdata.psd_hfa', 'Pointdata.vission_loss', 'Pointdata.false_p', 'Pointdata.false_n', 'Pointdata.false_f', 'Pointdata.psd_hfa_2', 'Pointdata.loss_var', 'Pointdata.mean_std', 'Pointdata.ght', 'Pointdata.latitude', 'Pointdata.longitude', 'Pointdata.unique_id', 'Pointdata.version', 'Pointdata.diagnosys'), 'order' => array('Pointdata.id DESC'), 'limit' => $limit));
					//pr($results); die;
					if ($data_arrays['page'] != 0) {
						if ((count($results) > $data_arrays['page'] * 10)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
					if (!empty($results)) {
						$data = array();
						if ($data_arrays['page'] == 0) {
							$end = count($results) - 1;
						}
						$i = 0;
						$last_sync_time = '';
						foreach ($results as $key => $result) {
							if ($key >= $start && $key <= $end) {
								//pr($result);die;
								$data[$i] = $result['Pointdata'];
								$data[$i]['test_id'] = $result['Pointdata']['id'];
								unset($data[$i]['id']);
								$data[$i]['created_date'] = ($result['Pointdata']['created'] != null) ? ($result['Pointdata']['created']) : '';
								$data[$i]['patient_name'] = ($result['Pointdata']['patient_name'] != null) ? ($result['Pointdata']['patient_name']) : '';
								if (!empty($result['Pointdata']['file'])) {
									$data[$i]['pdf']=WWW_BASE.'pointData/'.$result['Pointdata']['file'];
									//$data[$i]['pdf'] = WWW_BASE . 'app/webroot/fileDownloadUrl/' . $result['Pointdata']['file'];
								}
								$data[$i]['color'] = ($result['Pointdata']['color'] != null) ? ($result['Pointdata']['color']) : '';
								$data[$i]['patient_id'] = isset($result['Pointdata']['patient_id']) ? ($result['Pointdata']['patient_id']) : '';
								$data[$i]['test_name'] = ($result['Pointdata']['test_name'] != null) ? ($result['Pointdata']['test_name']) : '';
								$data[$i]['backgroundcolor'] = ($result['Pointdata']['backgroundcolor'] != null) ? ($result['Pointdata']['backgroundcolor']) : '';
								$data[$i]['threshold'] = @$result['Pointdata']['threshold'];
								$data[$i]['strategy'] = @$result['Pointdata']['strategy'];
								$data[$i]['test_color_fg'] = @$result['Pointdata']['test_color_fg'];
								$data[$i]['test_color_bg'] = @$result['Pointdata']['test_color_bg'];
								$data[$i]['stmsize'] = ($result['Pointdata']['stmsize'] != null) ? ($result['Pointdata']['stmsize']) : '';
								/* $data[$i]['stmsize']=($result['Pointdata']['test_name']!=null)?($result['Pointdata']['test_name']):''; */
								$data[$i]['master_key'] = ($result['Pointdata']['master_key'] != null) ? ($result['Pointdata']['master_key']) : '';
								$data[$i]['test_type_id'] = ($result['Pointdata']['test_type_id'] != null) ? ($result['Pointdata']['test_type_id']) : '';
								$data[$i]['numpoints'] = ($result['Pointdata']['numpoints'] != null) ? ($result['Pointdata']['numpoints']) : '';
								$data[$i]['eye_select'] = ($result['Pointdata']['eye_select'] != null) ? ($result['Pointdata']['eye_select']) : '';
								$data[$i]['latitude'] = !empty($result['Pointdata']['latitude']) ? ($result['Pointdata']['latitude']) : '';
								$data[$i]['longitude'] = !empty($result['Pointdata']['longitude']) ? ($result['Pointdata']['longitude']) : '';
								$data[$i]['unique_id'] = !empty($result['Pointdata']['unique_id']) ? ($result['Pointdata']['unique_id']) : '';
								$data[$i]['version'] = !empty($result['Pointdata']['version']) ? ($result['Pointdata']['version']) : '';
								$data[$i]['diagnosys'] = !empty($result['Pointdata']['diagnosys']) ? ($result['Pointdata']['diagnosys']) : '';
								if (isset($data[$i]['file'])) unset($data[$i]['file']);
								if (isset($data[$i]['created_date_utc'])) unset($data[$i]['created_date_utc']); 
								//$last_sync_time = !empty($last_sync_time) ? $last_sync_time : $result['Pointdata']['created_date_utc'];
								$last_sync_time = ($last_sync_time != '') ? $last_sync_time : $result['Pointdata']['created_date_utc'];
								$i++;
							}
						}
						if($data_arrays['sync_start_time'] == ''){
							date_default_timezone_set('UTC');
            				$UTCDate = date('Y-m-d H:i:s');
							$last_sync_time = $UTCDate;
						}
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['last_sync_time'] = $last_sync_time;
							$response['result'] = 1;
							$response['more_data'] = $more_data;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $more_data;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}
	/*for only VF/VS/FDT v6 for UTC time 22-11-2022 by Madan*/

	public function get_pointData_report_vs_v2()
	{
		if ($this->check_key()) {
			//echo 'hello';die;
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				//pr($data_arrays);die;
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 10 - 1;
					} else {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = ($data_arrays['page'] - 1) * 10;
						$end = $data_arrays['page'] * 10 - 1;
					}
					//$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$data_arrays['staff_id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.office_id')));
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id']), 'fields' => array('User.office_id')));
					//pr($office_id); die;
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
					$this->Pointdata->virtualFields['patient_name'] = 'select concat(first_name," ",middle_name," ",last_name) from mmd_patients as patients where Pointdata.patient_id = patients.id';
					/* $this->Pointdata->virtualFields['patient_id'] = 'select id from mmd_patients as patients where Pointdata.patient_id = patients.id'; */
					$this->Pointdata->virtualFields['staff_name'] = 'select concat(first_name," ",middle_name," ",last_name) as name from mmd_users as users where Pointdata.staff_id = users.id';
					//$this->VfPointdata->virtualFields['test_id'] = 'VfPointdata.point_data_id';
					$condition['Pointdata.staff_id'] = $all_staff_ids;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['Pointdata.patient_id'] = $data_arrays['patient_id'];
					}
					if (isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["Pointdata.patient_name LIKE"] = '%' . $data_arrays['patient_name'] . "%";
					}
					$condition["Pointdata.test_name LIKE"] = '%Vision Screening%';
					/*if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['Pointdata.created >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}*/
					if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['Pointdata.merge_report_date >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}
					$this->Pointdata->unbindModel(array('hasMany' => array('VfPointdata')));
					$results = $this->Pointdata->find('all', array('conditions' => $condition, 'fields' => array('Pointdata.id', 'Pointdata.age_group', 'Pointdata.created', 'Pointdata.patient_name', 'Pointdata.staff_name', 'Pointdata.file', 'Pointdata.color', 'Pointdata.backgroundcolor', 'Pointdata.stmsize', 'Pointdata.patient_id', 'Pointdata.eye_select', 'Pointdata.baseline', 'Pointdata.numpoints', 'Pointdata.test_type_id', 'Pointdata.master_key', 'Test.name', 'Pointdata.test_name', 'Pointdata.threshold', 'Pointdata.strategy', 'Pointdata.test_color_fg', 'Pointdata.test_color_bg', 'Pointdata.mean_dev', 'Pointdata.pattern_std', 'Pointdata.mean_sen', 'Pointdata.mean_def', 'Pointdata.pattern_std_hfa', 'Pointdata.psd_hfa', 'Pointdata.vission_loss', 'Pointdata.false_p', 'Pointdata.false_n', 'Pointdata.false_f', 'Pointdata.psd_hfa_2', 'Pointdata.loss_var', 'Pointdata.mean_std', 'Pointdata.ght', 'Pointdata.latitude', 'Pointdata.longitude', 'Pointdata.unique_id', 'Pointdata.version', 'Pointdata.diagnosys'), 'order' => array('Pointdata.id DESC'), 'limit' => $limit));
					//pr($results); die;
					if ($data_arrays['page'] != 0) {
						if ((count($results) > $data_arrays['page'] * 10)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
					if (!empty($results)) {
						$data = array();
						if ($data_arrays['page'] == 0) {
							$end = count($results) - 1;
						}
						$i = 0;
						$last_sync_time = '';
						foreach ($results as $key => $result) { //pr($result); die;
							if ($key >= $start && $key <= $end) {
								// pr($result);die;
								$data[$i] = $result['Pointdata'];
								$data[$i]['test_id'] = $result['Pointdata']['id'];
								unset($data[$i]['id']);
								$data[$i]['created_date'] = ($result['Pointdata']['created'] != null) ? ($result['Pointdata']['created']) : '';
								$data[$i]['patient_name'] = ($result['Pointdata']['patient_name'] != null) ? ($result['Pointdata']['patient_name']) : '';
								if (!empty($result['Pointdata']['file'])) {
									$data[$i]['pdf']=WWW_BASE.'pointData/'.$result['Pointdata']['file'];
									//$data[$i]['pdf'] = WWW_BASE . 'apisnew/fileDownloadUrl/' . $result['Pointdata']['file'];
								}
								$data[$i]['color'] = ($result['Pointdata']['color'] != null) ? ($result['Pointdata']['color']) : '';
								$data[$i]['patient_id'] = isset($result['Pointdata']['patient_id']) ? ($result['Pointdata']['patient_id']) : '';
								$data[$i]['test_name'] = ($result['Pointdata']['test_name'] != null) ? ($result['Pointdata']['test_name']) : '';
								$data[$i]['backgroundcolor'] = ($result['Pointdata']['backgroundcolor'] != null) ? ($result['Pointdata']['backgroundcolor']) : '';
								$data[$i]['age_group'] = $result['Pointdata']['age_group'];
								$data[$i]['threshold'] = @$result['Pointdata']['threshold'];
								$data[$i]['strategy'] = @$result['Pointdata']['strategy'];
								$data[$i]['test_color_fg'] = @$result['Pointdata']['test_color_fg'];
								$data[$i]['test_color_bg'] = @$result['Pointdata']['test_color_bg'];
								$data[$i]['stmsize'] = ($result['Pointdata']['stmsize'] != null) ? ($result['Pointdata']['stmsize']) : '';
								/* $data[$i]['stmsize']=($result['Pointdata']['test_name']!=null)?($result['Pointdata']['test_name']):''; */
								$data[$i]['master_key'] = ($result['Pointdata']['master_key'] != null) ? ($result['Pointdata']['master_key']) : '';
								$data[$i]['test_type_id'] = ($result['Pointdata']['test_type_id'] != null) ? ($result['Pointdata']['test_type_id']) : '';
								$data[$i]['numpoints'] = ($result['Pointdata']['numpoints'] != null) ? ($result['Pointdata']['numpoints']) : '';
								$data[$i]['eye_select'] = ($result['Pointdata']['eye_select'] != null) ? ($result['Pointdata']['eye_select']) : '';
								$data[$i]['latitude'] = !empty($result['Pointdata']['latitude']) ? ($result['Pointdata']['latitude']) : '';
								$data[$i]['longitude'] = !empty($result['Pointdata']['longitude']) ? ($result['Pointdata']['longitude']) : '';
								$data[$i]['unique_id'] = !empty($result['Pointdata']['unique_id']) ? ($result['Pointdata']['unique_id']) : '';
								$data[$i]['version'] = !empty($result['Pointdata']['version']) ? ($result['Pointdata']['version']) : '';
								$data[$i]['diagnosys'] = !empty($result['Pointdata']['diagnosys']) ? ($result['Pointdata']['diagnosys']) : '';
								if (isset($data[$i]['file'])) unset($data[$i]['file']);
								if (isset($data[$i]['created'])) unset($data[$i]['created']);
								$last_sync_time = !empty($last_sync_time) ? $last_sync_time : $result['Pointdata']['created'];
								$i++;
							}
						}
						//pr($data);die;
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['last_sync_time'] = $last_sync_time;
							$response['result'] = 1;
							$response['more_data'] = $more_data;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $more_data;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}

	/*Get VS report create new API by Madan 24-11-2022*/
	public function get_pointData_report_vs_v6()
	{
		if ($this->check_key()) {
			//echo 'hello';die;
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				//pr($data_arrays);die;
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 10 - 1;
					} else {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = ($data_arrays['page'] - 1) * 10;
						$end = $data_arrays['page'] * 10 - 1;
					}
					//$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$data_arrays['staff_id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.office_id')));
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id']), 'fields' => array('User.office_id')));
					//pr($office_id); die;
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
					$this->Pointdata->virtualFields['patient_name'] = 'select concat(first_name," ",middle_name," ",last_name) from mmd_patients as patients where Pointdata.patient_id = patients.id';
					/* $this->Pointdata->virtualFields['patient_id'] = 'select id from mmd_patients as patients where Pointdata.patient_id = patients.id'; */
					$this->Pointdata->virtualFields['staff_name'] = 'select concat(first_name," ",middle_name," ",last_name) as name from mmd_users as users where Pointdata.staff_id = users.id';
					//$this->VfPointdata->virtualFields['test_id'] = 'VfPointdata.point_data_id';
					$condition['Pointdata.staff_id'] = $all_staff_ids;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['Pointdata.patient_id'] = $data_arrays['patient_id'];
					}
					if (isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["Pointdata.patient_name LIKE"] = '%' . $data_arrays['patient_name'] . "%";
					}
					$condition["Pointdata.test_name LIKE"] = '%Vision Screening%';
					/*if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['Pointdata.created >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}*/
					if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['Pointdata.created_date_utc >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}
					$this->Pointdata->unbindModel(array('hasMany' => array('VfPointdata')));
					$results = $this->Pointdata->find('all', array('conditions' => $condition, 'fields' => array('Pointdata.id', 'Pointdata.age_group', 'Pointdata.created','Pointdata.created_date_utc', 'Pointdata.patient_name', 'Pointdata.staff_name', 'Pointdata.file', 'Pointdata.color', 'Pointdata.backgroundcolor', 'Pointdata.stmsize', 'Pointdata.patient_id', 'Pointdata.eye_select', 'Pointdata.baseline', 'Pointdata.numpoints', 'Pointdata.test_type_id', 'Pointdata.master_key', 'Test.name', 'Pointdata.test_name', 'Pointdata.threshold', 'Pointdata.strategy', 'Pointdata.test_color_fg', 'Pointdata.test_color_bg', 'Pointdata.mean_dev', 'Pointdata.pattern_std', 'Pointdata.mean_sen', 'Pointdata.mean_def', 'Pointdata.pattern_std_hfa', 'Pointdata.psd_hfa', 'Pointdata.vission_loss', 'Pointdata.false_p', 'Pointdata.false_n', 'Pointdata.false_f', 'Pointdata.psd_hfa_2', 'Pointdata.loss_var', 'Pointdata.mean_std', 'Pointdata.ght', 'Pointdata.latitude', 'Pointdata.longitude', 'Pointdata.unique_id', 'Pointdata.version', 'Pointdata.diagnosys'), 'order' => array('Pointdata.id DESC'), 'limit' => $limit));
					//pr($results); die;
					if ($data_arrays['page'] != 0) {
						if ((count($results) > $data_arrays['page'] * 10)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
					if (!empty($results)) {
						$data = array();
						if ($data_arrays['page'] == 0) {
							$end = count($results) - 1;
						}
						$i = 0;
						$last_sync_time = '';
						foreach ($results as $key => $result) { //pr($result); die;
							if ($key >= $start && $key <= $end) {
								// pr($result);die;
								$data[$i] = $result['Pointdata'];
								$data[$i]['test_id'] = $result['Pointdata']['id'];
								unset($data[$i]['id']);
								$data[$i]['created_date'] = ($result['Pointdata']['created'] != null) ? ($result['Pointdata']['created']) : '';
								$data[$i]['patient_name'] = ($result['Pointdata']['patient_name'] != null) ? ($result['Pointdata']['patient_name']) : '';
								if (!empty($result['Pointdata']['file'])) {
									$data[$i]['pdf']=WWW_BASE.'pointData/'.$result['Pointdata']['file'];
									//$data[$i]['pdf'] = WWW_BASE . 'apisnew/fileDownloadUrl/' . $result['Pointdata']['file'];
								}
								$data[$i]['color'] = ($result['Pointdata']['color'] != null) ? ($result['Pointdata']['color']) : '';
								$data[$i]['patient_id'] = isset($result['Pointdata']['patient_id']) ? ($result['Pointdata']['patient_id']) : '';
								$data[$i]['test_name'] = ($result['Pointdata']['test_name'] != null) ? ($result['Pointdata']['test_name']) : '';
								$data[$i]['backgroundcolor'] = ($result['Pointdata']['backgroundcolor'] != null) ? ($result['Pointdata']['backgroundcolor']) : '';
								$data[$i]['age_group'] = $result['Pointdata']['age_group'];
								$data[$i]['threshold'] = @$result['Pointdata']['threshold'];
								$data[$i]['strategy'] = @$result['Pointdata']['strategy'];
								$data[$i]['test_color_fg'] = @$result['Pointdata']['test_color_fg'];
								$data[$i]['test_color_bg'] = @$result['Pointdata']['test_color_bg'];
								$data[$i]['stmsize'] = ($result['Pointdata']['stmsize'] != null) ? ($result['Pointdata']['stmsize']) : '';
								/* $data[$i]['stmsize']=($result['Pointdata']['test_name']!=null)?($result['Pointdata']['test_name']):''; */
								$data[$i]['master_key'] = ($result['Pointdata']['master_key'] != null) ? ($result['Pointdata']['master_key']) : '';
								$data[$i]['test_type_id'] = ($result['Pointdata']['test_type_id'] != null) ? ($result['Pointdata']['test_type_id']) : '';
								$data[$i]['numpoints'] = ($result['Pointdata']['numpoints'] != null) ? ($result['Pointdata']['numpoints']) : '';
								$data[$i]['eye_select'] = ($result['Pointdata']['eye_select'] != null) ? ($result['Pointdata']['eye_select']) : '';
								$data[$i]['latitude'] = !empty($result['Pointdata']['latitude']) ? ($result['Pointdata']['latitude']) : '';
								$data[$i]['longitude'] = !empty($result['Pointdata']['longitude']) ? ($result['Pointdata']['longitude']) : '';
								$data[$i]['unique_id'] = !empty($result['Pointdata']['unique_id']) ? ($result['Pointdata']['unique_id']) : '';
								$data[$i]['version'] = !empty($result['Pointdata']['version']) ? ($result['Pointdata']['version']) : '';
								$data[$i]['diagnosys'] = !empty($result['Pointdata']['diagnosys']) ? ($result['Pointdata']['diagnosys']) : '';
								if (isset($data[$i]['file'])) unset($data[$i]['file']);
								if (isset($data[$i]['created'])) unset($data[$i]['created']);
								$last_sync_time = !empty($last_sync_time) ? $last_sync_time : $result['Pointdata']['created_date_utc'];
								$i++;
							}
						}
						if($data_arrays['sync_start_time'] == ''){
							date_default_timezone_set('UTC');
            				$UTCDate = date('Y-m-d H:i:s');
							$last_sync_time = $UTCDate;
						}
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['last_sync_time'] = $last_sync_time;
							$response['result'] = 1;
							$response['more_data'] = $more_data;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $more_data;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}
	/*Get VS report create new API by Madan 24-11-2022*/
	public function get_pointData_report_fdt()
	{
		if ($this->check_key()) {
			//echo 'hello';die;
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				//pr($data_arrays);die;
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 10 - 1;
					} else {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = ($data_arrays['page'] - 1) * 10;
						$end = $data_arrays['page'] * 10 - 1;
					}
					//$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$data_arrays['staff_id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.office_id')));
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id']), 'fields' => array('User.office_id')));
					//pr($office_id); die;
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
					$this->Pointdata->virtualFields['patient_name'] = 'select concat(first_name," ",middle_name," ",last_name) from mmd_patients as patients where Pointdata.patient_id = patients.id';
					/* $this->Pointdata->virtualFields['patient_id'] = 'select id from mmd_patients as patients where Pointdata.patient_id = patients.id'; */
					$this->Pointdata->virtualFields['staff_name'] = 'select concat(first_name," ",middle_name," ",last_name) as name from mmd_users as users where Pointdata.staff_id = users.id';
					//$this->VfPointdata->virtualFields['test_id'] = 'VfPointdata.point_data_id';
					$condition['Pointdata.staff_id'] = $all_staff_ids;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['Pointdata.patient_id'] = $data_arrays['patient_id'];
					}
					if (isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["Pointdata.patient_name LIKE"] = '%' . $data_arrays['patient_name'] . "%";
					}
					$fdtTestNames = array("C20-1", "C20-5", "N30-1", "N30-5","N30");
					$condition["Pointdata.test_name"] = $fdtTestNames;
					if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['Pointdata.created >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}
					$this->Pointdata->unbindModel(array('hasMany' => array('VfPointdata')));
					$results = $this->Pointdata->find('all', array('conditions' => $condition, 'fields' => array('Pointdata.id', 'Pointdata.age_group', 'Pointdata.created', 'Pointdata.patient_name', 'Pointdata.staff_name', 'Pointdata.file', 'Pointdata.color', 'Pointdata.backgroundcolor', 'Pointdata.stmsize', 'Pointdata.patient_id', 'Pointdata.eye_select', 'Pointdata.baseline', 'Pointdata.numpoints', 'Pointdata.test_type_id', 'Pointdata.master_key', 'Test.name', 'Pointdata.test_name', 'Pointdata.threshold', 'Pointdata.strategy', 'Pointdata.test_color_fg', 'Pointdata.test_color_bg', 'Pointdata.mean_dev', 'Pointdata.pattern_std', 'Pointdata.mean_sen', 'Pointdata.mean_def', 'Pointdata.pattern_std_hfa', 'Pointdata.psd_hfa', 'Pointdata.vission_loss', 'Pointdata.false_p', 'Pointdata.false_n', 'Pointdata.false_f', 'Pointdata.psd_hfa_2', 'Pointdata.loss_var', 'Pointdata.mean_std', 'Pointdata.ght', 'Pointdata.latitude', 'Pointdata.longitude', 'Pointdata.unique_id', 'Pointdata.version', 'Pointdata.diagnosys'), 'order' => array('Pointdata.id DESC'), 'limit' => $limit));
					//pr($results); die;
					if ($data_arrays['page'] != 0) {
						if ((count($results) > $data_arrays['page'] * 10)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
					if (!empty($results)) {
						$data = array();
						if ($data_arrays['page'] == 0) {
							$end = count($results) - 1;
						}
						$i = 0;
						$last_sync_time = '';
						foreach ($results as $key => $result) {
							if ($key >= $start && $key <= $end) {
								// pr($result);die;
								$data[$i] = $result['Pointdata'];
								$data[$i]['test_id'] = $result['Pointdata']['id'];
								unset($data[$i]['id']);
								$data[$i]['created_date'] = ($result['Pointdata']['created'] != null) ? ($result['Pointdata']['created']) : '';
								$data[$i]['patient_name'] = ($result['Pointdata']['patient_name'] != null) ? ($result['Pointdata']['patient_name']) : '';
								if (!empty($result['Pointdata']['file'])) {
									$data[$i]['pdf']=WWW_BASE.'pointData/'.$result['Pointdata']['file'];
									//$data[$i]['pdf'] =WWW_BASE . 'app/webroot/fileDownloadUrl/' . $result['Pointdata']['file'];
								}
								$data[$i]['color'] = ($result['Pointdata']['color'] != null) ? ($result['Pointdata']['color']) : '';
								$data[$i]['patient_id'] = isset($result['Pointdata']['patient_id']) ? ($result['Pointdata']['patient_id']) : '';
								$data[$i]['test_name'] = ($result['Pointdata']['test_name'] != null) ? ($result['Pointdata']['test_name']) : '';
								$data[$i]['backgroundcolor'] = ($result['Pointdata']['backgroundcolor'] != null) ? ($result['Pointdata']['backgroundcolor']) : '';
								$data[$i]['age_group'] = $result['Pointdata']['age_group'];
								$data[$i]['threshold'] = @$result['Pointdata']['threshold'];
								$data[$i]['strategy'] = @$result['Pointdata']['strategy'];
								$data[$i]['test_color_fg'] = @$result['Pointdata']['test_color_fg'];
								$data[$i]['test_color_bg'] = @$result['Pointdata']['test_color_bg'];
								$data[$i]['stmsize'] = ($result['Pointdata']['stmsize'] != null) ? ($result['Pointdata']['stmsize']) : '';
								/* $data[$i]['stmsize']=($result['Pointdata']['test_name']!=null)?($result['Pointdata']['test_name']):''; */
								$data[$i]['master_key'] = ($result['Pointdata']['master_key'] != null) ? ($result['Pointdata']['master_key']) : '';
								$data[$i]['test_type_id'] = ($result['Pointdata']['test_type_id'] != null) ? ($result['Pointdata']['test_type_id']) : '';
								$data[$i]['numpoints'] = ($result['Pointdata']['numpoints'] != null) ? ($result['Pointdata']['numpoints']) : '';
								$data[$i]['eye_select'] = ($result['Pointdata']['eye_select'] != null) ? ($result['Pointdata']['eye_select']) : '';
								$data[$i]['latitude'] = !empty($result['Pointdata']['latitude']) ? ($result['Pointdata']['latitude']) : '';
								$data[$i]['longitude'] = !empty($result['Pointdata']['longitude']) ? ($result['Pointdata']['longitude']) : '';
								$data[$i]['unique_id'] = !empty($result['Pointdata']['unique_id']) ? ($result['Pointdata']['unique_id']) : '';
								$data[$i]['version'] = !empty($result['Pointdata']['version']) ? ($result['Pointdata']['version']) : '';
								$data[$i]['diagnosys'] = !empty($result['Pointdata']['diagnosys']) ? ($result['Pointdata']['diagnosys']) : '';
								if (isset($data[$i]['file'])) unset($data[$i]['file']);
								if (isset($data[$i]['created'])) unset($data[$i]['created']);
								$last_sync_time = !empty($last_sync_time) ? $last_sync_time : $result['Pointdata']['created'];
								$i++;
							}
						}
						//pr($data);die;
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['last_sync_time'] = $last_sync_time;
							$response['result'] = 1;
							$response['more_data'] = $more_data;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $more_data;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}

	/*Get FDT report craete new API by Madan 24-11-2022*/
	public function get_pointData_report_fdt_v6()
	{
		if ($this->check_key()) {
			//echo 'hello';die;
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				//pr($data_arrays);die;
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 10 - 1;
					} else {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = ($data_arrays['page'] - 1) * 10;
						$end = $data_arrays['page'] * 10 - 1;
					}
					//$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$data_arrays['staff_id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.office_id')));
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id']), 'fields' => array('User.office_id')));
					//pr($office_id); die;
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
					$this->Pointdata->virtualFields['patient_name'] = 'select concat(first_name," ",middle_name," ",last_name) from mmd_patients as patients where Pointdata.patient_id = patients.id';
					/* $this->Pointdata->virtualFields['patient_id'] = 'select id from mmd_patients as patients where Pointdata.patient_id = patients.id'; */
					$this->Pointdata->virtualFields['staff_name'] = 'select concat(first_name," ",middle_name," ",last_name) as name from mmd_users as users where Pointdata.staff_id = users.id';
					//$this->VfPointdata->virtualFields['test_id'] = 'VfPointdata.point_data_id';
					$condition['Pointdata.staff_id'] = $all_staff_ids;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['Pointdata.patient_id'] = $data_arrays['patient_id'];
					}
					if (isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["Pointdata.patient_name LIKE"] = '%' . $data_arrays['patient_name'] . "%";
					}
					$fdtTestNames = array("C20-1", "C20-5", "N30-1", "N30-5","N30");
					$condition["Pointdata.test_name"] = $fdtTestNames;
					if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['Pointdata.created_date_utc >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}
					$this->Pointdata->unbindModel(array('hasMany' => array('VfPointdata')));
					$results = $this->Pointdata->find('all', array('conditions' => $condition, 'fields' => array('Pointdata.id', 'Pointdata.age_group', 'Pointdata.created','Pointdata.created_date_utc', 'Pointdata.patient_name', 'Pointdata.staff_name', 'Pointdata.file', 'Pointdata.color', 'Pointdata.backgroundcolor', 'Pointdata.stmsize', 'Pointdata.patient_id', 'Pointdata.eye_select', 'Pointdata.baseline', 'Pointdata.numpoints', 'Pointdata.test_type_id', 'Pointdata.master_key', 'Test.name', 'Pointdata.test_name', 'Pointdata.threshold', 'Pointdata.strategy', 'Pointdata.test_color_fg', 'Pointdata.test_color_bg', 'Pointdata.mean_dev', 'Pointdata.pattern_std', 'Pointdata.mean_sen', 'Pointdata.mean_def', 'Pointdata.pattern_std_hfa', 'Pointdata.psd_hfa', 'Pointdata.vission_loss', 'Pointdata.false_p', 'Pointdata.false_n', 'Pointdata.false_f', 'Pointdata.psd_hfa_2', 'Pointdata.loss_var', 'Pointdata.mean_std', 'Pointdata.ght', 'Pointdata.latitude', 'Pointdata.longitude', 'Pointdata.unique_id', 'Pointdata.version', 'Pointdata.diagnosys'), 'order' => array('Pointdata.id DESC'), 'limit' => $limit));
					//pr($results); die;
					if ($data_arrays['page'] != 0) {
						if ((count($results) > $data_arrays['page'] * 10)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
					if (!empty($results)) {
						$data = array();
						if ($data_arrays['page'] == 0) {
							$end = count($results) - 1;
						}
						$i = 0;
						$last_sync_time = '';
						foreach ($results as $key => $result) {
							if ($key >= $start && $key <= $end) {
								// pr($result);die;
								$data[$i] = $result['Pointdata'];
								$data[$i]['test_id'] = $result['Pointdata']['id'];
								unset($data[$i]['id']);
								$data[$i]['created_date'] = ($result['Pointdata']['created'] != null) ? ($result['Pointdata']['created']) : '';
								$data[$i]['patient_name'] = ($result['Pointdata']['patient_name'] != null) ? ($result['Pointdata']['patient_name']) : '';
								if (!empty($result['Pointdata']['file'])) {
									$data[$i]['pdf']=WWW_BASE.'pointData/'.$result['Pointdata']['file'];
									//$data[$i]['pdf'] =WWW_BASE . 'app/webroot/fileDownloadUrl/' . $result['Pointdata']['file'];
								}
								$data[$i]['color'] = ($result['Pointdata']['color'] != null) ? ($result['Pointdata']['color']) : '';
								$data[$i]['patient_id'] = isset($result['Pointdata']['patient_id']) ? ($result['Pointdata']['patient_id']) : '';
								$data[$i]['test_name'] = ($result['Pointdata']['test_name'] != null) ? ($result['Pointdata']['test_name']) : '';
								$data[$i]['backgroundcolor'] = ($result['Pointdata']['backgroundcolor'] != null) ? ($result['Pointdata']['backgroundcolor']) : '';
								$data[$i]['age_group'] = $result['Pointdata']['age_group'];
								$data[$i]['threshold'] = @$result['Pointdata']['threshold'];
								$data[$i]['strategy'] = @$result['Pointdata']['strategy'];
								$data[$i]['test_color_fg'] = @$result['Pointdata']['test_color_fg'];
								$data[$i]['test_color_bg'] = @$result['Pointdata']['test_color_bg'];
								$data[$i]['stmsize'] = ($result['Pointdata']['stmsize'] != null) ? ($result['Pointdata']['stmsize']) : '';
								/* $data[$i]['stmsize']=($result['Pointdata']['test_name']!=null)?($result['Pointdata']['test_name']):''; */
								$data[$i]['master_key'] = ($result['Pointdata']['master_key'] != null) ? ($result['Pointdata']['master_key']) : '';
								$data[$i]['test_type_id'] = ($result['Pointdata']['test_type_id'] != null) ? ($result['Pointdata']['test_type_id']) : '';
								$data[$i]['numpoints'] = ($result['Pointdata']['numpoints'] != null) ? ($result['Pointdata']['numpoints']) : '';
								$data[$i]['eye_select'] = ($result['Pointdata']['eye_select'] != null) ? ($result['Pointdata']['eye_select']) : '';
								$data[$i]['latitude'] = !empty($result['Pointdata']['latitude']) ? ($result['Pointdata']['latitude']) : '';
								$data[$i]['longitude'] = !empty($result['Pointdata']['longitude']) ? ($result['Pointdata']['longitude']) : '';
								$data[$i]['unique_id'] = !empty($result['Pointdata']['unique_id']) ? ($result['Pointdata']['unique_id']) : '';
								$data[$i]['version'] = !empty($result['Pointdata']['version']) ? ($result['Pointdata']['version']) : '';
								$data[$i]['diagnosys'] = !empty($result['Pointdata']['diagnosys']) ? ($result['Pointdata']['diagnosys']) : '';
								if (isset($data[$i]['file'])) unset($data[$i]['file']);
								if (isset($data[$i]['created'])) unset($data[$i]['created']);
								$last_sync_time = !empty($last_sync_time) ? $last_sync_time : $result['Pointdata']['created_date_utc'];
								$i++;
							}
						}
						if($data_arrays['sync_start_time'] == ''){
							date_default_timezone_set('UTC');
            				$UTCDate = date('Y-m-d H:i:s');
							$last_sync_time = $UTCDate;
						}
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['last_sync_time'] = $last_sync_time;
							$response['result'] = 1;
							$response['more_data'] = $more_data;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $more_data;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}
	/*Get FDT report craete new API by Madan 24-11-2022*/

	public function get_va_report()
	{
		if ($this->check_key()) {
			//echo 'hello';die;
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				//pr($data_arrays);die;
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 10 - 1;
					} else {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = ($data_arrays['page'] - 1) * 10;
						$end = $data_arrays['page'] * 10 - 1;
					}
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id'], 'User.user_type' => 'Staffuser'), 'fields' => array('User.office_id')));
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id'], 'User.user_type' => 'Staffuser'), 'fields' => array('User.id')));
					//$this->VaData->virtualFields['patient_name'] = 'select concat(first_name," ",middle_name," ",last_name) from mmd_patients as patients where VaData.patient_id = patients.id';
					/* $this->Pointdata->virtualFields['patient_id'] = 'select id from mmd_patients as patients where Pointdata.patient_id = patients.id'; */
					//$this->VaData->virtualFields['staff_name'] = 'select concat(first_name," ",middle_name," ",last_name) as name from mmd_users as users where VaData.staff_id = users.id';
					$condition['VaData.staff_id'] = $all_staff_ids;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['VaData.patient_id'] = $data_arrays['patient_id'];
					}
					if (isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["VaData.patient_name LIKE"] = '%' . $data_arrays['patient_name'] . "%";
					}
					if (isset($data_arrays['test_name']) && !empty($data_arrays['test_name'])) {
						//$condition['VaData.patient_name'] = $data_arrays['patient_name'];
						$condition["VaData.test_name LIKE"] = '%' . $data_arrays['test_name'] . "%";
					}
					$this->loadModel('VaData');
					$this->loadModel('User');
					$results = $this->VaData->find('all', array('conditions' => $condition, 'fields' => array('VaData.id', 'VaData.staff_id', 'VaData.created', 'VaData.patient_name', 'VaData.pdf', 'VaData.patient_id', 'VaData.eye_select', 'VaData.test_name'), 'order' => array('VaData.id DESC'), 'limit' => $limit));
					if ($data_arrays['page'] != 0) {
						if ((count($results) > $data_arrays['page'] * 10)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
					if (!empty($results)) {
						$data = array();
						if ($data_arrays['page'] == 0) {
							$end = count($results) - 1;
						}
						$i = 0;
						foreach ($results as $key => $result) {
							if ($key >= $start && $key <= $end) {
								//pr($result);die;
								$user = $this->User->findById($result['VaData']['staff_id']);
								$data[$i] = $result['VaData'];
								$data[$i]['staff_name'] = @$user['User']['first_name'] . ' ' . @$user['User']['middle_name'] . ' ' . @$user['User']['last_name'];
								$data[$i]['test_id'] = $result['VaData']['id'];
								unset($data[$i]['id']);
								$data[$i]['created_date'] = ($result['VaData']['created'] != null) ? ($result['VaData']['created']) : '';
								$data[$i]['patient_name'] = ($result['VaData']['patient_name'] != null) ? ($result['VaData']['patient_name']) : '';
								if (!empty($result['VaData']['pdf'])) {
									$data[$i]['pdf'] = WWW_BASE . 'pointData/' . $result['VaData']['pdf'];
								} else {
									$data[$i]['pdf'] = '';
								}
								$data[$i]['patient_id'] = isset($result['VaData']['patient_id']) ? ($result['VaData']['patient_id']) : '';
								$data[$i]['test_name'] = ($result['VaData']['test_name'] != null) ? ($result['VaData']['test_name']) : '';
								$data[$i]['eye_select'] = ($result['VaData']['eye_select'] != null) ? ($result['VaData']['eye_select']) : '';
								//if(isset($data[$i]['pdf'])) unset($data[$i]['pdf']) ;
								if (isset($data[$i]['created'])) unset($data[$i]['created']);
								$i++;
							}
						}
						//pr($data);die;
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['result'] = 1;
							$response['more_data'] = $more_data;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $more_data;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}
	public function get_cs_report()
	{
		if ($this->check_key()) {
			//echo 'hello';die;
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				//pr($data_arrays);die;
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 10 - 1;
					} else {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = ($data_arrays['page'] - 1) * 10;
						$end = $data_arrays['page'] * 10 - 1;
					}
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id'], 'User.user_type' => 'Staffuser'), 'fields' => array('User.office_id')));
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id'], 'User.user_type' => 'Staffuser'), 'fields' => array('User.id')));
					//$this->VaData->virtualFields['patient_name'] = 'select concat(first_name," ",middle_name," ",last_name) from mmd_patients as patients where VaData.patient_id = patients.id';
					/* $this->Pointdata->virtualFields['patient_id'] = 'select id from mmd_patients as patients where Pointdata.patient_id = patients.id'; */
					//$this->VaData->virtualFields['staff_name'] = 'select concat(first_name," ",middle_name," ",last_name) as name from mmd_users as users where VaData.staff_id = users.id';
					$condition['CsData.staff_id'] = $all_staff_ids;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['CsData.patient_id'] = $data_arrays['patient_id'];
					}
					if (isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["CsData.patient_name LIKE"] = '%' . $data_arrays['patient_name'] . "%";
					}
					/* if(isset($data_arrays['test_name']) && !empty($data_arrays['test_name'])){
						//$condition['VaData.patient_name'] = $data_arrays['patient_name'];
						$condition["CsData.test_name LIKE"] = '%'.$data_arrays['test_name']."%";
					}  */
					$this->loadModel('CsData');
					$this->loadModel('User');
					$results = $this->CsData->find('all', array('conditions' => $condition, 'fields' => array('CsData.id', 'CsData.staff_id', 'CsData.created', 'CsData.patient_name', 'CsData.pdf', 'CsData.patient_id', 'CsData.eye_select'), 'order' => array('CsData.id DESC'), 'limit' => $limit));
					if ($data_arrays['page'] != 0) {
						if ((count($results) > $data_arrays['page'] * 10)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
					if (!empty($results)) {
						$data = array();
						if ($data_arrays['page'] == 0) {
							$end = count($results) - 1;
						}
						$i = 0;
						foreach ($results as $key => $result) {
							if ($key >= $start && $key <= $end) {
								//pr($result);die;
								$user = $this->User->findById($result['CsData']['staff_id']);
								$data[$i] = $result['CsData'];
								$data[$i]['staff_name'] = @$user['User']['first_name'] . ' ' . @$user['User']['middle_name'] . ' ' . @$user['User']['last_name'];
								$data[$i]['test_id'] = $result['CsData']['id'];
								unset($data[$i]['id']);
								$data[$i]['created_date'] = ($result['CsData']['created'] != null) ? ($result['CsData']['created']) : '';
								$data[$i]['patient_name'] = ($result['CsData']['patient_name'] != null) ? ($result['CsData']['patient_name']) : '';
								if (!empty($result['CsData']['pdf'])) {
									$data[$i]['pdf'] = WWW_BASE . 'pointData/' . $result['CsData']['pdf'];
								} else {
									$data[$i]['pdf'] = '';
								}
								$data[$i]['patient_id'] = isset($result['CsData']['patient_id']) ? ($result['CsData']['patient_id']) : '';
								$data[$i]['eye_select'] = ($result['CsData']['eye_select'] != null) ? ($result['CsData']['eye_select']) : '';
								//if(isset($data[$i]['pdf'])) unset($data[$i]['pdf']) ;
								if (isset($data[$i]['created'])) unset($data[$i]['created']);
								$i++;
							}
						}
						//pr($data);die;
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['result'] = 1;
							$response['more_data'] = $more_data;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $more_data;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}
	public function get_refractors_report()
	{
		if ($this->check_key()) {
			//echo 'hello';die;
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				if (empty($data_arrays)) {
					$data_arrays = $_POST;
				}
				//pr($data_arrays);die;
				if (isset($data_arrays['page']) && (!empty($data_arrays['staff_id']))) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 10 - 1;
					} else {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = ($data_arrays['page'] - 1) * 10;
						$end = $data_arrays['page'] * 10 - 1;
					}
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id'], 'User.user_type' => 'Staffuser'), 'fields' => array('User.office_id')));
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id'], 'User.user_type' => 'Staffuser'), 'fields' => array('User.id')));
					$condition['Refractor.staff_id'] = $all_staff_ids;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['Refractor.patient_id'] = $data_arrays['patient_id'];
					}
					if (isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])) {
						$condition["Refractor.patient_name LIKE"] = '%' . $data_arrays['patient_name'] . "%";
					}
					if (isset($data_arrays['test_name']) && !empty($data_arrays['test_name'])) {
						//$condition['VaData.patient_name'] = $data_arrays['patient_name'];
						$condition["Refractor.test_name LIKE"] = '%' . $data_arrays['test_name'] . "%";
					}
					$this->loadModel('Refractor');
					$this->loadModel('User');
					$results = $this->Refractor->find('all', array('conditions' => $condition, 'fields' => array('Refractor.id', 'Refractor.staff_id', 'Refractor.created', 'Refractor.patient_name', 'Refractor.pdf', 'Refractor.patient_id', 'Refractor.eye_select'), 'order' => array('Refractor.id DESC'), 'limit' => $limit));
					if ($data_arrays['page'] != 0) {
						if ((count($results) > $data_arrays['page'] * 10)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
					if (!empty($results)) {
						$data = array();
						if ($data_arrays['page'] == 0) {
							$end = count($results) - 1;
						}
						$i = 0;
						foreach ($results as $key => $result) {
							if ($key >= $start && $key <= $end) {
								//pr($result);die;
								$user = $this->User->findById($result['Refractor']['staff_id']);
								$data[$i] = $result['Refractor'];
								$data[$i]['staff_name'] = @$user['User']['first_name'] . ' ' . @$user['User']['middle_name'] . ' ' . @$user['User']['last_name'];
								$data[$i]['test_id'] = $result['Refractor']['id'];
								unset($data[$i]['id']);
								$data[$i]['created_date'] = ($result['Refractor']['created'] != null) ? ($result['Refractor']['created']) : '';
								$data[$i]['patient_name'] = ($result['Refractor']['patient_name'] != null) ? ($result['Refractor']['patient_name']) : '';
								if (!empty($result['Refractor']['pdf'])) {
									$data[$i]['pdf'] = WWW_BASE . 'pointData/' . $result['Refractor']['pdf'];
								} else {
									$data[$i]['pdf'] = '';
								}
								$data[$i]['patient_id'] = isset($result['Refractor']['patient_id']) ? ($result['Refractor']['patient_id']) : '';
								$data[$i]['eye_select'] = ($result['Refractor']['eye_select'] != null) ? ($result['Refractor']['eye_select']) : '';
								//if(isset($data[$i]['pdf'])) unset($data[$i]['pdf']) ;
								if (isset($data[$i]['created'])) unset($data[$i]['created']);
								$i++;
							}
						}
						//pr($data);die;
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['result'] = 1;
							$response['more_data'] = $more_data;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $more_data;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}
	/*API for check/uncheck master key in Pointdata test report*/
	public function checkpointdatareportByMaster()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if ((isset($data_arrays['test_id'])) && (!empty($data_arrays['test_id'])) && (isset($data_arrays['master_key']))) {
					$data['Pointdata']['id'] = @$data_arrays['test_id'];
					$data['Pointdata']['master_key'] = @$data_arrays['master_key'];
					$pointData = $this->Pointdata->findById(@$data_arrays['test_id']);
					if (in_array(strtolower(str_replace(' ', '', $pointData['Pointdata']['strategy'])), array('fullthresholdfast', 'fullthresholdtest'))) {
						$this->Pointdata->updateAll(array('Pointdata.master_key' => 0), array('Pointdata.test_name' => $pointData['Pointdata']['test_name'], 'Pointdata.patient_id' => $pointData['Pointdata']['patient_id'], 'Pointdata.eye_select' => $pointData['Pointdata']['eye_select'], 'Pointdata.test_color_fg' => $pointData['Pointdata']['test_color_fg'], 'Pointdata.test_color_bg' => $pointData['Pointdata']['test_color_bg']));
						$result = $this->Pointdata->save($data);
						if ($result) {
							$response['message'] = 'Test Report has been Updated successfully.';
							$response['result'] = 1;
						} else {
							$response['message'] = 'Some error occured in updating test report.';
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'You can not set this record to master.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Please send test report id.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*API for get pointdata by  test id*/
	public function checkpointdatareportById()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if ((isset($data_arrays['test_id'])) && (!empty($data_arrays['test_id']))) {
					$id = $data_arrays['test_id'];
					$data = $this->Pointdata->findById($id);
					if ($data) {
						$response['status'] = 1;
						$response['result'] = $data;
					} else {
						$response['message'] = 'Please send valid test report id.';
						$response['status'] = 0;
					}
				} else {
					$response['message'] = 'Please send test report id.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*API for get pointdata by  test id*/
	public function macaddress_by_office_id()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if ((isset($data_arrays['office_id'])) && (!empty($data_arrays['office_id']))) {
					$id = $data_arrays['office_id'];
					$data = $this->TestDevice->find('all', array('conditions' => array('office_id' => $id), 'fields' => array('mac_address'), 'group' => array('mac_address')));
					if ($data) {
						$response['status'] = 1;
						$response['result'] = $data;
					} else {
						$response['message'] = 'Please send valid test Office id.';
						$response['status'] = 0;
					}
				} else {
					$response['message'] = 'Please send test Office id.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*API for getting master test report by patient id*/
	public function get_master_patient_by_id()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				$this->Pointdata->virtualFields['patient_name'] = 'select concat(first_name," ",middle_name," ",last_name) from mmd_patients as patients where Pointdata.patient_id = patients.id';
				$this->Pointdata->virtualFields['staff_name'] = 'select concat(first_name," ",middle_name," ",last_name) as name from mmd_users as users where Pointdata.staff_id = users.id';
				$this->VfPointdata->virtualFields['test_id'] = 'VfPointdata.point_data_id';
				$condition['Pointdata.patient_id'] = $data_arrays['patient_id'];
				$condition['Pointdata.master_key'] = 1;
				$results = $this->Pointdata->find('all', array('conditions' => $condition, 'fields' => array('id', 'created', 'patient_name', 'file', 'color', 'backgroundcolor', 'stmsize', 'test_name', 'eye_select', 'master_key'), 'order' => array('Pointdata.id DESC')));
				if (!empty($results)) {
					$data = array();
					$i = 0;
					foreach ($results as $key => $result) {
						$data[$i] = $result['Pointdata'];
						$data[$i]['test_id'] = $result['Pointdata']['id'];
						unset($data[$i]['id']);
						$data[$i]['created'] = ($result['Pointdata']['created'] != null) ? ($result['Pointdata']['created']) : '';
						$data[$i]['patient_name'] = ($result['Pointdata']['patient_name'] != null) ? ($result['Pointdata']['patient_name']) : '';
						if (!empty($result['Pointdata']['file'])) {
							$data[$i]['file'] = WWW_BASE . 'pointData/' . $result['Pointdata']['file'];
						}
						$data[$i]['color'] = ($result['Pointdata']['color'] != null) ? ($result['Pointdata']['color']) : '';
						$data[$i]['backgroundcolor'] = ($result['Pointdata']['backgroundcolor'] != null) ? ($result['Pointdata']['backgroundcolor']) : '';
						$data[$i]['stmsize'] = ($result['Pointdata']['stmsize'] != null) ? ($result['Pointdata']['stmsize']) : '';
						$data[$i]['stmsize'] = ($result['Pointdata']['test_name'] != null) ? ($result['Pointdata']['test_name']) : '';
						$data[$i]['stmsize'] = ($result['Pointdata']['master_key'] != null) ? ($result['Pointdata']['master_key']) : '';
						$data[$i]['stmsize'] = ($result['Pointdata']['eye_select'] != null) ? ($result['Pointdata']['eye_select']) : '';
						$i++;
					}
					if (!empty($data)) {
						$response['message'] = 'All test report list.';
						$response['result'] = 1;
						$response['data'] = $data;
					} else {
						$response['message'] = 'No test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'NO test report found.';
					$response['result'] = 0;
				}
				//header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}
	/* API for List patients by staff and names*/
	public function unity_searchlistPatients_v1()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$data_arrays = $_POST;
				// staff ID
				if (isset($data_arrays['page'])) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 25 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 25 - 1;
					} else {
						$limit = $data_arrays['page'] * 25 + 1;
						$start = ($data_arrays['page'] - 1) * 25;
						$end = $data_arrays['page'] * 25 - 1;
					}
				} else {
					$limit = '';
					$start = 0;
				}
				if (isset($data_arrays['staff_id'])) {
					$staff_id = $data_arrays['staff_id'];
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $staff_id), 'fields' => array('User.office_id')));
					if (!empty($office_id)) {
						$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
						$all_staff_ids = implode(",", $all_staff_ids);
						$all_staff_ids = explode(',', $all_staff_ids);
						$patient_staus = isset($data_arrays['showActiveOnly']) ? $data_arrays['showActiveOnly'] : array(0, 1);
						//pr($patient_staus);
						if (isset($data_arrays['search_key']) && !empty($data_arrays['search_key'])) {
							$condition = array(
								'OR' => array(
									/* array(
											"Patients.first_name LIKE" => $data_arrays['search_key'].'%',
										),
										array(
											"Patients.middle_name LIKE" => $data_arrays['search_key'].'%',
										),
										array(
											"Patients.last_name LIKE" => $data_arrays['search_key'].'%',
										), */
									array(
										"Patients.patient_name LIKE" => '%' . $data_arrays['search_key'] . '%',
									),
									array(
										"Patients.email LIKE" => '%' . $data_arrays['search_key'] . '%',
									)
								),
								'Patients.user_id' => $all_staff_ids,
								'Patients.is_delete' => 0,
								'Patients.status' => $patient_staus
							);
						} else {
							$condition = array(
								'Patients.user_id' => $all_staff_ids,
								'Patients.is_delete' => 0,
								'Patients.status' => $patient_staus
							);
						}
						$this->Patients->virtualFields['patient_name'] = 'CONCAT(first_name,middle_name,last_name)';
						$result = $this->Patients->find('all', array('conditions' => $condition, 'order' => array('Patients.id DESC'), 'limit' => $limit));
						//pr($result);die;
					} else {
						$response_array['message'] = 'Invalid staff.';
						$response_array['result'] = 0;
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'Please send staff id.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
					//$result = $this->Patients->find('all',array('conditions'=>array('Patients.is_delete'=>0),'order'=>array('Patients.first_name ASC','Patients.middle_name ASC','Patients.last_name ASC'),'limit'=>$limit));
				}
				$all_result_count = count($result);
				if (isset($data_arrays['page']) && ($data_arrays['page'] == 0)) {
					$end = $all_result_count;
				}
				if (!isset($data_arrays['page'])) {
					$end = $all_result_count;
				}
				if (isset($data_arrays['page'])) {
					if ($data_arrays['page'] != 0) {
						if (($all_result_count > $data_arrays['page'] * 25)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
				} else {
					$more_data = 0;
				}
				if (count($result)) {
					$data = array();
					foreach ($result as $key => $value) {
						if ($key >= $start && $key <= $end) {
							$value['Patients']['patient_id'] = $value['Patients']['id'];
							$value['Patients']['middle_name'] = ($value['Patients']['middle_name'] != null) ? ($value['Patients']['middle_name']) : '';
							$value['Patients']['phone'] = ($value['Patients']['phone'] != null) ? ($value['Patients']['phone']) : '';
							$value['Patients']['id_number'] = ($value['Patients']['id_number'] != null) ? ($value['Patients']['id_number']) : '';
							$value['Patients']['notes'] = ($value['Patients']['notes'] != null) ? ($value['Patients']['notes']) : '';
							$value['Patients']['created'] = ($value['Patients']['created'] != null) ? ($value['Patients']['created']) : '';
							if (!empty($value['Patients']['p_profilepic'])) {
								$value['Patients']['p_profilepic'] = WWW_BASE . $value['Patients']['p_profilepic'];
							} else {
								$value['Patients']['p_profilepic'] = WWW_BASE . 'img/uploads/no-user.png';
							}
							$this->loadModel('Pointdata');
							$report_status = $this->Pointdata->find('all', array('conditions' => array('patient_id' => $value['Patients']['patient_id'])));
							if (count($report_status) > 0) {
								#$report_status = 1;
								$value['Patients']['patient_report_status'] = 1;
							} else {
								$value['Patients']['patient_report_status'] = 0;
							}
							unset($value['Patients']['id']);
							$data[] = $value['Patients'];
						}
					}
					if (!empty($data)) {
						$response_array = array('message' => 'Get patients information.', 'status' => 1, 'more_data' => $more_data, 'data' => $data);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} else {
						$response_array = array('message' => 'No record found.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'No Record found.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
	/* API for Unoity List patients by staff */
	public function unity_listPatients_v1()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$data_arrays = $_POST;
				// staff ID
				$last_sync_time = '';
				if (isset($data_arrays['page'])) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 25 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 25 - 1;
					} else {
						$limit = $data_arrays['page'] * 25 + 1;
						$start = ($data_arrays['page'] - 1) * 25;
						$end = $data_arrays['page'] * 25 - 1;
					}
				} else {
					$limit = '';
					$start = 0;
				}
				if (isset($data_arrays['staff_id'])) {
					$staff_id = $data_arrays['staff_id'];
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $staff_id), 'fields' => array('User.office_id')));
					if (!empty($office_id)) {
						//$patient_staus = isset($data_arrays['showActiveOnly']) ? $data_arrays['showActiveOnly'] : array(0, 1);
						$patient_staus = 1;
						$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
						$all_staff_ids = implode(",", $all_staff_ids);
						$all_staff_ids = explode(',', $all_staff_ids);
						//$this->Patients->recursive = -1;
						//$this->Patients->Pointdata->virtualFields['patient_report_status'] = 'IF(count(Pointdatas.id)>0, 1, 0) AS patient_report_status';
						$this->Patients->virtualFields['patient_report_status'] = "IF(Pointdatas.id>0, 1,0)";
						if (isset($data_arrays['last_sync_time'])) {
							$condition['Patients.created >'] = date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time']));
							$condition['Patients.user_id'] = $all_staff_ids;
							$condition['Patients.is_delete'] = 0;
							//$condition['Patients.status'] = $patient_staus;
							$update_date = date('Y-m-d h:i:s', strtotime($data_arrays['last_sync_time']));
							$result = $this->Patients->find('all',
								array(
									'fields' => array(
										'Patients.*',
										//'IF(Pointdatas.id>0, 1,0) AS Patients__patient_report_status',
									),
									'joins' => array(
										array(
											'table' => 'mmd_pointdatas',
											'alias' => 'Pointdatas',
											'type' => 'LEFT',
											'foreignKey' => false,
											'conditions' => array(
												'Pointdatas.patient_id = Patients.id',
											)
										)
									),
									'conditions' => $condition,
									'order' => array('Patients.id DESC'),
									'group' => array('Patients.id'),
									'limit' => $limit)
							);
						} else {
							$result = $this->Patients->find('all',
								array(
									'fields' => array(
										'Patients.*',
										//'IF(Pointdatas.id>0, 1,0) AS Patients__patient_report_status',
									),
									'joins' => array(
										array(
											'table' => 'mmd_pointdatas',
											'alias' => 'Pointdatas',
											'type' => 'LEFT',
											'foreignKey' => false,
											'conditions' => array(
												'Pointdatas.patient_id = Patients.id',
											)
										)
									),
									'conditions' => array('Patients.user_id' => $all_staff_ids, 'Patients.is_delete' => 0),
										/*'Patients.status' => $patient_staus*/
									'order' => array('Patients.id DESC'),
									'group' => array('Patients.id'),
									'limit' => $limit)
							);
						}
						//$result =$this->Patients->find('all',array('conditions'=>array('Patients.user_id'=>$all_staff_ids,'Patients.is_delete'=>0,'Patients.status'=>$patient_staus),'order'=>array('Patients.id DESC'),'limit'=>$limit));
					} else {
						$response_array['message'] = 'Invalid staff.';
						$response_array['result'] = 0;
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'Please send staff id.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
					//$result = $this->Patients->find('all',array('conditions'=>array('Patients.is_delete'=>0),'order'=>array('Patients.first_name ASC','Patients.middle_name ASC','Patients.last_name ASC'),'limit'=>$limit));
				}
				//pr($result); die;
				$all_result_count = count($result);
				if (isset($data_arrays['page']) && ($data_arrays['page'] == 0)) {
					$end = $all_result_count;
				}
				if (!isset($data_arrays['page'])) {
					$end = $all_result_count;
				}
				if (isset($data_arrays['page'])) {
					if ($data_arrays['page'] != 0) {
						if (($all_result_count > $data_arrays['page'] * 25)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
				} else {
					$more_data = 0;
				}
				if (count($result)) {
					$data = array();
					foreach ($result as $key => $value) {
						if ($key >= $start && $key <= $end) {
							$value['Patients']['patient_id'] = $value['Patients']['id'];
							$value['Patients']['middle_name'] = ($value['Patients']['middle_name'] != null) ? ($value['Patients']['middle_name']) : '';
							$value['Patients']['phone'] = ($value['Patients']['phone'] != null) ? ($value['Patients']['phone']) : '';
							$value['Patients']['id_number'] = ($value['Patients']['id_number'] != null) ? ($value['Patients']['id_number']) : '';
							$value['Patients']['notes'] = ($value['Patients']['notes'] != null) ? ($value['Patients']['notes']) : '';
							$value['Patients']['created'] = ($value['Patients']['created'] != null) ? ($value['Patients']['created']) : '';
							$value['Patients']['unique_id'] = ($value['Patients']['unique_id'] != null) ? ($value['Patients']['unique_id']) : null;
							if (!empty($value['Patients']['p_profilepic'])) {
								$value['Patients']['p_profilepic'] = WWW_BASE . $value['Patients']['p_profilepic'];
							} else {
								$value['Patients']['p_profilepic'] = WWW_BASE . 'img/uploads/no-user.png';
							}
							$value['Patients']['patient_report_status'] = $value['Patients']['patient_report_status'];
							/* $this->loadModel('Pointdata');
						$report_status = $this->Pointdata->find('all',array('conditions'=>array('patient_id'=>$value['Patients']['patient_id'])));
						if(count($report_status)>0){
							#$report_status = 1;
							$value['Patients']['patient_report_status'] = 1;
						}else{
							$value['Patients']['patient_report_status'] = 0;
						}  */
							$last_sync_time = ($last_sync_time != '') ? $last_sync_time : $value['Patients']['created'];
							unset($value['Patients']['id']);
							$data[] = $value['Patients'];
						}
					}
					//$data=$result;
					if (!empty($data)) {
						$response_array = array('message' => 'Get patients information.', 'status' => 1, 'more_data' => $more_data, 'data' => $data, 'last_sync_time' => $last_sync_time);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} else {
						$response_array = array('message' => 'No record found.', 'status' => 0, 'last_sync_time' => $last_sync_time);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'No Record found.', 'status' => 0, 'last_sync_time' => $last_sync_time);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
/* last add patients sync time */
public function unity_listPatients_sepv2()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$data_arrays = $_POST; 
				// staff ID
				$last_sync_time = '';
				if (isset($data_arrays['page'])) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 25 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 25 - 1;
					} else {
						$limit = $data_arrays['page'] * 25 + 1;
						$start = ($data_arrays['page'] - 1) * 25;
						$end = $data_arrays['page'] * 25 - 1;
					}
				} else {
					$limit = '';
					$start = 0;
				}
				//$last_sync_time = array();
				//$condition_deleted['Patients.is_delete']= 1;
				//$condition_deleted['Patients.status']= 0;
				//pr($data_arrays['staff_id']);
				if (isset($data_arrays['staff_id'])) {
					$staff_id = $data_arrays['staff_id'];
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $staff_id), 'fields' => array('User.office_id')));
					//pr($office_id['User']['office_id']);
					$office_archive_status = $this->Office->find('first', array('conditions' => array('Office.id' => $office_id['User']['office_id']), 'fields' => array('Office.archive_status')));
					
					if (!empty($office_id)) {
						if($office_archive_status['Office']['archive_status'] == 1){
							$patient_staus = isset($data_arrays['showActiveOnly']) ? $data_arrays['showActiveOnly'] : array(1, 2);
							//$patient_staus=1;
						 
							$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
							$all_staff_ids = implode(",", $all_staff_ids);
							$all_staff_ids = explode(',', $all_staff_ids); 
							if (isset($data_arrays['last_sync_time_new']) && $data_arrays['last_sync_time_new']!="" ) {
								$condition['Patients.created >'] = date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition['Patients.user_id'] = $all_staff_ids;
								$condition['Patients.is_delete'] = 0;
								$condition['Patients.merge_status'] = 0;
								$condition['Patients.status'] = $patient_staus;  
								$condition_deleted['OR'][]['Patients.delete_date >']= date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new'])); 
								$condition_deleted['OR'][]['Patients.archived_date >']=date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition_deleted['OR'][]['Patients.merge_date >']=date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition_deleted['Patients.user_id']= $all_staff_ids;
								$update_date = date('Y-m-d h:i:s', strtotime($data_arrays['last_sync_time_new']));
								$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*'										
										),
										'conditions' => $condition,
										'order' => array('Patients.id DESC'),
										'group' => array('Patients.id'),
										'limit' => $limit)
								);
							} else {
								$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*',
										),
										'conditions' => array('Patients.user_id' => $all_staff_ids, 'Patients.merge_status' => 0,
										'Patients.status' => $patient_staus),
										'order' => array('Patients.id DESC'),
										'group' => array('Patients.id'),
										'limit' => $limit)
								); 
								$condition_deleted['Patients.user_id']= $all_staff_ids;
								$condition_deleted['OR'][]['Patients.is_delete'] = 1;
								$condition_deleted['OR'][]['Patients.merge_status'] = 1;
								$condition_deleted['OR'][]['Patients.archived_date NOT'] = "";
								
							//	'conditions' => ['OR' => [['Patients.is_delete' => 1], ['Patients.archived_date NOT' => ""]]],
							}
						}else{
							$patient_staus = 1;
							$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
							$all_staff_ids = implode(",", $all_staff_ids);
							$all_staff_ids = explode(',', $all_staff_ids); 
							$this->Patients->virtualFields['patient_report_status'] = "IF(Pointdatas.id>0, 1,0)";
							if (isset($data_arrays['last_sync_time_new']) && $data_arrays['last_sync_time_new']!="" ) {
								$condition['Patients.created >'] = date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition['Patients.user_id'] = $all_staff_ids;
								$condition['Patients.is_delete'] = 0; 
								$condition['Patients.merge_status'] = 0;
								//$condition['Patients.status'] = $patient_staus;
								$condition_deleted['OR'][]['Patients.delete_date >']= date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new'])); 
								$condition_deleted['OR'][]['Patients.archived_date >']=date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition_deleted['OR'][]['Patients.merge_date >']=date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition_deleted['Patients.user_id']= $all_staff_ids;
								$update_date = date('Y-m-d h:i:s', strtotime($data_arrays['last_sync_time_new']));
								$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*', 
										),
										'joins' => array(
											array(
												'table' => 'mmd_pointdatas',
												'alias' => 'Pointdatas',
												'type' => 'LEFT',
												'foreignKey' => false,
												'conditions' => array(
													'Pointdatas.patient_id = Patients.id',
												)
											)
										),
										'conditions' => $condition,
										'order' => array('Patients.id DESC'),
										'group' => array('Patients.id'),
										'limit' => $limit)
								);
							} else {
								$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*', 
										),
										'joins' => array(
											array(
												'table' => 'mmd_pointdatas',
												'alias' => 'Pointdatas',
												'type' => 'LEFT',
												'foreignKey' => false,
												'conditions' => array(
													'Pointdatas.patient_id = Patients.id',
												)
											)
										),
										'conditions' => array('Patients.user_id' => $all_staff_ids, 'Patients.merge_status' => 0),
										'order' => array('Patients.id DESC'),
										'group' => array('Patients.id'),
										'limit' => $limit)
								);
								$condition_deleted['Patients.user_id']= $all_staff_ids;
								$condition_deleted['Patients.is_delete'] = 1;
								$condition_deleted['Patients.merge_status'] = 1;
							}
						}
					} else {
						$response_array['message'] = 'Invalid staff.';
						$response_array['result'] = 0;
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'Please send staff id.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
				$all_result_count = count($result);
				if (isset($data_arrays['page']) && ($data_arrays['page'] == 0)) {
					$end = $all_result_count;
				}
				if (!isset($data_arrays['page'])) {
					$end = $all_result_count;
				}
				if (isset($data_arrays['page'])) {
					if ($data_arrays['page'] != 0) {
						if (($all_result_count > $data_arrays['page'] * 25)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
				} else {
					$more_data = 0;
				}
				
				if (count($result)) {
					$data = array();
					$delete_data = array();
					foreach ($result as $key => $value) {
						if ($key >= $start && $key <= $end) {
							$value['Patients']['patient_id'] = $value['Patients']['id'];
							$value['Patients']['middle_name'] = ($value['Patients']['middle_name'] != null) ? ($value['Patients']['middle_name']) : '';
							$value['Patients']['phone'] = ($value['Patients']['phone'] != null) ? ($value['Patients']['phone']) : '';
							$value['Patients']['id_number'] = ($value['Patients']['id_number'] != null) ? ($value['Patients']['id_number']) : '';
							$value['Patients']['notes'] = ($value['Patients']['notes'] != null) ? ($value['Patients']['notes']) : '';
							$value['Patients']['created'] = ($value['Patients']['created'] != null) ? ($value['Patients']['created']) : '';
							$value['Patients']['unique_id'] = ($value['Patients']['unique_id'] != null) ? ($value['Patients']['unique_id']) : null;
							if (!empty($value['Patients']['p_profilepic'])) {
								$value['Patients']['p_profilepic'] = WWW_BASE . $value['Patients']['p_profilepic'];
							} else {
								$value['Patients']['p_profilepic'] = WWW_BASE . 'img/uploads/no-user.png';
							}
							//$value['Patients']['patient_report_status'] = $value['Patients']['patient_report_status'];
							$last_sync_time = ($last_sync_time != '') ? $last_sync_time : $value['Patients']['created'];
							unset($value['Patients']['id']);
							$data[] = $value['Patients'];
						}
					} 
					if($last_sync_time==''){
					    $last_sync_time=date("Y-m-d H:i:s");
					}
					$this->Patients->virtualFields['patient_report_status'] = "Patients.id";
					$this->Patients->create(); 
					$data_new = $this->Patients->find('all',array('conditions'=>$condition_deleted,'fields' => array('Patients.id','Patients.unique_id')));
					$deleted_data=array();
					foreach($data_new as $key => $value){
						//if($office_archive_status['Office']['archive_status'] == 1){
						$deleted_data[]=$value['Patients'];
						//}
					}
					if (!empty($data)) {
						$response_array = array('message' => 'Get patients information.', 'status' => 1, 'more_data' => $more_data, 'data' => $data, 'last_sync_time' => $last_sync_time, 'delete_data' =>$deleted_data);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} else {
						$response_array = array('message' => 'No record found.', 'status' => 0, 'last_sync_time' => $last_sync_time, 'delete_data' =>$deleted_data);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				} else {
					$this->Patients->virtualFields['patient_report_status'] = "Patients.id";
					$this->Patients->create(); 
					$data_new = $this->Patients->find('all',array('conditions'=>$condition_deleted,'fields' => array('Patients.id','Patients.unique_id')));
					$deleted_data=array();
					foreach($data_new as $key => $value){
						$deleted_data[]=$value['Patients'];
					} 
				 
					$response_array = array('message' => 'No Record found.', 'status' => 0, 'last_sync_time' => $last_sync_time, 'delete_data' =>$deleted_data);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
/* last add patients sync time */
		public function unity_listPatients_v2()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$data_arrays = $_POST;
				$last_sync_time = '';
				if (isset($data_arrays['page'])) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 25 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 25 - 1;
					} else {
						$limit = $data_arrays['page'] * 25 + 1;
						$start = ($data_arrays['page'] - 1) * 25;
						$end = $data_arrays['page'] * 25 - 1;
					}
				} else {
					$limit = '';
					$start = 0;
				}
				if (isset($data_arrays['staff_id'])) {
					$staff_id = $data_arrays['staff_id'];
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $staff_id), 'fields' => array('User.office_id')));
					$office_archive_status = $this->Office->find('first', array('conditions' => array('Office.id' => $office_id['User']['office_id']), 'fields' => array('Office.archive_status')));
					if (!empty($office_id)) {
						if($office_archive_status['Office']['archive_status'] == 1){
							$patient_staus = isset($data_arrays['showActiveOnly']) ? $data_arrays['showActiveOnly'] : array(1, 2);
							$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
							$all_staff_ids = implode(",", $all_staff_ids);
							$all_staff_ids = explode(',', $all_staff_ids); 
							if (isset($data_arrays['last_sync_time_new']) && $data_arrays['last_sync_time_new']!="" ) {
								$condition['Patients.created >'] = date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition['Patients.user_id'] = $all_staff_ids;
								$condition['Patients.is_delete'] = 0;
								$condition['Patients.merge_status'] = 0;
								$condition['Patients.status'] = $patient_staus;  
								$condition_deleted['OR'][]['Patients.delete_date >']= date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new'])); 
								$condition_deleted['OR'][]['Patients.archived_date >']=date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition_deleted['OR'][]['Patients.merge_date >']=date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition_deleted['Patients.user_id']= $all_staff_ids;
								$update_date = date('Y-m-d h:i:s', strtotime($data_arrays['last_sync_time_new']));
								$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*'										
										),
										'conditions' => $condition,
										'order' => array('Patients.id DESC'),
										'group' => array('Patients.id'),
										'limit' => $limit)
								);
							} else {
								$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*',
										),
										'conditions' => array('Patients.user_id' => $all_staff_ids, 'Patients.merge_status' => 0,
										'Patients.status' => $patient_staus),
										'order' => array('Patients.id DESC'),
										'group' => array('Patients.id'),
										'limit' => $limit)
								); 
								$condition_deleted['Patients.user_id']= $all_staff_ids;
								$condition_deleted['OR'][]['Patients.is_delete'] = 1;
								$condition_deleted['OR'][]['Patients.merge_status'] = 1;
								$condition_deleted['OR'][]['Patients.archived_date NOT'] = "";
							}
						}else{
							$patient_staus = 1;
							$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
							$all_staff_ids = implode(",", $all_staff_ids);
							$all_staff_ids = explode(',', $all_staff_ids); 
							$this->Patients->virtualFields['patient_report_status'] = "IF(Pointdatas.id>0, 1,0)";
							if (isset($data_arrays['last_sync_time_new']) && $data_arrays['last_sync_time_new']!="" ) {
								$condition['Patients.created >'] = date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition['Patients.user_id'] = $all_staff_ids;
								$condition['Patients.is_delete'] = 0;
								$condition['Patients.merge_status'] = 0;
								$condition_deleted['OR'][]['Patients.delete_date >']= date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new'])); 
								$condition_deleted['OR'][]['Patients.archived_date >']=date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition_deleted['OR'][]['Patients.merge_date >']=date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition_deleted['Patients.user_id']= $all_staff_ids;
								$update_date = date('Y-m-d h:i:s', strtotime($data_arrays['last_sync_time_new']));
								$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*', 
										),
										'joins' => array(
											array(
												'table' => 'mmd_pointdatas',
												'alias' => 'Pointdatas',
												'type' => 'LEFT',
												'foreignKey' => false,
												'conditions' => array(
													'Pointdatas.patient_id = Patients.id',
												)
											)
										),
										'conditions' => $condition,
										'order' => array('Patients.id DESC'),
										'group' => array('Patients.id'),
										'limit' => $limit)
								);
							} else {
								$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*', 
										),
										'joins' => array(
											array(
												'table' => 'mmd_pointdatas',
												'alias' => 'Pointdatas',
												'type' => 'LEFT',
												'foreignKey' => false,
												'conditions' => array(
													'Pointdatas.patient_id = Patients.id',
												)
											)
										),
										'conditions' => array('Patients.user_id' => $all_staff_ids, 'Patients.merge_status' => 0),
										'order' => array('Patients.id DESC'),
										'group' => array('Patients.id'),
										'limit' => $limit)
								);
								$condition_deleted['Patients.user_id']= $all_staff_ids;
								$condition_deleted['Patients.is_delete'] = 1;
								$condition_deleted['Patients.merge_status'] = 1;
							}
						}
					} else {
						$response_array['message'] = 'Invalid staff.';
						$response_array['result'] = 0;
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'Please send staff id.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
				$all_result_count = count($result);
				if (isset($data_arrays['page']) && ($data_arrays['page'] == 0)) {
					$end = $all_result_count;
				}
				if (!isset($data_arrays['page'])) {
					$end = $all_result_count;
				}
				if (isset($data_arrays['page'])) {
					if ($data_arrays['page'] != 0) {
						if (($all_result_count > $data_arrays['page'] * 25)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
				} else {
					$more_data = 0;
				}
				
				if (count($result)) {
					$data = array();
					$delete_data = array();
					foreach ($result as $key => $value) {
						if ($key >= $start && $key <= $end) {
							$value['Patients']['patient_id'] = $value['Patients']['id'];
							$value['Patients']['middle_name'] = ($value['Patients']['middle_name'] != null) ? ($value['Patients']['middle_name']) : '';
							$value['Patients']['phone'] = ($value['Patients']['phone'] != null) ? ($value['Patients']['phone']) : '';
							$value['Patients']['id_number'] = ($value['Patients']['id_number'] != null) ? ($value['Patients']['id_number']) : '';
							$value['Patients']['notes'] = ($value['Patients']['notes'] != null) ? ($value['Patients']['notes']) : '';
							$value['Patients']['created'] = ($value['Patients']['created'] != null) ? ($value['Patients']['created']) : '';
							$value['Patients']['unique_id'] = ($value['Patients']['unique_id'] != null) ? ($value['Patients']['unique_id']) : null;
							if (!empty($value['Patients']['p_profilepic'])) {
								$value['Patients']['p_profilepic'] = WWW_BASE . $value['Patients']['p_profilepic'];
							} else {
								$value['Patients']['p_profilepic'] = WWW_BASE . 'img/uploads/no-user.png';
							}
							$last_sync_time = ($last_sync_time != '') ? $last_sync_time : $value['Patients']['created'];
							//echo $last_sync_time; die;
							unset($value['Patients']['id']);
							$data[] = $value['Patients'];
						}
					} //echo $last_sync_time; die;
					/*if($last_sync_time==''){
						$date = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
						$last_sync_time=$date->format('Y-m-d H:i:s');
					    //$last_sync_time=date("Y-m-d H:i:s");
					
					    
					}*/
					if($data_arrays['last_sync_time_new'] == ''){
						$date = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
						$last_sync_time=$date->format('Y-m-d H:i:s');
					}

					
					$this->Patients->virtualFields['patient_report_status'] = "Patients.id";
					$this->Patients->create(); 
					$data_new = $this->Patients->find('all',array('conditions'=>$condition_deleted,'fields' => array('Patients.id','Patients.unique_id')));
					$deleted_data=array();
					foreach($data_new as $key => $value){
						//if($office_archive_status['Office']['archive_status'] == 1){
						$deleted_data[]=$value['Patients'];
						//}
					}
					if (!empty($data)) {
						$response_array = array('message' => 'Get patients information.', 'status' => 1, 'more_data' => $more_data, 'data' => $data, 'last_sync_time' => $last_sync_time, 'delete_data' =>$deleted_data);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} else {
						$response_array = array('message' => 'No record found.', 'status' => 0, 'last_sync_time' => $last_sync_time, 'delete_data' =>$deleted_data);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				} else {
					$this->Patients->virtualFields['patient_report_status'] = "Patients.id";
					$this->Patients->create(); 
					$data_new = $this->Patients->find('all',array('conditions'=>$condition_deleted,'fields' => array('Patients.id','Patients.unique_id')));
					$deleted_data=array();
					foreach($data_new as $key => $value){
						$deleted_data[]=$value['Patients'];
					} 
					$response_array = array('message' => 'No Record found.', 'status' => 0, 'last_sync_time' => $last_sync_time, 'delete_data' =>$deleted_data);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}

	/*Create new api for list only archive patients*/
	/* last add patients sync time */
		public function get_list_archive_patients_v1()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$data_arrays = $_POST;
				if (isset($data_arrays['staff_id'])) {
					$staff_id = $data_arrays['staff_id'];
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $staff_id), 'fields' => array('User.office_id')));
					if (!empty($office_id)) {
							$patient_staus = 0;
							$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
							$all_staff_ids = implode(",", $all_staff_ids);
							$all_staff_ids = explode(',', $all_staff_ids); 
								$condition['Patients.user_id']= $all_staff_ids;
								$condition['Patients.status'] = 0;
								$condition['Patients.is_delete'] = 0;
								$condition['Patients.merge_status'] = 0;
						$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*', 
										),
										'joins' => array(
											array(
												'table' => 'mmd_pointdatas',
												'alias' => 'Pointdatas',
												'type' => 'LEFT',
												'foreignKey' => false,
												'conditions' => array(
													'Pointdatas.patient_id = Patients.id',
												)
											)
										),
										'conditions' => $condition,
										'order' => array('Patients.id DESC'),
										'group' => array('Patients.id'))
								);
						
					} else {
						$response_array['message'] = 'Invalid staff.';
						$response_array['result'] = 0;
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'Please send staff id.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
				//pr($result); die;
				if (count($result)) {
					$data = array();
					foreach ($result as $key => $value) {
							$value['Patients']['patient_id'] = $value['Patients']['id'];
							$value['Patients']['middle_name'] = ($value['Patients']['middle_name'] != null) ? ($value['Patients']['middle_name']) : '';
							$value['Patients']['phone'] = ($value['Patients']['phone'] != null) ? ($value['Patients']['phone']) : '';
							$value['Patients']['id_number'] = ($value['Patients']['id_number'] != null) ? ($value['Patients']['id_number']) : '';
							$value['Patients']['notes'] = ($value['Patients']['notes'] != null) ? ($value['Patients']['notes']) : '';
							$value['Patients']['created'] = ($value['Patients']['created'] != null) ? ($value['Patients']['created']) : '';
							$value['Patients']['unique_id'] = ($value['Patients']['unique_id'] != null) ? ($value['Patients']['unique_id']) : null;
							if (!empty($value['Patients']['p_profilepic'])) {
								$value['Patients']['p_profilepic'] = WWW_ROOT . $value['Patients']['p_profilepic'];
							} else {
								$value['Patients']['p_profilepic'] = WWW_ROOT . 'img/uploads/no-user.png';
							}
							unset($value['Patients']['id']);
							$data[] = $value['Patients'];
					} 
					if (!empty($data)) {
						$response_array = array('message' => 'Get patients information.', 'status' => 1, 'data' => $data, 'data' => $data);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} else {
						$response_array = array('message' => 'No record found.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'No Record found.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
	/*Create new api for list only archive patients*/


	/* last add patients sync time */
		public function get_list_archive_patients_v2()
	{
		
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$data_arrays = $_POST;
				$last_sync_time = '';
				if (isset($data_arrays['staff_id'])) {
					$staff_id = $data_arrays['staff_id'];
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $staff_id), 'fields' => array('User.office_id')));
					if (!empty($office_id)) {
							$patient_staus = 0;
							$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
							$all_staff_ids = implode(",", $all_staff_ids);
							$all_staff_ids = explode(',', $all_staff_ids); 
								$condition['Patients.user_id']= $all_staff_ids;
								$condition['Patients.status'] = 0;
								$condition['Patients.merge_status'] = 0;
								if (isset($data_arrays['last_sync_time']) && $data_arrays['last_sync_time']!="" ) {
									$condition['Patients.created_date_utc >'] = date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time']));
								}
						$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*', 
										),
										'joins' => array(
											array(
												'table' => 'mmd_pointdatas',
												'alias' => 'Pointdatas',
												'type' => 'LEFT',
												'foreignKey' => false,
												'conditions' => array(
													'Pointdatas.patient_id = Patients.id',
												)
											)
										),
										'conditions' => $condition,
										'order' => array('Patients.id DESC'),
										'group' => array('Patients.id'))
								);
						
					} else {
						$response_array['message'] = 'Invalid staff.';
						$response_array['result'] = 0;
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'Please send staff id.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
				//pr($result); die;
				if (count($result)) {
					$data = array();
					foreach ($result as $key => $value) {
							$value['Patients']['patient_id'] = $value['Patients']['id'];
							$value['Patients']['middle_name'] = ($value['Patients']['middle_name'] != null) ? ($value['Patients']['middle_name']) : '';
							$value['Patients']['phone'] = ($value['Patients']['phone'] != null) ? ($value['Patients']['phone']) : '';
							$value['Patients']['id_number'] = ($value['Patients']['id_number'] != null) ? ($value['Patients']['id_number']) : '';
							$value['Patients']['notes'] = ($value['Patients']['notes'] != null) ? ($value['Patients']['notes']) : '';
							$value['Patients']['created'] = ($value['Patients']['created'] != null) ? ($value['Patients']['created']) : '';
							$value['Patients']['unique_id'] = ($value['Patients']['unique_id'] != null) ? ($value['Patients']['unique_id']) : null;
							if (!empty($value['Patients']['p_profilepic'])) {
								$value['Patients']['p_profilepic'] = WWW_ROOT . $value['Patients']['p_profilepic'];
							} else {
								$value['Patients']['p_profilepic'] = WWW_ROOT . 'img/uploads/no-user.png';
							}
							$last_sync_time = ($last_sync_time != '') ? $last_sync_time : $value['Patients']['created_date_utc'];
							unset($value['Patients']['id']);
							$data[] = $value['Patients'];
					}
					if(empty($data_arrays['last_sync_time'])){
						$last_sync_times = $last_sync_time;
					}else{
						$last_sync_times = $data_arrays['last_sync_time'];
					} 
					if (!empty($data)) {
						$response_array = array('message' => 'Get patients information.', 'status' => 1, 'data' => $data, 'data' => $data,'last_sync_time' => $last_sync_times);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} else {
						$response_array = array('message' => 'No record found.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'No Record found.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
	/*Create new api for list only archive patients*/

	/*Update status by controller create new api 24-01-2023*/
		public function Update_status_by_app_V1() 	{ 
			$this->loadModel('Patient');
			$data_arrays = $_POST; //pr($data_arrays); die;
			if($data_arrays['patient_id']) {
			$Sdata = $this->Patient->find('first', array('conditions' => array('Patient.id' => $data_arrays['patient_id'])));
			//pr($Sdata); die;
			$data['Patient']['id'] = $Sdata['Patient']['id']; 
				if($data_arrays['status'] == 0){
					date_default_timezone_set('UTC');
					$data['Patient']['archived_date'] = date('Y-m-d H:i:s');
					$data['Patient']['status'] = 0;
				}else if($data_arrays['status']== 1){
					date_default_timezone_set("UTC"); 
					$data['Patient']['archived_date'] = null;
					$data['Patient']['status'] = 1;
					$data['Patient']['created_date_utc'] = date('Y-m-d H:i:s');
				}else if($data_arrays['status']== 2){
					date_default_timezone_set("UTC"); 
					$data['Patient']['archived_date'] = null;
					$data['Patient']['status'] = 2;
					$data['Patient']['created_date_utc'] = date('Y-m-d H:i:s');
				}else{
					$data['Patient']['status'] = $data_arrays['status']; 
				}
			if ($this->Patient->save($data)) {
				$response_array = array('message' => 'Status Changed Successfully.', 'status' => 1);
				header('Content-Type: application/json');
				echo json_encode($response_array);
				die;
			} else {
				$response_array = array('message' => 'Status Not Changed.', 'status' => 0);
				header('Content-Type: application/json');
				echo json_encode($response_array);
				die;
			}
		}else{
			$response_array = array('message' => 'Please send the Patient id.', 'status' => 0);
			header('Content-Type: application/json');
			echo json_encode($response_array);
			die;
		}
	}
	/*Update status by controller create new api 24-01-2023*/

	/*GEt all patients with UTC time by Madan 22-11-2022*/
	public function unity_listPatients_v6()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$data_arrays = $_POST;
				$last_sync_time = '';
				$last_sync_time_new = '';
				if (isset($data_arrays['page'])) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 25 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 25 - 1;
					} else {
						$limit = $data_arrays['page'] * 25 + 1;
						$start = ($data_arrays['page'] - 1) * 25;
						$end = $data_arrays['page'] * 25 - 1;
					}
				} else {
					$limit = '';
					$start = 0;
				} 
				if (isset($data_arrays['staff_id'])) {
					$staff_id = $data_arrays['staff_id'];
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $staff_id), 'fields' => array('User.office_id')));
					$office_archive_status = $this->Office->find('first', array('conditions' => array('Office.id' => $office_id['User']['office_id']), 'fields' => array('Office.archive_status')));
					if (!empty($office_id)) {
						if($office_archive_status['Office']['archive_status'] == 1){
							$patient_staus = isset($data_arrays['showActiveOnly']) ? $data_arrays['showActiveOnly'] : array(1, 2);
							$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
							$all_staff_ids = implode(",", $all_staff_ids);
							$all_staff_ids = explode(',', $all_staff_ids); 
							if (isset($data_arrays['last_sync_time_new']) && $data_arrays['last_sync_time_new']!="" ) {
								$condition['Patients.created_date_utc >'] = date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition['Patients.user_id'] = $all_staff_ids;
								$condition['Patients.is_delete'] = 0;
								$condition['Patients.merge_status'] = 0;
								$condition['Patients.status'] = $patient_staus;  
								$condition_deleted['OR'][]['Patients.delete_date >']= date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new'])); 
								$condition_deleted['OR'][]['Patients.archived_date >']=date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition_deleted['OR'][]['Patients.merge_date >']=date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition_deleted['Patients.user_id']= $all_staff_ids;
								$update_date = date('Y-m-d h:i:s', strtotime($data_arrays['last_sync_time_new']));
								$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*'										
										),
										'conditions' => $condition,
										'order' => array('Patients.id DESC'),
										'group' => array('Patients.id'),
										'limit' => $limit)
								);
							} else {
								$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*',
										),
										'conditions' => array('Patients.user_id' => $all_staff_ids, 'Patients.merge_status' => 0,
										'Patients.status' => $patient_staus),
										'order' => array('Patients.id DESC'),
										'group' => array('Patients.id'),
										'limit' => $limit)
								); 
								$condition_deleted['Patients.user_id']= $all_staff_ids;
								$condition_deleted['OR'][]['Patients.is_delete'] = 1;
								$condition_deleted['OR'][]['Patients.merge_status'] = 1;
								$condition_deleted['OR'][]['Patients.archived_date NOT'] = "";
							}
						}else{
							$patient_staus = 1;
							$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
							$all_staff_ids = implode(",", $all_staff_ids);
							$all_staff_ids = explode(',', $all_staff_ids); 
							$this->Patients->virtualFields['patient_report_status'] = "IF(Pointdatas.id>0, 1,0)";
							if (isset($data_arrays['last_sync_time_new']) && $data_arrays['last_sync_time_new']!="" ) {
								$condition['Patients.created_date_utc >'] = date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition['Patients.user_id'] = $all_staff_ids;
								$condition['Patients.is_delete'] = 0;
								$condition['Patients.merge_status'] = 0;
								$condition_deleted['OR'][]['Patients.delete_date >']= date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new'])); 
								$condition_deleted['OR'][]['Patients.archived_date >']=date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition_deleted['OR'][]['Patients.merge_date >']=date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition_deleted['Patients.user_id']= $all_staff_ids;
								$update_date = date('Y-m-d h:i:s', strtotime($data_arrays['last_sync_time_new']));
								$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*', 
										),
										'joins' => array(
											array(
												'table' => 'mmd_pointdatas',
												'alias' => 'Pointdatas',
												'type' => 'LEFT',
												'foreignKey' => false,
												'conditions' => array(
													'Pointdatas.patient_id = Patients.id',
												)
											)
										),
										'conditions' => $condition,
										'order' => array('Patients.id DESC'),
										'group' => array('Patients.id'),
										'limit' => $limit)
								);
							} else {
								$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*', 
										),
										'joins' => array(
											array(
												'table' => 'mmd_pointdatas',
												'alias' => 'Pointdatas',
												'type' => 'LEFT',
												'foreignKey' => false,
												'conditions' => array(
													'Pointdatas.patient_id = Patients.id',
												)
											)
										),
										'conditions' => array('Patients.user_id' => $all_staff_ids, 'Patients.merge_status' => 0),
										'order' => array('Patients.id DESC'),
										'group' => array('Patients.id'),
										'limit' => $limit)
								);
								$condition_deleted['Patients.user_id']= $all_staff_ids;
								$condition_deleted['Patients.is_delete'] = 1;
								$condition_deleted['Patients.merge_status'] = 1;
							}
						}
					} else {
						$response_array['message'] = 'Invalid staff.';
						$response_array['result'] = 0;
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'Please send staff id.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
				$all_result_count = count($result);
				if (isset($data_arrays['page']) && ($data_arrays['page'] == 0)) {
					$end = $all_result_count;
				}
				if (!isset($data_arrays['page'])) {
					$end = $all_result_count;
				}
				if (isset($data_arrays['page'])) {
					if ($data_arrays['page'] != 0) {
						if (($all_result_count > $data_arrays['page'] * 25)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
				} else {
					$more_data = 0;
				}
				
				if (count($result)) {
					$data = array();
					$delete_data = array();
					foreach ($result as $key => $value) {
						if ($key >= $start && $key <= $end) {
							$value['Patients']['patient_id'] = $value['Patients']['id'];
							$value['Patients']['middle_name'] = ($value['Patients']['middle_name'] != null) ? ($value['Patients']['middle_name']) : '';
							$value['Patients']['phone'] = ($value['Patients']['phone'] != null) ? ($value['Patients']['phone']) : '';
							$value['Patients']['id_number'] = ($value['Patients']['id_number'] != null) ? ($value['Patients']['id_number']) : '';
							$value['Patients']['notes'] = ($value['Patients']['notes'] != null) ? ($value['Patients']['notes']) : '';
							$value['Patients']['created'] = ($value['Patients']['created'] != null) ? ($value['Patients']['created']) : '';
							$value['Patients']['unique_id'] = ($value['Patients']['unique_id'] != null) ? ($value['Patients']['unique_id']) : null;
							if (!empty($value['Patients']['p_profilepic'])) {
								$value['Patients']['p_profilepic'] = WWW_BASE . $value['Patients']['p_profilepic'];
							} else {
								$value['Patients']['p_profilepic'] = WWW_BASE . 'img/uploads/no-user.png';
							}
							$last_sync_time = ($last_sync_time != '') ? $last_sync_time : $value['Patients']['created_date_utc'];
							//echo $last_sync_time; die;
							unset($value['Patients']['id']);
							$data[] = $value['Patients'];
						}
					} //echo $last_sync_time; die;
					/*if($last_sync_time==''){
						$date = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
						$last_sync_time=$date->format('Y-m-d H:i:s');
					    //$last_sync_time=date("Y-m-d H:i:s");
					
					    
					}*/
					if($data_arrays['last_sync_time_new'] == ''){
						//$date = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
						//$last_sync_time=$date->format('Y-m-d H:i:s');
						date_default_timezone_set('UTC');
            			$UTCDate = date('Y-m-d H:i:s');
						$last_sync_time=$UTCDate;
					}

					
					$this->Patients->virtualFields['patient_report_status'] = "Patients.id";
					$this->Patients->create(); 
					$data_new = $this->Patients->find('all',array('conditions'=>$condition_deleted,'fields' => array('Patients.id','Patients.unique_id')));
					$deleted_data=array();
					foreach($data_new as $key => $value){
						//if($office_archive_status['Office']['archive_status'] == 1){
						$deleted_data[]=$value['Patients'];
						//}
					}
					if (!empty($data)) {
						$response_array = array('message' => 'Get patients information.', 'status' => 1, 'more_data' => $more_data, 'data' => $data, 'last_sync_time' => $last_sync_time, 'delete_data' =>$deleted_data);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} else {
						$response_array = array('message' => 'No record found.', 'status' => 0, 'last_sync_time' => $last_sync_time, 'delete_data' =>$deleted_data);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				} else {
					$this->Patients->virtualFields['patient_report_status'] = "Patients.id";
					$this->Patients->create(); 
					$data_new = $this->Patients->find('all',array('conditions'=>$condition_deleted,'fields' => array('Patients.id','Patients.unique_id')));
					$deleted_data=array();
					foreach($data_new as $key => $value){
						$deleted_data[]=$value['Patients'];
					} 
					$response_array = array('message' => 'No Record found.', 'status' => 0, 'last_sync_time' => $last_sync_time, 'delete_data' =>$deleted_data);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
	/*GEt all patients with UTC time by Madan 22-11-2022*/

	/*GEt all patients with UTC time by Madan 23-06-2023*/
	public function unity_listPatients_v7()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$data_arrays = $_POST;
				$last_sync_time = '';
				$last_sync_time_new = '';
				if (isset($data_arrays['page'])) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 100 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 100 - 1;
					} else {
						$limit = $data_arrays['page'] * 100 + 1;
						$start = ($data_arrays['page'] - 1) * 100;
						$end = $data_arrays['page'] * 100 - 1;
					}
				} else {
					$limit = 2000;
					$start = 0;
				} 
				if (isset($data_arrays['staff_id'])) {
					$staff_id = $data_arrays['staff_id'];
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $staff_id), 'fields' => array('User.office_id')));
					$office_archive_status = $this->Office->find('first', array('conditions' => array('Office.id' => $office_id['User']['office_id']), 'fields' => array('Office.archive_status')));
					if (!empty($office_id)) {
						if($office_archive_status['Office']['archive_status'] == 1){
							$patient_staus = isset($data_arrays['showActiveOnly']) ? $data_arrays['showActiveOnly'] : array(1, 2);
							$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
							$all_staff_ids = implode(",", $all_staff_ids);
							$all_staff_ids = explode(',', $all_staff_ids); 
							if (isset($data_arrays['last_sync_time_new']) && $data_arrays['last_sync_time_new']!="" ) {
								$condition['Patients.created_date_utc >'] = date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition['Patients.user_id'] = $all_staff_ids;
								$condition['Patients.is_delete'] = 0;
								$condition['Patients.merge_status'] = 0;
								$condition['Patients.status'] = $patient_staus;  
								$condition_deleted['OR'][]['Patients.delete_date >']= date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new'])); 
								$condition_deleted['OR'][]['Patients.archived_date >']=date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition_deleted['OR'][]['Patients.merge_date >']=date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition_deleted['Patients.user_id']= $all_staff_ids;
								$update_date = date('Y-m-d h:i:s', strtotime($data_arrays['last_sync_time_new']));
								$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*'										
										),
										'conditions' => $condition,
										'order' => array('Patients.id DESC'),
										'group' => array('Patients.id'),
										'limit' => $limit)
								);
							} else {
								$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*',
										),
										'conditions' => array('Patients.user_id' => $all_staff_ids, 'Patients.merge_status' => 0,
										'Patients.status' => $patient_staus),
										'order' => array('Patients.id DESC'),
										'group' => array('Patients.id'),
										'limit' => $limit)
								); 
								$condition_deleted['Patients.user_id']= $all_staff_ids;
								$condition_deleted['OR'][]['Patients.is_delete'] = 1;
								$condition_deleted['OR'][]['Patients.merge_status'] = 1;
								$condition_deleted['OR'][]['Patients.archived_date NOT'] = "";
							}
						}else{
							$patient_staus = 1;
							$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
							$all_staff_ids = implode(",", $all_staff_ids);
							$all_staff_ids = explode(',', $all_staff_ids); 
							$this->Patients->virtualFields['patient_report_status'] = "IF(Pointdatas.id>0, 1,0)";
							if (isset($data_arrays['last_sync_time_new']) && $data_arrays['last_sync_time_new']!="" ) {
								$condition['Patients.created_date_utc >'] = date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition['Patients.user_id'] = $all_staff_ids;
								$condition['Patients.is_delete'] = 0;
								$condition['Patients.merge_status'] = 0;
								$condition['Patients.status'] = 1;
								$condition_deleted['OR'][]['Patients.delete_date >']= date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new'])); 
								$condition_deleted['OR'][]['Patients.archived_date >']=date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition_deleted['OR'][]['Patients.merge_date >']=date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition_deleted['Patients.user_id']= $all_staff_ids;
								$update_date = date('Y-m-d h:i:s', strtotime($data_arrays['last_sync_time_new']));
								$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*', 
										),
										'joins' => array(
											array(
												'table' => 'mmd_pointdatas',
												'alias' => 'Pointdatas',
												'type' => 'LEFT',
												'foreignKey' => false,
												'conditions' => array(
													'Pointdatas.patient_id = Patients.id',
												)
											)
										),
										'conditions' => $condition,
										'order' => array('Patients.id DESC'),
										'group' => array('Patients.id'),
										'limit' => $limit)
								);
							} else {
								$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*', 
										),
										'joins' => array(
											array(
												'table' => 'mmd_pointdatas',
												'alias' => 'Pointdatas',
												'type' => 'LEFT',
												'foreignKey' => false,
												'conditions' => array(
													'Pointdatas.patient_id = Patients.id',
												)
											)
										),
										'conditions' => array('Patients.user_id' => $all_staff_ids, 'Patients.merge_status' => 0, 'Patients.status' => 1),
										'order' => array('Patients.id DESC'),
										'group' => array('Patients.id'),
										'limit' => $limit)
								);
								$condition_deleted['Patients.user_id']= $all_staff_ids;
								$condition_deleted['Patients.is_delete'] = 1;
								$condition_deleted['Patients.merge_status'] = 1;
							}
						}
					} else {
						$response_array['message'] = 'Invalid staff.';
						$response_array['result'] = 0;
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'Please send staff id.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
				$all_result_count = count($result);
				if (isset($data_arrays['page']) && ($data_arrays['page'] == 0)) {
					$end = $all_result_count;
				}
				if (!isset($data_arrays['page'])) {
					$end = $all_result_count;
				}
				if (isset($data_arrays['page'])) {
					if ($data_arrays['page'] != 0) {
						if (($all_result_count > $data_arrays['page'] * 100)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
				} else {
					$more_data = 0;
				}
				
				if (count($result)) {
					$data = array();
					$delete_data = array();
					foreach ($result as $key => $value) {
						if ($key >= $start && $key <= $end) {
							$value['Patients']['patient_id'] = $value['Patients']['id'];
							$value['Patients']['middle_name'] = ($value['Patients']['middle_name'] != null) ? ($value['Patients']['middle_name']) : '';
							$value['Patients']['phone'] = ($value['Patients']['phone'] != null) ? ($value['Patients']['phone']) : '';
							$value['Patients']['id_number'] = ($value['Patients']['id_number'] != null) ? ($value['Patients']['id_number']) : '';
							$value['Patients']['notes'] = ($value['Patients']['notes'] != null) ? ($value['Patients']['notes']) : '';
							$value['Patients']['created'] = ($value['Patients']['created'] != null) ? ($value['Patients']['created']) : '';
							$value['Patients']['unique_id'] = ($value['Patients']['unique_id'] != null) ? ($value['Patients']['unique_id']) : null;
							if (!empty($value['Patients']['p_profilepic'])) {
								$value['Patients']['p_profilepic'] = WWW_BASE . $value['Patients']['p_profilepic'];
							} else {
								$value['Patients']['p_profilepic'] = WWW_BASE . 'img/uploads/no-user.png';
							}
							$last_sync_time = ($last_sync_time != '') ? $last_sync_time : $value['Patients']['created_date_utc'];
							//echo $last_sync_time; die;
							unset($value['Patients']['id']);
							$data[] = $value['Patients'];
						}
					} //echo $last_sync_time; die;
					/*if($last_sync_time==''){
						$date = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
						$last_sync_time=$date->format('Y-m-d H:i:s');
					    //$last_sync_time=date("Y-m-d H:i:s");
					
					    
					}*/
					if($data_arrays['last_sync_time_new'] == ''){
						//$date = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
						//$last_sync_time=$date->format('Y-m-d H:i:s');
						date_default_timezone_set('UTC');
            			$UTCDate = date('Y-m-d H:i:s');
						$last_sync_time=$UTCDate;
					}

					
					$this->Patients->virtualFields['patient_report_status'] = "Patients.id";
					$this->Patients->create(); 
					$data_new = $this->Patients->find('all',array('conditions'=>$condition_deleted,'fields' => array('Patients.id','Patients.unique_id')));
					$deleted_data=array();
					foreach($data_new as $key => $value){
						//if($office_archive_status['Office']['archive_status'] == 1){
						$deleted_data[]=$value['Patients'];
						//}
					}
					$test =$this->Patients->find('all', array('conditions' => array('Patients.user_id' => $data_arrays['staff_id'],'patients.is_delete'=>0,'patients.merge_status'=>0))); 

					if (!empty($data)) {
						$response_array = array('message' => 'Get patients information.', 'status' => 1, 'more_data' => $more_data, 'data' => $data, 'last_sync_time' => $last_sync_time, 'delete_data' =>$deleted_data,'total_patient'=>count($test));
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} else {
						$response_array = array('message' => 'No record found.', 'status' => 0, 'last_sync_time' => $last_sync_time, 'delete_data' =>$deleted_data);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				} else {
					$this->Patients->virtualFields['patient_report_status'] = "Patients.id";
					$this->Patients->create(); 
					$data_new = $this->Patients->find('all',array('conditions'=>$condition_deleted,'fields' => array('Patients.id','Patients.unique_id')));
					$deleted_data=array();
					foreach($data_new as $key => $value){
						$deleted_data[]=$value['Patients'];
					} 
					$response_array = array('message' => 'No Record found.', 'status' => 0, 'last_sync_time' => $last_sync_time, 'delete_data' =>$deleted_data);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
	/*GEt all patients with UTC time by Madan 22-11-2022*/
	/******************************pointData *********************************/
	public function unitypointData_v2()
	{
		//if($this->check_key()){
		$this->layout = false;
		//if($this->validatePostRequest()){
		$this->autoRender = false;
		$response = array();
		$resultData = $saved_data = array();
		$data_array = json_decode(file_get_contents("php://input"), true);
		//pr($data_array); die;
		if (!empty($data_array['data'])) {
			$i = 0;
			foreach ($data_array['data'] as $key => $data) {
				//pr($data);die;
				$data['stmsize'] = $data['stmSize'];
				$pointData = $data;
				if (!empty($data['pdf'])) {
					$foldername = "pointData";
					$imgstring = $data['pdf'];
					$pointData['file'] = $this->base64_to_pdf($imgstring, $foldername, $key);
				} else {
					unset($data['pdf']);
				}
				//pr($pointData['file']); die;
				$pointData['threshold'] = @$data['threshold'];
				$pointData['strategy'] = @$data['strategy'];
				$pointData['test_color_fg'] = @$data['test_color_fg'];
				$pointData['test_color_bg'] = @$data['test_color_bg'];
				$pointData['mean_dev'] = @$data['mean_dev'];
				$pointData['pattern_std'] = @$data['pattern_std'];
				$pointData['mean_sen'] = @$data['mean_sen'];
				$pointData['mean_def'] = @$data['mean_def'];
				$pointData['pattern_std_hfa'] = @$data['pattern_std_hfa'];
				$pointData['loss_var'] = @$data['loss_var'];
				$pointData['mean_std'] = @$data['mean_std'];
				$pointData['psd_hfa_2'] = @$data['psd_hfa_2'];
				$pointData['psd_hfa'] = @$data['psd_hfa'];
				$pointData['vission_loss'] = @$data['vission_loss'];
				$pointData['false_p'] = @$data['false_p'];
				$pointData['false_n'] = @$data['false_n'];
				$pointData['false_f'] = @$data['false_f'];
				$pointData['ght'] = @$data['ght'];
				$pointData['version'] = @$data['version'];
				$pointData['latitude'] = @$data['latitude'];
				$pointData['longitude'] = @$data['longitude'];
				$pointData['diagnosys'] = @$data['diagnosys'];
				$pointData['test_type_id'] = (int)@$data['test_type_id'];
				if (!empty($pointData['created_date'])) {
					$pointData['created'] = date('Y-m-d H:i:s', strtotime($pointData['created_date']));
				}
				// $count_baseline = $this->Pointdata->find('count',array(
				// 	'conditions'=>array(
				// 		'test_name'=>$data['test_name'],
				// 		'eye_select'=>$data['eye_select'],
				// 		'patient_id'=>$data['patient_id'],
				// 		'Pointdata.baseline'=>'1'
				// 	)
				// ));
				// if($count_baseline<2){
				// 	$pointData['baseline'] = 1;
				// }
				$data['Pointdata']['baseline'] = (isset($data_arrays['baseline']) && !empty($data_arrays['baseline'])) ? $data_arrays['baseline'] : 0;
				//pr($count_baseline);die;
				if (isset($pointData['id'])) {
					unset($pointData['id']);
				}
				$resultData['Pointdata'] = $pointData;
				$resultData['Pointdata']['VfPointdata'] = $pointData['vfpointdata'];
				unset($resultData['Pointdata']['vfpointdata']);
				//pr($resultData); die;
				//pr($count_baseline);die;
				if (!empty($resultData['Pointdata'])) {
					error_reporting(E_ALL);
					$rs = $this->Pointdata->saveAll($resultData, array('deep' => true));
					if ($rs) {
						$saved_data[]['id'] = $this->Pointdata->id;
					}
					/* else{
								$errors = $this->Pointdata->validationErrors;
								$response['message']='Some error occured in updating report.';
								$response['result']=0;
								pr($errors[array_keys($errors)[0]][0]); die;
							} */
				}
			}
			if (!empty($saved_data)) {
				//update credits------
				$this->loadModel('User');
				$this->User->id = $data['staff_id'];
				// $credits = $this->User->field('credits');
				// $new_credit = $credits-1;
				// $this->User->updateAll(array('User.credits'=>$new_credit),array('User.id' =>$data['staff_id']));
				$response['data'] = $saved_data;
				$response['message'] = 'Success.';
				$response['result'] = 1;
			} else {
				$response['message'] = 'Some error occured in updating report.';
				$response['result'] = 0;
			}
		} else {
			$response['message'] = 'Please fill the required fields.';
			$response['result'] = 0;
		}
		echo json_encode($response);
		exit;
		//}
		//}
	}
	/******************** VA reports *************************/
	public function unitypointData_v3()
	{
		#echo 'hello';die;
		$this->loadModel('VaData');
		$this->loadModel('VaPointdata');
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$resultData = $saved_data = array();
				$data_array = json_decode(file_get_contents("php://input"), true);
				//pr($data_array);die;
				$save_id = array();
				if (!empty($data_array['data'])) {
					$i = $ik = 0;
					foreach ($data_array['data'] as $key => $data) {
						//pr($data['staff_id']);die;
						$this->request->data['VaData']['staff_id'] = $data['staff_id'];
						$this->request->data['VaData']['patient_id'] = $data['patient_id'];
						$this->request->data['VaData']['patient_name'] = $data['patient_name'];
						$this->request->data['VaData']['test_type_id'] = $data['test_type_id'];
						$this->request->data['VaData']['test_name'] = $data['test_name'];
						$this->request->data['VaData']['master_key'] = $data['master_key'];
						$this->request->data['VaData']['eye_select'] = $data['eye_select'];
						$this->request->data['VaData']['created'] = $data['created_date'];
						$this->request->data['VaData']['pdf'] = $data['pdf'];
						#die;
						if (!empty($this->request->data['VaData']['pdf'])) {
							$foldername = "pointData";
							$imgstring = $this->request->data['VaData']['pdf'];
							$this->request->data['VaData']['pdf'] = $this->base64_to_pdf($imgstring, $foldername, $key);
						} else {
							unset($this->request->data['VaData']['pdf']);
							#echo 'else part';die;
						}
						//pr($data['VAArray']);die;
						if ($this->VaData->save($this->request->data)) {
							$save_id[$ik] = $this->VaData->id;
							foreach ($data['VAArray'] as $val) {
								//pr($val);die;
								$this->VaPointdata->create();
								$this->request->data['VaPointdata']['va_id'] = $this->VaData->id;
								$this->request->data['VaPointdata']['VA1'] = $val['VA1'];
								$this->request->data['VaPointdata']['VA2'] = $val['VA2'];
								$this->request->data['VaPointdata']['VA3'] = $val['VA3'];
								$this->request->data['VaPointdata']['VA4'] = $val['VA4'];
								$this->request->data['VaPointdata']['VA5'] = $val['VA5'];
								$this->request->data['VaPointdata']['VA6'] = $val['VA6'];
								$this->request->data['VaPointdata']['VA7'] = $val['VA7'];
								$this->request->data['VaPointdata']['VA8'] = $val['VA8'];
								$this->request->data['VaPointdata']['VA9'] = $val['VA9'];
								$this->request->data['VaPointdata']['VA10'] = $val['VA10'];
								$this->request->data['VaPointdata']['VA11'] = $val['VA11'];
								$this->request->data['VaPointdata']['VA12'] = $val['VA12'];
								$this->request->data['VaPointdata']['VA13'] = $val['VA13'];
								$this->request->data['VaPointdata']['VA14'] = $val['VA14'];
								$this->request->data['VaPointdata']['VA15'] = $val['VA15'];
								$this->request->data['VaPointdata']['VA16'] = $val['VA16'];
								unset($this->request->data['VaData']);
								if ($this->VaPointdata->save($this->request->data)) {
								}
							}
						}
						#die;
						$ik++;
					}
					if (!empty($save_id)) {
						//update credits------
						$this->loadModel('User');
						$this->loadModel('Officereport');
						$get_office_id = $this->User->find('first', ['conditions' => ['User.id' => $data['staff_id']]]);
						$get_office_id = $get_office_id['Office']['id'];
						$get_per_use_cost = $this->Officereport->find('first', ['conditions' => ['Officereport.office_id' => $get_office_id, 'Officereport.office_report' => 15]]);
						//pr($get_per_use_cost);die;
						$this->User->id = $data['staff_id'];
						// $credits = $this->User->field('credits');
						// $new_credit = $credits-$get_per_use_cost['Officereport']['per_use_cost'];
						// $this->User->updateAll(array('User.credits'=>$new_credit),array('User.id' =>$data['staff_id']));
						$response['data'] = $save_id;
						$response['message'] = 'success.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'Please fill the required fields.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Please fill the required fields.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit;
			}
		}
	}
	/************ Version four for multple request ***********/
	public function unitypointData_v4()	{
		//if($this->check_key()){
		$this->layout = false;
		if($this->validatePostRequest()){
		$this->autoRender = false;
		$response = array();
		$faildRequest = array();
		$resultData = $saved_data = $faild_data = array();
		$data_array = json_decode(file_get_contents("php://input"), true);
	/*	$report_status = $this->Pointdata->find('all',array('conditions'=>array('patient_id'=>117763)));
			echo 'mmd'; pr($report_status); 
		*/
		//	pr($data_array); die('hh');
		if (!empty($data_array['data'])) {
			$i = 0;
			foreach ($data_array['data'] as $key => $data) {  
				$data['stmsize'] = $data['stmSize'];
				$pointData = $data;
				if (!empty($data['pdf'])) {
					$foldername = "pointData";
					$imgstring = $data['pdf'];
					$pointData['file'] = $this->base64_to_pdf($imgstring, $foldername, $key);
				} else {
					unset($data['pdf']);
				}
				//pr($pointData['file']); die;
				$pointData['threshold'] = @$data['threshold'];
				$pointData['source'] = @$data['source'];
				$pointData['strategy'] = @$data['strategy'];
				$pointData['test_color_fg'] = @$data['test_color_fg'];
				$pointData['test_color_bg'] = @$data['test_color_bg'];
				$pointData['mean_dev'] = @$data['mean_dev'];
				$pointData['pattern_std'] = @$data['pattern_std'];
				$pointData['mean_sen'] = @$data['mean_sen'];
				$pointData['mean_def'] = @$data['mean_def'];
				$pointData['pattern_std_hfa'] = @$data['pattern_std_hfa'];
				$pointData['loss_var'] = @$data['loss_var'];
				$pointData['mean_std'] = @$data['mean_std'];
				$pointData['psd_hfa_2'] = @$data['psd_hfa_2'];
				$pointData['psd_hfa'] = @$data['psd_hfa'];
				$pointData['vission_loss'] = @$data['vission_loss'];
				$pointData['false_p'] = @$data['false_p'];
				$pointData['false_n'] = @$data['false_n'];
				$pointData['false_f'] = @$data['false_f'];
				$pointData['ght'] = @$data['ght'];
				$pointData['version'] = @$data['version'];
				$pointData['latitude'] = @$data['latitude'];
				$pointData['longitude'] = @$data['longitude'];
				$pointData['diagnosys'] = @$data['diagnosys'];
				$pointData['test_type_id'] = (int)@$data['test_type_id'];
				if (!empty($pointData['created_date'])) {
					$pointData['created'] = date('Y-m-d H:i:s', strtotime($pointData['created_date']));
				}
				$data['Pointdata']['baseline'] = (isset($data_arrays['baseline']) && !empty($data_arrays['baseline'])) ? $data_arrays['baseline'] : 0;
				if (isset($pointData['id'])) {
					unset($pointData['id']);
				}
				$resultData['Pointdata'] = $pointData;
				$resultData['Pointdata']['VfPointdata'] = $pointData['vfpointdata'];
				unset($resultData['Pointdata']['vfpointdata']); 
				if (!empty($resultData['Pointdata'])) { 
					error_reporting(E_ALL);
					$rs = $this->Pointdata->saveAll($resultData, array('deep' => true));
					if ($rs) {
						/*$success=array();
						$success['id']=$this->Pointdata->id;
						$success['unique_id']=$resultData['Pointdata']['unique_id'];
						$saved_data[]=$success; */
						$saved_data[]['id'] = $this->Pointdata->id;
					}else{
				  	$fail=array(); 
						$errors = $this->Pointdata->validationErrors;
						$response['message']='Some error occured in updating report.';
						$result2 = $this->Pointdata->find('first', array('conditions' => array('Pointdata.unique_id' => $resultData['Pointdata']['unique_id'])));
						$response['result']=0;
						$fail['id']=$result2['Pointdata']['id']; 
						$fail['unique_id']=$resultData['Pointdata']['unique_id'];
						//pr($result2['Patient']);die; 
						$name=$name=$result2['Patient']['first_name'];
						if($result2['Patient']['middle_name']!=""){
							$name=$name.' '.$result2['Patient']['middle_name'];
						}
						if($result2['Patient']['last_name']!=""){
							$name=$name.' '.$result2['Patient']['last_name'];
						}
						$fail['patient_name']=$name; 
						$fail['message']=$errors[array_keys($errors)[0]][0];
						$faild_data[]=$fail; 
					} 
				}
			}
			if (!empty($saved_data)) {
				//update credits------
				$this->loadModel('User');
				$this->User->id = $data['staff_id'];
				// $credits = $this->User->field('credits');
				// $new_credit = $credits-1;
				// $this->User->updateAll(array('User.credits'=>$new_credit),array('User.id' =>$data['staff_id']));
				$response['data'] = $saved_data;
				$response['message'] = 'Success.';
				$response['result'] = 1;
			} else {
				$response['message'] = 'Some error occured in updating report.';
				$response['result'] = 0;
			}
		} else {
			$response['message'] = 'Please fill the required fields.';
			$response['result'] = 0;
		}
		$response['failed_data'] = $faild_data;
		echo json_encode($response);
		exit;
		}
		//}
	}

	/*For offline patients id create new api*/
		public function unitypointData_v5()	{
		//if($this->check_key()){
		$this->layout = false;
		if($this->validatePostRequest()){
		$this->autoRender = false;
		$response = array();
		$faildRequest = array();
		$resultData = $saved_data = $faild_data=$faild_data_offline = array();
		$data_array = json_decode(file_get_contents("php://input"), true);
	/*	$report_status = $this->Pointdata->find('all',array('conditions'=>array('patient_id'=>117763)));
			echo 'mmd'; pr($report_status); 
		*/
			//pr($data_array['data']); die('hh');
		if (!empty($data_array['data'])) {
			$i = 0;
			foreach ($data_array['data'] as $key => $data) {  
				$data['stmsize'] = $data['stmSize'];
				$pointData = $data;
				if (!empty($data['pdf'])) {
					$foldername = "pointData";
					$imgstring = $data['pdf'];
					$pointData['file'] = $this->base64_to_pdf($imgstring, $foldername, $key);
				} else {
					unset($data['pdf']);
				}
				$pointData['threshold'] = @$data['threshold'];
				$pointData['strategy'] = @$data['strategy'];
				$pointData['test_color_fg'] = @$data['test_color_fg'];
				$pointData['test_color_bg'] = @$data['test_color_bg'];
				$pointData['mean_dev'] = @$data['mean_dev'];
				$pointData['pattern_std'] = @$data['pattern_std'];
				$pointData['mean_sen'] = @$data['mean_sen'];
				$pointData['mean_def'] = @$data['mean_def'];
				$pointData['pattern_std_hfa'] = @$data['pattern_std_hfa'];
				$pointData['loss_var'] = @$data['loss_var'];
				$pointData['mean_std'] = @$data['mean_std'];
				$pointData['psd_hfa_2'] = @$data['psd_hfa_2'];
				$pointData['psd_hfa'] = @$data['psd_hfa'];
				$pointData['vission_loss'] = @$data['vission_loss'];
				$pointData['false_p'] = @$data['false_p'];
				$pointData['false_n'] = @$data['false_n'];
				$pointData['false_f'] = @$data['false_f'];
				$pointData['ght'] = @$data['ght'];
				$pointData['version'] = @$data['version'];
				$pointData['latitude'] = @$data['latitude'];
				$pointData['longitude'] = @$data['longitude'];
				$pointData['diagnosys'] = @$data['diagnosys'];
				$pointData['test_type_id'] = (int)@$data['test_type_id'];
				if (!empty($pointData['created_date'])) {
					$pointData['created'] = date('Y-m-d H:i:s', strtotime($pointData['created_date']));
				}
				$data['Pointdata']['baseline'] = (isset($data_arrays['baseline']) && !empty($data_arrays['baseline'])) ? $data_arrays['baseline'] : 0;
				if (isset($pointData['id'])) {
					unset($pointData['id']);
				}
				$resultData['Pointdata'] = $pointData;
				$resultData['Pointdata']['VfPointdata'] = $pointData['vfpointdata'];
				unset($resultData['Pointdata']['vfpointdata']); 
				$x = $pointData['patient_id'] ; 
				preg_match('/offline/', $x, $matches);
				if(!empty($matches)){
					$un_id = $pointData['unique_id'] ;
				}
					if (!empty($resultData['Pointdata']) && empty($matches)) {
					error_reporting(E_ALL);
					$rs = $this->Pointdata->saveAll($resultData, array('deep' => true));
					if ($rs) {
						/*$success=array();
						$success['id']=$this->Pointdata->id;
						$success['unique_id']=$resultData['Pointdata']['unique_id'];
						$saved_data[]=$success;*/
						$saved_data[]['id'] = $this->Pointdata->id;
					}else{
				  	$fail=array(); 
				  	$Offlinefail=array(); 
						$errors = $this->Pointdata->validationErrors;
						$response['message']='Some error occured in updating report.';
						$result2 = $this->Pointdata->find('first', array('conditions' => array('Pointdata.unique_id' => $resultData['Pointdata']['unique_id'])));
						$response['result']=0;
						$fail['id']=$result2['Pointdata']['id']; 
						$Offlinefail['offline_id']=$x; 
						$fail['unique_id']=$resultData['Pointdata']['unique_id'];
						$Offlinefail['Offline_unique_id']=@$un_id;
						//pr($result2['Patient']);die; 
						$name=$name=$result2['Patient']['first_name'];
						if($result2['Patient']['middle_name']!=""){
							$name=$name.' '.$result2['Patient']['middle_name'];
						}
						if($result2['Patient']['last_name']!=""){
							$name=$name.' '.$result2['Patient']['last_name'];
						}
						$fail['patient_name']=$name; 
						$fail['message']=$errors[array_keys($errors)[0]][0];
						$Offlinefail['message']= 'Patients id is not correct';
						$faild_data[]=$fail; 
						$faild_data_offline[]=$Offlinefail; 
					} 
				}
			}
			if (!empty($saved_data)) {
				//update credits------
				$this->loadModel('User');
				$this->User->id = $data['staff_id'];
				// $credits = $this->User->field('credits');
				// $new_credit = $credits-1;
				// $this->User->updateAll(array('User.credits'=>$new_credit),array('User.id' =>$data['staff_id']));
				$response['data'] = $saved_data;
				$response['message'] = 'Success.';
				$response['result'] = 1;
			} else {
				$response['message'] = 'Some error occured in updating report.';
				$response['result'] = 0;
			}
		} else {
			$response['message'] = 'Please fill the required fields.';
			$response['result'] = 0;
		}
		$response['failed_data'] = $faild_data;
		$response['faild_data_offline'] = $faild_data_offline;
		echo json_encode($response);
		exit;
		}
		//}
	}
	/*For offline patients id create new api*/

	/*multiple report upload 22-11-2022 by Madan*/
	public function unitypointData_v6()	{
		$this->layout = false;
		if($this->validatePostRequest()){
		$this->autoRender = false;
		$response = array();
		$faildRequest = array();
		$resultData = $saved_data = $faild_data = array();
		$data_array = json_decode(file_get_contents("php://input"), true);
		if (!empty($data_array['data'])) {
			$i = 0;
			foreach ($data_array['data'] as $key => $data) {
			$pattern = "/offline/";
			$ignoreOfflineId =  preg_match($pattern, $data['patient_id']);
				if($ignoreOfflineId == 0){
					$data['stmsize'] = $data['stmSize'];
					$pointData = $data;
					if (!empty($data['pdf'])) {
						$foldername = "pointData";
						$imgstring = $data['pdf'];
						$pointData['file'] = $this->base64_to_pdf($imgstring, $foldername, $key);
					} else {
						unset($data['pdf']);
					}
					$pointData['threshold'] = @$data['threshold'];
					$pointData['source'] = @$data['source'];
					$pointData['strategy'] = @$data['strategy'];
					$pointData['test_color_fg'] = @$data['test_color_fg'];
					$pointData['test_color_bg'] = @$data['test_color_bg'];
					$pointData['mean_dev'] = @$data['mean_dev'];
					$pointData['pattern_std'] = @$data['pattern_std'];
					$pointData['mean_sen'] = @$data['mean_sen'];
					$pointData['mean_def'] = @$data['mean_def'];
					$pointData['pattern_std_hfa'] = @$data['pattern_std_hfa'];
					$pointData['loss_var'] = @$data['loss_var'];
					$pointData['mean_std'] = @$data['mean_std'];
					$pointData['psd_hfa_2'] = @$data['psd_hfa_2'];
					$pointData['psd_hfa'] = @$data['psd_hfa'];
					$pointData['vission_loss'] = @$data['vission_loss'];
					$pointData['false_p'] = @$data['false_p'];
					$pointData['false_n'] = @$data['false_n'];
					$pointData['false_f'] = @$data['false_f'];
					$pointData['ght'] = @$data['ght'];
					$pointData['version'] = @$data['version'];
					$pointData['latitude'] = @$data['latitude'];
					$pointData['longitude'] = @$data['longitude'];
					$pointData['diagnosys'] = @$data['diagnosys'];
					$pointData['test_type_id'] = (int)@$data['test_type_id'];
					if (!empty($pointData['created_date'])) {
						$pointData['created'] = date('Y-m-d H:i:s', strtotime($pointData['created_date']));
					}
					$data['Pointdata']['baseline'] = (isset($data_arrays['baseline']) && !empty($data_arrays['baseline'])) ? $data_arrays['baseline'] : 0;
					$pointData['created_date_utc'] = @$data['created_date_utc'];
					if (isset($pointData['id'])) {
						unset($pointData['id']);
					}
					$resultData['Pointdata'] = $pointData;
					$resultData['Pointdata']['VfPointdata'] = $pointData['vfpointdata'];
					unset($resultData['Pointdata']['vfpointdata']); 
					if (!empty($resultData['Pointdata'])) { 
						error_reporting(E_ALL);
						$conditionget['Pointdata.unique_id'] = $data['unique_id'];
						$conditionget['Pointdata.is_delete'] = array(1,0);
						$getuniqueid  = $this->Pointdata->find('first', array('conditions' => $conditionget));  
						if(empty($getuniqueid)){
							$this->Pointdata->saveAll($resultData, array('deep' => true));
							$saved_data[]['id'] = $this->Pointdata->id;
						}else{
					  	$fail=array(); 
							$errors = $this->Pointdata->validationErrors;
							$response['message']='Some error occured in updating report.';
							$response['result']=0;
							$fail['id']=$getuniqueid['Pointdata']['id']; 
							$fail['unique_id']=$resultData['Pointdata']['unique_id'];
							$name=$name=$getuniqueid['Patient']['first_name'];
							if($getuniqueid['Patient']['middle_name']!=""){
								$name=$name.' '.$getuniqueid['Patient']['middle_name'];
							}
							if($getuniqueid['Patient']['last_name']!=""){
								$name=$name.' '.$getuniqueid['Patient']['last_name'];
							}
							$fail['patient_name']=$name; 
							$faild_data[]=$fail; 
						} 
					}
				}
			}
			if (!empty($saved_data)) {
				$this->loadModel('User');
				$this->User->id = $data['staff_id'];
				$response['data'] = $saved_data;
				$response['message'] = 'Success.';
				$response['result'] = 1;
			} else {
				$response['message'] = 'Some error occured in updating report.';
				$response['result'] = 0;
			}
		} else {
			$response['message'] = 'Please fill the required fields.';
			$response['result'] = 0;
		}
		$response['failed_data'] = $faild_data;
		echo json_encode($response);
		exit;
		}
	}

	/*For FDT report create new API by Madan 25-11-2022*/
	public function saveMultipleFDTReport_v6()	{
		//if($this->check_key()){
		$this->layout = false;
		if($this->validatePostRequest()){
		$this->autoRender = false;
		$response = array();
		$faildRequest = array();
		$resultData = $saved_data = $faild_data = array();
		$data_array = json_decode(file_get_contents("php://input"), true);
	/*	$report_status = $this->Pointdata->find('all',array('conditions'=>array('patient_id'=>117763)));
			echo 'mmd'; pr($report_status); 
		*/
		//	pr($data_array); die('hh');
		if (!empty($data_array['data'])) {
			$i = 0;
			foreach ($data_array['data'] as $key => $data) {  
				$data['stmsize'] = $data['stmSize'];
				$pointData = $data;
				if (!empty($data['pdf'])) {
					$foldername = "pointData";
					$imgstring = $data['pdf'];
					$pointData['file'] = $this->base64_to_pdf($imgstring, $foldername, $key);
				} else {
					unset($data['pdf']);
				}
				//pr($pointData['file']); die;
				$pointData['threshold'] = @$data['threshold'];
				$pointData['strategy'] = @$data['strategy'];
				$pointData['test_color_fg'] = @$data['test_color_fg'];
				$pointData['test_color_bg'] = @$data['test_color_bg'];
				$pointData['mean_dev'] = @$data['mean_dev'];
				$pointData['pattern_std'] = @$data['pattern_std'];
				$pointData['mean_sen'] = @$data['mean_sen'];
				$pointData['mean_def'] = @$data['mean_def'];
				$pointData['pattern_std_hfa'] = @$data['pattern_std_hfa'];
				$pointData['loss_var'] = @$data['loss_var'];
				$pointData['mean_std'] = @$data['mean_std'];
				$pointData['psd_hfa_2'] = @$data['psd_hfa_2'];
				$pointData['psd_hfa'] = @$data['psd_hfa'];
				$pointData['vission_loss'] = @$data['vission_loss'];
				$pointData['false_p'] = @$data['false_p'];
				$pointData['false_n'] = @$data['false_n'];
				$pointData['false_f'] = @$data['false_f'];
				$pointData['ght'] = @$data['ght'];
				$pointData['version'] = @$data['version'];
				$pointData['latitude'] = @$data['latitude'];
				$pointData['longitude'] = @$data['longitude'];
				$pointData['diagnosys'] = @$data['diagnosys'];
				$pointData['test_type_id'] = (int)@$data['test_type_id'];
				if (!empty($pointData['created_date'])) {
					$pointData['created'] = date('Y-m-d H:i:s', strtotime($pointData['created_date']));
				}
				$data['Pointdata']['baseline'] = (isset($data_arrays['baseline']) && !empty($data_arrays['baseline'])) ? $data_arrays['baseline'] : 0;
				$pointData['created_date_utc'] = @$data['created_date_utc'];
				if (isset($pointData['id'])) {
					unset($pointData['id']);
				}
				$resultData['Pointdata'] = $pointData;
				$resultData['Pointdata']['VfPointdata'] = $pointData['vfpointdata'];
				unset($resultData['Pointdata']['vfpointdata']); 
				if (!empty($resultData['Pointdata'])) { 
					error_reporting(E_ALL);
					$rs = $this->Pointdata->saveAll($resultData, array('deep' => true));
					if ($rs) {
						/*$success=array();
						$success['id']=$this->Pointdata->id;
						$success['unique_id']=$resultData['Pointdata']['unique_id'];
						$saved_data[]=$success; */
						$saved_data[]['id'] = $this->Pointdata->id;
					}else{
				  	$fail=array(); 
						$errors = $this->Pointdata->validationErrors;
						$response['message']='Some error occured in updating report.';
						$result2 = $this->Pointdata->find('first', array('conditions' => array('Pointdata.unique_id' => $resultData['Pointdata']['unique_id'])));
						$response['result']=0;
						$fail['id']=$result2['Pointdata']['id']; 
						$fail['unique_id']=$resultData['Pointdata']['unique_id'];
						//pr($result2['Patient']);die; 
						$name=$name=$result2['Patient']['first_name'];
						if($result2['Patient']['middle_name']!=""){
							$name=$name.' '.$result2['Patient']['middle_name'];
						}
						if($result2['Patient']['last_name']!=""){
							$name=$name.' '.$result2['Patient']['last_name'];
						}
						$fail['patient_name']=$name; 
						$fail['message']=$errors[array_keys($errors)[0]][0];
						$faild_data[]=$fail; 
					} 
				}
			}
			if (!empty($saved_data)) {
				//update credits------
				$this->loadModel('User');
				$this->User->id = $data['staff_id'];
				// $credits = $this->User->field('credits');
				// $new_credit = $credits-1;
				// $this->User->updateAll(array('User.credits'=>$new_credit),array('User.id' =>$data['staff_id']));
				$response['data'] = $saved_data;
				$response['message'] = 'Success.';
				$response['result'] = 1;
			} else {
				$response['message'] = 'Some error occured in updating report.';
				$response['result'] = 0;
			}
		} else {
			$response['message'] = 'Please fill the required fields.';
			$response['result'] = 0;
		}
		$response['failed_data'] = $faild_data;
		echo json_encode($response);
		exit;
		}
		//}
	}
	/*For FDT report create new API by Madan 25-11-2022*/

	/*multiple report upload 22-11-2022 by Madan*/
	/******************** CS reports *************************/
	public function unityCsPonitData()
	{
		#echo 'hello';die;
		$this->loadModel('CsData');
		$this->loadModel('CsPointdata');
		$this->loadModel('User');
		$this->loadModel('Patient');
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$resultData = $saved_data = array();
				$data_array = json_decode(file_get_contents("php://input"), true);
				//pr($data_array);die;
				$save_id = array();
				if (!empty($data_array['data'])) {
					$i = $ik = 0;
					foreach ($data_array['data'] as $key => $data) {
						$save_data = array();
						$save_data['CsData']['staff_id'] = $data['staff_id'];
						$staff_data = $this->User->find('first', array('conditions' => array('User.id' => $data['staff_id'])));
						$pateint_data = $this->Patient->find('first', array('conditions' => array('Patient.id' => $data['patient_id'])));
						$save_data['CsData']['staff_name'] = @$staff_data['User']['first_name'] . ' ' . @$staff_data['User']['middle_name'] . ' ' . @$staff_data['User']['last_name'];
						$save_data['CsData']['patient_id'] = $this->User->id = $data['patient_id'];
						$save_data['CsData']['patient_name'] = @$pateint_data['Patient']['first_name'] . ' ' . @$pateint_data['Patient']['middle_name'] . ' ' . @$pateint_data['Patient']['last_name'];
						//$this->request->data['VaData']['test_type_id']  = $data['test_type_id'];
						//$this->request->data['VaData']['test_name'] = $data['test_name'];
						//$this->request->data['VaData']['master_key'] = $data['master_key'];
						$save_data['CsData']['eye_select'] = $data['eye_select'];
						$save_data['CsData']['office_id'] = $data['office_id'];
						$save_data['CsData']['created'] = $data['test_date_time'];
						$save_data['CsData']['pdf'] = $data['pdf'];
						#die;
						if (!empty($save_data['CsData']['pdf'])) {
							$foldername = "pointData";
							$imgstring = $save_data['CsData']['pdf'];
							$save_data['CsData']['pdf'] = $this->base64_to_pdf($imgstring, $foldername, $key);
						} else {
							unset($this->request->data['CsData']['pdf']);
							#echo 'else part';die;
						}
						//pr($data['VAArray']);die;
						$this->CsData->create();
						if ($this->CsData->save($save_data)) {
							$save_id[$ik] = $this->CsData->id;
							foreach ($data['cpdData'] as $k => $val) {
								//	pr($val);die;
								$save_dataCP = array();
								$this->CsPointdata->create();
								$save_dataCP['CsPointdata'] = array_merge($val, $data['ampData'][$k]);
								$save_dataCP['CsPointdata']['cs_id'] = $this->CsData->id;
								unset($save_dataCP['CsData']);
								if ($this->CsPointdata->save($save_dataCP['CsPointdata'])) {
								}
							}
						}
						#die;
						$ik++;
					}
					//pr($this->request->data['CsPointdata']);die;
					if (!empty($save_id)) {
						//update credits------
						$this->loadModel('User');
						$this->loadModel('Officereport');
						$get_per_use_cost = $this->Officereport->find('first', ['conditions' => ['Officereport.office_id' => $save_data['CsData']['office_id'], 'Officereport.office_report' => 18]]);
						$this->User->id = $data['staff_id'];
						// $credits = $this->User->field('credits');
						// $new_credit = $credits-$get_per_use_cost['Officereport']['per_use_cost'];
						// $this->User->updateAll(array('User.credits'=>$new_credit),array('User.id' =>$data['staff_id']));
						$response['data'] = $save_id;
						$response['message'] = 'success.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'Please fill the required fields.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Please fill the required fields.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit;
			}
		}
	}
	/******************** CS reports *************************/
	public function unityRefractorData()
	{
		#echo 'hello';die;
		$this->loadModel('Refractor');
		$this->loadModel('RefractorData');
		$this->loadModel('User');
		$this->loadModel('Patient');
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$resultData = $saved_data = array();
				$data_array = json_decode(file_get_contents("php://input"), true);
				//pr($data_array);die;
				//$save_id=array();
				if (!empty($data_array['data'])) {
					$i = $ik = 0;
					foreach ($data_array['data'] as $key => $data) {
						$save_data = array();
						$save_data['Refractor']['staff_id'] = @$data['staff_id'];
						$staff_data = $this->User->find('first', array('conditions' => array('User.id' => @$data['staff_id'])));
						$pateint_data = $this->Patient->find('first', array('conditions' => array('Patient.id' => $data['patient_id'])));
						$save_data['Refractor']['staff_name'] = @$staff_data['User']['first_name'] . ' ' . @$staff_data['User']['middle_name'] . ' ' . @$staff_data['User']['last_name'];
						$save_data['Refractor']['patient_id'] = $this->User->id = $data['patient_id'];
						$save_data['Refractor']['patient_name'] = @$pateint_data['Patient']['first_name'] . ' ' . @$pateint_data['Patient']['middle_name'] . ' ' . @$pateint_data['Patient']['last_name'];
						//$this->request->data['VaData']['test_type_id']  = $data['test_type_id'];
						//$this->request->data['VaData']['test_name'] = $data['test_name'];
						//$this->request->data['VaData']['master_key'] = $data['master_key'];
						$save_data['Refractor']['eye_select'] = $data['eye_select'];
						$save_data['Refractor']['office_id'] = $data['office_id'];
						$save_data['Refractor']['created'] = $data['test_date_time'];
						$save_data['Refractor']['pdf'] = $data['pdf'];
						#die;
						if (!empty($save_data['Refractor']['pdf'])) {
							$foldername = "pointData";
							$imgstring = $save_data['Refractor']['pdf'];
							$save_data['Refractor']['pdf'] = $this->base64_to_pdf($imgstring, $foldername, $key);
						} else {
							unset($this->request->data['Refractor']['pdf']);
							#echo 'else part';die;
						}
						//pr($data['VAArray']);die;
						$this->Refractor->create();
						if ($this->Refractor->save($save_data)) {
							$save_id[$ik] = $this->Refractor->id;
							//foreach($data['refData'] as $k =>$val){
							//pr($val);die;
							$save_dataCP = array();
							$this->RefractorData->create();
							$save_dataCP['RefractorData']['value'] = serialize(array_values($data['value']));
							$save_dataCP['RefractorData']['avg'] = serialize($data['avg']);
							$save_dataCP['RefractorData']['std'] = serialize($data['std']);
							$save_dataCP['RefractorData']['pres_power'] = $data['pres_power'];
							$save_dataCP['RefractorData']['refractor_id'] = $this->Refractor->id;
							if ($this->RefractorData->save($save_dataCP['RefractorData'])) {
							}
							//}
						}
						#die;
						$ik++;
					}
					//pr($this->request->data['RefractorData']);die;
					if (!empty($save_id)) {
						//update credits------
						$this->loadModel('User');
						$this->loadModel('Officereport');
						$get_per_use_cost = $this->Officereport->find('first', ['conditions' => ['Officereport.office_id' => $save_data['Refractor']['office_id'], 'Officereport.office_report' => 22]]);
						$this->User->id = $data['staff_id'];
						// $credits = $this->User->field('credits');
						// $new_credit = $credits-$get_per_use_cost['Officereport']['per_use_cost'];
						// $this->User->updateAll(array('User.credits'=>$new_credit),array('User.id' =>$data['staff_id']));
						$response['data'] = $save_id;
						$response['message'] = 'success.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'Please fill the required fields.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Please fill the required fields.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit;
			}
		}
	}
	public function save_device_token()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (!empty($input_data['device_token']) && !empty($input_data['device_id'])) {
					$this->loadModel('UserDevice');
					$id = $this->UserDevice->field('id', array('UserDevice.device_id' => $input_data['device_id']));
					$save_data['UserDevice']['ip_address'] = @$input_data['ip_address'];
					$save_data['UserDevice']['device_token'] = $input_data['device_token'];
					$save_data['UserDevice']['device_id'] = $input_data['device_id'];
					$save_data['UserDevice']['device_type'] = @$input_data['device_type'];
					if (!empty($id)) {
						$save_data['UserDevice']['id'] = $id;
					}
					//pr($save_data); die;
					if ($this->UserDevice->save($save_data)) {
						$response['message'] = 'Success.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'failed.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Please send all parameters.';
					$response['result'] = 0;
				}
			} else {
				$response['message'] = 'Invalid request.';
				$response['result'] = 0;
			}
		} else {
			$response['message'] = 'Invalid Key.';
			$response['result'] = 0;
		}
		echo json_encode($response);
		exit;
	}
	public function save_device_token_new()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (!empty($input_data['device_token']) && !empty($input_data['device_id'])) {
					$this->loadModel('NewUserDevice');
					$id = $this->NewUserDevice->field('id', array('NewUserDevice.device_id' => $input_data['device_id']));
					$save_data['NewUserDevice']['ip_address'] = @$input_data['ip_address'];
					$save_data['NewUserDevice']['device_token'] = $input_data['device_token'];
					$save_data['NewUserDevice']['device_id'] = $input_data['device_id'];
					$save_data['NewUserDevice']['device_type'] = @$input_data['device_type'];
					$save_data['NewUserDevice']['user_id'] = @$input_data['user_id'];
					if (!empty($id)) {
						$save_data['NewUserDevice']['id'] = $id;
					}
					if ($this->NewUserDevice->save($save_data)) {
						$response['message'] = 'Success.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'failed.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Please send all parameters.';
					$response['result'] = 0;
				}
			} else {
				$response['message'] = 'Invalid request.';
				$response['result'] = 0;
			}
		} else {
			$response['message'] = 'Invalid Key.';
			$response['result'] = 0;
		}
		echo json_encode($response);
		exit;
	}
	public function sendNotification()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = $_POST;
				if (empty($input_data)) {
					$input_data = json_decode(file_get_contents('php://input'), true);
				}
				//pr($input_data);die;
				if (!empty($input_data['device_id']) && !empty($input_data['message'])) {
					$this->loadModel('UserDevice');
					$device_token = $this->UserDevice->field('device_token', array('Lower(UserDevice.device_id)' => strtolower(trim($input_data['device_id']))));
					//pr($device_token);die;
					if (!empty($device_token)) {
						$rs = $this->sendPushNotificationNew($device_token, $input_data['message'], @$input_data['type'], @$input_data['ip_address']);
						//pr($rs);die;
						$this->loadModel('TestDevice');
						$ip_address = $this->TestDevice->field('ip_address', array('Lower(TestDevice.mac_address)' => strtolower(trim($input_data['device_id']))));
						$response['message'] = 'Success.';
						$response['result'] = 1;
						$response['ip_address'] = $rs;
						$response['testDeviceIP'] = @$ip_address;
					} else {
						$response['message'] = 'failed.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Please send all parameters.';
					$response['result'] = 0;
				}
			} else {
				$response['message'] = 'Invalid request.';
				$response['result'] = 0;
			}
		} else {
			$response['message'] = 'Invalid Key.';
			$response['result'] = 0;
		}
		echo json_encode($response);
		exit;
	}
	public function sendNotification1()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = $_POST;
				if (empty($input_data)) {
					$input_data = json_decode(file_get_contents("php://input"), true);
				}
				CakeLog::write('debug', json_encode($input_data));
				//pr($input_data);die;
				if (!empty($input_data['device_id']) && !empty($input_data['message'])) {
					$this->loadModel('NewUserDevice');
					$this->loadModel('TestDevice');
					$device_token = $this->NewUserDevice->field('device_token', array('Lower(NewUserDevice.device_id)' => strtolower(trim($input_data['device_id']))));
					//$rs = $this->sendPushNotificationNew1(trim($device_token),$input_data['message'],@$input_data['type'],@$input_data['ip_address']);
					$rs = $this->sendPushNotificationNewV2(trim($device_token), $input_data['message'], @$input_data['type'], @$input_data['ip_address']);
					$test_device_id = $this->TestDevice->field('id', array('Lower(TestDevice.mac_address)' => strtolower(trim($input_data['message']))));
					if (!empty($test_device_id)) {
						$res = $this->TestDevice->updateAll(array('ip_address' => "'" . $input_data['ip_address'] . "'"), array('TestDevice.id' => $test_device_id));
					}
					if (!empty($device_token)) {
						/************* Update NEWUSERDEVICE *************/
						$res2 = $this->NewUserDevice->updateAll(array('ip_address' => "'" . $input_data['ip_address'] . "'"), array('NewUserDevice.device_token' => $device_token));
						/************* End Update NEWUSERDEVICE *************/
						//pr($rs);die;
						$response['message'] = 'Success.';
						$response['result'] = 1;
						$response['ip_address'] = $rs;
						$response['device_token'] = trim($device_token);
					} else {
						$response['message'] = 'failed.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Please send all parameters.';
					$response['result'] = 0;
				}
			} else {
				$response['message'] = 'Invalid request.';
				$response['result'] = 0;
			}
		} else {
			$response['message'] = 'Invalid Key.';
			$response['result'] = 0;
		}
		echo json_encode($response);
		exit;
	}
	public function updateIpAddress()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				}
				//CakeLog::write('debug', json_encode($input_data));
				//pr($input_data);die;
				if (!empty($input_data['ip_address']) && !empty($input_data['mac_address'])) {
					$this->loadModel('TestDevice');
					$test_device_id = $this->TestDevice->field('id', array('Lower(TestDevice.mac_address)' => strtolower(trim($input_data['mac_address']))));
					if (!empty($test_device_id)) {
						$res = $this->TestDevice->updateAll(array('ip_address' => "'" . $input_data['ip_address'] . "'"), array('TestDevice.id' => $test_device_id));
					}
					if (!empty($res)) {
						$response['message'] = 'Success.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'failed.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Please send all parameters.';
					$response['result'] = 0;
				}
			} else {
				$response['message'] = 'Invalid request.';
				$response['result'] = 0;
			}
		} else {
			$response['message'] = 'Invalid Key.';
			$response['result'] = 0;
		}
		echo json_encode($response);
		exit;
	}
	/****************************pointData *********************************/
	public function get_credit_details()
	{
		$this->layout = false;
		$input_data = json_decode(file_get_contents("php://input"), true);
		if (empty($input_data)) {
			$input_data = $_POST;
		}
		if (!empty($input_data['staff_id'])) {
			$this->loadModel('User');
			$this->loadModel('Office');
			$this->loadModel('Officereport');
			$this->User->id = $input_data['staff_id'];
			$user_id = $this->User->field('created_by');
			$this->User->id = $input_data['staff_id'];
			$staff_credit = $this->User->field('credits');
			$this->User->id = $user_id;
			$office_id = $this->User->field('office_id');
			//pr($office_id);die;
			$this->Office->id = $office_id;
			$per_use_cost = $this->Office->field('per_use_cost');
			$office_id = $this->User->find('first', ['conditions' => ['User.id' => $input_data['staff_id']]]);
			$office_reports = $this->Officereport->find('all', ['conditions' => ['Officereport.office_id' => $office_id['Office']['id']]]);
			if (!empty($office_reports)) {
				foreach ($office_reports as $key => $office_value) {
					$office_data[$key]['id'] = $office_value['Officereport']['office_report'];
					$office_data[$key]['office_report'] = $this->getTestName($office_value['Officereport']['office_report']);
					$office_data[$key]['per_use_cost'] = $office_value['Officereport']['per_use_cost'];
				}
			}
			//pr($office_data);die;
			#$response['per_use_cost'] = ($per_use_cost > 0)? $per_use_cost :0;
			$response['staff_credit'] = ($staff_credit > 0) ? $staff_credit : 0;
			$response['data'] = ($office_data > 0) ? $office_data : 0;
			$response['message'] = 'success.';
			$response['result'] = 1;
		} else {
			$response['message'] = 'Please send all parameters.';
			$response['result'] = 0;
		}
		echo json_encode($response);
		exit;
	}
	private function getTestName($id)
	{
		$this->loadModel('Test');
		$office_name = $this->Test->find('first', ['conditions' => ['Test.id' => $id]]);
		//pr($office_name);die;
		return $office_name['Test']['name'];
	}
	public function upadate_server_key()
	{
		if ($this->check_key()) {
			$this->layout = false;
			$save_data = array();
			if ($this->validatePostRequest()) {
				$input_data = $_POST;
				if (empty($input_data)) {
					$input_data = json_decode(file_get_contents('php://input'), true);
				}
				//pr($input_data);die;
				if (!empty($input_data['NOTIFICATION_SERVER_KEY'])) {
					$save_data['AppConstant']['NOTIFICATION_SERVER_KEY'] = $input_data['NOTIFICATION_SERVER_KEY'];
				}
				if (!empty($input_data['NOTIFICATION_SERVER_KEY_NEW'])) {
					$save_data['AppConstant']['NOTIFICATION_SERVER_KEY_NEW'] = $input_data['NOTIFICATION_SERVER_KEY_NEW'];
				}
				if (!empty($save_data)) {
					$this->loadModel('AppConstant');
					$save_data['AppConstant']['id'] = 1;
					if ($this->AppConstant->save($save_data)) {
						$response['message'] = 'Success.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'Failed.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Send All Parameters.';
					$response['result'] = 0;
				}
			} else {
				$response['message'] = 'Invalid request.';
				$response['result'] = 0;
			}
		} else {
			$response['message'] = 'Invalid Key.';
			$response['result'] = 0;
		}
		echo json_encode($response);
		exit;
	}
	public function dataUpdated()
	{
		$response = array();
		$this->loadModel('AppConstant');
		$app_constant = $this->AppConstant->find('first');
		//pr($app_constant[0]['AppConstant']['is_update']);die;
		if (!empty($app_constant['AppConstant']['is_update'])) {
			$response['message'] = 'Changed.';
			$response['result'] = 1;
			$response['is_updated'] = 1;
		} else {
			$response['message'] = 'Not Changed.';
			$response['result'] = 0;
			$response['is_updated'] = 0;
		}
		echo json_encode($response);
		exit;
	}
	public function updateMasterData()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				}
				//pr($input_data);die;
				$this->loadModel('Ages');
				if (!empty($input_data)) {
					#$this->Ages->deleteAll(array('1 = 1'));
					$this->Ages->query('TRUNCATE TABLE mmd_ages;');
					foreach ($input_data as $data) {
						//pr($data);die;
						$res = $this->Ages->saveAll($data);
						if (!empty($res)) {
							$response['message'] = 'Success.';
							$response['result'] = 1;
							//die;
						} else {
							$response['message'] = 'failed.';
							$response['result'] = 0;
						}
					}
				} else {
					$response['message'] = 'Please send all parameters.';
					$response['result'] = 0;
				}
			} else {
				$response['message'] = 'Invalid request.';
				$response['result'] = 0;
			}
		} else {
			$response['message'] = 'Invalid Key.';
			$response['result'] = 0;
		}
		echo json_encode($response);
		exit;
	}
	public function getMasterData()
	{
		if ($this->check_key()) {
			$this->layout = false;
			$data = NULL;
			if ($this->validatePostRequest()) {
				$this->loadModel('Ages');
				$age_list2 = $this->Ages->find('all');
				$age_list = array_map('current', $age_list2);
				//pr($age_list1);die;
				if (!empty($age_list)) {
					$response['data'] = $age_list;
					$response['message'] = 'Success.';
					$response['result'] = 1;
					//die;
				} else {
					$response['message'] = 'failed.';
					$response['result'] = 0;
				}
			} else {
				$response['message'] = 'Invalid request.';
				$response['result'] = 0;
			}
		} else {
			$response['message'] = 'Invalid Key.';
			$response['result'] = 0;
		}
		echo json_encode($response);
		exit;
	}
	/*This API for return data from master data when input date is grater than date age group(optional) will be matched */
	public function get_master_data_by_address()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$input_data = $_POST;
				if (empty($input_data)) {
					$input_data = json_decode(file_get_contents('php://input'), true);
				}
				$condition = ['Masterdata.created > ' => $input_data['date']];
				if (!empty($input_data['age_group'])) {
					$condition[] = ['Masterdata.age_group' => $input_data['age_group']];
				}
				$data = $this->Masterdata->find('all', array('conditions' => $condition, 'recursive' => -1));
				if (!empty($data)) {
					//$response['data'] = $data;
					$response['message'] = 'Success.';
					$response['result'] = 1;
				} else {
					//$response['data'] = NULL;
					$response['message'] = 'No record found.';
					$response['result'] = 0;
				}
			}
		}
		echo json_encode($response);
		exit;
	}
	//This api for mark OS/OD baseline and mark will be maximum 2
	public function AddbaselineInReports()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$input_data = $_POST;
				if (empty($input_data)) {
					$input_data = json_decode(file_get_contents('php://input'), true);
				}
				CakeLog::write('info', 'AddbaselineInReports');
				CakeLog::write('info', json_encode($input_data));
				if ((isset($input_data['test_name']) && !empty($input_data['test_name'])) && (isset($input_data['eye_select'])) && (isset($input_data['patient_id'])) && ($input_data['patient_id'] != '') && (isset($input_data['id'])) && (isset($input_data['mark_baseline']))) {
					$id = (int)$input_data['id'];
					if ($id == 0) {
						$response['message'] = 'Please send all parameter.';
						$response['result'] = 0;
						echo json_encode($response);
						exit;
					}
					$this->loadModel('PointData');
					$this->PointData->updateAll(['PointData.baseline' => $input_data['mark_baseline']], ['PointData.id' => $input_data['id']]);
					$response['message'] = 'Record updated successfully.';
					$response['result'] = 1;
					$response['baseline'] = $input_data['mark_baseline'];
					/*$count_baseline = $this->Pointdata->find('count',array(
						'conditions'=>array(
							'test_name'=>$input_data['test_name'],
							'eye_select'=>$input_data['eye_select'],
							'patient_id'=>$input_data['patient_id'],
							'Pointdata.baseline'=>'1'
						)
					));
					if($input_data['mark_baseline']==0){
						$pointData['baseline'] = 1;
						$this->loadModel('PointData');
						$this->PointData->updateAll(['PointData.baseline'=>0],['PointData.id'=>$input_data['id']]);
						$response['message']='Record updated successfully.';
						$response['result']=1;
						$response['baseline']=0;
					}else{
						if($count_baseline<2){
							$pointData['baseline'] = 1;
							$this->loadModel('PointData');
							$this->PointData->updateAll(['PointData.baseline'=>1],['PointData.id'=>$input_data['id']]);
							$response['message']='Record updated successfully.';
							$response['result']=1;
							$response['baseline']=1;
						}else{
							$response['message']='You can select maximum 2 items. Please unselect one';
							$response['result']=0;
						}
					}*/
					//pr($count_baseline);die;
				} else {
					$response['message'] = 'Please send all parameter.';
					$response['result'] = 0;
				}
			}
		}
		echo json_encode($response);
		exit;
	}
	//This api for download baseline
	public function downloadBaseline()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$input_data = $_POST;
				if (empty($input_data)) {
					$input_data = json_decode(file_get_contents('php://input'), true);
				}
				if ((isset($input_data['test_name']) && !empty($input_data['test_name'])) && (isset($input_data['eye_select'])) && (isset($input_data['patient_id']))) {
					$get_baseline = $this->Pointdata->find('all', array(
						'conditions' => array(
							'test_name' => $input_data['test_name'],
							'eye_select' => $input_data['eye_select'],
							'patient_id' => $input_data['patient_id'],
							'Pointdata.baseline' => '1'
						),
						'fields' => array(
							'Pointdata.id', 'Pointdata.created', 'Pointdata.file', 'Pointdata.color', 'Pointdata.backgroundcolor', 'Pointdata.stmsize', 'Pointdata.patient_id', 'Pointdata.eye_select', 'Pointdata.baseline', 'Pointdata.numpoints', 'Pointdata.test_type_id', 'Pointdata.master_key', 'Test.name', 'Pointdata.test_name', 'Pointdata.threshold', 'Pointdata.strategy', 'Pointdata.test_color_fg', 'Pointdata.test_color_bg'
						)
					));
					//pr($get_baseline);die;
					if ($get_baseline) {
						$i = 0;
						foreach ($get_baseline as $key => $result) {
							//pr($result);die;
							$data[$i] = $result['Pointdata'];
							$data[$i]['test_id'] = $result['Pointdata']['id'];
							unset($data[$i]['id']);
							$data[$i]['created_date'] = ($result['Pointdata']['created'] != null) ? ($result['Pointdata']['created']) : '';
							if (!empty($result['Pointdata']['file'])) {
								$data[$i]['pdf'] = WWW_BASE . 'pointData/' . $result['Pointdata']['file'];
							}
							$data[$i]['color'] = ($result['Pointdata']['color'] != null) ? ($result['Pointdata']['color']) : '';
							$data[$i]['patient_id'] = isset($result['Pointdata']['patient_id']) ? ($result['Pointdata']['patient_id']) : '';
							$data[$i]['test_name'] = ($result['Pointdata']['test_name'] != null) ? ($result['Pointdata']['test_name']) : '';
							$data[$i]['backgroundcolor'] = ($result['Pointdata']['backgroundcolor'] != null) ? ($result['Pointdata']['backgroundcolor']) : '';
							$data[$i]['threshold'] = @$result['Pointdata']['threshold'];
							$data[$i]['strategy'] = @$result['Pointdata']['strategy'];
							$data[$i]['test_color_fg'] = @$result['Pointdata']['test_color_fg'];
							$data[$i]['test_color_bg'] = @$result['Pointdata']['test_color_bg'];
							$data[$i]['stmsize'] = ($result['Pointdata']['stmsize'] != null) ? ($result['Pointdata']['stmsize']) : '';
							$data[$i]['master_key'] = ($result['Pointdata']['master_key'] != null) ? ($result['Pointdata']['master_key']) : '';
							$data[$i]['test_type_id'] = ($result['Pointdata']['test_type_id'] != null) ? ($result['Pointdata']['test_type_id']) : '';
							$data[$i]['numpoints'] = ($result['Pointdata']['numpoints'] != null) ? ($result['Pointdata']['numpoints']) : '';
							$data[$i]['eye_select'] = ($result['Pointdata']['eye_select'] != null) ? ($result['Pointdata']['eye_select']) : '';
							if (isset($data[$i]['file'])) unset($data[$i]['file']);
							if (isset($data[$i]['created'])) unset($data[$i]['created']);
							$i++;
						}
						//pr($data);die;
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['result'] = 1;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'Record not found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Please send all parameter.';
					$response['result'] = 0;
				}
			}
		}
		echo json_encode($response);
		exit;
	}
	public function saveDevicePreference($id = null)
	{
		//pr($_POST); die;
		header("Content-Type: application/json; charset=UTF-8");
		$response['message'] = 'Invalid Request.';
		$response['result'] = 0;
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$input_data = $_POST;
				if (empty($input_data)) {
					$input_data = json_decode(file_get_contents('php://input'), true);
				}
				//pr($input_data); die;
				if ($this->DevicePreference->save($input_data)) {
					$response['message'] = 'Data Saved successfully.';
					$response['result'] = 1;
				} else {
					$response['message'] = $this->getFirstError($this->DevicePreference->validationErrors);
					$response['result'] = 0;
				}
			}
		}
		echo json_encode($response);
		exit;
	}
	public function viewDevicePreference($id = null)
	{
		header("Content-Type: application/json; charset=UTF-8");
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$input_data = $_POST;
				if (empty($input_data)) {
					$input_data = json_decode(file_get_contents('php://input'), true);
				}
				if (isset($input_data['id']) && !empty($input_data['id'])) {
					$DevicePreferenceData = $this->DevicePreference->find('first', array('conditions' => array('DevicePreference.id' => $input_data['id'])));
					if (!empty($DevicePreferenceData)) {
						$response['data'] = $DevicePreferenceData;
						$response['message'] = 'Data retrieved successfully.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'Error in retrieving recored.';
						$response['result'] = 0;
					}
				}
			}
		}
		echo json_encode($response, true);
		exit;
	}
	//Device Preference by office id
	public function officeDevicePreference($office_id = null)
	{
		header("Content-Type: application/json; charset=UTF-8");
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$input_data = $_POST;
				if (empty($input_data)) {
					$input_data = json_decode(file_get_contents('php://input'), true);
				}
				if (isset($input_data['office_id']) && !empty($input_data['office_id'])) {
					//pr($input_data); die;
					$allMac = $this->TestDevice->find('list', array('fields' => array('TestDevice.id', 'TestDevice.mac_address'), array('conditions' => array('TestDevice.office_id' => $input_data['office_id'], "NOT" => array('TestDevice.mac_address' => null)))));
					$DevicePreferenceData = $this->DevicePreference->find('all', array('conditions' => array('DevicePreference.id' => $allMac)));
					if (!empty($DevicePreferenceData)) {
						$response['data'] = $DevicePreferenceData;
						$response['message'] = 'Data retrieved successfully.';
						$response['result'] = 1;
					} else {
						$response['message'] = 'Error in retrieving recored.';
						$response['result'] = 0;
					}
				}
			}
		}
		echo json_encode($response, true);
		exit;
	}
	/*
		API Name: https://www.portal.micromedinc.com/apisnew/saveDiagnosis
		Request Parameter: office_id, array[name]
		Date: 12 March, 2018
	*/
	public function saveDiagnosis($id = null)
	{
		header("Content-Type: application/json; charset=UTF-8");
		$response['message'] = 'Invalid Request.';
		$response['result'] = 0;
		$checkUniqueName = '';
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$input_data = $_POST;
				if (empty($input_data['data'])) {
					$input_data = json_decode(file_get_contents('php://input'), true);
					$office_id = $input_data['office_id'];
					$availableRecords = $this->Diagnosis->find('list', array(
						'conditions' => ['OR' => [['Diagnosis.office_id' => 0], ['Diagnosis.office_id' => $office_id]]],
						'fields' => array('Diagnosis.id', 'Diagnosis.name'),
						'recursive' => 0
					));
					//pr($availableRecords); die;
					foreach ($input_data['data'] as $key => $value) {
						if (!in_array($value['name'], $availableRecords)) {
							$records[$key]['name'] = $value['name'];
							$records[$key]['office_id'] = $office_id;
						}
					}
					//pr($records); die;
					if (!empty($records)) {
						if ($this->Diagnosis->saveMany($records, array('deep' => true))) {
							$response['message'] = 'Data Saved successfully.';
							$response['result'] = 1;
						} else {
							$response['message'] = $this->getFirstError($this->Diagnosis->validationErrors);
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'Data Saved successfully.';
						$response['result'] = 1;
					}
				} else {
					$response['message'] = "Empty Array found";
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit;
			}
		}
	}
	/*
		API Name: https://www.portal.micromedinc.com/apisnew/getAllDiagnosis
		Request Parameter: office_id
		Date: 12 March, 2018
	*/
	public function getAllDiagnosis()
	{
		header("Content-Type: application/json; charset=UTF-8");
		$response['message'] = 'Invalid Request.';
		$response['result'] = 0;
		$checkUniqueName = '';
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$input_data = $_POST;
				if (empty($input_data)) {
					$input_data = json_decode(file_get_contents('php://input'), true);
					$office_id = $input_data['office_id'];
					//echo $office_id; die;
					$allDiagnosis = $this->Diagnosis->find('list',
						array(
							'conditions' => ['OR' => [['Diagnosis.office_id' => 0], ['Diagnosis.office_id' => $office_id]]],
							'fields' => array('id', 'name'),
							'order' => array('Diagnosis.id ASC'),
							'recursive' => 0
						)
					);
					$data = [];
					if (!empty($allDiagnosis)) {
						foreach ($allDiagnosis as $key => $value) {
							$data[]['name'] = $value;
						}
						$response['data'] = $data;
						$response['result'] = 1;
					} else {
						$response['message'] = 'Check your input and try again';
						$response['result'] = 0;
					}
				}
			}
		}
		//pr($response); die;
		echo json_encode($response);
		exit;
	}
	/*
		API Name: https://www.portal.micromedinc.com/apisnew/saveDiagnosis
		Date: 12 March, 2018
	*/
	public function getCmsData()
	{
		$termsAndConditions = $this->Cms->find('first', array('conditions' => array('Cms.page_name LIKE' => '%terms-conditions%'), 'fields' => array('Cms.page_name', 'Cms.title', 'Cms.content')));
		if (!empty($termsAndConditions)) {
			$response['data'] = $termsAndConditions['Cms'];
			$response['result'] = 1;
		} else {
			$response['message'] = 'Check your input and try again';
			$response['result'] = 0;
		}
		echo json_encode($response);
		exit;
		//pr($termsAndConditions); die;
	}
	/*
		API Name:  https://www.portal.micromedinc.com/apisnew/save_da_report_V1
		Request Parameter: office_id
		Date: 28 March, 2019
	*/
	public function save_da_report_V1()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$input_data = $_POST;
				$input_data = $_POST;
				if (empty($input_data)) {
					$input_data = json_decode(file_get_contents('php://input'), true);
				}
				//pr($input_data); die;
				if (isset($input_data['data']) && !empty($input_data['data'])) {
					//echo "<pre>";
					//print_r($input_data['data']);
					foreach ($input_data['data'] as $key => $value) {
						$data = [];
						if (isset($value['id']) && !empty(@$value['id'])) {
							$data['DarkAdaption']['id'] = @$value['id'];
							$darkAdaption = $this->DarkAdaption->find('first', array(
								'conditions' => array('DarkAdaption.id' => $value['id'])
							));
							$data['DarkAdaption']['pdf'] = $darkAdaption['DarkAdaption']['pdf'];
							//echo $data['DarkAdaption']['pdf'];
							//$responseData['data'][$key]['pdf']=WWW_BASE.'uploads/darkadaption/'.$value['DarkAdaption']['pdf'];
						}
						if (isset($value['pdf']) && (!empty($value['pdf']))) {
							$pid = (int)@$value['patient_id'];
							$folder_name = "uploads/darkadaption";
							$data['DarkAdaption']['pdf'] = $this->base64_to_pdf($value['pdf'], $folder_name, $pid);
							if (!empty($data['DarkAdaption']['pdf'])) {
								$responseData['data'][$key]['pdf'] = WWW_BASE . 'uploads/darkadaption/' . $data['DarkAdaption']['pdf'];
								if (!empty(@$value['id']) && !empty($darkAdaption['DarkAdaption']['pdf'])) {
									unlink(WWW_ROOT . 'uploads/darkadaption/' . $darkAdaption['DarkAdaption']['pdf']);
								}
							}
							//pr($darkAdaption); die;
						}
						$data['DarkAdaption']['patient_name'] = @$value['patient_name'];
						$data['DarkAdaption']['patient_id'] = (int)@$value['patient_id'];
						$data['DarkAdaption']['staff_name'] = @$value['staff_name'];
						$data['DarkAdaption']['unique_id'] = @$value['unique_id'];
						$data['DarkAdaption']['staff_id'] = @$value['staff_id'];
						$data['DarkAdaption']['office_id'] = @$value['office_id'];
						$data['DarkAdaption']['test_date_time'] = date('Y-m-d H:i:s', strtotime(@$value['test_date_time']));
						$data['DarkAdaption']['eye_select'] = @$value['eye_select'];
						//echo "<pre>";
						//print_r($data); die;
						$this->DarkAdaption->create();
						$result = $this->DarkAdaption->save($data);
						$lastId = $this->DarkAdaption->id;
						$last_sync_time = $result['DarkAdaption']['created'];
						$pointData = [];
						$responseData['data'][$key]['id'] = $lastId;
						if (isset($value['daPointdata']) && !empty($value['daPointdata'])) {
							foreach ($value['daPointdata'] as $key1 => $value1) {
								// Delete with array conditions similar to find()
								$this->DaPointData->deleteAll(array('DaPointData.dark_adaption_id' => $lastId), false);
								$pointData[$key1]['DaPointData']['dark_adaption_id'] = $lastId;
								$pointData[$key1]['DaPointData']['timeMin'] = $value1['timeMin'];
								$pointData[$key1]['DaPointData']['Decibles'] = $value1['Decibles'];
								$pointData[$key1]['DaPointData']['x'] = $value1['x'];
								$pointData[$key1]['DaPointData']['y'] = $value1['y'];
								$pointData[$key1]['DaPointData']['color'] = @$value1['color'];
								$pointData[$key1]['DaPointData']['index_name'] = @$value1['index_name'];
								$pointData[$key1]['DaPointData']['Eye'] = @$value1['Eye'];
							}
							$this->DaPointData->saveMany($pointData, array('deep' => true));
						}
					}
					$response['message'] = 'Dark Adaption saved successfully.';
					$response['result'] = 1;
					$response['last_sync_time'] = $last_sync_time;
					$response['data'] = $responseData['data'];
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*
      API Name: http://www.vibesync.com/apisnew/save_da_report_V2
      Request Parameter: office_id
      Date: 25 March, 2019
     */
	public function save_da_report_V2()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$input_data = $_POST;
				$input_data = $_POST;
				if (empty($input_data)) {
					$input_data = json_decode(file_get_contents('php://input'), true);
				}
				//pr($input_data); die;
				if (isset($input_data['data']) && !empty($input_data['data'])) {
					// $last_sync_time = '';
					// $all = '';
					foreach ($input_data['data'] as $key => $value) {
						$data = [];
						if (isset($value['id']) && !empty(@$value['id'])) {
							$data['DarkAdaption']['id'] = @$value['id'];
							$darkAdaption = $this->DarkAdaption->find('first', array(
								'conditions' => array('DarkAdaption.id' => $value['id'])
							));
							//pr($darkAdaption); die;
							$data['DarkAdaption']['pdf'] = $darkAdaption['DarkAdaption']['pdf'];
							//$responseData['data'][$key]['pdf']=WWW_BASE.'uploads/darkadaption/'.$value['pdf'];
						}
						if (isset($value['pdf']) && (!empty($value['pdf']))) {
							$pid = (int)@$value['patient_id'];
							$folder_name = "uploads/darkadaption";
							$data['DarkAdaption']['pdf'] = $this->base64_to_pdf($value['pdf'], $folder_name, $pid);
							if (!empty($data['DarkAdaption']['pdf'])) {
								$responseData['data'][$key]['pdf'] = WWW_BASE . 'uploads/darkadaption/' . $data['DarkAdaption']['pdf'];
								if (!empty(@$value['id']) && !empty($darkAdaption['DarkAdaption']['pdf'])) {
									unlink(WWW_ROOT . 'uploads/darkadaption/' . $darkAdaption['DarkAdaption']['pdf']);
								}
							}
							//pr($darkAdaption); die;
						}
						$data['DarkAdaption']['patient_name'] = @$value['patient_name'];
						$data['DarkAdaption']['patient_id'] = (int)@$value['patient_id'];
						$data['DarkAdaption']['staff_name'] = @$value['staff_name'];
						$data['DarkAdaption']['staff_id'] = @$value['staff_id'];
						$data['DarkAdaption']['office_id'] = @$value['office_id'];
						$data['DarkAdaption']['test_date_time'] = date('Y-m-d H:i:s', strtotime(@$value['test_date_time']));
						$data['DarkAdaption']['eye_select'] = @$value['eye_select'];
						$this->DarkAdaption->create();
						$result = $this->DarkAdaption->save($data);
						$lastId = $this->DarkAdaption->id;
						//$last ='';//  $this->DarkAdaption->findById($result->id);
						$last_sync_time = $result['DarkAdaption']['created'];
						//$all = array($result->id, $lastId); //$this->DarkAdaption;
						$pointData = [];
						$responseData['data'][$key]['id'] = $lastId;
						if (isset($value['daPointdata']) && !empty($value['daPointdata'])) {
							foreach ($value['daPointdata'] as $key1 => $value1) {
								// Delete with array conditions similar to find()
								$this->DaPointData->deleteAll(array('DaPointData.dark_adaption_id' => $lastId), false);
								$pointData[$key1]['DaPointData']['dark_adaption_id'] = $lastId;
								$pointData[$key1]['DaPointData']['timeMin'] = $value1['timeMin'];
								$pointData[$key1]['DaPointData']['Decibles'] = $value1['Decibles'];
								$pointData[$key1]['DaPointData']['x'] = $value1['x'];
								$pointData[$key1]['DaPointData']['y'] = $value1['y'];
								$pointData[$key1]['DaPointData']['color'] = @$value1['color'];
								$pointData[$key1]['DaPointData']['index_name'] = @$value1['index_name'];
								$pointData[$key1]['DaPointData']['Eye'] = @$value1['Eye'];
							}
							$this->DaPointData->saveMany($pointData, array('deep' => true));
						}
					}
					$response['message'] = 'Dark Adaption saved successfully.';
					$response['result'] = 1;
					$response['last_sync_time'] = $last_sync_time;
					CakeLog::write('info', "Test Device Message file upload : VF|VF_FILE_UPLOADED|" . $lastId);
					$data_device_message['DeviceMessage']['office_id'] = $data['office_id'];
					$data_device_message['DeviceMessage']['device_id'] = $data['device_id'];
					$data_device_message['DeviceMessage']['message'] = 'VF|VF_FILE_UPLOADED|' . $lastId;
					$data_device_message['DeviceMessage']['craeted_at'] = date("Y-m-d H:i:s");
					$data_device_message['DeviceMessage']['updated_at'] = date("Y-m-d H:i:s");
					$this->DeviceMessage->save($data_device_message);
					//$response['all'] = $all;
					$response['data'] = $responseData['data'];
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*
	The api required unique_id
		API Name:  https://www.portal.micromedinc.com/apisnew/save_da_report_V3
		Request Parameter: office_id
		Date: 28 March, 2019
	*/
	public function save_da_report_V3()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$input_data = $_POST;
				$last_sync_time = '';
				$responseData = array();
				$responseData['data'] = array();
				$input_data = $_POST;
				if (empty($input_data)) {
					$input_data = json_decode(file_get_contents('php://input'), true);
				}
				//pr($input_data); die;
				if (isset($input_data['data']) && !empty($input_data['data'])) {
					//echo "<pre>";
					//print_r($input_data['data']);
					foreach ($input_data['data'] as $key => $value) {
						$data = [];
						$uploadedids = [];
						$data['DarkAdaptionnew']['unique_id'] = @$value['unique_id'];
						$this->DarkAdaptionnew->set($data);
						if ($this->DarkAdaptionnew->validates()) {
							if (isset($value['id']) && !empty(@$value['id'])) {
								$data['DarkAdaptionnew']['id'] = @$value['id'];
								$darkAdaption = $this->DarkAdaptionnew->find('first', array(
									'conditions' => array('DarkAdaptionnew.id' => $value['id'])
								));
								$data['DarkAdaptionnew']['pdf'] = $darkAdaption['DarkAdaptionnew']['pdf'];
								//echo $data['DarkAdaption']['pdf'];
								//$responseData['data'][$key]['pdf']=WWW_BASE.'uploads/darkadaption/'.$value['DarkAdaption']['pdf'];
							}
							if (isset($value['pdf']) && (!empty($value['pdf']))) {
								$pid = (int)@$value['patient_id'];
								$folder_name = "uploads/darkadaption";
								$data['DarkAdaptionnew']['pdf'] = $this->base64_to_pdf($value['pdf'], $folder_name, $pid);
								if (!empty($data['DarkAdaptionnew']['pdf'])) {
									$responseData['data'][$key]['pdf'] = WWW_BASE . 'uploads/darkadaption/' . $data['DarkAdaptionnew']['pdf'];
									if (!empty(@$value['id']) && !empty($darkAdaption['DarkAdaptionnew']['pdf'])) {
										unlink(WWW_ROOT . 'uploads/darkadaption/' . $darkAdaption['DarkAdaptionnew']['pdf']);
									}
								}
								//pr($darkAdaption); die;
							}
							$data['DarkAdaptionnew']['patient_name'] = @$value['patient_name'];
							$data['DarkAdaptionnew']['patient_id'] = (int)@$value['patient_id'];
							$data['DarkAdaptionnew']['staff_name'] = @$value['staff_name'];
							$data['DarkAdaptionnew']['staff_id'] = @$value['staff_id'];
							$data['DarkAdaptionnew']['office_id'] = @$value['office_id'];
							$data['DarkAdaptionnew']['test_date_time'] = date('Y-m-d H:i:s', strtotime(@$value['test_date_time']));
							$data['DarkAdaptionnew']['eye_select'] = @$value['eye_select'];
							//echo "<pre>";
							//print_r($data); die;
							$this->DarkAdaptionnew->create();
							$result = $this->DarkAdaptionnew->save($data);
							$lastId = $this->DarkAdaptionnew->id;
							$last_sync_time = $result['DarkAdaptionnew']['created'];
							$pointData = [];
							$responseData['data'][$key]['id'] = $lastId;
							if (isset($value['daPointdata']) && !empty($value['daPointdata'])) {
								foreach ($value['daPointdata'] as $key1 => $value1) {
									// Delete with array conditions similar to find()
									$this->DaPointData->deleteAll(array('DaPointData.dark_adaption_id' => $lastId), false);
									$pointData[$key1]['DaPointData']['dark_adaption_id'] = $lastId;
									$pointData[$key1]['DaPointData']['timeMin'] = $value1['timeMin'];
									$pointData[$key1]['DaPointData']['Decibles'] = $value1['Decibles'];
									$pointData[$key1]['DaPointData']['x'] = $value1['x'];
									$pointData[$key1]['DaPointData']['y'] = $value1['y'];
									$pointData[$key1]['DaPointData']['color'] = @$value1['color'];
									$pointData[$key1]['DaPointData']['index_name'] = @$value1['index_name'];
									$pointData[$key1]['DaPointData']['Eye'] = @$value1['Eye'];
								}
								$this->DaPointData->saveMany($pointData, array('deep' => true));
							}
						} else {
							// didn't validate logic
							$uploadedids[$key]['id'] = $data['DarkAdaptionnew']['unique_id'];
							$errors = $this->DarkAdaptionnew->validationErrors;
						}
					}
					$response['message'] = 'Dark Adaption saved successfully.';
					$response['result'] = 1;
					$response['last_sync_time'] = $last_sync_time;
					$response['data'] = $responseData['data'];
					//$response['uploaded_data']=$uploadedids;
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}

	/*Save single report from API 24-11-2022 by Madan*/
		public function saveDAReport_v6(){
		if ($this->check_key()) {
				$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$input_data = $_POST;
				$last_sync_time = '';
				$responseData = $faild_data=$fail=  array();
				$responseData['data'] = array();
				$input_data = $_POST;
				if (empty($input_data)) {
					$input_data = json_decode(file_get_contents('php://input'), true);
				}
				$result2 = $this->DarkAdaption->find('first', array('conditions' => array('DarkAdaption.unique_id' => $input_data['data'][0]['unique_id'])));
				if(!empty($result2)){
					$response['message']='Some error occured in updating report.';
					$response['result']=0;
					$fail['id']=$result2['DarkAdaption']['id']; 
					$fail['unique_id']=$input_data['data'][0]['unique_id'];
					$fail['patient_name']=$result2['DarkAdaption']['patient_name']; 
					$faild_data[]=$fail;
					$response['failed_data'] = $faild_data;
					echo json_encode($response);
					exit(); 
				}
				if (isset($input_data['data']) && !empty($input_data['data'])){
					foreach ($input_data['data'] as $key => $value) {
						$data = [];
						$uploadedids = [];
						$data['DarkAdaptionnew']['unique_id'] = @$value['unique_id'];
						$this->DarkAdaptionnew->set($data);
						if ($this->DarkAdaptionnew->validates()){
							if (isset($value['id']) && !empty(@$value['id'])) {
								$data['DarkAdaptionnew']['id'] = @$value['id'];
								$darkAdaption = $this->DarkAdaptionnew->find('first', array(
									'conditions' => array('DarkAdaptionnew.id' => $value['id'])
								));
								$data['DarkAdaptionnew']['pdf'] = $darkAdaption['DarkAdaptionnew']['pdf'];
							}
							if (isset($value['pdf']) && (!empty($value['pdf']))) {
								$pid = (int)@$value['patient_id'];
								$folder_name = "uploads/darkadaption";
								$data['DarkAdaptionnew']['pdf'] = $this->base64_to_pdf($value['pdf'],$folder_name, $pid);
								if (!empty($data['DarkAdaptionnew']['pdf'])) {
									$responseData['data'][$key]['pdf'] = WWW_BASE . 'uploads/darkadaption/' . $data['DarkAdaptionnew']['pdf'];
									if (!empty(@$value['id']) && !empty($darkAdaption['DarkAdaptionnew']['pdf'])) {
										unlink(WWW_BASE . 'uploads/darkadaption/' . $darkAdaption['DarkAdaptionnew']['pdf']);
									}
								}
							}
							$data['DarkAdaptionnew']['patient_name'] = @$value['patient_name'];
							$data['DarkAdaptionnew']['test_name'] = 'Dark Adaption';
							$data['DarkAdaptionnew']['patient_id'] = (int)@$value['patient_id'];
							$data['DarkAdaptionnew']['staff_name'] = @$value['staff_name'];
							$data['DarkAdaptionnew']['staff_id'] = @$value['staff_id'];
							$data['DarkAdaptionnew']['office_id'] = @$value['office_id'];
							$data['DarkAdaptionnew']['test_date_time'] = date('Y-m-d H:i:s', strtotime(@$value['test_date_time']));
							$data['DarkAdaptionnew']['eye_select'] = @$value['eye_select'];
							$data['DarkAdaptionnew']['created'] = @$value['created'];
							$data['DarkAdaptionnew']['created_date_utc'] = @$value['created_date_utc'];
							$this->DarkAdaptionnew->create();
							$result = $this->DarkAdaptionnew->save($data);
							if($result){
								$lastId = $this->DarkAdaptionnew->id;
								$last_sync_time = $result['DarkAdaptionnew']['created_date_utc'];
								$pointData = [];
								$responseData['data'][$key]['id'] = $lastId;
								if (isset($value['daPointdata']) && !empty($value['daPointdata'])) {
									foreach ($value['daPointdata'] as $key1 => $value1) {
										// Delete with array conditions similar to find()
										$this->DaPointData->deleteAll(array('DaPointData.dark_adaption_id' => $lastId), false);
										$pointData[$key1]['DaPointData']['dark_adaption_id'] = $lastId;
										$pointData[$key1]['DaPointData']['timeMin'] = $value1['timeMin'];
										$pointData[$key1]['DaPointData']['Decibles'] = $value1['Decibles'];
										$pointData[$key1]['DaPointData']['x'] = $value1['x'];
										$pointData[$key1]['DaPointData']['y'] = $value1['y'];
										$pointData[$key1]['DaPointData']['color'] = @$value1['color'];
										$pointData[$key1]['DaPointData']['index_name'] = @$value1['index_name'];
										$pointData[$key1]['DaPointData']['Eye'] = @$value1['Eye'];
									}
									$this->DaPointData->saveMany($pointData, array('deep' => true));
								}
								if(empty($last_sync_time)){
									date_default_timezone_set('UTC');
			            			$UTCDate = date('Y-m-d H:i:s');
									$last_sync_time = $UTCDate;
								}
								$response['message'] = 'Dark Adaption saved successfully.';
								$response['result'] = 1;
								$response['last_sync_time'] = $last_sync_time;
								$response['data'] = $responseData['data'];
								echo json_encode($response);
							} 
						}else{
							$uploadedids[$key]['id'] = $data['DarkAdaptionnew']['unique_id'];
							$errors = $this->DarkAdaptionnew->validationErrors;
						}
					}
				}else{
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
					echo json_encode($response);
					exit();
				}
				
			}
		}
	}
	/*Save single report from API 24-11-2022 by Madan*/

	/*Multiple DarkAdaption report faild data 04-11-2022*/
	public function save_da_report_V4()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$input_data = $_POST;
				$last_sync_time = '';
				$responseData = array();
				$responseData['data'] = array();
				$input_data = $_POST;
				$faildRequest = array();
				$resultData = $saved_data = $faild_data = array();
				if (empty($input_data)) {
					$input_data = json_decode(file_get_contents('php://input'), true);
				}
				//pr($input_data); die;
				if (isset($input_data['data']) && !empty($input_data['data'])) {
					//echo "<pre>";
					//print_r($input_data['data']);
					foreach ($input_data['data'] as $key => $value) {
						$data = [];
						$uploadedids = [];
						$data['DarkAdaptionnew']['unique_id'] = @$value['unique_id'];
						$this->DarkAdaptionnew->set($data);
						if ($this->DarkAdaptionnew->validates()) {
							if (isset($value['id']) && !empty(@$value['id'])) {
								$data['DarkAdaptionnew']['id'] = @$value['id'];
								$darkAdaption = $this->DarkAdaptionnew->find('first', array(
									'conditions' => array('DarkAdaptionnew.id' => $value['id'])
								));
								$data['DarkAdaptionnew']['pdf'] = $darkAdaption['DarkAdaptionnew']['pdf'];
								//echo $data['DarkAdaption']['pdf'];
								//$responseData['data'][$key]['pdf']=WWW_BASE.'uploads/darkadaption/'.$value['DarkAdaption']['pdf'];
							}
							if (isset($value['pdf']) && (!empty($value['pdf']))) {
								$pid = (int)@$value['patient_id'];
								$folder_name = "uploads/darkadaption";
								$data['DarkAdaptionnew']['pdf'] = $this->base64_to_pdf($value['pdf'], $folder_name, $pid);
								if (!empty($data['DarkAdaptionnew']['pdf'])) {
									$responseData['data'][$key]['pdf'] = WWW_BASE . 'uploads/darkadaption/' . $data['DarkAdaptionnew']['pdf'];
									if (!empty(@$value['id']) && !empty($darkAdaption['DarkAdaptionnew']['pdf'])) {
										unlink(WWW_ROOT . 'uploads/darkadaption/' . $darkAdaption['DarkAdaptionnew']['pdf']);
									}
								}
								//pr($darkAdaption); die;
							}
							$data['DarkAdaptionnew']['patient_name'] = @$value['patient_name'];
							$data['DarkAdaptionnew']['patient_id'] = (int)@$value['patient_id'];
							$data['DarkAdaptionnew']['staff_name'] = @$value['staff_name'];
							$data['DarkAdaptionnew']['staff_id'] = @$value['staff_id'];
							$data['DarkAdaptionnew']['office_id'] = @$value['office_id'];
							$data['DarkAdaptionnew']['test_date_time'] = date('Y-m-d H:i:s', strtotime(@$value['test_date_time']));
							$data['DarkAdaptionnew']['eye_select'] = @$value['eye_select'];
							//echo "<pre>";
							//print_r($data); die;
							$this->DarkAdaptionnew->create();
							$result = $this->DarkAdaptionnew->save($data);
							if ($result) {
								$saved_data[]['id'] = $this->DarkAdaptionnew->id;
							}else{
							  	$fail=array(); 
									$errors = $this->DarkAdaptionnew->validationErrors;
									$response['message']='Some error occured in updating report.';
									$result2 = $this->DarkAdaptionnew->find('first', array('conditions' => array('DarkAdaptionnew.unique_id' => $value['unique_id'])));
									$response['result']=0;
									$fail['id']=$result2['DarkAdaptionnew']['id']; 
									$fail['unique_id']=$value['unique_id'];
									//pr($result2['Patient']);die; 
									$name=$name=$result2['Patient']['first_name'];
									if($result2['Patient']['middle_name']!=""){
										$name=$name.' '.$result2['Patient']['middle_name'];
									}
									if($result2['Patient']['last_name']!=""){
										$name=$name.' '.$result2['Patient']['last_name'];
									}
									$fail['patient_name']=$name; 
									$fail['message']=$errors[array_keys($errors)[0]][0];
									$faild_data[]=$fail; 
								} 
							$lastId = $this->DarkAdaptionnew->id;
							$last_sync_time = $result['DarkAdaptionnew']['created'];
							$pointData = [];
							$responseData['data'][$key]['id'] = $lastId;
							if (isset($value['daPointdata']) && !empty($value['daPointdata'])) {
								foreach ($value['daPointdata'] as $key1 => $value1) {
									// Delete with array conditions similar to find()
									$this->DaPointData->deleteAll(array('DaPointData.dark_adaption_id' => $lastId), false);
									$pointData[$key1]['DaPointData']['dark_adaption_id'] = $lastId;
									$pointData[$key1]['DaPointData']['timeMin'] = $value1['timeMin'];
									$pointData[$key1]['DaPointData']['Decibles'] = $value1['Decibles'];
									$pointData[$key1]['DaPointData']['x'] = $value1['x'];
									$pointData[$key1]['DaPointData']['y'] = $value1['y'];
									$pointData[$key1]['DaPointData']['color'] = @$value1['color'];
									$pointData[$key1]['DaPointData']['index_name'] = @$value1['index_name'];
									$pointData[$key1]['DaPointData']['Eye'] = @$value1['Eye'];
								}
								$this->DaPointData->saveMany($pointData, array('deep' => true));
							}
						} else {
							// didn't validate logic
							$uploadedids[$key]['id'] = $data['DarkAdaptionnew']['unique_id'];
							$errors = $this->DarkAdaptionnew->validationErrors;
						}
					}
					$response['message'] = 'Dark Adaption saved successfully.';
					$response['result'] = 1;
					$response['last_sync_time'] = $last_sync_time;
					$response['data'] = $responseData['data'];
					//$response['uploaded_data']=$uploadedids;
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				$response['failed_data'] = $faild_data;
				echo json_encode($response);
				exit();
			}
		}
	}
	/*Multiple DarkAdaption report faild data 04-11-2022*/

	/*Multiple DarkAdaption report faild data 24-11-2022*/
	public function saveMultipleDAReport_v6()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$input_data = $_POST;
				$last_sync_time = '';
				$responseData = array();
				$responseData['data'] = array();
				$input_data = $_POST;
				$faildRequest = array();
				$resultData = $saved_data = $faild_data = array();
				if (empty($input_data)) {
					$input_data = json_decode(file_get_contents('php://input'), true);
				}
				//pr($input_data); die;
				if (isset($input_data['data']) && !empty($input_data['data'])) {
					//echo "<pre>";
					//print_r($input_data['data']);
					foreach ($input_data['data'] as $key => $value) {
						$data = [];
						$uploadedids = [];
						$data['DarkAdaptionnew']['unique_id'] = @$value['unique_id'];
						$this->DarkAdaptionnew->set($data);
						if ($this->DarkAdaptionnew->validates()) {
							if (isset($value['id']) && !empty(@$value['id'])) {
								$data['DarkAdaptionnew']['id'] = @$value['id'];
								$darkAdaption = $this->DarkAdaptionnew->find('first', array(
									'conditions' => array('DarkAdaptionnew.id' => $value['id'])
								));
								$data['DarkAdaptionnew']['pdf'] = $darkAdaption['DarkAdaptionnew']['pdf'];
								//echo $data['DarkAdaption']['pdf'];
								//$responseData['data'][$key]['pdf']=WWW_BASE.'uploads/darkadaption/'.$value['DarkAdaption']['pdf'];
							}
							if (isset($value['pdf']) && (!empty($value['pdf']))) {
								$pid = (int)@$value['patient_id'];
								$folder_name = "uploads/darkadaption";
								$data['DarkAdaptionnew']['pdf'] = $this->base64_to_pdf($value['pdf'], $folder_name, $pid);
								if (!empty($data['DarkAdaptionnew']['pdf'])) {
									$responseData['data'][$key]['pdf'] = WWW_BASE . 'uploads/darkadaption/' . $data['DarkAdaptionnew']['pdf'];
									if (!empty(@$value['id']) && !empty($darkAdaption['DarkAdaptionnew']['pdf'])) {
										unlink(WWW_ROOT . 'uploads/darkadaption/' . $darkAdaption['DarkAdaptionnew']['pdf']);
									}
								}
								//pr($darkAdaption); die;
							}
							$data['DarkAdaptionnew']['patient_name'] = @$value['patient_name'];
							$data['DarkAdaptionnew']['patient_id'] = (int)@$value['patient_id'];
							$data['DarkAdaptionnew']['staff_name'] = @$value['staff_name'];
							$data['DarkAdaptionnew']['staff_id'] = @$value['staff_id'];
							$data['DarkAdaptionnew']['office_id'] = @$value['office_id'];
							$data['DarkAdaptionnew']['test_date_time'] = date('Y-m-d H:i:s', strtotime(@$value['test_date_time']));
							$data['DarkAdaptionnew']['eye_select'] = @$value['eye_select'];
							$data['DarkAdaptionnew']['created'] = @$value['created'];
							$data['DarkAdaptionnew']['created_date_utc'] = @$value['created_date_utc'];
							$data['DarkAdaptionnew']['test_name'] = 'Dark Adaption';
							//echo "<pre>";
							//print_r($data); die;
							$this->DarkAdaptionnew->create();
							$result = $this->DarkAdaptionnew->save($data);
							if ($result) {
								$saved_data[]['id'] = $this->DarkAdaptionnew->id;
							}else{
							  	$fail=array(); 
									$errors = $this->DarkAdaptionnew->validationErrors;
									$response['message']='Some error occured in updating report.';
									$result2 = $this->DarkAdaptionnew->find('first', array('conditions' => array('DarkAdaptionnew.unique_id' => $value['unique_id'])));
									$response['result']=0;
									$fail['id']=$result2['DarkAdaptionnew']['id']; 
									$fail['unique_id']=$value['unique_id'];
									//pr($result2['Patient']);die; 
									$name=$name=$result2['Patient']['first_name'];
									if($result2['Patient']['middle_name']!=""){
										$name=$name.' '.$result2['Patient']['middle_name'];
									}
									if($result2['Patient']['last_name']!=""){
										$name=$name.' '.$result2['Patient']['last_name'];
									}
									$fail['patient_name']=$name; 
									$fail['message']=$errors[array_keys($errors)[0]][0];
									$faild_data[]=$fail; 
								} 
							$lastId = $this->DarkAdaptionnew->id;
							$last_sync_time = $result['DarkAdaptionnew']['created_date_utc'];
							$pointData = [];
							$responseData['data'][$key]['id'] = $lastId;
							if (isset($value['daPointdata']) && !empty($value['daPointdata'])) {
								foreach ($value['daPointdata'] as $key1 => $value1) {
									// Delete with array conditions similar to find()
									$this->DaPointData->deleteAll(array('DaPointData.dark_adaption_id' => $lastId), false);
									$pointData[$key1]['DaPointData']['dark_adaption_id'] = $lastId;
									$pointData[$key1]['DaPointData']['timeMin'] = $value1['timeMin'];
									$pointData[$key1]['DaPointData']['Decibles'] = $value1['Decibles'];
									$pointData[$key1]['DaPointData']['x'] = $value1['x'];
									$pointData[$key1]['DaPointData']['y'] = $value1['y'];
									$pointData[$key1]['DaPointData']['color'] = @$value1['color'];
									$pointData[$key1]['DaPointData']['index_name'] = @$value1['index_name'];
									$pointData[$key1]['DaPointData']['Eye'] = @$value1['Eye'];
								}
								$this->DaPointData->saveMany($pointData, array('deep' => true));
							}
						} else {
							// didn't validate logic
							$uploadedids[$key]['id'] = $data['DarkAdaptionnew']['unique_id'];
							$errors = $this->DarkAdaptionnew->validationErrors;
						}
					}
					if($input_data['sync_start_time'] == ''){
						date_default_timezone_set('UTC');
            			$UTCDate = date('Y-m-d H:i:s');
						$last_sync_time = $UTCDate;
					}
					$response['message'] = 'Dark Adaption saved successfully.';
					$response['result'] = 1;
					$response['last_sync_time'] = $last_sync_time;
					$response['data'] = $responseData['data'];
					//$response['uploaded_data']=$uploadedids;
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				$response['failed_data'] = $faild_data;
				echo json_encode($response);
				exit();
			}
		}
	}


	/*
		API Name:  https://www.portal.micromedinc.com/apisnew/sync_office_logo
		Request Parameter: office_id, last_sync_time
		Date: 28 March, 2019
	*/
	public function sync_office_logo()
	{
		if ($this->check_key()) {
			//echo 'hello';die;
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				if (isset($data_arrays['last_sync_time']) && !empty($data_arrays['last_sync_time'])) {
					$update_date = date('Y-m-d h:i:s', strtotime($data_arrays['last_sync_time']));
					$condition['Office.updated !='] = $update_date;
				}
				if (!empty($data_arrays['office_id'])) {
					$condition['Office.id'] = $data_arrays['office_id'];
					$this->Office->unbindModel(array('hasMany' => array('Officereport')));
					$officeLogo = $this->Office->find('first',
						array(
							'conditions' => $condition,
							'fields' => array('Office.office_pic', 'Office.updated',),
						)
					);
					if (isset($officeLogo['Office']['office_pic']) && !empty($officeLogo['Office']['office_pic'])) {
						$logoImage = WWW_BASE . 'img/office/' . $officeLogo['Office']['office_pic'];
						$base64Image = $this->jpeg_to_base64($officeLogo['Office']['office_pic'], '../../../../inetpub/wwwroot/portalmi2/app/webroot/img/office/');
						$data['base64logo'] = $base64Image;
						$data['logo'] = $logoImage;
						$data['last_sync_time'] = $officeLogo['Office']['updated'];
						//['base64logo'=>$base64Image, 'logo'=>$logoImage];
						$response['message'] = 'Logo Fetch successfully.';
						$response['result'] = 1;
						$response['data'] = $data;
					} else {
						$response['message'] = 'No record or updates found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Office Id is required.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*
		API Name:  https://www.portal.micromedinc.com/apisnew/check_office_status
		Request Parameter: office_id
		Date: 28 March, 2019
	*/
	public function check_office_status()
	{
		if ($this->check_key()) {
			//echo 'hello';die;
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				if (!empty($data_arrays['office_id'])) {
					$condition['Office.id'] = $data_arrays['office_id'];
					$this->Office->unbindModel(array('hasMany' => array('Officereport')));
					$officeData = $this->Office->find('first',
						array(
							'conditions' => $condition,
							'fields' => array('Office.status', 'Office.updated',),
						)
					);
					if (isset($officeData['Office']) && !empty($officeData['Office'])) {
						$data['status'] = $officeData['Office']['status'];
						$response['message'] = 'Your office status 1: Active, 0: Inactive';
						$response['result'] = 1;
						$response['data'] = $data;
					} else {
						$response['message'] = 'No record or updates found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Office Id is required.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*
		API Name:  https://www.portal.micromedinc.com/apisnew/get_da_report
		Request Parameter: office_id
		Date: 28 March, 2019
	*/
	public function get_da_report()
	{
		if ($this->check_key()) {
			//echo 'hello';die;
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				//pr($data_arrays);die;
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 10 - 1;
					} else {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = ($data_arrays['page'] - 1) * 10;
						$end = $data_arrays['page'] * 10 - 1;
					}
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id'], 'User.user_type' => array('Staffuser', 'Subadmin')), 'fields' => array('User.office_id')));
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id'], 'User.user_type' => array('Staffuser', 'Subadmin')), 'fields' => array('User.id')));
					$condition['DarkAdaption.staff_id'] = $all_staff_ids;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['DarkAdaption.patient_id'] = $data_arrays['patient_id'];
					}
					if (isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["DarkAdaption.patient_name LIKE"] = '%' . $data_arrays['patient_name'] . "%";
					}
					if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['DarkAdaption.created >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}
					$this->loadModel('VaData');
					$this->loadModel('User');
				   
					$results = $this->DarkAdaption->find('all', array('conditions' => $condition, 'fields' => array('DarkAdaption.id', 'DarkAdaption.unique_id', 'DarkAdaption.staff_id', 'DarkAdaption.created', 'DarkAdaption.patient_name', 'DarkAdaption.pdf', 'DarkAdaption.patient_id', 'DarkAdaption.eye_select', 'DarkAdaption.test_date_time', 'DarkAdaption.office_id','Patient.first_name', 'Patient.middle_name', 'Patient.last_name'), 'order' => array('DarkAdaption.id DESC'), 'limit' => $limit));
					if ($data_arrays['page'] != 0) {
						if ((count($results) > $data_arrays['page'] * 10)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
					if (!empty($results)) {
						$data = array();
						if ($data_arrays['page'] == 0) {
							$end = count($results) - 1;
						}
						$i = 0;
						$last_sync_time = '';
						foreach ($results as $key => $result) { 
							if ($key >= $start && $key <= $end) {
								//pr($result);die;
								$user = $this->User->findById($result['DarkAdaption']['staff_id']);
								$data[$i] = $result['DarkAdaption'];
								$data[$i]['test_id'] = $result['DarkAdaption']['id'];
								$data[$i]['unique_id'] = $result['DarkAdaption']['unique_id'];
								$data[$i]['staff_name'] = @$user['User']['first_name'] . ' ' . @$user['User']['middle_name'] . ' ' . @$user['User']['last_name'];
								unset($data[$i]['id']);
								$data[$i]['created_date'] = ($result['DarkAdaption']['created'] != null) ? ($result['DarkAdaption']['created']) : '';
								$data[$i]['patient_name'] = $result['Patient']['first_name'].''.$result['Patient']['middle_name'].' '.$result['Patient']['last_name'];
								if (!empty($result['DarkAdaption']['pdf'])) {
									$data[$i]['pdf'] = WWW_BASE . 'uploads/darkadaption/' . $result['DarkAdaption']['pdf'];
								} else {
									$data[$i]['pdf'] = '';
								}
								$data[$i]['patient_id'] = isset($result['DarkAdaption']['patient_id']) ? ($result['DarkAdaption']['patient_id']) : '';
								$data[$i]['eye_select'] = ($result['DarkAdaption']['eye_select'] != null) ? ($result['DarkAdaption']['eye_select']) : '';
								//if(isset($data[$i]['pdf'])) unset($data[$i]['pdf']) ;
								if (isset($data[$i]['created'])) unset($data[$i]['created']);
								//$data[$i]['daPointdata']=isset($result['DaPointData'])?($result['DaPointData']):'';
								$last_sync_time = !empty($last_sync_time) ? $last_sync_time : $result['DarkAdaption']['created'];
								$i++;
							}
						}
						//pr($data);die;
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['result'] = 1;
							$response['more_data'] = $more_data;
							$response['last_sync_time'] = $last_sync_time;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $more_data;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}

		/*get DarkAdaption Report create new Api by Madan 24-11-2022*/

		public function get_da_report_v6()
	{
		if ($this->check_key()) {
			//echo 'hello';die;
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				//pr($data_arrays);die;
				if (isset($data_arrays['page']) && (isset($data_arrays['staff_id']) && (!empty($data_arrays['staff_id'])))) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 10 - 1;
					} else {
						$limit = $data_arrays['page'] * 10 + 1;
						$start = ($data_arrays['page'] - 1) * 10;
						$end = $data_arrays['page'] * 10 - 1;
					}
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $data_arrays['staff_id'], 'User.user_type' => array('Staffuser', 'Subadmin')), 'fields' => array('User.office_id')));
					if (empty($office_id)) {
						$response['message'] = 'Invalid staff.';
						$response['result'] = 0;
						echo json_encode($response);
						die;
					}
					$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id'], 'User.user_type' => array('Staffuser', 'Subadmin')), 'fields' => array('User.id')));
					$condition['DarkAdaption.staff_id'] = $all_staff_ids;
					if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
						$condition['DarkAdaption.patient_id'] = $data_arrays['patient_id'];
					}
					if (isset($data_arrays['patient_name']) && !empty($data_arrays['patient_name'])) {
						//$condition['Pointdata.patient_name'] = $data_arrays['patient_name'];
						$condition["DarkAdaption.patient_name LIKE"] = '%' . $data_arrays['patient_name'] . "%";
					}
					if (isset($data_arrays['sync_start_time']) && !empty($data_arrays['sync_start_time'])) {
						$condition['DarkAdaption.created_date_utc >'] = date('Y-m-d H:i:s', strtotime($data_arrays['sync_start_time']));
					}
					$this->loadModel('VaData');
					$this->loadModel('User');
				   
					$results = $this->DarkAdaption->find('all', array('conditions' => $condition, 'fields' => array('DarkAdaption.id', 'DarkAdaption.unique_id', 'DarkAdaption.staff_id', 'DarkAdaption.created', 'DarkAdaption.created_date_utc', 'DarkAdaption.patient_name', 'DarkAdaption.pdf', 'DarkAdaption.patient_id', 'DarkAdaption.eye_select', 'DarkAdaption.test_date_time', 'DarkAdaption.office_id','Patient.first_name', 'Patient.middle_name', 'Patient.last_name'), 'order' => array('DarkAdaption.id DESC'), 'limit' => $limit));
					if ($data_arrays['page'] != 0) {
						if ((count($results) > $data_arrays['page'] * 10)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
					if (!empty($results)) {
						$data = array();
						if ($data_arrays['page'] == 0) {
							$end = count($results) - 1;
						}
						$i = 0;
						$last_sync_time = '';
						foreach ($results as $key => $result) { 
							if ($key >= $start && $key <= $end) {
								//pr($result);die;
								$user = $this->User->findById($result['DarkAdaption']['staff_id']);
								$data[$i] = $result['DarkAdaption'];
								$data[$i]['test_id'] = $result['DarkAdaption']['id'];
								$data[$i]['unique_id'] = $result['DarkAdaption']['unique_id'];
								$data[$i]['staff_name'] = @$user['User']['first_name'] . ' ' . @$user['User']['middle_name'] . ' ' . @$user['User']['last_name'];
								unset($data[$i]['id']);
								$data[$i]['created_date'] = ($result['DarkAdaption']['created'] != null) ? ($result['DarkAdaption']['created']) : '';
								$data[$i]['patient_name'] = $result['Patient']['first_name'].''.$result['Patient']['middle_name'].' '.$result['Patient']['last_name'];
								if (!empty($result['DarkAdaption']['pdf'])) {
									$data[$i]['pdf'] = WWW_BASE . 'uploads/darkadaption/' . $result['DarkAdaption']['pdf'];
								} else {
									$data[$i]['pdf'] = '';
								}
								$data[$i]['patient_id'] = isset($result['DarkAdaption']['patient_id']) ? ($result['DarkAdaption']['patient_id']) : '';
								$data[$i]['eye_select'] = ($result['DarkAdaption']['eye_select'] != null) ? ($result['DarkAdaption']['eye_select']) : '';
								//if(isset($data[$i]['pdf'])) unset($data[$i]['pdf']) ;
								if (isset($data[$i]['created_date_utc'])) unset($data[$i]['created_date_utc']);
								//$data[$i]['daPointdata']=isset($result['DaPointData'])?($result['DaPointData']):'';
								$last_sync_time = !empty($last_sync_time) ? $last_sync_time : $result['DarkAdaption']['created_date_utc'];
								$i++;
							}
						}
						if($data_arrays['sync_start_time'] == ''){
							date_default_timezone_set('UTC');
            				$UTCDate = date('Y-m-d H:i:s');
							$last_sync_time = $UTCDate;
						}
						if (!empty($data)) {
							$response['message'] = 'All test report list.';
							$response['result'] = 1;
							$response['more_data'] = $more_data;
							$response['last_sync_time'] = $last_sync_time;
							$response['data'] = $data;
						} else {
							$response['message'] = 'No test report found.';
							$response['more_data'] = $more_data;
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO test report found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}
		/*get DarkAdaption Report create new Api by Madan 24-11-2022*/
	


	/*API Name:  https://www.portal.micromedinc.com/apisnew/get_user_preset
		Request Parameter: user_id
		Date: 17, Sept 2020
	*/
	public function get_user_preset()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				if (!empty($data_arrays['user_id'])) {
					//pr($data_arrays); die;
					$this->UserPreset->primaryKey = 'user_id';
					$this->UserPresetData->virtualFields['threshold_type'] = 'UserPresetData.testSubType';
					$this->UserPreset->bindModel(
						array(
							'hasMany' => array(
								'UserPresetData' => array(
									'className' => 'UserPresetData',
									'foreignKey' => 'user_id',
								)
							)
						)
					);
					$userPresets = $this->UserPreset->find('first', array(
						'conditions' => array('UserPreset.user_id' => $data_arrays['user_id'])
					));
					if (!empty($userPresets)) {
						$response['message'] = 'User Presets.';
						$response['result'] = 1;
						$response['data'] = $userPresets['UserPreset'];
						$response['data']['listOfUserPreset'] = $userPresets['UserPresetData'];
					} else {
						$response['message'] = 'NO record found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'User Id is required.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}
	public function update_user_preset()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				if (!empty($data_arrays['user_id'])) {
					$this->UserPreset->primaryKey = 'user_id';
					$this->UserPresetData->virtualFields['threshold_type'] = 'UserPresetData.testSubType';
					$this->UserPreset->bindModel(
						array(
							'hasMany' => array(
								'UserPresetData' => array(
									'className' => 'UserPresetData',
									'foreignKey' => 'user_id',
								)
							)
						)
					);
					$userPresets = $this->UserPreset->find('first', array(
						'conditions' => array('UserPreset.user_id' => $data_arrays['user_id'])
					));
					if (empty($userPresets) || !empty($userPresets)) {
						foreach ($data_arrays['listOfUserPreset'] as $key => $value) {
							$this->UserPresetData->clear();
							$userPresetsData = $this->UserPresetData->find('first', array(
								'conditions' => array('UserPresetData.user_id' => $data_arrays['user_id'], 'UserPresetData.user_presets' => $value['presetType'])));
							$userPresetsData['UserPresetData']['user_id'] = $data_arrays['user_id'];
							$userPresetsData['UserPresetData']['user_presets'] = ucfirst($value['presetType']);
							$userPresetsData['UserPresetData']['testType'] = $value['TestType'];
							$userPresetsData['UserPresetData']['testSubType'] = $value['Strategy'];
							$userPresetsData['UserPresetData']['testTypeName'] = $value['TestName'];
							$userPresetsData['UserPresetData']['stimulusSize'] = $value['Size'];
							$userPresetsData['UserPresetData']['stimulusIntensity'] = $value['Intensity'];
							$userPresetsData['UserPresetData']['wallBrightness'] = $value['Wall'];
							$userPresetsData['UserPresetData']['testSpeed'] = $value['Speed'];
							$userPresetsData['UserPresetData']['testColour'] = $value['Color'];
							$userPresetsData['UserPresetData']['testBackground'] = $value['BgColor'];
							$userPresetsData['UserPresetData']['Training'] = $value['Training'];
							$userPresetsData['UserPresetData']['Photopic'] = $value['Photopic'];
							$this->UserPresetData->save($userPresetsData);
							$this->UserPresetData->clear();
						}
					}
					if (empty($userPresets)) {
						$userPresets['UserPreset']['user_id'] = $data_arrays['user_id'];
						$userPresets['UserPreset']['presetA'] = $data_arrays['presetA'];
						$userPresets['UserPreset']['presetB'] = $data_arrays['presetB'];
						$userPresets['UserPreset']['presetC'] = $data_arrays['presetC'];
						$userPresets['UserPreset']['presetD'] = $data_arrays['presetD'];
						$userPresets['UserPreset']['presetE'] = $data_arrays['presetE'];
						$userPresets['UserPreset']['presetF'] = $data_arrays['presetF'];
						if ($this->UserPreset->save($userPresets)) {
							$this->UserPreset->primaryKey = 'user_id';
							$this->UserPresetData->virtualFields['threshold_type'] = 'UserPresetData.testSubType';
							$this->UserPreset->bindModel(
								array(
									'hasMany' => array(
										'UserPresetData' => array(
											'className' => 'UserPresetData',
											'foreignKey' => 'user_id',
										)
									)
								)
							);
							$userPresets2 = $this->UserPreset->find('first', array(
								'conditions' => array('UserPreset.user_id' => $data_arrays['user_id'])
							));
							$response['message'] = 'User Presets updated.';
							$response['result'] = 1;
							$response['data'] = $userPresets2['UserPreset'];
							$response['data']['listOfUserPreset'] = $userPresets2['UserPresetData'];
						} else {
							$errors = $this->UserPreset->validationErrors;
							//pr($errors); die;
							$response['message'] = $this->getFirstError($this->UserPreset->validationErrors);
							$response['result'] = 0;
						}
					} else if (!empty($userPresets)) {
						$userPresets['UserPreset']['presetA'] = $data_arrays['presetA'];
						$userPresets['UserPreset']['presetB'] = $data_arrays['presetB'];
						$userPresets['UserPreset']['presetC'] = $data_arrays['presetC'];
						$userPresets['UserPreset']['presetD'] = $data_arrays['presetD'];
						$userPresets['UserPreset']['presetE'] = $data_arrays['presetE'];
						$userPresets['UserPreset']['presetF'] = $data_arrays['presetF'];
						if ($this->UserPreset->save($userPresets)) {
							$this->UserPreset->primaryKey = 'user_id';
							$this->UserPresetData->virtualFields['threshold_type'] = 'UserPresetData.testSubType';
							$this->UserPreset->bindModel(
								array(
									'hasMany' => array(
										'UserPresetData' => array(
											'className' => 'UserPresetData',
											'foreignKey' => 'user_id',
										)
									)
								)
							);
							$userPresets2 = $this->UserPreset->find('first', array(
								'conditions' => array('UserPreset.user_id' => $data_arrays['user_id'])
							));
							$response['message'] = 'User Presets updated.';
							$response['result'] = 1;
							$response['data'] = $userPresets2['UserPreset'];
							$response['data']['listOfUserPreset'] = $userPresets2['UserPresetData'];
						} else {
							$errors = $this->UserPreset->validationErrors;
							//pr($errors); die;
							$response['message'] = $this->getFirstError($this->UserPreset->validationErrors);
							$response['result'] = 0;
						}
					} else {
						$response['message'] = 'NO record found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'User Id is required.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}
	/*
		API Name:  https://www.portal.micromedinc.com/apisnew/get_user_preset_data
		Request Parameter: user_id, preset_name
		Date: 17, Sept 2020
	*/
	public function get_user_preset_data()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				if (!empty($data_arrays['user_id']) && !empty($data_arrays['preset_name'])) {
					//pr($data_arrays); die;
					$this->UserPresetData->virtualFields['threshold_type'] = 'UserPresetData.testSubType';
					$this->UserPresetData->virtualFields['test_name'] = 'UserPresetData.testTypeName';
					$userPresets = $this->UserPresetData->find('first', array(
						'conditions' => array('UserPresetData.user_id' => $data_arrays['user_id'], 'UserPresetData.user_presets' => $data_arrays['preset_name'])
					));
					if (!empty($userPresets)) {
						$response['message'] = 'User Presets.';
						$response['result'] = 1;
						$response['data'] = $userPresets['UserPresetData'];
					} else {
						$response['message'] = 'NO record found.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'User Id is required.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}
	/*
		API Name:  https://www.portal.micromedinc.com/apisnew/update_user_preset
		Request Parameter: user_id, preset_name
		Date: 17, Sept 2020
	*/
	public function update_user_preset_v2()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (empty($data_arrays)) {
					$data_arrays = json_decode(file_get_contents('php://input'), true);
				}
				if (!empty($data_arrays['user_id']) && !empty($data_arrays['preset_name'])) {
					//pr($data_arrays); die;
					$userPresets = $this->UserPreset->find('first', array(
						'conditions' => array('UserPreset.user_id' => $data_arrays['user_id'])
					));
					if (!empty($userPresets)) {
						$userPresets['UserPreset'][lcfirst($data_arrays['preset_name'])] = $data_arrays['new_preset_name'];
						$this->UserPreset->save($userPresets);
						$response['message'] = 'Presets Updated Successfully.';
						$response['result'] = 1;
					} else {
						$userPresets['UserPreset']['presetA'] = 'presetA';
						$userPresets['UserPreset']['presetB'] = 'presetB';
						$userPresets['UserPreset']['presetC'] = 'presetC';
						$userPresets['UserPreset']['presetD'] = 'presetD';
						$userPresets['UserPreset']['presetE'] = 'presetE';
						$userPresets['UserPreset']['presetF'] = 'presetF';
						$userPresets['UserPreset'][$data_arrays['preset_name']] = $data_arrays['new_preset_name'];
						$this->UserPreset->save($userPresets);
						$response['message'] = 'Presets Updated Successfully.';
						$response['result'] = 1;
					}
					$userPresetsData = $this->UserPresetData->find('first', array(
						'conditions' => array('UserPresetData.user_id' => $data_arrays['user_id'], 'UserPresetData.user_presets' => $data_arrays['preset_name'])
					));
					//if(!empty($userPresets)){
					$userPresetsData['UserPresetData']['user_id'] = $data_arrays['user_id'];
					$userPresetsData['UserPresetData']['user_presets'] = ucfirst($data_arrays['preset_name']);
					$userPresetsData['UserPresetData']['testType'] = $data_arrays['testType'];
					$userPresetsData['UserPresetData']['testSubType'] = $data_arrays['threshold_type'];
					$userPresetsData['UserPresetData']['testTypeName'] = $data_arrays['test_name'];
					$userPresetsData['UserPresetData']['eye_taped'] = $data_arrays['eye_taped'];
					$userPresetsData['UserPresetData']['alarm_stop'] = $data_arrays['alarm_stop'];
					$userPresetsData['UserPresetData']['GazeTracking'] = $data_arrays['GazeTracking'];
					$userPresetsData['UserPresetData']['testBothEyes'] = $data_arrays['testBothEyes'];
					$userPresetsData['UserPresetData']['stimulusSize'] = $data_arrays['stimulusSize'];
					$userPresetsData['UserPresetData']['stimulusIntensity'] = $data_arrays['stimulusIntensity'];
					$userPresetsData['UserPresetData']['wallBrightness'] = $data_arrays['wallBrightness'];
					$userPresetsData['UserPresetData']['testSpeed'] = $data_arrays['testSpeed'];
					$userPresetsData['UserPresetData']['audioVolume'] = $data_arrays['audioVolume'];
					$userPresetsData['UserPresetData']['testColour'] = $data_arrays['testColour'];
					$userPresetsData['UserPresetData']['testBackground'] = $data_arrays['testBackground'];
					$userPresetsData['UserPresetData']['selectEye'] = $data_arrays['selectEye'];
					$userPresetsData['UserPresetData']['sliderTestSpeed_maxValue'] = $data_arrays['sliderTestSpeed_maxValue'];
					$userPresetsData['UserPresetData']['sliderTestSpeed_minValue'] = $data_arrays['sliderTestSpeed_minValue'];
					$this->UserPresetData->save($userPresetsData);
					$response['message'] = 'User Presets.';
					$response['message'] = 'Presets Updated Successfully.';
					$response['result'] = 1;
					/*}else{
						$response['message']='Presets Updated Successfully.';
						$response['result']=1;
					}*/
				} else {
					$response['message'] = 'User Id is required.';
					$response['result'] = 0;
				}
				header('Content-Type: application/json');
				echo json_encode($response);
				exit();
			}
		}
	}
	public function getFirstError($arr = [])
	{
		//pr(current($arr)); die;
		return current($arr)[0];
	}
	//API for return started test data
	/*
      API Name: http://www.vibesync.com/apisnew/get_test_start_data
      Request Parameter: office_id
*/
	public function get_test_start_data($office_id = 0)
	{
		$this->loadModel('TestStart');
		$response = array();
		$check = $this->TestStart->find('first', array('conditions' => array('TestStart.office_id' => $office_id)));
		if (empty($check)) {
			$response['message'] = 'Test not started';
			$response['result'] = 1;
		} else {
			$response['data'] = $check['TestStart']['testData'];
			$response['result'] = 0;
		}
		echo json_encode($response);
		exit();
	}
	//API for return started test data
	/*
      API Name: http://www.vibesync.com/apisnew/get_test_start_data2
      Request Parameter: office_id
*/
	public function get_test_start_data2($office_id = 0)
	{
		$this->loadModel('TestStart');
		$response = array();
		$check = $this->TestStart->find('first', array('conditions' => array('TestStart.office_id' => $office_id)));
		if (empty($check)) {
			$response['message'] = 'Test not started';
			$response['result'] = 0;
		} else {
			$response['data'] = json_decode($check['TestStart']['testData']);
			$response['result'] = 1;
		}
		echo json_encode($response);
		exit();
	}
	//API for return started test data
	/*
      API Name: http://www.vibesync.com/apisnew/get_test_start_data_new
      Request Parameter: office_id,device_id
*/
	public function get_test_start_data_new($office_id = 0, $device_id = 0)
	{
		$this->loadModel('TestStart');
		$input_data = $_POST; //print_r($_POST); die;
		if (empty($input_data)) {
			$input_data = json_decode(file_get_contents('php://input'), true);
		}
		if (!isset($input_data['office_id'])) {
			$input_data['office_id'] = $office_id;
		}
		if (!isset($input_data['office_id'])) {
			$input_data['device_id'] = $device_id;
		}
		$response = array();
		$check = $this->TestStart->find('first', array('conditions' => array('TestStart.office_id' => $input_data['office_id'], 'TestStart.device_id' => $input_data['device_id'])));
		if (empty($check)) { 
			$response['message'] = 'no command';
			$response['response'] = 99;
			$response['result'] = 1;
		} else { 
			$response['data'] = json_decode($check['TestStart']['testData']);
			$response['result'] = 1;
		}
		echo json_encode($response);
		exit();
	}
	//API for return started test data
	/*
      API Name: http://www.vibesync.com/apisnew/check_test_status
      Request Parameter: office_id, device_id
*/

      public function get_test_start_data_new_V1($office_id = 0, $device_id = 0)
	{
		$this->loadModel('TestStart');
		$input_data = $_POST; //print_r($_POST); die;
		if (empty($input_data)) {
			$input_data = json_decode(file_get_contents('php://input'), true);
		}
		if (!isset($input_data['office_id'])) {
			$input_data['office_id'] = $office_id;
		}
		if (!isset($input_data['office_id'])) {
			$input_data['device_id'] = $device_id;
		}
		$response = array();
		$check = $this->TestStart->find('first', array('conditions' => array('TestStart.office_id' => $input_data['office_id'], 'TestStart.device_id' => $input_data['device_id'])));
		if (empty($check)) { 
			$response['message'] = 'no command';
			$response['response'] = 99;
			$response['result'] = 1;
		} else { 
			$response['data'] = json_decode($check['TestStart']['testData']);
			$response['result'] = 1;
		}
		echo json_encode($response);
		exit();
	}
      
	public function check_test_status($office_id = 0, $device_id = 0)
	{
		//$message='check_test_status API office='.$office_id.', device='.$device_id;
		//CakeLog::write('info', "HII");
		$this->loadModel('TestStart');
		$response = array();
		$this->TestStart->cacheQuery = true;
		$check = $this->TestStart->find('first', array(
			'fields' => array('status', 'id'),
			'conditions' => array('TestStart.office_id' => $office_id, 'TestStart.device_id' => $device_id)
		));
		//pr($check);die;
		if (empty($check)) {
			$response['message'] = 'no command';
			$response['response'] = 99;
			$response['result'] = 1;
		} else {
			switch ($check['TestStart']['status']) {
				case 1:
					$response['message'] = 'Start';
					break;
				case 2:
					$response['message'] = 'Pause';
					break;
				case 3:
					$response['message'] = 'Resume';
					break;
				case 7:
				case 8:
					$response['message'] = 'Recover Last Test';
					$this->TestStart->delete($check['TestStart']['id']);
					break;
			}
			$response['response'] = json_decode($check['TestStart']['status']);
			$response['result'] = 1;
		}
		$this->TestStart->getDatasource()->disconnect();
		echo json_encode($response);
		//$dbo = $this->TestStart->getDatasource();
		//$logs = $dbo->getLog();
		// pr($logs);die;
		//CakeLog::write('info', "HII2");
		exit();
	}
	public function process_device_message()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				//  $data_arrays = $_POST;
				//pr($this->request);
				$data_arrays = $this->request->data;
				if (((!empty($data_arrays['office_id']))) && (!empty($data_arrays['device_id'])) && (!empty($data_arrays['message']))) {
					// Log::write('info', 'TEST COMMAN ',$this->request->data['message']);
					/* $HttpSocket = new HttpSocket();
                $results = $HttpSocket->post(
    'https://www.vibesync.com/admin/patients/start_test/',
    'name=test&type=user'
);*/
// array data
// $data = array('name' => 'test', 'type' => 'user');
// $results = $HttpSocket->post('https://www.vibesync.com/admin/patients/start_test/', $data);
					/* echo "<pre>";
  print_r($results);
$http = new HttpSocket();
$response = $HttpSocket->get('https://www.vibesync.com/admin/patients/start_test/');
$titlePos = strpos($response->body, '<title>');
print_r($titlePos);
 $code = $response->headers;*/
// https://book.cakephp.org/2/en/core-utility-libraries/httpsocket.html
					//CakeLog::write('info', "Test Device Message: ".$this->request->data['message']);
					//$device = $this->DeviceMessage->find('first', array('conditions' => array('DeviceMessage.office_id' => $data_arrays['office_id'],'DeviceMessage.device_id' => $data_arrays['device_id']), 'fildes'=>array('id')));
					$office_id = $this->request->data['office_id'];
					$device_id = $this->request->data['device_id'];
					$message = $this->request->data['message'];
					//  if (empty($device)) {
					$data['DeviceMessage']['office_id'] = $this->request->data['office_id'];
					$data['DeviceMessage']['device_id'] = $this->request->data['device_id'];
					$data['DeviceMessage']['message'] = $this->request->data['message'];
					$data['DeviceMessage']['craeted_at'] = date("Y-m-d H:i:s");
					$data['DeviceMessage']['updated_at'] = date("Y-m-d H:i:s");
					if ($this->DeviceMessage->query("INSERT into  `mmd_device_message`(office_id,device_id,message) values('$office_id','$device_id','$message')")) {
						//if($this->DeviceMessage->save($data)){
						$response['message'] = 'Message Saved successfully';
						$response['result'] = 1;
					} else {
						$response['message'] = 'Somthing is wrong';
						$response['result'] = 1;
					}
					// } else {
					//$id=$data['DeviceMessage']['id'];
					//  $data['DeviceMessage']['id']=$device['DeviceMessage']['id'];
					/*   $data['DeviceMessage']['office_id']=$this->request->data['office_id'];
                    $data['DeviceMessage']['device_id']=$this->request->data['device_id'];
                    $data['DeviceMessage']['message']=$this->request->data['message'];
                    $data['DeviceMessage']['updated_at']=date("Y-m-d H:i:s");*/
					//$this->DeviceMessage->query("UPDATE `mmd_device_message` SET `office_id`=$office_id,`device_id`='$office_id',`message`='$message' WHERE `id`=$id");
					// if($this->DeviceMessage->query("UPDATE `mmd_device_message` SET `office_id`='$office_id',`device_id`='$device_id',`message`='$message' WHERE `id`=$id")){
					//  if($this->DeviceMessage->query("INSERT into  `mmd_device_message`(office_id,device_id,message) values('$office_id','$device_id','$message')")){
					//if($this->DeviceMessage->save($data)){
					/*    $response['message'] = 'Message Saved successfully';
                        $response['result'] = 1;
                    }else{
                        $response['message'] = 'Somthing is wrong';
                        $response['result'] = 1;
                    }
                }*/
					$this->loadModel('TestStart');
					$check = $this->TestStart->find('first', array('conditions' => array('TestStart.office_id' => $data_arrays['office_id'], 'TestStart.device_id' => $data_arrays['device_id']), 'fildes' => array('status')));
					if (empty($check)) {
						$response['teststatus'] = 99;
					} else {
						$response['teststatus'] = $check['TestStart']['status'];
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	public function get_process_device_message()
	{
		$this->autoRender = false;
		$response = array();
		$data_arrays = $_POST;
		$device = $this->DeviceMessage->find('all', array('conditions' => array('DeviceMessage.office_id' => $data_arrays['office_id'], 'DeviceMessage.device_id' => $data_arrays['device_id']), 'order' => array('DeviceMessage.id' => 'ASC')));
		if (empty($device)) {
			$response['message'] = 'No data';
		} else {
			$response['message'] = $device;
		}
		echo json_encode($response);
		exit();
	}
	public function get_process_device_message_stop()
	{
		$this->autoRender = false;
		$response = array();
		$data_arrays = $_POST;
		$device = $this->DeviceMessage->find('all', array('conditions' => array('DeviceMessage.office_id' => $data_arrays['office_id'], 'DeviceMessage.device_id' => $data_arrays['device_id'], 'DeviceMessage.message LIKE ' => '%BTN_PRESS%'), 'order' => array('DeviceMessage.id' => 'ASC')));
		if (empty($device)) {
			$response['message'] = 'No data';
		} else {
			$response['message'] = $device;
			$this->DeviceMessage->delete($device[0]['DeviceMessage']['id']);
		}
		echo json_encode($response);
		exit();
	}
	/* API for update test device current status test device update_device_status */
	public function update_device_status($device_id = 0, $status = 0)
	{
		if ($this->check_key()) {
			$this->layout = false;
			$data_arrays = $_POST;
			if (empty($data_arrays)) {
				$data_arrays = json_decode(file_get_contents('php://input'), true);
			}
			$this->autoRender = false;
			$response = array();
			if (isset($data_arrays['device_id']) && (isset($data_arrays['status']))) {
				$result = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $data_arrays['device_id'])));
				$result['TestDevice']['current_status'] = $data_arrays['status'];
				$result['TestDevice']['current_status_updated_at'] = date("Y-m-d H:i:s");
				if ($this->TestDevice->save($result)) {
					$response['message'] = 'device updated successfully.';
					$response['result'] = 1;
				} else {
					$response['message'] = 'Something wrong.';
					$response['result'] = 0;
				}
			} else {
				$response['message'] = 'Please send device id.';
				$response['result'] = 0;
			}
			echo json_encode($response);
			exit();
		}
	}
	/*API for check device  mac address */
	public function check_mac_address()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (((!empty($data_arrays['office_id']))) && (!empty($data_arrays['mac_address']))) {
					$mac_address = $this->TestDevice->find('first', array('conditions' => array('TestDevice.mac_address' => $data_arrays['mac_address'], 'TestDevice.office_id' => $data_arrays['office_id'])));
					if (empty($mac_address)) {
						$response['message'] = 'Device MAC Address not found';
						$response['result'] = 1;
						$response['status'] = false;
					} else {
						$response['message'] = 'Device MAC Address found.';
						$response['result'] = 1;
						$response['status'] = true;
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	public function check_mac_address_v1()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = $_POST;
				if (((!empty($data_arrays['office_id']))) && (!empty($data_arrays['mac_address']))) {
					// if (1) {
					$mac_address = $this->TestDevice->find('first', array('conditions' => array('TestDevice.mac_address' => $data_arrays['mac_address'], 'TestDevice.office_id' => $data_arrays['office_id'])));
					if (empty($mac_address)) {
						$response['message'] = 'Device MAC Address not found';
						$response['result'] = 1;
						$response['status'] = false;
					} else {
						$response['message'] = 'Device MAC Address found.';
						$response['result'] = 1;
						$response['status'] = true;
						$response['deviceId'] = $mac_address['TestDevice']['id'];
						$response['device_type'] = $mac_address['TestDevice']['device_type'];
					}
				} else {
					$response['message'] = 'Some fields empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	/*check version */
	public function check_version()
	{
		$this->layout = false;
		if ($this->validatePostRequest()) {
			$this->autoRender = false;
			$response = array();
			$data_arrays = $_POST;
			CakeLog::write('info', json_encode($data_arrays));
			$data = $this->Apk->find('first', array('conditions' => array('Apk.device_type' => $data_arrays['device_type'])));
			//print_r($data);die;
			if (empty($data['Apk'])) {
				$response['message'] = 'Apk not found';
				$response['result'] = 0;
				$response['status'] = false;
			} else {
				if ($this->chek_valid_version($data_arrays['version'], $data['Apk']['minimum_version'])) {
					$response['message'] = 'Please Update your App';
					$response['result'] = 0;
					$response['status'] = false;
					$response['app_name'] = '/' . $data['Apk']['apk'];
					$response['download_link'] = WWW_BASE . 'files/apk/uploads/' . $data['Apk']['apk'];
					$response['link'] = WWW_BASE . 'apk/download/' . base_convert($data['Apk']['id'], 10, 36);
				} else if ($this->chek_valid_version($data_arrays['version'], $data['Apk']['version'])) {
					$response['message'] = 'Update avlaible';
					$response['result'] = 2;
					$response['status'] = false;
					$response['app_name'] = '/' . $data['Apk']['apk'];
					$response['download_link'] = WWW_BASE . 'files/apk/uploads/' . $data['Apk']['apk'];
					$response['link'] = WWW_BASE . 'apk/download/' . base_convert($data['Apk']['id'], 10, 36);
				} else {
					$response['message'] = 'Apk Uptodated';
					$response['result'] = 1;
					$response['status'] = false;
				}
			}
			echo json_encode($response);
			exit();
		}
	}
	/*check version */
	/*check version fro Raw data*/
	public function check_version_V3()
	{
		$this->layout = false;
		if ($this->validatePostRequest()) {
			$this->autoRender = false;
			$response = array();
			$data_arrays = file_get_contents('php://input'); 
			$data_arrays=json_decode($data_arrays);
			CakeLog::write('info', json_encode($data_arrays));
			$data = $this->Apk->find('first', array('conditions' => array('Apk.device_type' => $data_arrays->device_type)));
			//print_r($data);die;
			if (empty($data['Apk'])) {
				$response['message'] = 'Apk not found';
				$response['result'] = 0;
				$response['status'] = false;
			} else {
				if ($this->chek_valid_version($data_arrays->version, $data['Apk']['minimum_version'])) {
					$response['message'] = 'Please Update your App';
					$response['result'] = 0;
					$response['status'] = false;
					$response['app_name'] = '/' . $data['Apk']['apk'];
					$response['download_link'] = WWW_BASE . 'files/apk/uploads/' . $data['Apk']['apk'];
					$response['link'] = WWW_BASE . 'apk/download/' . base_convert($data['Apk']['id'], 10, 36);
				} else if ($this->chek_valid_version($data_arrays->version, $data['Apk']['version'])) {
					$response['message'] = 'Update avlaible';
					$response['result'] = 2;
					$response['status'] = false;
					$response['app_name'] = '/' . $data['Apk']['apk'];
					$response['download_link'] = WWW_BASE . 'files/apk/uploads/' . $data['Apk']['apk'];
					$response['link'] = WWW_BASE . 'apk/download/' . base_convert($data['Apk']['id'], 10, 36);
				} else {
					$response['message'] = 'Apk Uptodated';
					$response['result'] = 1;
					$response['status'] = false;
				}
			}
			echo json_encode($response);
			exit();
		}
	}
	/*check version */
	public function check_version_V4()
	{
		$this->layout = false;
		if ($this->validatePostRequest()) {
			$this->autoRender = false;
			$response = array();
			$data_arrays = $_POST;
			CakeLog::write('info', json_encode($data_arrays));
			$office_alpha_status = $this->Office->find('first', array('conditions' => array('Office.id' => $data_arrays['office_id'])));
			if($office_alpha_status['Office']['alphaapktype'] == 1){
				$data = $this->Apk->find('first', array('conditions' => array('Apk.device_type' => $data_arrays['device_type'],'Apk.apk_type' => 1)));
			}else{
				$data = $this->Apk->find('first', array('conditions' => array('Apk.device_type' => $data_arrays['device_type'],'Apk.apk_type' => 2)));
			}
			$data2 = $this->Apk->find('first', array('conditions' => array('Apk.device_type' => $data_arrays['device_type'])));
			if (empty($data2['Apk'])) {
				$response['message'] = 'Apk not found';
				$response['result'] = 0;
				$response['status'] = false;
			}else if (empty($data['Apk'])) {
				$response['message'] = 'Production Apk not found';
				$response['result'] = 1;
				$response['status'] = false;
			} else {
				if ($this->chek_valid_version($data_arrays['version'], $data['Apk']['minimum_version'])) {
					$response['message'] = 'Please Update your App';
					$response['result'] = 0;
					$response['status'] = false;
					$response['app_name'] = '/' . $data['Apk']['apk'];
					$response['download_link'] = WWW_BASE . 'files/apk/uploads/' . $data['Apk']['apk'];
					$response['link'] = WWW_BASE . 'apk/download/' . base_convert($data['Apk']['id'], 10, 36);
				} else if ($this->chek_valid_version($data_arrays['version'], $data['Apk']['version'])) {
					$response['message'] = 'Update avlaible';
					$response['result'] = 2;
					$response['status'] = false;
					$response['app_name'] = '/' . $data['Apk']['apk'];
					$response['download_link'] = WWW_BASE . 'files/apk/uploads/' . $data['Apk']['apk'];
					$response['link'] = WWW_BASE . 'apk/download/' . base_convert($data['Apk']['id'], 10, 36);
				} else {
					$response['message'] = 'Apk Uptodated';
					$response['result'] = 1;
					$response['status'] = false;
				}
			}
			echo json_encode($response);
			exit();
		}
	}
	public function check_version2()
	{
		$this->layout = false;
		if ($this->validatePostRequest()) {
			$this->autoRender = false;
			$response = array();
			$data_arrays = $_POST;
			CakeLog::write('info', json_encode($data_arrays));
			$data = $this->Apk->find('first', array('conditions' => array('Apk.device_type' => $data_arrays['device_type'],'Apk.apk_type' => 2)));
			$data2 = $this->Apk->find('first', array('conditions' => array('Apk.device_type' => $data_arrays['device_type'])));
			if (empty($data2['Apk'])) {
				$response['message'] = 'Apk not found';
				$response['result'] = 0;
				$response['status'] = false;
			}else if (empty($data['Apk'])) {
				$response['message'] = 'Production Apk not found';
				$response['result'] = 1;
				$response['status'] = false;
			} else {
				if ($this->chek_valid_version($data_arrays['version'], $data['Apk']['minimum_version'])) {
					$response['message'] = 'Please Update your App';
					$response['result'] = 0;
					$response['status'] = false;
					$response['app_name'] = '/' . $data['Apk']['apk'];
					$response['download_link'] = WWW_BASE . 'files/apk/uploads/' . $data['Apk']['apk'];
					$response['link'] = WWW_BASE . 'apk/download/' . base_convert($data['Apk']['id'], 10, 36);
				} else if ($this->chek_valid_version($data_arrays['version'], $data['Apk']['version'])) {
					$response['message'] = 'Update avlaible';
					$response['result'] = 2;
					$response['status'] = false;
					$response['app_name'] = '/' . $data['Apk']['apk'];
					$response['download_link'] = WWW_BASE . 'files/apk/uploads/' . $data['Apk']['apk'];
					$response['link'] = WWW_BASE . 'apk/download/' . base_convert($data['Apk']['id'], 10, 36);
				} else {
					$response['message'] = 'Apk Uptodated';
					$response['result'] = 1;
					$response['status'] = false;
				}
			}
			echo json_encode($response);
			exit();
		}
	}
	public function chek_valid_version($ver1, $ver2)
	{
		$v1 = explode(".", $ver1);
		$v2 = explode(".", $ver2);
		if (!isset($v2[0])) {
			$v2[0] = 0;
		}
		if (!isset($v2[1])) {
			$v2[1] = 0;
		}
		if (!isset($v2[2])) {
			$v2[2] = 0;
		}
		if (!isset($v1[0])) {
			$v1[0] = 0;
		}
		if (!isset($v1[1])) {
			$v1[1] = 0;
		}
		if (!isset($v1[2])) {
			$v1[2] = 0;
		}
		if($v1[0] > $v2[0]){
			return false;
		}else if ($v1[0] >= $v2[0]) {
			if ($v1[1] > $v2[1]) {
				return false;
			}else if ($v1[1] >= $v2[1]) {
				if ($v1[2] >= $v2[2]) {
					return false;
				} else {
					return true;
				}
			} else {
				return true;
			}
		} else {
			return true;
		}
	}
	public function detailed_progression_point_data()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$response = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$data_arrays['test_report_id'] = 1;
				CakeLog::write('info', "detailed_progression_point_data start");
				CakeLog::write('info', json_encode($data_arrays));
				CakeLog::write('info', "detailed_progression_point_data end");
				// pr($data_arrays);die;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$pid = $pid . "dp";
						$foldername = "pointData";
						$foldername2 = "pointData2";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
						$this->base64_to_pdf2($imgstring, $foldername2, $pid);
					}
					$data['Pointdata']['test_id'] = isset($data_arrays['test_id']) ? $data_arrays['test_id'] : '';
					$data['Pointdata']['numpoints'] = isset($data_arrays['numpoints']) ? $data_arrays['numpoints'] : 0;
					$data['Pointdata']['color'] = isset($data_arrays['color']) ? $data_arrays['color'] : '';
					$data['Pointdata']['backgroundcolor'] = isset($data_arrays['backgroundcolor']) ? $data_arrays['backgroundcolor'] : '';
					$data['Pointdata']['stmsize'] = isset($data_arrays['stmSize']) ? $data_arrays['stmSize'] : 0;
					$data['Pointdata']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['Pointdata']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['Pointdata']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['Pointdata']['master_key'] = isset($data_arrays['master_key']) ? $data_arrays['master_key'] : 0;
					$data['Pointdata']['eye_select'] = isset($data_arrays['eye_select']) ? $data_arrays['eye_select'] : 0;
					$data['Pointdata']['test_type_id'] = isset($data_arrays['test_type_id']) ? $data_arrays['test_type_id'] : '';
					$data['Pointdata']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : '';
					$data['Pointdata']['test_name'] = "Detailed_Progression_" . $data['Pointdata']['test_name'];
					$data['Pointdata']['mean_dev'] = @$data_arrays['mean_dev'];
					$data['Pointdata']['pattern_std'] = @$data_arrays['pattern_std'];
					$data['Pointdata']['mean_sen'] = @$data_arrays['mean_sen'];
					$data['Pointdata']['mean_def'] = @$data_arrays['mean_def'];
					$data['Pointdata']['pattern_std_hfa'] = @$data_arrays['pattern_std_hfa'];
					$data['Pointdata']['loss_var'] = @$data_arrays['loss_var'];
					$data['Pointdata']['mean_std'] = @$data_arrays['mean_std'];
					$data['Pointdata']['psd_hfa_2'] = @$data_arrays['psd_hfa_2'];
					$data['Pointdata']['psd_hfa'] = @$data_arrays['psd_hfa'];
					$data['Pointdata']['vission_loss'] = @$data_arrays['vission_loss'];
					$data['Pointdata']['false_p'] = @$data_arrays['false_p'];
					$data['Pointdata']['false_n'] = @$data_arrays['false_n'];
					$data['Pointdata']['false_f'] = @$data_arrays['false_f'];
					$data['Pointdata']['ght'] = @$data_arrays['ght'];
					$data['Pointdata']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['Pointdata']['threshold'] = @$data_arrays['threshold'];
					$data['Pointdata']['strategy'] = @$data_arrays['strategy'];
					$data['Pointdata']['test_color_fg'] = $data_arrays['test_color_fg'];
					$data['Pointdata']['test_color_bg'] = $data_arrays['test_color_bg'];
					$data['Pointdata']['latitude'] = @$data_arrays['latitude'];
					$data['Pointdata']['longitude'] = @$data_arrays['longitude'];
					$data['Pointdata']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['Pointdata']['version'] = @$data_arrays['version'];
					$data['Pointdata']['diagnosys'] = @$data_arrays['diagnosys'];
					// $count_baseline = $this->Pointdata->find('count', array(
					//     'conditions' => array(
					//         'test_name' => $data['Pointdata']['test_name'],
					//         'eye_select' => $data['Pointdata']['eye_select'], 'patient_id' => $data['Pointdata']['patient_id'], 'Pointdata.baseline' => '1'
					//     )
					// ));
					// if ($count_baseline < 2) {
					//     $data['Pointdata']['baseline'] = 1;
					// }
					$data['Pointdata']['baseline'] = (isset($data_arrays['baseline']) && !empty($data_arrays['baseline'])) ? $data_arrays['baseline'] : 0;
					//pr($count_baseline);die;
					//pr($data); die;
					$result = $this->Pointdata->save($data);
					$lastId = $this->Pointdata->id;
					$lastFile = $this->Pointdata->file;
					if ($result) {
						if (!empty($data_arrays['file'])) {
							$response['pdf'] = WWW_BASE . 'pointData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						$pdata = "";
						if (!empty($data_arrays['vfpointdata'])) {
							foreach ($data_arrays['vfpointdata'] as $pdatas) {
								$pdata['VfPointdata']['report_id'] = @$data_arrays['test_report_id'];
								$pdata['VfPointdata']['point_data_id'] = @$lastId;
								$pdata['VfPointdata']['x'] = isset($pdatas['x']) ? $pdatas['x'] : '';
								$pdata['VfPointdata']['y'] = isset($pdatas['y']) ? $pdatas['y'] : '';
								$pdata['VfPointdata']['intensity'] = isset($pdatas['intensity']) ? $pdatas['intensity'] : '';;
								$pdata['VfPointdata']['size'] = isset($pdatas['size']) ? $pdatas['size'] : '';
								$pdata['VfPointdata']['zPD'] = isset($pdatas['zPD']) ? $pdatas['zPD'] : '';
								$pdata['VfPointdata']['STD'] = isset($pdatas['STD']) ? $pdatas['STD'] : '';
								$pdata['VfPointdata']['index'] = isset($pdatas['index']) ? $pdatas['index'] : '';
								$pdata['VfPointdata']['created'] = (!empty($pdatas['created_date'])) ? date('Y-m-d H:i:s', strtotime($pdatas['created_date'])) : date('Y-m-d H:i:s');
								$this->VfPointdata->create();
								$result_p = $this->VfPointdata->save($pdata);
							}
						}
						$response['message'] = 'Success.';
						$response['result'] = 1;
						//update credits------
						$this->loadModel('User');
						$this->User->id = $data['Pointdata']['staff_id'];
						$credits = $this->User->field('credits');
						$new_credit = $credits - 1;
						$this->User->updateAll(array('User.credits' => $new_credit), array('User.id' => $data['Pointdata']['staff_id']));
					} else {
						$response['message'] = 'Some error occured in updating report.';
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				echo json_encode($response);
				exit();
			}
		}
	}
	//This API for ofice video listing of patient.
	public function video_listing()
	{
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				}
				$this->Video->virtualFields['download'] = "CONCAT('" . WWW_BASE . "files/video/uploads', '/', Video.video)";;
				if (isset($input_data['office_id'])) {
					$conditions = array();
					if (isset($input_data['search'])) {
						$conditions[] = array('Video.name like' => '%' . $input_data['search'] . '%');
					}
					$conditions[] = array('Video.office_id' => $input_data['office_id']);
					$datas = $this->Video->find('all', array('conditions' => $conditions));
					$datas = array_column($datas, 'Video');
					$response_array = array('message' => 'video listing for patinet.', 'status' => 1, 'data' => $datas);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				} else {
					$response_array = array('message' => 'Please send valid office id.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
	//This API for patient video listing.
	public function patient_video_listing()
	{
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				}
				$this->Video->virtualFields['download'] = "CONCAT('" . WWW_BASE . "files/video/uploads', '/', Video.video)";
				$this->Video->virtualFields['viewes'] = 0;
				$this->Video->bindModel(
					array(
						'hasOne' => array(
							'PatientVideoView' => array(
								'className' => 'PatientVideoView',
								'foreignKey' => 'video_id',
								'conditions' => array('PatientVideoView.patient_id' => $input_data['patient_id'])
							)
						)
					)
				);
				if (isset($input_data['office_id'])) {
					$conditions = array();
					if (isset($input_data['search'])) {
						$conditions[] = array('Video.name like' => '%' . $input_data['search'] . '%');
					}
					$conditions[] = array('Video.office_id' => $input_data['office_id']);
					$datas = $this->Video->find('all', array('conditions' => $conditions));
					foreach ($datas as $key => $value) {
						$value['Video']['viewes'] = $value['PatientVideoView'];
						$datas_new[] = $value['Video'];
					}
					// $datas = array_column($datas,'Video');
					$response_array = array('message' => 'video details', 'status' => 1, 'data' => $datas_new);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				} else {
					$response_array = array('message' => 'Please send valid office id.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
	//This API for patient video details of staff.
	public function video_details()
	{
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				}
				$this->Video->virtualFields['download'] = "CONCAT('" . WWW_BASE . "files/video/uploads', '/', Video.video)";
				$this->Video->virtualFields['viewes'] = 0;
				$this->Video->bindModel(
					array(
						'hasOne' => array(
							'PatientVideoView' => array(
								'className' => 'PatientVideoView',
								'foreignKey' => 'video_id',
								'conditions' => array('PatientVideoView.patient_id' => $input_data['patient_id'])
							)
						)
					)
				);
				if (isset($input_data['video_id'])) {
					$conditions = array();
					$conditions[] = array('Video.id' => $input_data['video_id']);
					$datas = $this->Video->find('first', array('conditions' => $conditions));
					$datas['Video']['viewes'] = $datas['PatientVideoView'];
					$datas = $datas['Video'];
					$response_array = array('message' => 'video details', 'status' => 1, 'data' => $datas);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				} else {
					$response_array = array('message' => 'Please send valid video id.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
	//This API for update patient video viewed status of staff.
	public function video_viewed_status_update()
	{
		if ($this->check_key()) {
			$save_data = array();
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				}
				if (isset($input_data['video_id']) && isset($input_data['patient_id'])) {
					$patient_viewed = $this->PatientVideoViews->find('first', array('conditions' => array('PatientVideoViews.video_id' => $input_data['video_id'], 'PatientVideoViews.patient_id' => $input_data['patient_id'])));
					if (empty($patient_viewed)) {
						$data['PatientVideoViews']['video_id'] = $input_data['video_id'];
						$data['PatientVideoViews']['patient_id'] = $input_data['patient_id'];
						$data['type']['patient_id'] = 'C';
						$this->PatientVideoViews->save($data);
						$response_array = array('message' => 'video viewed status saved', 'status' => 1);
					} else {
						$response_array = array('message' => 'video allready viewed', 'status' => 1);
					}
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				} else {
					$response_array = array('message' => 'Please send valid video id.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}




























/*Add single patient with UTC time 22-11-2022 by Madan*/
	public function addPatient_v6_v1()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				}
				/*$chekc_ID_number = $this->Patients->find('first',array('conditions'=>array('Patients.id_number'=>$input_data['id_number']),'fields'=>array('Patients.id')));
					if(!empty($chekc_ID_number)){
						$response_array = array('message'=>'Patient already exists with provided Id number.','status'=>0);
						header('Content-Type: application/json');
						echo json_encode($response_array);die;
					}
				 */
				@$input_data['od_left'] = isset($input_data['od_left']) ? $input_data['od_left'] : '';
				@$input_data['od_right'] = isset($input_data['od_right']) ? $input_data['od_right'] : '';
				@$input_data['os_left'] = isset($input_data['os_left']) ? $input_data['os_left'] : '';
				@$input_data['os_right'] = isset($input_data['os_right']) ? $input_data['os_right'] : '';
				@$input_data['unique_id'] = isset($input_data['unique_id']) ? $input_data['unique_id'] : null;
				$input_data['first_name'] = preg_replace('/[^A-Za-z0-9\-]/', '_', $input_data['first_name']);
				$input_data['middle_name'] = preg_replace('/[^A-Za-z0-9\-]/', '_', $input_data['middle_name']);
				$input_data['last_name'] = preg_replace('/[^A-Za-z0-9\-]/', '_', $input_data['last_name']);
				$input_data['first_name'] = str_replace('-', '_', $input_data['first_name']);
				$input_data['middle_name'] = str_replace('-', '_', $input_data['middle_name']);
				$input_data['last_name'] = str_replace("-", "_", $input_data['last_name']);
				$input_data['gender'] = isset($input_data['gender']) ? $input_data['gender'] : '';
	            date_default_timezone_set('UTC');
            	$UTCDate = date('Y-m-d H:i:s');
				$save_data = array(
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
					'created' => @$input_data['created'],
					'created_date_utc' => @$input_data['created_date_utc'],
					'created_at_for_archive' => @$UTCDate,
					'gender' => $input_data['gender']
				);
				//pr($save_data); die;
				if (isset($input_data['first_name']) && !isset($input_data['patient_id'])) {
					$user_office = $this->User->find('first', array('conditions' => array('User.id' => @$input_data['user_id']), 'fields' => array('User.office_id')));
					$save_data['office_id'] = $user_office['User']['office_id'];
					$this->Patientsnew->table = 'patients';
					$this->Patientsnew->useTable = 'patients';
					$this->Patientsnew->validate = array(
						'unique_id' => array(
							'notBlank' => array(
								'rule' => 'notBlank',
								'message' => 'Please enter Id number.'
							),
							'unique' => array(
								'rule' => 'isUnique',
								'message' => 'Please enter another unique id it is already taken.'
							),
						)
					);
					$this->Patientsnew->set($save_data);
					if ($this->Patientsnew->validates()) {
 						//date_default_timezone_set("America/Los_Angeles");
						//$save_data['created'] = date('Y-m-d H:i:s');
						//$save_data['created'] = $input_data['created'];
	                    //$save_data['created_at_for_archive']= date('Y-m-d H:i:s');
	                    /*date_default_timezone_set('UTC');
            			$UTCDate = date('Y-m-d H:i:s');*/
            			//$save_data['created_date_utc']= $input_data['created_date_utc'];

						$result = $this->Patients->save($save_data);
						if (count($result['Patients'])) {

							if(isset($input_data['diagnosis'])){
								foreach($input_data['diagnosis'] as $value){
            						$diagnosis=array();
            						$diagnosis['patient_id'] = $result['Patients']['id'];
            						$diagnosis['diagnosis_id'] = $value;
            						$diagnosis_data[] = $diagnosis;
            						$this->PatientDiagnosis->create();
									$this->PatientDiagnosis->save($pdata);

            					}

            				}
							$result['Patients']['patient_id'] = $result['Patients']['id'];
							unset($result['Patients']['id']);
							$response_array = array('message' => 'Patients Added successfully.', 'status' => 1, 'data' => $result['Patients']);
							header('Content-Type: application/json');
							echo json_encode($response_array);
							die;
						} else {
							$response_array = array('message' => 'Some problems occured during process. Please try again.', 'status' => 0);
							header('Content-Type: application/json');
							echo json_encode($response_array);
							die;
						}
					} else {
						$response_array = array('message' => 'Unique id allready taken.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				} else {
					$user_office = $this->User->find('first', array('conditions' => array('User.id' => @$input_data['user_id']), 'fields' => array('User.office_id')));
					$save_data['office_id'] = $user_office['User']['office_id'];
					$save_data['id'] = $input_data['patient_id'];
					$save_data['created'] = (!empty($input_data['created_date'])) ? date('Y-m-d H:i:s', strtotime($input_data['created_date'])) : date('Y-m-d H:i:s');
					$result = $this->Patients->save($save_data);
					if ($result) {
						$result['Patients']['patient_id'] = $result['Patients']['id'];
						unset($result['Patients']['id']);
						$response_array = array('message' => 'Patients Updated successfully.', 'status' => 1, 'data' => $result['Patients']);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} else {
						$response_array = array('message' => 'Some problems occured during process. Please try again.', 'status' => 0);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				}
			}
		}
	}
	/*Add single patient with UTC time 22-11-2022 by Madan*/





	/*Add patients by Utc time 22-11-2022 by Madan*/
	 public function addMultiplePatients_v6_v1()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$input_data = json_decode(file_get_contents("php://input"), true);
				$save_data = $saved_data = array();
				if (!empty($input_data['data']) && isset($input_data['user_id']) && !empty($input_data['user_id']) && isset($input_data['office_id']) && !empty($input_data['user_id'])) {		
					
					$i = 0;
					foreach ($input_data['data'] as $data) { 
						$patients = $data; 
						if (!empty($data['patient_id'])) {
							$patients['id'] = $patients['patient_id'];
						} 
						if($patients['unique_id']){
							$patients['unique_id'] = $data['unique_id'];
						}
						//date_default_timezone_set("America/Los_Angeles");
						$patients['race'] = trim(@$data['race']);
						$patients['office_id'] = @$input_data['office_id'];
						$patients['user_id'] = @$input_data['user_id'];
						$patients['created_date_utc'] = @$data['created_date_utc'];
						$patients['gender'] = @$data['gender'];
						$patients['created'] = @$data['created'];
						//date_default_timezone_set('UTC');
            			//$UTCDate = date('Y-m-d H:i:s');
            			//$patients['created_at_for_archive'] = @$UTCDate;
						//$patients['created'] = (isset($data['created_date']) && !empty($data['created_date'])) ? date('Y-m-d H:i:s', strtotime($data['created_date'])) : date('Y-m-d H:i:s');
						$this->Patientsnew->table = 'patients';
						$this->Patientsnew->useTable = 'patients';
						$this->Patientsnew->validate = array(
							'unique_id' => array(
								'notBlank' => array(
									'rule' => 'notBlank',
									'message' => 'Please enter Id number.'
								),
								'unique' => array(
									'rule' => 'isUnique',
									'message' => 'Please enter another unique id it is already taken.'
								),
							)
						);
						$this->Patientsnew->set($patients);
						if ($this->Patientsnew->validates()) {
							/*$rs = $this->Patients->save($patients);
							if ($rs) {
								$saved_data[$i]['id'] = $this->Patients->id;
								//$saved_data[$i]['unique_id'] = $this->Patients->unique_id;
								$saved_data[$i]['unique_id'] = $data['unique_id'];
							} */
						}
						if(!empty($data['unique_id'])){
							$result =$this->Patients->find('all', array('conditions' => array('Patients.unique_id' => $data['unique_id']), 'fields' => array('Patients.id')));
							if(empty($result)){
								$rs = $this->Patients->save($patients);
								if ($rs) {
									$countIds =$this->Patients->find('all', array('conditions' => array('Patients.unique_id' => $data['unique_id']), 'fields' => array('Patients.id')));


									if(isset($input_data['diagnosis'])){
										foreach($input_data['diagnosis'] as $value){
		            						$diagnosis=array();
		            						$diagnosis['patient_id'] =  $this->Patients->id;
		            						$diagnosis['diagnosis_id'] = $value;
		            						$diagnosis_data[] = $diagnosis;
		            						$this->PatientDiagnosis->create();
											$this->PatientDiagnosis->save($pdata);

		            					}

		            				}
									if(count($countIds) > 1){
										foreach($countIds as $key=>$countId){
											if($key !== 0){ 
												$this->Patients->delete($countId['Patients']['id']);
											}else{
												$patientsId = $countId['Patients']['id'];
											}
										}
									}
									if(empty($patientsIds)){

											$saved_data[$i]['id'] = $this->Patients->id;
											$saved_data[$i]['unique_id'] = $data['unique_id'];
									}else{
										$saved_data[$i]['id'] = $patientsIds;
										$saved_data[$i]['unique_id'] = $data['unique_id'];
									}
									//$saved_data[$i]['unique_id'] = $this->Patients->unique_id;
								}
							}else{

							}
							foreach($result as $key=>$val){
								
								/*$this->Patients->save($patients);
									$this->Patients->delete($age_group_data['Masterdata']['id']);*/
								$alreadyinsertid[$i]['id'] = $val['Patients']['id'];
								$alreadyinsertid[$i]['unique_id'] = $data['unique_id'];
							}
						}
						$i++;
					}

					foreach($saved_data as $key => $value){
					  	$saved_datas[] = $value;
					}
					if (!empty($saved_data)) {

						$response_array = array('message' => 'Patients Saved successfully.', 'status' => 1, 'data' => $saved_datas);
					}else if(empty($saved_data)){
						$response_array = array('message' => 'Already patients added.', 'status' => 1, 'data' => $alreadyinsertid);
					} else {
						$response_array = array('message' => 'Error in adding patients.', 'status' => 0, 'data' => $saved_datas);
					}
				} else {
					$response_array = array('message' => 'Please send all parameters.', 'status' => 0);
				}
				echo json_encode($response_array);
				die;
			}
		}
	}
		



/*GEt all patients with UTC time by Madan 22-11-2022*/
	public function unity_listPatients_v8()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$data_arrays = $_POST;
				$last_sync_time = '';
				$last_sync_time_new = '';
				if (isset($data_arrays['page'])) {
					if ($data_arrays['page'] == 0) {
						$limit = '';
						$start = 0;
					} elseif ($data_arrays['page'] == 1) {
						$limit = $data_arrays['page'] * 25 + 1;
						$start = 0;
						$end = $data_arrays['page'] * 25 - 1;
					} else {
						$limit = $data_arrays['page'] * 25 + 1;
						$start = ($data_arrays['page'] - 1) * 25;
						$end = $data_arrays['page'] * 25 - 1;
					}
				} else {
					$limit = '';
					$start = 0;
				} 
				if (isset($data_arrays['staff_id'])) {
					$staff_id = $data_arrays['staff_id'];
					$office_id = $this->User->find('first', array('conditions' => array('User.id' => $staff_id), 'fields' => array('User.office_id')));
					$office_archive_status = $this->Office->find('first', array('conditions' => array('Office.id' => $office_id['User']['office_id']), 'fields' => array('Office.archive_status')));
					if (!empty($office_id)) {
						if($office_archive_status['Office']['archive_status'] == 1){
							$patient_staus = isset($data_arrays['showActiveOnly']) ? $data_arrays['showActiveOnly'] : array(1, 2);
							$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
							$all_staff_ids = implode(",", $all_staff_ids);
							$all_staff_ids = explode(',', $all_staff_ids); 



							$this->Patients->bindModel(array(
								'hasMany'=>array(
									'PatientDiagnosis'=>array(
										'foreignKey'=>'patient_id',
										'fields' => array('diagnosis_id'),
									)
								)
							)); 

							if (isset($data_arrays['last_sync_time_new']) && $data_arrays['last_sync_time_new']!="" ) {
								$condition['Patients.created_date_utc >'] = date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition['Patients.user_id'] = $all_staff_ids;
								$condition['Patients.is_delete'] = 0;
								$condition['Patients.merge_status'] = 0;
								$condition['Patients.status'] = $patient_staus;  
								$condition_deleted['OR'][]['Patients.delete_date >']= date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new'])); 
								$condition_deleted['OR'][]['Patients.archived_date >']=date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition_deleted['OR'][]['Patients.merge_date >']=date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition_deleted['Patients.user_id']= $all_staff_ids;
								$update_date = date('Y-m-d h:i:s', strtotime($data_arrays['last_sync_time_new']));
								$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*'										
										),
										'conditions' => $condition,
										'recursive' => 1,
										'order' => array('Patients.id DESC'),
										'group' => array('Patients.id'),
										'limit' => $limit)
								);
							} else {
								$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*',
										),
										'conditions' => array('Patients.user_id' => $all_staff_ids, 'Patients.merge_status' => 0,
										'Patients.status' => $patient_staus),
										'order' => array('Patients.id DESC'),
										'group' => array('Patients.id'),
										'recursive' => 1,
										'limit' => $limit)
								); 
								$condition_deleted['Patients.user_id']= $all_staff_ids;
								$condition_deleted['OR'][]['Patients.is_delete'] = 1;
								$condition_deleted['OR'][]['Patients.merge_status'] = 1;
								$condition_deleted['OR'][]['Patients.archived_date NOT'] = "";
							}
						}else{
							$patient_staus = 1;
							$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
							$all_staff_ids = implode(",", $all_staff_ids);
							$all_staff_ids = explode(',', $all_staff_ids); 
							$this->Patients->virtualFields['patient_report_status'] = "IF(Pointdatas.id>0, 1,0)";
							if (isset($data_arrays['last_sync_time_new']) && $data_arrays['last_sync_time_new']!="" ) {
								$condition['Patients.created_date_utc >'] = date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition['Patients.user_id'] = $all_staff_ids;
								$condition['Patients.is_delete'] = 0;
								$condition['Patients.merge_status'] = 0;
								$condition_deleted['OR'][]['Patients.delete_date >']= date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new'])); 
								$condition_deleted['OR'][]['Patients.archived_date >']=date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition_deleted['OR'][]['Patients.merge_date >']=date('Y-m-d H:i:s', strtotime($data_arrays['last_sync_time_new']));
								$condition_deleted['Patients.user_id']= $all_staff_ids;
								$update_date = date('Y-m-d h:i:s', strtotime($data_arrays['last_sync_time_new']));
								$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*', 
										),
										'joins' => array(
											array(
												'table' => 'mmd_pointdatas',
												'alias' => 'Pointdatas',
												'type' => 'LEFT',
												'foreignKey' => false,
												'conditions' => array(
													'Pointdatas.patient_id = Patients.id',
												)
											)
										),
										'conditions' => $condition,
										'recursive' => 1,
										'order' => array('Patients.id DESC'),
										'group' => array('Patients.id'),
										'limit' => $limit)
								);
							} else {
								$result = $this->Patients->find('all',
									array(
										'fields' => array(
											'Patients.*', 
										),
										'joins' => array(
											array(
												'table' => 'mmd_pointdatas',
												'alias' => 'Pointdatas',
												'type' => 'LEFT',
												'foreignKey' => false,
												'conditions' => array(
													'Pointdatas.patient_id = Patients.id',
												)
											)
										),
										'conditions' => array('Patients.user_id' => $all_staff_ids, 'Patients.merge_status' => 0),
										'order' => array('Patients.id DESC'),
										'recursive' => 1,
										'group' => array('Patients.id'),
										'limit' => $limit)
								);
								$condition_deleted['Patients.user_id']= $all_staff_ids;
								$condition_deleted['Patients.is_delete'] = 1;
								$condition_deleted['Patients.merge_status'] = 1;
							}
						}
					} else {
						$response_array['message'] = 'Invalid staff.';
						$response_array['result'] = 0;
						echo json_encode($response_array);
						die;
					}
				} else {
					$response_array = array('message' => 'Please send staff id.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
				$all_result_count = count($result);
				if (isset($data_arrays['page']) && ($data_arrays['page'] == 0)) {
					$end = $all_result_count;
				}
				if (!isset($data_arrays['page'])) {
					$end = $all_result_count;
				}
				if (isset($data_arrays['page'])) {
					if ($data_arrays['page'] != 0) {
						if (($all_result_count > $data_arrays['page'] * 25)) {
							$more_data = 1;
						} else {
							$more_data = 0;
						}
					} else {
						$more_data = 0;
					}
				} else {
					$more_data = 0;
				}
				
				if (count($result)) {
					$data = array();
					$delete_data = array();
					foreach ($result as $key => $value) {
						if ($key >= $start && $key <= $end) {
							$value['Patients']['patient_id'] = $value['Patients']['id'];
							$value['Patients']['middle_name'] = ($value['Patients']['middle_name'] != null) ? ($value['Patients']['middle_name']) : '';
							$value['Patients']['phone'] = ($value['Patients']['phone'] != null) ? ($value['Patients']['phone']) : '';
							$value['Patients']['id_number'] = ($value['Patients']['id_number'] != null) ? ($value['Patients']['id_number']) : '';
							$value['Patients']['notes'] = ($value['Patients']['notes'] != null) ? ($value['Patients']['notes']) : '';
							$value['Patients']['created'] = ($value['Patients']['created'] != null) ? ($value['Patients']['created']) : '';
							$value['Patients']['unique_id'] = ($value['Patients']['unique_id'] != null) ? ($value['Patients']['unique_id']) : null;
							$value['Patients']['gender'] = ($value['Patients']['gender'] != null) ? ($value['Patients']['gender']) : null;
							if (!empty($value['Patients']['p_profilepic'])) {
								$value['Patients']['p_profilepic'] = WWW_BASE . $value['Patients']['p_profilepic'];
							} else {
								$value['Patients']['p_profilepic'] = WWW_BASE . 'img/uploads/no-user.png';
							}
							$last_sync_time = ($last_sync_time != '') ? $last_sync_time : $value['Patients']['created_date_utc'];
							//echo $last_sync_time; die;
							unset($value['Patients']['id']);

							$diagnosis =array();
							if(!empty($value['PatientDiagnosis'])){
								foreach($value['PatientDiagnosis'] as $d_value){
									$diagnosis[] = $d_value['diagnosis_id'];
								}
							}
							unset($value['PatientDiagnosis']);
							$value['Patients']['diagnosis'] = $diagnosis;
							$data[] = $value['Patients'];
						}
					} //echo $last_sync_time; die;
					/*if($last_sync_time==''){
						$date = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
						$last_sync_time=$date->format('Y-m-d H:i:s');
					    //$last_sync_time=date("Y-m-d H:i:s");
					
					    
					}*/
					if($data_arrays['last_sync_time_new'] == ''){
						//$date = new DateTime("now", new DateTimeZone('America/Los_Angeles'));
						//$last_sync_time=$date->format('Y-m-d H:i:s');
						date_default_timezone_set('UTC');
            			$UTCDate = date('Y-m-d H:i:s');
						$last_sync_time=$UTCDate;
					}

					
					$this->Patients->virtualFields['patient_report_status'] = "Patients.id";
					$this->Patients->create(); 
					$data_new = $this->Patients->find('all',array('conditions'=>$condition_deleted,'fields' => array('Patients.id','Patients.unique_id')));
					$deleted_data=array();
					foreach($data_new as $key => $value){
						//if($office_archive_status['Office']['archive_status'] == 1){
						$deleted_data[]=$value['Patients'];
						//}
					}
					if (!empty($data)) {
						$response_array = array('message' => 'Get patients information.', 'status' => 1, 'more_data' => $more_data, 'data' => $data, 'last_sync_time' => $last_sync_time, 'delete_data' =>$deleted_data);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					} else {
						$response_array = array('message' => 'No record found.', 'status' => 0, 'last_sync_time' => $last_sync_time, 'delete_data' =>$deleted_data);
						header('Content-Type: application/json');
						echo json_encode($response_array);
						die;
					}
				} else {
					$this->Patients->virtualFields['patient_report_status'] = "Patients.id";
					$this->Patients->create(); 
					$data_new = $this->Patients->find('all',array('conditions'=>$condition_deleted,'fields' => array('Patients.id','Patients.unique_id')));
					$deleted_data=array();
					foreach($data_new as $key => $value){
						$deleted_data[]=$value['Patients'];
					} 
					$response_array = array('message' => 'No Record found.', 'status' => 0, 'last_sync_time' => $last_sync_time, 'delete_data' =>$deleted_data);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
			}
		}
	}
	/*GEt all patients with UTC time by Madan 22-11-2022*/



	/*Save single report PUP Create new API by MAdan 24-11-2022*/
	public function savePUPReport_v6_v1()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$this->loadModel('PupTest');
				$this->loadModel('PupPointdata');
				$response =$faild_data= array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'savePUPReport_v6';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "PupTestControllerData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['PupTest']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
					$data['PupTest']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['PupTest']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['PupTest']['staff_name'] = isset($data_arrays['staff_name']) ? $data_arrays['staff_name'] : '';
					$data['PupTest']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['PupTest']['patient_name'] = isset($data_arrays['patient_name']) ? $data_arrays['patient_name'] : '';
					$data['PupTest']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : 'Pupolimeter';
					$data['PupTest']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['PupTest']['created_date_utc'] = $data_arrays['created_date_utc'];
					$data['PupTest']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['PupTest']['device_id'] = isset($data_arrays['device_id']) ? $data_arrays['device_id'] : 0;
					$data['PupTest']['office_id'] = isset($data_arrays['office_id']) ? $data_arrays['office_id'] : 0;
					$data['PupTest']['age_group'] = isset($data_arrays['age_group']) ? $data_arrays['age_group'] : 1;
					$data['PupTest']['version'] = isset($data_arrays['version']) ? $data_arrays['version'] : '1.0';
					$data['PupTest']['age'] = isset($data_arrays['age']) ? $data_arrays['ave'] : '';
					$result = $this->PupTest->save($data);
					if ($result) {
						$lastId = $this->PupTest->id;


						if(isset($input_data['diagnosis'])){
								foreach($input_data['diagnosis'] as $value){
            						$diagnosis=array();
            						$diagnosis['pup_id'] = $lastId;
            						$diagnosis['diagnosis_id'] = $value;
            						$diagnosis_data[] = $diagnosis;
            						$this->PupDiagnosis->create();
									$this->PupDiagnosis->save($pdata);

            					}

            				}


						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						$this->ReportRequestBackup->save($result2);
						if (!empty($data_arrays['file'])) {
							$response['pdf'] = WWW_BASE . 'app/webroot/PupTestControllerData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						foreach ($data_arrays['pupPointData'] as $pdatas) {
							$pdata['PupPointdata']['pup_test_id'] = $lastId;
							$pdata['PupPointdata']['time'] = isset($pdatas['time']) ? $pdatas['time'] : 0;
							$pdata['PupPointdata']['pupilDiam_OS'] = isset($pdatas['pupilDiam_OS']) ? $pdatas['pupilDiam_OS'] : 0.00;
							$pdata['PupPointdata']['pupilDiam_OD'] = isset($pdatas['pupilDiam_OD']) ? $pdatas['pupilDiam_OD'] : 0;
							$pdata['PupPointdata']['testState'] = isset($pdatas['testState']) ? $pdatas['testState'] : 0;
							$this->PupPointdata->create();
							$result_p = $this->PupPointdata->save($pdata);
						}
						$response['message'] = 'Success.';
						$response['result'] = 1;
					} else {
						$fail=array(); 
						$errors = $this->PupTest->validationErrors;
						$response['message']='Some error occured in updating report.';
						$result2 = $this->PupTest->find('first', array('conditions' => array('PupTest.unique_id' => $data_arrays['PupTest']['unique_id'])));
						$response['result']=0;
						$fail['id']=$result2['PupTest']['id']; 
						$fail['unique_id']=$resultData['PupTest']['unique_id'];
						//pr($result2['Patient']);die; 
						$name=$name=$result2['Patient']['first_name'];
						if($result2['Patient']['middle_name']!=""){
							$name=$name.' '.$result2['Patient']['middle_name'];
						}
						if($result2['Patient']['last_name']!=""){
							$name=$name.' '.$result2['Patient']['last_name'];
						}
						$fail['patient_name']=$name; 
						$fail['message']=$errors[array_keys($errors)[0]][0];
						$faild_data[]=$fail; 
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				/*echo json_encode($response);
				exit();*/
				$response['failed_data'] = $faild_data;
				echo json_encode($response);
				exit;
			}
		}
	}
	/*Save single report PUP Create new API by MAdan 24-11-2022*/

/*Save multiple PUP report Craete Api by Madan 24-11-2022*/
	public function saveMultiplePUPReport_v6_v1()
	{
		if ($this->check_key()) {
			$this->layout = false;
			if ($this->validatePostRequest()) {
				$this->autoRender = false;
				$this->loadModel('PupTest');
				$this->loadModel('PupPointdata');
				$response = array();
				$faildRequest = array();
				$resultData = $saved_data = $faild_data = array();
				$data_arrays = json_decode(file_get_contents("php://input"), true);
				$request_data = file_get_contents("php://input");
				$reportdata['ReportRequestBackup']['data'] = $request_data;
				$reportdata['ReportRequestBackup']['api_name'] = 'saveMultiplePUPReport_v6';
				$reportdata['ReportRequestBackup']['status'] = 0;
				$result_bpk = $this->ReportRequestBackup->save($reportdata);
				$lastId_bpk = $this->ReportRequestBackup->id;
				if (isset($data_arrays['patient_id']) && !empty($data_arrays['patient_id'])) {
					if (!empty($data_arrays['pdf'])) {
						$pid = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '0';
						$foldername = "PupTestControllerData";
						$imgstring = $data_arrays['pdf'];
						$data_arrays['file'] = $this->base64_to_pdf($imgstring, $foldername, $pid);
					}
					$data['PupTest']['source'] = isset($data_arrays['source']) ? $data_arrays['source'] : 'C';
					$data['PupTest']['file'] = isset($data_arrays['file']) ? $data_arrays['file'] : '';
					$data['PupTest']['staff_id'] = isset($data_arrays['staff_id']) ? $data_arrays['staff_id'] : '';
					$data['PupTest']['staff_name'] = isset($data_arrays['staff_name']) ? $data_arrays['staff_name'] : '';
					$data['PupTest']['patient_id'] = isset($data_arrays['patient_id']) ? $data_arrays['patient_id'] : '';
					$data['PupTest']['patient_name'] = isset($data_arrays['patient_name']) ? $data_arrays['patient_name'] : '';
					$data['PupTest']['test_name'] = isset($data_arrays['test_name']) ? $data_arrays['test_name'] : 'VT';
					$data['PupTest']['created'] = (!empty($data_arrays['created_date'])) ? date('Y-m-d H:i:s', strtotime($data_arrays['created_date'])) : date('Y-m-d H:i:s');
					$data['PupTest']['created_date_utc'] = $data_arrays['created_date_utc'] ;
					$data['PupTest']['unique_id'] = (isset($data_arrays['unique_id']) && !empty($data_arrays['unique_id'])) ? $data_arrays['unique_id'] : null;
					$data['PupTest']['device_id'] = isset($data_arrays['device_id']) ? $data_arrays['device_id'] : 0;
					$data['PupTest']['office_id'] = isset($data_arrays['office_id']) ? $data_arrays['office_id'] : 0;
					$data['PupTest']['age_group'] = isset($data_arrays['age_group']) ? $data_arrays['age_group'] : 1;
					$data['PupTest']['version'] = isset($data_arrays['version']) ? $data_arrays['version'] : '1.0';
					$data['PupTest']['age'] = isset($data_arrays['age']) ? $data_arrays['ave'] : '';
					$result = $this->PupTest->save($data);
					if ($result) {
						$saved_data[]['id'] = $this->PupTest->id;
					}else{
					  	$fail=array(); 
							$errors = $this->PupTest->validationErrors;
							$response['message']='Some error occured in updating report.';
							$result2 = $this->PupTest->find('first', array('conditions' => array('PupTest.unique_id' => $data_arrays['unique_id'])));
							$response['result']=0;
							$fail['id']=$result2['PupTest']['id']; 
							$fail['unique_id']=$data_arrays['unique_id'];
							$name=$name=$result2['Patient']['first_name'];
							if($result2['Patient']['middle_name']!=""){
								$name=$name.' '.$result2['Patient']['middle_name'];
							}
							if($result2['Patient']['last_name']!=""){
								$name=$name.' '.$result2['Patient']['last_name'];
							}
							$fail['patient_name']=$name; 
							$fail['message']=$errors[array_keys($errors)[0]][0];
							$faild_data[]=$fail; 
						} 
					if ($result) {
						$lastId = $this->PupTest->id;

						if(isset($input_data['diagnosis'])){
								foreach($input_data['diagnosis'] as $value){
            						$diagnosis=array();
            						$diagnosis['pup_id'] = $lastId;
            						$diagnosis['diagnosis_id'] = $value;
            						$diagnosis_data[] = $diagnosis;
            						$this->PupDiagnosis->create();
									$this->PupDiagnosis->save($pdata);

            					}

            				}
						$result2 = $this->ReportRequestBackup->find('first', array('conditions' => array('ReportRequestBackup.id' => $lastId_bpk)));
						$result2['ReportRequestBackup']['status'] = 1;
						$this->ReportRequestBackup->save($result2);
						if (!empty($data_arrays['file'])) {
							$response['pdf'] = WWW_BASE . 'app/webroot/PupTestControllerData/' . $data_arrays['file'];
							$response['new_id'] = $lastId;
						} else {
							$response['pdf'] = '';
						}
						foreach ($data_arrays['pupPointData'] as $pdatas) {
							$pdata['PupPointdata']['pup_test_id'] = $lastId;
							$pdata['PupPointdata']['time'] = isset($pdatas['time']) ? $pdatas['time'] : 0;
							$pdata['PupPointdata']['pupilDiam_OS'] = isset($pdatas['pupilDiam_OS']) ? $pdatas['pupilDiam_OS'] : 0.00;
							$pdata['PupPointdata']['pupilDiam_OD'] = isset($pdatas['pupilDiam_OD']) ? $pdatas['pupilDiam_OD'] : 0;
							$pdata['PupPointdata']['testState'] = isset($pdatas['testState']) ? $pdatas['testState'] : 0;
							$this->PupPointdata->create();
							$result_p = $this->PupPointdata->save($pdata);
						}
						$response['data'] = $saved_data;
						$response['message'] = 'Success.';
						$response['result'] = 1;
					} else {
						$response['message'] = $this->getFirstError($this->PupTest->validationErrors);
						$response['result'] = 0;
					}
				} else {
					$response['message'] = 'Patient id can\'t be empty.';
					$response['result'] = 0;
				}
				$response['failed_data'] = $faild_data;
				echo json_encode($response);
				exit();
			}
		}
	}
}
?>