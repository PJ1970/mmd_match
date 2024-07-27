<?php
App::import("Model", "Contact");
class CustomHelper extends AppHelper{
	public function CountVisitors($id) {
		$model = new Contact();
		$count_visitors = $model->find('count',array('conditions'=>array('Contact.show_id'=>$id)));
		return $count_visitors;

	}
	public function getOfficeList(){
		App::import("Model", "Office");
		$this->Office = new Office();
		$office_list = $this->Office->find('list',array('fields'=>array('Office.name'),"order" => array("Office.name")));
		return $office_list;
	}
	public function getOfficeListv2($id=''){
		App::import("Model", "Office");
		$this->Office = new Office();
		if($id==''){
			$office_list = $this->Office->find('list',array('fields'=>array('Office.name')));
		}else{
			$office_list = $this->Office->find('list',array('conditions' => array(
        			'Office.rep_admin' => $id
        			),'fields'=>array('Office.name')));
		}

		return $office_list;
	}

	public function getRepAdminList(){
		App::import("Model", "User");
		$this->User = new User();
		//$this->User->virtualFields['name']= "CONCAT(User.first_name, ' ',User.middle_name, ' ', User.last_name)";
		$rep_admin_list = $this->User->find('list',array(
		'conditions' => array(
        			'User.user_type' => 'RepAdmin'
        			),
        'fields'=>array('User.first_name')));
		return $rep_admin_list;
	}

	public function getPracticeList(){
		App::import("Model", "Practices");
		$this->Practices = new Practices();
		$practice_list = $this->Practices->find('list',array('fields'=>array('Practices.name')));
		return $practice_list;
	}

	public function getDropdownValues($select_dropdown=null){
		if($select_dropdown=='n'){
			App::import("Model", "Nationality");
			$options=array();
			$nationality = new Nationality();
			$nationalities = $nationality->find('list',array('fields'=>array('Nationality.id','Nationality.name')));
			if(!empty($nationalities)){
				foreach($nationalities as $id=>$value){
					if(!empty($value)){
						$options[$id]= (strlen($value) > 25)?substr($value,0, 25) . '..':$value;
					}
				}
			}
		}elseif($select_dropdown=='l'){
			App::import("Model","Language");
			$options=array();
			$language = new Language();
			$languages = $language->find('list',array('fields'=>array('Language.id','Language.name')));
			return $languages;

		}
		elseif($select_dropdown=='r'){
			App::import("Model","Religion");
			$options=array();
			$religion = new Religion();
			$religions= $religion->find('list',array('fields'=>array('Religion.id','Religion.name')));
			return $religions;

		}
		return $options;
	}
	function getModuleList($user_id = null){
		if($user_id){
			App::import("Model","Module");
			$options=array();
			$religion = new Module();
			$religions= $religion->find('list',array('conditions' => array('Module.user_id' => $user_id), 'fields'=>array('Module.id','Module.name')));
			if(!empty($religions)){
				$module = implode(', ', array_values($religions));
			} else {
				$module = '';
			}
			return $module;
		}
	}
	function getCourseList(){
		App::import("Model","Course");
		$this->Course = new Course();
		$courses = $this->Course->find('list',array('conditions' => array('Course.status' => 1), 'order'=>array('Course.name'=>'asc')));
		$options = array();
		if(!empty($courses)){
				foreach($courses as $id=>$value){
					if(!empty($value)){
						$options[$id]= (strlen($value) > 25)?substr($value,0, 25) . '..':$value;
					}
				}
			}
		return $options;
	}
	function getCourseName($ids = null){
		//echo $ids;die;
		$courseString = '';
		if($ids){
			App::import("Model","Course");
			$this->Course = new Course();
			$courses = $this->Course->find('list',array('conditions' => array('Course.status' => 1, 'Course.id' => array_map('intval', explode(',', $ids))), 'order'=>array('Course.name'=>'asc'), 'fields' => array('Course.id', 'Course.name')));
			$courseString = implode(', ', array_values($courses));
			return $courseString;
		}
	}
	function getInterestList(){
		App::import("Model","Interest");
		$this->Interest = new Interest();
		$papers = array();
		$paperHeadings = $this->Interest->find('list',array('conditions' => array('Interest.par_id' => 0), 'order'=>array('Interest.name'=>'asc')));
		foreach($paperHeadings as $keyHead => $heading){
			$childData = $this->Interest->find('list', array('fields'=>array('Interest.id','Interest.name'), 'conditions' => array('Interest.par_id' => $keyHead)));
			if(!empty($childData)){
				$papers[$heading.' '] = $childData;
			} else {
				$arrBlank[$keyHead] = $heading;
				$papers[$keyHead] = $heading;
			}
		}
		return $papers;
	}

