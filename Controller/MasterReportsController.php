<?php
App::uses('AppController','Controller');
App::uses('CakeEmail','Network/Email');

class MasterReportsController extends AppController{
	public $uses 		= array('Admin','User','Testreport','Office','File','Masterdata','TestDevice'); 
	var $helpers 		= array('Html', 'Form','Js' => array('Jquery'), 'Custom'); 
    public $components 	= array('Auth'=>array('authorize'=>array('Controller')),'Session','Email','Common','RememberMe');
	public $allowedActions =array('admin_testdata');
    
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow($this->allowedActions);	
	}
	
	/*Test Report List*/
	public function admin_master_reports_list(){
		if($this->Session->read('Auth.Admin.user_type')=='Admin'){
		$Admin = $this->Auth->user();
		$conditions=array('Masterdata.is_delete' => '0');
		if($Admin['user_type'] == 'Admin') {
			if(!empty($this->Session->read('Search.office'))){
				$office_id['User']['office_id'] =  $this->Session->read('Search.office');
				$staffuserAdmin=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id']),'fields'=>array('User.id')));
				$conditions[]['Masterdata.staff_id']=$staffuserAdmin;
			} 
		} elseif($Admin['user_type'] == 'Subadmin') {
			//$staffuser = $this->User->find('list',array('conditions'=>array('User.created_by'=>$Admin['id'])));
			$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$Admin['id'],'User.user_type'=>'Subadmin'),'fields'=>array('User.office_id')));
			if(!empty($this->Session->read('Search.office'))){
				$office_id['User']['office_id'] =  $this->Session->read('Search.office');
			}
			$staffuser=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id']),'fields'=>array('User.id')));
			
			// Calculate Total Records..
			$conditions[]['Masterdata.staff_id']=$staffuser;
			 
		} else {
			$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$Admin['id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.office_id')));
			if(!empty($this->Session->read('Search.office'))){
				$office_id['User']['office_id'] =  $this->Session->read('Search.office');
			}
			$all_staff_ids=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.id')));
			$conditions[]['Masterdata.staff_id']=$all_staff_ids;
		}
		
		if(!empty($this->request->query['search'])){
			//echo "yes";die;
			$search = trim($this->request->query['search']);
			$conditions['OR'][] = array('Masterdata.staff_name like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Masterdata.patient_name like'=> '%'.$search.'%');
			//$conditions['OR'][] = array('Masterdata.created'=> $search);
			$conditions['OR'][] = array('Masterdata.id'=> $search);
			$this->set(compact('search'));
		}
		
	 
		//creating virtual field for full name
		$this->Masterdata->virtualFields['staff_name'] = "CONCAT(User.first_name,' ', User.last_name)";
		$this->Masterdata->virtualFields['patient_name'] = "CONCAT(Patient.first_name,' ', Patient.last_name)";
		$params = array(
			'conditions' => $conditions,
			'limit'=>10,
			'order'=>array('Masterdata.created'=>'DESC')
			);
		
		$this->paginate=array('Masterdata'=>$params);
		$datas = $this->paginate('Masterdata');
		$this->set(compact('datas'));
		//pr($datas);die; 
		}else{
            $this->redirect('https://www.portal.micromedinc.com/admin/dashboards/index');
        }
	} 
	
		
	public function admin_ajaxUnityReportList(){
		$this->layout = false;
		$this->autoRender = false;
		$requestData = $this->request->data;
		$columns = array( 
			// datatable column index  => database column name
			0  => 'Masterdata.id', 
			1  => 'Masterdata.created', 
			2  => 'Masterdata.staff_name',
			3  => 'Masterdata.patient_name',
			4  => 'Masterdata.report_view'
		);
		
		
		$Admin = $this->Auth->user();
	
		if($Admin['user_type'] == 'Admin') {
			// Calculate Total Records..
			$totalData = $this->Masterdata->find('count');
			$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
			$conditions=array();
			
		} elseif($Admin['user_type'] == 'Subadmin') {
			//$staffuser = $this->User->find('list',array('conditions'=>array('User.created_by'=>$Admin['id'])));
			$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$Admin['id'],'User.user_type'=>'Subadmin'),'fields'=>array('User.office_id')));
			$staffuser=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id']),'fields'=>array('User.id')));
			
			// Calculate Total Records..
			$conditions['Masterdata.staff_id']=$staffuser;
			$totalData = $this->Masterdata->find('count',array('conditions'=>$conditions));
			$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		} else {
			$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$Admin['id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.office_id')));
			$all_staff_ids=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.id')));
			// Calculate Total Records..
			$conditions['Masterdata.staff_id']=$all_staff_ids;
			$totalData = $this->Masterdata->find('count',array('conditions'=>$conditions));
			$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		}
		//creating virtual field for full name
		$this->Masterdata->virtualFields['staff_name'] = "CONCAT(User.first_name,' ', User.last_name)";
		$this->Masterdata->virtualFields['patient_name'] = "CONCAT(Patient.first_name,' ', Patient.last_name)";
		
		
		//print_r($requestData);die;
		// if there is a search parameter, $requestData['search']['value'] contains search parameter
		if(!empty($requestData['search']['value'])){
			$conditions['AND']=array(
				'OR'=>array(
					"Masterdata.staff_name LIKE "=>$requestData['search']['value']."%",
					"Masterdata.patient_name LIKE "=>$requestData['search']['value']."%",
					"Masterdata.created"=>date('Y-m-d',strtotime($requestData['search']['value']))
				)
			);
			$totalFiltered = $this->Masterdata->find('count',array('conditions'=>$conditions));
		}
		
		
		$datas = $this->Masterdata->find(
			'all',array(
				'conditions'=>$conditions,
				'order' => array($columns[$requestData['order'][0]['column']]." ".strtoupper($requestData['order'][0]['dir'])),
				'limit' =>$requestData['length'],
				'offset' =>$requestData['start'],
			)
		);
		//print_r($datas);die('hii');
		$data = array();
		$i=0;
		
		if(($requestData['order'][0]['column']==0) &&($requestData['order'][0]['dir']=='asc')){
			$page=$totalFiltered;
		}else{
			$page=$requestData['start'];
		}
		foreach($datas as $key=>$staff){
			if(($requestData['order'][0]['column']==0) &&($requestData['order'][0]['dir']=='asc')){
				$data[$i]['id'] = $page-$key-$requestData['start']; 
			}else{
				$data[$i]['id'] = $page+$key+1;
			}
			$data[$i]['created'] = date('d M Y',strtotime($staff['Masterdata']['created']));

			$data[$i]['staff_name'] = "<a style='cursor: pointer;'  title='View' staffId='".$staff['Masterdata']['staff_id']."'class='staff' data-toggle='modal' data-target='#staffView'>". $staff['Masterdata']['staff_name']."</a>";
			$data[$i]['patient_name'] = "<a style='cursor: pointer;'  title='View' patientId='".$staff['Masterdata']['patient_id']."'class='patient' data-toggle='modal' data-target='#patientView'>".$staff['Masterdata']['patient_name']."</a>";
			$data[$i]['report_view'] = "<a style='cursor: pointer;'  title='View' testreportId='".$staff['Masterdata']['id']."'class='testreport' data-toggle='modal' data-target='#reportView'><i class='fa fa-eye' aria-hidden='true'></i></a>";
			$i++;
		}
		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),
			"recordsTotal"    => intval( $totalData ),
			"recordsFiltered" => intval( $totalFiltered ),
			"data"            => $data
		);
		
		echo json_encode($json_data);
	}
		public function admin_testdata3($id=null){
		$this->layout=false;
		if(!empty($id)){
		    $this->Masterdata->bindModel(array(
				'hasMany'=>array(
					'VfMasterdata'=>array(
						'foreignKey'=>'master_data_id',
						'order'=>'VfMasterdata.index ASC',
						'fields' => array('x','y','intensity','size','STD','index'),
					)
				)
			)); 
			$device=$this->TestDevice->find('first', array('conditions' => array('TestDevice.id' =>$_GET['deviceId'])));
    		$type="";
			 if($device['TestDevice']['device_type']==1){
			  $type="_Go"; 
			 }else if($device['TestDevice']['device_type']==2 || $device['TestDevice']['device_type']==4 || $device['TestDevice']['device_type']==5 || $device['TestDevice']['device_type']==6 || $device['TestDevice']['device_type']==8 ){
			  $type="_PICO";   
			 }else if($device['TestDevice']['device_type']==3){
			  $type="_Quest";   
			 }
			  $this->Masterdata->unbindModel(
    array('belongsTo' => array('User','Patient','Test'))
);  
			   if($_GET['testTypeName']=='Vision Screening'){
			     		$data=$this->Masterdata->find('first',array('conditions'=>array('Masterdata.age_group'=>$_GET['ageGroup'],'Masterdata.test_name'=>'Vision Screening'.$type)));
			$data['MasterRecordList']=$this->Masterdata->find('all',array('order' => 'Masterdata.age_group ASC','conditions'=>array('Masterdata.test_name'=>'Vision Screening'.$type)));	
			   }else{
			   	$data=$this->Masterdata->find('first',array('conditions'=>array('Masterdata.age_group'=>$_GET['ageGroup'],'Masterdata.test_name'=>$_GET['testTypeName'].$type)));
			$data['MasterRecordList']=$this->Masterdata->find('all',array('order' => 'Masterdata.age_group ASC','conditions'=>array('Masterdata.test_name'=>'Vision Screening'.$type)));
			   }
			  /* if($_GET['testType']==2 && $_GET['eye'] == 0){
					foreach($data['MasterRecordList'] as $key => $value){
						foreach($value['VfMasterdata'] as $key2 => $value2){
							$data['MasterRecordList'][$key]['VfMasterdata'][$key2]['x'] = $value2['x']*-1; 
						}
					}
   				}*/
	
			
			echo json_encode($data); 
			exit(); 
		}	
	}
		public function admin_testdata2($id=null){
		$this->layout=false;
		if(!empty($id)){
		    $this->Masterdata->bindModel(array(
				'hasMany'=>array(
					'VfMasterdata'=>array(
						'foreignKey'=>'master_data_id',
						'order'=>'VfMasterdata.index ASC',
						'fields' => array('x','y','intensity','size','STD','index'),
					)
				)
			)); 
			$device=$this->TestDevice->find('first', array('conditions' => array('TestDevice.id' =>$_GET['deviceId'])));
    		$type="";
			 if($device['TestDevice']['device_type']==1){
			  $type="_Go"; 
			 }else if($device['TestDevice']['device_type']==2 || $device['TestDevice']['device_type']==4 || $device['TestDevice']['device_type']==5 || $device['TestDevice']['device_type']==6 || $device['TestDevice']['device_type']==6 ){
			  $type="_PICO";   
			 }else if($device['TestDevice']['device_type']==3){
			  $type="_Quest";   
			 }
			  $this->Masterdata->unbindModel(
    array('belongsTo' => array('User','Patient','Test'))
); 
			$data['MasterRecordList']=$this->Masterdata->find('all',array('order' => 'Masterdata.age_group ASC','conditions'=>array('Masterdata.test_name'=>$_GET['testTypeName'].''.$type))); 
				/*if($_GET['testType']==2 && $_GET['eye'] == 0){
					foreach($data['MasterRecordList'] as $key => $value){
						foreach($value['VfMasterdata'] as $key2 => $value2){
							$data['MasterRecordList'][$key]['VfMasterdata'][$key2]['x'] = $value2['x']*-1;
						}
					}
   				}*/
			     $this->loadModel('Pointdata'); 
              $this->Pointdata->unbindModel(
    array('belongsTo' => array('User','Patient','Test'),'hasMany' => array('VfPointdata'))
);
//$data=array();
$data2=array();
if($_GET['testType']==2)
   {
      $data['previousTest']= $this->Pointdata->find('all', array(
            'order' => 'Pointdata.created ASC','conditions' => array('Pointdata.patient_id' => $_GET['patient_id'],'Pointdata.eye_select' =>  $_GET['eye'],'Pointdata.threshold IN' =>['Threshold','2'],'Pointdata.baseline'=>1), 'fields' => array('test_name')));     
   }else{
   	$data['previousTest']=[];
   } 	
   foreach($data['previousTest'] as $key =>$value){
       if(isset($data2[$value['Pointdata']['test_name']])){
           $data2[$value['Pointdata']['test_name']]= $data2[$value['Pointdata']['test_name']]+1;
       }else{
           $data2[$value['Pointdata']['test_name']]=1;
       } 
   }
   $data['previousTest']=$data2;

			echo json_encode($data); 
			exit(); 
		}	
	}
	public function admin_testdata($id=null){
		$this->layout=false;
		if(!empty($id)){
			$this->Masterdata->bindModel(array(
				'hasMany'=>array(
					'VfMasterdata'=>array(
						'foreignKey'=>'master_data_id',
						'order'=>'VfMasterdata.index ASC',
						'fields' => array('x','y','intensity','size','STD','index'),
					)
				)
			)); 
			$device=$this->TestDevice->find('first', array('conditions' => array('TestDevice.id' =>$_GET['deviceId'])));
    		$type="";  /// set defalt for device type 0 ans 4 (Geare master record )
			 if($device['TestDevice']['device_type']==1){
			  $type="_Go"; 
			 }else if($device['TestDevice']['device_type']==2  || $device['TestDevice']['device_type']==4 || $device['TestDevice']['device_type']==5  || $device['TestDevice']['device_type']==6 || $device['TestDevice']['device_type']==8){
			  $type="_PICO";   
			 }else if($device['TestDevice']['device_type']==3){
			  $type="_Quest";   
			 }

			  if($_GET['testTypeName']=='Vision Screening'){

			  	$data=$this->Masterdata->find('first',array('conditions'=>array('Masterdata.age_group'=>$_GET['ageGroup'],'Masterdata.test_name'=>$_GET['testTypeName'])));
			     $this->loadModel('Pointdata');
			  }else{
			  	$data=$this->Masterdata->find('first',array('conditions'=>array('Masterdata.age_group'=>$_GET['ageGroup'],'Masterdata.test_name'=>$_GET['testTypeName'].''.$type)));
			     $this->loadModel('Pointdata');
			  }
			  /*if($_GET['testType']==2 && $_GET['eye'] == 0){
						foreach($data['VfMasterdata'] as $key2 => $value2){
							$data['VfMasterdata'][$key2]['x'] = $value2['x']*-1;
						}
   				}*/
			
            
              $this->Pointdata->unbindModel(
    array('belongsTo' => array('User','Patient','Test'))
);

if($_GET['testType']==2)
   {
      $data['previousTest']= $this->Pointdata->find('all', array(
            'order' => 'Pointdata.created ASC','conditions' => array('Pointdata.patient_id' => $_GET['patient_id'],'Pointdata.test_name' => $_GET['testTypeName'],'Pointdata.eye_select' =>  $_GET['eye'],'Pointdata.baseline'=>1)));     
   } 
			echo json_encode($data);
			exit();
			 
		}	
		echo 0;
		exit();
	}
	
		/*Unity report view*/
	public function admin_view($id=null){
		$this->layout=false;
		if(!empty($id)){
			$this->Masterdata->bindModel(array(
				'hasMany'=>array(
					'VfMasterdata'=>array(
						'foreignKey'=>'master_data_id',
						'order'=>'VfMasterdata.index ASC'
					)
				)
			));
			$data = $this->Masterdata->findById($id);
			//$office = $this->Office->findById($data['User']['office_id']);
			/* $conditions = "Testreport.test_id NOT IN";  
			$datafile =  $this->File->find('all',array('conditions'=>array(
			"NOT" => array( "Testreport.test_id" => array(0,1) )
			))); */
			//$this->set(compact('datafile','datafile'));
			//pr($data);
			$this->set(compact('data','data'));
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
		 
		$header = array('x','y','Intensity','size','STD','Index'/* ,'FixationX','FixationY' */);
		$csv = fopen("php://output", 'w');
		header('Content-Type: application/csv; charset=utf-8');
		header('Content-type: application/ms-excel');
		header('Content-Disposition: attachment; filename='.$file_name.'.csv');
		fputcsv($csv, array_values($header));
		 
		if(!empty($id)){
			$this->Masterdata->bindModel(array(
				'hasMany'=>array(
					'VfMasterdata'=>array(
						'foreignKey'=>'master_data_id',
						'order'=>'VfMasterdata.index ASC'
					)
				)
			));
			$data = $this->Masterdata->findById($id);
			foreach($data['VfMasterdata'] as $pdata){
				$record[] = $pdata['x'];
				$record[] = $pdata['y'];
				$record[] = $pdata['intensity'];
				$record[] = $pdata['STD'];
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
	
	public function admin_delete($id=NULL){
		$this->loadModel('Masterdata');
		$Admin = $this->Auth->user();
		//pr($Admin);die;
		if($Admin['user_type'] == 'Admin') {
			$delete_record=$this->Masterdata->updateAll(
				array('Masterdata.is_delete' => '1'),
				array('Masterdata.id' => $id)
			);
			if($delete_record){
				$this->Session->setFlash("Master Record deleted successfully.",'message',array('class' => 'message'));
			}else{
				$this->Session->setFlash("Unable to delete.",'message',array('class' => 'message'));
			}
			$this->redirect(array('controller' => 'masterReports', 'action' => 'master_reports_list'));
		}else{
			echo 'can not access.';die;
		}
	}
	public function admin_sendNotification(){
		  $this->notify_master_app_constant();
		  $this->Session->setFlash("Notification Send successfully.",'message',array('class' => 'message'));
		 $this->redirect(array('controller' => 'masterReports', 'action' => 'master_reports_list'));
	}
}

?>