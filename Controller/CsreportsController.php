<?php
App::uses('AppController','Controller');
App::uses('CakeEmail','Network/Email');

class CsreportsController extends AppController {
	public $uses = array('Admin','User','Testreport','Office','File','CsData');
			
	var $helpers = array('Html', 'Form','Js' => array('Jquery'), 'Custom');

    public $components = array('Auth'=>array('authorize'=>array('Controller')),'Session','Email','Common','RememberMe');
	public $allowedActions =array();
    
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow($this->allowedActions);	
	}
	
	
	/*Test Report List*/
	public function admin_cs_reports_list(){
		$Admin = $this->Auth->user();
		$conditions=array('CsData.is_delete' => '0');
		if($Admin['user_type'] == 'Admin') {
			if(!empty($this->Session->read('Search.office'))){
				$office_id['User']['office_id'] =  $this->Session->read('Search.office');
				$staffuserAdmin=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id']),'fields'=>array('User.id')));
				$conditions[]['CsData.staff_id']=$staffuserAdmin;
			}
			
			
		} elseif($Admin['user_type'] == 'Subadmin') {
			//$staffuser = $this->User->find('list',array('conditions'=>array('User.created_by'=>$Admin['id'])));
			$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$Admin['id'],'User.user_type'=>'Subadmin'),'fields'=>array('User.office_id')));
			if(!empty($this->Session->read('Search.office'))){
				$office_id['User']['office_id'] =  $this->Session->read('Search.office');
			}
			$staffuser=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id']),'fields'=>array('User.id')));
			
			// Calculate Total Records..
			$conditions[]['CsData.staff_id']=$staffuser;
			 
		} else {
			$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$Admin['id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.office_id')));
			if(!empty($this->Session->read('Search.office'))){
				$office_id['User']['office_id'] =  $this->Session->read('Search.office');
			}
			$all_staff_ids=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.id')));
			$conditions[]['CsData.staff_id']=$all_staff_ids;
		}
		
		if(!empty($this->request->query['search'])){
			//echo "yes";die;
			$search = trim($this->request->query['search']);
			$conditions['OR'][] = array('CsData.staff_name like'=> '%'.$search.'%');
			$conditions['OR'][] = array('CsData.patient_name like'=> '%'.$search.'%');
			$conditions['OR'][] = array('CsData.created like'=> '%'.$search.'%');
			$conditions['OR'][] = array('CsData.id'=> $search);
			$this->set(compact('search'));
		}
		
	 
		//creating virtual field for full name
		//$this->CsData->virtualFields['staff_name'] = "CONCAT(User.first_name,' ', User.last_name)";
		//$this->CsData->virtualFields['patient_name'] = "CONCAT(Patient.first_name,' ', Patient.last_name)";
		$params = array(
			'conditions' => $conditions,
			'limit'=>10,
			'order'=>array('CsData.created'=>'DESC')
			);
		
		$this->paginate=array('CsData'=>$params);
		$datas = $this->paginate('CsData');
		
		if($Admin['user_type'] == 'Subadmin' || $Admin['user_type'] == 'Staffuser') {
			if($Admin['user_type'] == 'Staffuser'){
				/* $user_s = $this->User->find('first',array(
					'conditions'=>array('User.id'=>$Admin['created_by'])
				)); */
				$user_s = $this->User->find('first',array(
					'conditions'=>array('User.office_id'=>$Admin['office_id'], 'User.user_type'=>'Subadmin')
				));
			}
			$check_payable = '';
			$this->loadModel('Office');
			$check_payable=$this->Office->find('first',array(
				'conditions'=>array('Office.id'=>$Admin['Office']['id'])
			));
			//pr($check_payable);die;
			if($check_payable['Office']['payable']=='yes' && $check_payable['Office']['credits'] <= 0){
				$credit_expire = 'Credit expire';
				$this->set(compact('datas','credit_expire','check_payable'));
			}
		}
		
		$check_payable = '';
		$this->loadModel('Office');
		$check_payable=$this->Office->find('first',array(
			'conditions'=>array('Office.id'=>$Admin['Office']['id'])
		));
		$this->set(compact('datas','check_payable'));
		
	}

	public function admin_exportPdf($pdf=null){
		$fileName = $pdf_file   = '../../../../inetpub/wwwroot/portalmi2/app/webroot/pointData/'.$pdf;
		if (file_exists($fileName)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header("Content-Transfer-Encoding: Binary");
			header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
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
	
		
	public function admin_ajaxUnityReportList(){
		$this->layout = false;
		$this->autoRender = false;
		$requestData = $this->request->data;
		$columns = array( 
			// datatable column index  => database column name
			0  => 'CsData.id', 
			1  => 'CsData.created', 
			2  => 'CsData.staff_name',
			3  => 'CsData.patient_name',
			4  => 'CsData.report_view'
		);
		
		
		$Admin = $this->Auth->user();
	
		if($Admin['user_type'] == 'Admin') {
			// Calculate Total Records..
			$totalData = $this->CsData->find('count');
			$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
			$conditions=array();
			
		} elseif($Admin['user_type'] == 'Subadmin') {
			//$staffuser = $this->User->find('list',array('conditions'=>array('User.created_by'=>$Admin['id'])));
			$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$Admin['id'],'User.user_type'=>'Subadmin'),'fields'=>array('User.office_id')));
			$staffuser=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id']),'fields'=>array('User.id')));
			
			// Calculate Total Records..
			$conditions['CsData.staff_id']=$staffuser;
			$totalData = $this->CsData->find('count',array('conditions'=>$conditions));
			$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		} else {
			$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$Admin['id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.office_id')));
			$all_staff_ids=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.id')));
			// Calculate Total Records..
			$conditions['CsData.staff_id']=$all_staff_ids;
			$totalData = $this->CsData->find('count',array('conditions'=>$conditions));
			$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		}
		//creating virtual field for full name
		$this->CsData->virtualFields['staff_name'] = "CONCAT(User.first_name,' ', User.last_name)";
		$this->CsData->virtualFields['patient_name'] = "CONCAT(Patient.first_name,' ', Patient.middle_name,' ',Patient.last_name)";
		
		
		//print_r($requestData);die;
		// if there is a search parameter, $requestData['search']['value'] contains search parameter
		if(!empty($requestData['search']['value'])){
			$conditions['AND']=array(
				'OR'=>array(
					"CsData.staff_name LIKE "=>$requestData['search']['value']."%",
					"CsData.patient_name LIKE "=>$requestData['search']['value']."%",
					"CsData.created"=>date('Y-m-d',strtotime($requestData['search']['value']))
				)
			);
			$totalFiltered = $this->CsData->find('count',array('conditions'=>$conditions));
		}
		
		
		$datas = $this->CsData->find(
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
			$data[$i]['created'] = date('d M Y',strtotime($staff['CsData']['created']));

			$data[$i]['staff_name'] = "<a style='cursor: pointer;'  title='View' staffId='".$staff['CsData']['staff_id']."'class='staff' data-toggle='modal' data-target='#staffView'>". $staff['CsData']['staff_name']."</a>";
			$data[$i]['patient_name'] = "<a style='cursor: pointer;'  title='View' patientId='".$staff['CsData']['patient_id']."'class='patient' data-toggle='modal' data-target='#patientView'>".$staff['CsData']['patient_name']."</a>";
			$data[$i]['report_view'] = "<a style='cursor: pointer;'  title='View' testreportId='".$staff['CsData']['id']."'class='testreport' data-toggle='modal' data-target='#reportView'><i class='fa fa-eye' aria-hidden='true'></i></a>";
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
	
		/*Unity report view*/
	public function admin_view($id=null){
		$this->layout=false;
		if(!empty($id)){
			$this->CsData->bindModel(array(
				'hasMany'=>array(
					'CsPointdata'=>array(
						'foreignKey'=>'cs_id'
					)
				)
			));
			$data = $this->CsData->findById($id);
		 
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
	public function admin_delete($id=NULL){
		$this->loadModel('CsData');
		$Admin = $this->Auth->user();
		//pr($Admin);die;
		if($Admin['user_type'] == 'Admin' || $Admin['user_type'] == 'Subadmin') {
			$delete_record=$this->CsData->updateAll(
				array('CsData.is_delete' => '1'),
				array('CsData.id' => $id)
			);
			if($delete_record){
				$this->Session->setFlash("Contrast Sensitivity data Record deleted successfully.",'message',array('class' => 'message'));
			}else{
				$this->Session->setFlash("Unable to delete.",'message',array('class' => 'message'));
			}
			$this->redirect($this->referer());
			//$this->redirect(array('controller' => 'unityreports', 'action' => 'unity_reports_list'));
		}else{
			echo 'can not access.';die;
		}
	}
	
	

	
	

}

?>