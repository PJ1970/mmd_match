<?php #pr($data);die ?>
<div class="content">
      <div class="">
	 
        <div class="page-header-title">
		 
          <h4 class="page-title">Purchase Credit</h4>
		   
        </div>
      </div>
      <div class="page-content-wrapper ">
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-primary">
                <div class="panel-body">
				
                  <div class="row">
				    <?php echo $this->Form->create('Office', array('novalidate' => true,'url'=>array('controller'=>'payments','action'=>'index')));?>
                     <?php echo $this->Form->input('paypal_id',array('type'=>'hidden','value'=> @$data['paypal_id'])); ?>
					<div class="col-sm-6 col-xs-12">
						<div class="m-t-20">
					  
							<div class="form-group">
								<label>Office Name</label>
								<?php echo $this->Form->input('',array('type'=>'text','disabled','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"",'required'=>true,'value' => @$data['name'])); ?>
							</div> 
							<div class="form-group">
								<label>Per Use Cost</label>
								<?php echo $this->Form->input('',array('type'=>'text','disabled','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"",'required'=>true,'value' => (@$data['per_use_cost']>0)? $data['per_use_cost'] :0)); ?>
							</div>
							
							<div class="form-group">
								<label>Credits</label>
								<?php echo $this->Form->input('credits',array('type'=>'text','readonly','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"",'required'=>true,'value' => $data['monthly_package'])); ?>
								
								<?php echo $this->Form->input('amount',array('type'=>'hidden','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"",'required'=>true,'value' => $data['monthly_package'])); ?>
								
							</div>
							
							<!--<div class="form-group">
								<label>Credits</label>
								<?php echo $this->Form->input('credits',array('type'=>'select','class'=>'form-control credits','label'=>false,'div'=>false,'options'=>@$data['options'],'required'=>true)); ?>
								<?php echo $this->Form->hidden('amount',array('label'=>false,'div'=>false,'class'=>'amount','value'=>'','required'=>true)); ?>
							</div>
							<div class="form-group">
								<label>Amount: $</label>
								<strong style="margin-left: -3px;" data="<?php echo (@$data['per_use_cost']>0)? $data['per_use_cost'] :0; ?>" class="spanAmount">0.00</strong><small class ="clac"> </small>
							</div>
							-->
							
							<div class="form-group m-b-0">
								<div>
									<button type="submit" class="btn btn-primary waves-effect waves-light"> Pay </button>
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
 $('body').append('<div class="facebox" id="facebox" style="top: 70.8px; left: 475.5px;"><div class="popup popup56"><div class="content" style="padding: 45px"><div class="loading"><p style="color:#00aaff;"><b>Processing........Please do not click anywhere on the page until the process is complete.</b></p><img src="'+ajax_url+'img/ajaxloader.gif"></div> </div></div></div>'); 

	$(document).ready(function(e){
		$('#facebox').remove();
		var per_use_cost = parseFloat($('.spanAmount').attr('data').trim());
		var credits = parseFloat($('.credits').val().trim());
		var total_amount = per_use_cost * credits;
		 
		$('.amount').val(total_amount.toFixed(2));
		$('.clac').text(' ('+per_use_cost.toFixed(2)+' x '+credits +')');
		$('.spanAmount').text(total_amount.toFixed(2));
		$('.credits').change(function(){
			var per_use_cost = parseFloat($('.spanAmount').attr('data').trim());
			var credits = parseFloat($(this).val().trim());
			var total_amount = per_use_cost * credits;
			 
			$('.amount').val(total_amount.toFixed(2));
			$('.spanAmount').text(total_amount.toFixed(2));
			$('.clac').text(' ('+per_use_cost.toFixed(2)+' x '+credits+')');
				
		});
		
	});
 
 </script>