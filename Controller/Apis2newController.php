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
}

?>
