<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Video Listing</h4>
		 
        </div>
		 
      </div>
      <div class="page-content-wrapper ">
       <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-primary">
                <div class="panel-body">
				 <?php echo $this->Session->flash()."<br/>";?>
				 
			     
				<div class="col-md-12" style="padding-bottom: 20px;" id="add_video">
					<?php echo $this->Form->create('Video',array('type' => 'post','url' => array('controller' => 'video','action' => 'index'),'enctype'=>'multipart/form-data')); ?>
					 
					 <div class="col-md-3">  
					 <label>Upload Video file *</label>
						<?php echo $this->Form->input('video',array('div' => false,'label' => false,'type' =>'file','class' => 'form-control','placeholder' => 'Search','maxlength' => '100','required'=>true,'style'=>'border: 1px solid')); ?>
					
						 
					</div>
					<div class="col-md-7">  
					 <label>Name *</label>
						<?php echo $this->Form->input('name',array('div' => false,'label' => false,'type' =>'text','class' => 'form-control','placeholder' => 'Name','maxlength' => '100','required'=>true,'style'=>'border: 1px solid')); ?>
						 
					</div>
				  
					<div class="form-group m-b-0 col-md-2">
					    <br>
						<button type="submit" class="btn btn-primary waves-effect waves-light searchBtn" > Add new Video</button>	
					</div>
				  
					<?php echo $this->Form->end(); ?>
					 
					
				</div>
				
				<div class="col-md-12" style="padding-bottom: 20px;  visibility:hidden; height:1px;" id="update_video">
					<?php echo $this->Form->create('VideoEdit',array('type' => 'post','url' => array('controller' => 'apk','action' => 'edit'),'enctype'=>'multipart/form-data')); ?>
					 <div class="col-md-3">  
					 <label>Video</label>
					 	<?php echo $this->Form->input('video',array('div' => false,'label' => false,'type' =>'file','class' => 'form-control','placeholder' => 'Minimum Version','maxlength' => '100','style'=>'border: 1px solid')); ?>  
					</div>
					<div class="col-md-7">  
					 <label>Name *</label>
						<?php echo $this->Form->input('name',array('div' => false,'label' => false,'type' =>'text','class' => 'form-control','placeholder' => 'Name','maxlength' => '100','required'=>true,'style'=>'border: 1px solid')); ?>
						 	<?php echo $this->Form->input('id',array('div' => false,'label' => false,'type' =>'hidden','class' => 'form-control','placeholder' => 'Build','maxlength' => '100','required'=>true)); ?>
						 
					</div>
				  
					<div class="form-group m-b-0 col-md-2">
					    <br>
						<button type="submit" class="btn btn-primary waves-effect waves-light searchBtn" > Update Video</button>	
					</div>
					<?php echo $this->Form->end(); ?>
					  
				</div>
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                    	<?php echo $this->Form->create('VideoIndex',array('type' => 'get','url' => array('controller' => 'video','action' => 'index'),'enctype'=>'multipart/form-data')); ?>
	                     
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
							<a href="<?php echo WWW_BASE; ?>files/video/uploads/<?php echo $data['Video']['video'];?>" title="Download Video " download>
                              <i class="fa fa-download" aria-hidden="true"></i>
                              <a href="javascript:void(0)" title="Edit " onclick="openeditform(<?php echo $data['Video']['id'];?>,'<?php echo $data['Video']['name'];?>');">&nbsp;&nbsp;
                              <i class="fa fa-pencil" aria-hidden="true"></i>
                              	&nbsp;&nbsp;<?php echo $this->Html->link('<i class="fa fa-trash-o"></i>',array('controller'=>'video','action'=>'admin_delete_video',$data['Video']['id']),array('escape'=>false,'title'=>'Delete','confirm'=>'Are you sure you want to delete video?'));?> 
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
 
	
	$('#ApkVersion, #ApkMinimumVersion').on('change',function(){
     var min_ver=parseFloat($("#ApkMinimumVersion").val());
     var version=parseFloat($("#ApkVersion").val());
     if(min_ver>version){
         $("#ApkMinimumVersion").val(version);
     } 
    });
    
    	$('#ApkEditMinimumVersion').on('change',function(){
     var min_ver=parseFloat($("#ApkEditMinimumVersion").val());
     var version=parseFloat($("#ApkEditVersion").val());
     if(min_ver>version){
         $("#ApkEditMinimumVersion").val(version);
     } 
    });
});

function openeditform(id,name){
  
    document.getElementById("add_video").style.visibility = "hidden";
    document.getElementById("add_video").style.height = "1px";
    
    document.getElementById("update_video").style.visibility = "visible";
    document.getElementById("update_video").style.height = "auto";
    $("#VideoEditName").val(name);
    $("#VideoEditId").val(id);      
}
 </script>         