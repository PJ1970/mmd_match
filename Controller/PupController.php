<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class PupController extends AppController
{
	public $uses = array('User', 'PupTest','Patient', 'Tests', 'Practice', 'Office', 'Files', 'VfPointdata', 'Pointdata', 'Masterdata', 'MasterPointdata', 'DevicePreference', 'Diagnosis', 'Cms', 'DarkAdaption', 'DaPointData', 'ActTest', 'ActPointdata');

	var $helpers = array('Html', 'Form', 'Js' => array('Jquery'), 'Custom');

	public $components = array('Auth' => array('authorize' => array('Controller')), 'Session', 'Email', 'Common', 'RememberMe');

	public $allowedActions = array();

	function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow($this->allowedActions);
	}


	/*Test Report List*/
	public function admin_pup_list()
	{
		$Admin = $this->Auth->user();
		$conditions = array('PupTest.is_delete' => '0');
		if (!empty($this->Session->read('Search.office'))) {
			$office_id = $this->Session->read('Search.office');
			$conditions['OR'][] = array('Lower(PupTest.office_id)' => $office_id);
		} else {
			if ($Admin['user_type'] == 'Subadmin' || $Admin['user_type'] == 'Staffuser') {
				$conditions['AND'][] = array('Lower(PupTest.office_id)' => $Admin['office_id']);
			}
		}
		if (!empty($this->request->query['patent_id'])) {
			$conditions['AND'][] = array('Lower(PupTest.patient_id)' => $this->request->query['patent_id']);
		}
		if (!empty($this->request->query['search'])) {
			$staff_name = explode(" ", @$this->request->query['search']);
			$search = strtolower(trim($this->request->query['search']));
			$conditions['OR'][] = array('Lower(PupTest.patient_name) like' => '%' . $search . '%');
			foreach ($staff_name as $key => $value) {
				$conditions['OR'][] = array('User.first_name like' => '%' . trim($value) . '%');
				$conditions['OR'][] = array('User.middle_name like' => '%' . trim($value) . '%');
				$conditions['OR'][] = array('User.last_name like' => '%' . trim($value) . '%');
			}
			$conditions['OR'][] = array('Patient.id_number' => $search);
			$conditions['OR'][] = array('Patient.id' => $search);
			$this->set(compact('search'));
		}

		if (!empty($this->request->query['patientreport'])) {
			$patientreport = trim($this->request->query['patientreport']); 
			if (is_numeric($patientreport)) { 
				$conditions['OR'][] = array('Patient.id' => $patientreport); /* 3 dec Added new line */
			}
			$this->set(compact('patientreport'));
		}
		//pr($conditions);die();
		 $limit=10;
			if(@$this->request->query['rempve_layout']==1){
	       $limit=100;
	   }
		//pr($conditions);die();
		$params = array(
			'conditions' => $conditions,
			'limit' => $limit,
			'order' => array('PupTest.id' => 'DESC')
		);

		$this->PupTest->unbindModel(array('hasMany' => array("PupPointdata")),false);
		$this->PupTest->bindModel(array('belongsTo' => array("Office")),false);

		$this->paginate = array('PupTest' => $params);
		$datas = $this->paginate('PupTest');
		//pr($datas); die;
		if ($Admin['user_type'] == 'Subadmin' || $Admin['user_type'] == 'Staffuser') {
			$this->loadModel('Payment');
			$get_last_payment = $this->Payment->find('first', array(
				'conditions' => array('Payment.user_id' => $Admin['id'], 'Payment.payment_status' => 'Success', 'Payment.expiry_date > ' => date('Y-m-d h:i:s')),
				'order' => array('id' => 'DESC')
			)); //check credit expire or not

			if ($Admin['user_type'] == 'Staffuser') {
				$user_s = $this->User->find('first', array(
					'conditions' => array('User.office_id' => $Admin['office_id'], 'User.user_type' => 'Subadmin')
				));
				$get_last_payment = $this->Payment->find('first', array(
					'conditions' => array('Payment.user_id' => $user_s['User']['id'], 'Payment.payment_status' => 'Success', 'Payment.expiry_date > ' => date('Y-m-d h:i:s')),
					'order' => array('id' => 'DESC')
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

		$TestNameArray = $this->Common->testNameArray();
		$this->set(compact('datas', 'check_payable', 'TestNameArray'));

		if(@$this->request->query['rempve_layout']==1){
	       $this->layout = false;
	       $this->render('pup_list');
	   }
	}

	/*Unity report view*/
	public function admin_view($id = null)
	{
		$this->layout = false;
		if (!empty($id)) {
			$data = $this->PupTest->findById($id);

			$this->set(compact(['data']));
		}
	}
 
public function admin_export($id=null){
	 
		$this->layout=false;
		$this->autoRender=false;
		if(empty($this->request->query['url'])){
			$file_name = uniqid().'_'.date('Ymd');
		}else{
			$file_name = $this->request->query['url'];
		}
		 
		$header = array('Pupil Diam OS', 'Pupil Diam OD', 'Test State', 'Time', 'Diagnosis', 'Age', 'Sex','Race');
		 $csv = fopen("php://output", 'w');
		 header('Content-Type: application/csv; charset=utf-8');
		 header('Content-type: application/ms-excel');
			if(!empty($id)){
		$data = $this->PupTest->findById($id);
			$file_name = str_replace(" ", "_", $data['PupTest']['patient_name']) . "_" . date('YmdHis', strtotime($data['PupTest']['created']));
			header('Content-Disposition: attachment; filename=' . $file_name . '.csv');
			$diagnosis = $this->Diagnosis->find('list',array('fields'=>array('id','name')),array( 'conditions' =>  array('Diagnosis.is_delete' =>0)));
			$this->loadModel('PatientDiagnosis');
			$this->loadModel('Diagnosis'); 
		 $patientDiagnosis = $this->PatientDiagnosis->find('list', array('conditions' => array('PatientDiagnosis.patient_id' => $data['PupTest']['patient_id']), 'fields' => array('diagnosis_id')));
		 $patientDiagnosisList = array();
		 foreach($patientDiagnosis as $key => $value){
		 	$patientDiagnosisList[] = $value;
		 }
		$diagnosis = $this->Diagnosis->find('list',array('fields'=>array('id','name')),array( 'conditions' =>  array('Diagnosis.is_delete' =>0)));
		
		 $d1 = new DateTime($data['PupTest']['created']);
		$d2 = new DateTime($data['Patient']['dob']);

		$diff = $d2->diff($d1);

		fputcsv($csv, array_values($header));
			 
			foreach ($data['PupPointdata'] as $key => $pdata) {
				$record[] = $pdata['pupilDiam_OS'];
				$record[] = $pdata['pupilDiam_OD'];
				$record[] = $pdata['testState'];
				$record[] = $pdata['time'];
					
				if(isset($patientDiagnosisList[$key]) && isset($diagnosis[$patientDiagnosisList[$key]])){
					$record[]= $diagnosis[$patientDiagnosisList[$key]];
				}else{
					$record[] ='';
				}
				if($key==0){
					$record[] = $diff->y;
					$record[] = isset($data['Patient']['gender'])?$data['Patient']['gender']:'';
					$record[] = isset($data['Patient']['race'])?$data['Patient']['race']:'';
				}else{
					$record[] ='';
					$record[] ='';
					$record[] ='';
				}
				fputcsv($csv, $record);
				$record = array();
			
			}
			fclose($csv);
			exit();
		}else{
		    	header('Content-Disposition: attachment; filename='.$file_name.'.csv');
				fputcsv($csv, array_values($header));
		}
	}
	public function admin_delete($id = NULL)
	{
		$Admin = $this->Auth->user();
		//pr($Admin);die;
		if ($Admin['user_type'] == 'Admin' || $Admin['user_type'] == 'Subadmin') {
			$delete_record = $this->PupTest->updateAll(
				array('PupTest.is_delete' => '1'),
				array('PupTest.id' => $id)
			);
			if ($delete_record) {
				$this->Session->setFlash("Pup Test deleted successfully.", 'message', array('class' => 'message'));
			} else {
				$this->Session->setFlash("Unable to delete.", 'message', array('class' => 'message'));
			}
			$this->redirect($this->referer());
		} else {
			echo 'can not access.';
			die;
		}
	}
}

?>
