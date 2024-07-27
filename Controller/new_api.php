

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
		

