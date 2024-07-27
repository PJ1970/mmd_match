 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <link rel="stylesheet" href="/resources/demos/style.css">
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
             <h4 class="page-title2" style="color: #ffffff;">Name: </h4>
          </div>
          <div class="col-sm-2 mts-box-1">
          
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
                         <?php $count= 1; ?>
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
                            <option value="1" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 1)?'selected':'':'' ?>>English</option>  
                            <option value="2" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 2)?'selected':'':'' ?>>Spanish</option>
                            <option value="3" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 3)?'selected':'':'' ?>>French</option>
                            <option value="4" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 4)?'selected':'':'' ?>>Portuguese</option>
                            <option value="5" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 5)?'selected':'':'' ?>>Arabic</option>
                            <option value="6" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 6)?'selected':'':'' ?>>Hindi</option> 
                           <!--  <option value="8" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 8)?'selected':'':'' ?>>No Voice Instructions</option> -->
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
          <label>Test Device allready in use please wait some time.</label>
        </div>
        <div class="modal-body" id="clear-device-yes-msg">
          <label>Do you want to clear device?</label>
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
 
           


 function first(val){
  val++;
          if(val>=20){
            val=1;
          }
  // Simulate a code delay
  setTimeout( function(){
    console.log(1);
     $.ajax({
      url: "https://www.portal.micromedinc.com/apisnew/PointDatadummy/"+val,
      type: 'POST', 
       data: {"office_id": 1}, 
      success: function(data){
        
        }
      });
    first(val);
  }, 100 );
}

  $(window).load(function() {
    //get_deviceDatastop(0); 
    //first(0);
  });
     
</script>
 