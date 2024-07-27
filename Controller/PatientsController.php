<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::import('Controller', 'ChatApi');

class PatientsController extends AppController
{
	public $uses = array('Admin', 'User', 'Patient', 'Office', 'Officereport', 'NewUserDevice', 'PatientFiles', 'Pointdata', 'PushNotificationLog', 'DarkAdaption', 'TestStart', 'TestDevice', 'UserDefault', 'DeviceMessage', 'Masterdata', 'OfficeReportBackup', 'DeviceMessage', 'PatientVideoViews', 'Video');

	public $helpers = array('Html', 'Form', 'Js' => array('Jquery'), 'Custom', 'Dropdown');

	public $components = array('Auth' => array('authorize' => array('Controller')), 'Session', 'Email', 'Common', 'RememberMe');
	public $allowedActions = array('admin_cleardevice', 'admin_checkdevicestatus', 'admin_cleardevicedata','admin_Updatestatus30days','admin_UpdateIHUDevice');


	function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->deny();
		$this->Auth->allow($this->allowedActions);
		if ($this->request->is('ajax')) {
			$this->layout = '';
		}
	}

	public function admin_updatebackupformate()
	{
		$this->Session->write('Auth.Admin.Office.session_backup_type', $this->request->data['type']);
		echo 1;
		exit();
	}

	public function admin_updatebackup()
	{
		$this->Session->write('Auth.Admin.Office.session_backup', $this->request->data['backup']);
		$this->Session->write('Auth.Admin.checkautobackup', $this->request->data['backup_remember']);
		$user = $this->Session->read('Auth.Admin');
		
		$download_user = $this->User->find('first', array('conditions' => array('User.id' => $user['id']))); 
		$this->User->id = $download_user['User']['id'];
		$this->User->saveField('checkautobackup',$this->request->data['backup_remember']);
		 
		echo 1;
		exit();
	}

	public function admin_callapi($value = '')
	{

		$url = WWW_BASE."apisnew/PointData";
		echo $uniqueid = '7630220211121261' . strtotime(date('YmdHis')) . $value;

		$payload = '{"test_id":"","unique_id":"' . $uniqueid . '","staff_name":"","age_group":0,"staff_id":"744","patient_id":"7630","patient_name":"test  bbb","numpoints":"0","color":"10","backgroundcolor":"36","stmSize":"3","test_type_id":"","test_name":"Central_20_Point","master_key":"0","eye_select":"0","created_date":"2021-02-11 21:26:17","threshold":"Screening","strategy":"Single Intensity","test_color_fg":0,"test_color_bg":0,"baseline":"1","latitude":"","longitude":"","version":"1.2.129","diagnosys":"","mean_dev":0.0,"pattern_std":"0.00","mean_sen":"0.00","mean_def":"0.00","pattern_std_hfa":"0.00","loss_var":"0.00","mean_std":"0.00","psd_hfa_2":"0.00","psd_hfa":"0.00","vission_loss":"0","false_p":"0/0","false_n":"0/0","false_f":"0/0","ght":"","source":"C","vfpointdata":[],"pdf":"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QrmFRY8Z1ukZ10XdxHC3VVpaRJkhl0lsFuKxo0JmdaV0l8KoUNUa0l81tgmXgkWRmTq6zyHBIvPAosXsnuC8sahhkcnx5hEzRjUtDNt7uY6BWNI3Y7r0XxoyrytbP8yMTJU2DdS41LqiQ/LY9G0xEPr8zYjxZI6ZDy15m30XwxgYOTcUrkudGBfW/8n0o25b2WL5TZxegAUkilbCdd2qbs+wcHoxxzhzmYuPOPYfCA2Ma+1WFkosVaE0bm7fVL49DeqFzMVf28BY+9ks2d/RfIKNgtLxd4PCSY+k/LY7RIfga1GU6UpUsJgyguU6g/QOFrJpuykG1GUuZQNDp2jgUm5Gp1psNjE0sDV0yB+4IfhGzRGNPM49b7DfmX9bQtTMCmVuZHN0cmVhbQplbmRvYmoKOCAwIG9iago8PC9UeXBlL1BhZ2UvTWVkaWFCb3hbMCAwIDU5NSA4NDJdL1Jlc291cmNlczw8L1Byb2NTZXQgWy9QREYgL1RleHQgL0ltYWdlQiAvSW1hZ2VDIC9JbWFnZUldL0ZvbnQ8PC9GMSAxIDAgUj4+L1hPYmplY3Q8PC9pbWcwIDIgMCBSL2ltZzEgMyAwIFIvaW1nMiA0IDAgUi9pbWczIDUgMCBSPj4+Pi9Db250ZW50cyA2IDAgUi9QYXJlbnQgNyAwIFI+PgplbmRvYmoKMSAwIG9iago8PC9UeXBlL0ZvbnQvU3VidHlwZS9UeXBlMS9CYXNlRm9udC9IZWx2ZXRpY2EvRW5jb2RpbmcvV2luQW5zaUVuY29kaW5nPj4KZW5kb2JqCjcgMCBvYmoKPDwvVHlwZS9QYWdlcy9Db3VudCAxL0tpZHNbOCAwIFJdL0lUWFQoNS4wLjUpPj4KZW5kb2JqCjkgMCBvYmoKPDwvVHlwZS9DYXRhbG9nL1BhZ2VzIDcgMCBSPj4KZW5kb2JqCjEwIDAgb2JqCjw8L1Byb2R1Y2VyKGlUZXh0U2hhcnAgNS4wLjUgXChjXCkgMVQzWFQgQlZCQSkvQ3JlYXRpb25EYXRlKEQ6MjAyMTAyMTEyMTI2MTYtMDgnMDAnKS9Nb2REYXRlKEQ6MjAyMTAyMTEyMTI2MTYtMDgnMDAnKT4+CmVuZG9iagp4cmVmCjAgMTEKMDAwMDAwMDAwMCA2NTUzNSBmIAowMDAwMDE5NDE5IDAwMDAwIG4gCjAwMDAwMDAwMTUgMDAwMDAgbiAKMDAwMDAwMTE2NCAwMDAwMCBuIAowMDAwMDE1NDgwIDAwMDAwIG4gCjAwMDAwMTU4MDQgMDAwMDAgbiAKMDAwMDAxODU1MyAwMDAwMCBuIAowMDAwMDE5NTA3IDAwMDAwIG4gCjAwMDAwMTkyMDYgMDAwMDAgbiAKMDAwMDAxOTU3MCAwMDAwMCBuIAowMDAwMDE5NjE1IDAwMDAwIG4gCnRyYWlsZXIKPDwvU2l6ZSAxMS9Sb290IDkgMCBSL0luZm8gMTAgMCBSL0lEIFs8ZWZmZDBiODNlZjUwN2MyOThjMWJmYjRlZTRkNTBjMTM+PDI4MTlkODhjZDYzNTRhMjRjNzM1OTEyYmJjMGQ4OWFmPl0+PgpzdGFydHhyZWYKMTk3NTEKJSVFT0YK"}';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

		curl_setopt($ch, CURLOPT_URL, $url);
		$result = curl_exec($ch);
		curl_close($ch);
		//print_r($result);

		die();
	}

	public function admin_insert()
	{

		$message = array("VF|TEST_STARTED|1", "VF|TPS|0.1855915|0.1855915|1
VF|TPS|-0.1855915|0.1855915|1
VF|TPS|-0.1855915|-0.1855915|1
VF|TPS|0.1855914|-0.1855915|1
VF|TPS|0.568406|0.568406|1
VF|TPS|-0.568406|0.568406|1
VF|TPS|-0.5684061|-0.568406|1
VF|TPS|0.5684059|-0.5684062|1
VF|TPS|1.351256|0.3620679|1
VF|TPS|0.989188|0.989188|1
VF|TPS|0.2429205|1.37767|1
VF|TPS|-0.2429205|1.37767|1
VF|TPS|-0.989188|0.989188|1
VF|TPS|-1.37767|0.2429206|1
VF|TPS|-1.37767|-0.2429205|1
VF|TPS|-0.989188|-0.9891879|1
VF|TPS|-0.2429204|-1.37767|1
VF|TPS|0.2429204|-1.37767|1
VF|TPS|0.9891878|-0.9891881|1
VF|TPS|1.351256|-0.3620683|1", "VF|TEST_STATUS|CLICK|2", "VF|START_BUTTON_PRESSED", "VF|PBT|0|-0.9892|0.9892|10|0", "VF|BTN_PRESS", "VF|VF_SC1_RESULT|-0.9892|0.9892|1|10.0", "VF|PBT|0|-0.5684|0.5684|10|1", "VF|PBT|0|-0.9892|-0.9892|10|2", "VF|PBT|0|0.2429|-1.3777|10|3", "VF|BTN_PRESS", "VF|VF_SC1_RESULT|0.2429|-1.3777|1|10.0", "VF|PBT|0|-0.1856|0.1856|10|4", "VF|BTN_PRESS", "VF|VF_SC1_RESULT|-0.1856|0.1856|1|10.0", "VF|PBT|0|-1.3777|0.2429|10|5", "VF|BTN_PRESS", "VF|VF_SC1_RESULT|-1.3777|0.2429|1|10.0", "VF|PBT|0|0.2429|1.3777|10|6", "VF|BTN_PRESS", "VF|VF_SC1_RESULT|0.2429|1.3777|1|10.0", "VF|PBT|0|1.3513|-0.3621|10|7", "VF|BTN_PRESS", "VF|VF_SC1_RESULT|1.3513|-0.3621|1|10.0", "VF|PBT|0|1.3513|0.3621|10|8", "VF|BTN_PRESS", "VF|VF_SC1_RESULT|1.3513|0.3621|1|10.0", "VF|PBT|0|0.5684|-0.5684|10|9", "VF|BTN_PRESS", "VF|VF_SC1_RESULT|0.5684|-0.5684|1|10.0", "VF|PBT|0|-0.2429|1.3777|10|10", "VF|BTN_PRESS", "VF|VF_SC1_RESULT|-0.2429|1.3777|1|10.0", "VF|PBT|0|0.5684|0.5684|10|11", "VF|BTN_PRESS", "VF|VF_SC1_RESULT|0.5684|0.5684|1|10.0", "VF|PBT|0|0.9892|-0.9892|10|12", "VF|BTN_PRESS", "VF|VF_SC1_RESULT|0.9892|-0.9892|1|10.0", "VF|PBT|0|-0.5684|0.5684|10|13", "VF|BTN_PRESS", "VF|VF_SC1_RESULT|-0.5684|0.5684|1|10.0", "VF|PBT|0|-0.9892|-0.9892|10|14", "VF|BTN_PRESS", "VF|VF_SC1_RESULT|-0.9892|-0.9892|1|10.0", "VF|PBT|0|-1.3777|-0.2429|10|15", "VF|BTN_PRESS", "VF|VF_SC1_RESULT|-1.3777|-0.2429|1|10.0", "VF|PBT|0|-0.2429|-1.3777|10|16", "VF|BTN_PRESS", "VF|VF_SC1_RESULT|-0.2429|-1.3777|1|10.0", "VF|PBT|0|0.1856|-0.1856|10|17", "VF|BTN_PRESS", "VF|VF_SC1_RESULT|0.1856|-0.1856|1|10.0", "VF|PBT|0|0.9892|0.9892|10|18", "VF|BTN_PRESS", "VF|VF_SC1_RESULT|0.9892|0.9892|1|10.0", "VF|PBT|0|0.1856|0.1856|10|19", "VF|BTN_PRESS", "VF|VF_SC1_RESULT|0.1856|0.1856|1|10.0", "VF|PBT|0|-0.5684|-0.5684|10|20", "VF|BTN_PRESS", "VF|VF_SC1_RESULT|-0.5684|-0.5684|1|10.0", "VF|PBT|0|-0.1856|-0.1856|10|21", "VF|PBT|0|-0.1856|-0.1856|10|22", "VF|VF_SC1_RESULT|-0.1856|-0.1856|0|10.0", "VF|VF_TEST_COMPLETED|23", "VF|RESET_DATA|20", "VF|VF_SC1_RESULT|-0.9892|0.9892|1|10.0
VF|VF_SC1_RESULT|0.2429|-1.3777|1|10.0
VF|VF_SC1_RESULT|-0.1856|0.1856|1|10.0
VF|VF_SC1_RESULT|-1.3777|0.2429|1|10.0
VF|VF_SC1_RESULT|0.2429|1.3777|1|10.0
VF|VF_SC1_RESULT|1.3513|-0.3621|1|10.0
VF|VF_SC1_RESULT|1.3513|0.3621|1|10.0
VF|VF_SC1_RESULT|0.5684|-0.5684|1|10.0
VF|VF_SC1_RESULT|-0.2429|1.3777|1|10.0
VF|VF_SC1_RESULT|0.5684|0.5684|1|10.0
VF|VF_SC1_RESULT|0.9892|-0.9892|1|10.0
VF|VF_SC1_RESULT|-0.5684|0.5684|1|10.0
VF|VF_SC1_RESULT|-0.9892|-0.9892|1|10.0
VF|VF_SC1_RESULT|-1.3777|-0.2429|1|10.0
VF|VF_SC1_RESULT|-0.2429|-1.3777|1|10.0
VF|VF_SC1_RESULT|0.1856|-0.1856|1|10.0
VF|VF_SC1_RESULT|0.9892|0.9892|1|10.0
VF|VF_SC1_RESULT|0.1856|0.1856|1|10.0
VF|VF_SC1_RESULT|-0.5684|-0.5684|1|10.0
VF|VF_SC1_RESULT|-0.1856|-0.1856|0|10.0", "VF|VF_RESEND_DATA_COMPLETED|23", "VF|VF_FILE_UPLOADED|35045", "VF|VF_READY");
		for ($i = 0; $i <= 200; $i++) {
			foreach ($message as $key => $value) {
				$this->DeviceMessage->clear();
				$data['DeviceMessage']['office_id'] = 39;
				$data['DeviceMessage']['device_id'] = 39;
				$data['DeviceMessage']['message'] = $value;
				$data['DeviceMessage']['craeted_at'] = date("Y-m-d H:i:s");
				$data['DeviceMessage']['updated_at'] = date("Y-m-d H:i:s");
				$this->DeviceMessage->save($data);
				$this->DeviceMessage->clear();
				usleep(300000);
			}
		}
		die();

	}

	public function admin_insert2()
	{

		$message = array("VF|BTN_PRESS", "VF|BTN_PRESS", "VF|BTN_PRESS");

		foreach ($message as $key => $value) {
			$this->DeviceMessage->clear();
			$data['DeviceMessage']['office_id'] = 39;
			$data['DeviceMessage']['device_id'] = 379;
			$data['DeviceMessage']['message'] = $value;
			$data['DeviceMessage']['craeted_at'] = date("Y-m-d H:i:s");
			$data['DeviceMessage']['updated_at'] = date("Y-m-d H:i:s");
			$this->DeviceMessage->save($data);
			$this->DeviceMessage->clear();
			usleep(5000000);
		}

		die();

	}

	public function admin_test123()
	{
	}

	public function admin_test3()
	{

		$user = $this->Session->read('Auth.Admin');
		$check = (isset($user['Office']['session_backup'])) ? $user['Office']['session_backup'] : 0;
		$check1=0;
        if(isset($this->request->data['manualDownload']) && $this->request->data['manualDownload']==1){
        	$check1 = 1;
        }
		if ($check1 == 1) {

			$staffuserAdmin = $this->User->find('list', array('conditions' => array('User.office_id' => $user['Office']['id']), 'fields' => array('User.id')));
			$conditions['Pointdata.staff_id'] = $staffuserAdmin;
			$officereport = $this->OfficeReportBackup->find('first', array('conditions' => array('OfficeReportBackup.office_id' => $user['Office']['id'],'OfficeReportBackup.testtype'=>'VF')));

			$conditions['Pointdata.id >'] = (isset($officereport['OfficeReportBackup']['last_backup'])) ? $officereport['OfficeReportBackup']['last_backup'] : 0;
			/*echo "<pre>";
        print_r($conditions);
         $data2=$this->Pointdata->find('all',array('conditions'=>$conditions,'order' => 'Pointdata.id DESC'));
         print_r($data2);*/
			$data = $this->Pointdata->find('first', array('conditions' => $conditions, 'order' => 'Pointdata.id ASC'));
//print_r($data);
			if (isset($data['Pointdata'])) {

				$response['status'] = 1;

			} else {
				$response['status'] = 0;

			}
		} else {
			$response['status'] = 0;
		}

		echo json_encode($response);

		exit();

	}
	public function admin_testing()
	{

		$user = $this->Session->read('Auth.Admin');
		$check = (isset($user['Office']['session_backup'])) ? $user['Office']['session_backup'] : 0;
		/* echo "<pre>".$check;
        print_r($user);*/
		if ($check == 1) {

			$staffuserAdmin = $this->User->find('list', array('conditions' => array('User.office_id' => $user['Office']['id']), 'fields' => array('User.id')));
			$conditions['Pointdata.staff_id'] = $staffuserAdmin;
			$officereport = $this->OfficeReportBackup->find('first', array('conditions' => array('OfficeReportBackup.office_id' => $user['Office']['id'],'OfficeReportBackup.testtype'=>'VF')));

			$conditions['Pointdata.id >'] = (isset($officereport['OfficeReportBackup']['last_backup'])) ? $officereport['OfficeReportBackup']['last_backup'] : 0;
			/*echo "<pre>";
        print_r($conditions);
         $data2=$this->Pointdata->find('all',array('conditions'=>$conditions,'order' => 'Pointdata.id DESC'));
         print_r($data2);*/
			$data = $this->Pointdata->find('first', array('conditions' => $conditions, 'order' => 'Pointdata.id ASC'));
//print_r($data);
			if (isset($data['Pointdata'])) {

				$response['status'] = 1;

			} else {
				$response['status'] = 0;

			}
		} else {
			$response['status'] = 0;
		}

		echo json_encode($response);

		exit();

	}


	public function admin_download_report()
	{
		$fileName = '';
		$user = $this->Session->read('Auth.Admin');
		$checkradio = (isset($user['Office']['session_backup_type'])) ? $user['Office']['session_backup_type'] : 'pdf';
		$deleteRecord = (isset($user['Office']['delete_after_backup'])) ? $user['Office']['delete_after_backup'] : 0;
		$officereport = $this->OfficeReportBackup->find('first', array('conditions' => array('OfficeReportBackup.office_id' => $user['Office']['id'],'OfficeReportBackup.testtype'=>'VF')));

		$staffuserAdmin = $this->User->find('list', array('conditions' => array('User.office_id' => $user['Office']['id']), 'fields' => array('User.id')));
		$conditions['Pointdata.staff_id'] = $staffuserAdmin;


		$conditions['Pointdata.id >'] = (isset($officereport['OfficeReportBackup']['last_backup'])) ? $officereport['OfficeReportBackup']['last_backup'] : 0;
		$data = $this->Pointdata->find('first', array('conditions' => $conditions, 'order' => 'Pointdata.id ASC'));
		// pr($data);die();
		if (isset($data['Pointdata'])) {
			$fileName = $data['Pointdata']['file'];
			$patent_name = $data['Patient']['first_name'] . '-' . $data['Patient']['middle_name'] . '-' . $data['Patient']['last_name'];
			$test_date = date('dmY', strtotime($data['Pointdata']['created']));
			$officereport['OfficeReportBackup']['last_backup'] = $data['Pointdata']['id'];
			if ($this->OfficeReportBackup->save($officereport)) {
				if ($deleteRecord == 1) {
					$this->Pointdata->delete($data['Pointdata']['id']);
				}
				if ($checkradio == 'dicome') {
					$patent_id = $data['Pointdata']['patient_id'];
					$this->redirect('/admin/unityreports/exportDicom/' . $patent_id . '/' . $fileName);
				} else {
					$downloadFileName = 't4\1599682062_1064111.pdf';
					$file_url = WWW_BASE.'pointData/' . $fileName;
					header('Content-Description: File Transfer');
					header('Content-Type: text/csv');
					header('Content-Disposition: attachment; name="t4"; filename=' . $patent_name . '-' . $test_date . '-' . $fileName);
					ob_clean();
					flush();
					readfile($file_url);
					exit;
				}
			}
		} else {
			$response['status'] = 0;
			//echo "0";
			echo "<script>window.close();</script>";
			exit;
		}

		echo "<script>window.close();</script>";
		exit;
		die();

	}

	public function admin_report1($id = null)
	{

		$data = $this->Patient->find('first', array('conditions' => array('Patient.id' => $id)));
		$this->set(compact('data'));
	}

	public function admin_insertmanyrepport()
	{
	}

	public function admin_start_test($id = null, $testId = 0, $strategy = 0, $testname = '')
	{

		$this->loadModel('UserPreset');
		$this->Patient->unbindModel(
			array('hasMany' => array('Pointdata'))
		);
		$data = $this->Patient->find('first', array('recursive' => 2, 'conditions' => array('Patient.id' => $id)));
		$data['Patient']['dob'] = date('d-m-Y', strtotime($data['Patient']['dob']));
		$user = $this->Session->read('Auth.Admin');

		if ($user['office_id'] == 0) {
			$test_device = $this->TestDevice->find('all', array('conditions' => array('TestDevice.mac_address NOT' => '')));
		} else {

		//$test_device = $this->TestDevice->find('all', array('conditions' => array('TestDevice.mac_address NOT' => '', 'TestDevice.office_id' => $user['office_id'])));
			$test_device = $this->TestDevice->devicelist($user['office_id']);
		}
		$user_default = $this->UserDefault->find('first', array('conditions' => array('UserDefault.user_id' => $user['id'], 'UserDefault.page' => 1))); 
		$user_preset = $this->UserPreset->find('first', array('conditions' => array('UserPreset.user_id' => $user['id'])));
		$count = count($test_device);
		$defoult_device = array();

		if (!empty($user_default)) {

			if ($testId != 0) {
				$testData['testId2'] = $testId;
			} else if ($user_default['UserDefault']['test_type'] != null) {
				$testData['testId2'] = $user_default['UserDefault']['test_type'];
			} else {
				$testData['testId2'] = 2;
			}
			if ($strategy != 0) {
				$testData['strategy'] = $strategy;
			} else if ($testData['testId2'] == $testId && $testData['testId2'] == $user_default['UserDefault']['test_type'] && $user_default['UserDefault']['strategy'] != null) {
				$testData['strategy'] = $user_default['UserDefault']['strategy'];
			} else if ($testData['testId2'] == 2) {
				$testData['strategy'] = 4;
			} else {
				$testData['strategy'] = '';
			}
			if ($testname != '') {
				$testData['testname'] = $testname;
			} else if ($testData['testId2'] == $testId && $testData['testId2'] == $user_default['UserDefault']['test_type'] && $user_default['UserDefault']['test_name'] != null) {
				$testData['testname'] = $user_default['UserDefault']['test_name'];
			} else if ($testData['testId2'] == 2) {
				$testData['testname'] = 'Central_24_2';
			} else {
				$testData['testname'] = '';
			}
			$testData['testId'] = $testData['testId2'];
			$device_id = $user_default['UserDefault']['device_id'];
			$device = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $device_id)));
			if (!empty($device)) {
				$defoult_device = @$device['TestDevice'];
			}

		} else if ($count == 1) {
			$defoult_device = @$test_device['TestDevice'];
			if ($testId != 0) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 2;
			}

			if ($strategy != 0) {
				$testData['strategy'] = $strategy;
			} else if ($testData['testId'] == 2) {
				$testData['strategy'] = 4;
			} else {
				$testData['strategy'] = '';
			}
			if ($testname != '') {
				$testData['testname'] = $testname;
			} else if ($testData['testId'] == 2) {
				$testData['testname'] = 'Central_24_2';
			} else {
				$testData['testname'] = '';
			}
		} else {
			$testData['testId'] = ($testId != '') ? $testId : 2;
			if ($testId != 0) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 2;
			}

			if ($strategy != 0) {
				$testData['strategy'] = $strategy;
			} else if ($testData['testId'] == 2) {
				$testData['strategy'] = 4;
			} else {
				$testData['strategy'] = '';
			}
			if ($testname != '') {
				$testData['testname'] = $testname;
			} else if ($testData['testId'] == 2) {
				$testData['testname'] = 'Central_24_2';
			} else {
				$testData['testname'] = '';
			}
		}
		//pr($testData);die();
		$this->set(compact(['data', 'test_device', 'user_default', 'user_preset', 'testData', 'defoult_device',]));
	}


