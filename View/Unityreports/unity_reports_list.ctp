<?php $Admin = $this->Session->read('Auth.Admin'); ?>
<?php $thresshold_test=["Central_10_2", "Central_24_1", "Central_24_2", "Central_30_1", "Central_30_2","Central_24_2C"] ?>
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
         
      </div>
      <div class="modal-header" style="border:none;">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
</div>
      <div class="page-content-wrapper ">
       <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-primary">
                <div class="panel-body">

				<?php if(isset($credit_expire)){
				 
				 }else{

				 
					if(@$check_payable['Office']['payable'] =='no' && @$check_payable['Office']['restrict'] =='restrict'){
					?>
						<h2 style="color:red;text-align:center;">You don't have permission to see this. Please contact support: <br/>Email: support@micromedinc.com  <br/>Phone : 818-222-3310</h2>
					<?php
					}else{ ?> 
						<?php $Admin = $this->Session->read('Auth.Admin');?> 
					 
					  <div class="row">
						<div class="table-responsive col-md-12 col-sm-12 col-xs-12">
						  <table id="datatable_report1" class="table table-striped table-bordered">
							<thead>
							  <tr>
								<th style="width:34px;">S.No</th>
								<th> Date </th>
								<th> <?php
								echo ($Admin['user_type'] == "Admin")? 'Patient' : 'Patient Name'; ?></th>
								<th> Patient Id </th>
								<th>DOB <br>(DD-MM-YYYY)</th>

								<th> Test name</th>
								<?php if($reportType!='VS'){ ?>
								<th> OD/OS </th>
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
								<td><?php echo ($Admin['user_type'] == "Admin")?  $patient_new_id:$data['Pointdata']['patient_name'] ?></td>
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

								echo    $data['Pointdata']['staff_name'] ?></td>

								<?php if($Admin['user_type'] == "Admin"){ ?>

									<td><?php echo (!empty($data['Pointdata']['patient_dob']))?$data['Pointdata']['patient_age_years']:''; ?></td>
									<td><?php echo $data['Pointdata']['version']; ?></td>
									<td><?php echo $data['Pointdata']['diagnosys']; ?></td>
								<?php } ?>
								<td><?php echo $data['Pointdata']['source']; ?></td>
								<td class="action_sec">
								 
									<?php if(!empty($data['Pointdata']['file'])){ ?>
                                            &nbsp;&nbsp;&nbsp;
                                    <?php 
                                    	$related_id=(!empty($download[$ptdata_id]['tr_id']))? $download[$ptdata_id]['tr_id'] : 'tr-none';
                                        $related_ids=(!empty($downloads[$ptdata_id]['tr_id']))? $downloads[$ptdata_id]['tr_id'] : 'tr-none';
                                    ?>
                      
										  
<a title="View pdf report" target="_blank" href="<?php echo WWW_BASE.'pointData/'.$data['Pointdata']['file']; ?>"><i class="fa fa-file-pdf-o" aria-hidden="true" ></i></a>
		 
                      
										<?php  }?>
									 
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
	   
 

<script>
  $( function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,
  changeYear: true, yearRange: "-100:+0",maxDate: new Date()});
  } );
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
