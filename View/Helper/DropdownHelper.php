<?php 
/* /app/View/Helper/LinkHelper.php (using other helpers) */
App::uses('AppHelper', 'View/Helper');

class DropdownHelper extends AppHelper {
	public function ageDropdown(){
		$age = ['0-10'=>'0-10', '11-20'=>'11-20', '21-30'=>'21-30', '31-40'=>'31-40', '41-50'=>'41-50', '51-60'=>'51-60', '61-70'=>'61-70', '71-80'=>'71-80', '81-90'=>'81-90', '91-150'=>'>91'];
		return $age;
	}
	public function racesDropdown(){
		$races = [
				"White"=>"White",
				"Hispanic or Latino"=>"Hispanic or Latino",
				"American Indian or Alaskan Native"=>"American Indian or Alaskan Native",
				"Asian"=>"Asian",
				"Native Hawaiian or Other Pacific Islander"=>"Native Hawaiian or Other Pacific Islander",
				"Black or African American"=>"Black or African American",
				"Middle Eastern"=>"Middle Eastern"
			];
		return $races; die;
	}
}