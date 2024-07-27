<?php $Admin = $this->Session->read('Auth.Admin'); ?>
<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">VA Test Report</h4>
        </div>
		 
      </div>
      <div class="page-content-wrapper ">
       <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-primary">
                <div class="panel-body">
				<?php echo $this->Session->flash()."<br/>";?>
				<?php $Admin = $this->Session->read('Auth.Admin');?>
				<div class="col-md-12 form-group">
					<?php echo $this->Form->create('VaData',array('type' => 'get','url' => array('controller' => 'unityreports','action' => 'unity_reports_list'))); ?>
					
					 <div class="col-md-4">  
						<?php echo $this->Form->input('search',array('div' => false,'label' => false,'value' => @$search,'type' =>'text','class' => 'form-control','placeholder' => 'Search','maxlength' => '100')); ?>
						 
					</div>
					<div class="form-group m-b-0 col-md-4">
						<button type="submit" class="btn btn-primary waves-effect waves-light searchBtn" > Search </button>	
					</div>
					<?php echo $this->Form->end(); ?>
					<div align="right" class="col-md-4">
							<h4 class="m-b-30 m-t-0"></h4>
					</div>
				</div>
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <table id="datatable_report1" class="table table-striped table-bordered">
                        <thead>
                          <tr>
							<th style="width:34px;">S.No</th>
                            <th> <?php echo $this->Paginator->sort('VaData.created','Date'); ?> </th>
                            <th>  <?php echo $this->Paginator->sort('VaData.staff_name','Staff User'); ?> </th>
                            <th> <?php
							echo ($Admin['user_type'] == "Admin")? 'Patient' : $this->Paginator->sort('VaData.patient_name',' Patient Name'); ?></th>
                            <th>Action</th>
                          </tr>
                        </thead>
						<tbody>
						<?php if(!empty($datas)) {foreach($datas as $key=>$data){ ?>
						
						<tr>
							<td data-order="<?php echo $data['VaData']['id']; ?>"><?php echo $key+1; ?></td>
							<td><?php echo date('d M Y h:i:s a',strtotime($data['VaData']['created'])); ?></td>
						 
							<td><?php
							$string = @explode(' ',@$data['VaData']['patient_name']);
							$patient_new_id = @substr(@$string[0],0,1).@substr(@$string[1],0,1).$data['VaData']['patient_id'];
							echo   "<a style='cursor: pointer;'  title='View' staffId='".$data['VaData']['staff_id']."'class='staff loaderAjax' >". $data['VaData']['staff_name']."</a>"; ?></td>
							<td><?php echo ($Admin['user_type'] == "Admin")?  "<a style='cursor: pointer;'  title='View' patientId='".$data['VaData']['patient_id']."'class='patient loaderAjax'>".$patient_new_id."</a>" : "<a style='cursor: pointer;'  title='View' patientId='".$data['VaData']['patient_id']."'class='patient loaderAjax'>".$data['VaData']['patient_name']."</a>"; ?></td>
							<td class="action_sec"> 
							<?php
							echo "<a style='cursor: pointer;'  title='View' testreportId='".$data['VaData']['id']."' class='testreport loaderAjax' ><i class='fa fa-eye' aria-hidden='true'></i></a>";
							//echo "<a style='cursor: pointer;' href='".WWW_BASE."admin/unityreports/view/".$data['VaData']['id']."?".time()."' title='View' testreportId='".$data['VaData']['id']."' rel='facebox'><i class='fa fa-eye' aria-hidden='true'></i></a>";
							?>
							</td>
						</tr>
						<?php }
						  if(isset($this->params['paging']['VaData']['pageCount'])){ ?>
							<tr> 
								<td colspan='9' align="center" class="paginat">
									<div class="pagi_nat">
									 <!-- Shows the next and previous links -->
									 <?php echo $this->Paginator->prev('<'); ?>
									 <?php echo $this->Paginator->numbers(
										 array(
										  'separator'=>''
										  )
										  ); ?>
									 <?php echo $this->Paginator->next('>'); ?><br>
									 <!-- prints X of Y, where X is current page and Y is number of pages -->
									 </div>
									<div class="pagi"><?php echo $this->Paginator->counter();echo "&nbsp Page"; ?></div>
								</td>
							</tr>
						<?php }  
						}else{echo "<tr><td colspan='5' style='text-align:center;'>No record found.</td></tr>";} ?>
						</tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </div>
        </div>
     </div>
	 <div id="reportView" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content" id="reportContent">
			</div>
		</div>
	</div>	
	<div id="patientView" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content" id="patientContent">
				</div>
			</div>
		</div>	
		
		<div id="staffView" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content" id="staffContent">
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
 $('body').append('<div class="facebox" id="facebox" style="top: 70.8px; left: 475.5px;"><div class="popup popup56"><div class="content" style="padding: 45px"><div class="loading"><p style="color:#00aaff;"><b>Processing........Please do not click anywhere on the page until the process is complete.</b></p><img src="'+ajax_url+'img/ajaxloader.gif"></div> </div></div></div>');
