<?php $session_check_parent = $this->Session->read('Auth.Admin.user_type');
//pr($data);die;
 ?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel">Stb Test Report Details</h4>
</div>
<div class="modal-body">
	 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class=" col-md-4 form-control-label">Date Of Test:</label>
			<span  class=" col-md-4"><?php  echo date('d F Y',strtotime($data['StbTest']['created']));?></span>
			
			
		</div>		
	
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Patient Name:</label>
			<span  class=" col-md-4"><?php echo $data['StbTest']['patient_name'];?></span>
		</div> 
		
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Eye Value:</label>
			<span  class=" col-md-4"><?php echo $od_os =  (!empty($data['StbTest']['eye_select']))?'OD' : 'OS';?></span>
		</div>  
		
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">file:</label>
			<span  class=" col-md-4" style="display: inline-flex;">
			<?php if(!empty($data['StbTest']['file'])){ ?>
				<a href="<?php echo WWW_BASE.'uploads/stbdata/'.$data['StbTest']['file']; ?>" target="_blank"><button type="button" class="btn btn-primary" title="PDF Report Link">PDF Report</button></a>
			<?php } ?>
			</span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Staff User:</label>
			<span  class=" col-md-4"><?php echo $data['User']['complete_name'];?></span>
		</div> 
	
		<div class="form-group  col-md-12">
			
			
			<span  class=" col-md-4"></span>
			<span  class=" col-md-4"></span>
			<span  class=" col-md-4">
			<?php if($session_check_parent=='Admin'){?>
				<a href="<?php echo $this->Html->url(['controller'=>'stb','action'=>'export',$data['StbTest']['id']]); ?>" class="btn btn-default" style="float: right;">Export</a>
			<?php } ?>
			</span>
		</div> 
		<?php if($session_check_parent=='Admin'){ ?>
			<div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
				<center><label for="recipient-name" class="col-md-2  form-control-label">X</label></center>
				<center><label for="recipient-name" class="col-md-2  form-control-label">Y</label></center>
				<center><label for="recipient-name" class="col-md-1  form-control-label">Z</label></center>
				<center><label for="recipient-name" class="col-md-1  form-control-label">Eye</label></center> 
				<center><label for="recipient-name" class="col-md-2  form-control-label">Test State</label></center>
				<center><label for="recipient-name" class="col-md-1  form-control-label">locationX</label></center> 
				<center><label for="recipient-name" class="col-md-1  form-control-label">locationY</label></center>
				<center><label for="recipient-name" class="col-md-1  form-control-label">locationZ</label></center>
				<center><label for="recipient-name" class="col-md-1  form-control-label">TargetSize</label></center>

			</div>
			
			<?php 
			if(!empty($data['StbPointdata'])){
			//pr($data['DaPointData']); die;
			foreach ($data['StbPointdata'] as $pdata){?>
			<div class="form-group  col-md-12">
				<span  class=" col-md-2"><center><?php echo $pdata['x']; ?></center></span>
				<span  class=" col-md-2"><center><?php  echo $pdata['y']; ?></center></span>
				<span  class=" col-md-1"><center><?php  echo $pdata['z']; ?></center></span> 
				<span  class=" col-md-1"><center><?php  echo ($pdata['eye'])?'OD':'OS'; ?></center></span>
				<span  class=" col-md-2"><center><?php  echo $pdata['testState']; ?></center></span>
				<span  class=" col-md-1"><center><?php  echo $pdata['locationX']; ?></center></span>
				<span  class=" col-md-1"><center><?php  echo $pdata['locationY']; ?></center></span>
				<span  class=" col-md-1"><center><?php  echo $pdata['locationZ']; ?></center></span>
				<span  class=" col-md-1"><center><?php  echo $pdata['TargetSize']; ?></center></span>
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
                           