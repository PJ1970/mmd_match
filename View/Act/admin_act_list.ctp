<?php $Admin = $this->Session->read('Auth.Admin'); ?>
<?php //pr($datas); die; ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div class="content">
	<div class="">
		<div class="page-header-title">
			<h4 class="page-title">ACT Test Report</h4>
		</div>
	</div>
	<div class="page-content-wrapper ">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-primary">
						<div class="panel-body">
							<?php if (isset($credit_expire)){
								//pr($check_payable);die; // $check_payable['Office']['payable'] =='no' && $check_payable['Office']['restrict'] =='restrict'
								if ($check_payable['Office']['payable'] == 'no' && $check_payable['Office']['restrict'] == 'restrict') {?>
									<h2 style="color:red;text-align:center;">You don't have permission to see this.
										Please contact support: <br/>Email: support@micromedinc.com <br/>Phone :
										818-222-3310</h2>
								<?php } else {
									if ($Admin['user_type'] == "Staffuser") {?>
										<h2 style="color:red;text-align:center;">Your Credit has expired. Please contact
											to your office.</h2>
										<?php
									} else {?>
										<h2 style="color:red;text-align:center;">You are out of credit please contact
											Micro Medical office<br/>Email: support@micromedinc.com <br/>Phone :
											818-222-3310</h2>
									<?php }
								} ?>
							<?php }else{ ?>

							<?php echo $this->Session->flash() . "<br/>";

							if (@$check_payable['Office']['payable'] == 'no' && @$check_payable['Office']['restrict'] == 'restrict'){?>
								<h2 style="color:red;text-align:center;">You don't have permission to see this. Please
									contact support: <br/>Email: support@micromedinc.com <br/>Phone : 818-222-3310</h2>
								<?php
							} else{ ?>

							<?php $Admin = $this->Session->read('Auth.Admin'); ?>

							<div class="col-md-12 form-group">
								<?php echo $this->Form->create('ActTest', array('type' => 'get', 'url' => array('controller' => 'act', 'action' => 'act_list'))); ?>

								<div class="col-md-4">
									<?php echo $this->Form->input('search', array('div' => false, 'label' => false, 'value' => @$search, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Search', 'maxlength' => '100')); ?>
								</div>

								<div class="form-group m-b-0 col-md-4">
									<button type="submit" class="btn btn-primary waves-effect waves-light searchBtn">
										Search
									</button>
								</div>
								<?php echo $this->Form->end(); ?>
								<div align="right" class="col-md-4">
									<h4 class="m-b-30 m-t-0"></h4>
								</div>
							</div>


							<div class="row">
								<div class="table-responsive col-md-12 col-sm-12 col-xs-12">
									<table id="datatable_report1" class="table table-striped table-bordered">
										<thead>
											<tr>
												<th style="width:34px;">S.No</th>
												<th><?php echo $this->Paginator->sort('ActTest.created', 'Test Date'); ?> </th>
												<?php if ($Admin['user_type'] == 'Admin') { ?>
													<th>Office Name</th>
													<th><?php echo $this->Paginator->sort('ActTest.staff_name', 'Staff User'); ?> </th>
												<?php } ?>
												<th><?php echo $this->Paginator->sort('ActTest.patient_name', 'Patient name'); ?> </th>
												<th><?php echo $this->Paginator->sort('Patient.id_number', 'Patient id'); ?> </th>
												<th>Patient DOB</th>
												<th>Test name</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
										<?php if (!empty($datas)) {
											foreach ($datas as $key => $data) { //pr($data);?>
												<?php $ptdata_id = $data['ActTest']['id']; ?>
												<tr>
													<td data-order="<?php echo $data['ActTest']['id']; ?>"><?php echo $key + 1; ?></td>
													<td><?php echo date('d M Y h:i:s a', strtotime($data['ActTest']['created'])); ?></td>
													<?php if ($Admin['user_type'] == 'Admin') { ?>
														<td><?php echo @$data['Office']['name']; ?></td>
														<td>
															<a style='cursor: pointer;' title='View' staffId='<?php echo @$data['ActTest']['staff_id'] ?>' class='staff loaderAjax'><?php echo @$data['User']['first_name'] . ' ' . @$data['User']['middle_name'] . ' ' . @$data['User']['last_name']; ?></a>
														</td>
													<?php } ?>
													<td>
														<!-- <a style='cursor: pointer;' title='View' patientId='<?php echo @$data['ActTest']['patient_id'] ?>' class='patient loaderAjax'><?php echo @$data['ActTest']['patient_name'] ?></a> -->
														<a style='cursor: pointer;' title='View' staffId='<?php echo @$data['ActTest']['staff_id'] ?>' class='staff loaderAjax'><?php echo @$data['Patient']['first_name'] . ' ' . @$data['Patient']['middle_name'] . ' ' . @$data['Patient']['last_name']; ?></a>
													</td>
													<td><?php echo $data['Patient']['id_number']; ?></td>
													<td><?php echo (!empty($data['Patient']['dob'])) ? date('d-m-Y', strtotime($data['Patient']['dob'])) : ''; ?></td>
													<td><?php echo $data['ActTest']['test_name']; ?></td>
													<td class="action_sec">
														<?php
														//$related_id = (!empty($download[$ptdata_id]['tr_id'])) ? $download[$ptdata_id]['tr_id'] : 'tr-none';
														?>
														<?php
														echo "<a style='cursor: pointer;'  title='View' testreportId='" . $data['ActTest']['id'] . "' class='testreport loaderAjax' ><i class='fa fa-eye' aria-hidden='true'></i></a>";
														?>
														<!--<a data-type="pdf" data-related='<?php //echo $related_id; ?>' data-downloads='<?php //echo json_encode($download[$ptdata_id]); ?>'  title='Download OS/OD PDF Reports' href="javascript:;" class="vbs-popover"><i class="fa fa-download"></i></a> -->
														<?php if(!empty($data['ActTest']['file'])){ ?>
															<a title="View pdf report" href="<?php echo WWW_BASE . 'app/webroot/ActTestControllerData/' . $data['ActTest']['file']; ?>" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
														<?php } ?>
														<!--<a data-type="dicom" data-related='<?php //echo $related_id; ?>' data-downloads='<?php //echo json_encode($download[$ptdata_id]); ?>' title='Download OS/OD Dicom Reports' href="javascript:;" class="vbs-popover" ><i class="fa fa-download"></i></a>-->
														<!-- <a title="Download Dicom Report" href="<?php //echo $this->Html->url(['controller' => 'unityreports', 'action' => 'exportDicomda', $data['Act']['patient_id'], $data['Act']['file']]); ?>" target="_blank"><i class="fa fa-image" aria-hidden="true"></i></a>-->
														&nbsp;&nbsp;&nbsp;
														<!--a title='Download DICOM Report' href="<?php //echo $this->Html->url(['controller' => 'unityreports', 'action' => 'exportImageStb', $data['Act']['file']]); ?>" target="_blank"><i class="fa fa-image" aria-hidden="true"></i></a-->
														<?php //pr($Admin); die;
														if (!empty($Admin) && ($Admin['user_type'] == "Subadmin" || $Admin['user_type'] == "Admin")) {
															echo "<a href='".WWW_BASE."admin/act/delete/" . $data['ActTest']['id'] . "' title='Delete' onclick='if (confirm(&quot;Are you sure you want to delete?&quot;)) { return true; } return false;'><i class='fa fa-trash-o'></i></a>";
														}
														?>
													</td>
												</tr>
											<?php }
											if (isset($this->params['paging']['ActTest']['pageCount'])) { ?>
												<tr>
													<td colspan='15' align="center" class="paginat">
														<div class="pagi_nat">
															<!-- Shows the next and previous links -->
															<?php echo $this->Paginator->prev('<'); ?>
															<?php echo $this->Paginator->numbers(
																	array('separator' => '')
															); ?>
															<?php echo $this->Paginator->next('>'); ?><br>
															<!-- prints X of Y, where X is current page and Y is number of pages -->
														</div>
														<div class="pagi"><?php echo $this->Paginator->counter();
															echo "&nbsp Page"; ?></div>
													</td>
												</tr>
											<?php }
										} else {
											echo "<tr><td colspan='15' style='text-align:center;'>No record found.</td></tr>";
										} ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<?php }
					} ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="reportView" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="reportContent">
		</div>
	</div>
</div>
<div id="patientView" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" id="patientContent">
		</div>
	</div>
</div>

<div id="staffView" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" id="staffContent">
		</div>
	</div>
</div>

<div class="modal fade bs-example-modal-sm" id="myPleaseWait" tabindex="-1" role="dialog" aria-hidden="true"
	 data-backdrop="static">
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
                        progress-bar-striped active"
						 style="width: 100%">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('body').append('<div class="facebox" id="facebox" style="top: 70.8px; left: 475.5px;"><div class="popup popup56"><div class="content" style="padding: 45px"><div class="loading"><p style="color:#00aaff;"><b>Processing........Please do not click anywhere on the page until the process is complete.</b></p><img src="' + ajax_url + 'img/ajaxloader.gif"></div> </div></div></div>');
	jQuery(document).ready(function () {
		//$('a[rel*=facebox]').facebox();
		jQuery('.facebox').remove();
		jQuery(document).on("click", ".testreport", function () {
			var testreportId = jQuery(this).attr("testreportId");
			jQuery("#reportContent").load("<?php echo WWW_BASE; ?>admin/act/view/" + testreportId + "?" + new Date().getTime() + new Date().getMilliseconds(), function (result) {
				jQuery("#reportView").modal("show");
				$('.customFacebox').remove();
			});
		});
		jQuery(document).on("click", ".loaderAjax", function () {
			$('body').append('<div class="customFacebox" id="facebox" style="top: 70.8px; left: 475.5px;"><div class="popup popup56"><div class="content" style="padding: 45px"><div class="loading"><p style="color:#00aaff;"><b>Processing........Please do not click anywhere on the page until the process is complete.</b></p><img src="' + ajax_url + 'img/ajaxloader.gif"></div> </div></div></div>');

		});


		jQuery(document).on("click", ".patient", function () {
			var patientId = jQuery(this).attr("patientId");
			jQuery("#patientContent").load("<?php echo WWW_BASE; ?>admin/patients/view/" + patientId + "?" + new Date().getTime() + new Date().getMilliseconds(), function (result) {
				jQuery("#patientView").modal("show");
				$('.customFacebox').remove();
			});
		});

		jQuery(document).on("click", ".staff", function () {
			var staffId = jQuery(this).attr("staffId");
			jQuery("#staffContent").load("<?php echo WWW_BASE; ?>admin/staff/staffView/" + staffId + "?" + new Date().getTime() + new Date().getMilliseconds(), function (result) {
				jQuery("#staffView").modal("show");
				$('.customFacebox').remove();
			});
		});


	});
	jQuery(document).ready(function () {
		jQuery('.vbs-popover').hover(function () {
			var related = jQuery(this).attr("data-related");
			jQuery(".tr-ptdata").removeClass("active");
			jQuery(this).parents('tr').addClass("active");
			jQuery("#" + related).addClass("active");

		}, function () {
			jQuery(".tr-ptdata").removeClass("active");
		});

		jQuery('.vbs-popover').click(function () {
			var downloads = jQuery.parseJSON(jQuery(this).attr("data-downloads"));
			var type = jQuery(this).attr("data-type");
			downloadAll(downloads[type], downloads['filename']);
		});

		function downloadAll(urls, filenames) {
			var link = document.createElement('a');
			link.style.display = 'none';
			document.body.appendChild(link);
			for (var i = 0; i < urls.length; i++) {
				var url = urls[i];
				var filename = filenames[i]; // url.substring(url.lastIndexOf('/')+1);
				link.setAttribute('download', filename);
				link.setAttribute('href', urls[i]);
				link.click();
			}
			document.body.removeChild(link);
		}

		jQuery('.facebox').remove();
		jQuery(document).on("click", ".Pointdata", function () {
			var PointdataId = jQuery(this).attr("PointdataId");
			jQuery("#reportContent").load("<?php echo WWW_BASE; ?>admin/unityreports/view/" + PointdataId + "?" + new Date().getTime() + new Date().getMilliseconds(), function (result) {
				jQuery("#reportView").modal("show");
				$('.customFacebox').remove();
			});
		});
	});

</script>
