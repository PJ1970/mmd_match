<style type="text/css">
.battery-name{
  visibility: visible;
    text-align: center;
    margin-bottom: 8px;
    color: #fff;
    font-size: 12px;
}
.test-patient-section{
    margin-left:30px;
}
.battery-point{
  text-align: center;
    font-size: 12px;
    color: #fff;
}</style>

<style>
    #result {
  border: 1px dotted #ccc;
  padding: 3px;
  position: absolute;
    background: #3392e0;
    /*margin-left: 30px;*/
    visibility: hidden;
}
#result ul {
  list-style-type: none;
  padding: 0;
  margin: 0;
}
#result ul li {
  padding: 5px 0;
  min-width: 242px;
}
#result ul li:hover {
  background: #eee;
}
#change-patinet::placeholder { /* Microsoft Edge */
   color:    #DFCECE;
   font-size:16px;
}
body:not(.start_test_page) .item.item2, body:not(.start_test_page) .item.item3 { display: none; }
body:not(.start_test_page) .item.item4 { justify-content: flex-end;    width: initial; }
body:not(.start_test_page) .bettry { display: none!important; }
</style>

 <script>
 SearchRequest = null; 
function autocompleteMatch(input) {
  if (input == '') {
      res.innerHTML = '';
      $("#result").css('visibility', 'hidden');
    return [];
  } 
    SearchRequest = $.ajax({
      url: "<?php echo WWW_BASE; ?>admin/patients/autoCompleetList",
      type: 'GET',
      beforeSend: function () {
                if (SearchRequest != null) {
                    SearchRequest.abort();
                }
            },
      data: {'search':input},
      success: function (data) {
          res = document.getElementById("result");
                res.innerHTML = '';
        terms= jQuery.parseJSON(data); 
        let list = '';
        for (i=0; i<terms.length; i++) { 
    list += '<li onclick="changePatinet('+terms[i].Patient.id+')">' + terms[i].Patient.patient_name + '</li>';
  }
  res.innerHTML = '<ul>' + list + '</ul>';
   $("#result").css('visibility', 'visible');
      }
    }); 
}

