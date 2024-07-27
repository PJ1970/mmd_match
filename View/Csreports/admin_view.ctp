<style>
.modal-dialog{
	width: 850px;
}
.va_report_div td{
	text-align:right;
}

.table_va{width:100%;}
.table_va th, .table_va td{text-align:center; vertical-align:middle !important;}
.table_va_cont{overflow:auto;}
</style>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel">Contrast Sensitivity Report Details</h4>
</div>
<div class="modal-body">
	 <?php //pr($data); ?>
		<div class="form-group  col-md-12">
			<label for="recipient-name" class=" col-md-4 form-control-label">Date Of Test:</label>
			<span  class=" col-md-4"><?php  echo date('d F Y',strtotime($data['CsData']['created']));?></span>
		</div>		
	
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Patient Name:</label>
			<span  class=" col-md-4"><?php echo $data['CsData']['patient_name'];?></span>
		</div>
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Eye Value:</label>
			<span  class=" col-md-4"><?php echo (!empty($data['CsData']['eye_select']))?'OD' : 'OS';?></span>
		</div>  
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">file:</label>
			<?php if(!empty($data['CsData']['pdf'])){ ?>
			<span  class=" col-md-4"><a href="<?php echo WWW_BASE.'pointData/'.$data['CsData']['pdf']; ?>" target="_blank"><button type="button" class="btn btn-primary" title="PDF Report Link">PDF Report</button></a></span>
			<!--span  class=" col-md-4"><a href="<?php //echo $this->Html->url(['controller'=>'csreports','action'=>'exportPdf',$data['CsData']['pdf']]); ?>" target="_blank"><button type="button" class="btn btn-primary" title="PDF Report Link">PDF Report</button></a></span-->
			<?php }else{ ?>
			<span  class=" col-md-4"><?php echo 'N/A';?></span>
			<?php  } ?>
		</div> 
		
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Staff User:</label>
			<span  class=" col-md-4"><?php echo @$data['CsData']['staff_name'];?></span>
		</div> 
		<!--div class="form-group  col-md-12">
			<div  class="col-md-2" style="width: 13%"> 
				<button class="btn btn-default active data" data="AMP" >AMP Data</button>
			</div>
			<span  class=" col-md-2"> 
				<button class="btn btn-default data" data="CPD">CPD Data</button>
			</span>
		</div--> 
		
		<!--div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Color:</label>
			<span  class=" col-md-4"><?php echo $data['CsData']['color'];?></span>
		</div> 
			<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">BackgroundColor:</label>
			<span  class=" col-md-4"><?php echo $data['CsData']['backgroundcolor'];?></span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Stmsize:</label>
			<span  class=" col-md-4"><?php echo $data['CsData']['stmsize'];?></span-->
		</div> 
		
		<?php  
		 //pr($CsPointData);die;
		if(!empty($data)){
		 ?>
	 <div class="form-group va_report_div  col-md-12" style="" id="AMP">
	 <div class="table_va_cont">
		<table cellpadding="0" cellspacing="0" class="table table-bordered table_va" style="width: 58%;">
		<thead>
		<tr>
			<th style="text-align: right;padding-right: 18%;">CPD</th>
			<th style="text-align: right;padding-right: 18%;">AMP</th> 
		</tr>
		</thead>
		<tbody>
		<?php
		 
		foreach($data['CsPointdata'] as $pdata){ 
			
		?>
			<tr>
				<td style="text-align: right;padding-right: 18%;" ><?php  echo bcdiv($pdata['CPD1'], 1, 1);?></td> 
				<td style="text-align: right;padding-right: 18%;"><?php echo bcdiv($pdata['AMP1'], 1, 4); ?></td> 
			</tr>
			<tr>
				<td style="text-align: right;padding-right: 18%;" ><?php  echo bcdiv($pdata['CPD2'], 1, 1);?></td> 
				<td style="text-align: right;padding-right: 18%;"><?php echo bcdiv($pdata['AMP2'], 1, 4); ?></td> 
			</tr>
			<tr>
				<td style="text-align: right;padding-right: 18%;" ><?php  echo bcdiv($pdata['CPD3'], 1, 1);?></td> 
				<td style="text-align: right;padding-right: 18%;"><?php echo bcdiv($pdata['AMP3'], 1, 4); ?></td> 
			</tr>
			<tr>
				<td style="text-align: right;padding-right: 18%;" ><?php  echo bcdiv($pdata['CPD4'], 1, 1);?></td> 
				<td style="text-align: right;padding-right: 18%;"><?php echo bcdiv($pdata['AMP4'], 1, 4); ?></td> 
			</tr>
			<tr>
				<td style="text-align: right;padding-right: 18%;" ><?php  echo bcdiv($pdata['CPD5'], 1, 1);?></td> 
				<td style="text-align: right;padding-right: 18%;"><?php echo bcdiv($pdata['AMP5'], 1,4); ?></td> 
			</tr>
			<tr>
				<td style="text-align: right;padding-right: 18%;" ><?php  echo bcdiv($pdata['CPD6'], 1, 1);?></td> 
				<td style="text-align: right;padding-right: 18%;"><?php echo bcdiv($pdata['AMP6'], 1, 4); ?></td> 
			</tr>
			<tr>
				<td style="text-align: right;padding-right: 18%;" ><?php  echo bcdiv($pdata['CPD7'], 1, 1);?></td> 
				<td style="text-align: right;padding-right: 18%;"><?php echo bcdiv($pdata['AMP7'], 1, 4); ?></td> 
			</tr>
			<tr>
				<td style="text-align: right;padding-right: 18%;" ><?php  echo bcdiv($pdata['CPD8'], 1, 1);?></td> 
				<td style="text-align: right;padding-right: 18%;"><?php echo bcdiv($pdata['AMP8'], 1, 4); ?></td> 
			</tr>
			<tr>
				<td style="text-align: right;padding-right: 18%;" ><?php  echo bcdiv($pdata['CPD9'], 1, 1);?></td> 
				<td style="text-align: right;padding-right: 18%;"><?php echo bcdiv($pdata['AMP9'], 1, 4); ?></td> 
			</tr>
			<tr>
				<td style="text-align: right;padding-right: 18%;" ><?php  echo bcdiv($pdata['CPD10'], 1, 1);?></td> 
				<td style="text-align: right;padding-right: 18%;"><?php echo bcdiv($pdata['AMP10'], 1, 4); ?></td> 
			</tr>
			<?php } ?>
		</tbody>
		</table>
		</div>
		</div>
		
		 
	
		
	 
		<?php  }else{ ?>
		<div class="form-group  col-md-12" style="text:align:center;">
			
			<strong  class=" col-md-4 col-md-offset-4"> No record found.</strong>
			 
		</div>

	<?php	}		?>

</div> 
<div class="modal-footer" style="border-top:none">
	<!--<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>-->
</div>
<script>
/* $(document).on('click','.data',function(){
	$('.data').removeClass('active');
	$(this).addClass('active');
	var data = $(this).attr('data');
	$('.va_report_div').hide();
	$('#'+data).show();
}); */
</script> 