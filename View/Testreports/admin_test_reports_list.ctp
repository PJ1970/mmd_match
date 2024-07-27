
<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Camera Test Reports</h4>
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
					<?php echo $this->Form->create('TestReport',array('type' => 'get','url' => array('controller' => 'testreports','action' => 'test_reports_list'))); ?>
					
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
                            <th> <?php echo $this->Paginator->sort('Testreport.created','Date'); ?> </th>
                            <th> <?php echo $this->Paginator->sort('Test.name','Test Name'); ?> </th>
                        <!-- <th>Retinal Camera</th> -->
						   
                            <th>   <?php echo $this->Paginator->sort('Testreport.staff_name','Staff User'); ?>  </th>
							
                            <th>   <?php echo $this->Paginator->sort('Testreport.patient_name','Patient Name'); ?> </th>
                            <th>Action</th>
                          </tr>
                        </thead>
						<tbody>
						<?php if(!empty($datas)) {foreach($datas as $key=>$data){ ?>
						
						<tr>
							<td data-order="<?php echo $data['Testreport']['id']; ?>"><?php echo $key+1; ?></td>
							<td><?php echo date('d M Y h:i:s a',strtotime($data['Testreport']['created'])); ?></td>
							<td><?php echo $data['Test']['name']; ?></td>
							<td><?php echo $data['Testreport']['staff_name']; ?></td>
							<td><?php echo $data['Testreport']['patient_name']; ?></td>
							<td class="action_sec"> 
							<?php
							$file_name= getcwd().'/uploads/pdf/'.$data['Testreport']['pdf'];
							if(!empty($data['Testreport']['pdf'])&&(file_exists($file_name))):
								echo "<a style='cursor: pointer;'  title='View' testreportId='".$data['Testreport']['id']."'class='testreport' data-toggle='modal' data-target='#reportView'><i class='fa fa-eye' aria-hidden='true'></i></a>&nbsp;&nbsp;<a type='button'  title='Pdf' target='_blank' class='testpdf' data='".$data['Testreport']['pdf']."' href='".WWW_BASE.'uploads/pdf/'.$data['Testreport']['pdf']."'><i class='fa fa-file-pdf-o' aria-hidden=true></i></a>";
							else:
								echo "<a style='cursor: pointer;'  title='View' testreportId='".$data['Testreport']['id']."'class='testreport' data-toggle='modal' data-target='#reportView'><i class='fa fa-eye' aria-hidden='true'></i></a>";
							endif;
							?>
							</td>
						</tr>
						<?php }
						  if(isset($this->params['paging']['Testreport']['pageCount'])){ ?>
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
						}else{echo "<tr><td colspan='6' style='text-align:center;'>No record found.</td></tr>";} ?>
						</tbody>
						
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
		jQuery("#reportContent").load("<?php echo WWW_BASE; ?>admin/testreports/view/"+testreportId+ "?" + new Date().getTime()+ new Date().getMilliseconds(), function(result) {
			jQuery("#reportView").modal("show");
		});
	});	
	
	jQuery(document).on("click",".patient",function() {
		var patientId = jQuery(this).attr("patientId");
		jQuery("#patientContent").load("<?php echo WWW_BASE; ?>admin/patients/view/"+patientId+ "?" + new Date().getTime()+ new Date().getMilliseconds(), function(result) {
		
			jQuery("#patientView").modal("show");
		 });
	});	
	
	jQuery(document).on("click",".staff",function() {
		var staffId = jQuery(this).attr("staffId");
		jQuery("#staffContent").load("<?php echo WWW_BASE; ?>admin/staff/staffView/"+staffId+ "?" + new Date().getTime()+ new Date().getMilliseconds(), function(result) {
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

          