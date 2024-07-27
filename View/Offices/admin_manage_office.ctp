<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Offices Listing</h4>
		 
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
					<?php echo $this->Form->create('Office',array('type' => 'get','url' => array('controller' => 'offices','action' => 'manage_office'))); ?>
					
					 <div class="col-md-4">  
						<?php echo $this->Form->input('search',array('div' => false,'label' => false,'value' => @$search,'type' =>'text','class' => 'form-control','placeholder' => 'Search','maxlength' => '100')); ?>
						 
					</div>
					<div class="form-group m-b-0 col-md-4">
						<button type="submit" class="btn btn-primary waves-effect waves-light searchBtn" > Search </button>	
					</div>
					<?php echo $this->Form->end(); ?>
					<div align="right" class="col-md-4">
						<a href="<?php echo $this->HTML->url('/admin/offices/add'); ?>" class="btn btn-large btn-primary" >Add Office</a>
						<a href="<?php echo $this->HTML->url('/admin/offices/export'); ?>" class="btn btn-large btn-primary" >Export</a>
						<a href="<?php echo $this->HTML->url('/admin/offices/export_device'); ?>" class="btn btn-large btn-primary" >Export  <i class="fa fa-tablet" aria-hidden="true"></i>
</a>
							<h4 class="m-b-30 m-t-0"></h4>
					</div>
				</div>
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <table id=" " class="table table-striped table-bordered">
                        <thead>
                          <tr>
							<th style="width:34px;">S.No</th>
                            <th>  <?php echo $this->Paginator->sort('Office.name','Office Name'); ?> </th>
                            <th>  <?php echo $this->Paginator->sort('Office.email','Office Email'); ?> </th>
                            <th>  <?php echo $this->Paginator->sort('Office.second_email','Office second_email'); ?> </th>
							<!-- <th>  <?php echo $this->Paginator->sort('Office.per_use_cost','Per Use Cost'); ?> </th> -->
							<th>  <?php echo $this->Paginator->sort('Office.credits','Credits'); ?> </th>
							<th>Rep Admin name</th>
                          <!--   <th>  <?php echo $this->Paginator->sort('Office.address','Office Address'); ?> </th> -->
							<th>  <?php echo $this->Paginator->sort('Office.created','Created Date'); ?> </th>
							<th>Status</th>
                            <th>Action</th>
                           
                          </tr>
                        </thead>
                        <tbody>
						
						<?php if(!empty($datas))
							{ 
								foreach($datas as $key => $data){  ?>
						<tr> 
							<td data-order="<?php echo $data['Office']['id']; ?>"><?php echo $key+1; ?></td>
							<td><?php echo $data['Office']['name']; ?></td>
							<td><?php echo $data['Office']['email']; ?></td>
							<td><?php echo $data['Office']['second_email']; ?></td>
							<!-- <td><?php echo $data['Office']['per_use_cost']; ?></td> -->
							<td><?php echo $data['Office']['credits']; ?></td>
							<td><?php echo $data['User']['complete_name']; ?></td>
							<!-- <td><?php echo $data['Office']['address']; ?></td> -->
							<td><?php echo date('Y-m-d', strtotime($data['Office']['created'])); ?></td>
							<td><?php $status_data = ($data['Office']['status']==1)?"Active":"Inactive"; echo "<a href=".WWW_BASE."admin/offices/changeStatus/".$data['Office']['id']." class=".$status_data.">".$status_data."</a>"; ?></td>
							<td class="action_sec">
							&nbsp;&nbsp;<?php echo $this->Html->link('<i class="fa fa-credit-card" aria-hidden="true"></i>',array('controller'=>'offices','action'=>'admin_assign_credit', $data['Office']['id']),array('escape'=>false,'title'=>'Assign Credit'));?>
							&nbsp;&nbsp;<?php echo $this->Html->link('<i class="fa fa-pencil" aria-hidden="true"></i>',array('controller'=>'offices','action'=>'admin_add', $data['Office']['id']),array('escape'=>false,'title'=>'Edit Office'));?>
							&nbsp;&nbsp;<?php echo $this->Html->link('<i class="fa fa-trash-o"></i>',array('controller'=>'offices','action'=>'admin_office_delete',$data['Office']['id']),array('escape'=>false,'title'=>'Delete','confirm'=>'Are you sure you want to delete?'));?>
							 
							</td>
						</tr>
						<?php }
						  if(isset($this->params['paging']['Office']['pageCount'])){ ?>
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
$(document).ready(function (){
$('#datatable_office').dataTable({
		destroy: true,
		"searching": true,
		"paging":true,
		'aoColumnDefs': [{
			'bSortable': false,
			'aTargets': [-1,'notification','nosort'],
			//"asSorting": [ "desc" ]
		}],
		"order": [ [0, 'desc'] ]
	});
});
 </script>  
