<?php
App::uses('AppController','Controller');
App::uses('CakeEmail','Network/Email');
App::import('Controller','ChatApi');

class LanguagesController extends AppController {
	public $uses = array('Language', 'LanguageFile','Officelanguage');
			
	var $helpers = array('Html', 'Form','Js' => array('Jquery'), 'Custom');

    public $components = array('Auth'=>array('authorize'=>array('Controller')),'Session','Email','Common');
	public $allowedActions =array();
    
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->deny();
		$this->Auth->allow($this->allowedActions);	
	}
	  
	
	/*Test device list*/
	public function admin_listing(){
		if($this->Session->read('Auth.Admin.user_type')=='Admin'){
    		$conditions = array('Language.is_delete' =>'0');
    		if(!empty($this->request->query['search'])){
    			$search = strtolower(trim($this->request->query['search']));
    			$conditions['OR'][] = array('Lower(Language.name) like'=> '%'.$search.'%'); 
    			$this->set(compact('search'));
    		}
    		$this->paginate=array('conditions'=>$conditions,
    		'limit'=>10,
    		'order'=>array('Language.name'=>'DESC')); 
    		$datas=$this->paginate('Language'); 
    		$this->set(compact('datas'));
		}else{
            $this->redirect(WWW_BASE.'admin/dashboards/index');
        }
	}
	
	public function admin_add($id=null) {
		if($this->Session->read('Auth.Admin.user_type')=='Admin'){
		if($this->request->is(array('post'))) { 
		     	$folder_name = strtoupper($this->request->data['Language']['code']); 
		     	$path = ROOT . '/app/webroot/uploads/' . $folder_name;
                if(is_dir($path)) {
                    $this->Session->setFlash('Code allready Exiest.','message',array('class'=>'message'));
                    $this->redirect(array('controller'=>'languages','action'=>'admin_add'));
                } else {
                    mkdir($path);
                }
			if($this->Language->save($this->request->data)) {
				$this->Session->setFlash('Language has been added successfully.','message',array('class'=>'message'));
					$this->redirect(array('controller'=>'languages','action'=>'admin_listing'));
			}
		} 
		}else{
            $this->redirect(WWW_BASE.'admin/dashboards/index');
        }
	}
	
	public function admin_edit($id=null) {
		if($this->Session->read('Auth.Admin.user_type')=='Admin'){ 
		if(!empty($id)) {
			$data = $this->Language->findById($id);
			if($this->request->is(array('post','put'))) {
			    unset($this->request->data['Language']['code']);
			   // pr($this->request->data);die;
			    if($this->Language->save($this->request->data)) {
    				$this->Session->setFlash('Language has been updated successfully.','message',array('class'=>'message'));
    				$this->redirect(array('controller'=>'languages','action'=>'admin_listing'));
    			}
			}
		//	pr($data);die;
			$this->set(compact('data','id'));
		}
		}else{
            $this->redirect(WWW_BASE.'admin/dashboards/index');
        }
	}
	
	/*Test Device delete*/
	public function admin_delete($id=null) {
		if(!empty($id)) {
		    if($this->Session->read('Auth.Admin.user_type')=='Admin'){ 
		        $data = $this->Language->findById($id);
    			if($this->Language->delete($id)) { 
    			    try{
    			    $this->LanguageFile->deleteAll(array('LanguageFile.language_id'=>$id));
    			    $this->Officelanguage->deleteAll(array('Officelanguage.language_id'=>$id));
    			    }catch(Exception $e){ 
    			        pr($e);die;
				}
    			    $folder_name = strtoupper($data['Language']['code']); 
		     	echo $path = ROOT . '/app/webroot/uploads/' . $folder_name;
                if(is_dir($path)) {
                  foreach (scandir($path) as $item) {
                        if ($item == '.' || $item == '..') {
                            continue;
                        }
                        unlink($path.'/'.$item); 
                    } 
        			rmdir($path);
                }
    				$this->Session->setFlash('Language has been deleted successfully.','message',array('class'=>'message'));
    				$this->redirect(array('controller'=>'languages','action'=>'admin_listing'));
    			}
			}
		}
	}
	
	public static function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}
	public function admin_delete_file($id=null) {
		if(!empty($id)) {
		    if($this->Session->read('Auth.Admin.user_type')=='Admin'){ 
    			if($this->LanguageFile->delete($id)) { 
    				$this->Session->setFlash('Language has been deleted successfully.','message',array('class'=>'message'));
    				$this->redirect($this->referer());
    			}
			}
		}
		$this->redirect($this->referer());
	}
	
	
	public function admin_upload($id=null) {
	    
	 //   $db=$this->Language;   
//ini_set('max_file_uploads', '2'); 
//$db->query("SET max_file_uploads", 60);
		if($this->Session->read('Auth.Admin.user_type')=='Admin'){ 
		if(!empty($id)) {
			$data = $this->Language->findById($id);
			if($this->request->is(array('post','put'))) {
			    $folder_name = strtoupper($this->request->data['Language']['code']);
			   // pr($this->request['form']['documents']);die;
			    foreach($this->request['form']['documents']['name'] as $key => $value){
			        if (!empty($this->request['form']['documents']['tmp_name'][$key]) && is_uploaded_file($this->request['form']['documents']['tmp_name'][$key]) ) {
                        $apk_filename = basename($this->request['form']['documents']['name'][$key]); 
                        $ext = (explode(".", $apk_filename));
                        //pr($ext);
                        $type=".".end($ext);
                        //die; 
                        $first_number = (int) substr($apk_filename, 0, 4);
                        if($first_number >0){
                        	$apk_filename='BL-'.substr($apk_filename, 0, 4).$type;
                        }else{
                        	$apk_filename=substr($apk_filename, 0, 7).$type;
                        }
                        move_uploaded_file($this->request['form']['documents']['tmp_name'][$key], WWW_ROOT . DS . 'uploads/' .$folder_name. DS . $apk_filename);
                        $datas = $this->LanguageFile->find('first', array('conditions' => array('LanguageFile.name' => substr($apk_filename, 0, 7),'LanguageFile.language_id'=>$this->request->data['Language']['id'])));
                        $datas['LanguageFile']['file_name']=$apk_filename;
                        $datas['LanguageFile']['language_id']=$this->request->data['Language']['id'];
                        $datas['LanguageFile']['name']=substr($apk_filename, 0, 7);
                        $this->LanguageFile->create();
                        $this->LanguageFile->save($datas);
                        
                    } 
			    }
			      
			   $data['Language']['modified']=date("Y-m-d H:i:s");  
			   $this->Language->save($data);
			   $this->Session->setFlash('File has been uploaded successfully.','message',array('class'=>'message'));
    		   $this->redirect(array('controller'=>'languages','action'=>'admin_listing'));
			}
			$this->set(compact('data','id'));
		}
		}else{
            $this->redirect(WWW_BASE.'admin/dashboards/index');
        }
	}
    
    
    public function admin_view($id=null) {
		if($this->Session->read('Auth.Admin.user_type')=='Admin'){ 
		if(!empty($id)) {
			$data = $this->Language->findById($id);
		    $datas = $this->LanguageFile->find('all', array('conditions' => array('LanguageFile.language_id'=>$id))); 
			$this->set(compact('data','datas','id'));
		}
		}else{
            $this->redirect(WWW_BASE.'admin/dashboards/index');
        }
	}
	
}

?>