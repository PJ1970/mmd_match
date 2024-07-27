<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class StbController extends AppController
{
	public $uses = array('User', 'Patients', 'Tests', 'UserNotification', 'UserDevice', 'TestReport', 'TestDevice', 'Practice', 'Office', 'Files', 'VfPointdata', 'Pointdata', 'Masterdata', 'MasterPointdata', 'DevicePreference', 'Diagnosis', 'Cms', 'DarkAdaption', 'DaPointData', 'StbTest', 'StbPointdata');

	var $helpers = array('Html', 'Form', 'Js' => array('Jquery'), 'Custom');

	public $components = array('Auth' => array('authorize' => array('Controller')), 'Session', 'Email', 'Common', 'RememberMe');
	public $allowedActions = array();


	function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow($this->allowedActions);
	}


	/*Test Report List*/
	public function admin_stb_list()
	{
		$Admin = $this->Auth->user();
		$conditions = array('StbTest.is_delete' => '0');
		if (!empty($this->Session->read('Search.office'))) {
			$office_id = $this->Session->read('Search.office');
			$conditions['OR'][] = array('Lower(StbTest.office_id)' => $office_id);
		} else {
			if ($Admin['user_type'] == 'Subadmin' || $Admin['user_type'] == 'Staffuser') {
				$conditions['AND'][] = array('Lower(StbTest.office_id)' => $Admin['office_id']);
			}
		}
		if (!empty($this->request->query['patent_id'])) {
			$conditions['AND'][] = array('Lower(StbTest.patient_id)' => $this->request->query['patent_id']);
		}
		if (!empty($this->request->query['search'])) {
			$staff_name = explode(" ", @$this->request->query['search']);
			$search = strtolower(trim($this->request->query['search']));
			$conditions['OR'][] = array('Lower(StbTest.patient_name) like' => '%' . $search . '%');
			foreach ($staff_name as $key => $value) {
				$conditions['OR'][] = array('User.first_name like' => '%' . trim($value) . '%');
				$conditions['OR'][] = array('User.middle_name like' => '%' . trim($value) . '%');
				$conditions['OR'][] = array('User.last_name like' => '%' . trim($value) . '%');
			}
			$conditions['OR'][] = array('Patient.id_number' => $value);
			$conditions['OR'][] = array('Patient.id' => $value);
			// pr($conditions);die();
			$this->set(compact('search'));
		}

		if (!empty($this->request->query['patientreport'])) {
			$patientreport = trim($this->request->query['patientreport']); 
			if (is_numeric($patientreport)) { 
				$conditions['OR'][] = array('Patient.id' => $patientreport); /* 3 dec Added new line */
			}
			$this->set(compact('patientreport'));
		}

		$params = array(
			'conditions' => $conditions,
			'limit' => 10,
			'order' => array('StbTest.id' => 'DESC')
		);

		$this->StbTest->unbindModel(array('hasMany' => array("StbPointdata")));

		$this->paginate = array('StbTest' => $params);
		$datas = $this->paginate('StbTest');
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

		$download = array();
		if (count($datas) > 0):
			foreach ($datas as $ospt):

				// pr($ospt);
				//die;

				$patient_name = Hash::get($ospt, 'StbTest.patient_name');
				$patient_id = Hash::get($ospt, 'StbTest.patient_id');


				$odpt = $this->StbTest->find('first', array(
						'conditions' => array(
							//'Pointdata.id !='=> Hash::get($ospt, 'Pointdata.id'),
							'StbTest.is_delete' => 0,
							'StbTest.patient_id' => $patient_id,

							'StbTest.eye_select !=' => Hash::get($ospt, 'StbTest.eye_select'),
							'cast(StbTest.created as date) =' => date('Y-m-d', strtotime(Hash::get($ospt, 'StbTest.created'))),
						),
						'order' => 'StbTest.id DESC',
					)
				);

				$od_pdf = "";
				$os_pdf = "";
				if (Hash::get($ospt, 'StbTest.eye_select') == 1) {
					$od_pdf = Hash::get($ospt, 'StbTest.pdf');
				} else {
					$os_pdf = Hash::get($ospt, 'StbTest.pdf');

				}

				if (Hash::get($odpt, 'StbTest.eye_select') == 1) {
					if (empty($od_pdf)) {
						$od_pdf = Hash::get($odpt, 'StbTest.pdf');
					}
				} else {
					if (empty($os_pdf)) {
						$os_pdf = Hash::get($odpt, 'StbTest.pdf');
					}
				}

				$os_pdf_link = WWW_BASE . 'stbData/' . $os_pdf;
				$od_pdf_link = WWW_BASE . 'stbData/' . $od_pdf;

				$os_dicom_link = WWW_BASE . 'admin/unityreports/exportImageda/' . $os_pdf;// $this->Html->url(['controller'=>'unityreports','action'=>'exportImage',$os_pdf]);
				$od_dicom_link = WWW_BASE . 'admin/unityreports/exportImageda/' . $od_pdf;//$this->Html->url(['controller'=>'unityreports','action'=>'exportImage',$od_pdf]);


				$DarkAdaption_id = Hash::get($ospt, 'StbTest.id');
				$os_dicom_link = '/admin/unityreports/exportDicomda/' . $DarkAdaption_id . '/' . $os_pdf;
				$od_dicom_link = '/admin/unityreports/exportDicomda/' . $DarkAdaption_id . '/' . $od_pdf;

				$os_pointdata_id = Hash::get($ospt, 'StbTest.id');
				/*if(!empty($os_pointdata_id)):
					$download[$os_pointdata_id]['class'][]='os-cls-'.$os_pointdata_id;
				endif;*/
				if (!empty($os_pdf)) {
					$download[$os_pointdata_id]['pdf'][] = $os_pdf_link;
					$download[$os_pointdata_id]['dicom'][] = $os_dicom_link;
					$download[$os_pointdata_id]['filename'][] = $patient_name . '-OS-' . time();
				}
				if (!empty($od_pdf)) {
					$download[$os_pointdata_id]['pdf'][] = $od_pdf_link;
					$download[$os_pointdata_id]['dicom'][] = $od_dicom_link;
					$download[$os_pointdata_id]['filename'][] = $patient_name . '-OD-' . time();

				}
				$od_pointdata_id = Hash::get($odpt, 'StbTest.id');
				if (!empty($od_pointdata_id)):
					$download[$os_pointdata_id]['tr_id'] = 'stbdata-' . $od_pointdata_id;
				endif;

			endforeach;
		endif;


		$TestNameArray = $this->Common->testNameArray();
		$this->set(compact('datas', 'check_payable', 'TestNameArray', 'download'));
	}

	/*Unity report view*/
	public function admin_view($id = null)
	{
		$this->layout = false;
		if (!empty($id)) {
			if($this->Session->read('Auth.Admin.user_type') != 'Admin'){
				$this->StbTest->unbindModel(array('hasMany' => array("StbPointdata")));
			}
			$data = $this->StbTest->findById($id);

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
			$data = $this->StbTest->findById($id);
			$file_name = str_replace(" ", "_", $data['StbTest']['patient_name']) . "_" . date('YmdHis', strtotime($data['StbTest']['created']));
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
			$delete_record = $this->StbTest->updateAll(
				array('StbTest.is_delete' => '1'),
				array('StbTest.id' => $id)
			);
			if ($delete_record) {
				$this->Session->setFlash("STB Test deleted successfully.", 'message', array('class' => 'message'));
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
