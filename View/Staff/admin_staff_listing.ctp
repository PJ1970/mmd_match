
<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Manage Staff</h4>
        </div>
		 
      </div>
      <div class="page-content-wrapper ">
       <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-primary">
                <div class="panel-body">
				<?php echo $this->Session->flash()."<br/>";?>
					<div class="col-md-12">
						<?php echo $this->Form->create('User',array('type' => 'get','url' => array('controller' => 'staff','action' => 'staff_listing'))); ?>
						
						 <div class="col-md-4">  
							<?php echo $this->Form->input('search',array('div' => false,'label' => false,'value' => @$search,'type' =>'text','class' => 'form-control','placeholder' => 'Search','maxlength' => '100')); ?>
							 
						</div>
						<div class="form-group m-b-0 col-md-4">
							<button type="submit" class="btn btn-primary waves-effect waves-light searchBtn" > Search </button>	
						</div>
						<?php echo $this->Form->end(); ?>
						<div align="right" class="col-md-4">
							<a href="<?php echo $this->HTML->url('/admin/staff/add'); ?>" class="btn btn-large btn-primary" >Add Staff</a><br/>
							<h4 class="m-b-30 m-t-0"></h4>
						</div>
					</div>
					  <div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
						  <table id="" class="table table-striped table-bordered">
							<thead>
							  <tr>
								<th style="width:34px;">S.No</th>
								<th> <?php echo $this->Paginator->sort('User.first_name','First Name'); ?></th>
								<th> <?php echo $this->Paginator->sort('User.last_name','Last Name'); ?></th>
								<th> <?php echo $this->Paginator->sort('User.email','Email'); ?></th>
								<th> <?php echo $this->Paginator->sort('User.username','Username'); ?></th>
								<th> <?php echo $this->Paginator->sort('Office.name','Office Name'); ?></th>
								<th>Actions</th>
							  </tr>
							</thead>
							<tbody>
							
							<?php if(!empty($datas)) {foreach($datas as $key=>$data){ ?>
							<tr>
								<td data-order="<?php echo $data['User']['id']; ?>"><?php echo $key+1; ?></td>
								<td><?php echo $data['User']['first_name']; ?></td>
								<td><?php echo $data['User']['last_name']; ?></td>
								<td><?php echo $data['User']['email'];?></td>		
								<td><?php echo $data['User']['username']; ?></td>
								<td><?php echo @$data['Office']['name']; ?></td>
								
								<td class="action_sec">
								<a type="button" title="View" subAdminId="<?php echo $data['User']['id'];?>" class="SubAdminDetail" data-toggle="modal" data-target="#subAdminView"><i class="fa fa-eye" aria-hidden="true"></i></a>
								
							&nbsp;&nbsp;	<?php echo $this->Html->link('<i class="fa fa-pencil" aria-hidden="true"></i>',array('controller'=>'staff','action'=>'admin_edit', $data['User']['id']),array('escape'=>false,'title'=>'Edit'));?>
							&nbsp;&nbsp;	<?php echo $this->Html->link('<i class="fa fa-trash-o"></i>',array('controller'=>'staff','action'=>'admin_delete',$data['User']['id']),array('escape'=>false,'title'=>'Delete','confirm'=>'If you delete staff its associated patients will be assigned to related Admin/Subadmin. Are you sure you want to delete?'));?>
							
							<?php $Admin = $this->Session->read('Auth.Admin'); if($Admin['user_type']=="Admin") { ?>
							
							&nbsp;&nbsp; <?php echo $this->Html->link('<i class="fa fa-bell-o"></i>',array('controller'=>'notifications','action'=>'admin_notification_send',$data['User']['id']),array('escape'=>false,'title'=>'Push Notification'));?>
							
							&nbsp;&nbsp; <?php echo $this->Html->link('<i class="fa fa-key"></i>',array('controller'=>'staff','action'=>'admin_resetpassword',$data['User']['id']),array('escape'=>false,'title'=>'Reset Password'));
							
							} ?>
							
								</td>
							</tr>
							<?php }
							  if(isset($this->params['paging']['User']['pageCount'])){ ?>
								<tr> 
									<td colspan='9' align="center" class="paginat">
										<div class="pagi_nat">
										 <!-- Shows the next and previous links -->
										 <?php echo $this->Paginator->prev('<'); ?>
										 <?php echo $this->Paginator->numbers(
											 array(
											  'separator'=>''
											  )
											  ); ?>
										 <?php echo $this->Paginator->next('>'); ?><br>
										 <!-- prints X of Y, where X is current page and Y is number of pages -->
										 </div>
										<div class="pagi"><?php echo $this->Paginator->counter();echo "&nbsp Page"; ?></div>
									</td>
								</tr>
							<?php }  
							}else{echo "<tr><td colspan='7' style='text-align:center;'>No record found.</td></tr>";} ?>
							 
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
                        progress-bar-striped active"
                             style="width: 100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
jQuery(document).ready(function(){
	jQuery(document).on('click',".SubAdminDetail",function() {
	//jQuery('#myPleaseWait').modal('show');
 var subAdminId = jQuery(this).attr("subAdminId");
 jQuery("#subadmin_detail").load("<?php echo WWW_BASE; ?>admin/staff/staffView/"+subAdminId+ "?" + new Date().getTime(), function(result) {
  //jQuery('#myPleaseWait').modal('hide');
  jQuery("#subAdminView").modal("show");
 });
});	
});

</script>	
          