<div class="left side-menu">
	<div class="sidebar-inner slimscrollleft">
		<?php $Admin = $this->Session->read('Auth.Admin');?>
		<div id="sidebar-menu">
			<ul>
				<li <?php if ((($this->params['action'] == "admin_index"))&& $this->params['controller'] == "dashboards") { ?> class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/dashboards/index'); ?>" class="waves-effect"><i class="mdi mdi-home"></i><span> Dashboard</span></a></li>
				<li <?php if ($this->params['action'] == "admin_index" && $this->params['controller'] == "support" || $this->params['action'] == "admin_add" || $this->params['action'] == "admin_reply" || $this->params['action'] == "admin_view" && $this->params['controller'] == "support") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/support'); ?>" class="waves-effect"><i class="fa fa-question-circle"></i><span>Support</span>&nbsp;&nbsp;<span  style="border-radius: 5px;  background: #ffac33;  visibility: visible; color: #000;  padding: 1px 5px;  font-size: 14px; font-weight: 500; float: right; visibility:hidden" id="unread_support"></span></a></li>
				<?php if(!empty($Admin) && $Admin['user_type']=="Admin"){ ?>
					<li <?php if ($this->params['action'] == "admin_manage_office" && $this->params['controller'] == "offices" || $this->params['action'] == "admin_add" && $this->params['controller'] == "offices") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/offices/manage_office'); ?>" class="waves-effect"><i class="mdi mdi-office"></i><span>Manage Office</span></a></li>
					<li <?php if ($this->params['action'] == "admin_office_report") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/offices/office_report'); ?>" class="waves-effect"><i class="mdi mdi-office"></i><span>Office Report</span></a></li>
					<li <?php if ($this->params['action'] == "admin_index" && $this->params['controller'] == "cms" || $this->params['action'] == "admin_edit" && $this->params['controller'] == "cms") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/cms/index'); ?>" class="waves-effect"><i class="fa fa-file-word-o"></i><span>Manage Cms</span></a></li>
					<li <?php if ($this->params['action'] == "admin_index" && $this->params['controller'] == "apk") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/apk/index'); ?>" class="waves-effect"><i class="fa fa-android"></i><span>Manage Apk</span></a></li>
					<li <?php if ($this->params['action'] == "admin_index" && $this->params['controller'] == "ticketcategory") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/categories/index'); ?>" class="waves-effect"><i class="fa fa-android"></i><span>Manage Ticket Category</span></a></li>
				<?php } ?>
				<?php
                //subadmin office edit menu
				if(!empty($Admin) && $Admin['user_type']=="Subadmin") {
					//office_id
					//if(CustomHelper::checkVideoModulePermission($Admin['office_id'])){ ?>
						<li <?php if ($this->params['action'] == "admin_index" && $this->params['controller'] == "video") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/video/'); ?>" class="waves-effect"><i class="fa fa-file-video-o"></i><span>Manage Video</span></a></li>
						<?php //} ?>
						<li <?php if ($this->params['action'] == "admin_add" && $this->params['controller'] == "offices") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/offices/subedit/'.$Admin['office_id']); ?>" class="waves-effect"><i class="mdi mdi-office"></i><span>Manage Office</span></a></li>
					<?php } ?>

					<?php if(!empty($Admin) && $Admin['user_type']=="Admin") { ?>
						<li <?php if ((($this->params['action'] == "admin_add_admin") || ($this->params['action'] == "admin_listing_admin") || ($this->params['action'] =="admin_edit_admin") || ($this->params['action'] =="admin_user_delete")|| ($this->params['action'] =="admin_view_admin"))&& $this->params['controller'] == "users") { ?> class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/users/listing_admin'); ?>" class="waves-effect"><i class="fa fa-user"></i><span>Manage Admin</span></a></li>
						<li <?php if ((($this->params['action'] == "admin_index") || ($this->params['action'] == "admin_add_office") || ($this->params['action'] =="admin_add") || ($this->params['action'] =="admin_edit")|| ($this->params['action'] =="admin_view")|| ($this->params['action'] =="admin_delete"))&& $this->params['controller'] == "customers") { ?> class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/customers/index'); ?>" class="waves-effect"><i class="fa fa-user"></i><span>Manage Customer</span></a></li>
						<li <?php if ((($this->params['action'] == "admin_addSubAdmin") || ($this->params['action'] == "admin_subadmin_listing") || ($this->params['action'] =="admin_edit") || ($this->params['action'] =="admin_user_delete")|| ($this->params['action'] =="admin_subAdminView"))&& $this->params['controller'] == "users") { ?> class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/users/subadmin_listing'); ?>" class="waves-effect"><i class="mdi mdi-account"></i><span>Manage Sub-Admin</span></a></li>
						<li <?php if ((($this->params['action'] == "admin_addSuperSubAdmin") || ($this->params['action'] == "admin_super_subadmin_listing") || ($this->params['action'] =="admin_edit_super") || ($this->params['action'] =="admin_user_delete")|| ($this->params['action'] =="admin_subAdminView"))&& $this->params['controller'] == "users") { ?> class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/users/super_subadmin_listing'); ?>" class="waves-effect"><i class="mdi mdi-account"></i><span>Manage Download Admin</span></a></li>
						<li <?php if ((($this->params['action'] == "admin_addRepAdmin") || ($this->params['action'] == "admin_rep_admin_listing") || ($this->params['action'] =="admin_edit_rep") || ($this->params['action'] =="admin_user_delete")|| ($this->params['action'] =="admin_repAdminView"))&& $this->params['controller'] == "users") { ?> class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/users/rep_admin_listing'); ?>" class="waves-effect"><i class="mdi mdi-account"></i><span>Manage Rep Admin</span></a></li>
					<?php } ?>

					<?php if(!empty($Admin) && $Admin['user_type'] == "OfficeAdmin"){ ?>
						<li <?php if ((($this->params['action'] == "admin_addSubAdmin") || ($this->params['action'] == "admin_subadmin_listing") || ($this->params['action'] =="admin_edit") || ($this->params['action'] =="admin_user_delete")|| ($this->params['action'] =="admin_subAdminView"))&& $this->params['controller'] == "users") { ?> class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/users/subadmin_listing'); ?>" class="waves-effect"><i class="mdi mdi-account"></i><span>Manage Sub-Admin</span></a></li>
					<?php } ?>

					<?php if(!empty($Admin) && $Admin['user_type'] != "Staffuser" && $Admin['user_type'] != "SuperSubadmin" && $Admin['user_type'] != "SupportSuperAdmin" && $Admin['user_type'] != "RepAdmin"){ ?>
						<li <?php if ((($this->params['action'] == "admin_add") || ($this->params['action'] == "admin_delete") || ($this->params['action'] =="admin_edit") || ($this->params['action'] =="admin_staffView")|| ($this->params['action'] =="admin_staff_listing")|| ($this->params['action'] =="admin_resetpassword"))&& $this->params['controller'] == "staff"){ ?>class="active_li" <?php }elseif((($this->params['action'] =="admin_notification_send"))&& $this->params['controller'] == "notifications") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/staff/staff_listing'); ?>" class="waves-effect"><i class="mdi mdi-hospital"></i><span>Manage Staff</span></a></li>
					<?php } ?>

					<?php if($Admin['user_type'] != "SuperSubadmin" && $Admin['user_type'] != "SupportSuperAdmin" && $Admin['user_type'] != "RepAdmin"){ ?>
						<li <?php if ((($this->params['action'] == "admin_patients_listing")||($this->params['action'] == "admin_addPatient") || ($this->params['action'] == "admin_delete") || ($this->params['action'] =="admin_edit") || ($this->params['action'] =="admin_view")|| ($this->params['action'] =="admin_changeStatus"))&& $this->params['controller'] == "patients") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/patients/patients_listing'); ?>" class="waves-effect"><i class="mdi mdi-account-plus"></i><span>Manage Patients</span></a></li>
					<?php } ?>

					<li class="has_sub">
						<a href="javascript:void(0);" class="waves-effect">
							<i class="mdi mdi-view-list"></i>
							<span>Patient Reports</span>
							<span class="pull-right"><i class="mdi mdi-plus"></i></span>
						</a>
						<ul class="list-unstyled">
							<?php if((!empty($Admin) && $Admin['user_type'] == "Admin") || in_array('Visual Field Test', $activeSideMenu)){ ?>
								<li class="<?php echo ( in_array($this->params['action'],array("admin_unity_reports_list","admin_view")) && $this->params['controller'] == "unityreports" && @$this->request->pass[0] == "VF" ? 'active_li':'') ?>"> <a href="<?php echo $this->HTML->url('/admin/unityreports/unity_reports_list/VF'); ?>" class="waves-effect"><i class="mdi mdi-view-list"></i><span> VF Reports </span></a></li>
							<?php } ?>
							<?php if((!empty($Admin) && $Admin['user_type'] == "Admin") || in_array('Vision Screening', $activeSideMenu)){ ?>
								<li class="<?php echo ( in_array($this->params['action'],array("admin_unity_reports_list","admin_view")) && $this->params['controller'] == "unityreports" && @$this->request->pass[0] == "VS" ? 'active_li':'') ?>"> <a href="<?php echo $this->HTML->url('/admin/unityreports/unity_reports_list/VS'); ?>" class="waves-effect"><i class="mdi mdi-view-list"></i><span> VS Reports </span></a></li>
							<?php } ?>
							<?php if((!empty($Admin) && $Admin['user_type'] == "Admin") || in_array('FDT', $activeSideMenu)){ ?>
								<li class="<?php echo ( in_array($this->params['action'],array("admin_unity_reports_list","admin_view")) && $this->params['controller'] == "unityreports" && @$this->request->pass[0] == "FDT" ? 'active_li':'') ?>"> <a href="<?php echo $this->HTML->url('/admin/unityreports/unity_reports_list/FDT'); ?>" class="waves-effect"><i class="mdi mdi-view-list"></i><span> FDT Reports </span></a></li>
							<?php } ?>
							<?php if($Admin['user_type'] == "Admin" || in_array('Advance Color Test', $activeSideMenu)){ ?>
								<li <?php if ((($this->params['action'] == "admin_act_list")) && $this->params['controller'] == "act") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/act/act_list'); ?>" class="waves-effect"><i class="mdi mdi-view-list"></i><span> ACT Reports</span></a></li>
							<?php } ?>
							<?php if($Admin['user_type'] == "Admin" || in_array('Vision Therapy', $activeSideMenu)){ ?>
								<li <?php if ((($this->params['action'] == "admin_vt_list")) && $this->params['controller'] == "vt") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/vt/vt_list'); ?>" class="waves-effect"><i class="mdi mdi-view-list"></i><span> VT Reports</span></a></li>
							<?php } ?>
							<?php if($Admin['user_type'] == "Admin" || in_array('Pupilometer', $activeSideMenu)){ ?>
								<li <?php if ((($this->params['action'] == "admin_pup_list")) && $this->params['controller'] == "pup") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/pup/pup_list'); ?>" class="waves-effect"><i class="mdi mdi-view-list"></i><span> Pup Reports</span></a></li>
							<?php } ?>
							<?php if($Admin['user_type'] == "Admin" || in_array('Visual Acuity Test', $activeSideMenu)) { ?>
								<li <?php if ((($this->params['action'] == "admin_va_reports_list") || ($this->params['action'] == "admin_view"))&& $this->params['controller'] == "vareports") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/vareports/va_reports_list'); ?>" class="waves-effect"><i class="mdi mdi-view-list"></i><span> VA Reports</span></a></li>
							<?php } ?>
							<?php if($Admin['user_type'] == "Admin" || in_array('Dark Adaptation', $activeSideMenu)==1){ ?>
								<li <?php if ((($this->params['action'] == "admin_dark_adaptations_list"))&& $this->params['controller'] == "darkadaptations") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/darkadaptations/dark_adaptations_list'); ?>" class="waves-effect"><i class="mdi mdi-view-list"></i><span> DA Reports</span></a></li>
							<?php } ?>
							<?php if($Admin['user_type'] == "Admin" || in_array('STB', $activeSideMenu)){ ?>
								<li <?php if ((($this->params['action'] == "admin_stb_list")) && $this->params['controller'] == "stb") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/stb/stb_list'); ?>" class="waves-effect"><i class="mdi mdi-view-list"></i><span> STB Reports</span></a></li>
							<?php } ?>
						</ul>
					</li>
					<li <?php if ((($this->params['action'] == "admin_test_device_home_use_list"))&& $this->params['controller'] == "testdevice") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/testdevice/test_device_home_use_list'); ?>" class="waves-effect"><i class="mdi mdi-desktop-mac"></i><span>Manage In Home Use</span></a></li>

					<?php if(!empty($Admin) && $Admin['user_type']=="Admin"){ ?>
						<!--<li <?php /*if ((($this->params['action'] == "admin_practice_list") || ($this->params['action'] == "admin_edit") || ($this->params['action'] =="admin_delete") || ($this->params['action'] =="admin_addPractice")|| ($this->params['action'] =="admin_view"))&& $this->params['controller'] == "practices") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/practices/practice_list'); */?>" class="waves-effect"><i class="mdi mdi-xing-box"></i><span>Manage Practices</span></a></li>-->
						<li <?php if ((($this->params['action'] == "admin_test_device_list") || ($this->params['action'] == "admin_view") || ($this->params['action'] =="admin_edit") || ($this->params['action'] =="admin_add")|| ($this->params['action'] =="admin_delete"))&& $this->params['controller'] == "testdevice") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/testdevice/test_device_list'); ?>" class="waves-effect"><i class="mdi mdi-desktop-mac"></i><span>Manage Test Devices</span></a></li>
						<li <?php if ((($this->params['action'] == "admin_test_reports_list") || ($this->params['action'] == "admin_view"))&& $this->params['controller'] == "testreports") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/testreports/test_reports_list'); ?>" class="waves-effect"><i class="mdi mdi-view-list"></i><span>Camera Test Report</span></a></li>
						<li <?php if ((($this->params['action'] == "admin_master_reports_list") || ($this->params['action'] == "admin_view"))&& $this->params['controller'] == "masterReports") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/masterReports/master_reports_list'); ?>" class="waves-effect"><i class="mdi mdi-view-list"></i><span>Master Test Report</span></a></li>
					<?php } ?>


					<?php if($Admin['user_type'] != "SuperSubadmin") { ?>
					<!-- <?php  if((!empty($Admin) && $Admin['user_type'] == "Admin") || in_array('Contrast Sensitivity', $activeSideMenu)==1){ ?>
						<li <?php if ((($this->params['action'] == "admin_cs_reports_list") || ($this->params['action'] == "admin_view"))&& $this->params['controller'] == "csreports") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/csreports/cs_reports_list'); ?>" class="waves-effect"><i class="mdi mdi-view-list"></i><span> Contrast Sensitivity </span></a></li>
					<?php } ?>
					<?php if((!empty($Admin) && $Admin['user_type'] == "Admin") || in_array('Refractor Test', $activeSideMenu)==1){ ?>
						<li <?php if ((($this->params['action'] == "admin_reports") || ($this->params['action'] == "admin_view"))&& $this->params['controller'] == "refractors") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/refractors/reports'); ?>" class="waves-effect"><i class="mdi mdi-view-list"></i><span> Refractor Test Report</span></a></li>
					<?php }
				?> -->

			<?php } ?>


			<?php /*<li <?php if ($this->params['action'] == "admin_index" && $this->params['controller'] == "Officereports") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/Officereports/index'); ?>" class="waves-effect"><i class="mdi mdi-office"></i><span>Manage Office Reports</span></a></li>*/ ?>

			<?php if(!empty($Admin) && $Admin['user_type'] == "Admin") { ?>
				<li <?php if ($this->params['action'] == "admin_index" && $this->params['controller'] == "tests") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/tests/index'); ?>" class="waves-effect"><i class="mdi mdi-view-list"></i><span>Manage Test</span></a></li>
				<li <?php if ($this->params['action'] == "admin_index" && $this->params['controller'] == "test_names") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/test_names/index'); ?>" class="waves-effect"><i class="mdi mdi-view-list"></i><span>Manage Test Name</span></a></li>
			<?php } ?>

			<?php if(!empty($Admin) && $Admin['user_type'] == "Admin") { ?>
				<li <?php if ((($this->params['action'] == "admin_notification_list") || ($this->params['action'] == "admin_notification_delete") )&& $this->params['controller'] == "notifications") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/notifications/notification_list'); ?>" class="waves-effect"><i class="mdi mdi-message-alert"></i><span>Push Notifications</span></a></li>
				<li <?php if ((($this->params['action'] == "admin_linked_device") || ($this->params['action'] == "admin_linked_device") )&& $this->params['controller'] == "notifications") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/notifications/linked_device'); ?>" class="waves-effect"><i class="mdi mdi-message-alert"></i><span>Linked Device</span></a></li>
			<?php } ?>

				<!--<?php
				if(!empty($Admin) && ($Admin['user_type'] == "Subadmin" || $Admin['user_type'] == "OfficeAdmin")){ ?>
				<li <?php if ($this->params['action'] == "admin_purchase_credit" && $this->params['controller'] == "payments") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/payments/purchase_credit'); ?>" class="waves-effect"><i class="glyphicon glyphicon-usd"></i><span>Purchase Credits</span></a></li>
				<li <?php if ($this->params['action'] == "admin_assign_credits" && $this->params['controller'] == "staff") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/staff/assign_credits'); ?>" class="waves-effect"><i class="glyphicon glyphicon-credit-card"></i><span>Assign Credits</span></a></li>
				<?php } ?>-->

				<!--li class="has_sub"> <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-album"></i> <span>Manage Staff Users</span> <span class="pull-right"><i class="mdi mdi-plus"></i></span></a>
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
				</li-->
			  	<!--li class="has_sub"> <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-layers"></i><span> Practice Name </span><span class="pull-right"><i class="mdi mdi-plus"></i></span></a>
					<ul class="list-unstyled">
						<li><a href="form-elements.html">General Elements</a></li>
						<li><a href="form-validation.html">Form Validation</a></li>
						<li><a href="form-advanced.html">Advanced Form</a></li>
						<li><a href="form-wysiwyg.html">WYSIWYG Editor</a></li>
						<li><a href="form-uploads.html">Multiple File Upload</a></li>
					</ul>
				</li-->

				<!--li class="has_sub"> <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-table"></i><span> Test Device </span><span class="pull-right"><i class="mdi mdi-plus"></i></span></a>
					<ul class="list-unstyled">
						<li><a href="tables-basic.html">Basic Tables</a></li>
						<li><a href="tables-datatable.html">Data Table</a></li>
					</ul>
				</li-->
				<!--li class="has_sub"> <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-chart-pie"></i><span> Charts </span><span class="pull-right"><i class="mdi mdi-plus"></i></span></a>
					<ul class="list-unstyled">
						<li><a href="charts-morris.html">Morris Chart</a></li>
						<li><a href="charts-chartjs.html">Chartjs</a></li>
						<li><a href="charts-flot.html">Flot Chart</a></li>
						<li><a href="charts-other.html">Other Chart</a></li>
					</ul>
				</li-->
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
				<?php if(!empty($Admin) && $Admin['user_type']=="RepAdmin"){ ?>
					<li <?php if ($this->params['action'] == "admin_office_report") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/offices/office_report'); ?>" class="waves-effect"><i class="mdi mdi-office"></i><span>Office Report</span></a></li>
				<?php } ?>

				<?php if(!empty($Admin) && $Admin['user_type']=="Staffuser"){
					//if(CustomHelper::checkVideoModulePermission($Admin['office_id'])){ ?>
						<li <?php if ($this->params['action'] == "admin_list" && $this->params['controller'] == "video") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/video/list'); ?>" class="waves-effect"><i class="fa fa-file-video-o"></i><span>View Video</span></a></li>
						<?php
					// }
					} ?>
					<?php // copy user menu ?>
				<!--<?php if(!empty($Admin) && $Admin['user_type']=="Subadmin"){?>
					<li <?php if ($this->params['action'] == "admin_copy_user" && $this->params['controller'] == "preset") { ?>class="active_li" <?php } ?>> <a href="<?php echo $this->HTML->url('/admin/preset/copy'); ?>" class=""><i class="fa fa-copy"></i><span>Copy User Preset</span></a></li>
					<?php } ?> -->
				</ul>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<script>
		$(function(){
			$(".active_li").find('a').addClass('subdrop');
			$(".active_li").parent('ul').show();
			$(".active_li").parent('ul').siblings('a').addClass('subdrop active');
			$(".active_li").parents('.has_sub').find('span i').removeClass('mdi-plus').addClass('mdi-minus');
		});
	</script>
	<script>
		$(window).load(function() {
			$.ajax({
				url: "<?php echo WWW_BASE; ?>admin/support/unread/",
				type: 'POST',
				data: {"page": 1},
				success: function(data){
					document.getElementById("unread_support").style.visibility = "visible";
					$("#unread_support").text(data);
				}
			});
		});
	</script>
