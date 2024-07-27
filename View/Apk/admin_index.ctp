<div class="content">
  <div class="">
    <div class="page-header-title">
      <h4 class="page-title">APK Listing</h4>
    </div>
  </div>
 <div class="page-content-wrapper ">
   <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="panel panel-primary">
            <div class="panel-body">
			 <?php echo $this->Session->flash()."<br/>";?>
			 <div id="add_apk">
			 	<?php echo $this->Form->create('Apk',array('type' => 'post','url' => array('controller' => 'apk','action' => 'index'),'enctype'=>'multipart/form-data')); ?>
			 	<div class="row">
				 	<div class="col-md-4">  
						 <div class="form-group">
						 	<label>Build *</label>
							<?php echo $this->Form->input('build',array('div' => false,'label' => false,'type' =>'text','class' => 'form-control','placeholder' => 'Build','maxlength' => '100','required'=>true)); ?>
						 </div>
					</div>
					<div class="col-md-4">  
						<div class="form-group">
							 <label>Version *</label>
							<?php echo $this->Form->input('version',array('div' => false,'label' => false,'type' =>'text','class' => 'form-control','placeholder' => 'Version','maxlength' => '100','required'=>true)); ?>
						</div>
					</div>
					<div class="col-md-4">  
						<div class="form-group">
							 <label>Device Type *</label>
							<?php $type_option = array('0'=>'Gear','1'=>'GO','2'=>'PICO_NEO','3'=>'Quest','4'=>'PICO_G2','5'=>'PICO_NEO_3', '6' =>'PUPIL_NEO2','7'=>'PUPIL_NEO3','10'=>'Controller','11'=>'AppManager '); ?>
										<?php echo $this->Form->input('device_type',array('options' =>$type_option,'empty'=>'Select Device Type','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3')); ?>
						</div>
					</div>
					<div class="col-md-4">  
						<div class="form-group">
							<label>App Type</label>
							<?php $type_option2 = array('0'=>'Internal','1'=>'Alpha','2'=>'Production'); ?>
									<?php echo $this->Form->input('apk_type',array('options' =>$type_option2,'empty'=>'Select App Type','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3')); ?>
						</div>
				    </div>
				    <div class="col-md-4">  
						<div class="form-group">
							 <label>Video</label>
						 	<?php echo $this->Form->input('video',array('div' => false,'label' => false,'type' =>'file','class' => 'form-control','placeholder' => 'Minimum Version','maxlength' => '100','required'=>true)); ?>
						</div>
				    </div>
				    <div class="col-md-4">  
						<div class="form-group">
							  <label>Upload Apk file *</label>
							<?php echo $this->Form->input('apk',array('div' => false,'label' => false,'type' =>'file','class' => 'form-control','placeholder' => 'Search','maxlength' => '100','required'=>true)); ?>
						</div>
				    </div>
				    <div class="col-md-6">  
						<div class="form-group">
							 <label>Instruction</label>
							 <?php echo $this->Form->input('instruction', array('type' => 'textarea', 'class' => 'form-control', 'label' => false, 'div' => false, 'placeholder' => "Enter Instruction", 'required' => true)); ?>
						</div>
				    </div>
				    <div class="col-md-6">  
						<div class="form-group">
							  <label>What's new changes</label>
							 <?php echo $this->Form->input('comments', array('type' => 'textarea', 'class' => 'form-control', 'label' => false, 'div' => false, 'placeholder' => "Enter comments..", 'required' => true)); ?>
						</div>
				    </div>
				    <div class="col-md-12">
				   		<button type="submit" class="btn btn-primary waves-effect waves-light searchBtn" > Add new APK</button>
				   	</div>
			 	</div>
			 <?php echo $this->Form->end(); ?>	
			 </div>
			<div class="col-md-12" style="padding-bottom: 20px;  visibility:hidden; height:1px;" id="update_apk">
				<?php echo $this->Form->create('ApkEdit',array('type' => 'post','url' => array('controller' => 'apk','action' => 'edit'),'enctype'=>'multipart/form-data')); ?>
				<div class="col-md-3"> 
					<div class="form-group"> 
					 <label>Build *</label>
						<?php echo $this->Form->input('build',array('div' => false,'label' => false,'type' =>'text','class' => 'form-control','placeholder' => 'Build','maxlength' => '100','required'=>true)); ?>
						<?php echo $this->Form->input('id',array('div' => false,'label' => false,'type' =>'hidden','class' => 'form-control','placeholder' => 'Build','maxlength' => '100','required'=>true)); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">  
					 <label>Version *</label>
						<?php echo $this->Form->input('version',array('div' => false,'label' => false,'type' =>'text','class' => 'form-control','placeholder' => 'Version','maxlength' => '100','required'=>true)); ?>
					</div>
				</div>
				<div class="col-md-3"> 
					<div class="form-group"> 
					 <label>Minimum Version *</label>
						<?php echo $this->Form->input('minimum_version',array('div' => false,'label' => false,'type' =>'text','class' => 'form-control','placeholder' => 'Minimum Version','maxlength' => '100','required'=>true)); ?>
					</div>
				</div>
				<div class="col-md-3"> 
					<div class="form-group"> 
					 <label>Device Type *</label>
						<?php $type_option = array('0'=>'Gear','1'=>'GO','2'=>'PICO_NEO','3'=>'Quest','4'=>'PICO_G2','5'=>'PICO_NEO_3', '6' =>'PUPIL_NEO2','7'=>'PUPIL_NEO3','10'=>'Controller','11'=>'AppManager','13'=>'Auto Download'); ?>
						<?php echo $this->Form->input('device_type',array('options' =>$type_option,'empty'=>'Select Device Type','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3')); ?>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Upload Apk file *</label>
					<?php echo $this->Form->input('apk',array('div' => false,'label' => false,'type' =>'file','class' => 'form-control','placeholder' => 'Search','maxlength' => '100','required'=>true)); ?>
						 
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
					 <label>Video</label>
					 	<?php echo $this->Form->input('video',array('div' => false,'label' => false,'type' =>'file','class' => 'form-control','placeholder' => 'Minimum Version','maxlength' => '100')); ?>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>App Type</label>
					 	<?php $type_option2 = array('0'=>'Internal','1'=>'Alpha','2'=>'Production','3'=>'WPF'); ?>
						<?php echo $this->Form->input('apk_type',array('options' =>$type_option2,'empty'=>'Select App Type','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3')); ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">  
				 		<label>Instruction</label>
					 	<?php echo $this->Form->input('instruction', array('type' => 'textarea', 'class' => 'form-control', 'label' => false, 'div' => false, 'placeholder' => "Enter Instruction", 'required' => true)); ?>
					</div>
				</div>
				<div class="col-md-6">  
					<div class="form-group">
				 		<label>What's new changes</label>
					 	<?php echo $this->Form->input('comments', array('type' => 'textarea', 'class' => 'form-control', 'label' => false, 'div' => false, 'placeholder' => "Enter comments", 'required' => true)); ?>
					</div>
				</div>
				<div class="form-group m-b-0 col-md-12">
					<button type="submit" class="btn btn-primary waves-effect waves-light searchBtn" > Update APK</button>
				</div>
				<?php echo $this->Form->end(); ?>
			</div>
          	<div class="row">
            	<div class="col-md-12 col-sm-12 col-xs-12">
            		<?php echo $this->Form->create('ApkIndex',array('type' => 'get','url' => array('controller' => 'apk','action' => 'index'),'enctype'=>'multipart/form-data')); ?>
                	<div class="col-md-3">
	                	<div class="form-group">  
							<?php echo $this->Form->input('apk_type',array('options' =>$type_option2,'empty'=>'Select App Type','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'selected'=>@$this->request->query['apk_type'], 'data-selected-text-format' => 'count > 3')); ?>
						</div>
					</div>
					<div class="col-md-3"> 
						<div class="form-group"> 
							<?php echo $this->Form->input('device_type',array('options' =>$type_option,'empty'=>'Select Device Type','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'selected'=>@$this->request->query['device_type'], 'data-selected-text-format' => 'count > 3')); ?>
						</div>
					</div>
					<div class="col-md-3">  
					 	<button type="submit" class="btn btn-primary">Serach</button>
					</div>
            	</div>
	            <div class="col-md-12 col-sm-12 col-xs-12">
	            	<div class="table-responsive table_custom">
		              		<table id=" " class="table table-striped table-bordered">
		                <thead>
		                  <tr>
							<th style="width:34px;">S.No</th>
		                    <th>Build</th>
		                    <th>Version</th>
		                    <th>Minimum Version</th>
							<th>Device Type</th>
							<th>APK Type</th>
							<th>Updated</th>
							<th>Comments</th>
		                    <th style="width:30%;">Download Link</th>
		                    <th>Action</th>
		                  </tr>
		                </thead>
		                <tbody>
						<?php if(!empty($datas)){ 
								foreach($datas as $key => $data){   ?>
						<tr>
							<td data-order="<?php echo $data['Apk']['id']; ?>"><?php echo $key+1; ?></td>
							<td><?php echo $data['Apk']['build']; ?></td>
							<td><?php echo $data['Apk']['version']; ?></td>
							<td><?php echo $data['Apk']['minimum_version']; ?></td>
							<td><?php echo $type_option[$data['Apk']['device_type']]; ?></td>
							<td><?php echo $type_option2[$data['Apk']['apk_type']]; ?></td>
							<td><?php echo date('Y-m-d H:i:s', strtotime($data['Apk']['updated_at'])); ?></td>
							<td><textarea readonly rows="3" cols="25"><?php echo $data['Apk']['comments']; ?></textarea></td>
							<?php if($data['Apk']['apk_type']!=2){ ?>
							<td style="width:30%;"><a><?php echo WWW_BASE; ?>apk/download/<?php echo $type_option2[$data['Apk']['apk_type']].'/'.base_convert( $data['Apk']['id'] , 10, 36 );?></a>
							</td>
							<?php }else{ ?>
							<td><a><?php echo WWW_BASE; ?>apk/download/<?php echo base_convert( $data['Apk']['id'] , 10, 36 );?></a>
							</td>
							<?php } ?> 
							<td>
							<a href="<?php echo WWW_BASE; ?>apk/uploads/<?php echo $data['Apk']['apk'];?>" title="Download apk " download>
		                      <i class="fa fa-download" aria-hidden="true"></i>
		                      <a href="javascript:void(0)" title="Edit " onclick="openeditform(<?php echo $data['Apk']['id'];?>,'<?php echo $data['Apk']['build'];?>','<?php echo $data['Apk']['version'];?>','<?php echo $data['Apk']['minimum_version'];?>',<?php echo $data['Apk']['device_type'];?>,<?php echo $data['Apk']['apk_type'];?>,'<?php echo $data['Apk']['instruction'];?>',`<?php echo $data['Apk']['comments']; ?>`);">&nbsp;&nbsp;
		                      <i class="fa fa-pencil" aria-hidden="true"></i>
		                      	&nbsp;&nbsp;<?php echo $this->Html->link('<i class="fa fa-trash-o"></i>',array('controller'=>'apk','action'=>'admin_delete_apk',$data['Apk']['id']),array('escape'=>false,'title'=>'Delete','confirm'=>'Are you sure you want to delete apk?'));?> 
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
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>
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

function openeditform(id,build,version,minimum_version,device_type,apk_type,instruction,comments){
    document.getElementById("add_apk").style.display = "none";
    document.getElementById("add_apk").style.height = "1px";
    
    document.getElementById("update_apk").style.visibility = "visible";
    document.getElementById("update_apk").style.height = "auto";
    $("#ApkEditBuild").val(build);
    $("#ApkEditId").val(id);
    $("#ApkEditVersion").val(version);
    $("#ApkEditMinimumVersion").val(minimum_version);
    $("#ApkEditInstruction").val(instruction);
    $("#ApkEditComments").val(comments);
    $("#ApkEditDeviceType").val(device_type);
    $("#ApkEditApkType").val(apk_type);
    document.getElementById("ApkEditBuild").disabled = true;
    document.getElementById("ApkEditVersion").disabled = true;
    document.getElementById("ApkEditDeviceType").disabled = true;
      
}
 </script>         