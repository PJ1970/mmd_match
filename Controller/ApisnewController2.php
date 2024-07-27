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
			if ($userDeatils['User']['user_type'] === 'Admin') {
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

	public function devicelogin2($mac_address = 'FC:DB:B3:C1:E6:29', $password = '26036590')
	{
		if ($this->check_key()) {
			$input_data = json_decode(file_get_contents("php://input"), true);

			if (empty($input_data)) {
				$input_data = $_POST;
			}


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
							$testDevice['version'] = $input_data['version'];
							$this->TestDevice->save($testDevice);
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
								$