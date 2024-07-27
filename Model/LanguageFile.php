<?php
class LanguageFile extends AppModel {
	public $name = 'LanguageFile';
	public $useTable='language_files';
	 
	 public $belongsTo = array(
        'Language' => array(
            'className' => 'Language'
        )
    );
}
?>