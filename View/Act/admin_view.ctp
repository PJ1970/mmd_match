<?php $session_check_parent = $this->Session->read('Auth.Admin.user_type');
//pr($data);die;
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel">ACT Test Report Details</h4>
</div>
<div class="modal-body">
	<div class="form-group col-md-12">
		<label for="recipient-name" class=" col-md-4 form-control-label">Date Of Test:</label>
		<span class=" col-md-4"><?php echo date('d F Y', strtotime($data['ActTest']['created'])); ?></span>
	</div>
	<div class="form-group col-md-12">
		<label for="recipient-name" class="col-md-4  form-control-label">Patient Name:</label>
		<span class=" col-md-4"><?php echo $data['ActTest']['patient_name']; ?></span>
	</div>
	<div class="form-group col-md-12">
		<label for="recipient-name" class="col-md-4  form-control-label">Eye Value:</label>
		<span class=" col-md-4"><?php echo $od_os = (!empty($data['ActTest']['eye'])) ? 'OD' : 'OS'; ?></span>
	</div>
	<div class="form-group col-md-12">
		<label for="recipient-name" class="col-md-4  form-control-label">file:</label>
		<span class=" col-md-4" style="display: inline-flex;">
			<?php if (!empty($data['ActTest']['file'])) { ?>
				<a href="<?php echo WWW_BASE . 'app/webroot/ActTestControllerData/' . $data['ActTest']['file']; ?>" target="_blank"><button type="button" class="btn btn-primary" title="PDF Report Link">PDF Report</button></a>
			<?php } ?>
		</span>
	</div>
	<div class="form-group  col-md-12">
		<label for="recipient-name" class="col-md-4  form-control-label">Staff User:</label>
		<span class=" col-md-4"><?php echo $data['User']['complete_name']; ?></span>
	</div>
	<div class="form-group  col-md-12">
		<span class=" col-md-4"></span>
		<span class=" col-md-4"></span>
		<span class=" col-md-4">
<!--			--><?php //if ($session_check_parent == 'Admin') { ?>
<!--				<a href="--><?php //echo $this->Html->url(['controller' => 'act', 'action' => 'export', $data['Act']['id']]); ?><!--" class="btn btn-default" style="float: right;">Export</a>-->
<!--			--><?php //} ?>
		</span>
	</div>
	<?php if ($session_check_parent == 'Admin') { ?>
		<div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
			<center><label for="recipient-name" class="col-md-4  form-control-label">Eye</label></center>
			<center><label for="recipient-name" class="col-md-4  form-control-label">Contrast DB</label></center>
			<center><label for="recipient-name" class="col-md-4  form-control-label">Test Color</label></center>
		</div>
		<?php
		if (!empty($data['ActPointdata'])) {
			foreach ($data['ActPointdata'] as $pdata) {?>
				<div class="form-group  col-md-12">
					<span class=" col-md-4"><center><?php echo ($pdata['eye']) ? 'OD' : 'OS'; ?></center></span>
					<span class=" col-md-4"><center><?php echo $pdata['ContrastDB']; ?></center></span>
					<span class=" col-md-4"><center><?php echo $pdata['testColor']; ?></center></span>
				</div>
			<?php }
		} else { ?>
			<div class="form-group  col-md-12" style="text:align:center;">
				<strong class=" col-md-4 col-md-offset-4"> No record found.</strong>
			</div>
		<?php } ?>
	<?php } ?>
</div>
<div class="modal-footer" style="border-top:none">
	<!--<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>-->
</div>
