<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Linked Device</h4>
		  <?php echo $this->Session->flash();?>
        </div>
		 
      </div>
      <div class="page-content-wrapper ">
       <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-primary">
                <div class="panel-body">
				<div class="notifiction_block">
					<div style="margin-bottom: 2%;">
						<h5><!--<a href="<?php echo WWW_BASE.'admin/notifications/notification_send/'; ?>" rel="facebox">New Notification</a>-->
						<!--<a href="<?php echo $this->HTML->url('/admin/notifications/notification_send/'); ?>" class="btn btn-large btn-primary" >Push Notification</a> </h5> -->
					</div>
				</div>
				 <?php $Admin = $this->Session->read('Auth.Admin');?>
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <table id="datatable_1" class="table table-striped table-bordered">
                        <thead>
                          <tr>
							<th style="width:34px;">S.No</th>
							<th>Test Controller(MAC Address)</th>
							<th>Test device IP address</th>
                        </thead>
                        <tbody>
						
						<?php if(!empty($new_user_devices)){ 
							foreach($new_user_devices as $key=>$new_user_device){
								//pr($new_user_device);die;
							?>
								<tr>
									<td><?php echo $key+1;?></td>
									<td><?php echo $new_user_device['NewUserDevices']['device_id'];?></td>
									<td><?php echo $new_user_device['NewUserDevices']['ip_address'];?></td>
								</tr>
							<?php }
							} ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </div>
        </div>
     </div>

	