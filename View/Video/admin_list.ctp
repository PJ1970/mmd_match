<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Video Listing</h4> 
        </div> 
      </div>
       <?php $Admin = $this->Session->read('Auth.Admin');
	 	$Office_folder_name = 'OV_'.strtoupper(base_convert( $Admin['office_id'], 10, 32 ));
	  ?>
      <div class="page-content-wrapper ">
       <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-primary">
                <div class="panel-body"> 
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                    	<?php echo $this->Form->create('VideoIndex',array('type' => 'get','url' => array('controller' => 'video','action' => 'list'),'enctype'=>'multipart/form-data')); ?> 
							<div class="col-md-3">  
								<input type="text" name="search_name" class="form-control" style="border: 1px solid;"  value="<?php echo @$this->request->query['search_name'] ?>" > 
							</div>
							<div class="col-md-3">  
							 	<button type="submit" class="btn btn-primary">Serach</button>
							</div>
						<?php echo $this->Form->end(); ?>
                      <table id=" " class="table table-striped table-bordered">
                        <thead>
                          <tr>
							<th style="width:34px;">S.No</th>
                            <th>Name</th> 
							<th>Updated</th>
                            <th>Download Link</th>
                            <th>Action</th> 
                          </tr>
                        </thead>
                        <tbody> 
						<?php if(!empty($datas))
							{ 
								foreach($datas as $key => $data){   ?>
									<tr>
										<td data-order="<?php echo $data['Video']['id']; ?>"><?php echo $key+1; ?></td>
										<td><?php echo $data['Video']['name']; ?></td> 
										<td><?php echo date('Y-m-d H:i:s', strtotime($data['Video']['updated_at'])); ?></td> 
											<td><a><?php echo WWW_BASE; ?>video/download/<?php echo base_convert( $data['Video']['id'] , 10, 36 );?></a></td> 
										<td>
										<a href="<?php echo WWW_BASE; ?>files/video/uploads/<?php echo $Office_folder_name.'/'.$data['Video']['video'];?>" title="Download Video " download>
			                              <i class="fa fa-download" aria-hidden="true"></i> 
			                            </a> 
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
									 </div>
									<div class="pagi"><?php echo $this->Paginator->counter();echo "&nbsp Page"; ?></div>
								</td>
							</tr>
						<?php }  
						}else{echo "<tr><td colspan='8' style='text-align:center;'>No record found.</td></tr>";} ?> 
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