
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <?php $user=$this->Session->read('Auth.Admin'); ?>
  
 <div class="content">
  <div class=""> 
    <?php $checked_data=array();
                               if (isset($data['Office']['Officereport'])) {
                    $checked_data = Hash::extract($data['Office']['Officereport'], '{n}.office_report'); 
                              }
                ?>
    <div class="page-header-title">
      <div class="row">
          <div class="col-sm-3 mts-box-1">
             <h3 class="page-title2" style="color: #ffffff; margin-top:6px;">Manage Patients<span class="pull-right" style="color:white"></span></h3>
          </div>
          
          <div class="col-sm-2 mts-box-1">
             <h4 class="page-title2" style="color: #ffffff;">Duration: <span id="setting-duration" class="" style="color: #ffffff;"></span></h4>
          </div>
          <div class="col-sm-2 mts-box-1">
             <h4 class="page-title2" style="color: #ffffff;">Name: <?php echo $data['Patient']['first_name']." ".$data['Patient']['last_name'] ?></h4>
          </div>
          <div class="col-sm-2 mts-box-1">
           <?php if (in_array(14, $checked_data)) { echo "<a style='width: 57px; background: #7e7e7e; color: white; border: 2px solid white;  margin-top: 3px;'   href='".WWW_BASE."admin/patients/start_test/".$data['Patient']['id']."'  class='btn' title='Start VF Test' >VF</a>"; }?>
            <?php if (in_array(15, $checked_data)) { echo "<a style='width: 57px; background: #7e7e7e; color: white; border: 2px solid white;  margin-top: 3px;' class='btn'   href='".WWW_BASE."admin/patients/start_test_fdt/".$data['Patient']['id']."' title='Start FDT Test' >FDT</a>"; }?>
                 <?php if (in_array(23, $checked_data)) { echo "<a style='width: 57px; background: #7e7e7e; color: white; border: 2px solid white;  margin-top: 3px;' href='".WWW_BASE."admin/patients/start_test_da/".$data['Patient']['id']."' title='Start DA Test' class='btn' >DA</a>"; }?>
               <?php if (in_array(25, $checked_data)) { echo "<a style='width: 57px; background: #3292e0; color: white; border: 2px solid #f3ecec; margin-top: 3px;' href='".WWW_BASE."admin/patients/start_test_vs/".$data['Patient']['id']."' title='Start VS Test' class='btn' >VS</a>"; }?>
                <?php if (in_array(34, $checked_data)) { echo "<a style='width: 57px; background: #7e7e7e; color: white; border: 2px solid white;  margin-top: 3px;' href='".WWW_BASE."admin/patients/start_test_pup/".$data['Patient']['id']."' title='Start Pupilometer Test' class='btn ' >PUP</a>"; }?>
               </div>
          <div class="col-sm-3 mts-box-1">
             <h4 class="page-title"> <span class="pull-right" style="color:white"></span></h4>
          </div>
      </div>
      
      
      
    </div>
