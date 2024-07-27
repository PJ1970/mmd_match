<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class ActController extends AppController
{
	public $uses = array('User', 'Patients', 'Tests', 'Practice', 'Office', 'Files', 'VfPointdata', 'Pointdata', 'Masterdata', 'MasterPointdata', 'DevicePreference', 'Diagnosis', 'Cms', 'DarkAdaption', 'DaPointData', 'ActTest', 'ActPointdata');

	var $helpers = array('Html', 'Form', 'Js' => array('Jquery'), 'Custom');

	public $components = array('Auth' => array('authorize' => array('Controller')), 'Session', 'Email', 'Common', 'RememberMe');

	public $allowedActions = array();

	function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow($this->allowedActions);
	}


	/*Test Report List*/
	public function admin_act_list()
	{
		$Admin = $this->Auth->user();
		$conditions = array('ActTest.is_delete' => '0');
		if (!empty($this->Session->read('Search.office'))) {
			$office_id = $this->Session->read('Search.office');
			$conditions['OR'][] = array('Lower(ActTest.office_id)' => $office_id);
		} else {
			if ($Admin['user_type'] == 'Subadmin' || $Admin['user_type'] == 'Staffuser') {
				$conditions['AND'][] = array('Lower(ActTest.office_id)' => $Admin['office_id']);
			}
		}
		if (!empty($this->request->query['patent_id'])) {
			$conditions['AND'][] = array('Lower(ActTest.patient_id)' => $this->request->query['patent_id']);
		}
		if (!empty($this->request->query['search'])) {
			$staff_name = explode(" ", @$this->request->query['search']);
			$search = strtolower(trim($this->request->query['search']));
			$conditions['OR'][] = array('Lower(ActTest.patient_name) like' => '%' . $search . '%');
			foreach ($staff_name as $key => $value) {
				$conditions['OR'][] = array('User.first_name like' => '%' . trim($value) . '%');
				$conditions['OR'][] = array('User.middle_name like' => '%' . trim($value) . '%');
				$conditions['OR'][] = array('User.last_name like' => '%' . trim($value) . '%');
			}
			$conditions['OR'][] = array('Patient.id_number' => $search);
			$conditions['OR'][] = array('Patient.id' => $search);
			$this->set(compact('search'));
		}
		//pr($conditions);die();
		$params = array(
			'conditions' => $conditions,
			'limit' => 10,
			'order' => array('ActTest.id' => 'DESC')
		);

		$this->ActTest->unbindModel(array('hasMany' => array("ActPointdata")),false);
		$this->ActTest->bindModel(array('belongsTo' => array("Office")),false);

		$this->paginate = array('ActTest' => $params);
		$datas = $this->paginate('ActTest');
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
	}

	/*Unity report view*/
	public function admin_view($id = null)
	{
		$this->layout = false;
		if (!empty($id)) {
			$data = $this->ActTest->findById($id);

			$this->set(compact(['data']));
		}
	}

	public function admin_export($id = null)
	{

		$this->layout = false;
		$this->autoRender = false;

		if (empty($this->request->query['url'])) {
			$file_name = uniqid() . '_' . date('Ymd');
		} else {
			$file_name = $this->request->query['url'];
		}

		$header = array('X', 'Y', 'Z', 'Eye', 'Test State', 'locationX', 'locationY', 'locationZ', 'TargetSize');
		$csv = fopen("php://output", 'w');
		header('Content-Type: application/csv; charset=utf-8');
		header('Content-type: application/ms-excel');
		if (!empty($id)) {
			$data = $this->ActTest->findById($id);
			$file_name = str_replace(" ", "_", $data['ActTest']['patient_name']) . "_" . date('YmdHis', strtotime($data['ActTest']['created']));
			header('Content-Disposition: attachment; filename=' . $file_name . '.csv');
			fputcsv($csv, array_values($header));


			//pr($data); die;
			foreach ($data['StbPointdata'] as $pdata) {
				$record[] = $pdata['x'];
				$record[] = $pdata['y'];
				$record[] = $pdata['z'];
				$record[] = ($pdata['eye']) ? 'OD' : 'OS';
				$record[] = $pdata['testState'];
				$record[] = $pdata['locationX'];
				$record[] = $pdata['locationY'];
				$record[] = $pdata['locationZ'];
				$record[] = $pdata['TargetSize'];
				fputcsv($csv, $record);
				$record = array();
			}
			fclose($csv);
			exit();
		} else {
			header('Content-Disposition: attachment; filename=' . $file_name . '.csv');
			fputcsv($csv, array_values($header));
		}
	}

	public function admin_delete($id = NULL)
	{
		$Admin = $this->Auth->user();
		//pr($Admin);die;
		if ($Admin['user_type'] == 'Admin' || $Admin['user_type'] == 'Subadmin') {
			$delete_record = $this->ActTest->updateAll(
				array('ActTest.is_delete' => '1'),
				array('ActTest.id' => $id)
			);
			if ($delete_record) {
				$this->Session->setFlash("ACT Test deleted successfully.", 'message', array('class' => 'message'));
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
