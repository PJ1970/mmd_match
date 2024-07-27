<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController','Controller');
App::uses('CakeEmail','Network/Email');
App::import('Controller','ChatApi');
/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class OfficesController extends AppController {
	public $uses = array('Admin','User', 'Module', 'AssignModule','AssignCoach','Office','Patient','Officereport','Test','OfficeReportBackup','Pointdata','Language','Officelanguage');
			
	var $helpers = array('Html', 'Form','Js' => array('Jquery'), 'Custom');

    public $components = array('Auth'=>array('authorize'=>array('Controller')),'Session','Email','Common','RememberMe','Upload');
	public $allowedActions =array('admin_forgot_password','admin_login','admin_logout');
    
	
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
		if(isset($user['user_type']) && (($user['user_type'] == 'Admin') || ($user['user_type'] == 'Subadmin') || ($user['user_type'] == 'Staffuser') || ($user['user_type'] == 'RepAdmin')) && isset($this->request->prefix) && ($this->request->prefix == 'admin')){
			return true;
		}else{
			$this->redirect($this->referer());
		}
    }
	//This function for listing of offices
	function admin_manage_office(){
	if($this->Session->read('Auth.Admin.user_type') == 'Admin'){  
		$conditions = array('Office.is_delete' =>'0');
		if(!empty($this->request->query['search'])){
			//echo "yes";die;
			$search = strtolower(trim($this->request->query['search']));
			$conditions['OR'][] = array('Lower(Office.name) like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(Office.email) like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(Office.address) like'=>'%'.$search.'%');
			$this->set(compact('search'));
		}
		
		$params = array(
			'conditions' => $conditions,
			'limit'=>10,
			'order'=>array('Office.id'=>'DESC')
		);
		
		$this->paginate=array('Office'=>$params);
		$datas = $this->paginate('Office'); 
		$this->set(compact('datas'));
		}else{
			$this->redirect(WWW_BASE.'admin/dashboards/index');
		}
	}

	function admin_office_report(){  
	if($this->Session->read('Auth.Admin.user_type') == 'Admin'){  
		$conditions = array('Office.is_delete' =>'0');
		if($this->Session->read('Auth.Admin.user_type') == 'RepAdmin'){
			$conditions[] = array('Office.rep_admin' =>$this->Session->read('Auth.Admin.id'));
		}
		if(!empty($this->request->query['search'])){
			//echo "yes";die;
			$search = strtolower(trim($this->request->query['search']));
			$conditions['OR'][] = array('Lower(Office.name) like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(Office.email) like'=> '%'.$search.'%');
			$conditions['OR'][] = array('Lower(Office.address) like'=>'%'.$search.'%');
			$this->set(compact('search'));
		}
		$last_sunday= date('Y-m-d 00:00:00',strtotime('last sunday'));
		$second_last_sunday=date('Y-m-d',strtotime('last sunday -7 days')); 
		$three_month_back = date('Y-m-d',strtotime(date('Y-m-d').' -3 months'));
		$this->Office->unbindModel(array('hasMany' => array('Officereport')));

		$this->Office->bindModel(
						array(
							'hasMany' => array(
								'TestDevice' => array(
									'className' => 'TestDevice',
									'foreignKey' => 'office_id',
								)
							)
						)
					);
		
		$this->Office->virtualFields['total_test_current_week'] ='select (select count(pointdatas.id) from mmd_pointdatas as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where mmd_users.office_id =Office.id  AND pointdatas.created >= "'.$last_sunday.'") + (select count(pointdatas.id) from mmd_dark_adaptions as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where mmd_users.office_id =Office.id AND pointdatas.created >= "'.$last_sunday.'") + (select count(pointdatas.id) from mmd_pointdatas as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where pointdatas.staff_id =Office.id AND pointdatas.created >= "'.$last_sunday.'") + (select count(pointdatas.id) from mmd_dark_adaptions as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where pointdatas.staff_id =Office.id AND pointdatas.created >= "'.$last_sunday.'" )';
	 	$this->Office->virtualFields['total_test_last_week'] ='select (select count(pointdatas.id) from mmd_pointdatas as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where mmd_users.office_id =Office.id AND pointdatas.created >= "'.$second_last_sunday.'"  AND pointdatas.created < "'.$last_sunday.'" ) + (  select count(pointdatas.id) from mmd_dark_adaptions as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where mmd_users.office_id =Office.id AND pointdatas.created >= "'.$second_last_sunday.'"  AND pointdatas.created < "'.$last_sunday.'") + (select count(pointdatas.id) from mmd_pointdatas as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where pointdatas.staff_id =Office.id AND pointdatas.created >= "'.$second_last_sunday.'"  AND pointdatas.created < "'.$last_sunday.'" ) + (  select count(pointdatas.id) from mmd_dark_adaptions as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where pointdatas.staff_id =Office.id AND pointdatas.created >= "'.$second_last_sunday.'"  AND pointdatas.created < "'.$last_sunday.'")';
	 	$this->Office->virtualFields['total_test_three_month'] ='select (select count(pointdatas.id) from mmd_pointdatas as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where mmd_users.office_id =Office.id AND pointdatas.created >= "'.$three_month_back.'" ) + (  select count(pointdatas.id) from mmd_dark_adaptions as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where mmd_users.office_id =Office.id AND pointdatas.created >= "'.$three_month_back.'") + (select count(pointdatas.id) from mmd_pointdatas as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where pointdatas.staff_id =Office.id AND pointdatas.created >= "'.$three_month_back.'" ) + (  select count(pointdatas.id) from mmd_dark_adaptions as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where pointdatas.staff_id =Office.id AND pointdatas.created >= "'.$three_month_back.'")';



//$this->Office->virtualFields['total_test_current_week'] ='select (select count(pointdatas.id) from mmd_pointdatas as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where pointdatas.staff_id =Office.id AND pointdatas.created >= "'.$last_sunday.'") + (select count(pointdatas.id) from mmd_dark_adaptions as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where pointdatas.staff_id =Office.id AND pointdatas.created >= "'.$last_sunday.'")';
	   //	$this->Office->virtualFields['total_test_last_week'] ='select (select count(pointdatas.id) from mmd_pointdatas as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where pointdatas.staff_id =Office.id AND pointdatas.created >= "'.$second_last_sunday.'"  AND pointdatas.created < "'.$last_sunday.'" ) + (  select count(pointdatas.id) from mmd_dark_adaptions as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where pointdatas.staff_id =Office.id AND pointdatas.created >= "'.$second_last_sunday.'"  AND pointdatas.created < "'.$last_sunday.'")';
	  // 	$this->Office->virtualFields['total_test_three_month'] ='select (select count(pointdatas.id) from mmd_pointdatas as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where pointdatas.staff_id =Office.id AND pointdatas.created >= "'.$three_month_back.'" ) + (  select count(pointdatas.id) from mmd_dark_adaptions as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where pointdatas.staff_id =Office.id AND pointdatas.created >= "'.$three_month_back.'")';


		$params = array(
			'conditions' => $conditions,
			'limit'=>10,
			'order'=>array('Office.total_test_current_week'=>'DESC')
		);
	 	 
		$this->paginate=array('Office'=>$params);
		$datas = $this->paginate('Office');
		 
		$this->set(compact('datas'));
	}else{
			$this->redirect(WWW_BASE.'admin/dashboards/index');
		}	
	}
	
	
	
	//This function for adding offices
	public function admin_add($id=null){
		if($this->Session->read('Auth.Admin.user_type') == 'Admin'){  
		$user_credit_total = 0;
		$editData = "";
		$test_c = $this->Test->find('list',array('fields'=>array('id','name')),array( 'conditions' =>  array('Test.is_delete' =>'0')));
		//pr($test_c);die;
		$language = $this->Language->find('list',array('fields'=>array('id','name')), array('conditions' => array('Language.is_delete'=>0))); 
		if($id){
		$editData = $this->Office->find('first',array('conditions'=>array('Office.id'=>$id)));
		$editData['Office']['password']=$editData['Office']['password2'];
		$password=$editData['Office']['password2'];
		$user_c = $this->User->find('all',array( 'conditions' =>  array('User.office_id' =>$id)));
		if(!empty($user_c)){
			foreach($user_c as $c_value){
				$user_credit_total = $user_credit_total+$c_value['User']['credits'];
				//pr($c_value);die;
			}
		}else{
			$user_credit_total = 0;
		}
		//pr($user_c);die;
		}else{
		    $password='';
		}
		if($this->request->is(array('post','put'))){

			unset($this->request->data['Office']['left_credits']);
			$this->request->data['Officereport'] = Hash::remove($this->request->data['Officereport'], '{n}[office_report=0]');
			
			$this->request->data['Officelanguage'] = Hash::remove($this->request->data['Officelanguage'], '{n}[language_id=0]');
				if(empty($this->request->data['Office']['email'])){
					$this->Office->validator()->remove('email');
				}
				if($this->request->data['Office']['payable']=='no'){
					$this->Office->validator()->remove('monthly_package');
					$this->request->data['Office']['monthly_package'] = '';
				}
				if($this->request->data['Office']['payable']=='yes'){
					$this->request->data['Office']['restrict'] = 'non-restrict';
				}
				if(!empty($this->request->data['Office']['password2'])){
					$this->request->data['Office']['password2']=trim(preg_replace('/\s\s+/', '', $this->request->data['Office']['password2']));
				}

				if($id){
					$this->request->data['Office']['password']=$this->request->data['Office']['password2'];
				   unset($this->request->data['Office']['password']);
					$deletedata = $this->Officereport->deleteAll(array('Officereport.office_id'=>$id),false);
					//pr($deletedata);die;
					//$this->Officelanguage->deleteAll(array('Officelanguage.office_id'=>$id),false);
					$old_language = $this->Officelanguage->find('list',array( 'conditions' =>  array('Officelanguage.office_id' =>$id),'fields'=>array('id','language_id')));
				    foreach($old_language as $key => $value){
				        if(!empty($this->request->data['Officelanguage'])){
    				        foreach($this->request->data['Officelanguage'] as $key2 =>$value2){
    				            if($value==$value2['language_id']){
    				                unset($old_language[$key]);
    				                unset($this->request->data['Officelanguage'][$key2]);
    				            }
    				        }
				        }
				    }
				    if(!empty($old_language)){
				        $this->Officelanguage->deleteAll(array('Officelanguage.office_id'=>$id, 'Officelanguage.language_id'=>$old_language),false);   
				    }
				}
				 
				if(isset($this->request->data['Office']['office_pic']['name'])&&(!empty($this->request->data['Office']['office_pic']['name']))){
					$office_pic=time().$this->request->data['Office']['office_pic']['name'];
					$image_type=strtolower(substr($office_pic,strrpos($office_pic,'.')+1));
					  
					$uploadFiles = $this->request->data['Office']['office_pic'];
					$image_info = getimagesize($this->request->data['Office']['office_pic']);
					$fileName = $office_pic;
					$upload_path=getcwd()."/app/webroot/img/office/";
					
					$data12 = array('type' => 'resize', 'size' => array(150, 150), 'output' => $image_type, 'quality' => 100);
					
					$status = $this->Upload->upload($uploadFiles,$upload_path, $fileName, $data12, null);
					$this->request->data['Office']['office_pic']=$office_pic;
				}else{
					$this->Office->id=$this->request->data['Office']['id'];
					$office_pic=$this->Office->field('office_pic');
					$this->request->data['Office']['office_pic']=$office_pic;
				} 
				//echo "<pre>";
				if($this->request->data['Office']['rep_admin']==''){
					$this->request->data['Office']['rep_admin']=0;
				}
				$archiveTime = $this->request->data['Office']['weekdays']; 
				if($archiveTime == 0 || empty($archiveTime)){ 
					$this->Patient->updateAll(array('Patient.device_type' => NULL,'Patient.ihuunassigntime' => NULL,'Patient.progression_deatild' => NULL,'Patient.language' => NULL,'Patient.test_name_ihu' => NULL,'Patient.eye_type' => NULL),array('Patient.office_id'=>$id));
				}
				//pr($this->request->data);die();
				if($this->Office->saveAll($this->request->data)) {
					//echo "saved";
					 $office_id=$this->Office->id;  
					 $currentOrderAlert  		=  $this->Office->query("CREATE OR REPLACE VIEW mmd_pointdatas_".$office_id." AS  SELECT  `id`, `test_type_id`, `test_name`, `numpoints`, `color`, `backgroundcolor`, `stmsize`, `file`, `staff_id`, `patient_id`, `eye_select`, `baseline`, `is_delete`, `master_key`, `test_color_fg`, `test_color_bg`, `mean_dev`, `pattern_std`, `mean_sen`, `mean_def`, `pattern_std_hfa`, `loss_var`, `mean_std`, `psd_hfa_2`, `psd_hfa`, `vission_loss`, `false_p`, `false_n`, `false_f`, `threshold`, `strategy`, `ght`, `latitude`, `longitude`, `unique_id`, `version`, `diagnosys`, `age_group`, `device_id`, `office_id`, `source`, `stereopsis`, `created`  FROM   `mmd_pointdatas` WHERE mmd_pointdatas.staff_id in (SELECT id from mmd_users where office_id=".$office_id.")");

					  $currentOrderAlert  		=  $this->Office->query("CREATE OR REPLACE VIEW mmd_patients_".$office_id." AS  SELECT  `id`, `unique_id`, `user_id`, `first_name`,`middle_name`, `last_name`,`email`,`phone`,`id_number`,`office_id`,`dob`, `is_delete`,`merge_status`,`merge_date`,`delete_date`,`notes`,`status`,`archived_date`,`p_profilepic`, `od_left`,`od_right`, `os_left`,`os_right`,`race`,`created_date_utc`,`created`,`created_at_for_archive` from `mmd_patients` where `user_id` in (select `mmd_users`.`id` from `mmd_users` where `mmd_users`.`office_id`=".$office_id.")");

				    $staffuserAdmin=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id),'fields'=>array('User.id')));
    				$conditions['Pointdata.staff_id']=$staffuserAdmin;
    				$this->Pointdata->unbindModel(array('hasMany' => array('VfPointdata'), 'belongsTo' => array('User','Patient','Test')));
    				$data=$this->Pointdata->find('first',array('conditions'=>$conditions,'order' => 'Pointdata.id DESC')); 
				    	
				    $this->OfficeReportBackup->primaryKey = 'office_id';
        			$officereport = $this->OfficeReportBackup->find('first',array('conditions'=>array('OfficeReportBackup.office_id'=>$office_id))); 
        			$officereport['OfficeReportBackup']['office_id']=$office_id;
        			//if(!empty($officereport['id'])){
        				unset($officereport['OfficeReportBackup']['id']);
        			//}
        			$officereport['OfficeReportBackup']['last_backup']=(isset($data['Pointdata']))?$data['Pointdata']['id']:0; 
        			$this->OfficeReportBackup->save($officereport); 

					if(is_null($id)){
						$this->Session->setFlash('Office has been created successfully.','message',array('class'=>'message'));
					} else {
						
						$this->loadModel('AppConstant');
						$Admin = $this->Session->read('Auth.Admin');
						$role_constant = Configure::read('role_constant');
						//pr($Admin['user_type']);die;
						if(in_array($Admin['user_type'],$role_constant)){
							$status_1 = 1;
							$this->AppConstant->updateAll(array('AppConstant.is_update'=> "'".$status_1."'"),array('AppConstant.id'=>1));
							$this->loadModel('NewUserDevice');
							$new_user_device = $this->NewUserDevice->find('all');
							//pr($new_user_device);die;
							foreach($new_user_device as $key => $val){
								$device_token = $val['NewUserDevice']['device_token'];
								if(!empty($device_token) && $this->checkNotification($val['NewUserDevice']['device_id'])){
									//$res = $this->sendPushNotificationNewAdminDataUpdate($device_token);
									$res = $this->sendPushNotificationNewAdminDataUpdateV2($device_token);
								}
							}
						}
						$this->Session->setFlash("Office has been updated successfully.",'message',array('class' => 'message'));
					}
					//die();
					$this->redirect(array('controller'=>'offices','action'=>'admin_manage_office'));
				}
				//die();
				if($this->request->data['Office']['archive_status'] == 0){ 
					$conditions['Patient.status']= 	0;
					$conditions['Patient.office_id']= 	$id;
					$this->Patient->updateAll(array('Patient.status' => 1,'Patient.archived_date'=>null),array('Patient.office_id'=>$id));
				}
				if($this->request->data['Office']['archive_status'] == 1){
					$conditions['Patient.office_id']= 	$id;
					date_default_timezone_set("UTC");
		   			$updateDAte=date('Y-m-d H:i:s');
					$this->Patient->updateAll(array('Patient.status' => 1,'Patient.created_date_utc' => "'".$updateDAte."'"),array('Patient.office_id'=>$id));
				}
		}else{
			$this->request->data = $editData;
		}
		//pr($this->request->data);die;
		$this->set(compact('test_c','user_credit_total','password','language'));
	}else{
			$this->redirect('https://www.portal.micromedinc.com/admin/dashboards/index');
		}
	}
	
	//This function for adding offices
	public function admin_subedit($id=null){
		$user_credit_total = 0;
		$editData = "";
		$test_c = $this->Test->find('list',array('fields'=>array('id','name')),array( 'conditions' =>  array('Test.is_delete' =>'0')));
		//pr($test_c);die;
		if($id){
		$editData = $this->Office->find('first',array('conditions'=>array('Office.id'=>$id)));
		$language = $this->Language->find('list',array('fields'=>array('id','name')), array('conditions' => array('Language.is_delete'=>0))); 
		$user_c = $this->User->find('all',array( 'conditions' =>  array('User.office_id' =>$id)));
		if(!empty($user_c)){
			foreach($user_c as $c_value){
				$user_credit_total = $user_credit_total+$c_value['User']['credits'];
				//pr($c_value);die;
			}
		}else{
			$user_credit_total = 0;
		}
		//pr($user_c);die;
		}
		if($this->request->is(array('post','put'))){
			//pr($this->request->data); die;
			unset($this->request->data['Office']['left_credits']);
			//$this->request->data['Officereport'] = Hash::remove($this->request->data['Officereport'], '{n}[office_report=0]');
			//pr($this->request->data);die;
			$this->request->data['Officelanguage'] = Hash::remove($this->request->data['Officelanguage'], '{n}[language_id=0]');
				if(empty($this->request->data['Office']['email'])){
					$this->Office->validator()->remove('email');
				}
				if($this->request->data['Office']['archive_status'] == 0){
					//$conditions['Patient.status']= 	0;
					//$conditions['Patient.office_id']= 	$id;
					date_default_timezone_set("UTC");
	       			$updateDa=date('Y-m-d H:i:s');
					$this->Patient->updateAll(array('Patient.status' => 1,'Patient.archived_date'=>null,'Patient.created_date_utc'=>"'".$updateDa."'"),array('Patient.office_id'=>$id));
				}
				if($this->request->data['Office']['archive_status'] == 1){
					date_default_timezone_set("UTC");
	       			$updateDAtes=date('Y-m-d H:i:s');
	       			$archiveTime = $this->request->data['Office']['p_archived_date'];
					if(!empty($archiveTime || $archiveTime== null)){
						$archiveTime =$this->request->data['Office']['p_archived_date'];
					}else{
						$archiveTime = 30 ;
					}
	       			$last_date_start=date('Y-m-d H:i:s', strtotime("-".$archiveTime." Days"));
	       			if($conditions['Patient.created_date_utc <']=$last_date_start){
	       			$conditions['Patient.office_id']= 	$id;
	       				$this->Patient->updateAll(array('Patient.status' => 0,'Patient.archived_date' => "'".$updateDAtes."'",'Patient.created_date_utc' => "'".$updateDAtes."'"), array($conditions));
	       			}else{
						$this->Patient->updateAll(array('Patient.status' => 1,'Patient.created_date_utc' => "'".$updateDAtes."'",'Patient.delete_date' => "'".$updateDAtes."'"),array('Patient.office_id'=>$id,'Patient.is_delete'=>1));
					}
				}
				if($this->request->data['Office']['payable']=='no'){
					$this->Office->validator()->remove('monthly_package');
					$this->request->data['Office']['monthly_package'] = '';
				}
				if($this->request->data['Office']['payable']=='yes'){
					$this->request->data['Office']['restrict'] = 'non-restrict';
				}
				if($id){
					//$deletedata = $this->Officereport->deleteAll(array('Officereport.office_id'=>$id),false);
					//pr($deletedata);die;
					//$this->Officelanguage->deleteAll(array('Officelanguage.office_id'=>$id),false);
				}
				//pr($this->request->data);die;
				
                                
				if(isset($this->request->data['Office']['office_pic']['name'])&&(!empty($this->request->data['Office']['office_pic']['name']))){
                                    //echo "bbbb";
					$office_pic=time().$this->request->data['Office']['office_pic']['name'];
					$image_type=strtolower(substr($office_pic,strrpos($office_pic,'.')+1));
					
					$uploadFiles = $this->request->data['Office']['office_pic'];
					
					$fileName = $office_pic;
					$upload_path=getcwd()."/app/webroot/img/office/"; //@ON LIVE Need to check //getcwd()."/img/office/";//forlocal
					
					$data12 = array('type' => 'resize', 'size' => array(150, 150), 'output' => $image_type, 'quality' => 100);
					
					$status = $this->Upload->upload($uploadFiles,$upload_path, $fileName, $data12, null);
                                       // pr($status);
                                       // pr(array($uploadFiles,$upload_path, $fileName, $data12, null));
                                       // die;
					$this->request->data['Office']['office_pic']=$office_pic;
				}else{
                                    //echo "aaaa";
					$this->Office->id=$this->request->data['Office']['id'];
					$office_pic=$this->Office->field('office_pic');
					$this->request->data['Office']['office_pic']=$office_pic;
				} 
                                
                                //pr($this->request->data);  die;
                                
				if($this->Office->save($this->request->data)) {
					$old_language = $this->Officelanguage->find('list',array( 'conditions' =>  array('Officelanguage.office_id' =>$id),'fields'=>array('id','language_id')));
				    foreach($old_language as $key => $value){
				        if(!empty($this->request->data['Officelanguage'])){
    				        foreach($this->request->data['Officelanguage'] as $key2 =>$value2){
    				            if($value==$value2['language_id']){
    				                unset($old_language[$key]);
    				                unset($this->request->data['Officelanguage'][$key2]);
    				            }
    				        }
				        }
				    }
				    if(!empty($old_language)){
				        $this->Officelanguage->deleteAll(array('Officelanguage.office_id'=>$id, 'Officelanguage.language_id'=>$old_language),false);   
				    }
				    if(!empty($this->request->data['Officelanguage'])){
    					 foreach($this->request->data['Officelanguage'] as $key =>$value){
    					      
                        $datas['Officelanguage']['language_id']=$value['language_id'];
                        $datas['Officelanguage']['office_id']=$id;
                        $this->Officelanguage->create();
                        $this->Officelanguage->save($datas);
    					 }
    					 
				    }
				    if(!empty($old_language) || !empty($this->request->data['Officelanguage'])){
				        $this->Session->setFlash('You may need to restart the VF headset app to get the language packs updated.','message',array('class'=>'message'));
				    }
					if(is_null($id)){
						$this->Session->setFlash('Office has been created successfully.','message',array('class'=>'message'));
					} else {
						
						$this->loadModel('AppConstant');
						$Admin = $this->Session->read('Auth.Admin');
						$role_constant = Configure::read('role_constant');
						//pr($Admin['user_type']);die;
						if(in_array($Admin['user_type'],$role_constant)){
							$status_1 = 1;
							$this->AppConstant->updateAll(array('AppConstant.is_update'=> "'".$status_1."'"),array('AppConstant.id'=>1));
							$this->loadModel('NewUserDevice');
					 		$new_user_device = $this->NewUserDevice->find('all');
							//pr($new_user_device);die;
							foreach($new_user_device as $key => $val){
								$device_token = $val['NewUserDevice']['device_token'];
								if(!empty($device_token) && $this->checkNotification($val['NewUserDevice']['device_id'])){
									//$res = $this->sendPushNotificationNewAdminDataUpdate($device_token);
									$res = $this->sendPushNotificationNewAdminDataUpdateV2($device_token);
								}
							}
							
						}
						
						$this->Session->setFlash("Office has been updated successfully.",'message',array('class' => 'message'));
					}
                                        $this->redirect($this->referer());
					//$this->redirect(array('controller'=>'offices','action'=>'admin_subedit', 'id'=> $id));
                                        
				} 
		}else{
			$this->request->data = $editData;
		}
		//pr($this->request->data);die;
		$this->set(compact('test_c','user_credit_total','language'));
	}
        
        function admin_subedit_pic($id=null){
            $this->layout=false;
           //var_dump($id);
             $pic=NULL;   
             $this->Office->updateAll(array('Office.office_pic' => $pic),array('Office.id' => (int) $id));
             $this->Session->setFlash("Image deleted successfully.",'message',array('class' => 'message'));
             $this->redirect(['controller' => 'offices', 'action' => 'subedit', $id]);
            //die;
        }

        function admin_adminedit_pic($id=null){
            $this->layout=false;
           //var_dump($id);
             $pic=NULL;   
             $this->Office->updateAll(array('Office.office_pic' => $pic),array('Office.id' => (int) $id));
             $this->Session->setFlash("Image deleted successfully.",'message',array('class' => 'message'));
             $this->redirect(['controller' => 'offices', 'action' => 'add', $id]);
            //die;
        }

	
	
	//This function for deleting offices
	function admin_office_delete($id = null){
		$this->loadModel('Testreport');
		$this->loadModel('TestDevice');
		$this->loadModel('Pointdata');
		if($id){
			$result = $this->Office->updateAll(array('Office.is_delete' => '1'),array('Office.id' => $id));
			$paitents = $this->Patient->find('list',array('fields' => array('Patient.id'),'conditions' => array('Patient.office_id' => $id)));
			$staffs = $this->User->find('list',array('fields' => array('User.id'),'conditions' => array('User.office_id' => $id,'User.user_type' => 'Staffuser')));
			if($result){
				$result1 = $this->User->updateAll(array('User.is_delete' => '1'),array('User.office_id' => $id,'User.user_type !=' => 'Admin'));
				
				$result4 = $this->TestDevice->updateAll(array('TestDevice.is_delete' => '1'),array('TestDevice.office_id' => $id));
				
				$result2 = $this->Patient->updateAll(array('Patient.is_delete' => '1'),array('Patient.office_id' => $id));
				$result3 = $this->Testreport->updateAll(array('Testreport.is_delete' => '1'),array('OR'=> array('Testreport.patient_id' => $paitents,'Testreport.staff_id' => $staffs)));
				
				$result3 = $this->Pointdata->updateAll(array('Pointdata.is_delete' => '1'),array('OR'=> array('Pointdata.patient_id' => $paitents,'Pointdata.staff_id' => $staffs))); 
				
				$this->loadModel('AppConstant');
				$Admin = $this->Session->read('Auth.Admin');
				$role_constant = Configure::read('role_constant');
				//pr($Admin['user_type']);die;
				if(in_array($Admin['user_type'],$role_constant)){
					$status_1 = 1;
					$this->AppConstant->updateAll(array('AppConstant.is_update'=> "'".$status_1."'"),array('AppConstant.id'=>1));
					$this->loadModel('NewUserDevice');
					$new_user_device = $this->NewUserDevice->find('all');
					//pr($new_user_device);die;
					foreach($new_user_device as $key => $val){
						$device_token = $val['NewUserDevice']['device_token'];
						if(!empty($device_token) && $this->checkNotification($val['NewUserDevice']['device_id'])){
							//$res = $this->sendPushNotificationNewAdminDataUpdate($device_token);
							$res = $this->sendPushNotificationNewAdminDataUpdateV2($device_token);
						}
					}
					
				}
				
				$this->Session->setFlash("Office deleted successfully.",'message',array('class' => 'message'));
				
			}else{
				$this->Session->setFlash("Unable to delete.",'message',array('class' => 'message'));
			}
		}
		$this->redirect(array('controller' => 'offices', 'action' => 'manage_office'));
	}
	/*This function for changing status of Office*/
	public function admin_changeStatus($id=null) {
		$this->layout=false;
		$data=$this->Office->find('first',array('conditions'=>array('Office.id'=>$id)));
		if($data['Office']['status']==1) {
			$this->Office->id = $id;
			$this->Office->saveField('status',0);	
		} else {
			$this->Office->id = $id;
			$this->Office->saveField('status',1);	
		}
		
		
		$this->Session->setFlash('Office status has been updated successfully.','message',array('class' => 'message'));
		$this->redirect($this->referer());
	}
	/*Archive status change*/
	public function admin_updateArchiveStatus() 
	{	//pr($this->request->data['office_id']); die;
			$id = $this->request->data['office_id'];
			echo $id;
			$sdata = $this->Office->find('first', array('conditions' => array('Office.id' => $id)));
			//pr($this->request->data['archive_status']);
			//pr($data['Office']['archive_status']);
			$data['Office']['id'] =$sdata['Office']['id'];
			$data['Office']['archive_status'] = $this->request->data['archive_status']; 
			if ($this->Office->save($data)) {
				echo 1;
				exit();
			} else {
				echo 0;
				exit();
			}
		exit();
		
	}
	/*This function is used for assigning credit to office*/
	public function admin_assign_credit($id=null){
		
		$dataOffice =$editData = /*$this->Office->find('first',array('conditions'=>array('Office.id'=>$id)))*/ $this->Office->findById($id);
		$officeSubAdmin = $this->User->find('first',array( 'conditions' =>  array('User.office_id' =>$id, 'User.user_type'=>'Subadmin')));
		//pr($officeSubAdmin['User']['id']);  die;
		if($this->request->is(array('post','put'))){
			// echo "<pre>";
			// print_r($this->request->data);die;
			$record['Payment']['payment_status'] = 'Success'; 
			$record['Payment']['paid_amount'] = $this->request->data['Office']['credits'];
			$record['Payment']['txn_id'] = time();
			$record['Payment']['payment_type'] = 'Direct from Micro Medical Admin';
			$record['Payment']['payment_date'] = date('Y-m-d H:i:s');
			$today_date = date('Y-m-d h:i:s');
			$record['Payment']['expiry_date'] = date('Y-m-d h:i:s', strtotime($today_date . " + 30 day"));
			$record['Payment']['admin_paypal_id'] = 'support@micromedinc.com';
			$record['Payment']['user_id'] = @$officeSubAdmin['User']['id'];
			$this->loadModel('Payment');
			if($this->request->data['Office']['deduct']==1){
				$record['Payment']['paid_amount']=$record['Payment']['paid_amount']*-1;
			}
			if(!empty(@$officeSubAdmin['User']['id']) && $this->Payment->save($record)){
				//$update_data['Office']['credits'] = $dataOffice['Office']['credits'] + ($record['Payment']['paid_amount'] / $dataOffice['Office']['per_use_cost']);
				  
				$update_data['Office']['credits'] = $dataOffice['Office']['credits'] + $record['Payment']['paid_amount']; 
		 
				$update_data['Office']['id'] = $id;
				if(!empty($update_data['Office']['id'])){
					$this->Office->save($update_data);
					if($this->request->data['Office']['deduct']==1){
				$this->Session->setFlash('Credit deducted to office.','message',array('class' => 'message'));
			}else{
				$this->Session->setFlash('Credit Added to office.','message',array('class' => 'message'));
			}
					
					$this->redirect(array('controller'=>'offices','action'=>'admin_manage_office'));
				}else{
					$this->Session->setFlash('Something went wrong','message',array('class' => 'message'));
				}
			}else{
				$this->Session->setFlash('Something went wrong or office don\'t have subadmin','message',array('class' => 'message'));
				$this->redirect($this->referer());
			}
		}
		
		$this->request->data = $editData;
		$this->set(compact('officeSubAdmin'));
	}

	public function admin_export(){
	 
		$this->layout=false;
		$this->autoRender=false;

		$file_name = 'Offices_'.uniqid().'_'.date('Ymd');
		 
		$header = array('S.No','Office Name','Office Email','Office Second Email');
		$csv = fopen("php://output", 'w');
		header('Content-Type: application/csv; charset=utf-8');
		header('Content-type: application/ms-excel');
		header('Content-Disposition: attachment; filename='.$file_name.'.csv');
		fputcsv($csv, array_values($header));
		
		$data = $this->Office->find('all',array('fields'=>array('name','email','second_email')));
		//pr($data);die;
		$i = 1;
		foreach($data as $key){
			$record[] = $i;
			$record[] = $key['Office']['name'];
			$record[] = $key['Office']['email'];
			$record[] = $key['Office']['second_email'];
			$i++;
			fputcsv($csv, $record);
			$record = array();
		}
		fclose($csv);
		exit();
	}
	public function admin_export_report(){
	 
		$this->layout=false;
		$this->autoRender=false;

		$file_name = 'Offices_test_report_'.uniqid().'_'.date('Ymd');
		 
		$header = array('S.No','Office Name','Office Email','Device Type','Total Test Current Week','Total Test Last Week','Avg','Created Date','Status');
		$csv = fopen("php://output", 'w');
		header('Content-Type: application/csv; charset=utf-8');
		header('Content-type: application/ms-excel');
		header('Content-Disposition: attachment; filename='.$file_name.'.csv');
		fputcsv($csv, array_values($header));
		$last_sunday= date('Y-m-d 00:00:00',strtotime('last sunday'));
		$second_last_sunday=date('Y-m-d',strtotime('last sunday -7 days')); 
		$three_month_back = date('Y-m-d',strtotime(date('Y-m-d').' -3 months'));
		$this->Office->unbindModel(array('hasMany' => array('Officereport')));
		$this->Office->bindModel(
			array(
				'hasMany' => array(
					'TestDevice' => array(
						'className' => 'TestDevice',
						'foreignKey' => 'office_id',
					)
				)
			)
		);
		$type_option = array('0'=>'Gear','1'=>'GO','2'=>'PICO_NEO','3'=>'Quest','4'=>'PICO_G2','5'=>'PICO_NEO_3', '6' =>'PUPIL_NEO2','7'=>'PUPIL_NEO3','8'=>'PICO_G3','10'=>'Controller','11'=>'AppManager ','13'=>'Auto Download');
			 
		$this->Office->virtualFields['total_test_current_week'] ='select (select count(pointdatas.id) from mmd_pointdatas as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where mmd_users.office_id =Office.id  AND pointdatas.created >= "'.$last_sunday.'") + (select count(pointdatas.id) from mmd_dark_adaptions as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where mmd_users.office_id =Office.id AND pointdatas.created >= "'.$last_sunday.'") + (select count(pointdatas.id) from mmd_pointdatas as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where pointdatas.staff_id =Office.id AND pointdatas.created >= "'.$last_sunday.'") + (select count(pointdatas.id) from mmd_dark_adaptions as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where pointdatas.staff_id =Office.id AND pointdatas.created >= "'.$last_sunday.'" )';
	 	$this->Office->virtualFields['total_test_last_week'] ='select (select count(pointdatas.id) from mmd_pointdatas as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where mmd_users.office_id =Office.id AND pointdatas.created >= "'.$second_last_sunday.'"  AND pointdatas.created < "'.$last_sunday.'" ) + (  select count(pointdatas.id) from mmd_dark_adaptions as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where mmd_users.office_id =Office.id AND pointdatas.created >= "'.$second_last_sunday.'"  AND pointdatas.created < "'.$last_sunday.'") + (select count(pointdatas.id) from mmd_pointdatas as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where pointdatas.staff_id =Office.id AND pointdatas.created >= "'.$second_last_sunday.'"  AND pointdatas.created < "'.$last_sunday.'" ) + (  select count(pointdatas.id) from mmd_dark_adaptions as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where pointdatas.staff_id =Office.id AND pointdatas.created >= "'.$second_last_sunday.'"  AND pointdatas.created < "'.$last_sunday.'")';
	 	$this->Office->virtualFields['total_test_three_month'] ='select (select count(pointdatas.id) from mmd_pointdatas as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where mmd_users.office_id =Office.id AND pointdatas.created >= "'.$three_month_back.'" ) + (  select count(pointdatas.id) from mmd_dark_adaptions as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where mmd_users.office_id =Office.id AND pointdatas.created >= "'.$three_month_back.'") + (select count(pointdatas.id) from mmd_pointdatas as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where pointdatas.staff_id =Office.id AND pointdatas.created >= "'.$three_month_back.'" ) + (  select count(pointdatas.id) from mmd_dark_adaptions as pointdatas  INNER JOIN mmd_users ON (mmd_users.id=pointdatas.staff_id)  where pointdatas.staff_id =Office.id AND pointdatas.created >= "'.$three_month_back.'")';
		$data = $this->Office->find('all',array('fields'=>array('name','email','total_test_current_week','total_test_last_week','total_test_three_month','created','status'),'order'=>array('Office.total_test_current_week'=>'DESC'),'conditions' => array('status' => 1)));
		
		$i = 1;
		foreach($data as $key){
			$record[] = $i;
			$record[] = $key['Office']['name'];
			$record[] = $key['Office']['email'];

			$device_keys=array();
			foreach($key['TestDevice'] as $device_key => $device_value){
				if(isset($type_option[$device_value['device_type']])){
					$device_keys[$device_value['device_type']] = $type_option[$device_value['device_type']];
				}
				

			}
			$record[] = implode(', ', $device_keys);
			$record[] = $key['Office']['total_test_current_week'];
			$record[] = $key['Office']['total_test_last_week'];
			$record[] = (int) ($key['Office']['total_test_three_month']/12); 
			$record[] = ' '.date('Y-m-d', strtotime($key['Office']['created']));
			$record[] = ($key['Office']['status']==1)?"Active":"Inactive";
			
			$i++;
			fputcsv($csv, $record);
			$record = array();
		}
		fclose($csv);
		exit();
	}

	public function admin_export_device(){
	 
		$this->layout=false;
		$this->autoRender=false;

		$file_name = 'Offices_device_list_'.uniqid().'_'.date('Ymd');
		 
		$header = array('S.No','Office Name','Office Email','Serial Number', 'Device Name', 'Device Type', 'Created', 'Status');

		$csv = fopen("php://output", 'w');
		header('Content-Type: application/csv; charset=utf-8');
		header('Content-type: application/ms-excel');
		header('Content-Disposition: attachment; filename='.$file_name.'.csv');
		fputcsv($csv, array_values($header));
		$last_sunday= date('Y-m-d 00:00:00',strtotime('last sunday'));
		$second_last_sunday=date('Y-m-d',strtotime('last sunday -7 days')); 
		$three_month_back = date('Y-m-d',strtotime(date('Y-m-d').' -3 months'));
		$this->Office->unbindModel(array('hasMany' => array('Officereport')));
		$this->Office->bindModel(
			array(
				'hasMany' => array(
					'TestDevice' => array(
						'className' => 'TestDevice',
						'foreignKey' => 'office_id',
					)
				)
			)
		);
		$type_option = array('0'=>'Gear','1'=>'GO','2'=>'PICO_NEO','3'=>'Quest','4'=>'PICO_G2','5'=>'PICO_NEO_3', '6' =>'PUPIL_NEO2','7'=>'PUPIL_NEO3','8'=>'PICO_G3','10'=>'Controller','11'=>'AppManager ','13'=>'Auto Download');
			 
		 
		$data = $this->Office->find('all',array('fields'=>array('name','email','created','status'),'order'=>array('Office.name'=>'DESC'),'conditions' => array('status' => 1)));
		
		$i = 1; 
		foreach($data as $key => $value){ 
			 $record = array(); 
			foreach($value['TestDevice'] as $device_key => $device_value){ 
				if($device_key==0){
					$record[] = $i;
					$record[] = $value['Office']['name'];
					$record[] = $value['Office']['email'];
				}else{
					$record[] = '';
					$record[] = '';
					$record[] = '';
				}
				 
				$record[] = $device_value['deviceSeraial'];
				$record[] = $device_value['name'];
				$record[] = isset($type_option[$device_value['device_type']])?($type_option[$device_value['device_type']]):'';
				$record[] = $device_value['created'];
				$record[] = ($device_value['status'] == 1)?'Active':'InActive';
				fputcsv($csv, $record);
				$record = array();
			}
			  
			
			$i++;
			
		}
		fclose($csv);
		exit();
	}
	public function admin_genratePassword(){
	    $password="";
	    while($password==""){
	      $length = 12;
          $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz'; 
          $password2=substr(str_shuffle($data), 0, $length);
          $data=$this->Office->find('first',array('conditions' =>  array('Office.password' =>$password2)));
          if(count($data)>0){
             
          }else{
             $password=$password2;
              echo $password2;
          }
            
	    } 
	     exit(); 	    
	}
}

?>