<?php
App::uses('AppController','Controller');
App::uses('CakeEmail','Network/Email');
App::import('Controller','ChatApi');

class UsersController extends AppController {

	
	//public $uses = array('Admin','User', 'Module', 'AssignModule','AssignCoach','Office','Patient');
	public $uses = array('Admin','User', 'Office','Patient');
	//public $uses = array('Admin','User');


	var $helpers = array('Html', 'Form','Js' => array('Jquery'), 'Custom');

    public $components = array('Auth'=>array('authorize'=>array('Controller')),'Session','Email','Common','RememberMe','Upload');
	public $allowedActions =array('admin_forgot_password','admin_login','admin_logout', 'admin_reset_password','admin_login2');


	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow($this->allowedActions);
	}

	protected function _setCookie($options = array(), $cookieKey = 'rememberMe') {
		$this->RememberMe->settings['cookieKey'] = $cookieKey;
		$this->RememberMe->configureCookie($options);
		$this->RememberMe->setCookie();
	}

	public function isAuthorized($user=null){
		$userDeatils = $this->User->find('first', ['conditions' => array('User.username' =>@$user['User']['username'])]);
		if(!empty($userDeatils)){
			if($userDeatils['User']['user_type']==='Admin' || $userDeatils['User']['user_type']==='SupportSuperAdmin' || $userDeatils['User']['user_type']==='RepAdmin' || $userDeatils['User']['user_type']==='SuperRepAdmin'){
				return true;
			}else if($userDeatils['Office']['status']==1){
				return true;
			}else {
				return false;
			}
		}else{
			return true;
		}
	  /*   parent::beforeFilter();
		if(isset($user['user_type']) && (($user['user_type'] == 'Admin') || ($user['user_type'] == 'Subadmin') || ($user['user_type'] == 'Staffuser')) && isset($this->request->prefix) && ($this->request->prefix == 'admin')){
			return true;
		}else{
			$this->redirect($this->referer());
		} */
    }


    public function admin_login2(){
    	echo "page loaded";die();
    }
	//Admin Login
	public function admin_login(){

		    if($this->Auth->loggedIn()){
				$this->redirect(array('controller'=>'dashboards','action'=>'admin_index'));
			}
			if($this->request->is(array('post','put'))){ //pr($this->request->data['User']); die;
				$this->request->data['User']['g-recaptcha-response'] = $this->request->data['g-recaptcha-response'];
				if($this->request->data['g-recaptcha-response'] == ''){
						$captcha_validate = '<div class="error-message">' .__('Invalid Captcha') . '</div>';
						$this->set('capchaError',$captcha_validate);
				}else{
					if($this->User->validates()){
						if(!$this->isAuthorized($this->request->data)){
							$this->Session->setFlash(__('Your office status is inactive. Please contact to admin'),'message');
							return $this->redirect(array('action'=>'admin_login'));
						}
						if($this->Auth->login()){
							//Cache::delete('test_device_list', '_cake_model_');
							/* $user_name = $this->Auth->user('first_name');
							$profile_pic = $this->Auth->user('profile_pic');
							$user_id = $this->Auth->user('id');
							$this->Session->write('profile_pic',$profile_pic);
							$this->Session->write('username',$user_name);
							$this->Session->write('user_id',$user_id);
							$office_name = $this->Auth->user('Office.name');
							$this->Session->write('office_name',$office_name); */
							//Remember Me Cookie
							if (empty($this->request->data['User']['remember_me'])){
								$this->RememberMe->destroyCookie();
							} else {
								$this->_setCookie();
							}
							if(!empty(Configure::read('Config.language'))){
								$this->Session->write('Config.language', Configure::read('Config.language'));
							}else{
								$this->Session->write('Config.language', 'eng');
							}
							//$this->Session->setFlash(__('Welcome '.$user_name),'message');
							return $this->redirect($this->Auth->redirectUrl());
						}else{
							$this->Session->setFlash(__('Invalid Email/Password,try again.'),'message');
							return $this->redirect(array('action'=>'admin_login'));
						}
					}
			}
		}
		//session_write_close();
	}
	/*Logout*/
	function admin_logout(){
		$this->Session->destroy();
		$this->RememberMe->destroyCookie();
		$this->redirect($this->Auth->logout());
	}
	/*forgot password*/
	/*public function admin_forgot_password(){
		if($this->request->is(array('post','put'))){
			//print_r($this->request->data);die;
			$this->User->validator()->remove('username','unique');
			$this->User->set($this->request->data);
			if($this->User->validates()){
				$username = $this->data['User']['username'];
				$user = array();
				$this->User->unbindModel(array('hasOne' => array('Office')), true);
				$user=$this->User->find('first',array('conditions'=>array('username'=> $username)));
				if(!empty($user)){
					$password = $this->generateRandomString();
					$this->User->id = $user['User']['id'];
					$this->User->saveField('password', $password);

					$name = $user['User']['first_name'].' '.$user['User']['last_name'];
					$user['User']['username']=$user['User']['username'];
					$user['User']['custom_password']=$password;
					$subject = "Password Reset";
					$body ="Hi $name, <br/><br/>Your new paswword is:- ".$password;
					$body.="<br/><br/>Thanks & Regards,<br/>".'UnityEye';
					try{
						$Email = new CakeEmail();
						$Email->viewVars($user['User']);
						$Email->template('forgot_password');
						$Email->from(array(FROM_EMAIL=>'UnityEye'));
						$Email->to($user['User']['email']);
						$Email->subject($subject);
						$Email->emailFormat('html');
						$Email->send();
					}catch(Exception $e){

					}
					$this->Session->setFlash('The password has been sent to your email.','message',array('class' => 'message'));
                    $this->redirect(array('controller'=>'users','action'=>'admin_login'));
				}else{
					$this->Session->setFlash("Email doesn't exist.",'message',array('class' => 'message'));

				}
			}
        }
	} */
	/*forgot password*/
	public function admin_forgot_password(){
		if($this->request->is(array('post','put'))){
			//print_r($this->request->data);die;
			//$this->User->validator()->remove('email','unique');
			$this->User->validator()->remove('username','unique');
			$this->User->set($this->request->data);
			if($this->User->validates()){
				//$email = $this->data['User']['email'];
				$username = $this->data['User']['username'];
				$user = array();
				$this->User->unbindModel(array('hasOne' => array('Office')), true);
				//$user=$this->User->find('first',array('conditions'=>array('email'=> $email)));
				$user=$this->User->find('first',array('conditions'=>array('username'=> $username)));

				if(!empty($user)){
					//$password = $this->generateRandomString();
					$resettoken = $this->generateRandomString().time();
					$resetLink = Router::Url(['controller' => 'users', 'action' => 'admin_reset_password'], true) . '/' . $resettoken;
					//$resetLink = $this->Html->link('Click here to change your password', array('controller' => 'users', 'action' => 'admin_reset_password',$resettoken));
					$this->User->id = $user['User']['id'];
					$this->User->saveField('reset_token', $resettoken);

					$name = $user['User']['first_name'].' '.$user['User']['last_name'];
					$user['User']['username']=$user['User']['username'];
					//$user['User']['custom_password']=$password;
					$user['User']['reset_link']=$resetLink;
					$subject = "Click here to reset your password";
					//$body ="Hi $name, <br/><br/>Click here to change your password:- ".$password;
					//$body.="<br/><br/>Thanks & Regards,<br/>".'UnityEye';

					try{
						$Email = new CakeEmail();
						$Email->config('smtp');
						$Email->viewVars($user['User']);
						$Email->template('forgot_password');
						$Email->from(array(FROM_EMAIL=>'MMD'));
						$Email->to($user['User']['email']);
						//$Email->to('madan@braintechnosys.com');
						$Email->subject($subject);
						$Email->emailFormat('html');
						$Email->send();
					}catch(Exception $e){
						/*echo "<pre>";
						print_r($e->getMessage());
						die;*/
					}
					$this->Session->setFlash('The password link has been sent to your email.','message',array('class' => 'message'));
                    $this->redirect(array('controller'=>'users','action'=>'admin_login'));

				}else{
					$this->Session->setFlash("Username doesn't exist.",'message',array('class' => 'message'));

				}
			}
        }
	}
	/* Reset Password */
	function admin_reset_password($reset_string=null){
		//echo $reset_string; die;
		$this->set('reset_string', $reset_string);
		if($this->request->is(array('post','put'))){
			//pr($this->request->data);die;
			//$this->User->validator()->remove('email','unique');
			$this->User->validator()->remove('username','unique');
			$this->User->set($this->request->data);
			if(strcmp($this->request->data['User']['password'], $this->request->data['User']['confirm_password'])==0){
				//$email = $this->data['User']['email'];
				//$username = $this->data['User']['username'];
				$user = array();
				$this->User->unbindModel(array('hasOne' => array('Office')), true);
				//$user=$this->User->find('first',array('conditions'=>array('email'=> $email)));
				$user=$this->User->find('first',array('conditions'=>array('reset_token'=> $reset_string)));
				//pr($user); die;
				if(!empty($user)){
					$password = $this->request->data['User']['password'];
					$this->User->id = $user['User']['id'];
					$this->User->password = $password;
					$this->User->reset_token = null;
					$this->User->saveField('password', $password);
					$this->User->saveField('reset_token', null);
					$this->Session->setFlash('The password has been sent to your email.','message',array('class' => 'message'));
					$this->Session->destroy();
                    $this->redirect(array('controller'=>'users','action'=>'admin_login'));
				}else{
					$this->Session->setFlash("Username doesn't exist.",'message',array('class' => 'message'));
				}
			}else{
				$this->Session->setFlash("Both password should be equal.",'message',array('class' => 'message'));
			}
        }
	}


	/*Change password*/
	function admin_change_password(){
		$this->layout='admin_default';
		$uid = $this->Auth->user('id');

		$this->User->id = $uid;
		$user = $this->User->read();
		$this->set('user',$user);

		if($this->request->isPost())
		{
			$userdata = $this->User->findById($uid);
			if(!empty($this->data['User']['oldpassword'])&& !empty($this->data['User']['password']) && !empty($this->data['User']['confirm_password'])){

				if(($this->data['User']['password']!=$this->data['User']['confirm_password']) || (($this->BlowFish->check( $this->data['User']['oldpassword'] , $userdata['User']['password'] )) == false) || ($this->data['User']['oldpassword'] == $this->data['User']['password']))
                {
					if($this->data['User']['oldpassword'] == $this->data['User']['password'])
					 $this->User->validationErrors['password'] = "New password cann't be same as old.";

					if($this->data['User']['password']!=$this->data['User']['confirm_password'])
					   $this->User->validationErrors['confirm_password'] = "Password's doesn't match.";

					if(($this->BlowFish->check( $this->data['User']['oldpassword'] , $userdata['User']['password'] )) == false)
						$this->User->validationErrors['oldpassword'] = "Old Password not correct.";
                }
                else
				{
					$this->User->create();
					$this->User->id = $this->Auth->user('id');
					$password = $this->data['User']['password'];

					if($this->User->saveField('password', $password)){
						$this->Session->setFlash('Your password changed successfully.','message',array('class' => 'message'));
						return $this->redirect(array('controller'=>'dashboards','action' => 'admin_index'));
					}
					else{

						$this->Session->setFlash('Some error occurred.','message',array('class' => 'message'));
					}
				}
			}
			else{

				if(empty($this->data['User']['oldpassword']))
					$this->User->validationErrors['oldpassword'] = 'Please enter old password.';

				if(empty($this->data['User']['password']))
					$this->User->validationErrors['password'] = 'Please enter new password.';

				if(empty($this->data['User']['confirm_password']))
					$this->User->validationErrors['confirm_password'] = 'Please enter confirm password.';
			}

		}
	}


	/*Add subAdmin*/
	public function admin_addSubAdmin(){
		if($this->Session->read('Auth.Admin.user_type')=='Admin'){
		$this->loadModel('Office');
		$Admin = $this->Session->read('Auth.Admin');
		if($this->request->is('post')){
			$password = $this->request->data['User']['password'];
			$confirm_pass = $this->request->data['User']['confirm_pass'];
			unset($this->request->data['User']['confirm_pass']);
			if($password == $confirm_pass){
				$this->request->data['User']['user_type']='Subadmin';
				$this->request->data['User']['created_by'] = $Admin['id'];

				if(!empty($this->request->data['User']['office_id'])) {
					$office_assign = $this->Office->findById($this->request->data['User']['office_id']);
					if($office_assign['Office']['assign_status'] == 1){
						$this->User->validationErrors['office_id'] = array("This Office is assign to other user please select another one.");
					}
				}
				if(!isset($this->User->validationErrors['office_id'])){

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

						$this->Office->id = $this->request->data['User']['office_id'];
						$this->Office->saveField('assign_status',1);
						$this->Session->setFlash("New sub admin has been created successfully.",'message',array('class' => 'message'));
						$this->redirect(array('controller'=>'users','action'=>'admin_subadmin_listing'));
					}
				}
			} else {
				$this->User->validationErrors['confirm_pass'] = array("Password and confirm mismatch.");
			}
		}
		}else{
            $this->redirect(WWW_BASE.'admin/dashboards/index');
        }
	}

