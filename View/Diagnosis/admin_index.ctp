<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Manage Diagnosis</h4>
		 
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
				 
				 <div class="col-md-6">
				
				 <?php  echo $this->Form->create('Diagnosi', array('novalidate' => true, 'id'=>'officeForm','method' => 'post', 'url'=>array('action'=>'index')));?>
	                
				   <?php echo $this->Form->input('name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Enter diagnosis name",'required'=>true)); ?>
				</div>
				 <div class="col-md-2">
				 <?php $type_option = array('0'=>'Ocular Diagnosis','1'=>'Neuro Diagnosis'); ?>
                                <?php echo $this->Form->input('type',array('options' =>$type_option,'empty'=>'Select Type','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3')); ?>
			        <?php echo $this->Form->input('id',array('type'=>'hidden')); ?>
				  </div>
					<div class="form-group m-b-0 col-md-4">
					  <div>
					  <button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>
					  <!--<a type="reset" class="btn btn-default waves-effect m-l-5"> Cancel </a>-->
					 </div>
					</div>
				   
				  <?php echo $this->Form->end();?>
							 
					
					</div>
				  
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">  
                      <table id="" class="table table-striped table-bordered"> 
                        <thead>
                          <tr>
							<th style="width:34px;">S.No.</th>
                            <th>  <?php echo $this->Paginator->sort('Diagnosis.name','Diagnosis Name'); ?> </th>
                            <th>  <?php echo $this->Paginator->sort('Diagnosis.type','Type'); ?> </th>
                             <th>Action</th>
							
                          </tr>
                        </thead>
                        <tbody>
						
						<?php if(!empty($datas))
							{ 
								foreach($datas as $key => $data){  ?> 
						<tr>
							<td data-order="<?php echo $data['Diagnosis']['id']; ?>"><?php echo $key+1; ?></td>
							<td><?php echo $data['Diagnosis']['name']; ?></td>
							<td><?php echo ($data['Diagnosis']['type']==1)?'Neuro Diagnosis':'Ocular Diagnosis'; ?></td>
							<td class="action_sec">
						
							&nbsp;&nbsp;<?php //echo $this->Html->link('<i class="fa fa-pencil" aria-hidden="true"></i>',array('controller'=>'Diagnosis','action'=>'admin_index', $data['Diagnosis']['id']),array('escape'=>false,'title'=>'Edit eiagnosis'));?>
							&nbsp;&nbsp;<?php echo $this->Html->link('<i class="fa fa-trash-o"></i>',array('controller'=>'Diagnosis','action'=>'admin_delete',$data['Diagnosis']['id']),array('escape'=>false,'title'=>'Delete diagnosis','confirm'=>'Are you sure you want to delete Diagnosis?'));?>
							
							</td>
						</tr>
						<?php }
						  if(isset($this->params['paging']['Diagnosis']['pageCount'])){ ?>
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