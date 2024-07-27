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
class DashboardsController extends AppController {
	public $uses = array('User','TestDevice', 'Practices','Testreport','Office', 'Pointdata','DarkAdaption');
			
	var $helpers = array('Html', 'Form','Js' => array('Jquery'), 'Custom');

    public $components = array('Auth'=>array('authorize'=>array('Controller')),'Session','Email','Common');
	//public $allowedActions =array();
    
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow($this->allowedActions);	
	}
	
	public function isAuthorized($user){
		parent::beforeFilter();	
		if(isset($user['user_type']) && (($user['user_type'] == 'Admin') || ($user['user_type'] == 'Subadmin') || ($user['user_type'] == 'Staffuser')) && isset($this->request->prefix) && ($this->request->prefix == 'admin')){
			return true;
		}else{
			$this->redirect($this->referer());
		}
    }
	//This function is for dashboard of Admin,Subadmin,Staff
	public function admin_index($msg=null){ 
		if(!$this->Auth->loggedIn()){
			return $this->redirect(array('controller'=>'users','action'=>'admin_login'));
		}
		$this->loadModel('Patient'); 
		$this->loadModel('ActTest'); 
		$this->loadModel('DarkAdaption'); 
		$this->loadModel('PupTest'); 
		$this->loadModel('StbTest'); 
		$this->loadModel('VtTest'); 
		$this->loadModel('UserNotification');
		$Admin = $this->Session->read('Auth.Admin');
		if($Admin['checkautobackup']==1){
			$Admin['Office']['session_backup']=1;
			$this->Session->write('Auth.Admin.Office.session_backup', 1);
		}  
		 date_default_timezone_set('US/Eastern');
		  // $datetime =date('Y-m-d H:i:s');
		  // $tz_from = $Admin['time_zone']; 
		  // $tz_to = 'UTC';
		  // $format = 'Y-m-d H:i:s';
		  // $dt = new DateTime($datetime, new DateTimeZone($tz_from));
		  // $dt->setTimeZone(new DateTimeZone($tz_to));
		  // $date_new= $dt->format($format);
		 
		//$totalStaffUser = $this->User->find('count',array('conditions'=>array('User.user_type'=>"Staffuser")));
		
		$all_staff_ids=$this->User->find('list',array('conditions'=>array('User.office_id'=>$Admin['office_id']),'fields'=>array('User.id')));
		
		$vfFdtVsReports = $this->Pointdata->find('count',array('conditions'=>array('Pointdata.staff_id'=>$all_staff_ids)));
		$actReportsCount = $this->ActTest->find('count',array('conditions'=>array('ActTest.staff_id'=>$all_staff_ids)));
		$daReportsCount = $this->DarkAdaption->find('count',array('conditions'=>array('DarkAdaption.staff_id'=>$all_staff_ids))); 
		$pupReportsCount = $this->PupTest->find('count',array('conditions'=>array('PupTest.staff_id'=>$all_staff_ids))); 
		$stbReportsCount = $this->StbTest->find('count',array('conditions'=>array('StbTest.staff_id'=>$all_staff_ids)));
		$vtReportsCount = $this->VtTest->find('count',array('conditions'=>array('VtTest.staff_id'=>$all_staff_ids)));
		$vfReportsCount = $vfFdtVsReports+$actReportsCount+$daReportsCount+$pupReportsCount+$stbReportsCount+$vtReportsCount;
		$conditions=array('Pointdata.is_delete' => '0');
		$pointdataYear=array('Pointdata.is_delete' => '0');
		$ActTestYear=array('ActTest.is_delete' => '0');
		$DarkAdaptionYear=array('DarkAdaption.is_delete' => '0');
		$PupTestYear=array('PupTest.is_delete' => '0');
		$StbTestYear=array('StbTest.is_delete' => '0');
		$VtTestYear=array('VtTest.is_delete' => '0');
		if($Admin['user_type'] == 'Admin') {
			if(!empty($this->Session->read('Search.office'))){
			$office_id['User']['office_id'] =  $this->Session->read('Search.office');
			$staffuserAdmin=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id']),'fields'=>array('User.id')));
			//pr($staffuserAdmin); die;
			$conditions[]['Pointdata.staff_id']=$staffuserAdmin;
			$pointdataYear[]['Pointdata.staff_id']=$staffuserAdmin;
			$ActTestYear[]['ActTest.staff_id']=$staffuserAdmin;
			$DarkAdaptionYear[]['DarkAdaption.staff_id']=$staffuserAdmin;
			$PupTestYear[]['PupTest.staff_id']=$staffuserAdmin;
			$StbTestYear[]['StbTest.staff_id']=$staffuserAdmin;
			$VtTestYear[]['VtTest.staff_id']=$staffuserAdmin;
					}
		} elseif($Admin['user_type'] == 'Subadmin') {
			$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$Admin['id'],'User.user_type'=>'Subadmin'),'fields'=>array('User.office_id')));
			if(!empty($this->Session->read('Search.office'))){
				$office_id['User']['office_id'] =  $this->Session->read('Search.office');
			}
			$staffuser=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id']),'fields'=>array('User.id')));
			
			// Calculate Total Records..
			$conditions[]['Pointdata.staff_id']=$staffuser;
			$pointdataYear[]['Pointdata.staff_id']=$staffuser;
			$ActTestYear[]['ActTest.staff_id']=$staffuser;
			$DarkAdaptionYear[]['DarkAdaption.staff_id']=$staffuser;
			$PupTestYear[]['PupTest.staff_id']=$staffuser;
			$StbTestYear[]['StbTest.staff_id']=$staffuser;
			$VtTestYear[]['VtTest.staff_id']=$staffuser;
		}else{
			$office_id=$this->User->find('first',array('conditions'=>array('User.id'=>$Admin['id'],'User.user_type'=>'Staffuser'),'fields'=>array('User.office_id')));

			if(!empty($this->Session->read('Search.office'))){
				$office_id['User']['office_id'] =  $this->Session->read('Search.office');
			} 

			if($Admin['user_type'] == 'SuperSubadmin' && empty($office_id)){
				$office_id['User']['office_id']=$Admin['office_id'];
			}
			$all_staff_ids=$this->User->find('list',array('conditions'=>array('User.office_id'=>$office_id['User']['office_id']),'fields'=>array('User.id'))); 
			$conditions[]['Pointdata.staff_id']=$all_staff_ids;
			$pointdataYear[]['Pointdata.staff_id']=$all_staff_ids;
			$ActTestYear[]['ActTest.staff_id']=$all_staff_ids;
			$DarkAdaptionYear[]['DarkAdaption.staff_id']=$all_staff_ids;
			$PupTestYear[]['PupTest.staff_id']=$all_staff_ids;
			$StbTestYear[]['StbTest.staff_id']=$all_staff_ids;
			$VtTestYear[]['VtTest.staff_id']=$all_staff_ids;
		}
		$pointdataYear[]['Pointdata.created >']= date('Y-01-01');
		$pointdataYear[]['Pointdata.created <']= date('Y-12-31');
		$ActTestYear[]['ActTest.created >']= date('Y-01-01');
		$ActTestYear[]['ActTest.created <']= date('Y-12-31');
		$DarkAdaptionYear[]['DarkAdaption.created >']= date('Y-01-01');
		$DarkAdaptionYear[]['DarkAdaption.created <']= date('Y-12-31');
		$PupTestYear[]['PupTest.created >']= date('Y-01-01');
		$PupTestYear[]['PupTest.created <']= date('Y-12-31');
		$StbTestYear[]['StbTest.created >']= date('Y-01-01');
		$StbTestYear[]['StbTest.created <']= date('Y-12-31');
		$VtTestYear[]['VtTest.created >']= date('Y-01-01');
		$VtTestYear[]['VtTest.created <']= date('Y-12-31');
		$conditions[]['Pointdata.created like']="%".date('Y-m-d')."%";
		$totalTest = $this->Pointdata->find('count',array('conditions'=>$conditions));
		$totalTestPointdata = $this->Pointdata->find('count',array('conditions'=>$pointdataYear));
		$totalTestActTest = $this->ActTest->find('count',array('conditions'=>$ActTestYear));
		$totalTestDarkAdaption = $this->DarkAdaption->find('count',array('conditions'=>$DarkAdaptionYear));
		$totalTestPupTest = $this->PupTest->find('count',array('conditions'=>$PupTestYear));
		$totalTestStbTest = $this->StbTest->find('count',array('conditions'=>$StbTestYear));
		$totalTestVtTest = $this->VtTest->find('count',array('conditions'=>$VtTestYear));
		$totalTestYear=$totalTestPointdata+$totalTestActTest+$totalTestDarkAdaption+$totalTestPupTest+$totalTestStbTest+$totalTestVtTest;
		if(!empty($Admin) && $Admin['user_type'] == "Admin"){
			$totalStaffUser = $this->User->find('count',array('conditions'=>array('User.user_type'=>"Staffuser")));
			$totalPatient = $this->Patient->find('count',array('conditions'=>array('Patient.merge_status'=>0)));
			$totaloffice = $this->Office->find('count');
			$totalTestDevice = $this->TestDevice->find('count');
			
			$this->set(compact('totalTestDevice','totaloffice','totalPatient'));
			
			$report_datas = $this->Testreport->find('all', array('limit' => 6,'order' =>'Testreport.test_id DESC'));
			
			$Notifications = $this->UserNotification->find('all', array(
			  'limit' => 3,
			  'order' => 'UserNotification.created DESC' )
			);
			//print_r($report_datas);die;
		} else if(!empty($Admin) && $Admin['user_type'] == "Subadmin"){
			
			$totalStaffUser=$this->User->find('count',array('conditions'=>array('User.user_type'=>'Staffuser','User.office_id'=>$Admin['office_id'])));
			
			$staffuser = $this->User->find('list',array('conditions'=>array('User.office_id'=>$Admin['office_id'])));
			
			$report_datas = $this->Testreport->find('all', array('conditions'=>array('Testreport.staff_id'=>$staffuser),'limit' => 6,'order' =>'Testreport.test_id DESC'));
			
			$totalPatient=$this->Patient->find('count',array('conditions'=>array('Patient.merge_status'=>0,'Patient.user_id'=>$staffuser,'Patient.office_id'=>$Admin['office_id'])));
			
			$Notifications = $this->UserNotification->find('all', array('conditions'=>array('UserNotification.user_id'=>$staffuser),'limit' => 3,'order' => 'UserNotification.created DESC'));
			$this->loadModel('Office');
			$credits = $this->Office->field('credits',array('Office.id'=>$Admin['office_id']));
			$this->set(compact('credits'));
			
		} else if(!empty($Admin) && $Admin['user_type'] == "Staffuser"){
			//$totalPatient=$this->Patient->find('count',array('conditions'=>array('OR'=>array('Patient.user_id'=>$Admin['id'],'Patient.office_id'=>$Admin['office_id']))));
			
			$totalPatient=$this->Patient->find('count',array('conditions'=>array('Patient.user_id'=>$Admin['id'],'Patient.office_id'=>$Admin['office_id'])));
			
			$report_datas = $this->Testreport->find('all', array('conditions'=>array('Testreport.staff_id'=>$Admin['id']),'limit' => 6,'order' =>'Testreport.test_id DESC'));
			
			$Notifications = $this->UserNotification->find('all', array('conditions'=>array('UserNotification.user_id'=>$Admin['id']),'limit' => 3,'order' => 'UserNotification.created DESC'));
			$avl_credit = $this->User->field('credits',array('User.id'=>$this->Session->read('Auth.Admin.id')));
			$this->set(compact('avl_credit'));
		}
		
		
		$this->set(compact('totalStaffUser','totalPatient','Notifications','report_datas', 'vfReportsCount','totalTest','totalTestYear'));
		if(!empty($msg)){
			//pr($this->Session->read('mydata'));die;
			$this->Session->setFlash('Thanks for Purchasing Credits.Credits will added in your account soon.','message',array('class' => 'message'));
			$this->loadModel('Office');
			$credits = $this->Office->field('credits',array('Office.id'=>$Admin['office_id']));
			$this->set(compact('credits'));
			$this->redirect(array('action'=>'index'));
		}