<?php
/*$host    = "160.153.72.199";
$port    = 25003;
$message = "Hello Server";
echo "Message To server :".$message;
// create socket
$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
// connect to server
$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");  
// send string to server
socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");
// get server response
$result = socket_read ($socket, 1024) or die("Could not read server response\n");
echo "Reply From Server  :".$result;
// close socket
socket_close($socket);*/
?>
  </div>
  <div class="page-content-wrapper ">
   <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-primary" style="margin:0px;">
          <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css">

          <!-- 6 may 2020 -->
          <?php echo $this->Html->css(array('admin/mmd-custom.css?v=13'));?>
          <div class="panel-body mmd-st">
            <div class="mmds-box">
              <div class="container">
                <div class="row">
                  <div class="col-sm-6 mts-box-1">
                    <div class="stm-box">

                   <!--  <h4><?php echo $data['Patient']['first_name']." ".$data['Patient']['last_name'] ?></h4> -->
                    <h3>Status: <span id="setting-status"></span></h3>
                    <h3>Questions : <span id="setting-questions"></span></h3> 
                    <!-- <h3>Reliability: <span id="setting-reliability"></span></h3> -->
                    <button id="setting-reliability-btn" class="btn" style="background-color: #00ff00 "  
 ><span id="setting-reliability">Reliability</span></button><br>
                    <h3>Stm Size: <span id="setting-stm-size">3</span></h3>
                    <h3>Bkg Color: <span id="setting-bkg-color">0.7</span>db</h3>
                    <h3>Speed: <span id="setting-speed">0.7</span>deg/sec</h3><br>
                    <h3>Fixation Loss: <span id="fixation-losses"></span></h3>
                    <h3>False Positive: <span id="false-positive"></span></h3>
                    <h3>False Negative: <span id="false-nigative"></span></h3><br>
                    
                   <!--  <div  id="battery" style="
    background: linear-gradient(to right, #00ff00 0%, #00ff00 67%, #d6d6d6 30%, #d6d6d6 100%);
    display: inline-block;
    height: 30px; visibility: hidden;
    transform: rotateZ(270deg);
"><i class="fa fa-battery-0" id="fa-battery" style="font-size:48px;color:#000000;margin-top: -9px;"></i></div> -->
<div id="feedback-box"></div>
                  </div>
                </div>
                 <div class="col-sm-6">
                  <div class="stm-nbtn-box">
                    <div href="#" class="thrushold">
                      <div class="dropdown">
                         <?php $count= count($test_device); ?>
                         <select class="mmd-dash-btn" id="test-device" onChange="checkStatus();" style="height: 30px;">

                          <option value="">Select Device</option> 
                           <?php foreach ($test_device as $key => $value) { ?>
                            <option value="<?php echo $value['TestDevice']['id'] ?>" <?php echo ($count==1)?'selected':'' ?> <?php echo (!empty($user_default))?($user_default['UserDefault']['device_id'] == $value['TestDevice']['id'])?'selected':'':'' ?> ><?php echo $value['TestDevice']['name'] ?></option>  
                            <?php } ?>
                         </select> 
                          <?php foreach ($test_device as $key => $value) { 
                         if($count==1 || $user_default['UserDefault']['device_id'] == $value['TestDevice']['id']){  ?>
                              <script type="text/javascript">
                                device_type='<?php echo $value['TestDevice']['id'] ?>';
                              </script>
                          <?php  }
                        } ?>
                      </div>
                    </div> 
                  </div>
                  <div class="stm-nbtn-box">
                    <div href="#" class="thrushold">
                      <div class="dropdown"> 
                         <select class="mmd-dash-btn" id="language"  onChange="updateDevice();" style="height: 30px;">

                            <option value="0">Select Language</option> 
                            <option value="1" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 1)?'selected':'':'' ?>>English</option>  
                            <option value="2" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 2)?'selected':'':'' ?>>Spanish</option>
                            <option value="3" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 3)?'selected':'':'' ?>>French</option>
                            <option value="4" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 4)?'selected':'':'' ?>>Portuguese</option>
                            <option value="5" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 5)?'selected':'':'' ?>>Arabic</option>
                            <option value="6" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 6)?'selected':'':'' ?>>Hindi</option> 
                            <option value="7" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 7)?'selected':'':'' ?>>Chinese-Mandarin</option> 
                            <option value="8" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 8)?'selected':'':'' ?>>Vietnamese</option> 
                             <option value="9" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 9)?'selected':'':'' ?>>Chinese-Cantonese</option>
                          <!--   <option value="8" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 8)?'selected':'':'' ?>>No Voice Instructions</option> -->
                         </select> 

                      </div>
                    </div> 
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="stm-nbtn-box"> 
                    
                  </div>
                </div>
              </div>


              <div class="row dash-statc">

                  <div class="col-lg-2 col-sm-2 d-desktop">
                      <div class="mmt-canvas-control">
                        <h3>User Preset</h3>
                        <button class="dash-control" value="PresetF"><?php echo (!empty($user_preset))?$user_preset['UserPreset']['presetF']:'PresetF' ?></button>
                        <button class="dash-control" value="PresetE"><?php echo (!empty($user_preset))?$user_preset['UserPreset']['presetE']:'PresetE' ?></button>
                        <button class="dash-control" value="PresetD"><?php echo (!empty($user_preset))?$user_preset['UserPreset']['presetD']:'PresetD' ?></button>
                        <button class="dash-control" value="PresetC"><?php echo (!empty($user_preset))?$user_preset['UserPreset']['presetC']:'PresetC' ?></button>
                        <button class="dash-control" value="PresetB"><?php echo (!empty($user_preset))?$user_preset['UserPreset']['presetB']:'PresetB' ?></button>
                        <button class="dash-control" value="PresetA"><?php echo (!empty($user_preset))?$user_preset['UserPreset']['presetA']:'PresetA' ?></button>
                        <span>* Double click to save preset</span>
                      </div>
                      
                  </div>

                  <div class="col-lg-3 col-sm-2 d-phone">
                      

                  </div>

                  <div class="col-lg-10 col-sm-10">
                      <div class="mmt-main-canvas" style="position: relative; height: auto;">
                        <div class="border-div w-100" style="">
                        <span style="font-size: 18px;color: #990000;font-weight: 600; text-align: center;" class="setting-alert-message"></span><br>
                        <span style="font-size: 18px;color: #990000;font-weight: 600; text-align: center;" class="setting-alert-message2"></span>
                      </div>
                          <div class="mt-btns gz-trck d-desktop">
                              <div class="mt-checkboxes mt-checkboxes-eye">

                              <div class="form-group"  id="voice_instructions_div">
                                  <input type="checkbox"  id="voice_instructions" class="voice_instructions" aria-label="..." onchange="voiceinstructions(this)">
                                  <label for="voice_instructions"><span type="checkbox" style="font-weight: 500;" id="voice_instructions_txt">Voice Instructions</span></label>
                              </div>
                               
                              <div class="form-group">
                                  <input type="checkbox" id="alarm-stop"   class="alarm-stop" onchange="alarmstop(this)" aria-label="...">
                                  <label for="alarm-stop"><span type="checkbox"  style="font-weight: 500;">Alarm on Stop</span></label>
                              </div>
                              </div>
                              <button class="mmd-dash-btn md-btn-gry stBtn"  data-toggle="modal" data-target="#exampleModalCenter">settings</button>
                              
                              
                              <button class="mmd-dash-btn md-btn-gry dismiss-alarm" style="visibility: hidden; background-color: #d63636 !important;"   onclick="stopalarm();" >Dismiss Alarm</button>
                          </div>
                          
                          <!-- middle options -->
                          <div class="middle-options">
                            <div class="row">

                              <div class="col-sm-6"></div>
                              <div class="col-sm-3">
                                  <label class="btn btn-primary">OS</label>
                              </div>
                              <div class="col-sm-3">
                                <label class="btn btn-primary">OD</label>
                            </div>
                           </div>
                            <!-- device list -->
                            <div class="device-list">
                              <!-- device item -->
                              <!--   <?php if (in_array(28, $checked_data)) { ?> 
                              <div class="row">
                                <div class="col-sm-4">
                                  <p>Visual Acuity</p>
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox"  onchange="changetest(this,'va_os')">
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox" onchange="changetest(this,'va_od')">
                                </div>
                              </div>
                               <?php }?> 
                               <?php if (in_array(30, $checked_data)) { ?>  
                              <div class="row">
                                <div class="col-sm-4">
                                  <p>Color Test</p>
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox" onchange="changetest(this,'color_os')">
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox" onchange="changetest(this,'color_od')">
                                </div>
                              </div> 
                            <?php } ?>
                             <?php if (in_array(29, $checked_data)) { ?>  
                              <div class="row">
                                <div class="col-sm-4">
                                  <p>Stereopsis</p>
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox" class="stereopsis" onchange="changetest(this,'stereopsis')">
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox" class="stereopsis" onchange="changetest(this,'stereopsis')">
                                </div>
                              </div>
                              <?php } ?>
                             <?php if (in_array(15, $checked_data)) { ?> 
                              <div class="row">
                                <div class="col-sm-4">
                                  <p>FDT</p>
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox"  onchange="changetest(this,'fdt_os')">
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox"  onchange="changetest(this,'fdt_od')">
                                </div>
                              </div> 
                              <?php } ?>
                               <?php if (in_array(23, $checked_data)) { ?>  
                              <div class="row">
                                <div class="col-sm-4">
                                  <p>Dark Adaptation</p>
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox"  onchange="changetest(this,'da_os')">
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox"  onchange="changetest(this,'da_od')">
                                </div>
                              </div>
                            <?php } ?>
                             <?php if (in_array(22, $checked_data)) { ?> 
                              <div class="row">
                                <div class="col-sm-4">
                                  <p>Refractor Test</p>
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox"  onchange="changetest(this,'ref_os')">
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox"  onchange="changetest(this,'ref_od')">
                                </div>
                              </div>
                            <?php } ?>
                               <?php if (in_array(18, $checked_data)) { ?> 
                              <div class="row">
                                <div class="col-sm-4">
                                  <p>Contrast Sensitivity</p>
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox"  onchange="changetest(this,'cont_os')">
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox"  onchange="changetest(this,'cont_od')">
                                </div>
                              </div>
                            <?php } ?>
                             <?php if (in_array(14, $checked_data)) { ?> 
                              <div class="row">
                                <div class="col-sm-4">
                                  <p>Visual Fields Test</p>
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox"  onchange="changetest(this,'vf_os')">
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox"  onchange="changetest(this,'vf_od')">
                                </div>
                              </div>
                            <?php } ?> -->
                                 
                              <div class="row">
                                <div class="col-sm-3">
                                  <p style="<?php echo (in_array(25, $checked_data))?'':'background-color: #c6a9a9' ?>">Visual Acuity</p>
                                </div>
                                <div class="col-sm-3 " >
                                   
                                <!--   <select class="mmd-dash-btn m-0" id="vas" style="visibility: hidden;  display:none; height:26px;" onchange="changeVA(this)"> -->
                                    <select class="mmd-dash-btn m-0 w-100" id="vas" onchange="changeVA(this)" style="height: 26px;">
                                    
                                    <option value="Standard">Standard</option>
                                   <!--  <option value="Cs" selected >Cs</option>
                                    <option value="HOTV">HOTV</option>
                                    <option value="LEA">LEA</option> -->
                                    
                                  </select>
                                  
                                </div>
                                <div class="col-sm-3 coustom_checkbox">
                                     
                                      
                                 
                               <!--    <label for="With_Glare"> 
                                  <input type="checkbox" id="With_Glare" style="margin:0; display:inline-block;"  class="vaoc"  onchange="with_glare(this)" aria-label="...">
                                  <span type="checkbox" style="font-weight: 500; vertical-align: top; line-height: 25px;">With Glare</span></label>  -->
                                   <!--  <label class="container-checkbox"><span class="count-message" id="VA_OS_count"></span>
                                      <input type="checkbox"  <?php echo (in_array(28, $checked_data))?'':'disabled' ?> class="vs_check" onchange="changetest(this,'va_os')"  >
                                      <span class="checkmark" id="VA_OS"></span>
                                    </label>
                                    <span class="status-message" id="VA_OS_status"></span>  -->
                                    <label class="container-checkbox"><span class="count-message" id="VA_OS_count"></span> 
                                    </label>
                                    <span class="status-message" id="VA_OS_status"></span>
                                </div>
                                <div class="col-sm-3 coustom_checkbox">
                                    <!--  <label class="container-checkbox"><span class="count-message" id="VA_OD_count"></span>
                                      <input type="checkbox"  <?php echo (in_array(28, $checked_data))?'':'disabled' ?> class="vs_check" onchange="changetest(this,'va_od')"  >
                                      <span class="checkmark" id="VA_OD"></span>
                                    </label>
                                    <span class="status-message" id="VA_OD_status"></span>  -->
                                    <label class="container-checkbox"><span class="count-message" id="VA_OD_count"></span> 
                                    </label>
                                    <span class="status-message" id="VA_OD_status"></span>
                                </div>
                                
                              </div> 
                             <!--  <div class="row text-center" id="vao" style="visibility: hidden; display:none;"> -->
                              <div class="row ">
                                 <div class="col-sm-6">

                                    <label class="container-checkbox" for="Distant_without_correction"> 
                                  <input type="checkbox"  id="Distant_without_correction"  onchange="Distant_without_correction(this)"  >
                                  <span class="checkmark"  style="border-radius: 50%;"></span>
                                       <span type="checkbox"  style="font-weight: 500; vertical-align: top; line-height: 25px; font-size: 14px;">Distance without correction</span>
                                    </label>

                                 <!--  <label for="Distant_without_correction">
                                      <input type="radio" id="Distant_without_correction" style="margin:0; display:inline-block;" class="vaoc"  onchange="Distant_without_correction(this)" aria-label="...">
                                      <span type="checkbox"  style="font-weight: 500; vertical-align: top; line-height: 25px;">Distant without correction</span></label>  -->
                                </div>
                                <div class="col-sm-3 va_dwtc_div coustom_checkbox" id="va_dwtc_os_div" style="visibility: hidden;">
                                   <label class="container-checkbox">
                                    <span class="count-message" id="VA_DWTC_OS_count"></span>
                                  <input type="checkbox"  class="va_dwtc_check" id="va_dwtc_os_check" onchange="changetest(this,'va_dwtc_os')"  >
                                      <span class="checkmark" id="VA_DWTC_OS"></span>
                                    </label>
                                     <span class="status-message" id="VA_DWTC_OS_status"></span>

                                </div>
                                <div class="col-sm-3 va_dwtc_div coustom_checkbox" id="va_dwtc_od_div" style="visibility: hidden;">
                                   <label class="container-checkbox">
                                    <span class="count-message" id="VA_DWTC_OD_count"></span>
                                  <input type="checkbox"  class="va_dwtc_check" id="va_dwtc_od_check" onchange="changetest(this,'va_dwtc_od')"  >
                                      <span class="checkmark" id="VA_DWTC_OD"></span>
                                    </label>
                                     <span class="status-message" id="VA_DWTC_OD_status"></span>

                                </div>
                              </div>

                               <div class="row ">
                                 <div class="col-sm-6">

                                    <label class="container-checkbox" for="Distant_without_correction_glare"> 
                                  <input type="checkbox"  id="Distant_without_correction_glare"  onchange="Distant_without_correction_glare(this)"  >
                                  <span class="checkmark"  style="border-radius: 50%;"></span>
                                       <span type="checkbox"  style="font-weight: 500; vertical-align: top; line-height: 25px; font-size: 14px;">Distance without correction  with glare</span>
                                    </label>

                                 <!--  <label for="Distant_without_correction">
                                      <input type="radio" id="Distant_without_correction" style="margin:0; display:inline-block;" class="vaoc"  onchange="Distant_without_correction(this)" aria-label="...">
                                      <span type="checkbox"  style="font-weight: 500; vertical-align: top; line-height: 25px;">Distant without correction</span></label>  -->
                                </div>
                                <div class="col-sm-3 va_dwtc_glare_div coustom_checkbox" id="va_dwtc_os_div" style="visibility: hidden;">
                                   <label class="container-checkbox">
                                    <span class="count-message" id="VA_DWTC__GLARE_OS_count"></span>
                                  <input type="checkbox"  class="va_dwtc_glare_check" id="va_dwtc_glare_os_check" onchange="changetest(this,'va_dwtc_glare_os')"  >
                                      <span class="checkmark" id="VA_DWTC_GLARE_OS"></span>
                                    </label>
                                     <span class="status-message" id="VA_DWTC_GLARE_OS_status"></span>

                                </div>
                                <div class="col-sm-3 va_dwtc_glare_div coustom_checkbox" id="va_dwtc_glare_od_div" style="visibility: hidden;">
                                   <label class="container-checkbox">
                                    <span class="count-message" id="VA_DWTC_GLARE_OD_count"></span>
                                  <input type="checkbox" id="va_dwtc_glare_od_check" class="va_dwtc_glare_check" onchange="changetest(this,'va_dwtc_glare_od')"  >
                                      <span class="checkmark" id="VA_DWTC_GLARE_OD"></span>
                                    </label>
                                     <span class="status-message" id="VA_DWTC_GLARE_OD_status"></span>

                                </div>
                              </div>
                              
                              <div class="row ">
                                <div class="col-sm-6">

                                   <label class="container-checkbox" for="Distant_with_correction"> 
                                  <input type="checkbox"  id="Distant_with_correction"   onchange="Distant_with_correction(this)"  >
                                  <span class="checkmark" style="border-radius: 50%;"></span>
                                       <span type="checkbox"  style="font-weight: 500; vertical-align: top; line-height: 25px; font-size: 14px;">Distance with correction</span>
                                    </label>

                                     
                                <!--   <label for="Distant_with_correction">
                                       <input type="radio" id="Distant_with_correction" style="margin:0; display:inline-block;" class="vaoc"  onchange="Distant_with_correction(this)" aria-label="...">
                                       <span type="checkbox"   style="font-weight: 500; vertical-align: top; line-height: 25px;">Distant with correction</span></label>  -->
                                  </div>
                               <div class="col-sm-3 va_dwc_div coustom_checkbox" id="va_dwc_os_div" style="visibility: hidden;">
                                   <label class="container-checkbox">
                                    <span class="count-message" id="VA_DWC_OS_count"></span>
                                  <input type="checkbox" id="va_dwc_os_check" class="va_dwc_check" onchange="changetest(this,'va_dwc_os')"  >
                                      <span class="checkmark" id="VA_DWC_OS"></span>
                                    </label>
                                     <span class="status-message" id="VA_DWC_OS_status"></span>

                                </div>
                                <div class="col-sm-3 va_dwc_div coustom_checkbox" id="va_dwc_od_div" style="visibility: hidden;">
                                   <label class="container-checkbox">
                                    <span class="count-message" id="VA_DWC_OD_count"></span>
                                  <input type="checkbox" id="va_dwc_od_check" class="va_dwc_check" onchange="changetest(this,'va_dwc_od')"  >
                                      <span class="checkmark" id="VA_DWC_OD"></span>
                                    </label>
                                     <span class="status-message" id="VA_DWC_OD_status"></span>

                                </div>
                                 </div>
                                  <div class="row ">
                                <div class="col-sm-6">

                                   <label class="container-checkbox" for="Distant_with_correction_glare"> 
                                  <input type="checkbox"  id="Distant_with_correction_glare"   onchange="Distant_with_correction_glare(this)"  >
                                  <span class="checkmark" style="border-radius: 50%;"></span>
                                       <span type="checkbox"  style="font-weight: 500; vertical-align: top; line-height: 25px; font-size: 14px;">Distance with correction with glare</span>
                                    </label>
 
                                  </div>
                               <div class="col-sm-3 va_dwc_glare_div coustom_checkbox" id="va_dwc_glare_os_div" style="visibility: hidden;">
                                   <label class="container-checkbox">
                                    <span class="count-message" id="VA_DWC_GLARE_OS_count"></span>
                                  <input type="checkbox" id="va_dwc_glare_os_check"  class="va_dwc_glare_check" onchange="changetest(this,'va_dwc_glare_os')"  >
                                      <span class="checkmark" id="VA_DWC_GLARE_OS"></span>
                                    </label>
                                     <span class="status-message" id="VA_DWC_GLARE_OS_status"></span>

                                </div>
                                <div class="col-sm-3 va_dwc_glare_div coustom_checkbox" id="va_dwc_glare_od_div" style="visibility: hidden;">
                                   <label class="container-checkbox">
                                    <span class="count-message" id="VA_DWC_GLARE_OD_count"></span>
                                  <input type="checkbox" id="va_dwc_glare_od_check" class="va_dwc_glare_check" onchange="changetest(this,'va_dwc_glare_od')"  >
                                      <span class="checkmark" id="VA_DWC_GLARE_OD"></span>
                                    </label>
                                     <span class="status-message" id="VA_DWC_GLARE_OD_status"></span>

                                </div>
                                 </div>
                                 <div class="row">
                                 <div class="col-sm-6"> 
                                  <label for="Distant_without_correction">
                                    
                                     <label class="container-checkbox" for="Closeup_with_correction"> 
                                  <input type="checkbox"  id="Closeup_with_correction" onchange="closeup_with_correction(this)"  >
                                  <span class="checkmark"  style="border-radius: 50%;"></span>
                                       <span type="checkbox"  style="font-weight: 500; vertical-align: top; line-height: 25px; font-size: 14px;">Close up with correction</span>
                                    </label>


                                <!--   <label for="Closeup_with_correction">
                                       <input type="radio" id="Closeup_with_correction" style="margin:0; display:inline-block;" class="vaoc"  onchange="closeup_with_correction(this)" aria-label="...">
                                       <span type="checkbox"  style="font-weight: 500; vertical-align: top; line-height: 25px;">Closeup with correction</span></label> -->
                                </div>
                                 <div class="col-sm-3 va_cwc_div coustom_checkbox" id="va_cwc_os_div" style="visibility: hidden;">
                                   <label class="container-checkbox">
                                    <span class="count-message" id="VA_CWC_OS_count"></span>
                                  <input type="checkbox" id="va_cwc_os_check" class="va_cwc_check" onchange="changetest(this,'va_cwc_os')"  >
                                      <span class="checkmark" id="VA_CWC_OS"></span>
                                    </label>
                                     <span class="status-message" id="VA_CWC_OS_status"></span>

                                </div>
                                <div class="col-sm-3 va_cwc_div coustom_checkbox" id="va_cwc_od_div" style="visibility: hidden;">
                                   <label class="container-checkbox">
                                    <span class="count-message" id="VA_CWC_OD_count"></span>
                                  <input type="checkbox" id="va_cwc_od_check" class="va_cwc_check" onchange="changetest(this,'va_cwc_od')"  >
                                      <span class="checkmark" id="VA_CWC_OD"></span>
                                    </label>
                                     <span class="status-message" id="VA_CWC_OD_status"></span>

                                </div>
                              </div>
                                 <div class="row text-center">
                                <div class="col-sm-6">
                                  
                                  </div>
                                  <div class="col-sm-3"></div>
                                <div class="col-sm-3"></div>
                                 </div><div class="row text-center">
                                <div class="col-sm-3">
                                 
                                </div>  
                              </div> 
                              <div class="row">
                                <div class="col-sm-3">
                                  <p style="<?php echo (in_array(26, $checked_data))?'':'background-color: #c6a9a9' ?>" onclick="checkbotheye('color');">Color Test</p>
                                </div>
                                <div class="col-sm-3">
                                  
                                </div>
                                <div class="col-sm-3 coustom_checkbox">
                                   <label class="container-checkbox">
                                    <span class="count-message" id="COLOR_OS_count"></span>
                                  <input type="checkbox" id="color_os_check"  <?php echo (in_array(26, $checked_data))?'':'disabled' ?>  class="color_check" onchange="changetest(this,'color_os')"  >
                                      <span class="checkmark" id="COLOR_OS"></span>
                                    </label>
                                     <span class="status-message" id="COLOR_OS_status"></span>
                                <!--   <input type="checkbox" <?php echo (in_array(30, $checked_data))?'':'disabled' ?> class="color_check" onchange="changetest(this,'color_os')"> -->
                                </div>
                                <div class="col-sm-3 coustom_checkbox">
                                   <label class="container-checkbox">
                                    <span class="count-message" id="COLOR_OD_count"></span>
                                  <input type="checkbox" id="color_od_check"  <?php echo (in_array(26, $checked_data))?'':'disabled' ?> class="color_check" onchange="changetest(this,'color_od')"  >
                                      <span class="checkmark" id="COLOR_OD"></span>
                                    </label>
                                     <span class="status-message" id="COLOR_OD_status"></span>
                                  <!-- <input type="checkbox" <?php echo (in_array(30, $checked_data))?'':'disabled' ?> class="color_check" onchange="changetest(this,'color_od')"> -->
                                </div>
                              </div>   
                              <div class="row">
                                <div class="col-sm-3">
                                  <p style="<?php echo (in_array(27, $checked_data))?'':'background-color: #c6a9a9' ?>" onclick="checkbotheye('stereopsis');">Stereopsis</p>
                                </div>
                                  <div class="col-sm-3">
                                  
                                </div>
                                <div class="col-sm-3 coustom_checkbox">
                                   <label class="container-checkbox">
                                     <span class="count-message" id="STEREOPSIS_OS_count"></span>
                                  <input type="checkbox"  <?php echo (in_array(27, $checked_data))?'':'disabled' ?> class="stereopsis" onchange="changetest(this,'stereopsis')"  >
                                      <span class="checkmark" id="STEREOPSIS_OS"></span>
                                    </label>
                                     <span class="status-message" id="STEREOPSIS_OS_status"></span>
                                  <!-- <input type="checkbox" <?php echo (in_array(29, $checked_data))?'':'disabled' ?> class="stereopsis" onchange="changetest(this,'stereopsis')"> -->
                                </div>
                                <div class="col-sm-3 coustom_checkbox">
                                   <label class="container-checkbox">
                                     <span class="count-message" id="STEREOPSIS_OD_count"></span>
                                   <input type="checkbox"  <?php echo (in_array(27, $checked_data))?'':'disabled' ?> class="stereopsis" onchange="changetest(this,'stereopsis')"  >
                                      <span class="checkmark" id="STEREOPSIS_OD"></span>
                                    </label>
                                     <span class="status-message" id="STEREOPSIS_OD_status"></span>
                                  <!-- <input type="checkbox" <?php echo (in_array(29, $checked_data))?'':'disabled' ?> class="stereopsis" onchange="changetest(this,'stereopsis')"> -->
                                </div>
                              </div>  
                               <div class="row">
                                <div class="col-sm-3">
                                  <p style="<?php echo (in_array(18, $checked_data))?'':'background-color: #c6a9a9' ?>" onclick="checkbotheye('cs');">Contrast Sensitivity</p>
                                </div>
                                <div class="col-sm-3"> 
                                   <select class="mmd-dash-btn m-0" id="cs" style="visibility: hidden; height:26px;" onchange="changeCS(this)">
                                    <option value="Red">Red</option>
                                    <option value="Green">Green</option>
                                    <option value="Blue">Blue</option>
                                    <option value="Gray">Gray</option>
                                   	<option value="R+G+B">R+G+B</option>
                                  </select> 
                                </div>
                                <div class="col-sm-3 coustom_checkbox">
                                   <label class="container-checkbox">
                                    <span class="count-message" id="CS_OS_count"></span>
                                  <input type="checkbox" id="cs_os_check"  <?php echo (in_array(18, $checked_data))?'':'disabled' ?>  class="cs_check" onchange="changetest(this,'cs_os')"  >
                                      <span class="checkmark" id="CS_OS"></span>
                                    </label>
                                     <span class="status-message" id="CS_OS_status"></span>
                                </div>
                                <div class="col-sm-3 coustom_checkbox">
                                   <label class="container-checkbox">
                                    <span class="count-message" id="CS_OD_count"></span>
                                  <input type="checkbox" id="cs_od_check"  <?php echo (in_array(18, $checked_data))?'':'disabled' ?> class="cs_check" onchange="changetest(this,'cs_od')"  >
                                      <span class="checkmark" id="CS_OD"></span>
                                    </label>
                                     <span class="status-message" id="CS_OD_status"></span>
                                </div>
                              </div>  
                             <!--  <div class="row">
                                <div class="col-sm-3">
                                  <p style="<?php echo (in_array(15, $checked_data))?'':'background-color: #c6a9a9' ?>" onclick="checkbotheye('fdt');">VF Screening</p>
                                </div>
                                  <div class="col-sm-3">
                                    <select class="mmd-dash-btn m-0" id="fdts" style="visibility: hidden; height:26px;" onchange="changeFDT(this)">
                                    <option value="FDT">FDT</option>
                                    <option value="40 point">40 point</option>
                                   
                                  </select> 
                                </div>
                                <div class="col-sm-3 coustom_checkbox">
                                   <label class="container-checkbox">
                                    <span class="count-message" id="VF_SCREENING_OS_count"></span>

                                   <input type="checkbox" id="fdt_os_check"  <?php echo (in_array(15, $checked_data))?'':'disabled' ?> class="fdt_check" onchange="changetest(this,'fdt_os')"  >
                                      <span class="checkmark" id="VF_SCREENING_OS"></span>
                                    </label>
                                     <span class="status-message" id="VF_SCREENING_OS_status"></span>
                                 
                                </div>
                                <div class="col-sm-3 coustom_checkbox">
                                   <label class="container-checkbox">
                                    <span class="count-message" id="VF_SCREENING_OD_count"></span>

                                   <input id="fdt_od_check" type="checkbox"  <?php echo (in_array(15, $checked_data))?'':'disabled' ?> class="fdt_check" onchange="changetest(this,'fdt_od')"  >
                                      <span class="checkmark" id="VF_SCREENING_OD"></span>
                                    </label>
                                     <span class="status-message" id="VF_SCREENING_OD_status"></span>
                                 
                                </div>
                              </div>  -->   
                             <!--  <div class="row">
                                <div class="col-sm-4">
                                  <p style="<?php echo (in_array(23, $checked_data))?'':'background-color: #c6a9a9' ?>">Dark Adaptation</p>
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox" <?php echo (in_array(23, $checked_data))?'':'disabled' ?> onchange="changetest(this,'da_os')">
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox" <?php echo (in_array(23, $checked_data))?'':'disabled' ?> onchange="changetest(this,'da_od')">
                                </div>
                              </div> 
                              <div class="row">
                                <div class="col-sm-4">
                                  <p style="<?php echo (in_array(22, $checked_data))?'':'background-color: #c6a9a9' ?>">Refractor Test</p>
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox" <?php echo (in_array(22, $checked_data))?'':'disabled' ?> onchange="changetest(this,'ref_os')">
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox" <?php echo (in_array(22, $checked_data))?'':'disabled' ?> onchange="changetest(this,'ref_od')">
                                </div>
                              </div> -->
                            
                             
                            <!--  <div class="row">
                                <div class="col-sm-4">
                                  <p  style="<?php echo (in_array(14, $checked_data))?'':'background-color: #c6a9a9' ?>">Visual Fields Test</p>
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox" <?php echo (in_array(14, $checked_data))?'':'disabled' ?>  onchange="changetest(this,'vf_os')">
                                </div>
                                <div class="col-sm-4">
                                  <input type="checkbox" <?php echo (in_array(14, $checked_data))?'':'disabled' ?> onchange="changetest(this,'vf_od')">
                                </div>
                              </div>  -->
                            </div>
                            
                            
                          </div>
                          <!-- middle options end -->

                          <div class="canvash-content"> 
                          <div class="border-div d-big-screens d-none" style="">
                              <!--<button class="mmd-dash-btn md-btn-gry reload_page" style="visibility: hidden;width: 0px; height: 0px; margin: 0px;padding: 0px;">Reload the page</button><br>-->
                              <span style="font-size: 16px;color: #990000;font-weight: 600; text-align: center;" class="setting-alert-message"></span><br>
                              <span style="font-size: 16px;color: #990000;font-weight: 600; text-align: center;" class="setting-alert-message2"></span>
                            </div>
                          <div   id="myCanvas" height="1020" width="1020" style=" 
                            "> 
                            
                          </div> 
                        </div>
                          <div class="mt-btns gz-trck d-phone">
                              <div class="mt-checkboxes mt-checkboxes-eye">
                                <div class="form-group"  id="voice_instructions_div">
                                  <input type="checkbox"  id="voice_instructions" class="voice_instructions" aria-label="..." onchange="voiceinstructions(this)">
                                  <label for="voice_instructions"><span type="checkbox" style="font-weight: 500;" id="voice_instructions_txt">Voice Instructions</span></label>
                              </div>
                               
                              <div class="form-group">
                                  <input type="checkbox" id="alarm-stop" class="alarm-stop" onchange="alarmstop(this)" aria-label="...">
                                  <label for="alarm-stop"><span type="checkbox"  style="font-weight: 500;">Alarm on Stop</span></label>
                              </div>
                              </div>
                              <button class="mmd-dash-btn md-btn-gry"  data-toggle="modal" data-target="#exampleModalCenter">settings</button>
                                
                              <button class="mmd-dash-btn md-btn-gry dismiss-alarm" style="visibility: hidden; background-color: #d63636 !important;"   onclick="stopalarm();" value="1" >Dismiss Alarm</button>
                          </div>

                          <div class="mt-btns"> 
                            <button class="mmd-dash-btn md-btn-gry reload_page"   style="visibility: hidden;width: 0px; height: 0px; margin: 0px;padding: 0px; background-color: #ea3b3b; !importent">Reload the page</button>
                            <a href="" id="view-pdf-url" target="_blank" class="btn btn-info" style="visibility: hidden;">View Report</a>
                            <button class="mmd-dash-btn md-btn-desabley" id="recall_last_data" style="visibility: hidden; height: 0px;">Recover Last Test</button>
                           <button class="mmd-dash-btn md-btn-yellow" style="" data-toggle="modal" data-target="#myhelpModal" id="connection-help">Help</button>
                            <button class="mmd-dash-btn md-btn-yellow" style="visibility: hidden;" id="connection-verify"></button>
                            <button class="mmd-dash-btn md-btn-gry" id="start">Start</button>
                            <button class="mmd-dash-btn md-btn-desabley" id="pause">Pause</button>
                            <button class="mmd-dash-btn md-btn-desabley" id="resume">Resume</button>
                            <button class="mmd-dash-btn md-btn-desabley" id="stop">Stop</button>
                          </div>
                      </div>
                  </div>

                  <div class="col-lg-3 col-sm-2 d-desktop">
                     
                  </div>
                  
                  <div class="col-lg-2 col-sm-3 d-phone">
                      <div class="mmt-canvas-control">
                        <h3>User Preset</h3>
                        <button class="dash-control" value="PresetF"><?php echo (!empty($user_preset))?$user_preset['UserPreset']['presetF']:'PresetF' ?></button>
                        <button class="dash-control" value="PresetE"><?php echo (!empty($user_preset))?$user_preset['UserPreset']['presetE']:'PresetE' ?></button>
                        <button class="dash-control" value="PresetD"><?php echo (!empty($user_preset))?$user_preset['UserPreset']['presetD']:'PresetD' ?></button>
                        <button class="dash-control" value="PresetC"><?php echo (!empty($user_preset))?$user_preset['UserPreset']['presetC']:'PresetC' ?></button>
                        <button class="dash-control" value="PresetB"><?php echo (!empty($user_preset))?$user_preset['UserPreset']['presetB']:'PresetB' ?></button>
                        <button class="dash-control" value="PresetA"><?php echo (!empty($user_preset))?$user_preset['UserPreset']['presetA']:'PresetA' ?></button>
                      </div>
                      <span>* Double click to save preset</span>
                  </div>

                <!--   <div class="col-lg-3 col-sm-2 d-phone">
                      <div class="mmt-rt-control">
                      <div class="stm-btn-box pb-0">
                          <a href="javascript:void(0)" class="mmd-dash-btn md-btn-gry">Save Report</a>
                      </div>
                      </div>

                  </div> -->

              </div>

        </div>
      </div>
      <!-- 6 may 2020 end -->
      <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">D.O.B.</h4>
        </div>
        <div class="modal-body">
           <div class="row">
                <div class="col-sm-4 col-xs-4 col-lg-4">
                     <input type="number" name="mm" id="mm" class="form-control" placeholder="MM">
                </div>
                 <div class="col-sm-4 col-xs-4 col-lg-4">
                    <input type="number" name="dd" id="dd" class="form-control" placeholder="DD"> 
                </div>
                 <div class="col-sm-4 col-xs-4 col-lg-4">
                     <input type="number" name="yyyy" id="yyyy" class="form-control" placeholder="YYYY">
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" id="submit-age">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

  <div class="modal fade" id="myhelpModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Help</h4>
        </div>
        <div class="modal-body">
          <p style="font-size: 18px;">1. Make sure you can hear the beep on the headset which means the clicker is connected to the headset.<br>2. Make sure the Headset has internet connection.<br>3. Make sure the correct device is selected on the device list.<br>4. Refresh this page (Ctrl+R) to make sure you are logged in</p>
        </div>
        <div class="modal-footer"> 
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
   <div class="modal fade" id="myPtosis_Auto_9_PT" role="dialog">
    <div class="modal-dialog modal-md">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Auto Ptosis Test</h4>
        </div>
        <div class="modal-body">
          <p style="font-size: 18px;">This option will perform two Kinetic-Ptosis tests on the selected eye. First normal and then with eyelid taped.</p>
        </div>
        <div class="modal-footer"> 
          <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
        </div>
      </div>
      
    </div>
  </div>
   <div class="modal fade" id="myPtosis_Auto_9_PT2" role="dialog">
    <div class="modal-dialog modal-md">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Taped Test ?</h4>
        </div>
        <div class="modal-body">
          <p style="font-size: 18px;">Taped the selected eyelied then press to start</p>
        </div>
        <div class="modal-footer"> 
          <button type="button" class="btn btn-success" id="start-Ptosis_Auto_9_PT-auto" data-dismiss="modal">Yes</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
        </div>
      </div>
      
    </div>
  </div>
