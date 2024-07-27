<script src="https://cdn.ckeditor.com/4.11.2/standard/ckeditor.js"></script>
<div class="content">
      <div class="">
	 <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
        <div class="page-header-title">
            <h4 class="page-title">Add Ticket</h4>
		 <?php /*if(!isset($this->request->data['Office']['id'])):?>
          <h4 class="page-title">Add Office</h4>
		  <?php else: ?>
		  <h4 class="page-title">Edit Page</h4>
		  <?php endif; */?>
        </div>
      </div>
      <div class="page-content-wrapper ">
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-primary">
                <div class="panel-body">
				 <?php echo $this->Session->flash()."<br/>";?>
                  <div class="row">
				    <?php echo $this->Form->create('Support', array('novalidate' => true,'url'=>array('controller'=>'support','action'=>'admin_edit2'),'enctype'=>'multipart/form-data'));?>
                    
					<div class="col-sm-12 col-xs-12">
						<div class="m-t-20">
							<?php if($this->Session->read('Auth.Admin.user_type')=='Admin' || $this->Session->read('Auth.Admin.user_type')=='SupportSuperAdmin'){ ?>
								<div class="form-group">
									<label>Reference No</label>
									<?php echo $this->Form->input('refrance_no',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Reference No")); ?>
								</div>
							<?php } ?>
					      <?php if($this->Session->read('Auth.Admin.user_type')=='Admin' || $this->Session->read('Auth.Admin.user_type')=='SupportSuperAdmin'  || $this->Session->read('Auth.Admin.user_type')=='RepAdmin'){ ?>
						  <div class="form-group">
						  	 
                            <label>Select Office *</label>
                            <div>
							<?php
							 if( $this->Session->read('Auth.Admin.user_type')=='RepAdmin'){
							 	$options=$this->custom->getOfficeListv2($this->Session->read('Auth.Admin.id'));
							 }else{
							 	$options=$this->custom->getOfficeListv2();
							 }
								 
								echo $this->Form->input('office_id',array('options' =>$options,'id'=>'SlectStaff','empty'=>'Select Office','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3'));
							?>
                            </div>
                        </div>
					 
						<div class="form-group">
                            <label>Select Staff *</label>
                            <div>
							<?php 	
								echo $this->Form->input('user_id',array('options' =>$users_da,'empty'=>'Select Staff','selected' =>(isset($selected))?$selected:$this->request->data['Support']['user_id'],'id'=>'setStaff','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3'));
							?>  
                            </div>
                        </div>
					 <?php } ?>
							<div class="form-group">
								<label>Category</label>
								<?php echo $this->Form->input('caregory_id',array('options' =>$category,'id'=>'selectCategory','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3')); ?>
							</div>
							<div class="form-group">
								<label>Title</label>
								<?php echo $this->Form->input('title',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Title",'required'=>true)); ?>
							</div>
							<div class="form-group">
								<label>Device Serial No</label>
								<?php echo $this->Form->input('device_serial_no',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Enter device erial no",'required'=>true)); ?>
							</div>
							<div class="form-group">
								<label>Model</label>
								<?php echo $this->Form->input('model',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Enter Model name")); ?>
							</div>
							
							<div class="form-group">
								<label>Message</label>
								<?php echo $this->Form->input('message',array('type'=>'textarea','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Content",'required'=>true)); ?>
								<?php echo $this->Form->input('id',array('type'=>'hidden','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Content")); ?>
							</div>
							<div class="form-group">
								<label>Attach file</label>
								<?php echo $this->Form->input('file',array('type'=>'file','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"File",'required'=>true)); ?>
							</div>
							<div class="form-group m-b-0">
								<div>
									<button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>
								</div>
							</div>
						</div>
					<?php echo $this->Form->end();?>
					</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
	 <script>
			CKEDITOR.replace( 'data[Support][message]' );
	</script>
	 <script>
$(document).ready(function(){

	 $('#SlectStaff').selectize({
          sortField: 'text'
      });

	$('body').on('change','#SlectStaff',function(){
		var company_id = $(this).val();
		var location = "<?php echo WWW_BASE."admin/patients/getStaffListByCompanyId";?>";
		$.ajax({
			url: location,
			type: 'GET',
			data:{
				company_id : company_id,
			},
			success : function(response) { 
			$('#setStaff').html($("<option value=''>Select Staff</option>"));
				$.each(jQuery.parseJSON(response), function(key, value) {
					//console.log(value);
				//for (var i = 0; i < response.length; i++) {
					 $('#setStaff').append($("<option value='"+key+"'>"+value+"</option>"));
				});
				
			},
		});
	});
});
</script>
 