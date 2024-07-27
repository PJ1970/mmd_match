<?php
App::uses('AppModel', 'Model'); 
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
class CloseUser extends AppModel
{

 
public $useTable = 'users';
	public $name = 'CloseUser';
}