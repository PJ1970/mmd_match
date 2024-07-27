<div class="content">
      <div class="">
        <div class="page-header-title">
          <div class="row">
          <div class="col-sm-3 mts-box-1">
             <h3 class="page-title2" style="color: #ffffff; margin-top:6px;">Video Listing<span class="pull-right" style="color:white"></span></h3>
          </div> 
          <div class="col-sm-6 mts-box-1">
             <h4 class="page-title2" style="color: #ffffff;">Name: <?php echo $patient['Patient']['first_name'].' '.$patient['Patient']['middle_name'].''.$patient['Patient']['last_name'] ?></h4>
          </div>
           
          <div class="col-sm-3 mts-box-1">
             <h4 class="page-title">  </h4>
          </div>
      </div>
        </div> 
      </div>
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
											<!-- <td><a><?php echo WWW_BASE; ?>video/download/<?php echo base_convert( $data['Video']['id'] , 10, 36 );?></a></td>  -->
											<td><a href="javascript:void(0);" onclick="open_video('<?php echo $data['Video']['id']; ?>')">View video</a>
												<?php if(!empty($viewed[$data['Video']['id']])){ ?>

													<i class="fa fa-eye" title="viewed on <?php echo date('Y-m-d', strtotime($data['Video']['updated_at']))  ?>"></i>
											<?php	}else{ ?>
													<i class="fa fa-eye-slash" title="Not viewed"></i>
											<?php } ?>

											</td>
										<td>
										<a href="<?php echo WWW_BASE; ?>files/video/uploads/<?php echo $data['Video']['video'];?>" title="Download Video " download>
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

     <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">View Video</h4>
        </div>
        <div class="modal-body">
           <div class="row">
                <div class="col-sm-12 col-xs-12 col-lg-12" id="play-section">
                    
                </div>
                
                 
            </div>
        </div> 
      </div>
      
    </div>
  </div>


     <script>
     	function open_video(video_id,patient_id) {
     			$.ajax({
	          url: "<?php echo WWW_BASE; ?>admin/patients/view_video",
	          type: 'POST', 
	          data: {"patient_id": '<?php echo $patient['Patient']['id'] ?>',"video_id": video_id},
	          success: function(data){ 
	          	$('#play-section').html(data);
	          	$('#myModal').modal("show"); 
	          }
	      });
     	}
     </script>