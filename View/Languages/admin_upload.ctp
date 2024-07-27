<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Upload File</h4>
        </div>
      </div>
      <div class="page-content-wrapper ">
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-primary">
                <div class="panel-body">
                  <div class="row">
                      <?php echo $this->Session->flash()."<br/>";?>
				    <?php echo $this->Form->create('Language', array('novalidate' => true,'url'=>array('controller'=>'languages','action'=>'admin_upload',$id),'enctype'=>'multipart/form-data'));?>
                    <div class="col-sm-6 col-xs-12">
						<div class="m-t-20">
					  <div class="form-group">
					      	<?php echo $this->Form->input('id',array('type'=>'hidden',"value"=>$id)); ?>
								<label>Language Name</label>
								<?php echo $this->Form->input('name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Name",'required'=>true,"value"=>$data['Language']['name'] , 'readonly'=>'readonly')); ?>
							</div>
							<div class="form-group">
								<label>Language Code</label>
								<?php echo $this->Form->input('code',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Code",'required'=>true,"value"=>$data['Language']['code'], 'readonly'=>'readonly')); ?>
						 
							</div>
							
							<div class="form-group">
								<label>Upload File (Select Multiple file)</label>
								 <?php	echo $this->Form->control('documents', [
    'type' => 'file',
    'label' => __('Choose PDF Files'),
    'multiple' => 'multiple',
    'name' => 'documents[]',
]); ?>
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
$(function() {
	$( ".datepicker" ).datepicker();
});
</script>
 