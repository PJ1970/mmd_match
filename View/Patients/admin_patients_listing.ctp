 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js" ></script>
<?php $Admin = $this->Session->read('Auth.Admin');
		if(!empty($Admin) && $Admin['user_type'] == "Admin"){
			 echo $this->element('pateint_list_admin');
		}else{  ?>
<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Manage Patients</h4>
		  
        </div>
		 
      </div>
      <div class="page-content-wrapper ">
       <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-primary">
                <div class="panel-body">
				
				<?php 
				if(isset($credit_expire)){  
				//pr($check_payable);die; // $check_payable['Office']['payable'] =='no' && $check_payable['Office']['restrict'] =='restrict'
					if($check_payable['Office']['payable'] =='no' && $check_payable['Office']['restrict'] =='restrict'){?>
						<h2 style="color:red;text-align:center;">You don't have permission to see this. Please contact support: <br/>Email: support@micromedinc.com  <br/>Phone : 818-222-3310</h2> 
					<?php }else{
						if($Admin['user_type'] == "Staffuser"){
							?>
							<h2 style="color:red;text-align:center;">Your Credit has expired. Please contact to your office.</h2>	
							<?php
						}else{?>
							<h2 style="color:red;text-align:center;">You are out of credit please contact Micro Medical office<br/>Email: support@micromedinc.com  <br/>Phone : 818-222-3310</h2> 
							
							<?php } 
					}?>
				<?php }else{?>
				
				<?php echo $this->Session->flash()."<br/>";
				
				if(@$check_payable['Office']['payable'] =='no' && @$check_payable['Office']['restrict'] =='restrict'){
					?>
					<h2 style="color:red;text-align:center;">You don't have permission to see this. Please contact support: <br/>Email: support@micromedinc.com  <br/>Phone : 818-222-3310</h2>
				<?php
				}else{ ?>
				
				<div class="col-md-12" style="float: left;width: 100%;margin-bottom:20px;">
					<!-- <h4 style="padding-left: 10px;">Normal Search</h4>
					<?php echo $this->Form->create('Patient',array('type' => 'get','url' => array('controller' => 'patients','action' => 'patients_listing'))); ?> -->
					
					 <div class="col-md-3" style="padding-left: 0px;margin-bottom: 10px;">  
						<!-- <?php echo $this->Form->input('search',array('div' => false,'label' => false,'value' => @$search,'type' =>'text','class' => 'form-control','placeholder' => 'Search','maxlength' => '100')); ?> -->
						 
					</div>
					<div class="form-group m-b-0 col-md-1">
						<!-- <button type="submit" class="btn btn-primary waves-effect waves-light searchBtn" > Search </button>	 -->
					</div>
					<?php echo $this->Form->end(); ?>
					<!--<?php //if(!empty($avl_credit)){  ?>
						<div class="col-md-4 col-md-offset-1" style="padding-left:0;">  
							<h4><span>Availabe Credits:  </span><strong data="<?php //echo (@$avl_credit>0)? $avl_credit : '0';  ?>" class="avl_credit"><?php //echo (@$avl_credit>0)? $avl_credit : '0';  ?></strong></h4>
						</div>
					<?php // } ?>-->
					<?php if($_SERVER[ 'REQUEST_URI' ]=='/admin/patients/patients_listing'){
								$url = $_SERVER[ 'REQUEST_URI' ].'?merge=1'; 
							}else{
								$url = $_SERVER[ 'REQUEST_URI' ].'&merge=1'; 
							} ?>
					<div align="right" class="col-md-4"style="float:right;">
					    <?php if($Admin['user_type'] == "Subadmin"){ ?>
					    <?php if(@$merge==1){ ?>
					     <a type="button" class="btn btn-large btn-primary disabled"  id="merge-update">Merge Patients Update</a>
					    <?php }else{ ?>
					    <a href="<?php echo $this->HTML->url($url); ?>" id ="mergePatients" class="btn btn-large btn-primary" >Merge Patients</a>
					    <?php }
					    } ?>
						<a href="<?php echo $this->HTML->url('/admin/patients/addPatient'); ?>" class="btn btn-large btn-primary" >Add Patient</a>
						<?php echo $this->Form->create('Patient',array('type' => 'post','id'=>'PatientPatientsListingFormPost','url' => array('controller' => 'patients','action' => 'patients_listing'))); ?>
						<input type="hidden" name="PatientPatientsListingFormPost1" id="PatientPatientsListingFormPost1">
						 <?php echo $this->Form->end(); ?>
					</div>
					
					
				</div>
				<div style="border: 1px solid #ddd;float: left;width: 100%;margin-bottom:20px;">
					<h4 style="padding-left: 10px;">Advance Search</h4>
					<?php echo $this->Form->create('Patient',array('type' => 'get','url' => array('controller' => 'patients','action' => 'patients_listing'))); ?>
					<div class="row" style="margin: 5px;">
						<input type="hidden" name="advance_search" value="advance_search">
						<div class="col-md-3">
							<?php echo $this->Form->input('first_name',array('div' => false,'label' => false,'value'=>@$first_name,'type' =>'text','class' => 'form-control','placeholder' => 'First Name','maxlength' => '100', 'required'=>false)); ?>
							<?php echo $this->Form->input('merge',array('div' => false,'label' => false,'value'=>@$merge,'type' =>'hidden', 'required'=>false)); ?>
						</div>
						<div class="col-md-3">
							<?php echo $this->Form->input('middle_name',array('div' => false,'label' => false,'value'=>@$middle_name,'type' =>'text','class' => 'form-control','placeholder' => 'Middle Name','maxlength' => '100', 'required'=>false)); ?>
						</div>
						<div class="col-md-3">
							<?php echo $this->Form->input('last_name',array('div' => false,'label' => false,'value'=>@$last_name,'type' =>'text','class' => 'form-control','placeholder' => 'Last Name','maxlength' => '100', 'required'=>false)); ?>
						</div>
						<div class="col-md-3">
							<?php echo $this->Form->input('id_number',array('div' => false,'label' => false,'value'=>@$id_number,'type' =>'text','class' => 'form-control','placeholder' => 'Patient ID','maxlength' => '100', 'required'=>false)); ?>
						</div>
						<div class="col-md-3"style="margin-top: 20px;">
							<?php echo $this->Form->input('dob',array('type'=>'text','class'=>'datepicker form-control','label'=>false,'div'=>false,'placeholder'=>"DOB (dd-mm-yyyy)", 'autocomplete'=>'off','pattern' => "(0[1-9]|1[0-9]|2[0-9]|3[01])-(0[1-9]|1[012])-[0-9]{4}", 
							'title'=>"Date of Birth in DD-MM-YYYY",
							'data-inputmask'=>"'mask': '99-99-9999'",'value'=>(!empty($dob)?date('d-m-Y', strtotime($dob)):''))); ?>
						</div>
						
						<div class="col-md-3"style="margin-top: 20px;">
						 <?php 
						 	if(@$datas[0]['Office']['archive_status'] == 0) {
								$stat = array(1=>'Active');
							}else{
						 		$stat = array(1=>'Active',0=>'Archive',2=>'Permanent');
							}
						 echo $this->Form->input('status',array('options' =>@$stat,'empty'=>'Select status','selected' =>(isset($selected))?$selected:'','id'=>'setstatus','div'=>false,'class' => 'form-control','label' => false,'value'=>@$status ));  ?>
						</div>
					
						<!--<div class="col-md-1">
							<a href="javascript:void(0);" id="clear-date" class="btn btn-primary waves-effect waves-light searchBtn" > Clear Date </a>
						</div>-->
						<div class="col-md-1"style="margin-top: 20px;">
							<?php echo $this->Form->input('page_no',array('div' => false,'label' => false,'value'=>'@$page_no','type' =>'number','class' => 'form-control','placeholder' => 'Page No','maxlength' => '100', 'required'=>false)); ?>
						</div>
						<div class="col-md-1"style="margin-top: 20px;">
							<button type="submit" class="btn btn-primary waves-effect waves-light searchBtn" > Search </button>
						</div>
					</div>
					
					<?php echo $this->Form->end(); ?>
					<div align="right" class="col-md-4">
						<h4 class="m-b-30 m-t-0"></h4>
					</div>
				</div>
				<span class="success_message" style="color:#fff;display:none;font-size:20px;background: green;padding: 0px 70px;margin-left: 27%;">You have changed status successfully!</span>
						<span class="unsuccess_message" style="color:#fff;display:none;background: #e50a0a;font-size:20px;margin-left: 27%; padding: 0px 70px;">You have not changed status!</span>
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <table id="datatable_patients1" class="table table-striped table-bordered">
                        <thead>
                          <tr>
							<th style="width:34px;">S.No</th>
							<?php if(@$merge==1){ ?>
							<th style="width:34px;">Merge</th>
							<?php } ?>
							<!--<th> <?php //echo $this->Paginator->sort('Patient.id_number','ID Number'); ?> </th>-->
							 
                            <th><?php echo $this->Paginator->sort('Patient.first_name','First Name'); ?> </th>
							<th><?php echo $this->Paginator->sort('Patient.middle_name','Middle Name'); ?> </th>
                            <th><?php echo $this->Paginator->sort('Patient.last_name','Last Name'); ?> </th>
                            <th colspan=""><?php echo $this->Paginator->sort('Patient.id_number','Patient Id'); ?></th>
                            <th colspan=""><?php echo $this->Paginator->sort('Patient.dob','D.O.B. (DD-MM-YYYY)'); ?></th>
                            
							<th>Start Test</th>
							<th>Status</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
							<?php 
							if(!empty($datas)) {foreach($datas as $key=>$data){
						  
							/* if(isset($data['Patient']['delete_date'])){
								$now = time(); // or your date as well
								$database_date = strtotime($data['Patient']['delete_date']);
								$datediff = $now - $database_date;
								$no_of_days = round($datediff / (60 * 60 * 24));
							}else{
								$data['Patient']['delete_date'] = 100;
							} */
							
							$delete_date =  @$data['Patient']['delete_date'];
							$permanentDelete =0;
							if(!empty($delete_date)){
								//$after_30_date = date('Y-m-d',strtotime('+30 days',strtotime($delete_date)));
								$after_365_date = date('Y-m-d',strtotime('+1 years',strtotime($delete_date)));
								$date = date('Y-m-d'); 
								 
								if(strtotime($date) >= strtotime($after_365_date)){
									$permanentDelete=1;
								}
								
							}
							
							
							?>
						<tr style="<?php echo (!empty($data['Patient']['is_delete']))? 'background-color: #ffdada;':''; ?>" >
							<td data-order="<?php echo $data['Patient']['id']; ?>"><?php echo $key+1; ?></td>
							<!--<td><?php //echo $data['Patient']['id_number']; ?></td>-->
							 <?php if(@$merge==1){ ?>
							<td><input type="checkbox" value="<?php echo $data['Patient']['id'];?>" class="form-control merge-check"></td>
							<?php } ?>
							<td><?php echo $data['Patient']['first_name'];?></td>
							<td><?php echo $data['Patient']['middle_name'];?></td>
							<td><?php echo $data['Patient']['last_name'];?></td>	 	
							 <td><?php print_r($data['Patient']['id_number']) ?> </td>
                  <td><?php echo (!empty($data['Patient']['dob']))?date('d-m-Y', strtotime($data['Patient']['dob'])):''; ?></td>
							 
							 <td>
							
								

								&nbsp;&nbsp;  
								<?php /*
								<!--   <?php if (in_array(14, $checked_data)) { echo "<a style='cursor: pointer;height: 26px;padding-top: 3px;padding-bottom: 3px;' class='btn btn-info' href='".WWW_BASE."admin/patients/start_test/".$data['Patient']['id']."' title='Start VF Test' >VF</a>"; }?>
							  <?php if (in_array(15, $checked_data)) { echo "<a style='cursor: pointer;height: 26px;padding-top: 3px;padding-bottom: 3px;' class='btn btn-info' href='".WWW_BASE."admin/patients/start_test_fdt/".$data['Patient']['id']."' title='Start FDT Test' >FDT</a>"; }?> 
							   <?php if (in_array(23, $checked_data)) { echo "<a style='cursor: pointer;height: 26px;padding-top: 3px;padding-bottom: 3px;' class='btn btn-info' href='".WWW_BASE."admin/patients/start_test_da/".$data['Patient']['id']."' title='Start DA Test' >DA</a>"; }?> --> 
							   <?php */ ?>
							   <?php //pr($data['Office']['server_test']); die; 
							   	if($data['Office']['server_test']==1){ 
							   			echo "<a style='cursor: pointer;height: 26px;padding-top: 3px;padding-bottom: 3px;' class='btn btn-info' href='".WWW_BASE."admin/patients/findtest/".$data['Patient']['id']."' title='Start Test' >Start Test</a>"; }

							   
							   	  ?> 
							 	</td>
							 	<td>
						<select class="mmd-dash-btn form-control" id="patient_status" data-id="<?php echo $data['Patient']['id'];?>" style="height: 30px;">
							<?php if($data['Patient']['is_delete'] == 0){ ?>
							<option value="1" <?php echo ($data['Patient']['status'] == 1) ? 'selected' : ''?>>
								Active
							</option>
							<?php if($data['Office']['archive_status']==1){?>
							<option value="2" <?php echo ($data['Patient']['status'] == 2) ? 'selected' : ''?>>
								Permanent
							</option>
							<?php }}if($data['Office']['archive_status']==1){ ?>
								<option value="0" <?php echo ($data['Patient']['status'] == 0) ? 'selected' : ''?>>
									Archive
								</option>
							<?php } ?>
						</select>
					</td>
							<td class="action_sec">
								<?php if(CustomHelper::checkVideoModulePermission($data['Patient']['office_id'])){ ?>
								  <?php echo $this->Html->link('<i class="fa fa-file-video-o" aria-hidden="true"></i>',array('controller'=>'patients','action'=>'admin_patient_view_video', $data['Patient']['id']),array('escape'=>false,'title'=>'View Video'));?>&nbsp;&nbsp; <?php } ?>
								<a type="button" title="View" subAdminId="<?php echo $data['Patient']['id'];?>" class="SubAdminDetail" data-toggle="modal" data-target="#subAdminView"><i class="fa fa-eye" aria-hidden="true"></i></a>
									
								&nbsp;&nbsp;	<?php echo $this->Html->link('<i class="fa fa-pencil" aria-hidden="true"></i>',array('controller'=>'patients','action'=>'admin_edit', $data['Patient']['id']),array('escape'=>false,'title'=>'Edit'));?>
								<?php if($Admin['user_type'] != "Staffuser"){ ?>
									&nbsp;&nbsp;<?php 
										echo $this->Html->link('<i class="fa fa-file-pdf-o" aria-hidden="true"></i>',array('controller'=>'patients','action'=>'admin_upload_pdf', $data['Patient']['id']),array('escape'=>false,'title'=>'Upload Pdf'));
									?>
								<?php } ?>
								&nbsp;	<?php #echo $this->Html->link('<i class="fa fa-trash-o"></i>',array('controller'=>'patients','action'=>'admin_delete',$data['Patient']['id']),array('escape'=>false,'title'=>'Delete','confirm'=>'Are you sure you want to delete?'));?>
								<?php 
								
								/* if($data['Patient']['is_delete']==1 && $permanentDelete == 1){ ?>
									&nbsp;&nbsp;	<?php echo $this->Html->link('<i class="fa fa-retweet"></i>',array('controller'=>'patients','action'=>'admin_delete_revert',$data['Patient']['id']),array('escape'=>false,'title'=>'Revert Delete','confirm'=>'Are you sure you want to revert this deleted patient?'));?> 
								<?php }else{ ?>
									&nbsp;&nbsp;	<?php echo $this->Html->link('<i class="fa fa-trash-o"></i>',array('controller'=>'patients','action'=>'admin_delete',$data['Patient']['id']),array('escape'=>false,'title'=>'Delete','confirm'=>'Are you sure you want to delete?'));
								} */ ?> 
								<div class="dropdown" style="display: inline-block;">
								  <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border: none;
    color: #337ab7;
    font-size: 16px;
    background-color: transparent;">
								    <i class="mdi mdi-view-list"></i>
								  </button>
								  <ul class="dropdown-menu" aria-labelledby="dLabel">
								    	<li><?php echo "<a style='cursor: pointer;' href='".WWW_BASE."admin/unityreports/unity_reports_list/VF?patientreport=".$data['Patient']['id']."' title='View VF Reports' target='_blank'>VF</a>"; ?></li> 
								    	<li><?php echo "<a style='cursor: pointer;' href='".WWW_BASE."admin/unityreports/unity_reports_list/VS?patientreport=".$data['Patient']['id']."' title='View VS Reports' target='_blank'>VS</a>"; ?></li>
								    	<li><?php echo "<a style='cursor: pointer;' href='".WWW_BASE."admin/unityreports/unity_reports_list/FDT?patientreport=".$data['Patient']['id']."' title='View FDT Reports' target='_blank'>FDT</a>"; ?></li>
								    	<li><?php echo "<a style='cursor: pointer;' href='".WWW_BASE."admin/act/act_list?patientreport=".$data['Patient']['id']."' title='View ACT Reports' target='_blank'>ACT</a>"; ?></li>


								    	<li><?php echo "<a style='cursor: pointer;' href='".WWW_BASE."admin/vt/vt_list?patientreport=".$data['Patient']['id']."' title='View VT Reports' target='_blank'>VT</a>"; ?></li>
								    	<li><?php echo "<a style='cursor: pointer;' href='".WWW_BASE."admin/pup/pup_list?patientreport=".$data['Patient']['id']."' title='View PUP Reports' target='_blank'>PUP</a>"; ?></li>
								    	<li><?php echo "<a style='cursor: pointer;' href='".WWW_BASE."admin/darkadaptations/dark_adaptations_list?patientreport=".$data['Patient']['id']."' title='View DA Reports' target='_blank'>DA</a>"; ?></li>
								    	<li><?php echo "<a style='cursor: pointer;' href='".WWW_BASE."admin/stb/stb_list?patientreport=".$data['Patient']['id']."' title='View STB Reports' target='_blank'>STB</a>"; ?></li>

								  </ul>
								</div>
								<?php
									//echo "<a style='cursor: pointer;'  title='View' testreportId='".$data['Pointdata']['id']."' class='testreport loaderAjax' >VF</a>";

									
								?> 
								<?php  
									if(!empty($Admin) && $Admin['user_type'] == "Subadmin"){ 
										if($permanentDelete ==1 && $data['Patient']['is_delete']==1){ 
										?>
										<i class="fa fa-ban" style="cursor: default;color:red;" title="Parmanent Deleted"></i>
										<?php
										
										}elseif(empty($permanentDelete) && !empty($data['Patient']['is_delete'])){
											echo $this->Html->link('<i class="fa fa-retweet"></i>',array('controller'=>'patients','action'=>'admin_delete_revert',$data['Patient']['id']),array('escape'=>false,'title'=>'Revert Delete','confirm'=>'Are you sure you want to revert this deleted patient?'));
										}else{
											echo $this->Html->link('<i class="fa fa-trash-o"></i>',array('controller'=>'patients','action'=>'admin_delete',$data['Patient']['id']),array('escape'=>false,'title'=>'Delete','confirm'=>'Are you sure you want to delete?'));
										}
									}
								?>
								
							</td>
						</tr>
						<?php }
						  if(isset($this->params['paging']['Patient']['pageCount'])){ ?>
							<tr> 
								<td colspan='9' align="center" class="paginat">
									<div class="pagi_nat">
									 <!-- Shows the next and previous links -->
									 <?php echo $this->Paginator->prev('<'); ?>
									 <?php echo $this->Paginator->numbers(
										 array(
										  'separator'=>''
										  )
										  ); ?>
									 <?php echo $this->Paginator->next('>'); ?><br>
									 <!-- prints X of Y, where X is current page and Y is number of pages -->
									 </div>
									<div class="pagi"><?php echo $this->Paginator->counter();echo "&nbsp Page"; ?></div>
								</td>
							</tr>
						<?php }  
						}else{echo"<tr><td colspan='9' style='text-align:center;'>No record found.</td></tr>";} ?>
						</tbody>
                      </table>
                    </div>
				<?php }} ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </div>
        </div>
     </div>

		<div id="subAdminView" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content" id="subadmin_detail">
				</div>
			</div>
		</div>	
		
	<div class="modal fade bs-example-modal-sm" id="myPleaseWait" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <span class="glyphicon glyphicon-time">
                        </span>Please Wait
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="progress">
                        <div class="progress-bar progress-bar-info
                        progress-bar-striped active"
                             style="width: 100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
jQuery(document).ready(function(){
	jQuery(document).on('click',".SubAdminDetail",function(){
	//jQuery('#myPleaseWait').modal('show');
		var subAdminId = jQuery(this).attr("subAdminId");
		jQuery("#subadmin_detail").load("<?php echo WWW_BASE; ?>admin/patients/view/"+subAdminId+ "?" + new Date().getTime(), function(result) {
			// jQuery('#myPleaseWait').modal('hide');
			jQuery("#subAdminView").modal("show");
		});
	});	
	// var table = $('#datatable_patients').DataTable({
	// 		processing: false,
	// 		serverSide: true,
	// 		start: 0,
	// 		ajax: {
	// 			url: "<?php echo WWW_BASE;?>admin/patients/ajaxPatientsListing",
	// 			type: "POST",
	// 			error: function(){  // error handling
 //                    $(".datatable_report-error").html("");
 //                    $("#datatable_report").append('<tbody class="datatable_report-error"><tr><th colspan="8">No data found in the server</th></tr></tbody>');
 //                    $("#datatable_report_processing").css("display","none");
 
 //                }
	// 		},
	// 		columns: [
	// 			{ "data": "id"},
	// 			{ "data": "id_number"},
	// 			{ "data": "p_profilepic",'orderable':false},
	// 			{ "data": "first_name" },
	// 			{ "data": "last_name"},
	// 			{ "data": "email"},
	// 			{ "data": "office_name"},
	// 			{ "data": "status"},
	// 			{ "data": "patient_view",'orderable':false}
				
				
				
	// 		],
 //            searching: true,
 //            lengthChange: true,
	// 		lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
	// 		paging: true,
 //            order: [[ 0, "desc" ]],
	// });
	jQuery(document).on('click',".delete_patient",function(){
		 //alert('ff');
        return confirm('Are you sure want to delete?');
    });

});

</script>	
          
		<?php } ?>
		
