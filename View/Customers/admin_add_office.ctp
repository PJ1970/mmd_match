<div class="content">
      <div class="">
	 
        <div class="page-header-title">
			<h1>
				<span class="label label-success active_label">Add Office</span>
				<label><i class="mdi mdi-menu-right"></i></label>
				<span class="label label-primary">Add Customer</span>
			</h1>
		</div>
      </div>
      <div class="page-content-wrapper ">
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-primary">
                <div class="panel-body">
				
                  <div class="row">
				    <?php echo $this->Form->create('Office', array('novalidate' => true,'url'=>array('controller'=>'Customers','action'=>'admin_add_office')));?>
                     <?php //echo $this->Form->input('id',array('type'=>'hidden')); ?>
                    <!--<input type="hidden" name="monthly_package" value="0" />-->
					<?php echo $this->Form->input('monthly_package',array('type'=>'hidden', 'value'=>0,'class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Name Of Office",'required'=>true)); ?>
					<div class="col-sm-6 col-xs-12">
						<div class="m-t-20">
					  
							<div class="form-group">
								<label>Name</label>
								<?php echo $this->Form->input('name',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Name Of Office",'required'=>true)); ?>
							</div>
							
							<div class="form-group">
								<label>Email</label>
								<?php echo $this->Form->input('email',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Email",'required'=>true)); ?>
							</div>
							<div class="form-group">
								<label>Phone</label>
								<?php echo $this->Form->input('phone',array('type'=>'text','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Phone",'required'=>true)); ?>
							</div>
							
							
							</div>
							<div class="form-group">
								<label>Address</label>
								<div>
									
									<?php echo $this->Form->input('address',array('type'=>'textarea','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Address",'required'=>true)); ?>
								</div>
							</div>
							
							<div class="form-group m-b-0">
								<div>
									<button type="submit" class="btn btn-primary waves-effect waves-light"> Next </button>
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