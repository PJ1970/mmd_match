<?php $session_check_parent = $this->Session->read('Auth.Admin.user_type');
//pr($data);die;
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel">VT Test Report Details</h4>
</div>
<div class="modal-body">
	<div class="form-group col-md-12">
		<label for="recipient-name" class=" col-md-4 form-control-label">Date Of Test:</label>
		<span class=" col-md-4"><?php echo date('d F Y', strtotime($data['VtTest']['created'])); ?></span>
	</div>
	<div class="form-group col-md-12">
		<label for="recipient-name" class="col-md-4  form-control-label">Patient Name:</label>
		<span class=" col-md-4"><?php echo $data['VtTest']['patient_name']; ?></span>
	</div>
	<div class="form-group col-md-12">
		<label for="recipient-name" class="col-md-4  form-control-label">Source:</label>
		<span class=" col-md-4"><?php echo $data['VtTest']['source']; ?></span>
	</div>
	<div class="form-group col-md-12">
		<label for="recipient-name" class="col-md-4  form-control-label">file:</label>
		<span class=" col-md-4" style="display: inline-flex;">
			<?php if (!empty($data['VtTest']['file'])) { ?>
				<a href="<?php echo WWW_BASE . 'app/webroot/VtTestControllerData/' . $data['VtTest']['file']; ?>" target="_blank"><button type="button" class="btn btn-primary" title="PDF Report Link">PDF Report</button></a>
			<?php } ?>
		</span>
	</div>
	<div class="form-group  col-md-12">
		<label for="recipient-name" class="col-md-4  form-control-label">Staff User:</label>
		<span class=" col-md-4"><?php echo $data['User']['complete_name']; ?></span>
	</div>
</div>
<div class="modal-footer" style="border-top:none">
	<!--<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>-->
</div>
