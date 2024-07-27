<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Edit Admin</h4>
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
					
				    <?php echo $this->Form->create('User', array('novalidate' => true, 'id'=>'loginFrm', 'url'=>array('action'=>'admin_edit_admin'),'enctype'=>'multipart/form-data'));?>
                    <div class="col-sm-6 col-xs-12">
                     <!-- <h3 class="header-title m-t-0"><small class="text-info"><b>Validation type</b></small></h3>-->
                      <div class="m-t-20">
                        <!--<form class="" action="#">-->
						<?php echo $this->Form->input('id',array('type'=>'hidden','value'=>$data['User']['id']));?>
                          <div class="form-group">
                            <label>First Name</label>
                            <!--<input type="text" class="form-control" required placeholder="Type something"/>-->
							<?php echo $this->Form->input('first_name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"First Name",'required'=>true)); ?>
                          </div>
						  <div class="form-group">
                            <label>Middle Name</label>
                            <!--<input type="text" class="form-control" required placeholder="Type something"/>-->
							<?php echo $this->Form->input('middle_name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Middle Name",'required'=>true)); ?>
                          </div>
						   <div class="form-group">
                            <label>Last Name</label>
                            <!--<input type="text" class="form-control" required placeholder="Type something"/>-->
							<?php echo $this->Form->input('last_name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Middle Name",'required'=>true)); ?>
                           </div>
                          
                          
						   <div class="form-group">
                            <label>Phone</label>
                            <div>
                              <!--<input type="email" class="form-control" required parsley-type="email" placeholder="Enter a valid e-mail"/>-->
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
                          <!--<div class="form-group">
                            <label>Gender</label>
                            <div>
							<?php //$options = array('Male'=>'Male','Female'=>'Female');
							//echo $this->Form->input('gender',array('type' => 'radio', 'options' => $options,'div'=>false,'legend'=>false,'separator'=> '</div><div class="right radios">','before' => '<div class="form-control">',
                                     //'after' => '</div>',));?>
                              <!--<select class="form-control">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                              </select>
                            </div>
                          </div>-->
                          
                        
                          <!--<div class="form-group">
                            <label>Textarea</label>
                            <div>
                              <textarea required class="form-control" rows="5"></textarea>
                            </div>
                          </div>-->
                         <!-- <div class="form-group">
                            <div>
                              <button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>
                              <button type="reset" class="btn btn-default waves-effect m-l-5"> Cancel </button>
                            </div>
                          </div>-->
                        <!--</form>-->
						
                      </div>
                    </div>
                   <div class="col-sm-6 col-xs-12">
				   
						<div class="m-t-20">
							<!--<div class="form-group">
								<label>Date Of Birth</label>
								<div>
									<?php echo $this->Form->input('dob',array('type'=>'text','class'=>'datepicker form-control','label'=>false,'div'=>false,'placeholder'=>"Date Of Birth",'required'=>true)); ?>
								</div>
							</div>-->
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
                             <!-- <input parsley-type="url" type="url" class="form-control" required placeholder="URL"/>-->
                            </div>
                          </div>
                            <div class="form-group">
                            <label>Password</label>
                            <div>
               <?php echo $this->Form->input('password',array('type'=>'password','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Enter a Password",'required'=>true)); ?>
               <span style="color:#000099; font-style: italic;">Password must be between 8 to 16 characters long and contain at least one uppercase, one lowercase, one number, and one special character.</span>
                             <!-- <input parsley-type="url" type="url" class="form-control" required placeholder="URL"/>-->
                            </div>
                          </div>
							<!--<div class="form-group">
								<label>Select Practice Name</label>
									<div>
										<?php 
											//$practice_list=$this->custom->getPracticeList();
											//echo $this->Form->input('practice_id',array('multiple' => true,'options' => $practice_list,'div'=>false,'legend'=>false,'class' => 'form-control selectpicker show-tick','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3'));
										?>
									</div>
							</div>-->
                          <!--<div class="form-group">
                            <label>Max Value</label>
                            <div>
                              <input type="text" class="form-control" required data-parsley-max="6" placeholder="Max value is 6"/>
                            </div>
                          </div>
                          <div class="form-group">
                            <label>Range Value</label>
                            <div>
                              <input class="form-control" required type="text range" min="6" max="100" placeholder="Number between 6 - 100"/>
                            </div>
                          </div>
                          <div class="form-group">
                            <label>Regular Exp</label>
                            <div>
                              <input type="text" class="form-control" required data-parsley-pattern="#[A-Fa-f0-9]{6}" placeholder="Hex. Color"/>
                            </div>
                          </div>
                          <div class="form-group">
                            <label>Min check</label>
                            <div class="radio">
                              <input type="radio" name="radio" id="radio1" value="option1" checked="checked">
                              <label for="radio1"> Small </label>
                            </div>
                            <div class="radio">
                              <input type="radio" name="radio" id="radio2" value="option2" checked="checked">
                              <label for="radio2"> Big </label>
                            </div>
                            <div class="radio">
                              <input type="radio" name="radio" id="radio3" value="option3" checked="checked">
                              <label for="radio3"> Medium </label>
                            </div>
                          </div>
                          <div class="form-group">
                            <label>Max check</label>
                            <div>
                              <div class="checkbox checkbox-primary">
                                <input id="checkbox4" type="checkbox" data-parsley-multiple="group1">
                                <label for="checkbox4"> And this </label>
                              </div>
                              <div class="checkbox checkbox-primary">
                                <input id="checkbox5" type="checkbox" data-parsley-multiple="group1">
                                <label for="checkbox5"> Can't check this </label>
                              </div>
                              <div class="checkbox checkbox-primary">
                                <input id="checkbox6" type="checkbox" data-parsley-multiple="group1" data-parsley-maxcheck="1">
                                <label for="checkbox6"> This too </label>
                              </div>
                            </div>
                          </div>-->
                          <div class="form-group m-b-0">
                            <div>
                              <button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>
                              <!--<a type="reset" class="btn btn-default waves-effect m-l-5"> Cancel </a>-->
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