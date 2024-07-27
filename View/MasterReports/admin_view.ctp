 <?php //pr($data); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel">Master Report Details</h4>
</div>
<div class="modal-body">
	
		<div class="form-group  col-md-12">
			<label for="recipient-name" class=" col-md-4 form-control-label">Date Of Test:</label>
			<span  class=" col-md-5"><?php  
			echo date('d F Y h:i:s a',strtotime($data['Masterdata']['created']));
			?></span>
			
			
		</div>		
	
		
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Patient Name:</label>
			<span  class=" col-md-5">
			<?php 
			#echo $data['Patient']['first_name'].' '.$data['Patient']['last_name'];
			echo $data['Masterdata']['test_name'].' , '.$data['Masterdata']['age_group'];
			?>
			</span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Eye Value:</label>
			<span  class=" col-md-4"><?php echo $od_os =  (!empty($data['Masterdata']['eye_select']))?'OD' : 'OS';?></span>
		</div>  
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">file:</label>
			<span  class=" col-md-4">
			<?php if(!empty($data['Masterdata']['file'])){ ?>
			<a href="<?php echo WWW_BASE.'Masterdata/'.$data['Masterdata']['file']; ?>" target="_blank"><button type="button" class="btn btn-primary" title="PDF Report Link">PDF Report</button></a>
			<?php } else{ echo 'N/A'; }?>
			</span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Staff User:</label>
			<span  class=" col-md-4"><?php echo $data['User']['first_name'].' '.$data['User']['last_name'];?></span>
		</div> 
		<!--<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Color:</label>
			<span  class=" col-md-4"><?php echo $data['Masterdata']['color'];?></span>
		</div> 
			<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">BackgroundColor:</label>
			<span  class=" col-md-4"><?php echo $data['Masterdata']['backgroundcolor'];?></span>
		</div> -->
		<div class="form-group  col-md-12">
			<!--<label for="recipient-name" class="col-md-4  form-control-label">Stmsize:</label>
			<span  class=" col-md-4"><?php echo $data['Masterdata']['stmsize'];?></span>-->
			<?php 
		 
			//$string = @explode(' ',@$data['Masterdata']['patient_name']);
			$patient_new_id = @substr(@$data['Patient']['first_name'],0,1).@substr(@$data['Patient']['last_name'],0,1).$data['Masterdata']['patient_id'].'_'.date('d M Y h:i:s a',strtotime($data['Masterdata']['created'])).'_'.$od_os;
			?>
			<span  class=" col-md-4"></span>
			<span  class=" col-md-4"></span>
			<span  class=" col-md-4"><a href="<?php echo $this->Html->url(['controller'=>'masterReports','action'=>'export',$data['Masterdata']['id'],'?'=>['url'=>$patient_new_id]]); ?>" class="btn btn-default" style="float: right;">Export</a></span>
		</div> 
		<div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class="col-md-2  form-control-label">X:</label>
		<label for="recipient-name" class="col-md-2  form-control-label">Y:</label>
		<label for="recipient-name" class="col-md-2  form-control-label">Intensity:</label>
		<label for="recipient-name" class="col-md-2  form-control-label">Size:</label>
		<label for="recipient-name" class="col-md-2  form-control-label">STD:</label>
		<label for="recipient-name" class="col-md-2  form-control-label">Index:</label>
		</div>
		<?php 
		if(!empty($data['VfMasterdata'])){
		
		foreach ($data['VfMasterdata'] as $pdata) {?>
		<div class="form-group  col-md-12">
		<span  class=" col-md-2"><?php echo $pdata['x']; ?></span>
		<span  class=" col-md-2"><?php  echo $pdata['y']; ?></span>
		<span  class=" col-md-2"><?php  echo $pdata['intensity']; ?></span>
		<span  class=" col-md-2"><?php  echo $pdata['size']; ?></span>
		<span  class=" col-md-2"><?php  echo $pdata['STD']; ?></span>
		<span  class=" col-md-2"><?php  echo $pdata['index']; ?></span>
		</div>
		<?php }}else{ ?>
		<div class="form-group  col-md-12" style="text:align:center;">
			
			<strong  class=" col-md-4 col-md-offset-4"> No record found.</strong>
			 
		</div>

	<?php	}		?>

</div> 
<div class="modal-footer" style="border-top:none">
	<!--<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>-->
</div>
                           