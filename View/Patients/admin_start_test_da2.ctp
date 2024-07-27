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
             <h4 class="page-title2" style="color: #ffffff;">Name: <?php echo $data['Patient']['first_name']." ".$data['Patient']['last_name'] ?></h4>
          </div>
     <div class="col-sm-2 mts-box-1">
            <?php if (in_array(14, $checked_data)) { echo "<a style='width: 57px; background: #7e7e7e; color: white; border: 2px solid white;  margin-top: 3px;'   href='".WWW_BASE."admin/patients/start_test/".$data['Patient']['id']."' class='btn' title='Start VF Test' >VF</a>"; }?>
             <?php if (in_array(15, $checked_data)) { echo "<a style='width: 57px; background: #7e7e7e; color: white; border: 2px solid white;  margin-top: 3px;' class='btn'   href='".WWW_BASE."admin/patients/start_test_fdt/".$data['Patient']['id']."' title='Start FDT Test' >FDT</a>"; }?>
              <?php if (in_array(23, $checked_data)) { echo "<a style='width: 57px; background: #3292e0; color: white; border: 2px solid #f3ecec; margin-top: 3px;'   href='javascript:void(0);' class='btn' title='Start DA Test' >DA</a>"; }?>
               </div>
          <div class="col-sm-3 mts-box-1">
             <h4 class="page-title"> <span class="pull-right" style="color:white"></span></h4>
          </div>
      </div>  
    </div> 
  </div>
  <div class="page-content-wrapper ">
   <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-primary" style="margin:0px;">
          <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css"> 
          <!-- 6 may 2020 -->
          <?php echo $this->Html->css(array('admin/mmd-custom'));?>
          <div class="panel-body mmd-st">
            <div class="mmds-box">
              <div class="container">
                <div class="row">
                  <div class="col-sm-6 mts-box-1">
                    <div class="stm-box"> 
                   <!--  <h4><?php echo $data['Patient']['first_name']." ".$data['Patient']['last_name'] ?></h4> -->
                    <h3>Status: <span id="setting-status"></span></h3>
                    <h3>Questions : <span id="setting-questions"></span></h3>  
                    <button id="setting-reliability-btn" class="btn" style="background-color: #00ff00 "  
 ><span id="setting-reliability">Reliability</span></button><br>  
                    <span style="float: right; font-size: 18px;color: #990000;font-weight: 600;" id="setting-alert-message"></span> 
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
                            <option value="1" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 1)?'selected':'':'' ?>>English</option>  
                            <option value="2" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 2)?'selected':'':'' ?>>Spanish</option>
                            <option value="3" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 3)?'selected':'':'' ?>>French</option>
                            <option value="4" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 4)?'selected':'':'' ?>>Portuguese</option>
                            <option value="5" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 5)?'selected':'':'' ?>>Arabic</option>
                            <option value="6" <?php echo (!empty($user_default))?($user_default['UserDefault']['language_id'] == 6)?'selected':'':'' ?>>Hindi</option> 
                         </select> 

                      </div>
                    </div> 
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="stm-nbtn-box"> 
                    <div href="#" class="thrushold">
                      <div class="dropdown">
                        <select class="mmd-dash-btn" id="test-time"  style="height: 30px;">
                          <option value="-1">Select Time</option>
                          <option value="4" <?php echo ($testData['testId']==4)?'selected':'' ?> >5</option>
                          <option value="3" <?php echo ($testData['testId']==3)?'selected':'' ?>>7.5</option>
                          <option value="2" <?php echo ($testData['testId']==2)?'selected':'' ?>>10</option>
                          <option value="1" <?php echo ($testData['testId']==1)?'selected':'' ?>>15</option> 
                        </select>

                      </div>
                    </div>
                   
                  </div>
                </div>
              </div>


              <div class="row dash-statc"> 
                <div class="col-lg-2 col-sm-3 d-desktop">
                   
                      
                  </div>

                  <div class="col-lg-3 col-sm-2 d-phone">
                      <div class="mmt-rt-control">
                      <div class="stm-btn-box" id="test-type-option">

                      </div>
                      </div>

                  </div>
                  <div class="col-lg-7 col-sm-7">
                      <div class="mmt-main-canvas">
                          <div class="mt-btns gz-trck d-desktop">
                              <div class="mt-checkboxes mt-checkboxes-eye"> 
                              </div>
                              <!-- <button class="mmd-dash-btn md-btn-gry stBtn"  data-toggle="modal" data-target="#exampleModalCenter">settings</button> -->
                              <div class="it-1" id="scroll-myinput3">
                          <h4 style="padding-right: 0px; width: 100%;"><center>Volume (<span id="myinput-3-val">0-1</span>)</center></h4><br>
                          <input type="range" id="myinput-3" style="width: 100%; background: linear-gradient(to right, #f5f5f5 0%, #f5f5f5 40%, #d6d6d6 40%, #d6d6d6 100%);" value="40" >
                        </div>
                        
                              <div class="mt-checkboxes" style="margin-top: 5px; ">

                              <div class="form-group"> 
                                  <input type="checkbox" id="both-eye" class="both-eye" aria-label="..." onchange="botheye(this)">
                                  <label for="both-eye"><span type="checkbox" style="font-weight: 500;">Both Eyes (OU)</span></label>
                              </div>
                            </div>
                              <button class="mmd-dash-btn md-btn-gry eye" value="0">Left Eye (OS)</button>
                             <!--  <button class="mmd-dash-btn md-btn-gry eye" value="2">Both Eye (OU)</button> -->
                              <button class="mmd-dash-btn md-btn-yellow eye" value="1" >Right Eye (OD)</button>
                              <button class="mmd-dash-btn md-btn-gry dismiss-alarm" style="visibility: hidden; background-color: #d63636 !important;"   onclick="stopalarm();" >Dismiss Alarm</button>
                          </div>
                          <div style="visibility: hidden;"> 
                          <canvas id="myCanvas2" width="128" height="128" style="border:1px solid #d3d3d3; max-width: 0px;"></canvas> </div> 
                          <canvas   id="myCanvas" height="1020" width="1020" style="background-color: #fff; 
                          "> 
                          </canvas> 

                          <div class="mt-btns gz-trck d-phone">
                              <div class="mt-checkboxes mt-checkboxes-eye">
 
                              </div>
                             <!--  <button class="mmd-dash-btn md-btn-gry"  data-toggle="modal" data-target="#exampleModalCenter">settings</button> -->
                             <div class="it-1" id="scroll-myinput4">
                          <h4 style="padding-right: 0px; width: 100%;"><center>Volume (<span id="myinput-4-val">0-1</span>)</center></h4><br>
                          <input type="range" id="myinput-4" style=" width: 100%; background: linear-gradient(to right, #f5f5f5 0%, #f5f5f5 40%, #d6d6d6 40%, #d6d6d6 100%);" value="40" >
                        </div>
                               <div class="mt-checkboxes" style="margin-top: 5px; ">

                              <div class="form-group">
                                  <input type="checkbox" id="both-eye" class="both-eye" aria-label="..." onchange="botheye(this)">
                                  <label for="both-eye"><span type="checkbox" style="font-weight: 500;">Both Eyes (OU)</span></label>
                              </div>
                            </div>
                              <button class="mmd-dash-btn md-btn-gry eye" value="0">Left Eye (OS)</button>
                             <!--  <button class="mmd-dash-btn md-btn-gry eye" value="2">Both Eye (OU)</button> -->
                              <button class="mmd-dash-btn md-btn-yellow eye" >Right Eye (OD)</button>
                              <button class="mmd-dash-btn md-btn-gry dismiss-alarm" style="visibility: hidden; background-color: #d63636 !important;"   onclick="stopalarm();" value="1" >Dismiss Alarm</button>
                          </div>

                          <div class="mt-btns"> 
                            <a href="" id="view-pdf-url" target="_blank" class="btn btn-info" style="visibility: hidden;">View Report</a>
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
          <input type="date" name="dob" id="dob" class="form-control">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" id="submit-age">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
    DELETE_STM:"1",
    DARK_ADAPTATION:"1",
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
    VR_SET_AWAKE:'', 
    BOTH_EYES:'',  
    DA_COLOR:'',
    DA_TESTTIME:'',  
    SET_SCREEN_BRIGHTNESS:''
  };
  var CONST_POINT_DISPLAY_SIZE = 10;
  var plotIndividualPoints=true;
  var yMajorSize=10;
  var smargin=40;
  var DrawAll=1;
  var displayStep=6;
  var numColors=3;
  var margin=40;
  var MaxMinutes = 10;
  var xNumSections = 4;
  var autoPtosisReport=false;
  var measureReactionTime=true;
  var reportDone_OD=false; 
  var daTestTime;
  var DaTestType;
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
  var uploadTest='';   
  var ptosisReportIndex=0; 
  var fixationLossTotal=0;
  var fixationLossCount=0; 
  var falsePosTotal=0;
  var falsePosCount=0;
  var falseNegTotal=0;
  var falseNegCount=0;   
  var numTestPointsCompleted=0; 
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
  var audioVolume=0.40;
  var agegroup=3;
  var startTestStatus=0; 
  var dataCapturedpause=false;
  var deviceId="";
  var testColour=0;
  var testBackground=0;
  var zoomLevel=1;
  var deviceMessages=[];   

  var plateauDetect_i=[];
  var darkAdaptDetect_i=[];
  var yMajorAxis=[];
  var enabletestnameliest=[]; 

  var xMajorAxis=[];
  numInvalid=[3];
  var dA_ResultIndex=[[0,0,0],[0,0,0],[0,0,0],[0,0,0]];
  var stmColor=[[[0,0,0],[0,0,0],[0,0,0],[0,0,0]],[[0,0,0],[0,0,0],[0,0,0],[0,0,0]],[[0,0,0],[0,0,0],[0,0,0],[0,0,0]],[[0,0,0],[0,0,0],[0,0,0],[0,0,0]]];
  var stmLocX=[[[0,0,0],[0,0,0],[0,0,0],[0,0,0]],[[0,0,0],[0,0,0],[0,0,0],[0,0,0]],[[0,0,0],[0,0,0],[0,0,0],[0,0,0]],[[0,0,0],[0,0,0],[0,0,0],[0,0,0]]]; 
  var stmLocY=[[[0,0,0],[0,0,0],[0,0,0],[0,0,0]],[[0,0,0],[0,0,0],[0,0,0],[0,0,0]],[[0,0,0],[0,0,0],[0,0,0],[0,0,0]],[[0,0,0],[0,0,0],[0,0,0],[0,0,0]]]; 
  var stmDetectTime=[[[0,0,0],[0,0,0],[0,0,0],[0,0,0]],[[0,0,0],[0,0,0],[0,0,0],[0,0,0]],[[0,0,0],[0,0,0],[0,0,0],[0,0,0]],[[0,0,0],[0,0,0],[0,0,0],[0,0,0]]];
  var stmIndex=[[[0,0,0],[0,0,0],[0,0,0],[0,0,0]],[[0,0,0],[0,0,0],[0,0,0],[0,0,0]],[[0,0,0],[0,0,0],[0,0,0],[0,0,0]],[[0,0,0],[0,0,0],[0,0,0],[0,0,0]]];
  var stmValid=[[[0,0,0],[0,0,0],[0,0,0],[0,0,0]],[[0,0,0],[0,0,0],[0,0,0],[0,0,0]],[[0,0,0],[0,0,0],[0,0,0],[0,0,0]],[[0,0,0],[0,0,0],[0,0,0],[0,0,0]]];
  var dA_ValidResultIndex=[[0,0,0,0],[0,0,0,0],[0,0,0,0],[0,0,0,0]]; 
  var stmDecibles=[[[0,0,0],[0,0,0],[0,0,0],[0,0,0]],[[0,0,0],[0,0,0],[0,0,0],[0,0,0]],[[0,0,0],[0,0,0],[0,0,0],[0,0,0]],[[0,0,0],[0,0,0],[0,0,0],[0,0,0]]];
  var darkAdaptDetect=[];
  var plateauDetect=[];
  var flagFDT=0;
  var size=1000; 
  var PTB_j=0;
  var PTB_Intensity=0;
  var PTB_time=0;
  var PTB_eye=0; 
  var topVal=0;
  var bottomVal=0;
  var yNumSections=0;
  var yLowValDisp=0; 
  var myColor='';

  jQuery(document).ready(function(){ 
    <?php if($data['Patient']['dob']==''|| $data['Patient']['dob']==null|| $data['Patient']['dob']=='1970-01-01'){ ?>
       $('#myModal').modal("show"); 


     $('#submit-age').on('click',function(){
      var dob=$("#dob").val(); 
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

    
   $('#myinput-3').on('change',function(){
      audioVolume=(parseInt($("#myinput-3").val())/100).toFixed(2);
     
     $("#myinput-3-val").html(audioVolume);
     $("#myinput-4-val").html(audioVolume);    
   });
   $('#myinput-4').on('change',function(){
     audioVolume=(parseInt($("#myinput-4").val())/100).toFixed(2);
     $("#myinput-4-val").html(audioVolume);
     $("#myinput-3-val").html(audioVolume);  
   });
   
  
   $('#start').on('click',function(){ 
    if(dataCaptured==false){
      testType=$("#test-type").val();
      testSubType=1; 
      deviceId=$("#test-device").val();
      var lang=$("#language").val();
      if ((testType != 0) && (dropdownThresholdType != 0) && (testSubType != 0) && (restartReady) && deviceId!='' && lang!=0){
         if(testType!=2 || enabletestnameliest.includes(testTypeName)){
 
        dataCaptured=true;
        saveresult=false;
        desableAll();
        dataCapturedpause=false;
        startTestStatus=1; 
        numTestPointsCompleted = 0;
        numTestPoints = 0;
        document.getElementById("view-pdf-url").style.visibility = "hidden"; 
        setTestDuration("00:00");
        startTest();
        drawImage(testTypeName); 
        TestData();
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

    }
  });
   $('#stop').on('click',function(){  
    if(dataCaptured==true){ 
      if(stopSavestatus==true){ 
        $('#mystopModal').modal("show"); 
      }else{
          dataCaptured=false;
          dataCapturedpause=false;
          startTestStatus=0;
          saveresult=true;
          stopTest();
          TestData();
          deviceMessages=[];
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
      testSubType=1;
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

    <? } ?>
  
   
       updateDevice();
     restartReady = true;
     dropdownThresholdType = 4;
     get_deviceDatastop();
    drawImage('test');

  });
   
   

   $('.eye').on('click',function(){ 

    if(selectEye!=$(this).val()){
      if((dataCaptured)|| (dataCapturedpause)){
        
       /*var r = confirm("Report has not been saved and the last test results will be lost. Do you want to switch the test type?");
       if (r == true) {
        dataCaptured=false; 
        saveresult=true;
        eye_taped= false; 
        deviceMessages=[];
        $('.eye').addClass("md-btn-gry");
        $('.eye').removeClass("md-btn-yellow");
        $(this).removeClass("md-btn-gry");
        $(this).addClass("md-btn-yellow");
        selectEye=$(this).val(); 
        clertData();
        ptosisReportIndex = 0;
      }*/      
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
 
 const starttestdevice = async () => {
   drawImageb(testTypeName,'#ff0000');
   await sleep(300);
  drawImageb(testTypeName,'#000000');   
 }

 const sleep = (milliseconds) => {
  return new Promise(resolve => setTimeout(resolve, milliseconds))
}
const get_deviceDatastop = async function(){  
    if((!dataCaptured) && (!restartdata) && (saveresult)){
          deviceId=$("#test-device").val();
          var feedback = $.ajax({
          type: "POST",
          url: "<?php echo WWW_BASE; ?>/apisnew/get_process_device_message_stop",
           data: {"office_id": '<?php echo $user['office_id'] ?>', "device_id": deviceId },
          async: false
      }).success(function(){ 
          setTimeout(function(){get_deviceDatastop();}, 400);
      }).responseText; 
     obj = JSON.parse(feedback);
      for (var key in obj.message) {
        if(obj.message!='No data'){
           if(!deviceMessages.includes(obj.message[key]['DeviceMessage']['message']+"|"+obj.message[key]['DeviceMessage']['id'])){
               deviceMessages.push(obj.message[key]['DeviceMessage']['message']+"|"+obj.message[key]['DeviceMessage']['id']);
                 var  res= obj.message[key]['DeviceMessage']['message'].split("|"); 
              if(res[1]=='BTN_PRESS'){
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
        url: "<?php echo WWW_BASE; ?>/apisnew/get_process_device_message",
         data: {"office_id": '<?php echo $user['office_id'] ?>', "device_id": deviceId },
        // data: {"office_id": 39, "device_id": 39 },
        async: false
    }).success(function(){
        setTimeout(function(){get_deviceData();}, 400);
    }).responseText; 
   obj = JSON.parse(feedback);
   for (var key in obj.message) {
 
console.log(obj.message);
if(obj.message!='No data'){ 
   if(!deviceMessages.includes(obj.message[key]['DeviceMessage']['message']+"|"+obj.message[key]['DeviceMessage']['id'])){
       deviceMessages.push(obj.message[key]['DeviceMessage']['message']+"|"+obj.message[key]['DeviceMessage']['id']);
         var  res= obj.message[key]['DeviceMessage']['message'].split("|");
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
       document.getElementById("battery").style.visibility = "visible"; 
    }else if(res[1]=='BTN_PRESS'){
      starttestdevice();
    }else if(res[0]=='DA' && res[1]=='DA_TEST_COMPLETED'){

       setpixcelVF_TEST_COMPLETED(obj.message[key]['DeviceMessage']['message'],obj.message[key]['DeviceMessage']['id'],obj.message[key]['DeviceMessage']['office_id'],obj.message[key]['DeviceMessage']['device_id']);
    }else{
      var  res2= obj.message[key]['DeviceMessage']['message'].split("VF|");
  
       
       const fillTPS2 = async function(item) {
          item_new="DA|"+item;
         res3=item.split("|"); 
     console.log(res3); 
     if(res3[1]=='DA_PTB'){
       const promises = setpixcelDA_PTB(item_new);
        await Promise.all(promises);
    }else if(res3[1]=='DA_RESULT'){
      const promises = setpixcelDA_RESULT(item_new);
        await Promise.all(promises);
    }else if(res3[1]=='START_BUTTON_PRESSED'){
        setpixcelVF_START_BUTTON_PRESSED(item_new);
        
    }else if(res3[1]=='VF_FILE_UPLOADED'){
        setpixcelVF_FILE_UPLOADED(item_new);
        
    }
 else if(res3[0]=='TEST_STATUS'){
      const promises =  setpixcelVF_TEST_STATUS(item_new);
        await Promise.all(promises);  
    }
    else if(res3[0]=='USER_PAUSE'){
      const promises =  setpixcelVF_USER_PAUSE(item_new);
        await Promise.all(promises);  
    }
    else if(res3[0]=='USER_RESUME'){
      const promises =  setpixcelVF_USER_RESUME(item_new);
        await Promise.all(promises);  
    }
    else if(res3[0]=='VF_RESEND_DATA_COMPLETED'){
      const promises =  setpixcelVF_RESEND_DATA_COMPLETED(item_new);
        await Promise.all(promises);  
    }  
     }
     const promises = res2.map(fillTPS2);
       await Promise.all(promises);
   }

   }else{
    console.log('else');
   }
   }
   }
  }
}
function drawImageb(argument,col1) {
   
}
function setpixcelVF_RESEND_DATA_COMPLETED(data) {
  var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d");
  var  splitData= data.split("|");
  setTestStatus('Completed');
  setTestQuestions(splitData[2]);
  }
function setEYE(argument) {
    if(argument=="OD"){
      $('.eye').addClass("md-btn-gry");
      $('.eye').removeClass("md-btn-yellow");
      $(".eye").each((key, element) => {
          let value = $(element).val(); 
          if(value==1){
            $(element).removeClass("md-btn-gry");  
             $(element).addClass("md-btn-yellow");
          } 
        });
      selectEye=1;
      eye_taped= false;
      ptosisReportIndex = 0; 
    }else{
      $('.eye').addClass("md-btn-gry");
      $('.eye').removeClass("md-btn-yellow");
      $(".eye").each((key, element) => {
          let value = $(element).val(); 
          if(value==0){
            $(element).removeClass("md-btn-gry");  
             $(element).addClass("md-btn-yellow");
          } 
        });
      selectEye=0;
      eye_taped= false;
      ptosisReportIndex = 0; 
    }
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
    setting_alert('Test Completed',2000);
    
     elapsedTime = CalculateElapsedTime(); 
    //     initPDFData(); 
    //     SelectStop2();
}
 
  const setpixcelDA_PTB = async function(data) {
    
   var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d"); 
    var  splitData= data.split("|");
   // splitData[4]=parseFloat(splitData[4])*-1;
   PTB_j=parseInt(splitData[2]);
   PTB_Intensity = parseFloat(splitData[3]);
   PTB_time = parseFloat(splitData[4]) / 60;
   PTB_eye = parseInt(splitData[5]); 
   if(PTB_eye != selectEye)
    {
        selectEye = PTB_eye;
        //setxMajorAxis();
        drawImage('test');
        initDaDrawing('DA');
        if (selectEye == 1)
        {
             setEYE("OD");
        }
        else
        {
             setEYE("OS");
        }
    }
    var x = PTB_time;
    var y = PTB_Intensity;
    var xMin = x;
    var yIntensity = yLowValDisp - y;
    
    var x1=0;
    var y1=0;
    var xtemp = (xMajorAxis[xNumSections] - xMajorAxis[0]) * xMin / MaxMinutes;

    x1 = xMajorAxis[0] + Math.round(xtemp);
    y1 = yMajorAxis[0] + Math.round(yIntensity * yMajorSize / displayStep);
    topVal = 0;
    bottomVal = 40;
    yNumSections = 8;
    yLowValDisp =parseInt(bottomVal);
    PlotDAPoints();

    switch (PTB_j)
    {
        case 0:
            myColor = "#ff0000";
            break;
        case 1:
            myColor = "#00ff00";
            break;
        case 2:
            myColor = "#0000ff";
            break;
        default:
            myColor = "#ff0000";
            break;
    }
     DrawPoints(x1, y1, myColor, PTB_eye, PTB_j);
     ClearSquare(x1, y1, PTB_eye, PTB_j);
 }

  const setpixcelDA_RESULT = async function(data) {
   var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d"); 
    var  splitData= data.split("|");
    Intensity = Math.round(parseFloat(splitData[5]));
 /*   j = parseInt(splitData[4]);    //color
    eye = parseInt(splitData[8]);*/
     j = parseInt(splitData[5]);    //color
    eye = parseInt(splitData[9]);
    console.log(j);
    console.log(eye);
    console.log('test value');
    
    console.log(stmLocX);
    stmLocX[eye][j][dA_ResultIndex[eye][j]] = parseFloat(splitData[3]);
    stmLocY[eye][j][dA_ResultIndex[eye][j]] = parseFloat(splitData[4]);
    stmDecibles[eye][j][dA_ResultIndex[eye][j]] = parseFloat(splitData[6]);
    stmDetectTime[eye][j][dA_ResultIndex[eye][j]] = parseFloat(splitData[7]) / 60;
    stmIndex[eye][j][dA_ResultIndex[eye][j]] = parseInt(splitData[8]);

    /* stmLocX[eye][j][dA_ResultIndex[eye][j]] = parseFloat(splitData[2]);
    stmLocY[eye][j][dA_ResultIndex[eye][j]] = parseFloat(splitData[3]);
    stmDecibles[eye][j][dA_ResultIndex[eye][j]] = parseFloat(splitData[5]);
    stmDetectTime[eye][j][dA_ResultIndex[eye][j]] = parseFloat(splitData[6]) / 60;
    stmIndex[eye][j][dA_ResultIndex[eye][j]] = parseInt(splitData[7]);*/
    
    switch (j)
    {
        case 0:
            stmColor[eye][j][dA_ResultIndex[eye][j]] = "#ff00ff";
            break;
        case 1:
             stmColor[eye][j][dA_ResultIndex[eye][j]] = "#00ff00";
            break;
        case 2:
             stmColor[eye][j][dA_ResultIndex[eye][j]] = "#0000ff";
            break;
        default:
             stmColor[eye][j][dA_ResultIndex[eye][j]] = "#000000";
            break;
    }
    if (dA_ResultIndex[eye][j] > 0)
    {
        stmValid[eye][j][dA_ResultIndex[eye][j]] = true;
    }
    else{
        stmValid[eye][j][dA_ResultIndex[eye][j]] = true;
    }

    dA_ResultIndex[eye][j]++;
    dA_ValidResultIndex[eye][j] = dA_ResultIndex[eye][j] - numInvalid[j];

    topVal = 0;
    bottomVal = 40;
    yNumSections = 8; 
    yLowValDisp = parseInt(bottomVal);
    if (plotIndividualPoints==true)
    {
        initDaDrawing('test');
        PlotDAPoints();
    }
 
 }

const PlotDAPoints  =  async () => {
  var x;
  var y;
  for (e = 0; e < 2; e++)
        {
            if ((e == selectEye) || (selectEye == 3))
            {
                for ( j = 0; j < numColors; j++)
                {
                    for ( i = 0; i < dA_ResultIndex[e][j]; i++)
                    {
                        x = stmDetectTime[e][j][i];
                        y = stmDecibles[e][j][i];
                        xMin = x;
                        yIntensity = yLowValDisp - y;
                        var x1;
                        var y1; 
                        xtemp = (xMajorAxis[xNumSections] - xMajorAxis[0]) * xMin / MaxMinutes;

                        x1 = xMajorAxis[0] + Math.round(xtemp);
                        y1 = yMajorAxis[0] + Math.round(yIntensity * yMajorSize / displayStep);

                        myColor;
                        switch (j)
                        {
                            case 0:
                                myColor = "#ff0000";
                                break;
                            case 1:
                                myColor = "#00ff00";
                                break;
                            case 2:
                                myColor = "#0000ff";
                                break;
                            default:
                                myColor = "#ff0000";
                                break;
                        }
                        if (selectEye != 3)
                            DrawPoints(x1, y1, myColor, e, j);

                        if(e == 1)
                            DrawPoints(x1, y1, myColor, e, j);
                        else 
                            DrawPoints(x1, y1, myColor, e, j);
                    }
                }
            }
        }
}
  const ClearSquare = async function(x1, y1, eye, selCol) {
    {
       await sleep(250); 
       DrawPoints(x1, y1, "#ffffff", eye, selCol);
    }
  }
 const DrawPoints = async function(x, y, col, Eye, selCol) {
   var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d"); 
    if (Eye == 1)
        {   //OD
            if(selCol == 0)        //Filled Square
            {
                for (x2 = x - CONST_POINT_DISPLAY_SIZE; x2 < x + CONST_POINT_DISPLAY_SIZE; x2++)
                {
                    for (y2 = y - CONST_POINT_DISPLAY_SIZE; y2 < y + CONST_POINT_DISPLAY_SIZE; y2++)
                    {
                      ctx.beginPath();
                      ctx.fillStyle = col;
                      ctx.fillRect(x2,y2,3,3); 
                      ctx.stroke();  
                    }
                }
            }
            else if(selCol == 1) //Filled Circle
            {
                for (i = 1; i <CONST_POINT_DISPLAY_SIZE; i++)
                { 
                    ctx.beginPath();
                    ctx.strokeStyle = col;
                    ctx.arc(Math.round(x), Math.round(y), i, 0, 2 * Math.PI);
                    ctx.stroke();
                }
            }
            else if (selCol == 2) //Filled Diamond
            {
                for (i = 1; i < CONST_POINT_DISPLAY_SIZE * 1.5; i++)
                {
                     
                    ctx.beginPath();
                    ctx.lineWidth = 3;
                    ctx.strokeStyle = col;
                    ctx.moveTo(x, y + i);
                    ctx.lineTo(x + i, y); 
                    ctx.stroke(); 
                     
                    ctx.beginPath();
                    ctx.lineWidth = 3;
                    ctx.strokeStyle = col;
                    ctx.moveTo(x + i, y);
                    ctx.lineTo(x, y - i); 
                    ctx.stroke(); 
                    
                    ctx.beginPath();
                    ctx.lineWidth = 3;
                    ctx.strokeStyle = col;
                    ctx.moveTo(x, y - i);
                    ctx.lineTo(x - i, y); 
                    ctx.stroke();
 
                    ctx.beginPath();
                    ctx.lineWidth = 3;
                    ctx.strokeStyle = col;
                    ctx.moveTo(x - i, y);
                    ctx.lineTo(x, y + i); 
                    ctx.stroke();

                }
            }
        }else{
             if (selCol == 0)        //Filled Square
            {
                for (x2 = x - CONST_POINT_DISPLAY_SIZE; x2 < x + CONST_POINT_DISPLAY_SIZE; x2++)
                {
                    for (y2 = y - CONST_POINT_DISPLAY_SIZE; y2 < y + CONST_POINT_DISPLAY_SIZE; y2++)
                    {
                      ctx.beginPath();
                      ctx.fillStyle = col;
                      ctx.fillRect(x2,y2,3,3); 
                      ctx.stroke(); 
                    }
                }
            }
            else if (selCol == 1) //Filled Circle
            {
                for (i = 1; i < CONST_POINT_DISPLAY_SIZE; i++)
                {
                    ctx.beginPath();
                    ctx.strokeStyle = col;
                    ctx.arc(Math.round(x), Math.round(y), i, 0, 2 * Math.PI);
                    ctx.stroke();
                }
            }
            else if (selCol == 2) //Filled Diamond
            {
                for (i = 1; i < CONST_POINT_DISPLAY_SIZE * 1.5; i++)
                { 
                    ctx.beginPath();
                    ctx.lineWidth = 3;
                    ctx.strokeStyle = col;
                    ctx.moveTo(x, y + i);
                    ctx.lineTo(x + i, y); 
                    ctx.stroke(); 

                    ctx.beginPath();
                    ctx.lineWidth = 3;
                    ctx.strokeStyle = col;
                    ctx.moveTo(x + i, y);
                    ctx.lineTo(x, y - i); 
                    ctx.stroke();

                    ctx.beginPath();
                    ctx.lineWidth = 3;
                    ctx.strokeStyle = col;
                    ctx.moveTo(x, y - i);
                    ctx.lineTo(x - i, y); 
                    ctx.stroke();

                    ctx.beginPath();
                    ctx.lineWidth = 3;
                    ctx.strokeStyle = col;
                    ctx.moveTo(x - i, y);
                    ctx.lineTo(x, y + i); 
                    ctx.stroke();

                }
            }
        }
  }
 const initDaDrawing = async function(data) {
   var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d"); 
  var maxTime = 0;
  var x1;
  var startTime = '';
  for (e = 0; e < 2; e++)
  {
    darkAdaptDetect_i=[];
    plateauDetect_i=[];
      for (jj = 0; jj < numColors; jj++)
      {
        darkAdaptDetect_i.push(false);
        plateauDetect_i.push(false);
         /* darkAdaptDetect[e][jj] = false;
          plateauDetect[e][jj] = false;*/
      }
       darkAdaptDetect.push(darkAdaptDetect_i);
        plateauDetect.push(plateauDetect_i);
  }
  for (e = 0; e < 2; e++)
  {
      for (j = 0; j < numColors; j++)
      {
          if(dA_ResultIndex.length>0){
          for (i = 0; i < dA_ResultIndex[e][j]; i++)
          {
              x1 = stmDetectTime[e][j][i];
              if (x1 > maxTime)
                  maxTime = x1;
          }
        }
      }
  }
  if(maxTime <= 10)
  {
      MaxMinutes = 10;
      xNumSections = 4;
  }
  else
  {
      MaxMinutes = 15;
      xNumSections = 6;
  }

  d = size - (4 * margin);
  xMajorSize = d / xNumSections;
  msg = "";
  for ( i = 0; i <= xNumSections; i++)
  {
      x = (2 * margin) + (i * xMajorSize);
      xMajorAxis[i] = x; 
     /* ctx.beginPath();
     ctx.strokeStyle = '#000000';
     ctx.moveTo(Math.round(x), Math.round(margin));
     ctx.lineTo(Math.round(x), Math.round(smargin + 35));
     ctx.stroke(); 
      if (DrawAll)
      {
         ctx.beginPath();
     ctx.strokeStyle = '#000000';
     ctx.moveTo(Math.round(x), Math.round(margin));
     ctx.lineTo(Math.round(x), Math.round(smargin + 35));
     ctx.stroke();
     ctx.beginPath();
     ctx.strokeStyle = '#000000';
     ctx.moveTo(Math.round(x), Math.round(margin));
     ctx.lineTo(Math.round(x), Math.round(smargin + 35));
     ctx.stroke();
      }*/
      switch (i)
      {
          case 0:
              msg = "0";
              break;
          case 1:
              msg = "";
              break;
          case 2:
              msg = "5";
              break;
          case 3:
              msg = "";
              break;
          case 4:
              msg = "10";
              break;
          case 5:
              msg = "";
              break;
          case 6:
              msg = "15";
              break;
          case 7:
              msg = "";
              break;
          case 8:
              msg = "20";
              break;
      } 
     /* label = msg;
     ctx.beginPath();
     ctx.fillStyle = '#000000'; 
     ctx.font = "33px Arial";  
     ctx.fillText(label,Math.round(x - 20),Math.round(margin - 70)); 
     ctx.stroke(); 
      if (DrawAll)
      {
          label = msg;
     ctx.beginPath();
     ctx.fillStyle = '#000000'; 
     ctx.font = "33px Arial";  
     ctx.fillText(label,Math.round(x - 20),Math.round(margin - 70)); 
     ctx.stroke(); 
     label = msg;
     ctx.beginPath();
     ctx.fillStyle = '#000000'; 
     ctx.font = "33px Arial";  
     ctx.fillText(label,Math.round(x - 20),Math.round(margin - 70)); 
     ctx.stroke(); 
      }*/
  }
  msg = "M IN";
  /*label = msg;
     ctx.beginPath();
     ctx.fillStyle = '#000000'; 
     ctx.font = "33px Arial";  
     ctx.fillText(label,Math.round(xMajorAxis[xNumSections] + margin - 35),Math.round(margin - 70)); 
     ctx.stroke(); 
  if (DrawAll)
  {
      label = msg;
     ctx.beginPath();
     ctx.fillStyle = '#000000'; 
     ctx.font = "33px Arial";  
     ctx.fillText(label,Math.round(xMajorAxis[xNumSections] + margin - 35),Math.round(margin - 70)); 
     ctx.stroke(); 
     label = msg;
     ctx.beginPath();
     ctx.fillStyle = '#000000'; 
     ctx.font = "33px Arial";  
     ctx.fillText(label,Math.round(xMajorAxis[xNumSections] + margin - 35),Math.round(margin - 70)); 
     ctx.stroke(); 
  }*/

  for ( i = 0; i <= yNumSections; i++)
        {
            y = (2 * margin) + (i * yMajorSize);
            yMajorAxis[i] = y; 
           /* ctx.beginPath();
           ctx.strokeStyle = '#000000';
           ctx.moveTo(Math.round(margin), Math.round(y));
           ctx.lineTo(Math.round(size- margin), Math.round(y));
           ctx.stroke();
            if (DrawAll)
            {
                 ctx.beginPath();
           ctx.strokeStyle = '#000000';
           ctx.moveTo(Math.round(margin), Math.round(y));
           ctx.lineTo(Math.round(size- margin), Math.round(y));
           ctx.stroke();
            ctx.beginPath();
           ctx.strokeStyle = '#000000';
           ctx.moveTo(Math.round(margin), Math.round(y));
           ctx.lineTo(Math.round(size- margin), Math.round(y));
           ctx.stroke();
            }*/
 
             
            /*label = msg;
             ctx.beginPath();
             ctx.fillStyle = '#000000'; 
             ctx.font = "33px Arial";  
             ctx.fillText(label,Math.round(margin - 35),Math.round(y - 40)); 
             ctx.stroke();
            if (DrawAll)
            {
                label = msg;
             ctx.beginPath();
             ctx.fillStyle = '#000000'; 
             ctx.font = "33px Arial";  
             ctx.fillText(label,Math.round(margin - 35),Math.round(y - 40)); 
             ctx.stroke();

             label = msg;
             ctx.beginPath();
             ctx.fillStyle = '#000000'; 
             ctx.font = "33px Arial";  
             ctx.fillText(label,Math.round(margin - 35),Math.round(y - 40)); 
             ctx.stroke();
            }*/
        }
        msg = "D B";
       /* label = msg;
       ctx.beginPath();
       ctx.fillStyle = '#000000'; 
       ctx.font = "33px Arial";  
       ctx.fillText(label,Math.round(margin - 45),Math.round(yMajorAxis[yNumSections] + margin - 15)); 
       ctx.stroke();
        if (DrawAll)
        {
            label = msg;
       ctx.beginPath();
       ctx.fillStyle = '#000000'; 
       ctx.font = "33px Arial";  
       ctx.fillText(label,Math.round(margin - 45),Math.round(yMajorAxis[yNumSections] + margin - 15)); 
       ctx.stroke();

       label = msg;
       ctx.beginPath();
       ctx.fillStyle = '#000000'; 
       ctx.font = "33px Arial";  
       ctx.fillText(label,Math.round(margin - 45),Math.round(yMajorAxis[yNumSections] + margin - 15)); 
       ctx.stroke();
        }*/
 
 }
function setpixcelVF_START_BUTTON_PRESSED(data) { 
  
  stopSavestatus=true;
  completedDeviveStatus=false;
  startTime= new Date().getTime();
  dtStartTime= new Date().getTime();
  testduration();
}
function setpixcelVF_FILE_UPLOADED(data) {
   var  splitData= data.split("|");
    document.getElementById("view-pdf-url").style.visibility = "visible";
    //document.getElementById("view-pdf-url").href="<?php echo WWW_BASE; ?>/pointData/"+splitData[2]; 
    document.getElementById("view-pdf-url").href="<?php echo WWW_BASE; ?>admin/patients/view_pdf_report/"+splitData[2];
    saveresult=true; 
    dataCaptured==false;
    stopSavestatus=false;
    round1=0;
     $.ajax({
      url: "<?php echo WWW_BASE; ?>admin/patients/clear_alldata/3554",
      type: 'POST', 
       data: {"patient_id": '<?php echo $data['Patient']['id'] ?>',"office_id": '<?php echo $user['office_id'] ?>',"deviceId": $("#test-device").val()}, 
      success: function(data){ 
         get_deviceDatastop();
        }
      });
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
            break;
          case "TRAINING":
            TestStatus = "Training " + splitData[3];
            setTestStatus(TestStatus);
            break;
          default:
            break;    
        }
}
function setpixcelVF_USER_PAUSE(data) {
   TestStatus= "Paused";
   setTestStatus(TestStatus);
}
function setpixcelVF_USER_RESUME(data) {
   TestStatus= "In Progress";
   setTestStatus(TestStatus);
}

 function checkIndex(name,a=null,b=null,c=null,d=null) {
    if(d!=null){
        if (typeof name[a][b][c][d] !== 'undefined') {
           return true;
        }else{
          return false;
        }
    }else if(c!=null){
        if (typeof name[a][b][c] !== 'undefined') {
           return true;
        }else{
          return false;
        }
    }else if(b!=null){
        if (typeof name[a][b] !== 'undefined') {
           return true;
        }else{
          return false;
        }
    }else if(a!=null){
        if (typeof name[a] !== 'undefined') {
           return true;
        }else{
          return false;
        }
    }else{
      return false;
    }  
 }

 function checkIndexassign(name,a=null,b=null,c=null,d=null) {
  var new_arr=[];
    if(d!=null){
        if (typeof name[a][b][c][d] !== 'undefined') {
           return true;
        }else{
          checkIndexassign(name,a,b,c);
          checkIndexassign[a][b][c].push(new_arr);
          return true;
        }
    }else if(c!=null){
       console.log(name);
        if (typeof name[a][b][c] !== 'undefined') {
           return true;
        }else{
          checkIndexassign(name,a,b);
          checkIndexassign[a][b].push(new_arr);
          return true;
        }
    }else if(b!=null){
        if (typeof name[a][b] !== 'undefined') {
           return true;
        }else{
          checkIndexassign(name,a);
          checkIndexassign[a].push(new_arr);
          return true;
        }
    }else if(a!=null){
        if (typeof name[a] !== 'undefined') {
           return true;
        }else{ 
          checkIndexassign[a].push(new_arr);
          return true;
        }
    }else{
      return false;
    }  
 }
 function checkIndexassign2(name,a=null,b=null,c=null,d=null) {
  var new_arr=[];

  if(a!=null){
        if (typeof name[a] !== 'undefined') { 
        }else{ 
          for(q=0;q<a;q++){
            name.push(new_arr);
            }
           
        }
        if(b!=null){
          if (typeof name[a][b] !== 'undefined') { 
          }else{
            for(q=0;q<b;q++){
            name[a].push(new_arr); 
            }
          }
          if(c!=null){
            console.log(name);
            if (typeof name[a][b][c] !== 'undefined') { 
            }else{
              for(q=0;q<c;q++){
            name[a][b].push(new_arr);
              } 
            }
            if(d!=null){
                if (typeof name[a][b][c][d] !== 'undefined') { 
                }else{ 
                  name[a][b][c].push(new_arr); 
                }
             }
          }
        }
    } 
      return true;
    
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
     //await Promise.all(promises);
  var t1=0;
  var r=0,r1=0,r2=0,r3=0,r4=0,r5=0,r6=0,r7=0,r8=0;
  testTypeName=value; 
  zoomLevel=1.5;
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
  
    if ((zoomLevel > 1.0) && (zoomLevel <= 4.0))
    {
      
     label = "D B"; 
     ctx.beginPath();
     ctx.fillStyle = '#000000';
     ctx.font = "33px Arial"; 
     ctx.fillText(label,10,30);
     ctx.stroke();
     
     ctx.beginPath();
     ctx.strokeStyle = "#000000";
     ctx.rect(40, 40, size-80, size-80);
     ctx.stroke();

     ctx.beginPath();
     ctx.strokeStyle = "#000000";
     ctx.moveTo(40, 80);
     ctx.lineTo((size-40), 80);
     ctx.stroke();

     label = "0"; 
     ctx.beginPath();
     ctx.fillStyle = '#000000';
     ctx.font = "33px Arial"; 
     ctx.fillText(label,10,90);
     ctx.stroke();
     
     ctx.beginPath();
     ctx.strokeStyle = "#000000";
     ctx.moveTo(40, 180);
     ctx.lineTo((size-40), 180);
     ctx.stroke();

     label = "5"; 
     ctx.beginPath();
     ctx.fillStyle = '#000000';
     ctx.font = "33px Arial"; 
     ctx.fillText(label,10,190);
     ctx.stroke();
     
     ctx.beginPath();
     ctx.strokeStyle = "#000000";
     ctx.moveTo(40, 280);
     ctx.lineTo((size-40), 280);
     ctx.stroke();

     label = "10"; 
     ctx.beginPath();
     ctx.fillStyle = '#000000';
     ctx.font = "33px Arial"; 
     ctx.fillText(label,1,290);
     ctx.stroke();
     
      ctx.beginPath();
     ctx.strokeStyle = "#000000";
     ctx.moveTo(40, 380);
     ctx.lineTo((size-40), 380);
     ctx.stroke();

     label = "15"; 
     ctx.beginPath();
     ctx.fillStyle = '#000000';
     ctx.font = "33px Arial"; 
     ctx.fillText(label,1,390);
     ctx.stroke();
     
      ctx.beginPath();
     ctx.strokeStyle = "#000000";
     ctx.moveTo(40, 480);
     ctx.lineTo((size-40), 480);
     ctx.stroke();

     label = "20"; 
     ctx.beginPath();
     ctx.fillStyle = '#000000';
     ctx.font = "33px Arial"; 
     ctx.fillText(label,1,490);
     ctx.stroke();
      ctx.beginPath();
     ctx.strokeStyle = "#000000";
     ctx.moveTo(40, 580);
     ctx.lineTo((size-40), 580);
     ctx.stroke();

     label = "25"; 
     ctx.beginPath();
     ctx.fillStyle = '#000000';
     ctx.font = "33px Arial"; 
     ctx.fillText(label,1,590);
     ctx.stroke();
      ctx.beginPath();
     ctx.strokeStyle = "#000000";
     ctx.moveTo(40, 680);
     ctx.lineTo((size-40), 680);
     ctx.stroke();

     label = "30"; 
     ctx.beginPath();
     ctx.fillStyle = '#000000';
     ctx.font = "33px Arial"; 
     ctx.fillText(label,1,690);
     ctx.stroke();
      ctx.beginPath();
     ctx.strokeStyle = "#000000";
     ctx.moveTo(40, 780);
     ctx.lineTo((size-40), 780);
     ctx.stroke();

     label = "35"; 
     ctx.beginPath();
     ctx.fillStyle = '#000000';
     ctx.font = "33px Arial"; 
     ctx.fillText(label,1,790);
     ctx.stroke();
     
     ctx.beginPath();
     ctx.strokeStyle = "#000000";
     ctx.moveTo(40, 880);
     ctx.lineTo((size-40), 880);
     ctx.stroke();

     label = "40"; 
     ctx.beginPath();
     ctx.fillStyle = '#000000';
     ctx.font = "33px Arial"; 
     ctx.fillText(label,1,890);
     ctx.stroke();
     
     ctx.beginPath();
     ctx.strokeStyle = "#000000";
     ctx.moveTo(100, 910);
     ctx.lineTo(100, 960);
     ctx.stroke();
     
     label = "0"; 
     ctx.beginPath();
     ctx.fillStyle = '#000000';
     ctx.font = "33px Arial"; 
     ctx.fillText(label,80,990);
     ctx.stroke();
     
     ctx.beginPath();
     ctx.strokeStyle = "#000000";
     ctx.moveTo(300, 910);
     ctx.lineTo(300, 960);
     ctx.stroke();
     
     ctx.beginPath();
     ctx.strokeStyle = "#000000";
     ctx.moveTo(500, 910);
     ctx.lineTo(500, 960);
     ctx.stroke();
     
     label = "5"; 
     ctx.beginPath();
     ctx.fillStyle = '#000000';
     ctx.font = "33px Arial"; 
     ctx.fillText(label,480,990);
     ctx.stroke();
     
     ctx.beginPath();
     ctx.strokeStyle = "#000000";
     ctx.moveTo(700, 910);
     ctx.lineTo(700, 960);
     ctx.stroke();
     
     ctx.beginPath();
     ctx.strokeStyle = "#000000";
     ctx.moveTo(900, 910);
     ctx.lineTo(900, 960);
     ctx.stroke();
     
     label = "10 MIN"; 
     ctx.beginPath();
     ctx.fillStyle = '#000000';
     ctx.font = "33px Arial"; 
     ctx.fillText(label,880,990);
     ctx.stroke();
      
    }
 
  ctx.stroke(); 
}
 
function clertData(argument) {
 stopTestforce();
 dataCaptured=false;



      // msgResend = true;        
      // data.vfpointdata.Clear();
        numTestPointsCompleted = 0;
        numTestPoints = 0;
      // falsePosCount = 0;
      // falsePosTotal = 0;
      // falseNegCount = 0;
      // falseNegTotal = 0;
      // fixationLossTotal = 0;
      // fixationLossCount = 0;   
      // dataCaptured = false;
      // reportGenEnabled = false;  

      // initPDFDrawing();

      // uploadTest.enabled = false;
      // var colors = uploadTest.GetComponent<Button>().colors;   
      // uploadTest.GetComponent<Button>().colors = colors;
    }
    function removeEyeTaped() {
     $("#eye-taped-parent").remove();
     EyeTapedStatus=0;
   }
   function addEyeTaped(value=false) { 
    console.log(EyeTapedStatus);
    if(EyeTapedStatus==0){
    EyeTapedStatus=1;
    var state=''
    if(value){
      state='checked';
    } 
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
 
  function startTest() {  
    
    /*$("#recall_last_data").addClass("md-btn-desabley");
    $("#recall_last_data").removeClass("md-btn-gry");*/
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
   /* $("#recall_last_data").removeClass("md-btn-desabley");
    $("#recall_last_data").addClass("md-btn-gry");*/
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
      if(alarm_stop){
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
     get_deviceDatastop();
  }
  function stopTestforce() {
   /* $("#recall_last_data").removeClass("md-btn-desabley");
    $("#recall_last_data").addClass("md-btn-gry");;*/
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
     get_deviceDatastop();
  }
   function pauseTest() {
   // $("#recall_last_data").addClass("md-btn-desabley");
    $("#pause").removeClass("md-btn-gry");
    $("#resume").removeClass("md-btn-desabley");
    $("#pause").removeClass("md-btn-desabley");
    $("#stop").addClass("md-btn-desabley");
    $("#start").addClass("md-btn-desabley"); 
    $("#resume").addClass("md-btn-gry");
  }
   function resumeTest() {
  //  $("#recall_last_data").addClass("md-btn-desabley");
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
  function stop_save_yes(argument) {

    startTestStatus=6;
    round1=0;
    saveresult=false;
    dataCaptured=true;
    dataCaptured=false;
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
  }
  //function testduration() {
  const testduration = async () => {
    for(;;){
        if(startTestStatus==0){
            break;
        }else{ 
            diffTime = Math.abs(new Date().getTime() - dtStartTime);
            minutes = parseInt(diffTime / (1000 * 60 ));
            seconds = Math.ceil((diffTime / (1000 ))-(minutes*60));
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
 
}
 
 
  function desableAll() {
     var elems = document.getElementsByClassName("both-eye");
     for(var i = 0; i < elems.length; i++) {
        elems[i].disabled = true;
     }
      
  }
  function enableAll() {
     var elems = document.getElementsByClassName("both-eye");
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
      seconds = Math.ceil((diffTime / (1000 ))-(minutes*60)); 
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
    var ageDifMs = Date.now() - Date.parse(dob);
    var ageDate = new Date(ageDifMs); // miliseconds from epoch
    var age=Math.abs(ageDate.getUTCFullYear() - 1970);
    if ((age > 0) && (age <= 25)){
       agegroup = 1;
     }else if ((age > 25) && (age <= 45)){
        agegroup = 2;
     }else if ((age > 45) && (age <= 65)){
        agegroup = 3;
     }else{
        agegroup = 4;
     } 
  }
 
  
  function botheye(obj) {

  if($(obj).is(":checked")){
   testBothEyes=true;
   selectEye=2; 
  }else{
    testBothEyes=false;  
  } 
}
function TestWithEyeTaped() { 
        if (dialogResult == 1)
        {
            ptosisReportIndex = 1; 
        }
        
} 
 
function setTestStatus(argument) {
   $("#setting-status").html(argument);
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


  function testStatus(value) {
    switch(value){
          case 0:
          // $("#msg-mopdel-span").html('Test Stoped');
          // $('#mymsgModal').modal("show"); 
          break;
          case 1:
         /* $("#msg-mopdel-span").html("Don't close the page while the testing in progress, stop the test before leaveing the page.");
          $('#mymsgModal').modal("show"); */
          setting_alert("Make sure the Headset is On and the VF2000 app is running. Press the clicker button a few times to wake up the device and start the test. Do not exit this page while the test is in progress.",5000);
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
     
     $("#connection-verify").addClass('md-btn-yellow');
   $("#connection-verify").html(msg); 
  $("#connection-verify").attr("style", "color:#ffffff; visibility:visible");
  await sleep(2000);
  $("#connection-verify").attr("style", "color:#ffffff; visibility:hidden");
  }
     const setting_alert = async function(msg,time) { 
     $("#setting-alert-message").html(msg);
      await sleep(time);
      $("#setting-alert-message").html("");
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
    } else {
     document.getElementById("test-device").value = deviceId;
  }
}else{
  updateDevice();
}
  }
  function updateSpeed(min=0.2,max=1.2,value=0.6) {
    document.getElementById("myinput-3").style.background = 'linear-gradient(to right, #f5f5f5 0%, #f5f5f5 ' + (value-min)*(100/(max-min)) + '%, #d6d6d6 ' + (value-min)*(100/(max-min)) + '%, #d6d6d6 100%)';

 $("#myinput-3").val((value-min)*(100/(max-min)));

                $("#myinput-3-val").html(value); 
  }
  function updateDevice() {
     var language=$("#language").val();
     deviceId=$("#test-device").val();
     if(deviceId!=null && deviceId!=0 && deviceId!=''){
     $.ajax({
      url: "<?php echo WWW_BASE; ?>admin/patients/updateDefoult/3554",
      type: 'POST', 
       data: {"page": 3,"deviceId": deviceId,"languageId": language}, 
      success: function(data){ 
        data=JSON.parse(data) 
        setGaze(data.device.TestDevice.device_type);
        }
      }); 
  }
  }
  
  function TestData() {
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
            window.location.replace('');
         }else{
            setting_alert('Something wrong. Please try again',2000);
         }
          
    },
      success: function(data){
        if(data==1 && startTestStatus==1){
          setting_alert('Device busy try again',2000);
          stopTestforce();
          dataCaptured=false;
        }else{
        DaTestType=$("#test-time").val();
        switch (DaTestType)
        {
            case 1:
                daTestTime = 15;
                break;
            case 2:
                daTestTime = 10;
                break;
            case 3:
                daTestTime = 7.5;
                break;
            case 4:
                daTestTime = 5;
                break;
            default:
                daTestTime = 7.5;
                break;

        }

        var language=$("#language").val(); 
          StartTestData.test_id=null;
          StartTestData.unique_id=''; 
          StartTestData.staff_name='<?php echo $user['first_name']." ".$user['last_name'];?>';
          StartTestData.staff_id='<?php echo $user['id']; ?>';  
          StartTestData.backgroundcolor=null;
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
          StartTestData.testBothEyes=testBothEyes; 
          StartTestData.LANGUAGE_SEL= language.toString(); 
          StartTestData.EYE= selectEye.toString(); 
          StartTestData.TEST_TYPE= null;
          StartTestData.THRESHOLD_TYPE= null;
          StartTestData.TEST_SUB_TYPE= null;
          StartTestData.TEST_SPEED= null;
          StartTestData.VOLUME= null;
          StartTestData.STM_SIZE=6;
          StartTestData.STM_INTENSITY=null;
          StartTestData.WALL_COLOR= null;
          StartTestData.BKG_INTENSITY= null;
          StartTestData.TEST_COLOR= null;
          StartTestData.PID= "'<?php echo $data['Patient']['id'];?>'";
          StartTestData.START= startTestStatus; //0=stop; 1=start; 2=pause; 3=resume

          StartTestData.VR_SET_AWAKE=''; 
          StartTestData.BOTH_EYES= (testBothEyes==true)?"1":"0"; 
          StartTestData.DA_COLOR= 3;
          StartTestData.DA_TESTTIME= daTestTime.toString(); 
          StartTestData.SET_SCREEN_BRIGHTNESS= "1.0";

          var obj=''; 
              var obj = {   
                previous_test:null,
                StartTest:StartTestData, 
                MasterRecord:null, 
              };   
var myJSON = JSON.stringify(obj); 
var test_time=$("#test-time").val();
$.ajax({
      url: "<?php echo WWW_BASE; ?>admin/patients/addTestStart/3554",
      type: 'POST',

       data: {"page": 3,"testType":test_time,"strategy":1,"test_name":'none',"patient_id": '<?php echo $data['Patient']['id'] ?>',"office_id": '<?php echo $user['office_id'] ?>',"deviceId": deviceId,"languageId": language, "TestStatus": startTestStatus, "testData": myJSON }, 
      success: function(data){
        if(data==2){
          alert('Test Device allready in use');
          stopTestforce();
          dataCaptured=false;
        }else{
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
console.log(myJSON);
 
 }
      }
    });


  }
</script>
<script>  
  document.getElementById("myinput-3").oninput = function() {
    this.style.background = 'linear-gradient(to right, #f5f5f5 0%, #f5f5f5 ' + this.value + '%, #d6d6d6 ' + this.value + '%, #d6d6d6 100%)'
  };

  document.getElementById("myinput-4").oninput = function() {
    this.style.background = 'linear-gradient(to right, #f5f5f5 0%, #f5f5f5 ' + this.value + '%, #d6d6d6 ' + this.value + '%, #d6d6d6 100%)'
  };
</script>
