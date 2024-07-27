<div class="col-sm-6 col-xs-12">
		<?php echo $this->Form->create(false, array('url' => array('controller' => 'Testdevice', 'action' => 'add_device_preference'),'id' => 'DevicePreference')); ?>
	<div class="m-t-20">
		<div class="form-group">
			<label>Device ID</label>
			<?php echo $this->Form->input('id',array('type'=>'text','value'=>isset($this->request->data['TestDevice']['mac_address'])?$this->request->data['TestDevice']['mac_address']:'','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Device ID",'required'=>true,'readonly'=>'readonly')); ?>
		</div>
		<div class="form-group">
			<label>FocusMin</label>
			<?php echo $this->Form->input('FocusMin',array('type'=>'text','value'=>isset($this->request->data['devicePreference']['FocusMin'])?$this->request->data['devicePreference']['FocusMin']:'','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"FocusMin")); ?>
		</div>
		<div class="form-group">
			<label>FocusMax</label>
			<?php echo $this->Form->input('FocusMax',array('type'=>'text','value'=>isset($this->request->data['devicePreference']['FocusMax'])?$this->request->data['devicePreference']['FocusMax']:'','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"FocusMax")); ?>
		</div>
		<div class="form-group">
			<label>FocusStep</label>
			<?php echo $this->Form->input('FocusStep',array('type'=>'text','value'=>isset($this->request->data['devicePreference']['FocusStep'])?$this->request->data['devicePreference']['FocusStep']:'','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"FocusStep")); ?>
		</div>
		<div class="form-group">
			<label>LimitX1</label>
			<?php echo $this->Form->input('LimitX1',array('type'=>'text','value'=>isset($this->request->data['devicePreference']['LimitX1'])?$this->request->data['devicePreference']['LimitX1']:'','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"LimitX1")); ?>
		</div>
		<div class="form-group">
			<label>LimitY1</label>
			<?php echo $this->Form->input('LimitY1',array('type'=>'text','value'=>isset($this->request->data['devicePreference']['LimitY1'])?$this->request->data['devicePreference']['LimitY1']:'','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"LimitY1")); ?>
		</div>
		<div class="form-group">
			<label>LimitX2</label>
			<?php echo $this->Form->input('LimitX2',array('type'=>'text','value'=>isset($this->request->data['devicePreference']['LimitX2'])?$this->request->data['devicePreference']['LimitX2']:'','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"LimitX2")); ?>
		</div>
		<div class="form-group">
			<label>LimitY2</label>
			<?php echo $this->Form->input('LimitY2',array('type'=>'text','value'=>isset($this->request->data['devicePreference']['LimitY2'])?$this->request->data['devicePreference']['LimitY2']:'','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"LimitY2")); ?>
		</div>
		<div class="form-group">
			<label>FocusThreshold</label>
			<?php echo $this->Form->input('FocusThreshold',array('type'=>'text','value'=>isset($this->request->data['devicePreference']['FocusThreshold'])?$this->request->data['devicePreference']['FocusThreshold']:'','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"FocusThreshold")); ?>
		</div>
		<div class="form-group m-b-0">
			<div>
				<button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>
			</div>
		</div>
	</div>
	<?php echo $this->Form->end();?> 
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>
<script>
	$(document).ready(function(){
		$("#DevicePreference").validate({
			// Specify validation rules
			rules: {
			  
			  "data[id]": {
				required: true,
				minlength: 5
			  },
			  "data[FocusMin]": {required:true, number:true},
			  "data[FocusMax]": {required:true, number:true},
			  "data[FocusStep]": {required:true, number:true},
			  "data[LimitX1]": {required:true, number:true},
			  "data[LimitY1]": {required:true, number:true},
			  "data[LimitX2]": {required:true, number:true},
			  "data[LimitY2]": {required:true, number:true},
			  "data[FocusThreshold]": {required:true, number:true}
			 
			  
			  
			},
			// Specify validation error messages
			messages: {
			  
			  "data[id]": {
				required: "Please save this value in first form",
				minlength: "Your password must be at least 5 characters long"
			  },
			  "data[FocusMin]": {
				  required:"Please fill FocusMin",
				  number:"Please fill numeric value only"
			  },
			  "data[FocusMax]": {
				  required:"Please fill FocusMax",
				  number:"Please fill numeric value only"
			  },
			  "data[FocusStep]": {
				  required:"Please fill FocusStep",
				  number:"Please fill numeric value only"
			  },
			  "data[LimitX1]": {
				  required:"Please fill LimitX1",
				  number:"Please fill numeric value only"
			  },
			  "data[LimitY1]": {
				  required:"Please fill LimitY1",
				  number:"Please fill numeric value only"
			  },
			  "data[LimitX2]": {
				  required:"Please fill LimitX2",
				  number:"Please fill numeric value only"
			  },
			  "data[LimitY2]": {
				  required:"Please fill LimitY2",
				  number:"Please fill numeric value only"
			  },
			  "data[FocusThreshold]": {
				  required:"Please fill FocusThreshold",
				  number:"Please fill numeric value only"
			  }
			},
			// Make sure the form is submitted to the destination defined
			// in the "action" attribute of the form when valid
			submitHandler: function(form) {
			  form.submit();
			}
		  });
	});
</script>
           