<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel">Practice Details</h4>
</div>
<div class="modal-body">
	 
		<div class="form-group  col-md-12">
			<label for="recipient-name" class=" col-md-4 form-control-label">Name:</label>
			<span  class=" col-md-4"><?php echo $data['Practices']['name']; ?></span>
		</div>
		<div class="form-group  col-md-12">
			<label for="recipient-name" class=" col-md-4 form-control-label">Phone:</label>
			<span  class=" col-md-4"><?php echo $data['Practices']['phone']; ?></span>
		</div>		
		<div class="form-group  col-md-12">
			<label for="recipient-name" class="col-md-4  form-control-label">Address:</label>
			<span  class=" col-md-4"><?php echo $data['Practices']['address']; ?></span>
		</div>
</div> 
 <div class="modal-footer" style="border-top:none">
	<!-- <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button> -->
</div>
                           