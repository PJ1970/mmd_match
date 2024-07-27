<?php
App::uses('AppController','Controller');
App::uses('CakeEmail','Network/Email');

class TestreportsController extends AppController {
	public $uses = array('Admin','User','Testreport','Office','File');
			
	var $helpers = array('Html', 'Form','Js' => array('Jquery'), 'Custom');

    public $components = array('Auth'=>array('authorize'=>array('Controller')),'Session','Email','Common','RememberMe');
	public $allowedActions =array();
    
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow($this->allowedActions);	
	}
	
	
	/*Test Report List*/
	 public function admin_test_reports_list() {
	 	if($this->Session->read('Auth.Admin.user_type')=='Admin'){
		 $Admin = $this->Auth->user();
		$conditions=array('Testreport.is_delete' =>'0');
		if($Admin['user_type'] == 'Admin') {
			
		} elseif($Admin['user_type'] == 'Subadmin') {
			$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$Admin['id'],'User.user_type'=>'Subadmin'),'fields'=>array('User.office_id')));
			$staffuser=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id']),'fields'=>array('User.id')));
			
			// Calculate Total Records..
			$conditions[]['Testreport.staff_id']=$staffuser;
			} else {
				
			$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$Admin['id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.office_id')));
			$all_staff_ids=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.id')));
		
			// Calculate Total Records..
			$conditions[]['Testreport.staff_id']=$all_staff_ids;
			}
			//creating virtual field for full name
			 
		if(!empty($this->request->query['search'])){
			//echo "yes";die;
			$search = trim($this->request->query['search']);
			$conditions['OR'][] = array('Testreport.staff_name like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Testreport.patient_name like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(Test.name) like'=> '%'.strtolower($search).'%');
			$conditions['OR'][] = array('Lower(Testreport.created)'=> $search);
			$this->set(compact('search'));
		}
		$this->Testreport->virtualFields['staff_name'] = "CONCAT(User.first_name,' ', User.last_name)";
		$this->Testreport->virtualFields['patient_name'] = "CONCAT(Patient.first_name,' ', Patient.middle_name,' ',Patient.last_name)";
		$params = array(
			'conditions' => $conditions,
			'limit'=>10,
			'order'=>array('Testreport.created'=>'DESC')
			);
		
		$this->paginate=array('Testreport'=>$params);
		$datas = $this->paginate('Testreport');
		$this->set(compact('datas'));
		}else{
            $this->redirect(WWW_BASE.'admin/dashboards/index');
        }
	} 
	
	
	public function admin_ajaxTestReportList(){
		$this->layout = false;
		$this->autoRender = false;
		$requestData = $this->request->data;
		$columns = array( 
			// datatable column index  => database column name
			0  => 'Testreport.id', 
			1  => 'Testreport.created', 
			2  => 'Test.name',
			3  => 'Testreport.staff_name',
			4  => 'Testreport.patient_name',
			5  => 'Patient.report_view'
			/* 6  => 'File.file_path' */
		);
		//pr($requestData);
		
		$Admin = $this->Auth->user();
		
		if($Admin['user_type'] == 'Admin') {
			// Calculate Total Records..
			$totalData = $this->Testreport->find('count');
			$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
			$conditions=array();
			
		} elseif($Admin['user_type'] == 'Subadmin') {
			//$staffuser = $this->User->find('list',array('conditions'=>array('User.created_by'=>$Admin['id'])));
			$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$Admin['id'],'User.user_type'=>'Subadmin'),'fields'=>array('User.office_id')));
			$staffuser=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id']),'fields'=>array('User.id')));
			
			// Calculate Total Records..
			$conditions['Testreport.staff_id']=$staffuser;
			$totalData = $this->Testreport->find('count',array('conditions'=>$conditions));
			$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		} else {
			$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$Admin['id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.office_id')));
			$all_staff_ids=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.id')));
			// Calculate Total Records..
			$conditions['Testreport.staff_id']=$all_staff_ids;
			$totalData = $this->Testreport->find('count',array('conditions'=>$conditions));
			$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
		}
		//creating virtual field for full name
		$this->Testreport->virtualFields['staff_name'] = "CONCAT(User.first_name,' ', User.last_name)";
		$this->Testreport->virtualFields['patient_name'] = "CONCAT(Patient.first_name,' ', Patient.last_name)";
		
		
		// if there is a search parameter, $requestData['search']['value'] contains search parameter
		if(!empty($requestData['search']['value'])){
			$conditions['AND']=array(
				'OR'=>array(
					"Testreport.staff_name LIKE "=>$requestData['search']['value']."%",
					"Testreport.patient_name LIKE "=>$requestData['search']['value']."%",
					"Test.name LIKE "=>$requestData['search']['value']."%",
					"Testreport.created"=>date('Y-m-d',strtotime($requestData['search']['value']))
				)
			);
			$totalFiltered = $this->Testreport->find('count',array('conditions'=>$conditions));
		}
		
		
		$datas = $this->Testreport->find(
			'all',array(
				'conditions'=>$conditions,
				'order' => array($columns[$requestData['order'][0]['column']]." ".strtoupper($requestData['order'][0]['dir'])),
				'limit' =>$requestData['length'],
				'offset' =>$requestData['start'],
			)
		);
		//pr($datas);
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
			$data[$i]['created'] = date('d M Y',strtotime($staff['Testreport']['created']));
			$data[$i]['name'] = $staff['Test']['name'];
			/* $data[$i]['file_path'] = $staff['File']['file_path']; */
			$data[$i]['staff_name'] = "<a style='cursor: pointer;'  title='View' staffId='".$staff['Testreport']['staff_id']."'class='staff' data-toggle='modal' data-target='#staffView'>". $staff['Testreport']['staff_name']."</a>";
			$data[$i]['patient_name'] = "<a style='cursor: pointer;'  title='View' patientId='".$staff['Testreport']['patient_id']."'class='patient' data-toggle='modal' data-target='#patientView'>".$staff['Testreport']['patient_name']."</a>";
			$file_name= getcwd().'/uploads/pdf/'.$staff['Testreport']['pdf'];
			if(!empty($staff['Testreport']['pdf'])&&(file_exists($file_name))):
				$data[$i]['report_view'] = "<a style='cursor: pointer;'  title='View' testreportId='".$staff['Testreport']['id']."'class='testreport' data-toggle='modal' data-target='#reportView'><i class='fa fa-eye' aria-hidden='true'></i></a>&nbsp;&nbsp;<a type='button'  title='Pdf' target='_blank' class='testpdf' data='".$staff['Testreport']['pdf']."' href='".WWW_BASE.'uploads/pdf/'.$staff['Testreport']['pdf']."'><i class='fa fa-file-pdf-o' aria-hidden=true></i></a>";
			else:
				$data[$i]['report_view'] = "<a style='cursor: pointer;'  title='View' testreportId='".$staff['Testreport']['id']."'class='testreport' data-toggle='modal' data-target='#reportView'><i class='fa fa-eye' aria-hidden='true'></i></a>";
			endif;
			$i++;
		}
		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),
			"recordsTotal"    => intval( $totalData ),
			"recordsFiltered" => intval( $totalFiltered ),
			"data"            => $data
		);
		
		//pr($json_data);die;
		echo json_encode($json_data);
	}
	
	
	/*Test report view*/
	public function admin_view($id=null){
		$this->layout=false;
		if(!empty($id)){
			$this->Testreport->bindModel(array(
				'hasMany'=>array(
					'File'=>array(
						'foreignKey'=>'test_report_id'
					)
				)
			));
			$data = $this->Testreport->findById($id);
			$office = $this->Office->findById($data['User']['office_id']);
			/* $conditions = "Testreport.test_id NOT IN";  
			$datafile =  $this->File->find('all',array('conditions'=>array(
			"NOT" => array( "Testreport.test_id" => array(0,1) )
			))); */
			//$this->set(compact('datafile','datafile'));
			//pr($data);
			$this->set(compact('data','office'));
		}	
	}
}

?>