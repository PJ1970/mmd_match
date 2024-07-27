<?php
App::uses('AppController', 'Controller');
class Apis2newController extends AppController
{
 
	function beforeFilter()
	{
		parent::beforeFilter(); 
		$this->Auth->allow('*');
	}
 
	//API for return started test data
	/*
      API Name: http://www.vibesync.com/apisnew/check_test_status
      Request Parameter: office_id, device_id
*/
	public function check_test_status($office_id = 0, $device_id = 0)
	{ 
		$this->loadModel('TestStart');
		$response = array();
		$this->TestStart->cacheQuery = true;
		$check = $this->TestStart->find('first', array(
			'fields' => array('status', 'id'),
			'conditions' => array('TestStart.office_id' => $office_id, 'TestStart.device_id' => $device_id)
		)); 
		if (empty($check)) {
			$response['message'] = 'no command';
			$response['response'] = 99;
			$response['result'] = 1;
		} else {
			switch ($check['TestStart']['status']) {
				case 1:
					$response['message'] = 'Start';
					break;
				case 2:
					$response['message'] = 'Pause';
					break;
				case 3:
					$response['message'] = 'Resume';
					break;
				case 7:
				case 8:
					$response['message'] = 'Recover Last Test';
					$this->TestStart->delete($check['TestStart']['id']);
					break;
			}
			$response['response'] = json_decode($check['TestStart']['status']);
			$response['result'] = 1;
		}
		$this->TestStart->getDatasource()->disconnect();
		echo json_encode($response); 
		exit();
	} 

		public function office_language_file()
	{
	    
	    
				$input_data = json_decode(file_get_contents("php://input"), true);
				if (empty($input_data)) {
					$input_data = $_POST;
				}
				if (isset($input_data['office_id']) && isset($input_data['office_id'])) {
				    $this->loadModel('Officelanguage'); 
				    $this->loadModel('LanguageFile'); 
				     $last_sync=''; 
				     $new_language_data = array();
				    $data = $this->Officelanguage->find('list',array( 'conditions' =>  array('Officelanguage.office_id' =>$input_data['office_id']),'fields'=>array('language_id','language_id')));
				     
				    if(isset($input_data['last_sync_time']) && $input_data['last_sync_time']!=""){
				         $last_sync=$input_data['last_sync_time'];
				        $new_language_data=  $this->Officelanguage->find('all',array( 'conditions' =>  array('Officelanguage.office_id' =>$input_data['office_id'],'Officelanguage.created_at >' =>$input_data['last_sync_time']), 'fields'=>array('created_at','office_id','id','language_id')));
				         $new_language=  $this->Officelanguage->find('list',array( 'conditions' =>  array('Officelanguage.office_id' =>$input_data['office_id'],'Officelanguage.created_at >' =>$input_data['last_sync_time']),'fields'=>array('language_id','language_id')));
				        $data_all = $this->LanguageFile->find('all',array('recursive' => 3, 'order' => 'LanguageFile.modified ASC', 'conditions' =>  array('OR' => array(array("LanguageFile.language_id" => $new_language), array('LanguageFile.language_id' =>$data, 'LanguageFile.modified >' =>$input_data['last_sync_time'])))));
				    }else{
				        $new_language_data=  $this->Officelanguage->find('all',array( 'conditions' =>  array('Officelanguage.office_id' =>$input_data['office_id']), 'fields'=>array('created_at','office_id','id','language_id')));
				        $data_all = $this->LanguageFile->find('all',array('recursive' => 3, 'order' => 'LanguageFile.modified ASC', 'conditions' =>  array('LanguageFile.language_id' =>$data)));
				    }
				    $datas = array();
				     foreach($data_all as $key => $value){
				         
				         $value['LanguageFile']['folder_name'] =strtoupper($value['Language']['code']);
				         $value['LanguageFile']['language_id'] =strtoupper($value['Language']['l_id']);
				         $value['LanguageFile']['file_path'] = WWW_BASE . 'app/webroot/uploads/' . strtoupper($value['Language']['code']) . '/' . $value['LanguageFile']['file_name'];
				        $datas[$value['LanguageFile']['folder_name']][] = $value['LanguageFile'];   /// new changes 
				         $last_sync=$value['LanguageFile']['modified'];  
				     } 
				     if(!empty($new_language_data)){
				         foreach($new_language_data as $key => $value2){
				             if($value2['Officelanguage']['created_at'] > $last_sync){
				                 $last_sync = $value2['Officelanguage']['created_at'];
				             }
				         }
				     }
				     $data_new = array();
				     foreach($datas as $key => $value){
				     	$data_new = array_merge($data_new,$datas[$key]);
				     }
				     $datas = array_merge($datas['ES'],$datas['EN']);
         			$response_array = array('status' => 1, 'last_sync_time' =>$last_sync, 'datas'=>$data_new);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
        			
				}else{
				    $response_array = array('message' => 'Please send valid input data.', 'status' => 0);
					header('Content-Type: application/json');
					echo json_encode($response_array);
					die;
				}
				
			}
	     
}

?>
