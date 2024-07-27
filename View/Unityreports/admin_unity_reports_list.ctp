<?php $Admin = $this->Session->read('Auth.Admin'); ?>
<?php $thresshold_test=["Central_10_2", "Central_24_1", "Central_24_2", "Central_30_1", "Central_30_2","Central_24_2C"] ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <style>
     table.table tr.tr-ptdata.active td{background: #ccc; }
 </style>
 <?php if(!empty($testOptions)){  
$testOptions = array_flip($testOptions);
foreach($testOptions as $key => $value){
	$testOptions[$key]= $key;
} 
 } ?>
<div class="content"> <?php //echo $Admin['user_type']; ?>
      <div class="">
        <div class="page-header-title reports">
          <h4 class="page-title"><?php echo $reportType; ?> Test Report</h4>
        </div>

      </div>
      <div class="page-content-wrapper ">
       <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-primary">
                <div class="panel-body">

				<?php if(isset($credit_expire)){
				//pr($check_payable);die; // $check_payable['Office']['payable'] =='no' && $check_payable['Office']['restrict'] =='restrict'
					if($check_payable['Office']['payable'] =='no' && $check_payable['Office']['restrict'] =='restrict'){?>
						<h2 style="color:red;text-align:center;">You don't have permission to see this. Please contact support: <br/>Email: support@micromedinc.com  <br/>Phone : 818-222-3310</h2>
					<?php }else{
						if($Admin['user_type'] == "Staffuser"){
							?>
							<h2 style="color:red;text-align:center;">Your Credit has expired. Please contact to your office.</h2>
							<?php
						}else{?>
							<h2 style="color:red;text-align:center;">You are out of credit please contact Micro Medical office<br/>Email: support@micromedinc.com  <br/>Phone : 818-222-3310</h2>
						<?php }
					}?>
				<?php }else{?>

					<?php echo $this->Session->flash()."<br/>";

					if(@$check_payable['Office']['payable'] =='no' && @$check_payable['Office']['restrict'] =='restrict'){
					?>
						<h2 style="color:red;text-align:center;">You don't have permission to see this. Please contact support: <br/>Email: support@micromedinc.com  <br/>Phone : 818-222-3310</h2>
					<?php
					}else{ ?> 
						<?php $Admin = $this->Session->read('Auth.Admin');?> 
						<?php if($Admin['user_type'] == "Admin"){ ?>
						 
							<div style="border: 1px solid #ddd;float: left;width: 100%;margin-bottom:20px;padding-bottom: 0px;">
								<h4 style="padding-left: 10px;">Normal Search</h4>
								<?php echo $this->Form->create('Pointdata',array('type' => 'get')); ?>
								<div class="col-md-8">
									<?php echo $this->Form->input('include_patient_name',array('div' => false,'label' => false, 'value'=>@$include_patient_name,'type' =>'text','class' => 'form-control','placeholder' => 'Search with Patient Name','maxlength' => '100')); ?>
								</div>
								<div class="form-group m-b-0 col-md-4">
									<button type="submit" class="btn btn-primary waves-effect waves-light searchBtn" > Search </button>	&nbsp;
								</div>
								 
								<div align="right" class="col-md-4">
									<h4 class="m-b-30 m-t-0"></h4>
								</div>
							</div>
							<div style="border: 1px solid #ddd;float: left;width: 100%;margin-bottom:20px;">
								<h4 style="padding-left: 10px;">Advance Search</h4>
								 <div class="row" style="margin: 5px;">
									<div class="col-md-2">
										<?php echo $this->Form->input('version',array('div' => false,'label' => false,'value' => @$version,'type' =>'text','class' => 'form-control','placeholder' => 'Version','maxlength' => '100')); ?>
									</div>
									<?php if(!empty($testOptions)){ ?>
										<div class="col-md-2">
											<?php echo $this->Form->input('test_name',array('options' => $testOptions, 'default' => @$test_name,'empty' => 'Test Name', 'div' => false,'label' => false, 'class' => 'form-control','maxlength' => '100')); ?>
										</div>
									<?php } ?>
									<div class="col-md-2">
										<?php echo $this->Form->input('eye_select', array('options' => ['0'=>'OS', '1'=>'OD', '2'=>'BOTH'], 'empty' => 'Eye Tool', 'default' => @$eye_select, 'class' => 'form-control', 'div' => false,'label' => false)); ?>
									</div>
									<div class="col-md-2">
										<?php echo $this->Form->input('patient_name',array('div' => false,'label' => false,'value'=>@$patient_name,'type' =>'text','class' => 'form-control','placeholder' => 'Exclude this Patient Name','maxlength' => '100')); ?>
									</div>

									<div class="col-md-2">
										<?php
											echo $this->Form->input('patient_age', array('options'=>$this->Dropdown->ageDropdown(),'empty' => 'AGE In Years', 'div' => false,'label' => false,'value'=>@$patient_age, 'class' => 'form-control', 'maxlength' => '100'));
										?>
									</div>

									<div class="col-md-2">
										<?php
											echo $this->Form->input('race',array('options' =>$this->Dropdown->racesDropdown(),'empty'=>'Select Race','id'=>'setStaff','div'=>false,'legend'=>false,'class' => 'form-control','label' => false, 'value'=>@$race, ));
										?>
									</div>
								</div>
								<div class="row" style="padding-top: 10px;">
									<div class="col-md-4"></div>
									<div class="form-group m-b-0 col-md-4">
										<button type="submit" class="btn btn-primary waves-effect waves-light searchBtn" > Search </button>	&nbsp;
										<?php if(!empty(@$test_name) && !empty($datas)){ ?>
										<a href="<?php echo $this->Html->url(array('action'=>'export_search','?'=>$this->request->query)); ?>" class="btn btn-success waves-effect waves-light searchBtn" > <i class="fa fa-file-o" aria-hidden="true"></i> Export </a>
										<?php } ?>
									</div>
									<div class="col-md-4"></div>
								</div>
								<?php echo $this->Form->end(); ?>
								<div align="right" class="col-md-4">
									<h4 class="m-b-30 m-t-0"></h4>
								</div>
							</div>
						<?php }else{ ?>
							<div class="col-md-12 form-group">
								<?php echo $this->Form->create('Pointdata',array('type' => 'get')); ?>

								<div class="col-md-4">
									<?php echo $this->Form->input('search',array('div' => false,'label' => false,'value' => @$search,'type' =>'text','class' => 'form-control','placeholder' => 'Search','maxlength' => '100')); ?>
								</div>
								<?php if(!empty($testOptions)){ ?>
									<div class="col-md-2">
										<?php echo $this->Form->input('test_name',array('options' =>$testOptions, 'default' => @$test_name,'empty' => 'Test Name', 'div' => false,'label' => false, 'class' => 'form-control','maxlength' => '100')); ?>
									</div>
								<?php } ?>
								<div class="form-group m-b-0 col-md-4">
									<button type="submit" class="btn btn-primary waves-effect waves-light searchBtn" > Search </button>
								</div>
								<?php echo $this->Form->end(); ?>
								<div align="right" class="col-md-4">
									<h4 class="m-b-30 m-t-0"></h4>
								</div>
							</div>
						<?php } ?>

					  <div class="row">
						<div class="table-responsive col-md-12 col-sm-12 col-xs-12">
						  <table id="datatable_report1" class="table table-striped table-bordered">
							<thead>
							  <tr>
								<th style="width:34px;">S.No</th>
								<th> <?php echo $this->Paginator->sort('Pointdata.created','Date'); ?> </th>
								<th> <?php
								echo ($Admin['user_type'] == "Admin")? 'Patient' : $this->Paginator->sort('Pointdata.patient_name',' Patient Name'); ?></th>
								<th>  <?php echo $this->Paginator->sort('Patient.id_number','Patient Id'); ?> </th>
								<th>DOB <br>(DD-MM-YYYY)</th>

								<th>  <?php echo $this->Paginator->sort('Pointdata.test_name','Test name'); ?> </th>
								<?php if($reportType!='VS'){ ?>
								<th>  <?php echo $this->Paginator->sort('Pointdata.eye_select','OD/OS'); ?> </th>
								<?php } if($reportType =='VF'){?>
								<th title="Selected items are included for Progression Analysis" style="width: 80px;">Progression</th>
								<?php } if($Admin['user_type']=='Admin'){ ?> <th>Office Name</th> <?php } ?>
								<th>  <?php echo $this->Paginator->sort('Pointdata.staff_name','Staff User'); ?> </th>
								<?php if($Admin['user_type'] == "Admin"){ ?>

									<th>Age</th>

									<th>Version</th>
									<th>Diagnosys</th>
								<?php } ?>
									<th>Source</th>
								<th with="140px;">Action</th>
							  </tr>
							</thead>
							<tbody>
							<?php if(!empty($datas)) {foreach($datas as $key=>$data){ ?>
							 <?php $ptdata_id=$data['Pointdata']['id'];  ?>
							<tr id="ptdata-<?php echo $ptdata_id; ?>" class="tr-ptdata ptdata-<?php echo $ptdata_id; ?>">
								<?php $patient_new_id = @$data['Pointdata']['patient_name']; ?>
								<td data-order="<?php echo $data['Pointdata']['id']; ?>"><?php echo $key+1; ?></td>
								<td><?php echo date('d M Y h:i:s a',strtotime($data['Pointdata']['created'])); ?></td>
								<td><?php echo ($Admin['user_type'] == "Admin")?  "<a style='cursor: pointer;'  title='View' patientId='".$data['Pointdata']['patient_id']."'class='patient loaderAjax'>".$patient_new_id."</a>" : "<a style='cursor: pointer;'  title='View' patientId='".$data['Pointdata']['patient_id']."'class='patient loaderAjax'>".$data['Pointdata']['patient_name']."</a>"; ?></td>
								 <td><?php echo $data['Patient']['id_number'];?></td>
							    <td><?php echo (!empty($data['Pointdata']['patient_dob']))?date('d-m-Y', strtotime($data['Pointdata']['patient_dob'])):''; ?></td>


								<td><?php echo (!empty($data['Pointdata']['test_name']))?$data['Pointdata']['test_name'] : 'N/A';?></td>
								<?php if($reportType!='VS'){ ?>
								<td><?php
								$ch = $data['Pointdata']['eye_select'];
								switch ($ch){
									case 0:
									echo "OS";
									break;
									case 1:
									echo "OD";
									break;
									case 2:
									echo "OU";

								}
								?></td>
							<?php }if($reportType =='VF'){?>
								<td>
									<?php if (in_array($data['Pointdata']['test_name'], $thresshold_test)) {
									  ?>
									<input type="checkbox" title="Selected items are included for Progression Analysis" class="form-control" <?php echo ($data['Pointdata']['baseline']==1)?'checked':''; ?> style="height: 25px !important;" onchange="savedata(this,<?php echo $data['Pointdata']['id']; ?>)" >
								<?php } ?>
								</td>
							<?php } ?>
								 <?php if($Admin['user_type']=='Admin'){ ?> <td><?php echo (array_key_exists($data['User']['office_id'],$office))?$office[$data['User']['office_id']]:''; ?></td><?php } ?>
								<td><?php
								$string = @explode(' ',@$data['Pointdata']['patient_name']);
								//pr($string);
								//$patient_new_id = @substr(@$string[0],0,1).@substr(@$string[1],0,1).$data['Pointdata']['patient_id'];
								//$patient_new_id = @$string[0].' '.@$string[1].' '.@$string[2];

								echo   "<a style='cursor: pointer;'  title='View' staffId='".$data['Pointdata']['staff_id']."'class='staff loaderAjax' >". $data['Pointdata']['staff_name']."</a>"; ?></td>

								<?php if($Admin['user_type'] == "Admin"){ ?>

									<td><?php echo (!empty($data['Pointdata']['patient_dob']))?$data['Pointdata']['patient_age_years']:''; ?></td>
									<td><?php echo $data['Pointdata']['version']; ?></td>
									<td><?php echo $data['Pointdata']['diagnosys']; ?></td>
								<?php } ?>
								<td><?php echo $data['Pointdata']['source']; ?></td>
								<td class="action_sec">
									<?php
										echo "<a style='cursor: pointer;'  title='View' testreportId='".$data['Pointdata']['id']."' class='testreport loaderAjax' ><i class='fa fa-eye' aria-hidden='true'></i></a>";
										//echo "<a style='cursor: pointer;' href='".WWW_BASE."admin/unityreports/view/".$data['Pointdata']['id']."?".time()."' title='View' testreportId='".$data['Pointdata']['id']."' rel='facebox'><i class='fa fa-eye' aria-hidden='true'></i></a>";
									?>
									<?php if(!empty($data['Pointdata']['file'])){ ?>
                                            &nbsp;&nbsp;&nbsp;
                                    <?php 
                                    	$related_id=(!empty($download[$ptdata_id]['tr_id']))? $download[$ptdata_id]['tr_id'] : 'tr-none';
                                        $related_ids=(!empty($downloads[$ptdata_id]['tr_id']))? $downloads[$ptdata_id]['tr_id'] : 'tr-none';
                                    ?>
                     <a data-type="pdf" data-related='<?php echo $related_id; ?>' data-downloads='<?php echo json_encode($download[$ptdata_id]); ?>'  title='Download PDF Reports' href="javascript:;" class="vbs-popover"><i class="fa fa-download"></i></a>
                                    &nbsp;&nbsp;
                     <a data-mid="<?php echo $ptdata_id; ?>" data-type="pdf" data-related='<?php echo $related_ids; ?>' data-downloadsa='<?php echo json_encode($download[$ptdata_id]); ?>'data-downloads='<?php echo json_encode($downloads[$ptdata_id]); ?>'  title='Download OS/OD Merged PDF Reports' href="javascript:;" class="vbs-mergepdf"><i class="fa fa-angle-double-down"></i></a>
										&nbsp;&nbsp;
                     <a title="View pdf report" href="<?php echo WWW_BASE.'pointData/'.$data['Pointdata']['file']; ?>" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
										&nbsp;&nbsp;&nbsp;
										<?php if(WWW_BASE !='https://www.vibesync.com/'){ ?>
										<a title="Download Dicom Report" href="<?php echo $this->Html->url(['controller'=>'unityreports','action'=>'exportDicom',$data['Pointdata']['patient_id'], $data['Pointdata']['file']]); ?>" target="_blank"><i class="fa fa-image" aria-hidden="true"></i></a>
                                        <a data-type="dicom" data-related='<?php echo $related_id; ?>' data-downloads='<?php echo json_encode($download[$ptdata_id]); ?>' title='Download OS/OD Dicom Reports' href="javascript:;" class="vbs-popover" ><i class="fa fa-download"></i></a>
										&nbsp;&nbsp; 
									<?php } }?>
									<?php //pr($Admin); die;
										if(!empty($Admin) && ($Admin['user_type'] == "Subadmin" || $Admin['user_type'] == "Admin")){
											echo "<a href=".$this->Html->url(['controller'=>'unityreports','action'=>'delete',$data['Pointdata']['id']])."  title='Delete' onclick='if (confirm(&quot;Are you sure you want to delete?&quot;)) { return true; } return false;'><i class='fa fa-trash-o'></i></a>";
										}
									?>
								</td>
							</tr>
							<?php }
							  if(isset($this->params['paging']['Pointdata']['pageCount'])){ ?>
								<tr>
									<td colspan='15' align="center" class="paginat">
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
							}else{echo "<tr><td colspan='15' style='text-align:center;'>No record found.</td></tr>";} ?>
							</tbody>
						  </table>
						</div>
					  </div>
					</div>
				  </div>
				<?php }} ?>
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
  $( function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,
  changeYear: true, yearRange: "-100:+0",maxDate: new Date()});
  } );
