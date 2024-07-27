<?php
App::uses('AppController','Controller');
App::uses('CakeEmail','Network/Email');
App::import('Controller','ChatApi');

class CustomersController extends AppController {
	public $uses = array('Admin','User', 'Module', 'AssignModule','AssignCoach','Office','Patient', 'Test');
			
	var $helpers = array('Html', 'Form','Js' => array('Jquery'), 'Custom');

    public $components = array('Auth'=>array('authorize'=>array('Controller')),'Session','Email','Common','RememberMe','Upload');
	public $allowedActions =array('admin_forgot_password','admin_login','admin_logout', 'admin_reset_password');
    
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow($this->allowedActions);	
	}
	
	protected function _setCookie($options = array(), $cookieKey = 'rememberMe') {
		$this->RememberMe->settings['cookieKey'] = $cookieKey;
		$this->RememberMe->configureCookie($options);
		$this->RememberMe->setCookie();
	}
	
	public function isAuthorized($user){ 
	    parent::beforeFilter();
		if(isset($user['user_type']) && (($user['user_type'] == 'Admin') || ($user['user_type'] == 'Subadmin') || ($user['user_type'] == 'Staffuser')) && isset($this->request->prefix) && ($this->request->prefix == 'admin')){
			return true;
		}else{
			$this->redirect($this->referer());
		}
    }
	public function admin_index(){
	if($this->Session->read('Auth.Admin.user_type')=='Admin'){
		$conditions = array('User.user_type'=>'OfficeAdmin','User.is_delete'=>0);
			 
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
		//pr($datas); die;
		$this->set(compact('datas'));
		}else{
            $this->redirect(WWW_BASE.'admin/dashboards/index');
        } 
	}
	public function admin_add_office($id=null){
		if($this->Session->read('Auth.Admin.user_type')=='Admin'){
		if($this->request->is(array('post'))){
			$this->Office->set($this->request->data);
			if ($this->Office->validates(array('fieldList' => array('name', 'email')))) {
				//pr($this->request->data); die;
				$this->Session->write('Office', $this->request->data);
				return $this->redirect(
					array('action' => 'admin_add')
				);
			} else {
				$errors = $this->Office->validationErrors;
				//pr($errors); die;
			}
				
		}
		}else{
            $this->redirect(WWW_BASE.'admin/dashboards/index');
        } 
		
	}
	public function admin_add(){
		$this->loadModel('Office');
		$Admin = $this->Session->read('Auth.Admin');
		$office = $this->Session->read('Office');
		//pr($office); die; 
		$this->set(compact('office'));
		if($this->request->is('post')){
			
			//pr($this->request->data); die;
			$password = $this->request->data['User']['password'];
			$confirm_pass = $this->request->data['User']['confirm_pass'];
			unset($this->request->data['User']['confirm_pass']);
			if($password == $confirm_pass){
				
				if($this->Office->save($this->Session->read('Office'))){
					$this->request->data['User']['user_type']='OfficeAdmin';
					$this->request->data['User']['created_by'] = $Admin['id'];
					$newOfficeId = $this->Office->id;
					$this->request->data['User']['office_id']=$newOfficeId;
					if(isset($this->request->data['User']['profile_pic']['name'])&&(!empty($this->request->data['User']['profile_pic']['name']))){
						$profile_pic=time().$this->request->data['User']['profile_pic']['name'];
						$image_type=strtolower(substr($profile_pic,strrpos($profile_pic,'.')+1));
						$uploadFiles = $this->request->data['User']['profile_pic'];
						$fileName = $profile_pic;
						$upload_path="img/uploads/";
						
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
						$this->Office->id = $this->request->data['User']['office_id'];
						$this->Office->saveField('assign_status',1);
						$this->Session->delete('Office');
						$this->Session->setFlash("New customers has been created successfully.",'message',array('class' => 'message'));
						$this->redirect(array('controller'=>'customers','action'=>'index'));		
					}else{
						$this->Office->delete($newOfficeId);
						$errors = $this->User->validationErrors;
					}
				}
			} else {
				$this->User->validationErrors['confirm_pass'] = array("Password and confirm mismatch.");
			}			
		}
	}
	public function admin_edit($id=null){
		$data=$this->User->find('first',array('conditions'=>array('User.id'=>$id)));
		unset($data['User']['password']);
		//pr($data); die;
		$data['User']['dob'] = date('m/d/Y', strtotime($data['User']['dob']));
		$data['User']['practice_id']=explode(',',$data['User']['practice_id']);
		$this->set(compact('data'));
		if($this->request->is(array('post','put'))){
			if(empty($this->request->data['User']['password'])){
				unset($this->request->data['User']['password']);
			}
			if(!empty($this->request->data['User']['office_id'])) {
				$office_assign = $this->Office->findById($this->request->data['User']['office_id']);
				
				//check Patient or staff assign to subAdmin
				
				$all_subadmin_staffs = $this->User->find('all',array('conditions'=>array('User.created_by'=>$this->request->data['User']['id'],'User.user_type'=>'Staffuser')));
				$all_subadmin_patients = $this->Patient->find('all',array('conditions'=>array('Patient.user_id'=>$this->request->data['User']['id'])));
				if(($data['User']['office_id']!=$office_assign['Office']['id'])&&(!empty($all_subadmin_staffs)||(!empty($all_subadmin_patients)))){
					$this->User->validationErrors['office_id'] = array("You can not change Office because Staff or Patients assigned to this subAdmin."); 
				}//end
				else if(($data['User']['office_id']!=$office_assign['Office']['id']) && $office_assign['Office']['assign_status'] == 1){

					$this->User->validationErrors['office_id'] = array("This Office is assign to other user please select another one."); 
				}
				else if($data['User']['office_id']!=$office_assign['Office']['id']){
					$this->Office->id = $data['User']['office_id'];
					$this->Office->saveField('assign_status',0);
				}
				$username=$this->User->field('username');
				
				if($username==$this->request->data['User']['username']){
					$this->User->validator()->remove('username', 'unique');
				}
			}
			
			if(isset($this->request->data['User']['profile_pic']['name'])&&(!empty($this->request->data['User']['profile_pic']['name']))){
				$profile_pic=time().$this->request->data['User']['profile_pic']['name'];
				$image_type=strtolower(substr($profile_pic,strrpos($profile_pic,'.')+1));
				$uploadFiles = $this->request->data['User']['profile_pic'];
				$fileName = $profile_pic;
				$upload_path="img/uploads/";
				$data12 = array('type' => 'resize', 'size' => array(150, 150), 'output' => $image_type, 'quality' => 100);
				$status = $this->Upload->upload($uploadFiles,$upload_path, $fileName, $data12, null);
				$this->request->data['User']['profile_pic']=$profile_pic;
				
			}else{
				$this->User->id=$this->request->data['User']['id'];
				$profile_pic=$this->User->field('profile_pic');
				$this->request->data['User']['profile_pic']=$profile_pic;
			}
			
			if(!isset($this->User->validationErrors['office_id'])){
				//pr($this->request->data); die;
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
					$this->Office->id = $this->request->data['User']['office_id'];
					$this->Office->saveField('assign_status',1);	
					$this->Session->setFlash('Customer has been updated successfully.','message',array('class' => 'message'));
					$this->redirect(array('controller'=>'customers','action'=>'index'));
				}
			}else{
				$this->request->data = $data; 
			}
		} else {
			$this->request->data = $data; 
		}	
	}
	public function admin_view($id=null){
		$this->layout=false;
		$data=$this->User->find('first',array('conditions'=>array('User.id'=>$id)));
		//pr($data); die;
		$this->set(compact('data'));
	}
	public function admin_delete($id=null){
		
	}
}

?>