//SubAdmin Listing
	public function admin_super_subadmin_listing(){
		if($this->Session->read('Auth.Admin.user_type')=='Admin'){
		$conditions = array('User.is_delete'=>0);
		$conditions['AND'][] = array('User.user_type'=>'SuperSubadmin');
		//$conditions['OR'][]= array('User.user_type'=>'OfficeAdmin');
	  	//$datas=$this->User->find('all',array('conditions'=>,'order'=>array('User.id DESC')));
		//$this->set(compact('datas'));

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
	/*Edit SubAdmin*/
	public function admin_edit_super($id=null){
		if($this->Session->read('Auth.Admin.user_type')=='Admin'){
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
					$this->User->validationErrors['office_id'] = array("You can not change Office because Staff or Patients assigned to this download Admin.");
				}//end
				else if(($data['User']['office_id']!=$office_assign['Office']['id']) && $office_assign['Office']['assign_status_new'] == 1){

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
					$this->Session->setFlash('Download admin has been updated successfully.','message',array('class' => 'message'));
					$this->redirect(array('controller'=>'users','action'=>'admin_super_subadmin_listing'));
				}
			}else{
				$this->request->data = $data;
			}
		} else {
			$this->request->data = $data;
		}
		}else{
            $this->redirect(WWW_BASE.'admin/dashboards/index');
        }
	}

	/*Add SupersubAdmin*/
	public function admin_addSuperSubAdmin(){
		if($this->Session->read('Auth.Admin.user_type')=='Admin'){
		$this->loadModel('Office');
		$Admin = $this->Session->read('Auth.Admin');
		if($this->request->is('post')){
			$password = $this->request->data['User']['password'];
			$confirm_pass = $this->request->data['User']['confirm_pass'];
			unset($this->request->data['User']['confirm_pass']);
			if($password == $confirm_pass){
				$this->request->data['User']['user_type']='SuperSubadmin';
				$this->request->data['User']['created_by'] = $Admin['id'];

				if(!empty($this->request->data['User']['office_id'])) {
					$office_assign = $this->Office->findById($this->request->data['User']['office_id']);
					if($office_assign['Office']['assign_status_new'] == 1){
						$this->User->validationErrors['office_id'] = array("This Office is assign to other user please select another one.");
					}
				}
				if(!isset($this->User->validationErrors['office_id'])){

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
						$this->Office->saveField('assign_status_new',1);
						$this->Session->setFlash("New download admin has been created successfully.",'message',array('class' => 'message'));
						$this->redirect(array('controller'=>'users','action'=>'admin_super_subadmin_listing'));
					}
				}
			} else {
				$this->User->validationErrors['confirm_pass'] = array("Password and confirm mismatch.");
			}
		}
		}else{
            $this->redirect(WWW_BASE.'admin/dashboards/index');
        }
	}

	//RepAdmin Listing
	public function admin_rep_admin_listing(){
		if($this->Session->read('Auth.Admin.user_type')=='Admin'){
		$conditions = array('User.is_delete'=>0);
		$conditions['OR'][] = array('User.user_type'=>'SupportSuperAdmin');
		$conditions['OR'][] = array('User.user_type'=>'RepAdmin');
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
	/*Add RepAdmin*/
	public function admin_addRepAdmin(){
		if($this->Session->read('Auth.Admin.user_type')=='Admin'){
		$this->loadModel('Office');
		$Admin = $this->Session->read('Auth.Admin');
		if($this->request->is('post')){
			$password = $this->request->data['User']['password'];
			$confirm_pass = $this->request->data['User']['confirm_pass'];
			unset($this->request->data['User']['confirm_pass']);
			if($password == $confirm_pass){
				//$this->request->data['User']['user_type']='RepAdmin';
				$this->request->data['User']['created_by'] = $Admin['id'];
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
						$this->Session->setFlash("New rep admin has been created successfully.",'message',array('class' => 'message'));
						$this->redirect(array('controller'=>'users','action'=>'admin_rep_admin_listing'));
					}

			} else {
				$this->User->validationErrors['confirm_pass'] = array("Password and confirm mismatch.");
			}
		}
		}else{
            $this->redirect(WWW_BASE.'admin/dashboards/index');
        }
	}

	/*Edit RepAdmin*/
	public function admin_edit_rep($id=null){
		if($this->Session->read('Auth.Admin.user_type')=='Admin'){
		$data=$this->User->find('first',array('conditions'=>array('User.id'=>$id)));
		unset($data['User']['password']);
		$data['User']['dob'] = date('m/d/Y', strtotime($data['User']['dob']));
		$data['User']['practice_id']=explode(',',$data['User']['practice_id']);
		$this->set(compact('data'));
		if($this->request->is(array('post','put'))){
			if(empty($this->request->data['User']['password'])){
				unset($this->request->data['User']['password']);
			}
				$username=$this->User->field('username');
				if($username==$this->request->data['User']['username']){
					$this->User->validator()->remove('username', 'unique');
				}
			if(isset($this->request->data['User']['profile_pic']['name'])&&(!empty($this->request->data['User']['profile_pic']['name']))){
				$profile_pic=time().$this->request->data['User']['profile_pic']['name'];
				$image_type=strtolower(substr($profile_pic,strrpos($profile_pic,'.')+1));
				$uploadFiles = $this->request->data['User']['profile_pic'];
				$fileName = $profile_pic;
				$upload_path=getcwd()."/app/webroot/img/uploads/";
				$data12 = array('type' => 'resize', 'size' => array(150, 150), 'output' => $image_type, 'quality' => 100);
				$status = $this->Upload->upload($uploadFiles,$upload_path, $fileName, $data12, null);
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
					$this->Session->setFlash('Rep admin has been updated successfully.','message',array('class' => 'message'));
					$this->redirect(array('controller'=>'users','action'=>'admin_rep_admin_listing'));
				}
		} else {
			$this->request->data = $data;
		}
		}else{
            $this->redirect(WWW_BASE.'admin/dashboards/index');
        }
	}


	//SubAdmin Listing
	public function admin_subadmin_listing(){
		if($this->Session->read('Auth.Admin.user_type')=='Admin'){
		$conditions = array('User.user_type'=>'Subadmin','User.is_delete'=>0);
		//$datas=$this->User->find('all',array('conditions'=>,'order'=>array('User.id DESC')));
		//$this->set(compact('datas'));

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
		$this->set(compact('datas'));
		}else{
            $this->redirect(WWW_BASE.'admin/dashboards/index');
        } 
	}


	/*Delete subAdmin*/
	public function admin_user_delete($id=null){
		$this->autoRender = false;
		if($id){
			$check_user=$this->User->find('first',array(
				'conditions'=>array('User.id'=>$id),
				'fields'=>array('User.id,User.user_type','User.office_id')
			));
			if(!empty($check_user)){
				$office_id=$check_user['User']['office_id'];
				/*update status of subadmin */
				$delete_subadmin['User']['id']=$id;
				$delete_subadmin['User']['is_delete']=1;
				if($this->User->save($delete_subadmin)){
					/*free office*/
					$this->Office->id=$office_id;
					$this->Office->saveField('assign_status',0);
					//end

					/*delete all staff created by subadmin(manage status)*/
					$all_staff_ids=$this->User->find('list',array('conditions'=>array('User.created_by'=>$id),'fields'=>array('User.id')));

					if(!empty($all_staff_ids)){
						$this->User->updateAll(
							array('User.is_delete'=>1),
							array('User.id' => $all_staff_ids)
						);
					}
					//end

					/*delete all patients of subadmin and  staff(manages status)*/
					$all_staff_ids=implode(",",$all_staff_ids);
					$all_staff_ids=$id.','.$all_staff_ids;
					$all_staff_ids=explode(',',$all_staff_ids);
					$all_patients_ids=$this->Patient->find('list',array('conditions'=>array('Patient.user_id'=>$all_staff_ids),'fields'=>array('Patient.id')));
					if(!empty($all_patients_ids)){
						$this->Patient->updateAll(
							array('Patient.is_delete'=>1),
							array('Patient.id' => $all_patients_ids)
						);
					}
					//end

					$this->Session->setFlash('Sub Admin  and its associated staff,patients has been deleted successfully.','message',array('class' => 'message'));
					$this->redirect(array('controller'=>'users','action'=>'admin_subadmin_listing'));
				}else{
					$this->Session->setFlash('Oops! There is some problem in deleting sub admin.','message',array('class' => 'message'));
					$this->redirect(array('controller'=>'users','action'=>'admin_subadmin_listing'));
				}
			}else{
				$this->Session->setFlash('Invalid sub admin.','message',array('class' => 'message'));
				$this->redirect(array('controller'=>'users','action'=>'admin_subadmin_listing'));
			}
		}
	}


	/*Edit SubAdmin*/
	public function admin_edit($id=null){
		if($this->Session->read('Auth.Admin.user_type')=='Admin'){
		$data=$this->User->find('first',array('conditions'=>array('User.id'=>$id)));
		unset($data['User']['password']);
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
				$upload_path=getcwd()."/app/webroot/img/uploads/";
				$data12 = array('type' => 'resize', 'size' => array(150, 150), 'output' => $image_type, 'quality' => 100);
				$status = $this->Upload->upload($uploadFiles,$upload_path, $fileName, $data12, null);
				$this->request->data['User']['profile_pic']=$profile_pic;

			}else{
				$this->User->id=$this->request->data['User']['id'];
				$profile_pic=$this->User->field('profile_pic');
				$this->request->data['User']['profile_pic']=$profile_pic;
			}

			if(!isset($this->User->validationErrors['office_id'])){

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
					$this->Session->setFlash('Sub admin has been updated successfully.','message',array('class' => 'message'));
					$this->redirect(array('controller'=>'users','action'=>'admin_subadmin_listing'));
				}
			}else{
				$this->request->data = $data;
			}
		} else {
			$this->request->data = $data;
		}
		}else{
            $this->redirect(WWW_BASE.'admin/dashboards/index');
        }
	}

	/*SubAdmin View*/
	public function admin_subAdminView($id=null){
		$this->layout=false;
		$data=$this->User->find('first',array('conditions'=>array('User.id'=>$id)));
		$this->set(compact('data'));
	}

	public function admin_viewAdmin($id=null){
		$this->layout=false;
		$data=$this->User->find('first',array('conditions'=>array('User.id'=>$id)));
		$this->set(compact('data'));
	}



	/*Edit profile*/
	function admin_edit_profile(){
		$Admin = $this->Auth->user('id');
		$data = $this->User->find('first',array('conditions'=>array('User.id'=>$Admin)));
		$this->set(compact('data'));
		if($this->request->is(array('post','put'))){
			if(isset($this->request->data['User']['profile_pic']['name'])&&(!empty($this->request->data['User']['profile_pic']['name']))){
				$profile_pic=time().$this->request->data['User']['profile_pic']['name'];
				$image_type=strtolower(substr($profile_pic,strrpos($profile_pic,'.')+1));
				$uploadFiles = $this->request->data['User']['profile_pic'];
				$fileName = $profile_pic;
				$upload_path="img/uploads/";

				$data12 = array('type'=>'resize','size' =>array(150, 150),'output'=>$image_type, 'quality' => 100);
				$status = $this->Upload->upload($uploadFiles,$upload_path,$fileName, $data12, null);
				$this->request->data['User']['profile_pic']=$profile_pic;
				//$this->Auth->User['Admin.profile_pic']=$profile_pic;
				$this->Session->write('Auth.Admin.profile_pic',$profile_pic);

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
							$this->Session->write('Auth.Admin.profile_pic',$profile_pic);
						}
					}
				}

				$this->Session->write('Auth.Admin.first_name',$this->request->data['User']['first_name']);
				$this->Session->setFlash('Profile has been updated successfully.','message',array('class' => 'message'));
				$this->redirect(array('controller'=>'dashboards','action'=>'index'));
			}
		} else {
			$this->request->data = $data;
		}
	}

	#Admin module started-----------------------------------

	public function admin_add_admin(){
		if($this->Session->read('Auth.Admin.user_type') !='Admin'){
			$this->redirect($this->referer());
		}
		$this->loadModel('Office');
		$Admin = $this->Session->read('Auth.Admin');
		if($this->request->is('post')){
			$password = $this->request->data['User']['password'];
			$confirm_pass = $this->request->data['User']['confirm_pass'];
			unset($this->request->data['User']['confirm_pass']);
			if($password == $confirm_pass){
				$this->request->data['User']['user_type']='Admin';
				$this->request->data['User']['created_by'] = $Admin['id'];

					if(isset($this->request->data['User']['profile_pic']['name'])&&(!empty($this->request->data['User']['profile_pic']['name']))){

						$profile_pic=time().$this->request->data['User']['profile_pic']['name'];
						$image_type=strtolower(substr($profile_pic,strrpos($profile_pic,'.')+1));
						$uploadFiles = $this->request->data['User']['profile_pic'];
						$fileName = $profile_pic;
						$upload_path=getcwd()."/app/webroot/img/uploads/";

						$data12 = array('type' => 'resize', 'size' => array(150, 150), 'output' => $image_type, 'quality' => 100);
						$status = $this->Upload->upload($uploadFiles, $upload_path, $fileName, $data12, null);
						//pr($this->request->data);die;
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
						$this->Session->setFlash("New admin has been created successfully.",'message',array('class' => 'message'));
						$this->redirect(array('controller'=>'users','action'=>'admin_listing_admin'));
					}
			} else {
				$this->User->validationErrors['confirm_pass'] = array("Password and confirm mismatch.");
			}
		}
	}

	public function admin_edit_admin($id=null){
		if($this->Session->read('Auth.Admin.user_type') == 'Admin'){
		if($this->Session->read('Auth.Admin.id')!=1 && $id==1){
			$this->Session->setFlash("You don\'t have permission to delete this admin.",'message',array('class' => 'message'));
			$this->redirect($this->referer());
		}
		$data=$this->User->find('first',array('conditions'=>array('User.id'=>$id)));
		$data['User']['dob'] = date('m/d/Y', strtotime($data['User']['dob']));
		$data['User']['practice_id']=explode(',',$data['User']['practice_id']);
		$this->set(compact('data'));
		if($this->request->is(array('post','put'))){

			if(isset($this->request->data['User']['profile_pic']['name'])&&(!empty($this->request->data['User']['profile_pic']['name']))){
				$profile_pic=time().$this->request->data['User']['profile_pic']['name'];
				$image_type=strtolower(substr($profile_pic,strrpos($profile_pic,'.')+1));
				$uploadFiles = $this->request->data['User']['profile_pic'];
				$fileName = $profile_pic;
				$upload_path=getcwd()."/app/webroot/img/uploads/";
				$data12 = array('type' => 'resize', 'size' => array(150, 150), 'output' => $image_type, 'quality' => 100);
				$status = $this->Upload->upload($uploadFiles,$upload_path, $fileName, $data12, null);
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
					$this->Session->setFlash('Admin has been updated successfully.','message',array('class' => 'message'));
					$this->redirect(array('controller'=>'users','action'=>'admin_listing_admin'));
				}else{
					$this->request->data = $data;
				}

		} else {
			$this->request->data = $data;
		}
		}else{
            $this->redirect(WWW_BASE.'admin/dashboards/index');
        } 
	}

	public function admin_listing_admin(){

		if($this->Session->read('Auth.Admin.user_type')!='Admin'){
			$this->redirect($this->referer());
		}
		$conditions = array('User.user_type'=>'Admin','User.is_delete'=>0);
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
		$this->set(compact('datas'));
	}

	public function admin_delete_admin($id=null){
		if($this->Session->read('Auth.Admin.id')!=1 && $id==1){
			$this->Session->setFlash("You don\'t have permission to delete this admin.",'message',array('class' => 'message'));
			$this->redirect($this->referer());
		}else{
			$this->User->id = $id;
			$this->User->saveField('is_delete','1');
			$this->Session->setFlash("Admin has been deleted successfully.",'message',array('class' => 'message'));
		}
		$this->redirect($this->referer());

	}
	public function admin_clear_consent($id=null){
		//echo $id; die;
		$this->User->updateAll(
									array('User.first_consent' => NULL),
									array('User.id' => $id)
								);
		//$this->Session->setFlash("Consent Cleard successfully.",'message',array('class' => 'message'));
		$this->redirect($this->referer());
	}
}

?>
