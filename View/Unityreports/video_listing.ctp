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
	<button type="button" id="view_video_popup_close" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
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
							<label>Patinet Name : <?php echo (ucfirst(@$patient['Patient']['first_name']).' '.ucfirst(@$patient['Patient']['last_name'])) ; ?></label>
						  <table id="datatable_report1" class="table table-striped table-bordered">
							<thead>
							  <tr>
								<th style="width:34px;">S.N.</th>
								<th> Name </th>
								<th> Viewed </th>
								<th with="140px;">To View</th>
							  </tr>
							</thead>
							<tbody>
							<?php if(!empty($datas_new)) {foreach($datas_new as $key=>$data){ ?>
							  
							<tr >
								<td data-order="<?php echo $data['id']; ?>"><?php echo $key+1; ?></td>
								<td><?php echo $data['name']; ?>  </td>
								<td>
									<div style="display: inline-flex;">
									<input type="checkbox" id="video_id_<?php echo $data['id']?>" disabled <?php echo ($data['viewes']==1)?'checked':''  ?> style="width:25px; height: 25px;" >&nbsp;
									<div style="padding-top: 7px; font-size: 14px;"><span id="video_viewed_id_<?php echo $data['id']?>"><?php

										if($data['video_status'] ==2){
									 		echo $data['viewed_date'];
									 	}else if($data['video_status']!=''){
									 		echo "Viewed ".$data['video_len_sec'].'/'.$data['video_watched_sec'].' Sec';
									 	}
									  ?><span></div>
									</div>
								</td>
								 <td>
								 	<div style="display: inline-flex;"><input type="checkbox" name="video_play_request[]" class="video_request_for_view" data-vide_id="<?php echo $data['id']; ?>" style="width:25px; height: 25px;" onchange="setVideo(this)" >&nbsp;
									<div style="padding-top: 7px; font-size: 14px;" class="video_cheked_order" id="video_cheked_order_<?php echo $data['id']?>"></div>
									</div>
								 	</td>
							      
								</td>
							</tr>
							<?php } ?>
							  <tr>
							  	<td colspan="4" class="mmds-box">
							  		<button class="plat-vide-buttons mmd-dash-btn md-btn-gry" id="video_play" style="width:24%; margin-left:1% ;" >Play</button>
							  		<button class="plat-vide-buttons mmd-dash-btn md-btn-desabley" id="video_pouse" style="width:24%;">Pouse</button>
							  		<button class="plat-vide-buttons mmd-dash-btn md-btn-desabley" id="video_resume" style="width:24%;" >Resume</button>
							  		<button class="plat-vide-buttons mmd-dash-btn md-btn-desabley" id="video_stop" style="width:24%;" >Stop</button>
							  	</td>
							  </tr>
						<?php	}else{echo "<tr><td colspan='4' style='text-align:center;'>No record found.</td></tr>";} ?>
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
	var videos = [];
  $( function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'dd-mm-yy',changeMonth: true,
  changeYear: true, yearRange: "-100:+0",maxDate: new Date()});
  } );
  function videoOrderView(value, index, array) {
  	var order_id = '#video_cheked_order_'+value;
  	var message = 'Video View Order '+(index+1);
  	$(order_id).html(message) 
}

  function setVideo(obj) {
  	var video_id = $(obj).data('vide_id');
		if ($(obj).is(":checked")) {
			videos.push(video_id);
		} else {
			var index = videos.indexOf(video_id);
			if (index > -1) {
			  videos.splice(index, 1); 
			}
		}
		$('.video_cheked_order').html('');
		videos.forEach(videoOrderView);
	}
  jQuery(document).ready(function () {

  	$('.select_video_for_view').on('change', function () {
  		
  		changeButton(1);
  		saveData(1);
  });


  $('#video_play').on('click', function () {
  		if($(this).hasClass("md-btn-gry") && videos.length > 0){
  			
  			$('#view_video_popup_close').attr("data-dismiss","a");  
	  		changeButton(1);
	  		saveData(1);
	  	}
  });
  $('#video_pouse').on('click', function () {
  		if($(this).hasClass("md-btn-gry")){
	  		changeButton(2);
	  		saveData(2);
	  	}
  });
  $('#video_resume').on('click', function () {
  		if($(this).hasClass("md-btn-gry")){
	  		changeButton(3);
	  		saveData(3);
	  	}
  });
  $('#video_stop').on('click', function () {
  		if($(this).hasClass("md-btn-gry")){
  			$('#view_video_popup_close').attr("data-dismiss","modal");  
	  		changeButton(4);
	  		saveData(4);
	  		$('.video_request_for_view').prop("checked", false);
	  		$('.video_cheked_order').html('');
	  		videos=[];
	  	}
  });
}); 
  function saveData(type) { 
  	var office_id= '<?php echo $office_ids ?>'; 
  	var patient_id= '<?php echo $patient_id ?>'; 
  	$.ajax({
				url: "<?php echo WWW_BASE; ?>admin/patients/play_video/3554",
				type: 'POST',

				data: {"type": type, "video": JSON.stringify(videos),"device_id": deviceId, "office_id": office_id, "patient_id":patient_id},
				success: function (data) {

				}
			});
  }
  function changeButton(type) {
  	$('.plat-vide-buttons').removeClass('md-btn-desabley');
  	$('.plat-vide-buttons').removeClass('md-btn-gry');
  	if(type==1){
  		$("#video_pouse").addClass('md-btn-gry');
  		$("#video_stop").addClass('md-btn-gry');
  		$("#video_resume").addClass('md-btn-desabley');
  	}else if(type==2){
  		$("#video_resume").addClass('md-btn-gry');
  		$("#video_stop").addClass('md-btn-gry');
  		$("#video_play").addClass('md-btn-desabley');
  	}else if(type==3){
  		$("#video_pouse").addClass('md-btn-gry');
  		$("#video_stop").addClass('md-btn-gry');
  		$("#video_play").addClass('md-btn-desabley');
  	}else if(type==4){
  		$("#video_play").addClass('md-btn-gry');
  		$("#video_pouse").addClass('md-btn-desabley');
  		$("#video_resume").addClass('md-btn-desabley');
  		$("#video_stop").addClass('md-btn-desabley');
  	}
  }
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
