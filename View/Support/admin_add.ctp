<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.2/ckeditor5.css">
<div class="content">
      <div class="">
	 <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
        <div class="page-header-title">
            <h4 class="page-title">Add Support Ticket</h4>
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
				    <?php echo $this->Form->create('Support', array('novalidate' => true,'url'=>array('controller'=>'support','action'=>'admin_add'),'enctype'=>'multipart/form-data'));?>
                    
					<div class="col-sm-12 col-xs-12">
						<div class="m-t-20">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Device Serial No</label>
									<?php echo $this->Form->input('device_serial_no',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Enter device serial no",'required'=>true)); ?>
								</div>
							</div>
					      <?php if($this->Session->read('Auth.Admin.user_type')=='Admin' || $this->Session->read('Auth.Admin.user_type')=='SupportSuperAdmin'  || $this->Session->read('Auth.Admin.user_type')=='RepAdmin'){ ?>
					      	<div class="col-sm-6">
						      	<div class="form-group">
		                            <label>Model*</label>
		                            <div>
										<?php $type = array('Focus'=>'Focus','G2'=>'G2','Neo2'=>'Neo2','Neo3'=>'Neo3');	
											echo $this->Form->input('model',array('options' =>$type,'empty'=>'Select Model','selected' =>(isset($selected))?$selected:'','id'=>'setmodel','div'=>false,'legend'=>false,'class' => 'form-control','label' => false,'default'=>Null));
										?>  
		                            </div>
	                        	</div>
                    		</div>
                    		<?php } ?>
                    	</div>
                    </div>
                     <?php if($this->Session->read('Auth.Admin.user_type')=='Admin' || $this->Session->read('Auth.Admin.user_type')=='SupportSuperAdmin'  || $this->Session->read('Auth.Admin.user_type')=='RepAdmin'){ ?>
                    <div class="col-sm-12 col-xs-12">
						<div class="m-t-20">	
                        <div class="col-sm-4">
                            <div class="form-group">
	                            <label>Reportable *</label>
	                            <div>
									<?php $reportabl = array('Yes'=>'Yes','No'=>'No');	
										echo $this->Form->input('reportable',array('options' =>$reportabl,'empty'=>'Select Model','selected' =>(isset($selected))?$selected:'No','id'=>'setreportable','div'=>false,'legend'=>false,'class' => 'form-control','label' => false,'default'=>'No'));
									?>  
	                            </div>
                        	</div>
                        </div>
                        <div class="col-sm-4">
	                        <div class="form-group">
	                            <label>Investigation Needed *</label>
	                            <div>
									<?php $Investigat = array('Yes'=>'Yes','No'=>'No');	
										echo $this->Form->input('investigation',array('options' =>$Investigat,'empty'=>'Select Model','selected' =>(isset($selected))?$selected:'No','id'=>'setinvestigation','div'=>false,'legend'=>false,'class' => 'form-control','label' => false,'default'=>'No'));
									?>  
	                            </div>
	                        </div>
                        </div>
                        <div class="col-sm-4">
	                        <div class="form-group">
	                            <label>RMA QP2 *</label>
	                            <div>
									<?php $rma_Qp = array('Yes'=>'Yes','No'=>'No');	
										echo $this->Form->input('rma_Qp_2',array('options' =>$rma_Qp,'empty'=>'Select Model','selected' =>(isset($selected))?$selected:'No','id'=>'setrma_Qp_2','div'=>false,'legend'=>false,'class' => 'form-control','label' => false,'default'=>'No'));
									?>  
	                            </div>
	                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xs-12">
					<div class="m-t-20">
						<div class="col-sm-6">
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
                        </div>
						<div class="col-sm-6">
							<div class="form-group">
	                            <label>Select Staff *</label>
	                            <div>
									<?php 	
										echo $this->Form->input('user_id',array('options' =>$users_da,'empty'=>'Select Staff','selected' =>(isset($selected))?$selected:'','id'=>'setStaff','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3','default'=>null));
									?>  
	                            </div>
	                        </div>
	                    </div>
                    </div>
                </div>
            <?php } ?>
                <div class="col-sm-12 col-xs-12">
					<div class="m-t-20">
					 <?php if($this->Session->read('Auth.Admin.user_type')=='Admin'){ ?>
					 	<div class="col-sm-6">
							<div class="form-group">
								<label>Category</label>
								<?php echo $this->Form->input('caregory_id',array('options' =>$category,'id'=>'selectCategory','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3')); ?>
							</div>
						</div>
						<?php }else{ ?>
						<div class="col-sm-6">
							<div class="form-group">
								<?php echo $this->Form->input('caregory_id',array('type'=>'hidden','value'=>1,'div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true')); ?>
							</div>
						</div>
						<?php } ?>
						<?php if($this->Session->read('Auth.Admin.user_type')=='Admin' || $this->Session->read('Auth.Admin.user_type')=='SupportSuperAdmin'  || $this->Session->read('Auth.Admin.user_type')=='RepAdmin'){ ?>
						<div class="col-sm-6">
							<div class="form-group">
							    <label>Complaint Type *</label>
							    <div>
									<?php $type = array('Complaint'=>'Complaint','RMA'=>'RMA');	
										echo $this->Form->input('complaint_type',array('options' =>$type,'empty'=>'Select Type','selected' =>(isset($selected))?$selected:'','id'=>'settype','div'=>false,'legend'=>false,'class' => 'form-control','label' => false,'default'=>'Complaint'));
									?>  
							    </div>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="col-sm-12 col-xs-12">
					<div class="m-t-20">
							<div class="form-group">
								<label>Title</label>
								<?php echo $this->Form->input('title',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Title",'required'=>true)); ?>
							</div>
							<div class="form-group">
								<label>Message</label>
								<?php echo $this->Form->input('message',array('type'=>'textarea','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Content",'required'=>true)); ?>
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
 
 <script type="importmap">
            {
                "imports": {
                    "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/42.0.2/ckeditor5.js",
                    "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/42.0.2/"
                }
            }
        </script>
        <script type="module">
            import {
                ClassicEditor,
                Essentials, Paragraph, Bold, Italic, Underline, Strikethrough, Subscript, Superscript, Code, Link,
            List, Indent, BlockQuote, MediaEmbed, ImageUpload, Highlight, FontSize, FontFamily,
            FontColor, FontBackgroundColor, Alignment, RemoveFormat, HorizontalLine, PageBreak, SpecialCharacters,
            FindAndReplace, SourceEditing
            } from 'ckeditor5';

            ClassicEditor
                .create( document.querySelector( '#SupportMessage' ), {
                    plugins: [ Essentials, Paragraph, Bold, Italic, Underline, Strikethrough, Subscript, Superscript, Code, Link,
            List, Indent, BlockQuote, MediaEmbed, ImageUpload, Highlight, FontSize, FontFamily,
            FontColor, FontBackgroundColor, Alignment, RemoveFormat, HorizontalLine, PageBreak, SpecialCharacters,
            FindAndReplace, SourceEditing ],
                    toolbar: [
            'undo', 'redo', '|', 
            'bold', 'italic', 'underline', 'strikethrough', '|',
            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
            'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                'mediaEmbed', '|',
            'alignment', 'removeFormat', 'horizontalLine', 'specialCharacters', '|',
            'sourceEditing'
                    ]
                } )
                .then( editor => {
                    window.editor = editor;
                } )
                .catch( error => {
                    console.error( error );
                } ); 
        </script>
        <style>
.ck-editor__editable_inline {
    min-height: 200px;
}
</style>