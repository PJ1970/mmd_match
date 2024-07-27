<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Copy User Preset</h4> 
        </div> 
      </div>
      <div class="page-content-wrapper ">
       <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-primary">
                <div class="panel-body"> 
                  <div class="row">
				  <form action="" method="post" class="preset_list">
					<div class="multiselect">
						<div class="selectBox" onclick="showCheckboxes()">
						<select>
							<option>Select User</option>
						</select>
						<div class="overSelect"></div>
						</div>
						<div id="checkboxes" >
						<?php if(!empty($data)) {foreach($data as $key=>$datas){ //echo $datas;  ?>
						<label>
							<input  style="margin-right: 8px;" name='staff_list[]' value="<?php echo $key ?>" type="checkbox"/><?php echo $datas; ?></label>
							<?php }} ?>
						</div>
					</div>
					<input type="submit" class="btn btn-primary copy_button" value="Copy" >
					</form>
				
                  </div>
                </div>
              </div>
            </div>
          </div>
          </div>
        </div>
     </div> 
	 <script>
	var expanded = false;

function showCheckboxes() {
  var checkboxes = document.getElementById("checkboxes");
  if (!expanded) {
    checkboxes.style.display = "block";
    expanded = true;
  } else {
    checkboxes.style.display = "none";
    expanded = false;
  }
} 
	 </script>
	 <style>
	.multiselect {
  width: 200px;
}

.selectBox {
  position: relative;
}

.selectBox select {
  width: 100%;
  font-weight: bold;
}

.overSelect {
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
}

#checkboxes {
  display: none;
  border: 1px #dadada solid;
}

#checkboxes label {
  display: block;
}

#checkboxes label:hover {
  background-color: #1e90ff;
}	 
	</style>