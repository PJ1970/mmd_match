<?php
App::uses('AppController','Controller');
App::uses('CakeEmail','Network/Email');

class DarkadaptationsController extends AppController {
	public $uses = array('User','Patients','Tests','UserNotification','UserDevice','TestReport','TestDevice','Practice','Office','Files','VfPointdata','Pointdata','Masterdata','MasterPointdata', 'DevicePreference', 'Diagnosis', 'Cms', 'DarkAdaption', 'DaPointData'); 
			
	var $helpers = array('Html', 'Form','Js' => array('Jquery'), 'Custom');

    public $components = array('Auth'=>array('authorize'=>array('Controller')),'Session','Email','Common','RememberMe');
	public $allowedActions =array();
    
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow($this->allowedActions);	
	}
	
	
	/*Test Report List*/
	public function admin_dark_adaptations_list(){
		$Admin = $this->Auth->user();
		$conditions = array('DarkAdaption.is_delete' =>'0');
		if(!empty($this->Session->read('Search.office'))){
			$office_id =  $this->Session->read('Search.office');
			$conditions['OR'][] = array('Lower(DarkAdaption.office_id)'=> $office_id);
                }else{
                    if($Admin['user_type'] == 'Subadmin' || $Admin['user_type'] == 'Staffuser') {
			$conditions['AND'][] = array('Lower(DarkAdaption.office_id)'=> $Admin['office_id']);
                    }
                }
                 if(!empty($this->request->query['patent_id'])){
                    $conditions['AND'][] = array('Lower(DarkAdaption.patient_id)'=> $this->request->query['patent_id']);
                }
		if(!empty($this->request->query['search'])){
			$staff_name=explode(" ", @$this->request->query['search']);
			$search = strtolower(trim($this->request->query['search']));
			$conditions['OR'][] = array('Lower(DarkAdaption.patient_name) like'=> '%'.$search.'%');
			foreach($staff_name as $key=>$value){
				$conditions['OR'][] = array('User.first_name like'=> '%'.trim($value).'%');
				$conditions['OR'][] = array('User.middle_name like'=> '%'.trim($value).'%');
				$conditions['OR'][] = array('User.last_name like'=> '%'.trim($value).'%');
			} 
			$conditions['OR'][] = array('Patient.id_number'=> $value);
			$conditions['OR'][] = array('Patient.id'=> $value);
			$this->set(compact('search'));
		}
		if (!empty($this->request->query['patientreport'])) {
			$patientreport = trim($this->request->query['patientreport']); 
			if (is_numeric($patientreport)) { 
				$conditions['OR'][] = array('Patient.id' => $patientreport); /* 3 dec Added new line */
			}
			$this->set(compact('patientreport'));
		}

		$limit=10;
		if(@$this->request->query['rempve_layout']==1){
	       $limit=100;
	   }
		$params = array(
			'conditions' => $conditions,
			'limit'=>$limit,
			'order'=>array('DarkAdaption.id'=>'DESC')
		);
		
		$this->paginate=array('DarkAdaption'=>$params);
		$datas = $this->paginate('DarkAdaption');
               // if(!empty($_GET['ttt'])){
		//pr($conditions); die;
                    
                //}
		if($Admin['user_type'] == 'Subadmin' || $Admin['user_type'] == 'Staffuser') {
			$this->loadModel('Payment');
			$get_last_payment=$this->Payment->find('first',array(
				'conditions'=>array('Payment.user_id'=>$Admin['id'],'Payment.payment_status'=>'Success','Payment.expiry_date > '=>date('Y-m-d h:i:s')),
				'order' => array('id' => 'DESC')
			)); //check credit expire or not
			
			if($Admin['user_type'] == 'Staffuser'){
				$user_s = $this->User->find('first',array(
					'conditions'=>array('User.office_id'=>$Admin['office_id'], 'User.user_type'=>'Subadmin')
				));
				$get_last_payment=$this->Payment->find('first',array(
					'conditions'=>array('Payment.user_id'=>$user_s['User']['id'],'Payment.payment_status'=>'Success','Payment.expiry_date > '=>date('Y-m-d h:i:s')),
					'order' => array('id' => 'DESC')
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



 $download=array();
                if(count($datas)>0):
                    foreach($datas as $ospt):
                    
                   // pr($ospt);
                //die;
                    
                        $patient_name= Hash::get($ospt, 'DarkAdaption.patient_name');
                        $patient_id= Hash::get($ospt, 'DarkAdaption.patient_id');
                        
                        
                        $odpt=$this->DarkAdaption->find('first',array(
                                     'conditions'=>array(
                                         //'Pointdata.id !='=> Hash::get($ospt, 'Pointdata.id'),
                                         'DarkAdaption.is_delete'=>0,
                                         'DarkAdaption.patient_id' => $patient_id,
                                         
                                         'DarkAdaption.eye_select !=' => Hash::get($ospt, 'DarkAdaption.eye_select') ,
                                         'cast(DarkAdaption.created as date) =' => date('Y-m-d', strtotime(Hash::get($ospt, 'DarkAdaption.created'))),
                                     ),
                                     'order' => 'DarkAdaption.id DESC',
                                    )
                                 );
 
 						$od_pdf="";
                         $os_pdf="";
                         if(Hash::get($ospt, 'DarkAdaption.eye_select') == 1){ 
                             $od_pdf = Hash::get($ospt, 'DarkAdaption.pdf');
                         }else{
                             $os_pdf=Hash::get($ospt, 'DarkAdaption.pdf');
                           
                         } 

                         if(Hash::get($odpt, 'DarkAdaption.eye_select') == 1){
                             if(empty($od_pdf)){
                                 $od_pdf = Hash::get($odpt, 'DarkAdaption.pdf');
                             }
                         }else{
                             if(empty($os_pdf)){
                                 $os_pdf=Hash::get($odpt, 'DarkAdaption.pdf');
                             }
                         }

 						 $os_pdf_link= WWW_BASE.'uploads/darkadaption/'.$os_pdf;
                         $od_pdf_link= WWW_BASE.'uploads/darkadaption/'.$od_pdf;

                          $os_dicom_link=WWW_BASE.'admin/unityreports/exportImageda/'.$os_pdf;// $this->Html->url(['controller'=>'unityreports','action'=>'exportImage',$os_pdf]);
                        $od_dicom_link= WWW_BASE.'admin/unityreports/exportImageda/'.$od_pdf;//$this->Html->url(['controller'=>'unityreports','action'=>'exportImage',$od_pdf]);

                         
                        
                        $DarkAdaption_id=Hash::get($ospt, 'DarkAdaption.id');
                        $os_dicom_link='/admin/unityreports/exportDicomda/'.$DarkAdaption_id.'/'.$os_pdf;
                        $od_dicom_link= '/admin/unityreports/exportDicomda/'.$DarkAdaption_id.'/'.$od_pdf;

                         $os_pointdata_id=Hash::get($ospt, 'DarkAdaption.id');
                        /*if(!empty($os_pointdata_id)):
                            $download[$os_pointdata_id]['class'][]='os-cls-'.$os_pointdata_id;
                        endif;*/
                        if(!empty($os_pdf)){
                            $download[$os_pointdata_id]['pdf'][]=$os_pdf_link;
                            $download[$os_pointdata_id]['dicom'][]=$os_dicom_link;
                            $download[$os_pointdata_id]['filename'][]=$patient_name.'-OS-'.time();
                        }
                        if(!empty($od_pdf)){
                            $download[$os_pointdata_id]['pdf'][]=$od_pdf_link;
                            $download[$os_pointdata_id]['dicom'][]=$od_dicom_link;
                            $download[$os_pointdata_id]['filename'][]=$patient_name.'-OD-'.time();
                            
                        }
                        $od_pointdata_id=Hash::get($odpt, 'DarkAdaption.id');
                        if(!empty($od_pointdata_id)):
                            $download[$os_pointdata_id]['tr_id']='ptdata-'.$od_pointdata_id;
                        endif;
                        
                    endforeach;
                endif;


   
		$TestNameArray = $this->Common->testNameArray();
		$this->set(compact('datas', 'check_payable', 'TestNameArray', 'download'));

		if(@$this->request->query['rempve_layout']==1){
	       $this->layout = false;
	       $this->render('dark_adaptations_list');
	   }
	}
		/*Unity report view*/
	public function admin_view($id=null){
		$this->layout=false;
		if(!empty($id)){
			
			$data = $this->DarkAdaption->findById($id);
			//pr($data['DaPointData']); die;
			$this->set(compact(['data']));
		}	
	}
	
	public function admin_exportPdf($pdf=null){
		$fileName = $pdf_file   = '../../../../inetpub/wwwroot/portalmi2/app/webroot/uploads/darkadaption/'.$pdf;
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
	public function admin_export($id=null){
	 
		$this->layout=false;
		$this->autoRender=false;
		
		if(empty($this->request->query['url'])){
			$file_name = uniqid().'_'.date('Ymd');
		}else{
			$file_name = $this->request->query['url'];
		}
		 
		$header = array('x','y','Time Min','Decibles','Color','Index','Eye');
		$csv = fopen("php://output", 'w');
		header('Content-Type: application/csv; charset=utf-8');
		header('Content-type: application/ms-excel');
			if(!empty($id)){
		$data = $this->DarkAdaption->findById($id);
		$file_name=str_replace(" ","_",$data['DarkAdaption']['patient_name'])."_".date('YmdHis',strtotime($data['DarkAdaption']['test_date_time']));
		header('Content-Disposition: attachment; filename='.$file_name.'.csv');
		fputcsv($csv, array_values($header));
			 
			foreach($data['DaPointData'] as $pdata){
				$record[] = $pdata['x'];
				$record[] = $pdata['y'];
				$record[] = $pdata['timeMin'];
				$record[] = $pdata['Decibles'];
				$record[] = $pdata['color'];
				$record[] = $pdata['index_name'];
				$record[] = $pdata['Eye'];
				fputcsv($csv, $record);
				$record = array();
			}
			fclose($csv);
			exit();
		}else{
		    	header('Content-Disposition: attachment; filename='.$file_name.'.csv');
		fputcsv($csv, array_values($header));
		}	
	}
	public function admin_delete($id=NULL){
		$Admin = $this->Auth->user();
		//pr($Admin);die;
		if($Admin['user_type'] == 'Admin' || $Admin['user_type'] == 'Subadmin') {
			$delete_record=$this->DarkAdaption->updateAll(
				array('DarkAdaption.is_delete' => '1'),
				array('DarkAdaption.id' => $id)
			);
			if($delete_record){
				$this->Session->setFlash("Dark Adaption deleted successfully.",'message',array('class' => 'message'));
			}else{
				$this->Session->setFlash("Unable to delete.",'message',array('class' => 'message'));
			}
			$this->redirect($this->referer());
		}else{
			echo 'can not access.';die;
		}
	}
}
?>