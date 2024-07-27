<?php //pr($this->request->data); die; ?>
<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Edit Test Device</h4>
        </div>
      </div>
      <div class="page-content-wrapper ">
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-primary">
                <div class="panel-body">
                  <div class="row">
				    <?php echo $this->Form->create('TestDevice', array('novalidate' => true,'url'=>array('controller'=>'testdevice','action'=>'admin_edit')));?>
                    <div class="col-sm-6 col-xs-12">
						<div class="m-t-20">
						
							<?php echo $this->Form->input('id',array('type'=>'hidden')); ?>
							<div class="form-group">
								<label> Device Serial Number</label>
								<?php echo $this->Form->input('deviceSeraial',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"",'required'=>true)); ?>
							</div>
							<div class="form-group">
								<label>Device Name</label>
								<?php echo $this->Form->input('name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Name Of Device",'required'=>true)); ?>
							</div>
							
							<div class="form-group">
								<label>IP Address</label>
								<?php echo $this->Form->input('ip_address',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"IP Address",'required'=>true)); ?>
							</div>
							
							<div class="form-group">
								<label>MAC Address</label>
								<?php echo $this->Form->input('mac_address',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"MAC Address",'required'=>true)); ?>
							</div>
							
							<div class="form-group">
								<label>Bluetooth MAC Address</label>
								<?php echo $this->Form->input('bt_mac_address',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>" Bluetooth MAC Address",'required'=>true)); ?>
							</div>
							
							<div class="form-group">
								<label>Select Device Status</label>
								<div>
									<?php $status_option = array('1'=>'Active','2'=>'Inactive'); ?>
									<?php echo $this->Form->input('status',array('options' =>$status_option,'empty'=>'Select Device Status','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3')); ?>
								</div>
							</div>
							<div class="form-group">
								<label>Select Office</label>
								<div>
								<?php 
									$options=$this->custom->getOfficeList();
									//pr($options); die;
									echo $this->Form->input('office_id',array('options' =>$options,'empty'=>'Select Office','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3'));
								?>
								</div>
							</div>
							<div class="form-group">
								<label>Device Type</label>
								<div>
									<?php $type_option = array('0'=>'Gear','1'=>'GO','2'=>'PICO_NEO','3'=>'Quest','4'=>'PICO_G2','5'=>'PICO_NEO_3','6'=>'PICO_G2_IHU','8'=>'PICO_G3'); ?>
									<?php echo $this->Form->input('device_type',array('options' =>$type_option,'empty'=>'Select Device Type','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'data-live-search' => 'true', 'data-selected-text-format' => 'count > 3')); ?>
								</div>
							</div>
							 <div class="form-group">
								<label>Device Level</label>
								<div>
								<?php  
									  echo $this->Form->input('device_level',array('type'=>'number','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Device Level",'required'=>true));
								 	?>
								</div>
							</div> 
							<div class="form-group m-b-0">
								<div>
									<button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>
								</div>
							</div>
						</div>
					<?php echo $this->Form->end();?>
					</div>
					<?php echo $this->element('device_preference'); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>