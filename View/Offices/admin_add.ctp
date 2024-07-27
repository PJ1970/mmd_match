<style>
.profile-img{}
.delete-img{vertical-align: bottom;margin: 9px;color:red;font-weight: bolder;}
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
  background-color: #2196F3;
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
.archive_time{width:30%;}
.ihudevice, .ihudevices{width: 30%;}
</style>

<div class="content">
    <div class="">
        <div class="page-header-title">
            <?php if (!isset($this->request->data['Office']['id'])): ?>
                <h4 class="page-title">Add Office</h4>
            <?php else: ?>
                <h4 class="page-title">Edit Office</h4>
            <?php endif; ?>
        </div>
    </div>
    <div class="page-content-wrapper ">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <div class="row">
                                <?php echo $this->Session->flash() . "<br/>"; ?>
                                <?php echo $this->Form->create('Office', array('novalidate' => true, 'url' => array('controller' => 'offices', 'action' => 'admin_add'), 'enctype' => 'multipart/form-data')); ?>
                                <?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="m-t-20">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <?php echo $this->Form->input('name', array('type' => 'text', 'class' => 'form-control', 'label' => false, 'div' => false, 'placeholder' => "Name Of Office", 'required' => true)); ?>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <?php echo $this->Form->input('email', array('type' => 'text', 'class' => 'form-control', 'label' => false, 'div' => false, 'placeholder' => "Email", 'required' => true)); ?>
                                        </div>
                                        <div class="form-group">
                                            <label>Secondary Email</label>
                                            <?php echo $this->Form->input('second_email', array('type' => 'text', 'class' => 'form-control', 'label' => false, 'div' => false, 'placeholder' => "Second Email", 'required' => false)); ?>
                                        </div>
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <?php echo $this->Form->input('phone', array('type' => 'text', 'class' => 'form-control', 'label' => false, 'div' => false, 'placeholder' => "Phone", 'required' => true)); ?>
                                        </div>
                                        <div class="form-group">
                                            <label>Status</label>
                                            <?php
                                            $option = array('0' => 'Inactive', '1' => 'Active');
                                            echo $this->Form->input(
                                                    'status',
                                                    array('options' => $option, 'class' => 'form-control', 'id' => 'payable', 'onclick' => 'hideDiv();', 'label' => false)
                                            );
                                            ?>
                                        </div>
                                        <!--<div class="form-group">
                                                <label>Per Use Cost</label>
                                        <?php #echo $this->Form->input('per_use_cost',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Enter Cost for each report",'required'=>false,'value' => !empty($this->request->data['Office']['per_use_cost']) ? $this->request->data['Office']['per_use_cost'] : '0' )); ?>
                                        </div>-->
                                        <div class="form-group">
                                            <label>Unassigned Credits</label>
                                            <?php echo $this->Form->input('credits', array('type' => 'text', 'class' => 'form-control', 'label' => false, 'div' => false, 'placeholder' => "Enter Cost for each report", 'required' => false, 'value' => !empty($this->request->data['Office']['credits']) ? $this->request->data['Office']['credits'] : '0', 'readonly' => 'readonly')); ?>
                                        </div>

                                        <div class="form-group">
                                            <label>Assigned Credits</label>
                                            <?php echo $this->Form->input('left_credits', array('type' => 'text', 'readonly' => 'readonly', 'class' => 'form-control', 'label' => false, 'div' => false, 'placeholder' => "Enter Cost for each report", 'required' => false, 'value' => !empty($user_credit_total) ? $user_credit_total : '0')); ?>
                                        </div>


                                        <div class="form-group">
                                            <label>Telemedicine</label>
                                            <?php
                                            $option = array('yes' => 'Payable', 'no' => 'Non-Payable');
                                            echo $this->Form->input(
                                                    'payable',
                                                    array('options' => $option, 'default' => 'yes', 'class' => 'form-control', 'id' => 'payable', 'onclick' => 'hideDiv();', 'label' => false)
                                            );
                                            ?>
                                        </div>

                                        <div class="form-group">
                                            <?php echo $this->Form->input('monthly_package', array('type' => 'text', 'class' => 'form-control', 'id' => 'monthly_package', 'label' => false, 'div' => false, 'placeholder' => "Enter Cost (Monthly package)", 'required' => false, 'value' => !empty($this->request->data['Office']['monthly_package']) ? $this->request->data['Office']['monthly_package'] : '')); ?>
                                        </div>

                                        <div class="form-group">
                            <label>Select Rep Admin</label>
                            <div>
              <?php 
                $options=$this->custom->getRepAdminList();
                echo $this->Form->input('rep_admin',array('default' => (!empty($this->request->data['Office']['rep_admin'])) ? $this->request->data['Office']['rep_admin'] : @$this->Session->read('Search.office'), 'options' => $options,'empty'=>'Select Rep Admin','div'=>false,'legend'=>false,'class' => 'form-control selectpicker show-tick','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3'));
              ?>
                            </div>
                        </div>

                                        <div class="form-group">
                                             <label>Password</label>
                                             <?php if($password=='' || $password==null){ ?>
                                             <button type="button" class="btn btn-primary" style="float:right" onclick="genratePassword();">Generate password</button>
                                            <?php echo $this->Form->input('password', array('type' => 'text', 'class' => 'form-control', 'id' => 'password', 'label' => false, 'div' => false, 'placeholder' => "Enter Password", 'required' => true, 'readonly'=>true)); ?>
                                            <?php echo $this->Form->input('password2', array('type' => 'hidden', 'class' => 'form-control', 'id' => 'password2', 'label' => false, 'div' => false, 'placeholder' => "Enter Password")); ?>
                                            <?php }else{ ?>
                                            <?php echo $this->Form->input('password', array('type' => 'hidden',  'readonly' => 'readonly' ,'class' => 'form-control', 'id' => 'password', 'label' => false, 'div' => false, 'placeholder' => "Enter Password")); ?>
                                            <?php echo $this->Form->input('password2', array('type' => 'text', 'readonly' => 'readonly', 'class' => 'form-control', 'id' => 'password2', 'label' => false, 'div' => false, 'placeholder' => "Enter Password")); ?>
                                            
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <?php
                                            $option = array('restrict' => 'Restrict', 'non-restrict' => 'Non-Restrict');
                                            echo $this->Form->input(
                                                    'restrict',
                                                    array('options' => $option, 'class' => 'form-control', 'id' => 'restrict', 'label' => false)
                                            );
                                            ?>
                                        </div>

                                        <div class="form-group">
                                            <label>	Office test Reports</label><br/>
                                            <?php
                                            $checked_data = array();
                                            if (isset($this->request->data['Officereport'])) {
                                                $checked_data = Hash::extract($this->request->data['Officereport'], '{n}.office_report');

                                                foreach ($this->request->data['Officereport'] as $filter_cost_value) {
                                                    $filter_cost_value_array[$filter_cost_value['office_report']] = $filter_cost_value['per_use_cost'];
                                                }
                                            }

                                            //pr($filter_cost_value_array);
                                            $checked = '';
                                            $arr = $test_c;
                                            $i = 0;
                                            foreach ($arr as $key => $val) {
                                                if (in_array($key, $checked_data)) {
                                                    $checked = 'checked';
                                                } else {
                                                    $checked = '';
                                                }
                                                ?>
                                                <div class="change_checkbox">
                                                    <?php
                                                    echo $this->Form->input("Officereport.$i.office_report", array('value' => $key, 'label' => array('text' => $val), 'type' => 'checkbox', $checked));

                                                    echo $this->Form->input('Officereport.' . $i . '.per_use_cost', array('type' => 'text', 'class' => 'form-control', 'label' => false, 'div' => false, 'placeholder' => "Enter Cost for each report", 'required' => false, 'value' => !empty($filter_cost_value_array[$key]) ? $filter_cost_value_array[$key] : 0));

                                                    $i++;
                                                    ?>
                                                </div>
                                                <?php
                                            }
                                            //echo $this->Form->select('Officereports.office_report', $arr, array('multiple' => 'checkbox'));
                                            ?>
                                        </div>
                                        
                                        
                                        <div class="form-group">
                                            <label> Office Language</label><br/>
                                            <?php
                                            $checked_data = array();
                                            if (isset($this->request->data['Officelanguage'])) {
                                                $checked_data = Hash::extract($this->request->data['Officelanguage'], '{n}.language_id'); 
                                            }
                                            if(!count($checked_data)){
                                                    $checked_data = array(30,31,32,33,34,35,40,41,42);
                                                }
                                            $checked = '';
                                            $arr = $test_c;
                                            $i = 0;
                                            foreach ($language as $key => $val) {
                                                if (in_array($key, $checked_data)) {
                                                    $checked = 'checked';
                                                } else {
                                                    $checked = '';
                                                }
                                                 $readonly = '0';
                                                if($val =='English'){
                                                    $readonly  = '1';
                                                    $checked = 'checked';
                                                }
                                                ?>
                                                <div class="change_checkbox">
                                                    <?php
                                                     echo $this->Form->input("Officelanguage.$i.language_id", array('value' => $key, 'label' => array('text' => $val), 'type' => 'checkbox', $checked, 'disabled' => $readonly));
                                                    if($val =='English'){
                                                        echo $this->Form->input('Officelanguage.$i.language_id', array('type' => 'hidden', 'class' => 'form-control', 'label' => false, 'div' => false, 'value' => $key  ));
                                                   }
                                        
                                                    $i++;
                                                    ?>
                                                </div>
                                                <?php
                                            }
                                            //echo $this->Form->select('Officereports.office_report', $arr, array('multiple' => 'checkbox'));
                                            ?>
                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            <div>

                                                <?php echo $this->Form->input('address', array('type' => 'textarea', 'class' => 'form-control', 'label' => false, 'div' => false, 'placeholder' => "Address", 'required' => true)); ?>
                                            </div>
                                        </div>
                                       <!--  <div class="form-group">
                                            <label>Auto Backup</label>
                                            <div>

                                               <label class="switch">
                                                <?php echo $this->Form->input("backup", array('label' => false, 'div' => false, 'type' => 'checkbox', 'class' => 'checkbox')); ?>
                                                   <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div> -->
                                         <div class="form-group" style="display: flex;  align-items: center; flex-wrap: wrap;">
                                            
                                            <label style="width: 100%;">Auto Backup</label>
                                            <div>

                                               <label class="switch">
                                                <?php echo $this->Form->input("backup", array('label' => false, 'div' => false, 'type' => 'checkbox', 'class' => 'checkbox')); ?>
                                                   <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <label style="display: flex; flex-direction: row-reverse; margin-left: 70px;"><span style=" white-space: nowrap;  align-self: center; margin-left: 8px;">Delete on server after backup</span>
                                            <?php echo $this->Form->input("delete_after_backup", array('label' => false, 'div' => false, 'type' => 'checkbox', 'class' => 'checkbox form-control', 'style'=>'width: 26px; margin-top: 0;')); ?>
                                           </label>
                                        </div>
                                         <div class="form-group">
                                            <label>Detailed Progression</label>
                                            <div>

                                               <label class="switch">
                                                <?php echo $this->Form->input("detailed_progression", array('label' => false, 'div' => false, 'type' => 'checkbox', 'class' => 'checkbox')); ?>
                                                   <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Web Controller</label>
                                            <div>

                                               <label class="switch">
                                                <?php echo $this->Form->input("server_test", array('label' => false, 'div' => false, 'type' => 'checkbox', 'class' => 'checkbox')); ?>
                                                   <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                         <div class="form-group">
                                            <label>Archive ON/OFF</label>
                                            <div>

                                               <label class="switch">
                                                <?php echo $this->Form->input("archive_status", array('label' => false, 'div' => false, 'type' => 'checkbox', 'class' => 'checkbox')); ?>
                                                   <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Alpha Apk</label>
                                            <div>
                                               <label class="switch">
                                                <?php echo $this->Form->input("alphaapktype", array('label' => false, 'div' => false, 'type' => 'checkbox', 'class' => 'checkbox')); ?>
                                                   <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>High Security</label>
                                            <div>
                                               <label class="switch">
                                                <?php echo $this->Form->input("high_security", array('label' => false, 'div' => false, 'type' => 'checkbox', 'class' => 'checkbox')); ?>
                                                   <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group archived_time" style="display: none;">
                                        <label>Archived time (Days)</label>
                                        <?php echo $this->Form->input('p_archived_date', array('type' => 'text', 'class' => ' archive_time form-control', 'label' => false, 'div' => false, 'placeholder' => "Enter archive Days", 'required' => true )); ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Reassign Device Time (Days)</label>
                                        <?php echo $this->Form->input('weekdays', array('type' => 'number', 'class' => 'ihudevices form-control', 'label' => false, 'div' => false, 'placeholder' => "Enter Days", 'required' => true )); ?>
                                    </div>
                                        <div class="form-group">
                                            <label>Upload Office Logo <span style="color:red;"> * Image file size shoud be 90-180X90-180</span></label>
                                            <div>
                                                <?php /* echo $this->Form->input('office_pic', array('type' => 'file', 'label' => false, "class" => 'form-control', 'onchange' => 'readURL(this)', 'accept' => 'image/*')); ?>
                                                <!--span class="user_dsply image" style="margin: 10px 10px;float:left;"-->
                                                <?php*/
                                                $cls_hidden = "";
                                                $browse_hidden = "";
                                                if (@$this->request->data['Office']['office_pic'] == '') {
                                                    $image_name = 'no-user.png';
                                                    $cls_hidden = "hidden";
                                                } else {
                                                    $image_name = @$this->request->data['Office']['office_pic'];
                                                }
                                                ?>
                                                <?php echo $this->Form->input('office_pic', array('type' => 'file', 'label' => false, "class" => 'form-control fileread ' . $browse_hidden, 'id' => 'OfficeOfficePic', 'accept' => 'image/*')); //'onchange' => 'readURL(this)', ?>
                                                <label  for="OfficeOfficePic" class="imgread <?php echo $cls_hidden; ?>"><img id="preview"  src="<?php echo WWW_BASE; ?>/img/office/<?php echo $image_name; ?>" alt="logo" width="100" height="100"/> </label>
                                                <?php
                                                $delete_image= '';
                                                 if (!empty($this->request->data['Office']['id'])):
                                                $delete_image = $this->Html->url(array(
                                                    "controller" => "offices",
                                                    "action" => "adminedit_pic",
                                                    $this->request->data['Office']['id']
                                                ));
                                                 endif;
                                                ?>
                                                <a class="delete-img imgread <?php echo $cls_hidden; ?>"  href="<?php echo $delete_image; ?>" onclick="return confirm('Are you want to delete office logo?')" id="deleteOfficeLogo">  Delete logo</a>
                                                <?php /*
                                                <img id="preview" src="<?php echo WWW_BASE; ?>/img/office/<?php echo $image_name; ?>" alt="your image" width="100" height="100"/>
                                                <!--/span-->*/ ?>

                                            </div>
                                        </div>
                                        <div class="form-group imgread  <?php echo $cls_hidden; ?>">
                                            <label>Rotate At</label>
                                            <div class="outerradio">
                                                <div class="innerrotate">
                                                    <input type="radio" name="data[Office][rotate]" value="90"><span>90&deg;</span></div>
                                                <div class="innerrotate">
                                                    <input type="radio" name="data[Office][rotate]" value="180"><span>180&deg;</span></div>
                                                <div class="innerrotate">
                                                    <input type="radio" name="data[Office][rotate]" value="270"><span>270&deg;</span></div>

                                            </div>
                                        </div>

                                        <div class="form-group clearfix">
                                            <div>
                                                <button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php echo $this->Form->end(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>$('.archived_time').show();</script>
        <?php if (@$this->request->data['Office']['payable'] == 'no') { ?>
            <script type="text/javascript">
                document.getElementById("monthly_package").style.display = "none";

            </script> 
        <?php } else { ?>
            <script type="text/javascript">
                document.getElementById("restrict").style.display = "none";
            </script>
        <?php } ?>
        <script type="text/javascript">
            function genratePassword(){
             $.ajax({
      url: "<?php echo WWW_BASE; ?>admin/offices/genratePassword",
      type: 'POST',

      data: {}, 
      success: function(data){
          $("#password").val(data);
          $("#password2").val(data);
      }
      
             });
          
        }
            function hideDiv() {
                var val = document.getElementById("payable").value;
                if (val == 'no') {
                    document.getElementById("monthly_package").style.display = "none";
                    document.getElementById("restrict").style.display = "block";
                } else {
                    document.getElementById("monthly_package").style.display = "block";
                    document.getElementById("restrict").style.display = "none";
                }

            }

            var _URL = window.URL || window.webkitURL;
            $("#OfficeOfficePic").change(function (e) {
                var $this = e.target;
                if ($this.files && $this.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        var $that = e.target;

                        var image = new Image();
                        image.src = reader.result;
                        image.onload = function () {
                            if ((image.height >= 25 && image.height <= 220) && (image.width >= 25 && image.width <= 220)) {

                                $('#preview').attr('src', $that.result);
                                $(".imgread").removeClass("hidden");
                            } else {
                                //$('#preview').attr('src', '#');
                                $("#OfficeOfficePic").val('');
                                //$(".imgread").addClass("hidden");
                                alert("Height and Width must not exceed 90-180*90-180.");
                            }
                        };
                    }

                    reader.readAsDataURL($this.files[0]);
                }
            });
            $(".archive_on").click(function(){
  $('.archived_time').show();
});
<?php /* </script>   
  <script>
  function readURL(input) {
  if (input.files && input.files[0]) {
  var reader = new FileReader();

  reader.onload = function (e) {
  $('#preview').attr('src', e.target.result);
  }

  reader.readAsDataURL(input.files[0]);
  }
  }
  var _URL = window.URL || window.webkitURL;
  $("#OfficeOfficePic").change(function (e) {
  var file, img;
  if ((file = this.files[0])) {
  img = new Image();
  var objectUrl = _URL.createObjectURL(file);
  img.onload = function () {
  if ((this.height >= 90 && this.height <= 180) && (this.width >= 90 && this.width <= 180)) {
  _URL.revokeObjectURL(objectUrl);
  return true;
  }
  $('#preview').attr('src', '#');
  $("#OfficeOfficePic").val('');
  alert("Height and Width must not exceed 90-180*90-180.");
  _URL.revokeObjectURL(objectUrl);
  return false;
  };
  img.src = objectUrl;
  }
  }); */ ?>
        </script>