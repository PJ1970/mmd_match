<div class="panel panel-color panel-primary outer_login_pages">
    <div class="black"><img src=<?php  echo WWW_BASE.'img/admin/black-and-white.png';?> alt="black-and-white"/></div>
    <div class="panel-body login_pages_block">
      <h3 class="m-t-0 m-b-15">
      <span class="left">Login</span>
      <a href="#" class="right"><img src=<?php echo WWW_BASE.'img/admin/logo.png';?> alt="Logo"/></a>      
     </h3>
   <?php echo $this->Session->flash();?>
    <div class="login_logout">
      
        <?php echo $this->Form->create('User', array('novalidate' => true, 'id'=>'loginFrm', 'url'=>array('action'=>'admin_login'),'class'=>'form-horizontal m-t-20'));
    ?>
    <div class="form-group">
          <div class="col-xs-12">
            <label>Username</label>
           <!-- <input class="form-control" type="text" required="">-->
      
      <?php echo $this->Form->input('username',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,"autocomplete"=>false)); ?>
          </div>
        </div>
        <div class="form-group new_form_group">
          <div class="col-xs-12">
          <label>Password</label>
          <?php echo $this->Form->input('password', array('type'=>'password','label'=>false,'class'=>'form-control','id'=>'password','div'=>false,"autocomplete"=>false)); ?>
      </div>
        </div>
          <div class="captcha form-group">
                   
          <!-- <script src='<?php //echo WWW_BASE.'js/admin/api.js';?>'></script> -->
          <script src='https://www.google.com/recaptcha/api.js'></script>
          <div class="g-recaptcha" data-sitekey="6Le15VAUAAAAAEgfp9XCTpIzJIWokXlzZrnM1z_N"></div>
          <?php echo isset($capchaError)?$capchaError:'';?>
          </div>
        <div class="form-group">
          <div class="col-xs-12">
            <div class="checkbox checkbox-primary">
            <div class="col-sm-5 padding_zero">
      <?php echo $this->Form->input('User.remember_me',array('type'=>'checkbox','label'=>false,'div'=>false)); ?>             
       <label class="rem" for="checkbox-signup"> Remember me </label>
            </div>  
            <div class="col-sm-7 padding_zero"><!-- <a href="pages-recoverpw.html" class="text-muted"> Forgot password?</a>--><?php echo $this->Html->link('Forgot password?',array('action'=>'forgot_password'),array('escape'=>false,'class'=>'text-muted'));?></div>
            </div>
          </div>
        </div>
        <div class="form-group bottom_btn">
          <div class="col-xs-12">
            <button class="btn btn-primary waves-effect waves-light" type="submit">Sign In</button>
          </div>
        </div>
    <?php echo $this->Form->end();?>
      </div>
    </div>
  </div>