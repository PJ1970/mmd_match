<?php $Admin = $this->Session->read('Auth.Admin'); ?>
 
<div class="content">
	<div class="">
         
      </div>
      <div class="modal-header" style="border:none;">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
</div>
	<div class="page-content-wrapper ">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-primary">
						<div class="panel-body">
							 

							<?php echo $this->Session->flash() . "<br/>";

							if (@$check_payable['Office']['payable'] == 'no' && @$check_payable['Office']['restrict'] == 'restrict'){?>
								<h2 style="color:red;text-align:center;">You don't have permission to see this. Please
									contact support: <br/>Email: support@micromedinc.com <br/>Phone : 818-222-3310</h2>
								<?php
							} else{ ?>

							 


							<div class="row">
								<div class="table-responsive col-md-12 col-sm-12 col-xs-12">
									<table id="datatable_report1" class="table table-striped table-bordered">
										<thead>
											<tr>
												<th style="width:34px;">S.No</th>
												<th>Test Date </th>
												<?php if ($Admin['user_type'] == 'Admin') { ?>
													<th>Office Name</th>
													<th>Staff User</th>
												<?php } ?>
												<th>Patient name </th>
												<th>Patient id </th>
												<th>Patient DOB</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
										<?php if (!empty($datas)) {
											foreach ($datas as $key => $data) { ?>
												<?php $ptdata_id = $data['PupTest']['id']; ?>
												<tr>
													<td data-order="<?php echo $data['PupTest']['id']; ?>"><?php echo $key + 1; ?></td>
													<td><?php echo date('d M Y h:i:s a', strtotime($data['PupTest']['created'])); ?></td>
													<?php if ($Admin['user_type'] == 'Admin') { ?>
														<td><?php echo @$data['Office']['name']; ?></td>
														<td>
														<?php echo @$data['User']['first_name'] . ' ' . @$data['User']['middle_name'] . ' ' . @$data['User']['last_name']; ?>
														</td>
													<?php } ?>
													<td>
														<!-- <a style='cursor: pointer;' title='View' patientId='<?php echo @$data['PupTest']['patient_id'] ?>' class='patient loaderAjax'><?php echo @$data['PupTest']['patient_name'] ?></a> -->
														<?php echo @$data['Patient']['first_name'].' '.$data['Patient']['last_name'] ?>
													</td>
													<td><?php echo $data['Patient']['id_number']; ?></td>
													<td><?php echo (!empty($data['Patient']['dob'])) ? date('d-m-Y', strtotime($data['Patient']['dob'])) : ''; ?></td>
													<td class="action_sec">
														<?php
														//$related_id = (!empty($download[$ptdata_id]['tr_id'])) ? $download[$ptdata_id]['tr_id'] : 'tr-none';
														?>
														 	<?php if(!empty($data['PupTest']['file'])){ ?>
															<a title="View pdf report" href="<?php echo WWW_BASE . 'app/webroot/PupTestControllerData/' . $data['PupTest']['file']; ?>" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
														<?php } ?>
													 
													</td>
												</tr>
											<?php }
											if (isset($this->params['paging']['PupTest']['pageCount'])) { ?>
											 
											<?php }
										} else {
											echo "<tr><td colspan='6' style='text-align:center;'>No record found.</td></tr>";
										} ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<?php }
					?>
				</div>
			</div>
		</div>
	</div>
</div>
 
