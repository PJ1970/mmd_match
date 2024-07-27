<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Add Practice</h4>
        </div>
      </div>
      <div class="page-content-wrapper ">
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-primary">
                <div class="panel-body">
                  <div class="row">
				     <?php echo $this->Form->create('Practices', array('novalidate' => true, 'id'=>'loginFrm', 'url'=>array('controller'=>'practices','action'=>'admin_edit')));?>
					 
					
                    <div class="col-sm-6 col-xs-12">
                      <div class="m-t-20">
						<?php echo $this->Form->input('id',array('type'=>'hidden','value'=>$datas['Practices']['id'],'class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Name",'required'=>true)); ?>
						
                          <div class="form-group">
                            <label>Name</label>
							<?php echo $this->Form->input('name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Name",'required'=>true)); ?>
                          </div>
						  <div class="form-group">
                            <label>Phone</label>
							<?php echo $this->Form->input('phone',array('type'=>'text','maxLength'=>10,'class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Phone",'required'=>true)); ?>
                          </div>
						  <div class="form-group">
                            <label>Address</label>
							<?php echo $this->Form->input('address',array('type'=>'textarea','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Address",'required'=>true)); ?>
                          </div>
						  <div class="form-group m-b-0">
                            <div>
                              <button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>
                            </div>
                          </div>
                      </div>
                    </div>
                   <div class="col-sm-6 col-xs-12">
				   
                     <!-- <h3 class="m-t-0"><small class="text-info"><b>Range validation</b></small></h3>-->
                      <div class="m-t-20">
                        
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
    </div>