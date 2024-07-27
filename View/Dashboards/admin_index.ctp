      <style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #06d315;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>

<style>
/* The container */
.container_new {
 display: flex;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 18px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  color: #ffffff;
  align-items: center;
}

/* Hide the browser's default radio button */
.container_new input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

/* Create a custom radio button */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
  border-radius: 50%;
}

/* On mouse-over, add a grey background color */
.container_new:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the radio button is checked, add a blue background */
.container_new input:checked ~ .checkmark {
  background-color: #06d315;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the indicator (dot/circle) when checked */
.container_new input:checked ~ .checkmark:after {
  display: block;
}

/* Style the indicator (dot/circle) */
.container_new .checkmark:after {
  top: 9px;
  left: 9px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: white;
}
</style>

<?php  
 
$Admin = $this->Session->read('Auth.Admin'); 
$check=(isset($Admin['Office']['session_backup']))?$Admin['Office']['session_backup']:0;
$check2=(isset($Admin['Office']['server_test']))?$Admin['Office']['server_test']:0;
$checkradio=(isset($Admin['Office']['session_backup_type']))?$Admin['Office']['session_backup_type']:'pdf';
 ?>
<div class="content">
      <div class="">
        <div class="page-header-title">
    <?php echo $this->Session->flash();
     $fdt_tests=array("C20-1","C20-5","C30-1","C30-5","C20-1");
   
   ?>
    
          <h4 class="page-title"><?php if ($this->Session->read('Auth.Admin.user_type')=='SuperSubadmin') : ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php else: ?>Dashboard<?php endif; ?></h4>
          <?php if($this->Session->read('Auth.Admin.user_type')=='Subadmin'){ ?>
            
            <div class="form-group  pull-right">
                                           <!--  <label>Auto Backup</label> -->
   <!-- <div style="display: flex;
    justify-content: flex-end;
    margin-bottom: 5px;" >
    <label style="font-size: 18px; color: #ffffff; padding-top: 0px;">Web Controller &nbsp;</label>
    <label class="switch">
    <?php //echo $this->Form->input("server_test", array('onchange'=>'serverTest(this)', 'label' => false, 'div' => false, 'checked'=>$check2, 'type' => 'checkbox', 'class' => 'checkbox')); ?>
        <span class="slider round"></span>
    </label>
  </div> -->
  <div style="display: flex;" >
    <div class="innerrotate" style="
    display: flex;">
     
</div>
</div>
</div>
            
  <?php } ?>
<?php if($Admin['Office']['backup']==1 && $this->Session->read('Auth.Admin.user_type')=='SuperSubadmin' && $this->Session->read('Auth.Admin.user_type')=='SuperSubadmin'){ ?>
      <div class="form-group  pull-right">
	  <style>.page-header-title{padding: 10px 15px 75px 20px;}</style>
      <!--  <label>Auto Backup</label> -->
    <div style="display: flex; justify-content: flex-end; margin-bottom: 5px;" >
    
    <!--input name="checkbox" <?php //echo ($this->Session->read('Auth.Admin.checkautobackup'))?'checked':'' ?>  id="checkautobackup" type="checkbox"> -->
	 <label style="font-size: 18px; color: #ffffff;padding-top: 5px;">Auto Download&nbsp;</label>
    <label class="switch">
    <?php echo $this->Form->input("backup", array('onchange'=>'updateBackup(this)', 'label' => false, 'div' => false, 'checked'=>$check, 'type' => 'checkbox', 'class' => 'checkbox')); ?>
        <span class="slider round"></span>
    </label>
  </div>
  <div style="display: flex;" >
    <div class="innerrotate" style=" display: flex;"> 
<label class="container_new">PDF&nbsp;
  <input type="radio" <?php echo ($checkradio=='pdf')?'checked':'' ?> onchange="updateBackup2(this.value)" name="data[User][rotate]" id="dicome_formate" value="pdf">
  <span class="checkmark"></span>
</label>
<label class="container_new">DICOM&nbsp;
  <input type="radio" name="data[User][rotate]" <?php echo ($checkradio=='dicome')?'checked':'' ?> onchange="updateBackup2(this.value)" id="dicome_formate" value="dicome">
  <span class="checkmark"></span>
</label>
</div>
    </div>
        </div>
    <?php } ?>
        </div>
      </div>
       <script>
        function serverTest(obj){
          var backup=0;
          if($(obj).is(":checked")){
           backup=1;
          }else{
          backup=0;
          }  
          $.ajax({
              url: "<?php echo WWW_BASE; ?>admin/patients/servertest/3554",
              type: 'POST', 
               data: {"backup": backup}, 
              success: function(data){ 
                  
              }
                 
              });
     }
         function updateBackup2(obj) { 
         $.ajax({
      url: "<?php echo WWW_BASE; ?>admin/patients/updatebackupformate/3554",
      type: 'POST', 
       data: {"type": obj}, 
      success: function(data){  
      }
         
      });
       }
    function updateBackup(obj) {
        var backup=0;
  if($(obj).is(":checked")){
   backup=1;
  }else{
  backup=0;
  }  

  var backup_remember='<?php echo $this->Session->read('Auth.Admin.checkautobackup') ?>';
  if($("#checkautobackup").is(":checked")){
    backup_remember=1;
  }else{
    backup_remember=0;
  }


  $.ajax({
      url: "<?php echo WWW_BASE; ?>admin/patients/updatebackup/3554",
      type: 'POST', 
       data: {"backup": backup,'backup_remember':backup}, 
      success: function(data){ 
          get_reportBackup();
      }
         
      });
}
 </script>
      <div class="page-content-wrapper ">
        <div class="container">
          <div class="row staff_block">
            <div class="col-sm-6 col-lg-3">
              <?php //echo $this->Session->read('Auth.Admin.user_type'); ?>
      <?php $Admin = $this->Session->read('Auth.Admin'); if(!empty($Admin) && $Admin['user_type'] == "Admin" && $this->Session->read('Auth.Admin.user_type')!='SuperSubadmin'): ?>
              <div class="panel text-center effect_box">
                <div class="panel-heading">
                  <h4 class="panel-title text-muted font-light">Number of records uploaded today</h4>
                </div>
                <div class="panel-body p-t-10">
                  <h2 class="m-t-0 m-b-15"><img src="<?php echo WWW_BASE.'img/admin/a.png';?>" class="m-r-10" alt="A"/><?php echo @$totalTest;?></h2>
                  <p class="text-muted m-b-0 m-t-20"><a href="<?php echo $this->HTML->url('/admin/unityreports/unity_reports_list?search='.date('Y-m-d')); ?>"><img src="<?php echo WWW_BASE.'img/admin/point.png';?>" alt="Point"/> View Detail <img src="<?php echo WWW_BASE.'img/admin/point.png';?>" alt="Point"/></a></p>
                </div>
        <?php endif; ?>
              </div>
            </div>
             <?php if ($this->Session->read('Auth.Admin.user_type')!='SuperSubadmin' && $this->Session->read('Auth.Admin.user_type')!='RepAdmin'):
                        ?>
            <div class="col-sm-6 col-lg-3">
              <div class="panel text-center effect_box">
                <div class="panel-heading">
                  <h4 class="panel-title text-muted font-light">Patients</h4>
                </div>
                <div class="panel-body p-t-10">
                  <h2 class="m-t-0 m-b-15"><img src="<?php echo WWW_BASE.'img/admin/b.png';?>"  class="m-r-10" alt="B"/><?php echo @$totalPatient;?></h2>
                  <!-- <p class="text-muted m-b-0 m-t-20"><a href="<?php echo $this->HTML->url('/admin/patients/patients_listing'); ?>"><img src="<?php echo WWW_BASE.'img/admin/point.png';?>" alt="Point"/> View Detail <img src="<?php echo WWW_BASE.'img/admin/point.png';?>" alt="Point"/></a></p> -->
                </div>
              </div>
            </div> 
            <?php if(!empty($totalTestYear)){ ?> 
              <div class="col-sm-6 col-lg-3">
                <div class="panel text-center effect_box">
                  <div class="panel-heading">
                    <h4 class="panel-title text-muted font-light"><?php echo date('Y'); ?> Year Total Test</h4>
                  </div>
                  <div class="panel-body p-t-10">
                    <h2 class="m-t-0 m-b-15"><img src="<?php echo WWW_BASE.'img/admin/b.png';?>"  class="m-r-10" alt="B"/><?php echo @$totalTestYear;?></h2>
                  </div>
                </div>
              </div>  
            <?php } endif; ?>
      <?php if(isset($credits)&& (!empty($Admin) && ($Admin['user_type']!= "Subadmin" && $Admin['user_type'] != "Staffuser"))){   ?>
      <div class="col-sm-6 col-lg-3">
              <div class="panel text-center effect_box">
                <div class="panel-heading">
                  <h4 class="panel-title text-muted font-light">Total Credits</h4>
                </div>
                <div class="panel-body p-t-10">
                  <h2 class="m-t-0 m-b-15"><img src="<?php echo WWW_BASE.'img/admin/crd.png';?>"  class="m-r-10" alt="B"/><?php echo $credits;?></h2>
                  <p class="text-muted m-b-0 m-t-20"><a href="<?php echo $this->HTML->url('/admin/payments/purchase_credit'); ?>"><img src="<?php echo WWW_BASE.'img/admin/point.png';?>" alt="Point"/>Purchase Credits <img src="<?php echo WWW_BASE.'img/admin/point.png';?>" alt="Point"/></a></p>
                </div>
              </div>
            </div>
      <?php } ?>
      <?php if(!empty($Admin) && ($Admin['user_type']== "Subadmin" || $Admin['user_type'] == "Staffuser")){   ?>
      <div class="col-sm-6 col-lg-3">
              <div class="panel text-center effect_box">
                <div class="panel-heading">
                  <h4 class="panel-title text-muted font-light">Total Reports</h4>
                </div>
                <div class="panel-body p-t-10">
                  <h2 class="m-t-0 m-b-15"><img src="<?php echo WWW_BASE.'img/admin/b.png';?>"  class="m-r-10" alt="B"/><?php echo $vfReportsCount;?></h2>
                  <!-- <p class="text-muted m-b-0 m-t-20"><a href="<?php echo $this->HTML->url('/admin/unityreports/unity_reports_list'); ?>"><img src="<?php echo WWW_BASE.'img/admin/point.png';?>" alt="Point"/> View Detail <img src="<?php echo WWW_BASE.'img/admin/point.png';?>" alt="Point"/></a></p> -->
                </div>
              </div>
            </div>
      <?php } ?>
      <?php if(isset($avl_credit) && (!empty($Admin) && ($Admin['user_type']!= "Subadmin" && $Admin['user_type'] != "Staffuser"))){ ?>
      <div class="col-sm-6 col-lg-3">
              <div class="panel text-center effect_box">
                <div class="panel-heading">
                  <h4 class="panel-title text-muted font-light">Credits</h4>
                </div>
                <div class="panel-body p-t-10">
                  <h2 class="m-t-0 m-b-15"><img src="<?php echo WWW_BASE.'img/admin/crd.png';?>"  class="m-r-10" alt="B"/><?php echo $avl_credit;?></h2>
                  <p class="text-muted m-b-0 m-t-20"><a href="#" style="color: #42ae79;cursor:default;"><img src="<?php echo WWW_BASE.'img/admin/point.png';?>" alt="Point"/>Total Available Credits<img src="<?php echo WWW_BASE.'img/admin/point.png';?>" alt="Point"/></a></p>
                </div>
              </div>
            </div>
      <?php } ?>
      <?php if(isset($totaloffice)) : ?>
            <div class="col-sm-6 col-lg-3">
              <div class="panel text-center effect_box">
                <div class="panel-heading">
                  <h4 class="panel-title text-muted font-light">Offices</h4>
                </div>
                <div class="panel-body p-t-10">
                  <h2 class="m-t-0 m-b-15"><img src="<?php echo WWW_BASE.'img/admin/c.png';?>" class="m-r-10" alt="C"/><?php echo $totaloffice; ?></h2>
                  <p class="text-muted m-b-0 m-t-20"><a href="<?php echo $this->HTML->url('/admin/offices/manage_office'); ?>"><img src="<?php echo WWW_BASE.'img/admin/point.png';?>" alt="Point"/> View Detail <img src="<?php echo WWW_BASE.'img/admin/point.png';?>" alt="Point"/></a></p>
                </div>
              </div>
            </div>
      <?php endif; ?>
      <?php if(isset($totalTestDevice)) : ?>
            <div class="col-sm-6 col-lg-3">
              <div class="panel text-center effect_box">
                <div class="panel-heading">
                  <h4 class="panel-title text-muted font-light">Test Device</h4>
                </div>
                <div class="panel-body p-t-10">
                  <h2 class="m-t-0 m-b-15"><img src="<?php echo WWW_BASE.'img/admin/d.png';?>" class="m-r-10" alt="D"/><?php echo $totalTestDevice; ?></h2>
                  <p class="text-muted m-b-0 m-t-20"><a href="<?php echo $this->HTML->url('/admin/testdevice/test_device_list'); ?>"><img src="<?php echo WWW_BASE.'img/admin/point.png';?>" alt="Point"/> View Detail <img src="<?php echo WWW_BASE.'img/admin/point.png';?>" alt="Point"/></a></p>
                </div>
              </div>
            </div>
      <?php endif; ?>
          </div>
          
          <div class="row test_block">
            <?php if ($this->Session->read('Auth.Admin.user_type')=='SuperSubadmin') : ?>
     <div class="col-md-12" style="margin-top:5%; padding-left:5%; padding-right:5%;" ><ul style="font-size: 27px; font-weight: 500;">
        <li>To enable automatic download of your office reports, enable the “Auto-Download” toggle and select the format for the report that you want to download (PDF or DICOM).</li>
        <li>Set the download folder location using your Web browser’s Settings->Downloads->Location.</li>
        <li>Have only one instance of the Download Admin account logged into the server at any time.</li>
        <li>Your browser's pop-up blocker may prevent the reports from downloading. Please disable pop-up blocker for this site.</li>
       </ul></div>
    <?php endif; ?>
             <?php if ($this->Session->read('Auth.Admin.user_type')!='SuperSubadmin') : ?>
            <div class="col-md-12">
            <h4 class="m-b-30 m-t-0 rec_con">Recent patient list</h4>
            <div class="rg" style="padding-top: 0px !important"><!-- <img src="<?php echo WWW_BASE.'img/admin/star.png';?>" class="m-r-5" alt="D"/> Add Patient-->
              <?php 
              if($this->Session->read('Auth.Admin.user_type')!='RepAdmin'){ ?>
            <a href="<?php echo $this->HTML->url('/admin/patients/addPatient'); ?>" class="btn btn-large btn-primary" >Add Patient</a>
          <?php } ?>
          </div>
              <div class="panel con_panel">
                <div class="panel-body">
                  
                  <div class="row">
                    <div class="col-xs-12">
                      <div class="table-responsive">
                        <table class="table table-bordered table-hover m-b-0">
                          <thead>
                            <tr>
                              <th>Patient</th>
                              <th>Patient ID</th>
                              <th>D.O.B (DD-MM-YYYY)</th> 
                              <th>Start Test</th> 
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
        if(isset($credit_expire)){  
        echo "<tr><td colspan='4'>";
          if($check_payable['Office']['payable'] =='no' && $check_payable['Office']['restrict'] =='restrict'){?>
            <h2 style="color:red;text-align:center;">You don't have permission to see this. Please contact support: <br/>Email: support@micromedinc.com  <br/>Phone : 818-222-3310</h2> 
          <?php }else{
            if($Admin['user_type'] == "Staffuser"){
              ?>
              <h2 style="color:red;text-align:center;">Your Credit has expired. Please contact to your office.</h2> 
              <?php
            }else{?>
              <h2 style="color:red;text-align:center;">You are out of credit please contact Micro Medical office<br/>Email: support@micromedinc.com  <br/>Phone : 818-222-3310</h2> 
              
              <?php } 
          }?>
        <?php  echo "</tr></td>"; }else{
          if(@$check_payable['Office']['payable'] =='no' && @$check_payable['Office']['restrict'] =='restrict'){
          ?>
         <tr><td colspan='4'>
          <h2 style="color:red;text-align:center;">You don't have permission to see this. Please contact support: <br/>Email: support@micromedinc.com  <br/>Phone : 818-222-3310</h2>
        </tr></td>
        <?php
        }else{
            if(!empty($datas)) {foreach($datas as $key=>$data){
              $test_page_name=""; 
              $checked_data=array();
                               if (isset($data['Office']['Officereport'])) {
                    $checked_data = Hash::extract($data['Office']['Officereport'], '{n}.office_report'); 
                              }
                
               if(isset($data['DarkAdaption'][0]['id'])){
                 $da_data=strtotime($data['DarkAdaption'][0]['test_date_time']);
               }else{
                $da_data=0;
               }
               if(isset($data['Pointdata'][0]['id'])){
                 $point_data=strtotime($data['Pointdata'][0]['created']);
               }else{
                $point_data=0;
               } 

               if($da_data>$point_data){
                $test_page_name='start_test_da'; //da link
               }else if($point_data>0){
                //check test
                if(in_array($data['Pointdata'][0]['test_name'], $fdt_tests)){
                  $test_page_name='start_test_fdt'; //fdt link
                }else if($data['Pointdata'][0]['test_name']="Vision Screening" && in_array(25, $checked_data)){
                  $test_page_name='start_test_vs'; //vs link
                }else{
                  $test_page_name='start_test';// vf link
                }
               }else{
                if (in_array(14, $checked_data)){
                  $test_page_name='start_test';//vf
                }else if (in_array(15, $checked_data)){
                  $test_page_name='start_test_fdt';// fdt
                }else if (in_array(23, $checked_data)){
                  $test_page_name='start_test_da';///da
                }else if (in_array(25, $checked_data)){
                  $test_page_name='start_test_vs';///vs
                }
               }
               ?>
                <tr>
                  <td><?php echo $data['Patient']['first_name']." ".$data['Patient']['middle_name']." ".$data['Patient']['last_name'];?></td>
                  <td><?php print_r($data['Patient']['id_number']) ?> </td>
                  <td><?php echo (!empty($data['Patient']['dob']))?date('d-m-Y', strtotime($data['Patient']['dob'])):''; ?></td>
                  <td><?php if($test_page_name!=""){
                    echo "<a style='cursor: pointer;height: 26px;padding-top: 3px;padding-bottom: 3px;' class='btn btn-info' href='".WWW_BASE."admin/patients/".$test_page_name."/".$data['Patient']['id']."' title='Start Test' >Start Test</a>"; }
                    ?></td>
                </tr>


         <?php   }
          }else{
            echo"<tr><td colspan='4' style='text-align:center;'>No record found.</td></tr>";
          }
        } 
        }?>
               
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
           <!--  <div class="col-md-8">
            <h4 class="m-b-30 m-t-0 rec_con">Test Report</h4>
            <div class="rg"><img src="<?php echo WWW_BASE.'img/admin/star.png';?>" class="m-r-5" alt="D"/>Manage Report</div>
              <div class="panel con_panel">
                <div class="panel-body">
                  
                  <div class="row">
                    <div class="col-xs-12">
                      <div class="table-responsive">
                        <table class="table table-bordered table-hover m-b-0">
                          <thead>
                            <tr>
                              <th>Date</th>
                              <th>Test Name</th> 
                              <th>Staff User</th>
                              <th>Patient Name</th>
                              <th>Results</th>
                            </tr>
                          </thead>
                          <tbody>
              <?php if(isset($report_datas) && !empty($report_datas)) : foreach($report_datas as $data): ?>
                            <tr>
                              <td><?php  echo date('d F Y',strtotime($data['Testreport']['created']));?></td>
                              <td><?php echo $data['Test']['name']; ?></td> 
                              <td><?php echo $data['User']['first_name'].' '.$data['User']['last_name'];?></td>
                              <td><?php echo $data['Patient']['first_name'].' '.$data['Patient']['last_name'];?></td>
                              <td><?php echo substr($data['Testreport']['result'],0,20); ?></td>
                            </tr>
              <?php endforeach; endif; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <h4 class="m-b-30 m-t-0 rec_con">Notification</h4>
              <div class="rg"><a href="<?php echo $this->HTML->url('/admin/notifications/notification_list'); ?>"><img src="<?php echo WWW_BASE.'img/admin/eye.png';?>" class="m-r-5" alt="D"/>View All</a></div>
              <div class="panel con_panel">
        <?php if(!empty($Notifications)) {
          foreach($Notifications as $notification) :
        
        ?>
              <div class="notify">
        
                <div class="panel-body">
                  <div class="left">
                      <img src="<?php echo WWW_BASE.'img/admin/one.jpg';?>" class="m-r-5" alt="D"/>
                        <span class="blue"></span>
                    </div>
          <?php 
          if(!empty($notification['User']['middle_name'])) {
            $name = $notification['User']['first_name']." ".$notification['User']['middle_name']." ".$notification['User']['last_name'];
          }else {
            $name = $notification['User']['first_name']." ".$notification['User']['last_name'];
          } ?>
                    <div class="right">
                      <span class="cl"><?php echo ucfirst($name); ?></span>
                        <span class="cll"><?php echo date('d F Y H:i',strtotime($notification['UserNotification']['created']));?></span>
                        <span class="clll"><?php echo substr($notification['UserNotification']['text'],0,50); ?></span>
                    </div>
                </div>
              </div> 
        <?php endforeach; } else { ?>
           <div class="panel-body">
          <div class="left">
                        <span class="blue"></span>
            <div class="right">
              No Notification Found.
            </div>
                    </div>
        </div>
         <?php }  ?>
              </div>
            </div> -->
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    