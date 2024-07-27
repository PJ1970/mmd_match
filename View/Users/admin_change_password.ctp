<div class="panel panel-color panel-primary outer_login_pages">
  	<div class="black"><img src=<?php  echo WWW_BASE.'img/admin/black-and-white.png';?> alt="black-and-white"/></div>
    <div class="panel-body login_pages_block">
      <h3 class="m-t-0 m-b-15">
      <span class="left">Change Password</span>
      <a href="<?php echo $this->HTML->url('/admin/dashboards/index'); ?>" class="right"><img src=<?php echo WWW_BASE.'img/admin/logo.png';?> alt="Logo"/></a>      
     </h3>
	 <?php echo $this->Session->flash();?>
    <div class="login_logout">
      
        <?php echo $this->Form->create('User', array('novalidate' => true, 'id'=>'loginFrm', 'url'=>array('action'=>'admin_change_password'),'class'=>'form-horizontal m-t-20'));
		?>
		<div class="form-group">
          <div class="col-xs-12">
            <label>Original Password</label>
			
			<?php echo $this->Form->input('oldpassword',array('type'=>'password','class'=>'form-control','label'=>false,'div'=>false)); ?>
          </div>
        </div>
		
		<div class="form-group">
          <div class="col-xs-12">
            <label>New Password</label>
			
			<?php echo $this->Form->input('password',array('type'=>'password','class'=>'form-control','label'=>false,'div'=>false)); ?>
      <span style="color:#000099; font-style: italic;">Password must be between 8 to 16 characters long and contain at least one uppercase, one lowercase, one number, and one special character.</span><br>
          </div>
        </div>
		
		<div class="form-group">
          <div class="col-xs-12">
            <label>Confirm Password</label>
			
			<?php echo $this->Form->input('confirm_password',array('type'=>'password','class'=>'form-control','label'=>false,'div'=>false)); ?>
          </div>
        </div>
       <div class="form-group">
          <div class="col-xs-12">
            <div class="col-sm-3 col-sm-offset-9 padding_zero">
			<a class="back" href="<?php echo WWW_BASE;?>admin/dashboards/index" role="button"> <span class="glyphicon glyphicon-chevron-left"></span>Back</a>
			</div>
          </div>
        </div>
        <div class="form-group bottom_btn">		
          <div class="col-xs-12">
            <button class="btn btn-primary waves-effect waves-light" type="submit">Submit</button>
          </div>
		  
        </div>
		<?php echo $this->Form->end();?>

      </div>
    </div>
  </div>








