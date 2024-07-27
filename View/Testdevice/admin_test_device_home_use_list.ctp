<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Manage IHU Test Devices</h4>
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
					<?php echo $this->Form->create('TestDevice',array('type' => 'get','url' => array('controller' => 'testdevice','action' => 'test_device_home_use_list'))); ?>
					
					 <div class="col-md-4">  
						<?php echo $this->Form->input('search',array('div' => false,'label' => false,'value' => @$search,'type' =>'text','class' => 'form-control','placeholder' => 'Search','maxlength' => '100')); ?>
						 
					</div>
					<div class="form-group m-b-0 col-md-4">
						<button type="submit" class="btn btn-primary waves-effect waves-light searchBtn" > Search </button>	
					</div>
					<?php echo $this->Form->end(); ?>
					<!-- <div align="right" class="col-md-4">
						<a href="<?php echo $this->HTML->url('/admin/testdevice/add'); ?>" class="btn btn-large btn-primary" >Add Device</a>
							<h4 class="m-b-30 m-t-0"></h4>
					</div> -->
				</div>
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12"style="margin-top: 10px;">
                      <table id="" class="table table-striped table-bordered">
                        <thead>
                          <tr>
							<th style="width: 34px;">S.No</th>
							<th>Patient Name</th>
              <th>Device Name</th>
              <th>Status</th>
							<th>Office</th>
							<th>Device Type</th>
              <th>Created</th>
							    </tr>
                        </thead>
                        <tbody>
						
						<?php if(!empty($datas)) {foreach($datas as $key=>$data){ 
							?>
						
						<tr>
							<td data-order="<?php echo $data['TestDevice']['id']; ?>"><?php echo $key+1; ?></td>
							<td><a href="/admin/patients/edit/<?php echo $data['Patient']['id']; ?>"><?php echo $data['Patient']['first_name'].' '.$data['Patient']['middle_name'].' '.$data['Patient']['last_name']; ?></a></td>
							<td <?php if(empty($data['Patient']['first_name'])){  ?>style='color:green;'<?php }?>><?php  echo $data['TestDevice']['name'] ;?> </td>
							<td> <?php if(empty($data['Patient']['first_name'])){  echo 'Unassign' ;}else{echo 'Assign';}?></td>
							<td><?php echo $name=$this->custom->getOfficeName($data['TestDevice']['office_id']); ?></td>
							<td>
							<?php
							if($data['TestDevice']['device_type']==6){
							    echo 'PICO_G2_IHU';
							}
						  ?></td>
							<td><?php  echo date('d F Y',strtotime($data['TestDevice']['created']));?></td>
							</tr>
						<?php }
						  if(isset($this->params['paging']['TestDevice']['pageCount'])){ ?>
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