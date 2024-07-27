<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class UnityreportsController extends AppController
{
	public $uses = array('Admin', 'User', 'Testreport', 'Office', 'File', 'Pointdata', 'Patient');

	var $helpers = array('Html', 'Form', 'Js' => array('Jquery'), 'Custom', 'Dropdown');

	public $components = array('Auth' => array('authorize' => array('Controller')), 'Session', 'Email', 'Common', 'RememberMe');

	public $allowedActions = array('admin_exportDicomda','admin_exportDicomstb','admin_exportDicom');

	function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow($this->allowedActions);
	}

	/*Test Report List*/
	public function admin_unity_reports_list($reportType=""){
		$vfTestNames = array("Central_20_Point", "Central_40_Point", "Esterman_120_point","Estrman_120_point", "76_Point_Pattern", "Central_80_Point", "Central_166_Point", "Armally_Central","Central_10_2", "Central_24_1", "Central_24_2", "Central_24_2C" , "Central_30_1", "Central_30_2","Superior_24_2", "Superior_30_2", "Superior_50_1", "Superior_64", "Neuro_20", "Neuro_35", "Full_Field_120_PTS","Kinetic_60_16", "Kinetic_30_16", "Kinetic_60_28", "Kinetic_30_28", "Ptosis_9_PT", "Ptosis_Auto_9_PT","Detailed_Progression_Central_20_Point","Detailed_Progression_Central_24_2","Detailed_Progression_Central_40_Point","C20-1", "C20-5", "N30-1", "N30-5","N30","AFVF_8_PT","Amsler_Grid_10_16");
		$fdtTestNames = array("C20-1", "C20-5", "N30-1", "N30-5","N30");
		$vsTestNames = array("Vision Screening");
		$PupTestNames = array("Pupolimeter");

		$Admin = $this->Auth->user();
		$conditions = array('Pointdata.is_delete' => '0');

		if($reportType == "VF") {
			$conditions[] = array('Pointdata.test_name' => $vfTestNames);
			$this->set("testOptions",$vfTestNames);
		}

		if($reportType == "VS") {
			$conditions[] = array('Pointdata.test_name like' => '%Vision Screening%');
			$this->set("testOptions",array());
		}

		if($reportType == "FDT") {
			$conditions[] = array('Pointdata.test_name' => $fdtTestNames);
			$this->set("testOptions",$fdtTestNames);
		}
		if($reportType == "pup_list") {
			$conditions[] = array('Pointdata.test_name like' => '%Pupolimeter%');
			$this->set("testOptions",array());
		}

		$this->set(compact('reportType'));

		if ($Admin['user_type'] == 'Admin') {
			if (!empty($this->Session->read('Search.office'))) {
				$office_id['User']['office_id'] = $this->Session->read('Search.office');
				$staffuserAdmin = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
				//pr($staffuserAdmin); die;
				$conditions[]['Pointdata.staff_id'] = $staffuserAdmin;
			}
		} elseif ($Admin['user_type'] == 'Subadmin') {
			//$staffuser = $this->User->find('list',array('conditions'=>array('User.created_by'=>$Admin['id'])));
			$office_id = $this->User->find('first', array('conditions' => array('User.id' => $Admin['id'], 'User.user_type' => 'Subadmin'), 'fields' => array('User.office_id')));
			if (!empty($this->Session->read('Search.office'))) {
				$office_id['User']['office_id'] = $this->Session->read('Search.office');
			}
			$staffuser = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
			// Calculate Total Records..
			$conditions[]['Pointdata.staff_id'] = $staffuser;

		} else {
			$office_id = $this->User->find('first', array('conditions' => array('User.id' => $Admin['id'], 'User.user_type' => 'Staffuser'), 'fields' => array('User.office_id')));
			if (!empty($this->Session->read('Search.office'))) {
				$office_id['User']['office_id'] = $this->Session->read('Search.office');
			}
			//$all_staff_ids=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.id')));
			$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
			//pr($all_staff_ids); die;
			$conditions[]['Pointdata.staff_id'] = $all_staff_ids;
		}
		$test_name = @$this->request->query['test_name'];
		if (!empty($test_name))
			$conditions['AND'][] = array('Pointdata.test_name like' => '%' . trim($test_name) . '%');
		if ($Admin['user_type'] == 'Admin') {
			$version = @$this->request->query['version'];

			$eye_select = @$this->request->query['eye_select'];
			$patient_name = (!empty(@$this->request->query['patient_name'])) ? explode(" ", @$this->request->query['patient_name']) : [];
			$include_patient_name = (!empty(@$this->request->query['include_patient_name'])) ? explode(" ", @$this->request->query['include_patient_name']) : [];
			$patient_dob=@$this->request->query['patient_dob'];
			$patient_age = @$this->request->query['patient_age'];
			$race = @$this->request->query['race'];
			$staff_name = '';
			if ($eye_select != '' && $eye_select != 2)
				$conditions['AND'][] = array('Pointdata.eye_select' => $eye_select);
			if (!empty($version))
				$conditions['AND'][] = array('Pointdata.version like' => '%' . trim($version) . '%');
			if (!empty($race))
				$conditions['AND'][] = array('Patient.race like' => '%' . trim($race) . '%');
			if (!empty($patient_age)) {
				$ageArray = explode('-', $patient_age);
				//pr($ageArray); die;
				$conditions['AND'][] = array('DATEDIFF(CURDATE(), STR_TO_DATE(Patient.dob, "%d-%m-%Y"))<=' . $ageArray[1] * 365);
				$conditions['AND'][] = array('DATEDIFF(CURDATE(), STR_TO_DATE(Patient.dob, "%d-%m-%Y"))>=' . $ageArray[0] * 365);
			}
			if (!empty($this->request->query['page_type'])) {
				$vfTestname = array("Central_20_Point", "Central_40_Point", "Esterman_120_point","Estrman_120_point", "76_Point_Pattern", "Central_80_Point", "Central_166_Point", "Armally_Central", "Central_10_2", "Central_24_1", "Central_24_2", "Central_24_2C", "Central_30_1", "Central_30_2", "Superior_24_2", "Superior_30_2", "Superior_50_1", "Superior_64", "Neuro_20", "Neuro_35", "Full_Field_120_PTS", "Kinetic_60_16", "Kinetic_30_16", "Kinetic_60_28", "Kinetic_30_28", "Ptosis_9_PT", "Ptosis_Auto_9_PT","AFVF_8_PT");
				$fdtTestname = array("C20-1", "C20-5", "N30-1", "N30-5", "N30","Amsler_Grid_10_16");
				if ($this->request->query['page_type'] == 'vf') {
					$conditions['AND'][] = array('Pointdata.test_name' => $vfTestname);
				} else if ($this->request->query['page_type'] == 'fdt') {
					$conditions['AND'][] = array('Pointdata.test_name' => $fdtTestname);
				}
			}
			if (!empty($patient_name)) {
				foreach ($patient_name as $key => $value) {
					$conditions['AND'][] = array('Patient.first_name NOT like' => '%' . trim($value) . '%');
					$conditions['AND'][] = array('Patient.middle_name NOT like' => '%' . trim($value) . '%');
					$conditions['AND'][] = array('Patient.last_name NOT like' => '%' . trim($value) . '%');
				}
				/* $conditions['AND'][] = array("NOT"=>array("Patient.first_name" =>$patient_name,
														  "Patient.middle_name" =>$patient_name,
														  "Patient.last_name" =>$patient_name)); */

			}
			if (!empty($include_patient_name)) {
				foreach ($include_patient_name as $key => $value) {
					$conditions['OR'][] = array('Patient.first_name like' => '%' . trim($value) . '%');
					$conditions['OR'][] = array('Patient.middle_name like' => '%' . trim($value) . '%');
					$conditions['OR'][] = array('Patient.last_name like' => '%' . trim($value) . '%');
				}
				/* $conditions['AND'][] = array("OR" => array("Patient.first_name" =>$include_patient_name,
											 "Patient.middle_name" =>$include_patient_name,
											 "Patient.last_name" =>$include_patient_name)); */
				$conditions['OR'][] = array('Patient.id_number' => $value);
			}
			$this->set(compact('version', 'test_name', 'eye_select', 'patient_name', 'staff_name', 'include_patient_name', 'patient_dob', 'patient_age', 'race'));
		}
		if (!empty($this->request->query['search'])) {
			//echo $this->request->query['search'];die;
			$search = trim($this->request->query['search']);
			$conditions['OR'][] = array('Pointdata.staff_name like' => '%' . $search . '%');
			$conditions['OR'][] = array('Pointdata.patient_name like' => '%' . $search . '%');
			$conditions['OR'][] = array('Pointdata.created like' => '%' . date('Y-m-d', strtotime($search)) . '%');
			if (is_numeric($search)) {
				$conditions['OR'][] = array('Pointdata.id' => $search);
				$conditions['OR'][] = array('Pointdata.patient_id' => $search); /* 3 dec Added new line */
			}
			$conditions['OR'][] = array('Patient.id_number' => $search);
			$this->set(compact('search'));
		}
		$this->set(compact('test_name'));
		//pr($conditions); die;
		//creating virtual field for full name
		$this->Pointdata->virtualFields['staff_name'] = "CONCAT(User.first_name,' ', User.last_name)";
		$this->Pointdata->virtualFields['patient_name'] = "if(Patient.middle_name is null || Patient.middle_name='',CONCAT(Patient.first_name,' ',Patient.last_name),CONCAT(Patient.first_name,' ', Patient.middle_name,' ',Patient.last_name))";
		$this->Pointdata->virtualFields['patient_dob'] = "Patient.dob";
		//$this->Pointdata->virtualFields['patient_age'] = "DATEDIFF(CURDATE(), STR_TO_DATE(Patient.dob, '%d-%m-%Y') )";
		$this->Pointdata->virtualFields['patient_age_years'] = "TIMESTAMPDIFF(YEAR,  STR_TO_DATE(Patient.dob, '%d-%m-%Y'), now())";
		$this->Pointdata->virtualFields['patient_age_months'] = "TIMESTAMPDIFF(MONTH,  STR_TO_DATE(Patient.dob, '%d-%m-%Y'), now())% 12";
		$this->Pointdata->virtualFields['patient_age_days'] = "FLOOR( TIMESTAMPDIFF(DAY, STR_TO_DATE(Patient.dob, '%d-%m-%Y'), now())% 30.4375 )";
		$this->Pointdata->virtualFields['race'] = "Patient.race";

		$params = array(
			'conditions' => $conditions,
			'limit' => 10,
			'order' => array('Pointdata.created' => 'DESC') 
			//'fields'=>array('Patient.first_name','Patient.last_name','Patient.middle_name','Patient.dob','Pointdata.file','Patient.id','Pointdata.id','Pointdata.patient_name','Pointdata.created','Pointdata.patient_id','Pointdata.patient_dob','Pointdata.test_name','Pointdata.eye_select','Pointdata.baseline','Pointdata.staff_id','Pointdata.staff_name','Pointdata.patient_age_years','Pointdata.version','Pointdata.diagnosys','Pointdata.source','Patient.id_number')
		);

		/*//-----------debug:0
		if(!empty($_GET['ttt'])){
			pr($params); die;
		}*/
		$this->Pointdata->unbindModel(array(
			'belongsTo' => array('Test'),
			'hasMany' => array('VfPointdata')
		),false);

		$this->paginate = array('Pointdata' => $params);
		if ($Admin['user_type'] == 'Admin' || $Admin['user_type'] =='SuperSubadmin') {
			//$this->Pointdata->useTable = 'pointdatas_admin';
			$datas = $this->paginate('Pointdata');
		}else{
			try {
				$this->Pointdata->useTable = 'pointdatas_'.$office_id['User']['office_id'];   //for removing view by adarsh
				$datas = $this->paginate('Pointdata');
			}catch(Exception $e){
				$this->Pointdata->useTable = 'pointdatas';
				$datas = $this->paginate('Pointdata');
			}
		}
		//$datas = $this->paginate('Pointdata');
		
		
		//echo "<pre>"; 
	//print_r($datas);
	//die();

		if ($Admin['user_type'] == 'Subadmin' || $Admin['user_type'] == 'Staffuser') {
			if ($Admin['user_type'] == 'Staffuser') {
				/* $user_s = $this->User->find('first',array(
					'conditions'=>array('User.id'=>$Admin['created_by'])
				)); */
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
		//$this->loadModel('Office'); // ------- comment
		if (!empty($Admin['Office']['id'])) { //-------added
			$check_payable = $this->Office->find('first', array(
				'conditions' => array('Office.id' => $Admin['Office']['id'])
			));
		}

		/*//-----------debug:1
			if(!empty($_GET['ttt'])){
						pr($check_payable);
						print_r(array(
				'conditions'=>array('Office.id'=>$Admin['Office']['id'])
			));
			die;
		}*/

		//pr($datas);die;
		$office = $this->Office->find('list', array('fields' => array('Office.id', 'Office.name')));
		//pr($office); die;

		/*//-----------debug:2
		if(!empty($_GET['ttt'])){
			pr($office); die;
		}*/

		//------RELATED OS/OD REPORT
		$download = array();
		$downloads = array();

		if (count($datas) > 0):
			foreach ($datas as $ospt):
				$first_name = @$ospt['Patient']['first_name'];// Hash::get($ospt, 'Patient.first_name');
				$last_name = @$ospt['Patient']['last_name'];//Hash::get($ospt, 'Patient.last_name');
				$test_name = @$ospt['Pointdata']['test_name'];// Hash::get($ospt, 'Pointdata.test_name');
				/* $this->Pointdata->unbindModel(array(
				   'belongsTo'=>array('User','Patient','Test'),
				   'hasMany'=>array('VfPointdata')
				));*/
				$this->Pointdata->unbindModel(array(
					'belongsTo' => array('Test'),
					'hasMany' => array('VfPointdata')
				),false);
				$odpt = $this->Pointdata->find('first', array(
						'conditions' => array(
							//'Pointdata.id !='=> Hash::get($ospt, 'Pointdata.id'),
							'Pointdata.is_delete' => 0,
							'Pointdata.test_name' => $test_name,//Hash::get($ospt, 'Pointdata.test_name'),
							//'Patient.id' => Hash::get($pointdata_1, 'Pointdata.test_name') ,
							'Patient.first_name' => $first_name,// Hash::get($ospt, 'Patient.first_name'),
							'Patient.middle_name' => @$ospt['Patient']['middle_name'],// Hash::get($ospt, 'Patient.middle_name'),
							'Patient.last_name' => $last_name, //Hash::get($ospt, 'Patient.last_name'),
							'Patient.dob' => @$ospt['Patient']['dob'],//Hash::get($ospt, 'Patient.dob'),
							'Pointdata.eye_select !=' => @$ospt['Pointdata']['eye_select'],// Hash::get($ospt, 'Pointdata.eye_select') ,
							'cast(Pointdata.created as date) =' => date('Y-m-d', strtotime(@$ospt['Pointdata']['created'])), //Hash::get($ospt, 'Pointdata.created')
						),
						'fields' => array('Pointdata.eye_select', 'Pointdata.file', 'Pointdata.id'),
						'order' => 'Pointdata.id DESC',
					)
				);
				//echo "<pre>";
				//print_r($odpt);
				//die;
				// Hash::get($ospt, 'Pointdata.file');
				//$ospt
				$od_pdf = "";
				$os_pdf = "";
				$os_od_pdf = "";
				if (@$ospt['Pointdata']['eye_select'] == 1) { //Hash::get($ospt, 'Pointdata.eye_select')
					$od_pdf = @$ospt['Pointdata']['file'];// Hash::get($ospt, 'Pointdata.file');
				} else {
					$os_pdf = @$ospt['Pointdata']['file'];// Hash::get($ospt, 'Pointdata.file');
				}
				//$os_od_pdf = @$ospt['Pointdata']['file_merge'];
				//$odpt
				if (@$odpt['Pointdata']['eye_select'] == 1) { //Hash::get($odpt, 'Pointdata.eye_select')
					if (empty($od_pdf)) {
						$od_pdf = @$odpt['Pointdata']['file'];// Hash::get($odpt, 'Pointdata.file');
					}
				} else {
					if (empty($os_pdf)) {
						$os_pdf = @$odpt['Pointdata']['file'];//Hash::get($odpt, 'Pointdata.file');
					}
				}
				$os_pdf_file = explode(".",$os_pdf)[0];
				$od_pdf_file = explode(".",$od_pdf)[0];
				$os_pdf_link = WWW_BASE . 'pointData/' . $os_pdf;
				$od_pdf_link = WWW_BASE . 'pointData/' . $od_pdf;
				if((!empty($os_pdf_file)) && (!empty($od_pdf_file))){
					$os_od_pdf_link = WWW_BASE . 'pointData/' . $os_pdf_file.'_'.$od_pdf_file.'_merge.pdf';
				}else if($os_pdf){
					$os_od_pdf_link = WWW_BASE . 'pointData/' . $os_pdf;
				}else{
					$os_od_pdf_link = WWW_BASE . 'pointData/' . $od_pdf;
				}
				$patient_id = @$ospt['Patient']['id'];//Hash::get($ospt, 'Patient.id');

				//$os_dicom_link=WWW_BASE.'admin/unityreports/exportImage/'.$os_pdf;// $this->Html->url(['controller'=>'unityreports','action'=>'exportImage',$os_pdf]);
				//$od_dicom_link= WWW_BASE.'admin/unityreports/exportImage/'.$od_pdf;//$this->Html->url(['controller'=>'unityreports','action'=>'exportImage',$od_pdf]);

				$os_dicom_link = '/admin/unityreports/exportDicom/' . $patient_id . '/' . $os_pdf;
				$od_dicom_link = '/admin/unityreports/exportDicom/' . $patient_id . '/' . $od_pdf;

				$os_pointdata_id = @$ospt['Pointdata']['id'];//Hash::get($ospt, 'Pointdata.id');
				/*if(!empty($os_pointdata_id)):
					$download[$os_pointdata_id]['class'][]='os-cls-'.$os_pointdata_id;
				endif;*/
				if(!empty($ospt['Patient']['id_number'])){
					$p_id = $ospt['Patient']['id_number'];
				}else{
					$p_id= 'NA';
				}
				if (!empty($os_pdf)) {
					$download[$os_pointdata_id]['pdf'][] = $os_pdf_link;
					$download[$os_pointdata_id]['dicom'][] = $os_dicom_link . '?filename=' . $first_name . '-' . $last_name . '-' . $test_name . '-OS';
					$download[$os_pointdata_id]['filename'][] = $first_name . '-' . $last_name . '-' . $test_name . '-OS-'.$p_id.'-'. time();

				}
				if (!empty($od_pdf)) {
					$download[$os_pointdata_id]['pdf'][] = $od_pdf_link;
					$download[$os_pointdata_id]['dicom'][] = $od_dicom_link . '?filename=' . $first_name . '-' . $last_name . '-' . $test_name . '-OD';
					$download[$os_pointdata_id]['filename'][] = $first_name . '-' . $last_name . '-' . $test_name . '-OD-'.$p_id.'-' . time();

				}
				if ((!empty($os_pdf)) && (!empty($od_pdf))) {
					$downloads[$os_pointdata_id]['mergepdf'][] = $os_od_pdf_link;
					$downloads[$os_pointdata_id]['mergefilename'][] = $first_name . '-' . $last_name . '-' . $test_name . '-OD-OS-'.$p_id.'-'.$os_pdf_file.'_'.$od_pdf_file;
				}
				$od_pointdata_id = @$odpt['Pointdata']['id'];//Hash::get($odpt, 'Pointdata.id');
				$downloads[$os_pointdata_id]['tr_id'] = 'ptdata-' . $od_pointdata_id;
				if (!empty($od_pointdata_id)):
					$download[$os_pointdata_id]['tr_id'] = 'ptdata-' . $od_pointdata_id;
				endif;
			endforeach;
		endif;
		//-------------------------

		$TestNameArray = $this->Common->testNameArray();
	
		$this->set(compact('datas', 'check_payable', 'office', 'TestNameArray', 'download','downloads'));
	}


	public function admin_ajaxUnityReportList()
	{
		$this->layout = false;
		$this->autoRender = false;
		$requestData = $this->request->data;
		$columns = array(
			// datatable column index  => database column name
			0 => 'Pointdata.id',
			1 => 'Pointdata.created',
			2 => 'Pointdata.staff_name',
			3 => 'Pointdata.patient_name',
			4 => 'Pointdata.report_view'
		);


		$Admin = $this->Auth->user();

		if ($Admin['user_type'] == 'Admin') {
			// Calculate Total Records..
			$totalData = $this->Pointdata->find('count');
			$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
			$conditions = array();

		} elseif ($Admin['user_type'] == 'Subadmin') {
			//$staffuser = $this->User->find('list',array('conditions'=>array('User.created_by'=>$Admin['id'])));
			$office_id = $this->User->find('first', array('conditions' => array('User.id' => $Admin['id'], 'User.user_type' => 'Subadmin'), 'fields' => array('User.office_id')));
			$staffuser = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));

			// Calculate Total Records..
			$conditions['Pointdata.staff_id'] = $staffuser;
			$totalData = $this->Pointdata->find('count', array('conditions' => $conditions));
			$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		} else {
			$office_id = $this->User->find('first', array('conditions' => array('User.id' => $Admin['id'], 'User.user_type' => 'Staffuser'), 'fields' => array('User.office_id')));
			$all_staff_ids = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id'], 'User.user_type' => 'Staffuser'), 'fields' => array('User.id')));
			// Calculate Total Records..
			$conditions['Pointdata.staff_id'] = $all_staff_ids;
			$totalData = $this->Pointdata->find('count', array('conditions' => $conditions));
			$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		}
		//creating virtual field for full name
		$this->Pointdata->virtualFields['staff_name'] = "CONCAT(User.first_name,' ', User.last_name)";
		$this->Pointdata->virtualFields['patient_name'] = "CONCAT(Patient.first_name, ' ' ,Patient.middle_name,' ',Patient.last_name)";


		//print_r($requestData);die;
		// if there is a search parameter, $requestData['search']['value'] contains search parameter
		if (!empty($requestData['search']['value'])) {
			$conditions['AND'] = array(
				'OR' => array(
					"Pointdata.staff_name LIKE " => $requestData['search']['value'] . "%",
					"Pointdata.patient_name LIKE " => $requestData['search']['value'] . "%",
					"Pointdata.created" => date('Y-m-d', strtotime($requestData['search']['value']))
				)
			);
			$totalFiltered = $this->Pointdata->find('count', array('conditions' => $conditions));
		}


		$datas = $this->Pointdata->find(
			'all', array(
				'conditions' => $conditions,
				'order' => array($columns[$requestData['order'][0]['column']] . " " . strtoupper($requestData['order'][0]['dir'])),
				'limit' => $requestData['length'],
				'offset' => $requestData['start'],
			)
		);
		//print_r($datas);die('hii');
		$data = array();
		$i = 0;

		if (($requestData['order'][0]['column'] == 0) && ($requestData['order'][0]['dir'] == 'asc')) {
			$page = $totalFiltered;
		} else {
			$page = $requestData['start'];
		}
		foreach ($datas as $key => $staff) {
			if (($requestData['order'][0]['column'] == 0) && ($requestData['order'][0]['dir'] == 'asc')) {
				$data[$i]['id'] = $page - $key - $requestData['start'];
			} else {
				$data[$i]['id'] = $page + $key + 1;
			}
			$data[$i]['created'] = date('d M Y', strtotime($staff['Pointdata']['created']));

			$data[$i]['staff_name'] = "<a style='cursor: pointer;'  title='View' staffId='" . $staff['Pointdata']['staff_id'] . "'class='staff' data-toggle='modal' data-target='#staffView'>" . $staff['Pointdata']['staff_name'] . "</a>";
			$data[$i]['patient_name'] = "<a style='cursor: pointer;'  title='View' patientId='" . $staff['Pointdata']['patient_id'] . "'class='patient' data-toggle='modal' data-target='#patientView'>" . $staff['Pointdata']['patient_name'] . "</a>";
			$data[$i]['report_view'] = "<a style='cursor: pointer;'  title='View' testreportId='" . $staff['Pointdata']['id'] . "'class='testreport' data-toggle='modal' data-target='#reportView'><i class='fa fa-eye' aria-hidden='true'></i></a>";
			$i++;
		}
		$json_data = array(
			"draw" => intval($requestData['draw']),
			"recordsTotal" => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data" => $data
		);

		echo json_encode($json_data);
	}

	/*Unity report view*/
	public function admin_view($id = null)
	{
		$this->layout = false;
		if (!empty($id)) {
			$this->Pointdata->bindModel(array(
				'hasMany' => array(
					'VfPointdata' => array(
						'foreignKey' => 'point_data_id',
						'order' => 'VfPointdata.index ASC'
					)
				)
			));
			$data = $this->Pointdata->findById($id);
			//$office = $this->Office->findById($data['User']['office_id']);
			/* $conditions = "Testreport.test_id NOT IN";
			$datafile =  $this->File->find('all',array('conditions'=>array(
			"NOT" => array( "Testreport.test_id" => array(0,1) )
			))); */
			//$this->set(compact('datafile','datafile'));
			//pr($data);
			$od_pdf = "";
			$os_pdf = "";
			if (Hash::get($data, 'Pointdata.eye_select') == 1) {
				$od_pdf = Hash::get($data, 'Pointdata.file');
			} else {
				$os_pdf = Hash::get($data, 'Pointdata.file');
			}

			//$odpt
			if (Hash::get($data, 'Pointdata.eye_select') == 1) {
				if (empty($od_pdf)) {
					$od_pdf = Hash::get($data, 'Pointdata.file');
				}
			} else {
				if (empty($os_pdf)) {
					$os_pdf = Hash::get($data, 'Pointdata.file');
				}
			}

			$os_pdf_link = WWW_BASE . 'pointData/' . $os_pdf;
			$od_pdf_link = WWW_BASE . 'pointData/' . $od_pdf;


			$os_dicom_link = WWW_BASE . 'admin/unityreports/exportImage/' . $os_pdf;//
			$od_dicom_link = WWW_BASE . 'admin/unityreports/exportImage/' . $od_pdf;//

			$patient_id = Hash::get($data, 'Patient.id');
			$os_dicom_link = '/admin/unityreports/exportDicom/' . $patient_id . '/' . $os_pdf;
			$od_dicom_link = '/admin/unityreports/exportDicom/' . $patient_id . '/' . $od_pdf;


			$os_pointdata_id = Hash::get($data, 'Pointdata.id');


			if (!empty($os_pdf)) {
				$download[$os_pointdata_id]['pdf'][] = $os_pdf_link;
				$download[$os_pointdata_id]['dicom'][] = $os_dicom_link;
				$download[$os_pointdata_id]['filename'][] = $data['Patient']['first_name'] . '-' . $data['Patient']['last_name'] . '-' . $data['Pointdata']['test_name'] . '-OS-' . time();
			}
			if (!empty($od_pdf)) {
				$download[$os_pointdata_id]['pdf'][] = $od_pdf_link;
				$download[$os_pointdata_id]['dicom'][] = $od_dicom_link;
				$download[$os_pointdata_id]['filename'][] = $data['Patient']['first_name'] . '-' . $data['Patient']['last_name'] . '-' . $data['Pointdata']['test_name'] . '-OD-' . time();

			}


			$this->set(compact('data', 'data', 'download'));
		}
	}

	public function admin_export_search()
	{
		$this->layout = false;
		$this->autoRender = false;
		set_time_limit(0);
		ini_set('max_execution_time', 0);
		$Admin = $this->Auth->user();
		$conditions = array('Pointdata.is_delete' => '0');
		if ($Admin['user_type'] == 'Admin') {
			if (!empty($this->Session->read('Search.office'))) {
				$office_id['User']['office_id'] = $this->Session->read('Search.office');
				$staffuserAdmin = $this->User->find('list', array('conditions' => array('User.office_id' => $office_id['User']['office_id']), 'fields' => array('User.id')));
				$conditions[]['Pointdata.staff_id'] = $staffuserAdmin;
			}
		}
		if ($Admin['user_type'] == 'Admin') {
			$version = @$this->request->query['version'];
			$test_name = @$this->request->query['test_name'];
			$eye_select = @$this->request->query['eye_select'];
			$patient_name = (!empty(@$this->request->query['patient_name'])) ? explode(" ", @$this->request->query['patient_name']) : [];
			$include_patient_name = @$this->request->query['include_patient_name'];
			$patient_age = @$this->request->query['patient_age'];
			$race = @$this->request->query['race'];
			$fileName = date('Y-m-d');
			if (!empty($test_name)) {
				$conditions['AND'][] = array('Pointdata.test_name like' => '%' . trim($test_name) . '%');
				$fileName .= '_' . $test_name;
			}
			if ($eye_select != '') {
				$conditions['AND'][] = array('Pointdata.eye_select' => $eye_select);
				$fileName .= ($eye_select == 0) ? '_OS' : '_OD';
			}
			if (!empty($version)) {
				$conditions['AND'][] = array('Pointdata.version like' => '%' . trim($version) . '%');
				$fileName .= '_' . $version;
			}
			if (!empty($patient_name)) {
				foreach ($patient_name as $key => $value) {
					$conditions['AND'][] = array('Patient.first_name NOT like' => '%' . trim($value) . '%');
					$conditions['AND'][] = array('Patient.middle_name NOT like' => '%' . trim($value) . '%');

					/* $conditions['AND'][] = array("NOT"=>array("Patient.first_name" =>$patient_name,
															  "Patient.middle_name" =>$patient_name,
															  "Patient.last_name" =>$patient_name)); */
				}
				if (!empty($include_patient_name)) {
					$conditions['AND'][] = array('Pointdata.patient_name LIKE' => trim($include_patient_name) . '%');
					$fileName .= '_' . $include_patient_name;
				}


				/* if(!empty($staff_name))
					$conditions['AND'][] = array('Pointdata.staff_name like'=> trim($staff_name).'%'); */
			}
			if (!empty($race))
				$conditions['AND'][] = array('Patient.race like' => '%' . trim($race) . '%');
			if (!empty($patient_age)) {
				$ageArray = explode('-', $patient_age);
				$conditions['AND'][] = array('DATEDIFF(CURDATE(), STR_TO_DATE(Patient.dob, "%d-%m-%Y"))<=' . $ageArray[1] * 365);
				$conditions['AND'][] = array('DATEDIFF(CURDATE(), STR_TO_DATE(Patient.dob, "%d-%m-%Y"))>=' . $ageArray[0] * 365);
			}
			//$this->Pointdata->virtualFields['staff_name'] = "CONCAT(User.first_name,' ', User.last_name)";
			$this->Pointdata->virtualFields['patient_name'] = "CONCAT(Patient.first_name,' ', Patient.middle_name,' ',Patient.last_name)";
			$this->Pointdata->virtualFields['patient_dob'] = "Patient.dob";
			//$this->Pointdata->virtualFields['patient_age'] = "DATEDIFF(CURDATE(), STR_TO_DATE(Patient.dob, '%d-%m-%Y') )";
			$this->Pointdata->virtualFields['patient_age_years'] = "TIMESTAMPDIFF(YEAR,  STR_TO_DATE(Patient.dob, '%d-%m-%Y'), now())";
			$this->Pointdata->virtualFields['patient_age_months'] = "TIMESTAMPDIFF(MONTH,  STR_TO_DATE(Patient.dob, '%d-%m-%Y'), now())% 12";
			$this->Pointdata->virtualFields['patient_age_days'] = "FLOOR( TIMESTAMPDIFF(DAY, STR_TO_DATE(Patient.dob, '%d-%m-%Y'), now())% 30.4375 )";

			$this->Pointdata->unbindModel(array(
				'belongsTo' => array('User', 'Test')
			));
			$this->Pointdata->bindModel(array(
				'hasMany' => array(
					'VfPointdata' => array(
						'foreignKey' => 'point_data_id',
						'conditions' => ['size <' => 99],
						'order' => 'VfPointdata.index ASC'
					)
				)
			));
			//pr($conditions); die;
			$pointDatas = $this->Pointdata->find('all', array(
				'conditions' => $conditions,
				'fields' => array('Pointdata.id', 'Pointdata.test_name', 'Pointdata.patient_name', 'Patient.id', 'Pointdata.patient_dob', 'Pointdata.patient_age_years', 'Pointdata.patient_age_months', 'Pointdata.patient_age_days', 'Pointdata.eye_select'),
				'order' => array('Pointdata.patient_dob' => 'ASC')
			));
			//pr($pointDatas); die;
			//$header = array('index', 'X','Y' );
			$csv = '';
			if (!empty($pointDatas)) {
				foreach ($pointDatas as $key => $data) {
					if (!empty($data['VfPointdata'])) {
						$countVfPointdata = 0;
						foreach ($data['VfPointdata'] as $key1 => $vfData) {
							if ($vfData['index'] == 0) {
								$countVfPointdata++;
							}
							unset($pointDatas[$key]['VfPointdata'][$key1]);
							$pointDatas[$key]['VfPointdata'][$vfData['index']] = $vfData;
						}
						$pointDatas[$key]['Pointdata']['count'] = count($pointDatas[$key]['VfPointdata']);
						unset($vfData);
						//$zerosArrayCount = count( Hash::extract($data['VfPointdata'],"{n}[index=0]"));
						if ($countVfPointdata == count($data['VfPointdata'])) {
							unset($pointDatas[$key]);
						} else {

							//array_push($header, $data['Pointdata']['patient_name']);
						}
					} else {
						unset($pointDatas[$key]);
					}


				}
				$oldPointDatas = $pointDatas;
				$pointDatas = Hash::sort($pointDatas, '{n}.Pointdata.count', 'desc');
				$csv = fopen("php://output", 'w');
				//$file_name = uniqid().'_'.date('Ymd');
				$file_name = $fileName;
				header('Content-Type: application/csv; charset=utf-8');
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename=' . $file_name . '.csv');

				$pointDatas = array_values($pointDatas);
				//pr($pointDatas); die;
				$header1 = array('index', 'X', 'Y');
				//$header2 = Hash::extract($pointDatas, '{n}.Pointdata.patient_name');
				$header2 = [];
				$dobRow2 = [];
				$ageRow2 = [];
				foreach ($pointDatas as $key => $value) {
					$header2[] = $value['Pointdata']['patient_name'];
					$dobRow2[] = (!empty($value['Pointdata']['patient_dob'])) ? $value['Pointdata']['patient_dob'] : '';
					$ageRow2[] = (!empty($value['Pointdata']['patient_dob'])) ? $value['Pointdata']['patient_age_years'] : '';
					$eyeTool2[] = ($value['Pointdata']['eye_select'] == 1) ? 'OD' : (($value['Pointdata']['eye_select'] == 0) ? 'OS' : '');
				}
				//pr($dobRow2); die;
				$headers = array_merge($header1, $header2);
				fputcsv($csv, array_values($headers));
				$dobRow1 = array('DOB', '', '');
				$ageRow1 = array('Age in Years', '', '');
				$eyeTool1 = array('Eye Tool', '', '');
				//$dobRow2 = Hash::extract($pointDatas, '{n}.Pointdata.patient_dob');

				$dobRows = array_merge($dobRow1, $dobRow2);
				$ageRows = array_merge($ageRow1, $ageRow2);
				$eyeTool = array_merge($eyeTool1, $eyeTool2);
				fputcsv($csv, array_values($dobRows));
				fputcsv($csv, array_values($ageRows));
				fputcsv($csv, array_values($eyeTool));

				//pr($pointDatas); die;

				if (!empty($pointDatas)) {

					foreach ($pointDatas as $pointData) {
						//pr($pointData); die;
						foreach ($pointData['VfPointdata'] as $key => $vfData) {
							$record[] = $vfData['index'];
							$record[] = $vfData['x'];
							$record[] = $vfData['y'];
							$count = count($pointDatas);
							//pr($pointData[$i]['VfPointdata'][$key]['intensity']);die;
							for ($i = 0; $i < $count; $i++) {
								//pr(@$pointDatas[$i]['VfPointdata']); die;
								if ($key == $vfData['index']) {
									$record[] = @$pointDatas[$i]['VfPointdata'][$vfData['index']]['intensity'];
								} else {
									$record[] = '';
								}
							}
							//pr($record);die;
							fputcsv($csv, $record);
							$record = array();
							//usleep(250000);
						}
						break;
					}
				}
			}

			fclose($csv);
			//return $this->redirect($this->referer());
			exit();
		}
	}

	public function admin_reports_odos($id = null)
	{
		$this->autoRender = false;
		/*$this->response->type('json');
		$json = json_encode($response);
		$this->response->body($json);*/

		$ospt = $this->Pointdata->find('first', array(
				'conditions' => array(
					'Pointdata.id' => $id,
					'Pointdata.is_delete' => 0,
				),
				// 'contain'=>array('Pointdata', 'Patient'),
				'fields' => array(
					'Pointdata.id',
					'Pointdata.test_name',
					'Pointdata.eye_select',
					'Pointdata.created',
					'Pointdata.file',


					'Patient.id',
					'Patient.first_name',
					'Patient.middle_name',
					'Patient.last_name',
					'Patient.dob',
				)
			)
		);

		// $eye=(Hash::get($ospt, 'Patient.eye_select') == 1) ? 0 : 1;

		$odpt = $this->Pointdata->find('first', array(
				'conditions' => array(
					//'Pointdata.id !='=> Hash::get($ospt, 'Pointdata.id'),
					'Pointdata.is_delete' => 0,
					//'Patient.id' => Hash::get($pointdata_1, 'Pointdata.test_name') ,
					'Patient.first_name' => Hash::get($ospt, 'Patient.first_name'),
					'Patient.middle_name' => Hash::get($ospt, 'Patient.middle_name'),
					'Patient.last_name' => Hash::get($ospt, 'Patient.last_name'),
					'Patient.dob' => Hash::get($ospt, 'Patient.dob'),
					'Pointdata.eye_select !=' => Hash::get($ospt, 'Pointdata.eye_select'),
					'cast(Pointdata.created as date) =' => date('Y-m-d', strtotime(Hash::get($ospt, 'Pointdata.created'))),
				),
				'fields' => array(
					'Pointdata.id',
					'Pointdata.test_name',
					'Pointdata.eye_select',
					'Pointdata.file',
					'Pointdata.created',

					'Patient.id',
					'Patient.first_name',
					'Patient.middle_name',
					'Patient.last_name',
					'Patient.dob',
				),
				'order' => 'Pointdata.id DESC',
			)
		);

		unset($ospt['VfPointdata']);
		unset($odpt['VfPointdata']);
		//pr($ospt);
		//pr($odpt);
		// table-hover

		Hash::get($ospt, 'Pointdata.file');
		//$ospt
		$od_pdf = "";
		$os_pdf = "";
		if (Hash::get($ospt, 'Pointdata.eye_select') == 1) {
			$od_pdf = Hash::get($ospt, 'Pointdata.file');
		} else {
			$os_pdf = Hash::get($ospt, 'Pointdata.file');
		}

		//$odpt
		if (Hash::get($odpt, 'Pointdata.eye_select') == 1) {
			if (empty($od_pdf)) {
				$od_pdf = Hash::get($odpt, 'Pointdata.file');
			}
		} else {
			if (empty($os_pdf)) {
				$os_pdf = Hash::get($odpt, 'Pointdata.file');
			}
		}


		$os_pdf_link = WWW_BASE . 'pointData/' . $os_pdf;
		$od_pdf_link = WWW_BASE . 'pointData/' . $od_pdf;


		$os_dicom_link = WWW_BASE . 'admin/unityreports/exportImage/' . $os_pdf;// $this->Html->url(['controller'=>'unityreports','action'=>'exportImage',$os_pdf]);
		$od_dicom_link = WWW_BASE . 'admin/unityreports/exportImage/' . $od_pdf;//$this->Html->url(['controller'=>'unityreports','action'=>'exportImage',$od_pdf]);


		$download = array();

		if (!empty($os_pdf)) {
			$download['pdf'][] = $os_pdf_link;
			$download['dicom'][] = $os_dicom_link;
		}
		if (!empty($od_pdf)) {
			$download['pdf'][] = $od_pdf_link;
			$download['dicom'][] = $od_dicom_link;
		}


		$response = array(
			'status' => 'success',
			'data' => array(
				'pdf' => array(
					'os' => $os_pdf_link,
					'od' => $od_pdf_link
				),
				'dicom' => array(
					'os' => $os_dicom_link,
					'od' => $od_dicom_link
				),
			),
			'download' => $download
		);
		$this->response->type('json');
		$json = json_encode($response);
		$this->response->body($json);


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

		$header = array('x', 'y', 'Intensity', 'size', 'index'/* ,'FixationX','FixationY' */);
		$csv = fopen("php://output", 'w');
		header('Content-Type: application/csv; charset=utf-8');
		header('Content-type: application/ms-excel');
		header('Content-Disposition: attachment; filename=' . $file_name . '.csv');
		fputcsv($csv, array_values($header));

		if (!empty($id)) {
			$this->Pointdata->bindModel(array(
				'hasMany' => array(
					'VfPointdata' => array(
						'foreignKey' => 'point_data_id',
						'order' => 'VfPointdata.index ASC'
					)
				)
			));
			$data = $this->Pointdata->findById($id);
			//pr($data); die;
			foreach ($data['VfPointdata'] as $pdata) {
				$record[] = $pdata['x'];
				$record[] = $pdata['y'];
				$record[] = $pdata['intensity'];
				$record[] = $pdata['size'];
				$record[] = $pdata['index'];
				// $record[] = $pdata['fixationX'];
				// $record[] = $pdata['fixationY'];
				fputcsv($csv, $record);
				$record = array();
			}
			fclose($csv);
			exit();
		}
	}


	public function admin_exportPdf($pdf = null)
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

	public function admin_exportDicomda($patientId = null, $pdf = null)
	{
		$dicomname = substr($pdf, 0, strrpos($pdf, '.'));
		$pdf_file = '../../../../inetpub/wwwroot/portalmi2/app/webroot/uploads/darkadaption/' . $pdf;
		$fileName = $save_to = '../../../../inetpub/wwwroot/portalmi2/app/webroot/pointDataDCMDA/' . $dicomname . '.DCM';


		$patientDetails = $this->Patient->find('first', array(
			'conditions' => array('Patient.id' => $patientId)
		));
		$dicomOptions = $patientDetails['Patient'];
		$patientName = $dicomOptions['last_name'] . "^" . $dicomOptions['first_name'];

		$basename = basename($fileName);
		if (!empty($_GET['filename'])) {
			$basename = $_GET['filename'] . '-' . $basename;
		}

		$id = (!empty($dicomOptions['id_number'])) ? $dicomOptions['id_number'] : '0';
		$output = shell_exec('pdf2dcm +t Morrison +cn "DICOM" "OF" "' . $pdf . '" +pn "' . $patientName . '" +pi ' . $id . ' +pb ' . date('Ymd', strtotime($dicomOptions['dob'])) . ' +ps M ' . $pdf_file . ' ' . $fileName);
		//echo $fileName;
		if (file_exists($fileName)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header("Content-Transfer-Encoding: Binary");
			header('Content-Disposition: attachment; filename="' . $basename . '"');
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

	public function admin_exportDicomstb($patientId = null, $pdf = null)
	{
		$dicomname = substr($pdf, 0, strrpos($pdf, '.'));
		$pdf_file = '../../../../inetpub/wwwroot/portalmi2/app/webroot/uploads/stb/' . $pdf;
		$fileName = $save_to = '../../../../inetpub/wwwroot/portalmi2/app/webroot/stbDataImage/' . $dicomname . '.DCM';


		$patientDetails = $this->Patient->find('first', array(
			'conditions' => array('Patient.id' => $patientId)
		));
		$dicomOptions = $patientDetails['Patient'];
		$patientName = $dicomOptions['last_name'] . "^" . $dicomOptions['first_name'];

		$basename = basename($fileName);
		if (!empty($_GET['filename'])) {
			$basename = $_GET['filename'] . '-' . $basename;
		}

		$id = (!empty($dicomOptions['id_number'])) ? $dicomOptions['id_number'] : '0';
		$output = shell_exec('pdf2dcm +t Morrison +cn "DICOM" "OF" "' . $pdf . '" +pn "' . $patientName . '" +pi ' . $id . ' +pb ' . date('Ymd', strtotime($dicomOptions['dob'])) . ' +ps M ' . $pdf_file . ' ' . $fileName);
		//	echo $fileName;die;
		if (file_exists($fileName)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header("Content-Transfer-Encoding: Binary");
			header('Content-Disposition: attachment; filename="' . $basename . '"');
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

	public function admin_exportDicom($patientId = null, $pdf = null)
	{
		$dicomname = substr($pdf, 0, strrpos($pdf, '.'));
		$pdf_file = '../../../../inetpub/wwwroot/portalmi2/app/webroot/pointData/' . $pdf;
		$fileName = $save_to = '../../../../inetpub/wwwroot/portalmi2/app/webroot/pointDataDCM/' . $dicomname . '.DCM';


		$patientDetails = $this->Patient->find('first', array(
			'conditions' => array('Patient.id' => $patientId)
		));
		$dicomOptions = $patientDetails['Patient'];
		$patientName = $dicomOptions['last_name'] . "^" . $dicomOptions['first_name'];

		$basename = basename($fileName);
		if (!empty($_GET['filename'])) {
			// pr(array($patientId, $pdf, basename($fileName), $patientDetails));
			// die;
			$basename = $_GET['filename'] . '-' . $basename;
		}


		//pr($dicomOptions['first_name']); die;
		//???echo 'pdf2dcm +t Morrison +cn "99DCMTK" "PDF" "Some PDF Document" +pn "'.$patientName.'" +pi bt40 +pb 19900202 +ps M '.$pdf_file.' '.$fileName;   //die;

		//$output = shell_exec('pdf2dcm +t Amithab +cn CSD +pn "Kumar^Amithab" +pi 6349 +pb 19701105 +ps M '." $pdf_file $fileName");

		//$output = shell_exec("pdf2dcm +t ".$dicomOptions['first_name']." +cn '99DCMTK' 'PDF' 'Some PDF Document' +pn ".'dddd^test'." +pi ".$dicomOptions['id']." +pb ".date('Ymd',strtotime($dicomOptions['dob']))." +ps M $pdf_file $fileName");
		$id = (!empty($dicomOptions['id_number'])) ? $dicomOptions['id_number'] : '0';
		$output = shell_exec('pdf2dcm +t Morrison +cn "DICOM" "OF" "' . $pdf . '" +pn "' . $patientName . '" +pi ' . $id . ' +pb ' . date('Ymd', strtotime($dicomOptions['dob'])) . ' +ps M ' . $pdf_file . ' ' . $fileName);
		//echo $fileName;
		if (file_exists($fileName)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header("Content-Transfer-Encoding: Binary");
			header('Content-Disposition: attachment; filename="' . $basename . '"');
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

	public function admin_exportImage($pdf = null)
	{
		$jpgname = substr($pdf, 0, strrpos($pdf, '.'));
		$pdf_file = '../../../../inetpub/wwwroot/portalmi2/app/webroot/pointData/' . $pdf;
		//$pdf_file='https://www.portal.micromedinc.com/pointData/1601082612_17455.pdf';
		$fileName = $save_to = '../../../../inetpub/wwwroot/portalmi2/app/webroot/pointDataImage/' . $jpgname . '.jpg';
		/* die($fileName);
		magick convert rose.jpg rose.png */
		//exec("magick convert $pdf_file $fileName");

		//echo file_exists($pdf_file);
		$img = new Imagick($pdf_file);
		//die($img);
		$img->setResolution(500, 500);
		//set new format
		$img->readImage($pdf_file);;
		//save image file
		$img->writeImage($save_to);
		//pr($img); die;

		//echo file_exists($fileName).':'.$fileName; die;
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
		return $this->redirect(WWW_BASE.'pointDataImage/' . $jpgname . '.jpg');
		//echo $save_to; die;
	}

	public function admin_exportImageStb($pdf = null)
	{
		$jpgname = substr($pdf, 0, strrpos($pdf, '.'));
		/*$pdf_file   = '../webroot/uploads/stbData/'.$pdf;

		$save_to    = '../webroot/uploads/stbDataImage/'.$jpgname.'.jpg';*/

		//$pdf_file   = '../../../../inetpub/wwwroot/portalmi2/app/webroot/uploads/stbdata/'.$pdf;
		//$pdf_file   = WWW_BASE. 'uploads/stbdata/'.$pdf;
		$pdf_file = getcwd() . '/app/webroot/uploads/stbdata/' . $pdf;
		//$fileName = $save_to    = '../../../../inetpub/wwwroot/portalmi2/app/webroot/stbDataImage/'.$jpgname.'.jpg';
		//	$fileName = $save_to    =  WWW_BASE. 'stbDataImage/'.$jpgname.'.jpg';
		$fileName = $save_to = getcwd() . '/app/webroot/stbDataImage/' . $jpgname . '.jpg';

		// echo $pdf_file;die();
		$img = new Imagick($pdf_file);
		// die();
		$img->setResolution(500, 500);
		//set new format

		$img->readImage($pdf_file);;
		// die();
		//save image file
		$img->writeImage($save_to);
		//pr($img); die;
		return $this->redirect(WWW_BASE.'stbDataImage/' . $jpgname . '.jpg');
		//echo $save_to; die;
	}

	public function admin_exportImageda($pdf = null)
	{
		$jpgname = substr($pdf, 0, strrpos($pdf, '.'));
		$pdf_file = '../../../../inetpub/wwwroot/portalmi2/app/webroot/uploads/darkadaption/' . $pdf;

		$fileName = $save_to = '../../../../inetpub/wwwroot/portalmi2/app/webroot/pointDataImageda/' . $jpgname . '.jpg';
		/* die($fileName);
		magick convert rose.jpg rose.png */
		//exec("magick convert $pdf_file $fileName");

		//echo file_exists($pdf_file);
		$img = new Imagick($pdf_file);

		$img->setResolution(500, 500);
		//set new format
		$img->readImage($pdf_file);;
		//save image file
		$img->writeImage($save_to);
		//pr($img); die;

		//echo file_exists($fileName).':'.$fileName; die;
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
		return $this->redirect(WWW_BASE.'pointDataImageda/' . $jpgname . '.jpg');
		//echo $save_to; die;
	}

	public function admin_delete($id = NULL)
	{
		$this->loadModel('Pointdata');
		$Admin = $this->Auth->user();
		//pr($Admin);die;
		if ($Admin['user_type'] == 'Admin' || $Admin['user_type'] == 'Subadmin') {
			$delete_record = $this->Pointdata->updateAll(
				array('Pointdata.is_delete' => '1'),
				array('Pointdata.id' => $id)
			);
			if ($delete_record) {
				$this->Session->setFlash("Point data Record deleted successfully.", 'message', array('class' => 'message'));
			} else {
				$this->Session->setFlash("Unable to delete.", 'message', array('class' => 'message'));
			}
			$this->redirect(array('controller' => 'unityreports', 'action' => 'unity_reports_list'));
		} else {
			echo 'can not access.';
			die;
		}
	}



	public function admin_creat_view(){
		$datas=$this->Office->find('all');
		foreach($datas as $key => $value){ 
			
			//$currentOrderAlert  		=  $this->Office->query("DROP VIEW mmd_pointdatas_".$value['Office']['id']);
			

			$currentOrderAlert  		=  $this->Office->query("CREATE OR REPLACE VIEW mmd_pointdatas_".$value['Office']['id']." AS  SELECT  `id`, `test_type_id`, `test_name`, `numpoints`, `color`, `backgroundcolor`, `stmsize`, `file`, `staff_id`, `patient_id`, `eye_select`, `baseline`, `is_delete`, `master_key`, `test_color_fg`, `test_color_bg`, `mean_dev`, `pattern_std`, `mean_sen`, `mean_def`, `pattern_std_hfa`, `loss_var`, `mean_std`, `psd_hfa_2`, `psd_hfa`, `vission_loss`, `false_p`, `false_n`, `false_f`, `threshold`, `strategy`, `ght`, `latitude`, `longitude`, `unique_id`, `version`, `diagnosys`, `age_group`, `device_id`, `office_id`, `source`, `stereopsis`, `created`  FROM   `mmd_pointdatas` WHERE mmd_pointdatas.staff_id in (SELECT id from mmd_users where office_id=".$value['Office']['id'].")");
		} 
		//pr($datas);
		die();
	}
}

?>
