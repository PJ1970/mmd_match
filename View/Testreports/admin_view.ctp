<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel">Camera Test Report Details</h4>
</div>
<div class="modal-body">
	 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class=" col-md-4 form-control-label">Test Name:</label>
			<span  class=" col-md-4"><?php echo $data['Test']['name']; ?></span>
		</div>
		<div class="form-group  col-md-12">
			<label for="recipient-name" class=" col-md-4 form-control-label">Date Of Test:</label>
			<span  class=" col-md-4"><?php  echo date('d F Y',strtotime($data['Testreport']['created']));?></span>
		</div>		
		<!--<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Practice:</label>
			<span  class=" col-md-4"><?php echo $data['Practices']['name']; ?></span>
		</div> -->
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Office Name:</label>
			<span  class=" col-md-4"><?php echo @$office['Office']['name'];?></span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Staff User:</label>
			<span  class=" col-md-4"><?php echo $data['User']['first_name'].' '.$data['User']['last_name'];?></span>
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Patient Name:</label>
			<span  class=" col-md-4"><?php echo $data['Patient']['first_name'].' '.$data['Patient']['last_name'];?></span>
		</div> 
		<div class="form-group  col-md-12">
		<?php 
		$eyeleft = 1;
		$eyeright =1;
		//echo "<pre>";print_r($data);
		foreach($data['File'] as $key=>$val){
			
				if($val['eye'] == 0 && $val['eye'] != null)
				{
					if($eyeleft == 1)
						{ ?>
						
						<label for="recipient-name" class="col-md-4  form-control-label">Retinal Camera:(OS)	</label>	
<?php 					} ?>
						<span  class=" col-md-4">
			             <a href="<?php echo WWW_BASE.$val['file_path']; ?>" target="_blank" <?php echo $val['eye']; ?> class="pic_name">
						 <?php //echo $nme = substr($val['file_path'], strrpos($val['file_path'], '/') + 1); ?>
						 <?php echo $nme = $data['Patient']['first_name'].' '.$data['Patient']['last_name'].'_'.date('Y-m-d',strtotime($val['created_at'])).'_OS'; ?>
						 </a>
					   </span>
					    
<?php 					$eyeleft++;
				}
				

		
		}
			?>
</div><div class="form-group  col-md-12">
<?php 
		foreach($data['File'] as $key=>$val){
			
				if($val['eye'] == 1)
				{
					if($eyeright == 1)
						{ ?>
						
						<label for="recipient-name" class="col-md-4  form-control-label">Retinal Camera:(OD)	</label>	
<?php 					} ?>
						<span  class=" col-md-4">
			            <a href="<?php echo WWW_BASE.$val['file_path']; ?>" target="_blank" <?php echo $val['eye']; ?> class="pic_name">
						<?php //echo $nme = substr($val['file_path'], strrpos($val['file_path'], '/') + 1); ?>
						<?php echo $nme = $data['Patient']['first_name'].' '.$data['Patient']['last_name'].'_'.date('Y-m-d',strtotime($val['created_at'])).'_OD'; ?>
						</a>
					</span>
					    
<?php 					$eyeright++;
				}
		
		}
			?>
</div>

			<?php  
			if($data['Testreport']['test_id'] >= 2 ){ ?>
			<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">PDF:</label>
			<span  class=" col-md-4"><a type="button" title="Pdf" target="_blank" class="testpdf" data="FileName.pdf" href="<?php echo WWW_BASE.'uploads/pdf/'.$data['Testreport']['pdf']; ?>"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></span>
			<?php } ?>
				
		</div> 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">
			
			
			</label>
			
		</div> 
		
		
		
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Results:</label>
			<span  class=" col-md-4"><?php echo $data['Testreport']['result']; ?></span>
		</div> 
</div> 
<div class="modal-footer" style="border-top:none">
	<!--<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>-->
</div>
                           