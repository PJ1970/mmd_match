<?php echo $this->Html->script(array('jquery.form','User/profile'));?>
<?php //echo $this->element('left-section');
	  
	 //pr($this->validationErrors);
	?>
  <section class="my_container">
			<div class="flash_dash"><?php echo $this->Session->flash();?></div>
			 
		    <div class="main_container"> 
			<h2><span class="bt_color">BT</span> Visitors <span class="sub_heading">Fill the form below and we will contact you shortly</span></h2>
			
			<div class="myprofile_topsection">
			<?php 
				echo $this->Form->create('Contact', array('role' => 'form','novalidate'=>true,'class'=>'register_form','enctype'=>'multipart/form-data' ));
				
			?>
                <div class="col-md-12">
				   <?php if(!empty($shows)){ ?>
                	<div class="right_formblock">
					<div class="row">
					   	<div class="col-md-12">
								<div class="form-group">
								    <div style="font-size:25px;">
									<?php 
									if(!empty($shows)){
									echo $this->Form->input('show_id', array('type' => 'hidden','value'=>$shows['Show']['id']));
									echo $shows['Show']['name']; 
									}?>
								    </div>
								</div>
							</div>
					    </div>
                    	<div class="row">
					   	<div class="col-md-12">
								
							</div>
							<div class="col-md-6">
								<div class="form-group">
									
									<?php echo $this->Form->input('name', array('div' => false, 'type' => 'text', 'placeholder' => __('Name'), 'class' => 'form-control','label' => __('Name') . '<span class="mandetory">*</span>')); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<?php echo $this->Form->input('email', array('div' => false, 'type' => 'text', 'placeholder' => __('Email'), 'class' => 'form-control','label' =>  __('Email') . '<span class="mandetory">*</span>')); ?>
								</div>
							</div>
                        </div>
                        <div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<?php echo $this->Form->input('company_name', array('div' => false, 'type' => 'text', 'placeholder' => __('Company Name'), 'class' => 'form-control','label' => __('Company Name') . '<span class="mandetory">*</span>')); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<?php echo $this->Form->input('company_website', array('div' => false, 'type' => 'text', 'placeholder' => __('Company website'), 'class' => 'form-control','label' =>  __('Company Website'))); ?>
								</div>
							</div>
                        </div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<?php echo $this->Form->input('mobile', array('div' => false, 'type' => 'text', 'placeholder' => __('Mobile'), 'class' => 'form-control','label' => __('Mobile') . '<span class="mandetory">*</span>')); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<?php echo $this->Form->input('phone', array('div' => false, 'type' => 'text', 'placeholder' => __('Phone'), 'class' => 'form-control','label' =>  __('Phone'))); ?>
								</div>
							</div>
                        </div>
						<?php if(!empty($options)){
							//pr($options);
							?>
						<div class="col-md-12">
                        	<div class="form-group">
                            	<div style="text-transform:uppercase; color:#666;">Interested In</div>
								
								<?php 
								//echo $this->Form->input('interest', array('multiple' => 'checkbox', 'options' => $options,'div'=>false,'class' => 'form-control_check','label'=>' Interested in'));
								
								?>
								<div class="checkbox">
								    <?php 
									$i=1;
									foreach($options as $option){ ?>
									<div class="inn_chk">
									  <input type="checkbox"  name="data[Contact][interest][]" id="checkboxG<?php echo $i; ?>" class="css-checkbox"  value="<?php echo $option['Interest']['id']; ?>"/>
									  <label for="checkboxG<?php echo $i; ?>" class="css-label"><?php echo $option['Interest']['name']; ?></label>
									</div>
									<?php $i++;} ?>
								</div>
                            </div>
                        </div>
						<div class="col-md-12">
							<div class="form-group">
								<button class="profile_btn_save"><?php echo __('Save'); ?></button>
								<!--<a class="profile_btn_cncl" href="<?php echo WWW_BASE;?>"><?php echo __('Cancel'); ?></a>-->
								<?php echo $this->Form->reset('Cancel',array('class'=>'profile_btn_cncl'));?>
							</div>
						</div>
						<?php } ?>
                    </div>
				   <?php }else{
					?>
					<div class="right_formblock">
					   <div class="row">
					    <div style="text-align:center;">
						Show not available.
						</div>
                        </div>
					</div>
				   <?php } ?>
                </div>
            
        </div><!--/ Form Top Section/-->
			
			</div>
   <?php echo $this->Form->end();	?>
    </section>
<script>
  $(function() {
    $( "#datetimepicker1" ).datepicker({dateFormat:'yy-mm-dd'});
  });
</script>
