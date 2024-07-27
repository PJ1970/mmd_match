<script src="https://cdn.ckeditor.com/4.11.2/standard/ckeditor.js"></script>
<div class="content">
      <div class="">
	 
        <div class="page-header-title">
		 <?php if(!isset($this->request->data['Office']['id'])):?>
          <h4 class="page-title">Add Office</h4>
		  <?php else: ?>
		  <h4 class="page-title">Edit Office</h4>
		  <?php endif; ?>
        </div>
      </div>
      <div class="page-content-wrapper ">
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-primary">
                <div class="panel-body">
				
                  <div class="row">
				    <?php echo $this->Form->create('Cms', array('novalidate' => true,'url'=>array('controller'=>'cms','action'=>'admin_edit')));?>
                     <?php echo $this->Form->input('id',array('type'=>'hidden')); ?>
                     
					<div class="col-sm-12 col-xs-12">
						<div class="m-t-20">
					  
							<div class="form-group">
								<label>Page Name</label>
								<?php echo $this->Form->input('page_name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Name Of Cms",'required'=>true, 'readonly'=>'readonly')); ?>
							</div>
							
							<div class="form-group">
								<label>Page Title</label>
								<?php echo $this->Form->input('title',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Title",'required'=>true)); ?>
							</div>
							<div class="form-group">
								<label>Content</label>
								<?php echo $this->Form->input('content',array('type'=>'textarea','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Content",'required'=>true)); ?>
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
			CKEDITOR.replace( 'data[Cms][content]' );
	</script>
 