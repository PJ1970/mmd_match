<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Manage Customers</h4>
		 
        </div>
		 
      </div>
      <div class="page-content-wrapper ">
       <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-primary">
                <div class="panel-body">
				 <?php echo $this->Session->flash()."<br/>";?>
				 
				  <!--<?php //echo $this->Form->create('User',array('type' => 'get','url' => array('controller'=>'customers', 'action' => 'index'))); ?>
					
					 <div class="col-md-4">  
						<?php //echo $this->Form->input('search',array('div' => false,'label' => false,'value' => @$search,'type' =>'text','class' => 'form-control','placeholder' => 'Search','maxlength' => '100')); ?>
						 
					</div>
					<div class="form-group m-b-0 col-md-4">
						<button type="submit" class="btn btn-primary waves-effect waves-light searchBtn" > Search </button>	
					</div>
					<?php //echo $this->Form->end(); ?>-->
					<div align="left" class="col-md-4">
							<a href="<?php echo $this->HTML->url('/admin/customers/add_office'); ?>" class="btn btn-large btn-primary" >Add Customers</a>
								<h4 class="m-b-30 m-t-0"></h4>
					</div>
                  
                </div>
              </div>
            </div>
          </div>
          </div>
        </div>
     </div>

		<div id="OfficeAdminView" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content" id="office_admin_detail">
				</div>
			</div>
		</div>	
		
	<div class="modal fade bs-example-modal-sm" id="myPleaseWait" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <span class="glyphicon glyphicon-time">
                        </span>Please Wait
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="progress">
                        <div class="progress-bar progress-bar-info
                        progress-bar-striped active"
                             style="width: 100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
jQuery(document).ready(function(){
		jQuery(document).on('click',".OfficeAdminDetail",function() {
		//jQuery('#myPleaseWait').modal('show');
		var officeAdminId = jQuery(this).attr("OfficeAdminId");
		jQuery("#office_admin_detail").load("<?php echo WWW_BASE; ?>admin/customers/view/"+officeAdminId+ "?" + new Date().getTime(), function(result) {
			//jQuery('#myPleaseWait').modal('hide');
			jQuery("#OfficeAdminView").modal("show");
		});
	});	
});

</script>	
          