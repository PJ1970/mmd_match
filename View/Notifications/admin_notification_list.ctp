<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Push Notifications</h4>
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
							<th>Notification Text</th>
							<th>User Name</th>
                            <th>Created</th>
							<?php if(!empty($Admin) && $Admin['user_type']=="Admin"): ?>
                            <th class="notification">Action</th>
							<?php endif; ?>
                          </tr>
                        </thead>
                        <tbody>
						
						<?php if(!empty($user_notifications)){ foreach($user_notifications as $key=>$user_notification){   ?>
						<tr>
							<td><?php echo $key+1;?></td>
							<td><?php echo $user_notification['User']['first_name'].' '.$user_notification['User']['last_name'];?></td>
							<td><?php echo $user_notification['UserNotification']['text'];?></td>
							<td><?php echo date('d F Y H:i',strtotime($user_notification['UserNotification']['created']));?></td>
							<?php if(!empty($Admin) && $Admin['user_type']=="Admin"): ?>
							<td class="action_sec">
							
							<?php echo $this->Html->link('<i class="fa fa-trash-o"></i>',array('controller'=>'notifications','action'=>'admin_notification_delete',$user_notification['UserNotification']['id']),array('escape'=>false,'title'=>'Delete','confirm'=>'Are you sure you want to delete?'));?>
							
							</td>
							<?php endif; ?>
						</tr>
						<?php }} ?>
                         
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

	