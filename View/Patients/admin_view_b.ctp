<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel">Patient Details</h4>
</div>
<div class="modal-body">
	 
	 <div class="form-group col-md-12">
			<label for="recipient-name" class="col-md-4 form-control-label">ID Number:</label>
			<span  class=" col-md-4"><?php echo $data['Patient']['id_number']; ?></span>
		</div>
		<div class="form-group col-md-12">
			<label for="recipient-name" class="col-md-4 form-control-label">Profile Image:</label>
		
			<span  class=" col-md-4">	<?php if(!empty($data['Patient']['p_profilepic'])){
				$p_img = WWW_BASE.$data['Patient']['p_profilepic'];?>
				<a href="<?php  echo $p_img;?>" target="_blank"><img src="<?php  echo $p_img;?>" width="100px"></a>
			<?php }else{ 
				$p_img = WWW_BASE.'img/uploads/no-user.png'; ?>
				<img src="<?php  echo $p_img;?>" width="100px">
			<?php } ?></span>
		</div>
		<div class="form-group col-md-12">
			<label for="recipient-name" class="col-md-4 form-control-label">First Name:</label>
			<span  class=" col-md-4"><?php echo $data['Patient']['first_name']; ?></span>
		</div>
			<div class="form-group  col-md-12">
			<label for="recipient-name" class=" col-md-4 form-control-label">Middle Name:</label>
			<span  class=" col-md-4"><?php echo $data['Patient']['middle_name']; ?></span>
		</div>
		
		
		<div class="form-group col-md-12">
			<label for="recipient-name" class="col-md-4 form-control-label">Last Name:</label>
			<span  class=" col-md-4"><?php echo $data['Patient']['last_name']; ?></span>
		</div>
		<div class="form-group col-md-12">
			<label for="recipient-name" class="col-md-4 form-control-label">Email:</label>
			<span  class=" col-md-4"><?php echo $data['Patient']['email']; ?></span>
		</div>
		<div class="form-group col-md-12">
			<label for="recipient-name" class="col-md-4 form-control-label">phone:</label>
			<span  class=" col-md-4"><?php echo $data['Patient']['phone']; ?></span>
		</div>
		<div class="form-group col-md-12">
			<label for="recipient-name" class="col-md-4 form-control-label">Date Of Birth:</label>
			<span  class=" col-md-4"><?php if(!empty($data['Patient']['dob'])){echo date("d F Y",strtotime($data['Patient']['dob']));} else {echo ($data['Patient']['dob']);} ?></span>
		</div>
		<div class="form-group col-md-12">
			<label for="recipient-name" class="col-md-4 form-control-label">Office:</label>
			<span  class=" col-md-4"><?php echo $this->custom->getOfficeName($data['Patient']['office_id']); ?></span>
		</div>
		<div class="form-group col-md-12">
			<label for="recipient-name" class="col-md-4 form-control-label">Notes:</label>
			<span  class=" col-md-8"><?php echo $data['Patient']['notes']; ?></span>
		</div>
	 
</div>
<div class="modal-footer" style="border-top:none">
	<!--<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>-->
</div>
                           