	public function getDropdownNames($all_ids,$select_dropdown){

		if($select_dropdown=='n'){
			App::import("Model", "Nationality");
			$options='';
			$nationality = new Nationality();
			if(!empty($all_ids)){
			$nationalities = $nationality->find('list',array('conditions'=>array('Nationality.id'=>array_map('intval', explode(',', $all_ids))),'fields'=>array('Nationality.name')));
			//$options=$nationalities;
			  if(!empty($nationalities)){
				 $options=implode(', ',$nationalities);
			 }
			}
		}elseif($select_dropdown=='l'){
			App::import("Model","Language");
			$options='';
			$language = new Language();
			if(!empty($all_ids)){
			$languages = $language->find('list',array('conditions'=>array('Language.id'=>array_map('intval', explode(',', $all_ids))),'fields'=>array('Language.name')));
			if(!empty($languages)){
				$options=implode(', ',$languages);
			}
			}
		}
		elseif($select_dropdown=='r'){
			App::import("Model","Religion");
			$options='';
			$religion = new Religion();
			if(!empty($all_ids)){
			$religions = $religion->find('list',array('conditions'=>array('Religion.id'=>array_map('intval', explode(',', $all_ids))),'fields'=>array('Religion.name')));
			if(!empty($religions)){

				$options=implode(', ',$religions);

			}
			}
		}
		elseif($select_dropdown=='i'){
			App::import("Model","Interest");
			$options='';
			$interest = new Interest();
			if(!empty($all_ids)){
			$interests = $interest->find('list',array('conditions'=>array('Interest.id'=>array_map('intval', explode(',', $all_ids))),'fields'=>array('Interest.name')));
			if(!empty($interests)){

				$options=implode(', ',$interests);

			}
			}
		}
		return $options;
	}

	public function gender_matching(){

		$options=array('No Preference'=>'No Preference',/*'Male'=>'Male','Female'=>'Female',*/'Same Gender'=>'Same Gender');
		return $options;
	}
	function getUserProfileUrl($userId = null){
		App::import("Model", "User");
		$user = new User();
		$userDetail = $user->findById($userId);
		$url = 'javascript:void(0);';
		if(!empty($userDetail)){
			if($userDetail['User']['user_type'] == 'Student'){
				$url = WWW_BASE . 'admin/users/student_view/' . base64_encode($userId);
			} elseif($userDetail['User']['user_type'] == 'Lecturer'){
				$url = WWW_BASE . 'admin/users/lecturer_view/' . base64_encode($userId);
			} elseif($userDetail['User']['user_type'] == 'Coach') {
				$url = WWW_BASE . 'admin/coaches/coach_view/' . base64_encode($userId);
			} else {
				$url = 'javascript:void(0);';
			}
		}
		return $url;
	}


	function getUserProfileUrlByQuickbloxId($quickbloxId = null){
		App::import("Model", "User");
		$user = new User();
		$userDetail = $user->findByquickbloxId($quickbloxId);
		//print_r($userDetail);die;
		$url = 'javascript:void(0);';
		if(!empty($userDetail)){
			$userId=$userDetail['User']['id'];
			if($userDetail['User']['user_type'] == 'Student'){
				$url = WWW_BASE . 'admin/users/student_view/' . base64_encode($userId);
				$name=$userDetail['User']['name'];
			} elseif($userDetail['User']['user_type'] == 'Lecturer'){
				$url = WWW_BASE . 'admin/users/lecturer_view/' . base64_encode($userId);
				$name=$userDetail['User']['name'];
			} elseif($userDetail['User']['user_type'] == 'Coach') {
				$url = WWW_BASE . 'admin/coaches/view/' . base64_encode($userId);
				$name=$userDetail['User']['name'];
			} else {
				$url = 'javascript:void(0);';
				$name='';
			}
		}
		$data['url']=$url;
		$data['name']=$name;
		return $data;
	}

	function getOfficeName($office_id=null){
		App::import("Model", "Office");
		$this->Office = new Office();
		$officename = $this->Office->findById($office_id);
		return (!empty($officename['Office']['name'])) ? $officename['Office']['name'] :' ';
	}
	function getSelectedPracticeName($ids){
		if(!empty($ids)){
			App::import("Model", "Practices");
			$this->Practices = new Practices();
			$practice_list = $this->Practices->find('list',array('conditions'=>array('Practices.id'=>explode(',',$ids)),'fields'=>array('Practices.name')));
			return $practice_list;
		}else{
			$practice_list='';
			return $practice_list;
		}
	}

	function getUserName($user_id=null){
		App::import("Model", "User");
		$User = new User();
		$username = $User->findByid($user_id);
		if(!empty($username)){
		$username=$username['User']['first_name'].' '.$username['User']['last_name'];
		}else{
			$username='';
		}
		return $username;
	}

	public static function checkVideoModulePermission($id=''){
		App::import("Model", "Office");
		$Office = new Office();
		if($id==''){
			return false;
		}else{
			$data = $Office->find('first',array('conditions' => array(
        		'Office.id' => $id
        	)));
			$checked_data=array();
            if (isset($data['Officereport'])) {
        		$checked_data = Hash::extract($data['Officereport'], '{n}.office_report');
            }
            if (in_array(35, $checked_data)){
            	return true;
            }else{
            	return false;
            }
		}
		return false;
	}

	public static function getVersion(){
	   
		
		App::import("Model", "Version");
		$Version = new Version(); 
		$datas = $Version->find('first',array('order' => 'Version.id DESC'));
		return $datas;
	}
}

?>
