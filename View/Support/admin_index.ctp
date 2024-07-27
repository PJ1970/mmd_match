<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Support Ticket Listing</h4>
		 
        </div>
		 
      </div>
      <div class="page-content-wrapper ">
       <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-primary">
                <div class="panel-body">
				 <?php echo $this->Session->flash()."<br/>";?>
				 
			     
				<div class="col-md-12" style="padding-bottom: 20px;" id="add_apk">
				 	 <?php echo $this->Form->create('Support',array('type' => 'get','url' => array('controller'=>'support','action' => 'index'))); ?>
					
					 <div class="col-md-3">  
						<?php echo $this->Form->input('search',array('div' => false,'label' => false,'value' => @$search,'type' =>'text','class' => 'form-control','placeholder' => 'Search','maxlength' => '100')); ?>
						 
					</div>
					<div class="form-group m-b-0 col-md-2">
						<button type="submit" class="btn btn-primary waves-effect waves-light searchBtn" > Search </button>	
					</div>
                    <?php //echo $this->Session->read('Auth.Admin.user_type') ;?>
                    <?php if($this->Session->read('Auth.Admin.user_type')=='Admin'){ ?>
					<div class="col-md-2">  
					 <label>Search by Category</label>
							<?php echo $this->Form->input('category',array('options' =>$category,'type'=>'select','empty'=>'Select Category','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3')); ?>
					</div>
					<div class="col-md-2">  
					 <label>Search by Status</label>
						<?php $type_option = array('Closed'=>'Closed','Open'=>'Open'); ?>
							<?php echo $this->Form->input('status',array('options' =>$type_option,'empty'=>'Select Status','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3')); ?>
					</div>
                    <?php } ?>  

					<?php echo $this->Form->end(); ?>
					       
					<div class="form-group m-b-0 col-md-2">
					    <?php if($this->Session->read('Auth.Admin.user_type')=='Subadmin' || 1){ ?>
					    <a href="<?php echo $this->HTML->url('/admin/support/add'); ?>" class="btn btn-large btn-primary" >Add new Ticket</a>
					 <?php } ?>
					</div>
					<?php if($this->Session->read('Auth.Admin.user_type')=='Admin'){ ?>
					 <a href="<?php echo $this->Html->url(['controller'=>'Support','action'=>'exportall']); ?>" ><i style='font-size: 33px;'class="fa fa-file-excel-o" ></i></a>
				 <?php } ?>
					<div align="right" class="col-md-4">
						
						<h4 class="m-b-30 m-t-0"></h4>
					</div>
					
				</div>
				
			 
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <table id=" " class="table table-striped table-bordered">
                        <thead>
                          <tr>
							<th style="width:34px;">S.No</th>
							<th>Reference No</th>
							<th>Device serial No</th>
							<th>Model</th>
							<th>Office name</th>
                            <th>User name</th>
                            <th>Title</th>
                            <?php if($this->Session->read('Auth.Admin.user_type')=='Admin' || $this->Session->read('Auth.Admin.user_type')=='SupportSuperAdmin'){ ?>
							<th>Category</th>
							<?php } ?>
							<th>Status</th>
							<th>Created at</th>
                          
                            <th>Action</th>
                           
                          </tr>
                        </thead>
                        <tbody>
						
						<?php if(!empty($datas))
							{ 
								foreach($datas as $key => $data){ //pr($data);   ?>
						<tr>
							<td data-order="<?php echo $data['Support']['id']; ?>"><?php echo $key+1; ?></td>
								<td> <?php echo $this->Html->link($data['Support']['refrance_no'],array('controller'=>'Support','action'=>'admin_view', $data['Support']['id']),array('escape'=>false,'title'=>'View ticket', 'target'=>'blanck'));?></td>
								<td><?php echo $data['Support']['device_serial_no']; ?></td>
								<td><?php echo $data['Support']['model']; ?></td>
								<td><?php echo $data['Office']['name']; ?></td>
							<td><?php echo $data['User']['first_name'].' '.$data['User']['middle_name'].' '.$data['User']['last_name']; ?></td>
							<td><?php echo $data['Support']['title']; ?></td>
							<?php if($this->Session->read('Auth.Admin.user_type')=='Admin' || $this->Session->read('Auth.Admin.user_type')=='SupportSuperAdmin'){ ?>
							<td><?php echo $data['Category']['name']; ?></td>
							<?php } ?>
							<td><b><?php echo $data['Support']['status']; ?></b>
							<?php 
							if($data['Support']['status']=='Closed'){
							     echo '<br>Closed by: '; 
					    	            echo $data['CloseUser']['first_name'].' '.$data['CloseUser']['middle_name'].' '.$data['CloseUser']['last_name']; 
							    echo '<br>'.$data['Support']['closed_at'];
							}
							?>
							
							</td>
							<td><?php echo date('Y-m-d H:i:s', strtotime($data['Support']['created_at'])); ?></td>
							
							<td>
								<a href="<?php echo $this->Html->url(['controller'=>'Support','action'=>'export',$data['Support']['id']]); ?>" ><i class="fa fa-file-excel-o" ></i></a>
								 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
							   <?php echo $this->Html->link('<i class="fa fa-eye" aria-hidden="true"></i>',array('controller'=>'Support','action'=>'admin_view', $data['Support']['id']),array('escape'=>false,'title'=>'View', 'target'=>'blanck'));?>
							    <?php if($data['Support']['status']=='Open'){ ?>
						   
						     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $this->Html->link('<i class="fa fa-reply" aria-hidden="true"></i>',array('controller'=>'Support','action'=>'admin_reply', $data['Support']['id']),array('escape'=>false,'title'=>'Reply'));?>
						     
                          
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="javascript:void(0);"  onClick="setTicketId(<?php echo $data['Support']['id'] ?>)"  data-toggle="modal" data-target="#myModal"><i class="fa fa-check-circle" aria-hidden="true"></i></a>
                          
                            <?php } ?>
							 <?php if($data['Support']['total_message']>$data['Support']['total_message_read']){
                               echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="width: 20px;  height: 20px; border-radius: 50%; background: #3292e0; color: #fff; display: inline-block; text-align: center;  line-height: 20px; font-size: 12px; font-weight: 500;" >'.($data['Support']['total_message']-$data['Support']['total_message_read']).'</span>'; 
                          } ?>
						  
						  <?php if($this->Session->read('Auth.Admin.user_type')=='Admin' || $this->Session->read('Auth.Admin.user_type')=='SupportSuperAdmin'){ 
								 echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$this->Html->link('<i class="fa fa-pencil" aria-hidden="true"></i>',array('controller'=>'Support','action'=>'admin_edit', $data['Support']['id']),array('escape'=>false,'title'=>'Edit', 'target'=>'blanck'));
							} ?>
							</td>
						</tr>
						<?php }
						  if(isset($this->params['paging']['Support']['pageCount'])){ ?>
							<tr> 
								<td colspan='10' align="center" class="paginat">
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
						}else{echo "<tr><td colspan='10' style='text-align:center;'>No record found.</td></tr>";} ?>
                         
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

    <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
       <?php echo $this->Form->create('Support', array('novalidate' => true,'url'=>array('controller'=>'support','action'=>'admin_closed'),'enctype'=>'multipart/form-data'));?>

      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Enter your comment</h4>
        </div>
        <div class="modal-body">
        	<?php echo $this->Form->input('message',array('type'=>'textarea','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Please Enter your comment",'required'=>true)); ?>

        	<?php echo $this->Form->input('parent_id',array('type'=>'hidden','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Content",'required'=>true)); ?> 
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Close Ticket</button>
        </div>
      </div>
      
    </div>
  </div>
   
<script>
	function setTicketId(argument) {
		$("#SupportParentId").val(argument);
	}
	//CKEDITOR.replace( 'data[Support][message]' );
$(document).ready(function (){
 
});

 
 </script>         