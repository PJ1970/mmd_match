<?php $session_check_parent = $this->Session->read('Auth.Admin.created_by');
#pr($session_check_parent);die;
 ?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel">VF Test Report Details</h4>
</div>
<div class="modal-body">
	 <?php //pr($data); ?>
		<div class="form-group  col-md-12">
			<label for="recipient-name" class=" col-md-4 form-control-label">Date Of Test:</label>
			<span  class=" col-md-4"><?php  echo date('d F Y',strtotime($data['Pointdata']['created']));?></span>
			
			
		</div>		
	
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Patient Name:</label>
			<span  class=" col-md-4"><?php echo $data['Patient']['first_name'].' '.$data['Patient']['last_name'];?></span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Patient Id:</label>
			<span  class=" col-md-4"><?php echo (!empty($data['Patient']['id_number']))?$data['Patient']['id_number']:'0';?></span>
		</div>   
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Test name:</label>
			<span  class=" col-md-4"><?php echo (!empty($data['Pointdata']['test_name']))?$data['Pointdata']['test_name'] : 'N/A';?></span>
		</div> 
		
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Eye Value:</label>
			<span  class=" col-md-4"><?php echo $od_os =  (!empty($data['Pointdata']['eye_select']))?'OD' : 'OS';?></span>
		</div>  
		
		
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">MeanDev:</label>
			<span  class=" col-md-4"><?php echo (!empty($data['Pointdata']['mean_dev']))?number_format($data['Pointdata']['mean_dev'],2) : 'N/A';?></span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">PatternSTD:</label>
			<span  class=" col-md-4"><?php echo (!empty($data['Pointdata']['psd_hfa']))?$data['Pointdata']['psd_hfa'] : 'N/A';?></span>
		</div>
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">VissionLoss:</label>
			<span  class=" col-md-4"><?php echo (!empty($data['Pointdata']['vission_loss']))?$data['Pointdata']['vission_loss'] : 'N/A';?></span>
		</div>
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">GHT:</label>
			<span  class=" col-md-4"><?php echo (!empty($data['Pointdata']['ght']))?$data['Pointdata']['ght'] : 'N/A';?></span>
		</div>
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">file:</label>
			<span  class=" col-md-4" style="display: inline-flex;">
				<?php 
                    $related_id=(!empty($download[$data['Pointdata']['id']]['tr_id']))? $download[$data['Pointdata']['id']]['tr_id'] : 'tr-none'; 
                    
                ?>
			<?php if(!empty($data['Pointdata']['file'])){ ?>
				<a href="javascript:;" data-type="pdf" data-related='<?php echo $related_id; ?>' data-downloads='<?php echo json_encode($download[$data['Pointdata']['id']]); ?>' class="vbs-popover"><button type="button" class="btn btn-primary" title="PDF Report Link">PDF Report</button></a>
				
				<!--a href="<?php //echo $this->Html->url(['controller'=>'unityreports','action'=>'exportPdf',$data['Pointdata']['file']]); ?>" target="_blank"><button type="button" class="btn btn-primary" title="PDF Report Link">PDF Report</button></a-->
				&nbsp;&nbsp;&nbsp;
				<!--<a href="<?php //echo $this->Html->url(['controller'=>'unityreports','action'=>'exportImage',$data['Pointdata']['file']]); ?>" target="_blank"><button type="button" class="btn btn-primary" title="Image Report Link">Image Report</button></a>
				&nbsp;&nbsp;&nbsp; -->
				<a href="<?php echo $this->Html->url(['controller'=>'unityreports','action'=>'exportDicom',$data['Pointdata']['patient_id'], $data['Pointdata']['file']]); ?>" target="_blank"><button type="button" class="btn btn-primary" title="Dicom Report Link">Dicom Report</button></a>
			<?php } else{ echo 'N/A'; }?>
				
			</span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Staff User:</label>
			<span  class=" col-md-4"><?php echo $data['User']['first_name'].' '.$data['User']['last_name'];?></span>
		</div> 
		<!--<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Color:</label>
			<span  class=" col-md-4"><?php echo $data['Pointdata']['color'];?></span>
		</div> 
			<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">BackgroundColor:</label>
			<span  class=" col-md-4"><?php echo $data['Pointdata']['backgroundcolor'];?></span>
		</div> -->
		<div class="form-group  col-md-12">
			<!--<label for="recipient-name" class="col-md-4  form-control-label">Stmsize:</label>
			<span  class=" col-md-4"><?php echo $data['Pointdata']['stmsize'];?></span>-->
			<?php 
		 
			//$string = @explode(' ',@$data['Pointdata']['patient_name']);
			$patient_new_id = @substr(@$data['Patient']['first_name'],0,1).@substr(@$data['Patient']['last_name'],0,1).$data['Pointdata']['patient_id'].'_'.date('d M Y h:i:s a',strtotime($data['Pointdata']['created'])).'_'.$od_os;
			?>
			<span  class=" col-md-4"></span>
			<span  class=" col-md-4"></span>
			<span  class=" col-md-4">
			<?php if($session_check_parent==0){?>
				<a href="<?php echo $this->Html->url(['controller'=>'unityreports','action'=>'export',$data['Pointdata']['id'],'?'=>['url'=>$patient_new_id]]); ?>" class="btn btn-default" style="float: right;">Export</a>
			<?php } ?>
			</span>
		</div> 
		<?php if($session_check_parent==0){?>
			<div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
				<center><label for="recipient-name" class="col-md-2  form-control-label">X</label></center>
				<center><label for="recipient-name" class="col-md-2  form-control-label">Y</label></center>
				<center><label for="recipient-name" class="col-md-2  form-control-label">Intensity</label></center>
				<center><label for="recipient-name" class="col-md-2  form-control-label">Size</label></center>
				<center><label for="recipient-name" class="col-md-1  form-control-label">zPD</label></center>
				<center><label for="recipient-name" class="col-md-1  form-control-label">STD</label></center>
				<center><label for="recipient-name" class="col-md-2  form-control-label">Index</label></center>
			</div>
			<?php 
			if(!empty($data['VfPointdata'])){
			
			foreach ($data['VfPointdata'] as $pdata) {?>
			<div class="form-group  col-md-12">
			<span  class=" col-md-2"><center><?php echo $pdata['x']; ?></center></span>
			<span  class=" col-md-2"><center><?php  echo $pdata['y']; ?></center></span>
			<span  class=" col-md-2"><center><?php  echo $pdata['intensity']; ?></center></span>
			<?php if($session_check_parent==0){?>
				<span  class=" col-md-2"><center><?php  echo $pdata['size']; ?></center></span>
			<?php } ?>
			<span  class=" col-md-1"><center><?php  echo $pdata['zPD']; ?></center></span>
			<span  class=" col-md-1"><center><?php  echo $pdata['STD']; ?></center></span>
			<span  class=" col-md-2"><center><?php  echo $pdata['index']; ?></center></span>
			</div>
			<?php }}else{ ?>
			<div class="form-group  col-md-12" style="text:align:center;">
				<strong  class=" col-md-4 col-md-offset-4"> No record found.</strong>
			</div>
		<?php } ?>
	<?php } ?>