<script>
	var patient_list = []; 
	$( function() {
	/* 	$('.datepicker').on('change', function(){
			var input = document.getElementsByClassName("datepicker")[0];
			input.oninvalid = function(event) {
				event.target.setCustomValidity('Date of birth should be in DD-MM-YYYY');
			}
		}) */
		$('.datepicker').inputmask('DD-MM-YYYY');
		$( ".datepicker" ).datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,
		  changeYear: true, yearRange: "-100:+0",maxDate: new Date()});
		$('#clear-date').on('click', function(){
			$('.datepicker').datepicker('setDate', null);
		})
		
	});
	
	$(document).on("change","#patient_status",function(){
		let status=$(this).val();
		let patent_id=$(this).data('id');
		//let message = confirm("Are you want to change status?");
       //if (message == true) {
       	$(".success_message").show();
       	setTimeout(function(){
       	$(".success_message").hide();
       	//location.reload();
       	}, 2000);
		$.ajax({
          type: 'POST',
          url: '<?php echo WWW_BASE; ?>admin/patients/updateStatus',
          data: {'status':status,'patient_id':patent_id},
          success: function (data) {
          }
      });
		/*}else{
			$(".unsuccess_message").show();
			setTimeout(function(){
		       $(".unsuccess_message").hide();
		       //location.reload();
		    }, 2000);
		}*/
	});

	$(document).ready(function(){
		if(window.sessionStorage.getItem("patient_list")){
			var patientLists=window.sessionStorage.getItem("patient_list");
			 const split_string= patientLists.split(",");
			 patient_list=split_string;
			 $( ".merge-check" ).each(function( index ) {
				 		currentVal=$(this).val(); 
				 		if(split_string.includes(currentVal)){
				 			$(this).prop("checked",true);
				 		}
				});
			   if(patient_list.length>=2){
	       	   $("#merge-update").prop('disabled',false);
	        	 $("#merge-update").removeClass('disabled');
			    }else{
			        $("#merge-update").prop('disabled',true);
			        $("#merge-update").addClass('disabled');
			    } 
		}

	})

		$(document).on("change",".merge-check",function(){ console.log($(this).is(':checked'));
		
	    if($(this).prop("checked") == true){
	        if (!patient_list.includes($(this).val())) {
						patient_list.push($(this).val());
	    	}
	    }else{
	        if (patient_list.includes($(this).val())) { 	
						var remove_Item = $(this).val(); 
            patient_list = $.grep(patient_list, function(value) {
              return value != remove_Item;
            });
	    	}
	    }

	    window.sessionStorage.setItem("patient_list", patient_list);
	    if(patient_list.length>=2){
	        $("#merge-update").prop('disabled',false);
	        $("#merge-update").removeClass('disabled');
	    }else{
	        $("#merge-update").prop('disabled',true);
	        $("#merge-update").addClass('disabled');
	    } 
	    if(window.sessionStorage.getItem("patient_list")){
				var patientLists=window.sessionStorage.getItem("patient_list");
				 const split_string= patientLists.split(",");
				 const topArray = split_string[0];
				 patient_list=split_string;
				 $( ".merge-check" ).each(function( index ) {
					 		currentVal=$(this).val(); 
					 		if(topArray.includes(currentVal)){
					 			$(this).addClass("grencheckbox");
					 		}else{
					 			$(this).removeClass("grencheckbox");
					 		}
					});
			}
	});
	$(document).on("click","#merge-update",function(){
	    if(patient_list.length>=2){
    	    $("#PatientPatientsListingFormPost1").val(patient_list);
    	    $("#PatientPatientsListingFormPost").submit();
	    }
	    sessionStorage.removeItem("patient_list");
	});	
	$(document).on("click","#mergePatients",function(e){
		e.preventDefault();
		sessionStorage.removeItem("patient_list");
			if(confirm('1. After merging, need to sync in controller to get the latest record.\n\n 2. After merging, the reports for the additional records will still show the original patient name as the PDF record cannot be changed. \n \n 3. The first record you select will be the primary record that is saved. All additional records you select will be merged under the primary record with that patient information.\n \n 4. You are about to merge 2 or more patient records. This action cannot be reversed. Please make sure the records belong to the same patient before completing the process.')){
				window.location.href = $(this).attr('href');
		  }
	});
  </script>