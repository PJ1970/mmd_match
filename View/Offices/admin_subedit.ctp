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
.profile-img{}
.delete-img{vertical-align: bottom;margin: 9px;color:red;font-weight: bolder;}
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
                                <?php echo $this->Form->create('Office', array('novalidate' => true, 'url' => array('controller' => 'offices', 'action' => 'admin_subedit'), 'enctype' => 'multipart/form-data')); ?>
                                <?php
                                echo $this->Form->input('id', array('type' => 'hidden'));

                                echo $this->Form->input('name', array('type' => 'hidden')); //disabled
                                echo $this->Form->input('status', array('type' => 'hidden')); //disabled

                                echo $this->Form->input('credits', array('type' => 'hidden')); //disabled
                                echo $this->Form->input('left_credits', array('type' => 'hidden')); //disabled
                                echo $this->Form->input('payable', array('type' => 'hidden')); //disabled

                                echo $this->Form->input('monthly_package', array('type' => 'hidden')); //disabled
                                echo $this->Form->input('restrict', array('type' => 'hidden')); //disabled
                                ?>

                                <div class="col-sm-6 col-xs-12">
                                     <div class="form-group">
                                        <label>Archive OFF/ON</label>
                                        <div>
                                            <label class="switch">
                                                <?php echo $this->Form->input("archive_status", array('label' => false, 'div' => false, 'type' => 'checkbox', 'class' => 'checkbox archive_on')); ?>
                                                <span class="slider round"></span>
                                            </label>
                                            </div>
                                    </div>
                                    <div class="form-group archived_time" style="display: none;">
                                        <label>Archived time (Days)</label>
                                        <?php echo $this->Form->input('p_archived_date', array('type' => 'text', 'class' => ' archive_time form-control', 'label' => false, 'div' => false, 'placeholder' => "Enter archive Days", 'required' => true, 'allowEmpty' => true)); ?>
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

                                    <?php
                                    $checked_data = array();
                                    if (isset($this->request->data['Officereport'])) {
                                        $checked_data = Hash::extract($this->request->data['Officereport'], '{n}.office_report');

                                        foreach ($this->request->data['Officereport'] as $filter_cost_value) {
                                            $filter_cost_value_array[$filter_cost_value['office_report']] = $filter_cost_value['per_use_cost'];
                                        }
                                    }
                                    $checked = '';
                                    $arr = $test_c;
                                    $i = 0;
                                    foreach ($arr as $key => $val) {
                                        if (in_array($key, $checked_data)) {
                                            $checked = 'checked';
                                        } else {
                                            $checked = '';
                                        }
                                        echo $this->Form->input("Officereport.$i.office_report", array('type' => 'hidden')); //disabled
                                        echo $this->Form->input("Officereport.$i.per_use_cost", array('type' => 'hidden')); //disabled
                                        $i++;

                                    }
                                    ?>
                                    <div class="form-group">
                                        <label>Address</label>
                                        <div>

                                            <?php echo $this->Form->input('address', array('type' => 'textarea', 'class' => 'form-control', 'label' => false, 'div' => false, 'placeholder' => "Address", 'required' => true)); ?>
                                        </div>
                                    </div>
                                     <div class="form-group">
                                            <label> Office Language</label><br><span style="color:#ff0000">Office Language update In Development. Changes will not apply until update 1.4.20 for headset goes live. Language Selections Will Only Apply After Restarting VR Headset.</span> <br/>
                                            
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
                                        <label>Upload Office Logo <span style="color:red;"> * Image file size shoud be 90-180X90-180</span></label>
                                        <div class="profile-img">
                                            <?php
                                            $cls_hidden = "";
                                            $browse_hidden = "";
                                            if (@$this->request->data['Office']['office_pic'] == '') {
                                                $image_name = ''; // 'no-office.png';
                                                $cls_hidden = "hidden";
                                            } else {
                                                $image_name = @$this->request->data['Office']['office_pic'];
                                                //$browse_hidden = "hidden";
                                            }
                                            ?>
                                            <?php echo $this->Form->input('office_pic', array('type' => 'file', 'label' => false, "class" => 'form-control fileread ' . $browse_hidden, 'id' => 'OfficeOfficePic', 'accept' => 'image/*')); //'onchange' => 'readURL(this)', ?>
                                            <label  for="OfficeOfficePic" class="imgread <?php echo $cls_hidden; ?>"><img id="preview"  src="<?php echo WWW_BASE; ?>/img/office/<?php echo $image_name; ?>" alt="logo" width="100" height="100"/> </label>
                                            <?php
                                            // if (!empty($this->request->data['Office']['office_pic'])):
                                            $delete_image = $this->Html->url(array(
                                                "controller" => "offices",
                                                "action" => "subedit_pic",
                                                $this->request->data['Office']['id']
                                            ));
                                            ?>
                                            <a class="delete-img imgread <?php echo $cls_hidden; ?>"  href="<?php echo $delete_image; ?>" onclick="return confirm('Are you want to delete office logo?')" id="deleteOfficeLogo">  Delete logo</a>
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
                                        <div >
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
    <?php if (@$this->request->data['Office']['archive_status'] == 1) {  ?>
    <script>$('.archived_time').show();</script>
 <?php }?>
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
                        if ((image.height >= 90 && image.height <= 220) && (image.width >= 90 && image.width <= 220)) {
                            
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
$(".ihudevice").on("input", function() {
       var getV = $(this).val(); 
       if(getV == 0 && getV != ''){
        $(".showMsg").show();
       }else{
        $(".showMsg").hide();
       }
});

    </script>   