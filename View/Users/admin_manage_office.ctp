<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Manage Sub-admin</h4>
		 
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
				 
				 
				 
				 <?php echo $this->Form->create('Office', array('novalidate' => true, 'id'=>'officeForm','method' => 'post', 'url'=>array('action'=>'manage_office')));?>
	  
				   <?php echo $this->Form->input('name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Enter a officename",'required'=>true)); ?>
			
			        <?php echo $this->Form->input('id',array('type'=>'hidden')); ?>
					<div class="form-group m-b-0">
					  <div>
					  <button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>
					  <!--<a type="reset" class="btn btn-default waves-effect m-l-5"> Cancel </a>-->
					 </div>
					</div>
				   
				  <?php echo $this->Form->end();?>
							 
							 </div>
				 
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>Office Name</th>
                             
                           
                          </tr>
                        </thead>
                        <tbody>
						
						<?php if(!empty($datas))
							{ 
								foreach($datas as $data){ ?>
						<tr>
							<td><?php echo $data['Offices']['name']; ?></td>
							<td class="action_sec">
						
							&nbsp;&nbsp;<?php echo $this->Html->link('<i class="fa fa-pencil" aria-hidden="true"></i>',array('controller'=>'users','action'=>'admin_edit', $data['Offices']['id']),array('escape'=>false,'title'=>'Edit'));?>
							&nbsp;&nbsp;<?php echo $this->Html->link('<i class="fa fa-trash-o"></i>',array('controller'=>'users','action'=>'admin_user_delete',$data['Offices']['id']),array('escape'=>false,'title'=>'Delete','confirm'=>'If you delete subAdmin its associated staff and patients will be deleted.Are you sure you want to delete?'));?>
							
							</td>
						</tr>
						<?php }}else{echo "No record found;";} ?>
                         
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
	jQuery(".SubAdminDetail").click(function() {
	jQuery('#myPleaseWait').modal('show');
 var subAdminId = jQuery(this).attr("subAdminId");
 jQuery("#subadmin_detail").load("<?php echo WWW_BASE; ?>admin/users/subAdminView/"+subAdminId+ "?" + new Date().getTime(), function(result) {
  jQuery('#myPleaseWait').modal('hide');
  jQuery("#subAdminView").modal("show");
 });
});	
});

</script>	
          