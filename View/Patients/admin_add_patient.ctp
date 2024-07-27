<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Add Patient </h4>
        </div>
      </div>
      <div class="page-content-wrapper ">
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-primary">
                <div class="panel-body">
                  <!--<h4 class="m-t-0 m-b-30">Examples</h4>-->
                  <div class="row">
				    <?php echo $this->Form->create('Patient', array('novalidate' => true, 'id'=>'loginFrm', 'url'=>array('action'=>'admin_addPatient')));?>
                    <div class="col-sm-6 col-xs-12">
                     <!-- <h3 class="header-title m-t-0"><small class="text-info"><b>Validation type</b></small></h3>-->
                      <div class="m-t-20">
                        <!--<form class="" action="#">-->
						
                          <!--<div class="form-group">
                            <label>ID Number</label>
                            <input type="text" class="form-control" required placeholder="Type something"/>
							<?php //echo $this->Form->input('id_number',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"ID Number",'required'=>true)); ?>
                          </div>-->
                          <input type="hidden" name="cTimeSystem" id="cTimeSystem">
						  <div class="form-group">
                            <label>First Name *</label>
                            <!--<input type="text" class="form-control" required placeholder="Type something"/>-->
							<?php echo $this->Form->input('first_name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"First Name",'required'=>true)); ?>
                          </div>
						  <div class="form-group">
                            <label>Middle Name (optional)</label>
                            <!--<input type="text" class="form-control" required placeholder="Type something"/>-->
							<?php echo $this->Form->input('middle_name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Middle Name")); ?>
                          </div>
						   <div class="form-group">
                            <label>Last Name *</label>
                            <!--<input type="text" class="form-control" required placeholder="Type something"/>-->
							<?php echo $this->Form->input('last_name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Last Name",'required'=>true)); ?>
                           </div>
                        <!--   <div class="form-group">
                            <label>E-Mail</label>
                            <div>
                              
							  <?php echo $this->Form->input('email',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Enter a valid e-mail",'required'=>true)); ?>
                            </div>
                          </div>
						  	<div class="form-group">
                            <label>Phone</label>
                            <div>
                              
							  <?php echo $this->Form->input('phone',array('type'=>'text','max'=>10,'class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Phone")); ?>
                            </div>
                          </div> -->
                          <div class="form-group">
                            <label>Patient ID (optional)</label>
                            <div>
                              <!--<input type="email" class="form-control" required parsley-type="email" placeholder="Enter a valid e-mail"/>-->
                <?php echo $this->Form->input('id_number',array('type'=>'text','max'=>10,'class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Patient ID")); ?>
                            </div>
                          </div>
                          <?php $Admin = $this->Session->read('Auth.Admin');    ?>
                          <div><label>Patient Status</label>
                           <select class="mmd-dash-btn form-control" name="status"  style="height: 30px;">
                                <option value="1" >Active</option>
                                <?php if($Admin['Office']['archive_status'] == 1){ ?>
                                <option value="2">Parmanent</option>
                                <option value="0" >Archive</option>
                              <?php } ?>
                            </select>
                          </div>
                          <?php 
                          if($Admin['user_type']=="Staffuser" || $Admin['user_type']=="Subadmin" ) {
                          if(!empty($TestDeviceslist)){//pr($TestDeviceslist);
                                foreach ($TestDeviceslist as $key => $value) { //pr($value);
                                  $type_option[$value['TestDevice']['name']] = $value['TestDevice']['name'];
                                  $type_device = $value['TestDevice']['device_type'];
                                }
                          }
                          if($type_device == 6){ ?>
                          <h4>For Home Use</h4>
                          <div class="container" style="border: 1px solid;margin-top: 10px;">
                            <div class="row">
                              <div class="form-group">
                              <label>Device Name</label>
                              <div>
                                <?php //pr($TestDeviceslist);
                              
                                //$type_option = array('6'=>'PICO_G2_IHU'); ?>
                                <?php echo $this->Form->input('device_type',array('options' =>$type_option,'empty'=>'Select Device Name','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3')); ?>
                              </div>
                            </div>
                            <div class="form-group">
                            <label>Test Name (Threshold)</label>
                            <div>
                            <?php   
                              echo $this->Form->input('test_name',array('options' =>@$TestTypethresholdArray,'empty'=>'Select Test Name','id'=>'testName','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true','required'=>true, 'data-selected-text-format' => 'count > 3'));
                            ?>  
                            </div>
                        </div>
                        <div class="form-group">
                      <label>OD/OS/OU<span class="error"></span></label>
                      <?php echo $this->Form->input('eye_type',array('options' =>[0=>'OS',1=>'OD',2=>'OU'], 'default' => 'OS','empty' => 'Select', 'div' => false,'label' => false, 'class' => 'form-control','maxlength' => '100', 'required'=>true)); ?>
                      <p class="error" id="od-os-error"></p>
                    </div>
                    <div class="form-group">
                            <label>Detailed Progression</label>
                            <div>

                               <label class="switch">
                                <?php echo $this->Form->input("progression_deatild", array('label' => false, 'div' => false, 'type' => 'checkbox', 'class' => 'checkbox')); ?>
                                   <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                      <label>Language</label>
                      <?php echo $this->Form->input('language',array('options' =>[0=>'Select Language',1=>'English',2=>'Spanish',3=>'French',4=>'Portuguese',5=>'Arabic',6=>'Hindi',7=>'Chinese',8=>'Vietnamese',9=>'Chinese-Cantonese'], 'default' => 'OS', 'div' => false,'label' => false, 'class' => 'form-control','maxlength' => '100', 'required'=>true)); ?>
                      <p class="error" id="od-os-error"></p>
                    </div>
                    <div class="form-group">
                            <label>Test Time For IHU device (Days)</label>
                            <?php echo $this->Form->input('weektime', array('type' => 'number', 'class' => 'ihudevice form-control', 'label' => false, 'div' => false, 'placeholder' => "Enter week", 'required' => true )); ?>
                            <span class="showMsg" style="color:red;display: none;">You can assign the device for atleast 1 week.</span>
                        </div>
                      </div>
                    </div>
                  <?php } } ?>
                      </div>
                    </div>
                   <div class="col-sm-6 col-xs-12">
				   
                     <!-- <h3 class="m-t-0"><small class="text-info"><b>Range validation</b></small></h3>-->
                      <div class="m-t-20">
                        <!--<form action="#">-->
                          <div class="form-group">
                            <label>Date Of Birth *</label>
                            <div>
                               <div class="row">
                              
                               <div class="col-sm-4 col-xs-4 col-lg-4">
                                <?php echo $this->Form->input('mm',array('type'=>'number','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"MM")); ?>
                               </div>
                               <div class="col-sm-4 col-xs-4 col-lg-4">
                                <?php echo $this->Form->input('dd',array('type'=>'number','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"DD")); ?>
                               </div> 
                               <div class="col-sm-4 col-xs-4 col-lg-4">
                                <?php echo $this->Form->input('yy',array('type'=>'number','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"YYYY")); ?>
                               </div>
                              
                             </div>
							 <?php //echo $this->Form->input('dob',array('type'=>'text','class'=>'datepicker form-control','label'=>false,'div'=>false,'placeholder'=>"Date Of Birth",'required'=>true,'autocomplete'=>'off','readonly' => 'readonly')); ?>
                             <!-- <input parsley-type="url" type="url" class="form-control" required placeholder="URL"/>-->
                            </div>
                          </div>
						  <div class="form-group">
                            <label>Notes (optional)</label>
                            <div>
							 <?php echo $this->Form->input('notes',array('type'=>'textarea','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Write Notes Here..",'required'=>true)); ?>
                             <!-- <input parsley-type="url" type="url" class="form-control" required placeholder="URL"/>-->
                            </div>
                          </div>
						<?php 
							$Admin = $this->Session->read('Auth.Admin'); 
							if($Admin['user_type']=="Admin")  : 
						?>
                        <div class="form-group">
                            <label>Select Office *</label>
                            <div>
							<?php 
								$options=$this->custom->getOfficeList();
								//pr($options); die;
								echo $this->Form->input('office_id',array('options' =>$options,'id'=>'SlectStaff','empty'=>'Select Office','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3','default'=>(!empty($this->request->data['Patient']['office_id'])) ? $this->request->data['Patient']['office_id'] : @$this->Session->read('Search.office'),));
							?>
                            </div>
                        </div>
						<!--<div class="form-group">
                            <label>Select Staff</label>
                            <div>
							<?php 	
								//echo $this->Form->input('user_id',array('options' =>'','empty'=>'Select Staff','id'=>'setStaff','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3'));
							?>  
                            </div>
                        </div>-->
						<?php 
							endif; 
							if(isset($users_da)) :
						?>
						<!--<div class="form-group">
                            <label>Select Staff</label>
                            <div>
							<?php 	
								//echo $this->Form->input('user_id',array('options' =>(isset($users_da))?$users_da:'','empty'=>'Select Staff','id'=>'setStaff','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3'));
							?>  
                            </div>
                        </div>-->
						<?php endif; ?>
						<div class="form-group">
                            <label>Race (optional)</label>
                            <div>
							<?php 	
								echo $this->Form->input('race',array('options' =>$this->Dropdown->racesDropdown(),'empty'=>'Select Race','id'=>'raceName','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3'));
							?>  
                            </div>
                        </div>

                         <div class="form-group">
                            <label>Visual Acuity (optional)</label>
                            <!--<input type="text" class="form-control" required placeholder="Type something"/>-->
                             <div class="row">
                               <div class="col-sm-2 col-xs-2 col-lg-2">
                                <label>OD</label>
                               </div>
                               <div class="col-sm-2 col-xs-2 col-lg-2">
                                <?php echo $this->Form->input('od_left',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"")); ?>
                               </div>
                               <div class="col-sm-2 col-xs-2 col-lg-2">
                                <?php echo $this->Form->input('od_right',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"")); ?>
                               </div>
                                <div class="col-sm-2 col-xs-2 col-lg-2">
                                <label>OS</label>
                               </div>
                               <div class="col-sm-2 col-xs-2 col-lg-2">
                                <?php echo $this->Form->input('os_left',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"")); ?>
                               </div>
                               <div class="col-sm-2 col-xs-2 col-lg-2">
                                <?php echo $this->Form->input('os_right',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"")); ?>
                               </div>
                             </div>
              
                           </div>

                          <div class="form-group m-b-0">
                            <div>
                               <button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>
							  <!-- <button type="reset" class="btn btn-default waves-effect m-l-5"> Cancel </button>--.
                            </div>
                          </div>
                        <!--</form>-->
                      </div>
                    </div>
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
$(function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,
      //changeYear: true, yearRange: "-100:+0",maxDate: new Date()}).datepicker("setDate", null);
	  changeYear: true, yearRange: "-100:+100"}).datepicker("setDate", null);
});
</script>
<script>
$(document).ready(function(){
 

	/* $('body').on('change','#SlectStaff',function(){
		var company_id = $(this).val();
		var location = "<?php echo WWW_BASE."admin/patients/getStaffListByCompanyId";?>";
		$.ajax({
			url: location,
			type: 'GET',
			data:{
				company_id : company_id,
			},
			success : function(response) { 
			$('#setStaff').html($("<option value=''>Select Staff</option>"));
				$.each(jQuery.parseJSON(response), function(key, value) {
					//console.log(value);
				//for (var i = 0; i < response.length; i++) {
					 $('#setStaff').append($("<option value='"+key+"'>"+value+"</option>"));
				});
				
			},
		});
	}); */
});
$(document).ready(function(){
var time = new Date();
  $("#cTimeSystem").val(time);
});

$(".ihudevice").on("input", function() {
   var getV = $(this).val(); 
   if(getV == 0 && getV != ''){
    $(".showMsg").show();
   }else{
    $(".showMsg").hide();
   }
});
</script>
 