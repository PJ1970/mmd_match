<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Edit Profile</h4>
        </div>
      </div>
      <div class="page-content-wrapper ">
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-primary">
                <div class="panel-body">
                  <div class="row">
				    <?php echo $this->Form->create('User', array('novalidate' => true, 'id'=>'editFrm', 'url'=>array('controller'=>'users','action'=>'admin_edit_profile'),'enctype'=>'multipart/form-data'));?>
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
								
								
									<?php /*echo $this->Form->input('rotate', array(
															'type' => 'radio',
															'div'=>false,
															'label'=>false,
															'legend'=>false,
															'options' => array(
																'90' => '90',
																'180' => '180',
																'270' => '270'
																)
															));*/?> 
									
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
						  <?php if($this->Session->read('Auth.Admin.user_type') =='Admin'){ ?>
						  <div class="form-group">
                            <label>Paypal ID</label>
                            <div>
							 <?php echo $this->Form->input('paypal_id',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Enter Paypal ID",'required'=>true)); ?>
                            </div>
                          </div>
						  <?php  }  ?>
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
	function readURL(input){
	 if (input.files && input.files[0]){
            var reader = new FileReader();
            
            reader.onload = function (e){
                $('#preview').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
	}
	</script>