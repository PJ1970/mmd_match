<div class="panel panel-color panel-primary outer_login_pages">
  	<div class="black"><img src="<?php  echo WWW_BASE.'img/admin/black-and-white.png';?>" alt="black-and-white"/></div>
    <div class="panel-body login_pages_block">
      <h3 class="m-t-0 m-b-15">
      <span class="left">Forgot Password</span>
      <a href="index.html" class="right"><img src="<?php echo WWW_BASE.'img/admin/logo.png';?>" alt="Logo"/></a>      
     </h3>
	 <?php echo $this->Session->flash();?>
    <div class="login_logout">
        <?php echo $this->Form->create('User', array('novalidate' => true, 'id'=>'loginFrm', 'url'=>array('action'=>'admin_forgot_password'),'class'=>'form-horizontal m-t-20'));
		?>
		<div class="form-group">
          <div class="col-xs-12">
            <!--<label>Email</label>-->
			<label>Username</label>
			<?php //echo $this->Form->input('email',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false)); ?> 
			<?php echo $this->Form->input('username',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false)); ?> 
            <div class="col-sm-3 col-sm-offset-9 padding_zero">
			<a class="back" href="<?php echo WWW_BASE;?>admin/users/login" role="button"> <span class="glyphicon glyphicon-chevron-left"></span>Back</a>
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