<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Offices Reports Listing</h4>
		 
        </div>
		 
      </div>
      <div class="page-content-wrapper ">
       <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-primary">
                <div class="panel-body">
				 <?php echo $this->Session->flash()."<br/>";?>
				 
			 <?php /*
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
							<h4 class="m-b-30 m-t-0"></h4>
					</div>
				</div>*/ ?>
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <table id=" " class="table table-striped table-bordered">
                        <thead>
                          <tr>
							<th style="width:34px;">S.No</th>
                            <th>  <?php echo 'Office Name'; ?> </th>
                            <th>  <?php echo 'Office Reports'; ?> </th>                           
                          </tr>
                        </thead>
                        <tbody>
						
						<?php 
						
						if(!empty($datas))
							{ $count = 1;
								foreach($datas as $key => $data){  ?>
									<tr>
									<?php #pr($data);die; ?>
										<td><?php echo $count++; ?></td>
										<td><?php echo $data['Office']['name']; ?></td>
										<td><?php 
										$disabled='disabled';
										$Admin = $this->Session->read('Auth.Admin');
										if(!empty($Admin) && $Admin['user_type']=="Admin"){
											$disabled='';
										}
											echo $this->Form->create('Office');
											echo $this->Form->input('id',array('value'=>$data['Office']['id'],'type'=>'hidden'));
											$arr = array('ALL' => 'ALL', 'VF' => 'VF Reports', 'VA' => 'VA Reports');
											echo $this->Form->input('office_report',array('type'=>'select','options' => $arr,'onchange'=>"this.form.submit();", 'value'=>$data['Office']['office_report'],$disabled,'class' => 'form-control','label'=>FALSE));
											echo $this->Form->end();  
										?>
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
						}else{echo "<tr><td colspan='5' style='text-align:center;'>No record found.</td></tr>";} ?>
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