</div> 
<div class="modal-footer" style="border-top:none">
	<!--<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>-->
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
<script>
 $('body').append('<div class="facebox" id="facebox" style="top: 70.8px; left: 475.5px;"><div class="popup popup56"><div class="content" style="padding: 45px"><div class="loading"><p style="color:#00aaff;"><b>Processing........Please do not click anywhere on the page until the process is complete.</b></p><img src="'+ajax_url+'img/ajaxloader.gif"></div> </div></div></div>');
jQuery(document).ready(function(){
    
       
       
       
       
     /*jQuery('.vbs-popover').popover({  
                title:'OS/OD Reports Download',  
                content: vbs_fetchData,  
                html:true,  
                placement:'left'  
           });*/  
        
            /*jQuery('.vbs-popover').click(vbs_fetchData);
           function vbs_fetchData(){  
             
                var element = jQuery(this);  
                var id = element.attr("id");
                var type=element.attr("data-type");
                $.ajax({  
                     url:element.data("href"),  
                     //method:"POST",  
                     async:false,  
                     //data:{id:id},  
                     success:function(msg){  
                         console.log(msg);
                        var download=msg.download; 
                        
                        if(type == 'dicom'){
                            console.log("aaaaaaaaaaaaa");
                            console.log(download.dicom);
                         downloadAll(download.dicom);
                        }else{
                         downloadAll(download.pdf);
                        }
                     }  
                });  
           }  */
        
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
            
            jQuery('.vbs-popover').click( function(){
                var downloads=jQuery.parseJSON(jQuery(this).attr("data-downloads"));
                var type=jQuery(this).attr("data-type");
                //return false;
                console.log(type);
                //console.log(downloads);
                console.log(downloads[type]);
                downloadAll(downloads[type], downloads['filename']); 
            });
            
    
           function downloadAll(urls, filenames) {
                var link = document.createElement('a');

                link.style.display = 'none';

                document.body.appendChild(link);

                for (var i = 0; i < urls.length; i++) {
                  var url=urls[i];  
                  var filename =filenames[i]; //url.substring(url.lastIndexOf('/')+1);  
                  link.setAttribute('download', filename);
                  link.setAttribute('href', urls[i]);
                  link.click();
                }
                document.body.removeChild(link);
           }    
    
               /* jQuery('*[data-poload]').hover(function() {
                    var e = jQuery(this);
                    e.off('hover');
                    jQuery.get(e.data('poload'), function(d) {
                        e.popover({
                            html : true,
                            placement:'left',  
                            content: d
                        }).popover('toggle');
                    });
            });*/
    
    
    
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
                           