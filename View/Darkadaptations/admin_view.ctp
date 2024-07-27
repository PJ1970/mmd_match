<?php $session_check_parent = $this->Session->read('Auth.Admin.user_type');
//pr($session_check_parent);die;
 ?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel">Dark Adaption Test Report Details</h4>
</div>
<div class="modal-body">
	 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class=" col-md-4 form-control-label">Date Of Test:</label>
			<span  class=" col-md-4"><?php  echo date('d F Y',strtotime($data['DarkAdaption']['test_date_time']));?></span>
			
			
		</div>		
	
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Patient Name:</label>
			<span  class=" col-md-4"><?php echo $data['DarkAdaption']['patient_name'];?></span>
		</div> 
		
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Eye Value:</label>
			<span  class=" col-md-4"><?php echo $od_os =  (!empty($data['DarkAdaption']['eye_select']))?'OD' : 'OS';?></span>
		</div>  
		
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">file:</label>
			<span  class=" col-md-4" style="display: inline-flex;">
			<?php if(!empty($data['DarkAdaption']['pdf'])){ ?>
				<a href="<?php echo WWW_BASE.'uploads/darkadaption/'.$data['DarkAdaption']['pdf']; ?>" target="_blank"><button type="button" class="btn btn-primary" title="PDF Report Link">PDF Report</button></a>
			<?php } ?>
			</span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Staff User:</label>
			<span  class=" col-md-4"><?php echo $data['DarkAdaption']['staff_name'];?></span>
		</div> 
	
		<div class="form-group  col-md-12">
			
			
			<span  class=" col-md-4"></span>
			<span  class=" col-md-4"></span>
			<span  class=" col-md-4">
			<?php if($session_check_parent=='Admin'){?>
				<a href="<?php echo $this->Html->url(['controller'=>'darkadaptations','action'=>'export',$data['DarkAdaption']['id']]); ?>" class="btn btn-default" style="float: right;">Export</a>
			<?php } ?>
			</span>
		</div> 
		<?php if($session_check_parent=='Admin'){ ?>
			<div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
				<center><label for="recipient-name" class="col-md-2  form-control-label">X</label></center>
				<center><label for="recipient-name" class="col-md-2  form-control-label">Y</label></center>
				<center><label for="recipient-name" class="col-md-1  form-control-label">Time Min</label></center>
				<center><label for="recipient-name" class="col-md-2  form-control-label">Decibles</label></center>
				<center><label for="recipient-name" class="col-md-2  form-control-label">Color</label></center>
				<center><label for="recipient-name" class="col-md-2  form-control-label">Index</label></center>
				<center><label for="recipient-name" class="col-md-1  form-control-label">Eye</label></center>
			</div>
			
			<?php 
			if(!empty($data['DaPointData'])){
			//pr($data['DaPointData']); die;
			foreach ($data['DaPointData'] as $pdata){?>
			<div class="form-group  col-md-12">
				<span  class=" col-md-2"><center><?php echo $pdata['x']; ?></center></span>
				<span  class=" col-md-2"><center><?php  echo $pdata['y']; ?></center></span>
				<span  class=" col-md-1"><center><?php  echo $pdata['timeMin']; ?></center></span>
				<span  class=" col-md-2"><center><?php  echo $pdata['Decibles']; ?></center></span>
				<span  class=" col-md-2"><center><?php  echo $pdata['color']; ?></center></span>
				<span  class=" col-md-2"><center><?php  echo $pdata['index_name']; ?></center></span>
				<span  class=" col-md-1"><center><?php  echo $pdata['Eye']; ?></center></span>
			</div>
			<?php }}else{ ?>
			<div class="form-group  col-md-12" style="text:align:center;">
				<strong  class=" col-md-4 col-md-offset-4"> No record found.</strong>
			</div>
		<?php } ?>
	<?php } ?>
</div> 
<div class="modal-footer" style="border-top:none">
	<!--<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>-->
</div>
                           