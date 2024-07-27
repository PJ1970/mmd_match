 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!--  <link rel="stylesheet" href="/resources/demos/style.css"> -->
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
         
        <div class="col-sm-2 mts-box-1">
          <h4 class="page-title2" style="background-color: #93b2c1;border-radius: 11px; padding-left: 10px;color: #000000;font-weight: 500; padding-top: 3px; padding-bottom: 3px;">Duration: <span id="setting-duration" class=""
                                          style="color: #ffffff;"></span></h4>
        </div>
        <div class="col-sm-3 mts-box-1">
          <h4 class="page-title2" id="patients_name" style="background-color: #93b2c1;border-radius: 11px; padding-left: 10px;color: #000000;font-weight: 500; padding-top: 3px; padding-bottom: 3px;">
            Name: <?php echo $data['Patient']['first_name'] . " " . $data['Patient']['last_name'] ?></h4>
        </div>
        <div class="col-sm-2 mts-box-1">
            <h4 class="page-title2" id="patients_dob" style="background-color: #93b2c1;border-radius: 11px; padding-left: 10px;color: #000000;font-weight: 500; padding-top: 3px; padding-bottom: 3px;">
            DOB: <?php echo $data['Patient']['dob'] ?></h4>
        </div>
        <div class="col-sm-2 mts-box-1">
             <h4 class="page-title2" id="patients_id_number" style="background-color: #93b2c1;border-radius: 11px; padding-left: 10px;color: #000000;font-weight: 500; padding-top: 3px; padding-bottom: 3px;">
            Patient ID: <?php echo $data['Patient']['id_number'] ?></h4>
        </div>
        <div class="col-sm-3 mts-box-1">
          <h4 class="page-title"><span class="pull-right" style="color:white"></span></h4>
        </div>
      </div>
            <div class="row">
                <div class="col-sm-12 mts-box-1" style="text-align:center;">
          <?php if (in_array(14, $checked_data)) {
            echo "<a style='width: 75px; background: #7e7e7e; color: white; border: 2px solid #f3ecec; margin-top: 3px;'    href='" . WWW_BASE . "admin/patients/start_test/" . $data['Patient']['id'] . "' class='btn' title='Start VF Test' >VF</a>";
          } ?>
          <?php if (in_array(15, $checked_data)) {
            echo "<a style='width: 75px; background: #3292e0; color: white; border: 2px solid white;  margin-top: 3px;' class='btn'   href='javascript:void(0);' title='Start FDT Test' >FDT</a>";
          } ?>
          <?php if (in_array(23, $checked_data)) {
           // echo "<a style='width: 75px; background: #7e7e7e; color: white; border: 2px solid white;  margin-top: 3px;' href='" . WWW_BASE . "admin/patients/start_test_da/" . $data['Patient']['id'] . "' title='Start DA Test' class='btn' >DA</a>";
          } ?>
          <?php if (in_array(25, $checked_data)) {
            echo "<a style='width: 75px; background: #7e7e7e; color: white; border: 2px solid white;  margin-top: 3px;' href='" . WWW_BASE . "admin/patients/start_test_vs/" . $data['Patient']['id'] . "' title='Start VS Test' class='btn ' >VS</a>";
          } ?>
          <?php if (in_array(34, $checked_data)) {
           // echo "<a style='width: 75px; background: #7e7e7e; color: white; border: 2px solid white;  margin-top: 3px;' href='" . WWW_BASE . "admin/patients/start_test_pup/" . $data['Patient']['id'] . "' title='Start Pupilometer Test' class='btn ' >PUP</a>";
          } ?>
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
          <?php echo $this->Html->css(array('admin/mmd-custom.css?v=8'));?>
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
                 <div class="col-sm-3">
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

                      </div>
                    </div> 
                  </div>
                  <div class="stm-nbtn-box">
                    <div href="#" class="thrushold">
                      <div class="dropdown"> 
                         <select class="mmd-dash-btn" id="language"  onChange="updateDevice();" style="height: 30px;">

                            <option value="0">Select Language</option>
                              <?php foreach($language_datas as $key => $value){ ?> 
                              <option value="<?php echo $value['language_id'] ?>" <?php echo (!empty($user_default)) ? ($user_default['UserDefault']['language_id'] == $value['language_id']) ? 'selected' : '' : '' ?>>
                                <?php echo $value['name'] ?>
                              </option>
                              <?php } ?> 
                            </select>

                      </div>
                    </div> 
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="stm-nbtn-box"> 
                    <div href="#" class="thrushold">
                      <div class="dropdown">
                        <select class="mmd-dash-btn" id="test-type" onchange="reloadPage('testId');" style="height: 30px;">
                          <option value="-1">Select Test</option>
                          <option value="1" <?php echo ($testData['testId']==1)?'selected':'' ?> >FDT Screening</option>
                          <option value="2" <?php echo ($testData['testId']==2)?'selected':'' ?>>FDT Threshold</option> 
                        </select>

                      </div>
                    </div>
                    <div href="#" class="thrushold ">
                      <div class="dropdown">
                        <select class="mmd-dash-btn"   id="test-strategy" onchange="reloadPage('strategy');" style="height: 30px;">
                          <option value="-1">Select Test</option>
                          
                        </select>
                        <!-- <button class="mmd-dash-btn dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <span class="intrc" id="test-strategy-name">Full Threshold</span>
                        <img src=<?php echo WWW_BASE.'img/down-arrow2.svg';?> alt="down arrow">
                        </button>
                        <ul class="dropdown-menu" id="sub-test-name" aria-labelledby="dropdownMenu1">
                           
                        </ul> -->
                      </div>
                    </div>
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
                      <div class="mmt-rt-control">
                      <div class="stm-btn-box" id="test-type-option">

                      </div>
                      </div>

                  </div>

                  <div class="col-lg-7 col-sm-8">
                      <div class="mmt-main-canvas" style="position: relative;">
                        <div class="border-div d-laptop" style="">
                          <button class="mmd-dash-btn md-btn-gry reload_page"   style="visibility: hidden;width: 0px; height: 0px; margin: 0px;padding: 0px;">Reload the page</button><br>
                        <span style="font-size: 18px;color: #990000;font-weight: 600; text-align: center;" class="setting-alert-message"></span><br>
                        <span style="font-size: 18px;color: #990000;font-weight: 600; text-align: center;" class="setting-alert-message2"></span>
                      </div>
                          <div class="mt-btns gz-trck d-desktop">
                              <div class="mt-checkboxes mt-checkboxes-eye">

                                <div class="form-group" id="patient_training_div">
                              <input type="checkbox" id="patient_training"
                                   class="patient_training" aria-label="..."
                                   onchange="patienTrainingUpdate(this)">
                              <label for="patient_training"><span type="checkbox"
                                                  style="font-weight: 500;"
                                                  id="patient_training_txt">Patient Training</span></label>
                            </div>
                                <div class="form-group"  id="voice_instructions_div">
                                  <input type="checkbox"  id="voice_instructions" class="voice_instructions" aria-label="..." onchange="voiceinstructions(this)">
                                  <label for="voice_instructions"><span type="checkbox" style="font-weight: 500;" id="voice_instructions_txt">Voice Instructions</span></label>
                              </div>
                              <!--<div class="form-group"  id="progression_analysis_div">-->
                              <!--    <input type="checkbox"  id="progression_analysis" class="progression_analysis" aria-label="..." onchange="progressionanalysis(this)">-->
                              <!--    <label for="progression_analysis"><span type="checkbox" style="font-weight: 500;" id="progression_analysis_txt">Progression Analysis</span></label>-->
                              <!--</div>-->
                              <div class="form-group"  id="gaze_tracking_div">
                                  <input type="checkbox" id="gaze-track" class="gaze-track" aria-label="..." onchange="gazetrack(this)">
                                  <label for="gaze-track"><span type="checkbox" style="font-weight: 500;" id="gaze_tracking_txt">Gaze Tracking</span></label>
                              </div>
                              <div class="form-group">
                                  <input type="checkbox" id="alarm-stop"   class="alarm-stop" onchange="alarmstop(this)" aria-label="...">
                                  <label for="alarm-stop"><span type="checkbox"  style="font-weight: 500;">Alarm on Stop</span></label>
                              </div>
                              </div>
                              <button class="mmd-dash-btn md-btn-gry stBtn"  data-toggle="modal" data-target="#exampleModalCenter">settings</button>
                              <div class="mt-checkboxes">

                              <div class="form-group"> 
                                  <input type="checkbox" id="both-eye" class="both-eye" aria-label="..." onchange="botheye(this)">
                                  <label for="both-eye"><span type="checkbox" style="font-weight: 500;">Both Eyes (OU)</span></label>
                              </div>
                            </div>
                              <button class="mmd-dash-btn md-btn-gry eye" value="0">Left Eye (OS)</button>
                             <!--  <button class="mmd-dash-btn md-btn-gry eye" value="2">Both Eye (OU)</button> -->
                              <button class="mmd-dash-btn md-btn-yellow eye" value="1" >Right Eye (OD)</button>
                              <button class="mmd-dash-btn md-btn-gry dismiss-alarm" style="visibility: hidden; background-color: #d63636;"   onclick="stopalarm();" >Dismiss Alarm</button>
                          </div>
                          <div style="visibility: hidden;"> 
                          <canvas id="myCanvas2" width="128" height="128" style="border:1px solid #d3d3d3; max-width: 0px;"></canvas> </div>
                          <div class="canvash-content">
                         <div class="border-div d-big-screens" style="">
                              <button class="mmd-dash-btn md-btn-gry reload_page" style="visibility: hidden;width: 0px; height: 0px; margin: 0px;padding: 0px;">Reload the page</button><br>
                              <span style="font-size: 16px;color: #990000;font-weight: 600; text-align: center;" class="setting-alert-message"></span><br>
                              <span style="font-size: 16px;color: #990000;font-weight: 600; text-align: center;" class="setting-alert-message2"></span>
                            </div>
                          <canvas   id="myCanvas" height="1020" width="1020" style="background-color: #fff; 
                          "> 
                          </canvas> 
                        </div>
                          <div class="mt-btns gz-trck d-phone">
                              <div class="mt-checkboxes mt-checkboxes-eye">

                                <div class="form-group" id="patient_training_div">
                              <input type="checkbox" id="patient_training"
                                   class="patient_training" aria-label="..."
                                   onchange="patienTrainingUpdate(this)">
                              <label for="patient_training"><span type="checkbox"
                                                  style="font-weight: 500;"
                                                  id="patient_training_txt">Patient Training</span></label>
                            </div>
                                <div class="form-group"  id="voice_instructions_div">
                                  <input type="checkbox"  id="voice_instructions" class="voice_instructions" aria-label="..." onchange="voiceinstructions(this)">
                                  <label for="voice_instructions"><span type="checkbox" style="font-weight: 500;" id="voice_instructions_txt">Voice Instructions</span></label>
                              </div>
                              <!--<div class="form-group"  id="progression_analysis_div">-->
                              <!--    <input type="checkbox"  id="progression_analysis" class="progression_analysis" aria-label="..." onchange="progressionanalysis(this)">-->
                              <!--    <label for="progression_analysis"><span type="checkbox" style="font-weight: 500;" id="progression_analysis_txt">Progression Analysis</span></label>-->
                              <!--</div>-->
                              <div class="form-group" id="gaze_tracking_div">
                                  <input type="checkbox" id="gaze-track" class="gaze-track" aria-label="..." onchange="gazetrack(this)">
                                  <label for="gaze-track"><span type="checkbox" style="font-weight: 500;" id="gaze_tracking_txt">Gaze Tracking</span></label>
                              </div>
                              <div class="form-group">
                                  <input type="checkbox" id="alarm-stop" class="alarm-stop" onchange="alarmstop(this)" aria-label="...">
                                  <label for="alarm-stop"><span type="checkbox"  style="font-weight: 500;">Alarm on Stop</span></label>
                              </div>
                              </div>
                              <button class="mmd-dash-btn md-btn-gry"  data-toggle="modal" data-target="#exampleModalCenter">settings</button>
                               <div class="mt-checkboxes">

                              <div class="form-group">
                                  <input type="checkbox" id="both-eye" class="both-eye" aria-label="..." onchange="botheye(this)">
                                  <label for="both-eye"><span type="checkbox" style="font-weight: 500;">Both Eyes (OU)</span></label>
                              </div>
                            </div>
                              <button class="mmd-dash-btn md-btn-gry eye" value="0">Left Eye (OS)</button>
                             <!--  <button class="mmd-dash-btn md-btn-gry eye" value="2">Both Eye (OU)</button> -->
                              <button class="mmd-dash-btn md-btn-yellow eye" >Right Eye (OD)</button>
                              <button class="mmd-dash-btn md-btn-gry dismiss-alarm" style="visibility: hidden; background-color: #d63636;"   onclick="stopalarm();" value="1" >Dismiss Alarm</button>
                          </div>

                          <div class="mt-btns"> 
                            <a href="" id="view-pdf-url" target="_blank" class="btn btn-info" style="visibility: hidden;">View Report</a>
                            <button class="mmd-dash-btn md-btn-yellow" data-toggle="modal" data-target="#myhelpModal" id="connection-help">Help</button>
                             <button class="mmd-dash-btn md-btn-desabley" id="recall_last_data">Recover Last Test</button> 
                           <button class="mmd-dash-btn md-btn-yellow" style="visibility: hidden;" id="connection-verify"></button>
                            <button class="mmd-dash-btn md-btn-gry" id="start">Start</button>
                            <button class="mmd-dash-btn md-btn-desabley" id="pause">Pause</button>
                            <button class="mmd-dash-btn md-btn-desabley" id="resume">Resume</button>
                            <button class="mmd-dash-btn md-btn-desabley" id="stop">Stop</button>
                          </div>
                      </div>
                  </div>

                  <div class="col-lg-3 col-sm-2 d-desktop">
                      <div class="mmt-rt-control">
                      <div class="stm-btn-box" id="test-type-option2">
                          
                      </div>
                      <!-- <div class="stm-btn-box pb-0">
                        <a href="javascript:void(0)" id="save_report" class="mmd-dash-btn md-btn-gry">Save Report</a>
                      </div> -->
                      </div>
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
  <div class="modal fade" id="deviceUseModel" role="dialog">
    <div class="modal-dialog modal-md">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Alert</h4>
        </div>
        <div class="modal-body">
          <label>Test Device already in use</label>
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
                          <h4><center>Test Speed (<span id="myinput-3-val">0.60</span> deg/sec.)</center></h4>
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
                        <!--<button class="sve-mstr-Done" id="test-start-device">Start</button>-->
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
function setRelativeWidth(){

   let i = 0;
    $('.page-header-title .mts-box-1').each(function(){
        i = i + 1;
        const width = $(this).outerWidth();
        if(i<=4){
            const element = document.querySelector(`.item.item${i}`);
            element.style.setProperty('--rel-width', `${width}px`);
        }
    
    }); 
    $('body').addClass('start_test_page');
}
setRelativeWidth();

