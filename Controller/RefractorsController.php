<?php
App::uses('AppController','Controller');
App::uses('CakeEmail','Network/Email');

class RefractorsController extends AppController {
	public $uses = array('Admin','User','Testreport','Office','File','Refractor');
			
	var $helpers = array('Html', 'Form','Js' => array('Jquery'), 'Custom');

    public $components = array('Auth'=>array('authorize'=>array('Controller')),'Session','Email','Common','RememberMe');
	public $allowedActions =array('admin_view');
    
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow($this->allowedActions);	
	}
	
	
	/*Test Report List*/
	public function admin_reports(){
		$Admin = $this->Auth->user();
		$conditions=array('Refractor.is_delete' => '0');
		if($Admin['user_type'] == 'Admin'){
			if(!empty($this->Session->read('Search.office'))){
				$office_id['User']['office_id'] =  $this->Session->read('Search.office');
				$staffuserAdmin=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id']),'fields'=>array('User.id')));
				$conditions[]['Refractor.staff_id']=$staffuserAdmin;
			}
		} elseif($Admin['user_type'] == 'Subadmin') {
			//$staffuser = $this->User->find('list',array('conditions'=>array('User.created_by'=>$Admin['id'])));
			$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$Admin['id'],'User.user_type'=>'Subadmin'),'fields'=>array('User.office_id')));
			if(!empty($this->Session->read('Search.office'))){
				$office_id['User']['office_id'] =  $this->Session->read('Search.office');
			}
			$staffuser=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id']),'fields'=>array('User.id')));
			
			// Calculate Total Records..
			$conditions[]['Refractor.staff_id']=$staffuser;
			 
		} else {
			$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$Admin['id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.office_id')));
			if(!empty($this->Session->read('Search.office'))){
				$office_id['User']['office_id'] =  $this->Session->read('Search.office');
			}
			$all_staff_ids=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.id')));
			$conditions[]['Refractor.staff_id']=$all_staff_ids;
		}
		
		if(!empty($this->request->query['search'])){
			//echo "yes";die;
			$search = trim($this->request->query['search']);
			//$conditions['OR'][] = array('Refractor.staff_name like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Refractor.patient_name like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Refractor.created like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Refractor.id'=> $search);
			$this->set(compact('search'));
		} 
		//creating virtual field for full name
		//$this->Refractor->virtualFields['staff_name'] = "CONCAT(User.first_name,' ', User.last_name)";
		//$this->Refractor->virtualFields['patient_name'] = "CONCAT(Patient.first_name,' ', Patient.last_name)";
		$params = array(
			'conditions' => $conditions,
			'limit'=>10,
			'order'=>array('Refractor.created'=>'DESC')
		);
		$this->loadModel('User');
		$users = $this->User->find('list',array('conditions'=>array('User.is_delete'=>'0'),'fields' => array('User.id','User.first_name')));
		$this->paginate=array('Refractor'=>$params);
		$datas = $this->paginate('Refractor');
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
	
		
	 
	public function admin_view($id=null){
		$this->layout=false;
		if(!empty($id)){
			$this->Refractor->bindModel(array(
				'hasMany'=>array(
					'RefractorData'=>array(
						'foreignKey'=>'refractor_id'
					)
				) 
			)); 
			$data = $this->Refractor->findById($id);
			$user = $this->User->findById(@$data['Refractor']['staff_id']); 
			$this->set(compact('data','user'));
		}	
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
	public function admin_delete($id=NULL){
		$this->loadModel('Refractor');
		$Admin = $this->Auth->user();
		//pr($Admin);die;
		if($Admin['user_type'] == 'Admin' || $Admin['user_type'] == 'Subadmin') {
			$delete_record=$this->Refractor->updateAll(
				array('Refractor.is_delete' => '1'),
				array('Refractor.id' => $id)
			);
			if($delete_record){
				$this->Session->setFlash("Refractor Record deleted successfully.",'message',array('class' => 'message'));
			}else{
				$this->Session->setFlash("Unable to delete.",'message',array('class' => 'message'));
			}
			$this->redirect(array('controller' => 'refractors', 'action' => 'reports'));
		}else{
			echo 'can not access.';die;
		}
	}
}

?>