</script>

<script>
 $('body').append('<div class="facebox" id="facebox" style="top: 70.8px; left: 475.5px;"><div class="popup popup56"><div class="content" style="padding: 45px"><div class="loading"><p style="color:#00aaff;"><b>Processing........Please do not click anywhere on the page until the process is complete.</b></p><img src="'+ajax_url+'img/ajaxloader.gif"></div> </div></div></div>');
jQuery(document).ready(function(){

 jQuery('.vbs-popover').hover( function(){
                //var el = jQuery(this);
                //var id = el.attr("id");
                //var type=el.attr("data-type");
                var related=jQuery(this).attr("data-related");
                //var downloads=el.attr("data-downloads");
                jQuery(".tr-ptdata").removeClass("active");
                jQuery(this).parents('tr').addClass("active");
                jQuery("#"+related).addClass("active");


            }, function(){
                jQuery(".tr-ptdata").removeClass("active");
            }); 
 jQuery('.vbs-mergepdf').hover( function(){
                //var el = jQuery(this);
                //var id = el.attr("id");
                //var type=el.attr("data-type");
                var related=jQuery(this).attr("data-related");
                //var downloads=el.attr("data-downloads");
                jQuery(".tr-ptdata").removeClass("active");
                jQuery(this).parents('tr').addClass("active");
                jQuery("#"+related).addClass("active");


            }, function(){
                jQuery(".tr-ptdata").removeClass("active");
            });

            jQuery('.vbs-popover').click( function(){
                var downloads=jQuery.parseJSON(jQuery(this).attr("data-downloads"));
                var type=jQuery(this).attr("data-type");
                downloadAll(downloads[type],downloads['filename']);
            }); 
            jQuery('.vbs-mergepdf').click( function(){
                event.preventDefault();
                var downloads=jQuery.parseJSON(jQuery(this).attr("data-downloadsa")); // get url to merge 
                var downloadp=jQuery.parseJSON(jQuery(this).attr("data-downloads")); // Download after merge
                var type=jQuery(this).attr("data-type");
                var pdfarray = downloads['pdf'];
                var fileNameArray=[]; var resultedArray=[];var data;data = new FormData();
                if(pdfarray){
                	pdfarray.map((val,key)=>{
                		let fileNameWithExtension=val.split("/")[4];
                		let fileNameWithoutPdf=fileNameWithExtension.split(".")[0];
										fileNameArray.push(fileNameWithoutPdf);
										resultedArray.push("/home/jb03iy2ldm4f/public_html/app/webroot/pointData/"+fileNameWithExtension);
                	})
                }
                if(fileNameArray){
                	var time;
                	fileNameArray.forEach((item,index)=>{
                		if(!index){
                			time=item;
                		}else{
                			time=time+'_'+item;
                		}
                		data.append( `pdf_file[${index}]`, resultedArray[index]);	//ES6 => template literal `${javascript_variable}`
                	});
                	time=time+'_merge.pdf';
                }    
                savepdf = "/home/jb03iy2ldm4f/public_html/app/webroot/pointData/"+time;
    						data.append( 'file_save_path', savepdf);
    						if(downloadp['mergepdf']){
	                $.ajax({
	                    url: "<?php echo WWW_BASE; ?>pdfmerge/api/pdf-merge",
	                    data: data,
	                    processData: false,
	                    type: 'POST',
	                    contentType: false,
	                    success: function ( data ) {
	                    	downloadAll(downloadp['mergepdf'],downloadp['mergefilename']);
	                    }
									});
	              }else{
	              		//downloadAll(downloadp['mergepdf'],downloadp['mergefilename']);
	              		downloadAll(downloads[type],downloads['filename']);
	              }
            });       

           function downloadAll(urls, filenames) {
                var link = document.createElement('a');
                link.style.display = 'none';
                document.body.appendChild(link);
                for (var i = 0; i < urls.length; i++) {
                  var url=urls[i];
                  var filename =filenames[i]; // url.substring(url.lastIndexOf('/')+1);
                  link.setAttribute('download', filename);
                  link.setAttribute('href', urls[i]);
                  link.click();
                }
                document.body.removeChild(link);
           } 
	//$('a[rel*=facebox]').facebox();
	jQuery('.facebox').remove();
	jQuery(document).on("click",".testreport",function() {
		var testreportId = jQuery(this).attr("testreportId");
		jQuery("#reportContent").load("<?php echo WWW_BASE; ?>admin/unityreports/view/"+testreportId+ "?" + new Date().getTime()+ new Date().getMilliseconds(), function(result) {
			jQuery("#reportView").modal("show");
			$('.customFacebox').remove();
		});
	});
	jQuery(document).on("click",".loaderAjax",function() {
		$('body').append('<div class="customFacebox" id="facebox" style="top: 70.8px; left: 475.5px;"><div class="popup popup56"><div class="content" style="padding: 45px"><div class="loading"><p style="color:#00aaff;"><b>Processing........Please do not click anywhere on the page until the process is complete.</b></p><img src="'+ajax_url+'img/ajaxloader.gif"></div> </div></div></div>');

	});
	//window.onload = function(){ $('.customFacebox').remove(); }
	jQuery(document).on("click",".Pointdata",function() {
		var PointdataId = jQuery(this).attr("PointdataId");
		jQuery("#reportContent").load("<?php echo WWW_BASE; ?>admin/unityreports/view/"+PointdataId+ "?" + new Date().getTime()+ new Date().getMilliseconds(), function(result) {
			jQuery("#reportView").modal("show");
			$('.customFacebox').remove();
		});
	});

	jQuery(document).on("click",".patient",function() {
		var patientId = jQuery(this).attr("patientId");

		jQuery("#patientContent").load("<?php echo WWW_BASE; ?>admin/patients/view/"+patientId+ "?" + new Date().getTime()+ new Date().getMilliseconds(), function(result) {

			jQuery("#patientView").modal("show");
			$('.customFacebox').remove();
		 });
	});

	jQuery(document).on("click",".staff",function() {
		var staffId = jQuery(this).attr("staffId");
		jQuery("#staffContent").load("<?php echo WWW_BASE; ?>admin/staff/staffView/"+staffId+ "?" + new Date().getTime()+ new Date().getMilliseconds(), function(result) {
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

<script type="text/javascript">
	function savedata(data,id) {
		var value=0;
		if(data.checked == true){
			value=1;
		}else{
			value=0;
		}
		$.ajax({
          url: "<?php echo WWW_BASE; ?>admin/patients/update_pointdata",
          type: 'POST',
          data: {"id": id,"value": value},
          success: function(data){
          }
      });
	}
</script>
