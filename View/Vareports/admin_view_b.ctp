<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel">VA Test Report Details</h4>
</div>
<div class="modal-body">
	 <?php //pr($data); ?>
		<div class="form-group  col-md-12">
			<label for="recipient-name" class=" col-md-4 form-control-label">Date Of Test:</label>
			<span  class=" col-md-4"><?php  echo date('d F Y',strtotime($data['VaData']['created']));?></span>
		</div>		
	
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Staff User:</label>
			<span  class=" col-md-4"><?php echo @$user['User']['first_name'].' '.@$user['User']['last_name'];?></span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Patient Name:</label>
			<span  class=" col-md-4"><?php echo$data['VaData']['patient_name'];?></span>
		</div> 

		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">file:</label>
			<span  class=" col-md-4"><a href="<?php echo WWW_BASE.'pointData/'.$data['VaData']['pdf']; ?>" target="_blank"><?php echo @$data['VaData']['pdf'];?></a></span>
		</div> 
		<!--div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Color:</label>
			<span  class=" col-md-4"><?php echo $data['VaData']['color'];?></span>
		</div> 
			<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">BackgroundColor:</label>
			<span  class=" col-md-4"><?php echo $data['VaData']['backgroundcolor'];?></span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Stmsize:</label>
			<span  class=" col-md-4"><?php echo $data['VaData']['stmsize'];?></span-->
		</div> 
		
		<?php  
		 
		if(!empty($VaPointdata['VaPointdata'])){
			$pdata = $VaPointdata['VaPointdata'];
		 ?>
	 <div class="form-group  col-md-12" style="">
		 <div class="form-group  col-md-12" style="  border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA1:</label>
		<span class="col-md-8" ><?php echo $pdata['VA1']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class="  col-md-4 form-control-label">VA2:</label>
		<span class="col-md-8"><?php echo $pdata['VA2']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA3:</label>
		<span class="col-md-8"><?php echo $pdata['VA3']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA4:</label>
		<span class="col-md-8"><?php echo $pdata['VA4']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA5:</label>
		<span class="col-md-8"><?php echo $pdata['VA5']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA6:</label>
		<span class="col-md-8"><?php echo $pdata['VA6']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4  form-control-label">VA7:</label>
		<span class="col-md-8"><?php echo $pdata['VA7']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA8:</label>
		<span class="col-md-8"><?php echo $pdata['VA8']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA9:</label>
		<span class="col-md-8"><?php echo $pdata['VA9']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA10:</label>
			<span class="col-md-8"><?php echo $pdata['VA10']; ?></span>
			</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA11:</label>
		<span class="col-md-8"><?php echo $pdata['VA11']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA12:</label>
		<span class="col-md-8"><?php echo $pdata['VA12']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA13:</label>
		<span class="col-md-8"><?php echo $pdata['VA13']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4  form-control-label">VA14:</label>
		<span class="col-md-8"><?php echo $pdata['VA14']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA15:</label>
		<span class="col-md-8"><?php echo $pdata['VA15']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA16:</label>
		<span class="col-md-8"><?php echo $pdata['VA16']; ?></span>
		</div> 
		 
	</div>
		
	 
		<?php  }else{ ?>
		<div class="form-group  col-md-12" style="text:align:center;">
			
			<strong  class=" col-md-4 col-md-offset-4"> No record found.</strong>
			 
		</div>

	<?php	}		?>

</div> 
<div class="modal-footer" style="border-top:none">
	<!--<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>-->
</div>
                           