window.addEventListener('resize', setRelativeWidth);
</script>
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
    eyeTaped:'',
    GazeTracking:'',
    testBothEyes:'',
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
  };
    
  var MasterRecordData={
    test_type_id:'',
    test_name:'',
    eye_select:'',
    age_group:'',
    numpoints:'',
    color:'',
    test_color_fg:'',
    test_color_bg:'',
    stmSize:'',
    master_key:'',
    created_date:'',
    threshold:'',
    strategy:'',
    backgroundcolor:'',
    publicList:''   
  };
  var cleardata=false;
  var progression_analysis=false;
  var voice_instractuin=true;
  var deviceTypeId=0;
  var coundown_counter=0;
  var connectionStatus=0;
  var botheyecount=0;
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
  var sliderTestSpeed_minValue=0.2; 
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
  var wallBrightness=34;
  var testSpeed=0.60;
  var audioVolume=0.40;
  var agegroup=3;
  var startTestStatus=0; 
  var dataCapturedpause=false;
  var deviceId="";
  var testColour=0;
  var testBackground=0;
  var zoomLevel=1;
  var deviceMessages=[];
  var stmLocX=[];
  var stmLocY=[];
  var stmDecibles=[];
  var stmDetectTime=[];
  var enabletestnameliest=[];
  var VF_ResultIndex=0;
  var flagFDT=1;
  var size=1000;
  var patinet_traning_value = "0";
  var patient_previous_test = "<?php echo  $patient_previous_test ?>";
  var test_name = ["Screening", "FDT Screening", "FDT Threshold"];
  var items = [
  ["C20-1", "C20-5", "N30-1", "N30-5"],
  ["N30"]
  ];
  var sub_test_name = [
  ["Select Strategy", "FDT Normative"],
  ["Select Strategy", "FDT Full Threshold"]
  ];
  var default_Selected=[1,1];


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
            TestData();
        },
        No: function() {  
          round1=0;
          startTestStatus=0;
          saveresult=true;
          $(this).dialog("close");
          stopTest();
          TestData();
        }
      },
      close: function(event, ui) {
        $(this).remove();
         set_user_preset=1;
      }
    });
}
  jQuery(document).ready(function(){ 
    $(".test-patient-section").css('visibility', 'visible');
    $('.reload_page').on('click',function(){
  window.location.replace('');
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
     console.log('1111 ',wallBrightness);
     $("#myinput-2-val").html(wallBrightness);
     $("#setting-bkg-color").html(wallBrightness);  

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
     
     $("#myinput-3-val").html(testSpeed);
     $("#setting-speed").html(testSpeed);     
   });
   $('#myinput-4').on('change',function(){
     audioVolume=(parseInt($("#myinput-4").val())/100).toFixed(2);
     $("#myinput-4-val").html(audioVolume);  
   });
  /* $('#connection-help').on('click',function(){
    setting_alert_new('1. Make sure you can hear the beep on the headset which means the clicker is connected to the headset.<br>2. Make sure the Headset has internet connection.<br>3. Make sure the correct device is selected on the device list.<br>4. Refresh this page (Ctrl+R) to make sure you are logged in');
   }); */
   $('#recall_last_data').on('click',function(){ 
    if(dataCaptured==false && dataCapturedpause==false){
      testType=$("#test-type").val();
      testSubType=$("#test-strategy").val(); 
      deviceId=$("#test-device").val();
      var lang=$("#language").val();
      if ((testType != 0) && (dropdownThresholdType != 0) && (testSubType != 0) && (restartReady) && deviceId!='' && lang!=0){
        dataCaptured=true;
        saveresult=false;
        desableAll();
        dataCapturedpause=false;
        startTestStatus=8; 
         testBothEyes=false;
         setting_alert_new2('Press the Clicker to start last test recovery',30000);
          document.getElementById("view-pdf-url").style.visibility = "hidden"; 
        $(".both-eye").prop("checked", testBothEyes); 
       //startTest();
        recallLastData();
        drawImage(testTypeName); 
        TestData();
      }else{
        if(deviceId==''){
          alert('Please select device');
        }
        if(lang==0){
          alert('Please select language');
        }
        if(testSubType==0){
          alert('Please select test strategy');
        }
      }

    }
  });
   $('#start').on('click',function(){ 
    
      testType=$("#test-type").val();
      testSubType=$("#test-strategy").val(); 
      deviceId=$("#test-device").val();
      var lang=$("#language").val();
      if ((testType != 0) && (dropdownThresholdType != 0) && (testSubType != 0) && (restartReady) && deviceId!='' && lang!=0){
         if(testType!=2 || enabletestnameliest.includes(testTypeName)){
          if(dataCaptured==false){

            if(botheyecount!=2 && botheyecount!=1 && botheyecount!=0){
            botheyecount=0;
          }
          coundown_counter=0;
          setFixationLosses('');
              setFalsePositive('');
              setFalseNigative('');
           $("#connection-help").attr("style", "color:#ffffff; visibility:hidden");
          setting_alert_new('Press the Clicker to initiate the test instructions');
           setting_alert3('Keep the page open during the test',30000);
          if(testTypeName=='Ptosis_Auto_9_PT'){ 
        if(round1==0){
          eye_taped=false;
          ptosisReportIndex = 0;
          round1=1;
           $(".eye-taped").prop("checked", false);
        }else if(round1==1){
          round1=2;
        }
      }
      if(testBothEyes==true){
        if(botheyecount==0){
          botheyecount=1;
          selectEye=1;
          $('.eye').addClass("md-btn-gry");
          $('.eye').removeClass("md-btn-yellow");
          $(".eye").each((key, element) => {
          let value = $(element).val(); 
          if(value==1){
            $(element).removeClass("md-btn-gry");  
            $(element).addClass("md-btn-yellow");
          } 
        }); 
        }else if(botheyecount==1){
          //botheyecount=2;
          // remove patient traning after 1st eye

          patinet_traning_value = "0";

          $(".patient_training").prop("checked", false);
          botheyecount++;
          selectEye=(selectEye-1)*-1; 
          $('.eye').addClass("md-btn-gry");
          $('.eye').removeClass("md-btn-yellow");
          $(".eye").each((key, element) => {
          let value = $(element).val(); 
          if(value==selectEye){
            $(element).removeClass("md-btn-gry");  
            $(element).addClass("md-btn-yellow");
          } 
        });
        }
      }
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
        TestData();
         } 
      }else{
            alert('Please select test type');
         }
         }else{
        if(deviceId==''){
          alert('Please select device');
        }
        if(lang==0){
          alert('Please select language');
        }
        if(testSubType==0){
          alert('Please select test strategy');
        }
      }

    
  });
   $('#stop').on('click',function(){  
    if(dataCaptured==true){ 
      if(stopSavestatus==true){
       // ConfirmDialog2('Do you want to save the report?');
        $('#mystopModal').modal("show"); 
      }else{
          dataCaptured=false;
          dataCapturedpause=false;
          startTestStatus=0;
          saveresult=true;
          stopTest();
          TestData();
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
      TestData();
    }
  });

     $('#resume').on('click',function(){ 
    if(dataCaptured==false  && dataCapturedpause==true){
      testType=$("#test-type").val();
      testSubType=$("#test-strategy").val();
      if ((testType != 0) && (dropdownThresholdType != 0) && (testSubType != 0) && (restartReady)){
        dataCaptured=true;
        dataCapturedpause=false;
        startTestStatus=3;
        pausecount++;
        resumeTest(); 
        TestData();
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
function getPixel(index,x,y) {
  var context = document.getElementById('myCanvas2').getContext('2d');
    var img = new Image();
    name='vf_img_'+index+'.bmp';
    img.src = "<?php echo WWW_BASE.'img/bitmap/';?>"+name;
    context.drawImage(img,0,0);
    var imgData = context.getImageData(0, 0, 128, 128);  
    var rgba = getPixels( imgData, x, y); 
    return rgba;
}

   $(window).load(function() {
    clearallolddeviceData();
     $("#myinput-2-val").html(wallBrightness);
     $("#setting-speed").html(testSpeed);  
     $("#setting-bkg-color").html(wallBrightness);
     setting_alert_new('Press the Clicker button and watch for the “Connection Verified” message to appear above the Start button. This indicates you are ready to start a test.');
     <?php if(!empty($defoult_device)){ ?> 
      setGaze(<?php echo $defoult_device['device_type'] ?>);
    <?php } ?>

     <?php if(isset($_GET['selectEye'])){ ?>
      $('.eye').addClass("md-btn-gry");
      $('.eye').removeClass("md-btn-yellow");
      $(".eye").each((key, element) => {
          let value = $(element).val(); 
          if(value==<?php echo $_GET['selectEye'] ?>){
            $(element).removeClass("md-btn-gry");  
             $(element).addClass("md-btn-yellow");
          } 
        });
    selectEye=<?php echo $_GET['selectEye'] ?>;
    <?php } ?>
    <?php if(isset($_GET['GazeTracking'])){
      if($_GET['GazeTracking']=="true"){
        ?> GazeTracking=true;<?php
      }else{
         ?> GazeTracking=false;<?php
      }
     ?>
       $(".gaze-track").prop("checked", GazeTracking); 

    <?php } ?>
       <?php if(isset($_GET['testBothEyes'])){
      if($_GET['testBothEyes']=="true"){
        ?> testBothEyes=true;<?php
      }else{
         ?> testBothEyes=false;<?php
      }
     ?>
       $(".both-eye").prop("checked", testBothEyes); 

    <?php } ?>
    <?php if(isset($_GET['alarm_stop'])){  
      if($_GET['alarm_stop']=="true"){
        ?> alarm_stop=true;<?php
      }else{
         ?> alarm_stop=false;<?php
      } ?>
      $(".alarm-stop").prop("checked", alarm_stop);

    <?php } ?>
    <?php if(isset($_GET['voice_instractuin'])){
      if($_GET['voice_instractuin']=="true"){
        ?> voice_instractuin=true;<?php
      }else{
         ?> voice_instractuin=false;<?php
      }
     ?>
       $(".voice_instructions").prop("checked", voice_instractuin); 
 <?php if(isset($_GET['progression_analysis'])){
      if($_GET['progression_analysis']=="true"){
        ?> progression_analysis=true;<?php
      }else{
         ?> progression_analysis=false;<?php
      }
     ?>
       $(".progression_analysis").prop("checked", progression_analysis); 

    <?php } ?>
    $(".progression_analysis").prop("checked", progression_analysis);
    
    <?php } ?>
    $(".voice_instructions").prop("checked", voice_instractuin);
       updateDevice();
     restartReady = true;
     dropdownThresholdType = 1;
      get_deviceDatastop();
     <?php if($testData['testId']){ ?> 
      <?php if($testData['strategy']){ ?>
         <?php if($testData['testname']){ ?>
           changeTest(<?php echo $testData['testId'] ?>,<?php echo $testData['strategy']?>,'<?php echo $testData['testname']?>');
          <?php }else{ ?>
             changeTest(<?php echo $testData['testId'] ?>,<?php echo $testData['strategy']?>);
          <?php } ?>
      <?php }else{ ?>
        changeTest(<?php echo $testData['testId'] ?>);
   <?php } ?>

   <?php }else{ ?>
             for (i = 0; i < items[1].length; i++) {
      var colour='md-btn-gry';
      if(i==2){
        drawImage(items[1][i]);
        colour='';
      }
      var test_name_temp='"'+items[1][i]+'"';
      $("#test-type-option").append("<a class='mmd-dash-btn "+colour+"  test-type-name' onclick='reloadPage("+test_name_temp+")'>"+items[1][i]+"</a>");
      
        $("#test-type-option2").append("<a class='mmd-dash-btn "+colour+" test-type-name' onclick='reloadPage("+test_name_temp+")'>"+items[1][i]+"</a>");


    } 
    $("#test-strategy").html(""); 
    for (i = 0; i < sub_test_name[1].length; i++) {
      var selected=''
      if(i==4){ 
        selected='selected';
      }
      $("#test-strategy").append("<option value='"+i+"' "+selected+">"+sub_test_name[1][i]+"</option>"); 
    }   
    $('.test-type-name-b').on('click',function(){ 
      if(saveresult==false){
      if ((!dataCaptured) && (!dataCapturedpause)){ 
             var r = confirm("Report has not been saved and the last test results will be lost. Do you want to switch the test?");
             if (r == true) {
              dataCaptured=false;
              saveresult=true;
              deviceMessages=[];
              stopTestforce();
               drawImage($(this).text());
              $('.test-type-name').addClass("md-btn-gry");
              $(this).removeClass("md-btn-gry");
            } else {
             
          } 
        }
        }else{
           drawImage($(this).text());
      $('.test-type-name').addClass("md-btn-gry");
      $(this).removeClass("md-btn-gry");
        }   
    });
   <?php } ?>

  });
   $('#test-type').on('change',function(){ 

     var value = $("#test-type").val(); 
      changeTest(value);
   
  });
   $('#test-strategy').on('change',function(){  
    
    // if ((dataCaptured)|| (dataCapturedpause)){
 if(saveresult==false){
     var r = confirm("Report has not been saved and the last test results will be lost. Do you want to switch the test type?");
     if (r == true) {
      dataCaptured=false;
      saveresult=true;
      deviceMessages=[];
      stopTestforce(); 
       testSubType = $("#test-strategy").val(); 
      ShowavailableReports($("#test-type").val(),testSubType)
    } else {
     document.getElementById("test-strategy").value = testSubType;
  }
}else{
   testSubType = $("#test-strategy").val(); 
  ShowavailableReports($("#test-type").val(),testSubType)
}   
   });

   $('.eye').on('click',function(){ 
    
    if(selectEye!=$(this).val()){
      if((dataCaptured)|| (dataCapturedpause)){
        
          
    }
    else{ 
      clertData();
      selectEye=$(this).val();
      eye_taped= false;
      ptosisReportIndex = 0; 
      // testBothEyes=false;
      //  $(".both-eye").prop("checked", false);
      $('.eye').addClass("md-btn-gry");
      $('.eye').removeClass("md-btn-yellow");
      $(this).removeClass("md-btn-gry");
      $(this).addClass("md-btn-yellow");
    }
  }

});


 });
  function selectTest(value,stragigy='',testname='') {
    
    var time=20;
    var lastChangeTime=0;

    if (((dataCaptured)|| (dataCapturedpause)|| (saveresult==false)) && (testType != -1) && (time > lastChangeTime + 5)){

     var r = confirm("Report has not been saved and the last test results will be lost. Do you want to switch the test type?");
     if (r == true) {
      dataCaptured=false;
      deviceMessages=[];
      saveresult=true;
      stopTestforce();
      
      PopulateList(value,stragigy,testname);
    } else {
     document.getElementById("test-type").value = testType;
  }
}else{
 // selectTest

  if (flagFDT == 0)
  {
    switch (testType)
    {
      case 1:
      TestTypeText = "Screening";
      test_type = "Screening";
      dropdownThresholdType = 1;
      break;
      case 2:
      TestTypeText = "Threshold";
      test_type = "Threshold";
      dropdownThresholdType = 4;
      break;
      case 3:
      TestTypeText = "Ptosis";
      test_type = "Ptosis";
      dropdownThresholdType = 2;
      break;
      case 4:
      TestTypeText = "TNeuro";
      test_type = "Neuro";
      dropdownThresholdType = 1;
      break;
      case 5:
      TestTypeText = "Kinetic";
      test_type = "Kinetic";
      dropdownThresholdType = 1;
      break;
      default:
      TestTypeText = "Error";
      test_type = "Error";
      break;
    }

    PopulateList(value,stragigy,testname);
           // string msg = "TEST_TYPE|" + testType.ToString(); //1=Screening, 2=Glaucoma, 3=Ptosis, 4=Neuro, 5=Kinetic
           // sendMessageString(msg, false);
           restartReady = true;
         }
         else
         {
          switch (testType)
          {
            case 1:
            TestTypeText = "Screening";
            test_type = "Screening";
            dropdownThresholdType = 1;
            break;
            case 2:
            TestTypeText = "Threshold";
            test_type = "Threshold";
            dropdownThresholdType = 4;
            break;
            default:
            TestTypeText = "Error";
            test_type = "Error";
            break;
          }

          PopulateList(value,stragigy,testname);
           // string msg = "TEST_TYPE|" + testType.ToString(); //1=Screening, 2=Glaucoma, 3=Ptosis, 4=Neuro, 5=Kinetic
            //sendMessageString(msg, false);
            restartReady = true;
          }
        }
      }
      function PopulateList(value,stragigy='',testname='') { 
        
        if(value!="-1"){
          $("#test-type-option").html("");
          $("#test-type-option2").html("");
          for (i = 0; i < items[(value-1)].length; i++) {
            var test_name_temp='"'+items[(value-1)][i]+'"';
            if(testname!='' && testname==items[(value-1)][i]){
              drawImage(items[(value-1)][i]);
              $("#test-type-option" ).append("<a class='mmd-dash-btn  test-type-name' onclick='reloadPage("+test_name_temp+")'>"+items[(value-1)][i]+"</a>"); 
               $("#test-type-option2" ).append("<a class='mmd-dash-btn  test-type-name' onclick='reloadPage("+test_name_temp+")'>"+items[(value-1)][i]+"</a>"); 
            }else if(testname!=''){
               $("#test-type-option" ).append("<a class='mmd-dash-btn md-btn-gry test-type-name' onclick='reloadPage("+test_name_temp+")'>"+items[(value-1)][i]+"</a>");
              $("#test-type-option2" ).append("<a class='mmd-dash-btn md-btn-gry test-type-name' onclick='reloadPage("+test_name_temp+")'>"+items[(value-1)][i]+"</a>"); 
            } 

            if(testname=='' && i==0){
              drawImage(items[(value-1)][i]);
              $("#test-type-option" ).append("<a class='mmd-dash-btn  test-type-name' onclick='reloadPage("+test_name_temp+")'>"+items[(value-1)][i]+"</a>"); 
               $("#test-type-option2" ).append("<a class='mmd-dash-btn  test-type-name' onclick='reloadPage("+test_name_temp+")'>"+items[(value-1)][i]+"</a>"); 
            }else if(testname==''){
              $("#test-type-option" ).append("<a class='mmd-dash-btn md-btn-gry test-type-name' onclick='reloadPage("+test_name_temp+")'>"+items[(value-1)][i]+"</a>");
              $("#test-type-option2" ).append("<a class='mmd-dash-btn md-btn-gry test-type-name' onclick='reloadPage("+test_name_temp+")'>"+items[(value-1)][i]+"</a>"); 
            }
           
         } 
       }else{
       
    }

    $("#test-strategy").html(""); 
    if(value!="-1"){
     for (i = 0; i < sub_test_name[(value-1)].length; i++) {
       var s='';
       
       if(stragigy!=''){
        if(stragigy==i){
          s='selected';
          ShowavailableReports(value,i);
        }
      }else{
        if(default_Selected[(value-1)]==i){
          s='selected';
          ShowavailableReports(value,i);
        }
      }
       
       $("#test-strategy").append("<option value='"+i+"' "+s+">"+sub_test_name[(value-1)][i]+"</option>"); 
     }   
   }else{ 
     $("#test-strategy").append("<option value='0'>Select Strategy</option>");
   }
   $('.test-type-name-b').on('click',function(){ 
    if(saveresult==false){
     if ((!dataCaptured) && (!dataCapturedpause)){ 
             var r = confirm("Report has not been saved and the last test results will be lost. Do you want to switch the test?");
             if (r == true) {
              dataCaptured=false;
              saveresult=true;
              deviceMessages=[];
              stopTestforce();
              drawImage($(this).text());
            $('.test-type-name').addClass("md-btn-gry");
            $(this).removeClass("md-btn-gry");
            } else {
              
          } 
        }
        }else{
          drawImage($(this).text()); 
    $('.test-type-name').addClass("md-btn-gry");
    $(this).removeClass("md-btn-gry");
        }
    
    });
 }
       function PopulateList2(value) { 

        if(value!="-1"){
          $("#test-type-option").html("");
          $("#test-type-option2").html("");
          for (i = 0; i < items[(value-1)].length; i++) {
             var test_name_temp='"'+items[(value-1)][i]+'"';
              $("#test-type-option" ).append("<a class='mmd-dash-btn md-btn-gry test-type-name' onclick='reloadPage("+test_name_temp+")'>"+items[(value-1)][i]+"</a>");
              $("#test-type-option2" ).append("<a class='mmd-dash-btn md-btn-gry test-type-name' onclick='reloadPage("+test_name_temp+")'>"+items[(value-1)][i]+"</a>"); 
            
         } 
       }else{
       
    }

    $("#test-strategy").html(""); 
    if(value!="-1"){
     for (i = 0; i < sub_test_name[(value-1)].length; i++) {
       var s='';
      if(default_Selected[(value-1)]==i){
        s='selected';
        ShowavailableReports(value,i);
      } 
       $("#test-strategy").append("<option value='"+i+"' "+s+">"+sub_test_name[(value-1)][i]+"</option>"); 
     }   
   }else{ 
     $("#test-strategy").append("<option value='0'>Select Strategy</option>");
   }
   $('.test-type-name-b').on('click',function(){ 
    if(saveresult==false){
     if ((!dataCaptured) && (!dataCapturedpause)){ 
             var r = confirm("Report has not been saved and the last test results will be lost. Do you want to switch the test?");
             if (r == true) {
              dataCaptured=false;
              saveresult=true;
              deviceMessages=[];
              stopTestforce();
              drawImage($(this).text());
            $('.test-type-name').addClass("md-btn-gry");
            $(this).removeClass("md-btn-gry");
            } else {
              
          } 
        }
        }else{
          drawImage($(this).text()); 
    $('.test-type-name').addClass("md-btn-gry");
    $(this).removeClass("md-btn-gry");
        }
    
    });
 }
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
   $('#test-start-device').on('click',function(){
      starttestdevice();

    });
 
 });
 const starttestdevice = async () => {
   drawImageb(testTypeName,'#ff0000');
   await sleep(300);
  drawImageb(testTypeName,'#000000');   
 }

 const sleep = (milliseconds) => {
  return new Promise(resolve => setTimeout(resolve, milliseconds))
}

 const clearallolddeviceData = async function(){  
          var feedback = $.ajax({
          type: "POST",
          url: "<?php echo WWW_BASE; ?>admin/patients/cleardevice",
           data: {"d": 1},
          async: false
      }).success(function(){ 
          setTimeout(function(){clearallolddeviceData();}, 1000*60);
      }).responseText; 
 }

 const get_deviceDatastop = async function(){  
  if(connectionStatus==0){
  connectionStatus=1;
  $("#connection-help").attr("style", "color:#ffffff; visibility:visible");
  setting_alert_new('Press the Clicker button and watch for the “Connection Verified” message to appear above the Start button. This indicates you are ready to start a test.');
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

//function get_deviceData(){
  const get_deviceData = async function(){

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
      starttestdevice();
    }else if(res[0]=='VF' && res[1]=='VF_TEST_COMPLETED'){

       setpixcelVF_TEST_COMPLETED(obj.message[key]['message'],obj.message[key]['id'],obj.message[key]['office_id'],obj.message[key]['device_id']);
    }else{
      var  res2= obj.message[key]['message'].split("VF|");
  
       
       const fillTPS2 = async function(item) {
          item_new="VF|"+item;
         res3=item.split("|"); 
     if(res3[0]=='TPS'){
      const promises = setpixcelTPS(item_new);
        await Promise.all([promises]);
     // setpixcelTPS(item_new);
    }else if(res3[0]=='PBT'){
      const promises = setpixcelPBT(item_new);
        await Promise.all([promises]);
    }else if(res3[0]=='VF_RESULT'){
     
       const promises =  setpixcelVF_RESULT(item_new);
        await Promise.all([promises]);
    }else if(res3[0]=='VF_SC1_RESULT'){
     // setpixcelVF_SC1_RESULT(item_new);
       const promises =  setpixcelVF_SC1_RESULT(item_new);
        await Promise.all([promises]);
    }
    else if(res3[0]=='VF_SC2_RESULT'){
        const promises =  setpixcelVF_SC2_RESULT(item_new);
        await Promise.all([promises]);
    }
    else if(res3[0]=='VF_SC3_RESULT'){
       const promises =  setpixcelVF_SC3_RESULT(item_new);
        await Promise.all([promises]);
    }
    else if(res3[0]=='VF_SC4_RESULT'){
        const promises =  setpixcelVF_SC4_RESULT(item_new);
        await Promise.all([promises]);
    }
    else if(res3[0]=='VF_FN_RESULT'){
       const promises =  setpixcelVF_FN_RESULT(item_new);
        await Promise.all([promises]); 
    }
    else if(res3[0]=='VF_FP_RESULT'){
       const promises =  setpixcelVF_FP_RESULT(item_new);
        await Promise.all([promises]); 
    }
    else if(res3[0]=='VF_FIX_RESULT'){
       const promises =  setpixcelVF_FIX_RESULT(item_new);
        await Promise.all([promises]); 
    }
    else if(res3[0]=='BSPOT'){
      const promises =  setpixcelBSPOT(item_new);
        await Promise.all([promises]);  
    }
    else if(res3[0]=='VF_KIN_RESULT'){
      const promises =  setpixcelVF_KIN_RESULT(item_new);
        await Promise.all([promises]);  
    }
    else if(res3[0]=='VF_RESEND_DATA_COMPLETED'){
      const promises =  setpixcelVF_RESEND_DATA_COMPLETED(item_new);
        await Promise.all([promises]);  
    }
    else if(res3[0]=='TEST_STATUS'){
      const promises =  setpixcelVF_TEST_STATUS(item_new);
        await Promise.all([promises]);  
    }
    else if(res3[0]=='USER_PAUSE'){
      const promises =  setpixcelVF_USER_PAUSE(item_new);
        await Promise.all([promises]);  
    }
    else if(res3[0]=='USER_RESUME'){
      const promises =  setpixcelVF_USER_RESUME(item_new);
        await Promise.all([promises]);  
    }
     else if(res3[0]=='TEST_PAUSED_BY_PATIENT'){
      setpixcelVF_TEST_PAUSED_BY_PATIENT(item_new);
     /* const promises =  setpixcelVF_USER_RESUME(item_new);
        await Promise.all([promises]);  */
    }
    else if(res3[0]=='PRIMARY_POINTS'){
      const promises =  setpixcelVF_PRIMARY_POINTS(item_new);
        await Promise.all([promises]);  
    }
    else if(res3[0]=='BLINDSPOT_POINTS'){
      const promises =  setpixcelVF_BLINDSPOT_POINTS(item_new);
        await Promise.all([promises]);  
    }
    else if(res3[0]=='MAIN_POINTS'){
      const promises =  setpixcelVF_MAIN_POINTS(item_new);
        await Promise.all([promises]);  
    }
    else if(res3[0]=='START_BUTTON_PRESSED'){
        setpixcelVF_START_BUTTON_PRESSED(item_new);
        
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
function drawImageb(argument,col1) {
    var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d");
  for ( x1 = 1; x1 <= 1000 ; x1++)
    {
        ctx.fillStyle = col1;
        ctx.fillRect(x1,500,3,3);   
    }
    for ( x1 = 1; x1 <= 1000 ; x1++)
    {
        ctx.fillStyle = col1;
        ctx.fillRect(499,x1,3,3);   
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
              //startTestStatus=9;
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
}
const setting_alert3 = async function(msg, time=30000) { 
     $(".setting-alert-message2").html(msg); 
      // await sleep(time);
      // $(".setting-alert-message2").html(""); 
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
function setpixcelVF_FILE_UPLOADED(data) {
   var  splitData= data.split("|");
    document.getElementById("view-pdf-url").style.visibility = "visible";
    setting_alert_new2('Test has Finished',3000);
    //document.getElementById("view-pdf-url").href="<?php echo WWW_BASE; ?>/pointData/"+splitData[2]; 
    document.getElementById("view-pdf-url").href="<?php echo WWW_BASE; ?>admin/patients/view_pdf_report/"+splitData[2];
    window.open("<?php echo WWW_BASE; ?>admin/patients/view_pdf_report/"+splitData[2]);
    saveresult=true; 
    dataCaptured=false;
    stopSavestatus=false;
    round1=0;
     $.ajax({
      url: "<?php echo WWW_BASE; ?>admin/patients/clear_alldata/3554",
      type: 'POST', 
       data: {"patient_id": '<?php echo $data['Patient']['id'] ?>',"office_id": '<?php echo $user['office_id'] ?>',"deviceId": $("#test-device").val()}, 
        error: function (request, status, error) {
         dataCapturedpause=false;
            connectionStatus=0;
               get_deviceDatastop();
        },     
      success: function(data){
        if(testBothEyes==true){
           if(botheyecount==1){
               setFixationLosses('');
              setFalsePositive('');
              setFalseNigative('');
              botheyecount=2;
              selectEye=(selectEye-1)*-1; 
              $('.eye').addClass("md-btn-gry");
              $('.eye').removeClass("md-btn-yellow");
              $(".eye").each((key, element) => {
              let value = $(element).val(); 
              if(value==selectEye){
                $(element).removeClass("md-btn-gry");  
                $(element).addClass("md-btn-yellow");
              }   
            });
               testType=$("#test-type").val();
      testSubType=$("#test-strategy").val(); 
      deviceId=$("#test-device").val();
      var lang=$("#language").val();

      if(testTypeName=='Ptosis_Auto_9_PT'){ 
        if(round1==2){
          eye_taped=false;
          ptosisReportIndex = 0;
          round1=1;
           $(".eye-taped").prop("checked", false);
        }else if(round1==1){
       /*   round1=2;
          $(".eye-taped").prop("checked", true); 
          eye_taped=true;
          ptosisReportIndex=1;*/
        }
      } 
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
        TestData();
            }
          }else{
               dataCapturedpause=false;
            connectionStatus=0;
               get_deviceDatastop();
          }
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

function setpixcelVF_FN_RESULT(data) {
  var  splitData= data.split("|");
  ivalue = parseInt(splitData[4]);
  falseNegTotal++;
  msgCount++;
  changeRelibility();
  if (ivalue == 0)
  {
    color = "#ff0000";
    falseNegCount++;
    changeRelibility();
  }
  else
  {
    color = "#00ff00";
  }
   falseNegStatus = falseNegCount + "/" + falseNegTotal;
   setFalseNigative(falseNegStatus);
}
function setpixcelVF_FP_RESULT(data) {
  var  splitData= data.split("|");
  ivalue = parseInt(splitData[4]);
  lastActionTime = new Date();;
  falsePosTotal++;
  msgCount++;
  changeRelibility();
  if (ivalue == 1)
  {
    color = "#ff0000";
    falsePosCount++;
    changeRelibility();

  }
  else{
    color = "#00ff00";
  }
  falsePosStatus = falsePosCount + "/" + falsePosTotal;
   setFalsePositive(falsePosStatus);
}
function setpixcelVF_FIX_RESULT(data) {
  var  splitData= data.split("|");
  ivalue = (splitData[4]);
  fixationLossTotal++;
  msgCount++;
  if (ivalue == 0)
  {
    color = "#ff0000";
  }
  else
  {
    color ="#00ff00";
    fixationLossCount++;
  }
   FixenStatus = fixationLossCount + "/" + fixationLossTotal;
   setFixationLosses(FixenStatus);
}
function setpixcelVF_TEST_COMPLETED(data,id,office_id,device_id) {

    var  splitData= data.split("|");
    testState = 0;
    TotalQuestions = (splitData[2]);
   // testStatus = "Status: Completed";
    setTestStatus('Completed');
    setTestQuestions(splitData[2]);
   // Questions = "Questions: " + splitData[2];
    restartdata=true;
    dataCaptured=false;
    completedDeviveStatus=true;
    numTestPointsCompleted=0;
    deviceMessages=[];
    clertDatashow();
    $.ajax({
      url: "<?php echo WWW_BASE; ?>admin/patients/testcomplited/3554",
      type: 'POST',

       data: {"id": id,"office_id": office_id,"device_id": device_id}, 
      success: function(data){
        
        }
      });
    stopTest(1);
    startTestStatus=0;
    drawImage(testTypeName); 
    setting_alert('Test Completed');
    
     elapsedTime = CalculateElapsedTime(); 
    //     initPDFData(); 
    //     SelectStop2();
}
function setpixcelVF_RESEND_DATA_COMPLETED(data) {
  var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d");
  var  splitData= data.split("|");
 // splitData[5]=parseFloat(splitData[5])*-1;
  
  /* document.getElementById("view-pdf-url").style.visibility = "visible";
    document.getElementById("view-pdf-url").href="<?php echo WWW_BASE; ?>admin/patients/view_pdf_report/<?php echo $data['Patient']['id'] ?>/"+testTypeName;
  */
  measureReactionTime = false;
 if(!CONST_DEBUG_LERP){
        if ((msgCount == msgCountTotal) || (numDataTxAttempts > 2))
        {
          testState = 0;
          TotalQuestions = parseInt(splitData[2]);
          // TestStatus = "Status: Completed";
          // Questions = "Questions: " + splitData[2]; 
          setTestStatus('Completed');
          setTestQuestions(splitData[2]);
          normalColor ="#ffffff";
          colors="#ffffff";
          pressedColor = "#ffffff";
          highlightedColor ="#ffffff";
          GetComponent = colors;
          GetComponentInChildren= "Start"; 
          elapsedTime = CalculateElapsedTime(); 
          if (datas.vfpointdata.length>0){
            return;
          } 
          zoomOffset = 3.0 / zoomLevel;
          zoomMult = 1000.0 / (2 * zoomOffset);
          if (datas.vfpointdata.length>0)
          {
            n = datas.vfpointdata.length;
              for (ii = 0; ii < n; ii++)
              {
                  vfpointdata = datas.vfpointdata[ii];
                  if (parseInt(vfpointdata.size) == 99)
                  {
                      x = parseFloat(vfpointdata.x) + zoomOffset;
                      y = parseFloat(vfpointdata.y) + zoomOffset;
                      x1 = Math.round(x * zoomMult);
                      y1 = Math.round(y * zoomMult);
                      for (i = 0; i < 3; i++)
                      {
                          if ((testTypeName != "Central_10_2") && (zoomLevel > 0.2))
                          { 
                              ctx.beginPath();
                              ctx.lineWidth = 3;
                              ctx.strokeStyle = "#ff0000";
                              ctx.moveTo(x1, y1 + (15 + i));
                              ctx.lineTo(x1 - (15 + i), y1 - (15 + i));
                              ctx.moveTo(x1, y1 + (15 + i));
                              ctx.lineTo(x1 + (15 + i), y1 - (15 + i));  
                              ctx.moveTo(x1 - (15 + i), y1 - (15 + i));
                              ctx.lineTo(x1 + (15 + i), y1 - (15 + i));  
                              ctx.stroke();  
                          }
                      }
                  }
              }

               if((testType == 5))
              {
                  if (autoPtosisReport)
                  {
                    if (msgCountTotal > 9){
                        ptosisReportIndex = 1;
                        if (ptosisReportIndex == 0)
                      {
                        p1Save_x =0;
                        p1Save_y =0;
                        p2_x = 0;
                        p2_y = 0;
                        if (datas.vfpointdata.length>0)
                        {
                            n = datas.vfpointdata.length;
                            for (ii = 0; ii < n - 1; ii++)
                            {
                                p1x =kinLocX[ii];
                                p1y =kinLocY[ii]; 
                                if (ii == 0){
                                  p1Save_x =p1x;
                                  p1Save_y =p1y; 
                                }    
                                p2_x =kinLocX[ii + 1];
                                p2_y =kinLocY[ii + 1]; 
                                ctx.beginPath();
                                ctx.lineWidth = 3;
                                ctx.strokeStyle = "#ff0000";
                                ctx.moveTo(p1x, p1y);
                                ctx.lineTo(p2_x, p2_y); 
                                ctx.stroke(); 
                                
                            }
                            ctx.beginPath();
                            ctx.lineWidth = 3;
                            ctx.strokeStyle = "#ff0000";
                            ctx.moveTo(p1Save_x, p1Save_y);
                            ctx.lineTo(p2_x, p2_y); 
                            ctx.stroke();
                          }
                      }else{
                          ///
                            var p1Save_x =0;
                            var p1Save_y =0;
                            var p2Save_x =0;
                            var p2Save_y =0;
                            var p2_x = 0;
                            var p2_y = 0;
                            var p1_x = 0;
                            var p1_y = 0;
                            var sum = 0;
                            if (datas.vfpointdata.length>0)
                            {
                                n = datas.vfpointdata.length;
                                for (ii = 9; ii < n - 1; ii++)
                                {
                                    p1_x =kinLocX[ii];
                                    p1_y =kinLocY[ii]; 
                                    if (ii == 9){
                                      p1Save_x =p1_x;
                                      p1Save_y =p1_y; 
                                    } 
                                    p2_x =kinLocX[ii + 1];
                                    p2_y =kinLocY[ii + 1]; 
                                    if (ii == n - 2){
                                      p2Save_x =p2_x;
                                      p2Save_y =p2_y;
                                    }
                                    ctx.beginPath();
                                    ctx.lineWidth = 3;
                                    ctx.strokeStyle = "#0000ff";
                                    ctx.moveTo(p1_x, p1_y);
                                    ctx.lineTo(p2_x, p2_y); 
                                    ctx.stroke();      

                                    ctx.beginPath();
                                    ctx.lineWidth = 3;
                                    ctx.strokeStyle = "#0000ff";
                                    ctx.moveTo(kinLocX[ii] + 1, kinLocY[ii]);
                                    ctx.lineTo(kinLocX[ii + 1] + 1, kinLocY[ii + 1]); 
                                    ctx.stroke(); 

                                    ctx.beginPath();
                                    ctx.lineWidth = 3;
                                    ctx.strokeStyle = "#0000ff";
                                    ctx.moveTo(kinLocX[ii], kinLocY[ii] + 1);
                                    ctx.lineTo(kinLocX[ii + 1], kinLocY[ii + 1] + 1); 
                                    ctx.stroke(); 
                                     
                                    ctx.beginPath();
                                    ctx.lineWidth = 3;
                                    ctx.strokeStyle = "#0000ff";
                                    ctx.moveTo(kinLocX[ii] - 1, kinLocY[ii]);
                                    ctx.lineTo(kinLocX[ii + 1] - 1, kinLocY[ii + 1]); 
                                    ctx.stroke(); 

                                    ctx.beginPath();
                                    ctx.lineWidth = 3;
                                    ctx.strokeStyle = "#0000ff";
                                    ctx.moveTo(kinLocX[ii], kinLocY[ii] - 1);
                                    ctx.lineTo(kinLocX[ii + 1], kinLocY[ii + 1] - 1); 
                                    ctx.stroke();
  
                                    tmp = Math.pow((kinPhi[ii] + kinPhi[ii + 1]), 2) * Math.PI / (8 * 8);
                                    sum = sum + tmp;
                                }
                                areaTaped = sum;
                                ctx.beginPath();
                                ctx.lineWidth = 3;
                                ctx.strokeStyle = "#0000ff";
                                ctx.moveTo(p1Save_x,p1Save_y);
                                ctx.lineTo(p2Save_x,p2Save_y); 
                                ctx.stroke();
                                
                                ctx.beginPath();
                                ctx.lineWidth = 3;
                                ctx.strokeStyle = "#0000ff";
                                ctx.moveTo(p1Save_x + 1, p1Save_y);
                                ctx.lineTo(p2Save_x + 1, p2Save_y); 
                                ctx.stroke();

                                ctx.beginPath();
                                ctx.lineWidth = 3;
                                ctx.strokeStyle = "#0000ff";
                                ctx.moveTo(p1Save_x, p1Save_y + 1);
                                ctx.lineTo(p2Save_x, p2Save_y + 1); 
                                ctx.stroke();
                                 
                                ctx.beginPath();
                                ctx.lineWidth = 3;
                                ctx.strokeStyle = "#0000ff";
                                ctx.moveTo(p1Save_x - 1, p1Save_y);
                                ctx.lineTo(p2Save_x - 1, p2Save_y + 1); 
                                ctx.stroke();

                                ctx.beginPath();
                                ctx.lineWidth = 3;
                                ctx.strokeStyle = "#0000ff";
                                ctx.moveTo(p1Save_x, p1Save_y - 1);
                                ctx.lineTo(p2Save_x, p2Save_y - 1); 
                                ctx.stroke(); 
                            }

                            p2_x = 0;
                            p2_y = 0;
                            p1_x = 0;
                            p1_y = 0;
                            sum = 0;
                            if (datas.vfpointdata.length>0)
                            {
                                //n = data.vfpointdata.Count;
                                n = 9;
                                for (ii = 0; ii < n - 1; ii++)
                                {
                                    p1_x =kinLocX[ii];
                                    p1_y =kinLocY[ii];
                                     
                                    if (ii == 0){
                                      p1Save_x = p1_x;
                                      p1Save_y = p1_y;
                                    }
                                    p2_x =kinLocX[ii + 1];
                                    p2_y =kinLocY[ii + 1]; 

                                    
                                    if (ii == n - 2){
                                      p2Save_x = p2_x;
                                      p2Save_y = p2_y;
                                    }
                                        
                                    ctx.beginPath();
                                    ctx.lineWidth = 3;
                                    ctx.strokeStyle = "#ff0000";
                                    ctx.moveTo(p1_x, p1_y - 1);
                                    ctx.lineTo(p2_x, p2_y - 1); 
                                    ctx.stroke(); 

                                    ctx.beginPath();
                                    ctx.lineWidth = 3;
                                    ctx.strokeStyle = "#ff0000";
                                    ctx.moveTo(kinLocX[ii] + 1, kinLocY[ii]);
                                    ctx.lineTo(kinLocX[ii + 1] + 1, kinLocY[ii + 1]); 
                                    ctx.stroke(); 

                                    ctx.beginPath();
                                    ctx.lineWidth = 3;
                                    ctx.strokeStyle = "#ff0000";
                                    ctx.moveTo(kinLocX[ii], kinLocY[ii] + 1);
                                    ctx.lineTo(kinLocX[ii + 1], kinLocY[ii + 1] + 1); 
                                    ctx.stroke(); 

                                    ctx.beginPath();
                                    ctx.lineWidth = 3;
                                    ctx.strokeStyle = "#ff0000";
                                    ctx.moveTo(kinLocX[ii] - 1, kinLocY[ii]);
                                    ctx.lineTo(kinLocX[ii + 1] - 1, kinLocY[ii + 1]); 
                                    ctx.stroke(); 

                                    ctx.beginPath();
                                    ctx.lineWidth = 3;
                                    ctx.strokeStyle = "#ff0000";
                                    ctx.moveTo(kinLocX[ii], kinLocY[ii] - 1);
                                    ctx.lineTo(kinLocX[ii + 1], kinLocY[ii + 1] - 1); 
                                    ctx.stroke();
             

                                    tmp = Math.pow((kinPhi[ii] + kinPhi[ii + 1]), 2) * Math.PI / (8 * 8);
                                    sum = sum + tmp;
                                }
                                areaUnTaped = sum;
                                ctx.beginPath();
                                ctx.lineWidth = 3;
                                ctx.strokeStyle = "#ff0000";
                                ctx.moveTo(p1Save_x, p1Save_y);
                                ctx.lineTo(p2Save_y, p2Save_y); 
                                ctx.stroke(); 

                                ctx.beginPath();
                                ctx.lineWidth = 3;
                                ctx.strokeStyle = "#ff0000";
                                ctx.moveTo(p1Save_x + 1, p1Save_y);
                                ctx.lineTo(p2Save_x + 1, p2Save_y); 
                                ctx.stroke(); 

                                ctx.beginPath();
                                ctx.lineWidth = 3;
                                ctx.strokeStyle = "#ff0000";
                                ctx.moveTo(p1Save_x, p1Save_y + 1);
                                ctx.lineTo(p2Save_x, p2Save_y + 1); 
                                ctx.stroke(); 

                                ctx.beginPath();
                                ctx.lineWidth = 3;
                                ctx.strokeStyle = "#ff0000";
                                ctx.moveTo(p1Save_x - 1, p1Save_y);
                                ctx.lineTo(p2Save_x - 1, p2Save_y + 1); 
                                ctx.stroke();  
 

                                ctx.beginPath();
                                ctx.lineWidth = 3;
                                ctx.strokeStyle = "#ff0000";
                                ctx.moveTo(p1Save_x, p1Save_y - 1);
                                ctx.lineTo(p2Save_x, p2Save_y - 1); 
                                ctx.stroke();

                            }
                          ///
                        }
                         x1 = 30;
                        y1 = 30;
                        nsize = 5;
                        ctx.beginPath();
                        ctx.strokeStyle = "#ff0000";
                        ctx.rect(x1, y1, nsize, nsize);
                        ctx.stroke();
                        ctx.beginPath();
                        ctx.rect(x1, y1, nsize- 1, nsize- 1);
                        ctx.stroke();
                        ctx.beginPath();
                        ctx.strokeStyle = "#ff0000";
                        ctx.rect(x1, y1, nsize+ 1, nsize+ 1);
                        ctx.stroke();
                        ctx.beginPath();
                        ctx.strokeStyle = "#ff0000";
                        ctx.rect(x1, y1, nsize-2, nsize-2);
                        ctx.stroke();
                        ctx.beginPath();
                        ctx.strokeStyle = "#ff0000";
                        ctx.rect(x1, y1, nsize+2, nsize+2);
                        ctx.stroke(); 
                        msg = "U N T A P E D";
                        ctx.beginPath();
                        ctx.fillStyle = "#000000";
                        ctx.strokeStyle = "#000000";
                        ctx.font = "33px Arial";   
                        ctx.fillText(msg,Math.round(x1 + 50),Math.round( y1-40));
                        ctx.stroke();


                        x1 = 30;
                        y1 = 80;
                        ctx.stroke();
                        ctx.beginPath();
                        ctx.strokeStyle = "#0000ff";
                        ctx.rect(x1, y1, 10, 10);
                        ctx.stroke();
                        ctx.beginPath();
                        ctx.strokeStyle = "#0000ff";
                        ctx.rect(x1, y1, 9, 9);
                        ctx.stroke();
                        ctx.beginPath();
                        ctx.strokeStyle = "#0000ff";
                        ctx.rect(x1, y1, 8, 8);
                        ctx.stroke(); 
                        ctx.beginPath();
                        ctx.strokeStyle = "#0000ff";
                        ctx.rect(x1, y1, 7, 7);
                        ctx.stroke();
                        ctx.beginPath();
                        ctx.strokeStyle = "#0000ff";
                        ctx.rect(x1, y1, 6, 6);
                        ctx.stroke();
                        ctx.beginPath();
                        ctx.strokeStyle = "#0000ff";
                        ctx.rect(x1, y1, 5, 5);
                        ctx.stroke(); 
                        msg = "T A P E D";
                        ctx.beginPath();
                        ctx.fillStyle = "#000000";
                        ctx.strokeStyle = "#000000";
                        ctx.font = "33px Arial";   
                        ctx.fillText(msg,Math.round(x1 + 50),Math.round( y1-40));
                        ctx.stroke(); 
                    }
                    else{
                        var p1Save_x =0;
                        var p1Save_y =0;
                        var p2Save_x =0;
                        var p2Save_y =0;
                        var p2_x = 0;
                        var p2_y = 0;
                        var p1_x = 0;
                        var p1_y = 0;
                        if (datas.vfpointdata.length>0)
                        {
                            n = datas.vfpointdata.length;
                            for (ii = 0; ii < n - 1; ii++)
                            {
                                p1_x =kinLocX[ii];
                                p1_y =kinLocY[ii]; 
                                if (ii == 0){
                                  p1Save_x = p1_x;
                                  p1Save_y = p1_y;
                                }
                                    

                                p2_x =kinLocX[ii + 1];
                                p2_y =kinLocY[ii + 1];
                                 
                                if (ii == n - 2){
                                  p2Save_x = p2_x;
                                  p2Save_y = p2_y;
                                } 
                                ctx.beginPath();
                                ctx.lineWidth = 3;
                                ctx.strokeStyle = "#ff0000";
                                ctx.moveTo(p1_x,p1_y);
                                ctx.lineTo(p2_x,p2_y); 
                                ctx.stroke(); 

                                ctx.beginPath();
                                ctx.lineWidth = 3;
                                ctx.strokeStyle = "#ff0000";
                                ctx.moveTo(kinLocX[ii] + 1, kinLocY[ii]);
                                ctx.lineTo(kinLocX[ii + 1] + 1, kinLocY[ii + 1]); 
                                ctx.stroke(); 

                                ctx.beginPath();
                                ctx.lineWidth = 3;
                                ctx.strokeStyle = "#ff0000";
                                ctx.moveTo(kinLocX[ii], kinLocY[ii] + 1);
                                ctx.lineTo(kinLocX[ii + 1], kinLocY[ii + 1] + 1); 
                                ctx.stroke(); 

                                ctx.beginPath();
                                ctx.lineWidth = 3;
                                ctx.strokeStyle = "#ff0000";
                                ctx.moveTo(kinLocX[ii] - 1, kinLocY[ii]);
                                ctx.lineTo(kinLocX[ii + 1] - 1, kinLocY[ii + 1]); 
                                ctx.stroke();

                                ctx.beginPath();
                                ctx.lineWidth = 3;
                                ctx.strokeStyle = "#ff0000";
                                ctx.moveTo(kinLocX[ii], kinLocY[ii] - 1);
                                ctx.lineTo(kinLocX[ii + 1], kinLocY[ii + 1] - 1); 
                                ctx.stroke();
  
                            }

                            ctx.beginPath();
                            ctx.lineWidth = 3;
                            ctx.strokeStyle = "#ff0000";
                            ctx.moveTo(p1Save_x, p1Save_y);
                            ctx.lineTo(p2Save_x, p2Save_y); 
                            ctx.stroke();

                            ctx.beginPath();
                            ctx.lineWidth = 3;
                            ctx.strokeStyle = "#ff0000";
                            ctx.moveTo(p1Save_x + 1, p1Save_y);
                            ctx.lineTo(p2Save_x + 1, p2Save_y); 
                            ctx.stroke();

                            ctx.beginPath();
                            ctx.lineWidth = 3;
                            ctx.strokeStyle = "#ff0000";
                            ctx.moveTo(p1Save_x, p1Save_y + 1);
                            ctx.lineTo(p2Save_x, p2Save_y + 1); 
                            ctx.stroke(); 

                            ctx.beginPath();
                            ctx.lineWidth = 3;
                            ctx.strokeStyle = "#ff0000";
                            ctx.moveTo(p1Save_x - 1, p1Save_y);
                            ctx.lineTo(p2Save_x - 1, p2Save_y + 1); 
                            ctx.stroke();

                            ctx.beginPath();
                            ctx.lineWidth = 3;
                            ctx.strokeStyle = "#ff0000";
                            ctx.moveTo(p1Save_x, p1Save_y- 1);
                            ctx.lineTo(p2Save_x, p2Save_y - 1); 
                            ctx.stroke(); 
                        }
                      }
                  }
                   // SelectStop2(); 
              }

          }
          //initPDFData();

                    uploadTest.enabled = true; 
                    normalColor = "#ffffff";
                    colors="#ffffff";
                    pressedColor ="#808080";
                    highlightedColor = "#ffffff";
                    uploadTest= colors;


                    measureReactionTime = false;
                    if (autoPtosisReport)
                    {
                        if (ptosisReportIndex == 0)
                        {
                            TestWithEyeTaped();
                        }
                        else
                        {
                            //StartCoroutine(queryGenerateReport());
                        }
                    }
                    else
                    {
                       // StartCoroutine(queryGenerateReport());
                    }

        }else{
          //Data sent not complete. resend
          numDataTxAttempts++;
          msg = "RESEND_VR_DATA|";
          //sendMessageString(msg, false);
        }
}else{
     // StartCoroutine(queryGenerateReport());
  }

}
function setpixcelVF_KIN_RESULT(data) {
  var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d");
  var  splitData= data.split("|");
  splitData[5]=parseFloat(splitData[5])*-1;

  theta = parseFloat(splitData[2]);
  Phi = parseFloat(splitData[3]);
  linearDispFactor = 1000 / (2 * 8 * 10);

  R = Phi;
  R1 = R * linearDispFactor;
  if (selectEye == 1)  //1=OD, 0=OS
  {
      x = R1 * Math.cos(theta * Math.PI / 180);
  }
  else
  {
      x = -R1 * Math.cos(theta * Math.PI / 180);
  }

  y = R1 * Math.sin(theta * Math.PI / 180); 
  y=y*-1;
  x1 = Math.round((x) + (size / 2));
  y1 = Math.round((y) + (size / 2));
   if(completedDeviveStatus==true){
  var vfpointdata = new Object();  
  vfpointdata.x = splitData[4];
  vfpointdata.y = splitData[5]; 
  vfpointdata.x1 = x1;
  vfpointdata.y1 = y1; 
  vfpointdata.intensity = splitData[6];
  vfpointdata.size = stimulusSize;
  vfpointdata.STD = "NA";
  vfpointdata.index = splitData[7];
  if (datas.vfpointdata.length>0){
      datas.vfpointdata.push(vfpointdata); 
  }
  else
  { 
    datas.vfpointdata.push(vfpointdata);
  } 
}
 k = parseInt(splitData[7]);
 nsize = 5;
 
 if (autoPtosisReport==true)
  {
      if (msgCountTotal >= 18)
      {
          if(msgCount < 9)
          { 
              ctx.beginPath();
              ctx.strokeStyle = "#ff0000";
              ctx.rect(x1, y1, nsize, nsize);
              ctx.stroke();
              ctx.beginPath();
              ctx.strokeStyle = "#ff0000";
              ctx.rect(x1, y1, nsize- 1, nsize- 1);
              ctx.stroke();
              ctx.beginPath();
              ctx.strokeStyle = "#ff0000";
              ctx.rect(x1, y1, nsize+ 1, nsize+ 1);
              ctx.stroke();
              ctx.beginPath();
              ctx.strokeStyle = "#ff0000";
              ctx.rect(x1, y1, nsize-2, nsize-2);
              ctx.stroke();
              ctx.beginPath();
              ctx.strokeStyle = "#ff0000";
              ctx.rect(x1, y1, nsize+2, nsize+2);
              ctx.stroke();

              kinLocX[msgCount] = x1;
              kinLocY[msgCount] = y1;
              kinPhi[msgCount] = Phi;
              kinTheta[msgCount] = theta;
          }
          else
          {
              
              ctx.beginPath();
              ctx.strokeStyle = '#0000ff';
              ctx.arc(Math.round(x1), Math.round(y1), 10, 0, 2 * Math.PI);
              ctx.stroke();
              ctx.beginPath();
              ctx.strokeStyle = '#0000ff';
              ctx.arc(Math.round(x1), Math.round(y1), 9, 0, 2 * Math.PI);
              ctx.stroke();
              ctx.beginPath();
              ctx.strokeStyle = '#0000ff';
              ctx.arc(Math.round(x1), Math.round(y1), 8, 0, 2 * Math.PI);
              ctx.stroke();
              ctx.beginPath();
              ctx.strokeStyle = '#0000ff';
              ctx.arc(Math.round(x1), Math.round(y1), 7, 0, 2 * Math.PI);
              ctx.stroke();
              ctx.beginPath();
              ctx.strokeStyle = '#0000ff';
              ctx.arc(Math.round(x1), Math.round(y1), 6, 0, 2 * Math.PI);
              ctx.stroke();
              ctx.beginPath();
              ctx.strokeStyle = '#0000ff';
              ctx.arc(Math.round(x1), Math.round(y1), 5, 0, 2 * Math.PI);
              ctx.stroke();
              kinLocX[msgCount] = x1;
              kinLocY[msgCount] = y1;
              kinPhi[msgCount] = Phi;
              kinTheta[msgCount] = theta;
          }
      }
      else
      {
          if (ptosisReportIndex == 0)
          {
              ctx.beginPath();
              ctx.strokeStyle = "#ff0000";
              ctx.rect(x1, y1, nsize, nsize);
              ctx.stroke();
              ctx.beginPath();
              ctx.strokeStyle = "#ff0000";
              ctx.rect(x1, y1, nsize- 1, nsize- 1);
              ctx.stroke();
              ctx.beginPath();
              ctx.strokeStyle = "#ff0000";
              ctx.rect(x1, y1, nsize+ 1, nsize+ 1);
              ctx.stroke();
              ctx.beginPath();
              ctx.strokeStyle = "#ff0000";
              ctx.rect(x1, y1, nsize-2, nsize-2);
              ctx.stroke();
              ctx.beginPath();
              ctx.strokeStyle = "#ff0000";
              ctx.rect(x1, y1, nsize+2, nsize+2);
              ctx.stroke();

              kinLocX[k] = x1;
              kinLocY[k] = y1;
              kinPhi[k] = Phi;
              kinTheta[k] = theta;
          }
          else
          {
             
              ctx.beginPath();
              ctx.strokeStyle = '#0000ff';
              ctx.arc(Math.round(x1), Math.round(y1), 10, 0, 2 * Math.PI);
              ctx.stroke();
              ctx.beginPath();
              ctx.strokeStyle = '#0000ff';
              ctx.arc(Math.round(x1), Math.round(y1), 9, 0, 2 * Math.PI);
              ctx.stroke();
              ctx.beginPath();
              ctx.strokeStyle = '#0000ff';
              ctx.arc(Math.round(x1), Math.round(y1), 8, 0, 2 * Math.PI);
              ctx.stroke();
              ctx.beginPath();
              ctx.strokeStyle = '#0000ff';
              ctx.arc(Math.round(x1), Math.round(y1), 7, 0, 2 * Math.PI);
              ctx.stroke();
              ctx.beginPath();
              ctx.strokeStyle = '#0000ff';
              ctx.arc(Math.round(x1), Math.round(y1), 6, 0, 2 * Math.PI);
              ctx.stroke();
              ctx.beginPath();
              ctx.strokeStyle = '#0000ff';
              ctx.arc(Math.round(x1), Math.round(y1), 5, 0, 2 * Math.PI);
              ctx.stroke();


              k = k + 9;
              kinLocX[k] = x1;
              kinLocY[k] = y1;
              kinPhi[k] = Phi;
              kinTheta[k] = theta;
          }
      }
  }else
  { 
    ctx.beginPath();
    ctx.strokeStyle = "#ff0000";
    ctx.rect(x1, y1, nsize, nsize);
    ctx.stroke();
    ctx.beginPath();
    ctx.strokeStyle = "#ff0000";
    ctx.rect(x1, y1, nsize- 1, nsize- 1);
    ctx.stroke();
    ctx.beginPath();
    ctx.strokeStyle = "#ff0000";
    ctx.rect(x1, y1, nsize+ 1, nsize+ 1);
    ctx.stroke();
    ctx.beginPath();
    ctx.strokeStyle = "#ff0000";
    ctx.rect(x1, y1, nsize-2, nsize-2);
    ctx.stroke();
    ctx.beginPath();
    ctx.strokeStyle = "#ff0000";
    ctx.rect(x1, y1, nsize+2, nsize+2);
    ctx.stroke();

    kinLocX[k] = x1;
    kinLocY[k] = y1;
    kinPhi[k] = Phi;
    kinTheta[k] = theta;
  }
 
  if(completedDeviveStatus==true){
    var new_colour="#ff0000"
       if (autoPtosisReport==true){
          if (ptosisReportIndex == 1){
              new_colour="#0000ff"
          }  
       }  
  
  if(datas.vfpointdata.length==9){ // || datas.vfpointdata.length==18
    for(ni=0; ni<8; ni++){
      ox1=datas.vfpointdata[ni].x1;
      oy1=datas.vfpointdata[ni].y1;
      ox2=datas.vfpointdata[ni+1].x1;
      oy2=datas.vfpointdata[ni+1].y1;
      ctx.beginPath();
      ctx.strokeStyle = new_colour;
      ctx.moveTo(Math.round(ox1), Math.round(oy1));
      ctx.lineTo(Math.round(ox2), Math.round(oy2));
      ctx.stroke(); 
    }
    ox1=datas.vfpointdata[0].x1;
    oy1=datas.vfpointdata[0].y1;
    ox2=datas.vfpointdata[8].x1;
    oy2=datas.vfpointdata[8].y1;
    ctx.beginPath();
    ctx.strokeStyle = new_colour;
    ctx.moveTo(Math.round(ox1), Math.round(oy1));
    ctx.lineTo(Math.round(ox2), Math.round(oy2));
    ctx.stroke(); 
  }
  if(datas.vfpointdata.length==18){ // || datas.vfpointdata.length==18
    for(ni=9; ni<17; ni++){
      ox1=datas.vfpointdata[ni].x1;
      oy1=datas.vfpointdata[ni].y1;
      ox2=datas.vfpointdata[ni+1].x1;
      oy2=datas.vfpointdata[ni+1].y1;
      ctx.beginPath();
      ctx.strokeStyle = new_colour;
      ctx.moveTo(Math.round(ox1), Math.round(oy1));
      ctx.lineTo(Math.round(ox2), Math.round(oy2));
      ctx.stroke(); 
    }
     for(ni=0; ni<8; ni++){ 
      ox1=datas.vfpointdata[ni].x1;
      oy1=datas.vfpointdata[ni].y1;
      ox2=datas.vfpointdata[ni+1].x1;
      oy2=datas.vfpointdata[ni+1].y1;

      ctx.beginPath();
      ctx.strokeStyle = "#ff0000";
      ctx.rect(ox1, oy1, nsize, nsize);
      ctx.stroke();
      ctx.beginPath();
      ctx.strokeStyle = "#ff0000";
      ctx.rect(ox1, oy1, nsize- 1, nsize- 1);
      ctx.stroke();
      ctx.beginPath();
      ctx.strokeStyle = "#ff0000";
      ctx.rect(ox1, oy1, nsize+ 1, nsize+ 1);
      ctx.stroke();
      ctx.beginPath();
      ctx.strokeStyle = "#ff0000";
      ctx.rect(ox1, oy1, nsize-2, nsize-2);
      ctx.stroke();
      ctx.beginPath();
      ctx.strokeStyle = "#ff0000";
      ctx.rect(ox1, oy1, nsize+2, nsize+2);
      ctx.stroke(); 

      ctx.beginPath();
      ctx.strokeStyle = "#ff0000";
      ctx.moveTo(Math.round(ox1), Math.round(oy1));
      ctx.lineTo(Math.round(ox2), Math.round(oy2));
      ctx.stroke(); 
    }  
    ctx.beginPath();
    ctx.strokeStyle = "#ff0000";
    ctx.rect(ox2, oy2, nsize, nsize);
    ctx.stroke();
    ctx.beginPath();
    ctx.strokeStyle = "#ff0000";
    ctx.rect(ox2, oy2, nsize- 1, nsize- 1);
    ctx.stroke();
    ctx.beginPath();
    ctx.strokeStyle = "#ff0000";
    ctx.rect(ox2, oy2, nsize+ 1, nsize+ 1);
    ctx.stroke();
    ctx.beginPath();
    ctx.strokeStyle = "#ff0000";
    ctx.rect(ox2, oy2, nsize-2, nsize-2);
    ctx.stroke();
    ctx.beginPath();
    ctx.strokeStyle = "#ff0000";
    ctx.rect(ox2, oy2, nsize+2, nsize+2);
    ctx.stroke();

    ox1=datas.vfpointdata[0].x1;
    oy1=datas.vfpointdata[0].y1;
    ox2=datas.vfpointdata[8].x1;
    oy2=datas.vfpointdata[8].y1;
    ctx.beginPath();
    ctx.strokeStyle = "ff0000";
    ctx.moveTo(Math.round(ox1), Math.round(oy1));
    ctx.lineTo(Math.round(ox2), Math.round(oy2));
    ctx.stroke();

    ox1=datas.vfpointdata[9].x1;
    oy1=datas.vfpointdata[9].y1;
    ox2=datas.vfpointdata[17].x1;
    oy2=datas.vfpointdata[17].y1;
    ctx.beginPath();
    ctx.strokeStyle = "0000ff";
    ctx.moveTo(Math.round(ox1), Math.round(oy1));
    ctx.lineTo(Math.round(ox2), Math.round(oy2));
    ctx.stroke(); 
  }
 }

}
function setpixcelBSPOT(data) {
  var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d");
  var  splitData= data.split("|");
  splitData[3]=parseFloat(splitData[3])*-1;
  msgCount++;
  dataReceived = true;
  if (zoomLevel <= 0.2)
  {
    thetaRad = Math.atan(parseFloat(splitData[3]) / parseFloat(splitData[2]));
    theta1 = thetaRad * 180 / Math.PI;
    R2 = parseFloat(splitData[2]) / Math.cos(thetaRad);
    phiRad = Math.atan(R2 / 3.0);
    Phi1 = phiRad * 180 / Math.PI;

    linearDispFactor = 1000 / (2 * 8 * 10);
    R3 = Phi1 * linearDispFactor;
    x = R3 * Math.cos(thetaRad);
    y = R3 * Math.sin(thetaRad);

    x1 = Math.round((x) + (size / 2));
    y1 = Math.round((y) + (size / 2));
  }else
  {
    zoomOffset = 3.0 / zoomLevel;
    zoomMult = 1000.0 / (2 * zoomOffset);
    x = parseFloat(splitData[2]) + zoomOffset;
    y = parseFloat(splitData[3]) + zoomOffset;
    x1 = Math.round(x * zoomMult);
    y1 = Math.round(y * zoomMult);
  }
  CEN = parseFloat(splitData[5]);

  value = parseInt(splitData[4]);
  if ((value == 0) && (!blindSpotShown))
  {
    for ( i = 0; i < 3; i++)
    {
      if (testTypeName != "Central_10_2")
      {
        
        ctx.beginPath();
        ctx.lineWidth = 3;
        ctx.strokeStyle = "#ff0000";
        ctx.moveTo(x1 - (15 + i), y1 + (15 + i));
        ctx.lineTo(x1 + (15 + i), y1 + (15 + i)); 

        ctx.moveTo(x1, y1 - (15+ i));
        ctx.lineTo(x1 - (15 + i), y1 + (15 + i));
        
        ctx.moveTo(x1, y1 - (15+ i));
        ctx.lineTo(x1 + (15 + i), y1 + (15 + i));  
        
        ctx.stroke(); 
      }
    }
  }
  if(value == 0){
    var vfpointdata = new Object();  
    vfpointdata.x = splitData[2];
    vfpointdata.y = splitData[3];
    vfpointdata.intensity = CEN;
    vfpointdata.size = "99";
    vfpointdata.STD = "0";
    vfpointdata.index = "0";

    if (datas.vfpointdata.length>0){
      datas.vfpointdata.push(vfpointdata); 
    }
    else
    { 
      datas.vfpointdata.push(vfpointdata);
    }
  }
}
//function setpixcelVF_SC1_RESULT(data) {
  const setpixcelVF_SC1_RESULT = async function(data) {
  var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d");
   numTestPointsCompleted++;
  msgCount++;
  TestStatus = "(" + numTestPointsCompleted + "/" + numTestPoints + ")";
   setTestStatus(TestStatus);
   
  var  splitData= data.split("|");
  splitData[3]=parseFloat(splitData[3])*-1;
  if (zoomLevel <= 0.2)
  {
    thetaRad = Math.atan(parseFloat(splitData[3]) / parseFloat(splitData[2]));
    theta1 = thetaRad * 180 / Math.PI;
    R2 = parseFloat(splitData[2]) / Math.cos(thetaRad);
    phiRad = Math.atan(R2 / 3.0);
    Phi1 = phiRad * 180 / Math.PI;

    linearDispFactor = 1000 / (2 * 8 * 10);
    R3 = Phi1 * linearDispFactor;
    x = R3 * Math.cos(thetaRad);
    y = R3 * Math.sin(thetaRad);

    x1 = Math.round((x) + (size / 2));
    y1 = Math.round((y) + (size / 2));
  }else
  {
    zoomOffset = 3.0 / zoomLevel;
    zoomMult = 1000.0 / (2 * zoomOffset);
    x = parseFloat(splitData[2]) + zoomOffset;
    y = parseFloat(splitData[3]) + zoomOffset;
    x1 =Math.round(x * zoomMult);
    y1 =Math.round(y * zoomMult);
  }
  value = Math.round(parseFloat(splitData[4]));
   
 switch (value)
  {
      case 0:
        color = "#ff0000";
        numPointsMissed++;
 
        if (zoomLevel > 0.5)
        { 
          for (x2 = -2; x2 <= 2; x2++)
          {  
            ctx.lineWidth = 3;
            ctx.beginPath();
            ctx.strokeStyle = color;
            ctx.moveTo(Math.round(x1 - CONST_POINT_DISPLAY_SIZE + x2), Math.round(y1 - CONST_POINT_DISPLAY_SIZE));
            ctx.lineTo(Math.round(x1 + CONST_POINT_DISPLAY_SIZE - 1 + x2), Math.round(y1 + CONST_POINT_DISPLAY_SIZE - 1));
            ctx.moveTo(Math.round(x1 + CONST_POINT_DISPLAY_SIZE - 1 + x2), Math.round(y1 - CONST_POINT_DISPLAY_SIZE));
            ctx.lineTo(Math.round(x1 - CONST_POINT_DISPLAY_SIZE - 1 + x2), Math.round(y1 + CONST_POINT_DISPLAY_SIZE - 1));  
            ctx.stroke(); 
          }
        }
        else
        {
          for ( x2 = -2; x2 <= 2; x2++)
          { 
            ctx.lineWidth = 3;
            ctx.beginPath();
            ctx.strokeStyle = color;
            ctx.moveTo(Math.round(x1 - CONST_POINT_DISPLAY_SIZE_SMALL + x2), Math.round(y1 - CONST_POINT_DISPLAY_SIZE_SMALL));
            ctx.lineTo(Math.round(x1 + CONST_POINT_DISPLAY_SIZE_SMALL - 1 + x2), Math.round(y1 + CONST_POINT_DISPLAY_SIZE_SMALL - 1));  
            ctx.moveTo(Math.round(x1 + CONST_POINT_DISPLAY_SIZE_SMALL - 1 + x2), Math.round(y1 - CONST_POINT_DISPLAY_SIZE_SMALL));
            ctx.lineTo(Math.round(x1 - CONST_POINT_DISPLAY_SIZE_SMALL - 1 + x2), Math.round(y1 + CONST_POINT_DISPLAY_SIZE_SMALL - 1));  
            ctx.stroke(); 
          }
        }
        break;
        case 1:
            color = "#00ff00";
            numPointsSeen++;
            if (zoomLevel > 0.5)
            {
              for (x2 = x1 -CONST_POINT_DISPLAY_SIZE; x2 < x1 + CONST_POINT_DISPLAY_SIZE; x2++)
              {
                for (y2 = y1 -CONST_POINT_DISPLAY_SIZE; y2 < y1 + CONST_POINT_DISPLAY_SIZE; y2++)
                {
                  ctx.fillStyle = color;
                  ctx.fillRect(x2,y2,3,3);
                }
              }
            }
            else
            {
              for ( x2 = x1 -CONST_POINT_DISPLAY_SIZE_SMALL; x2 < x1 + CONST_POINT_DISPLAY_SIZE_SMALL; x2++)
              {
                for (y2 = y1 - CONST_POINT_DISPLAY_SIZE_SMALL; y2 < y1 +CONST_POINT_DISPLAY_SIZE_SMALL; y2++)
                {
                  ctx.fillStyle = color;
                  ctx.fillRect(x2,y2,3,3);
                }
              }
            }
            break;
          default:
            color = Color.black;
            break;
  }
  var vfpointdata = new Object();  
  vfpointdata.x = splitData[2];
  vfpointdata.y = splitData[3];
  vfpointdata.intensity = splitData[5];
  vfpointdata.size = stimulusSize;
  vfpointdata.STD = "0";
  vfpointdata.index = "0";

  if (datas.vfpointdata.length>0){
    datas.vfpointdata.push(vfpointdata); 
  }
  else
  { 
    datas.vfpointdata.push(vfpointdata);
  }
}
 const setpixcelVF_SC2_RESULT = async function(data) {
//function setpixcelVF_SC2_RESULT(data) {
   var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d");
   numTestPointsCompleted++;
  msgCount++;
  TestStatus = "(" + numTestPointsCompleted + "/" + numTestPoints + ")";
  setTestStatus(TestStatus);
  var  splitData= data.split("|");
  splitData[3]=parseFloat(splitData[3])*-1;
  if (zoomLevel <= 0.2)
  {
    thetaRad = Math.atan(parseFloat(splitData[3]) / parseFloat(splitData[2]));
    theta1 = thetaRad * 180 / Math.PI;
    R2 = parseFloat(splitData[2]) / Math.cos(thetaRad);
    phiRad = Math.atan(R2 / 3.0);
    Phi1 = phiRad * 180 / Math.PI;

    linearDispFactor = 1000 / (2 * 8 * 10);
    R3 = Phi1 * linearDispFactor;
    x = R3 * Math.cos(thetaRad);
    y = R3 * Math.sin(thetaRad);

    x1 = Math.round((x) + (size / 2));
    y1 = Math.round((y) + (size / 2));
  }else
  {
    zoomOffset = 3.0 / zoomLevel;
    zoomMult = 1000.0 / (2 * zoomOffset);
    x = parseFloat(splitData[2]) + zoomOffset;
    y = parseFloat(splitData[3]) + zoomOffset;
    x1 =Math.round(x * zoomMult);
    y1 =Math.round(y * zoomMult);
  }
  value = Math.round(parseFloat(splitData[4]));
   
 switch (value)
  {
      case 0:
        color = "#ff0000";
        numPointsMissed++;
 
        if (zoomLevel > 0.5)
        { 
          for (x2 = -2; x2 <= 2; x2++)
          {  
            ctx.lineWidth = 3;
            ctx.beginPath();
            ctx.strokeStyle = color;
            ctx.moveTo(Math.round(x1 - CONST_POINT_DISPLAY_SIZE + x2), Math.round(y1 - CONST_POINT_DISPLAY_SIZE));
            ctx.lineTo(Math.round(x1 + CONST_POINT_DISPLAY_SIZE - 1 + x2), Math.round(y1 + CONST_POINT_DISPLAY_SIZE - 1));
            ctx.moveTo(Math.round(x1 + CONST_POINT_DISPLAY_SIZE - 1 + x2), Math.round(y1 - CONST_POINT_DISPLAY_SIZE));
            ctx.lineTo(Math.round(x1 - CONST_POINT_DISPLAY_SIZE - 1 + x2), Math.round(y1 + CONST_POINT_DISPLAY_SIZE - 1));  
            ctx.stroke(); 
          }
        }
        else
        {
          for ( x2 = -2; x2 <= 2; x2++)
          { 
            ctx.lineWidth = 3;
            ctx.beginPath();
            ctx.strokeStyle = color;
            ctx.moveTo(Math.round(x1 - CONST_POINT_DISPLAY_SIZE_SMALL + x2), Math.round(y1 - CONST_POINT_DISPLAY_SIZE_SMALL));
            ctx.lineTo(Math.round(x1 + CONST_POINT_DISPLAY_SIZE_SMALL - 1 + x2), Math.round(y1 + CONST_POINT_DISPLAY_SIZE_SMALL - 1));  
            ctx.moveTo(Math.round(x1 + CONST_POINT_DISPLAY_SIZE_SMALL - 1 + x2), Math.round(y1 - CONST_POINT_DISPLAY_SIZE_SMALL));
            ctx.lineTo(Math.round(x1 - CONST_POINT_DISPLAY_SIZE_SMALL - 1 + x2), Math.round(y1 + CONST_POINT_DISPLAY_SIZE_SMALL - 1));  
            ctx.stroke(); 
          }
        }
        break;
        case 1:
            color = "#00ff00";
            numPointsSeen++;
            for ( x2 = x1 - CONST_POINT_DISPLAY_SIZE; x2 < x1 + CONST_POINT_DISPLAY_SIZE; x2++)
            {
              for ( y2 = y1 -CONST_POINT_DISPLAY_SIZE; y2 < y1 +CONST_POINT_DISPLAY_SIZE; y2++)
              { 
                ctx.fillStyle = color;
                ctx.fillRect(x2,y2,3,3);
              }
            } 
            break;
          default:
            color = Color.black;
            break;
  }
  var vfpointdata = new Object();  
  vfpointdata.x = splitData[2];
  vfpointdata.y = splitData[3];
  vfpointdata.intensity = splitData[5];
  vfpointdata.size = stimulusSize;
  vfpointdata.STD = "0";
  vfpointdata.index = "0";

  if (datas.vfpointdata.length>0){
    datas.vfpointdata.push(vfpointdata); 
  }
  else
  { 
    datas.vfpointdata.push(vfpointdata);
  }
}
 const setpixcelVF_SC3_RESULT = async function(data) {
//function setpixcelVF_SC3_RESULT(data) {
  var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d");
  numTestPointsCompleted++;
  msgCount++;
  TestStatus = "(" + numTestPointsCompleted + "/" + numTestPoints + ")";
  setTestStatus(TestStatus);
  var  splitData= data.split("|");
  splitData[3]=parseFloat(splitData[3])*-1;
  if (zoomLevel <= 0.2)
  {
    thetaRad = Math.atan(parseFloat(splitData[3]) / parseFloat(splitData[2]));
    theta1 = thetaRad * 180 / Math.PI;
    R2 = parseFloat(splitData[2]) / Math.cos(thetaRad);
    phiRad = Math.atan(R2 / 3.0);
    Phi1 = phiRad * 180 / Math.PI;

    linearDispFactor = 1000 / (2 * 8 * 10);
    R3 = Phi1 * linearDispFactor;
    x = R3 * Math.cos(thetaRad);
    y = R3 * Math.sin(thetaRad);

    x1 = Math.round((x) + (size / 2));
    y1 = Math.round((y) + (size / 2));
  }else
  {
    zoomOffset = 3.0 / zoomLevel;
    zoomMult = 1000.0 / (2 * zoomOffset);
    x = parseFloat(splitData[2]) + zoomOffset;
    y = parseFloat(splitData[3]) + zoomOffset;
    x1 =Math.round(x * zoomMult);
    y1 =Math.round(y * zoomMult);
  }
  value = Math.round(parseFloat(splitData[4]));
   switch (value)
  {
    case 1:
      color = "#00ff00";
      numPointsSeen++;
      for (x2 = x1 -CONST_POINT_DISPLAY_SIZE; x2 < x1 + CONST_POINT_DISPLAY_SIZE; x2++)
      {
        for ( y2 = y1 -CONST_POINT_DISPLAY_SIZE; y2 < y1 + CONST_POINT_DISPLAY_SIZE; y2++)
        { 
          ctx.fillStyle = color;
          ctx.fillRect(x2,y2,3,3);
        }
      }
      break;
      case 2:
      color ="#000000";
      numPointsRelative++;
      for (x2 = x1 - CONST_POINT_DISPLAY_SIZE; x2 < x1 -CONST_POINT_DISPLAY_SIZE + 2; x2++)
      {
        for (y2 = y1 - CONST_POINT_DISPLAY_SIZE; y2 < y1 + CONST_POINT_DISPLAY_SIZE; y2++)
        {
          ctx.fillStyle = color;
          ctx.fillRect(x2,y2,3,3); 
        }
      }
      for ( x2 = x1 + CONST_POINT_DISPLAY_SIZE - 2; x2 < x1 + CONST_POINT_DISPLAY_SIZE; x2++)
      {
        for ( y2 = y1 - CONST_POINT_DISPLAY_SIZE; y2 < y1 + CONST_POINT_DISPLAY_SIZE; y2++)
        {
          ctx.fillStyle = color;
          ctx.fillRect(x2,y2,3,3); 
        }
      }

      for ( y2 = y1 - CONST_POINT_DISPLAY_SIZE; y2 < y1 - CONST_POINT_DISPLAY_SIZE + 2; y2++)
      {
        for ( x2 = x1 - CONST_POINT_DISPLAY_SIZE; x2 < x1 + CONST_POINT_DISPLAY_SIZE; x2++)
        {
          ctx.fillStyle = color;
          ctx.fillRect(x2,y2,3,3);
        }
      }
      for ( y2 = y1 + CONST_POINT_DISPLAY_SIZE - 2; y2 < y1 + CONST_POINT_DISPLAY_SIZE; y2++)
      {
        for ( x2 = x1 - CONST_POINT_DISPLAY_SIZE; x2 < x1 +CONST_POINT_DISPLAY_SIZE; x2++)
        {
          ctx.fillStyle = color;
          ctx.fillRect(x2,y2,3,3);
        }
      }
      break;
      case 3:
            color = "#ff0000";
            numPointsMissed++; 
            if (zoomLevel > 0.5)
            {
              for ( x2 = -2; x2 <= 2; x2++)
              {
                ctx.lineWidth = 3;
                ctx.beginPath();
                ctx.strokeStyle = "#ff0000";
                ctx.moveTo(Math.round(x1 - CONST_POINT_DISPLAY_SIZE + x2), Math.round(y1 - CONST_POINT_DISPLAY_SIZE));
                ctx.lineTo(Math.round(x1 + CONST_POINT_DISPLAY_SIZE - 1 + x2), Math.round(y1 + CONST_POINT_DISPLAY_SIZE - 1)); 
                ctx.moveTo(Math.round(x1 + CONST_POINT_DISPLAY_SIZE - 1 + x2), Math.round(y1 - CONST_POINT_DISPLAY_SIZE));
                ctx.lineTo(Math.round(x1 - CONST_POINT_DISPLAY_SIZE - 1 + x2), Math.round(y1 + CONST_POINT_DISPLAY_SIZE - 1));  
                ctx.stroke(); 
              }
            }
            else
            {
              for (x2 = -2; x2 <= 2; x2++)
              { 
                ctx.lineWidth = 3;
                ctx.beginPath();
                ctx.strokeStyle = color;
                ctx.moveTo(x1 -CONST_POINT_DISPLAY_SIZE_SMALL + x2, y1 -CONST_POINT_DISPLAY_SIZE_SMALL);
                ctx.lineTo(x1 + CONST_POINT_DISPLAY_SIZE_SMALL - 1 + x2, y1 + CONST_POINT_DISPLAY_SIZE_SMALL - 1); 
                ctx.moveTo(x1 + CONST_POINT_DISPLAY_SIZE_SMALL - 1 + x2, y1 - CONST_POINT_DISPLAY_SIZE_SMALL);
                ctx.lineTo(x1 - CONST_POINT_DISPLAY_SIZE_SMALL - 1 + x2, y1 + CONST_POINT_DISPLAY_SIZE_SMALL - 1);  
                ctx.stroke();

              }
            } 
            break;
          default:
            color = "#000000";
            break;
  }
  var vfpointdata = new Object();  
  vfpointdata.x = splitData[2];
  vfpointdata.y = splitData[3];
  vfpointdata.intensity = splitData[5];
  vfpointdata.size = stimulusSize;
  vfpointdata.STD = "0";
  vfpointdata.index = "0";

  if (datas.vfpointdata.length>0){
    datas.vfpointdata.push(vfpointdata); 
  }
  else
  { 
    datas.vfpointdata.push(vfpointdata);
  }
}
 const setpixcelVF_SC4_RESULT = async function(data) {
//function setpixcelVF_SC4_RESULT(data) {
   var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d");
  numTestPointsCompleted++;
  msgCount++;
  TestStatus = "(" + numTestPointsCompleted + "/" + numTestPoints + ")";
  setTestStatus(TestStatus);
  var  splitData= data.split("|");
  splitData[3]=parseFloat(splitData[3])*-1;
  if (zoomLevel <= 0.2)
  {
    thetaRad = Math.atan(parseFloat(splitData[3]) / parseFloat(splitData[2]));
    theta1 = thetaRad * 180 / Math.PI;
    R2 = parseFloat(splitData[2]) / Math.cos(thetaRad);
    phiRad = Math.atan(R2 / 3.0);
    Phi1 = phiRad * 180 / Math.PI;

    linearDispFactor = 1000 / (2 * 8 * 10);
    R3 = Phi1 * linearDispFactor;
    x = R3 * Math.cos(thetaRad);
    y = R3 * Math.sin(thetaRad);

    x1 = Math.round((x) + (size / 2));
    y1 = Math.round((y) + (size / 2));
  }else
  {
    zoomOffset = 3.0 / zoomLevel;
    zoomMult = 1000.0 / (2 * zoomOffset);
    x = parseFloat(splitData[2]) + zoomOffset;
    y = parseFloat(splitData[3]) + zoomOffset;
    x1 =Math.round(x * zoomMult);
    y1 =Math.round(y * zoomMult);
  }
  value = Math.round(parseFloat(splitData[4]));
  var color='';
  if (value != 4)
  {
    switch (value)
    {
      case 1:
        color = "#00ff00";
        numPointsSeen++;
        break;
      case 2:
        color = "#808080";
        break;
      case 3:
        color = "#ff0000";
        numPointsMissed++;
        break;
      case 4:
        color = "#0000ff";
        break;
      default:
        color = "#000000";
        break;
    }
    for ( x2 = x1 -CONST_POINT_DISPLAY_SIZE; x2 < x1 + CONST_POINT_DISPLAY_SIZE; x2++)
    {
      for (y2 = y1 - CONST_POINT_DISPLAY_SIZE; y2 < y1 + CONST_POINT_DISPLAY_SIZE; y2++)
      {
        ctx.fillStyle = color;
        ctx.fillRect(x2,y2,3,3);
      }
    }
  }else{
    if (zoomLevel > 0.5)
        {
          for ( x2 = x1 - 20; x2 <= x1 + 20; x2++)
          {
            for ( y2 = y1 - 20; y2 <= y1 + 20; y2++)
            {
              ctx.fillStyle = "#ffffff";
              ctx.fillRect(x2,y2,3,3); 
            }
          }
          label = splitData[5]; 
          ctx.fillStyle = "#000000";
          ctx.font = "33px Arial";   
          ctx.fillText(label,Math.round(x1 - 15),Math.round(y1 + 12));
          ctx.stroke();
        }
        else
        {
          for ( x2 = x1 - 8; x2 <= x1 + 8; x2++)
          {
            for ( y2 = y1 - 8; y2 <= y1 + 8; y2++)
            {
              ctx.fillStyle = "#ffffff";
              ctx.fillRect(x2,y2,3,3);
            }
          }
          label = value;
          ctx.fillStyle = "#000000";
          ctx.font = "33px Arial";   
          ctx.fillText(label,Math.round(x1 - 15),Math.round(y1 + 12));
          ctx.stroke();
        }
  }
  var vfpointdata = new Object();  
  vfpointdata.x = splitData[2];
  vfpointdata.y = splitData[3];
  vfpointdata.intensity = splitData[5];
  vfpointdata.size = stimulusSize;
  vfpointdata.STD = "0";
  vfpointdata.index = "0";

  if (datas.vfpointdata.length>0){
    datas.vfpointdata.push(vfpointdata); 
  }
  else
  { 
    datas.vfpointdata.push(vfpointdata);
  }
}
 const setpixcelVF_RESULT = async function(data) {
//function setpixcelVF_RESULT(data) {
  var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d");
  numTestPointsCompleted++;
  msgCount++;
  TestStatus= "(" + numTestPointsCompleted + "/" + numTestPoints + ")";
  setTestStatus(TestStatus);
  var  splitData= data.split("|");
  splitData[3]=parseFloat(splitData[3])*-1;
  dataReceived = true;
  zoomOffset = 3.0 / zoomLevel;
  zoomMult = 1000.0 / (2 * zoomOffset);
  x = parseFloat(splitData[2]) + zoomOffset;
  y = parseFloat(splitData[3]) + zoomOffset;
  x1 =  Math.round(x * zoomMult);
  y1 = Math.round(y * zoomMult);
  Intensity =  Math.round(parseFloat(splitData[5]));
  stmLocX[VF_ResultIndex] = parseFloat(splitData[2]);
  stmLocY[VF_ResultIndex] = parseFloat(splitData[3]);
  stmDecibles[VF_ResultIndex] = parseFloat(splitData[5]);
  stmDetectTime[VF_ResultIndex] = parseFloat(splitData[6]);
  VF_ResultIndex++;
  if (testType == 1) //Screening FDT
  {
      xx1 = 0.7935 + zoomOffset;
      xx2 = 0.2645 + zoomOffset;
      x4 = Math.round(xx1 * zoomMult);
      y4 = Math.round(xx2 * zoomMult);
      squareSizeA = Math.abs(x4 - y4);

      sizeX = 128;
      sizeY = 128;
       offsetX = sizeX / 2;
       offsetY = sizeY / 2;
       ratioX = 1;
       ratioY = 1;
       tmp;
     // Color val;
       cirVal = Math.pow(sizeX >> 2, 2);
       xOffset = x1 - (squareSizeA / 2);
       yOffset = y1 - (squareSizeA / 2);
       sx = (squareSizeA - sizeX) / 2;
       sy = (squareSizeA - sizeY) / 2;
       if ((Math.abs(parseFloat(splitData[2])) < 0.01) && (Math.abs(parseFloat(splitData[3])) < 0.01))
        {
            ctx.beginPath(); 
            ctx.strokeStyle = '#000000';
            ctx.arc(Math.round(xOffset + (sizeX >> 1)), Math.round(yOffset + (sizeX >> 1)), sizeX >> 2, 0, 2 * Math.PI);
            ctx.stroke(); 
            value = parseInt(splitData[4]);
            FDT_CenterPointVal = value;
            switch (value)
            {
                case 1:
                for (x2 = -(sizeX >> 2); x2 <= (sizeX >> 2); x2 = x2 + ratioX)
                {
                    for ( y2 = -(sizeY >> 2); y2 <= (sizeY >> 2); y2 = y2 + ratioY)
                    {
                        tmp = (x2 * x2) + (y2 * y2);
                        if (tmp < cirVal)
                        {
                           // val = vfResultBitmap[0].GetPixel((x2), (y2));
                           // tex2.SetPixel(xOffset + (sizeX >> 1) + ((x2 - sx)), yOffset + (sizeX >> 1) + ((y2 - sy)), val);
                           v=getPixel(0,Math.round(x2),Math.round(y2));
                          ctx.fillStyle = v;
                          ctx.fillRect(xOffset + (sizeX >> 1) + ((x2 - sx)),yOffset + (sizeX >> 1) + ((y2 - sy)),3,3);
                        }
                    }
                }
                break;
                case 2:
                 FDT_ScreeningResult = 1;
                  for ( x2 = -(sizeX >> 2); x2 <= (sizeX >> 2); x2 = x2 + ratioX)
                  {
                      for ( y2 = -(sizeY >> 2); y2 <= (sizeY >> 2); y2 = y2 + ratioY)
                      {
                          tmp = (x2 * x2) + (y2 * y2);
                          if (tmp < cirVal)
                          {
                              // val = vfResultBitmap[3].GetPixel((x2), (y2));
                              // tex2.SetPixel(xOffset + (sizeX >> 1) + ((x2 - sx)), yOffset + (sizeX >> 1) + ((y2 - sy)), val);
                             v=getPixel(3,Math.round(x2),Math.round(y2));
                              ctx.fillStyle = v;
                              ctx.fillRect(xOffset + (sizeX >> 1) + ((x2 - sx)),yOffset + (sizeX >> 1) + ((y2 - sy)),3,3);
                          }
                      }
                  }
                  break;
                  case 3:
                  FDT_ScreeningResult = 1;
                  for (x2 = -(sizeX >> 2); x2 <= (sizeX >> 2); x2 = x2 + ratioX)
                  {
                      for (y2 = -(sizeY >> 2); y2 <= (sizeY >> 2); y2 = y2 + ratioY)
                      {
                          tmp = (x2 * x2) + (y2 * y2);
                          if (tmp < cirVal)
                          {
                              // val = vfResultBitmap[6].GetPixel((x2), (y2));
                              // tex2.SetPixel(xOffset + (sizeX >> 1) + ((x2 - sx)), yOffset + (sizeX >> 1) + ((y2 - sy)), val);
                              v=getPixel(6,Math.round(x2),Math.round(y2));
                              ctx.fillStyle = v;
                              ctx.fillRect(xOffset + (sizeX >> 1) + ((x2 - sx)), yOffset + (sizeX >> 1) + ((y2 - sy)),3,3);
                          }
                      }
                  }
                  break;
                  case 4:
                  FDT_ScreeningResult = 1;
                  for ( x2 = -(sizeX >> 2); x2 <= (sizeX >> 2); x2 = x2 + ratioX)
                  {
                      for (y2 = -(sizeY >> 2); y2 <= (sizeY >> 2); y2 = y2 + ratioY)
                      {
                          tmp = (x2 * x2) + (y2 * y2);
                          if (tmp < cirVal)
                          {
                              /*val = vfResultBitmap[9].GetPixel((x2), (y2));
                              tex2.SetPixel(xOffset + (sizeX >> 1) + ((x2 - sx)), yOffset + (sizeX >> 1) + ((y2 - sy)), val);*/
                              v=getPixel(9,Math.round(x2),Math.round(y2));
                              ctx.fillStyle = v;
                              ctx.fillRect(xOffset + (sizeX >> 1) + ((x2 - sx)), yOffset + (sizeX >> 1) + ((y2 - sy)),3,3);
                          }
                      }
                  }
                  break;
              default:
                  break;
            }
        }else{
          value = parseInt(splitData[4]);
          switch (value)
          {
              case 1:
              for (x2 = sx; x2 <= sizeX - sx; x2 = x2 + ratioX)
              {
                  for (y2 = sy; y2 <= sizeY - sy; y2 = y2 + ratioY)
                  {
                      /*val = vfResultBitmap[0].GetPixel((x2), (y2));
                      tex2.SetPixel(xOffset + x2, yOffset + y2, val);*/
                      v=getPixel(0,Math.round(x2),Math.round(y2));
                      ctx.fillStyle = v;
                      ctx.fillRect(xOffset + x2, yOffset + y2,3,3);
                  }
              }
              break;
              case 2:
                FDT_ScreeningResult = 1;
                for ( x2 = sx; x2 <= sizeX - sx; x2 = x2 + ratioX)
                {
                    for ( y2 = sy; y2 <= sizeY - sy; y2 = y2 + ratioY)
                    {
                        /*val = vfResultBitmap[3].GetPixel((x2), (y2));
                        tex2.SetPixel(xOffset + x2, yOffset + y2, val);*/
                        v=getPixel(3,Math.round(x2),Math.round(y2));
                      ctx.fillStyle = v;
                        ctx.fillRect(xOffset + x2, yOffset + y2,3,3);
                    }
                }
                break;
            case 3:
                FDT_ScreeningResult = 1;
                for ( x2 = sx; x2 <= sizeX - sx; x2 = x2 + ratioX)
                {
                    for ( y2 = sy; y2 <= sizeY - sy; y2 = y2 + ratioY)
                    {
                        /*val = vfResultBitmap[6].GetPixel((x2), (y2));
                        tex2.SetPixel(xOffset + x2, yOffset + y2, val);*/
                        v=getPixel(6,Math.round(x2),Math.round(y2));
                        ctx.fillStyle = v;
                        ctx.fillRect(xOffset + x2, yOffset + y2,3,3);
                    }
                }
                break;
            case 4:
                FDT_ScreeningResult = 1;
                for ( x2 = sx; x2 <= sizeX - sx; x2 = x2 + ratioX)
                {
                    for ( y2 = sy; y2 <= sizeY - sy; y2 = y2 + ratioY)
                    {
                        /*val = vfResultBitmap[9].GetPixel((x2), (y2));
                        tex2.SetPixel(xOffset + x2, yOffset + y2, val);*/
                        v=getPixel(9,Math.round(x2),Math.round(y2));
                        ctx.fillStyle = v;
                        ctx.fillRect(xOffset + x2, yOffset + y2,3,3);
                    }
                }
                break;
            default:
                break;
          }
          if(FDT_CenterPointVal != 0)
          {
              x = 0 + zoomOffset;
              y = 0 + zoomOffset;
              x1 = Math.round(x * zoomMult);
              y1 = Math.round(y * zoomMult);
              xOffset = x1 - (squareSizeA / 2);
              yOffset = y1 - (squareSizeA / 2); 
              ctx.beginPath(); 
              ctx.strokeStyle = '#000000';
              ctx.arc(xOffset + (sizeX >> 1), yOffset + (sizeX >> 1), sizeX >> 2, 0, 2 * Math.PI);
              ctx.stroke(); 
              switch (FDT_CenterPointVal)
              {
                   case 1:
                    for ( x2 = -(sizeX >> 2); x2 <= (sizeX >> 2); x2 = x2 + ratioX)
                    {
                        for ( y2 = -(sizeY >> 2); y2 <= (sizeY >> 2); y2 = y2 + ratioY)
                        {
                            tmp = (x2 * x2) + (y2 * y2);
                            if (tmp < cirVal)
                            {
                               /* val = vfResultBitmap[0].GetPixel((x2), (y2));
                                tex2.SetPixel(xOffset + (sizeX >> 1) + ((x2 - sx)), yOffset + (sizeX >> 1) + ((y2 - sy)), val);*/
                                v=getPixel(0,Math.round(x2),Math.round(y2));
                                ctx.fillStyle = v;
                                ctx.fillRect(xOffset + (sizeX >> 1) + ((x2 - sx)), yOffset + (sizeX >> 1) + ((y2 - sy)),3,3);
                            }
                        }
                    }
                    break;
                    case 2:
                    FDT_ScreeningResult = 1;
                    for ( x2 = -(sizeX >> 2); x2 <= (sizeX >> 2); x2 = x2 + ratioX)
                    {
                        for ( y2 = -(sizeY >> 2); y2 <= (sizeY >> 2); y2 = y2 + ratioY)
                        {
                            tmp = (x2 * x2) + (y2 * y2);
                            if (tmp < cirVal)
                            {
                                // val = vfResultBitmap[3].GetPixel((x2), (y2));
                                // tex2.SetPixel(xOffset + (sizeX >> 1) + ((x2 - sx)), yOffset + (sizeX >> 1) + ((y2 - sy)), val);
                                 v=getPixel(3,Math.round(x2),Math.round(y2));
                                ctx.fillStyle = v;
                                ctx.fillRect(xOffset + (sizeX >> 1) + ((x2 - sx)), yOffset + (sizeX >> 1) + ((y2 - sy)),3,3);
                            }
                        }
                    }
                    break;
                    case 3:
                      FDT_ScreeningResult = 1;
                      for ( x2 = -(sizeX >> 2); x2 <= (sizeX >> 2); x2 = x2 + ratioX)
                      {
                          for ( y2 = -(sizeY >> 2); y2 <= (sizeY >> 2); y2 = y2 + ratioY)
                          {
                              tmp = (x2 * x2) + (y2 * y2);
                              if (tmp < cirVal)
                              {
                                 /* val = vfResultBitmap[6].GetPixel((x2), (y2));
                                  tex2.SetPixel(xOffset + (sizeX >> 1) + ((x2 - sx)), yOffset + (sizeX >> 1) + ((y2 - sy)), val);*/
                                   v=getPixel(6,Math.round(x2),Math.round(y2));
                                ctx.fillStyle = v;
                                ctx.fillRect(xOffset + (sizeX >> 1) + ((x2 - sx)), yOffset + (sizeX >> 1) + ((y2 - sy)),3,3);
                              }
                          }
                      }
                      break;
                  case 4:
                      FDT_ScreeningResult = 1;
                      for ( x2 = -(sizeX >> 2); x2 <= (sizeX >> 2); x2 = x2 + ratioX)
                      {
                          for ( y2 = -(sizeY >> 2); y2 <= (sizeY >> 2); y2 = y2 + ratioY)
                          {
                              tmp = (x2 * x2) + (y2 * y2);
                              if (tmp < cirVal)
                              {
                                  /*val = vfResultBitmap[9].GetPixel((x2), (y2));
                                  tex2.SetPixel(xOffset + (sizeX >> 1) + ((x2 - sx)), yOffset + (sizeX >> 1) + ((y2 - sy)), val);*/
                                   v=getPixel(9,Math.round(x2),Math.round(y2));
                                ctx.fillStyle = v;
                                ctx.fillRect(xOffset + (sizeX >> 1) + ((x2 - sx)), yOffset + (sizeX >> 1) + ((y2 - sy)),3,3);
                              }
                          }
                      }
                      break;
                  default:
                      break;
              }
          }
        }

  } 
  
  if (SymbolMap)
  {
    var color='';
    value = parseInt(splitData[4]);
    switch (value)
    {
        case 1:
            color = "#00ff00";
            break;
        case 2:
            color = "#0000ff";
            break;
        case 3:
            color = "#ff0000";
            break;
        default:
            color = "#00ffff";
            break;
    }
    if (zoomLevel > 0.5)
    {
        for (x2 = x1 - CONST_POINT_DISPLAY_SIZE; x2 < x1 + CONST_POINT_DISPLAY_SIZE; x2++)
        {
            for ( y2 = y1 - CONST_POINT_DISPLAY_SIZE; y2 < y1 + CONST_POINT_DISPLAY_SIZE; y2++)
            {
                ctx.fillStyle = color;
                ctx.fillRect(x2, y2,3,3);
            }
        }
    }
    else
    {
        for (x2 = x1 - CONST_POINT_DISPLAY_SIZE_SMALL; x2 < x1 + CONST_POINT_DISPLAY_SIZE_SMALL; x2++)
        {
            for (y2 = y1 -CONST_POINT_DISPLAY_SIZE_SMALL; y2 < y1 + CONST_POINT_DISPLAY_SIZE_SMALL; y2++)
            {
                ctx.fillStyle = color;
                ctx.fillRect(x2, y2,3,3);
            }
        }
    }
  } else if ((NumericMap) || (GrayScaleMap))
    {
        if (zoomLevel > 0.5)
        {
            for (x2 = x1 - 20; x2 <= x1 + 20; x2++)
            {
                for (y2 = y1 - 20; y2 <= y1 + 20; y2++)
                {
                    ctx.fillStyle = "#ffffff";
                    ctx.fillRect(x2, y2,3,3);
                }
            }
        }
        else
        {
            for (x2 = x1 - 8; x2 <= x1 + 8; x2++)
            {
                for (y2 = y1 - 8; y2 <= y1 + 8; y2++)
                {
                    ctx.fillStyle = "#ffffff";
                    ctx.fillRect(x2, y2,3,3);
                }
            }
        }
        label = Intensity; 
        ctx.fillStyle = "#000000";
        ctx.font = "33px Arial";   
        ctx.fillText(label,Math.round(x1 - 15),Math.round(y1 + 12));
        ctx.stroke();
    }
    if (testType == 1)   //Screening
    {
      return;
    }
    var vfpointdata = new Object(); 
    
    vfpointdata.x = splitData[2];
    vfpointdata.y = splitData[3];
    vfpointdata.intensity = Intensity;
    vfpointdata.size = stimulusSize;
    if (testType == 1){
       vfpointdata.STD = splitData[4]; //Gray scale display code
     }else{
      vfpointdata.STD = "0";
     }
     if ((testTypeName != "Neuro_20") && (testTypeName != "Neuro_35") && (testTypeName != "Full_Field_120_PTS") )
        {
           index = FindPointIndex(parseFloat(splitData[2]), parseFloat(splitData[3]));   //save point index in fixaxtionY
         
          vfpointdata.index = index;
        } 
        if (datas.vfpointdata.length>0)
        {
          for (i = 0; i < datas.vfpointdata.length; i++)
          {
             xErr = Math.abs(parseFloat(splitData[2]) - parseFloat(datas.vfpointdata[i].x));
             yErr = Math.abs(parseFloat(splitData[3]) - parseFloat(datas.vfpointdata[i].y));
            if ((xErr < 0.001) && (yErr < 0.001))
            {
               datas.vfpointdata.splice(i,1); 
              break;
            }
          }
        }
        if (datas.vfpointdata.length>0){
          datas.vfpointdata.push(vfpointdata); 
        }
        else
        { 
          datas.vfpointdata.push(vfpointdata);
        }  
}

function FindPointIndex(x,y) {
  var matchIndex;
  var xMatch;
  var yMatch;
  var matchIndex = 999;
  var MasterX;
  var MasterY;
  var numPoints = masterData.vfpointdata.length;
  for (i = 0; i < numPoints; i++)
        {
            if (masterData.vfpointdata.length != 99)
            {
                xMatch = false;
                yMatch = false;
                if (selectEye == 1) //1-OD, 0-OS
                    MasterX = parseFloat(masterData.vfpointdata[i].x);
                else
                    MasterX = -parseFloat(masterData.vfpointdata[i].x);

                MasterY = parseFloat(masterData.vfpointdata[i].y);
                if (Math.abs(MasterX - x) < 0.001)
                {
                    xMatch = true;
                }
                if (Math.abs(MasterY - y) < 0.001)
                {
                    yMatch = true;
                }
                if (xMatch && yMatch)
                {
                    //matchfound
                    matchIndex = parseInt(masterData.vfpointdata[i].index);
                    return matchIndex;
                }
            }
        }
        if(matchIndex == 999)
        {
            Debug.Log("index search error!");
        }
        return matchIndex;
   
}
 const setpixcelPBT = async function(data) {
   var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d");
   //var  res= data.split("VF");
    
   // res.forEach(fillPBT)
   //  function fillPBT(item, index) {
    
    var  splitData= data.split("|");
    splitData[4]=parseFloat(splitData[4])*-1;
    dataReceived = true;
   var pval;
        TotalQuestions = parseInt(splitData[6]);
       // Questions = "Questions: " + TotalQuestions;
        setTestQuestions(TotalQuestions);       
    if (flagFDT == 1)
    {
        zoomOffset = 3.0 / zoomLevel;
                    zoomMult = 1000.0 / (2 * zoomOffset);

                    pval = parseInt(splitData[2]);
                    if ((pval == 20) || (pval == 30))
                    {
                        x = zoomOffset;
                        y = zoomOffset;
                    }
                    else
                    {
                        x = parseFloat(splitData[3]) + zoomOffset;
                        y = parseFloat(splitData[4]) + zoomOffset;
                    }
                    x1 =Math.round(x * zoomMult);
                    y1 = Math.round(y * zoomMult);

                    for ( x2 = x1 - 20; x2 <= x1 + 20; x2++)
                    {
                        for ( y2 = y1 - 20; y2 <= y1 + 20; y2++)
                        {
                          ctx.fillStyle = "#ffffff";
                          ctx.fillRect(x2,y2,3,3);
                        }
                    }
                    if(pval==0){  
                       ctx.fillStyle = "#ff0000"; 
                    }
                    switch (pval)
                    {
                        case 0:
                          
                            ctx.beginPath();
                            ctx.fillStyle = "#ff0000"; 
                            label = splitData[5];
                            ctx.font = "33px Arial";   
                            ctx.fillText(label,Math.round(x1 - 15),Math.round(y1 + 12));
                             ctx.stroke(); 
                            ClearCircle(x1, y1, pval); 
                            //StartCoroutine(ClearCircle(tex, x1, y1, pval));
                            break;
                        case 10:
                              ctx.beginPath(); 
                            ctx.strokeStyle = '#00ff00';
                            ctx.arc(Math.round(x1), Math.round(y1), 24, 0, 2 * Math.PI);
                             ctx.stroke(); 
                             ctx.beginPath(); 
                            ctx.strokeStyle = '#00ff00';
                            ctx.arc(Math.round(x1), Math.round(y1), 23, 0, 2 * Math.PI);
                             ctx.stroke(); 
                             ctx.beginPath(); 
                            ctx.strokeStyle = '#00ff00';
                            ctx.arc(Math.round(x1), Math.round(y1), 22, 0, 2 * Math.PI);
                             ctx.stroke(); 
                            ClearCircle(x1, y1, pval);
                            //StartCoroutine(ClearCircle(tex, x1, y1, pval));
                            break;
                        case 20:
                             ctx.beginPath(); 
                            ctx.strokeStyle = '#0000ff';
                            ctx.arc(Math.round(x1), Math.round(y1), 24, 0, 2 * Math.PI);
                             ctx.stroke(); 
                             ctx.beginPath(); 
                            ctx.strokeStyle = '#0000ff';
                            ctx.arc(Math.round(x1), Math.round(y1), 23, 0, 2 * Math.PI);
                             ctx.stroke(); 
                             ctx.beginPath(); 
                            ctx.strokeStyle = '#0000ff';
                            ctx.arc(Math.round(x1), Math.round(y1), 22, 0, 2 * Math.PI);
                             ctx.stroke(); 
                            ClearCircle(x1, y1, pval);
                            //StartCoroutine(ClearCircle(tex, x1, y1, pval));
                            break;
                        case 30:
                             ctx.beginPath(); 
                            ctx.strokeStyle = '#808080';
                            ctx.arc(Math.round(x1), Math.round(y1), 24, 0, 2 * Math.PI);
                             ctx.stroke(); 
                             ctx.beginPath(); 
                            ctx.strokeStyle = '#808080';
                            ctx.arc(Math.round(x1), Math.round(y1), 23, 0, 2 * Math.PI);
                             ctx.stroke(); 
                             ctx.beginPath(); 
                            ctx.strokeStyle = '#808080';
                            ctx.arc(Math.round(x1), Math.round(y1), 22, 0, 2 * Math.PI);
                             ctx.stroke(); 
                            ClearCircle(x1, y1, pval);
                            //StartCoroutine(ClearCircle(tex, x1, y1, pval));
                            break;
                        case 40:
                            ctx.beginPath(); 
                            ctx.strokeStyle = '#00ffff';
                            ctx.arc(Math.round(x1), Math.round(y1), 24, 0, 2 * Math.PI);
                             ctx.stroke(); 
                             ctx.beginPath(); 
                            ctx.strokeStyle = '#00ffff';
                            ctx.arc(Math.round(x1), Math.round(y1), 23, 0, 2 * Math.PI);
                             ctx.stroke(); 
                             ctx.beginPath(); 
                            ctx.strokeStyle = '#00ffff';
                            ctx.arc(Math.round(x1), Math.round(y1), 22, 0, 2 * Math.PI);
                             ctx.stroke(); 
                            ClearCircle(x1, y1, pval);
                            //StartCoroutine(ClearCircle(tex, x1, y1, pval));
                            break;
                        default:
                            break;
                    }
    }else{
      
      if (zoomLevel == 0.2)
        {
            thetaRad = Math.atan(parseFloat(splitData[4]) / parseFloat(splitData[3]));
            R2 = parseFloat(splitData[3]) / Math.cos(thetaRad);
            phiRad = Math.atan(R2 / 3.0);
            var theta1;
            var Phi1;

          pval = parseInt(splitData[2]);
          if ((pval == 20) || (pval == 30))
          {
            phiRad = 0;
            thetaRad = 0;
            Phi1 = phiRad * 180 / Math.PI;
            theta1 = thetaRad * 180 / Math.PI;
          }
          else
          {
            Phi1 = phiRad * 180 / Math.PI;
            theta1 = thetaRad * 180 / Math.PI;
          }

          linearDispFactor = 1000 / (2 * 8 * 10);
          R3 = Phi1 * linearDispFactor;
          x = R3 * Math.cos(thetaRad);
          y = R3 * Math.sin(thetaRad);

          x1 = Math.round((x) + (size / 2));
          y1 = Math.round((y) + (size / 2)); 

          switch (pval)
          {
            case 0:
              
              ctx.beginPath();
              ctx.fillStyle = "#ff0000"; 
              label = splitData[5];
              ctx.font = "27px Arial";   
              ctx.fillText(label,Math.round(x1 - 24),Math.round(y1 + 12));
               ctx.stroke();
               ClearCircleSmall(x1, y1, pval);
              // StartCoroutine(ClearCircleSmall(tex, x1, y1, pval));
              break;
            case 10:
               ctx.beginPath();
               ctx.strokeStyle = '#00ff00';
               ctx.arc(Math.round(x1), Math.round(y1), 10, 0, 2 * Math.PI);
               ctx.stroke(); 
               ctx.beginPath();
               ctx.strokeStyle = '#00ff00';
               ctx.arc(Math.round(x1), Math.round(y1), 9, 0, 2 * Math.PI);
               ctx.stroke();
               ClearCircleSmall(x1, y1, pval);
             // StartCoroutine(ClearCircleSmall(tex, x1, y1, pval));
              break;
            case 20:
              ctx.beginPath();
               ctx.strokeStyle = '#0000ff';
               ctx.arc(Math.round(x1), Math.round(y1), 10, 0, 2 * Math.PI);
               ctx.stroke(); 
               ctx.beginPath();
               ctx.strokeStyle = '#0000ff';
               ctx.arc(Math.round(x1), Math.round(y1), 9, 0, 2 * Math.PI);
               ctx.stroke();
               ClearCircleSmall(x1, y1, pval);
              //StartCoroutine(ClearCircleSmall(tex, x1, y1, pval));
              break;
            case 30:
               ctx.beginPath();
               ctx.strokeStyle = '#808080';
               ctx.arc(Math.round(x1), Math.round(y1), 10, 0, 2 * Math.PI);
               ctx.stroke(); 
               ctx.beginPath();
               ctx.strokeStyle = '#808080';
               ctx.arc(Math.round(x1), Math.round(y1), 9, 0, 2 * Math.PI);
               ctx.stroke();
               ClearCircleSmall(x1, y1, pval);
              //StartCoroutine(ClearCircleSmall(tex, x1, y1, pval));
              break;
            case 40:
               ctx.beginPath();
               ctx.strokeStyle = '#00ffff';
               ctx.arc(Math.round(x1), Math.round(y1), 10, 0, 2 * Math.PI);
               ctx.stroke(); 
               ctx.beginPath();
               ctx.strokeStyle = '#00ffff';
               ctx.arc(Math.round(x1), Math.round(y1), 9, 0, 2 * Math.PI);
               ctx.stroke(); 
               ClearCircleSmall(x1, y1, pval);
              //StartCoroutine(ClearCircleSmall(tex, x1, y1, pval));
              break;
            default:
              break;
          }
        }else{ 
           zoomOffset = 3.0 / zoomLevel;
          zoomMult = 1000.0 / (2 * zoomOffset);

          pval = parseInt(splitData[2]);
          if ((pval == 20) || (pval == 30))
          {
            x = zoomOffset;
            y = zoomOffset;
          }
          else
          {
            x = parseFloat(parseFloat(splitData[3]) + parseFloat(zoomOffset));
            y = parseFloat(parseFloat(splitData[4]) + parseFloat(zoomOffset));
          }
          x1 = Math.round(x * zoomMult);
          y1 = Math.round(y * zoomMult);
          if (zoomLevel > 0.5)
          {
             
            for ( x2 = x1 - 20; x2 <= x1 + 20; x2++)
            {
              for ( y2 = y1 - 20; y2 <= y1 + 20; y2++)
              {
                ctx.fillStyle = "#ffffff";
                ctx.fillRect(x2,y2,3,3); 
              }
            }
            
            if(pval==0){  
               ctx.fillStyle = "#ff0000"; 
            } 
            switch (pval)
            {
              case 0: 
             
                ctx.beginPath(); 
                label = splitData[5];
                ctx.font = "33px Arial";
                ctx.fillStyle = '#ff0000';   
                ctx.fillText(label,Math.round(x1 - 15),Math.round(y1 + 12));
                ctx.stroke(); 
                ClearCircle(x1, y1, pval);
                // StartCoroutine(ClearCircle(tex, x1, y1, pval));
                break;
              case 10:
                ctx.beginPath();
                ctx.strokeStyle = '#00ff00';
                ctx.arc(Math.round(x1), Math.round(y1), 24, 0, 2 * Math.PI);
                ctx.stroke(); 
                ctx.beginPath();
                ctx.strokeStyle = '#00ff00';
                ctx.arc(Math.round(x1), Math.round(y1), 23, 0, 2 * Math.PI);
                ctx.stroke(); 
                ctx.beginPath();
                ctx.strokeStyle = '#00ff00';
                ctx.arc(Math.round(x1), Math.round(y1), 22, 0, 2 * Math.PI);
                ctx.stroke(); 
                ClearCircle(x1, y1, pval);
                //StartCoroutine(ClearCircle(tex, x1, y1, pval));
                break;
              case 20: 
               ctx.beginPath();
                ctx.strokeStyle = '#0000ff';
                ctx.arc(Math.round(x1), Math.round(y1), 24, 0, 2 * Math.PI);
                ctx.stroke(); 
                ctx.beginPath();
                ctx.strokeStyle = '#0000ff';
                ctx.arc(Math.round(x1), Math.round(y1), 23, 0, 2 * Math.PI);
                ctx.stroke(); 
                ctx.beginPath();
                ctx.strokeStyle = '#0000ff';
                ctx.arc(Math.round(x1), Math.round(y1), 22, 0, 2 * Math.PI);
                ctx.stroke(); 
                 ClearCircle(x1, y1, pval);
                //StartCoroutine(ClearCircle(tex, x1, y1, pval));
                break;
              case 30:  
              ctx.beginPath(); 
                ctx.strokeStyle = '#808080';
                ctx.arc(Math.round(x1), Math.round(y1), 24, 0, 2 * Math.PI);
                ctx.stroke(); 
                ctx.beginPath();
                ctx.strokeStyle = '#808080';
                ctx.arc(Math.round(x1), Math.round(y1), 23, 0, 2 * Math.PI);
                ctx.stroke(); 
                ctx.beginPath();
                ctx.strokeStyle = '#808080';
                ctx.arc(Math.round(x1), Math.round(y1), 22, 0, 2 * Math.PI);
                ctx.stroke(); 
                ClearCircle(x1, y1, pval);
                //StartCoroutine(ClearCircle(tex, x1, y1, pval));
                break;
              case 40: 
              ctx.beginPath(); 
                ctx.strokeStyle = '#00ffff'; 
                ctx.arc(Math.round(x1), Math.round(y1), 24, 0, 2 * Math.PI);
                ctx.stroke(); 
                ctx.beginPath(); 
                ctx.strokeStyle = '#00ffff'; 
                ctx.arc(Math.round(x1), Math.round(y1), 23, 0, 2 * Math.PI);
                ctx.stroke(); 
                ctx.beginPath(); 
                ctx.strokeStyle = '#00ffff'; 
                ctx.arc(Math.round(x1), Math.round(y1), 22, 0, 2 * Math.PI);
                ctx.stroke(); 
                ClearCircle(x1, y1, pval);
               // StartCoroutine(ClearCircle(tex, x1, y1, pval));
                break;
              default:
                break;
            }
          }else{
            for ( x2 = x1 - 8; x2 <= x1 + 8; x2++)
            {
              for ( y2 = y1 - 8; y2 <= y1 + 8; y2++)
              {
                 ctx.fillStyle = "#ffffff";
                  ctx.fillRect(x2,y2,1,1);
              }
            }
            if(pval==0){  
               ctx.fillStyle = "#ff0000"; 
            }
            switch (pval)
            {
              case 0:
              
                ctx.beginPath();
                ctx.fillStyle = "#ff0000";
                label = splitData[5];
                ctx.font = "27px Arial";   
                ctx.fillText(label,Math.round(x1 - 24),Math.round(y1 + 12));
                 ctx.stroke();
                ClearCircleSmall(x1, y1, pval);
               //StartCoroutine(ClearCircleSmall(tex, x1, y1, pval));
                break;
                case 20:
                  ctx.beginPath();
                  ctx.strokeStyle = '#0000ff';
                  ctx.arc(Math.round(x1), Math.round(y1), 10, 0, 2 * Math.PI);
                  ctx.stroke(); 
                  ctx.beginPath();
                  ctx.strokeStyle = '#0000ff';
                  ctx.arc(Math.round(x1), Math.round(y1), 9, 0, 2 * Math.PI);
                  ctx.stroke(); 
                   ClearCircleSmall(x1, y1, pval);
                  //StartCoroutine(ClearCircleSmall(tex, x1, y1, pval));
                break;
              case 30:
                   ctx.beginPath();
                   ctx.strokeStyle = '#808080';
                   ctx.arc(Math.round(x1), Math.round(y1), 10, 0, 2 * Math.PI);
                   ctx.stroke(); 
                   ctx.beginPath();
                   ctx.strokeStyle = '#808080';
                   ctx.arc(Math.round(x1), Math.round(y1), 9, 0, 2 * Math.PI);
                   ctx.stroke(); 
                   ClearCircleSmall(x1, y1, pval);
                  //StartCoroutine(ClearCircleSmall(tex, x1, y1, pval));
                break;
              case 40: 
                  ctx.beginPath();
                   ctx.strokeStyle = '#00ffff';
                   ctx.arc(Math.round(x1), Math.round(y1), 10, 0, 2 * Math.PI);
                   ctx.stroke(); 
                   ctx.beginPath();
                   ctx.strokeStyle = '#00ffff';
                   ctx.arc(Math.round(x1), Math.round(y1), 9, 0, 2 * Math.PI);
                   ctx.stroke(); 
                   ClearCircleSmall(x1, y1, pval);
                //StartCoroutine(ClearCircleSmall(tex, x1, y1, pval));
                break;
                default:
                break;
              }
          }
        }
    }
    ctx.stroke();
 // }
}
function clearArea(x1,y1) {
  var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d");
  for ( x2 = x1 - 20; x2 <= x1 + 20; x2++)
            {
              for ( y2 = y1 - 20; y2 <= y1 + 20; y2++)
              {
              //  ctx.beginPath(); 
                ctx.fillStyle = "#ffffff";
                ctx.fillRect(x2,y2,3,3); 
               // ctx.stroke(); 
              }
            }
             ctx.stroke();
}
const ClearCircle = async function(x1, y1, val) {
  if(dataCaptured || dataCapturedpause){
     await sleep(350);
  }
  
 //function ClearCircle(x1, y1, val) {
    
 // yield return new WaitForSecondsRealtime(0.35f);
 var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d");
  ctx.beginPath();
   ctx.strokeStyle = '#ffffff';
    ctx.arc(Math.round(x1), Math.round(y1), 24, 0, 2 * Math.PI);
     ctx.stroke(); 
     ctx.beginPath();
   ctx.strokeStyle = '#ffffff';
    ctx.arc(Math.round(x1), Math.round(y1), 23, 0, 2 * Math.PI);
    ctx.stroke();
    ctx.beginPath();
   ctx.strokeStyle = '#ffffff';
    ctx.arc(Math.round(x1), Math.round(y1), 22, 0, 2 * Math.PI); 
    ctx.stroke(); 
       
    if (val == 0)
    {
      if (zoomLevel > 0.5)
      {
        for ( x2 = x1 - 20; x2 <= x1 + 20; x2++)
        {
          for ( y2 = y1 - 20; y2 <= y1 + 20; y2++)
          {
            ctx.fillStyle = "#ffffff";
            ctx.fillRect(x2,y2,3,3);
          }
        }
        for ( x2 = x1 - 5; x2 <= x1 + 5; x2++)
        {
          for ( y2 = y1 - 5; y2 <= y1 + 5; y2++)
          {
            ctx.fillStyle = "#808080";
            ctx.fillRect(x2,y2,3,3);
          }
        }
      }
      else
      {
        for ( x2 = x1 - 8; x2 <= x1 + 8; x2++)
        {
          for ( y2 = y1 - 8; y2 <= y1 + 8; y2++)
          {
            ctx.fillStyle = "#ffffff";
            ctx.fillRect(x2,y2,3,3);
          }
        }
        for ( x2 = x1 - 2; x2 <= x1 + 2; x2++)
        {
          for ( y2 = y1 - 2; y2 <= y1 + 2; y2++)
          {
            ctx.fillStyle = "#808080";
            ctx.fillRect(x2,y2,3,3);
          }
        }
      }
    }
  //  ctx.stroke();
}
 
  const ClearCircleSmall = async function(x1, y1, val) {
   if(dataCaptured || dataCapturedpause){
     await sleep(350);
  }
  //yield return new WaitForSecondsRealtime(0.35f);
  var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d");
     ctx.beginPath();
   ctx.strokeStyle = '#ffffff';
    ctx.arc(Math.round(x1), Math.round(y1), 10, 0, 2 * Math.PI);
    ctx.stroke(); 
     ctx.beginPath();
   ctx.strokeStyle = '#ffffff';
    ctx.arc(Math.round(x1), Math.round(y1), 9, 0, 2 * Math.PI);
     ctx.stroke(); 
    if (val == 0)
    {
      if (zoomLevel > 0.5)
      {
        for ( x2 = x1 - 20; x2 <= x1 + 20; x2++)
        {
          for ( y2 = y1 - 20; y2 <= y1 + 20; y2++)
          {
            ctx.fillStyle = "#ffffff";
            ctx.fillRect(x2,y2,1,1);
          }
        }
        for ( x2 = x1 - 5; x2 <= x1 + 5; x2++)
        {
          for ( y2 = y1 - 5; y2 <= y1 + 5; y2++)
          {
           ctx.fillStyle = "#808080";
            ctx.fillRect(x2,y2,1,1);
          }
        }
      }
      else
      {
        for ( x2 = x1 - 8; x2 <= x1 + 8; x2++)
        {
          for ( y2 = y1 - 8; y2 <= y1 + 8; y2++)
          {
           ctx.fillStyle = "#ffffff";
            ctx.fillRect(x2,y2,1,1);
          }
        }
        for ( x2 = x1 - 2; x2 <= x1 + 2; x2++)
        {
          for ( y2 = y1 - 2; y2 <= y1 + 2; y2++)
          {
            ctx.fillStyle = "#808080";
            ctx.fillRect(x2,y2,1,1);
          }
        }
      }
    }
    ctx.stroke();
}
const setpixcelTPS = async function(data) {
//function setpixcelTPS(data) {
   var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d");
   var  res= data.split("VF");
  
   res.forEach(fillTPS)
   function fillTPS(item, index) {
    var  splitData= item.split("|");
     splitData[3]=parseFloat(splitData[3])*-1; 
    if(zoomLevel==0.2){
      size=1000;
       displayTestPoint = splitData[4];
      if (displayTestPoint == 1)  //0-BlindSpot point
          {
            numTestPoints++;
            thetaRad = Math.atan(parseFloat(splitData[3]) / parseFloat(splitData[2]));
            theta1 = thetaRad * 180 / Math.PI;
            R2 = parseFloat(splitData[2]) / Math.cos(thetaRad);
            phiRad = Math.atan(R2 / 3);
            Phi1 = phiRad * 180 / Math.PI;

            linearDispFactor = 1000 / (2 * 8 * 10);
            R3 = Phi1 * linearDispFactor;
            x = R3 * Math.cos(thetaRad);
            y = R3 * Math.sin(thetaRad);

            x1 = Math.round((x) + (size / 2));
            y1 = Math.round((y) + (size / 2));
            for ( x2 = x1 - 6; x2 <= x1 + 6; x2++)
            {
              for ( y2 = y1 - 6; y2 <= y1 + 6; y2++)
              {
                ctx.fillStyle = "#ffffff";
                   ctx.fillRect(x2, y2,1,1);
                //tex.SetPixel(x2, y2, Color.white);
              }
            }
            for ( x2 = x1 - 3; x2 <= x1 + 3; x2++)
            {
              for ( y2 = y1 - 3; y2 <= y1 + 3; y2++)
              {
                ctx.fillStyle = "#808080";
                   ctx.fillRect(x2, y2,1,1); 
                //tex.SetPixel(x2, y2, Color.gray);
              }
            }

          }
    }else{
      zoomOffset = 3./ zoomLevel;
      zoomMult = 1000/ (2 * zoomOffset);
      displayTestPoint = splitData[4];
      if (displayTestPoint == 1)  //0-BlindSpot point
          {
             numTestPoints++;
           var  x = parseFloat(splitData[2]) + zoomOffset;
           var  y = parseFloat(splitData[3]) + zoomOffset;
           var  x1 = Math.round(x * zoomMult);
           var  y1 = Math.round(y * zoomMult);
           if (zoomLevel > 0.5)
            {
              for (x2 = x1 - 20; x2 <= x1 + 20; x2++)
              {
                for (y2 = y1 - 20; y2 <= y1 + 20; y2++)
                {
                  ctx.fillStyle = "#ffffff";
                   ctx.fillRect(x2, y2,1,1); 
                  //tex.SetPixel(x2, y2, Color.white);
                }
              }
              for ( x2 = x1 - 5; x2 <= x1 + 5; x2++)
              {
                for ( y2 = y1 - 5; y2 <= y1 + 5; y2++)
                {
                  ctx.fillStyle = "#808080";
                   ctx.fillRect(x2, y2,1,1); 
               //   tex.SetPixel(x2, y2, Color.gray);
                }
              }
            }
            else
            {
              for ( x2 = x1 - 8; x2 <= x1 + 8; x2++)
              {
                for ( y2 = y1 - 8; y2 <= y1 + 8; y2++)
                {
                  ctx.fillStyle = "#ffffff";
                  ctx.fillRect(x2,y2,1,1); 
                 // tex.SetPixel(x2, y2, Color.white);
                }
              }
              for ( x2 = x1 - 2; x2 <= x1 + 2; x2++)
              {
                for ( y2 = y1 - 2; y2 <= y1 + 2; y2++)
                {
                  ctx.fillStyle = "#808080";
                  ctx.fillRect(x2, y2,1,1); 
                 // tex.SetPixel(x2, y2, Color.gray);
                }
              }
            }
          }
    }
    
    // ctx.fillRect((10+index),10,5,5)  
}
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
     const promises =  clearDeviceData(1);
     //await Promise.all([promises]);
  var t1=0;
  var r=0,r1=0,r2=0,r3=0,r4=0,r5=0,r6=0,r7=0,r8=0;
  testTypeName=value; 
  setZoomLevel($("#test-type").val(),value);
  zoomOffset = 3/zoomLevel;
  size=1000;
  zoomMult = size/ (2 * zoomOffset);
  offsetY = 35;
  offsetX = 0;
  degY = 55;
  degX = 40;
  degSize = 3;

  var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d");
  ctx.lineWidth = 3;
  ctx.clearRect(0, 0, c.width, c.height)
  ctx.beginPath();
  ctx.strokeStyle = col1;
  ctx.moveTo(1, Math.round(size/2));
  ctx.lineTo(Math.round(size), Math.round(size/2));
  ctx.moveTo(Math.round(size/2), 1);
  ctx.lineTo(Math.round(size/2), Math.round(size));
  ctx.stroke();
  //ctx.strokeStyle = "#5f5f5f";
   if(zoomLevel==0.17){
       linearDispFactor = 1000 / (2 * 8 * 10);
       offsetY = 65;
       offsetX = 30;
       for(zi = 1; zi <= 8; zi++)
       {
         //Draw 10 degree circles
         ctx.beginPath(); 
         t1 = zi* 10 * linearDispFactor;
         r = Math.round(t1); //166.67 = 1000/6
         ctx.arc(Math.round(size/2), Math.round(size/2), r, 0, 2 * Math.PI);
         ctx.stroke(); 
         ctx.beginPath(); 
         label = (zi*10);
         ctx.fillStyle = '#000000';  
         ctx.font = "33px Arial";   
         ctx.fillText(label+String.fromCharCode(176),Math.round(r + 5 - offsetX + size / 2),Math.round(size / 2+offsetY/2));  
         ctx.stroke(); 
       }

        t1 = 80 * linearDispFactor;
       r = Math.round(t1); //166.67 = 1000/6

       for ( zi = 0; zi < 360; zi = zi + 30)
       {
         //Draw 30 deg lines
         x1 =  Math.round(r * Math.cos(zi * Math.PI / 180));
         y1 = Math.round(r * Math.sin(zi * Math.PI / 180));
         ctx.beginPath();
         ctx.strokeStyle = '#000000';
         ctx.moveTo(Math.round(size/2), Math.round(size/2));
         ctx.lineTo(Math.round(size / 2 + x1), Math.round(size/2+ y1));
         ctx.stroke(); 
         label = zi;
         ctx.beginPath();
         ctx.fillStyle = '#000000'; 
         ctx.font = "33px Arial";  
         ctx.fillText(label,Math.round(size / 2+x1),Math.round(size / 2 -y1)); 
         ctx.stroke(); 
       }
   }else if (zoomLevel == 0.2)
    {
      linearDispFactor = 1000 / (2 * 8 * 10);
      offsetY = 65;
      offsetX = 30;
      for (zi = 1; zi <= 8; zi = zi + 1)
      {
         ctx.beginPath();
        //Draw 10 degree circles
        t1 = zi * 10 * linearDispFactor;
        r =Math.round(t1); //166.67 = 1000/6
        ctx.arc(Math.round(size/2), Math.round(size/2), r, 0, 2 * Math.PI);
        ctx.stroke();  
        label = (zi * 10); 
        ctx.beginPath();
        ctx.fillStyle = '#000000'; 
        ctx.font = "33px Arial";   
        ctx.fillText(label+String.fromCharCode(176),Math.round(r + 5 - offsetX + size / 2),Math.round(size / 2+offsetY/2)); 
        ctx.stroke(); 
      }

      t1 = 80 * linearDispFactor;
      r = Math.round(t1); //166.67 = 1000/6

      for ( zi = 0; zi < 360; zi = zi + 30)
      {
        //Draw 30 deg lines
        x1 =  Math.round(r * Math.cos(zi * Math.PI / 180));
        y1 = Math.round(r * Math.sin(zi * Math.PI / 180));
        ctx.beginPath();
         ctx.strokeStyle = '#000000';
        ctx.moveTo(Math.round(size/2), Math.round(size/2));
         ctx.lineTo(Math.round(size / 2 + x1), Math.round(size/2+ y1));
          ctx.stroke(); 
         label = zi;
         ctx.beginPath();
        ctx.fillStyle = '#000000'; 
         ctx.font = "33px Arial";  
         ctx.fillText(label,Math.round(size / 2+x1),Math.round(size / 2 -y1));
         ctx.stroke(); 
      }

    }else if (zoomLevel == 0.5)
    {
      //Draw 10 degree circle
      ctx.beginPath(); 
      t1 = 3.0 * Math.tan(10 * Math.PI / 180);
      r = Math.round(t1 * zoomMult); //166.67 = 1000/6 
      ctx.arc(Math.round(size/2), Math.round(size/2), r, 0, 2 * Math.PI);
       ctx.stroke(); 
      label = "10";
       ctx.beginPath();
        ctx.fillStyle = '#000000'; 
       ctx.font = "33px Arial";  
     ctx.fillText(label+String.fromCharCode(176),Math.round(r + 5 + size / 2),Math.round(size / 2));  
     ctx.stroke(); 
      //Draw 20 degree circle
      ctx.beginPath(); 
      t1 = 3.0 * Math.tan(20 * Math.PI / 180);
      r = Math.round(t1 * zoomMult); //166.67 = 1000/6 
      ctx.arc(Math.round(size/2), Math.round(size/2), r, 0, 2 * Math.PI);
       ctx.stroke(); 
      label = "20";
      ctx.beginPath();
        ctx.fillStyle = '#000000';
       ctx.font = "33px Arial";  
     ctx.fillText(label+String.fromCharCode(176),Math.round(r + 5 + size / 2),Math.round(size / 2));  
     ctx.stroke(); 
      //Draw 30 degree circle
       ctx.beginPath(); 
      t1 = 3.0 * Math.tan(30 * Math.PI / 180);
      r = Math.round(t1 * zoomMult); //166.67 = 1000/6 
      ctx.arc(Math.round(size/2), Math.round(size/2), r, 0, 2 * Math.PI);
       ctx.stroke(); 
      label = "30";
      ctx.beginPath();
        ctx.fillStyle = '#000000';
       ctx.font = "33px Arial";  
     ctx.fillText(label+String.fromCharCode(176),Math.round(r + 5 + size / 2),Math.round(size / 2)); 
      ctx.stroke();   
      //Draw 40 degree circle
       ctx.beginPath(); 
      t1 = 3.0 * Math.tan(40 * Math.PI / 180);
      r = Math.round(t1 * zoomMult); //166.67 = 1000/6 
      ctx.arc(Math.round(size/2), Math.round(size/2), r, 0, 2 * Math.PI);
       ctx.stroke(); 
      label = "40";
      ctx.beginPath();
        ctx.fillStyle = '#000000';
       ctx.font = "33px Arial";  
     ctx.fillText(label+String.fromCharCode(176),Math.round(r + 5 + size / 2),Math.round(size / 2)); 
      ctx.stroke();  
      //Draw 50 degree circle
      ctx.beginPath(); 
      t1 = 3.0 * Math.tan(50 * Math.PI / 180);
      r = Math.round(t1 * zoomMult); //166.67 = 1000/6 
      ctx.arc(Math.round(size/2), Math.round(size/2), r, 0, 2 * Math.PI);
       ctx.stroke(); 
      label = "50";
      ctx.beginPath();
        ctx.fillStyle = '#000000';
       ctx.font = "33px Arial";  
     ctx.fillText(label+String.fromCharCode(176),Math.round(r + 5 + size / 2),Math.round(size / 2));   
     ctx.stroke();  
      //Draw 60 degree circle
       ctx.beginPath(); 
      t1 = 3.0 * Math.tan(60 * Math.PI / 180);
      r = Math.round(t1 * zoomMult); //166.67 = 1000/6 
      ctx.arc(Math.round(size/2), Math.round(size/2), r, 0, 2 * Math.PI);
       ctx.stroke(); 
      label = "60";
      ctx.beginPath();
        ctx.fillStyle = '#000000';
       ctx.font = "33px Arial";  
     ctx.fillText(label+String.fromCharCode(176),Math.round(r + 5 + size / 2),Math.round(size / 2));  
     ctx.stroke();  
    }else if (zoomLevel == 1.0)
    {
      //Draw 10 degree circle
      ctx.beginPath(); 
      t1 = 3.0 * Math.tan(10 * Math.PI / 180);
      r = Math.round(t1 * zoomMult); //166.67 = 1000/6 
      ctx.arc(Math.round(size/2), Math.round(size/2), r, 0, 2 * Math.PI);
      ctx.stroke(); 
      label = "10";
       ctx.beginPath();
        ctx.fillStyle = '#000000';
      ctx.font = "33px Arial";   
      ctx.fillText(label+String.fromCharCode(176),Math.round(r + 5 + size / 2),Math.round(size / 2));  
       ctx.stroke();

      //Draw 20 degree circle
     ctx.beginPath(); 
      t1 = 3.0 * Math.tan(20 * Math.PI / 180);
      r = Math.round(t1 * zoomMult); //166.67 = 1000/6 
      ctx.arc(Math.round(size/2), Math.round(size/2), r, 0, 2 * Math.PI);
      ctx.stroke(); 
      label = "20";
      ctx.beginPath();
        ctx.fillStyle = '#000000';
      ctx.font = "33px Arial";   
      ctx.fillText(label+String.fromCharCode(176),Math.round(r + 5 + size / 2),Math.round(size / 2));
       ctx.stroke();
      //Draw 30 degree circle
     ctx.beginPath(); 
      t1 = 3.0 * Math.tan(30 * Math.PI / 180);
      r = Math.round(t1 * zoomMult); //166.67 = 1000/6 
      ctx.arc(Math.round(size/2), Math.round(size/2), r, 0, 2 * Math.PI);
      ctx.stroke(); 
      label = "30";
      ctx.beginPath();
        ctx.fillStyle = '#000000';
      ctx.font = "33px Arial";   
      ctx.fillText(label+String.fromCharCode(176),Math.round(r + 5 + size / 2),Math.round(size / 2));
       ctx.stroke();
      //Draw 40 degree circle
     ctx.beginPath(); 
      t1 = 3.0 * Math.tan(40 * Math.PI / 180);
      r = Math.round(t1 * zoomMult); //166.67 = 1000/6 
      ctx.arc(Math.round(size/2), Math.round(size/2), r, 0, 2 * Math.PI);
      ctx.stroke(); 
      label = "40";
      ctx.beginPath();
        ctx.fillStyle = '#000000';
      ctx.font = "33px Arial";   
      ctx.fillText(label+String.fromCharCode(176),Math.round(r + 5 + size / 2),Math.round(size / 2));
       ctx.stroke();
    }else if ((zoomLevel > 1.0) && (zoomLevel <= 1.5))
    {
       ctx.beginPath();
      t1 = 3.0 * Math.tan(10 * Math.PI / 180);
      r = Math.round(t1 * zoomMult); //166.67 = 1000/6 
       ctx.arc(Math.round(size/2), Math.round(size/2), r, 0, 2 * Math.PI);
      ctx.stroke();
      label = "10"; 
      ctx.beginPath();
        ctx.fillStyle = '#000000';
      ctx.font = "33px Arial";   
      ctx.fillText(label+String.fromCharCode(176),Math.round(r + 5 + size / 2),Math.round(size / 2));
         ctx.stroke();
      //Draw 20 degree circle
      ctx.beginPath();
      t1 = 3.0 * Math.tan(20 * Math.PI / 180);
      r = Math.round(t1 * zoomMult); //166.67 = 1000/6 
       ctx.arc(Math.round(size/2), Math.round(size/2), r, 0, 2 * Math.PI);
      ctx.stroke();
      label = "20"; 
      ctx.beginPath();
        ctx.fillStyle = '#000000';
      ctx.font = "33px Arial";   
      ctx.fillText(label+String.fromCharCode(176),Math.round(r + 5 + size / 2),Math.round(size / 2));
         ctx.stroke();
      //Draw 30 degree circle
       ctx.beginPath();
     t1 = 3.0 * Math.tan(30 * Math.PI / 180);
      r = Math.round(t1 * zoomMult); //166.67 = 1000/6 
       ctx.arc(Math.round(size/2), Math.round(size/2), r, 0, 2 * Math.PI);
      ctx.stroke();
      label = "30"; 
      ctx.beginPath();
        ctx.fillStyle = '#000000';
      ctx.font = "33px Arial";   
      ctx.fillText(label+String.fromCharCode(176),Math.round(r + 5 + size / 2),Math.round(size / 2));
       ctx.stroke();
    }else if ((zoomLevel > 1.5) && (zoomLevel <= 2))
    {
       ctx.beginPath();
      t1 = 3.0 * Math.tan(10 * Math.PI / 180);
      r = Math.round(t1 * zoomMult); //166.67 = 1000/6 
      ctx.arc(Math.round(size/2), Math.round(size/2), r, 0, 2 * Math.PI);
      ctx.stroke();
      label = "10"; 
      ctx.beginPath();
        ctx.fillStyle = '#000000';
       ctx.font = "33px Arial";   
      ctx.fillText(label+String.fromCharCode(176),Math.round(r + 5 + size / 2),Math.round(size / 2));
         ctx.stroke();
      //Draw 20 degree circle
      ctx.beginPath();
      t1 = 3.0 * Math.tan(20 * Math.PI / 180);
      r = Math.round(t1 * zoomMult); //166.67 = 1000/6 
      ctx.arc(Math.round(size/2), Math.round(size/2), r, 0, 2 * Math.PI);
      ctx.stroke();
      label = "20"; 
      ctx.beginPath();
        ctx.fillStyle = '#000000';
       ctx.font = "33px Arial";   
      ctx.fillText(label+String.fromCharCode(176),Math.round(r + 5 + size / 2),Math.round(size / 2));
       ctx.stroke();
    }else if ((zoomLevel > 2.0) && (zoomLevel <= 4.0))
    {
      ctx.beginPath();
      t1 = (3.0 * Math.tan(5 * Math.PI / 180));
      r = Math.round(t1 * zoomMult); //166.67 = 1000/6
      ctx.arc(Math.round(size/2), Math.round(size/2), r, 0, 2 * Math.PI);
      ctx.stroke();
      label = "5"; 
      ctx.beginPath();
        ctx.fillStyle = '#000000';
         ctx.font = "33px Arial"; 
      ctx.fillText(label+String.fromCharCode(176),Math.round(r + 5 + size / 2),Math.round(size / 2));
         ctx.stroke();
      //Draw 10 degree circle
     ctx.beginPath();
      t1 = (3.0 * Math.tan(10 * Math.PI / 180));
      r = Math.round(t1 * zoomMult); //166.67 = 1000/6
      ctx.arc(Math.round(size/2), Math.round(size/2), r, 0, 2 * Math.PI);
      ctx.stroke();
      label = "10"; 
      ctx.beginPath();
        ctx.fillStyle = '#000000';
         ctx.font = "33px Arial"; 
      ctx.fillText(label+String.fromCharCode(176),Math.round(r + 5 + size / 2),Math.round(size / 2));
       ctx.stroke();
    }
 
  ctx.stroke();
  setZoomLevel($("#test-type").val(),value);
}
function setZoomLevel(testTypeId,testName) {
  if(testTypeId==1){
      if(testName=='Armally_Central'){
        zoomLevel=1.0; 
      }else if(testName=='Esterman_120_point'){
        zoomLevel=0.2; 
      }else{
        zoomLevel=1.5;
      }
  }else if(testTypeId==2){
      if(testName=='Central_10_2'){
        zoomLevel=3.5;
      }else if(testName=='Central_24_1'||testName=='Central_24_2'){
        zoomLevel=1.75;
      }else if(testName=='Central_30_1'||testName=='Central_30_2'){
        zoomLevel=1.5;
      }else{
        zoomLevel=1.5;
      }
  }else if(testTypeId==3){
      if(testName=='Superior_64'){
        zoomLevel=0.5;
      }else if(testName=='Superior_50_1'){
        zoomLevel=0.5;
      }else if(testName=='Superior_24_2'){
        zoomLevel=1.875;
      }else if(testName=='Superior_30_2'){
        zoomLevel=1.5;
      }else{
        zoomLevel=1.5;
      }
  }else if(testTypeId==4){
      if(testName=='Full_Field_120_PTS'){
        zoomLevel=0.2;
      }else{
        zoomLevel=1.5;
      }
  }else if(testTypeId==5){
      zoomLevel=0.17; 
      AutoFixation=false;
      if(testName=='Ptosis_9_PT'){
         SetEyeTaped(true,false);
        sliderTestSpeed_maxValue = 8;
        sliderTestSpeed_minValue = 2;
        testSpeed = 7;
        updateSpeed(sliderTestSpeed_minValue,sliderTestSpeed_maxValue,testSpeed);

      }else if(testName=='Ptosis_Auto_9_PT'){
         SetEyeTaped(true,false);
        autoPtosisReport = true;
        if(round1==0){
          ptosisReportIndex = 0;
        }
        sliderTestSpeed_maxValue = 8;
        sliderTestSpeed_minValue = 2;
        testSpeed = 7;
        updateSpeed(sliderTestSpeed_minValue,sliderTestSpeed_maxValue,testSpeed);
      }else{
        sliderTestSpeed_maxValue = 8;
        sliderTestSpeed_minValue = 2;
        testSpeed = 3;
        updateSpeed(sliderTestSpeed_minValue,sliderTestSpeed_maxValue,testSpeed);
      }
  }else{
    zoomLevel=1.5;
  }
    
      
}
function ShowavailableReports(testTypeId, thresholdTypeID) {
 
  if (flagFDT == 1)
    {
        SymbolMap = false;
        NumericMap = true;
        GrayScaleMap = true;
        ReportType = 1;   //0=screening, 1=Threshold
    }else{
      if ((testTypeId == 1) || (testTypeId == 3) || (testTypeId == 4)) //Screening or Ptosis or Nuero
    {

      ReportType = 0;   //0=screening, 1=Threshold 
      if(thresholdTypeID==1){
           SymbolMap = true;
          NumericMap = false;
          GrayScaleMap = false;
      }else if(thresholdTypeID==2){
         SymbolMap = true;
          NumericMap = false;
          GrayScaleMap = false;
      }else if(thresholdTypeID==3){
         SymbolMap = true;
          NumericMap = false;
          GrayScaleMap = false;
      }else if(thresholdTypeID==4){
         SymbolMap = true;
          NumericMap = false;
          GrayScaleMap = false;
      } 
    }
    else if (testTypeId == 2) //Threshold
    {
      ReportType = 1;   //0=screening, 1=Threshold
      if(thresholdTypeID==1){
         SymbolMap = false;
          NumericMap = true;
          GrayScaleMap = true;
      }else if(thresholdTypeID==2){
         SymbolMap = false;
          NumericMap = true;
          GrayScaleMap = true;
      }else if(thresholdTypeID==3 || thresholdTypeID==4){
         SymbolMap = false;
          NumericMap = true;
          GrayScaleMap = true;
      } 
    }
    else if (testTypeId == 5) //Kinetic
    {
      ReportType = 2;   //0=screening, 1=Threshold, 2=Kinetic
      SymbolMap = true;
      NumericMap = false;
      GrayScaleMap = false;
    }
    } 
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
function clertData(argument) {
 stopTestforce();
 dataCaptured=false;
 
        numTestPointsCompleted = 0;
        numTestPoints = 0;
       
    }
    function removeEyeTaped() {
     $("#eye-taped-parent").remove();
     EyeTapedStatus=0;
   }
   function addEyeTaped(value=false) {  
    if(EyeTapedStatus==0){
    EyeTapedStatus=1;
    var state=''
    if(value){
      state='checked';
    }
    $(".mt-checkboxes-eye").prepend("<div class='form-group' id='eye-taped-parent'><input type='checkbox' id='eye-taped' "+state+" class='eye-taped' aria-label='...' onchange='eyeTaped(this)'><label for='eye-taped'><span type='checkbox' style='font-weight:500;'>Eye Lid Taped</span></label> </div>");  
 }
  }
  function SetEyeTaped(status,value){
    if(status==true){
       // removeEyeTaped();
     
        addEyeTaped(value);
        eye_taped=value;
    }else{
      
      removeEyeTaped();
      eye_taped=true;
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

  function changeRelibility() {
     if (!GazeTracking)
      {
        setTestReliability("Reliability");  
        reliabilityCount = 0;

        if (falsePosTotal > 0){
          falsePosError = parseFloat(falsePosCount)/parseFloat(falsePosTotal); 
        }
        else{
          falsePosError = -1;
        }

        if (falseNegTotal > 0){
          falseNegError = parseFloat(falseNegCount) / parseFloat(falseNegTotal);
        }
        else{
          falseNegError = -1;
        }

        if (testType == 3)   //Ptosis
        {
          fixLossError = 0;
        }
        else
        {
          if (AutoFixation==true)
          {
            fixLossError = 0;
          }
          else
          {
            if (fixationLossTotal > 0)
              fixLossError = parseFloat(fixationLossCount) / parseFloat(fixationLossTotal);
            else
              fixLossError = -1;
          }
        }

        if ((falsePosError == -1) || (falseNegError == -1) || (fixLossError == -1))
        {
          setReliabilitybtn("#808080");
        }
        else
        {
          if (falsePosError > 0.2){
            reliabilityCount++;
          }
          if (falseNegError > 0.2){
            reliabilityCount++;
          }
          if (fixLossError > 0.2){
            reliabilityCount++;
          }

          switch (reliabilityCount)
          {
            case 0:
              setReliabilitybtn("#00ff00");
              break;
            case 1:
              setReliabilitybtn("#ffff00");
              break;
            case 2:
            case 3:
              setReliabilitybtn("#ff0000");
              break;
          }
        }
      }
  }
  function desableTestName(argument,type) {
    device_type='';
   if(type==1){
       device_type='_Go';
   }else if(type==2 || type==4){
      device_type='_PICO';
   }else if(type==3){
      device_type='_Quest';
   }
   enabletestnameliest=[];
    $(".test-type-name").each((key, element) => {
          let value = $(element).text();
          $(element).addClass("md-btn-desabley");
          $(element).value=0;
           $.each(argument, function( index, value2 ) {
            if(value2.Masterdata.test_name==value+device_type){ 
              $(element).removeClass("md-btn-desabley");
              enabletestnameliest.push(value);
            } 
          }); 
     });
  }
  function stop_save_yes(argument) {

    startTestStatus=6;
    round1=0;
    saveresult=false;
    dataCaptured=true; 
    dataCapturedpause=false;
    
    stopTest();
    TestData();
    deviceMessages=[];
  }
   function stop_save_no(argument) {
    round1=0;
    startTestStatus=0;
    saveresult=true;
    dataCaptured=false;
    dataCapturedpause=false; 
    stopTest();
    TestData();
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
function setGaze(argument) {
    deviceTypeId=argument;
  updateRelibility();
  if(argument==0){
    $("#gaze_tracking_txt").html('Gaze Tracking');
     document.getElementById("gaze_tracking_div").style.visibility = "visible";
     document.getElementById("gaze_tracking_div").style.height = "20px";
  }else if(argument==2){
     $("#gaze_tracking_txt").html('Eye Tracking');
     document.getElementById("gaze_tracking_div").style.visibility = "visible";
     document.getElementById("gaze_tracking_div").style.height = "20px"; 
  }else{
    document.getElementById("gaze_tracking_div").style.visibility = "hidden";
    document.getElementById("gaze_tracking_div").style.height = "1px";
  } 
}
 function changeTest(value,stragigy='',testname='') {

 
 testname2=testname.toString(); 
    selectTest(value,stragigy,testname); 
     if(value==3){
      addEyeTaped();
    }else{ 
        removeEyeTaped(); 
        setZoomLevel(value,testname2); 
    }
    if (flagFDT == 0)
    {
      if (value == 5)
            {
              sliderTestSpeed_maxValue = 8;
              sliderTestSpeed_minValue = 2;
              testSpeed = 3;
              updateSpeed(sliderTestSpeed_minValue,sliderTestSpeed_maxValue,testSpeed);
            }else{
              sliderTestSpeed_maxValue = 1.2;
              sliderTestSpeed_minValue = 0.2;
              testSpeed = 0.7;
              updateSpeed(sliderTestSpeed_minValue,sliderTestSpeed_maxValue,testSpeed); 
            }
    }else{
              sliderTestSpeed_maxValue = 1.2;
              sliderTestSpeed_minValue = 0.2;
              testSpeed = 0.7;
              updateSpeed(sliderTestSpeed_minValue,sliderTestSpeed_maxValue,testSpeed); 
    }
  }
  function reloadPage(type) {
   
    if(type=='testId'){
      if (((dataCaptured)|| (dataCapturedpause)|| (!saveresult)) && (testType != -1)){

           var r = confirm("Report has not been saved and the last test results will be lost. Do you want to switch the test type?");
           if (r == true) {
              window.location.replace('<?php echo WWW_BASE.'admin/patients/start_test_fdt/'.$this->request->params['pass'][0] ?>/'+$("#test-type").val()+'?selectEye='+selectEye+'&GazeTracking='+GazeTracking+'&alarm_stop='+alarm_stop+'&testBothEyes='+testBothEyes+'&voice_instractuin='+voice_instractuin+'&progression_analysis='+progression_analysis);
          } else {
              document.getElementById("test-type").value = testType;
         }
      }else{
          window.location.replace('<?php echo WWW_BASE.'admin/patients/start_test_fdt/'.$this->request->params['pass'][0] ?>/'+$("#test-type").val()+'?selectEye='+selectEye+'&GazeTracking='+GazeTracking+'&alarm_stop='+alarm_stop+'&testBothEyes='+testBothEyes+'&voice_instractuin='+voice_instractuin+'&progression_analysis='+progression_analysis);
      } 
    }else if(type=='strategy'){
        if(!saveresult){
            var r = confirm("Report has not been saved and the last test results will be lost. Do you want to switch the test type?");
            if (r == true) { 
               window.location.replace('<?php echo WWW_BASE.'admin/patients/start_test_fdt/'.$this->request->params['pass'][0] ?>/'+$("#test-type").val()+'/'+$("#test-strategy").val()+'/'+testTypeName+'?selectEye='+selectEye+'&GazeTracking='+GazeTracking+'&alarm_stop='+alarm_stop+'&testBothEyes='+testBothEyes+'&voice_instractuin='+voice_instractuin+'&progression_analysis='+progression_analysis);
            } else {
              document.getElementById("test-strategy").value = testSubType;
            }
        }else{
            window.location.replace('<?php echo WWW_BASE.'admin/patients/start_test_fdt/'.$this->request->params['pass'][0] ?>/'+$("#test-type").val()+'/'+$("#test-strategy").val()+'/'+testTypeName+'?selectEye='+selectEye+'&GazeTracking='+GazeTracking+'&alarm_stop='+alarm_stop+'&testBothEyes='+testBothEyes+'&voice_instractuin='+voice_instractuin+'&progression_analysis='+progression_analysis);
        }

    }else{
       if($("#test-type").val()==2){
        if(enabletestnameliest.includes(type)){
          if(!saveresult){
          if((!dataCaptured) && (!dataCapturedpause)){ 
              var r = confirm("Report has not been saved and the last test results will be lost. Do you want to switch the test?");
              if (r == true) {
              window.location.replace('<?php echo WWW_BASE.'admin/patients/start_test_fdt/'.$this->request->params['pass'][0] ?>/'+$("#test-type").val()+'/'+$("#test-strategy").val()+'/'+type+'?selectEye='+selectEye+'&GazeTracking='+GazeTracking+'&alarm_stop='+alarm_stop+'&testBothEyes='+testBothEyes+'&voice_instractuin='+voice_instractuin+'&progression_analysis='+progression_analysis);
              } 
          }
      }else{
          window.location.replace('<?php echo WWW_BASE.'admin/patients/start_test_fdt/'.$this->request->params['pass'][0] ?>/'+$("#test-type").val()+'/'+$("#test-strategy").val()+'/'+type+'?selectEye='+selectEye+'&GazeTracking='+GazeTracking+'&alarm_stop='+alarm_stop+'&testBothEyes='+testBothEyes+'&voice_instractuin='+voice_instractuin+'&progression_analysis='+progression_analysis);
      } 
        }
      
    }else{
      if(!saveresult){
          if((!dataCaptured) && (!dataCapturedpause)){ 
              var r = confirm("Report has not been saved and the last test results will be lost. Do you want to switch the test?");
              if (r == true) {
              window.location.replace('<?php echo WWW_BASE.'admin/patients/start_test_fdt/'.$this->request->params['pass'][0] ?>/'+$("#test-type").val()+'/'+$("#test-strategy").val()+'/'+type+'?selectEye='+selectEye+'&GazeTracking='+GazeTracking+'&alarm_stop='+alarm_stop+'&testBothEyes='+testBothEyes+'&voice_instractuin='+voice_instractuin+'&progression_analysis='+progression_analysis);
              } 
          }
      }else{
          window.location.replace('<?php echo WWW_BASE.'admin/patients/start_test_fdt/'.$this->request->params['pass'][0] ?>/'+$("#test-type").val()+'/'+$("#test-strategy").val()+'/'+type+'?selectEye='+selectEye+'&GazeTracking='+GazeTracking+'&alarm_stop='+alarm_stop+'&testBothEyes='+testBothEyes+'&voice_instractuin='+voice_instractuin+'&progression_analysis='+progression_analysis);
      } 
    }
}
}
  function desableAll() {
     document.getElementById("test-device").disabled = true;
     document.getElementById("test-type").disabled = true;
      document.getElementById("language").disabled = true;
     document.getElementById("test-strategy").disabled = true;
     var elems = document.getElementsByClassName("gaze-track");
     for(var i = 0; i < elems.length; i++) {
        elems[i].disabled = true;
     }
     var elems = document.getElementsByClassName("both-eye");
     for(var i = 0; i < elems.length; i++) {
        elems[i].disabled = true;
     }
     var elems = document.getElementsByClassName("eye-taped");
     for(var i = 0; i < elems.length; i++) {
        elems[i].disabled = true;
     }
  }
  function enableAll() {
    document.getElementById("test-device").disabled = false;
     document.getElementById("test-type").disabled = false;
      document.getElementById("language").disabled = false;
     document.getElementById("test-strategy").disabled = false;
     var elems = document.getElementsByClassName("gaze-track");
     for(var i = 0; i < elems.length; i++) {
        elems[i].disabled = false;
     }
     var elems = document.getElementsByClassName("both-eye");
     for(var i = 0; i < elems.length; i++) {
        elems[i].disabled = false;
     }
     var elems = document.getElementsByClassName("eye-taped");
     for(var i = 0; i < elems.length; i++) {
        elems[i].disabled = false;
     }
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
          data: {"user_preset_text": $("#user-preset-input").val(),"user_preset": user_preset,"testType": $("#test-type").val(),"testSubType": $("#test-strategy").val(),"testTypeName": testTypeName,"eye_taped": eye_taped,"GazeTracking": GazeTracking,"alarm_stop":alarm_stop,"testBothEyes": testBothEyes,"stimulusSize": stimulusSize,"stimulusIntensity": stimulusIntensity,"wallBrightness": wallBrightness,"testSpeed": testSpeed,"audioVolume": audioVolume,"testColour": testColour,"testBackground": testBackground,"selectEye":selectEye,"sliderTestSpeed_maxValue": sliderTestSpeed_maxValue,"sliderTestSpeed_minValue":sliderTestSpeed_minValue,"voice_instractuin":voice_instractuin,"progression_analysis":progression_analysis},
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
 // function setUserPreset(user_preset) {
  await sleep(40);
       $.ajax({
          url: "<?php echo WWW_BASE; ?>admin/patients/getuserpreset",
          type: 'POST', 
          data: {"user_preset": user_preset},
          success: function(data){  
            if(set_user_preset){
              setUserPresetselect(user_preset);
              data=JSON.parse(data)  
                if (data.user_preset_data.hasOwnProperty('UserPresetData')) {
                   GazeTracking=(data.user_preset_data.UserPresetData.GazeTracking == "true")? true : false;
                   voice_instractuin=(data.user_preset_data.UserPresetData.voice_instractuin == "true")? true : false;
                   progression_analysis=(data.user_preset_data.UserPresetData.progression_analysis == "true")? true : false;
                  alarm_stop=(data.user_preset_data.UserPresetData.alarm_stop == "true")? true : false;
                  testBothEyes=(data.user_preset_data.UserPresetData.testBothEyes == "true")? true : false; 
                  eye_taped=(data.user_preset_data.UserPresetData.eye_taped == "true")? true : false;
                  audioVolume=parseFloat(data.user_preset_data.UserPresetData.audioVolume);
                  testSpeed=parseFloat(data.user_preset_data.UserPresetData.testSpeed);
                  stimulusIntensity=parseInt(data.user_preset_data.UserPresetData.stimulusIntensity);
                  stimulusSize=parseInt(data.user_preset_data.UserPresetData.stimulusSize); 
                  testBackground=parseInt(data.user_preset_data.UserPresetData.testBackground);
                  testColour=parseInt(data.user_preset_data.UserPresetData.testColour);
                  testSubType=parseInt(data.user_preset_data.UserPresetData.testSubType);
                  testType=parseInt(data.user_preset_data.UserPresetData.testType);
                  testTypeName=data.user_preset_data.UserPresetData.testTypeName;
                  wallBrightness=parseInt(data.user_preset_data.UserPresetData.wallBrightness);
                  console.log('2222 ',wallBrightness);
                  selectEye=parseInt(data.user_preset_data.UserPresetData.selectEye);
                  sliderTestSpeed_maxValue=parseFloat(data.user_preset_data.UserPresetData.sliderTestSpeed_maxValue);
                  sliderTestSpeed_minValue=parseFloat(data.user_preset_data.UserPresetData.sliderTestSpeed_minValue);
                   // $("#test-type").val();
                  document.getElementById("test-type").value = testType;
                //  selectTest(testType); 
                PopulateList2(testType);
                   if(testType==3){
                    removeEyeTaped(); 
                    addEyeTaped();
                  }else{
                    removeEyeTaped();  
      if(testTypeName!=''){
     setZoomLevel(testType,testTypeName); 
   }
                  }
                  document.getElementById("test-strategy").value = testSubType;

                  $(".test-type-name").each((key, element) => {
                let value = $(element).text(); 
                if(testTypeName==value){
                  $(element).removeClass("md-btn-gry"); 
                }else{
                   $(element).addClass("md-btn-gry");
                }
              });
                   drawImage(testTypeName);
                    $(".progression_analysis").prop("checked", progression_analysis); 
                    $(".voice_instructions").prop("checked", voice_instractuin); 
                  $(".gaze-track").prop("checked", GazeTracking); 
                  $(".alarm-stop").prop("checked", alarm_stop);
                  $(".eye-taped").prop("checked", eye_taped);
                  $(".both-eye").prop("checked", testBothEyes);
                    $('.eye').addClass("md-btn-gry");
                   $('.eye').removeClass("md-btn-yellow");
                    $(".eye").each((key, element) => {
                                let value = $(element).val(); 
                                if(value==selectEye){
                                  $(element).removeClass("md-btn-gry");  
                                   $(element).addClass("md-btn-yellow");
                                } 
                              });

                  if(testBothEyes){ 
                  // selectEye=2;
                  // $('.eye').addClass("md-btn-gry");
                  // $('.eye').removeClass("md-btn-yellow");
                  }else{ 
                   
                   // $('.eye').addClass("md-btn-gry");
                   // $('.eye').removeClass("md-btn-yellow");
                   //  $(".eye").each((key, element) => {
                   //              let value = $(element).val(); 
                   //              if(value==selectEye){
                   //                $(element).removeClass("md-btn-gry");  
                   //                 $(element).addClass("md-btn-yellow");
                   //              } 
                   //            });

                  } 

                 

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
  function botheye(obj) {

  if($(obj).is(":checked")){
    if(patient_previous_test==1){
        patinet_traning_value = "1";
        $(".patient_training").prop("checked", true);
      }
   testBothEyes=true;
  // selectEye=2;
   botheyecount = 0;
  selectEye=1;
          $('.eye').addClass("md-btn-gry");
          $('.eye').removeClass("md-btn-yellow");
          $(".eye").each((key, element) => {
          let value = $(element).val(); 
          if(value==1){
            $(element).removeClass("md-btn-gry");  
            $(element).addClass("md-btn-yellow");
          } 
        });
  }else{
    testBothEyes=false;
    if(patient_previous_test==1){
        patinet_traning_value = "0";
        $(".patient_training").prop("checked", false);
      }

  } 
}
function patienTrainingUpdate(obj) {

    if ($(obj).is(":checked")) {

      patinet_traning_value = "1";

    } else {

      patinet_traning_value = "0";

    }

  }
function TestWithEyeTaped() { 
        if (dialogResult == 1)
        {
            ptosisReportIndex = 1; 
        }
        
}
function updateRelibility(argument) {
  if(deviceTypeId==2 && GazeTracking==true){
    setTestReliability('Fixation');
  }else{
    setTestReliability('Reliability');
  } 
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
 function gazetrack(obj) {
  if($(obj).is(":checked")){
   GazeTracking=true;
  }else{
    GazeTracking=false;
  }  
   updateRelibility(); 
}
function alarmstop(obj) {
  if($(obj).is(":checked")){
   alarm_stop=true;
  }else{
    alarm_stop=false;
  } 
}
 function eyeTaped(obj) {
  if($(obj).is(":checked")){
   eye_taped=true;
   ptosisReportIndex=1;
  }else{
    eye_taped=false;
    ptosisReportIndex=0;
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
   $(".reload_page").attr("style", "width:auto;width:auto; margin-top:5px; margin-botom:5px; padding-top:1px; padding-bottom:1px; padding-left:6px; padding-right:6px;");
     
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
   const setting_alert_btn_press = async function(msg) { 
     $("#connection-help").attr("style", "color:#ffffff; visibility:hidden");
   $("#connection-verify").addClass('md-btn-yellow');
   $("#connection-verify").html(msg); 
  $("#connection-verify").attr("style", "color:#ffffff; visibility:visible");
  await sleep(2000);
  $("#connection-verify").attr("style", "color:#ffffff; visibility:hidden");
  }
     const setting_alert = async function(msg) { 
     $(".setting-alert-message").html(msg);
      await sleep(2000);
      $(".setting-alert-message").html("");
  }

  const setting_alert_new = async function(msg) { 
     $(".setting-alert-message").html(msg); 
  }
  const setting_alert_new2 = async function(msg,time) { 
     $(".setting-alert-message").html(msg);
      await sleep(time);
      $(".setting-alert-message").html("");
  }
   const checkpausestatus = async function(count) {  
       await sleep(1000*60*15); 
      if(pausecount==count){ 
        dataCaptured=false;
        dataCapturedpause=false;
        startTestStatus=0;
        saveresult=true;
        stopTest();
        TestData();
        deviceMessages=[];
      }
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
      updateDevice();
      document.getElementById("view-pdf-url").style.visibility = "hidden"; 
    } else {
     document.getElementById("test-device").value = deviceId;
  }
}else{
  document.getElementById("view-pdf-url").style.visibility = "hidden"; 
  updateDevice();
}
  }
  function updateSpeed(min=0.2,max=1.2,value=0.6) {
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
       data: {"page": 2,"deviceId": deviceId,"languageId": language}, 
      success: function(data){ 
        data=JSON.parse(data) 
        setGaze(data.device.TestDevice.device_type);
            if($("#test-type").val()==2){
                desableTestName(data.masterdata,data.device.TestDevice.device_type);
            }
        }
      }); 
   }
  }
  function TestData() {
    var tmp_testTypeName='';
    if ((testTypeName == "N30-1") || (testTypeName == "N30-5") || (testTypeName == "C20-1") || (testTypeName == "C20-5")){
       tmp_testTypeName = "N30";
     }else{
        tmp_testTypeName = testTypeName;
     }
                

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
            reloadPageButton();
         }else{
            setting_alert('Something wrong. Please try again',2000);
         }
          
    },
      success: function(data){
        if(data==1 && startTestStatus==1){
          setting_alert('Device busy try again');
          stopTestforce();
          dataCaptured=false;
        }else{


    var language=$("#language").val();
   
    $.ajax({
      url: "<?php echo WWW_BASE; ?>admin/masterReports/testdata/3554",
      type: 'GET',
 
      data: {"ageGroup": agegroup, "testTypeName": tmp_testTypeName,"patient_id": '<?php echo $data['Patient']['id'] ?>', "testType": testType, "eye":selectEye,"deviceId": deviceId },
      error: function (request, status, error) {
        
         dataCaptured=false;
         restartdata=false;
         saveresult=true;
         stopTest();
         if(request.status==403){
           reloadPageButton();
         }else{
            setting_alert('Something wrong. Please try again');
         }
          
    },
      success: function(data){
           /*if(startTestStatus==1 && testType==5){ 
              measureReactionTime=true;
          }else{
             measureReactionTime=false;
          }*/
        data=JSON.parse(data) 
        var arr=data.VfMasterdata;  
      //  if(data.VfMasterdata.length!=0){
          if(data.hasOwnProperty('VfMasterdata')){
          masterData.vfpointdata = data.VfMasterdata.slice(); 
        }
        if(testType==2 && (!data.hasOwnProperty('VfMasterdata'))){
          alert('The device have no master record');
        }
         if(data.hasOwnProperty('Masterdata')){
            stimulusSize=(data.hasOwnProperty('Masterdata'))?parseInt(data.Masterdata.stmsize):null; 
              $("#myinput").val(stimulusSize); 
                $("#myinput-val").html(stimulusSize); 
                $("#setting-stm-size").html(stimulusSize); 
                document.getElementById("myinput").style.background = 'linear-gradient(to right, #f5f5f5 0%, #f5f5f5 ' + (stimulusSize)*10 + '%, #d6d6d6 ' + stimulusSize + '%, #d6d6d6 100%)';
                wallBrightness=(data.hasOwnProperty('Masterdata'))?parseInt(data.Masterdata.backgroundcolor):null;
                console.log('33333 ',wallBrightness);
                console.log((data.Masterdata));
                console.log((data.Masterdata.backgroundcolor));
                console.log(parseInt(data.Masterdata.backgroundcolor));
                $("#myinput-2").val(wallBrightness*100/96);
                $("#myinput-2-val").html(wallBrightness);
                $("#setting-bkg-color").html(wallBrightness);


               document.getElementById("myinput-2").style.background = 'linear-gradient(to right, #f5f5f5 0%, #f5f5f5 ' + wallBrightness*100/96 + '%, #d6d6d6 ' + wallBrightness*100/96 + '%, #d6d6d6 100%)';


                testColour=(data.hasOwnProperty('Masterdata'))?parseInt(data.Masterdata.test_color_fg):null;
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
                 testBackground=(data.hasOwnProperty('Masterdata'))?parseInt(data.Masterdata.test_color_bg):null;
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
        //  masterData.vfpointdata = data.VfMasterdata.slice(); 
        }
        
        var resultm;
        if(data.hasOwnProperty('VfMasterdata')){
            
            var resultm=data.VfMasterdata.map(function(x){ 
            x.x=parseFloat(x.x);
            x.y=parseFloat(x.y);
            x.STD=parseFloat(x.STD);
            //x.index=parseInt(x.index);
            //x.index=toString(x.index);
            x.intensity=parseFloat(x.intensity);
            x.size=parseInt(x.size); 
              return x; 
            }); 
        }
        if(testType==2){
        if(data.hasOwnProperty('previousTest')){
            
            var resultm2=data.previousTest.map(function(testdata){ 
            var resultm3=testdata.VfPointdata.map(function(testdata2){
            testdata2.x=parseFloat(testdata2.x);
            testdata2.y=parseFloat(testdata2.y);
            testdata2.STD=parseFloat(testdata2.STD);
            testdata2.index=parseInt(testdata2.index);
            testdata2.intensity=parseFloat(testdata2.intensity);
            testdata2.size=parseInt(testdata2.size); 
             //  return testdata2; 
            });  
              testdata.Pointdata.backgroundcolor=parseInt(testdata.Pointdata.backgroundcolor);
              testdata.Pointdata.stmSize=parseInt(testdata.Pointdata.stmSize); 
              testdata.Pointdata.eye_select=parseInt(testdata.Pointdata.eye_select); 
              testdata.Pointdata.test_color_fg=parseInt(testdata.Pointdata.test_color_fg);
              testdata.Pointdata.test_color_bg=parseInt(testdata.Pointdata.test_color_bg);
              testdata.Pointdata.baseline=parseInt(testdata.Pointdata.baseline);
              
               return testdata; 
                });
        } 
      }
        
        MasterRecordData.age_group=(data.hasOwnProperty('Masterdata'))?data.Masterdata.age_group.toString():"";
        MasterRecordData.numpoints=(data.hasOwnProperty('Masterdata'))?data.Masterdata.numpoints:"";
        MasterRecordData.color=(data.hasOwnProperty('Masterdata'))?data.Masterdata.color.toString():"";
        MasterRecordData.backgroundcolor=(data.hasOwnProperty('Masterdata'))?data.Masterdata.backgroundcolor.toString():"";
        MasterRecordData.stmSize=(data.hasOwnProperty('Masterdata'))?parseInt(data.Masterdata.stmsize):null;
        MasterRecordData.master_key=(data.hasOwnProperty('Masterdata'))?data.Masterdata.master_key.toString():'';
        MasterRecordData.created_date=(data.hasOwnProperty('Masterdata'))?data.Masterdata.created.toString():''; 
        MasterRecordData.test_type_id=(parseInt(testType)+10).toString();
        MasterRecordData.test_name=testTypeName.toString();
        MasterRecordData.eye_select=parseInt(selectEye);
        MasterRecordData.test_color_fg=parseInt(testBackground);
        MasterRecordData.test_color_bg=parseInt(testColour); 
        MasterRecordData.threshold=(parseInt(testType)+10).toString();
        MasterRecordData.strategy=testSubType.toString(); 
        MasterRecordData.publicList=(data.hasOwnProperty('VfMasterdata'))?data.VfMasterdata:null;

          StartTestData.test_id=(parseInt(testType)+10);
          StartTestData.unique_id=''; 
          StartTestData.staff_name='<?php echo $user['first_name']." ".$user['last_name'];?>';
          StartTestData.staff_id='<?php echo $user['id']; ?>';  
          StartTestData.backgroundcolor=parseInt(wallBrightness);
          StartTestData.DisplaySelect=parseInt(DisplaySelect);
          StartTestData.autoPtosisReport=autoPtosisReport; 
          StartTestData.PTOSIS_INDEX=(ptosisReportIndex==1)?true:false; 
             
          StartTestData.zoomLevel=zoomLevel;
          StartTestData.Patient_Name='<?php echo $data['Patient']['first_name'];?>'+' '+'<?php echo $data['Patient']['middle_name'];?>'+' '+'<?php echo $data['Patient']['last_name'];?>';
          StartTestData.DOB='<?php echo $data['Patient']['dob'];?>';
          StartTestData.pid='<?php echo $data['Patient']['id'];?>';
          StartTestData.OfficePateintID='<?php echo $data['Patient']['id_number'];?>';
          StartTestData.od_left='<?php echo $data['Patient']['od_left'];?>';
          StartTestData.od_right='<?php echo $data['Patient']['od_right'];?>';
          StartTestData.os_left='<?php echo $data['Patient']['os_left'];?>';
          StartTestData.os_right='<?php echo $data['Patient']['os_right'];?>';
          StartTestData.eyeTaped=eye_taped;
          StartTestData.REACTION_TIME=measureReactionTime;
          StartTestData.GazeTracking=GazeTracking;
          StartTestData.PATIENT_TRAINING = patinet_traning_value;
          //StartTestData.testBothEyes=testBothEyes;
          if(botheyecount==2){
            StartTestData.testBothEyes=false;
          }else{
            StartTestData.testBothEyes=testBothEyes;
          } 
          StartTestData.LANGUAGE_SEL= language.toString(); 
          StartTestData.EYE= selectEye.toString(); 
          StartTestData.TEST_TYPE= (parseInt(testType)+10).toString();
          StartTestData.THRESHOLD_TYPE= testSubType.toString();
          StartTestData.TEST_SUB_TYPE= testTypeName.toString();
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
          var obj='';
          if(testType==2){
              var obj = {  
                previous_test:(data.hasOwnProperty('previousTest'))?data.previousTest:'',
                StartTest:StartTestData,
                MasterRecord:MasterRecordData, 
              }; 
          }else{
              var obj = {  
                previous_test:[],
                //previous_test:(data.hasOwnProperty('previousTest'))?data.previousTest:'',
                StartTest:StartTestData,
                MasterRecord:MasterRecordData, 
              }; 
          } 
        
var myJSON = JSON.stringify(obj);  
$.ajax({
      url: "<?php echo WWW_BASE; ?>admin/patients/addTestStart/3554",
      type: 'POST',

       data: {"page": 2,"testType":testType,"strategy":testSubType,"test_name":testTypeName,"patient_id": '<?php echo $data['Patient']['id'] ?>',"office_id": '<?php echo $user['office_id'] ?>',"deviceId": deviceId,"languageId": language, "TestStatus": startTestStatus, "testData": myJSON }, 
      success: function(data){
       data=JSON.parse(data); 
        if(data.status==2){ 
          deviceuse(data.staff_id,'<?php echo $user['id'] ?>',deviceId);
          stopTestforce();
          dataCaptured=false;
        }else{
           cleardata=true;
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
 }
      }
    });


  }
 function updateStatus() {
      var language=$("#language").val();
   
    $.ajax({
      url: "<?php echo WWW_BASE; ?>admin/masterReports/testdata/3554",
      type: 'GET',
 
      data: {"ageGroup": agegroup, "testTypeName": testTypeName,"patient_id": '<?php echo $data['Patient']['id'] ?>', "testType": testType, "eye":selectEye,"deviceId": deviceId },
      error: function (request, status, error) {
        
         dataCaptured=false;
         restartdata=false;
         saveresult=true;
         stopTest();
         if(request.status==403){

            window.location.replace('');
         }else{
            setting_alert('Something wrong. Please try again');
         }
          
    },
      success: function(data){
          
        data=JSON.parse(data) 
        var arr=data.VfMasterdata;   
          if(data.hasOwnProperty('VfMasterdata')){
          masterData.vfpointdata = data.VfMasterdata.slice(); 
        }
        if(testType==2 && (!data.hasOwnProperty('VfMasterdata'))){
          alert('The device have no master record');
        }
         if(data.hasOwnProperty('Masterdata')){
            stimulusSize=(data.hasOwnProperty('Masterdata'))?parseInt(data.Masterdata.stmsize):null;
            console.log('stm size'+stimulusSize);
              $("#myinput").val(stimulusSize); 
                $("#myinput-val").html(stimulusSize); 
                $("#setting-stm-size").html(stimulusSize); 
                document.getElementById("myinput").style.background = 'linear-gradient(to right, #f5f5f5 0%, #f5f5f5 ' + (stimulusSize)*10 + '%, #d6d6d6 ' + stimulusSize + '%, #d6d6d6 100%)';
                wallBrightness=(data.hasOwnProperty('Masterdata'))?parseInt(data.Masterdata.backgroundcolor):null;
                console.log('4444 ',wallBrightness);
                $("#myinput-2").val(wallBrightness*100/96);
                $("#myinput-2-val").html(wallBrightness);
                $("#setting-bkg-color").html(wallBrightness);


               document.getElementById("myinput-2").style.background = 'linear-gradient(to right, #f5f5f5 0%, #f5f5f5 ' + wallBrightness*100/96 + '%, #d6d6d6 ' + wallBrightness*100/96 + '%, #d6d6d6 100%)';


                testColour=(data.hasOwnProperty('Masterdata'))?parseInt(data.Masterdata.test_color_fg):null;
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
                 testBackground=(data.hasOwnProperty('Masterdata'))?parseInt(data.Masterdata.test_color_bg):null;
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
        
        var resultm;
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
        if(testType==2){
        if(data.hasOwnProperty('previousTest')){
            
            var resultm2=data.previousTest.map(function(testdata){ 
            var resultm3=testdata.VfPointdata.map(function(testdata2){
            testdata2.x=parseFloat(testdata2.x);
            testdata2.y=parseFloat(testdata2.y);
            testdata2.STD=parseFloat(testdata2.STD);
            testdata2.index=parseInt(testdata2.index);
            testdata2.intensity=parseFloat(testdata2.intensity);
            testdata2.size=parseInt(testdata2.size); 
            });  
              testdata.Pointdata.backgroundcolor=parseInt(testdata.Pointdata.backgroundcolor);
              testdata.Pointdata.stmSize=parseInt(testdata.Pointdata.stmSize); 
              testdata.Pointdata.eye_select=parseInt(testdata.Pointdata.eye_select); 
              testdata.Pointdata.test_color_fg=parseInt(testdata.Pointdata.test_color_fg);
              testdata.Pointdata.test_color_bg=parseInt(testdata.Pointdata.test_color_bg);
              testdata.Pointdata.baseline=parseInt(testdata.Pointdata.baseline);
              
               return testdata; 
                });
        } 
      }
        
        MasterRecordData.age_group=(data.hasOwnProperty('Masterdata'))?data.Masterdata.age_group.toString():"";
        MasterRecordData.numpoints=(data.hasOwnProperty('Masterdata'))?data.Masterdata.numpoints:"";
        MasterRecordData.color=(data.hasOwnProperty('Masterdata'))?data.Masterdata.color.toString():"";
        MasterRecordData.backgroundcolor=(data.hasOwnProperty('Masterdata'))?data.Masterdata.backgroundcolor.toString():"";
        MasterRecordData.stmSize=(data.hasOwnProperty('Masterdata'))?parseInt(data.Masterdata.stmsize):null;
        MasterRecordData.master_key=(data.hasOwnProperty('Masterdata'))?data.Masterdata.master_key.toString():'';
        MasterRecordData.created_date=(data.hasOwnProperty('Masterdata'))?data.Masterdata.created.toString():''; 
        MasterRecordData.test_type_id=testSubType.toString();
        MasterRecordData.test_name=testTypeName.toString();
        MasterRecordData.eye_select=parseInt(selectEye);
        MasterRecordData.test_color_fg=parseInt(testBackground);
        MasterRecordData.test_color_bg=parseInt(testColour); 
        MasterRecordData.threshold=testType.toString();
        MasterRecordData.strategy=testTypeName.toString(); 
        MasterRecordData.publicList=(data.hasOwnProperty('VfMasterdata'))?data.VfMasterdata:null;

          StartTestData.test_id=testType;
          StartTestData.unique_id=''; 
          StartTestData.staff_name='<?php echo $user['first_name']." ".$user['last_name'];?>';
          StartTestData.staff_id='<?php echo $user['id']; ?>';  
          StartTestData.backgroundcolor=parseInt(wallBrightness);
          StartTestData.DisplaySelect=parseInt(DisplaySelect);
          StartTestData.autoPtosisReport=autoPtosisReport;            
          StartTestData.PTOSIS_INDEX=(ptosisReportIndex==1)?true:false;
          
           
             
          StartTestData.zoomLevel=zoomLevel;
          StartTestData.Patient_Name='<?php echo $data['Patient']['first_name'];?>'+' '+'<?php echo $data['Patient']['middle_name'];?>'+' '+'<?php echo $data['Patient']['last_name'];?>';
          StartTestData.DOB='<?php echo $data['Patient']['dob'];?>';
          StartTestData.pid='<?php echo $data['Patient']['id'];?>';
          StartTestData.OfficePateintID='<?php echo $data['Patient']['id_number'];?>';
          StartTestData.od_left='<?php echo $data['Patient']['od_left'];?>';
          StartTestData.od_right='<?php echo $data['Patient']['od_right'];?>';
          StartTestData.os_left='<?php echo $data['Patient']['os_left'];?>';
          StartTestData.os_right='<?php echo $data['Patient']['os_right'];?>';
          StartTestData.eyeTaped=eye_taped;
          StartTestData.REACTION_TIME=measureReactionTime;
          StartTestData.GazeTracking=GazeTracking;
          StartTestData.PATIENT_TRAINING = patinet_traning_value;
          //StartTestData.testBothEyes=testBothEyes; 
          if(botheyecount==2){
            StartTestData.testBothEyes=false;
          }else{
            StartTestData.testBothEyes=testBothEyes;
          }
          StartTestData.LANGUAGE_SEL= language.toString(); 
          StartTestData.EYE= selectEye.toString(); 
          StartTestData.TEST_TYPE= testType.toString();
          StartTestData.THRESHOLD_TYPE= testSubType.toString();
          StartTestData.TEST_SUB_TYPE= testTypeName.toString();
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
          var obj='';
          if(testType==2){
              var obj = {  
                previous_test:(data.hasOwnProperty('previousTest'))?data.previousTest:'',
                StartTest:StartTestData,
                MasterRecord:MasterRecordData, 
              }; 
          }else{
              var obj = {  
                previous_test:[],
                StartTest:StartTestData,
                MasterRecord:MasterRecordData, 
              }; 
          } 
        
var myJSON = JSON.stringify(obj);  
$.ajax({
      url: "<?php echo WWW_BASE; ?>admin/patients/addTestStart/3554",
      type: 'POST',
       data: {"page": 1,"testType":testType,"strategy":testSubType,"test_name":testTypeName,"patient_id": '<?php echo $data['Patient']['id'] ?>',"office_id": '<?php echo $user['office_id'] ?>',"deviceId": deviceId,"languageId": language, "TestStatus": startTestStatus, "testData": myJSON }, 
      success: function(data){
        
        }
      });

}
});
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
     console.log('5555 ',wallBrightness);
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
     
     $("#myinput-3-val").html(testSpeed);
     $("#setting-speed").html(testSpeed);
  };

  document.getElementById("myinput-4").oninput = function() {
    this.style.background = 'linear-gradient(to right, #f5f5f5 0%, #f5f5f5 ' + this.value + '%, #d6d6d6 ' + this.value + '%, #d6d6d6 100%)';
     audioVolume=(parseInt($("#myinput-4").val())/100).toFixed(2);
     $("#myinput-4-val").html(audioVolume);  
  };
</script>
