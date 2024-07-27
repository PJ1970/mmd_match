<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel">Test Device Details</h4>
</div>
<div class="modal-body">
	 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class=" col-md-4 form-control-label">Device Name:</label>
			<span  class=" col-md-4"><?php echo $data['TestDevice']['name']; ?></span>
		</div>
		<div class="form-group  col-md-12">
			<label for="recipient-name" class=" col-md-4 form-control-label">IP Address:</label>
			<span  class=" col-md-4"><?php echo $data['TestDevice']['ip_address']; ?></span>
		</div>
		<div class="form-group  col-md-12">
			<label for="recipient-name" class=" col-md-4 form-control-label">MAC Address:</label>
			<span  class=" col-md-4"><?php echo $data['TestDevice']['mac_address']; ?></span>
		</div>			
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Status:</label>
			<span  class=" col-md-4"><?php echo ($data['TestDevice']['status']==1)?"Active":"Inactive"; ?></span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">created At:</label>
			<span  class=" col-md-4"><?php  echo date('d F Y',strtotime($data['TestDevice']['created']));?></span>
		</div> 
		<?php if(!empty($this->request->query['office'])){ ?>
			<div class="form-group  col-md-12">
				<label for="recipient-name" class="col-md-4  form-control-label">Office Name</label>
				<span  class=" col-md-4"><?php  echo $data['Office']['name']; ?></span>
			</div> 
			<div class="form-group  col-md-12">
				<label for="recipient-name" class="col-md-4  form-control-label">Office Email</label>
				<span  class=" col-md-4"><?php  echo $data['Office']['email']; ?></span>
			</div> 
			<div class="form-group  col-md-12"> 
			 	&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link('Edit',array('controller'=>'offices','action'=>'admin_add', $data['Office']['id']),array('escape'=>false,'title'=>'Edit', 'class' => 'btn btn-info'));?>
			 	<?php echo $this->Html->link('Support',array('controller'=>'support','action'=>'admin_index','?'=>array('search'=>$data['Office']['name'])),array('escape'=>false,'title'=>'Support', 'class' => 'btn btn-info'));?> 
			</div>
			<div class="form-group  col-md-12">
				<label for="recipient-name" class="col-md-4  form-control-label">Rep Admin Name:</label>
				<span  class=" col-md-4"><?php  echo $data['User']['complete_name'];?></span>
			</div>
		<?php } ?>
</div> 
<div class="modal-footer" style="border-top:none">
	<!--<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>-->
</div>
                           