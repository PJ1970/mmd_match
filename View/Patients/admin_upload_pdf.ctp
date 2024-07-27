 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js" integrity="sha256-sPB0F50YUDK0otDnsfNHawYmA5M0pjjUf4TvRJkGFrI=" crossorigin="anonymous"></script>
 <script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js" ></script>
 <div class="content">
 	<div class="">
 		<div class="page-header-title">
 			<h4 class="page-title">Upload PDF </h4>
 		</div>
 	</div>
 	<div class="page-content-wrapper ">
 		<div class="container">
 			<div class="row">
 				<div class="col-sm-12">
 					<div class="panel panel-primary">
 						<div class="panel-body">
 							<div class="row">
 								<?php echo $this->Form->create('PatientFiles', array('novalidate' => false, 'id'=>'adminUploadPdf', 'url'=>array('controller'=>'patients','action'=>'upload_pdf', $id), 'type' => 'file', 'onsubmit'=>'return validateForm(this)'));?>
 								<div class="col-sm-6 col-xs-12">
 									<div class="m-t-20">
 										<?php echo $this->Form->input('id',array('type'=>'hidden','value'=>$id));?>
 										<div class="form-group">
 											<label>First Name</label>
 											<?php echo $this->Form->input('first_name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"First Name",'required'=>false, 'disabled' => true, 'value'=>@$patientData['Patient']['first_name'])); ?>
 										</div>
 										<div class="form-group">
 											<label>Middle Name</label>
 											<?php echo $this->Form->input('middle_name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Middle Name",'required'=>false, 'disabled' => true, 'value'=>@$patientData['Patient']['middle_name'])); ?>
 										</div>
 										<div class="form-group">
 											<label>Last Name</label>
 											<?php echo $this->Form->input('last_name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Last Name",'required'=>false, 'disabled' => true,'value'=>@$patientData['Patient']['last_name'] )); ?>
 										</div>
 										<div class="form-group">
 											<label>Test Type <span class="error"> *</span></label>
 											<?php echo $this->Form->input('test_type',array('options' =>$TestTypeArray, 'default' => 'Visual_Fields','empty' => 'Test Type', 'div' => false,'label' => false, 'class' => 'form-control','maxlength' => '100',  'required'=>false)); ?>
 											<p class="error" id="test-type-error"></p>
 										</div>
 										<div class="form-group" id="test_name_div">
 											<label>Test Name <span class="error"> *</span></label>
 											<?php echo $this->Form->input('test_report_id',array('options' =>$testNameVisualFieldsArray, 'default' => @$test_name,'empty' => 'Test Name', 'div' => false,'label' => false, 'class' => 'form-control','maxlength' => '100',  'required'=>false)); ?>
 											<p class="error" id="test-name-error"></p>
 										</div>


 									</div>
 								</div>
 								<div class="col-sm-6 col-xs-12"> 
 									<div class="m-t-20">
 										<div class="form-group">
 											<label>OD/OS/OU<span class="error"> *</span></label>
 											<?php echo $this->Form->input('eye',array('options' =>[0=>'OS', 1=>'OD',2=>'OU'], 'default' => 'OS','empty' => 'Select', 'div' => false,'label' => false, 'class' => 'form-control','maxlength' => '100', 'required'=>false)); ?>
 											<p class="error" id="od-os-error"></p>
 										</div>
 										<div class="form-group">
 											<label>Date Created <span class="error"> *</span></label>
 											<div>
 												<?php echo $this->Form->input('date_created',array('type'=>'text','class'=>'datepicker form-control','label'=>false,'div'=>false,'placeholder'=>"Date Created (dd-mm-yyyy)", 'autocomplete'=>'off','pattern' => "(0[1-9]|1[0-9]|2[0-9]|3[01])-(0[1-9]|1[012])-[0-9]{4}", 
 													'title'=>"Date Created in DD-MM-YYYY",
 													'data-inputmask'=>"'mask': '99-99-9999'",'value'=>date('d-m-Y'), 'required'=>false)); ?>
 													<p class="error" id="date-created-error"></p>
 												</div>
 											</div>
 										</div>
 										<div class="form-group">
 											<label>PDF File <span class="error"> *</span></label>
 											<?php echo $this->Form->input('file_name',array('type' => 'file' ,'empty' => 'Fle Name', 'div' => false,'label' => false, 'class' => 'form-control','maxlength' => '100' ,'required'=>false)); ?>
 											<p class="error" id="pdf-error"></p>
 										</div>

 										<?php 
 										$Admin = $this->Session->read('Auth.Admin');

 										if(!empty($Admin) && $Admin['user_type'] == "Admin"){
 											?>
 											<style>
 												.overlay {
 													left: 0;
 													top: 0;
 													width: 100%;
 													height: 100%;
 													position: fixed;
 													z-index: 9999;
 													background: rgba(100, 100, 100, .4);;
 												}

 												.overlay__inner {
 													left: 0;
 													top: 0;
 													width: 100%;
 													height: 100%;
 													position: absolute;
 												}

 												.overlay__content {
 													left: 50%;
 													position: absolute;
 													top: 50%;
 													transform: translate(-50%, -50%);
 												}

 												.overlay .spinner {
 													width: 75px;
 													height: 75px;
 													display: inline-block;
 													border-width: 2px;
 													border-color: rgba(255, 255, 255, 0.05);
 													border-top-color: #fff;
 													animation: spin 1s infinite linear;
 													border-radius: 100%;
 													border-style: solid;
 												}

 												@keyframes spin {
 													100% {
 														transform: rotate(360deg);
 													}
 												}
 												.overlay.d-none{ display: none;}
 											</style>
 											<div class="form-group vib-loader-in">
 												<!-- overlay element  -->
 												<div class="overlay">
 													<div class="overlay__inner">
 														<div class="overlay__content"><span class="spinner"></span></div>
 													</div>
 												</div>
 												<!-- end overlay  -->
 												<select id="vibSelectOffice" name="office_id" class="form-control">
 													<option value="0">Select Office</option>
 													<?php foreach ($offices as $office):?>
 														<option value="<?php echo $office['Office']['id']; ?>"><?php echo $office['Office']['name']; ?></option>
 													<?php endforeach; ?>
 												</select> 
 												<br/>
 												<select id="vibSelectStaff" name="vib_staff" class="form-control">
 													<option value="0">Select Staff</option>
 												</select>
 												<p class="error" id="staff-error"></p>
 											</div>


 										<?php }else{ ?>
 											<?php echo $this->Form->input('office_id',array('type'=>'hidden','value'=>$Admin['office_id']));?>
 											<?php } ?>	

 									</div>
 									<div class="col-md-12 col-xs-12">
 										<div>
 											<button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>

 										</div>
 									</div>
 									<input type="hidden" name="uploadtime" id="uploadtime">
 									<?php echo $this->Form->end();?>
 								</div>
 							</div>
 						</div>
 					</div>
 				</div>
 			</div>
 		</div>
 	</div>
 	<script>
 		function printError(elemId, hintMsg) {
 			document.getElementById(elemId).innerHTML = hintMsg;
 		}
 		function validateForm(form) {
 			let flag=true;
 			$('.error').html('');
 			let TestReportId = document.getElementById('PatientFilesTestReportId').value;
 			let eye = document.getElementById('PatientFilesEye').value;
 			let TestTypeId = document.getElementById('PatientFilesTestType').value;
 			let DateCreated = document.getElementById('PatientFilesDateCreated').value;
 			let fileName = document.getElementById('PatientFilesFileName').value;
			//console.log(TestReportId);
			if(TestReportId.trim() == "" && TestTypeId.trim()=="Visual_Fields") {
				console.log(TestReportId);
				printError("test-name-error", "Please Select Test Name");
				flag=false;
			} 
			console.log(TestTypeId.trim());
			if(TestTypeId.trim() == "") {
				console.log(TestTypeId);
				printError("test-type-error", "Please Select Test Type");
				flag=false;
			}
			if(eye.trim() == "") {
				printError("od-os-error", "Please Select OD/OS");
				flag=false;
			}
			if(DateCreated.trim() == "") {
				printError("date-created-error", "Date created can't be empty");
				flag=false;
			}
			if(fileName.trim() == "") {
				printError("pdf-error", "Please Select Pdf File");
				flag=false;
			}


			<?php if(!empty($Admin) && $Admin['user_type'] == "Admin"): ?>
				let staff = document.getElementById('vibSelectStaff').value;
				if(staff.trim() == 0) {
					printError("staff-error", "Please Select staff based on office!");
					flag=false;
				}
			<?php endif; ?>

			//console.log(TestReportId);
			return flag;
		}
		$(function() {
			$('.datepicker').inputmask('DD-MM-YYYY');
			$( ".datepicker" ).datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,
			  //changeYear: true, yearRange: "-100:+0",maxDate: new Date()}).datepicker("setDate", null);
			  changeYear: true, yearRange: "-100:+100"}).datepicker();
		});
		$('document').ready(function(){


                        /**
                         * Get Staff Listing
                         * @param {type} office
                         * @returns {undefined}
                         */

                         function vibStaffListing(office_id){
                         	if(office_id == 0){
                         		$("#vibSelectStaff").html('<option value="0">Select Staff</option>');
                         		return false;
                         	}
                         	$(".overlay").removeClass('d-none');

                         	$.post('<?php echo $this->Html->url(array('controller' => 'Patients', 'action' => 'allSattfinOfice'), true); ?>', {office_id: office_id}, function(msg){
                                //console.log(msg);
                                $("#vibSelectStaff").html(msg.option);
                                $(".overlay").addClass('d-none');
                            });

                         }

                        /**
                         * Office based staff
                         * When loggedinas supper admin
                         * 
                         * @version 2.0.0
                         */
                         if($("#vibSelectOffice").length){

                         	$(".overlay").addClass('d-none');

                         	$("#vibSelectOffice").on('change', function(){
                         		var office_id=$(this).val();
                         		vibStaffListing(office_id);
                         	});
                         }


                         $('#PatientFilesFileName').on('change', function(){
                         	var fileInput = document.getElementById('PatientFilesFileName');
                         	var filePath = fileInput.value;
                         	var allowedExtensions = /(\.pdf)$/i;
                         	if(!allowedExtensions.exec(filePath)){
                         		alert('Please upload file having extensions .pdf.gif only.');
                         		fileInput.value = '';
                         		return false;
                         	}else{
					//Image preview
					if (fileInput.files && fileInput.files[0]) {
						var reader = new FileReader();
						reader.onload = function(e) {
							document.getElementById('imagePreview').innerHTML = '<img src="'+e.target.result+'"/>';
						};
						reader.readAsDataURL(fileInput.files[0]);
					}
				}
			})
         var testNameVisualFieldsArray=Object.values(<?php echo json_encode($testNameVisualFieldsArray); ?>); 
         var testNameFDTArray=Object.values(<?php echo json_encode($testNameFDTArray); ?>); 
         $('#PatientFilesTestType').on('change', function(){ 
         	let TestTypeId = document.getElementById('PatientFilesTestType').value;
         	if(TestTypeId.trim()=="Visual_Fields" || TestTypeId.trim()=="F_D_T") {
         		$("#test_name_div").css("visibility", "visible");
         		if(TestTypeId.trim()=="Visual_Fields"){
         			$("#PatientFilesTestReportId").html("");  
         			for (i = 0; i < testNameVisualFieldsArray.length; i++) { 
         				$("#PatientFilesTestReportId").append("<option value='"+testNameVisualFieldsArray[i]+"' >"+testNameVisualFieldsArray[i]+"</option>"); 
         			} 
         		}
         		if(TestTypeId.trim()=="F_D_T"){
         			$("#PatientFilesTestReportId").html("");  
         			for (i = 0; i < testNameFDTArray.length; i++) { 
         				$("#PatientFilesTestReportId").append("<option value='"+testNameFDTArray[i]+"' >"+testNameFDTArray[i]+"</option>"); 
         			} 
         		}
         	}else{
         		$("#test_name_div").css("visibility", "hidden");
         	} 

         })


     })
$(document).ready(function(){
var time = new Date();
  $("#uploadtime").val(time);
});
 </script>