function showResults(val) {
  let terms = autocompleteMatch(val); 
}
</script>
<div class="topbar">
    <div class="topbar-left">
      <div class="text-center"> <a href="<?php echo $this->HTML->url('/admin/dashboards/index'); ?>" class="logo"><img src="<?php echo WWW_BASE.'img/admin/inner-logo.jpg';?>" alt="Inner-Logo"></a>
      <a href="<?php echo $this->HTML->url('/admin/dashboards/index'); ?>" class="logo-sm"><span><img src="<?php echo WWW_BASE.'img/admin/mobile-logo.png';?>"  alt="Mobile-Logo"></span></a></div>
    </div>
	
    <div class="navbar navbar-default" role="navigation">
	
      <div class="container">
        <?php if($this->Session->read('Auth.Admin.user_type') =='Admin' && $this->params['action']!= 'admin_test_reports_list'){ ?>
        <div class="full_box row" style='display: block;'>
             <?php }else{ ?>
              <div class="full_box row">
              <?php }?>
          <div class="pull-left item item1 col-sm-2 mts-box-1">
            <button type="button" class="button-menu-mobile open-left waves-effect waves-light"> <i class="ion-navicon"></i> </button>
            <span class="clearfix"></span></div>
            <?php if($this->Session->read('Auth.Admin.user_type') =='Admin' && $this->params['action']!= 'admin_test_reports_list'){ ?>
              <ul class="nav navbar-nav navbar-right pull-right" style='width:670px;display: block;'>
              <?php }else{ ?>
                <div class="spacer item item2 col-sm-3 mts-box-1"></div>
              <div class="middle item item3 col-sm-2 mts-box-1">
                  
                                     <div>
                    <input type="text" placeholder="Select Patient" style="height: 36px; margin-top: 17px; background:none; visibility: hidden; border: 2px solid #ffffff; color:#ffffff; min-width: 250px;" id="change-patinet" class="test-patient-section" onKeyUp="showResults(this.value)" />
<div id="result"></div></div>
                  
              </div>
              <?php 
                   
                  $version = 1;//CustomHelper::getVersion();
                   
                   Configure::write('Version', @$version['Version']['version']);
                  ?>
                <div class="main-div pull-right item item4 col-sm-5 mts-box-1" style="display:flex;align-items: center;">
                  <div class="bettry" style="display: flex;"> 
                    <button style="height: 36px; margin-top: 17px; font-size:16px;   background:none; visibility: hidden;  border: 2px solid #ffffff;  color:#ffffff;" class="test-patient-section add_start_test_reprrt" >Add Patient</button>
                  <a href="javascript:void(0);" style="visibility: hidden;  border: 2px solid #ffffff !important; color:#ffffff;"  class="waves-effect waves-light notification-icon-box test-patient-section view_start_test_reprrt" title="View Patient Report"><i class="mdi mdi-eye"></i></a>
                  
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
            <li class="dropdown"> <a href="#" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true"> <img src="<?php echo WWW_BASE.'img/uploads/'.$profile_pic;?>" alt="user-img" class="img-circle"> <span class="profile-username profile-username_client "><?php echo substr($this->Session->read('username'),0,5);?> <br/>
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
          window.open("https://www.portal.micromedinc.com/admin/patients/download_report");
      }else{
          call_time=100;
      }
                    }
                    
                </script>
            <?php }   ?>
                    
                <script>
                var call_refresh=60000;
                $( document ).ready(function() {
                    refresh_token();
});
function refresh_token(){
    
   var feedback = $.ajax({
          type: "POST",
          url: "<?php echo WWW_BASE; ?>admin/unityreports/refresh_token",
           data: {},
          async: false
      }).success(function(){ 
          setTimeout(function(){refresh_token();}, call_refresh);
      });
       
                    }
                    
                </script> 
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
            <li class="dropdown"> <a href="#" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true"> <img src="<?php echo WWW_BASE.'img/uploads/'.$profile_pic;?>" alt="user-img" class="img-circle"> <span class="profile-username profile-username_client "><?php echo substr($this->Session->read('username'),0,5);?> <br/>
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
    <div id="addPatientViewStartTest" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content" id="addPatientContentStartTest">
        </div>
      </div>
    </div>
      <div id="patientViewStartTest" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content" id="patientContentStartTest">
        </div>
      </div>
    </div>
    <div id="patientViewVideo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content" id="patientContentVideo">
        </div>
      </div>
    </div>
   <script>
   
    function changePatinet(patientId) {
        var test_page_url=window.location.href;
        var url_array = test_page_url.split("/"); 
        url_array[6]=patientId;
        var new_url = url_array.join("/"); 
        window.location.replace(new_url);
    }
     jQuery(document).on("click",".add_start_test_reprrt",function() {
$('body').append('<div class="customFacebox" id="facebox" style="top: 70.8px; left: 475.5px;"><div class="popup popup56"><div class="content" style="padding: 45px"><div class="loading"><p style="color:#00aaff;"><b>Processing........Please do not click anywhere on the page until the process is complete.</b></p><img src="'+ajax_url+'img/ajaxloader.gif"></div> </div></div></div>');
jQuery("#addPatientContentStartTest").load("<?php echo WWW_BASE?>/admin/patients/addPatientNew/?rempve_layout=1", function(result) {
      jQuery("#addPatientViewStartTest").modal("show");
     $('.customFacebox').remove();
     });
  });
  
  
   jQuery(document).on("click",".view_start_test_reprrt",function() {
      
    $('body').append('<div class="customFacebox" id="facebox" style="top: 70.8px; left: 475.5px;"><div class="popup popup56"><div class="content" style="padding: 45px"><div class="loading"><p style="color:#00aaff;"><b>Processing........Please do not click anywhere on the page until the process is complete.</b></p><img src="'+ajax_url+'img/ajaxloader.gif"></div> </div></div></div>');
<?php if($this->params['action'] == "admin_start_test"){ ?> 

jQuery("#patientContentStartTest").load("<?php echo WWW_BASE?>/admin/unityreports/unity_reports_list/vf?rempve_layout=1&search=<?php echo @$data['Patient']['id'] ?>", function(result) {
      jQuery("#patientViewStartTest").modal("show");
     $('.customFacebox').remove();
     });
     
  <?php } elseif($this->params['action'] == "admin_start_test_fdt"){ ?>
  
  
jQuery("#patientContentStartTest").load("<?php echo WWW_BASE?>/admin/unityreports/unity_reports_list/FDT?rempve_layout=1&search=<?php echo @$data['Patient']['id'] ?>", function(result) {
      jQuery("#patientViewStartTest").modal("show");
     $('.customFacebox').remove();
     });
  
  <?php }elseif($this->params['action'] == "admin_start_test_da"){ ?>
  
  
jQuery("#patientContentStartTest").load("<?php echo WWW_BASE?>/admin/darkadaptations/dark_adaptations_list?rempve_layout=1&search=<?php echo @$data['Patient']['id'] ?>", function(result) {
      jQuery("#patientViewStartTest").modal("show");
     $('.customFacebox').remove();
     });
  <?php }elseif($this->params['action'] == "admin_start_test_vs"){ ?>
  
  
jQuery("#patientContentStartTest").load("<?php echo WWW_BASE?>/admin/unityreports/unity_reports_list/VS?rempve_layout=1&search=<?php echo @$data['Patient']['id'] ?>", function(result) {
      jQuery("#patientViewStartTest").modal("show");
     $('.customFacebox').remove();
     });
  <?php }elseif($this->params['action'] == "admin_start_test_pup"){ ?>
  
  
jQuery("#patientContentStartTest").load("<?php echo WWW_BASE?>/admin/pup/pup_list?rempve_layout=1&search=<?php echo @$data['Patient']['id'] ?>", function(result) {
      jQuery("#patientViewStartTest").modal("show");
     $('.customFacebox').remove();
     });
      <?php } ?>
  });

   jQuery(document).on("click",".view_start_test_video",function() {
      
    $('body').append('<div class="customFacebox" id="facebox" style="top: 70.8px; left: 475.5px;"><div class="popup popup56"><div class="content" style="padding: 45px"><div class="loading"><p style="color:#00aaff;"><b>Processing........Please do not click anywhere on the page until the process is complete.</b></p><img src="'+ajax_url+'img/ajaxloader.gif"></div> </div></div></div>');
<?php if($this->params['action'] == "admin_start_test"){ ?> 

jQuery("#patientContentVideo").load("<?php echo WWW_BASE?>/admin/unityreports/video_listing/"+ new Date().getTime()+"?rempve_layout=1&search=<?php echo @$data['Patient']['id'] ?>&" , function(result) {
      jQuery("#patientViewVideo").modal({backdrop: 'static', keyboard: false}, "show");
     $('.customFacebox').remove();
     });
     
  <?php }?>
  });
  jQuery(document).on("click","#add-patient-from-start",function() {
    //  $('body').append('<div class="customFacebox" id="facebox" style="top: 70.8px; left: 475.5px;"><div class="popup popup56"><div class="content" style="padding: 45px"><div class="loading"><p style="color:#00aaff;"><b>Processing........Please do not click anywhere on the page until the process is complete.</b></p><img src="'+ajax_url+'img/ajaxloader.gif"></div> </div></div></div>');
        $.ajax({
      url: "<?php echo WWW_BASE; ?>admin/patients/addPatient?rempve_layout=1",
      type: 'POST',
      data: $('#AddPatinentStartTest').serialize(),
      success: function (data) {
         if(data.lastId){
              var test_page_url=window.location.href;
                    var url_array = test_page_url.split("/"); 
                    url_array[6]=data.lastId;
                    var new_url = url_array.join("/"); 
                    window.location.replace(new_url);
          }else{
            const obj = JSON.parse(data)
             if(obj['error']['first_name'][0]){
                 document.getElementById('first_name_error').innerHTML=obj['error']['first_name'][0];
             }
             if(obj['error']['last_name'][0]){
                 document.getElementById('last_name_error').innerHTML=obj['error']['last_name'][0];
             }
             if(obj['error']['mm'][0]){
                 document.getElementById('mm_error').innerHTML=obj['error']['mm'][0];
             }
             if(obj['error']['dd'][0]){
                 document.getElementById('dd_error').innerHTML=obj['error']['dd'][0];
             }
             if(obj['error']['yy'][0]){
                 document.getElementById('yy_error').innerHTML=obj['error']['yy'][0];
             }
           
          }
           //$('.customFacebox').remove();
      }
    });
      
  })
 $('body').on('change','.selectOffice',function(){
   $('#selectOfficeForm').submit();
 });
 $("#patientViewVideo").modal({
            backdrop: "static",
            keyboard: false,
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