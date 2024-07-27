<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel">Admin Details</h4>
</div>
<div class="modal-body">
	 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class=" col-md-4 form-control-label">First Name:</label>
			<span  class=" col-md-4"><?php echo $data['User']['first_name']; ?></span>
		</div>
	<div class="form-group  col-md-12">
			<label for="recipient-name" class=" col-md-4 form-control-label">Middle Name:</label>
			<span  class=" col-md-4"><?php echo $data['User']['middle_name']; ?></span>
		</div>		
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Last Name:</label>
			<span  class=" col-md-4"><?php echo $data['User']['last_name']; ?></span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Email:</label>
			<span  class=" col-md-4"><?php echo $data['User']['email']; ?></span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Username:</label>
			<span  class=" col-md-4"><?php echo $data['User']['username']; ?></span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Phone:</label>
			<span  class=" col-md-4"><?php echo $data['User']['phone']; ?></span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Gender:</label>
			<span  class=" col-md-4"><?php echo $data['User']['gender']; ?></span>
		</div> 
		<!--<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Date Of Birth:</label>
			<span  class=" col-md-4"><?php echo date("d F Y", strtotime($data['User']['dob'])); ?></span>
		</div> -->
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Assigned Office Name:</label>
			<span  class=" col-md-4"><?php echo $data['Office']['name']; ?></span>
		</div> 
		
		<!--<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Practice List:</label>
			<span  class=" col-md-4"><?php //if(!empty($data['User']['practice_id'])) {echo implode(',',$this->custom->getSelectedPracticeName($data['User']['practice_id']));}else {echo '';} ?></span>
		</div> -->
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Profile image:</label>
			<span  class=" col-md-4"><?php if(!empty($data['User']['profile_pic'])){?>
				<?php echo $this->Html->image("/img/uploads/".$data['User']['profile_pic'],array("width"=>100,"height"=>100,"escape"=>false)); ?>
				<?php }else{?>
				<?php echo $this->Html->image("/img/uploads/".'no-user.png',array("width"=>100,"height"=>100,"escape"=>false)); ?>
				<?php }?>
			</span>
		</div> 
 
</div> 
 <div class="modal-footer" style="border-top:none">
	<!-- <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button> -->
</div>
                           