/* new code for manage patient */


$this->loadModel('Patient');
		$this->loadModel('User');
		$Admin = $this->Session->read('Auth.Admin');
		if(!empty($Admin) && $Admin['user_type'] == "Admin"){
			$conditions=array();
		} else if(!empty($Admin) && $Admin['user_type'] == "Subadmin"){
			$conditions=array('Patient.office_id'=>$Admin['office_id']);
		} else if(!empty($Admin) && $Admin['user_type'] == "Staffuser"){
			$conditions=array('Patient.office_id'=>$Admin['office_id']);	
			$avl_credit = $this->User->field('credits',array('User.id'=>$this->Session->read('Auth.Admin.id')));
			$this->set(compact('avl_credit'));
		}
		if(!empty($this->Session->read('Search.office'))){
			$conditions[] = array('Patient.office_id' => $this->Session->read('Search.office'));
		}
		$conditions['AND']['OR']= array('Patient.delete_date !='=>null,'Patient.is_delete'=>0);
	  
		$this->Patient->virtualFields['office_name'] = "select name from mmd_offices as O where O.id=Patient.office_id ";
		$this->Patient->unbindModel(
    array('hasMany' => array('Pointdata'))
);
//         $this->Patient->bindModel(array(
//             'hasMany'=>array(
//                 'Pointdata' => array(
//                     'className' => 'Pointdata',
//                     'foreignKey' => 'patient_id', 
//                     "limit"=>1,
//                     "order" => 'Pointdata.id desc'
//                 )
//             )
//         ));
//         $this->Patient->bindModel(array(
//             'hasMany'=>array(
//                 'DarkAdaption' => array(
//                     'className' => 'DarkAdaption',
//                     'foreignKey' => 'patient_id', 
//                     "limit"=>1,
//                     "order" => 'DarkAdaption.id desc'
//                 )
//             )
//         ));
//         $this->Pointdata->unbindModel(
//     array('hasMany' => array('VfPointdata'), 'belongsTo' => array('User','Patient','Test'))
// );
//          $this->DarkAdaption->unbindModel(
//     array('hasMany' => array('DaPointData'), 'belongsTo' => array('User','Office'))
// ); 
        // unset($conditions['Pointdata.is_delete']);
        
		$this->paginate=array('conditions'=>$conditions,
		'limit'=>10,
        'recursive'=>2,
		'callbacks' => false,
		'joins' => array(
	        array(
	            'table' => 'mmd_pointdatas',
	            'alias' => 'Pointdata',
	            'type' => 'INNER',
	            'conditions' => array(
	                'Pointdata.patient_id = Patient.id'
	            )
	        ),
	        // array(
	        //     'table' => 'mmd_dark_adaptions',
	        //     'alias' => 'DarkAdaption',
	        //     'type' => 'INNER',
	        //     'conditions' => array(
	        //         'DarkAdaption.patient_id = Patient.id'
	        //     )
	        // )
	    ),
		'order'=>array('Patient.id'=>'DESC'),
		'group'=>array('Patient.id')
		); 

		  // pr($conditions);
    //      die();
		$datas=$this->paginate('Patient');


	 
		//echo "<pre>";print_r($datas);die();
      
		if($Admin['user_type'] == 'Subadmin' || $Admin['user_type'] == 'Staffuser') {
			$this->loadModel('Payment');
			
			
			if($Admin['user_type'] == 'Staffuser'){ 
				$user_s = $this->User->find('first',array(
					'conditions'=>array('User.office_id'=>$Admin['office_id'], 'User.user_type'=>'Subadmin')
				));
			}
			$check_payable = '';
			$this->loadModel('Office');
			$check_payable=$this->Office->find('first',array(
				'conditions'=>array('Office.id'=>$Admin['Office']['id'])
			)); 
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


/*manage patient code end */
	}
	public function admin_assign_office(){
		$this->layout = false;
		$this->autoRender = false;
		if($this->request->is('post')){
			$this->Session->write('Search.office',$this->request->data['Dashboard']['office']);
			$referer = Router::parse($this->referer('/',true)); 
			//$this->redirect(array('controller'=>$referer['controller'], 'action'=>$referer['action']));
			$this->redirect($this->request->data['Dashboard']['currenturl']);
		}
	}
}

?>