<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Manage Test Devices</h4>
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
					<?php echo $this->Form->create('TestDevice',array('type' => 'get','url' => array('controller' => 'testdevice','action' => 'test_device_list'))); ?>
					
					 <div class="col-md-4">  
						<?php echo $this->Form->input('search',array('div' => false,'label' => false,'value' => @$search,'type' =>'text','class' => 'form-control','placeholder' => 'Search','maxlength' => '100')); ?>
						 
					</div>
					<div class="form-group m-b-0 col-md-4">
						<button type="submit" class="btn btn-primary waves-effect waves-light searchBtn" > Search </button>	
					</div>
					<?php echo $this->Form->end(); ?>
					<div align="right" class="col-md-4">
						<a href="<?php echo $this->HTML->url('/admin/testdevice/add'); ?>" class="btn btn-large btn-primary" >Add Device</a>
							<h4 class="m-b-30 m-t-0"></h4>
					</div>
				</div>
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <table id="" class="table table-striped table-bordered">
                        <thead>
                          <tr>
							<th style="width: 34px;">S.No</th>
							<th> <?php echo $this->Paginator->sort('TestDevice.deviceSeraial','Serial Number'); ?> </th>
                            <th> <?php echo $this->Paginator->sort('TestDevice.name','Device Name'); ?> </th>
                            <th class="nosort"> <?php echo $this->Paginator->sort('TestDevice.ip_address','IP Address'); ?> </th>
							<th class="nosort"> <?php echo $this->Paginator->sort('TestDevice.mac_address','MAC Address'); ?>  </th>
							<th class="nosort"> <?php echo $this->Paginator->sort('TestDevice.bt_mac_address','Bluetooth MAC Address'); ?>  </th>
							<th> Office  </th>
							<th> Device Type  </th>
                            <th><?php echo $this->Paginator->sort('TestDevice.created','created'); ?></th>
							<th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
						
						<?php if(!empty($datas)) {foreach($datas as $key=>$data){ ?>
						
						<tr>
							<td data-order="<?php echo $data['TestDevice']['id']; ?>"><?php echo $key+1; ?></td>
							<td><?php  echo ($this->Session->read('Auth.Admin.user_type') == "Admin")? "<a style='cursor: pointer;'  title='View' deviceId='".$data['TestDevice']['id']."'class='device loaderAjax'>".$data['TestDevice']['deviceSeraial']."</a>": $data['TestDevice']['deviceSeraial'] ?> </td>
							<td><?php  echo ($this->Session->read('Auth.Admin.user_type') == "Admin")? "<a style='cursor: pointer;'  title='View' deviceId='".$data['TestDevice']['id']."'class='device loaderAjax'>".$data['TestDevice']['name']."</a>": $data['TestDevice']['name'] ?> 
							</td>
							<td><?php echo $data['TestDevice']['ip_address']; ?></td>
							<td><?php echo $data['TestDevice']['mac_address']; ?>
							<td><?php echo $data['TestDevice']['bt_mac_address']; ?></td>
							<td><?php echo $name=$this->custom->getOfficeName($data['TestDevice']['office_id']); ?></td>
							<td>
							<?php
							if($data['TestDevice']['device_type']==6){
							    echo 'PICO_G2_IHU';
							}else	if($data['TestDevice']['device_type']==5){
							    echo 'PICO_NEO_3';
							}else	if($data['TestDevice']['device_type']==4){
							    echo 'PICO_G2';
							}else if($data['TestDevice']['device_type']==3){
							    echo 'Quest';
							}else if($data['TestDevice']['device_type']==2){
							    echo 'PICO_NEO';
							}else if($data['TestDevice']['device_type']==1){
							    echo 'GO';
							}else{
							    echo 'Gear';
							}
						  ?></td>
							<td><?php  echo date('d F Y',strtotime($data['TestDevice']['created']));?></td>
							<td><?php echo ($data['TestDevice']['status']==1)?"Active":"Inactive"; ?></td>
							<td class="action_sec">
							
								&nbsp;&nbsp;<?php echo $this->Html->link('<i class="fa fa-pencil" aria-hidden="true"></i>',array('controller'=>'testdevice','action'=>'admin_edit', $data['TestDevice']['id']),array('escape'=>false,'title'=>'Edit'));?>
								
								&nbsp;&nbsp;<?php echo $this->Html->link('<i class="fa fa-trash-o"></i>',array('controller'=>'testdevice','action'=>'admin_delete',$data['TestDevice']['id']),array('escape'=>false,'title'=>'Delete','confirm'=>'Are you sure you want to delete?'));?>
							</td>
						</tr>
						<?php }
						  if(isset($this->params['paging']['TestDevice']['pageCount'])){ ?>
							<tr> 
								<td colspan='11' align="center" class="paginat">
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
						}else{echo "<tr><td colspan='8' style='text-align:center;'>No record found.</td></tr>";} ?>
						</tbody>
                         
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

     <div id="deviceView" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content" id="deviceContent">
				</div>
			</div>
		</div>


<script>
$(document).ready(function (){
/* $('#datatable_device').dataTable({
		destroy: true,
		"searching": true,
		"paging":true,
		'aoColumnDefs': [{
			'bSortable': false,
			'aTargets': [-1]
		}],
		 "order": [ [0, 'DESC'] ]
	});*/

jQuery(document).on("click",".loaderAjax",function() {
		$('body').append('<div class="customFacebox" id="facebox" style="top: 70.8px; left: 475.5px;"><div class="popup popup56"><div class="content" style="padding: 45px"><div class="loading"><p style="color:#00aaff;"><b>Processing........Please do not click anywhere on the page until the process is complete.</b></p><img src="'+ajax_url+'img/ajaxloader.gif"></div> </div></div></div>');
		 
	});	
 jQuery(document).on("click",".device",function() {
		var deviceId = jQuery(this).attr("deviceId");
		
		jQuery("#deviceContent").load("<?php echo WWW_BASE; ?>admin/testdevice/view/"+deviceId+ "?office=1&" + new Date().getTime()+ new Date().getMilliseconds(), function(result) {
		
			jQuery("#deviceView").modal("show");
			$('.customFacebox').remove();
		 });
	});
});
 </script>     