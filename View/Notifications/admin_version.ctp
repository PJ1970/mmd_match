<script src="https://cdn.ckeditor.com/4.11.2/standard/ckeditor.js"></script>
<div class="content">
      <div class="">
	 
        <div class="page-header-title">
          <h4 class="page-title">Add Web Version</h4>
		   
        </div>
      </div>
      <div class="page-content-wrapper ">
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-primary">
                <div class="panel-body">
				<?php echo $this->Session->flash() . "<br/>"; ?>
                  <div class="row">
				    <?php echo $this->Form->create('Version', array('novalidate' => true,'url'=>array('controller'=>'Notifications','action'=>'admin_version')));?>
                     <?php echo $this->Form->input('id',array('type'=>'hidden')); ?>
                     
				 
					
						<div class="col-sm-3 col-xs-3">
						<div class="m-t-20">
					  
							<div class="form-group">

								<label style="font-size:16px; font-weight: 800; margin-bottom: 15px;">Current Version : <?php echo @$data['Version']['version'] ?></label><br>
								<label style="font-size:16px; font-weight: 800; margin-bottom: 15px;">Version</label>
								<?php echo $this->Form->input('version',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Enter New Version",'required'=>true)); ?>
							</div>
						 
							<div class="form-group m-b-0">
								<div>
									<button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>
								</div>
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
     
	 