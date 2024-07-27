<?php
App::uses('AppModel', 'Model'); 
class KnowledgeBase extends AppModel
{
		
	public $name = 'KnowledgeBase';
    public $validate = array(
       'title'=>array(
			'rule'=>'notBlank',
			'required'=>true,
			'message'=>'Please enter title'
		)/* ,
		'youtube_id' => array(
			'rule' => '/^((http\:\/\/){0,}(www\.){0,}(youtube\.com){1}|(youtu\.be){1}(\/watch\?v\=[^\s]){1})$/',
			'allowEmpty' =>  true,
			'message' => 'Please enter a valid youtube url'
		) */
		
    );
}