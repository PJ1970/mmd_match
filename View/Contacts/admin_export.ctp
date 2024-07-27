<?php 
	$i = 0;
	$header = array('Name', 'Email', 'Company Name', 'Company Website','Mobile', 'Phone',  'Created On','Intrested In','Show Name');
	//pr($data); die;
	foreach ($data as $contact){
		
		if($i == 0){	
			$this->Csv->addRow($header);	
		}
		unset($contact['Contact']['id']);
		$contact['Contact']['created'] = date('M d, Y',strtotime($contact['Contact']['created']));
		$contact['Contact']['interest'] = $contact['Contact']['interests'];
		unset($contact['Contact']['interests']);
		$contact['Contact']['show']=$contact['Show']['name'];
		$line = $contact['Contact'];
		$this->Csv->addRow($line);
		$i++;	
	}
	$filename=$export_file_name.'.csv';
   echo $this->Csv->render($filename);
?>