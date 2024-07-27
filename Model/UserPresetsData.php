<?php
App::uses('AppModel', 'Model'); 
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
class UserPresetData extends AppModel
{
	public $name = 'UserPresetData';
	public $userTable='mmd_user_preset_data';

    

}