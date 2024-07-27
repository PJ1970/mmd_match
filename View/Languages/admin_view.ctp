<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">View Language</h4>
		 
        </div>
		 
      </div>
      <div class="page-content-wrapper ">
       <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-primary">
                <div class="panel-body">
				 <?php echo $this->Session->flash()."<br/>";?>
				 
				 <div class="col-md-12" style="padding-bottom:40px;">
				 
				 <?php echo $this->Form->create('Language',array('type' => 'get','url' => array('action' => 'listing'))); ?>
					
					 <div class="col-md-4">  
					    <b> <lable>Language Name</lable><br>
					 	 <?php echo $data['Language']['name']; ?></b>
					</div>
					<div class="form-group m-b-0 col-md-4">
					    <b><lable>Code</lable><br>
						  <?php echo $data['Language']['code']; ?></b>
					</div>
					<?php echo $this->Form->end(); ?>
					<div align="right" class="col-md-4">
					    <a href="<?php echo $this->HTML->url('/admin/languages/upload/'.$data['Language']['id']); ?>" class="btn btn-large btn-primary" ><i class="fa fa-file" aria-hidden="true"></i>Upload File</a>
					      
								<h4 class="m-b-30 m-t-0"></h4>
					</div>
				  
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">  
                      <table id="" class="table table-striped table-bordered"> 
                        <thead>
                          <tr>
							<th style="width:34px;">S.No.</th>
                            <th> Name </th>
                             <th>File</th>
                             <th>Action</th>
							
                          </tr>
                        </thead>
                        <tbody>
						
						<?php if(!empty($datas))
							{ 
								foreach($datas as $key => $data){  ?> 
						<tr>
							<td data-order="<?php echo $data['LanguageFile']['id']; ?>"><?php echo $key+1; ?></td>
							<td><?php echo $data['LanguageFile']['name']; ?></td>
								<td><?php echo $data['LanguageFile']['file_name']; ?></td>
							<td class="action_sec">
						
							&nbsp;&nbsp;<?php echo $this->Html->link('<i class="fa fa-trash-o"></i>',array('controller'=>'languages','action'=>'admin_delete_file',$data['LanguageFile']['id']),array('escape'=>false,'title'=>'Delete test name','confirm'=>'Are you sure you want to delete file?'));?>
							
							</td>
						</tr>
						<?php }
						  if(isset($this->params['paging']['LanguageFile']['pageCount'])){ ?>
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
						}else{echo "<tr><td colspan='3' style='text-align:center;'>No record found.</td></tr>";} ?>
                         
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
 $('#datatable1').dataTable({
		destroy: true,
		"searching": true,
		"paging":true,
		'aoColumnDefs': [{
			'bSortable': false,
			'aTargets': [-1],
			//"asSorting": [ "DESC" ]
		}],
		 "order": [ [0, 'DESC'] ]
	});
});
 </script>         