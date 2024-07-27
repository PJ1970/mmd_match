<?php #pr($data);die; ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel">Start Test</h4>
</div> 
<div class="modal-body">
 
	 
	 <div class="form-group col-md-12">
			<!--<label for="recipient-name" class="col-md-4 form-control-label" style="margin-top: 10px;">Test Name:</label>-->
			<span  class=" col-md-12" style="text-align:center;display: inline-grid;">
		
			<?php
				foreach($officereports as $key => $value){
			        echo "<a style='margin: 5px;cursor: pointer;height: 26px;padding-top: 3px;padding-bottom: 3px;' class='btn btn-info' href='".WWW_BASE."admin/patients/".$test_name[$value['Officereport']['office_report']]['path']."/".$lastId."' title='Start VF Test' >".$test_name[$value['Officereport']['office_report']]['name']."</a>";
				    
				}
			?>
		 		</span>
		</div>
	 <div class="form-group col-md-12" style="text-align:center;">
	     <button  class=" btn btn-primary" data-dismiss="modal" style="min-width:150px;" >Skip</button>
	</div>
</div>
 
<div class="modal-footer" style="border-top:none">
	<!--<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>-->
</div>
                           