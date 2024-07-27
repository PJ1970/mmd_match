<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class FilesController extends AppController
{
	public $uses = array('User', 'Patients', 'Tests', 'Practice', 'Office', 'Files', 'VfPointdata', 'Pointdata', 'Masterdata', 'MasterPointdata', 'DevicePreference', 'Diagnosis', 'Cms', 'DarkAdaption', 'DaPointData', 'ActTest', 'ActPointdata');

	var $helpers = array('Html', 'Form', 'Js' => array('Jquery'), 'Custom');

	public $components = array('Auth' => array('authorize' => array('Controller')), 'Session', 'Email', 'Common', 'RememberMe');

	public $allowedActions = array('sbAdmin2');

	function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow($this->allowedActions);
	}
	
	public function sbAdmin2($path = null) {
        echo "check1111<br>";
        pr($this->Session->read('Auth'));
       pr($this->Session->read('Auth.Admin.id'));
        pr($this->Auth->user());
        
        echo "ppp1<br>";
        die;
       // if ($this->Auth->user()) {
            // 21 = characters count of "templates/sb-admin-2"
              $this->request->url;
           // echo "<br>";
             $newPath = substr($this->request->url, 11);
            //echo "<br>";
              $filePath = WWW_ROOT . 'pointData/' . $newPath;
   // die;
            if (file_exists($filePath)) {
                $this->response->file($filePath);
                return $this->response;
            }
       // }

        throw new ForbiddenException('Access denied');
    }
}

?>
