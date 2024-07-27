<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel">VF Test Report Details</h4>
</div>
<div class="modal-body">
	 <?php //pr($data); ?>
		<div class="form-group  col-md-12">
			<label for="recipient-name" class=" col-md-4 form-control-label">Date Of Test:</label>
			<span  class=" col-md-4"><?php  echo date('d F Y',strtotime($data['Pointdata']['created']));?></span>
		</div>		
	
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Staff User:</label>
			<span  class=" col-md-4"><?php echo $data['User']['first_name'].' '.$data['User']['last_name'];?></span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Patient Name:</label>
			<span  class=" col-md-4"><?php echo $data['Patient']['first_name'].' '.$data['Patient']['last_name'];?></span>
		</div> 

		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">file:</label>
			<span  class=" col-md-4"><a href="<?php echo WWW_BASE.'pointData/'.$data['Pointdata']['file']; ?>" target="_blank"><?php echo $data['Pointdata']['file'];?></a></span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Color:</label>
			<span  class=" col-md-4"><?php echo $data['Pointdata']['color'];?></span>
		</div> 
			<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">BackgroundColor:</label>
			<span  class=" col-md-4"><?php echo $data['Pointdata']['backgroundcolor'];?></span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Stmsize:</label>
			<span  class=" col-md-4"><?php echo $data['Pointdata']['stmsize'];?></span>
		</div> 
		<div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class="col-md-2  form-control-label">X:</label>
		<label for="recipient-name" class="col-md-2  form-control-label">Y:</label>
		<label for="recipient-name" class="col-md-2  form-control-label">Intensity:</label>
		<label for="recipient-name" class="col-md-2  form-control-label">Size:</label>
		<label for="recipient-name" class="col-md-2  form-control-label">FixationX:</label>
		<label for="recipient-name" class="col-md-2  form-control-label">FixationY:</label>
		</div>
		<?php 
		if(!empty($data['VfPointdata'])){
		
		foreach ($data['VfPointdata'] as $pdata) {?>
		<div class="form-group  col-md-12">
		<span  class=" col-md-2"><?php echo $pdata['x']; ?></span>
		<span  class=" col-md-2"><?php  echo $pdata['y']; ?></span>
		<span  class=" col-md-2"><?php  echo $pdata['intensity']; ?></span>
		<span  class=" col-md-2"><?php  echo $pdata['size']; ?></span>
		<span  class=" col-md-2"><?php  echo $pdata['fixationX']; ?></span>
		<span  class=" col-md-2"><?php  echo $pdata['fixationY']; ?></span>
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
                           