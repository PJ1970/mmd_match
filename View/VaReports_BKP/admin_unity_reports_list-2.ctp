
<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Manage Test Reports</h4>
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
				<div class="col-md-12">
					<div class="col-md-6">
						<h4 class="m-b-30 m-t-0"></h4>
					</div>
				</div>
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <table id="datatable_report" class="table table-striped table-bordered">
                        <thead>
                          <tr>
							<th style="width:34px;">S.No</th>
                            <th>Date</th>
                            <th>Test Name</th>
                        <!-- <th>Retinal Camera</th> -->
						   
                            <th>Staff User</th>
							
                            <th>Patient Name</th>
                            <th>Action</th>
                          </tr>
                        </thead>
						<!-- Table body from Ajax -->
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
jQuery(document).ready(function(){
	jQuery(document).on("click",".testreport",function() {
		var testreportId = jQuery(this).attr("testreportId");
		jQuery("#reportContent").load("<?php echo WWW_BASE; ?>admin/testreports/view/"+testreportId+ "?" + new Date().getTime(), function(result) {
			jQuery("#reportView").modal("show");
		});
	});	
	
	jQuery(document).on("click",".patient",function() {
		var patientId = jQuery(this).attr("patientId");
		jQuery("#patientContent").load("<?php echo WWW_BASE; ?>admin/patients/view/"+patientId+ "?" + new Date().getTime(), function(result) {
		
			jQuery("#patientView").modal("show");
		 });
	});	
	
	jQuery(document).on("click",".staff",function() {
		var staffId = jQuery(this).attr("staffId");
		jQuery("#staffContent").load("<?php echo WWW_BASE; ?>admin/staff/staffView/"+staffId+ "?" + new Date().getTime(), function(result) {
			jQuery("#staffView").modal("show");
			});
	});	
			
			
		var table = $('#datatable_report').DataTable({
			processing: false,
			serverSide: true,
			start: 0,
			ajax: {
				url: "<?php echo WWW_BASE;?>admin/testreports/ajaxTestReportList",
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
				{ "data": "name" },
			/* 	{ "data": "file_path","orderable":false}, */
				{ "data": "staff_name"},
				{ "data": "patient_name"},
				{ "data": "report_view","orderable":false}
				
				
			],
            searching: true,
            lengthChange: true,
			lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
			paging: true,
            order: [[ 0, "desc" ]],
		} );
			
});

</script>

          