<div class="modal fade" id="deviceUseModel" role="dialog">
    <div class="modal-dialog modal-md">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Alert</h4>
        </div>
        <div class="modal-body">
          <label>Test Device already in use.</label>
        </div>
        <div class="modal-body" id="clear-device-yes-msg">
          <label>Do you want to clear the device?</label>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal" onclick="clea_test_device();" id="clear-device-yes" >Yes</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal" id="clear-device-no" >No</button>
          <button type="button" class="btn btn-info" data-dismiss="modal" id="clear-device-ok" >Ok</button>
        </div>
      </div>
      
    </div>
  </div>

    <div class="modal fade" id="mystopModal" role="dialog">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Stop Test</h4>
        </div>
        <div class="modal-body">
          <label>Do you want to save the report?</label>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="stop-save-yes" >Yes</button>
          <button type="button" class="btn btn-danger" id="stop-save-no" >No</button>
        </div>
      </div>
      
    </div>
  </div>
  <div class="modal fade" id="mymsgModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Test Status</h4>
        </div> 
        <div class="modal-body">
          <center><span id="msg-mopdel-span"></span></center>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
        </div>
      </div> 
    </div>
  </div>

    <div class="modal fade" id="imputModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Press Button</h4>
        </div> 
        <div class="modal-body">
          <center><input type="text" class="form-control" id="user-preset-input"></center>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success"  data-dismiss="modal" onclick="saveUserPreset();" >Save</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>
      </div> 
    </div>
  </div>

      <!-- Modal -->
      <div class="modal fade mmt-modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content" style="max-width: 670px;margin: auto;">
            <div class="mmd-vf-box">
              <div class="container">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="vf-setting-box">
                      <div class="it-1 rg-master">
                        <h2>VF Settings</h2> 
                        <button type="button" class="close mt-modal-close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div><span id="wall-brightness-warn" style="color: #990000;font-weight: 600;"></span></div>
                      <div class="rg-sliders">
                        <div class="it-1" id="scroll-myinput">
                          <h4><center>Stimulus Size (<span id="myinput-val">3</span>)</center></h4>
                          <input type="range" id="myinput" style="background: linear-gradient(to right, #f5f5f5 0%, #f5f5f5 30%, #d6d6d6 30%, #d6d6d6 100%);" value="3" min="0" max="10"  >
                        </div>
                        <div class="it-1" id="scroll-myinput1">
                          <h4><center>Stimulus Intensity (<span id="myinput-1-val">10</span>dB)</center></h4>
                          <input type="range" id="myinput-1" style="background: linear-gradient(to right, #f5f5f5 0%, #f5f5f5 20%, #d6d6d6 20%, #d6d6d6 100%);" value="20" >
                        </div>
                        <div class="it-1" id="scroll-myinput2">
                          <h4><center>Wall Brightness (<span id="myinput-2-val">28</span>dB)</center></h4>
                          <input type="range" id="myinput-2" style="background: linear-gradient(to right, #f5f5f5 0%, #f5f5f5 28%, #d6d6d6 30%, #d6d6d6 100%);" value="28"     >
                        </div>
                        <div class="it-1" id="scroll-myinput3">
                          <h4><center>Test Speed (<span id="myinput-3-val">0.60</span><span id="myinput-3-val-test"></span></center></h4>
                          <input type="range" id="myinput-3" style="background: linear-gradient(to right, #f5f5f5 0%, #f5f5f5 40%, #d6d6d6 40%, #d6d6d6 100%);" value="40" >
                        </div>
                        <div class="it-1" id="scroll-myinput4">
                          <h4><center>Audio Volume (<span id="myinput-4-val">0.40</span>)</center></h4>
                          <input type="range" id="myinput-4" style="background: linear-gradient(to right, #f5f5f5 0%, #f5f5f5 40%, #d6d6d6 40%, #d6d6d6 100%);" value="40" >
                        </div>
                      </div>
                      <div class="mmd-stimulus">
                        <h3 class="bg-txt"><center>Stimulus Color</center></h3>
                        <div class="mmd-color-pk">
                          <div class="stimulus-color mmd-red-col pk-col">R</div>
                          <div class="stimulus-color mmd-grn-col pk-col">G</div>
                          <div class="stimulus-color mmd-blu-col pk-col">B</div>
                          <div class="stimulus-color mmd-wit-col pk-col colour-selected">W</div>
                        </div>
                      </div>
                      <div class="mmd-stimulus">
                        <h3 class="bg-txt"><center>Background Color</center></h3>
                        <div class="mmd-color-pk">
                          <div class="background-color mmd-ylw-col pk-col">Y</div>
                          <div class="background-color mmd-blk-col pk-col colour-selected">B</div>
                        </div>
                      </div>
                      <div class="dels-drop">
                        <div href="#" class="thrushold">
                          <div class="dropdown mmds-box p-0">
                            <select class="mmd-dash-btn m-0" id="display-type" style="height: 30px;">
                              <option value="">Select Display Type</option>
                              <option value="1" selected="selected">Classic Display</option>
                              <option value="2">Gray Scale</option>
                              <option value="3">Color</option> 
                            </select>

                          </div>
                        </div>
                      </div> 
                      <?php if(isset($_GET['debug']) && $_GET['debug']==1){?>
                        <div class="mt-checkboxes nnt-cb">
                          <div class="form-group">
                            <input type="checkbox" aria-label="...">
                            <span>Delete</span>
                          </div>
                        </div>
                      <?php }?> 
                      <div class="mt-bottom-btns">
                        <div class="con-sve-mast"> 
                        </div>
                        <button class="sve-mstr-Done" data-dismiss="modal">Done</button>
                       
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
</div>
</div>
</div>
</div>

<div id="subAdminView" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="subadmin_detail">
    </div>
  </div>
</div>  

<div class="modal fade bs-example-modal-sm" id="myPleaseWait" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">
          <span class="glyphicon glyphicon-time">
          </span>Please Wait
        </h4>
      </div>
      <div class="modal-body">
        <div class="progress">
          <div class="progress-bar progress-bar-info
          progress-bar-striped active" style="width: 100%">
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<script> 


  var datas = {              
    vfpointdata: []
  };
  var masterData={
    vfpointdata:[]
  };
   
  var StartTestData={
    test_id:'',
    unique_id:'',
    staff_name:'',
    staff_id:'', 
    zoomLevel:'',
    Patient_Name:'',
    DOB:'',
    pid:'',
    od_left:'',
    od_right:'',
    os_left:'',
    os_right:'',
    eyeTaped:false,
    GazeTracking:false,
    testBothEyes:false,
    backgroundcolor:'',
    OfficePateintID:'',
    autoPtosisReport:'',
    DisplaySelect:'',
    REACTION_TIME:'',
    PATIENT_TRAINING:"0",
    PTOSIS_INDEX:'',
    LANGUAGE_SEL:'',
    DELETE_STM:"0",
    DARK_ADAPTATION:"0",
    AUTO_FIXATION:"0",
    WIDE_FIELD_PARAMS:"0",
    EYE:'',
    TOP_LEVEL_TEST_NAME:"1",
    TEST_TYPE:'',
    THRESHOLD_TYPE:'',
    TEST_SUB_TYPE:'',
    TEST_SPEED:'',
    VOLUME:'',
    STM_SIZE:'',
    STM_INTENSITY:'',
    WALL_COLOR:'',
    BKG_INTENSITY:'',
    TEST_COLOR:'',
    PID:'',
    START:'',
    voice_instractuin:'',
    progression_analysis:'',
    vision_screening:'',
    fdt_test_name:'',
    cs_test_name:'',
    va_test_name:'',
    va_test_type:[],
    VR_SET_AWAKE:'', 
    DA_COLOR:0,
    DA_TESTTIME:'',  
    SET_SCREEN_BRIGHTNESS:'',
  };
    
  var MasterRecordData={
    test_type_id:'',
    test_name:'',
    eye_select:0,
    age_group:'',
    numpoints:'',
    color:'',
    test_color_fg:'',
    test_color_bg:'',
    stmSize:0,
    master_key:'',
    created_date:'',
    threshold:'',
    strategy:'',
    backgroundcolor:'',
    publicList:null   
  };
  var Vs={
    va_os:false,
    va_od:false,
    color_os:false,
    color_od:false,
    stereopsis:false,
    fdt_os:false,
    fdt_od:false,
    cs_os:false,
    cs_od:false,
    va_dwtc_os:false,
    va_dwtc_od:false,
    va_dwc_os:false,
    va_dwc_od:false,
    va_dwtc_glare_os:false,
    va_dwtc_glare_od:false,
    va_dwc_glare_os:false,
    va_dwc_glare_od:false,
    va_cwc_os:false,
    va_cwc_od:false,
    // da_os:false,
    // da_od:false,
    // vf_os:false,
    // vf_od:false, 
    // cont_os:false,
    // cont_od:false,
    // ref_os:false,
    // ref_od:false,
  }
   var testnameval={
    cs:false,
    fdt:false,
    color:false,
    stereopsis:false, 
  }
  var fdt_test_name="FDT";
  var cs_test_name="";
  var va_test_name="Standard";
  var va_test_type={
    distant_without_correction:false,
    distant_without_correction_glare:false,
    distant_with_correction:false,
    distant_with_correction_glare:false,
    closeup_with_correction:false,
    with_glare:false,
  }
  var cleardata=false;  // default false 
  var device_type='';
  var check_blink='';
  var start_status=0;
  var check_blink_status=0;
  var MasterRecordList=[];
  var progression_analysis=false;
  var voice_instractuin=true;
  var deviceTypeId=0;
  var coundown_counter=0;
  var kin_count_redraw=0;
  var botheyecount=0;
  var connectionStatus=0;
  var pausecount=0;
  var AutoFixation=true;
  var falsePosError=0;
  var falseNegError=0;
  var fixLossError=0;
  var reliabilityCount=0;
  var completedDeviveStatus=false;
  var round1=0;
  var stopSavestatus=false;
  var EyeTapedStatus=0;
  var DisplaySelect=1;
  var sliderTestSpeed_maxValue=1.2;
  var sliderTestSpeed_minValue=0.4; 
  var startTime=0;
  var dtStartTime=0;
  var elapsedTime='';
  var set_user_preset=1;
  var user_preset='';  
  var user_preset_text='';
  var testBothEyes=false;
  var GazeTracking=false;
  var alarm_stop=false;
  var alarm_sound=false;
  var saveresult=true;
  var restartdata=false;
  var normalColor='';
  var pressedColor ='';
  var highlightedColor = '';
  var measureReactionTime=true;
  var uploadTest='';
  var numDataTxAttempts=0;
  var CONST_DEBUG_LERP=false;
  var autoPtosisReport=false;
  var msgCountTotal=0;
  var ptosisReportIndex=0;
  var kinLocX=[];
  var kinLocY=[];
  var kinPhi=[];
  var kinTheta=[];
  var blindSpotShown=false;
  var fixationLossTotal=0;
  var fixationLossCount=0;
  var lastActionTime='';
  var falsePosTotal=0;
  var falsePosCount=0;
  var falseNegTotal=0;
  var falseNegCount=0;
  var numPointsMissed=0;
  var numPointsSeen=0;
  var numPointsRelative=0
  var FDT_CenterPointVal=0;
  var FDT_ScreeningResult=0;
  var SymbolMap=false;
  var NumericMap=true;
  var GrayScaleMap=true;
  var ReportType;
  var CONST_POINT_DISPLAY_SIZE=10;
  var CONST_POINT_DISPLAY_SIZE_SMALL=5;
  var numTestPointsCompleted=0;
  var msgCount=0;
  var numTestPoints=0;
  var dataCaptured=false;
  var testType=0;
  var lastChangeTime=0;  
  var test_type = "";
  var dropdownThresholdType=0;
  var testTypeName;
  var testSubType=0;
  var selectEye=1;
  var eye_taped=false;
  var stimulusSize=3;
  var stimulusIntensity=10;
  var wallBrightness=28;
  var testSpeed=0.50;
  var testSpeedSleep=200;
  var audioVolume=0.40;
  var agegroup=3;
  var startTestStatus=0; 
  var dataCapturedpause=false;
  var deviceId="";
  var testColour=0;
  var testBackground=0;
  var zoomLevel=0.5;
  var deviceMessages=[];
  var stmLocX=[];
  var stmLocY=[];
  var stmDecibles=[];
  var stmDetectTime=[];
  var enabletestnameliest=[];
  var VF_ResultIndex=0;
  var flagFDT=0;
  var size=1000;
  var messageCount=0;
   
      function ConfirmDialog2(message) {
  $('<div></div>').appendTo('body')
    .html('<div><h6>' + message + '?</h6></div>')
    .dialog({
      modal: true,
      title: 'Stop test',
      zIndex: 10000,
      autoOpen: true,
      width: 'auto',
      resizable: false,
      
      bgiframe: false,
      buttons: {
        Yes: function() {   
            startTestStatus=6;
            round1=0;
            saveresult=false;
            dataCaptured=true;
            $(this).dialog("close");
            stopTest();
            TestData(1);
        },
        No: function() {  
          round1=0;
          startTestStatus=0;
          saveresult=true;
          $(this).dialog("close");
          stopTest();
          TestData(0);
        }
      },
      close: function(event, ui) {
        $(this).remove();
         set_user_preset=1;
      }
    });
}
  jQuery(document).ready(function(){ 

$('.reload_page').on('click',function(){
  window.location.replace('');
  });
$('#start-Ptosis_Auto_9_PT-auto').on('click',function(){
   setFixationLosses('');
              setFalsePositive('');
              setFalseNigative('');

              round1=2;
          kin_count_redraw=1;
          $(".eye-taped").prop("checked", true);  
          eye_taped=true;
          ptosisReportIndex=1;
setting_alert_new('Press the Clicker to initiate the test instructions');
 setting_alert3('Keep the page open during the test',30000);
dataCaptured=true;
        saveresult=false;
        desableAll();
        clertDatashow();
        dataCapturedpause=false;
        startTestStatus=1; 
        numTestPointsCompleted = 0;
        numTestPoints = 0;
        document.getElementById("view-pdf-url").style.visibility = "hidden"; 
        setTestDuration("00:00");
        startTest();
        drawImage(testTypeName); 
        TestData(0);
  });
    <?php if($data['Patient']['dob']==''|| $data['Patient']['dob']==null|| $data['Patient']['dob']=='1970-01-01'){ ?>
       $('#myModal').modal("show"); 


     $('#submit-age').on('click',function(){
      var dd=$("#dd").val(); 
      var mm=$("#mm").val(); 
      var yyyy=$("#yyyy").val();  
      var dob=yyyy+'-'+mm+'-'+dd; 
      agegroupf(dob);
      $('#myModal').modal("hide");
        $.ajax({
          url: "<?php echo WWW_BASE; ?>admin/patients/updatedob",
          type: 'POST', 
          data: {"patient_id": '<?php echo $data['Patient']['id'] ?>',"dob": dob},
          success: function(data){ 
          }
      });
     });
  <?php  }else{ ?>
    agegroupf('<?php echo $data['Patient']['dob'] ?>');
    
   <?php } ?>

    $('#stop-save-yes').on('click',function(){
      $('#mystopModal').modal("hide");
      stop_save_yes();
    });
    $('#stop-save-no').on('click',function(){
      $('#mystopModal').modal("hide");
      stop_save_no();
    });
    $('#display-type').on('change',function(){
      DisplaySelect=$("#display-type").val();
    });

   $('#myinput').on('change',function(){
    stimulusSize=(parseInt($("#myinput").val())).toFixed(0); 
    $("#myinput-val").html(stimulusSize); 
    $("#setting-stm-size").html(stimulusSize);   
  }); 
   $('#myinput-1').on('change',function(){
    stimulusIntensity=(parseInt($("#myinput-1").val())/100*48).toFixed(0);
    $("#myinput-1-val").html(stimulusIntensity);  
  });
   $('#myinput-2').on('change',function(){
     wallBrightness=(parseInt($("#myinput-2").val())/100*96).toFixed(0);
     $("#myinput-2-val").html(wallBrightness);
     $("#setting-bkg-color").html(wallBrightness);  
      if(testType==2 && testSubType==1){
      if(wallBrightness!=34){
        $("#wall-brightness-warn").html('The statistical Analysis uses a value of 34 for background intensity. Changing this value may result in changes in statistical outcome.');
      }else{
        $("#wall-brightness-warn").html('');
      }
      
     }

   });

   $('#myinput-3').on('change',function(){
    var floattofixed;
    if((parseInt(sliderTestSpeed_maxValue)-parseInt(sliderTestSpeed_minValue))==6){
        floattofixed=0;
    }else{
        floattofixed=2;
    }
     //testSpeed=(0.2+(parseInt($("#myinput-3").val())/100)).toFixed(2);
     testSpeed=(sliderTestSpeed_minValue +(parseInt($("#myinput-3").val())/(100/(sliderTestSpeed_maxValue-sliderTestSpeed_minValue)))).toFixed(floattofixed);
     testSpeedSleep=testSpeed*500;
     $("#myinput-3-val").html(testSpeed);
     $("#setting-speed").html(testSpeed);     
   });
   $('#myinput-4').on('change',function(){
     audioVolume=(parseInt($("#myinput-4").val())/100).toFixed(2);
     $("#myinput-4-val").html(audioVolume);  
   });
   
    
   $('#recall_last_data').on('click',function(){ 
    if(dataCaptured==false && dataCapturedpause==false){  
      deviceId=$("#test-device").val();
      var lang=$("#language").val();
      if ((restartReady) && deviceId!='' && lang!=0){
        dataCaptured=true; 
        saveresult=false;
        desableAll();
        dataCapturedpause=false;
        startTestStatus=8; 
         testBothEyes=false;
         setting_alert_new2('Press the Clicker to start last test recovery',30000); 
        recallLastData(); 
        TestData(1);
        clearOldMessage();
      }else{
        if(deviceId==''){
          alert('Please select device');
        }
        if(lang==0){
          alert('Please select language');
        } 
      } 
    }
  });
   $('#start').on('click',function(){ 
   
      testType=$("#test-type").val();
      testSubType=$("#test-strategy").val(); 
      deviceId=$("#test-device").val();
      var lang=$("#language").val();
      if ( (restartReady) && deviceId!='' && lang!=0){
          if(device_type==2 || device_type==4){

             if(dataCaptured==false){
              check_blink='';
               clearDeviceData(1);

               // const promises = clearDeviceData(1);
               // await Promise.all([promises]);
            coundown_counter=0;
            setFixationLosses('');
            setFalsePositive('');
            setFalseNigative('');
            setting_alert2('');
          setting_alert_new('Press the Clicker to start recovery');
           setting_alert3('Keep the page open during the test',30000);
          $("#connection-help").attr("style", "color:#ffffff; visibility:hidden");
     
        dataCaptured=true;
        saveresult=false;
        desableAll();
        start_status=1;
        clertDatashow();
        dataCapturedpause=false;
        startTestStatus=1; 
        numTestPointsCompleted = 0;
        numTestPoints = 0;
        document.getElementById("view-pdf-url").style.visibility = "hidden"; 
        setTestDuration("00:00");
        startTest(); 
        TestData(1);
        checkblenking();
        clearOldMessage();
         } 
      }else{
            setting_alert_new('The Device is not allowed for VS test.');
         }
         }else{
        if(deviceId==''){
          alert('Please select device');
        }
        if(lang==0){
          alert('Please select language');
        }
        
      }
 
  });
   $('#stop').on('click',function(){  
    if(dataCaptured==true){ 
      if(stopSavestatus==true){
       // ConfirmDialog2('Do you want to save the report?');
       // $('#mystopModal').modal("show"); 
        stop_save_no();
      }else{
          dataCaptured=false;
          dataCapturedpause=false;
          startTestStatus=0;
          saveresult=true;
          stopTest();
          TestData(0);
          deviceMessages=[];
          cleardata=false;
      }
      
    }
  });
    $('#pause').on('click',function(){ 
    if(dataCaptured==true && dataCapturedpause==false){
      dataCaptured=false;
      dataCapturedpause=true;
      startTestStatus=2;
      pausecount++;
      checkpausestatus(pausecount);
      pauseTest();
      TestData(1);
    }
  });

     $('#resume').on('click',function(){ 
    if(dataCaptured==false  && dataCapturedpause==true){
      testType=$("#test-type").val();
      testSubType=$("#test-strategy").val();
      if ((testType != 0) && (testSubType != 0) && (restartReady)){
        dataCaptured=true;
        dataCapturedpause=false;
        startTestStatus=3;
        pausecount++;
        resumeTest(); 
        TestData(1);
      }else{

      }

    }
  });
    /*  $('#save_report').on('click',function(){ 
        if((!dataCaptured) && (!dataCapturedpause)){
          saveresult=true;
          startTestStatus=5;
          enableAll();
          TestData();
        }   
  });*/

     function getPixels( imagedata, x, y ) 
{ 
    var position = ( x + imagedata.width * y ) * 4, data = imagedata.data;
    return "#"+("0" + data[ position ].toString(16)).slice(-2)+("0" + data[ position + 1 ].toString(16)).slice(-2)+("0" + data[ position + 2 ].toString(16)).slice(-2);
   // return { r: data[ position ], g: data[ position + 1 ], b: data[ position + 2 ]}; 
}
 
   $(window).load(function() { 
    clearDeviceData();
 
   // va_test_type.distant_without_correction=true;
   //  $(".va_dwtc_div").css("visibility", "visible");
   //  Vs.va_dwtc_os=true;  
   //  Vs.va_dwtc_od=true;
   //  $(".va_dwtc_check").prop("checked", true);
   //  $("#Distant_without_correction").prop("checked", true);
   
 

   clearallolddeviceData();
     $("#myinput-2-val").html(wallBrightness);
     $("#setting-speed").html(testSpeed);  
     $("#setting-bkg-color").html(wallBrightness);
     setting_alert_new('Press the Clicker button and watch for the “Connection Verified” message to appear above the Start button. This indicates you are ready to start a test.');
     <?php if(!empty($defoult_device)){ ?>  
    <?php } ?> 
<?php if(isset($_GET['voice_instractuin'])){
      if($_GET['voice_instractuin']=="true"){
        ?> voice_instractuin=true;<?php
      }else{
         ?> voice_instractuin=false;<?php
      }
     ?>
       $(".voice_instructions").prop("checked", voice_instractuin);  
    <?php } ?>
    $(".voice_instructions").prop("checked", voice_instractuin); 
    <?php if(isset($_GET['alarm_stop'])){  
      if($_GET['alarm_stop']=="true"){
        ?> alarm_stop=true;<?php
      }else{
         ?> alarm_stop=false;<?php
      } ?>
      $(".alarm-stop").prop("checked", alarm_stop); 
    <?php } ?>
       updateDevice();
     restartReady = true; 
     get_deviceDatastop(); 
  }); 
 });
 
      
 jQuery(document).ready(function(){ 
  $('.dash-control').on('click',function(){
     if(saveresult==false){
      if ((!dataCaptured) && (!dataCapturedpause)){ 
        var r = confirm("Report has not been saved and the last test results will be lost. Do you want to switch the test?");
             if (r == true) {
              dataCaptured=false;
              saveresult=true;
              deviceMessages=[];
              stopTestforce();
              setUserPreset($(this).val()); 
            } else { 
          }
      }else{
        //setUserPreset($(this).val()); 
      }
    }else{
       setUserPreset($(this).val()); 
    }
      
     });
      $('.dash-control').on('dblclick',function(){
        set_user_preset=0;
        user_preset=$(this).val();  
        user_preset_text=$(this).text(); 
        if(saveresult==false){
          if ((!dataCaptured) && (!dataCapturedpause)){ 
            var r = confirm("Report has not been saved and the last test results will be lost. Do you want to switch the test?");
             if (r == true) {
              dataCaptured=false;
              saveresult=true;
              deviceMessages=[];
              stopTestforce();
              if(testType==-1){
                alert('please select test type');
                set_user_preset=1;
              }else{
                ConfirmDialog('Do you want to save this current setting? Press yes to save'); 
              } 
            }else{
               set_user_preset=1;
            }
          }else{
             set_user_preset=1; 
          }
      }else{
        if(testType==-1){
              alert('please select test type');
               set_user_preset=1;
            }else{
              ConfirmDialog('Do you want to save this current setting? Press yes to save'); 
            }
      }
      
     }); 
   
      function ConfirmDialog(message) {
  $('<div></div>').appendTo('body')
    .html('<div><h6>' + message + '?</h6></div>')
    .dialog({
      modal: true,
      title: 'Save setting',
      zIndex: 10000,
      autoOpen: true,
      width: 'auto',
      resizable: false,
      
        bgiframe: false,
      buttons: {
        Yes: function() {   
          $("#user-preset-input").val(user_preset_text);
           $('#imputModal').modal("show"); 
          $(this).dialog("close");
           set_user_preset=1;
        },
        No: function() {  
          $(this).dialog("close");
           set_user_preset=1;
        }
      },
      close: function(event, ui) {
        $(this).remove();
         set_user_preset=1;
      }
    });
};
  $('.stimulus-color').on('click',function(){
    if(!dataCaptured){
    $('.stimulus-color').removeClass("colour-selected");
    $(this).addClass("colour-selected");
    var testColours=$(this).html();
    switch(testColours){
      case 'W':
      testColour=0;
      break;
      case 'R':
      testColour=1;
      break;
      case 'G':
      testColour=2;
      break;
      case 'B':
      testColour=3;
      break;
      default:
      testColour=0;
      break;
    }
  }
    //
  });
  $('.background-color').on('click',function(){
    if(!dataCaptured){
    $('.background-color').removeClass("colour-selected");
    $(this).addClass("colour-selected");
    var testbkgColours=$(this).html();
    switch(testbkgColours){
      case 'B':
      testBackground=0;
      break;
      case 'Y':
      testBackground=1;
      break; 
      default:
      testBackground=0;
      break;
    }
  }
  });
   
 
 });
 function clearOldMessage(argument) { 
    $("#VA_OS_status").html('');
    $("#VA_OD_status").html(''); 
    $("#VA_DWTC_OS_status").html('');
    $("#VA_DWTC_OD_status").html('');
    $("#VA_DWTC_GLARE_OS_status").html('');
    $("#VA_DWTC_GLARE_OD_status").html('');
    $("#VA_DWC_GLARE_OS_status").html('');
    $("#VA_DWC_GLARE_OD_status").html('');
    $("#VA_DWC_OS_status").html('');
    $("#VA_DWC_OD_status").html('');
    $("#VA_CWC_OS_status").html('');
    $("#VA_CWC_OD_status").html(''); 
    $("#COLOR_OS_status").html('');
    $("#COLOR_OD_status").html('');
    $("#STEREOPSIS_OS_status").html('');
    $("#STEREOPSIS_OD_status").html(''); 
    $("#VF_SCREENING_OS_count").html('');
    $("#VF_SCREENING_OD_count").html('');
    $("#VF_SCREENING_OS_status").html('');
    $("#VF_SCREENING_OD_status").html('');
    $("#CS_OS_status").html('');
    $("#CS_OD_status").html('');
    $('.base').removeClass("base");
    $('.green').removeClass("green");
    
    
 }
 function vs_suboption(val1,val2) { 
  if(val1==true || val2 ==true){ 
     // va_test_name="CS"; 

      document.getElementById("vas").style.visibility = "visible";
       document.getElementById("vas").style.display = "block";
       document.getElementById("vao").style.visibility = "visible";
       document.getElementById("vao").style.display = "block";
  }else{ 
    va_test_name="";
     va_test_type.distant_without_correction=false;
    va_test_type.distant_with_correction=false;
    va_test_type.closeup_with_correction=false;
    va_test_type.with_glare=false; 
     //$(".vaoc").prop("checked", false); 
     document.getElementById("Distant_without_correction").checked = false;
     document.getElementById("Distant_with_correction").checked = false;
      document.getElementById("Closeup_with_correction").checked = false;
     document.getElementById("With_Glare").checked = false;
   // document.getElementById("vas").value = "HOTV";
       document.getElementById("vas").style.visibility = "hidden";
       document.getElementById("vao").style.visibility = "hidden"; 
         document.getElementById("vao").style.display = "none";
          document.getElementById("vas").style.display = "none";
  } 
 }
  function fdt_suboption(val1,val2) { 
  if(val1==true || val2 ==true){  
      fdt_test_name="FDT"; 
      document.getElementById("fdts").style.visibility = "visible"; 
  }else{ 
    fdt_test_name='';

     document.getElementById("fdts").value = "FDT";
       document.getElementById("fdts").style.visibility = "hidden"; 
  }
 }
 function cs_suboption(val1,val2) { 
  if(val1==true || val2 ==true){  
      document.getElementById("cs").style.visibility = "visible"; 
      if(cs_test_name==''){  
      cs_test_name="Green"; 
      document.getElementById("cs").value="Green";
      }
  }else{ 
    cs_test_name='';

     document.getElementById("cs").value = "Red";
       document.getElementById("cs").style.visibility = "hidden"; 
  }
 }
 function changeFDT(argument) {
   fdt_test_name=$("#fdts").val(); 
 }
 function changeCS(argument) {
   cs_test_name=$("#cs").val(); 
 }
 function changeVA(argument) {
   va_test_name=$("#vas").val(); 
 }
 function getPrevTest(val1, val2) {
  if(val1==true || val2 ==true){ 
  getPrevTest2();
}else{
  MasterRecordList=[];
  delete  MasterRecordData.publicList;
}
 }

 function getPrevTest2() {
  
    $.ajax({
      url: "<?php echo WWW_BASE; ?>admin/masterReports/testdata3/1111",
      type: 'GET',
 
      data: {"ageGroup": agegroup, "testTypeName": 'N30',"patient_id": '<?php echo $data['Patient']['id'] ?>', "testType": 1, "eye":selectEye,"deviceId": deviceId },
      error: function (request, status, error) { 
          
    },
      success: function(data){ 
        data=JSON.parse(data)  
        var arr=data.VfMasterdata;   
          if(data.hasOwnProperty('MasterRecordList')){
              MasterRecordList=data.MasterRecordList;
          }else{
            MasterRecordList=[];
          }
           var arr=data.VfMasterdata;  
      //  if(data.VfMasterdata.length!=0){
          if(data.hasOwnProperty('VfMasterdata')){
          masterData.vfpointdata = data.VfMasterdata.slice(); 
        }
    
      
         if(data.hasOwnProperty('Masterdata')){
            // stimulusSize=(data.hasOwnProperty('Masterdata'))?parseInt(data.Masterdata.stmsize):3;  
            // $("#myinput").val(stimulusSize); 
            // $("#myinput-val").html(stimulusSize); 
            // $("#setting-stm-size").html(stimulusSize); 
          
            // document.getElementById("myinput").style.background = 'linear-gradient(to right, #f5f5f5 0%, #f5f5f5 ' + (stimulusSize)*10 + '%, #d6d6d6 ' + stimulusSize + '%, #d6d6d6 100%)';
                
            if(data.hasOwnProperty('Masterdata')){
                if(data.Masterdata.backgroundcolor!=null && data.Masterdata.backgroundcolor!=''){
                     wallBrightness=data.Masterdata.backgroundcolor;  
                }
            }
            
            testColour=(data.hasOwnProperty('Masterdata'))?parseInt(data.Masterdata.test_color_fg):null;
            testBackground=(data.hasOwnProperty('Masterdata'))?parseInt(data.Masterdata.test_color_bg):null;   
        }
         if(data.hasOwnProperty('VfMasterdata')){ 
            var resultm=data.VfMasterdata.map(function(x){ 
            x.x=parseFloat(x.x);
            x.y=parseFloat(x.y);
            x.STD=parseFloat(x.STD); 
            x.intensity=parseFloat(x.intensity);
            x.size=parseInt(x.size); 
              return x; 
            }); 
        }
        MasterRecordData.age_group=(data.hasOwnProperty('Masterdata'))?data.Masterdata.age_group.toString():"";
        MasterRecordData.numpoints=(data.hasOwnProperty('Masterdata'))?data.Masterdata.numpoints:"";
        MasterRecordData.color=(data.hasOwnProperty('Masterdata'))?data.Masterdata.color.toString():"";
        MasterRecordData.backgroundcolor=(data.hasOwnProperty('Masterdata'))?data.Masterdata.backgroundcolor.toString():"";
        MasterRecordData.stmSize=(data.hasOwnProperty('Masterdata'))?parseInt(data.Masterdata.stmsize):0;
        MasterRecordData.master_key=(data.hasOwnProperty('Masterdata'))?data.Masterdata.master_key.toString():'';
        MasterRecordData.created_date=(data.hasOwnProperty('Masterdata'))?data.Masterdata.created.toString():'';  
        MasterRecordData.test_color_fg=parseInt(testBackground);
        MasterRecordData.test_color_bg=parseInt(testColour);  
        MasterRecordData.publicList=(data.hasOwnProperty('VfMasterdata'))?data.VfMasterdata:null;   
}
});
  }
 const sleep = (milliseconds) => {
  return new Promise(resolve => setTimeout(resolve, milliseconds))
}
 const clearallolddeviceData = async function(){  
          var feedback = $.ajax({
          type: "POST",
          url: "<?php echo WWW_BASE; ?>admin/patients/cleardevice",
           data: {"d": 4},
          async: false
      }).success(function(){ 
          setTimeout(function(){clearallolddeviceData();}, 1000*60);
      }).responseText; 
 }

 const checkblenking = async function(){  
          if(saveresult==false && check_blink!='' && start_status==1){
             console.log(check_blink);
            if(check_blink=='STEREOPSIS_OU'){
              if(check_blink_status==0){
              check_blink_status=1;
               $('#STEREOPSIS_OS').addClass("base");
            $('#STEREOPSIS_OD').addClass("base");
            }else{
              check_blink_status=0;
               $('#STEREOPSIS_OS').removeClass("base");
             $('#STEREOPSIS_OD').removeClass("base");
            }
            }else{
              if(check_blink_status==0){
              check_blink_status=1;
               $('#'+check_blink).addClass("base");
            }else{
              check_blink_status=0;
              $('#'+check_blink).removeClass("base");
            }
            }
          }
          setTimeout(function(){checkblenking();}, 1000);
       
 }

//function get_deviceData(){
 const get_deviceDatastop = async function(){ 
 if(connectionStatus==0){
  connectionStatus=1;
  setting_alert_new('Press the Clicker button and watch for the “Connection Verified” message to appear above the Start button. This indicates you are ready to start a test.');
  $("#connection-help").attr("style", "color:#ffffff; visibility:visible");
 } 
    if((!dataCaptured) && (!restartdata) && (saveresult)){
          deviceId=$("#test-device").val();
          var feedback = $.ajax({
          type: "POST",
          url: "<?php echo WWW_BASE; ?>css/get_device_data_stop.php",
           data: {"office_id": '<?php echo $user['office_id'] ?>', "device_id": deviceId },
          async: false
      }).success(function(){ 
          setTimeout(function(){get_deviceDatastop();}, 400);
      }).responseText; 
     obj = JSON.parse(feedback);
      for (var key in obj.message) {
        if(obj.message!='No data'){
           if(!deviceMessages.includes(obj.message[key]['message']+"|"+obj.message[key]['id'])){
               deviceMessages.push(obj.message[key]['message']+"|"+obj.message[key]['id']);
               
                 var  res= obj.message[key]['message'].split("|"); 
              if(res[1]=='BTN_PRESS'){
                setting_alert_new('Ready to Start');
                setting_alert_btn_press("Connection Verified");
              }
            }
        }
      }
    }
 }

  const get_deviceData = async function(){
 /* const promises =  setpixcelVF_SC2_RESULT(item_new);
        await Promise.all([promises]);*/
  if((dataCaptured || restartdata) && (!saveresult)){
  deviceId=$("#test-device").val();
    var feedback = $.ajax({
        type: "POST",
        url: "<?php echo WWW_BASE; ?>css/get_device_data.php",
         data: {"office_id": '<?php echo $user['office_id'] ?>', "device_id": deviceId },
        // data: {"office_id": 39, "device_id": 39 },
        async: false
    }).success(function(){
        setTimeout(function(){get_deviceData();}, 400);
    }).responseText; 
   obj = JSON.parse(feedback);
   console.log('end4='+cleardata);
   if(cleardata==true)
   for (var key in obj.message) {
  
if(obj.message!='No data'){
   if(restartdata){
      // restartdata=false;
      // dataCaptured=true;
     // startTest();
    }
   if(!deviceMessages.includes(obj.message[key]['message']+"|"+obj.message[key]['id'])){
       deviceMessages.push(obj.message[key]['message']+"|"+obj.message[key]['id']);
        
     
      var  res= obj.message[key]['message'].split("|");
    //$('#feedback-box').html(res[1]);
    if(res[1]=='BATTERY_LEVEL'){
      var bat_con='#00ff00';
      if(res[2]>20){
        bat_con='#00ff00';
        document.getElementById("fa-battery").style.color = "#000000";
      }else{
        bat_con='#ff0000';
        
        document.getElementById("fa-battery").style.color = "#ff0000";
      }
      var bat_per=parseInt(parseInt(res[2])/100*95);
      document.getElementById("battery").style.background = "linear-gradient(to right, "+bat_con+" 0%, "+bat_con+" "+bat_per+"%, #3292e0 0%, #3292e0 100%)";
      //background: linear-gradient(to right, #00ff00 0%, #00ff00 67%, #d6d6d6 30%, #d6d6d6 100%);
       document.getElementById("battery").style.visibility = "visible";
        
    }else if(res[1]=='BTN_PRESS'){
       
    }else if(res[1]=='DETAILED_PROG_PROCESSING'){
      const promises = DETAILED_PROG_PROCESSING(res[2]);
      
       await Promise.all([promises]);
    }else if(res[0]=='VS' && res[1]=='SCREENING_DONE'){ 
        const promises =  setpixcelVF_TEST_COMPLETED(obj.message[key]['message'],obj.message[key]['id'],obj.message[key]['office_id'],obj.message[key]['device_id']); 
        await Promise.all([promises]);
    }
    else if((res[0]=='VA' || res[0]=='VS') && res[1]=='HOTV_RESULT'){ 
        const promises =  setpixcelVF_HOTV_RESULT(res[2],res[3]); 
        await Promise.all([promises]);
    }
    else if((res[0]=='VA' || res[0]=='VS')  && res[1]=='DWTC_OS_RESULT'){ 
        const promises =  setpixcelVF_DWTC_OS_RESULT(res[2]); 
        await Promise.all([promises]);
    }
    else if((res[0]=='VA' || res[0]=='VS')  && res[1]=='DWTC_GLARE_OS_RESULT'){ 
        const promises =  setpixcelVF_DWTC_GLARE_OS_RESULT(res[2]); 
        await Promise.all([promises]);
    }
     else if((res[0]=='VA' || res[0]=='VS')  && res[1]=='DWTC_GLARE_OD_RESULT'){ 
        const promises =  setpixcelVF_DWTC_GLARE_OD_RESULT(res[2]); 
        await Promise.all([promises]);
    }
    else if((res[0]=='VA' || res[0]=='VS')  && res[1]=='DWC_GLARE_OS_RESULT'){ 
        const promises =  setpixcelVF_DWC_GLARE_OS_RESULT(res[2]); 
        await Promise.all([promises]);
    }
     else if((res[0]=='VA' || res[0]=='VS')  && res[1]=='DWC_GLARE_OD_RESULT'){ 
        const promises =  setpixcelVF_DWC_GLARE_OD_RESULT(res[2]); 
        await Promise.all([promises]);
    }
    else if((res[0]=='VA' || res[0]=='VS')  && res[1]=='DWTC_OD_RESULT'){ 
        const promises =  setpixcelVF_DWTC_OD_RESULT(res[2]); 
        await Promise.all([promises]);
    }
    else if((res[0]=='VA' || res[0]=='VS')  && res[1]=='DWC_OS_RESULT'){ 
        const promises =  setpixcelVF_DWC_OS_RESULT(res[2]); 
        await Promise.all([promises]);
    }
    else if((res[0]=='VA' || res[0]=='VS')  && res[1]=='DWC_OD_RESULT'){ 
        const promises =  setpixcelVF_DWC_OD_RESULT(res[2]); 
        await Promise.all([promises]);
    }
    else if((res[0]=='VA' || res[0]=='VS')  && res[1]=='CWC_OS_RESULT'){ 
        const promises =  setpixcelVF_CWC_OS_RESULT(res[2]); 
        await Promise.all([promises]);
    }
    else if((res[0]=='VA' || res[0]=='VS')  && res[1]=='CWC_OD_RESULT'){ 
        const promises =  setpixcelVF_CWC_OD_RESULT(res[2]); 
        await Promise.all([promises]);
    }
     else if((res[0]=='VA' || res[0]=='VS')  && res[1]=='CS_OS_RESULT'){ 
        const promises =  setpixcelVF_CS_OS_RESULT(res[2]); 
        await Promise.all([promises]);
    }
    else if((res[0]=='VA' || res[0]=='VS')  && res[1]=='CS_OD_RESULT'){ 
        const promises =  setpixcelVF_CS_OD_RESULT(res[2]); 
        await Promise.all([promises]);
    }

     else if((res[0]=='VA' || res[0]=='VS')  && res[1]=='SCREENING_OS_RESULT'){ 
        const promises =  setpixcelVF_SCREENING_OS_RESULT(res[2]); 
        await Promise.all([promises]);
    }
    else if((res[0]=='VA' || res[0]=='VS')  && res[1]=='SCREENING_OD_RESULT'){ 
        const promises =  setpixcelVF_SCREENING_OD_RESULT(res[2]); 
        await Promise.all([promises]);
    } 
    else if((res[0]=='VA' || res[0]=='VS')  && res[1]=='CB_RESULT'){ 
        const promises =  setpixcelVF_CB_RESULT(res[2]); 
        await Promise.all([promises]);
    }
    else if((res[0]=='VA' || res[0]=='VS')  && res[1]=='CB_RESULT_OD'){ 
        const promises =  setpixcelVF_CB_RESULT_OD(res[2]); 
        await Promise.all([promises]);
    }
    else if((res[0]=='VA' || res[0]=='VS')  && res[1]=='CB_RESULT_OS'){ 
        const promises =  setpixcelVF_CB_RESULT_OS(res[2]); 
        await Promise.all([promises]);
    }
    else if((res[0]=='VA' || res[0]=='VS')  && res[1]=='STEROPSIS_RESULT'){ 
        const promises =  setpixcelVF_STEROPSIS_RESULT(res[2]); 
        await Promise.all([promises]);
    }
    else if(res[0]=='VS' && res[2]=='IN_PROGRESS'){ 
        const promises =  changeProgress(res[1]); 
        await Promise.all([promises]);
    }
    else if(res[0]=='VS' && res[2]=='DONE'){ 
        const promises =  changeDone(res[1]); 
        await Promise.all([promises]);
    }
    else if(res[0]=='VS' && res[1]=='SCREENING_START'){ 
        console.log('SCREENING_START message');
    	  setpixcelVF_START_BUTTON_PRESSED('item_new'); 
    } else{
    	 

      var  res2= obj.message[key]['message'].split("VF|");
  
       
       const fillTPS2 = async function(item) {
          item_new="VF|"+item;
          res3=item.split("|"); 
         if(res3[0]=='TEST_STATUS'){
      setpixcelVF_TEST_STATUS(item_new); 
    }
    else if(res3[0]=='USER_PAUSE'){
      setpixcelVF_USER_PAUSE(item_new); 
    }
    else if(res3[0]=='USER_RESUME'){
      setpixcelVF_USER_RESUME(item_new); 
    }
    else if(res3[0]=='TEST_PAUSED_BY_PATIENT'){
      setpixcelVF_TEST_PAUSED_BY_PATIENT(item_new); 
    }
    else if(res3[0]=='PRIMARY_POINTS'){
      setpixcelVF_PRIMARY_POINTS(item_new); 
    }
    else if(res3[0]=='BLINDSPOT_POINTS'){
      setpixcelVF_BLINDSPOT_POINTS(item_new); 
    }
    else if(res3[0]=='MAIN_POINTS'){ 
        setpixcelVF_MAIN_POINTS(item_new);
    }
    else if(res3[0]=='START_BUTTON_PRESSED'){
       // setpixcelVF_START_BUTTON_PRESSED(item_new);
        
    }else if(res3[0]=='VF_FILE_UPLOADED'){ 
         const promises =  setpixcelVF_FILE_UPLOADED(item_new); 
         await Promise.all([promises]); 
        
    }else if(res3[0]=='FIXATION'){
        setpixcelVF_FIXATION(item_new);
        
    }

    
     }
     const promises = res2.map(fillTPS2);
       await Promise.all([promises]);
   }

   }else{ 
   }
   }
   }
  }
}
 
function setpixcelVF_FIXATION(data) {
  var  splitData= data.split("|");
  ifixVal = parseInt(splitData[2]);
  if (GazeTracking==true)
  {
    setTestReliability('Fixation'); 
    if (ifixVal == 1)
    {
      setReliabilitybtn("#00ff00"); 
    }
    else
    {
     setReliabilitybtn("#ff0000");
    }
  }
}
function setpixcelVF_TEST_STATUS(data) {
  var  splitData= data.split("|");
  switch(splitData[2])
        {
          case "CLICK":
            TestStatus= "Click " + splitData[3];
            setTestStatus(TestStatus);
            break;
          case "COUNTDOWN":
            TestStatus = "Countdown " + splitData[3];
            setTestStatus(TestStatus);
            if(coundown_counter==0){
              coundown_counter=1;
             // startTestStatus=9;
              updateStatus();
            }
            break;
          case "TRAINING":
            TestStatus = "Training " + splitData[3];
            setTestStatus(TestStatus);
            break;
          default:
            break;    
        }
        return 1;
}
function setpixcelVF_USER_PAUSE(data) {
   TestStatus= "Paused";
   setTestStatus(TestStatus);
}
function setpixcelVF_USER_RESUME(data) {
   TestStatus= "In Progress";
   setTestStatus(TestStatus);
}
function setpixcelVF_TEST_PAUSED_BY_PATIENT(data) {
  var  splitData= data.split("|");
   pval = parseInt(splitData[2]);
   if(pval==1){
    TestStatus= "Test is Paused by the patient.  To    restart the test, ask the patient to press the trigger button.";
   setting_alert_new(TestStatus);
   }else{
    TestStatus= "Test has been Restarted by the Patient.";
    setting_alert_new2(TestStatus,2000);
   }
  
  //setTestStatus(TestStatus);
}

function setpixcelVF_PRIMARY_POINTS(data) {
   var  splitData= data.split("|");
   pval = parseInt(splitData[2]);
        if (pval == 1)
        { 
          TestStatus= "Primary Points";
          setTestStatus(TestStatus); 
        } 
}
function setpixcelVF_BLINDSPOT_POINTS(data) { 
   var  splitData= data.split("|"); 
   pval = parseInt(splitData[2]);
        if (pval == 1)
        { 
          TestStatus= "Blindspot Points";
          setTestStatus(TestStatus); 
        } 
}
function setpixcelVF_MAIN_POINTS(data) {
   var  splitData= data.split("|"); 
   pval =  parseInt(splitData[2]);
        if (pval == 1)
        { 
          TestStatus= "Main Test Points";
          setTestStatus(TestStatus); 
        } 
}
function setpixcelVF_START_BUTTON_PRESSED(data) {
  stopSavestatus=true;
  completedDeviveStatus=false;
  startTime= new Date().getTime();
  dtStartTime= new Date().getTime();
  testduration();
  setting_alert_new2('Starting Test',3000);
}
const setpixcelVF_FILE_UPLOADED = async function(data) {
   // var  splitData= data.split("|");
   //   document.getElementById("view-pdf-url").style.visibility = "visible";
   //  setting_alert_new2('Test has Finished',3000);
   //   document.getElementById("view-pdf-url").href="<?php echo WWW_BASE; ?>admin/patients/view_pdf_report/"+splitData[2]; 
   //   window.open("<?php echo WWW_BASE; ?>admin/patients/view_pdf_report/"+splitData[2]);
   //    saveresult=true; 
   //  dataCaptured=false;
   //  stopSavestatus=false;
   //   $.ajax({
   //    url: "<?php echo WWW_BASE; ?>admin/patients/clear_alldata/3554",
   //    type: 'POST', 
   //     data: {"patient_id": '<?php echo $data['Patient']['id'] ?>',"office_id": '<?php echo $user['office_id'] ?>',"deviceId": $("#test-device").val()}, 
   //      error: function (request, status, error) {
   //       dataCapturedpause=false;
   //        connectionStatus=0;
   //        start_status=0;
   //        get_deviceDatastop();
   //      },
   //    success: function(data){ 
   //            dataCapturedpause=false;
   //          connectionStatus=0;
   //          restartdata=false;
   //          start_status=0;
   //             get_deviceDatastop();
   //        }
   //    });
   //    if(startTestStatus==8){
   //    restartdata=false;
   //  dataCaptured=false;
   //  completedDeviveStatus=false;
   //  numTestPointsCompleted=0;
   //  clertDatashow();
   //  deviceMessages=[]; 
   //  stopTest(1);
   //  startTestStatus=0; 
   //   } 
   //   return 1; 

   var  splitData= data.split("|");
     document.getElementById("view-pdf-url").style.visibility = "visible"; 
     document.getElementById("view-pdf-url").href="<?php echo WWW_BASE; ?>admin/patients/view_pdf_report/"+splitData[2]; 
     window.open("<?php echo WWW_BASE; ?>admin/patients/view_pdf_report/"+splitData[2]);
       
     return 1;
}
  const changeProgress = async function(data) {
    check_blink=data;
    $('#STEREOPSIS_OS').removeClass("base");
    $('#STEREOPSIS_OD').removeClass("base");
    $('#COLOR_OS').removeClass("base");
    $('#COLOR_OD').removeClass("base");
    $('#VA_OS').removeClass("base");
    $('#VA_OD').removeClass("base");
    $('#VF_SCREENING_OS').removeClass("base");
    $('#VF_SCREENING_OD').removeClass("base");
    $('#CS_OS').removeClass("base");
    $('#CS_OD').removeClass("base");

     if(data=='STEREOPSIS_OU'){
      $('#STEREOPSIS_OD_status').html('In progress');
        $('#STEREOPSIS_OS_status').html('In progress');
     }else{
        $('#'+data+'_status').html('In progress');
     }
  }
  const changeDone = async function(data) {
    check_blink='';
    if(data=='STEREOPSIS_OU'){
       $('#STEREOPSIS_OS').addClass("green");
      $('#STEREOPSIS_OD').addClass("green");
       $('#STEREOPSIS_OS').removeClass("base");
        $('#STEREOPSIS_OD').removeClass("base");
      //  $('#STEREOPSIS_OD_status').html('');
      //  $('#STEREOPSIS_OS_status').html('');
    }else{
      if(data=='VF_SCREENING_OD'){
        $('#VF_SCREENING_OD_status').html('');  
      }
      if(data=='VF_SCREENING_OS'){
        $('#VF_SCREENING_OS_status').html('');  
      }
    $('#'+data).removeClass("base");
     $('#'+data).addClass("green");
    }
  }
  

const setpixcelVF_DWTC_OS_RESULT = async function(val) { 
        $('#VA_DWTC_OS_status').html(val); 
  }
  
  const setpixcelVF_DWTC_OD_RESULT = async function(val) { 
        $('#VA_DWTC_OD_status').html(val); 
  }
   const setpixcelVF_DWTC_GLARE_OS_RESULT = async function(val) { 
        $('#VA_DWTC_GLARE_OS_status').html(val); 
  }
  const setpixcelVF_DWTC_GLARE_OD_RESULT = async function(val) { 
        $('#VA_DWTC_GLARE_OD_status').html(val); 
  }
   const setpixcelVF_DWC_GLARE_OS_RESULT = async function(val) { 
        $('#VA_DWC_GLARE_OS_status').html(val); 
  }
  const setpixcelVF_DWC_GLARE_OD_RESULT = async function(val) { 
        $('#VA_DWC_GLARE_OD_status').html(val); 
  }
  const setpixcelVF_DWC_OS_RESULT = async function(val) { 
        $('#VA_DWC_OS_status').html(val); 
  }
  const setpixcelVF_DWC_OD_RESULT = async function(val) { 
        $('#VA_DWC_OD_status').html(val); 
  }
   const setpixcelVF_CWC_OS_RESULT = async function(val) { 
        $('#VA_CWC_OS_status').html(val); 
  }
  const setpixcelVF_CWC_OD_RESULT = async function(val) { 
        $('#VA_CWC_OD_status').html(val); 
  }
  const setpixcelVF_CS_OS_RESULT = async function(val) { 
        $('#CS_OS_status').html(val); 
  }
  const setpixcelVF_CS_OD_RESULT = async function(val) { 
        $('#CS_OD_status').html(val); 
  }

  const setpixcelVF_SCREENING_OS_RESULT = async function(val) { 
        $('#VF_SCREENING_OS_status').html(val); 
  }
  const setpixcelVF_SCREENING_OD_RESULT = async function(val) { 
        $('#VF_SCREENING_OD_status').html(val); 
  }

  

const setpixcelVF_CB_RESULT_OD = async function(val) { 
    val_new=val.split("Total");  
        $('#COLOR_OD_status').html("Total"+val_new[1]); 
  }
  const setpixcelVF_CB_RESULT_OS = async function(val) { 
     val_new=val.split("Total"); 
        $('#COLOR_OS_status').html("Total"+val_new[1]); 
  }
const setpixcelVF_CB_RESULT = async function(val) { 
        $('#COLOR_OS_status').html(val); 
  }
  const setpixcelVF_STEROPSIS_RESULT = async function(val) { 
        $('#STEREOPSIS_OS_status').html(val); 
        $('#STEREOPSIS_OD_status').html(''); 
  }
const setpixcelVF_HOTV_RESULT = async function(eye,val) {
    if(eye==0){
        //$('#VA_OS_count').html(val);
       // $('#VA_DWTC_OS_count').html(val);
        
        $('#VA_OS_status').html(val);
    }else{
       $('#VA_OD_status').html(val);
      //$('#VA_OD_count').html(val);
      //$('#VA_DWTC_OD_count').html(val);
    }
  }
 const setpixcelVF_TEST_COMPLETED = async function(data,id,office_id,device_id) {
//function setpixcelVF_TEST_COMPLETED(data,id,office_id,device_id) {
    var  splitData= data.split("|");
    testState = 0;
    setTestStatus('Completed');  
    restartdata=true;
    dataCaptured=false;
    completedDeviveStatus=true;
    numTestPointsCompleted=0;
    clertDatashow();
    deviceMessages=[];
    $.ajax({
      url: "<?php echo WWW_BASE; ?>admin/patients/testcomplited/3554",
      type: 'POST',
       data: {"id": id,"office_id": office_id,"device_id": device_id}, 
      success: function(data){
        }
      });
    stopTest(1);
    startTestStatus=0; 
    setting_alert('Test Completed');
     elapsedTime = CalculateElapsedTime(); 



///  NEW INPLEMNTED CODE OF FILE UPLOAF DUNCTION 

     saveresult=true; 
    dataCaptured=false;
    stopSavestatus=false;
    
     $.ajax({
      url: "<?php echo WWW_BASE; ?>admin/patients/clear_alldata/3554",
      type: 'POST', 
       data: {"patient_id": '<?php echo $data['Patient']['id'] ?>',"office_id": '<?php echo $user['office_id'] ?>',"deviceId": $("#test-device").val()}, 
        error: function (request, status, error) {
         dataCapturedpause=false;
          connectionStatus=0;
          start_status=0;
          get_deviceDatastop();
        },
      success: function(data){ 
              dataCapturedpause=false;
            connectionStatus=0;
            restartdata=false;
            start_status=0;
               get_deviceDatastop();
          }
      });
     
      if(startTestStatus==8){
      restartdata=false;
    dataCaptured=false;
    completedDeviveStatus=false;
    numTestPointsCompleted=0;
    clertDatashow();
    deviceMessages=[]; 
    stopTest(1);
    startTestStatus=0; 
     }
}
  
  

const DETAILED_PROG_PROCESSING = async function(data) {
  // body...
  if(data==1){
    setting_alert_new('Detailed Progression In Progress. Please Wait.');
  }else{
     setting_alert_new('');
  }
  return 1;
 
}
  
 
   
</script> 
<script>
  
const clearDeviceData = async function(data) { 
    if($("#test-device").val()!=''){
      $.ajax({
      url: "<?php echo WWW_BASE; ?>admin/patients/cleardevicedata/3554",
      type: 'POST',

       data: {"office_id": '<?php echo $user['office_id'] ?>',"deviceId": $("#test-device").val()}, 
      success: function(data){
        
        }
      });
    }else{
      return;
    }
  }
//function drawImage(value,col1='#000000') { 
 const drawImage = async function(value,col1='#000000') { 
     
}
function setZoomLevel(testTypeId,testName) {
  
    
      
}
 
function clertDatashow() {
      numTestPointsCompleted = 0;
      numTestPoints = 0;
      fixationLossTotal = 0;
      fixationLossCount = 0;
      falsePosCount = 0;
      falsePosTotal = 0;
      falseNegCount = 0;
      falseNegTotal = 0;
}
 
    
  function removeProgressionAnalysis() {
     $("#progression_analysis_div").remove();
     progression_analysis=false;
   }
   function addProgressionAnalysis(value=false, disable=true) { 
       removeProgressionAnalysis(); 
 
 <?php $flg=(isset($user['Office']['detailed_progression']))?$user['Office']['detailed_progression']:1; ?>
 if(<?php echo $flg ?>){
      var state=''
        if(value==true){
          state='checked';
        }
        $(".mt-checkboxes-eye").prepend("<div class='form-group' id='progression_analysis_div'><input type='checkbox' id='progression_analysis' "+state+" class='progression_analysis' aria-label='...' onchange='progressionanalysis(this)'><label for='progression_analysis'><span type='checkbox' style='font-weight:500;'>Detailed Progression</span></label> </div>");  
        getPrevTest(value);
 }


    
  }
  
   function clea_test_device() {
   deviceId=$("#test-device").val();  
        $.ajax({
          url: "<?php echo WWW_BASE; ?>admin/patients/cleartestdevice",
          type: 'POST', 
          data: {"deviceId": deviceId},
          success: function(data){ 
          }
      });
 }
  function recallLastData(){
    $("#recall_last_data").removeClass("md-btn-desabley");
    $("#recall_last_data").removeClass("md-btn-gry");
    $("#start").removeClass("md-btn-gry");
    $("#start").addClass("md-btn-desabley");
    $("#stop").removeClass("md-btn-desabley");
    $("#pause").removeClass("md-btn-desabley");
    $("#stop").addClass("md-btn-gry");
    $("#pause").addClass("md-btn-gry");
    $("#resume").addClass("md-btn-desabley");
    $("#scroll-myinput").addClass('disabled');
    $("#scroll-myinput1").addClass('disabled');
    $("#scroll-myinput2").addClass('disabled');
    get_deviceData();
  }
  function startTest() {  
    
    $("#recall_last_data").addClass("md-btn-desabley");
    $("#recall_last_data").removeClass("md-btn-gry");
    $("#start").removeClass("md-btn-gry");
    $("#start").removeClass("md-btn-desabley");
    $("#stop").removeClass("md-btn-desabley");
    $("#pause").removeClass("md-btn-desabley");
    $("#stop").addClass("md-btn-gry");
    $("#pause").addClass("md-btn-gry");
    $("#resume").addClass("md-btn-desabley");
    $("#scroll-myinput").addClass('disabled');
    $("#scroll-myinput1").addClass('disabled');
    $("#scroll-myinput2").addClass('disabled');
    get_deviceData();
  }
  function stopTest(type=0) {
    $("#recall_last_data").removeClass("md-btn-desabley");
    $("#recall_last_data").addClass("md-btn-gry");
    $("#stop").removeClass("md-btn-gry");
    $("#start").removeClass("md-btn-desabley");
    $("#stop").addClass("md-btn-desabley"); 
    $("#start").addClass("md-btn-gry");
    $("#pause").addClass("md-btn-desabley");
    $("#resume").addClass("md-btn-desabley");
    $("#scroll-myinput").removeClass('disabled');
    $("#scroll-myinput1").removeClass('disabled');
    $("#scroll-myinput2").removeClass('disabled');
    enableAll();
    if(type==1){
      if((alarm_stop && testBothEyes==false) || (alarm_stop && botheyecount==2)){
        alarm_sound=true;
        $(".dismiss-alarm").attr("style", "visibility:visible; background-color: #d63636 !important;");
         beepdound();
      }
     
      $.ajax({
      url: "<?php echo WWW_BASE; ?>admin/patients/deleteTestStart/3554",
      type: 'POST', 
       data: {"patient_id": '<?php echo $data['Patient']['id'] ?>',"office_id": '<?php echo $user['office_id'] ?>',"deviceId": deviceId}, 
      success: function(data){ 
        }
      });
    }
    connectionStatus=0;
    start_status=0;
    get_deviceDatastop();
  }
  function stopTestforce() {
    $("#recall_last_data").removeClass("md-btn-desabley");
    $("#recall_last_data").addClass("md-btn-gry");;
    $("#stop").addClass("md-btn-gry");
    $("#start").addClass("md-btn-gry");
     $("#start").removeClass("md-btn-desabley");
    $("#stop").addClass("md-btn-desabley"); 
    $("#pause").addClass("md-btn-desabley");
    $("#resume").addClass("md-btn-desabley");
    $("#scroll-myinput").removeClass('disabled');
    $("#scroll-myinput1").removeClass('disabled');
    $("#scroll-myinput2").removeClass('disabled');
    dataCapturedpause=false;
    enableAll();
    connectionStatus=0;
    get_deviceDatastop();
    start_status=0;
  }
   function pauseTest() {
   $("#recall_last_data").addClass("md-btn-desabley");
    $("#pause").removeClass("md-btn-gry");
    $("#resume").removeClass("md-btn-desabley");
    $("#pause").removeClass("md-btn-desabley");
    $("#stop").addClass("md-btn-desabley");
    $("#start").addClass("md-btn-desabley"); 
    $("#resume").addClass("md-btn-gry");
  }
   function resumeTest() {
   $("#recall_last_data").addClass("md-btn-desabley");
    $("#resume").removeClass("md-btn-gry");
    $("#stop").addClass("md-btn-gry");
    $("#stop").removeClass("md-btn-desabley");
    $("#pause").removeClass("md-btn-desabley");
    $("#start").addClass("md-btn-desabley");
    $("#pause").addClass("md-btn-gry"); 
    get_deviceData();
  }

  
  function desableTestName(argument,type,level=1) {
    device_type=type;
  }
  function desableTestName2(level=1,type) { 
   device_type=type;
  }
  function stop_save_yes(argument) {
 
    startTestStatus=6;
    round1=0;
    saveresult=false;
    dataCaptured=true; 
    dataCapturedpause=false;
    
    stopTest();
    TestData(1);
    deviceMessages=[];
  }
   function stop_save_no(argument) {
    round1=0;
    startTestStatus=0;
    saveresult=true;
    dataCaptured=false;
    dataCapturedpause=false; 
    stopTest(1);
    TestData(0);
    deviceMessages=[];
     cleardata=false;
  }
  //function testduration() {
  const testduration = async () => {
    for(;;){
        if(startTestStatus==0){
            break;
        }else{ 
            diffTime = Math.abs(new Date().getTime() - dtStartTime);
            minutes = parseInt(diffTime / (1000 * 60 ));
            seconds = Math.floor((diffTime / (1000 ))-(minutes*60));
            eTime = String(minutes).padStart(2, '0')+":"+String(seconds).padStart(2, '0'); 
            setTestDuration(eTime);
            await sleep(1000);
        }
    }
  }
  function stopalarm() {
   alarm_sound=false;
   $(".dismiss-alarm").attr("style", "visibility:hidden; background-color: #d63636 !important;");
  }
  function setUserPresetselect(argument) { 
      $(".dash-control").each((key, element) => {
    let value = $(element).val(); 
    if(argument==value){
      $(element).addClass("md-btn-desabley"); 
    }else{
       $(element).removeClass("md-btn-desabley");
    }
  });
}
//function beepdound(argument) {
  const beepdound = async () => {
    a=new AudioContext();
    for(;;){
      if(alarm_sound){
          beep(50,1000,100);
          await sleep(1000);
      }else{
          break;   
      }
    }
   
   
    
}
function beep(vol, freq, duration){
  v=a.createOscillator()
  u=a.createGain()
  v.connect(u)
  v.frequency.value=freq
  v.type="Sine"
  u.connect(a.destination)
  u.gain.value=vol*0.01
  v.start(a.currentTime)
  v.stop(a.currentTime+duration*0.001)
}
 
  
  function desableAll() {
     document.getElementById("test-device").disabled = true;
      document.getElementById("language").disabled = true;
      
  }
  function enableAll() {
    document.getElementById("test-device").disabled = false;
       document.getElementById("language").disabled = false;
      
  }
  function CalculateElapsedTime()
  {
    var eTime;

    if (startTime > 0)
    {
       
      diffTime = Math.abs(new Date().getTime() - dtStartTime);
      minutes = parseInt(diffTime / (1000 * 60 ));
      seconds = Math.floor((diffTime / (1000 ))-(minutes*60)); 
      eTime = String(minutes).padStart(2, '0')+":"+String(seconds).padStart(2, '0'); 
    }
    else
    {
      eTime =  "00:00";
    }
    setTestDuration(eTime);
    return eTime; 

  }
  function agegroupf(dob) {
     var  res= dob.split("-");
    if(res[0]<=31){
      dob=res[2]+'-'+res[1]+'-'+res[0];
    }
    console.log(res);
    console.log('dob='+dob);
    var ageDifMs = Date.now() - Date.parse(dob);
    console.log('dob1='+ageDifMs);
    var ageDate = new Date(ageDifMs); // miliseconds from epoch
    console.log('dob2='+ageDate);
    var age=Math.abs(ageDate.getUTCFullYear() - 1970);
     console.log('dob3='+age);
    if ((age > 0) && (age <= 30)){
       agegroup = 1;
     }else if ((age > 30) && (age <= 50)){
        agegroup = 2;
     }else if ((age > 50) && (age <= 70)){
        agegroup = 3;
     }else{
        agegroup = 4;
     } 
  }
  function saveUserPreset() { 
    $.ajax({
          url: "<?php echo WWW_BASE; ?>admin/patients/userpreset",
          type: 'POST', 
          data: {"page":4,"user_preset_text": $("#user-preset-input").val(),"user_preset": user_preset,"cs":cs_test_name,"vs": va_test_name,"fdt": fdt_test_name,'distant_without_correction':va_test_type.distant_without_correction,'distant_with_correction':va_test_type.distant_with_correction,'distant_without_correction_glare':va_test_type.distant_without_correction_glare,'distant_with_correction_glare':va_test_type.distant_with_correction_glare,'closeup_with_correction':va_test_type.closeup_with_correction,'with_glare':va_test_type.with_glare,'va_os':Vs.va_os ,'va_od':Vs.va_od,'color_os':Vs.color_os,'color_od':Vs.color_od,'fdt_os':Vs.fdt_os,'fdt_od':Vs.fdt_od,'cs_os':Vs.cs_os,'cs_od':Vs.cs_od,'va_dwtc_os':Vs.va_dwtc_os,'va_dwtc_od':Vs.va_dwtc_od,'va_dwc_os':Vs.va_dwc_os,'va_dwc_od':Vs.va_dwc_od,'va_dwtc_glare_os':Vs.va_dwtc_glare_os,'va_dwtc_glare_od':Vs.va_dwtc_glare_od,'va_dwc_glare_os':Vs.va_dwc_glare_os,'va_dwc_glare_od':Vs.va_dwc_glare_od,'va_cwc_os':Vs.va_cwc_os,'va_cwc_od':Vs.va_cwc_od,'stereopsis':Vs.stereopsis,"alarm_stop":alarm_stop,"stimulusSize": stimulusSize,"stimulusIntensity": stimulusIntensity,"wallBrightness": wallBrightness,"testSpeed": testSpeed,"audioVolume": audioVolume,"testColour": testColour,"testBackground": testBackground,"voice_instractuin":voice_instractuin},
          success: function(data){ 
          $(".dash-control").each((key, element) => {
              let value = $(element).val(); 
              if(user_preset==value){
                $(element).text($("#user-preset-input").val());
              } 
          });
          }
      }); 
  }
   const setUserPreset = async function(user_preset, y1, val) {
 
  await sleep(40);
       $.ajax({
          url: "<?php echo WWW_BASE; ?>admin/patients/getuserpreset",
          type: 'POST', 
          data: {"page":4,"user_preset": user_preset},
          success: function(data){  
            if(set_user_preset){
              setUserPresetselect(user_preset);
              data=JSON.parse(data)  
                if (data.user_preset_data.hasOwnProperty('UserPresetDatavs')) {  
                voice_instractuin=(data.user_preset_data.UserPresetDatavs.voice_instractuin == "true")? true : false; 
                alarm_stop=(data.user_preset_data.UserPresetDatavs.alarm_stop == "true")? true : false; 
                audioVolume=parseFloat(data.user_preset_data.UserPresetDatavs.audioVolume);
                testSpeed=parseFloat(data.user_preset_data.UserPresetDatavs.testSpeed);
                stimulusIntensity=parseInt(data.user_preset_data.UserPresetDatavs.stimulusIntensity);
                stimulusSize=parseInt(data.user_preset_data.UserPresetDatavs.stimulusSize); 
                testBackground=parseInt(data.user_preset_data.UserPresetDatavs.testBackground);
                testColour=parseInt(data.user_preset_data.UserPresetDatavs.testColour); 
                wallBrightness=parseInt(data.user_preset_data.UserPresetDatavs.wallBrightness); 

               <?php if(in_array(28, $checked_data)){ ?>

                va_test_type.distant_without_correction=(data.user_preset_data.UserPresetDatavs.distant_without_correction == "true")? true : false;
                va_test_type.distant_with_correction=(data.user_preset_data.UserPresetDatavs.distant_with_correction == "true")? true : false;
                va_test_type.distant_without_correction_glare=(data.user_preset_data.UserPresetDatavs.distant_without_correction_glare == "true")? true : false;
                va_test_type.distant_with_correction_glare=(data.user_preset_data.UserPresetDatavs.distant_with_correction_glare == "true")? true : false;
                va_test_type.closeup_with_correction=(data.user_preset_data.UserPresetDatavs.closeup_with_correction == "true")? true : false;
                va_test_type.with_glare=(data.user_preset_data.UserPresetDatavs.with_glare == "true")? true : false;
                 // document.getElementById("test-type").value = testType;

                 if(va_test_type.distant_without_correction==true){
                     $(".va_dwtc_div").css("visibility", "visible");
                      Vs.va_dwtc_os=(data.user_preset_data.UserPresetDatavs.va_dwtc_os == "true")? true : false;  
                      Vs.va_dwtc_od=(data.user_preset_data.UserPresetDatavs.va_dwtc_od == "true")? true : false;
                      $("#va_dwtc_os_check").prop("checked", Vs.va_dwtc_os);
                      $("#va_dwtc_od_check").prop("checked", Vs.va_dwtc_od);
                 }else{
                    Vs.va_dwtc_os=false;  
                    Vs.va_dwtc_od=false;
                    $(".va_dwtc_div").css("visibility", "hidden"); 
                 }
                 if(va_test_type.distant_with_correction==true){
                     $(".va_dwc_div").css("visibility", "visible");
                      Vs.va_dwc_os=(data.user_preset_data.UserPresetDatavs.va_dwc_os == "true")? true : false;  
                      Vs.va_dwc_od=(data.user_preset_data.UserPresetDatavs.va_dwc_od == "true")? true : false;
                      $("#va_dwc_os_check").prop("checked", Vs.va_dwc_os);
                      $("#va_dwc_od_check").prop("checked", Vs.va_dwc_od);
                 }else{
                    Vs.va_dwc_os=false;  
                    Vs.va_dwc_od=false;
                    $(".va_dwc_div").css("visibility", "hidden"); 
                 }
                 if(va_test_type.distant_without_correction_glare==true){
                     $(".va_dwtc_glare_div").css("visibility", "visible");
                      Vs.va_dwtc_glare_os=(data.user_preset_data.UserPresetDatavs.va_dwtc_glare_os == "true")? true : false;  
                      Vs.va_dwtc_glare_od=(data.user_preset_data.UserPresetDatavs.va_dwtc_glare_od == "true")? true : false;
                      $("#va_dwtc_glare_os_check").prop("checked", Vs.va_dwtc_glare_os);
                      $("#va_dwtc_glare_od_check").prop("checked", Vs.va_dwtc_glare_od);
                 }else{
                    Vs.va_dwtc_glare_os=false;  
                    Vs.va_dwtc_glare_od=false;
                    $(".va_dwtc_glare_div").css("visibility", "hidden"); 
                 }
                 if(va_test_type.distant_with_correction_glare==true){
                     $(".va_dwc_glare_div").css("visibility", "visible");
                      Vs.va_dwc_glare_os=(data.user_preset_data.UserPresetDatavs.va_dwc_glare_os == "true")? true : false;  
                      Vs.va_dwc_glare_od=(data.user_preset_data.UserPresetDatavs.va_dwc_glare_od == "true")? true : false;
                      $("#va_dwc_glare_os_check").prop("checked", Vs.va_dwc_glare_os);
                      $("#va_dwc_glare_od_check").prop("checked", Vs.va_dwc_glare_od);
                 }else{
                    Vs.va_dwc_glare_os=false;  
                    Vs.va_dwc_glare_od=false;
                    $(".va_dwc_glare_div").css("visibility", "hidden"); 
                 }
                  if(va_test_type.closeup_with_correction==true){
                     $(".va_cwc_div").css("visibility", "visible");
                      Vs.va_cwc_os=(data.user_preset_data.UserPresetDatavs.va_cwc_os == "true")? true : false;  
                      Vs.va_cwc_od=(data.user_preset_data.UserPresetDatavs.va_cwc_od == "true")? true : false;
                      $("#va_cwc_os_check").prop("checked", Vs.va_cwc_os);
                      $("#va_cwc_od_check").prop("checked", Vs.va_cwc_od);
                 }else{
                    Vs.va_cwc_os=false;  
                    Vs.va_cwc_od=false;
                    $(".va_cwc_div").css("visibility", "hidden"); 
                 }

                   
                $("#Distant_without_correction").prop("checked", va_test_type.distant_without_correction); 
                $("#Distant_with_correction").prop("checked", va_test_type.distant_with_correction); 
                $(".voice_instructions").prop("checked", voice_instractuin); 
                $(".alarm-stop").prop("checked", alarm_stop);
                $("#Distant_without_correction_glare").prop("checked", va_test_type.distant_without_correction_glare); 
                $("#Distant_with_correction_glare").prop("checked", va_test_type.distant_with_correction_glare); 
                $("#Closeup_with_correction").prop("checked", va_test_type.closeup_with_correction); 
                $("#With_Glare").prop("checked", va_test_type.with_glare); 
                va_test_name=data.user_preset_data.UserPresetDatavs.vs
                
                document.getElementById("vas").value = va_test_name;
              <?php } ?>

               <?php if(in_array(26, $checked_data)){ ?> 
                Vs.color_os=(data.user_preset_data.UserPresetDatavs.color_os == "true")? true : false;  
                Vs.color_od=(data.user_preset_data.UserPresetDatavs.color_od == "true")? true : false;
                $("#color_os_check").prop("checked", Vs.color_os);
                $("#color_od_check").prop("checked", Vs.color_od);
                
               <?php } ?>

               <?php if(in_array(27, $checked_data)){ ?> 
                Vs.stereopsis=(data.user_preset_data.UserPresetDatavs.stereopsis == "true")? true : false;   
                $(".stereopsis").prop("checked", Vs.color_od);
                
               <?php } ?>
               <?php if(in_array(15, $checked_data)){ ?> 
                Vs.fdt_os=(data.user_preset_data.UserPresetDatavs.fdt_os == "true")? true : false; 
                Vs.fdt_od=(data.user_preset_data.UserPresetDatavs.fdt_od == "true")? true : false;  
                $("#fdt_os_check").prop("checked", Vs.fdt_os);
                $("#fdt_od_check").prop("checked", Vs.fdt_od); 
                
                if(Vs.fdt_os==true || Vs.fdt_od){
                  fdt_test_name=data.user_preset_data.UserPresetDatavs.fdt;
                  document.getElementById("fdts").style.visibility = "visible"; 
                   document.getElementById("fdts").value = fdt_test_name;
                }else{
                  fdt_test_name='';
                  document.getElementById("fdts").style.visibility = "hidden"; 
                }
                
               <?php } ?>

               <?php if(in_array(18, $checked_data)){ ?> 
                Vs.cs_os=(data.user_preset_data.UserPresetDatavs.cs_os == "true")? true : false; 
                Vs.cs_od=(data.user_preset_data.UserPresetDatavs.cs_od == "true")? true : false;  
                $("#cs_os_check").prop("checked", Vs.cs_os);
                $("#cs_od_check").prop("checked", Vs.cs_od); 
               
               <?php } ?>

              // 
                   
                $("#myinput").val(stimulusSize); 
                $("#myinput-val").html(stimulusSize); 
                $("#setting-stm-size").html(stimulusSize); 
                $("#myinput-1").val(stimulusIntensity*100/48);
                $("#myinput-1-val").html(stimulusIntensity);

                $("#myinput-2").val(wallBrightness*100/96);
                $("#myinput-2-val").html(wallBrightness);
                $("#setting-bkg-color").html(wallBrightness);

                $("#myinput-3").val((testSpeed-sliderTestSpeed_minValue)*(100/(sliderTestSpeed_maxValue-sliderTestSpeed_minValue)));

                $("#myinput-3-val").html(testSpeed);
                $("#setting-speed").html(testSpeed);

                $("#myinput-4").val(audioVolume*100);
                $("#myinput-4-val").html(audioVolume);  

                document.getElementById("myinput").style.background = 'linear-gradient(to right, #f5f5f5 0%, #f5f5f5 ' + (stimulusSize)*10 + '%, #d6d6d6 ' + stimulusSize + '%, #d6d6d6 100%)';

                document.getElementById("myinput-1").style.background = 'linear-gradient(to right, #f5f5f5 0%, #f5f5f5 ' + (stimulusIntensity*100/48) + '%, #d6d6d6 ' + (stimulusIntensity*100/48) + '%, #d6d6d6 100%)';

                document.getElementById("myinput-2").style.background = 'linear-gradient(to right, #f5f5f5 0%, #f5f5f5 ' + wallBrightness*100/96 + '%, #d6d6d6 ' + wallBrightness*100/96 + '%, #d6d6d6 100%)';

                document.getElementById("myinput-3").style.background = 'linear-gradient(to right, #f5f5f5 0%, #f5f5f5 ' + (testSpeed-sliderTestSpeed_minValue)*(100/(sliderTestSpeed_maxValue-sliderTestSpeed_minValue)) + '%, #d6d6d6 ' + (testSpeed-sliderTestSpeed_minValue)*(100/(sliderTestSpeed_maxValue-sliderTestSpeed_minValue)) + '%, #d6d6d6 100%)';

                document.getElementById("myinput-4").style.background = 'linear-gradient(to right, #f5f5f5 0%, #f5f5f5 ' + audioVolume*100 + '%, #d6d6d6 ' + audioVolume*100 + '%, #d6d6d6 100%)';

                var col='W'
                switch(testColour){
                    case 0:
                     col='W' 
                    break;
                    case 1:
                     col='R'  
                    break;
                    case 2:
                     col='G'  
                    break;
                    case 3:
                     col='B'  
                    break;
                    default:
                     col='W'  
                    break;
                  } 
                 $(".stimulus-color").each((key, element) => {
                let value = $(element).text(); 
                if(col==value){
                  $(element).addClass("colour-selected"); 
                }else{
                   $(element).removeClass("colour-selected");
                }
              });

                var colb='B'
                switch(testBackground){
                    case 0:
                     colb='B' 
                    break;
                    case 1:
                     colb='Y'  
                    break; 
                    default:
                     colb='B'  
                    break;
                  } 
                 $(".background-color").each((key, element) => {
                let value = $(element).text(); 
                if(colb==value){
                  $(element).addClass("colour-selected"); 
                }else{
                   $(element).removeClass("colour-selected");
                }
              });



                } 
            }
          }
      });
  }
 
 
function voiceinstructions(obj) {
  if($(obj).is(":checked")){
   voice_instractuin=true;
  }else{
    voice_instractuin=false;
  } 
}
function progressionanalysis(obj) {
  if($(obj).is(":checked")){
   progression_analysis=true;
  }else{
    progression_analysis=false;
  } 
}


 
function alarmstop(obj) {
  if($(obj).is(":checked")){
   alarm_stop=true;
  }else{
    alarm_stop=false;
  } 
}

function Distant_without_correction(obj) {
  if($(obj).is(":checked")){
   va_test_type.distant_without_correction=true;
    $(".va_dwtc_div").css("visibility", "visible");
    Vs.va_dwtc_os=true;  
    Vs.va_dwtc_od=true;
    $(".va_dwtc_check").prop("checked", true);
     
   // va_test_type.distant_with_correction=false;
   // va_test_type.closeup_with_correction=false;
  }else{
    Vs.va_dwtc_os=false;  
    Vs.va_dwtc_od=false;
     $(".va_dwtc_div").css("visibility", "hidden");
    va_test_type.distant_without_correction=false;
  } 
}
function Distant_without_correction_glare(obj) {
  
  if($(obj).is(":checked")){
    
   va_test_type.distant_without_correction_glare=true;
    $(".va_dwtc_glare_div").css("visibility", "visible");
    Vs.va_dwtc_glare_os=true;  
    Vs.va_dwtc_glare_od=true;
    $(".va_dwtc_glare_check").prop("checked", true); 
  }else{
  
    Vs.va_dwtc_glare_os=false;  
    Vs.va_dwtc_glare_od=false;
     $(".va_dwtc_glare_div").css("visibility", "hidden");
    va_test_type.distant_without_correction_glare=false;
  } 
}
function Distant_with_correction(obj) {
  if($(obj).is(":checked")){
   // va_test_type.distant_without_correction=false;
   va_test_type.distant_with_correction=true;
    $(".va_dwc_div").css("visibility", "visible");
    Vs.va_dwc_os=true;  
    Vs.va_dwc_od=true;
    $(".va_dwc_check").prop("checked", true);
  // va_test_type.closeup_with_correction=false;
  }else{
     Vs.va_dwc_os=false;  
    Vs.va_dwc_od=false;
     $(".va_dwc_div").css("visibility", "hidden");
    va_test_type.distant_with_correction=false;
  } 
}
function Distant_with_correction_glare(obj) {
  if($(obj).is(":checked")){
   // va_test_type.distant_without_correction=false;
   va_test_type.distant_with_correction_glare=true;
    $(".va_dwc_glare_div").css("visibility", "visible");
    Vs.va_dwc_glare_os=true;  
    Vs.va_dwc_glare_od=true;
    $(".va_dwc_glare_check").prop("checked", true);
  // va_test_type.closeup_with_correction=false;
  }else{
     Vs.va_dwc_glare_os=false;  
    Vs.va_dwc_glare_od=false;
     $(".va_dwc_glare_div").css("visibility", "hidden");
    va_test_type.distant_with_correction_glare=false;
  } 
}

function closeup_with_correction(obj) { 
  if($(obj).is(":checked")){
   //  va_test_type.distant_without_correction=false;
   // va_test_type.distant_with_correction=false;
   va_test_type.closeup_with_correction=true; 
   $(".va_cwc_div").css("visibility", "visible");
   Vs.va_cwc_os=true;  
    Vs.va_cwc_od=true;
    $(".va_cwc_check").prop("checked", true);
  }else{
     Vs.va_cwc_os=false;  
    Vs.va_cwc_od=false;
     $(".va_cwc_div").css("visibility", "hidden");
    va_test_type.closeup_with_correction=false;
  } 
}

function with_glare(obj) {
  if($(obj).is(":checked")){
   va_test_type.with_glare=true;
  }else{
    va_test_type.with_glare=false;
  } 
}
 
function setFixationLosses(argument) {
   $("#fixation-losses").html(argument);
}
function setFalsePositive(argument) {
   $("#false-positive").html(argument);
}
function setFalseNigative(argument) {
   $("#false-nigative").html(argument);
}

function setTestStatus(argument) {
   $("#setting-status").html(argument);
   setting_alert_new(argument);
}
function setTestDuration(argument) {
   $("#setting-duration").html(argument);
}
function setTestQuestions(argument) {
   $("#setting-questions").html(argument);
}
function setTestReliability(argument) {
   $("#setting-reliability").html(argument);
}
function setReliabilitybtn(argument) {
    document.getElementById("setting-reliability-btn").style.background = argument;
}
function reloadPageButton() { 
   $(".reload_page").attr("style", "width:auto;width:auto; margin-top:5px; margin-botom:5px; padding-top:1px; padding-bottom:1px; padding-left:6px; padding-right:6px;  background-color: #ea3b3b;");
     
}
function deviceuse(staff_id, user_id, device_id) {
  $('#deviceUseModel').modal("show"); 
  if(staff_id==user_id){
      $("#clear-device-yes").attr("style", "visibility:visible");
      $("#clear-device-no").attr("style", "visibility:visible");
      $("#clear-device-yes-msg").attr("style", "visibility:visible");
      $("#clear-device-ok").attr("style", "visibility:hidden");
      
  }else{
    $("#clear-device-yes").attr("style", "visibility:hidden");
      $("#clear-device-no").attr("style", "visibility:hidden");
      $("#clear-device-yes-msg").attr("style", "visibility:hidden");
      $("#clear-device-ok").attr("style", "visibility:visible");
  }
  
}

   const checkpausestatus = async function(count) {  
       await sleep(1000*60*15); 
      if(pausecount==count){ 
        dataCaptured=false;
        dataCapturedpause=false;
        startTestStatus=0;
        saveresult=true;
        stopTest();
        TestData(0);
        deviceMessages=[];
      }
  }
  function testStatus(value) {
    switch(value){
          case 0:
          // $("#msg-mopdel-span").html('Test Stoped');
          // $('#mymsgModal').modal("show"); 
          break;
          case 1:
         /* $("#msg-mopdel-span").html("Don't close the page while the testing in progress, stop the test before leaveing the page.");
          $('#mymsgModal').modal("show"); */
          //setting_alert("Make sure the Headset is On and the VF2000 app is running. Press the clicker button a few times to wake up the device and start the test. Do not exit this page while the test is in progress.");
          break;
          case 2:
          // $("#msg-mopdel-span").html('Test Paused');
          // $('#mymsgModal').modal("show"); 
          break;
          case 3:
          // $("#msg-mopdel-span").html('Test Resume');
          // $('#mymsgModal').modal("show"); 
          break; 
      }
  } 
     const setting_alert = async function(msg) { 
     $(".setting-alert-message").html(msg);
      await sleep(5000);
      $(".setting-alert-message").html("");
  }
  const setting_alert2 = async function(msg, type=0) { 
     $(".setting-alert-message2").html(msg);
     if(type==0){
      await sleep(2000);
      $(".setting-alert-message2").html("");
    }
  }
  const setting_alert3 = async function(msg, time=30000) { 
     $(".setting-alert-message2").html(msg); 
      // await sleep(time);
      // $(".setting-alert-message2").html(""); 
  }
  const setting_alert_new = async function(msg) { 
     $(".setting-alert-message").html(msg); 
  }
  const setting_alert_new2 = async function(msg,time) { 
     $(".setting-alert-message").html(msg);
      await sleep(time);
      $(".setting-alert-message").html("");
  }
  
  const setting_alert_btn_press = async function(msg) { 
    
  $("#connection-help").attr("style", "color:#ffffff; visibility:hidden");
  $("#connection-verify").addClass('md-btn-yellow');
   $("#connection-verify").html(msg); 
  $("#connection-verify").attr("style", "color:#ffffff; visibility:visible");
  await sleep(2000);
  $("#connection-verify").attr("style", "color:#ffffff; visibility:hidden");
 
  }
  function checkStatus() { 
    //if ((dataCaptured)|| (dataCapturedpause)){
 if(saveresult==false){
     var r = confirm("Report has not been saved and the last test results will be lost. Do you want to switch the device ?");
     if (r == true) {
      dataCaptured=false;
      saveresult=true;
      deviceMessages=[];
      stopTestforce(); 
      document.getElementById("view-pdf-url").style.visibility = "hidden"; 
      updateDevice();
     // addProgressionAnalysis();
    } else {
     document.getElementById("test-device").value = deviceId;
  }
}else{
  document.getElementById("view-pdf-url").style.visibility = "hidden"; 
  updateDevice();
 // addProgressionAnalysis();
}
  }
   function changetest(obj, name) {
    a=true;
    if($(obj).is(":checked")){
       a=true;
    }else{
      a=false;
    }
    if(name=='va_os'){
      Vs.va_os=a;
      if(a==true && Vs.va_od==false){
          Vs.va_od=a;
           $(".vs_check").prop("checked", a);
      }
    }
    if(name=='va_od'){
      Vs.va_od=a;
      if(a==true && Vs.va_os==false){
          Vs.va_os=a;
           $(".vs_check").prop("checked", a);
      }
    }
    if(name=='color_os'){
      Vs.color_os=a;
      if(a==true && Vs.color_od==false){
          // Vs.color_od=a;
          //  $(".color_check").prop("checked", a);
      }
    }
    if(name=='color_od'){
      Vs.color_od=a;
      if(a==true && Vs.color_os==false){
          // Vs.color_os=a;
          //  $(".color_check").prop("checked", a);
      }
    }
    if(name=='fdt_os'){
      Vs.fdt_os=a; 
       if(a==true && Vs.fdt_od==false){
          // Vs.fdt_od=a;
          //  $(".fdt_check").prop("checked", a);
      }
    }
    if(name=='fdt_od'){
      Vs.fdt_od=a;
       if(a==true && Vs.fdt_os==false){
          // Vs.fdt_os=a;
          //  $(".fdt_check").prop("checked", a);
      }
    }

    if(name=='cs_os'){
      Vs.cs_os=a; 
       if(a==true && Vs.cs_od==false){
          // Vs.cs_od=a;
          // $(".cs_check").prop("checked", a);
      }
    }
    if(name=='cs_od'){
      Vs.cs_od=a;
       if(a==true && Vs.cs_os==false){
          // Vs.cs_os=a;
          //  $(".cs_check").prop("checked", a);
      }
    }

    if(name=='va_dwtc_os'){
      Vs.va_dwtc_os=a; 
       if(a==true && Vs.va_dwtc_od==false){
          Vs.va_dwtc_od=a;
           $(".va_dwtc_check").prop("checked", a);
      }
    }
    if(name=='va_dwtc_od'){
      Vs.va_dwtc_od=a;  
       if(a==true && Vs.va_dwtc_os==false){
          Vs.va_dwtc_os=a;
           $(".va_dwtc_check").prop("checked", a);
      }
    }
    if(name=='va_dwtc_glare_os'){
      Vs.va_dwtc_glare_os=a; 
       if(a==true && Vs.va_dwtc_glare_od==false){
          Vs.va_dwtc_glare_od=a;
           $(".va_dwtc_glare_check").prop("checked", a);
      }
    }
    if(name=='va_dwtc_glare_od'){
      Vs.va_dwtc_glare_od=a;  
       if(a==true && Vs.va_dwtc_glare_os==false){
          Vs.va_dwtc_os=a;
           $(".va_dwtc_glare_check").prop("checked", a);
      }
    }
    if(name=='va_dwc_glare_os'){
      Vs.va_dwc_glare_os=a; 
       if(a==true && Vs.va_dwc_glare_od==false){
          Vs.va_dwc_glare_od=a;
           $(".va_dwc_glare_check").prop("checked", a);
      }
    }
    if(name=='va_dwc_glare_od'){
      Vs.va_dwc_glare_od=a;  
       if(a==true && Vs.va_dwc_glare_os==false){
          Vs.va_dwc_os=a;
           $(".va_dwc_glare_check").prop("checked", a);
      }
    }
    


    if(name=='va_dwc_os'){
      Vs.va_dwc_os=a; 
       if(a==true && Vs.va_dwc_od==false){
          Vs.va_dwc_od=a;
           $(".va_dwc_check").prop("checked", a);
      }
    }
    if(name=='va_dwc_od'){
      Vs.va_dwc_od=a;  
       if(a==true && Vs.va_dwc_os==false){
          Vs.va_dwc_os=a;
           $(".va_dwc_check").prop("checked", a);
      }
    }
    if(name=='va_cwc_os'){
      Vs.va_cwc_os=a; 
       if(a==true && Vs.va_cwc_od==false){
          Vs.va_cwc_od=a;
           $(".va_cwc_check").prop("checked", a);
      }
    }
    if(name=='va_cwc_od'){
      Vs.va_cwc_od=a;  
       if(a==true && Vs.va_cwc_os==false){
          Vs.va_cwc_os=a;
           $(".va_cwc_check").prop("checked", a);
      }
    }



    if(name=='stereopsis'){
     $(".stereopsis").prop("checked", a);    
      Vs.stereopsis=a;
    }
    if(name=='da_os'){
      Vs.da_os=a;
    }
    if(name=='da_od'){
      Vs.da_od=a;
    }
    if(name=='vf_os'){
      Vs.vf_os=a;
    }
    if(name=='vf_od'){
      Vs.vf_od=a;
    }
    if(name=='cont_os'){
      Vs.cont_os=a;
    }
    if(name=='cont_od'){
      Vs.cont_od=a;
    }
    if(name=='ref_os'){
      Vs.ref_os=a;
    }
    if(name=='ref_od'){
      Vs.ref_od=a;
    }

    // dynamic sub test name
    if(name=='va_os' || name=='va_od'){
      vs_suboption(Vs.va_os,Vs.va_od);
    } 
     if(name=='fdt_os' || name=='fdt_od'){
      fdt_suboption(Vs.fdt_os,Vs.fdt_od);
    } 
     if(name=='cs_os' || name=='cs_od'){
      cs_suboption(Vs.cs_os,Vs.cs_od);
    } 
    getPrevTest(Vs.fdt_os,Vs.fdt_od);
    console.log(a);
    console.log(name);
     console.log(Vs);
   }
    function checkbotheye(name) {
     // body...
     a=true;
     if(name=='cs'){
         if(testnameval.cs==true){
             a=false;
         }else{
             a=true;
         }
         testnameval.cs=a
        Vs.cs_os=a; 
        Vs.cs_od=a;  
        $(".cs_check").prop("checked", a); 
         cs_suboption(Vs.cs_os,Vs.cs_od);
     }else if(name=='fdt'){
         if(testnameval.fdt==true){
             a=false;
         }else{
             a=true;
         }
         testnameval.fdt=a
        Vs.fdt_os=a; 
        Vs.fdt_od=a;  
        $(".fdt_check").prop("checked", a); 
        fdt_suboption(Vs.fdt_os,Vs.fdt_od);
     }else if(name=='color'){ 
         if(testnameval.color==true){
             a=false;
         }else{
             a=true;
         }
         testnameval.color=a
        Vs.color_os=a; 
        Vs.color_od=a;  
        $(".color_check").prop("checked", a); 

     }else if(name=='stereopsis'){ 
         if(testnameval.stereopsis==true){
             a=false;
         }else{
             a=true;
         }
         testnameval.stereopsis=a
     $(".stereopsis").prop("checked", a);    
      Vs.stereopsis=a;
    }
   }
  function updateSpeed(min=0.4,max=1.2,value=0.5) {
    document.getElementById("myinput-3").style.background = 'linear-gradient(to right, #f5f5f5 0%, #f5f5f5 ' + (value-min)*(100/(max-min)) + '%, #d6d6d6 ' + (value-min)*(100/(max-min)) + '%, #d6d6d6 100%)';

 $("#myinput-3").val((value-min)*(100/(max-min)));

                $("#myinput-3-val").html(value);
                $("#setting-speed").html(value);
  }
  function updateDevice() {
     var language=$("#language").val();
     deviceId=$("#test-device").val();
     if(deviceId!=null && deviceId!=0 && deviceId!=''){
     $.ajax({
      url: "<?php echo WWW_BASE; ?>admin/patients/updateDefoult/3554",
      type: 'POST', 
       data: {"page": 4,"deviceId": deviceId,"languageId": language}, 
      success: function(data){ 
        data=JSON.parse(data)  
            if($("#test-type").val()==2){
                desableTestName(data.masterdata,data.device.TestDevice.device_type,data.device.TestDevice.device_level);
            }else{
               desableTestName2(data.device.TestDevice.device_level,data.device.TestDevice.device_type);
            }
        }
      }); 
 }
  }
  function TestData(type=0) {
    console.log(cleardata);
    $.ajax({
      url: "<?php echo WWW_BASE; ?>admin/patients/checkdevicestatus/3554",
      type: 'POST',

      data: {"deviceId": deviceId}, 
       error: function (request, status, error) {
        
         dataCaptured=false;
         restartdata=false;
         saveresult=true;
         stopTest();
         if(request.status==403){
           setting_alert('Something wrong. Please try again.');
           // window.location.replace('');
           reloadPageButton();
         }else{
            setting_alert('Something wrong. Please try again');
         }
          
    },
      success: function(data){
        if(data==1 && startTestStatus==1){
          setting_alert('Device busy try again');
          stopTestforce();
          dataCaptured=false;
        }else{


    var language=$("#language").val();

    if((Vs.fdt_os==true || Vs.fdt_od==true) && fdt_test_name=='FDT'){

      $.ajax({
      url: "<?php echo WWW_BASE; ?>admin/masterReports/testdata/3512",
      type: 'GET',
 
      data: {"ageGroup": agegroup, "testTypeName": 'N30',"patient_id": '<?php echo $data['Patient']['id'] ?>', "testType": 1, "eye":selectEye,"deviceId": deviceId },
      error: function (request, status, error) {
        
         dataCaptured=false;
         restartdata=false;
         saveresult=true;
         stopTest();
         if(request.status==403){
            setting_alert('Something wrong. Please try again.');
           // window.location.replace('');
           reloadPageButton();
         }else{
            setting_alert('Something wrong. Please try again');
         }
          
    },
      success: function(data){
            
        data=JSON.parse(data) 
        var arr=data.VfMasterdata;  


 MasterRecordData.age_group=(data.hasOwnProperty('Masterdata'))?data.Masterdata.age_group.toString():"";
        MasterRecordData.numpoints=(data.hasOwnProperty('Masterdata'))?data.Masterdata.numpoints:"";
        MasterRecordData.color=(data.hasOwnProperty('Masterdata'))?data.Masterdata.color.toString():"";
        MasterRecordData.backgroundcolor=(data.hasOwnProperty('Masterdata'))?data.Masterdata.backgroundcolor.toString():"";
        MasterRecordData.stmSize=(data.hasOwnProperty('Masterdata'))?parseInt(data.Masterdata.stmsize):0;
        MasterRecordData.master_key=(data.hasOwnProperty('Masterdata'))?data.Masterdata.master_key.toString():'';
        MasterRecordData.created_date=(data.hasOwnProperty('Masterdata'))?data.Masterdata.created.toString():'';  
        MasterRecordData.test_color_fg=parseInt(testBackground);
        MasterRecordData.test_color_bg=parseInt(testColour);  
        MasterRecordData.publicList=(data.hasOwnProperty('VfMasterdata'))?data.VfMasterdata:null;   
        
         MasterRecordData.test_color_fg=parseInt(testBackground);
        MasterRecordData.test_color_bg=parseInt(testColour); 
        
          StartTestData.staff_name='<?php echo $user['first_name']." ".$user['last_name'];?>';
          StartTestData.staff_id='<?php echo $user['id']; ?>';  
          StartTestData.backgroundcolor=parseInt(wallBrightness);
          StartTestData.DisplaySelect=parseInt(DisplaySelect);
          StartTestData.autoPtosisReport=autoPtosisReport;            
          StartTestData.PTOSIS_INDEX=(ptosisReportIndex==1)?true:false; 
          StartTestData.zoomLevel=parseFloat(zoomLevel);
          StartTestData.Patient_Name='<?php echo $data['Patient']['first_name'];?>'+' '+'<?php echo $data['Patient']['middle_name'];?>'+' '+'<?php echo $data['Patient']['last_name'];?>';
          StartTestData.DOB='<?php echo $data['Patient']['dob'];?>';
          StartTestData.pid='<?php echo $data['Patient']['id'];?>';
          StartTestData.OfficePateintID='<?php echo $data['Patient']['id_number'];?>';
          StartTestData.od_left='<?php echo $data['Patient']['od_left'];?>';
          StartTestData.od_right='<?php echo $data['Patient']['od_right'];?>';
          StartTestData.os_left='<?php echo $data['Patient']['os_left'];?>';
          StartTestData.os_right='<?php echo $data['Patient']['os_right'];?>'; 
          StartTestData.REACTION_TIME=measureReactionTime; 
          StartTestData.LANGUAGE_SEL= language.toString();  
          StartTestData.TEST_SPEED= parseFloat(testSpeed);
          StartTestData.VOLUME= parseFloat(audioVolume);
          StartTestData.STM_SIZE=parseInt(stimulusSize);
          StartTestData.STM_INTENSITY=Math.round(stimulusIntensity).toString();
          StartTestData.WALL_COLOR= testBackground.toString();
          StartTestData.BKG_INTENSITY= wallBrightness.toString();
          StartTestData.TEST_COLOR= testColour.toString();
          StartTestData.PID= "'<?php echo $data['Patient']['id'];?>'";
          StartTestData.START= startTestStatus; //0=stop; 1=start; 2=pause; 3=resume
          StartTestData.voice_instractuin= voice_instractuin;
          StartTestData.progression_analysis= progression_analysis;
          StartTestData.vision_screening=Vs;
          StartTestData.fdt_test_name= fdt_test_name;
          StartTestData.cs_test_name= cs_test_name;
          StartTestData.va_test_name= va_test_name;
          StartTestData.va_test_type=va_test_type;


         
              var obj = {  
                previous_test:[],
                StartTest:StartTestData,
                MasterRecord:MasterRecordData, 
                MasterRecordList:MasterRecordList,
              }; 
           
        console.log(MasterRecordData);
var myJSON = JSON.stringify(obj);  
$.ajax({
      url: "<?php echo WWW_BASE; ?>admin/patients/addTestStart2/3554",
      type: 'POST',

       data: {"page": 4,"testType":testType,"strategy":testSubType,"test_name":testTypeName,"patient_id": '<?php echo $data['Patient']['id'] ?>',"office_id": '<?php echo $user['office_id'] ?>',"deviceId": deviceId,"languageId": language, "TestStatus": startTestStatus, "testData": myJSON },
        error: function (request, status, error) {
        
         dataCaptured=false;
         restartdata=false;
         saveresult=true;
         stopTest();
         if(request.status==403){
           setting_alert('Something wrong. Please try again.'); 
           reloadPageButton();
         }else{
            setting_alert('Something wrong. Please try again');
         }
          
    }, 
      success: function(data){
        deviceMessages=[];
        start_status=1;
        data=JSON.parse(data); 
        if(data.status==2){ 
          deviceuse(data.staff_id,'<?php echo $user['id'] ?>',deviceId);
          stopTestforce();
          dataCaptured=false;
        }else{
           if(type==1){
           cleardata=true;
          }else{
            cleardata=false;
          }
           console.log('end1='+cleardata);
          if(startTestStatus==1){
            startTime=0;
            dtStartTime=new Date().getTime();
          get_deviceData();

          }
          if(startTestStatus==5 || startTestStatus==6){
            
             get_deviceData();
          }
          testStatus(startTestStatus);  
        }
        }
      }); 

    }
  });

    }else{
      MasterRecordData.test_color_fg=parseInt(testBackground);
        MasterRecordData.test_color_bg=parseInt(testColour); 
        
          StartTestData.staff_name='<?php echo $user['first_name']." ".$user['last_name'];?>';
          StartTestData.staff_id='<?php echo $user['id']; ?>';  
          StartTestData.backgroundcolor=parseInt(wallBrightness);
          StartTestData.DisplaySelect=parseInt(DisplaySelect);
          StartTestData.autoPtosisReport=autoPtosisReport;            
          StartTestData.PTOSIS_INDEX=(ptosisReportIndex==1)?true:false; 
          StartTestData.zoomLevel=parseFloat(zoomLevel);
          StartTestData.Patient_Name='<?php echo $data['Patient']['first_name'];?>'+' '+'<?php echo $data['Patient']['middle_name'];?>'+' '+'<?php echo $data['Patient']['last_name'];?>';
          StartTestData.DOB='<?php echo $data['Patient']['dob'];?>';
          StartTestData.pid='<?php echo $data['Patient']['id'];?>';
          StartTestData.OfficePateintID='<?php echo $data['Patient']['id_number'];?>';
          StartTestData.od_left='<?php echo $data['Patient']['od_left'];?>';
          StartTestData.od_right='<?php echo $data['Patient']['od_right'];?>';
          StartTestData.os_left='<?php echo $data['Patient']['os_left'];?>';
          StartTestData.os_right='<?php echo $data['Patient']['os_right'];?>'; 
          StartTestData.REACTION_TIME=measureReactionTime; 
          StartTestData.LANGUAGE_SEL= language.toString();  
          StartTestData.TEST_SPEED= parseFloat(testSpeed);
          StartTestData.VOLUME= parseFloat(audioVolume);
          StartTestData.STM_SIZE=parseInt(stimulusSize);
          StartTestData.STM_INTENSITY=Math.round(stimulusIntensity).toString();
          StartTestData.WALL_COLOR= testBackground.toString();
          StartTestData.BKG_INTENSITY= wallBrightness.toString();
          StartTestData.TEST_COLOR= testColour.toString();
          StartTestData.PID= "'<?php echo $data['Patient']['id'];?>'";
          StartTestData.START= startTestStatus; //0=stop; 1=start; 2=pause; 3=resume
          StartTestData.voice_instractuin= voice_instractuin;
          StartTestData.progression_analysis= progression_analysis;
          StartTestData.vision_screening=Vs;
          StartTestData.fdt_test_name= fdt_test_name;
          StartTestData.cs_test_name= cs_test_name;
          StartTestData.va_test_name= va_test_name;
          StartTestData.va_test_type=va_test_type;


         
              var obj = {  
                previous_test:[],
                StartTest:StartTestData,
                MasterRecord:MasterRecordData, 
                MasterRecordList:MasterRecordList,
              }; 
           
        console.log(obj);
var myJSON = JSON.stringify(obj);  
$.ajax({
      url: "<?php echo WWW_BASE; ?>admin/patients/addTestStart2/3554",
      type: 'POST',

       data: {"page": 4,"testType":testType,"strategy":testSubType,"test_name":testTypeName,"patient_id": '<?php echo $data['Patient']['id'] ?>',"office_id": '<?php echo $user['office_id'] ?>',"deviceId": deviceId,"languageId": language, "TestStatus": startTestStatus, "testData": myJSON },
        error: function (request, status, error) {
        
         dataCaptured=false;
         restartdata=false;
         saveresult=true;
         stopTest();
         if(request.status==403){
           setting_alert('Something wrong. Please try again.'); 
           reloadPageButton();
         }else{
            setting_alert('Something wrong. Please try again');
         }
          
    }, 
      success: function(data){
        deviceMessages=[];
        start_status=1;
        data=JSON.parse(data); 
        if(data.status==2){ 
          deviceuse(data.staff_id,'<?php echo $user['id'] ?>',deviceId);
          stopTestforce();
          dataCaptured=false;
        }else{
          if(type==1){
           cleardata=true;
          }else{
            cleardata=false;
          }
          console.log('end2='+cleardata);
          if(startTestStatus==1){
            startTime=0;
            dtStartTime=new Date().getTime();
          get_deviceData();

          }
          if(startTestStatus==5 || startTestStatus==6){
            
             get_deviceData();
          }
          testStatus(startTestStatus);  
        }
        }
      }); 
    }
    
        

 
 }
      }
    });
 console.log('end3='+cleardata);
 
  }

  function updateStatus() {
      var language=$("#language").val();

       if((Vs.fdt_os==true || Vs.fdt_od==true) && fdt_test_name=='FDT'){

      $.ajax({
      url: "<?php echo WWW_BASE; ?>admin/masterReports/testdata/3512",
      type: 'GET',
 
      data: {"ageGroup": agegroup, "testTypeName": 'N30',"patient_id": '<?php echo $data['Patient']['id'] ?>', "testType": 1, "eye":selectEye,"deviceId": deviceId },
      error: function (request, status, error) {
        
         dataCaptured=false;
         restartdata=false;
         saveresult=true;
         stopTest();
         if(request.status==403){
            setting_alert('Something wrong. Please try again.');
           // window.location.replace('');
           reloadPageButton();
         }else{
            setting_alert('Something wrong. Please try again');
         }
          
    },
      success: function(data){
            
        data=JSON.parse(data) 
        var arr=data.VfMasterdata;  
        MasterRecordData.test_color_fg=parseInt(testBackground);
        MasterRecordData.test_color_bg=parseInt(testColour);  
          StartTestData.staff_name='<?php echo $user['first_name']." ".$user['last_name'];?>';
          StartTestData.staff_id='<?php echo $user['id']; ?>';  
          StartTestData.backgroundcolor=parseInt(wallBrightness);
          StartTestData.DisplaySelect=parseInt(DisplaySelect);
          StartTestData.autoPtosisReport=autoPtosisReport;            
          StartTestData.PTOSIS_INDEX=(ptosisReportIndex==1)?true:false;
          StartTestData.Patient_Name='<?php echo $data['Patient']['first_name'];?>'+' '+'<?php echo $data['Patient']['middle_name'];?>'+' '+'<?php echo $data['Patient']['last_name'];?>';
          StartTestData.DOB='<?php echo $data['Patient']['dob'];?>';
          StartTestData.pid='<?php echo $data['Patient']['id'];?>';
          StartTestData.OfficePateintID='<?php echo $data['Patient']['id_number'];?>';
          StartTestData.od_left='<?php echo $data['Patient']['od_left'];?>';
          StartTestData.od_right='<?php echo $data['Patient']['od_right'];?>';
          StartTestData.os_left='<?php echo $data['Patient']['os_left'];?>';
          StartTestData.os_right='<?php echo $data['Patient']['os_right'];?>';
          StartTestData.REACTION_TIME=measureReactionTime;
          StartTestData.zoomLevel=parseFloat(zoomLevel);
          
          StartTestData.LANGUAGE_SEL= language.toString(); 
           
          StartTestData.TEST_SPEED= parseFloat(testSpeed);
          StartTestData.VOLUME= parseFloat(audioVolume);
          StartTestData.STM_SIZE=parseInt(stimulusSize);
          StartTestData.STM_INTENSITY=Math.round(stimulusIntensity).toString();
          StartTestData.WALL_COLOR= testBackground.toString();
          StartTestData.BKG_INTENSITY= wallBrightness.toString();
          StartTestData.TEST_COLOR= testColour.toString();
          StartTestData.PID= "'<?php echo $data['Patient']['id'];?>'";
          StartTestData.START= startTestStatus; //0=stop; 1=start; 2=pause; 3=resume
          StartTestData.voice_instractuin= voice_instractuin;
          StartTestData.progression_analysis= progression_analysis;
          StartTestData.vision_screening=Vs;
          StartTestData.fdt_test_name= fdt_test_name;
          StartTestData.cs_test_name= cs_test_name;
          StartTestData.va_test_name= va_test_name;
          StartTestData.va_test_type=va_test_type;
          var obj=''; 
              var obj = {  
                previous_test:[],
                StartTest:StartTestData,
                MasterRecord:MasterRecordData,
                MasterRecordList:MasterRecordList, 
              }; 
           
        
var myJSON = JSON.stringify(obj);  
$.ajax({
      url: "<?php echo WWW_BASE; ?>admin/patients/addTestStart/3554",
      type: 'POST',
       data: {"page": 4,"testType":testType,"strategy":testSubType,"test_name":testTypeName,"patient_id": '<?php echo $data['Patient']['id'] ?>',"office_id": '<?php echo $user['office_id'] ?>',"deviceId": deviceId,"languageId": language, "TestStatus": startTestStatus, "testData": myJSON }, 
      success: function(data){
        
        }
      });

      }

    });

    }else{

     

   
        MasterRecordData.test_color_fg=parseInt(testBackground);
        MasterRecordData.test_color_bg=parseInt(testColour);  
          StartTestData.staff_name='<?php echo $user['first_name']." ".$user['last_name'];?>';
          StartTestData.staff_id='<?php echo $user['id']; ?>';  
          StartTestData.backgroundcolor=parseInt(wallBrightness);
          StartTestData.DisplaySelect=parseInt(DisplaySelect);
          StartTestData.autoPtosisReport=autoPtosisReport;            
          StartTestData.PTOSIS_INDEX=(ptosisReportIndex==1)?true:false;
          StartTestData.Patient_Name='<?php echo $data['Patient']['first_name'];?>'+' '+'<?php echo $data['Patient']['middle_name'];?>'+' '+'<?php echo $data['Patient']['last_name'];?>';
          StartTestData.DOB='<?php echo $data['Patient']['dob'];?>';
          StartTestData.pid='<?php echo $data['Patient']['id'];?>';
          StartTestData.OfficePateintID='<?php echo $data['Patient']['id_number'];?>';
          StartTestData.od_left='<?php echo $data['Patient']['od_left'];?>';
          StartTestData.od_right='<?php echo $data['Patient']['od_right'];?>';
          StartTestData.os_left='<?php echo $data['Patient']['os_left'];?>';
          StartTestData.os_right='<?php echo $data['Patient']['os_right'];?>';
          StartTestData.REACTION_TIME=measureReactionTime;
          StartTestData.zoomLevel=parseFloat(zoomLevel);
          
          StartTestData.LANGUAGE_SEL= language.toString(); 
           
          StartTestData.TEST_SPEED= parseFloat(testSpeed);
          StartTestData.VOLUME= parseFloat(audioVolume);
          StartTestData.STM_SIZE=parseInt(stimulusSize);
          StartTestData.STM_INTENSITY=Math.round(stimulusIntensity).toString();
          StartTestData.WALL_COLOR= testBackground.toString();
          StartTestData.BKG_INTENSITY= wallBrightness.toString();
          StartTestData.TEST_COLOR= testColour.toString();
          StartTestData.PID= "'<?php echo $data['Patient']['id'];?>'";
          StartTestData.START= startTestStatus; //0=stop; 1=start; 2=pause; 3=resume
          StartTestData.voice_instractuin= voice_instractuin;
          StartTestData.progression_analysis= progression_analysis;
          StartTestData.vision_screening=Vs;
          StartTestData.fdt_test_name= fdt_test_name;
          StartTestData.cs_test_name= cs_test_name;
          StartTestData.va_test_name= va_test_name;
          StartTestData.va_test_type=va_test_type;
          var obj=''; 
              var obj = {  
                previous_test:[],
                StartTest:StartTestData,
                MasterRecord:MasterRecordData,
                MasterRecordList:MasterRecordList, 
              }; 
           
        
var myJSON = JSON.stringify(obj);  
$.ajax({
      url: "<?php echo WWW_BASE; ?>admin/patients/addTestStart/3554",
      type: 'POST',
       data: {"page": 4,"testType":testType,"strategy":testSubType,"test_name":testTypeName,"patient_id": '<?php echo $data['Patient']['id'] ?>',"office_id": '<?php echo $user['office_id'] ?>',"deviceId": deviceId,"languageId": language, "TestStatus": startTestStatus, "testData": myJSON }, 
      success: function(data){
        
        }
      });
}
  }


 
function myTrim(x) {
  return x.replace(/^\s+|\s+$/gm,'');
}
</script>
<script>
  document.getElementById("myinput").oninput = function() {
    this.style.background = 'linear-gradient(to right, #f5f5f5 0%, #f5f5f5 ' + (this.value)*10 + '%, #d6d6d6 ' + this.value + '%, #d6d6d6 100%)';
    stimulusSize=(parseInt($("#myinput").val())).toFixed(0); 
    $("#myinput-val").html(stimulusSize); 
    $("#setting-stm-size").html(stimulusSize);
  };

  document.getElementById("myinput-1").oninput = function() {
    this.style.background = 'linear-gradient(to right, #f5f5f5 0%, #f5f5f5 ' + this.value + '%, #d6d6d6 ' + this.value + '%, #d6d6d6 100%)';
     stimulusIntensity=(parseInt($("#myinput-1").val())/100*48).toFixed(0);
    $("#myinput-1-val").html(stimulusIntensity);  
  };

  document.getElementById("myinput-2").oninput = function() {
    this.style.background = 'linear-gradient(to right, #f5f5f5 0%, #f5f5f5 ' + this.value + '%, #d6d6d6 ' + this.value + '%, #d6d6d6 100%)';
    wallBrightness=(parseInt($("#myinput-2").val())/100*96).toFixed(0);
     $("#myinput-2-val").html(wallBrightness);
     $("#setting-bkg-color").html(wallBrightness);
  };

  document.getElementById("myinput-3").oninput = function() {
    this.style.background = 'linear-gradient(to right, #f5f5f5 0%, #f5f5f5 ' + this.value + '%, #d6d6d6 ' + this.value + '%, #d6d6d6 100%)';
    var floattofixed;
    if((parseInt(sliderTestSpeed_maxValue)-parseInt(sliderTestSpeed_minValue))==6){
        floattofixed=0;
    }else{
        floattofixed=2;
    } 
     testSpeed=(sliderTestSpeed_minValue +(parseInt($("#myinput-3").val())/(100/(sliderTestSpeed_maxValue-sliderTestSpeed_minValue)))).toFixed(floattofixed);
     testSpeedSleep=testSpeed*500;
     $("#myinput-3-val").html(testSpeed);
     $("#setting-speed").html(testSpeed);
  };

  document.getElementById("myinput-4").oninput = function() {
    this.style.background = 'linear-gradient(to right, #f5f5f5 0%, #f5f5f5 ' + this.value + '%, #d6d6d6 ' + this.value + '%, #d6d6d6 100%)';
     audioVolume=(parseInt($("#myinput-4").val())/100).toFixed(2);
     $("#myinput-4-val").html(audioVolume);  
  };
</script>

