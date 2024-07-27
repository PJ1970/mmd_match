<style type="text/css">
.battery-name{
  visibility: visible;
    text-align: center;
    margin-bottom: 8px;
    color: #fff;
    font-size: 12px;
}

.battery-point{
  text-align: center;
    font-size: 12px;
    color: #fff;
}</style>
<div class="topbar">
    <div class="topbar-left">
      <div class="text-center"> <a href="<?php echo $this->HTML->url('/admin/dashboards/index'); ?>" class="logo"><img src="<?php echo WWW_BASE.'img/admin/inner-logo.jpg';?>" alt="Inner-Logo"></a>
      <a href="<?php echo $this->HTML->url('/admin/dashboards/index'); ?>" class="logo-sm"><span><img src="<?php echo WWW_BASE.'img/admin/mobile-logo.png';?>"  alt="Mobile-Logo"></span></a></div>
    </div>
	
    <div class="navbar navbar-default" role="navigation">
	
      <div class="container">
        <div class="">
          <div class="pull-left">
            <button type="button" class="button-menu-mobile open-left waves-effect waves-light"> <i class="ion-navicon"></i> </button>
            <span class="clearfix"></span></div>
            <?php if($this->Session->read('Auth.Admin.user_type') =='Admin' && $this->params['action']!= 'admin_test_reports_list'){ ?>
              <ul class="nav navbar-nav navbar-right pull-right" style='width:670px;'>
              <?php }else{ ?>
                <div class="main-div pull-right" style="display:flex;align-items: center;">
                  <div class="bettry" style="display: flex;"> 
                    <div class="betrry_inner">
                <div id="Lbattery" class="battery-name"  style='visibility: hidden;'>L</div>
                    <div  id="battery2" style="
                        background-position: center bottom 3px;
                        background: linear-gradient(to right, #00ff00 0%, #00ff00 67%, #d6d6d6 30%, #d6d6d6 100%);
                        display: inline-block;
                        height: 20px; visibility: hidden;
                        transform: rotateZ(270deg);
                        ">
                        <i class="fa fa-battery-0" id="fa-battery" style="font-size:32px;color:#000000;margin-top:-6px;margin-right: -2px;"></i>
                    </div>
                    <div id="battery3p" class="battery-point" style='visibility: hidden;'></div>
              </div>
              <div class="betrry_inner">
                  <div id="VFbattery" class="battery-name" style='visibility: hidden;'>VF</div>
                    <div  id="battery" style="
                        background-position: center bottom 3px;
                        background: linear-gradient(to right, #00ff00 0%, #00ff00 67%, #d6d6d6 30%, #d6d6d6 100%);
                        display: inline-block;
                        height: 20px; visibility: hidden;
                        transform: rotateZ(270deg);
                        ">
                        <i class="fa fa-battery-0" id="fa-battery" style="font-size:32px;color:#000000;margin-top:-6px;margin-right: -2px;"></i>
                    </div>
                    <div id="battery2p" class="battery-point" style='visibility: hidden;'></div>
              </div>
              <div class="betrry_inner">
                      <div id="Rbattery" class="battery-name"  style='visibility: hidden;'>R</div>
                        <div  id="battery1" style="
                              background-position: center bottom 3px;
                              background: linear-gradient(to right, #00ff00 0%, #00ff00 67%, #d6d6d6 30%, #d6d6d6 100%);
                              display: inline-block;
                              height: 20px; visibility: hidden;
                              transform: rotateZ(270deg);
                              ">
                        <i class="fa fa-battery-0" id="fa-battery" style="font-size:32px;color:#000000;margin-top:-6px;margin-right: -2px;"></i>
                      </div>
                    <div id="battery1p" class="battery-point" style='visibility: hidden;'></div>
              </div>
              
      </div>
                    <ul class="nav navbar-nav navbar-right">
                      <li class="hidden-xs"> <a href="#" id="btn-fullscreen" class="waves-effect waves-light notification-icon-box"><i class="mdi mdi-fullscreen"></i></a></li>
    <?php $profile_pic=$this->Session->read('profile_pic');if(empty($profile_pic)){$profile_pic='no-user.png';}?>
            <li class="dropdown"> <a href="#" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true"> <img src="<?php echo WWW_BASE.'img/uploads/'.$profile_pic;?>" alt="user-img" class="img-circle"> <span class="profile-username profile-username_client "><?php echo $this->Session->read('username');?> <br/>
              </span> </a>
              <ul class="dropdown-menu">
                <!--<li><a href="javascript:void(0)"> Profile</a></li>
                <li><a href="javascript:void(0)"><span class="badge badge-success pull-right">5</span> Settings </a></li>
                <li><a href="javascript:void(0)"> Lock screen</a></li>-->
        <li><?php echo $this->Html->link('Edit Profile',array('controller'=>'users','action'=>'admin_edit_profile'));?></li>
        <li class="divider"></li>
        <li><?php echo $this->Html->link('Change Password',array('controller'=>'users','action'=>'admin_change_password'));?></li>
                <li class="divider"></li>
        
                <li><?php echo $this->Html->link('Logout',array('controller'=>'users','action'=>'admin_logout'));?></li>
              </ul>
                </div>
              <?php } ?>
          
            <!--<li class="dropdown hidden-xs"> <a href="#" data-target="#" class="dropdown-toggle waves-effect waves-light notification-icon-box" data-toggle="dropdown" aria-expanded="true"> <i class="fa fa-bell"></i> <span class="badge badge-xs badge-danger"></span> </a>
              <ul class="dropdown-menu dropdown-menu-lg">
                <li class="text-center notifi-title">Notification <span class="badge badge-xs badge-success">3</span></li>
                <li class="list-group"> <a href="javascript:void(0);" class="list-group-item">
                  <div class="media">
                    <div class="media-heading">Your order is placed</div>
                    <p class="m-0"> <small>Dummy text of the printing and typesetting industry.</small></p>
                  </div>
                  </a> <a href="javascript:void(0);" class="list-group-item">
                  <div class="media">
                    <div class="media-body clearfix">
                      <div class="media-heading">New Message received</div>
                      <p class="m-0"> <small>You have 87 unread messages</small></p>
                    </div>
                  </div>
                  </a> <a href="javascript:void(0);" class="list-group-item">
                  <div class="media">
                    <div class="media-body clearfix">
                      <div class="media-heading">Your item is shipped.</div>
                      <p class="m-0"> <small>It is a long established fact that a reader will</small></p>
                    </div>
                  </div>
                  </a> <a href="javascript:void(0);" class="list-group-item"> <small class="text-primary">See all notifications</small> </a></li>
              </ul>
            </li>-->
            <?php if($this->Session->read('Auth.Admin.Office.backup')==1 && $this->Session->read('Auth.Admin.user_type')=='SuperSubadmin'){ ?>
                <script>
                var call_time=300;
                $( document ).ready(function() {
                    get_reportBackup();
});
function get_reportBackup(){
    
   var feedback = $.ajax({
          type: "POST",
          url: "<?php echo WWW_BASE; ?>/admin/patients/test3",
           data: {},
          async: false
      }).success(function(){ 
          setTimeout(function(){get_reportBackup();}, call_time);
      }).responseText;
      obj = JSON.parse(feedback);
      if(obj.status==1){
          window.open("https://www.vibesync.com/admin/patients/download_report");
      }else{
          call_time=100;
      }
                    }
                    
                </script>
            <?php }   ?>
			<?php if($this->Session->read('Auth.Admin.user_type') =='Admin' && $this->params['action']!= 'admin_test_reports_list'){ 


        ?>
			<li style="padding-right:10px;margin-right: 80px;"> 
				<?php echo $this->Form->create('Dashboard',array('id' => 'selectOfficeForm','class'=> 'navbar-form pull-left','url' =>array('action' => 'assign_office'))); ?>
				 
				<div class="form-group">
          
          <?php echo $this->Form->input('currenturl', array('type' => 'hidden','value'=>$this->request->here(),'div' => false)); ?>

          <!--  <?php echo $this->Form->input('office2', array('type' => 'text','list' => 'browsers', 'class' => 'form-control search-bar serachSelect','empty'=>'Show All', 'label' => false, 'div' => false)); ?>
          
                      <datalist id="browsers">
                        <?php
                          foreach ($officesList as $key => $value) {
                            ?><option value="<?php echo $value; ?>"></option> <?php
                          }
                         ?>
                        <option value="{{client_catogory}}">
                      </datalist>
 -->

				 <?php echo $this->Form->input('office',array('type' => 'select','empty'=>'Show All','default' => @$this->Session->read('Search.office'),'options' => @$officesList,'class' => 'form-control search-bar selectOffice','label' => false,'div' => false)) ?>
				</div>
			   
				<?php echo $this->Form->end();?>
				
			</li>
      <ul class="nav navbar-nav navbar-right pull-right" style=''>
      <li class="hidden-xs"> <a href="#" id="btn-fullscreen" class="waves-effect waves-light notification-icon-box"><i class="mdi mdi-fullscreen"></i></a></li>
    <?php $profile_pic=$this->Session->read('profile_pic');if(empty($profile_pic)){$profile_pic='no-user.png';}?>
            <li class="dropdown"> <a href="#" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true"> <img src="<?php echo WWW_BASE.'img/uploads/'.$profile_pic;?>" alt="user-img" class="img-circle"> <span class="profile-username profile-username_client "><?php echo $this->Session->read('username');?> <br/>
              </span> </a>
              <ul class="dropdown-menu">
                <!--<li><a href="javascript:void(0)"> Profile</a></li>
                <li><a href="javascript:void(0)"><span class="badge badge-success pull-right">5</span> Settings </a></li>
                <li><a href="javascript:void(0)"> Lock screen</a></li>-->
        <li><?php echo $this->Html->link('Edit Profile',array('controller'=>'users','action'=>'admin_edit_profile'));?></li>
        <li class="divider"></li>
        <li><?php echo $this->Html->link('Change Password',array('controller'=>'users','action'=>'admin_change_password'));?></li>
                <li class="divider"></li>
        
                <li><?php echo $this->Html->link('Logout',array('controller'=>'users','action'=>'admin_logout'));?></li>
      <?php } ?>
     
            
            </li>
          </ul>
    </ul>

          </ul>
        </div>
      </div>
    </div>
  </div>
   <script>
 $('body').on('change','.selectOffice',function(){
   $('#selectOfficeForm').submit();
 });
 </script>
 <?php if($this->Session->read('Auth.Admin.Office.backup')== 1 && $this->Session->read('Auth.Admin.user_type')=='SuperSubadmin'){ ?>
 <script>
    $(document).ready(function(){
      setInterval(function(){ reload_page(); },30*60000);
    });
   function reload_page(){
    window.location.reload(true);
   }
 </script>
 <?php } ?>