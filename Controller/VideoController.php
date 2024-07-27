<?php

App::uses('AppController','Controller');
App::uses('CakeEmail','Network/Email');
App::import('Controller','ChatApi');

class VideoController extends AppController {
	public $uses = array('Admin','User','Patient','Office', 'NewUserDevice', 'Cms', 'Video');
			
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
	     $db=$this->Video;
	    
		$user=$this->Session->read('Auth.Admin'); 
		$results = $db->query("SET session wait_timeout=28800", FALSE);
		$results = $db->query("SET session interactive_timeout=28800", FALSE);
 	    if($this->request->is(array('post','put'))) {  
	        if(isset($this->request->data['Video']['video'])&&(!empty($this->request->data['Video']['video']))){ 

	             if (!empty($this->request->data['Video']['video']['tmp_name']) && is_uploaded_file($this->request->data['Video']['video']['tmp_name']) ) { 
                    $filename = basename($this->request->data['Video']['video']['name']); 
                    $Office_folder_name = 'OV_'.strtoupper(base_convert( $user['office_id'], 10, 32 ));
					 $path = ROOT . '/app/webroot/files/video/uploads/' . $Office_folder_name;
		                if(is_dir($path)) {
		                } else {
		                    mkdir($path);
		                }

                    if(move_uploaded_file($this->data['Video']['video']['tmp_name'], WWW_ROOT . DS . 'files/video/uploads' . DS . $Office_folder_name . DS . $filename)){ 
                        $this->request->data['Video']['video']=$filename; 
                        $data['Video']['video']=$this->request->data['Video']['video'];
                        $data['Video']['name']=$this->request->data['Video']['name']; 
                        $data['Video']['office_id'] = $user['office_id'];
                		if($this->Video->save($data)){
                           $this->Session->setFlash('Video has been added successfully.','message',array('class'=>'message'));
                           unset($this->request->data);
                        } 
                    }
                }
						  
			}else{
				$this->Video->validationErrors['video'] = array("Please try again.");
			} 
	    }  
        $conditions = array();
        if(isset($this->request->query['search_name'])  && ($this->request->query['search_name']) !=''){
           $conditions[] = array('Video.name like' => '%'.$this->request->query['search_name'].'%');  
        } 
        $conditions[] = array('Video.office_id' => $user['office_id']);
        
		$datas = $this->Video->find('all', array('conditions'=>$conditions)); 
        
		$this->set(compact('datas')); 
	} 

    public function admin_list()  
    {    
        $user=$this->Session->read('Auth.Admin');  
        $conditions = array();
        if(isset($this->request->query['search_name'])  && ($this->request->query['search_name']) !=''){
           $conditions[] = array('Video.name like' => '%'.$this->request->query['search_name'].'%');  
        } 
        $conditions[] = array('Video.office_id' => $user['office_id']);
        
        $datas = $this->Video->find('all', array('conditions'=>$conditions));  
        $this->set(compact('datas')); 
    }  
	
	public function admin_edit(){
	    if($this->request->is(array('post','put'))) { 
	       
	        $data=$this->Video->find('first',array('conditions'=>array('Video.id'=>$this->request->data['VideoEdit']['id']))); 
	        
	        
            if (!empty($this->request->data['VideoEdit']['video']['tmp_name']) && is_uploaded_file($this->request->data['VideoEdit']['video']['tmp_name']) ) {
            	$Office_folder_name = 'OV_'.strtoupper(base_convert( $user['office_id'], 10, 32 ));
                $video_filename = basename($this->request->data['VideoEdit']['video']['name']);
                move_uploaded_file($this->data['VideoEdit']['video']['tmp_name'], WWW_ROOT . DS . 'files/video/uploads' . DS . $Office_folder_name . DS . $video_filename);
                $data['Video']['video']=$video_filename; 
            }
	        
             $data['Video']['name']=$this->request->data['VideoEdit']['name']; 
             
    		if($this->Video->save($data)){
               $this->Session->setFlash('Video has been updated successfully.','message',array('class'=>'message'));
               unset($this->request->data);
            }
	    }
	    return $this->redirect(
            array('controller' => 'video', 'action' => 'index')
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
        $name = '';
        $data=$this->Video->find('first',array('conditions'=>array('Video.id'=>$id_new))); 
        if(isset($data['Video'])){  
            $fileName=$data['Video']['video']; 
            $name=$data['Video']['name'];   
        }else{ 
          $response['status']=0; 
        }   
    $this->set(compact('fileName','name')); 
    }
   
     public function admin_delete_video($id=null){
          if($this->Video->delete($id)){
			$this->Session->setFlash("Video has been deleted successfully.",'message',array('class' => 'message'));
			$this->redirect($this->referer());
		}else{ 
			$this->Session->setFlash("Video not deleted.",'message',array('class' => 'message'));
		}
		$this->redirect($this->referer());  
     }
    
}
?>