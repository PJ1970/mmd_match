<?php
	App::uses('AppController', 'Controller');
	App::uses('CakeEmail', 'Network/Email');
	class PresetController extends AppController{
		public $components = array('Auth' => array('authorize' => array('Controller')), 'Session', 'Email', 'Common', 'RememberMe');
		public function admin_copy(){
			$office = $this->Session->read('Auth.Admin'); 
			$this->loadModel('User');
			$this->loadModel('UserPresets');
			$this->loadModel('UserPresetData');
			$data = $this->User->find('list', array('fields' => array('id','complete_name'),'conditions' => array('User.office_id' => $office['office_id'])));
			if($this->request->is(array('post','put'))){ 
				$admin_user_presets = $this->UserPresets->find('all', array('conditions'=>array('UserPresets.user_id'=>$office['id'])));
				foreach($admin_user_presets as $key => $admin_user_preset){ 
					unset($admin_user_preset['UserPresets']['id']);
					unset($admin_user_preset['UserPresets']['user_id']);
					foreach($this->request->data['staff_list'] as $key => $value){ 
						$user_user_preset = $this->UserPresets->find('first', array('conditions'=>array('UserPresets.user_id'=>$value,'UserPresets.page'=>$admin_user_preset['UserPresets']['page'])));
						$id= null;
						if(!empty($user_user_preset['UserPresets']['id'])){
							$id = $user_user_preset['UserPresets']['id'];
						}
						$user_user_preset = $admin_user_preset;
						$user_user_preset['UserPresets']['user_id'] = $value;
						if($id){
							$user_user_preset['UserPresets']['id']= $id;
						}
						$this->UserPresets->create();
						$this->UserPresets->save($user_user_preset); 
					}
				} 
				$admin_user_presets_datas = $this->UserPresetData->find('all', array('conditions'=>array('UserPresetData.user_id'=>$office['id'])));
				foreach($admin_user_presets_datas as $key => $admin_user_presets_data){ 
					unset($admin_user_presets_data['UserPresetData']['id']);
					unset($admin_user_presets_data['UserPresetData']['user_id']);
					foreach($this->request->data['staff_list'] as $key => $value){ 
						$user_user_preset_data = $this->UserPresetData->find('first', array('conditions'=>array('UserPresetData.user_id'=>$value,'UserPresetData.user_presets'=>$admin_user_presets_data['UserPresetData']['user_presets'])));
						$id= null;
						if(!empty($user_user_preset_data['UserPresetData']['id'])){
							$id = $user_user_preset_data['UserPresetData']['id'];
						}
						$user_user_preset_data = $admin_user_presets_data;
						$user_user_preset_data['UserPresetData']['user_id'] = $value;
						if($id){
							$user_user_preset_data['UserPresetData']['id']= $id;
						}
						$this->UserPresetData->create();
						$this->UserPresetData->save($user_user_preset_data); 
					}
				}
			}
			$this->set(compact(['data']));
		}
	}
 ?>
 