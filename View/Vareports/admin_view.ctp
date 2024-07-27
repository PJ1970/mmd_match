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
	<h4 class="modal-title" id="myModalLabel">VA Test Report Details</h4>
</div>
<div class="modal-body">
	 <?php //pr($data); ?>
		<div class="form-group  col-md-12">
			<label for="recipient-name" class=" col-md-4 form-control-label">Date Of Test:</label>
			<span  class=" col-md-4"><?php  echo date('d F Y',strtotime($data['VaData']['created']));?></span>
		</div>		
	 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Eye Value:</label>
			<span  class=" col-md-4"><?php echo (!empty($data['VaData']['eye_select']))?'OD' : 'OS';?></span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Patient Name:</label>
			<span  class=" col-md-4"><?php echo$data['VaData']['patient_name'];?></span>
		</div>
		 

		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">file:</label>
			<?php if(!empty($data['VaData']['pdf'])){ ?>
			<span  class=" col-md-4"><a href="<?php echo $this->Html->url(['controller'=>'vareports','action'=>'exportPdf',$data['VaData']['pdf']]); ?>" target="_blank"><button type="button" class="btn btn-primary" title="PDF Report Link">PDF Report</button></a></span>
			<?php }else{ ?>
			<span  class=" col-md-4"><?php echo 'N/A';?></span>
			<?php  } ?>
		</div> 
		
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Staff User:</label>
			<span  class=" col-md-4"><?php echo @$user['User']['first_name'].' '.@$user['User']['last_name'];?></span>
		</div> 
		<!--div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Color:</label>
			<span  class=" col-md-4"><?php //echo $data['VaData']['color'];?></span>
		</div> 
			<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">BackgroundColor:</label>
			<span  class=" col-md-4"><?php //echo $data['VaData']['backgroundcolor'];?></span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Stmsize:</label>
			<span  class=" col-md-4"><?php //echo $data['VaData']['stmsize'];?></span-->
		</div> 
		
		<?php  
		// pr($VaPointdata);die;
		if(!empty($VaPointdata)){
		 ?>
	 <div class="form-group va_report_div  col-md-12" style="">
	 <div class="table_va_cont">
		<table cellpadding="0" cellspacing="0" class="table table-bordered table_va">
		<thead>
		<tr>
			<th width="6.25%">VA1</th>
			<th width="6.25%">VA2</th>
			<th width="6.25%">VA3</th>
			<th width="6.25%">VA4</th>
			<th width="6.25%">VA5</th>
			<th width="6.25%">VA6</th>
			<th width="6.25%">VA7</th>
			<th width="6.25%">VA8</th>
			<th width="6.25%">VA9</th>
			<th width="6.25%">VA10</th>
			<th width="6.25%">VA11</th>
			<th width="6.25%">VA12</th>
			<th width="6.25%">VA13</th>
			<th width="6.25%">VA14</th>
			<th width="6.25%">VA15</th>
			<th width="6.25%">VA16</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach($VaPointdata as $pdata){ 
		//pr($pdata);die;
		?>
			<tr>
				<td><?php echo $pdata['VaPointdata']['VA1']; ?></td>
				<td><?php echo $pdata['VaPointdata']['VA2']; ?></td>
				<td><?php echo $pdata['VaPointdata']['VA3']; ?></td>
				<td><?php echo $pdata['VaPointdata']['VA4']; ?></td>
				<td><?php echo $pdata['VaPointdata']['VA5']; ?></td>
				<td><?php echo $pdata['VaPointdata']['VA6']; ?></td>
				<td><?php echo $pdata['VaPointdata']['VA7']; ?></td>
				<td><?php echo $pdata['VaPointdata']['VA8']; ?></td>
				<td><?php echo $pdata['VaPointdata']['VA9']; ?></td>
				<td><?php echo $pdata['VaPointdata']['VA10']; ?></td>
				<td><?php echo $pdata['VaPointdata']['VA11']; ?></td>
				<td><?php echo $pdata['VaPointdata']['VA12']; ?></td>
				<td><?php echo $pdata['VaPointdata']['VA13']; ?></td>
				<td><?php echo $pdata['VaPointdata']['VA14']; ?></td>
				<td><?php echo $pdata['VaPointdata']['VA15']; ?></td>
				<td><?php echo $pdata['VaPointdata']['VA16']; ?></td>
			</tr>
			<?php } ?>
		</tbody>
		</table>
		</div>
		
		<?php /* 
		 <div class="form-group  col-md-12" style="  border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA1:</label>
		<span class="col-md-8" ><?php echo $pdata['VA1']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class="  col-md-4 form-control-label">VA2:</label>
		<span class="col-md-8"><?php echo $pdata['VA2']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA3:</label>
		<span class="col-md-8"><?php echo $pdata['VA3']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA4:</label>
		<span class="col-md-8"><?php echo $pdata['VA4']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA5:</label>
		<span class="col-md-8"><?php echo $pdata['VA5']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA6:</label>
		<span class="col-md-8"><?php echo $pdata['VA6']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4  form-control-label">VA7:</label>
		<span class="col-md-8"><?php echo $pdata['VA7']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA8:</label>
		<span class="col-md-8"><?php echo $pdata['VA8']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA9:</label>
		<span class="col-md-8"><?php echo $pdata['VA9']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA10:</label>
			<span class="col-md-8"><?php echo $pdata['VA10']; ?></span>
			</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA11:</label>
		<span class="col-md-8"><?php echo $pdata['VA11']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA12:</label>
		<span class="col-md-8"><?php echo $pdata['VA12']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA13:</label>
		<span class="col-md-8"><?php echo $pdata['VA13']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4  form-control-label">VA14:</label>
		<span class="col-md-8"><?php echo $pdata['VA14']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA15:</label>
		<span class="col-md-8"><?php echo $pdata['VA15']; ?></span>
		</div> <div class="form-group  col-md-12" style="border-bottom: 1px solid black;">
		<label for="recipient-name" class=" col-md-4 form-control-label">VA16:</label>
		<span class="col-md-8"><?php echo $pdata['VA16']; ?></span>
		</div> 
		 */ ?>
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
                           