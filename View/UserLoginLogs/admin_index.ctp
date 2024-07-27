
<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">User Login/Logout Logs</h4>
        </div>
		 <?php $Admin = $this->Session->read('Auth.Admin'); ?>
      </div>
      <div class="page-content-wrapper ">
       <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-primary">
                <div class="panel-body">
				<?php echo $this->Session->flash()."<br/>";?>
					<div class="col-md-12">
						<?php echo $this->Form->create('User',array('type' => 'get','url' => array('controller' => 'user_login_logs','action' => 'index'))); ?>
						
						 <div class="col-md-4">  
							<?php echo $this->Form->input('search',array('div' => false,'label' => false,'value' => @$search,'type' =>'text','class' => 'form-control','placeholder' => 'Search','maxlength' => '100')); ?>
							 
						</div>
						<?php  if($Admin['user_type']=="Admin") { ?>
						<div class="col-md-4">
							<?php
							$options=$this->custom->getOfficeList();
								 
								echo $this->Form->input('office_id',array('options' =>$options,'id'=>'office_id','empty'=>'Select Office','div'=>false,'legend'=>false, 'required' => false, 'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3','default'=>$office_id));
							?>
						</div>
					<?php } ?>
						<div class="form-group m-b-0 col-md-4">
							<button type="submit" class="btn btn-primary waves-effect waves-light searchBtn" > Search </button>	
						</div>
						<?php echo $this->Form->end(); ?> 
					</div>
					<br><br><br>
					  <div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
						  <table id="" class="table table-striped table-bordered">
							<thead>
							  <tr>
								<th style="width:34px;">S.No</th>
								<?php  if($Admin['user_type']=="Admin") { ?>
								<th> <?php echo $this->Paginator->sort('Office.name','Office Name'); ?></th>
								<?php } ?>
								<th> <?php echo $this->Paginator->sort('UserLoginLog.username','User Name'); ?></th>
								<th> <?php echo $this->Paginator->sort('UserLoginLog.UTCtimestamp','Time'); ?></th>
								<th> <?php echo $this->Paginator->sort('UserLoginLog.action','Action'); ?></th>
								<th> <?php echo $this->Paginator->sort('UserLoginLog.ip_address','IP Address'); ?></th>
								<th> <?php echo $this->Paginator->sort('UserLoginLog.source','Source'); ?></th>
								 
							  </tr>
							</thead>
							<tbody>
							
							<?php if(!empty($datas)) {foreach($datas as $key=>$data){ ?>
							<tr>
								<td data-order="<?php echo $data['UserLoginLog']['id']; ?>"><?php echo $key+1; ?></td>
								<?php  if($Admin['user_type']=="Admin") { ?>
								<td><?php echo @$data['Office']['name']; ?></td>
								<?php } ?>
								<td><?php echo $data['UserLoginLog']['username']; ?></td>
								<td><?php echo $data['UserLoginLog']['UTCtimestamp']; ?></td>
								<td><?php echo $data['UserLoginLog']['action'];?></td>		
								<td><?php echo $data['UserLoginLog']['ip_address']; ?></td>
								<td><?php echo $data['UserLoginLog']['source']; ?></td>
							</tr>
							<?php }
							  if(isset($this->params['paging']['UserLoginLog']['pageCount'])){ ?>
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
							}else{
									if($Admin['user_type']=="Admin") {
									echo "<tr><td colspan='7' style='text-align:center;'>No record found.</td></tr>";
								}else{
									echo "<tr><td colspan='6' style='text-align:center;'>No record found.</td></tr>";
								}
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
          