jQuery(document).ready(function(){
	//$('a[rel*=facebox]').facebox();
	jQuery('.facebox').remove(); 
	jQuery(document).on("click",".testreport",function() {
		var testreportId = jQuery(this).attr("testreportId");
		jQuery("#reportContent").load("<?php echo WWW_BASE; ?>admin/vareports/view/"+testreportId+ "?" + new Date().getTime(), function(result) {
			jQuery("#reportView").modal("show");
			$('.customFacebox').remove();
		});
	});	
	jQuery(document).on("click",".loaderAjax",function() {
		$('body').append('<div class="customFacebox" id="facebox" style="top: 70.8px; left: 475.5px;"><div class="popup popup56"><div class="content" style="padding: 45px"><div class="loading"><p style="color:#00aaff;"><b>Processing........Please do not click anywhere on the page until the process is complete.</b></p><img src="'+ajax_url+'img/ajaxloader.gif"></div> </div></div></div>');
		 
	});	
	
	jQuery(document).on("click",".VaData",function() {
		var VaDataId = jQuery(this).attr("VaDataId");
		jQuery("#reportContent").load("<?php echo WWW_BASE; ?>admin/unityreports/view/"+VaDataId+ "?" + new Date().getTime(), function(result) {
			jQuery("#reportView").modal("show");
			$('.customFacebox').remove();
		});
	});	
	
	jQuery(document).on("click",".patient",function() {
		var patientId = jQuery(this).attr("patientId");
		
		jQuery("#patientContent").load("<?php echo WWW_BASE; ?>admin/patients/view/"+patientId+ "?" + new Date().getTime(), function(result) {
		
			jQuery("#patientView").modal("show");
			$('.customFacebox').remove();
		 });
	});	
	
	jQuery(document).on("click",".staff",function() {
		var staffId = jQuery(this).attr("staffId");
		jQuery("#staffContent").load("<?php echo WWW_BASE; ?>admin/staff/staffView/"+staffId+ "?" + new Date().getTime(), function(result) {
			jQuery("#staffView").modal("show");
			$('.customFacebox').remove();
			});
	});	
			
			
	/* 	var table = $('#datatable_report').DataTable({
			processing: false,
			serverSide: true,
			start: 0,
			ajax: {
				url: "<?php echo WWW_BASE;?>admin/unityreports/ajaxUnityReportList",
				type: "POST",
				error: function(){  // error handling
                    $(".datatable_report-error").html("");
                    $("#datatable_report").append('<tbody class="datatable_report-error"><tr><th colspan="8">No data found in the server</th></tr></tbody>');
                    $("#datatable_report_processing").css("display","none");
 
                } 
			},
			columns: [
				{ "data": "id" },
				{ "data": "created" },
				{ "data": "staff_name"},
				{ "data": "patient_name"},
				{ "data": "report_view","orderable":false}
				
				
			],
            searching: true,
            lengthChange: true,
			lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
			paging: true,
            order: [[ 0, "desc" ]],
		} ); */
			
});

</script>

          