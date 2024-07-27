 <div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
      <?php $Admin = $this->Session->read('Auth.Admin');?>
      <div id="sidebar-menu">
        <ul>
			<li <?php if ((($this->params['action'] == "admin_index"))&& $this->params['controller'] == "dashboards") { ?> class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/dashboards/index'); ?>" class="waves-effect"><i class="mdi mdi-home"></i><span> Dashboard </span></a></li>
			
			<?php if(!empty($Admin) && $Admin['user_type']=="Admin"): ?>
			
			<li <?php if ((($this->params['action'] == "admin_addSubAdmin") || ($this->params['action'] == "admin_subadmin_listing") || ($this->params['action'] =="admin_edit") || ($this->params['action'] =="admin_user_delete")|| ($this->params['action'] =="admin_subAdminView"))&& $this->params['controller'] == "users") { ?> class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/users/subadmin_listing'); ?>" class="waves-effect"><i class="mdi mdi-account"></i><span>Manage Sub-Admin</span></a></li>
				
			<?php endif; ?>
			
			<?php if(!empty($Admin) && $Admin['user_type'] != "Staffuser"): ?>
			
			<li <?php if ((($this->params['action'] == "admin_add") || ($this->params['action'] == "admin_delete") || ($this->params['action'] =="admin_edit") || ($this->params['action'] =="admin_staffView")|| ($this->params['action'] =="admin_staff_listing")|| ($this->params['action'] =="admin_resetpassword"))&& $this->params['controller'] == "staff"){ ?>class="active_li" <?php }elseif((($this->params['action'] =="admin_notification_send"))&& $this->params['controller'] == "notifications") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/staff/staff_listing'); ?>" class="waves-effect"><i class="mdi mdi-hospital"></i><span>Manage Staff</span></a></li>
			
			<?php endif; ?>
			
			<li <?php if ((($this->params['action'] == "admin_patients_listing")||($this->params['action'] == "admin_addPatient") || ($this->params['action'] == "admin_delete") || ($this->params['action'] =="admin_edit") || ($this->params['action'] =="admin_view")|| ($this->params['action'] =="admin_changeStatus"))&& $this->params['controller'] == "patients") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/patients/patients_listing'); ?>" class="waves-effect"><i class="mdi mdi-account-plus"></i><span>Manage Patients</span></a></li>
			
			
			
			<?php if(!empty($Admin) && $Admin['user_type']=="Admin"): ?>
			
			<!--<li <?php /*if ((($this->params['action'] == "admin_practice_list") || ($this->params['action'] == "admin_edit") || ($this->params['action'] =="admin_delete") || ($this->params['action'] =="admin_addPractice")|| ($this->params['action'] =="admin_view"))&& $this->params['controller'] == "practices") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/practices/practice_list'); */?>" class="waves-effect"><i class="mdi mdi-xing-box"></i><span>Manage Practices</span></a></li>-->
			
			<li <?php if ((($this->params['action'] == "admin_test_device_list") || ($this->params['action'] == "admin_view") || ($this->params['action'] =="admin_edit") || ($this->params['action'] =="admin_add")|| ($this->params['action'] =="admin_delete"))&& $this->params['controller'] == "testdevice") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/testdevice/test_device_list'); ?>" class="waves-effect"><i class="mdi mdi-desktop-mac"></i><span>Manage Test Devices</span></a></li>
			
			<?php endif; ?>
			
			<li <?php if ((($this->params['action'] == "admin_test_reports_list") || ($this->params['action'] == "admin_view"))&& $this->params['controller'] == "testreports") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/testreports/test_reports_list'); ?>" class="waves-effect"><i class="mdi mdi-view-list"></i><span>Camera Test Report</span></a></li>
			
			<li <?php if ((($this->params['action'] == "admin_unity_reports_list") || ($this->params['action'] == "admin_view"))&& $this->params['controller'] == "unityreports") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/unityreports/unity_reports_list'); ?>" class="waves-effect"><i class="mdi mdi-view-list"></i><span>VF Test Report</span></a></li>
			
			<?php if(!empty($Admin) && $Admin['user_type']=="Admin"): ?>
			<li <?php if ($this->params['action'] == "admin_manage_office" && $this->params['controller'] == "offices") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/offices/manage_office'); ?>" class="waves-effect"><i class="mdi mdi-office"></i><span>Manage Office</span></a></li>
			<?php endif; ?>
			
			
			<li <?php if ($this->params['action'] == "admin_index" && $this->params['controller'] == "tests") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/tests/index'); ?>" class="waves-effect"><i class="mdi mdi-view-list"></i><span>Manage Test</span></a></li>
			
			<?php
			 
			if(!empty($Admin) && $Admin['user_type']== "Subadmin"){ ?>
			<li <?php if ($this->params['action'] == "admin_purchase_credit" && $this->params['controller'] == "payments") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/payments/purchase_credit'); ?>" class="waves-effect"><i class="glyphicon glyphicon-usd"></i><span>Purchase Credits</span></a></li>
			
			<li <?php if ($this->params['action'] == "admin_assign_credits" && $this->params['controller'] == "staff") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/staff/assign_credits'); ?>" class="waves-effect"><i class="glyphicon glyphicon-credit-card"></i><span>Assign Credits</span></a></li>
			
			<?php  }  ?>
			
			<li <?php if ((($this->params['action'] == "admin_notification_list") || ($this->params['action'] == "admin_notification_delete") )&& $this->params['controller'] == "notifications") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/notifications/notification_list'); ?>" class="waves-effect"><i class="mdi mdi-message-alert"></i><span>Push Notifications</span></a></li>
			
			<!--<li class="has_sub"> <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-album"></i> <span>Manage Staff Users</span> <span class="pull-right"><i class="mdi mdi-plus"></i></span></a>
				<ul class="list-unstyled">
					<li><a href="ui-buttons.html">Staff Users Details</a></li>
					<li><a href="ui-panels.html">Add Staff User</a></li>
				</ul>
			</li>
			<li class="has_sub"> <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-opacity"></i> <span> Manage Patients</span> <span class="pull-right"><i class="mdi mdi-plus"></i></span></a>
				<ul class="list-unstyled">
					<li><a href="icons-material.html">Material Design</a></li>
					<li><a href="icons-ion.html">Ion Icons</a></li>
					<li><a href="icons-fontawesome.html">Font awesome</a></li>
					<li><a href="icons-themify.html">Themify Icons</a></li>
				</ul>
			</li>-->
          <!--<li class="has_sub"> <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-layers"></i><span> Practice Name </span><span class="pull-right"><i class="mdi mdi-plus"></i></span></a>
            <ul class="list-unstyled">
              <li><a href="form-elements.html">General Elements</a></li>
              <li><a href="form-validation.html">Form Validation</a></li>
              <li><a href="form-advanced.html">Advanced Form</a></li>
              <li><a href="form-wysiwyg.html">WYSIWYG Editor</a></li>
              <li><a href="form-uploads.html">Multiple File Upload</a></li>
            </ul>
          </li>-->
          
          <!--<li class="has_sub"> <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-table"></i><span> Test Device </span><span class="pull-right"><i class="mdi mdi-plus"></i></span></a>
            <ul class="list-unstyled">
              <li><a href="tables-basic.html">Basic Tables</a></li>
              <li><a href="tables-datatable.html">Data Table</a></li>
            </ul>
          </li>-->
          <!--<li class="has_sub"> <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-chart-pie"></i><span> Charts </span><span class="pull-right"><i class="mdi mdi-plus"></i></span></a>
            <ul class="list-unstyled">
              <li><a href="charts-morris.html">Morris Chart</a></li>
              <li><a href="charts-chartjs.html">Chartjs</a></li>
              <li><a href="charts-flot.html">Flot Chart</a></li>
              <li><a href="charts-other.html">Other Chart</a></li>
            </ul>
          </li>-->
          <!--<li class="has_sub"> <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-map"></i><span> Test Report </span><span class="pull-right"><i class="mdi mdi-plus"></i></span></a>
            <ul class="list-unstyled">
              <li><a href="maps-google.html"> Google Map</a></li>
              <li><a href="maps-vector.html"> Vector Map</a></li>
            </ul>
          </li>-->
          
          <!--<li class="has_sub"> <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-assistant"></i><span> Notification </span><span class="pull-right"><i class="mdi mdi-plus"></i></span></a>
            <ul class="list-unstyled">
              <li><a href="layouts-collapse.html">Menu Collapse</a></li>
              <li><a href="layouts-smallmenu.html">Menu Small</a></li>
              <li><a href="layouts-menu2.html">Menu Style 2</a></li>
            </ul>
          </li>-->
          
        </ul>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
  
<script>
$(function(){
	 $(".active_li").find('a').addClass('subdrop');
});
</script>