<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Edit Customer</h4>
        </div>
      </div>
      <div class="page-content-wrapper ">
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-primary">
                <div class="panel-body">
                  
                  <div class="row">
					<div class="col-md-12" style="text-align: center;">
						<div class="form-group">
							<?php if(!empty($data['User']['first_consent'])){
									echo "<label>Consent:   </label> ".date('d-m-Y h:i:s', strtotime($data['User']['first_consent'])); 
									echo ' '.$this->Html->link('Clear Consent',array('controller' => 'Users','action' => 'admin_clear_consent',$data['User']['id'],'full_base' => true), ['style'=>"text-decoration:underline;    margin-left: 10px;"]); 
								} ?>
						</div>
					</div>
				    <?php echo $this->Form->create('User', array('novalidate' => true, 'id'=>'loginFrm', 'url'=>array('controller'=>'Customers', 'action'=>'admin_edit'),'enctype'=>'multipart/form-data'));?>
                    <div class="col-sm-6 col-xs-12">
                     
                      <div class="m-t-20">
                        
						<?php echo $this->Form->input('id',array('type'=>'hidden','value'=>$data['User']['id']));?>
                          <div class="form-group">
                            <label>First Name</label>
                            
							<?php echo $this->Form->input('first_name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"First Name",'required'=>true)); ?>
                          </div>
						  <div class="form-group">
                            <label>Middle Name</label>
                            
							<?php echo $this->Form->input('middle_name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Middle Name",'required'=>true)); ?>
                          </div>
						   <div class="form-group">
                            <label>Last Name</label>
                            
							<?php echo $this->Form->input('last_name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Middle Name",'required'=>true)); ?>
                           </div>
                          
                          
						   <div class="form-group">
                            <label>Phone</label>
                            <div>
                              
							  <?php echo $this->Form->input('phone',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Phone",'required'=>true)); ?>
                            </div>
                          </div>
						  <div class="form-group">
                            <label>Gender</label>
                            <div>
							<?php  $gender = array('Male'=>'Male','Female'=>'Female');
							echo $this->Form->input('gender',array('options' =>$gender,'empty'=>'Select gender','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3')); ?>
                            </div>
                          </div>
                          <div class="form-group">
                           <label>Upload profile image</label>
                            <div>
							 <?php echo $this->Form->input('profile_pic',array('type'=>'file','label' => false,"class"=>'form-control','onchange'=>'readURL(this)', 'accept' => 'image/*'));?>
						 <span class="user_dsply image" style="margin: 10px 10px;float:left;">
							<?php if($data['User']['profile_pic']==''){$image_name='no-user.png';}else {$image_name=$data['User']['profile_pic'];} ?>
								<img id="preview" src="<?php echo WWW_BASE; ?>/img/uploads/<?php echo $image_name;?>" alt="your image" width="100" height="100"/>
						</span>
                            </div>
                        </div>
						<div class="form-group">
								<label>Rotate At</label>
								<div class="outerradio">
								<div class="innerrotate"><input type="radio" name="data[User][rotate]" value="90"><span>90&deg;</span></div>
								<div class="innerrotate"><input type="radio" name="data[User][rotate]" value="180"><span>180&deg;</span></div>
								<div class="innerrotate"><input type="radio" name="data[User][rotate]" value="270"><span>270&deg;</span></div>
								</div>
							</div>
                          
						
                      </div>
                    </div>
                   <div class="col-sm-6 col-xs-12">
				   
						<div class="m-t-20">
							
							<div class="form-group">
								<label>E-Mail</label>
								<div>
								<?php echo $this->Form->input('email',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Enter a valid e-mail",'required'=>true)); ?>
								</div>
							</div>
                          <div class="form-group">
                            <label>Username</label>
                            <div>
							 <?php echo $this->Form->input('username',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Enter a username",'required'=>true)); ?>
                             
                            </div>
                          </div>
						   <div class="form-group">
                            <label>Password</label>
                            <div>
							 <?php echo $this->Form->input('password',array('type'=>'password','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Enter a Password",'required'=>true)); ?>
                             
                            </div>
                          </div>
                            <div class="form-group">
								<label>Select Office</label>
								<div>
								<?php 
									$options=$this->custom->getOfficeList();
									echo $this->Form->input('office_id',array('options' => $options,'empty'=>'Select Office','div'=>false,'legend'=>false,'class' => 'form-control selectpicker show-tick','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3', 'readonly'=>'readonly'));
								?>
								</div>
							</div>
							
                          <div class="form-group m-b-0">
                            <div>
                              <button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>
                              
                            </div>
                          </div>
                        
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
	//$('select[readonly="readonly"] option:not(:selected)').attr('disabled',true);
	$("#UserOfficeId option:not(:selected)").attr("disabled", "disabled")
		$( ".datepicker" ).datepicker({ dateFormat: 'mm/dd/yy',changeMonth: true,
      changeYear: true});
	  
	});
	function readURL(input){
	 if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#preview').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
  }
</script>