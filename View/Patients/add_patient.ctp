   
<div class="content">
      <div class="">
        
      </div>
      <div class="modal-header" style="border:none;">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
</div>
      <div class="page-content-wrapper ">
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-primary">
                <div class="panel-body">
                  <!--<h4 class="m-t-0 m-b-30">Examples</h4>-->
                  <div class="row">
				    <?php echo $this->Form->create('Patient', array('novalidate' => true, 'id'=>'AddPatinentStartTest', 'url'=>array('action'=>'admin_addPatient?rempve_layout=1')));?>
                    <div class="col-sm-6 col-xs-12">
                     
                      <div class="m-t-20">
                        
                          <input type="hidden" name="cTimeSystem" id="cTimeSystem">
						  <div class="form-group">
                            <label>First Name *</label>
                             
							<?php echo $this->Form->input('first_name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"First Name",'required'=>true)); ?>
							<div class="error-message" id="first_name_error"></div> 
                          </div>
						  <div class="form-group">
                            <label>Middle Name (optional)</label>
                             
							<?php echo $this->Form->input('middle_name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Middle Name")); ?>
                          </div>
						   <div class="form-group">
                            <label>Last Name *</label>
                            
							<?php echo $this->Form->input('last_name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Last Name",'required'=>true)); ?>
                           <div class="error-message" id="last_name_error"></div> 
                           </div>
                        
                          <div class="form-group">
                            <label>Patient ID (optional)</label>
                            <div>
                             
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
                                <div class="error-message" id="mm_error"></div> 
                               </div>
                               <div class="col-sm-4 col-xs-4 col-lg-4">
                                <?php echo $this->Form->input('dd',array('type'=>'number','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"DD")); ?>
                                <div class="error-message" id="dd_error"></div> 
                               </div> 
                               <div class="col-sm-4 col-xs-4 col-lg-4">
                                <?php echo $this->Form->input('yy',array('type'=>'number','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"YYYY")); ?>
                                <div class="error-message" id="yy_error"></div> 
                               </div>
                              
                             </div>
							 
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
					 
						<?php 
							endif; 
							if(isset($users_da)) :
						?>
					 
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
                            <label>Gender</label>
                            <div>
              <?php   
                echo $this->Form->input('race',array('options' =>array("Male"=>"Male","Female"=>"Female"),'empty'=>'Select Gender','id'=>'gender','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3'));
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
                            <div style="float:right; margin-top:25px;">
                               <button type="button" id="add-patient-from-start" class="btn btn-primary waves-effect waves-light" style="width: 200px; height: 35px;"> Submit </button>
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
 
 
 