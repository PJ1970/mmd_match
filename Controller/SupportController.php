<?php

App::uses('AppController','Controller');
App::uses('CakeEmail','Network/Email');
App::import('Controller','ChatApi');

class SupportController extends AppController {
	public $uses = array('Admin','User','Patient','Office', 'NewUserDevice', 'Cms', 'Apk','Support','SupportSee','Category');

	var $helpers = array('Html', 'Form','Js' => array('Jquery'), 'Custom', 'Dropdown');

	public $components = array('Auth'=>array('authorize'=>array('Controller')),'Session','Email','Common','RememberMe','Upload');
	public $allowedActions =array('admin_download','download');

	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->deny();
		$this->Auth->allow($this->allowedActions);	
		if ($this->request->is('ajax')){
			$this->layout = '';
		}
	}

	
	public function admin_index()  
	{     
		//$conditions=array();

		$conditions=array('Support.parent_id' => null);
		if($this->Auth->user('user_type')!='Admin' && $this->Auth->user('user_type')!='SupportSuperAdmin' && $this->Auth->user('user_type')!='RepAdmin'){
			$all_staff_ids=$this->User->find('list',array('conditions'=>array('User.office_id'=>$this->Auth->user('office_id')),'fields'=>array('User.id')));
			$conditions['Support.user_id']=$all_staff_ids; 
		}else if($this->Auth->user('user_type')=='RepAdmin'){ 
			$all_office=$this->Office->find('list',array('conditions'=>array('Office.rep_admin'=>$this->Auth->user('id')),'fields'=>array('Office.id'))); 
			$all_staff_ids=$this->User->find('list',array('conditions'=>array('User.office_id'=>$all_office),'fields'=>array('User.id'))); 
			$conditions['Support.user_id']=$all_staff_ids;
		}
		else{
			if(!empty($this->Session->read('Search.office'))){

				$all_staff_ids=$this->User->find('list',array('conditions'=>array('User.office_id'=>$this->Session->read('Search.office')),'fields'=>array('User.id')));
				$conditions['Support.user_id']=$all_staff_ids; 
			}
		}
		if(!empty($this->request->query['category'])){
			$conditions['Support.caregory_id']=$this->request->query['category'];
		}
		if(!empty($this->request->query['status'])){
			$conditions['Support.status']=$this->request->query['status'];
		}
		if(!empty($this->request->query['search'])){
			//echo "yes";die;
			$search = strtolower(trim($this->request->query['search']));
			$conditions['OR'][] = array('Lower(Support.title) like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(Support.message) like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(Support.status) like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(Office.name) like'=> '%'.$search.'%');
			$this->set(compact('search'));
		}
		$this->Support->virtualFields['total_message'] = 'select count(id) from mmd_supports as supportscount where Support.id = supportscount.id or supportscount.parent_id=Support.id';
		$this->Support->virtualFields['total_message_read'] = 'select count(id) from mmd_support_sees as supportsseecount where supportsseecount.user_id ='.$this->Auth->user('id').' and (Support.id = supportsseecount.support_id or  supportsseecount.support_id in (select id from mmd_supports as supportscount where supportscount.parent_id=Support.id))';
		$this->paginate = array('conditions' => $conditions,
			'limit' => 10,
			'recursive'=>1,
			'callbacks' => false,
			'order' => array('Support.id' => 'DESC')
		);
		$datas = $this->paginate('Support'); 
        //echo "<pre>";print_r($datas);die;
		$category = $this->Category->find('list',array('fields'=>array('id','name')));
		$this->set(compact('datas','category')); 
	}  
	public function admin_add()  
	{     
		$editData = "";
		$test_c = '';  
		if($this->request->is(array('post'))) {
			if (!empty($this->request->data['Support']['file']['tmp_name']) && is_uploaded_file($this->request->data['Support']['file']['tmp_name']) ) { 
				$filename =  time().'_'.basename($this->request->data['Support']['file']['name']); 
				if(move_uploaded_file($this->data['Support']['file']['tmp_name'], WWW_ROOT . DS . 'support/uploads' . DS . $filename)){ 
					$this->request->data['Support']['file']=$filename; 	 
				}else{
					$this->request->data['Support']['file']='';
				} 
			}else{
				$this->request->data['Support']['file']='';
			} 
			if($this->Auth->user('user_type')!='Admin' && $this->Auth->user('user_type')!='SupportSuperAdmin' && $this->Auth->user('user_type')!='RepAdmin'){
				$this->request->data['Support']['user_id']=$this->Auth->user('id');
				$this->request->data['Support']['office_id']=$this->Auth->user('office_id');
			}

			$ref_no=$this->admin_genrate_refrence_no();
			$this->request->data['Support']['refrance_no']=$ref_no;
		


			if($result=$this->Support->save($this->request->data)){
				$data = $this->Support->find('first',array('conditions'=>array('Support.id'=>$this->Support->id)));  
				try{
					  
					$Email = new CakeEmail();
					$Email->config('smtp');
					$Email->viewVars($data);
					$Email->template('new_ticket');
					$Email->from(array(FROM_EMAIL=>'MMD')); 
					$Email->to('support@micromedinc.com');
					//$Email->to('madan@braintechnosys.com');
					$Email->subject('New support ticket');
					$Email->emailFormat('html');
					$Email->send();

				}catch(Exception $e){
					/*echo "<pre>";
					print_r($e);die();*/
				}
				//echo "mail send";


				$this->Session->setFlash('Ticket added successfully.','message',array('class'=>'message'));
				unset($this->request->data);
			}else{
				$this->Session->setFlash('Ticket not added.','message',array('class'=>'message'));

			}
		} 
		$users_da=array();
		$this->request->data = $editData;
		$category = $this->Category->find('list',array('fields'=>array('Category.id','Category.name')));
		  
		$this->set(compact('test_c','users_da','category')); 
	}
	
	public function admin_edit($id)  
	{     
		$editData = "";
		$test_c = '';  
		if($id){
		$editData = $this->Support->find('first',array('conditions'=>array('Support.id'=>$id)));
		//pr($editData['Support']['title']); die;
		
		}
		if($this->request->is(array('post','put'))) { 
			if (!empty($this->request->data['Support']['file']['tmp_name']) && is_uploaded_file($this->request->data['Support']['file']['tmp_name']) ) { 
				$filename =  time().'_'.basename($this->request->data['Support']['file']['name']); 
				if(move_uploaded_file($this->data['Support']['file']['tmp_name'], WWW_ROOT . DS . 'support/uploads' . DS . $filename)){ 
					$this->request->data['Support']['file']=$filename; 	 
				}else{
					$this->request->data['Support']['file']='';
				} 
			}else{
				$this->request->data['Support']['file']='';
			} 
			if($this->Auth->user('user_type')!='Admin' && $this->Auth->user('user_type')!='SupportSuperAdmin' && $this->Auth->user('user_type')!='RepAdmin'){
				$this->request->data['Support']['user_id']=$this->Auth->user('id');
				$this->request->data['Support']['office_id']=$this->Auth->user('office_id');
			}
			//$ref_no=$this->admin_genrate_refrence_no();
			//$this->request->data['Support']['refrance_no']=$ref_no;
			 
			if($result=$this->Support->save($this->request->data)){

				$data = $this->Support->find('first',array('conditions'=>array('Support.id'=>$this->Support->id)));  
				try{
					$Email = new CakeEmail();
					$Email->viewVars($data);
					$Email->template('new_ticket');
					$Email->from(array(FROM_EMAIL=>'MMD')); 
					$Email->to('support@micromedinc.com');
					//$Email->to('madan@braintechnosys.com');
					$Email->subject('New support ticket');
					$Email->emailFormat('html');
					$Email->send();
				}catch(Exception $e){
					/*echo "<pre>";
					print_r($e);die();*/
				}
			//	echo "mail send";


				$this->Session->setFlash('Ticket Edit successfully.','message',array('class'=>'message'));
				unset($this->request->data);
			}else{
				$this->Session->setFlash('Ticket not Edited.','message',array('class'=>'message'));

			}
		} 
		
		$users_da=array();
		$this->request->data = $editData;
		//$users_data=array();
		$company_id = $this->request->data['Support']['office_id'];
		if (!empty($company_id)) {
			$users_data = $this->User->find('all', array('conditions' => array('User.office_id' => $company_id), 'fields' => array('User.*')));
			foreach ($users_data as $k => $user) {
				if ($user['User']['user_type'] == "Subadmin") {
					$users_da[$user['User']['id']] = $user['User']['first_name'] . " (Subadmin)";
				} else {
					$users_da[$user['User']['id']] = $user['User']['first_name'];
				}
			} 
		}
		
		
		$category = $this->Category->find('list',array('fields'=>array('Category.id','Category.name')));
		  
		$this->set(compact('test_c','users_da','category','data')); 
	}
	
	public function admin_closed(){

		$this->request->data['Support']['file']='';
		$this->request->data['Support']['user_id']=$this->Auth->user('id');
		if($this->Support->save($this->request->data)){

			$data = $this->Support->find('first',array('conditions'=>array('Support.id'=>$this->Support->id)));
			$data2 = $this->Support->find('first',array('conditions'=>array('Support.id'=>$this->request->data['Support']['parent_id'])));
			$data['Support']['device_serial_no']=$data2['Support']['device_serial_no'];
			$data['Support']['refrance_no']=$data2['Support']['refrance_no'];
			$data['User']=$data2['User']; 

			$data3 = $this->Support->find('first',array('conditions'=>array('Support.id'=>$this->request->data['Support']['parent_id']))); 
			$data3['Support']['status']='Closed';
			$data3['Support']['closed_by']=$this->Auth->user('id');
			$data3['Support']['closed_at']=date('Y-m-d H:i:s');
			if ($this->Support->save($data3)) {  
				// try{
				// 	$Email = new CakeEmail();
				// 	$Email->viewVars($data);
				// 	$Email->template('reply_ticket');
				// 	$Email->from(array(FROM_EMAIL=>'MMD')); 
				// 	//$Email->to($data['User']['email']);
				// 	$Email->to('adarsh@braintechnosys.com');
				// 	$Email->subject('Ticket Closed Update');
				// 	$Email->emailFormat('html');
				// 	$Email->send();
				// }catch(Exception $e){
				// 	echo "<pre>";
				// print_r($e); 
				// die();
				// } 
				$this->Session->setFlash('Ticket has been closed.','message',array('class' => 'message')); 
			} else {  
				$this->Session->setFlash('The ticket has not been updated. Please, try again.','message',array('class' => 'message'));  
			} 


		}else{
			$this->Session->setFlash('The ticket has not been updated. Please, try again.','message',array('class' => 'message'));  

		}



		$this->redirect($this->referer());
	}
	public function admin_reply($id){


		if($this->request->is(array('post'))) {  
			if (!empty($this->request->data['Support']['file']['tmp_name']) && is_uploaded_file($this->request->data['Support']['file']['tmp_name']) ) { 
				$filename = time().'_'.basename($this->request->data['Support']['file']['name']); 
				if(move_uploaded_file($this->data['Support']['file']['tmp_name'], WWW_ROOT . DS . 'support/uploads' . DS . $filename)){ 
					$this->request->data['Support']['file']=$filename; 	 
				}else{
					$this->request->data['Support']['file']='';
				} 
			}else{
				$this->request->data['Support']['file']='';
			}  

			$this->request->data['Support']['user_id']=$this->Auth->user('id');
			$this->request->data['Support']['parent_id']=$id;

			if($this->Support->save($this->request->data)){

				$data = $this->Support->find('first',array('conditions'=>array('Support.id'=>$this->Support->id)));
				$data2 = $this->Support->find('first',array('conditions'=>array('Support.id'=>$id)));
				$data['Support']['device_serial_no']=$data2['Support']['device_serial_no'];
				$data['Support']['refrance_no']=$data2['Support']['refrance_no'];
				$data['User']=$data2['User']; 
				try{
					$Email = new CakeEmail();
					$Email->viewVars($data);
					$Email->template('reply_ticket');
					$Email->from(array(FROM_EMAIL=>'MMD')); 
					$Email->to($data['User']['email']);
					$Email->subject('Ticket Update');
					$Email->emailFormat('html');
					$Email->send();
				}catch(Exception $e){

				}

				$this->Session->setFlash('Message added successfully.','message',array('class'=>'message'));
				unset($this->request->data);
			}else{
				$this->Session->setFlash('Message not added.','message',array('class'=>'message'));

			}
		}


	   // $this->Support->bindModel( 
				// 		array( 
				// 			'hasMany'=>array( 
				// 				'Support_chield' =>array( 
				// 					'className' => 'Support', 
				// 					'foreignKey' => 'parent_id',
				// 					'order' => 'id desc',
				// 				)        
				// 			) 
				// 		),false
				// 	);  
		$data = $this->Support->find('first',array('conditions'=>array('Support.id'=>$id))); 
		$datas = $this->Support->find('all',array('recursive'=>1,'order' => array('Support.id'=>'DESC'),'conditions'=>array('Support.parent_id'=>$id)));
		$check = $this->SupportSee->find('count',array('conditions'=>array('SupportSee.user_id'=>$this->Auth->user('id'), 'SupportSee.support_id'=>$data['Support']['id'])));
		if($check==0){
			$data['SupportSee']['support_id']=$data['Support']['id'];
			$data['SupportSee']['user_id']=$this->Auth->user('id');
			$this->SupportSee->create();
			$result_p = $this->SupportSee->save($data);
		}
		foreach($datas as $key => $value){
			$check = $this->SupportSee->find('count',array('conditions'=>array('SupportSee.user_id'=>$this->Auth->user('id'), 'SupportSee.support_id'=>$value['Support']['id'])));
			if($check==0){
				$data['SupportSee']['support_id']=$value['Support']['id'];
				$data['SupportSee']['user_id']=$this->Auth->user('id');
				$this->SupportSee->create();
				$result_p = $this->SupportSee->save($data);
			}
		}
		$this->set(compact('data','datas')); 

	}
	public function admin_view($id){  
		$data = $this->Support->find('first',array('recursive'=>3,'conditions'=>array('Support.id'=>$id)));  
		$datas = $this->Support->find('all',array('recursive'=>1,'order' => array('Support.id'=>'DESC'),'conditions'=>array('Support.parent_id'=>$id))); 
		$check = $this->SupportSee->find('count',array('conditions'=>array('SupportSee.user_id'=>$this->Auth->user('id'), 'SupportSee.support_id'=>$data['Support']['id'])));
		if($check==0){
			$data['SupportSee']['support_id']=$data['Support']['id'];
			$data['SupportSee']['user_id']=$this->Auth->user('id');
			$this->SupportSee->create();
			$result_p = $this->SupportSee->save($data);
		}
		foreach($datas as $key => $value){
			$check = $this->SupportSee->find('count',array('conditions'=>array('SupportSee.user_id'=>$this->Auth->user('id'), 'SupportSee.support_id'=>$value['Support']['id'])));
			if($check==0){
				$data['SupportSee']['support_id']=$value['Support']['id'];
				$data['SupportSee']['user_id']=$this->Auth->user('id');
				$this->SupportSee->create();
				$result_p = $this->SupportSee->save($data);
			}
		}
		$this->set(compact('data','datas'));  
	}
	public function admin_export($id)
	{
		 $this->layout=false;
		$this->autoRender=false;
		$data = $this->Support->find('first',array('recursive'=>3,'conditions'=>array('Support.id'=>$id)));  
		$datas = $this->Support->find('all',array('recursive'=>2,'order' => array('Support.id'=>'DESC'),'conditions'=>array('Support.parent_id'=>$id)));
	//	pr($datas); die;
		$header = array('Name','Date','Message');
		$csv = fopen("php://output", 'w');
		header('Content-Type: application/csv; charset=utf-8');
		header('Content-type: application/ms-excel');
		$file_name = "Support-ticket".$data['Support']['refrance_no'];
		header('Content-Disposition: attachment; filename='.$file_name.'.csv');
		$record = array();
		$record[] = 'Contact Name';
		$record[] = $data['User']['complete_name'];
		fputcsv($csv, $record);
		$record = array();

		$record = array();
		$record[] = 'Contact Email Address';
		$record[] = $data['User']['email'];
		fputcsv($csv, $record);
		$record = array();

		$record = array();
		$record[] = 'Contact Phone Number';
		$record[] = $data['User']['phone'];
		fputcsv($csv, $record);
		$record = array();

		$record = array();
		$record[] = 'Reference Number';
		$record[] = $data['Support']['refrance_no'];
		fputcsv($csv, $record);
		$record = array();

		$record = array();
		$record[] = 'Status';
		$record[] = $data['Support']['status'];
		fputcsv($csv, $record);
		$record = array();
		fputcsv($csv, $record);
		fputcsv($csv, array_values($header));
		 
			//pr($data); die;
			foreach($datas as $pdata){
				$record[] = $pdata['User']['complete_name'];
			 	$record[] = $pdata['Support']['created_at']; 
				$record[] = str_replace('&nbsp;','',trim(strip_tags($pdata['Support']['message']))); 
				fputcsv($csv, $record);
				$record = array();
			}
			fclose($csv);
			exit();
		 
	}
	public function admin_exportall($id=null){

	$this->layout=false;
	$this->autoRender=false;

	if(empty($this->request->query['url'])){
		$file_name = uniqid().'_'.date('Ymd');
	}else{
		$file_name = $this->request->query['url'];
	}
	$data = $this->Support->find('all',array('conditions'=>array('Support.parent_id'=>$id),'order' => array('Support.parent_id' => 'DESC')));
	//pr($data); die;
	$file_name = "Support-ticket";
	$header = array('Reference No','TYPE (Complaint/RMA)','Created at','Device serial No','Office name','Model (Focus/g2/Neo)','Close Out Initials','Caregory Id','Reportable (Y/N)','Investigation Needed (Y/N)','Title','RMA QP2 (Y/N)','Status','Close-out Date');
	$csv = fopen("php://output", 'w');
	header('Content-Type: application/csv; charset=utf-8');
	header('Content-type: application/ms-excel');
	 
		header('Content-Disposition: attachment; filename='.$file_name.'.csv');
		fputcsv($csv, array_values($header));
		
		foreach($data as $pdata ){
			$record[] = $pdata['Support']['refrance_no'];
			$record[] = $pdata['Support']['complaint_type'];//type
			$record[] = " ".$pdata['Support']['created_at']; 
			$record[] = $pdata['Support']['device_serial_no'];
			$record[] = $pdata['Office']['name'];
			$record[] = $pdata['Support']['model'];
			$record[] = $pdata['User']['first_name'];
			$record[] = $pdata['Support']['caregory_id'].'-'.$pdata['Category']['name'];
			$record[] = $pdata['Support']['reportable'];
			$record[] = $pdata['Support']['investigation'];  
			$record[] = $pdata['Support']['title']; 
			$record[] = $pdata['Support']['rma_Qp_2']; 
			$record[] = $pdata['Support']['status']; 
			$record[] = $pdata['Support']['closed_at']; 
			

			fputcsv($csv, $record);
			$record = array();
		}
		fclose($csv);
		exit();
	 	
}
	
	public function admin_unread(){
		$conditions=array();
		if($this->Auth->user('user_type')!='Admin' && $this->Auth->user('user_type')!='SupportSuperAdmin' && $this->Auth->user('user_type')!='RepAdmin'){
			$all_staff_ids=$this->User->find('list',array('conditions'=>array('User.office_id'=>$this->Auth->user('office_id')),'fields'=>array('User.id')));
			$conditions['Support.user_id']=$all_staff_ids; 
		}else if($this->Auth->user('user_type')=='RepAdmin'){ 
			$all_office=$this->Office->find('list',array('conditions'=>array('Office.rep_admin'=>$this->Auth->user('id')),'fields'=>array('Office.id'))); 
			$all_staff_ids=$this->User->find('list',array('conditions'=>array('User.office_id'=>$all_office),'fields'=>array('User.id'))); 
			$conditions['Support.user_id']=$all_staff_ids;
		}else{
			if(!empty($this->Session->read('Search.office'))){ 
				$all_staff_ids=$this->User->find('list',array('conditions'=>array('User.office_id'=>$this->Session->read('Search.office')),'fields'=>array('User.id')));
				$conditions['Support.user_id']=$all_staff_ids; 
			}
		}
		$total_count = $this->Support->find('count',array('conditions'=>$conditions));
		$total_see = $this->SupportSee->find('count',array('conditions'=>array('SupportSee.user_id'=>$this->Auth->user('id'))));
		echo ($total_count-$total_see);
		exit(); 
	}
	


/*	public function admin_genrate_refrence_no(){
		$password="";
		while($password==""){
			$length = 8;
         // $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz'; 
			$data = '1234567890'; 
			$password2=substr(str_shuffle($data), 0, $length);
			$data=$this->Support->find('first',array('conditions' =>  array('Support.refrance_no' =>$password2)));
			if(count($data)>0){

			}else{
				$password=$password2;
				return 'V'.$password2;
			}

		}   	    
	}*/
	public function admin_genrate_refrence_no(){
		//$count = 1385;
		$conditions['Support.parent_id'] = null;
		 $data = $this->Support->find('first',array('conditions' => $conditions,'order' => array('Support.created_at' => 'DESC'),'fields' => array('Support.refrance_no')));
		 $ref_num = $data['Support']['refrance_no'];
		 $int = (int) filter_var($ref_num, FILTER_SANITIZE_NUMBER_INT);
		 $refNumber = $int+1;
		 return 'V'.$refNumber;
		}   	    
}
?>