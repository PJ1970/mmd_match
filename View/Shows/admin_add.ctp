<?php echo $this->Form->create('Show', array('novalidate' => true, 'url'=>array('controller'=>'shows','action'=>'add')));
?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="page-title change_password_title">
			Add Show
		</td>
	</tr>
	<tr><td height="10px"> </td></tr>
	<tr>
		<td align="center">
			<table cellpadding="0" cellspacing="0" border="0" class="change-password form_section">
				<h2><?php //echo $this->Session->flash();?></h2>
				<tr>
					<td>Show Name<span class="required">*</span>: </td>
					<td>
						<?php echo $this->Form->input('name',array('type'=>'text', 'class' => 'form-control','label'=>false)); ?>
						<div class="clr10"></div>
					</td>
				</tr>
				<tr>
					<td>Show Description<span class="required">*</span>: </td>
					<td>
						<?php echo $this->Form->input('description',array('type'=>'textarea', 'class' => 'form-control','label'=>false)); ?>
						<div class="clr10"></div>
					</td>
				</tr>
				<tr>
					<td>Start Date<span class="required">*</span>: </td>
					<td>
						<?php echo $this->Form->input('start_date',array('type'=>'text', 'class' => 'form-control','label'=>false,'id'=>'start_date')); ?>
						<div class="clr10"></div>
					</td>
				</tr>
				<tr>
					<td>End Date<span class="required">*</span>: </td>
					<td>
						<?php echo $this->Form->input('end_date',array('type'=>'text', 'class' => 'form-control','label'=>false,'id'=>'end_date')); ?>
						<div class="clr10"></div>
					</td>
				</tr>
				<tr>
				   <td></td>
				   <td><button  class="btn-common" type="submit"  >Save</button>
				   <?php //echo $this->Form->end(); ?></td>
				</tr>
			</table>
	   </td>
	</tr>
</table>
<?php echo $this->Form->end(); ?>	

<script>
$(document).ready(function(){
    
    $("#start_date").datepicker({
       numberOfMonths: 1,
        onSelect: function(selected) {
          $("#end_date").datepicker("option","minDate", selected)
        },dateFormat: 'yy-mm-dd'
   });
    $("#end_date").datepicker({
        numberOfMonths: 1,
        onSelect: function(selected) {
	           $("#start_date").datepicker("option","maxDate", selected)
        },dateFormat: 'yy-mm-dd'
    }); 
});
</script>	
