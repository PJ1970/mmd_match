<?php
App::uses('AppController','Controller');
App::uses('CakeEmail','Network/Email');
App::import('Controller','ChatApi');
class ApkController extends AppController {
	public $uses = array('Admin','User','Patient','Office', 'NewUserDevice', 'Cms', 'Apk');
	var $helpers = array('Html', 'Form','Js' => array('Jquery'), 'Custom', 'Dropdown');
    public $components = array('Auth'=>array('authorize'=>array('Controller')),'Session','Email','Common','RememberMe','Upload');
	public $allowedActions =array('admin_download','download');
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->deny();
		$this->Auth->allow($this->allowedActions);	
		if ($this->request->is('ajax')){
			$this->layout = '';
		}
	}
	public function admin_index()  
	{   
        if($this->Session->read('Auth.Admin.user_type') == 'Admin'){
	     $db=$this->Apk;
// 	        $results = $db->query("SHOW VARIABLES LIKE '%timeout%'", TRUE);
// echo "<pre>";
// var_dump($results);
// echo "</pre>";
$results = $db->query("SET session wait_timeout=28800", FALSE);
// UPDATE - this is also needed
$results = $db->query("SET session interactive_timeout=28800", FALSE);
// $results = $db->query("SHOW VARIABLES LIKE '%timeout%'", TRUE);
// echo "<pre>";
// var_dump($results);
// echo "</pre>";
// die();
	    if($this->request->is(array('post','put'))) { 
	        if(isset($this->request->data['Apk']['apk'])&&(!empty($this->request->data['Apk']['apk']))){ 
	             if (!empty($this->request->data['Apk']['apk']['tmp_name']) && is_uploaded_file($this->request->data['Apk']['apk']['tmp_name']) ) { 
                    $filename = basename($this->request->data['Apk']['apk']['name']); 
                    if(move_uploaded_file($this->data['Apk']['apk']['tmp_name'], WWW_ROOT . DS . 'files/apk/uploads' . DS . $filename)){
                          //if (!empty($this->request->data['Apk']['video']['tmp_name']) && is_uploaded_file($this->request->data['Apk']['video']['tmp_name']) ) { 
                    $video_filename = basename($this->request->data['Apk']['video']['name']);
                    move_uploaded_file($this->data['Apk']['video']['tmp_name'], WWW_ROOT . DS . 'files/apk/video' . DS . $video_filename);
                        $this->request->data['Apk']['apk']=$filename;
                        $data=$this->Apk->find('first',array('conditions'=>array('Apk.device_type'=>$this->request->data['Apk']['device_type'], 'Apk.apk_type'=>$this->request->data['Apk']['apk_type'])));
                        $data['Apk']['apk']=$this->request->data['Apk']['apk'];
                        $data['Apk']['build']=$this->request->data['Apk']['build'];
                        $data['Apk']['version']=$this->request->data['Apk']['version'];
                        $data['Apk']['minimum_version']=$this->request->data['Apk']['version'];
                        $data['Apk']['device_type']=$this->request->data['Apk']['device_type'];
                        $data['Apk']['apk_type']=$this->request->data['Apk']['apk_type'];
                        $data['Apk']['video']=$video_filename;
                        $data['Apk']['instruction']=$this->request->data['Apk']['instruction'];
                        $data['Apk']['comments']=$this->request->data['Apk']['comments'];
                        $data['Apk']['updated_at']=date("Y-m-d H:i:s");
                		if($this->Apk->save($data)){
                           $this->Session->setFlash('APK has been added successfully.','message',array('class'=>'message'));
                           unset($this->request->data);
                        }
                          //}  
                    }
                }
						/*$profile_pic=time().$this->request->data['Apk']['apk']['name'];
						$image_type=strtolower(substr($profile_pic,strrpos($profile_pic,'.')+1));
						$uploadFiles = $this->request->data['Apk']['apk'];
						$fileName = $profile_pic;
						$upload_path="apk/uploads/"; 
						$data12 = array('type' => 'resize', 'size' => array(150, 150), 'output' => $image_type, 'quality' => 100);
						if($this->Upload->upload($uploadFiles, $upload_path, $fileName)){
						    $this->request->data['Apk']['apk']=$profile_pic;
    						if($this->Apk->save($this->request->data)){
                	           $this->Session->setFlash('APK has been added successfully.','message',array('class'=>'message'));
                	           unset($this->request->data);
                	        }
						}*/   
					}else{
						$this->Apk->validationErrors['apk'] = array("Please try again.");
					} 
	    } 
        $conditions = array();
        if(isset($this->request->query['apk_type'])  && ($this->request->query['apk_type']) !=''){
           $conditions[] = array('Apk.apk_type' => $this->request->query['apk_type']);  
        }
        if(isset($this->request->query['device_type']) && ($this->request->query['device_type']) !=''){
           $conditions[] = array('Apk.device_type' => $this->request->query['device_type']);
        }
		$datas = $this->Apk->find('all', array('conditions'=>$conditions)); 
		$this->set(compact('datas')); 
        }else{
            $this->redirect(WWW_BASE.'admin/dashboards/index');
        } 
	}  
	public function admin_edit(){
	    if($this->request->is(array('post','put'))) { 
	        $db=$this->Apk;  
            $results = $db->query("SET session wait_timeout=28800", FALSE); 
            $results = $db->query("SET session interactive_timeout=28800", FALSE);
	        $data=$this->Apk->find('first',array('conditions'=>array('Apk.id'=>$this->request->data['ApkEdit']['id']))); 
	        if (!empty($this->request->data['ApkEdit']['apk']['tmp_name']) && is_uploaded_file($this->request->data['ApkEdit']['apk']['tmp_name']) ) {
                $apk_filename = basename($this->request->data['ApkEdit']['apk']['name']);
                move_uploaded_file($this->data['ApkEdit']['apk']['tmp_name'], WWW_ROOT . DS . 'apk/apk' . DS . $apk_filename);
                $data['Apk']['apk']=$apk_filename; 
            }
            if (!empty($this->request->data['ApkEdit']['video']['tmp_name']) && is_uploaded_file($this->request->data['ApkEdit']['video']['tmp_name']) ) {
                $video_filename = basename($this->request->data['ApkEdit']['video']['name']);
                move_uploaded_file($this->data['ApkEdit']['video']['tmp_name'], WWW_ROOT . DS . 'apk/video' . DS . $video_filename);
                $data['Apk']['video']=$video_filename; 
            }
             $data['Apk']['minimum_version']=$this->request->data['ApkEdit']['minimum_version']; 
             $data['Apk']['instruction']=$this->request->data['ApkEdit']['instruction']; 
             $data['Apk']['apk_type']=$this->request->data['ApkEdit']['apk_type']; 
             $data['Apk']['comments']=$this->request->data['ApkEdit']['comments']; 
             $data['Apk']['updated_at']=date("Y-m-d H:i:s");
             // pr($data);die();
    		if($this->Apk->save($data)){
               $this->Session->setFlash('APK has been updated successfully.','message',array('class'=>'message'));
               unset($this->request->data);
            }
	    }
	    return $this->redirect(
            array('controller' => 'apk', 'action' => 'index')
        );
	}
	  public function download($id, $type=null){
        if($type!=null){
            $id=$type;
        }
	     $id_new= base_convert( $id , 36, 10 ); 
        $fileName='';  
        $instruction='Select the button to update your VF2000 software to the latest released version.';
        $video='';
        $data=$this->Apk->find('first',array('conditions'=>array('Apk.id'=>$id_new))); 
        if(isset($data['Apk'])){  
            $fileName=$data['Apk']['apk']; 
            $instruction=$data['Apk']['instruction']; 
            $video=$data['Apk']['video']; 
            ?>  <!--<center style="padding-top: 30px;"><a href="https://www.vibesync.com//apk/uploads/<?php echo $fileName ?>" title="Download apk " download="" style="text-decoration: none;background: #1f3ae0;color: #f5f1f1; margin: 7px; padding: 7px; border-radius: 25px;border: 1px solid;"> Download </a></center> --> <?php
            // $downloadFileName = 't4\1599682062_1064111.pdf'; 
            // $file_url = 'https://www.vibesync.com/apk/uploads/' . $fileName; 
            // header('Content-Description: File Transfer'); 
            // header('Content-Type: text/x-generic'); 
            // header('Content-Disposition: attachment; name="t4"; filename='.$fileName); 
            // ob_clean(); 
            // flush(); 
            // readfile($file_url); 
            //echo "<script>window.close();</script>";
          //  exit;  
        }else{ 
          $response['status']=0;  
         // echo "<script>window.close();</script>";
        //  exit;
        }  
       // echo "<script>window.close();</script>";
    //     exit;   
    // die(); 
    $this->set(compact('fileName','instruction','video')); 
    }
     public function admin_test(){
         $this->loadModel('Masterdata'); 
         $this->Masterdata->unbindModel(
    array('belongsTo' => array('User','Patient','Test'))
);
$this->Masterdata->deleteAll(array('Masterdata.test_name' => array("Central_10_2_PICO", "Central_24_1_PICO", "Central_24_2_PICO", "Central_30_1_PICO", "Central_30_2_PICO")), false);
         $data=$this->Masterdata->find('all',array('conditions'=>array('Masterdata.test_name'=> array("Central_10_2", "Central_24_1", "Central_24_2", "Central_30_1", "Central_30_2"))));
         echo "<pre>";
         $data2=array();
         foreach($data as $pdata){
             $data2=$pdata;
             $this->Masterdata->create();
             unset($data2['Masterdata']['id']);
             $data2['Masterdata']['test_name']=$data2['Masterdata']['test_name'].'_PICO';
             foreach($data2['VfMasterdata'] as $key => $value){
                 unset($data2['VfMasterdata'][$key]['id']); 
                 unset($data2['VfMasterdata'][$key]['master_data_id']); 
             }
              $this->Masterdata->saveAll($data2);
                  $this->Masterdata->create();
         }
          print_r($data2);
         //  print_r($data);
         
         die();
     }
     public function admin_delete_apk($id=null){
          if($this->Apk->delete($id)){
			$this->Session->setFlash("Apk has been deleted successfully.",'message',array('class' => 'message'));
			$this->redirect($this->referer());
		}else{ 
			$this->Session->setFlash("Apk not deleted.",'message',array('class' => 'message'));
		}
		$this->redirect($this->referer());
     }
}
?>