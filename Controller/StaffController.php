<?php
App::uses('AppController','Controller');
App::uses('CakeEmail','Network/Email');
App::import('Controller','ChatApi');

class StaffController extends AppController {
	public $uses = array('Admin','User','Patient');
			
	var $helpers = array('Html', 'Form','Js' => array('Jquery'), 'Custom');

    public $components = array('Auth'=>array('authorize'=>array('Controller')),'Session','Email','Common','RememberMe','Upload');
	public $allowedActions =array();
    
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->deny();
		$this->Auth->allow($this->allowedActions);	
	}
	public function isAuthorized($user){ 
	    parent::beforeFilter();
		if($user['user_type'] == 'Staffuser' && $this->params['action']=='admin_staffView'){
			return true;
		}
		if(isset($user['user_type']) && (($user['user_type'] == 'Admin') || ($user['user_type'] == 'Subadmin') && isset($this->request->prefix) && ($this->request->prefix == 'admin'))){
			return true;
		}else{
			$this->redirect($this->referer());
		}
    }
	
	
	/*Adding staff*/
	public function admin_add(){
		//if($this->Session->read('Auth.Admin.user_type')=='Admin'){
		$Admin = $this->Session->read('Auth.Admin');
		if($this->request->is('post')){
			$password = $this->request->data['User']['password'];
			$confirm_pass = $this->request->data['User']['confirm_pass'];
			$password_expiry =  date('Y-m-d H:i:s', strtotime("+90 days"));
			$this->request->data['User']['password_expiry_date'] = $password_expiry;  
			unset($this->request->data['User']['confirm_pass']);
			if($password == $confirm_pass) {
				if($Admin['user_type'] == 'Subadmin'){ 
					$this->request->data['User']['office_id']=$Admin['office_id'];
				}
				$this->request->data['User']['user_type']='Staffuser';
				$this->request->data['User']['created_by'] = $Admin['id'];
				
				if(isset($this->request->data['User']['profile_pic']['name'])&&(!empty($this->request->data['User']['profile_pic']['name']))){
					$profile_pic=time().$this->request->data['User']['profile_pic']['name'];
					$image_type=strtolower(substr($profile_pic,strrpos($profile_pic,'.')+1));
					$uploadFiles = $this->request->data['User']['profile_pic'];
					$fileName = $profile_pic;
					$upload_path=getcwd()."/app/webroot/img/uploads/";
					
					$data12 = array('type' => 'resize', 'size' => array(150, 150), 'output' => $image_type, 'quality' => 100);
					$status = $this->Upload->upload($uploadFiles, $upload_path, $fileName, $data12, null);
					$this->request->data['User']['profile_pic']=$profile_pic;
						
				}else{
					$this->request->data['User']['profile_pic']='';
				}
				if($this->User->save($this->request->data)){
					if(isset($this->request->data['User']['rotate'])&&(!empty($this->request->data['User']['rotate']))){
						$this->User->id=$this->User->getLastInsertId();
						$profile_pic=$this->User->field('profile_pic');
						if(!empty($profile_pic)){
							$rotate_value=$this->request->data['User']['rotate'];
							$profile_pic=$this->Upload->rotate_Edit($profile_pic,$rotate_value);
							if(!empty($profile_pic)){
								$this->User->saveField('profile_pic',$profile_pic);
								
							}
						}
					}
					
					$this->Session->setFlash("New staff user has been created successfully.",'message',array('class' => 'message'));
					$this->redirect(array('controller'=>'staff','action'=>'admin_staff_listing'));		
				}
			} else {
				$this->User->validationErrors['confirm_pass'] = array("Password and confirm mismatch.");
			}			
		}
		/*}else{
            $this->redirect('https://www.portal.micromedinc.com/admin/dashboards/index');
        }*/
	}
	
	/*Delete Staff*/
	public function admin_delete($id=null){
		$this->loadModel('Testreport');
		$this->loadModel('Pointdata');
		$this->loadModel('VaData');
		$this->autoRender = false;
		if($id){
			$check_user=$this->User->find('first',array(
				'conditions'=>array('User.id'=>$id),
				'fields'=>array('User.id,User.user_type','created_by','office_id')
			));
			if(!empty($check_user) && !empty($check_user['User']['created_by']) && !empty($check_user['User']['office_id'])){
				/*update status of staff */ 
				$check_subAdmin = $this->User->find('first',array(
									'conditions'=>array('User.office_id'=>$check_user['User']['office_id'], 'User.user_type'=>'Subadmin' , 'User.is_delete' => 0),
									'fields'=>array('User.id')
								));
				/*if(empty($check_subAdmin)){
					$check_subAdmin = $this->User->find('first',array(
									'conditions'=>array('User.office_id'=>$check_user['User']['office_id'], 'User.user_type'=>'Staffuser' , 'User.is_delete' => 0),
									'fields'=>array('User.id')
								));
				}*/
				if(!empty($check_subAdmin)){
					$delete_staff['User']['id']=$id;
					$delete_staff['User']['is_delete']=1;
					if($this->User->save($delete_staff)){
						/*Delete All patients of staff(Manage status)*/
						$all_patient_ids=$this->Patient->find('list',array('conditions'=>array('Patient.user_id'=>$id),'fields'=>array('Patient.id')));
						//pr($all_patient_ids);die;
						if(!empty($all_patient_ids)){
							$this->Patient->updateAll(
								array('Patient.user_id'=>$check_subAdmin['User']['id']),
								array('Patient.id' => $all_patient_ids)
							);
						}
						$this->Testreport->updateAll(array('Testreport.staff_id' => $check_subAdmin['User']['id']),array('Testreport.staff_id' => $id));
				
						$this->Pointdata->updateAll(array('Pointdata.staff_id' => $check_subAdmin['User']['id']),array('Pointdata.staff_id' => $id));
						
						$this->VaData->updateAll(array('VaData.staff_id' => $check_subAdmin['User']['id']),array('VaData.staff_id' => $id));
						
						/*end*/
						
						$this->Session->setFlash('Staff user has been deleted successfully.','message',array('class' => 'message'));
						$this->redirect(array('controller'=>'staff','action'=>'admin_staff_listing'));
					}else{
						$this->Session->setFlash('Oops! There is some problem in deleting satff user.','message',array('class' => 'message'));
						$this->redirect(array('controller'=>'staff','action'=>'admin_staff_listing'));
						
					}
				}else{
					$this->Session->setFlash('Invalid satff user.','message',array('class' => 'message'));
					$this->redirect(array('controller'=>'staff','action'=>'admin_staff_listing'));
				}
			}else{
				$this->Session->setFlash('Invalid satff user.','message',array('class' => 'message'));
				$this->redirect(array('controller'=>'staff','action'=>'admin_staff_listing'));
			}
		}
	}
	
	/*Edit staff*/
	
	public function admin_edit($id=null){
		//if($this->Session->read('Auth.Admin.user_type')=='Admin'){
		$data=$this->User->find('first',array('conditions'=>array('User.id'=>$id)));
		$data['User']['dob'] = date('m/d/Y', strtotime($data['User']['dob']));
		$Admin = $this->Session->read('Auth.Admin');
		$this->set(compact('data'));
		if($this->request->is(array('post','put'))){
			$this->User->id=$this->request->data['User']['id'];
			$username=$this->User->field('username');
			$office_id=$this->User->field('office_id');
			if($username==$this->request->data['User']['username']){
				$this->User->validator()->remove('username', 'unique');
			}
			if(empty($this->request->data['User']['password'])){
				unset($this->request->data['User']['password']);
				unset($this->request->data['User']['confirm_pass']);
			}else{
				$password_expiry =  date('Y-m-d H:i:s', strtotime("+90 days"));
				$this->request->data['User']['password_expiry_date'] = $password_expiry; 
			}
			//pr($this->request->data);die;
			//check patient assign to staff
			$all_staff_patients = $this->Patient->find('all',array('conditions'=>array('Patient.user_id'=>$this->request->data['User']['id'])));
			if((($this->request->data['User']['office_id'])!=$office_id)&&(!empty($all_staff_patients))){
				$this->User->validationErrors['office_id'] = array("You can not change Office because Patients assigned to this Staff."); 
			}else{
				if(isset($this->request->data['User']['profile_pic']['name'])&&(!empty($this->request->data['User']['profile_pic']['name']))){
					$profile_pic=time().$this->request->data['User']['profile_pic']['name'];
					$image_type=strtolower(substr($profile_pic,strrpos($profile_pic,'.')+1));
					$uploadFiles = $this->request->data['User']['profile_pic'];
					$fileName = $profile_pic;
					$upload_path=getcwd()."/app/webroot/img/uploads/";
					
					$data12 = array('type' => 'resize', 'size' => array(150, 150), 'output' => $image_type, 'quality' => 100);
					$status = $this->Upload->upload($uploadFiles, $upload_path, $fileName, $data12, null);
					$this->request->data['User']['profile_pic']=$profile_pic;
				
					
				}else{
					$this->User->id=$this->request->data['User']['id'];
					$profile_pic=$this->User->field('profile_pic');
					$this->request->data['User']['profile_pic']=$profile_pic;
				}
				
				if($this->User->save($this->request->data)){
					
					if(isset($this->request->data['User']['rotate'])&&(!empty($this->request->data['User']['rotate']))){
						$this->User->id=$this->request->data['User']['id'];
						$profile_pic=$this->User->field('profile_pic');
						if(!empty($profile_pic)){
							$rotate_value=$this->request->data['User']['rotate'];
							$profile_pic=$this->Upload->rotate_Edit($profile_pic,$rotate_value);
							if(!empty($profile_pic)){
								$this->User->saveField('profile_pic',$profile_pic);
								
							}
						}
					}
					
					$this->Session->setFlash('Staff user has been updated successfully.','message',array('class' => 'message'));
					$this->redirect(array('controller'=>'staff','action'=>'staff_listing'));
				}
			}
			
		} else {
			$this->request->data = $data; 
		}
		/*}else{
            $this->redirect('https://www.portal.micromedinc.com/admin/dashboards/index');
        }	*/
	}
	
	
	/*password reset for staff*/
	public function admin_resetpassword($id=null){
		$this->layout='admin_default';
		$this->set('id',$id);
		if($this->request->is(array('post','put'))) {
			if(empty($this->request->data['User']['confirm_password'])) {
				$this->User->validationErrors['confirm_password'] = array("Please enter confirm password .");
			}
			$password = $this->request->data['User']['password'];
			$confirm_pass = $this->request->data['User']['confirm_password'];
			unset($this->request->data['User']['confirm_password']);
			if($password == $confirm_pass) {
				//$this->User->id = $id;
				if($this->User->save($this->request->data)){
					$this->Session->setFlash('Reset password has been done successfully.','message',array('class' => 'message'));
					$this->redirect(array('controller'=>'staff','action'=>'staff_listing'));
				}
			} else {
				$this->User->validationErrors['confirm_password'] = array("Password and confirm password mismatch.");
			}
		}	
	}
	
	
	/*Staff listing*/
	public function admin_staff_listing() {
		 
		//if($this->Session->read('Auth.Admin.user_type')!='Admin'){
		$Admin = $this->Session->read('Auth.Admin');
		if($Admin['user_type'] == 'Admin') {
			$conditions = array('User.user_type'=>'Staffuser','User.is_delete'=>0);
		}
		if($Admin['user_type'] == 'Subadmin') {	
			$conditions = array('User.user_type'=>'Staffuser','User.office_id' => $Admin['office_id'],'User.is_delete'=>0);
		}
	 
		if(!empty($this->Session->read('Search.office'))){
			$conditions[] = array('User.office_id' => $this->Session->read('Search.office'));
		}
		if(!empty($this->request->query['search'])){
			//echo "yes";die;
			$search = strtolower(trim($this->request->query['search']));
			$conditions['OR'][] = array('Lower(User.first_name) like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(User.last_name) like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(User.username) like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(User.email) like'=> '%'.$search.'%');
			$this->set(compact('search'));
		}
		$this->paginate=array('conditions'=>$conditions,
		'limit'=>10,
		'order'=>array('User.id'=>'DESC')); 
		$datas=$this->paginate('User');
		
		if($Admin['user_type'] == 'Subadmin' || $Admin['user_type'] == 'Staffuser') {
			$this->loadModel('Payment');
			//pr($Admin);die;
			$get_last_payment=$this->Payment->find('first',array(
				'conditions'=>array('Payment.user_id'=>$Admin['id'],'Payment.payment_status'=>'Success','Payment.expiry_date > '=>date('Y-m-d h:i:s')),
				'order' => array('id' => 'DESC')
			)); //check credit expire or not
			
			if($Admin['user_type'] == 'Staffuser'){
				$user_s = $this->User->find('first',array(
					'conditions'=>array('User.id'=>$Admin['created_by'])
				));
				//pr($user_s);die;
				$get_last_payment=$this->Payment->find('first',array(
					'conditions'=>array('Payment.user_id'=>$user_s['User']['id'],'Payment.payment_status'=>'Success','Payment.expiry_date > '=>date('Y-m-d h:i:s')),
					'order' => array('id' => 'DESC')
				));
			}
			
			//pr($get_last_payment);die;
			$this->loadModel('Office');
			$check_payable=$this->Office->find('first',array(
				'conditions'=>array('Office.id'=>$Admin['Office']['id'])
			));
			//pr($check_payable);die;
			if(empty($get_last_payment) && $check_payable['Office']['payable']=='yes'){
				$credit_expire = 'Credit expire';
				$this->set(compact('datas','credit_expire'));
			}
		}
		
		$this->set(compact('datas'));
	 /*else{
            $this->redirect('https://www.portal.micromedinc.com/admin/dashboards/index');
        }*/
	}
	
	/*Staff view*/
	public function admin_staffView($id=null) {
		$this->layout=false;
		$data=$this->User->find('first',array('conditions'=>array('User.id'=>$id)));
		$this->set(compact('data'));		
	}
	public function admin_assign_credits() {
		$Admin = $this->Session->read('Auth.Admin');
		if($Admin['user_type'] == 'Admin') {
			$conditions = array('User.user_type'=>'Staffuser','User.is_delete'=>0,'User.credits >'=>0,'User.office_id '=>$Admin['office_id']);
			$conditions1 = array('User.user_type'=>'Staffuser','User.is_delete'=>0,'User.office_id '=>$Admin['office_id']);
		}
		if($Admin['user_type'] == 'Subadmin'){
			$conditions = array('User.user_type'=>'Staffuser','User.office_id' => $Admin['office_id'],'User.is_delete'=>0,'User.credits >'=>0,'User.office_id '=>$Admin['office_id']);
			$conditions1 = array('User.user_type'=>'Staffuser','User.office_id' => $Admin['office_id'],'User.is_delete'=>0,'User.office_id '=>$Admin['office_id']);
		}
		$Admin = $this->Session->read('Auth.Admin');
		if(!empty($this->request->query['search'])){
			$search = strtolower(trim($this->request->query['search']));
			$conditions['OR'][] = array('Lower(User.first_name) like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(User.last_name) like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(User.username) like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(User.email) like'=> '%'.$search.'%');
			$this->set(compact('search'));
		}
		
		$this->loadModel('Office');
		$assigned_credit = $this->User->find('list',array('fields' => array('id','credits'),'conditions' => $conditions));
		 
		$assigned_credit = !empty($assigned_credit)? array_sum(array_values($assigned_credit)) :0;
		
		if(!empty($this->request->data)){
			//pr($this->request->data);die;
			if(@$this->request->data['User']['credits'] >0 && !empty($this->request->data['User']['id'])){
				$avl_crd = $this->Office->field('credits',array('Office.id'=>$Admin['office_id']));
				//$avl_crd = $avl_crd-$assigned_credit;
				
				if($avl_crd >0 && $this->request->data['User']['credits'] <= $avl_crd ){
					$user_credit = $this->User->field('credits',array('User.id'=>$this->request->data['User']['id']));
					$current_crdt = $this->request->data['User']['credits'];
					$this->request->data['User']['credits'] = $this->request->data['User']['credits'] + $user_credit ;
					if($this->User->save($this->request->data)){
						$update_credit = $avl_crd-$current_crdt;
						
						$this->Office->updateAll(array('Office.credits' => $update_credit),array('Office.id' => $Admin['office_id']));
						
						$this->Session->setFlash('Credit assigned successfully.','message',array('class' => 'message'));
						$this->redirect(array('controller'=>'staff','action'=>'admin_assign_credits'));
					}else{
						$this->Session->setFlash('Oops! Something went wrong.Please try again','message',array('class' => 'message'));
						$this->redirect(array('controller'=>'staff','action'=>'admin_assign_credits'));
					}
				}else{
					$this->Session->setFlash('Oops! There is not enough credits to assign.Please pruchase credits','message',array('class' => 'message'));
					$this->redirect(array('controller'=>'staff','action'=>'admin_assign_credits'));
				}
					
			}else{
				$this->Session->setFlash('Oops! Something went wrong.Please try again','message',array('class' => 'message'));
				$this->redirect(array('controller'=>'staff','action'=>'admin_assign_credits'));
			}
		}
		
		$avl_crd = $this->Office->field('credits',array('Office.id'=>$Admin['office_id']));
		//$avl_crd = $avl_crd-$assigned_credit;
		
		$staff = $this->User->find('list',array('order'=>array('User.first_name' =>'ASC','User.last_name' =>'ASC'),'conditions' => $conditions1,'fields'=> array('User.id','User.complete_name')));
		 
		$this->set(compact('staff','avl_crd'));
		$this->paginate=array('conditions'=>$conditions,
		'limit'=>10,
		'order'=>array('User.id'=>'DESC')); 
		$datas=$this->paginate('User');
		$this->set(compact('datas'));
	 
	}
	public function admin_remove_credits($id){
		$Admin = $this->Session->read('Auth.Admin');
		$this->loadModel('Office');
		if(empty($id)){
			$this->Session->setFlash('Oops! Something went wrong.Please try again','message',array('class' => 'message'));
			$this->redirect(array('controller'=>'staff','action'=>'admin_assign_credits'));
		}
		$user_data = $this->User->findById($id);
		$avl_crd = $this->Office->field('credits',array('Office.id'=>$Admin['office_id']));
		$data['Office']['id'] = $Admin['office_id'];
		$data['Office']['credits'] = ($user_data['User']['credits'] >0)? $avl_crd+$user_data['User']['credits'] : $avl_crd;
		 if($this->Office->save($data)){
			 $this->User->updateAll(array('User.credits' =>0),array('User.id' => $id));
			 $this->Session->setFlash('Credit removed from this staff and added in office credits.','message',array('class' => 'message'));
			$this->redirect(array('controller'=>'staff','action'=>'admin_assign_credits'));
		 }
		
	}
	public function admin_unlocl($id=null){
		//if($this->Session->read('Auth.Admin.user_type')=='Admin'){
		$data=$this->User->find('first',array('conditions'=>array('User.id'=>$id)));
		$this->User->id = $data['User']['id'];
		$this->User->saveField('lock_counter_time',null);
		$this->User->saveField('lock_time',null);
		$this->User->saveField('lock_counter',0);
		$this->Session->setFlash('Staff Unlock successfully.','message',array('class' => 'message'));
		$this->redirect($this->referer());
	}
	
}

?>