<div class="content">
      <div class="">
	 
        <div class="page-header-title">
		 <?php if(!isset($this->request->data['Office']['id'])):?>
          <h4 class="page-title">Add Office</h4>
		  <?php else: ?>
		  <h4 class="page-title">Assign Credits</h4>
		  <?php endif; ?>
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
				    <?php echo $this->Form->create('Office', array('novalidate' => true,'url'=>array('controller'=>'offices','action'=>'admin_assign_credit')));?>
                     <?php echo $this->Form->input('id',array('type'=>'hidden')); ?>
                     
					<div class="col-sm-6 col-xs-12">
						<div class="m-t-20">
					  
							<div class="form-group">
								<label>Name</label>
								<?php echo $this->Form->input('name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Name Of Office",'readonly'=>true)); ?>
							</div>
							
							<div class="form-group">
								<label>Email</label>
								<?php echo $this->Form->input('email',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Email",'readonly'=>true)); ?>
							</div>
							<div class="form-group">
								<label>Phone</label>
								<?php echo $this->Form->input('phone',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Phone",'readonly'=>true)); ?>
							</div>
							
							<div class="form-group">
								<label>Unassigned Credits</label>
								<?php echo $this->Form->input('credits',array('type'=>'number','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Enter Cost for each report",'required'=>false,'value' =>0, 'min'=>0)); ?> 
							</div>
							<div class="form-group">
								<label>Deduct</label>
								<?php echo $this->Form->input('deduct',array('type'=>'checkbox','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"",'required'=>false)); ?> 
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
<?php if(@$this->request->data['Office']['payable']=='no'){ ?>
	<script type="text/javascript">  
		document.getElementById("monthly_package").style.display = "none";	
	
	</script> 
	<?php }else{ ?>
	<script type="text/javascript">  
		document.getElementById("restrict").style.display = "none";
	</script>
	<?php } ?>
<script type="text/javascript">  
	function hideDiv(){  
		var val=document.getElementById("payable").value; 
		if(val=='no'){
			document.getElementById("monthly_package").style.display = "none";
			document.getElementById("restrict").style.display = "block";
		}else{
			document.getElementById("monthly_package").style.display = "block";
			document.getElementById("restrict").style.display = "none";
		}		
		  
	}  
</script>   