public function admin_start_test3($id = null, $testId = 0, $strategy = 0, $testname = '')
	{

		$this->loadModel('UserPreset');
		$this->Patient->unbindModel(
			array('hasMany' => array('Pointdata'))
		);
		$data = $this->Patient->find('first', array('recursive' => 2, 'conditions' => array('Patient.id' => $id)));
		$data['Patient']['dob'] = date('d-m-Y', strtotime($data['Patient']['dob']));
		$user = $this->Session->read('Auth.Admin');

		if ($user['office_id'] == 0) {
			$test_device = $this->TestDevice->find('all', array('conditions' => array('TestDevice.mac_address NOT' => '')));
		} else {
			$test_device = $this->TestDevice->devicelist($user['office_id']);
		}
		$user_default = $this->UserDefault->find('first', array('conditions' => array('UserDefault.user_id' => $user['id'], 'UserDefault.page' => 1)));
		$user_preset = $this->UserPreset->find('first', array('conditions' => array('UserPreset.user_id' => $user['id'])));
		$count = count($test_device);
		$defoult_device = array();

		if (!empty($user_default)) {

			if ($testId != 0) {
				$testData['testId2'] = $testId;
			} else if ($user_default['UserDefault']['test_type'] != null) {
				$testData['testId2'] = $user_default['UserDefault']['test_type'];
			} else {
				$testData['testId2'] = 2;
			}
			if ($strategy != 0) {
				$testData['strategy'] = $strategy;
			} else if ($testData['testId2'] == $testId && $testData['testId2'] == $user_default['UserDefault']['test_type'] && $user_default['UserDefault']['strategy'] != null) {
				$testData['strategy'] = $user_default['UserDefault']['strategy'];
			} else if ($testData['testId2'] == 2) {
				$testData['strategy'] = 4;
			} else {
				$testData['strategy'] = '';
			}
			if ($testname != '') {
				$testData['testname'] = $testname;
			} else if ($testData['testId2'] == $testId && $testData['testId2'] == $user_default['UserDefault']['test_type'] && $user_default['UserDefault']['test_name'] != null) {
				$testData['testname'] = $user_default['UserDefault']['test_name'];
			} else if ($testData['testId2'] == 2) {
				$testData['testname'] = 'Central_24_2';
			} else {
				$testData['testname'] = '';
			}
			$testData['testId'] = $testData['testId2'];
			$device_id = $user_default['UserDefault']['device_id'];
			$device = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $device_id)));
			if (!empty($device)) {
				$defoult_device = $device['TestDevice'];
			}

		} else if ($count == 1) {
			$defoult_device = $test_device['TestDevice'];
			if ($testId != 0) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 2;
			}

			if ($strategy != 0) {
				$testData['strategy'] = $strategy;
			} else if ($testData['testId'] == 2) {
				$testData['strategy'] = 4;
			} else {
				$testData['strategy'] = '';
			}
			if ($testname != '') {
				$testData['testname'] = $testname;
			} else if ($testData['testId'] == 2) {
				$testData['testname'] = 'Central_24_2';
			} else {
				$testData['testname'] = '';
			}
		} else {
			$testData['testId'] = ($testId != '') ? $testId : 2;
			if ($testId != 0) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 2;
			}

			if ($strategy != 0) {
				$testData['strategy'] = $strategy;
			} else if ($testData['testId'] == 2) {
				$testData['strategy'] = 4;
			} else {
				$testData['strategy'] = '';
			}
			if ($testname != '') {
				$testData['testname'] = $testname;
			} else if ($testData['testId'] == 2) {
				$testData['testname'] = 'Central_24_2';
			} else {
				$testData['testname'] = '';
			}
		}
		// pr($testData);die();
		$this->set(compact(['data', 'test_device', 'user_default', 'user_preset', 'testData', 'defoult_device']));
	}

	public function admin_start_test_view($id = null, $testId = 0, $strategy = 0, $testname = '')
	{

		$this->loadModel('UserPreset');
		$this->Patient->unbindModel(
			array('hasMany' => array('Pointdata'))
		);
		$data = $this->Patient->find('first', array('recursive' => 2, 'conditions' => array('Patient.id' => $id)));
		$data['Patient']['dob'] = date('d-m-Y', strtotime($data['Patient']['dob']));
		$user = $this->Session->read('Auth.Admin');

		if ($user['office_id'] == 0) {
			$test_device = $this->TestDevice->find('all', array('conditions' => array('TestDevice.mac_address NOT' => '')));
		} else {
			$test_device = $this->TestDevice->devicelist($user['office_id']);
		}
		$user_default = $this->UserDefault->find('first', array('conditions' => array('UserDefault.user_id' => $user['id'], 'UserDefault.page' => 1)));
		$user_preset = $this->UserPreset->find('first', array('conditions' => array('UserPreset.user_id' => $user['id'])));
		$count = count($test_device);
		$defoult_device = array();

		if (!empty($user_default)) {

			if ($testId != 0) {
				$testData['testId2'] = $testId;
			} else if ($user_default['UserDefault']['test_type'] != null) {
				$testData['testId2'] = $user_default['UserDefault']['test_type'];
			} else {
				$testData['testId2'] = 2;
			}
			if ($strategy != 0) {
				$testData['strategy'] = $strategy;
			} else if ($testData['testId2'] == $testId && $testData['testId2'] == $user_default['UserDefault']['test_type'] && $user_default['UserDefault']['strategy'] != null) {
				$testData['strategy'] = $user_default['UserDefault']['strategy'];
			} else if ($testData['testId2'] == 2) {
				$testData['strategy'] = 4;
			} else {
				$testData['strategy'] = '';
			}
			if ($testname != '') {
				$testData['testname'] = $testname;
			} else if ($testData['testId2'] == $testId && $testData['testId2'] == $user_default['UserDefault']['test_type'] && $user_default['UserDefault']['test_name'] != null) {
				$testData['testname'] = $user_default['UserDefault']['test_name'];
			} else if ($testData['testId2'] == 2) {
				$testData['testname'] = 'Central_24_2';
			} else {
				$testData['testname'] = '';
			}
			$testData['testId'] = $testData['testId2'];
			$device_id = $user_default['UserDefault']['device_id'];
			$device = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $device_id)));
			if (!empty($device)) {
				$defoult_device = $device['TestDevice'];
			}

		} else if ($count == 1) {
			$defoult_device = $test_device['TestDevice'];
			if ($testId != 0) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 2;
			}

			if ($strategy != 0) {
				$testData['strategy'] = $strategy;
			} else if ($testData['testId'] == 2) {
				$testData['strategy'] = 4;
			} else {
				$testData['strategy'] = '';
			}
			if ($testname != '') {
				$testData['testname'] = $testname;
			} else if ($testData['testId'] == 2) {
				$testData['testname'] = 'Central_24_2';
			} else {
				$testData['testname'] = '';
			}
		} else {
			$testData['testId'] = ($testId != '') ? $testId : 2;
			if ($testId != 0) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 2;
			}

			if ($strategy != 0) {
				$testData['strategy'] = $strategy;
			} else if ($testData['testId'] == 2) {
				$testData['strategy'] = 4;
			} else {
				$testData['strategy'] = '';
			}
			if ($testname != '') {
				$testData['testname'] = $testname;
			} else if ($testData['testId'] == 2) {
				$testData['testname'] = 'Central_24_2';
			} else {
				$testData['testname'] = '';
			}
		}
		// pr($testData);die();
		$this->set(compact(['data', 'test_device', 'user_default', 'user_preset', 'testData', 'defoult_device']));
	}

	public function admin_start_test_test($id = null, $testId = 0, $strategy = 0, $testname = '')
	{

		$this->loadModel('UserPreset');
		$this->Patient->unbindModel(
			array('hasMany' => array('Pointdata'))
		);
		$data = $this->Patient->find('first', array('recursive' => 2, 'conditions' => array('Patient.id' => $id)));
		$data['Patient']['dob'] = date('d-m-Y', strtotime($data['Patient']['dob']));
		$user = $this->Session->read('Auth.Admin');

		if ($user['office_id'] == 0) {
			$test_device = $this->TestDevice->find('all', array('conditions' => array('TestDevice.mac_address NOT' => '')));
		} else {
			$test_device = $this->TestDevice->devicelist($user['office_id']);
		}
		$user_default = $this->UserDefault->find('first', array('conditions' => array('UserDefault.user_id' => $user['id'], 'UserDefault.page' => 1)));
		$user_preset = $this->UserPreset->find('first', array('conditions' => array('UserPreset.user_id' => $user['id'])));
		$count = count($test_device);
		$defoult_device = array();

		if (!empty($user_default)) {

			if ($testId != 0) {
				$testData['testId2'] = $testId;
			} else if ($user_default['UserDefault']['test_type'] != null) {
				$testData['testId2'] = $user_default['UserDefault']['test_type'];
			} else {
				$testData['testId2'] = 2;
			}
			if ($strategy != 0) {
				$testData['strategy'] = $strategy;
			} else if ($testData['testId2'] == $testId && $testData['testId2'] == $user_default['UserDefault']['test_type'] && $user_default['UserDefault']['strategy'] != null) {
				$testData['strategy'] = $user_default['UserDefault']['strategy'];
			} else if ($testData['testId2'] == 2) {
				$testData['strategy'] = 4;
			} else {
				$testData['strategy'] = '';
			}
			if ($testname != '') {
				$testData['testname'] = $testname;
			} else if ($testData['testId2'] == $testId && $testData['testId2'] == $user_default['UserDefault']['test_type'] && $user_default['UserDefault']['test_name'] != null) {
				$testData['testname'] = $user_default['UserDefault']['test_name'];
			} else if ($testData['testId2'] == 2) {
				$testData['testname'] = 'Central_24_2';
			} else {
				$testData['testname'] = '';
			}
			$testData['testId'] = $testData['testId2'];
			$device_id = $user_default['UserDefault']['device_id'];
			$device = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $device_id)));
			if (!empty($device)) {
				$defoult_device = $device['TestDevice'];
			}

		} else if ($count == 1) {
			$defoult_device = $test_device['TestDevice'];
			if ($testId != 0) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 2;
			}

			if ($strategy != 0) {
				$testData['strategy'] = $strategy;
			} else if ($testData['testId'] == 2) {
				$testData['strategy'] = 4;
			} else {
				$testData['strategy'] = '';
			}
			if ($testname != '') {
				$testData['testname'] = $testname;
			} else if ($testData['testId'] == 2) {
				$testData['testname'] = 'Central_24_2';
			} else {
				$testData['testname'] = '';
			}
		} else {
			$testData['testId'] = ($testId != '') ? $testId : 2;
			if ($testId != 0) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 2;
			}

			if ($strategy != 0) {
				$testData['strategy'] = $strategy;
			} else if ($testData['testId'] == 2) {
				$testData['strategy'] = 4;
			} else {
				$testData['strategy'] = '';
			}
			if ($testname != '') {
				$testData['testname'] = $testname;
			} else if ($testData['testId'] == 2) {
				$testData['testname'] = 'Central_24_2';
			} else {
				$testData['testname'] = '';
			}
		}
		// pr($testData);die();
		$this->set(compact(['data', 'test_device', 'user_default', 'user_preset', 'testData', 'defoult_device']));
	}

	public function admin_start_test2($id = null, $testId = 2, $strategy = '', $testname = '')
	{
		$this->loadModel('UserPreset');
		$this->Patient->unbindModel(
			array('hasMany' => array('Pointdata'))
		);
		$data = $this->Patient->find('first', array('recursive' => 2, 'conditions' => array('Patient.id' => $id)));
		$data['Patient']['dob'] = date('d-m-Y', strtotime($data['Patient']['dob']));
		$user = $this->Session->read('Auth.Admin');
		$testData['testId'] = $testId;
		$testData['strategy'] = $strategy;
		$testData['testname'] = "'" . $testname . "'";
		$test_devi = $this->TestDevice->find('all', array('conditions' => array('TestDevice.mac_address NOT' => '', 'TestDevice.office_id' => $user['office_id'])));
		if ($user['office_id'] == 0) {
			$test_device = $this->TestDevice->find('all', array('conditions' => array('TestDevice.mac_address NOT' => '')));
		} else {
			$test_device = $this->TestDevice->devicelist($user['office_id']);
		}
		$user_default = $this->UserDefault->find('first', array('conditions' => array('UserDefault.user_id' => $user['id'], 'UserDefault.page' => 1)));
		//print_r($user_default);die;
		$user_preset = $this->UserPreset->find('first', array('conditions' => array('UserPreset.user_id' => $user['id'])));
		$count = count($test_device);
		$defoult_device = array();
		if (!empty($user_default)) {
			$device_id = $user_default['UserDefault']['device_id'];
			$device = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $device_id)));
			$defoult_device = $device['TestDevice'];
		} else if ($count == 1) {
			$defoult_device = $test_device['TestDevice'];
		}

		$this->set(compact(['data', 'test_device', 'user_default', 'user_preset', 'testData', 'defoult_device','test_devi']));
	}

	public function admin_start_test_vs($id = null, $testId = 3, $strategy = '', $testname = '')
	{
		$this->loadModel('UserPreset');
		$this->Patient->unbindModel(
			array('hasMany' => array('Pointdata'))
		);
		$data = $this->Patient->find('first', array('recursive' => 2, 'conditions' => array('Patient.id' => $id)));
		$data['Patient']['dob'] = date('d-m-Y', strtotime($data['Patient']['dob']));
		$user = $this->Session->read('Auth.Admin');
		$testData['strategy'] = $strategy;
		$testData['testname'] = $testname;

		if ($user['office_id'] == 0) {
			$test_device = $this->TestDevice->find('all', array('conditions' => array('TestDevice.mac_address NOT' => '')));
		} else {
			$test_device = $this->TestDevice->devicelist($user['office_id']);
		}

		$user_default = $this->UserDefault->find('first', array('conditions' => array('UserDefault.user_id' => $user['id'], 'UserDefault.page' => 4)));
		$user_preset = $this->UserPreset->find('first', array('conditions' => array('UserPreset.user_id' => $user['id'], 'UserPreset.page' => 4)));
		$count = count($test_device);
		$defoult_device = array();

		if (!empty($user_default)) {
			$device_id = $user_default['UserDefault']['device_id'];
			$device = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $device_id)));
			if ($testId != 3) {
				$testData['testId'] = $testId;
			} else if ($user_default['UserDefault']['test_type'] != null) {
				$testData['testId'] = $user_default['UserDefault']['test_type'];
			} else {
				$testData['testId'] = 3;
			}
			$defoult_device = $device['TestDevice'];
		} else if ($count == 1) {
			if ($testId != 3) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 3;
			}
			$defoult_device = $test_device['TestDevice'];
		} else {
			if ($testId != 3) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 3;
			}
		}

		$this->set(compact(['data', 'test_device', 'user_default', 'user_preset', 'testData', 'defoult_device']));

	}


	public function admin_start_test_vs2($id = null, $testId = 3, $strategy = '', $testname = '')
	{
		$this->loadModel('UserPreset');
		$this->Patient->unbindModel(
			array('hasMany' => array('Pointdata'))
		);
		$data = $this->Patient->find('first', array('recursive' => 2, 'conditions' => array('Patient.id' => $id)));
		$data['Patient']['dob'] = date('d-m-Y', strtotime($data['Patient']['dob']));
		$user = $this->Session->read('Auth.Admin');
		$testData['strategy'] = $strategy;
		$testData['testname'] = $testname;

		if ($user['office_id'] == 0) {
			$test_device = $this->TestDevice->find('all', array('conditions' => array('TestDevice.mac_address NOT' => '')));
		} else {
			$test_device = $this->TestDevice->devicelist($user['office_id']);
		}
			
		$user_default = $this->UserDefault->find('first', array('conditions' => array('UserDefault.user_id' => $user['id'], 'UserDefault.page' => 4)));
		$user_preset = $this->UserPreset->find('first', array('conditions' => array('UserPreset.user_id' => $user['id'], 'UserPreset.page' => 4)));
		$count = count($test_device);
		$defoult_device = array();

		if (!empty($user_default)) {
			$device_id = $user_default['UserDefault']['device_id'];
			$device = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $device_id)));
			if ($testId != 3) {
				$testData['testId'] = $testId;
			} else if ($user_default['UserDefault']['test_type'] != null) {
				$testData['testId'] = $user_default['UserDefault']['test_type'];
			} else {
				$testData['testId'] = 3;
			}
			$defoult_device = $device['TestDevice'];
		} else if ($count == 1) {
			if ($testId != 3) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 3;
			}
			$defoult_device = $test_device['TestDevice'];
		} else {
			if ($testId != 3) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 3;
			}
		}

		$this->set(compact(['data', 'test_device', 'user_default', 'user_preset', 'testData', 'defoult_device',]));

	}

	public function admin_servertest()
	{
		$id = $this->Session->read('Auth.Admin.Office');

		$office = $this->Office->find('first', [
			'conditions' => ['Office.id' => $id]
		]);
		$office['Office']['server_test'] = $this->request->data['backup'];
		$this->Office->save($office);
		$this->Session->write('Auth.Admin.Office.server_test', $this->request->data['backup']);
		echo 1;
		exit();
	}

	public function admin_start_test_fdt($id = null, $testId = 0, $strategy = 0, $testname = '')
	{
		$this->loadModel('UserPreset');
		$this->Patient->unbindModel(
			array('hasMany' => array('Pointdata'))
		);
		$data = $this->Patient->find('first', array('recursive' => 2, 'conditions' => array('Patient.id' => $id)));
		$data['Patient']['dob'] = date('d-m-Y', strtotime($data['Patient']['dob']));
		$user = $this->Session->read('Auth.Admin');

		if ($user['office_id'] == 0) {
			$test_device = $this->TestDevice->find('all', array('conditions' => array('TestDevice.mac_address NOT' => '')));
		} else {
			$test_device = $this->TestDevice->devicelist($user['office_id']);
		}
		$user_default = $this->UserDefault->find('first', array('conditions' => array('UserDefault.user_id' => $user['id'], 'UserDefault.page' => 2)));
		$user_preset = $this->UserPreset->find('first', array('conditions' => array('UserPreset.user_id' => $user['id'])));
		$count = count($test_device);
		$defoult_device = array();
		if (!empty($user_default)) {
			if ($testId != 0) {
				$testData['testId2'] = $testId;
			} else if ($user_default['UserDefault']['test_type'] != null) {
				$testData['testId2'] = $user_default['UserDefault']['test_type'];
			} else {
				$testData['testId2'] = 1;
			}
			if ($strategy != 0) {
				$testData['strategy'] = $strategy;
			} else if ($testData['testId2'] == $testId && $testData['testId2'] == $user_default['UserDefault']['test_type'] && $user_default['UserDefault']['strategy'] != null) {
				$testData['strategy'] = $user_default['UserDefault']['strategy'];
			} else if ($testData['testId2'] == 1) {
				$testData['strategy'] = 1;
			} else {
				$testData['strategy'] = '';
			}
			if ($testname != '') {
				$testData['testname'] = $testname;
			} else if ($testData['testId2'] == $testId && $testData['testId2'] == $user_default['UserDefault']['test_type'] && $user_default['UserDefault']['test_name'] != null) {
				$testData['testname'] = $user_default['UserDefault']['test_name'];
			} else if ($testData['testId2'] == 1) {
				$testData['testname'] = 'C20-1';
			} else {
				$testData['testname'] = '';
			}
			$testData['testId'] = $testData['testId2'];
			$device_id = $user_default['UserDefault']['device_id'];
			$device = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $device_id)));
			if (!empty($device)) {
				$defoult_device = $device['TestDevice'];
			}
		} else if ($count == 1) {
			if ($testId != 0) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 1;
			}
			if ($strategy != 0) {
				$testData['strategy'] = $strategy;
			} else if ($testData['testId'] == 1) {
				$testData['strategy'] = 1;
			} else {
				$testData['strategy'] = '';
			}
			if ($testname != '') {
				$testData['testname'] = $testname;
			} else if ($testData['testId'] == 1) {
				$testData['testname'] = 'C20-1';
			} else {
				$testData['testname'] = '';
			}
			$defoult_device = $test_device['TestDevice'];
		} else {
			if ($testId != 0) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 1;
			}
			if ($strategy != 0) {
				$testData['strategy'] = $strategy;
			} else if ($testData['testId'] == 1) {
				$testData['strategy'] = 1;
			} else {
				$testData['strategy'] = '';
			}
			if ($testname != '') {
				$testData['testname'] = $testname;
			} else if ($testData['testId'] == 1) {
				$testData['testname'] = 'C20-1';
			} else {
				$testData['testname'] = '';
			}
		}
		$this->set(compact(['data', 'test_device', 'user_default', 'user_preset', 'testData', 'defoult_device']));
	}

	public function admin_start_test_da2($id = null, $testId = 3, $strategy = '', $testname = '')
	{
		$this->loadModel('UserPreset');
		$this->Patient->unbindModel(
			array('hasMany' => array('Pointdata'))
		);
		$data = $this->Patient->find('first', array('recursive' => 2, 'conditions' => array('Patient.id' => $id)));
		$data['Patient']['dob'] = date('d-m-Y', strtotime($data['Patient']['dob']));
		$user = $this->Session->read('Auth.Admin');

		$testData['strategy'] = $strategy;
		$testData['testname'] = $testname;
		if ($user['office_id'] == 0) {
			$test_device = $this->TestDevice->find('all', array('conditions' => array('TestDevice.mac_address NOT' => '')));
		} else {
			$test_device = $this->TestDevice->devicelist($user['office_id']);
		}
		$user_default = $this->UserDefault->find('first', array('conditions' => array('UserDefault.user_id' => $user['id'], 'UserDefault.page' => 3)));
		$user_preset = $this->UserPreset->find('first', array('conditions' => array('UserPreset.user_id' => $user['id'])));
		$count = count($test_device);
		$defoult_device = array();
		if (!empty($user_default)) {
			$device_id = $user_default['UserDefault']['device_id'];
			$device = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $device_id)));
			if ($testId != 3) {
				$testData['testId'] = $testId;
			} else if ($user_default['UserDefault']['test_type'] != null) {
				$testData['testId'] = $user_default['UserDefault']['test_type'];
			} else {
				$testData['testId'] = 3;
			}
			$defoult_device = $device['TestDevice'];
		} else if ($count == 1) {
			if ($testId != 3) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 3;
			}
			$defoult_device = $test_device['TestDevice'];
		} else {
			if ($testId != 3) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 3;
			}
		}
		$this->set(compact(['data', 'test_device', 'user_default', 'user_preset', 'testData', 'defoult_device']));
	}

	public function admin_start_test_da($id = null, $testId = 3, $strategy = '', $testname = '')
	{
		$this->loadModel('UserPreset');
		$this->Patient->unbindModel(
			array('hasMany' => array('Pointdata'))
		);
		$data = $this->Patient->find('first', array('recursive' => 2, 'conditions' => array('Patient.id' => $id)));
		$user = $this->Session->read('Auth.Admin');
		$data['Patient']['dob'] = date('d-m-Y', strtotime($data['Patient']['dob']));
		$testData['strategy'] = $strategy;
		$testData['testname'] = $testname;
		if ($user['office_id'] == 0) {
			$test_device = $this->TestDevice->find('all', array('conditions' => array('TestDevice.mac_address NOT' => '')));
		} else {
			$test_device = $this->TestDevice->devicelist($user['office_id']);
		}
		$user_default = $this->UserDefault->find('first', array('conditions' => array('UserDefault.user_id' => $user['id'], 'UserDefault.page' => 3)));
		$user_preset = $this->UserPreset->find('first', array('conditions' => array('UserPreset.user_id' => $user['id'])));
		$count = count($test_device);
		$defoult_device = array();
		if (!empty($user_default)) {
			$device_id = $user_default['UserDefault']['device_id'];
			$device = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $device_id)));
			if ($testId != 3) {
				$testData['testId'] = $testId;
			} else if ($user_default['UserDefault']['test_type'] != null) {
				$testData['testId'] = $user_default['UserDefault']['test_type'];
			} else {
				$testData['testId'] = 3;
			}
			$defoult_device = $device['TestDevice'];
		} else if ($count == 1) {
			if ($testId != 3) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 3;
			}
			$defoult_device = $test_device['TestDevice'];
		} else {
			if ($testId != 3) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 3;
			}
		}
		$this->set(compact(['data', 'test_device', 'user_default', 'user_preset', 'testData', 'defoult_device']));
	}

	public function admin_start_test_pup($id = null, $testId = 3, $strategy = '', $testname = '')
	{
		$this->loadModel('UserPreset');
		$this->Patient->unbindModel(
			array('hasMany' => array('Pointdata'))
		);
		$data = $this->Patient->find('first', array('recursive' => 2, 'conditions' => array('Patient.id' => $id)));
		$user = $this->Session->read('Auth.Admin');
		$data['Patient']['dob'] = date('d-m-Y', strtotime($data['Patient']['dob']));
		$testData['strategy'] = $strategy;
		$testData['testname'] = $testname;
		if ($user['office_id'] == 0) {
			$test_device = $this->TestDevice->find('all', array('conditions' => array('TestDevice.mac_address NOT' => '')));
		} else {
			$test_device = $this->TestDevice->devicelist($user['office_id']);
		}
		$user_default = $this->UserDefault->find('first', array('conditions' => array('UserDefault.user_id' => $user['id'], 'UserDefault.page' => 3)));
		$user_preset = $this->UserPreset->find('first', array('conditions' => array('UserPreset.user_id' => $user['id'])));
		$count = count($test_device);
		$defoult_device = array();
		if (!empty($user_default)) {
			$device_id = $user_default['UserDefault']['device_id'];
			$device = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $device_id)));
			if ($testId != 3) {
				$testData['testId'] = $testId;
			} else if ($user_default['UserDefault']['test_type'] != null) {
				$testData['testId'] = $user_default['UserDefault']['test_type'];
			} else {
				$testData['testId'] = 3;
			}
			$defoult_device = $device['TestDevice'];
		} else if ($count == 1) {
			if ($testId != 3) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 3;
			}
			$defoult_device = $test_device['TestDevice'];
		} else {
			if ($testId != 3) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 3;
			}
		}

		$this->set(compact(['data', 'test_device', 'user_default', 'user_preset', 'testData', 'defoult_device']));
	}

	public function admin_test_setting()
	{

	}

	public function admin_findtest($patent_id = null)
	{
		if ($patent_id == null) {
			return $this->redirect($this->referer());
		} else {
			$this->loadModel('Patient');
			$this->Patient->bindModel(array(
				'hasMany' => array(
					'Pointdata' => array(
						'className' => 'Pointdata',
						'foreignKey' => 'patient_id',
						"limit" => 1,
						'fields' => array('Pointdata.id', 'Pointdata.created', 'Pointdata.test_name'),
						"order" => 'Pointdata.id desc'
					)
				)
			));
			$this->Patient->bindModel(array(
				'hasMany' => array(
					'DarkAdaption' => array(
						'className' => 'DarkAdaption',
						'foreignKey' => 'patient_id',
						"limit" => 1,
						'fields' => array('DarkAdaption.id', 'DarkAdaption.test_date_time'),
						"order" => 'DarkAdaption.id desc'
					)
				)
			));
			$this->Office->unbindModel(
				array('belongsTo' => array('User'))
			);
			$data = $this->Patient->find('first', array(
				'recursive' => 2, 'conditions' => array('Patient.id' => $patent_id)));

			$fdt_tests = array("C20-1", "C20-5", "C30-1", "C30-5", "C20-1");
			$test_page_name = "";
			$checked_data = array();
			if (isset($data['Office']['Officereport'])) {
				$checked_data = Hash::extract($data['Office']['Officereport'], '{n}.office_report');
			}
			if (isset($data['DarkAdaption'][0]['id'])) {
				$da_data = strtotime($data['DarkAdaption'][0]['test_date_time']);
			} else {
				$da_data = 0;
			}
			if (isset($data['Pointdata'][0]['id'])) {
				$point_data = strtotime($data['Pointdata'][0]['created']);
			} else {
				$point_data = 0;
			}
			if ($da_data > $point_data) {
				$test_page_name = 'start_test_da'; //da link
			} else if ($point_data > 0) {
				//check test
				if (in_array($data['Pointdata'][0]['test_name'], $fdt_tests)) {
					$test_page_name = 'start_test_fdt'; //fdt link
				} else if ($data['Pointdata'][0]['test_name'] = "Vision Screening" && in_array(25, $checked_data)) {
					$test_page_name = 'start_test_vs'; //vs link
				} else {
					$test_page_name = 'start_test';// vf link
				}
			} else {
				if (in_array(14, $checked_data)) {
					$test_page_name = 'start_test';//vf
				} else if (in_array(15, $checked_data)) {
					$test_page_name = 'start_test_fdt';// fdt
				} else if (in_array(23, $checked_data)) {
					$test_page_name = 'start_test_da';///da
				} else if (in_array(25, $checked_data)) {
					$test_page_name = 'start_test_vs';///vs
				}
			}

			if ($test_page_name == '') {
				return $this->redirect($this->referer());
			} else {
				$this->redirect(array('controller' => 'patients', 'action' => $test_page_name, $patent_id));
			}


		}
	}

	//This function for Patients listing
	public function admin_patients_listing()
	{
		$this->loadModel('Patient');
		$this->loadModel('User');
		$Admin = $this->Session->read('Auth.Admin');
		//$conditions=array('Patient.is_delete',0);
		if ($this->request->is('post')) {
		    $this->loadModel('ActTest');
		    $this->loadModel('VtTest');
		    $this->loadModel('VaData');
		    $this->loadModel('PupTest');
		    $this->loadModel('StbTest'); 
		    $patineList= (explode(",",$this->request->data['PatientPatientsListingFormPost1'])); 
            $move=$patineList[0];
		   	unset($patineList[0]); 
		   	date_default_timezone_set('UTC');
            $medate = date('Y-m-d H:i:s');
		    $this->Pointdata->updateAll(
                array('Pointdata.patient_id' => $move,'Pointdata.created_date_utc'=>"'".$medate."'"),
                 array('Pointdata.patient_id' => $patineList)
            );
            $this->ActTest->updateAll(
                array('ActTest.patient_id' => $move,'ActTest.created_date_utc'=>"'".$medate."'"),
                 array('ActTest.patient_id' => $patineList)
            );
            $this->VtTest->updateAll(
                array('VtTest.patient_id' => $move,'VtTest.created_date_utc'=>"'".$medate."'"),
                 array('VtTest.patient_id' => $patineList)
            );
            $this->VaData->updateAll(
                array('VaData.patient_id' => $move,'VaData.created_date_utc'=>"'".$medate."'"),
                 array('VaData.patient_id' => $patineList)
            );
            $this->PupTest->updateAll(
                array('PupTest.patient_id' => $move,'PupTest.created_date_utc'=>"'".$medate."'"),
                 array('PupTest.patient_id' => $patineList)
            );
            $this->DarkAdaption->updateAll(
                array('DarkAdaption.patient_id' => $move,'DarkAdaption.created_date_utc'=>"'".$medate."'"),
                 array('DarkAdaption.patient_id' => $patineList)
            );
            $this->StbTest->updateAll(
                array('StbTest.patient_id' => $move,'StbTest.created_date_utc'=>"'".$medate."'"),
                 array('StbTest.patient_id' => $patineList)
            ); 
            //$this->Patient->deleteAll(array('Patient.id' => $patineList), false);
            date_default_timezone_set("UTC");
            $mergedate = date('Y-m-d H:i:s');
            $this->Patient->updateAll(array('Patient.merge_status' => 1,'Patient.merge_date' => "'".$mergedate."'",'Patient.created_date_utc' => "'".$mergedate."'"), array('Patient.id' => $patineList));
		}
		if (!empty($Admin) && $Admin['user_type'] == "Admin") {

			$conditions = array();
		} else if (!empty($Admin) && $Admin['user_type'] == "Subadmin") { 
			$conditions = array('Patient.office_id' => $Admin['office_id']);
			//$conditions = array('Patient.user_id' => $Admin['id']);
		} else if (!empty($Admin) && $Admin['user_type'] == "Staffuser") {
			$conditions = array('Patient.office_id' => $Admin['office_id']);
			$avl_credit = $this->User->field('credits', array('User.id' => $this->Session->read('Auth.Admin.id')));
			$this->set(compact('avl_credit'));
		}
		if (!empty($this->Session->read('Search.office'))) {
			$conditions[] = array('Patient.office_id' => $this->Session->read('Search.office'));
		}
		//$conditions['AND']['OR'] = array('Patient.delete_date !=' => null, 'Patient.is_delete' => 0);
		if (!empty($this->request->query['search'])) {
			//echo "yes";die;
			$search = strtolower(trim($this->request->query['search']));
			$conditions['OR'][] = array("Patient.id_number LIKE " => '%' . $search . '%');
			$conditions['OR'][] = array('Lower(Patient.first_name) like' => '%' . $search . '%');
			$conditions['OR'][] = array('Lower(Patient.last_name) like' => '%' . $search . '%');
			//$conditions['OR'][] = array('Lower(Patient.office_name) like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(Patient.email) like' => '%' . $search . '%');
			$this->set(compact('search'));
		}
		if (!empty($this->request->query['advance_search'])) {
			//echo "yes";die;
			//pr($this->request->query);  die;
			@$first_name = trim($this->request->query['first_name']);
			@$merge = trim($this->request->query['merge']);
			@$middle_name = trim($this->request->query['middle_name']);
			@$last_name = trim($this->request->query['last_name']);
			@$id_number = trim($this->request->query['id_number']);
			@$dob = trim($this->request->query['dob']);
			@$status = ($this->request->query['status']);
			if (!empty($first_name))
				$conditions['AND'][] = array('Lower(Patient.first_name) like' => '%' . strtolower($first_name) . '%');
			if (!empty($id_number))
				$conditions['AND'][] = array('Lower(Patient.id_number) like' => '%' . strtolower($id_number) . '%');
			if (!empty($middle_name))
				$conditions['AND'][] = array('Lower(Patient.middle_name) like' => '%' . strtolower($middle_name) . '%');
			if (!empty($last_name))
				$conditions['AND'][] = array('Lower(Patient.last_name) like' => '%' . strtolower($last_name) . '%');
			if (!empty($dob))
				$conditions['AND'][] = array('Patient.dob' => $dob);
			if (!empty($status) || $status=='0')
				$conditions['AND'][] = array('Patient.status' => $status);
			$this->set(compact(['first_name', 'middle_name', 'last_name','id_number', 'dob','status','merge']));
		}else if(isset($this->request->query['merge'])){
		    	$merge = trim($this->request->query['merge']);
		    	$this->set(compact(['merge']));
		}
		$conditions['Patient.merge_status'] = 0;
		//pr($conditions);die;
		$this->Patient->virtualFields['office_name'] = "select name from mmd_offices as O where O.id=Patient.office_id ";
		$this->Patient->unbindModel(
			array('hasMany' => array('Pointdata'))
		);
		$this->Patient->bindModel(array(
			'belongsTo' => array(
				'Office' => array(
					'className' => 'Office',
					'foreignKey' => 'office_id',
					'fields' => array('Office.server_test','Office.archive_status'),
				)
			)
		));
		/* $this->Pointdata->unbindModel(
    array('hasMany' => array('VfPointdata'), 'belongsTo' => array('User','Patient','Test'))
);*/
		$this->Office->unbindModel(
			array('hasMany' => array('Officereport'), 'belongsTo' => array('User'))
		);
		/*     $this->DarkAdaption->unbindModel(
    array('hasMany' => array('DaPointData'), 'belongsTo' => array('User','Office'))
); */


		$this->paginate = array('conditions' => $conditions,
			'limit' => 10,
			'recursive' => 2,
			'callbacks' => false,
			'order' => array('Patient.id' => 'DESC')
		);
		//$datas = $this->paginate('Patient');
		if ($Admin['user_type'] == 'Admin') {
			//$this->Pointdata->useTable = 'pointdatas_admin';
			$datas = $this->paginate('Patient');
		}else{
			try {
				$this->Patient->useTable = 'Patient_'.$Admin['office_id']; 
				//$this->Patient->useTable = 'Patient_38'; 
				$datas = $this->paginate('Patient');
			}catch(Exception $e){
				$this->Patient->useTable = 'Patient';
				$datas = $this->paginate('Patient');
			}
		}

		if ($Admin['user_type'] == 'Subadmin' || $Admin['user_type'] == 'Staffuser') {
			$this->loadModel('Payment');


			if ($Admin['user_type'] == 'Staffuser') {
				/* $user_s = $this->User->find('first',array(
					'conditions'=>array('User.id'=>$Admin['created_by'])
				)); */
				//pr($user_s);die;
				$user_s = $this->User->find('first', array(
					'conditions' => array('User.office_id' => $Admin['office_id'], 'User.user_type' => 'Subadmin')
				));
			}
			$check_payable = '';
			$this->loadModel('Office');
			$check_payable = $this->Office->find('first', array(
				'conditions' => array('Office.id' => $Admin['Office']['id'])
			));
			//pr($check_payable);die;
			if ($check_payable['Office']['payable'] == 'yes' && $check_payable['Office']['credits'] <= 0) {
				$credit_expire = 'Credit expire';
				$this->set(compact('datas', 'credit_expire', 'check_payable'));
			}
		}
		$check_payable = '';
		$this->loadModel('Office');
		$check_payable = $this->Office->find('first', array(
			'conditions' => array('Office.id' => $Admin['Office']['id'])
		));
		$this->set(compact('datas', 'check_payable'));
	}

	public function admin_ajaxPatientsListing()
	{
		$this->layout = false;
		$this->autoRender = false;
		$requestData = $this->request->data;
		$columns = array(
			// datatable column index  => database column name
			0 => 'Patient.id',
			1 => 'Patient.id_number',
			2 => 'Patient.first_name',
			3 => 'Patient.last_name',
			4 => 'Patient.email',
			5 => 'Patient.office_name',
			6 => 'Patient.status',
			7 => 'Patient.patient_view',
			8 => 'Patient.p_profilepic'

		);


		$Admin = $this->Auth->user();

		if ($Admin['user_type'] == 'Admin') {
			// Calculate Total Records..
			$totalData = $this->Patient->find('count', array('conditions' => array('Patient.is_delete' => 0)));
			$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
			$conditions = array('Patient.is_delete' => 0);

		} elseif ($Admin['user_type'] == 'Subadmin') {
			// Calculate Total Records..
			$conditions = array('Patient.office_id' => $Admin['office_id'], 'Patient.is_delete' => 0);
			$totalData = $this->Patient->find('count', array('conditions' => $conditions));
			$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		} else if (!empty($Admin) && $Admin['user_type'] == "Staffuser") {

			// Calculate Total Records..
			$conditions = array('Patient.office_id' => $Admin['office_id'], 'Patient.is_delete' => 0);
			$totalData = $this->Patient->find('count', array('conditions' => $conditions));
			$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		}
		//creating virtual field for office name
		$this->Patient->virtualFields['office_name'] = "select name from mmd_offices as O where O.id=Patient.office_id ";


		// if there is a search parameter, $requestData['search']['value'] contains search parameter
		if (!empty($requestData['search']['value'])) {
			$conditions['AND'] = array(
				'OR' => array(
					//"Patient.id LIKE "=>$requestData['search']['value']."%",
					"Patient.id_number LIKE " => $requestData['search']['value'] . "%",
					"Patient.first_name LIKE " => $requestData['search']['value'] . "%",
					"Patient.last_name LIKE" => $requestData['search']['value'] . "%",
					"Patient.email LIKE" => $requestData['search']['value'] . "%",
					"Patient.office_name LIKE" => $requestData['search']['value'] . "%",
					//"Patient.status"=>$requestData['search']['value']."%",

				)
			);
			$totalFiltered = $this->Patient->find('count', array('conditions' => $conditions));
		}


		$datas = $this->Patient->find(
			'all', array(
				'conditions' => $conditions,
				'order' => array($columns[$requestData['order'][0]['column']] . " " . strtoupper($requestData['order'][0]['dir'])),
				'limit' => $requestData['length'],
				'offset' => $requestData['start'],
			)
		);
		$data = array();
		$i = 0;

		if (($requestData['order'][0]['column'] == 0) && ($requestData['order'][0]['dir'] == 'asc')) {
			$page = $totalFiltered;
		} else {
			$page = $requestData['start'];
		}
		foreach ($datas as $key => $patient) {
			if (($requestData['order'][0]['column'] == 0) && ($requestData['order'][0]['dir'] == 'asc')) {
				$data[$i]['id'] = $page - $key - $requestData['start'];
			} else {
				$data[$i]['id'] = $page + $key + 1;
			}
			$data[$i]['table_id'] = $patient['Patient']['id'];
			$data[$i]['id_number'] = $patient['Patient']['id_number'];
			$data[$i]['first_name'] = $patient['Patient']['first_name'];
			$data[$i]['last_name'] = $patient['Patient']['last_name'];
			$data[$i]['email'] = $patient['Patient']['email'];
			$data[$i]['office_name'] = $patient['Patient']['office_name'];
			if (!empty($patient['Patient']['p_profilepic'])) {
				$data[$i]['p_profilepic'] = "<a href=" . WWW_BASE . $patient['Patient']['p_profilepic'] . " target='_blank'><img src=" . WWW_BASE . $patient['Patient']['p_profilepic'] . " width='80px;'></a>";
			} else {
				$data[$i]['p_profilepic'] = "<img src=" . WWW_BASE . 'img/uploads/no-user.png' . " width='80px;'>";
				//$data[$i]['p_profilepic'] = WWW_BASE.'img/uploads/no-user.png';
			}

			$status_data = ($patient['Patient']['status'] == 1) ? "Active" : "Inactive";
			$data[$i]['status'] = "<a href=" . WWW_BASE . "admin/patients/changeStatus/" . $patient['Patient']['id'] . ">" . $status_data . "</a>";
			$data[$i]['patient_view'] = "<a style='cursor: pointer;' type='button' title='View' subAdminId='" . $patient['Patient']['id'] . "'class='SubAdminDetail' data-toggle='modal' data-target='#subAdminView'><i class='fa fa-eye' aria-hidden='true'></i></a>&nbsp;&nbsp;<a title='Edit' href=" . WWW_BASE . "admin/patients/edit/" . $patient['Patient']['id'] . "><i class='fa fa-pencil' aria-hidden='true'></i></a>&nbsp;&nbsp;<a title='Delete' class='delete_patient'  href=" . WWW_BASE . "admin/patients/delete/" . $patient['Patient']['id'] . "><i class='fa fa-trash-o'></i></a>";

			$i++;
		}
		//print_r($requestData);
		$json_data = array(
			"draw" => intval($requestData['draw']),
			"recordsTotal" => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data" => $data
		);


		//pr($json_data);die;
		echo json_encode($json_data);
	}


	//This function for add Patient
	public function admin_addPatient(){
		$this->loadModel('Patient');
		$TestTypethresholdArray = $this->Common->TestTypethresholdArray();
		$Admin = $this->Session->read('Auth.Admin');
		if (($Admin['user_type'] == 'Subadmin')) {
			$users_data = $this->User->find('all', array('conditions' => array('User.office_id' => $Admin['office_id']), 'fields' => array('User.*')));
			foreach ($users_data as $k => $user) {
				if ($user['User']['id'] == $Admin['id']) {
					$users_da[$user['User']['id']] = "My-Self";
				} else {
					$users_da[$user['User']['id']] = $user['User']['first_name'];
				}
			}
			$this->set(compact('users_da'));
			$TestDevice =$this->TestDevice->find('all',array('conditions' => array('TestDevice.office_id' => $Admin['office_id'],'TestDevice.device_type' => 6)));
		}else{
			$TestDevice =$this->TestDevice->find('all',array('conditions' => array('TestDevice.device_type' => 6)));
		}
		
		if(!empty($TestDevice)){
			$TestDeviceslist = $TestDevice;		
		}
		$this->set(compact('TestTypethresholdArray','TestDeviceslist'));
		if ($this->request->is('post')) { 
			if (empty($this->request->data['Patient']['email'])) {
				$this->Patient->validator()->remove('email', 'email');
				$this->Patient->validator()->remove('email', 'unique');
			}
			/* if(empty($this->request->data['Patient']['id_number'])){
				$this->Patient->validator()->remove('id_number', 'unique');
			} */
			//pr($this->request->data); die;
			$this->request->data['Patient']['first_name'] = preg_replace('/[^A-Za-z0-9\-]/', '_', $this->request->data['Patient']['first_name']);
			$this->request->data['Patient']['middle_name'] = preg_replace('/[^A-Za-z0-9\-]/', '_', $this->request->data['Patient']['middle_name']);
			$this->request->data['Patient']['last_name'] = preg_replace('/[^A-Za-z0-9\-]/', '_', $this->request->data['Patient']['last_name']);
			$this->request->data['Patient']['first_name'] = str_replace('-', '_', $this->request->data['Patient']['first_name']);
			$this->request->data['Patient']['middle_name'] = str_replace('-', '_', $this->request->data['Patient']['middle_name']);
			$this->request->data['Patient']['last_name'] = str_replace("-", "_", $this->request->data['Patient']['last_name']);
			$this->request->data['Patient']['status'] = $this->request->data['status'];
			//if($Admin['user_type']=="Staffuser" || $Admin['user_type']=="Subadmin" ) {
				$this->request->data['Patient']['test_name_ihu'] = @$this->request->data['Patient']['test_name_ihu'];
				$this->request->data['Patient']['eye_type'] = @$this->request->data['Patient']['eye_type'];
				$this->request->data['Patient']['progression_deatild'] = @$this->request->data['Patient']['progression_deatild'];
				$this->request->data['Patient']['device_type'] = @$this->request->data['Patient']['device_type'];
			//}
			if ($Admin['user_type'] == 'Subadmin' || $Admin['user_type'] == 'Staffuser') {
				$this->request->data['Patient']['user_id'] = $Admin['id'];
				$this->request->data['Patient']['office_id'] = $Admin['office_id'];
			}
			if($Admin['user_type']=="Staffuser" || $Admin['user_type']=="Subadmin" ) {
				if(!empty($this->request->data['Patient']['device_type'])){
					if(!empty($this->request->data['Patient']['test_name_ihu'])){
						$check_P = $this->Patient->find('all', array('conditions' => array('Patient.office_id' => $Admin['office_id']),
							'fields' => array('Patient.first_name','Patient.last_name','Patient.test_name_ihu','Patient.device_type')));
						foreach($check_P  as $check_device){
							if(($check_device['Patient']['device_type']) == ($this->request->data['Patient']['device_type'])){
								$this->Patient->validationErrors['device_type'] = array("This device already assign, please select another device type.");
							}
						}
					}else{
						$this->Patient->validationErrors['test_name_ihu'] = array("You can't empty test name, please select test name.");
					}
					if(!empty($this->request->data['Patient']['language'])) {
						$this->request->data['Patient']['language']= $this->request->data['Patient']['language'];
					}else{
						$this->request->data['Patient']['language']= 1;
					}
					date_default_timezone_set('UTC');
		            $ihutime = date('Y-m-d H:i:s');
					$this->request->data['Patient']['ihuunassigntime'] = $ihutime; 
					$weekDays = $this->request->data['Patient']['weektime'];
					if($weekDays == 0 || empty($weekDays)){
						$this->Patient->updateAll(array('Patient.device_type' => NULL,'Patient.ihuunassigntime' => NULL,'Patient.progression_deatild' => NULL,'Patient.language' => NULL,'Patient.test_name_ihu' => NULL,'Patient.eye_type' => NULL),array('Patient.id NOT'=>$id));
						$this->Patient->validationErrors['weektime'] = array("You can assign the device for atleast 1 Days."); 
					}
				}
			}
			if (!empty($this->request->data['Patient']['yy']) && !empty($this->request->data['Patient']['mm']) && !empty($this->request->data['Patient']['dd'])) {
				$this->request->data['Patient']['dob'] = date("Y-m-d", strtotime($this->request->data['Patient']['dd'] . '-' . $this->request->data['Patient']['mm'] . '-' . $this->request->data['Patient']['yy']));
			} else {
				$this->request->data['Patient']['dob'] = '';
			}
			date_default_timezone_set('UTC');
            $UTCDate = date('Y-m-d H:i:s');
			$this->request->data['Patient']['created_date_utc'] = $UTCDate; 
			$systemdate = $this->request->data['cTimeSystem'];
			$chkdtarr=explode("GMT",$systemdate); 
			$newdt= strtotime($chkdtarr[0]); 
			$sysDate =   date("Y-m-d h:i:s",$newdt);
			$this->request->data['Patient']['created'] = $sysDate; //pr($this->request->data); die;
			if((!isset($this->Patient->validationErrors['device_type'])) && (!isset($this->Patient->validationErrors['test_name_ihu']))){
			if ($this->Patient->save($this->request->data, array('validate' => true))) {
				$Admin = $this->Session->read('Auth.Admin');
				$role_constant = Configure::read('role_constant');
				$status_1 = 1;
				$this->AppConstant->updateAll(array('AppConstant.is_update' => "'" . $status_1 . "'"), array('AppConstant.id' => 1));
				$this->loadModel('NewUserDevice');
				$office_id = $this->request->data['Patient']['office_id'];
				$currentOrderAlert =  $this->Patient->query("CREATE OR REPLACE VIEW mmd_patients_".$office_id." AS  SELECT  `id`, `unique_id`, `user_id`, `first_name`, `middle_name`, `last_name`, `email`, `phone`, `id_number`, `office_id`, `dob`, `is_delete`, `delete_date`, `notes`, `status`, `archived_date`, `p_profilepic`, `od_left`, `od_right`, `os_left`, `os_right`, `race`,`created_date_utc`, `created`,`created_at_for_archive`  FROM   `mmd_patients` WHERE mmd_patients.user_id in (SELECT id from mmd_users where office_id=".$office_id.")");

				$allOfficeUser = $this->User->find('list', array(
					'fields' => array('User.id'),
					'conditions' => array('User.office_id' => $office_id)
				));
				$new_user_device = $this->NewUserDevice->find('all', array('conditions' => array('NewUserDevice.user_id' => $allOfficeUser)));
				if (isset($new_user_device) && !empty($new_user_device) && $this->checkNotification_status($office_id)) {
					foreach ($new_user_device as $key => $val) {
						$device_token = $val['NewUserDevice']['device_token'];
						if (!empty($device_token)) {
							$res = $this->sendPushNotificationNewAdminDataUpdate($device_token);
							$res1 = $this->sendPushNotificationNewAdminDataUpdateV2($device_token);
						}
					}
				}
				$this->Session->setFlash("New Patient has been created successfully.", 'message', array('class' => 'message'));
				$this->redirect(array('controller' => 'patients', 'action' => 'admin_patients_listing'));
			}
		}
		}
	}


	public function admin_getStaffListByCompanyId()
	{
		$this->autoRender = false;
		$company_id = $this->request->query['company_id'];
		if (!empty($company_id)) {
			$users_data = $this->User->find('all', array('conditions' => array('User.office_id' => $company_id), 'fields' => array('User.*')));
			foreach ($users_data as $k => $user) {
				if ($user['User']['user_type'] == "Subadmin") {
					$users_da[$user['User']['id']] = $user['User']['first_name'] . " (Subadmin)";
				} else {
					$users_da[$user['User']['id']] = $user['User']['first_name'];
				}
			}
			///pr($users_da) ; die;
			echo json_encode($users_da);
			die;
		}
	}

	/*This function for delete patients*/
	public function admin_delete($id = null)
	{
		//print_r($id);die;
		$this->loadModel('AppConstant');
		$this->autoRender = false;
		if ($id) {
			$check_Patient = $this->Patient->find('first', array(
				'conditions' => array('Patient.id' => $id),
				'fields' => array('Patient.id')
			));

			if (!empty($check_Patient)) {
				/*Delete status of patient */

				$delete_patient['Patient']['id'] = $id;
				$delete_patient['Patient']['is_delete'] = 1;
				$delete_patient['Patient']['status'] = 0;
				$delete_patient['Patient']['delete_date'] = date('Y-m-d H:i:s');
				if ($this->Patient->save($delete_patient)) {
					$this->loadModel('Testreport');
					$this->loadModel('Pointdata');
					$this->loadModel('VaData');
					$this->loadModel('NewUserDevice');
					$this->loadModel('CsData');
					$this->Testreport->updateAll(array('Testreport.is_delete' => '1'), array('Testreport.patient_id' => $id));
					$this->Pointdata->updateAll(array('Pointdata.is_delete' => '1'), array('Pointdata.patient_id' => $id));
					$this->VaData->updateAll(array('VaData.is_delete' => '1'), array('VaData.patient_id' => $id));
					$this->CsData->updateAll(array('CsData.is_delete' => '1'), array('CsData.patient_id' => $id));
					$Admin = $this->Session->read('Auth.Admin');
					$role_constant = Configure::read('role_constant');

					$this->Session->setFlash('Patient has been deleted successfully.', 'message', array('class' => 'message'));

					$this->redirect(array('controller' => 'patients', 'action' => 'admin_patients_listing'));
				} else {
					$this->Session->setFlash('Oops! There is some problem in deleting patient.', 'message', array('class' => 'message'));
					$this->redirect(array('controller' => 'patients', 'action' => 'admin_patients_listing'));

				}
			} else {
				$this->Session->setFlash('Invalid patient.', 'message', array('class' => 'message'));
				$this->redirect(array('controller' => 'patients', 'action' => 'admin_patients_listing'));
			}
		}
	}

	/*This function for delete patients*/
	public function admin_delete1($id = null)
	{
		//print_r($id);die;
		$this->loadModel('AppConstant');
		$this->autoRender = false;
		if ($id) {
			$check_Patient = $this->Patient->find('first', array(
				'conditions' => array('Patient.id' => $id),
				'fields' => array('Patient.id')
			));
			if (!empty($check_Patient)) {
				/*Delete status of patient */

				$delete_patient['Patient']['id'] = $id;
				$delete_patient['Patient']['is_delete'] = 1;
				$delete_patient['Patient']['status'] = 0;
				$delete_patient['Patient']['delete_date'] = date('Y-m-d H:i:s');
				if ($this->Patient->save($delete_patient)) {
					$this->loadModel('Testreport');
					$this->loadModel('Pointdata');
					$this->loadModel('VaData');
					$this->loadModel('NewUserDevice');
					$this->loadModel('CsData');
					$this->Testreport->updateAll(array('Testreport.is_delete' => '1'), array('Testreport.patient_id' => $id));
					$this->Pointdata->updateAll(array('Pointdata.is_delete' => '1'), array('Pointdata.patient_id' => $id));
					$this->VaData->updateAll(array('VaData.is_delete' => '1'), array('VaData.patient_id' => $id));
					$this->CsData->updateAll(array('CsData.is_delete' => '1'), array('CsData.patient_id' => $id));
					$Admin = $this->Session->read('Auth.Admin');
					$role_constant = Configure::read('role_constant');
					//pr($Admin['user_type']);die;
					if (in_array($Admin['user_type'], $role_constant)) {
						$status_1 = 1;
						$this->AppConstant->updateAll(array('AppConstant.is_update' => "'" . $status_1 . "'"), array('AppConstant.id' => 1));
						$this->loadModel('NewUserDevice');
						$new_user_device = $this->NewUserDevice->find('all');
						//pr($new_user_device);die;
						foreach ($new_user_device as $key => $val) {
							$device_token = $val['NewUserDevice']['device_token'];
							if (!empty($device_token) && $this->checkNotification($val['NewUserDevice']['device_id'])) {
								$res = $this->sendPushNotificationNewAdminDataUpdateV2($device_token);
								//$res = $this->sendPushNotificationNewAdminDataUpdate($device_token);
							}
						}

					}
					$this->Session->setFlash('Patient has been deleted successfully.', 'message', array('class' => 'message'));

					$this->redirect(array('controller' => 'patients', 'action' => 'admin_patients_listing'));
				} else {
					$this->Session->setFlash('Oops! There is some problem in deleting patient.', 'message', array('class' => 'message'));
					$this->redirect(array('controller' => 'patients', 'action' => 'admin_patients_listing'));

				}
			} else {
				$this->Session->setFlash('Invalid patient.', 'message', array('class' => 'message'));
				$this->redirect(array('controller' => 'patients', 'action' => 'admin_patients_listing'));
			}
		}
	}

	public function admin_delete_revert($id = null)
	{
		//print_r($id);
		$this->loadModel('AppConstant');
		$this->autoRender = false;
		if ($id) {
			$check_Patient = $this->Patient->find('first', array(
				'conditions' => array('Patient.id' => $id),
				'fields' => array('Patient.id')
			));
			//pr($check_Patient);die;
			if (!empty($check_Patient)) {
				/*Delete status of patient */
				$delete_patient['Patient']['id'] = $id;
				$delete_patient['Patient']['is_delete'] = 0;
				$delete_patient['Patient']['delete_date'] = null;

				if ($this->Patient->save($delete_patient)) {
					$this->loadModel('Testreport');
					$this->loadModel('Pointdata');
					$this->loadModel('VaData');
					$this->loadModel('NewUserDevice');
					$this->loadModel('CsData');
					$this->Testreport->updateAll(array('Testreport.is_delete' => '0'), array('Testreport.patient_id' => $id));
					$this->Pointdata->updateAll(array('Pointdata.is_delete' => '0'), array('Pointdata.patient_id' => $id));
					$this->VaData->updateAll(array('VaData.is_delete' => '0'), array('VaData.patient_id' => $id));
					$this->CsData->updateAll(array('CsData.is_delete' => '0'), array('CsData.patient_id' => $id));
					$Admin = $this->Session->read('Auth.Admin');
					$role_constant = Configure::read('role_constant');
					//pr($Admin['user_type']);die;
					if (in_array($Admin['user_type'], $role_constant)) {
						$status_1 = 1;
						$this->AppConstant->updateAll(array('AppConstant.is_update' => "'" . $status_1 . "'"), array('AppConstant.id' => 1));
						$this->loadModel('NewUserDevice');
						$new_user_device = $this->NewUserDevice->find('all');
						//pr($new_user_device);die;
						foreach ($new_user_device as $key => $val) {
							$device_token = $val['NewUserDevice']['device_token'];
							if (!empty($device_token) && $this->checkNotification($val['NewUserDevice']['device_id'])) {
								$res = $this->sendPushNotificationNewAdminDataUpdateV2($device_token);
								//$res = $this->sendPushNotificationNewAdminDataUpdate($device_token);
							}
						}

					}
					$this->Session->setFlash('Patient has been reverted successfully.', 'message', array('class' => 'message'));

					$this->redirect(array('controller' => 'patients', 'action' => 'admin_patients_listing'));
				} else {
					$this->Session->setFlash('Oops! There is some problem in deleting patient.', 'message', array('class' => 'message'));
					$this->redirect(array('controller' => 'patients', 'action' => 'admin_patients_listing'));

				}
			} else {
				$this->Session->setFlash('Invalid patient.', 'message', array('class' => 'message'));
				$this->redirect(array('controller' => 'patients', 'action' => 'admin_patients_listing'));
			}
		}
	}

	public function admin_deleteP($id = null)
	{
		//print_r($id);die;
		$this->loadModel('AppConstant');
		$this->autoRender = false;
		if ($id) {
			$check_Patient = $this->Patient->find('first', array(
				'conditions' => array('Patient.id' => $id),
				'fields' => array('Patient.id')
			));
			if (!empty($check_Patient)) {
				/*Delete status of patient */
				$delete_patient['Patient']['id'] = $id;
				$delete_patient['Patient']['is_delete'] = 1;
				$delete_patient['Patient']['status'] = 0;
				$delete_patient['Patient']['delete_date'] = null;
				if ($this->Patient->save($delete_patient)) {


					$this->loadModel('NewUserDevice');

					$Admin = $this->Session->read('Auth.Admin');
					$role_constant = Configure::read('role_constant');
					//pr($Admin['user_type']);die;
					if (in_array($Admin['user_type'], $role_constant)) {
						$status_1 = 1;
						$this->AppConstant->updateAll(array('AppConstant.is_update' => "'" . $status_1 . "'"), array('AppConstant.id' => 1));
						$this->loadModel('NewUserDevice');
						$new_user_device = $this->NewUserDevice->find('all');
						//pr($new_user_device);die;
						foreach ($new_user_device as $key => $val) {
							$device_token = $val['NewUserDevice']['device_token'];
							if (!empty($device_token) && $this->checkNotification($val['NewUserDevice']['device_id'])) {
								$res = $this->sendPushNotificationNewAdminDataUpdateV2($device_token);
								//$res = $this->sendPushNotificationNewAdminDataUpdate($device_token);
							}
						}

					}
					$this->Session->setFlash('Patient has been deleted successfully.', 'message', array('class' => 'message'));

					$this->redirect(array('controller' => 'patients', 'action' => 'admin_patients_listing'));
				} else {
					$this->Session->setFlash('Oops! There is some problem in deleting patient.', 'message', array('class' => 'message'));
					$this->redirect(array('controller' => 'patients', 'action' => 'admin_patients_listing'));

				}
			} else {
				$this->Session->setFlash('Invalid patient.', 'message', array('class' => 'message'));
				$this->redirect(array('controller' => 'patients', 'action' => 'admin_patients_listing'));
			}
		}
	}

	public function admin_upload_pdf($id = null)
	{
		$this->loadModel('ActTest');
		$this->loadModel('StbTest'); 
		$this->loadModel('VtTest');
		$this->loadModel('DarkAdaption');
		$this->loadModel('PupTest');
		$TestNameArray = $this->Common->testNameArray();
		$testNameVisualFieldsArray = $this->Common->testNameVisualFieldsArray();
		$testNameFDTArray = $this->Common->testNameFDTArray();
		$TestTypeArray = $this->Common->TestTypeArray();
		$patientData = $this->Patient->find('first', array('conditions' => array('Patient.id' => $id)));
		$patientName = $patientData['Patient']['first_name'] . " " . $patientData['Patient']['middle_name'] . " " . $patientData['Patient']['last_name'];
		if ($this->request->is(array('post', 'put'))) {
			$staff_id = $this->Auth->user('id');
			$staffName = $this->Auth->user('first_name') . " " . $this->Auth->user('middle_name') . " " . $this->Auth->user('last_name');
			if (!empty($this->request->data['vib_staff'])) {
				$staff_id = $this->request->data['vib_staff'];
			}
			$filename = '';
			if (!empty($this->request->data['PatientFiles']['file_name']['tmp_name']) && ($this->request->data['PatientFiles']['test_type'] == 'Visual_Fields' || $this->request->data['PatientFiles']['test_type'] == 'F_D_T' || $this->request->data['PatientFiles']['test_type'] == 'Visual_Screening')) {
				$filename = time() . '.pdf';
				move_uploaded_file(
					$this->request->data['PatientFiles']['file_name']['tmp_name'],
					WWW_ROOT . DS . 'pointData' . DS . $filename
				);
			}
			if (!empty($this->request->data['PatientFiles']['file_name']['tmp_name']) && $this->request->data['PatientFiles']['test_type'] == 'Dark_Adaption') {
				$filename = time() . '.pdf';
				move_uploaded_file(
					$this->request->data['PatientFiles']['file_name']['tmp_name'],
					WWW_ROOT . 'uploads/darkadaption' . DS . $filename
				);
			}

			if (!empty($this->request->data['PatientFiles']['file_name']['tmp_name']) && $this->request->data['PatientFiles']['test_type'] == 'Pupolimeter') {
				$filename = time() . '.pdf';
				move_uploaded_file(
					$this->request->data['PatientFiles']['file_name']['tmp_name'],
					WWW_ROOT . 'PupTestControllerData' . DS . $filename
				);
			}
			if (!empty($this->request->data['PatientFiles']['file_name']['tmp_name']) && $this->request->data['PatientFiles']['test_type'] == 'ACT') {
				$filename = time() . '.pdf';
				move_uploaded_file(
					$this->request->data['PatientFiles']['file_name']['tmp_name'],
					WWW_ROOT . 'ActTestControllerData' . DS . $filename
				);
			}
			if (!empty($this->request->data['PatientFiles']['file_name']['tmp_name']) && $this->request->data['PatientFiles']['test_type'] == 'Strabismus_Screening') {
				$filename = time() . '.pdf';
				move_uploaded_file(
					$this->request->data['PatientFiles']['file_name']['tmp_name'],
					WWW_ROOT . 'uploads/stbdata' . DS . $filename
				);
			}
			if (!empty($this->request->data['PatientFiles']['file_name']['tmp_name']) && $this->request->data['PatientFiles']['test_type'] == 'Vision_Therapy_Test') {
				$filename = time() . '.pdf';
				move_uploaded_file(
					$this->request->data['PatientFiles']['file_name']['tmp_name'],
					WWW_ROOT . 'VtTestControllerData' . DS . $filename
				);
			} 
			$systemdate = $this->request->data['uploadtime'];
			$chkdtarr=explode("GMT",$systemdate);
			$newdt= strtotime($chkdtarr[0]); 
			$sysDate =   date("h:i:s",$newdt); 
			$date = $this->request->data['PatientFiles']['date_created']; 
			$curtime =  date('Y-m-d', strtotime($date)).' '.$sysDate; 
			// Set the file-name only to save in the database
			if ($this->request->data['PatientFiles']['test_type'] == 'Visual_Fields' || $this->request->data['PatientFiles']['test_type'] == 'F_D_T' || $this->request->data['PatientFiles']['test_type'] == 'Visual_Screening') {

				$data['Pointdata']['test_type_id'] = 0;
				if($this->request->data['PatientFiles']['test_type'] == 'Visual_Screening'){
					$data['Pointdata']['test_name'] = 'Vision Screening';
				}else{
					$data['Pointdata']['test_name'] = $this->request->data['PatientFiles']['test_report_id'];
				}
				$data['Pointdata']['numpoints'] = 0;
				$data['Pointdata']['color'] = 0;
				$data['Pointdata']['backgroundcolor'] = 0;
				$data['Pointdata']['stmsize'] = 0;
				$data['Pointdata']['file'] = $filename;
				$data['Pointdata']['staff_id'] = $staff_id;
				$data['Pointdata']['patient_id'] = $this->request->data['PatientFiles']['id'];
				$data['Pointdata']['eye_select'] = $this->request->data['PatientFiles']['eye'];
				$data['Pointdata']['baseline'] = 0;
				$data['Pointdata']['is_delete'] = 0;
				$data['Pointdata']['master_key'] = 0;
				$data['Pointdata']['test_color_fg'] = 0;
				$data['Pointdata']['test_color_bg'] = 0;
				//$data['Pointdata']['created'] = !empty($this->request->data['PatientFiles']['date_created']) ? date('Y-m-d H:i:s', strtotime($this->request->data['PatientFiles']['date_created'] . ' ' . $curtime)) : date('Y-m-d H:i:s');
				$data['Pointdata']['created'] = $curtime;
				date_default_timezone_set('UTC');
            	$UTCDate = date('Y-m-d H:i:s');
				$data['Pointdata']['created_date_utc'] = $UTCDate;
				$data['Pointdata']['source'] = 'M';

				if ($this->Pointdata->save($data)) {
					$this->Session->setFlash('File uploaded successfully.', 'message', array('class' => 'message'));
					$this->redirect(array('controller' => 'patients', 'action' => 'admin_patients_listing'));
				} else {
					$this->Session->setFlash('Please check your input and try again.', 'message', array('class' => 'message'));
				}
			}else if($this->request->data['PatientFiles']['test_type'] == 'Vision_Therapy_Test'){
				$data['VtTest']['test_name'] = 'Vision Therapy Test';
				$data['VtTest']['file'] = $filename;
				$data['VtTest']['testId'] = 0;
				$data['VtTest']['staff_id'] = $staff_id;
				$data['VtTest']['patient_id'] = $this->request->data['PatientFiles']['id'];
				$data['VtTest']['office_id'] = $this->request->data['PatientFiles']['office_id'];
				$data['VtTest']['patient_name'] = $patientName;
				$data['VtTest']['is_delete'] = 0;
				$data['VtTest']['age_group'] = 0;
				$data['VtTest']['device_id'] = 0;
				//$data['VtTest']['created'] = !empty($this->request->data['PatientFiles']['date_created']) ? date('Y-m-d H:i:s', strtotime($this->request->data['PatientFiles']['date_created'] . ' ' . $curtime)) : date('Y-m-d H:i:s');
				$data['VtTest']['created'] = $curtime;
				date_default_timezone_set('UTC');
            	$UTCDate = date('Y-m-d H:i:s');
				$data['VtTest']['created_date_utc'] = $UTCDate;
				$data['VtTest']['source'] = 'M';
				if ($this->VtTest->save($data)) {
					$this->Session->setFlash('File uploaded successfully.', 'message', array('class' => 'message'));
					$this->redirect(array('controller' => 'patients', 'action' => 'admin_patients_listing'));
				} else {
					$this->Session->setFlash('Please check your input and try again.', 'message', array('class' => 'message'));
				}
		}else if($this->request->data['PatientFiles']['test_type'] == 'Strabismus_Screening'){
				$data['StbTest']['test_type_id'] = 0;
				$data['StbTest']['test_name'] = 'Strabismus Screening';
				$data['StbTest']['file'] = $filename;
				$data['StbTest']['staff_id'] = $staff_id;
				$data['StbTest']['patient_id'] = $this->request->data['PatientFiles']['id'];
				$data['StbTest']['eye_select'] = $this->request->data['PatientFiles']['eye'];
				$data['StbTest']['office_id'] = $this->request->data['PatientFiles']['office_id'];
				$data['StbTest']['patient_name'] = $patientName;
				$data['StbTest']['baseline'] = 1;
				$data['StbTest']['is_delete'] = 0;
				$data['StbTest']['age_group'] = 0;
				$data['StbTest']['device_id'] = 0;
				$data['StbTest']['master_key'] = 0;
				$data['StbTest']['created'] = $curtime;
				date_default_timezone_set('UTC');
            	$UTCDate = date('Y-m-d H:i:s');
				$data['StbTest']['created_date_utc'] = $UTCDate;
				$data['StbTest']['source'] = 'M';
				if ($this->StbTest->save($data)) {
					$this->Session->setFlash('File uploaded successfully.', 'message', array('class' => 'message'));
					$this->redirect(array('controller' => 'patients', 'action' => 'admin_patients_listing'));
				} else {
					$this->Session->setFlash('Please check your input and try again.', 'message', array('class' => 'message'));
				}
		} else if($this->request->data['PatientFiles']['test_type'] == 'Dark_Adaption') {
				$data['DarkAdaption']['pdf'] = $filename;
				$data['DarkAdaption']['staff_id'] = $staff_id;
				$data['DarkAdaption']['test_name'] = 'Dark Adaption';
				$data['DarkAdaption']['patient_id'] = $this->request->data['PatientFiles']['id'];
				$data['DarkAdaption']['office_id'] = $this->request->data['PatientFiles']['office_id'];
				$data['DarkAdaption']['eye_select'] = $this->request->data['PatientFiles']['eye'];
				$data['DarkAdaption']['is_delete'] = 0;
				$data['DarkAdaption']['patient_name'] = $patientName;
				$data['DarkAdaption']['staff_name'] = $this->getStaffName($staff_id);
				$data['DarkAdaption']['source'] = 'M';
				$data['DarkAdaption']['created'] = $curtime;
				date_default_timezone_set('UTC');
            	$UTCDate = date('Y-m-d H:i:s');
				$data['DarkAdaption']['created_date_utc'] = $UTCDate;
				$data['DarkAdaption']['test_date_time'] = !empty($this->request->data['PatientFiles']['date_created']) ? date('Y-m-d H:i:s', strtotime($this->request->data['PatientFiles']['date_created'] . ' ' . $curtime)) : date('Y-m-d H:i:s');
				if ($this->DarkAdaption->save($data)) {
					$this->Session->setFlash('File uploaded successfully.', 'message', array('class' => 'message'));
					$this->redirect(array('controller' => 'patients', 'action' => 'admin_patients_listing'));
				} else {
					$this->Session->setFlash('Please check your input and try again.', 'message', array('class' => 'message'));
				}
			}else if($this->request->data['PatientFiles']['test_type'] == 'ACT'){
				$data['ActTest']['file'] = $filename;
				$data['ActTest']['testId'] = 0;
				$data['ActTest']['device_id'] = 0;
				$data['ActTest']['version'] = '';
				$data['ActTest']['staff_id'] = $staff_id;
				$data['ActTest']['patient_id'] = $this->request->data['PatientFiles']['id'];
				$data['ActTest']['office_id'] = $this->request->data['PatientFiles']['office_id'];
				$data['ActTest']['is_delete'] = 0;
				$data['ActTest']['test_name'] = 'Advance Color Test';
				$data['ActTest']['patient_name'] = $patientName;
				$data['ActTest']['staff_name'] = $this->getStaffName($staff_id);
				$data['ActTest']['source'] = 'M';
				$data['ActTest']['created'] = $curtime;
				date_default_timezone_set('UTC');
            	$UTCDate = date('Y-m-d H:i:s');
				$data['ActTest']['created_date_utc'] = $UTCDate;
				if ($this->ActTest->save($data)) {
					$this->Session->setFlash('File uploaded successfully.', 'message', array('class' => 'message'));
					$this->redirect(array('controller' => 'patients', 'action' => 'admin_patients_listing'));
				} else {
					$this->Session->setFlash('Please check your input and try again.', 'message', array('class' => 'message'));
				}
			}else if($this->request->data['PatientFiles']['test_type'] == 'Pupolimeter'){
				$this->loadModel('PupTest');
				$data['PupTest']['file'] = $filename;
				$data['PupTest']['staff_id'] = $staff_id;
				$data['PupTest']['test_name'] = 'Pupolimeter';
				$data['PupTest']['patient_id'] = $this->request->data['PatientFiles']['id'];
				$data['PupTest']['office_id'] = $this->request->data['PatientFiles']['office_id'];
				$data['PupTest']['eye_select'] = $this->request->data['PatientFiles']['eye'];
				$data['PupTest']['is_delete'] = 0;
				$data['PupTest']['patient_name'] = $patientName;
				$data['PupTest']['staff_name'] = $this->getStaffName($staff_id);
				$data['PupTest']['source'] = 'M';
				$data['PupTest']['created'] = $curtime;
				date_default_timezone_set('UTC');
            	$UTCDate = date('Y-m-d H:i:s');
				$data['PupTest']['created_date_utc'] = $UTCDate;
				if ($this->PupTest->save($data)) {
					$this->Session->setFlash('File uploaded successfully.', 'message', array('class' => 'message'));
					$this->redirect(array('controller' => 'patients', 'action' => 'admin_patients_listing'));
				} else {
					$this->Session->setFlash('Please check your input and try again.', 'message', array('class' => 'message'));
				}
			}
			
		}
		$this->loadModel('Office');
		$offices = $this->Office->find('all', [
			'fields' => ['Office.id', 'Office.name'],
			'conditions' => ['Office.is_delete' == '0']
		]);
		/*echo "<pre>";
		print_r($offices); die;*/
		$this->set(compact(['id', 'TestNameArray','testNameFDTArray', 'testNameVisualFieldsArray', 'patientData', 'offices', 'TestTypeArray']));
	}

	public function admin_allSattfinOfice()
	{

		$post = $this->request->data;
		$this->loadModel('User');

		$staffs = $this->User->find('all', [
			'fields' => ['id', 'User.first_name', 'User.middle_name', 'User.last_name'],
			'conditions' => ['User.is_delete' => '0', 'User.office_id' => $post['office_id']]
		]);

		$response = array();

		$html = '<option value="0">Select Staff</option>';
		if (count($staffs)) {
			//$html='';
			foreach ($staffs as $staff) {
				$html .= '<option value="' . $staff['User']['id'] . '">' . $staff['User']['first_name'] . ' ' . $staff['User']['middle_name'] . ' ' . $staff['User']['last_name'] . '</option>';
			}
		}
		$response['status'] = 'success';
		$response['option'] = $html;
		$response['office_id'] = $post['office_id'];

		$this->autoRender = false;
		$this->response->type('json');
		$json = json_encode($response);
		$this->response->body($json);
	}

	/*This function for edit patients*/
	public function admin_edit($id = null)
	{
		$TestTypethresholdArray = $this->Common->TestTypethresholdArray();
		$this->loadModel('AppConstant');
		$data = $this->Patient->find('first', array('conditions' => array('Patient.id' => $id)));
		if (empty($data['Patient']['dob'])) {
			$data['Patient']['dob'] = '';
		}

		$Admin = $this->Session->read('Auth.Admin');
		if (($Admin['user_type'] == 'Subadmin')) {
			$selected = $data['Patient']['user_id'];
			$users_data = $this->User->find('all', array('conditions' => array('User.office_id' => $Admin['office_id']), 'fields' => array('User.*')));

			foreach ($users_data as $k => $user) {
				if ($user['User']['id'] == $Admin['id']) {
					$users_da[$user['User']['id']] = "My-Self";
				} else {
					$users_da[$user['User']['id']] = $user['User']['first_name'];
				}
			}
			$this->set(compact('users_da', 'selected'));
			$TestDevice =$this->TestDevice->find('all',array('conditions' => array('TestDevice.office_id' => $Admin['office_id'],'TestDevice.device_type' => 6)));
		}
		$TestDevice =$this->TestDevice->find('all',array('conditions' => array('TestDevice.device_type' => 6)));
		if(!empty($TestDevice)){
			$TestDeviceslist = $TestDevice;		
		}
		$this->set(compact('TestTypethresholdArray','TestDeviceslist'));
		if (($Admin['user_type'] == 'Admin')) {
			$selected = $data['Patient']['user_id'];
			$users_data = $this->User->find('all', array('conditions' => array('User.office_id' => $data['Patient']['office_id']), 'fields' => array('User.*')));

			foreach ($users_data as $k => $user) {
				$users_da[$user['User']['id']] = $user['User']['first_name'];
			}
			$this->set(compact('users_da', 'selected'));
		}
		$this->set(compact('data'));
		if ($this->request->is(array('post', 'put'))) {

			if (empty($this->request->data['Patient']['email'])) {
				$this->Patient->validator()->remove('email');
			}
			if (empty($this->request->data['Patient']['id_number'])) {
				$this->Patient->validator()->remove('id_number', 'unique');
			}
			$this->request->data['Patient']['first_name'] = preg_replace('/[^A-Za-z0-9\-]/', '_', $this->request->data['Patient']['first_name']);
			$this->request->data['Patient']['middle_name'] = preg_replace('/[^A-Za-z0-9\-]/', '_', $this->request->data['Patient']['middle_name']);
			$this->request->data['Patient']['last_name'] = preg_replace('/[^A-Za-z0-9\-]/', '_', $this->request->data['Patient']['last_name']);
			$this->request->data['Patient']['first_name'] = str_replace('-', '_', $this->request->data['Patient']['first_name']);
			$this->request->data['Patient']['middle_name'] = str_replace('-', '_', $this->request->data['Patient']['middle_name']);
			$this->request->data['Patient']['last_name'] = str_replace("-", "_", $this->request->data['Patient']['last_name']);
			if (!empty($this->request->data['Patient']['yy']) && !empty($this->request->data['Patient']['mm']) && !empty($this->request->data['Patient']['dd'])) {
				$this->request->data['Patient']['dob'] = date("Y-m-d", strtotime($this->request->data['Patient']['dd'] . '-' . $this->request->data['Patient']['mm'] . '-' . $this->request->data['Patient']['yy']));
			} else {
				$this->request->data['Patient']['dob'] = '';
			}
			if($Admin['user_type']=="Staffuser" || $Admin['user_type']=="Subadmin" ) {
				if(!empty($this->request->data['Patient']['device_type'])){
					if(!empty($this->request->data['Patient']['test_name_ihu'])){
							$check_P = $this->Patient->find('all', array('conditions' => array('Patient.office_id' =>$data['Patient']['office_id']),
							'fields' => array('Patient.first_name','Patient.last_name','Patient.test_name_ihu','Patient.device_type')));
							$checkSingleP = $this->Patient->find('first', array('conditions' => array('Patient.id' =>$data['Patient']['id']),
							'fields' => array('Patient.first_name','Patient.last_name','Patient.test_name_ihu','Patient.device_type')));
							if(($checkSingleP['Patient']['device_type']) != ($this->request->data['Patient']['device_type'])){
								foreach($check_P  as $check_device){
									if(($check_device['Patient']['device_type']) == ($this->request->data['Patient']['device_type'])){
										$this->Patient->validationErrors['device_type'] = array(" This device already assign, please select another device type.");
									}
								}
							}
					}else{
						$this->Patient->validationErrors['test_name_ihu'] = array("You can't empty test name, please select test name.");
					}
					if(!empty($this->request->data['Patient']['language'])) {
						$this->request->data['Patient']['language']= $this->request->data['Patient']['language'];
					}else{
						$this->request->data['Patient']['language']= 1;
					}
					date_default_timezone_set('UTC');
		            $ihutime = date('Y-m-d H:i:s');
					$this->request->data['Patient']['ihuunassigntime'] = $ihutime;
					$weekDays = $this->request->data['Patient']['weektime'];
					if($weekDays == 0 || empty($weekDays)){
						$this->Patient->updateAll(array('Patient.device_type' => NULL,'Patient.ihuunassigntime' => NULL,'Patient.progression_deatild' => NULL,'Patient.language' => NULL,'Patient.test_name_ihu' => NULL,'Patient.eye_type' => NULL),array('Patient.id NOT'=>$id));
						$this->Patient->validationErrors['weektime'] = array("You can assign the device for atleast 1 Days."); 
					}
				}
			}
			$this->request->data['Patient']['status'] = $this->request->data['status'];
			if((!isset($this->Patient->validationErrors['device_type'])) && (!isset($this->Patient->validationErrors['test_name_ihu'])) && (!isset($this->Patient->validationErrors['weektime']))){
				if ($this->Patient->save($this->request->data)) {

				$Admin = $this->Session->read('Auth.Admin');
				$role_constant = Configure::read('role_constant');
				$status_1 = 1;
				$this->AppConstant->updateAll(array('AppConstant.is_update' => "'" . $status_1 . "'"), array('AppConstant.id' => 1));


				$this->loadModel('NewUserDevice');
				$office_id = (isset($this->request->data['Patient']['office_id']) && !empty(!isset($this->request->data['Patient']['office_id']))) ? $this->request->data['Patient']['office_id'] : $Admin['Office']['id'];
				$allOfficeUser = $this->User->find('list', array(
					'fields' => array('User.id'),
					'conditions' => array('User.office_id' => $office_id)
				));


				$new_user_device = $this->NewUserDevice->find('all', array('conditions' => array('NewUserDevice.user_id' => $allOfficeUser)));
				//	$new_user_device = $this->NewUserDevice->find('all');
				//pr($new_user_device);die;
				if (isset($new_user_device) && !empty($new_user_device) && $this->checkNotification_status($Admin['Office']['id'])) {
					foreach ($new_user_device as $key => $val) {
						$device_token = $val['NewUserDevice']['device_token'];
						if (!empty($device_token)) {
							$res = $this->sendPushNotificationNewAdminDataUpdate($device_token);
							$res1 = $this->sendPushNotificationNewAdminDataUpdateV2($device_token);
						}
					}
				}
				//}
				$this->Session->setFlash('Patient has been updated successfully.', 'message', array('class' => 'message'));
				$this->redirect(array('controller' => 'patients', 'action' => 'admin_patients_listing'));
				}
			}
		} else {
			$this->request->data = $data;
		}
	}

	/*This function for showing patient detail*/
	public function admin_view($id = null)
	{
		$this->layout = false;
		$this->Patient->unbindModel(
			array('hasMany' => array('Pointdata'))
		);
		$data = $this->Patient->find('first', array('recursive' => 2, 'conditions' => array('Patient.id' => $id)));
		$vfTestname = array("Central_20_Point", "Central_40_Point", "Esterman_120_point", "76_Point_Pattern", "Central_80_Point", "Central_166_Point", "Armally_Central", "Central_10_2", "Central_24_1", "Central_24_2", "Central_30_1", "Central_30_2", "Superior_24_2", "Superior_30_2", "Superior_50_1", "Superior_64", "Neuro_20", "Neuro_35", "Full_Field_120_PTS", "Kinetic_60_16", "Kinetic_30_16", "Kinetic_60_28", "Kinetic_30_28", "Ptosis_9_PT", "Ptosis_Auto_9_PT","Amsler_Grid_10_16");
		$fdtTestname = array("C20-1", "C20-5", "N30-1", "N30-5", "N30");
		$this->loadModel('Pointdata');
		$this->loadModel('DarkAdaption');
		$this->Pointdata->unbindModel(array('belongsTo' => array('User', 'Patient', 'Test'), 'hasMany' => array('VfPointdata')));
		$vf = $this->Pointdata->find('first', ['conditions' => array('Pointdata.patient_id' => $id, 'Pointdata.test_name' => $vfTestname)]);
		$this->Pointdata->unbindModel(array('belongsTo' => array('User', 'Patient', 'Test'), 'hasMany' => array('VfPointdata')));
		$fdt = $this->Pointdata->find('first', ['conditions' => array('Pointdata.patient_id' => $id, 'Pointdata.test_name' => $fdtTestname)]);
		$this->DarkAdaption->unbindModel(array('belongsTo' => array('Office', 'User', 'Patient'), 'hasMany' => array('DaPointData')));
		$da = $this->DarkAdaption->find('first', ['conditions' => array('DarkAdaption.patient_id' => $id)]);
		$test['vf'] = (empty($vf)) ? 0 : 1;
		$test['fdt'] = (empty($fdt)) ? 0 : 1;
		$test['da'] = (empty($da)) ? 0 : 1;

		$this->set(compact('data', 'test'));
	}

	public function admin_view_pdf_report($patient_id = null, $test_name = null)
	{
		$this->loadModel('Pointdata');
		$this->Pointdata->unbindModel(
			array('belongsTo' => array('User', 'Patient', 'Test', 'VfPointdata'))
		);

		$data = $this->Pointdata->find('first', array(
			'order' => 'Pointdata.created DESC', 'conditions' => array('Pointdata.id' => $patient_id)));

		if (isset($data['Pointdata'])) {
			$location = "Location: " . WWW_BASE . "/pointData/" . $data['Pointdata']['file'];
		} else {
			$location = "Location: " . WWW_BASE . "admin/unityreports/unity_reports_list/";
		}
		header($location);
		die();
	}

	/*This function for changing status of patient*/
	public function admin_changeStatus($id = null)
	{
		$this->layout = false;
		$this->loadModel('AppConstant');
		$this->loadModel('NewUserDevice');
		$data = $this->Patient->find('first', array('conditions' => array('Patient.id' => $id)));
		if ($data['Patient']['status'] == 1) {
			$this->Patient->id = $id;
			$this->Patient->saveField('status', 0);
		} else {
			$this->Patient->id = $id;
			$this->Patient->saveField('status', 1);
		}
		$Admin = $this->Session->read('Auth.Admin');
		$role_constant = Configure::read('role_constant');
		//pr($Admin['user_type']);die;
		if (in_array($Admin['user_type'], $role_constant)) {
			$status_1 = 1;
			$this->AppConstant->updateAll(array('AppConstant.is_update' => "'" . $status_1 . "'"), array('AppConstant.id' => 1));

			$new_user_device = $this->NewUserDevice->find('all');
			//pr($new_user_device);die;
			foreach ($new_user_device as $key => $val) {
				$device_token = $val['NewUserDevice']['device_token'];
				if (!empty($device_token) && $this->checkNotification($val['NewUserDevice']['device_id'])) {
					$res = $this->sendPushNotificationNewAdminDataUpdateV2($device_token);
					//$res = $this->sendPushNotificationNewAdminDataUpdate($device_token);
				}
			}

		}
		$this->Session->setFlash('Patient status has been updated successfully.', 'message', array('class' => 'message'));
		$this->redirect($this->referer());
	}

	public function getStaffName($id)
	{
		$staffs = $this->User->find('first', [
			'fields' => ['User.first_name', 'User.middle_name', 'User.last_name'],
			'conditions' => ['User.id' => $id]
		]);
		$name = $staffs['User']['first_name'] . " " . $staffs['User']['middle_name'] . " " . $staffs['User']['last_name'];
		return $name;
	}

	public function admin_previousTest()
	{
		if ($this->request->is('post')) {
			$this->loadModel('Pointdata');
			$this->Pointdata->unbindModel(
				array('belongsTo' => array('User', 'Patient', 'Test'))
			);
			/* $data = $this->Pointdata->find('all', array( 'limit' => 10,
            'order' => 'Pointdata.created DESC','conditions' => array('Pointdata.patient_id' => $this->request->data['patient_id'],'Pointdata.test_type_id' => $this->request->data['testType'],'Pointdata.eye_select' => $this->request->data['eye'])));*/

			$data = $this->Pointdata->find('all', array('limit' => 10,
				'order' => 'Pointdata.created DESC', 'conditions' => array('Pointdata.patient_id' => $this->request->data['patient_id'], 'Pointdata.test_type_id' => 0, 'Pointdata.eye_select' => $this->request->data['eye'])));
			echo json_encode($data);
			exit();
		}
	}

	public function admin_updateDefoult()
	{
		$this->UserDefault->primaryKey = 'id';
		$user = $this->Session->read('Auth.Admin');
		$user_default = $this->UserDefault->find('first', array('conditions' => array('UserDefault.user_id' => $user['id'], 'UserDefault.page' => $this->request->data['page'])));
		if (!empty($user_default)) {
			$default['UserDefault']['id'] = $user_default['UserDefault']['id'];
		}
		$default['UserDefault']['user_id'] = $user['id'];
		$default['UserDefault']['page'] = $this->request->data['page'];
		$default['UserDefault']['device_id'] = $this->request->data['deviceId'];
		$default['UserDefault']['language_id'] = $this->request->data['languageId'];
		$this->UserDefault->save($default);

		$data['device'] = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $this->request->data['deviceId'])));
		$this->Masterdata->unbindModel(
			array('belongsTo' => array('User', 'Patient', 'Test'), 'hasMany' => array('VfMasterdat'))
		);
		if ($data['device']['TestDevice']['device_type'] == 1) {
			$data['masterdata'] = $this->Masterdata->find('all', array('conditions' => array('Masterdata.test_name like' => '%GO%'), 'group' => 'Masterdata.test_name'));
		} else if ($data['device']['TestDevice']['device_type'] == 2 || $data['device']['TestDevice']['device_type'] == 4) {
			$data['masterdata'] = $this->Masterdata->find('all', array('conditions' => array('Masterdata.test_name like' => '%PIC%'), 'group' => 'Masterdata.test_name'));
		} else {
			//$data['masterdata']=$this->Masterdata->find('all',array('conditions'=>array('Masterdata.test_name NOT like'=>'%GO%', 'Masterdata.test_name NOT like'=>'%NEO%'),'group' => 'Masterdata.test_name'));
			$data['masterdata'] = $this->Masterdata->find('all', array('conditions' => array(
				array('Masterdata.test_name NOT like' => '%GO%'),
				array('Masterdata.test_name NOT like' => '%NEO%')
			)
			, 'group' => 'Masterdata.test_name'));
		}
		echo json_encode($data);
		// echo 1;
		exit();
	}

	public function admin_addTestStart2()
	{

		if ($this->request->is('post')) {
			$this->UserDefault->primaryKey = 'id';
			$user = $this->Session->read('Auth.Admin');
			$user_default = $this->UserDefault->find('first', array('conditions' => array('UserDefault.user_id' => $user['id'], 'UserDefault.page' => $this->request->data['page'])));
			if (!empty($user_default)) {
				$default['UserDefault']['id'] = $user_default['UserDefault']['id'];
			}
			$default['UserDefault']['user_id'] = $user['id'];
			$default['UserDefault']['page'] = $this->request->data['page'];
			$default['UserDefault']['device_id'] = $this->request->data['deviceId'];
			$default['UserDefault']['language_id'] = $this->request->data['languageId'];
			$this->UserDefault->save($default);

			$other_data = $this->TestStart->find('first', array('conditions' => array('TestStart.office_id' => $this->request->data['office_id'], 'TestStart.device_id' => $this->request->data['deviceId'], 'TestStart.patient_id NOT' => $this->request->data['patient_id'])));
			if (!empty($other_data) && $this->request->data['TestStatus'] == 1) {
				if ($this->request->data['page'] == 4) {
					$last_activity_validate = date("Y-m-d H:i:s", strtotime($other_data['TestStart']['updated_at'] . "+ 60 minute"));
				} else {
					$last_activity_validate = date("Y-m-d H:i:s", strtotime($other_data['TestStart']['updated_at'] . "+ 30 minute"));
				}
				$last_activity_validate = date("Y-m-d H:i:s", strtotime($other_data['TestStart']['updated_at'] . "+ 30 minute"));
				$cureent_time = date("Y-m-d H:i:s");
				if ($last_activity_validate < $cureent_time) {
					$this->TestStart->delete($other_data['TestStart']['id']);
					$olddata = $this->TestStart->find('first', array('conditions' => array('TestStart.office_id' => $this->request->data['office_id'], 'TestStart.device_id' => $this->request->data['deviceId'])));
					$dataTestDevice = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $this->request->data['deviceId'])));
					$dataTestDevice['TestDevice']['current_status'] = 1;
					$this->TestDevice->save($dataTestDevice);
					if (!empty($olddata)) {
						if ($this->request->data['TestStatus'] == 1) { // chaek stop status
							$this->DeviceMessage->deleteAll(array('DeviceMessage.office_id' => $this->request->data['office_id'], 'DeviceMessage.device_id' => $this->request->data['deviceId']), false);
							$dataTestDevice = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $this->request->data['deviceId'])));
							$dataTestDevice['TestDevice']['current_status'] = 1;
							$this->TestDevice->save($dataTestDevice);
						}
						if ($this->request->data['TestStatus'] == 0) { // chaek stop status
							$this->TestStart->delete($olddata['TestStart']['id']);
							$this->DeviceMessage->deleteAll(array('DeviceMessage.office_id' => $this->request->data['office_id'], 'DeviceMessage.device_id' => $this->request->data['deviceId']), false);
							$dataTestDevice = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $this->request->data['deviceId'])));
							$dataTestDevice['TestDevice']['current_status'] = 0;
							$this->TestDevice->save($dataTestDevice);
							$response['status'] = 1;
							echo json_encode($response);
							exit();
						}
						// update data
						$data['TestStart']['id'] = $olddata['TestStart']['id'];
						$data['TestStart']['staff_id'] = $user['id'];
						$data['TestStart']['office_id'] = $this->request->data['office_id'];
						$data['TestStart']['device_id'] = $this->request->data['deviceId'];
						$data['TestStart']['patient_id'] = $this->request->data['patient_id'];
						$data['TestStart']['status'] = $this->request->data['TestStatus'];
						$data['TestStart']['testData'] = $this->request->data['testData'];
						$data['TestStart']['updated_at'] = date("Y-m-d H:i:s");
						if ($this->TestStart->save($data)) {
							$response['status'] = 1;
							echo json_encode($response);
							exit();
						}
					} else {
						//insert data
						$data['TestStart']['office_id'] = $this->request->data['office_id'];
						$data['TestStart']['staff_id'] = $user['id'];
						$data['TestStart']['device_id'] = $this->request->data['deviceId'];
						$data['TestStart']['patient_id'] = $this->request->data['patient_id'];
						$data['TestStart']['status'] = $this->request->data['TestStatus'];
						$data['TestStart']['testData'] = $this->request->data['testData'];
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
				// chack avlavle data for patient
				$olddata = $this->TestStart->find('first', array('conditions' => array('TestStart.office_id' => $this->request->data['office_id'], 'TestStart.device_id' => $this->request->data['deviceId'])));
				if (!empty($olddata)) {
					if ($this->request->data['TestStatus'] == 1) { // chaek stop status
						$this->DeviceMessage->deleteAll(array('DeviceMessage.office_id' => $this->request->data['office_id'], 'DeviceMessage.device_id' => $this->request->data['deviceId']), false);
						$dataTestDevice = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $this->request->data['deviceId'])));
						$dataTestDevice['TestDevice']['current_status'] = 1;
						$this->TestDevice->save($dataTestDevice);
					}
					if ($this->request->data['TestStatus'] == 0) { // chaek stop status
						$this->TestStart->delete($olddata['TestStart']['id']);
						$this->DeviceMessage->deleteAll(array('DeviceMessage.office_id' => $this->request->data['office_id'], 'DeviceMessage.device_id' => $this->request->data['deviceId']), false);
						$dataTestDevice = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $this->request->data['deviceId'])));
						$dataTestDevice['TestDevice']['current_status'] = 0;
						$this->TestDevice->save($dataTestDevice);
						$response['status'] = 1;
						echo json_encode($response);
						exit();
					} else {
						// update data
						$data['TestStart']['id'] = $olddata['TestStart']['id'];
						$data['TestStart']['staff_id'] = $user['id'];
						$data['TestStart']['office_id'] = $this->request->data['office_id'];
						$data['TestStart']['device_id'] = $this->request->data['deviceId'];
						$data['TestStart']['patient_id'] = $this->request->data['patient_id'];
						$data['TestStart']['status'] = $this->request->data['TestStatus'];
						$data['TestStart']['testData'] = $this->request->data['testData'];
						$data['TestStart']['updated_at'] = date("Y-m-d H:i:s");
						if ($this->TestStart->save($data)) {
							$response['status'] = 1;
							echo json_encode($response);
							exit();
						}
					}
				} else {
					//insert data
					$data['TestStart']['staff_id'] = $user['id'];
					$data['TestStart']['office_id'] = $this->request->data['office_id'];
					$data['TestStart']['device_id'] = $this->request->data['deviceId'];
					$data['TestStart']['patient_id'] = $this->request->data['patient_id'];
					$data['TestStart']['status'] = $this->request->data['TestStatus'];
					$data['TestStart']['testData'] = $this->request->data['testData'];
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
		}
		$response['status'] = 0;
		echo json_encode($response);
		exit();
	}

	public function admin_addTestStart()
	{
		if ($this->request->is('post')) {
			/*  $this->Session->write('patient.patient_id', $this->request->data['patient_id']);
            $this->Session->write('patient.deviceId', $this->request->data['deviceId']);
            $this->Session->write('patient.languageId', $this->request->data['languageId']);*/
			//check device alloted or not
			// UserDefault
			$this->UserDefault->primaryKey = 'id';
			$user = $this->Session->read('Auth.Admin');
			$user_default = $this->UserDefault->find('first', array('conditions' => array('UserDefault.user_id' => $user['id'], 'UserDefault.page' => $this->request->data['page'])));
			if (!empty($user_default)) {
				$default['UserDefault']['id'] = $user_default['UserDefault']['id'];
			}
			$default['UserDefault']['user_id'] = $user['id'];
			$default['UserDefault']['page'] = $this->request->data['page'];
			$default['UserDefault']['device_id'] = $this->request->data['deviceId'];
			$default['UserDefault']['language_id'] = $this->request->data['languageId'];
			$default['UserDefault']['test_type'] = $this->request->data['testType'];
			$default['UserDefault']['strategy'] = $this->request->data['strategy'];
			$default['UserDefault']['test_name'] = $this->request->data['test_name'];
			$this->UserDefault->save($default);
			$other_data = $this->TestStart->find('first', array('conditions' => array('TestStart.office_id' => $this->request->data['office_id'], 'TestStart.device_id' => $this->request->data['deviceId'], 'TestStart.patient_id NOT' => $this->request->data['patient_id'])));
			/*$test_device=$this->TestDevice->find('first',array('conditions'=>array('TestDevice.id'=>$this->request->data['deviceId'])));
          $device_token=$test_device['TestDevice']['token'];*/
			if (!empty($other_data) && $this->request->data['TestStatus'] == 1) {
				if ($this->request->data['page'] == 4) {
					$last_activity_validate = date("Y-m-d H:i:s", strtotime($other_data['TestStart']['updated_at'] . "+ 60 minute"));
				} else {
					$last_activity_validate = date("Y-m-d H:i:s", strtotime($other_data['TestStart']['updated_at'] . "+ 30 minute"));
				}

				$cureent_time = date("Y-m-d H:i:s");
				if ($last_activity_validate < $cureent_time) {
					/*if($this->request->data['TestStatus']==1){
                $this->sendPushNotificationtestStatus($device_token,'Test started');
            }else if($this->request->data['TestStatus']==0){
                $this->sendPushNotificationtestStatus($device_token,'Test Stoped');
            }*/

					$this->TestStart->delete($other_data['TestStart']['id']);
					// chack avlavle data for patient
					$olddata = $this->TestStart->find('first', array('conditions' => array('TestStart.office_id' => $this->request->data['office_id'], 'TestStart.device_id' => $this->request->data['deviceId'])));
					$dataTestDevice = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $this->request->data['deviceId'])));
					$dataTestDevice['TestDevice']['current_status'] = 1;
					$this->TestDevice->save($dataTestDevice);
					if (!empty($olddata)) {
						if ($this->request->data['TestStatus'] == 1) { // chaek stop status
							$this->DeviceMessage->deleteAll(array('DeviceMessage.office_id' => $this->request->data['office_id'], 'DeviceMessage.device_id' => $this->request->data['deviceId']), false);
							$dataTestDevice = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $this->request->data['deviceId'])));
							$dataTestDevice['TestDevice']['current_status'] = 1;
							$this->TestDevice->save($dataTestDevice);
						}
						if ($this->request->data['TestStatus'] == 0) { // chaek stop status
							$this->TestStart->delete($olddata['TestStart']['id']);
							$this->DeviceMessage->deleteAll(array('DeviceMessage.office_id' => $this->request->data['office_id'], 'DeviceMessage.device_id' => $this->request->data['deviceId']), false);
							$dataTestDevice = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $this->request->data['deviceId'])));
							$dataTestDevice['TestDevice']['current_status'] = 0;
							$this->TestDevice->save($dataTestDevice);
							$response['status'] = 1;
							echo json_encode($response);
							exit();
						}
						// update data
						$data['TestStart']['id'] = $olddata['TestStart']['id'];
						$data['TestStart']['staff_id'] = $user['id'];
						$data['TestStart']['office_id'] = $this->request->data['office_id'];
						$data['TestStart']['device_id'] = $this->request->data['deviceId'];
						$data['TestStart']['patient_id'] = $this->request->data['patient_id'];
						$data['TestStart']['status'] = $this->request->data['TestStatus'];
						$data['TestStart']['testData'] = $this->request->data['testData'];
						$data['TestStart']['updated_at'] = date("Y-m-d H:i:s");
						if ($this->TestStart->save($data)) {
							$response['status'] = 1;
							echo json_encode($response);
							exit();
						}
					} else {
						//insert data
						$data['TestStart']['office_id'] = $this->request->data['office_id'];
						$data['TestStart']['staff_id'] = $user['id'];
						$data['TestStart']['device_id'] = $this->request->data['deviceId'];
						$data['TestStart']['patient_id'] = $this->request->data['patient_id'];
						$data['TestStart']['status'] = $this->request->data['TestStatus'];
						$data['TestStart']['testData'] = $this->request->data['testData'];
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

				/* if($this->request->data['TestStatus']==1){
                $this->sendPushNotificationtestStatus($device_token,'Test started');
            }else if($this->request->data['TestStatus']==0){
                $this->sendPushNotificationtestStatus($device_token,'Test Stoped');
            }*/


				// chack avlavle data for patient
				$olddata = $this->TestStart->find('first', array('conditions' => array('TestStart.office_id' => $this->request->data['office_id'], 'TestStart.device_id' => $this->request->data['deviceId'])));
				if (!empty($olddata)) {
					if ($this->request->data['TestStatus'] == 1) { // chaek stop status
						$this->DeviceMessage->deleteAll(array('DeviceMessage.office_id' => $this->request->data['office_id'], 'DeviceMessage.device_id' => $this->request->data['deviceId']), false);
						$dataTestDevice = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $this->request->data['deviceId'])));

						$dataTestDevice['TestDevice']['current_status'] = 1;
						$this->TestDevice->save($dataTestDevice);
					}
					if ($this->request->data['TestStatus'] == 0) { // chaek stop status
						$this->TestStart->delete($olddata['TestStart']['id']);
						$this->DeviceMessage->deleteAll(array('DeviceMessage.office_id' => $this->request->data['office_id'], 'DeviceMessage.device_id' => $this->request->data['deviceId']), false);
						$dataTestDevice = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $this->request->data['deviceId'])));
						$dataTestDevice['TestDevice']['current_status'] = 0;

						$this->TestDevice->save($dataTestDevice);
						$response['status'] = 1;
						echo json_encode($response);
						exit();
					} else {
						// update data
						$data['TestStart']['id'] = $olddata['TestStart']['id'];
						$data['TestStart']['staff_id'] = $user['id'];
						$data['TestStart']['office_id'] = $this->request->data['office_id'];
						$data['TestStart']['device_id'] = $this->request->data['deviceId'];
						$data['TestStart']['patient_id'] = $this->request->data['patient_id'];
						$data['TestStart']['status'] = $this->request->data['TestStatus'];
						$data['TestStart']['testData'] = $this->request->data['testData'];
						$data['TestStart']['updated_at'] = date("Y-m-d H:i:s");
						if ($this->TestStart->save($data)) {
							$response['status'] = 1;
							echo json_encode($response);
							exit();
						}
					}
				} else {
					//insert data
					$data['TestStart']['staff_id'] = $user['id'];
					$data['TestStart']['office_id'] = $this->request->data['office_id'];
					$data['TestStart']['device_id'] = $this->request->data['deviceId'];
					$data['TestStart']['patient_id'] = $this->request->data['patient_id'];
					$data['TestStart']['status'] = $this->request->data['TestStatus'];
					$data['TestStart']['testData'] = $this->request->data['testData'];
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
		}
		$response['status'] = 0;
		echo json_encode($response);
		exit();
	}

	public function admin_cleardevicedata()
	{
		if ($this->request->is('post')) {
			// $this->DeviceMessage->deleteAll(array('DeviceMessage.office_id' => $this->request->data['office_id'],'DeviceMessage.device_id' => $this->request->data['deviceId'],'DeviceMessage.message not like' => '%VF_FILE_UPLOADED%'), false);
			$this->DeviceMessage->deleteAll(array('DeviceMessage.office_id' => $this->request->data['office_id'], 'DeviceMessage.device_id' => $this->request->data['deviceId']), false);
		}
		echo 1;
		exit();
	}

	public function admin_clear_alldata()
	{
		if ($this->request->is('post')) {
			sleep(2);
			/* $this->DeviceMessage->deleteAll(array('DeviceMessage.office_id' => $this->request->data['office_id'],'DeviceMessage.device_id' => $this->request->data['deviceId'],'DeviceMessage.message not like' => '%VF_FILE_UPLOADED%'), false); */
			$this->DeviceMessage->deleteAll(array('DeviceMessage.office_id' => $this->request->data['office_id'], 'DeviceMessage.device_id' => $this->request->data['deviceId']), false);
			$this->TestStart->deleteAll(array('TestStart.office_id' => $this->request->data['office_id'], 'TestStart.device_id' => $this->request->data['deviceId'], 'TestStart.patient_id' => $this->request->data['patient_id']), false);

		}
		echo 1;
		exit();
	}

	public function admin_checkdevicestatus()
	{
		if ($this->request->is('post')) {
			$data = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $this->request->data['deviceId'])));
			$data['TestDevice']['current_status'];
			if ($data['TestDevice']['current_status'] == 0) {
				echo 0;
				exit();
			} else {
				echo 1;
				exit();
			}
		}
		echo 0;
		exit();
	}

	public function admin_updatedob()
	{
		if ($this->request->is('post')) {
			$olddata = $this->Patient->find('first', array('conditions' => array('Patient.id' => $this->request->data['patient_id'])));
			$data['Patient']['id'] = $olddata['Patient']['id'];
			$data['Patient']['dob'] = $this->request->data['dob'];
			if ($this->Patient->save($data)) {
				echo 1;
				exit();
			} else {
				echo 0;
				exit();
			}
		}
		exit();
	}
	/* Update patients status*/
	public function admin_updateStatus() 
	{ 
		if ($this->request->is('POST')) { 
			$Sdata = $this->Patient->find('first', array('conditions' => array('Patient.id' => $this->request->data['patient_id'])));
			$data['Patient']['id'] = $Sdata['Patient']['id']; 
				if($this->request->data['status']== 0){
					date_default_timezone_set('UTC');
					$data['Patient']['archived_date'] = date('Y-m-d H:i:s');
					$data['Patient']['status'] = 0;
				}else if($this->request->data['status']== 1){
					date_default_timezone_set("UTC"); 
					$data['Patient']['archived_date'] = null;
					$data['Patient']['status'] = 1;
					$data['Patient']['created_date_utc'] = date('Y-m-d H:i:s');
				}else if($this->request->data['status']== 2){
					date_default_timezone_set("UTC"); 
					$data['Patient']['archived_date'] = null;
					$data['Patient']['status'] = 2;
					$data['Patient']['created_date_utc'] = date('Y-m-d H:i:s');
				}else{
					$data['Patient']['status'] = $this->request->data['status']; 
				}
			if ($this->Patient->save($data)) {
				echo 1;
				exit();
			} else {
				echo 0;
				exit();
			}
		}
		exit();
	}
	public function admin_UpdateIHUDevice(){ 
		$office_list = $this->Office->find('all'); 
		foreach($office_list as $office_lists){
			$archiveTime = $office_lists['Office']['weekdays'];
			if($archiveTime){ 
				date_default_timezone_set("UTC");
				$last_date_start=date('Y-m-d H:i:s', strtotime("-".$archiveTime." Days"));
				$this->Patient->updateAll(array('Patient.device_type' => NULL,'Patient.ihuunassigntime' => NULL,'Patient.progression_deatild' => NULL,'Patient.language' => NULL,'Patient.test_name_ihu' => NULL,'Patient.eye_type' => NULL), array('Patient.ihuunassigntime <' => $last_date_start,'Patient.office_id'=>$office_lists['Office']['id']));
			}
		}
		exit();
		die();
	}


		public function admin_Updatestatus30days(){
			$office_list = $this->Office->find('all', array('conditions' => array('Office.archive_status' => 1)));
			date_default_timezone_set("UTC"); 
			$data_archived_date = date('Y-m-d H:i:s');
			foreach($office_list as $office_lists){
				 $archiStatus = $office_lists['Office']['archive_status']; 
				 $archiveTime = $office_lists['Office']['p_archived_date'];
				if(!empty($archiveTime || $archiveTime== null)){
					$archiveTime =$office_lists['Office']['p_archived_date'];
				}else{
					$archiveTime = 2 ;
				}
				if(@$archiStatus == 1){
					$conditions['Patient.status']=1;
					$conditions['OR']['Patient.status']=2;
					$conditions['Patient.office_id']=$office_lists['Office']['id'];
					$archiveTime = $office_lists['Office']['p_archived_date']; 
					date_default_timezone_set("UTC"); 
					//$last_date_start=date('Y-m-d H:i:s', strtotime("-".$archiveTime." Days"));
					$last_date_start=date('Y-m-d H:i:s', strtotime("-".$archiveTime." minutes"));
					//$updateDAte=date('Y-m-d H:i:s');
					$conditions['Patient.created_date_utc <']=$last_date_start;
					$this->Patient->updateAll(array('Patient.status' => 0,'Patient.archived_date' => "'".$data_archived_date."'",'Patient.created_date_utc' => "'".$data_archived_date."'"), array($conditions));
				}
			} 
				/*	For IHU*/
			$office_listt = $this->Office->find('all'); 
				foreach($office_listt as $office_lists){
				$archiveTime = $office_lists['Office']['weekdays'];
				if($archiveTime){ 
					date_default_timezone_set("UTC");
					$last_date_start=date('Y-m-d H:i:s', strtotime("-".$archiveTime." Days"));
					$this->Patient->updateAll(array('Patient.device_type' => NULL,'Patient.ihuunassigntime' => NULL,'Patient.progression_deatild' => NULL,'Patient.language' => NULL,'Patient.test_name_ihu' => NULL,'Patient.eye_type' => NULL), array('Patient.ihuunassigntime <' => $last_date_start,'Patient.office_id'=>$office_lists['Office']['id']));
				}
			}
			exit();
			die();
	}

	public function admin_cleartestdevice()
	{
		if ($this->request->is('post')) {
			$condition = array('TestStart.device_id' => $this->request->data['deviceId']);
			$this->TestStart->deleteAll($condition, false);
			$data = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $this->request->data['deviceId'])));
			$data['TestDevice']['current_status'] = 0;
			unset($data['TestDevice']['device_type']);
			$this->Patient->save($data);
		}
		echo 1;
		exit();
	}

	public function admin_cleardevice()
	{
		if ($this->request->data['d'] == 4) {
			$last_activity_time = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . "- 60 minute"));
		} else {
			$last_activity_time = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . "- 30 minute"));
		}
		$condition = array('TestStart.updated_at <' => $last_activity_time);
		$this->TestStart->deleteAll($condition, false);

		$this->TestDevice->updateAll(
			array('TestDevice.current_status' => 0),
			array('TestDevice.current_status' => 1, 'TestDevice.current_status_updated_at <' => $last_activity_time)
		);
		exit();
	}

	public function admin_testcomplited()
	{
		/* $this->DeviceMessage->deleteAll(array('DeviceMessage.office_id' => $this->request->data['office_id'],'DeviceMessage.device_id' => $this->request->data['device_id'],'DeviceMessage.id <=' => $this->request->data['id'],'DeviceMessage.message not like' => '%VF_FILE_UPLOADED%'), false);*/
		$this->DeviceMessage->deleteAll(array('DeviceMessage.office_id' => $this->request->data['office_id'], 'DeviceMessage.device_id' => $this->request->data['device_id'], 'DeviceMessage.id <=' => $this->request->data['id']), false);
		exit();
	}

	public function admin_userpreset()

	{

		if ($this->request->is('post')) {

			$this->loadModel('UserPreset');
			$page = (isset($this->request->data['page'])) ? $this->request->data['page'] : 1;
			$user = $this->Session->read('Auth.Admin');
			$user_preset = $this->UserPreset->find('first', array('conditions' => array('UserPreset.user_id' => $user['id'], 'UserPreset.page' => $page)));
			$user_preset['UserPreset'][lcfirst($this->request->data['user_preset'])] = $this->request->data['user_preset_text'];
			$user_preset['UserPreset']['user_id'] = $user['id'];
			$this->UserPreset->save($user_preset);
			$this->loadModel('UserPresetData');
			$this->loadModel('UserPresetDatavs');
			if ($page == 4) {
				$user_preset_data = $this->UserPresetDatavs->find('first', array('conditions' => array('UserPresetDatavs.user_id' => $user['id'], 'UserPresetDatavs.user_presets' => lcfirst($this->request->data['user_preset']))));

				$user_preset_data['UserPresetDatavs']['user_id'] = $user['id'];
				$user_preset_data['UserPresetDatavs']['user_presets'] = $this->request->data['user_preset'];
				$user_preset_data['UserPresetDatavs']['vs'] = $this->request->data['vs'];
				$user_preset_data['UserPresetDatavs']['fdt'] = $this->request->data['fdt'];
				$user_preset_data['UserPresetDatavs']['distant_without_correction'] = $this->request->data['distant_without_correction'];
				$user_preset_data['UserPresetDatavs']['distant_with_correction'] = $this->request->data['distant_with_correction'];
				$user_preset_data['UserPresetDatavs']['distant_without_correction_glare'] = $this->request->data['distant_without_correction_glare'];
				$user_preset_data['UserPresetDatavs']['distant_with_correction_glare'] = $this->request->data['distant_with_correction_glare'];
				$user_preset_data['UserPresetDatavs']['closeup_with_correction'] = $this->request->data['closeup_with_correction'];
				$user_preset_data['UserPresetDatavs']['with_glare'] = $this->request->data['with_glare'];
				$user_preset_data['UserPresetDatavs']['va_os'] = $this->request->data['va_os'];
				$user_preset_data['UserPresetDatavs']['va_od'] = $this->request->data['va_od'];
				$user_preset_data['UserPresetDatavs']['color_os'] = $this->request->data['color_os'];
				$user_preset_data['UserPresetDatavs']['color_od'] = $this->request->data['color_od'];
				$user_preset_data['UserPresetDatavs']['fdt_os'] = $this->request->data['fdt_os'];
				$user_preset_data['UserPresetDatavs']['fdt_od'] = $this->request->data['fdt_od'];
				$user_preset_data['UserPresetDatavs']['va_dwtc_os'] = $this->request->data['va_dwtc_os'];
				$user_preset_data['UserPresetDatavs']['va_dwtc_od'] = $this->request->data['va_dwtc_od'];
				$user_preset_data['UserPresetDatavs']['va_dwc_os'] = $this->request->data['va_dwc_os'];
				$user_preset_data['UserPresetDatavs']['va_dwc_od'] = $this->request->data['va_dwc_od'];
				$user_preset_data['UserPresetDatavs']['va_dwtc_glare_os'] = $this->request->data['va_dwtc_glare_os'];
				$user_preset_data['UserPresetDatavs']['va_dwtc_glare_od'] = $this->request->data['va_dwtc_glare_od'];
				$user_preset_data['UserPresetDatavs']['va_dwc_glare_os'] = $this->request->data['va_dwc_glare_os'];
				$user_preset_data['UserPresetDatavs']['va_dwc_glare_od'] = $this->request->data['va_dwc_glare_od'];
				$user_preset_data['UserPresetDatavs']['va_cwc_os'] = $this->request->data['va_cwc_os'];
				$user_preset_data['UserPresetDatavs']['va_cwc_od'] = $this->request->data['va_cwc_od'];
				$user_preset_data['UserPresetDatavs']['stereopsis'] = $this->request->data['stereopsis'];
				$user_preset_data['UserPresetDatavs']['alarm_stop'] = $this->request->data['alarm_stop'];
				$user_preset_data['UserPresetDatavs']['voice_instractuin'] = $this->request->data['voice_instractuin'];
				$user_preset_data['UserPresetDatavs']['stimulusSize'] = $this->request->data['stimulusSize'];
				$user_preset_data['UserPresetDatavs']['stimulusIntensity'] = $this->request->data['stimulusIntensity'];
				$user_preset_data['UserPresetDatavs']['wallBrightness'] = $this->request->data['wallBrightness'];
				$user_preset_data['UserPresetDatavs']['testSpeed'] = $this->request->data['testSpeed'];
				$user_preset_data['UserPresetDatavs']['audioVolume'] = $this->request->data['audioVolume'];
				$user_preset_data['UserPresetDatavs']['testColour'] = $this->request->data['testColour'];
				$user_preset_data['UserPresetDatavs']['testBackground'] = $this->request->data['testBackground'];
				$this->UserPresetDatavs->save($user_preset_data);
			} else {
				$user_preset_data = $this->UserPresetData->find('first', array('conditions' => array('UserPresetData.user_id' => $user['id'], 'UserPresetData.user_presets' => lcfirst($this->request->data['user_preset']))));
				$user_preset_data['UserPresetData']['user_id'] = $user['id'];
				$user_preset_data['UserPresetData']['user_presets'] = $this->request->data['user_preset'];
				$user_preset_data['UserPresetData']['testType'] = $this->request->data['testType'];
				$user_preset_data['UserPresetData']['testSubType'] = $this->request->data['testSubType'];
				$user_preset_data['UserPresetData']['testTypeName'] = $this->request->data['testTypeName'];
				$user_preset_data['UserPresetData']['eye_taped'] = $this->request->data['eye_taped'];
				$user_preset_data['UserPresetData']['alarm_stop'] = $this->request->data['alarm_stop'];
				$user_preset_data['UserPresetData']['GazeTracking'] = $this->request->data['GazeTracking'];
				$user_preset_data['UserPresetData']['testBothEyes'] = $this->request->data['testBothEyes'];
				$user_preset_data['UserPresetData']['stimulusSize'] = $this->request->data['stimulusSize'];
				$user_preset_data['UserPresetData']['stimulusIntensity'] = $this->request->data['stimulusIntensity'];
				$user_preset_data['UserPresetData']['wallBrightness'] = $this->request->data['wallBrightness'];
				$user_preset_data['UserPresetData']['testSpeed'] = $this->request->data['testSpeed'];
				$user_preset_data['UserPresetData']['voice_instractuin'] = $this->request->data['voice_instractuin'];
				$user_preset_data['UserPresetData']['progression_analysis'] = $this->request->data['progression_analysis'];
				$user_preset_data['UserPresetData']['audioVolume'] = $this->request->data['audioVolume'];
				$user_preset_data['UserPresetData']['testColour'] = $this->request->data['testColour'];
				$user_preset_data['UserPresetData']['testBackground'] = $this->request->data['testBackground'];
				$user_preset_data['UserPresetData']['selectEye'] = $this->request->data['selectEye'];
				$user_preset_data['UserPresetData']['sliderTestSpeed_maxValue'] = $this->request->data['sliderTestSpeed_maxValue'];
				$user_preset_data['UserPresetData']['sliderTestSpeed_minValue'] = $this->request->data['sliderTestSpeed_minValue'];
				$user_preset_data['UserPresetData']['bilateralfixation'] = $this->request->data['bilateralfixation'];
				$this->UserPresetData->save($user_preset_data);
			}


			echo 1;

			exit();

		}

	}

	public function admin_getuserpreset()
	{
		$this->loadModel('UserPreset');

		$user = $this->Session->read('Auth.Admin');
		$page = (isset($this->request->data['page'])) ? $this->request->data['page'] : 1;
		$this->loadModel('UserPresetData');
		$this->loadModel('UserPresetDatavs');
		if ($page == 4) {
			$data['user_preset'] = $this->UserPreset->find('first', array('conditions' => array('UserPreset.user_id' => $user['id'], 'UserPreset.page' => 4)));
			$data['user_preset_data'] = $this->UserPresetDatavs->find('first', array('conditions' => array('UserPresetDatavs.user_id' => $user['id'], 'UserPresetDatavs.user_presets' => lcfirst($this->request->data['user_preset']))));
		} else {
			$data['user_preset'] = $this->UserPreset->find('first', array('conditions' => array('UserPreset.user_id' => $user['id'], 'UserPreset.page NOT' => 4)));
			$data['user_preset_data'] = $this->UserPresetData->find('first', array('conditions' => array('UserPresetData.user_id' => $user['id'], 'UserPresetData.user_presets' => lcfirst($this->request->data['user_preset']))));
		}
		echo json_encode($data);
		die;
	}

	public function admin_update_pointdata()
	{
		if ($this->request->is('post')) {
			$olddata = $this->Pointdata->find('first', array('conditions' => array('Pointdata.id' => $this->request->data['id'])));
			$data['Pointdata']['id'] = $olddata['Pointdata']['id'];
			$data['Pointdata']['baseline'] = $this->request->data['value'];
			if ($this->Pointdata->save($data)) {
				echo 1;
				exit();
			} else {
				echo 0;
				exit();
			}
		}
		exit();
	}

	public function admin_deleteTestStart()
	{
		if ($this->request->is('post')) {
			$this->TestStart->deleteAll(array('TestStart.office_id' => $this->request->data['office_id'], 'TestStart.device_id' => $this->request->data['deviceId'], 'TestStart.patient_id' => $this->request->data['patient_id']), false);
			echo 0;
			exit();
		}
		exit();
	}

	public function admin_patient_view_video($id)
	{

		$user = $this->Session->read('Auth.Admin');
		$conditions = array();
		if (isset($this->request->query['search_name']) && ($this->request->query['search_name']) != '') {
			$conditions[] = array('Video.name like' => '%' . $this->request->query['search_name'] . '%');
		}
		$conditions[] = array('Video.office_id' => $user['office_id']);
		$datas = $this->Video->find('all', array('conditions' => $conditions));
		$viewed = $this->PatientVideoViews->find('all', array('conditions' => array('PatientVideoViews.patient_id' => $id)));
		$viewed = Hash::combine($viewed, '{*}.PatientVideoViews.video_id', '{*}.PatientVideoViews');
		$patient = $this->Patient->find('first', array('conditions' => array('Patient.id' => $id)));
		$this->set(compact('datas', 'viewed', 'patient'));
	}

	public function admin_view_video()
	{
		$video_id = $this->request->data['video_id'];
		$patient_id = $this->request->data['patient_id'];
		$video = $this->Video->find('first', array('conditions', array('Video.id' => $video_id)));
		$patient_viewed = $this->PatientVideoViews->find('first', array('conditions' => array('PatientVideoViews.video_id' => $video_id, 'PatientVideoViews.patient_id' => $patient_id)));
		if (empty($patient_viewed)) {
			$data['PatientVideoViews']['video_id'] = $video_id;
			$data['PatientVideoViews']['patient_id'] = $patient_id;
			$this->PatientVideoViews->save($data);
		}
		echo '<video   controls id="video-player"  style=" width: 100%; margin-top: 10px; ">
          <source id="video-player-src"  src="' . WWW_BASE . '/files/video/uploads/' . $video["Video"]["video"] . '" type="video/mp4">
        </video>';
		// echo  WWW_BASE.'/files/video/uploads/'.$video['Video']['video'];
		exit();
	}
	public function admin_patient_creat_view(){
		$datas=$this->Office->find('all');
		foreach($datas as $key => $value){ 
		/*	try{

			//$currentOrderAlert  		=  $this->Office->query("DROP VIEW mmd_patients_".$value['Office']['id']);
		}catch(Exception $e){
		}*/
			$currentOrderAlert  =  $this->Patient->query("CREATE OR REPLACE VIEW mmd_patients_".$value['Office']['id']." AS  SELECT  `id`, `unique_id`, `user_id`, `first_name`, `middle_name`, `last_name`, `email`, `phone`, `id_number`, `office_id`, `dob`, `is_delete`, `delete_date`, `notes`, `status`, `archived_date`, `p_profilepic`, `od_left`, `od_right`, `os_left`, `os_right`, `race`, `created_date_utc`, `created`,`created_at_for_archive`  FROM   `mmd_patients` WHERE mmd_patients.user_id in (SELECT id from mmd_users where office_id=".$value['Office']['id'].")");
		} 
		//pr($datas);
		die();
	}
	
		public function admin_start_test_eye($id = null, $testId = 0, $strategy = 0, $testname = '')
	{

		$this->loadModel('UserPreset');
		$this->Patient->unbindModel(
			array('hasMany' => array('Pointdata'))
		);
		$data = $this->Patient->find('first', array('recursive' => 2, 'conditions' => array('Patient.id' => $id)));
		$data['Patient']['dob'] = date('d-m-Y', strtotime($data['Patient']['dob']));
		$user = $this->Session->read('Auth.Admin');

		if ($user['office_id'] == 0) {
			$test_device = $this->TestDevice->find('all', array('conditions' => array('TestDevice.mac_address NOT' => '')));
		} else {

		//$test_device = $this->TestDevice->find('all', array('conditions' => array('TestDevice.mac_address NOT' => '', 'TestDevice.office_id' => $user['office_id'])));
			$test_device = $this->TestDevice->devicelist($user['office_id']);
		}
		$user_default = $this->UserDefault->find('first', array('conditions' => array('UserDefault.user_id' => $user['id'], 'UserDefault.page' => 1))); 
		$user_preset = $this->UserPreset->find('first', array('conditions' => array('UserPreset.user_id' => $user['id'])));
		$count = count($test_device);
		$defoult_device = array();

		if (!empty($user_default)) {

			if ($testId != 0) {
				$testData['testId2'] = $testId;
			} else if ($user_default['UserDefault']['test_type'] != null) {
				$testData['testId2'] = $user_default['UserDefault']['test_type'];
			} else {
				$testData['testId2'] = 2;
			}
			if ($strategy != 0) {
				$testData['strategy'] = $strategy;
			} else if ($testData['testId2'] == $testId && $testData['testId2'] == $user_default['UserDefault']['test_type'] && $user_default['UserDefault']['strategy'] != null) {
				$testData['strategy'] = $user_default['UserDefault']['strategy'];
			} else if ($testData['testId2'] == 2) {
				$testData['strategy'] = 4;
			} else {
				$testData['strategy'] = '';
			}
			if ($testname != '') {
				$testData['testname'] = $testname;
			} else if ($testData['testId2'] == $testId && $testData['testId2'] == $user_default['UserDefault']['test_type'] && $user_default['UserDefault']['test_name'] != null) {
				$testData['testname'] = $user_default['UserDefault']['test_name'];
			} else if ($testData['testId2'] == 2) {
				$testData['testname'] = 'Central_24_2';
			} else {
				$testData['testname'] = '';
			}
			$testData['testId'] = $testData['testId2'];
			$device_id = $user_default['UserDefault']['device_id'];
			$device = $this->TestDevice->find('first', array('conditions' => array('TestDevice.id' => $device_id)));
			if (!empty($device)) {
				$defoult_device = @$device['TestDevice'];
			}

		} else if ($count == 1) {
			$defoult_device = @$test_device['TestDevice'];
			if ($testId != 0) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 2;
			}

			if ($strategy != 0) {
				$testData['strategy'] = $strategy;
			} else if ($testData['testId'] == 2) {
				$testData['strategy'] = 4;
			} else {
				$testData['strategy'] = '';
			}
			if ($testname != '') {
				$testData['testname'] = $testname;
			} else if ($testData['testId'] == 2) {
				$testData['testname'] = 'Central_24_2';
			} else {
				$testData['testname'] = '';
			}
		} else {
			$testData['testId'] = ($testId != '') ? $testId : 2;
			if ($testId != 0) {
				$testData['testId'] = $testId;
			} else {
				$testData['testId'] = 2;
			}

			if ($strategy != 0) {
				$testData['strategy'] = $strategy;
			} else if ($testData['testId'] == 2) {
				$testData['strategy'] = 4;
			} else {
				$testData['strategy'] = '';
			}
			if ($testname != '') {
				$testData['testname'] = $testname;
			} else if ($testData['testId'] == 2) {
				$testData['testname'] = 'Central_24_2';
			} else {
				$testData['testname'] = '';
			}
		}
		//pr($testData);die();
		$this->set(compact(['data', 'test_device', 'user_default', 'user_preset', 'testData', 'defoult_device',]));
	}
}


?>
