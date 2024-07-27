<?php #pr($data);die; ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel">Patient Details</h4>
</div> 
<div class="modal-body">
	<?php if(empty($data)){
		?>
	 <div class="form-group col-md-12">
	 <h4>This Patient has been deleted.</h4>
	 </div>
		
	<?php }else{?>
	 <div class="form-group col-md-10">
			<label for="recipient-name" class="col-md-4 form-control-label">ID Number:</label>
			<span  class=" col-md-4"><?php echo $data['Patient']['id_number']; ?></span>
		</div>
		 <div class="form-group col-md-2">
		<?php echo $this->Html->link('Edit',array('controller'=>'patients',  'action'=>'admin_edit', $data['Patient']['id']),array('escape'=>false,'title'=>'Edit','class'=>'btn btn-info'));?>
</div>
		<!-- <div class="form-group col-md-12">
			<label for="recipient-name" class="col-md-4 form-control-label">Profile Image:</label>
		
			<span  class=" col-md-4">	<?php if(!empty($data['Patient']['p_profilepic'])){
				$p_img = WWW_BASE.$data['Patient']['p_profilepic'];?>
				<a href="<?php  echo $p_img;?>" target="_blank"><img src="<?php  echo $p_img;?>" width="100px"></a>
			<?php }else{ 
				$p_img = WWW_BASE.'img/uploads/no-user.png'; ?>
				<img src="<?php  echo $p_img;?>" width="100px">
			<?php } ?></span>
		</div> -->
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
		<!-- <div class="form-group col-md-12">
			<label for="recipient-name" class="col-md-4 form-control-label">Email:</label>
			<span  class=" col-md-4"><?php echo $data['Patient']['email']; ?></span>
		</div>
		<div class="form-group col-md-12">
			<label for="recipient-name" class="col-md-4 form-control-label">phone:</label>
			<span  class=" col-md-4"><?php echo $data['Patient']['phone']; ?></span>
		</div> -->
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
		<div class="form-group col-md-12">
			<label for="recipient-name" class="col-md-4 form-control-label">View Test:</label>
			<span  class=" col-md-8"> 
								<?php if ($test['vf']==1) { echo "<a style='margin-bottom: 10px;cursor: pointer;height: 26px;padding-top: 3px;padding-bottom: 3px;' class='btn btn-info' href='".WWW_BASE."admin/unityreports/unity_reports_list/?page_type=vf&search=".$data['Patient']['id']."' title='View VF Test' >VF</a>"; }?>
							 	 <?php if ($test['fdt']==1) { echo "<a style='margin-bottom: 10px;cursor: pointer;height: 26px;padding-top: 3px;padding-bottom: 3px;' class='btn btn-info' href='".WWW_BASE."admin/unityreports/unity_reports_list/?page_type=fdt&search=".$data['Patient']['id']."' title='View FDT Test' >FDT</a>"; }?>

							 	 <?php if ($test['vs']==1) { echo "<a style='margin-bottom: 10px;cursor: pointer;height: 26px;padding-top: 3px;padding-bottom: 3px;' class='btn btn-info' href='".WWW_BASE."admin/unityreports/unity_reports_list/VS?search=".$data['Patient']['id']."' title='View VS Test' >VS</a>"; }?> 

							 	 <?php if ($test['pup']==1) { echo "<a style='margin-bottom: 10px;cursor: pointer;height: 26px;padding-top: 3px;padding-bottom: 3px;' class='btn btn-info' href='".WWW_BASE."admin/pup/pup_list?search=".$data['Patient']['id']."' title='View PUP Test' >PUP</a>"; }?>


								 <?php if ($test['da']==1) { echo "<a style='margin-bottom: 10px;cursor: pointer;height: 26px;padding-top: 3px;padding-bottom: 3px;' class='btn btn-info'  href='".WWW_BASE."admin/darkadaptations/dark_adaptations_list/?patent_id=".$data['Patient']['id']."' title='View DA Test' >DA</a>"; }?>

								 

								 <?php if ($test['act']==1) { echo "<a style='margin-bottom: 10px;cursor: pointer;height: 26px;padding-top: 3px;padding-bottom: 3px;' class='btn btn-info'  href='".WWW_BASE."admin/act/act_list?patent_id=".$data['Patient']['id']."' title='View ACT Test' >ACT</a>"; }?>

								  <?php if ($test['vt']==1) { echo "<a style='margin-bottom: 10px;cursor: pointer;height: 26px;padding-top: 3px;padding-bottom: 3px;' class='btn btn-info'  href='".WWW_BASE."admin/vt/vt_list?patent_id=".$data['Patient']['id']."' title='View VT Test' >VT</a>"; }?>

								  <?php if ($test['va']==1) { echo "<a style='margin-bottom: 10px;cursor: pointer;height: 26px;padding-top: 3px;padding-bottom: 3px;' class='btn btn-info'  href='".WWW_BASE."admin/vareports/va_reports_list?patent_id=".$data['Patient']['id']."' title='View VA Test' >VA</a>"; }?>

								   <?php if ($test['stb']==1) { echo "<a style='margin-bottom: 10px;cursor: pointer;height: 26px;padding-top: 3px;padding-bottom: 3px;' class='btn btn-info'  href='".WWW_BASE."admin/stb/stb_list?patent_id=".$data['Patient']['id']."' title='View STB Test' >STB</a>"; }?>


							</span>
		</div>
		<?php if($data['Office']['server_test']==1){ ?>
	 <div class="form-group col-md-12">
			<label for="recipient-name" class="col-md-4 form-control-label">Start Test:</label>
			<span  class=" col-md-8"><?php	$checked_data=array();
			
                            	 if (isset($data['Office']['Officereport'])) {
								    $checked_data = Hash::extract($data['Office']['Officereport'], '{n}.office_report'); 
	                            }
							  ?>
								 <?php if (in_array(14, $checked_data)) { echo "<a style='cursor: pointer;height: 26px;padding-top: 3px;padding-bottom: 3px;' class='btn btn-info' href='".WWW_BASE."admin/patients/start_test/".$data['Patient']['id']."' title='Start VF Test' >VF</a>"; }?>
							 	 <?php if (in_array(15, $checked_data)) { echo "<a style='cursor: pointer;height: 26px;padding-top: 3px;padding-bottom: 3px;' class='btn btn-info' href='".WWW_BASE."admin/patients/start_test_fdt/".$data['Patient']['id']."' title='Start FDT Test' >FDT</a>"; }?>
								 <?php if (in_array(23, $checked_data)) { echo "<a style='cursor: pointer;height: 26px;padding-top: 3px;padding-bottom: 3px;' class='btn btn-info' href='".WWW_BASE."admin/patients/start_test_da/".$data['Patient']['id']."' title='Start DA Test' >DA</a>"; }?>
								 <?php if (in_array(25, $checked_data)) { echo "<a style='cursor: pointer;height: 26px;padding-top: 3px;padding-bottom: 3px;' class='btn btn-info' href='".WWW_BASE."admin/patients/start_test_vs/".$data['Patient']['id']."' title='Start VS Test' >VS</a>"; } ?>
							</span>
		</div>
	<?php } ?>
</div>
	<?php } ?>
<div class="modal-footer" style="border-top:none">
	<!--<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>-->
</div>
                           